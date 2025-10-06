<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image ? url('storage/' . $this->image) : null,
            'image_url' => $this->image_url,
            'avatar_url' => $this->image ? url('storage/' . $this->image) : null,
            'status' => (bool) ($this->status ?? true),
            'role_id' => $this->role_id,
            'role' => $this->whenLoaded('role', function () {
                return [
                    'id' => $this->role->id,
                    'name' => $this->role->name,
                    'permission_type' => $this->role->permission_type,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            'profile_completed' => $this->isProfileCompleted(),
        ];
    }

    /**
     * Check if admin profile is completed
     */
    private function isProfileCompleted(): bool
    {
        return !empty($this->name) && 
               !empty($this->email) && 
               !empty($this->role_id);
    }
}
