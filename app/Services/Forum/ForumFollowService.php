<?php

namespace App\Services\Forum;

use App\Models\ForumFollow;
use App\Models\ForumPost;

class ForumFollowService
{
    public function follow(int $userId, string $type, string $id): array
    {
        if (!in_array($type, ['user', 'category', 'tag'])) {
            return ['error' => 'Type không hợp lệ'];
        }
        if ($type === 'user' && (string) $userId === $id) {
            return ['error' => 'Không thể follow chính mình'];
        }

        ForumFollow::firstOrCreate([
            'follower_id' => $userId,
            'followable_type' => $type,
            'followable_id' => $id,
            'created_at' => now(),
        ]);

        return ['success' => true];
    }

    public function unfollow(int $userId, string $type, string $id): void
    {
        ForumFollow::where('follower_id', $userId)
            ->where('followable_type', $type)
            ->where('followable_id', $id)
            ->delete();
    }

    public function feed(int $userId, int $perPage = 20)
    {
        $follows = ForumFollow::where('follower_id', $userId)->get();

        $userIds = $follows->where('followable_type', 'user')->pluck('followable_id')->toArray();
        $categoryIds = $follows->where('followable_type', 'category')->pluck('followable_id')->toArray();
        $tagNames = $follows->where('followable_type', 'tag')->pluck('followable_id')->toArray();

        return ForumPost::where(function ($q) use ($userIds, $categoryIds, $tagNames) {
            if ($userIds) $q->orWhereIn('customer_id', $userIds);
            if ($categoryIds) $q->orWhereIn('category_id', $categoryIds);
            if ($tagNames) $q->orWhereHas('tags', fn ($t) => $t->whereIn('name', $tagNames));
        })
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->with(['customer', 'category'])
            ->paginate($perPage);
    }

    public function followers(string $type, string $id)
    {
        return ForumFollow::where('followable_type', $type)
            ->where('followable_id', $id)
            ->count();
    }

    public function following(int $userId)
    {
        return ForumFollow::where('follower_id', $userId)->get();
    }
}
