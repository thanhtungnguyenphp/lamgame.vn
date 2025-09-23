<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'voteable_type',
        'voteable_id',
        'voter_identifier',
        'vote_type',
        'ip_address',
        'user_agent',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($vote) {
            // Update vote counts on the voteable model
            if ($vote->voteable) {
                $vote->voteable->updateVoteStats();
            }
        });

        static::deleted(function ($vote) {
            // Update vote counts on the voteable model
            if ($vote->voteable) {
                $vote->voteable->updateVoteStats();
            }
        });
    }

    /**
     * Get the owning voteable model.
     */
    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include likes.
     */
    public function scopeLikes($query)
    {
        return $query->where('vote_type', 'like');
    }

    /**
     * Scope a query to only include dislikes.
     */
    public function scopeDislikes($query)
    {
        return $query->where('vote_type', 'dislike');
    }

    /**
     * Scope a query to filter by voter.
     */
    public function scopeByVoter($query, $identifier)
    {
        return $query->where('voter_identifier', $identifier);
    }

    /**
     * Check if this is a like vote.
     */
    public function isLike()
    {
        return $this->vote_type === 'like';
    }

    /**
     * Check if this is a dislike vote.
     */
    public function isDislike()
    {
        return $this->vote_type === 'dislike';
    }
}
