<?php

namespace App\Services\Forum;

use App\Models\ForumVote;
use Illuminate\Database\Eloquent\Model;

class ForumVoteService
{
    public function __construct(
        protected ForumReputationService $reputationService,
    ) {}

    /**
     * Toggle vote on a voteable (ForumPost or ForumComment).
     * Returns updated counts.
     */
    public function toggle(Model $voteable, string $voterIdentifier, string $voteType): array
    {
        $existing = $voteable->votes()->where('voter_identifier', $voterIdentifier)->first();

        if ($existing) {
            if ($existing->vote_type === $voteType) {
                $existing->delete();
            } else {
                $existing->update([
                    'vote_type'  => $voteType,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } else {
            $voteable->votes()->create([
                'voter_identifier' => $voterIdentifier,
                'vote_type'        => $voteType,
                'ip_address'       => request()->ip(),
                'user_agent'       => request()->userAgent(),
            ]);

            // Award reputation to content author for new votes
            $authorId = $voteable->customer_id ?? null;
            if ($authorId) {
                $action = $voteType === 'like' ? 'vote_like' : 'vote_dislike';
                $this->reputationService->award($authorId, $action, $voteable);
            }
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
