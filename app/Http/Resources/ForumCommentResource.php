<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumCommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'content'        => $this->content,
            'is_best_answer' => $this->is_best_answer,
            'author'         => [
                'name'   => $this->author_name,
                'avatar' => $this->avatar_url,
            ],
            'stats'          => [
                'likes'    => $this->likes_count,
                'dislikes' => $this->dislikes_count,
                'replies'  => $this->replies_count,
            ],
            'parent_id'      => $this->parent_id,
            'replies'        => self::collection($this->whenLoaded('publishedReplies')),
            'created_at'     => $this->created_at?->toISOString(),
        ];
    }
}
