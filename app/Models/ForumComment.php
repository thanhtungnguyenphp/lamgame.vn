<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'parent_id',
        'content',
        'author_name',
        'author_email',
        'author_avatar',
        'author_website',
        'status',
        'likes_count',
        'dislikes_count',
        'replies_count',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'likes_count' => 'integer',
        'dislikes_count' => 'integer',
        'replies_count' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            // Update post comment stats
            if ($comment->post) {
                $comment->post->updateCommentStats();
            }

            // Update parent comment replies count
            if ($comment->parent) {
                $comment->parent->updateRepliesCount();
            }
        });

        static::deleted(function ($comment) {
            // Update post comment stats
            if ($comment->post) {
                $comment->post->updateCommentStats();
            }

            // Update parent comment replies count
            if ($comment->parent) {
                $comment->parent->updateRepliesCount();
            }
        });
    }

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'post_id');
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumComment::class, 'parent_id');
    }

    /**
     * Get the child comments.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumComment::class, 'parent_id');
    }

    /**
     * Get published replies.
     */
    public function publishedReplies(): HasMany
    {
        return $this->replies()->where('status', 'published')->orderBy('created_at');
    }

    /**
     * Get all descendants (replies and their replies).
     */
    public function descendants(): HasMany
    {
        return $this->replies()->with('descendants');
    }

    /**
     * Get all of the comment's votes.
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'voteable');
    }

    /**
     * Get all of the comment's likes.
     */
    public function likes(): MorphMany
    {
        return $this->votes()->where('vote_type', 'like');
    }

    /**
     * Get all of the comment's dislikes.
     */
    public function dislikes(): MorphMany
    {
        return $this->votes()->where('vote_type', 'dislike');
    }

    /**
     * Scope a query to only include published comments.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include root comments (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to order by latest first.
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by oldest first.
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Get human readable time since comment creation.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get the comment depth level.
     */
    public function getDepthAttribute()
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Check if this comment is a reply to another comment.
     */
    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if this comment has replies.
     */
    public function hasReplies()
    {
        return $this->replies_count > 0;
    }

    /**
     * Update replies count.
     */
    public function updateRepliesCount()
    {
        $this->update([
            'replies_count' => $this->replies()->where('status', 'published')->count(),
        ]);
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

    /**
     * Get avatar URL.
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->author_avatar) {
            return $this->author_avatar;
        }

        // Generate Gravatar URL based on email
        if ($this->author_email) {
            $hash = md5(strtolower(trim($this->author_email)));
            return "https://www.gravatar.com/avatar/{$hash}?s=40&d=identicon";
        }

        // Default avatar
        return "https://ui-avatars.com/api/?name=" . urlencode($this->author_name) . "&size=40&background=667eea&color=fff";
    }
}
