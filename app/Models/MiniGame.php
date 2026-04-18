<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniGame extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'keywords', 'category',
        'thumbnail', 'game_path', 'is_active', 'is_mobile_ready',
        'play_count', 'sort_order',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'is_mobile_ready' => 'boolean',
        'play_count'      => 'integer',
    ];

    public const CATEGORIES = [
        'arcade'  => 'Arcade & Classic',
        'puzzle'  => 'Puzzle & Trí tuệ',
        'casual'  => 'Casual',
        'card'    => 'Card & Board',
        'action'  => 'Action & Runner',
        'kids'    => 'Kids & Education',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getUrlAttribute(): string
    {
        return url('/choi-game/' . $this->slug);
    }

    public function getGameUrlAttribute(): string
    {
        return asset($this->game_path . '/index.html');
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
