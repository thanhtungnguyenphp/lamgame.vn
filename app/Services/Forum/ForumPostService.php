<?php

namespace App\Services\Forum;

use App\Models\ForumPost;
use App\Models\ForumTag;
use App\Models\ForumCategory;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class ForumPostService
{
    public function getIndexData(array $filters): array
    {
        $perPage = config('forum.posts_per_page', 15);

        $query = ForumPost::published()->with(['category', 'tags']);

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['type'])) {
            $query->ofType($filters['type']);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        $sort = $filters['sort'] ?? 'latest';
        $query = match ($sort) {
            'popular'  => $query->popular(),
            'activity' => $query->byActivity(),
            default    => $query->latest(),
        };

        return [
            'posts'      => $query->paginate($perPage)->appends($filters),
            'sticky'     => ForumPost::published()->sticky()->with(['category', 'tags'])->latest()->limit(3)->get(),
            'categories' => ForumCategory::active()->ordered()->withCount('publishedPosts')->get(),
            'tags'       => ForumTag::popular()->limit(20)->get(),
            'stats'      => $this->getPublicStats(),
        ];
    }

    public function getPublicStats(): array
    {
        return [
            'total_posts'    => ForumPost::published()->count(),
            'total_comments' => \App\Models\ForumComment::where('status', 'published')->count(),
            'total_members'  => ForumPost::published()->distinct('author_email')->count('author_email'),
            'categories_count' => ForumCategory::active()->count(),
        ];
    }

    public function getAdminStats(): array
    {
        return [
            'total_posts'        => ForumPost::count(),
            'published_posts'    => ForumPost::where('status', 'published')->count(),
            'pending_posts'      => ForumPost::where('status', 'pending')->count(),
            'total_comments'     => \App\Models\ForumComment::count(),
            'published_comments' => \App\Models\ForumComment::where('status', 'published')->count(),
            'pending_comments'   => \App\Models\ForumComment::where('status', 'pending')->count(),
            'pending_reports'    => \App\Models\ForumReport::where('status', 'pending')->count(),
            'total_reports'      => \App\Models\ForumReport::count(),
        ];
    }

    public function create(array $data, $customer = null): ForumPost
    {
        $postData = [
            'title'        => $data['title'],
            'content'      => $data['content'],
            'category_id'  => $data['category_id'],
            'type'         => $data['type'] ?? 'discussion',
            'status'       => 'published',
            'author_name'  => $customer->name ?? ($data['author_name'] ?? 'Anonymous'),
            'author_email' => $customer->email ?? ($data['author_email'] ?? null),
            'author_avatar' => $customer->image_url ?? ($data['author_avatar'] ?? null),
            'customer_id'  => $customer?->id,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ];

        $post = ForumPost::create($postData);

        if (!empty($data['tags'])) {
            $this->syncTags($post, $data['tags']);
        }

        return $post;
    }

    public function update(ForumPost $post, array $data, $customer = null): ForumPost
    {
        $editHistory = $post->edit_history ?? [];
        $editHistory[] = [
            'date'    => now()->toISOString(),
            'author'  => $customer->name ?? $post->author_name,
            'reason'  => $data['edit_reason'] ?? null,
            'changes' => array_keys(array_diff_assoc(
                array_intersect_key($data, array_flip(['title', 'content', 'category_id', 'type'])),
                $post->only(['title', 'content', 'category_id', 'type'])
            )),
        ];

        $post->update([
            'title'        => $data['title'],
            'content'      => $data['content'],
            'category_id'  => $data['category_id'],
            'type'         => $data['type'] ?? $post->type,
            'edit_history'  => $editHistory,
        ]);

        if (array_key_exists('tags', $data)) {
            $this->syncTags($post, $data['tags'] ?? '');
        }

        return $post->fresh();
    }

    public function delete(ForumPost $post): void
    {
        $post->delete();
    }

    public function getDetail(ForumPost $post, ?string $ip = null): array
    {
        $post->load(['category', 'tags', 'rootComments' => function ($q) {
            $q->with('publishedReplies')->where('status', 'published')->oldest();
        }]);

        // Deduplicate view count by IP
        if ($ip && !session()->has("forum_viewed_{$post->id}")) {
            $post->incrementViews();
            session()->put("forum_viewed_{$post->id}", true);
        }

        $related = ForumPost::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest()->limit(5)->get();

        $authorPosts = ForumPost::published()
            ->where('id', '!=', $post->id)
            ->where('author_email', $post->author_email)
            ->latest()->limit(5)->get();

        return [
            'post'        => $post,
            'related'     => $related,
            'authorPosts' => $authorPosts,
        ];
    }

    public function getByCategory(ForumCategory $category, array $filters = []): LengthAwarePaginator
    {
        $perPage = config('forum.posts_per_page', 15);

        return $category->publishedPosts()
            ->with(['category', 'tags'])
            ->latest()
            ->paginate($perPage)
            ->appends($filters);
    }

    public function getByTag(ForumTag $tag, array $filters = []): LengthAwarePaginator
    {
        $perPage = config('forum.posts_per_page', 15);

        return $tag->publishedPosts()
            ->with(['category', 'tags'])
            ->latest()
            ->paginate($perPage)
            ->appends($filters);
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        $perPage = config('forum.posts_per_page', 15);

        $q = ForumPost::published()->with(['category', 'tags'])->search($query);

        if (!empty($filters['category'])) {
            $q->where('category_id', $filters['category']);
        }
        if (!empty($filters['type'])) {
            $q->ofType($filters['type']);
        }

        return $q->latest()->paginate($perPage)->appends(array_merge(['q' => $query], $filters));
    }

    public function updateStatus(ForumPost $post, string $status, ?bool $isFeatured = null, ?bool $isSticky = null): void
    {
        $data = ['status' => $status];
        if ($isFeatured !== null) $data['is_featured'] = $isFeatured;
        if ($isSticky !== null) $data['is_sticky'] = $isSticky;
        $post->update($data);
    }

    public function massUpdateStatus(array $ids, string $status): int
    {
        $count = 0;
        foreach ($ids as $id) {
            $post = ForumPost::find($id);
            if ($post) {
                $post->update(['status' => $status]);
                $count++;
            }
        }
        return $count;
    }

    public function massDelete(array $ids): int
    {
        $count = 0;
        foreach ($ids as $id) {
            if (ForumPost::destroy($id)) $count++;
        }
        return $count;
    }

    // --- Tag helpers ---

    public function syncTags(ForumPost $post, string $tagsString): void
    {
        if (empty(trim($tagsString))) {
            $post->tags()->detach();
            return;
        }

        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));
        $tagIds = [];

        foreach ($tagNames as $name) {
            $tag = ForumTag::firstOrCreate(
                ['slug' => Str::slug($name) ?: $this->createUniqueSlug($name)],
                ['name' => $name, 'color' => '#6b7280']
            );
            $tagIds[] = $tag->id;
        }

        $post->tags()->sync($tagIds);

        // Update tag post counts
        ForumTag::whereIn('id', $tagIds)->each(fn ($t) => $t->updatePostsCount());
    }

    private function createUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'tag-' . Str::random(6);
        $slug = $base;
        $i = 1;
        while (ForumTag::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
