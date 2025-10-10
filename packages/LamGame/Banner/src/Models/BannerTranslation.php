<?php

namespace LamGame\Banner\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannerTranslation extends Model
{
    protected $table = 'banner_translations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'banner_id',
        'locale',
        'title',
        'content',
        'image_alt',
        'meta_title',
        'meta_description',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = true;

    // ===== RELATIONSHIPS =====

    /**
     * Get the banner that owns the translation.
     */
    public function banner(): BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }
}