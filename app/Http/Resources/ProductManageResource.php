<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ProductManageResource extends JsonResource
{
    public function toArray($request): array
    {
        $flat = $this->flat;

        return [
            'id'                 => $this->id,
            'sku'                => $this->sku,
            'type'               => $this->type,
            'pending_review'     => (bool) $this->pending_review,
            'rejection_reason'   => $this->rejection_reason,

            // From product_flat
            'name'               => $flat?->name,
            'description'        => $flat?->description,
            'short_description'  => $flat?->short_description,
            'url_key'            => $flat?->url_key,
            'price'              => $flat?->price ? (float) $flat->price : null,
            'special_price'      => $flat?->special_price ? (float) $flat->special_price : null,
            'special_price_from' => $flat?->special_price_from,
            'special_price_to'   => $flat?->special_price_to,
            'status'             => $flat?->status ? (int) $flat->status : 0,
            'thumbnail'          => $flat?->thumbnail,

            // Relations
            'images'             => $this->whenLoaded('images', fn () =>
                $this->images->map(fn ($img) => [
                    'id' => $img->id, 'path' => $img->path, 'position' => $img->position,
                ])
            ),
            'categories'         => $this->whenLoaded('categories', fn () =>
                $this->categories->map(fn ($cat) => [
                    'id' => $cat->id, 'name' => $cat->translations->first()?->name,
                ])
            ),
            'downloadable_links' => $this->whenLoaded('downloadable_links', fn () =>
                $this->downloadable_links->map(fn ($link) => [
                    'id' => $link->id, 'title' => $link->title ?? $link->file_name,
                    'price' => (float) $link->price, 'type' => $link->type,
                ])
            ),
            'seller'             => $this->when($this->relationLoaded('seller') && $this->seller, fn () => [
                'id'        => $this->seller->id,
                'shop_name' => $this->seller->shop_name,
                'shop_slug' => $this->seller->shop_slug,
            ]),

            // Custom EAV attributes (loaded via $this->custom_attributes)
            'attributes'         => $this->when(isset($this->custom_attributes), $this->custom_attributes),

            // Stats (loaded via $this->product_stats)
            'stats'              => $this->when(isset($this->product_stats), $this->product_stats),

            // Meta
            'meta' => [
                'meta_title'       => $flat?->meta_title,
                'meta_description' => $flat?->meta_description,
                'meta_keywords'    => $flat?->meta_keywords,
            ],

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
