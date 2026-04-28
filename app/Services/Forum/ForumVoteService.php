<?php

namespace App\Services\Forum;

use App\Models\ForumVote;
use Illuminate\Database\Eloquent\Model;

class ForumVoteService
{
    /**
     * Toggle vote on a voteable (ForumPost or ForumComment).
     * Returns updated counts.
     */
    public function toggle(Model $voteable, string $voterIdentifier, string $voteType): array
    {
        $existing = $voteable->votes()->where('voter_identifier', $voterIdentifier)->first();

        if ($existing) {
            if ($existing->vote_type === $voteType) {
                // Same vote type → remove (toggle off)
                $existing->delete();
            } else {
                // Different vote type → switch
                $existing->update([
                    'vote_type'  => $voteType,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } else {
            // New vote
            $voteable->votes()->create([
                'voter_identifier' => $voterIdentifier,
                'vote_type'        => $voteType,
                'ip_address'       => request()->ip(),
                'user_agent'       => request()->userAgent(),
            ]);
        }

        $voteable->refresh();

        return [
            'likes_count'    => $voteable->likes_count,
            'dislikes_count' => $voteable->dislikes_count,
        ];
    }

    /**
     * Resolve the voteable model from type + id.
     */
    public function resolveVoteable(string $type, int $id): ?Model
    {
        return match ($type) {
            'post'    => \App\Models\ForumPost::find($id),
            'comment' => \App\Models\ForumComment::find($id),
            default   => null,
        };
    }
}
