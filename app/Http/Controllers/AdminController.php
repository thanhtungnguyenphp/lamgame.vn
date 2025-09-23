<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumReport;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\User\Models\AdminProxy;
use Webkul\Admin\Http\Controllers\Controller;

class AdminController extends Controller
{

    /**
     * Display the admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_posts' => ForumPost::count(),
            'total_comments' => ForumComment::count(),
            'total_users' => CustomerProxy::modelClass()::count(),
            'pending_reports' => ForumReport::pending()->count(),
            'recent_posts' => ForumPost::with(['category'])
                ->latest()
                ->take(5)
                ->get(),
            'recent_reports' => ForumReport::with(['reporter', 'reportable'])
                ->pending()
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.forum.dashboard', compact('stats'));
    }

    /**
     * Display posts for moderation
     */
    public function posts(Request $request)
    {
        $query = ForumPost::with(['category', 'comments'])
            ->withCount('comments')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->paginate(20);
        
        return view('admin.forum.posts', compact('posts'));
    }

    /**
     * Update post status
     */
    public function updatePostStatus(Request $request, ForumPost $post)
    {
        $request->validate([
            'status' => 'required|in:published,hidden,locked'
        ]);

        $post->update([
            'status' => $request->status,
            'moderated_by' => Auth::guard('admin')->id(),
            'moderated_at' => now()
        ]);

        return response()->json([
            'message' => 'Post status updated successfully',
            'status' => $post->status
        ]);
    }

    /**
     * Display comments for moderation  
     */
    public function comments(Request $request)
    {
        $query = ForumComment::with(['post'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('content', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $comments = $query->paginate(20);
        
        return view('admin.forum.comments', compact('comments'));
    }

    /**
     * Update comment status
     */
    public function updateCommentStatus(Request $request, ForumComment $comment)
    {
        $request->validate([
            'status' => 'required|in:published,hidden'
        ]);

        $comment->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Comment status updated successfully',
            'status' => $comment->status
        ]);
    }

    /**
     * Display reports for review
     */
    public function reports(Request $request)
    {
        $query = ForumReport::with(['reporter', 'reviewer', 'reportable'])
            ->latest();

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } else {
                $query->where('status', $request->status);
            }
        }

        $reports = $query->paginate(20);
        
        return view('admin.forum.reports', compact('reports'));
    }

    /**
     * Review a report
     */
    public function reviewReport(Request $request, ForumReport $report)
    {
        $request->validate([
            'status' => 'required|in:reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $report->update([
            'status' => $request->status,
            'reviewed_by' => Auth::guard('admin')->id(),
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now()
        ]);

        return response()->json([
            'message' => 'Report reviewed successfully',
            'status' => $report->status
        ]);
    }

    /**
     * Display users for management
     */
    public function users(Request $request)
    {
        $customerModel = CustomerProxy::modelClass();
        $query = $customerModel::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(20);
        
        return view('admin.forum.users', compact('users'));
    }

    /**
     * Ban/unban a user
     */
    public function updateUserStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
            'banned_until' => 'nullable|date|after:now',
            'ban_reason' => 'nullable|string|max:500'
        ]);

        $customerModel = CustomerProxy::modelClass();
        $user = $customerModel::findOrFail($userId);

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->status === 'banned') {
            $updateData['banned_until'] = $request->banned_until;
            $updateData['ban_reason'] = $request->ban_reason;
            $updateData['banned_by'] = Auth::guard('admin')->id();
        } else {
            $updateData['banned_until'] = null;
            $updateData['ban_reason'] = null;
            $updateData['banned_by'] = null;
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'User status updated successfully',
            'status' => $user->status
        ]);
    }

    /**
     * Update user forum role
     */
    public function updateUserRole(Request $request, $userId)
    {
        $request->validate([
            'forum_role' => 'required|in:user,moderator'
        ]);

        $customerModel = CustomerProxy::modelClass();
        $user = $customerModel::findOrFail($userId);

        $user->update([
            'forum_role' => $request->forum_role
        ]);

        return response()->json([
            'message' => 'User role updated successfully',
            'role' => $user->forum_role
        ]);
    }
}
