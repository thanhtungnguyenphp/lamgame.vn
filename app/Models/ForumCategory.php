<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_active',
        'is_featured',
        'posts_count',
        'comments_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'posts_count' => 'integer',
        'comments_count' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get the posts for the category.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class, 'category_id');
    }

    /**
     * Get the published posts for the category.
     */
    public function publishedPosts(): HasMany
    {
        return $this->posts()->where('status', 'published');
    }

    /**
     * Get the featured posts for the category.
     */
    public function featuredPosts(): HasMany
    {
        return $this->posts()->where('is_featured', true);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured categories.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order categories by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Update posts and comments count.
     */
    public function updateCounts()
    {
        $this->update([
            'posts_count' => $this->posts()->count(),
            'comments_count' => $this->posts()->withCount('comments')->get()->sum('comments_count'),
        ]);
    }
}
