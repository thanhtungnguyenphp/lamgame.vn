<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandingPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'template',
        'hero_title', 'hero_subtitle', 'hero_cta_text', 'hero_cta_url',
        'hero_bg_image', 'hero_bg_color',
        'description', 'sections',
        'meta_title', 'meta_description', 'meta_keywords', 'og_image',
        'status', 'start_at', 'end_at',
        'author', 'author_id', 'views',
    ];

    protected $casts = [
        'status'   => 'boolean',
        'sections' => 'array',
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
        'views'    => 'integer',
    ];

    public const TEMPLATES = [
        'general'          => 'Trang thông tin chung',
        'event-countdown'  => 'Sự kiện / Giải đấu (có countdown)',
        'product-launch'   => 'Ra mắt Game / Sản phẩm',
        'mini-game'        => 'Mini Game / Dự đoán',
    ];

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('start_at')->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_at')->orWhere('end_at', '>=', now());
            });
    }

    // --- Accessors ---

    public function getUrlAttribute(): string
    {
        return url('/p/' . $this->slug);
    }

    public function getHeroBgImageUrlAttribute(): ?string
    {
        if (!$this->hero_bg_image) return null;
        return str_starts_with($this->hero_bg_image, 'http')
            ? $this->hero_bg_image
            : asset('storage/' . ltrim($this->hero_bg_image, '/'));
    }

    public function getOgImageUrlAttribute(): ?string
    {
        if ($this->og_image) {
            return str_starts_with($this->og_image, 'http')
                ? $this->og_image
                : asset('storage/' . ltrim($this->og_image, '/'));
        }
        return $this->hero_bg_image_url ?? asset('assets/logos/png/logo-square-512.png');
    }

    public function isLive(): bool
    {
        if (!$this->status) return false;
        if ($this->start_at && $this->start_at->isFuture()) return false;
        if ($this->end_at && $this->end_at->isPast()) return false;
        return true;
    }

    public function getTemplateLabelAttribute(): string
    {
        return self::TEMPLATES[$this->template] ?? $this->template;
    }

    /**
     * Get a specific section from the sections JSON by key
     */
    public function getSection(string $key, $default = null)
    {
        return data_get($this->sections, $key, $default);
    }
}
