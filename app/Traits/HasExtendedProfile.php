<?php

namespace App\Traits;

use App\Models\AdminUserInfo;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasExtendedProfile
{
    /**
     * Get the admin's extended profile information.
     */
    public function userInfo(): HasOne
    {
        return $this->hasOne(AdminUserInfo::class, 'admin_id');
    }

    /**
     * Get extended profile with fallback.
     */
    public function getExtendedProfileAttribute(): AdminUserInfo
    {
        return $this->userInfo ?? new AdminUserInfo([
            'admin_id' => $this->id
        ]);
    }

    /**
     * Create or update extended profile info.
     */
    public function updateExtendedProfile(array $data): AdminUserInfo
    {
        return $this->userInfo()->updateOrCreate(
            ['admin_id' => $this->id],
            $data
        );
    }

    /**
     * Check if extended profile exists and is complete.
     */
    public function hasCompleteExtendedProfile(): bool
    {
        return $this->userInfo && $this->userInfo->is_complete;
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletionPercentage(): int
    {
        if (!$this->userInfo) {
            return 0;
        }

        return $this->userInfo->completion_percentage;
    }

    /**
     * Get formatted full name with title.
     */
    public function getFullNameWithTitleAttribute(): string
    {
        if ($this->userInfo && $this->userInfo->job_title) {
            return $this->userInfo->job_title . ' ' . $this->name;
        }

        return $this->name;
    }

    /**
     * Get preferred contact method.
     */
    public function getPreferredContactAttribute(): string
    {
        if (!$this->userInfo) {
            return $this->email;
        }

        // Check privacy preferences
        $privacy = $this->userInfo->preferences['privacy'] ?? [];
        
        if (($privacy['show_phone'] ?? false) && $this->userInfo->phone) {
            return $this->userInfo->formatted_phone;
        }
        
        if ($privacy['show_email'] ?? true) {
            return $this->email;
        }
        
        return 'Contact through admin';
    }

    /**
     * Get user's location string.
     */
    public function getLocationAttribute(): ?string
    {
        if (!$this->userInfo) {
            return null;
        }

        return $this->userInfo->city && $this->userInfo->country 
            ? $this->userInfo->city . ', ' . $this->userInfo->country
            : ($this->userInfo->city ?? $this->userInfo->country);
    }

    /**
     * Check if user is in specific location.
     */
    public function isInLocation(string $city = null, string $country = null): bool
    {
        if (!$this->userInfo) {
            return false;
        }

        $matchesCity = $city ? (strtolower($this->userInfo->city) === strtolower($city)) : true;
        $matchesCountry = $country ? (strtolower($this->userInfo->country) === strtolower($country)) : true;

        return $matchesCity && $matchesCountry;
    }
}