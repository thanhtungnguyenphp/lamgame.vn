<?php

namespace App\Services\Forum;

use App\Models\ForumNotification;
use App\Models\ForumPost;
use App\Models\ForumComment;

class ForumNotificationService
{
    public function notifyReplyToPost(ForumPost $post, ForumComment $comment): void
    {
        // Don't notify if commenter is the post author
        if (!$post->customer_id || $post->customer_id === $comment->customer_id) return;

        ForumNotification::create([
            'customer_id' => $post->customer_id,
            'type'        => 'reply_post',
            'title'       => "{$comment->author_name} đã bình luận bài viết của bạn",
            'body'        => \Illuminate\Support\Str::limit(strip_tags($comment->content), 100),
            'url'         => route('forum.posts.show', $post->slug) . '#comment-' . $comment->id,
            'data'        => ['post_id' => $post->id, 'comment_id' => $comment->id],
        ]);
    }

    public function notifyReplyToComment(ForumComment $parent, ForumComment $reply): void
    {
        if (!$parent->customer_id || $parent->customer_id === $reply->customer_id) return;

        ForumNotification::create([
            'customer_id' => $parent->customer_id,
            'type'        => 'reply_comment',
            'title'       => "{$reply->author_name} đã trả lời bình luận của bạn",
            'body'        => \Illuminate\Support\Str::limit(strip_tags($reply->content), 100),
            'url'         => route('forum.posts.show', $parent->post->slug) . '#comment-' . $reply->id,
            'data'        => ['post_id' => $parent->post_id, 'comment_id' => $reply->id, 'parent_id' => $parent->id],
        ]);
    }

    public function notifyBestAnswer(ForumComment $comment): void
    {
        if (!$comment->customer_id) return;
        $post = $comment->post;
        if (!$post || $comment->customer_id === $post->customer_id) return;

        ForumNotification::create([
            'customer_id' => $comment->customer_id,
            'type'        => 'best_answer',
            'title'       => "Bình luận của bạn được chọn là câu trả lời tốt nhất!",
            'body'        => \Illuminate\Support\Str::limit($post->title, 100),
            'url'         => route('forum.posts.show', $post->slug) . '#comment-' . $comment->id,
            'data'        => ['post_id' => $post->id, 'comment_id' => $comment->id],
        ]);
    }

    public function notifyMention(int $mentionedCustomerId, ForumPost $post, ForumComment $comment, string $mentionerName): void
    {
        if ($mentionedCustomerId === $comment->customer_id) return;

        ForumNotification::create([
            'customer_id' => $mentionedCustomerId,
            'type'        => 'mention',
            'title'       => "{$mentionerName} đã nhắc đến bạn trong một bình luận",
            'body'        => \Illuminate\Support\Str::limit(strip_tags($comment->content), 100),
            'url'         => route('forum.posts.show', $post->slug) . '#comment-' . $comment->id,
            'data'        => ['post_id' => $post->id, 'comment_id' => $comment->id],
        ]);
    }

    public function getForCustomer(int $customerId, int $perPage = 20)
    {
        return ForumNotification::forCustomer($customerId)
            ->latest()
            ->paginate($perPage);
    }

    public function getUnreadCount(int $customerId): int
    {
        return ForumNotification::forCustomer($customerId)->unread()->count();
    }

    public function markAsRead(int $notificationId, int $customerId): void
    {
        ForumNotification::where('id', $notificationId)
            ->where('customer_id', $customerId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(int $customerId): int
    {
        return ForumNotification::forCustomer($customerId)
            ->unread()
            ->update(['read_at' => now()]);
    }
}
