<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'parent_id',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'parent_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->where('parent_id', 0)->orWhereNull('parent_id');
    }

    /**
     * Get the parent category
     */
    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id');
    }

    /**
     * Get blogs that belong to this category (as default_category)
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'default_category');
    }

    /**
     * Get published blogs count for this category
     */
    public function getPublishedBlogsCountAttribute()
    {
        return $this->blogs()->published()->count();
    }

    /**
     * Get category image URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return 'https://via.placeholder.com/300x200?text=' . urlencode($this->name);
    }

    /**
     * Get category URL
     */
    public function getUrlAttribute()
    {
        return route('blog.category', $this->slug);
    }

    /**
     * Check if category has children
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get all blog IDs that belong to this category (including those in categorys field)
     */
    public function getAllBlogIds()
    {
        // Get blogs where this category is the default category
        $defaultBlogs = $this->blogs()->pluck('id');
        
        // Get blogs where this category is in the categorys field
        $categoryBlogs = Blog::where('categorys', 'LIKE', '%' . $this->id . '%')->pluck('id');
        
        return $defaultBlogs->merge($categoryBlogs)->unique();
    }
}
