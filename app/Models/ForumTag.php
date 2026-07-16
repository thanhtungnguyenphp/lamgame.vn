<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ForumTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'posts_count',
        'is_featured',
    ];

    protected $casts = [
        'posts_count' => 'integer',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the posts for the tag.
     */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(ForumPost::class, 'forum_post_tags', 'tag_id', 'post_id')->withTimestamps();
    }

    /**
     * Get the published posts for the tag.
     */
    public function publishedPosts(): BelongsToMany
    {
        return $this->posts()->where('status', 'published');
    }

    /**
     * Scope a query to only include featured tags.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order tags by posts count.
     */
    public function scopePopular($query)
    {
        return $query->orderBy('posts_count', 'desc');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Resolve the route binding for implicit model binding.
     * Handles legacy duplicate tag slugs like "unity-1", "unity-2" by
     * redirecting to the clean tag slug "unity".
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Try normal resolution first
        $model = parent::resolveRouteBinding($value, $field);
        
        if ($model) {
            return $model;
        }

        // If not found, check if it's a suffixed duplicate (e.g., unity-1 → unity)
        $cleanSlug = preg_replace('/-\d+$/', '', $value);
        if ($cleanSlug !== $value) {
            $cleanModel = static::where('slug', $cleanSlug)->first();
            if ($cleanModel) {
                abort(redirect("/forum/tag/{$cleanSlug}", 301));
            }
        }

        // Not found at all — will trigger Laravel's ModelNotFoundException → 404
        return null;
    }

    /**
     * Update posts count.
     */
    public function updatePostsCount()
    {
        $this->update([
            'posts_count' => $this->publishedPosts()->count(),
        ]);
    }
}
