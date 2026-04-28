<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ForumPostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'slug'            => $this->slug,
            'excerpt'         => $this->excerpt,
            'content'         => $this->when($request->routeIs('*.show'), $this->content),
            'type'            => $this->type,
            'status'          => $this->status,
            'is_featured'     => $this->is_featured,
            'is_sticky'       => $this->is_sticky,
            'author'          => [
                'name'   => $this->author_name,
                'avatar' => $this->author_avatar,
            ],
            'category'        => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id, 'name' => $this->category->name, 'slug' => $this->category->slug,
            ]),
            'tags'            => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => [
                'id' => $t->id, 'name' => $t->name, 'slug' => $t->slug, 'color' => $t->color,
            ])),
            'stats'           => [
                'views'    => $this->views_count,
                'comments' => $this->comments_count,
                'likes'    => $this->likes_count,
                'dislikes' => $this->dislikes_count,
            ],
            'hot_score'       => $this->hot_score,
            'url'             => route('forum.posts.show', $this->slug),
            'created_at'      => $this->created_at?->toISOString(),
            'last_activity'   => ($this->last_comment_at ?? $this->created_at)?->toISOString(),
        ];
    }
}
