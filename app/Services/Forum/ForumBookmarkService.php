<?php

namespace App\Services\Forum;

use App\Models\ForumBookmark;
use App\Models\ForumPost;

class ForumBookmarkService
{
    /**
     * Toggle bookmark. Returns true if bookmarked, false if removed.
     */
    public function toggle(int $customerId, ForumPost $post): bool
    {
        $existing = ForumBookmark::where('customer_id', $customerId)
            ->where('post_id', $post->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        ForumBookmark::create([
            'customer_id' => $customerId,
            'post_id'     => $post->id,
        ]);

        return true;
    }

    /**
     * Get bookmarked posts for a customer.
     */
    public function getByCustomer(int $customerId, int $perPage = 15)
    {
        return ForumPost::whereHas('bookmarks', fn ($q) => $q->where('customer_id', $customerId))
            ->with(['category', 'tags'])
            ->latest()
            ->paginate($perPage);
    }
}
