<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'type',
        'author_name',
        'author_email',
        'author_avatar',
        'category_id',
        'status',
        'is_featured',
        'is_sticky',
        'views_count',
        'comments_count',
        'likes_count',
        'dislikes_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'edit_history',
        'ip_address',
        'user_agent',
        'last_comment_at',
        'last_comment_author',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_sticky' => 'boolean',
        'views_count' => 'integer',
        'comments_count' => 'integer',
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'edit_history' => 'array',
        'last_comment_at' => 'datetime',
    ];

    protected $dates = [
        'last_comment_at',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 160);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if ($post->isDirty('content') && empty($post->excerpt)) {
                $post->excerpt = Str::limit(strip_tags($post->content), 160);
            }
        });
    }

    /**
     * Get the category that owns the post.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'post_id');
    }

    /**
     * Get the published comments for the post.
     */
    public function publishedComments(): HasMany
    {
        return $this->comments()->where('status', 'published');
    }

    /**
     * Get the root comments (no parent) for the post.
     */
    public function rootComments(): HasMany
    {
        return $this->comments()->whereNull('parent_id')->where('status', 'published');
    }

    /**
     * Get the tags for the post.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ForumTag::class, 'forum_post_tags', 'post_id', 'tag_id')->withTimestamps();
    }

    /**
     * Get all of the post's votes.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'voteable');
    }

    /**
     * Get all of the post's likes.
     */
    public function likes(): MorphMany
    {
        return $this->votes()->where('vote_type', 'like');
    }

    /**
     * Get all of the post's dislikes.
     */
    public function dislikes(): MorphMany
    {
        return $this->votes()->where('vote_type', 'dislike');
    }

    /**
     * Scope a query to only include published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include sticky posts.
     */
    public function scopeSticky($query)
    {
        return $query->where('is_sticky', true);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to order by activity (latest comment or creation).
     */
    public function scopeByActivity($query)
    {
        return $query->orderByRaw('COALESCE(last_comment_at, created_at) DESC');
    }

    /**
     * Scope a query to order by popularity (views + comments + likes).
     */
    public function scopePopular($query)
    {
        return $query->orderByRaw('(views_count + comments_count * 3 + likes_count * 2) DESC');
    }

    /**
     * Scope a query to search posts.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the post URL.
     */
    public function getUrlAttribute()
    {
        return route('forum.posts.show', $this->slug);
    }

    /**
     * Get human readable time since post creation.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get human readable time since last activity.
     */
    public function getLastActivityAttribute()
    {
        $lastActivity = $this->last_comment_at ?? $this->created_at;
        return $lastActivity->diffForHumans();
    }

    /**
     * Increment the views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Update comments count and last comment info.
     */
    public function updateCommentStats()
    {
        $latestComment = $this->comments()
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->first();

        $this->update([
            'comments_count' => $this->comments()->where('status', 'published')->count(),
            'last_comment_at' => $latestComment ? $latestComment->created_at : null,
            'last_comment_author' => $latestComment ? $latestComment->author_name : null,
        ]);

        // Update category counts
        if ($this->category) {
            $this->category->updateCounts();
        }
    }

    /**
     * Update vote counts.
     */
    public function updateVoteStats()
    {
        $this->update([
            'likes_count' => $this->likes()->count(),
            'dislikes_count' => $this->dislikes()->count(),
        ]);
    }
}
