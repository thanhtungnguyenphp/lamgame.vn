<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ForumPost;
use App\Models\ForumComment;
use Webkul\Customer\Models\CustomerProxy;

class UserProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function show($userId)
    {
        $customerModel = CustomerProxy::modelClass();
        $user = $customerModel::findOrFail($userId);
        
        // Get user's forum activity
        $posts = ForumPost::where('author_id', $userId)
            ->with(['category', 'comments'])
            ->withCount(['comments', 'votes'])
            ->latest()
            ->paginate(10, ['*'], 'posts_page');
            
        $comments = ForumComment::where('author_id', $userId)
            ->with(['post'])
            ->latest()
            ->paginate(10, ['*'], 'comments_page');
            
        // Calculate user statistics
        $stats = [
            'total_posts' => ForumPost::where('author_id', $userId)->count(),
            'total_comments' => ForumComment::where('author_id', $userId)->count(),
            'reputation' => $user->reputation ?? 0,
            'joined_date' => $user->created_at,
            'last_activity' => max(
                ForumPost::where('author_id', $userId)->latest()->value('created_at'),
                ForumComment::where('author_id', $userId)->latest()->value('created_at')
            )
        ];
        
        return view('forum.profile.show', compact('user', 'posts', 'comments', 'stats'));
    }
    
    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::guard('customer')->user();
        
        if (!$user) {
            return redirect()->route('customer.session.create');
        }
        
        return view('forum.profile.edit', compact('user'));
    }
    
    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::guard('customer')->user();
        
        if (!$user) {
            return redirect()->route('customer.session.create');
        }
        
        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ]);
        
        $updateData = [
            'bio' => $request->bio,
        ];
        
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_url) {
                Storage::delete($user->avatar_url);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $updateData['avatar_url'] = $avatarPath;
        }
        
        $user->update($updateData);
        
        return redirect()->route('forum.profile.show', $user->id)
            ->with('success', 'Profile updated successfully!');
    }
    
    /**
     * Get user's posts via AJAX
     */
    public function posts(Request $request, $userId)
    {
        $posts = ForumPost::where('author_id', $userId)
            ->with(['category', 'comments'])
            ->withCount(['comments', 'votes'])
            ->latest()
            ->paginate(10);
            
        if ($request->ajax()) {
            return response()->json([
                'posts' => $posts->items(),
                'pagination' => $posts->toArray()
            ]);
        }
        
        return $posts;
    }
    
    /**
     * Get user's comments via AJAX
     */
    public function comments(Request $request, $userId)
    {
        $comments = ForumComment::where('author_id', $userId)
            ->with(['post'])
            ->latest()
            ->paginate(10);
            
        if ($request->ajax()) {
            return response()->json([
                'comments' => $comments->items(),
                'pagination' => $comments->toArray()
            ]);
        }
        
        return $comments;
    }
}
