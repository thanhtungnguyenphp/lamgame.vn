<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translation = $this->translations()->where('locale', 'vi')->first();
        
        return [
            'id' => $this->id,
            'name' => $translation?->name ?? 'Unknown Category',
            'slug' => $translation?->slug ?? '',
            'url_path' => $translation?->url_path ?? '',
            'description' => $translation?->description ?? '',
        ];
    }
}