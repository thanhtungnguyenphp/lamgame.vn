<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\AdminUserInfoResource;

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
            
            // Extended Profile Information (if available)
            'extended_profile' => $this->when(
                $this->relationLoaded('userInfo') && $this->userInfo,
                function () {
                    return new AdminUserInfoResource($this->userInfo);
                }
            ),
            
            // Quick access to commonly used extended fields
            'phone' => $this->when(
                $this->relationLoaded('userInfo') && $this->userInfo,
                $this->userInfo?->formatted_phone
            ),
            'location' => $this->when(
                $this->relationLoaded('userInfo') && $this->userInfo,
                $this->userInfo?->city && $this->userInfo?->country 
                    ? $this->userInfo->city . ', ' . $this->userInfo->country
                    : ($this->userInfo?->city ?? $this->userInfo?->country)
            ),
            'job_title' => $this->when(
                $this->relationLoaded('userInfo') && $this->userInfo,
                $this->userInfo?->job_title
            ),
            'bio' => $this->when(
                $this->relationLoaded('userInfo') && $this->userInfo,
                $this->userInfo?->bio
            ),
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
