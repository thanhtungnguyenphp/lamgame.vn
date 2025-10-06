<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserInfoResource extends JsonResource
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
            'admin_id' => $this->admin_id,
            
            // Personal Information
            'personal_info' => [
                'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
                'age' => $this->age,
                'gender' => $this->gender,
                'gender_display' => $this->gender_display,
                'phone' => $this->phone,
                'formatted_phone' => $this->formatted_phone,
            ],
            
            // Address Information
            'address_info' => [
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'postal_code' => $this->postal_code,
                'full_address' => $this->full_address,
            ],
            
            // Professional Information
            'professional_info' => [
                'job_title' => $this->job_title,
                'company' => $this->company,
                'bio' => $this->bio,
                'website' => $this->website,
            ],
            
            // Social Links
            'social_links' => $this->social_links,
            
            // User Preferences (filtered for privacy)
            'preferences' => $this->getFilteredPreferences(),
            
            // Profile Status
            'profile_status' => [
                'is_complete' => $this->is_complete,
                'completion_percentage' => $this->completion_percentage,
                'is_public' => $this->is_public,
                'profile_completed_at' => $this->profile_completed_at?->format('Y-m-d H:i:s'),
            ],
            
            // Timestamps
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
    
    /**
     * Get filtered preferences (exclude sensitive data).
     */
    private function getFilteredPreferences(): array
    {
        $preferences = $this->preferences;
        
        // Remove sensitive preferences from API response
        unset($preferences['emergency_contact']);
        
        return $preferences;
    }
    
    /**
     * Additional data to include with resource.
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'profile_completion_status' => $this->getCompletionStatus(),
                'missing_fields' => $this->getMissingRequiredFields(),
            ]
        ];
    }
    
    /**
     * Get profile completion status message.
     */
    private function getCompletionStatus(): string
    {
        $percentage = $this->completion_percentage;
        
        if ($percentage >= 90) {
            return 'Hồ sơ gần như hoàn thiện';
        } elseif ($percentage >= 70) {
            return 'Hồ sơ khá đầy đủ';
        } elseif ($percentage >= 50) {
            return 'Hồ sơ cần bổ sung thêm';
        } else {
            return 'Hồ sơ cần hoàn thiện';
        }
    }
    
    /**
     * Get list of missing required fields.
     */
    private function getMissingRequiredFields(): array
    {
        $requiredFields = [
            'phone' => 'Số điện thoại',
            'date_of_birth' => 'Ngày sinh',
            'address' => 'Địa chỉ',
            'city' => 'Thành phố'
        ];
        
        $missing = [];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = [
                    'field' => $field,
                    'label' => $label
                ];
            }
        }
        
        return $missing;
    }
}
