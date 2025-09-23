<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogTag extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blog_tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
        'locale',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
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
     * Scope for active tags
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get tag URL
     */
    public function getUrlAttribute()
    {
        return route('blog.tag', $this->slug);
    }

    /**
     * Get all blog IDs that have this tag
     */
    public function getBlogIds()
    {
        return Blog::where('tags', 'LIKE', '%' . $this->id . '%')
                   ->published()
                   ->pluck('id');
    }

    /**
     * Get published blogs count for this tag
     */
    public function getPublishedBlogsCountAttribute()
    {
        return Blog::where('tags', 'LIKE', '%' . $this->id . '%')
                   ->published()
                   ->count();
    }

    /**
     * Get blogs that have this tag
     */
    public function getBlogs()
    {
        return Blog::where('tags', 'LIKE', '%' . $this->id . '%')
                   ->published()
                   ->orderBy('published_at', 'desc')
                   ->get();
    }
}
