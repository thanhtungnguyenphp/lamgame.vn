<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Requests\ForumPostRequest;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumTag;
use App\Models\ForumVote;
use App\Models\ForumReport;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    /**
     * Display the forum homepage with categories and latest posts.
     */
    public function index(Request $request)
    {
        // Get filters from request
        $category = $request->get('category');
        $sort = $request->get('sort', 'latest');
        $search = $request->get('search');
        $type = $request->get('type');

        // Get categories with post counts
        $categories = ForumCategory::active()
            ->ordered()
            ->withCount(['posts' => function ($query) {
                $query->where('status', 'published');
            }])
            ->get();

        // Build posts query
        $postsQuery = ForumPost::with(['category', 'tags'])
            ->published();

        // Apply filters
        if ($category) {
            $categoryModel = ForumCategory::where('slug', $category)->first();
            if ($categoryModel) {
                $postsQuery->where('category_id', $categoryModel->id);
            }
        }

        if ($type) {
            $postsQuery->ofType($type);
        }

        if ($search) {
            $postsQuery->search($search);
        }

        // Apply sorting
        switch ($sort) {
            case 'popular':
                $postsQuery->popular();
                break;
            case 'activity':
                $postsQuery->byActivity();
                break;
            case 'oldest':
                $postsQuery->orderBy('created_at', 'asc');
                break;
            case 'latest':
            default:
                $postsQuery->orderBy('created_at', 'desc');
                break;
        }

        // Add sticky posts to top
        $stickyPosts = ForumPost::with(['category', 'tags'])
            ->published()
            ->sticky()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get paginated posts
        $posts = $postsQuery->paginate(15);

        // Get popular tags
        $popularTags = ForumTag::popular()
            ->featured()
            ->take(10)
            ->get();

        // Stats
        $stats = [
            'total_posts' => ForumPost::published()->count(),
            'total_comments' => ForumComment::published()->count(),
            'total_members' => ForumPost::distinct('author_email')->count('author_email'),
            'categories_count' => ForumCategory::active()->count(),
        ];

        return view('lamgame.pages.forum.index', compact(
            'categories',
            'posts', 
            'stickyPosts',
            'popularTags',
            'stats',
            'category',
            'sort',
            'search',
            'type'
        ));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(Request $request)
    {
        $categories = ForumCategory::active()->ordered()->get();
        $tags = ForumTag::popular()->get();
        $selectedCategory = $request->get('category');
        $selectedType = $request->get('type', 'discussion');

        return view('lamgame.pages.forum.create', compact(
            'categories', 
            'tags', 
            'selectedCategory', 
            'selectedType'
        ));
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(ForumPostRequest $request)
    {

        // Create the post
        $post = ForumPost::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'category_id' => $request->category_id,
            'status' => 'published',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Handle tags
        if ($request->tags) {
            $tagNames = explode(',', $request->tags);
            $tagNames = array_map('trim', $tagNames);
            $tagNames = array_filter($tagNames);
            
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;
                
                $tag = ForumTag::firstOrCreate([
                    'slug' => $this->createUniqueSlug($tagName)
                ], [
                    'name' => $tagName,
                ]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bài viết đã được đăng thành công!');
    }

    /**
     * Display the specified post.
     */
    public function show(ForumPost $post, Request $request)
    {
        // Load relationships
        $post->load(['category', 'tags', 'rootComments.publishedReplies']);

        // Increment views if not the author
        if ($request->ip() !== $post->ip_address) {
            $post->incrementViews();
        }

        // Get related posts
        $relatedPosts = ForumPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get recent posts from same author
        $authorPosts = ForumPost::published()
            ->where('id', '!=', $post->id)
            ->where('author_email', $post->author_email)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('lamgame.pages.forum.show', compact(
            'post',
            'relatedPosts',
            'authorPosts'
        ));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(ForumPost $post)
    {
        $categories = ForumCategory::active()->ordered()->get();
        $popularTags = ForumTag::popular()->get();
        $postTags = $post->tags->pluck('name')->toArray();

        return view('lamgame.pages.forum.edit', compact(
            'post',
            'categories',
            'popularTags',
            'postTags'
        ));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(ForumPostRequest $request, ForumPost $post)
    {

        // Store edit history if content changed
        $editHistory = $post->edit_history ?: [];
        
        if ($post->title !== $request->title || $post->content !== $request->content) {
            $editHistory[] = [
                'date' => now()->toISOString(),
                'author' => $request->author_name,
                'reason' => $request->edit_reason,
                'changes' => [
                    'title_changed' => $post->title !== $request->title,
                    'content_changed' => $post->content !== $request->content,
                ]
            ];
        }

        // Update the post
        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'category_id' => $request->category_id,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'edit_history' => $editHistory,
        ]);

        // Handle tags
        if ($request->tags) {
            $tagNames = explode(',', $request->tags);
            $tagNames = array_map('trim', $tagNames);
            $tagNames = array_filter($tagNames);
            
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (empty($tagName)) continue;
                
                $tag = ForumTag::firstOrCreate([
                    'slug' => $this->createUniqueSlug($tagName)
                ], [
                    'name' => $tagName,
                ]);
                $tagIds[] = $tag->id;
            }
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(ForumPost $post)
    {
        $post->delete();

        return redirect()->route('forum.index')
            ->with('success', 'Bài viết đã được xóa thành công!');
    }

    /**
     * Store a new comment for a post.
     */
    public function storeComment(Request $request, ForumPost $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|min:10|max:2000',
            'author_name' => 'required|string|max:100',
            'author_email' => 'required|email|max:255',
            'parent_id' => 'nullable|exists:forum_comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create the comment
        ForumComment::create([
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'status' => 'published',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('forum.posts.show', $post->slug)
            ->with('success', 'Bình luận đã được đăng thành công!');
    }

    /**
     * Vote on a post or comment.
     */
    public function vote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voteable_type' => 'required|in:App\Models\ForumPost,App\Models\ForumComment',
            'voteable_id' => 'required|integer',
            'vote_type' => 'required|in:like,dislike',
            'voter_identifier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $voteableType = $request->voteable_type;
        $voteableId = $request->voteable_id;
        $voteType = $request->vote_type;
        $voterIdentifier = $request->voter_identifier;

        // Check if user already voted
        $existingVote = ForumVote::where([
            'voteable_type' => $voteableType,
            'voteable_id' => $voteableId,
            'voter_identifier' => $voterIdentifier,
        ])->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $voteType) {
                // Remove vote if clicking the same type
                $existingVote->delete();
                $action = 'removed';
            } else {
                // Update vote type if clicking different type
                $existingVote->update(['vote_type' => $voteType]);
                $action = 'updated';
            }
        } else {
            // Create new vote
            ForumVote::create([
                'voteable_type' => $voteableType,
                'voteable_id' => $voteableId,
                'voter_identifier' => $voterIdentifier,
                'vote_type' => $voteType,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            $action = 'created';
        }

        // Get updated counts
        $voteable = $voteableType::find($voteableId);
        
        return response()->json([
            'success' => true,
            'action' => $action,
            'likes_count' => $voteable->likes_count,
            'dislikes_count' => $voteable->dislikes_count,
        ]);
    }

    /**
     * Show posts by category.
     */
    public function category(ForumCategory $category)
    {
        $posts = ForumPost::published()
            ->where('category_id', $category->id)
            ->with(['tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('lamgame.pages.forum.category', compact('category', 'posts'));
    }

    /**
     * Show posts by tag.
     */
    public function tag(ForumTag $tag)
    {
        $posts = $tag->publishedPosts()
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('lamgame.pages.forum.tag', compact('tag', 'posts'));
    }

    /**
     * Search posts.
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $type = $request->get('type');

        if (empty($query)) {
            return redirect()->route('forum.index');
        }

        $postsQuery = ForumPost::published()
            ->with(['category', 'tags'])
            ->search($query);

        if ($category) {
            $categoryModel = ForumCategory::where('slug', $category)->first();
            if ($categoryModel) {
                $postsQuery->where('category_id', $categoryModel->id);
            }
        }

        if ($type) {
            $postsQuery->ofType($type);
        }

        $posts = $postsQuery->paginate(15);

        return view('lamgame.pages.forum.search', compact('posts', 'query', 'category', 'type'));
    }

    /**
     * Report a post or comment
     */
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reportable_type' => 'required|in:post,comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|in:spam,inappropriate,harassment,copyright,other',
            'description' => 'nullable|string|max:500',
            'reporter_id' => 'required|integer|exists:customers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Map reportable type to model class
        $reportableTypeMap = [
            'post' => 'App\\Models\\ForumPost',
            'comment' => 'App\\Models\\ForumComment'
        ];

        $reportableType = $reportableTypeMap[$request->reportable_type];
        $reportableId = $request->reportable_id;

        // Check if the reportable item exists
        $reportable = $reportableType::find($reportableId);
        if (!$reportable) {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        }

        // Check if user already reported this item
        $existingReport = ForumReport::where([
            'reporter_id' => $request->reporter_id,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
        ])->first();

        if ($existingReport) {
            return response()->json([
                'success' => false, 
                'message' => 'You have already reported this item'
            ], 422);
        }

        // Create the report
        ForumReport::create([
            'reporter_id' => $request->reporter_id,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
            'reason' => $request->reason,
            'description' => $request->description,
            'ip_address' => $request->ip(),
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully. We will review it shortly.'
        ]);
    }

    /**
     * Create unique slug for tags to avoid duplicates
     */
    private function createUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        // Keep trying with incremental suffix until we get unique slug
        while (ForumTag::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}
