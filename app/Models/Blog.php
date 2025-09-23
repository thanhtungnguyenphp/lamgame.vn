<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'channels',
        'default_category',
        'categorys',
        'tags',
        'author',
        'author_id',
        'src',
        'locale',
        'status',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'published_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'allow_comments' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Scope for published blogs
     */
    public function scopePublished($query)
    {
        return $query->where('status', 1)
                    ->where('published_at', '<=', now());
    }

    /**
     * Get the category that owns the blog
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'default_category');
    }

    /**
     * Get all categories for this blog (from categorys field)
     */
    public function getCategories()
    {
        if (!$this->categorys) {
            return collect();
        }
        
        $categoryIds = explode(',', $this->categorys);
        return BlogCategory::whereIn('id', $categoryIds)->get();
    }

    /**
     * Get all tags for this blog (from tags field)
     */
    public function getTags()
    {
        if (!$this->tags) {
            return collect();
        }
        
        $tagIds = explode(',', $this->tags);
        return BlogTag::whereIn('id', $tagIds)->get();
    }

    /**
     * Get featured image URL
     */
    public function getFeaturedImageAttribute()
    {
        if (!$this->src) {
            return 'https://via.placeholder.com/800x400?text=' . urlencode($this->name);
        }

        // Handle old format paths that start with /storage/ or storage/
        if (str_starts_with($this->src, '/storage/') || str_starts_with($this->src, 'storage/')) {
            // Remove leading slash and storage/ prefix to avoid double storage path
            $cleanPath = ltrim($this->src, '/');
            if (str_starts_with($cleanPath, 'storage/')) {
                $cleanPath = substr($cleanPath, 8); // Remove 'storage/' prefix
            }
            return asset('storage/' . $cleanPath);
        }

        // Handle new format paths that are just the relative path within storage
        return asset('storage/' . $this->src);
    }

    /**
     * Get blog URL
     */
    public function getUrlAttribute()
    {
        return route('blog.show', $this->slug);
    }

    /**
     * Get excerpt from description
     */
    public function getExcerptAttribute($length = 200)
    {
        $text = strip_tags($this->description);
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }

    /**
     * Get reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->description));
        $minutes = ceil($wordCount / 200); // Average reading speed 200 words/minute
        return $minutes;
    }

    /**
     * Format published date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at->format('d/m/Y');
    }

    /**
     * Get relative published date
     */
    public function getRelativeDateAttribute()
    {
        return $this->published_at->diffForHumans();
    }
}
