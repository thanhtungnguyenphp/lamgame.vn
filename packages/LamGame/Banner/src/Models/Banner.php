<?php

namespace LamGame\Banner\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\Channel;
use Webkul\Core\Eloquent\TranslatableModel;

class Banner extends TranslatableModel
{
    use HasFactory;

    protected $table = 'banners';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'type',
        'position',
        'device_type',
        'channel_id',
        'locale',
        'start_date',
        'end_date',
        'sort_order',
        'status',
        'title',
        'content',
        'image',
        'image_alt',
        'link',
        'target',
        'css_classes',
        'attributes',
        'settings',
        'clicks_count',
        'impressions_count',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'css_classes' => 'array',
        'attributes' => 'array',
        'settings' => 'array',
        'clicks_count' => 'integer',
        'impressions_count' => 'integer',
    ];

    /**
     * Translatable attributes
     */
    public $translatedAttributes = [
        'title',
        'content',
        'image_alt',
        'meta_title',
        'meta_description',
        'settings'
    ];

    /**
     * Default device types
     */
    public const DEVICE_TYPES = [
        'all' => 'All Devices',
        'desktop' => 'Desktop',
        'tablet' => 'Tablet',
        'mobile' => 'Mobile',
    ];

    /**
     * Default banner types
     */
    public const BANNER_TYPES = [
        'image' => 'Image Banner',
        'html' => 'HTML Content',
        'video' => 'Video Banner',
    ];

    /**
     * Default positions
     */
    public const POSITIONS = [
        'homepage_hero' => 'Homepage Hero',
        'homepage_secondary' => 'Homepage Secondary',
        'sidebar_top' => 'Sidebar Top',
        'sidebar_bottom' => 'Sidebar Bottom',
        'header' => 'Header',
        'footer' => 'Footer',
        'product_detail' => 'Product Detail',
        'category_page' => 'Category Page',
        'checkout' => 'Checkout',
        'custom' => 'Custom Position',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the channel that the banner belongs to.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get the translations for the banner.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(BannerTranslation::class);
    }

    // ===== SCOPES =====

    /**
     * Scope to get active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope to get banners by position.
     */
    public function scopePosition($query, string $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope to get banners by device type.
     */
    public function scopeDevice($query, string $deviceType)
    {
        return $query->where(function ($q) use ($deviceType) {
            $q->where('device_type', 'all')
              ->orWhere('device_type', $deviceType);
        });
    }

    /**
     * Scope to get banners by channel.
     */
    public function scopeChannel($query, int $channelId)
    {
        return $query->where(function ($q) use ($channelId) {
            $q->whereNull('channel_id')
              ->orWhere('channel_id', $channelId);
        });
    }

    /**
     * Scope to get banners by locale.
     */
    public function scopeLocale($query, string $locale)
    {
        return $query->where(function ($q) use ($locale) {
            $q->whereNull('locale')
              ->orWhere('locale', $locale);
        });
    }

    /**
     * Scope to get banners within date range.
     */
    public function scopeWithinDateRange($query, $currentDate = null)
    {
        $currentDate = $currentDate ?? now();
        
        return $query->where(function ($q) use ($currentDate) {
            $q->where(function ($query) use ($currentDate) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', $currentDate);
            })
            ->where(function ($query) use ($currentDate) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $currentDate);
            });
        });
    }

    /**
     * Scope to order by sort order and created date.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Scope to get banners for frontend display.
     */
    public function scopeForDisplay($query, array $filters = [])
    {
        $query->active()
              ->withinDateRange()
              ->ordered();

        if (isset($filters['position'])) {
            $query->position($filters['position']);
        }

        if (isset($filters['device_type'])) {
            $query->device($filters['device_type']);
        }

        if (isset($filters['channel_id'])) {
            $query->channel($filters['channel_id']);
        }

        if (isset($filters['locale'])) {
            $query->locale($filters['locale']);
        }

        return $query;
    }

    // ===== ACCESSORS & MUTATORS =====

    /**
     * Get the full image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    /**
     * Get responsive image URLs.
     */
    public function getResponsiveImagesAttribute(): array
    {
        if (!$this->image) {
            return [];
        }

        $baseUrl = $this->getImageUrlAttribute();
        
        return [
            'mobile' => $baseUrl . '?w=480',
            'tablet' => $baseUrl . '?w=768',
            'desktop' => $baseUrl . '?w=1200',
            'large' => $baseUrl . '?w=1920',
        ];
    }

    /**
     * Check if banner is currently active.
     */
    public function getIsActiveAttribute(): bool
    {
        if (!$this->status) {
            return false;
        }

        $now = now();

        if ($this->start_date && $this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * Get CSS classes as string.
     */
    public function getCssClassesStringAttribute(): string
    {
        if (is_array($this->css_classes)) {
            return implode(' ', $this->css_classes);
        }

        return $this->css_classes ?? '';
    }

    /**
     * Get HTML attributes as string.
     */
    public function getHtmlAttributesStringAttribute(): string
    {
        if (!is_array($this->attributes)) {
            return '';
        }

        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            $attributes[] = "{$key}=\"{$value}\"";
        }

        return implode(' ', $attributes);
    }

    // ===== METHODS =====

    /**
     * Increment impressions count.
     */
    public function incrementImpressions(): void
    {
        $this->increment('impressions_count');
    }

    /**
     * Increment clicks count.
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks_count');
    }

    /**
     * Get click-through rate.
     */
    public function getClickThroughRate(): float
    {
        if ($this->impressions_count === 0) {
            return 0;
        }

        return round(($this->clicks_count / $this->impressions_count) * 100, 2);
    }

    /**
     * Get available positions for select options.
     */
    public static function getPositionOptions(): array
    {
        return collect(self::POSITIONS)->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => $label,
            ];
        })->values()->toArray();
    }

    /**
     * Get available device types for select options.
     */
    public static function getDeviceTypeOptions(): array
    {
        return collect(self::DEVICE_TYPES)->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => $label,
            ];
        })->values()->toArray();
    }

    /**
     * Get available banner types for select options.
     */
    public static function getBannerTypeOptions(): array
    {
        return collect(self::BANNER_TYPES)->map(function ($label, $value) {
            return [
                'value' => $value,
                'label' => $label,
            ];
        })->values()->toArray();
    }
}