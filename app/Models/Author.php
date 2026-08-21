<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Author model for E-E-A-T content attribution
 * 
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $title
 * @property string|null $bio
 * @property int|null $experience_years
 * @property array|null $expertise
 * @property array|null $social_links
 * @property string|null $avatar
 * @property string|null $email
 * @property string|null $website
 * @property int|null $customer_id
 * @property bool $is_staff
 * @property bool $is_verified
 * @property bool $is_active
 */
class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'title',
        'bio',
        'experience_years',
        'expertise',
        'social_links',
        'avatar',
        'email',
        'website',
        'customer_id',
        'is_staff',
        'is_verified',
        'is_active',
    ];

    protected $casts = [
        'expertise' => 'array',
        'social_links' => 'array',
        'is_staff' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'experience_years' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($author) {
            if (empty($author->slug)) {
                $author->slug = Str::slug($author->name);
            }
        });
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Blog posts written by this author
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    /**
     * Blog posts reviewed by this author
     */
    public function reviewedBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'reviewed_by');
    }

    /**
     * Linked customer account
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(\Webkul\Customer\Models\Customer::class);
    }

    /**
     * Get avatar URL with fallback
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // Gravatar fallback
        if ($this->email) {
            $hash = md5(strtolower(trim($this->email)));
            return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
        }
        
        return asset('images/default-avatar.png');
    }

    /**
     * Get formatted experience string
     */
    public function getExperienceTextAttribute(): ?string
    {
        if (!$this->experience_years) {
            return null;
        }
        
        if ($this->experience_years === 1) {
            return '1 năm kinh nghiệm';
        }
        
        return "{$this->experience_years} năm kinh nghiệm";
    }

    /**
     * Get expertise as comma-separated string
     */
    public function getExpertiseTextAttribute(): ?string
    {
        if (empty($this->expertise)) {
            return null;
        }
        
        return implode(', ', $this->expertise);
    }

    /**
     * Check if author has a specific social link
     */
    public function hasSocialLink(string $platform): bool
    {
        return !empty($this->social_links[$platform] ?? null);
    }

    /**
     * Get social link URL
     */
    public function getSocialLink(string $platform): ?string
    {
        return $this->social_links[$platform] ?? null;
    }

    /**
     * Get published blog count
     */
    public function getPublishedBlogCountAttribute(): int
    {
        return $this->blogs()->where('status', 1)->count();
    }

    /**
     * Scope: active authors only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: staff authors
     */
    public function scopeStaff($query)
    {
        return $query->where('is_staff', true);
    }

    /**
     * Scope: verified authors
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Generate schema.org Person markup
     */
    public function toSchemaOrg(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $this->name,
            'url' => route('authors.show', $this->slug),
        ];
        
        if ($this->title) {
            $schema['jobTitle'] = $this->title;
        }
        
        if ($this->bio) {
            $schema['description'] = Str::limit($this->bio, 200);
        }
        
        if ($this->avatar) {
            $schema['image'] = $this->avatar_url;
        }
        
        // Social links as sameAs
        $sameAs = [];
        foreach (['github', 'linkedin', 'twitter', 'website'] as $platform) {
            if ($this->hasSocialLink($platform)) {
                $sameAs[] = $this->getSocialLink($platform);
            }
        }
        if ($this->website) {
            $sameAs[] = $this->website;
        }
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }
        
        // Expertise as knowsAbout
        if (!empty($this->expertise)) {
            $schema['knowsAbout'] = $this->expertise;
        }
        
        return $schema;
    }

    /**
     * Alias for toSchemaOrg for blade templates
     */
    public function getSchemaOrgData(): array
    {
        return $this->toSchemaOrg();
    }
}
