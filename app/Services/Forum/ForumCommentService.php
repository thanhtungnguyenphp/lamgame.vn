<?php

namespace App\Services\Forum;

use App\Models\ForumComment;
use App\Models\ForumPost;

class ForumCommentService
{
    public function create(ForumPost $post, array $data, $customer = null): ForumComment
    {
        return ForumComment::create([
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
}
