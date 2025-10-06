<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Webkul\User\Models\Admin;

class AdminUserInfo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user_info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'admin_id',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'bio',
        'website',
        'job_title',
        'company',
        'social_links',
        'preferences',
        'emergency_contact',
        'custom_fields',
        'profile_completed_at',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'social_links' => 'array',
        'preferences' => 'array',
        'emergency_contact' => 'array',
        'custom_fields' => 'array',
        'profile_completed_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'emergency_contact', // Sensitive information
    ];

    /**
     * Get the admin that owns the user info.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Scope a query to only include public profiles.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope a query to filter by country.
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to filter by city.
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Get the user's age.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::now()->diffInYears($this->date_of_birth);
    }

    /**
     * Get formatted address.
     */
    public function getFullAddressAttribute(): ?string
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
        ]);

        return !empty($addressParts) ? implode(', ', $addressParts) : null;
    }

    /**
     * Get formatted phone number.
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) {
            return null;
        }

        // Format Vietnamese phone numbers
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            // Format: 0xxx xxx xxx
            return substr($phone, 0, 4) . ' ' . substr($phone, 4, 3) . ' ' . substr($phone, 7);
        }
        
        return $this->phone;
    }

    /**
     * Get social media links with defaults.
     */
    public function getSocialLinksAttribute($value): array
    {
        $default = [
            'facebook' => null,
            'twitter' => null,
            'linkedin' => null,
            'instagram' => null,
            'youtube' => null,
            'tiktok' => null,
        ];

        return array_merge($default, json_decode($value, true) ?? []);
    }

    /**
     * Get user preferences with defaults.
     */
    public function getPreferencesAttribute($value): array
    {
        $default = [
            'language' => 'vi',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'date_format' => 'd/m/Y',
            'time_format' => 'H:i',
            'notifications' => [
                'email' => true,
                'push' => true,
                'sms' => false,
            ],
            'privacy' => [
                'show_phone' => false,
                'show_email' => false,
                'show_address' => false,
            ],
        ];

        return array_merge($default, json_decode($value, true) ?? []);
    }

    /**
     * Check if profile is complete.
     */
    public function getIsCompleteAttribute(): bool
    {
        $requiredFields = ['phone', 'date_of_birth', 'address', 'city'];
        
        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Get completion percentage.
     */
    public function getCompletionPercentageAttribute(): int
    {
        $allFields = [
            'date_of_birth', 'gender', 'phone', 'address', 'city', 
            'state', 'postal_code', 'bio', 'website', 'job_title', 'company'
        ];
        
        $filledFields = 0;
        
        foreach ($allFields as $field) {
            if (!empty($this->$field)) {
                $filledFields++;
            }
        }
        
        return round(($filledFields / count($allFields)) * 100);
    }

    /**
     * Mark profile as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'profile_completed_at' => now()
        ]);
    }

    /**
     * Get display name for gender.
     */
    public function getGenderDisplayAttribute(): ?string
    {
        $genders = [
            'male' => 'Nam',
            'female' => 'Nữ', 
            'other' => 'Khác'
        ];

        return $genders[$this->gender] ?? null;
    }
}
