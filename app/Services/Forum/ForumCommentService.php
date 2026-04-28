<?php

namespace App\Services\Forum;

use App\Models\ForumComment;
use App\Models\ForumPost;

class ForumCommentService
{
    public function __construct(
        protected ForumNotificationService $notificationService,
        protected ForumReputationService $reputationService,
    ) {}

    public function create(ForumPost $post, array $data, $customer = null): ForumComment
    {
        $comment = ForumComment::create([
            'post_id'      => $post->id,
            'parent_id'    => $data['parent_id'] ?? null,
            'content'      => $data['content'],
            'author_name'  => $customer->name ?? ($data['author_name'] ?? 'Anonymous'),
            'author_email' => $customer->email ?? ($data['author_email'] ?? null),
            'author_avatar' => $customer->image_url ?? ($data['author_avatar'] ?? null),
            'customer_id'  => $customer?->id,
            'status'       => 'published',
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);

        // Notify post author
        $this->notificationService->notifyReplyToPost($post, $comment);

        // Notify parent comment author (if reply)
        if ($comment->parent_id && $comment->parent) {
            $this->notificationService->notifyReplyToComment($comment->parent, $comment);
        }

        // Process mentions
        $this->processMentions($comment, $post);

        // Award reputation
        if ($customer) {
            $this->reputationService->award($customer->id, 'comment_created', $comment);
        }

        return $comment;
    }

    public function pinBestAnswer(ForumComment $comment, ForumPost $post): void
    {
        // Unpin existing best answer
        $post->comments()->where('is_best_answer', true)->update(['is_best_answer' => false]);

        // Pin this comment
        $comment->update(['is_best_answer' => true]);

        // Notify comment author
        $this->notificationService->notifyBestAnswer($comment);

        // Award reputation
        if ($comment->customer_id) {
            $this->reputationService->award($comment->customer_id, 'best_answer', $comment);
        }
    }

    public function unpinBestAnswer(ForumPost $post): void
    {
        $post->comments()->where('is_best_answer', true)->update(['is_best_answer' => false]);
    }

    public function updateStatus(ForumComment $comment, string $status): void
    {
        $comment->update(['status' => $status]);
        $comment->post?->updateCommentStats();
    }

    public function delete(ForumComment $comment): void
    {
        $post = $comment->post;
        $comment->delete();
        $post?->updateCommentStats();
    }

    public function massUpdateStatus(array $ids, string $status): int
    {
        $posts = [];
        $count = 0;

        foreach ($ids as $id) {
            $comment = ForumComment::find($id);
            if ($comment) {
                $comment->update(['status' => $status]);
                if ($comment->post) $posts[$comment->post_id] = $comment->post;
                $count++;
            }
        }

        foreach ($posts as $post) {
            $post->updateCommentStats();
        }

        return $count;
    }

    public function massDelete(array $ids): int
    {
        $posts = [];
        $count = 0;

        foreach ($ids as $id) {
            $comment = ForumComment::find($id);
            if ($comment) {
                if ($comment->post) $posts[$comment->post_id] = $comment->post;
                $comment->delete();
                $count++;
            }
        }

        foreach ($posts as $post) {
            $post->updateCommentStats();
        }

        return $count;
    }

    /**
     * Parse @mentions in comment content and send notifications.
     */
    protected function processMentions(ForumComment $comment, ForumPost $post): void
    {
        preg_match_all('/@(\S+)/', strip_tags($comment->content), $matches);

        if (empty($matches[1])) return;

        $customerModel = \Webkul\Customer\Models\CustomerProxy::modelClass();
        $notified = [];

        foreach (array_unique($matches[1]) as $name) {
            $mentioned = $customerModel::where('first_name', $name)
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) = ?", [$name])
                ->first();

            if ($mentioned && !in_array($mentioned->id, $notified)) {
                $this->notificationService->notifyMention(
                    $mentioned->id, $post, $comment, $comment->author_name
                );
                $notified[] = $mentioned->id;
            }
        }
    }
}
