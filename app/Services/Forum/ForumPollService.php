<?php

namespace App\Services\Forum;

use App\Models\ForumPoll;
use App\Models\ForumPollVote;
use Illuminate\Support\Facades\DB;

class ForumPollService
{
    public function createPoll(int $postId, array $data): ForumPoll
    {
        $poll = ForumPoll::create([
            'forum_post_id' => $postId,
            'question' => $data['question'],
            'allow_multiple' => $data['allow_multiple'] ?? false,
            'is_anonymous' => $data['is_anonymous'] ?? false,
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        foreach ($data['options'] as $i => $text) {
            $poll->options()->create(['text' => $text, 'sort_order' => $i]);
        }

        return $poll->load('options');
    }

    public function vote(int $pollId, int $customerId, array $optionIds): array
    {
        $poll = ForumPoll::with('options')->findOrFail($pollId);

        if ($poll->isExpired()) {
            return ['error' => 'Poll đã hết hạn'];
        }

        if (!$poll->allow_multiple && count($optionIds) > 1) {
            return ['error' => 'Chỉ được chọn 1 option'];
        }

        // Check already voted (single vote mode)
        if (!$poll->allow_multiple) {
            $existing = ForumPollVote::where('forum_poll_id', $pollId)
                ->where('customer_id', $customerId)->exists();
            if ($existing) return ['error' => 'Bạn đã vote rồi'];
        }

        DB::transaction(function () use ($poll, $customerId, $optionIds) {
            foreach ($optionIds as $optionId) {
                ForumPollVote::firstOrCreate([
                    'forum_poll_id' => $poll->id,
                    'forum_poll_option_id' => $optionId,
                    'customer_id' => $customerId,
                ]);
                $poll->options()->where('id', $optionId)->increment('vote_count');
            }
            $poll->increment('total_votes');
        });

        return ['success' => true, 'poll' => $poll->fresh()->load('options')];
    }

    public function retractVote(int $pollId, int $customerId): array
    {
        $poll = ForumPoll::findOrFail($pollId);
        if ($poll->isExpired()) return ['error' => 'Poll đã hết hạn'];

        $votes = ForumPollVote::where('forum_poll_id', $pollId)
            ->where('customer_id', $customerId)->get();

        if ($votes->isEmpty()) return ['error' => 'Chưa vote'];

        DB::transaction(function () use ($poll, $votes) {
            foreach ($votes as $vote) {
                $poll->options()->where('id', $vote->forum_poll_option_id)->decrement('vote_count');
                $vote->delete();
            }
            $poll->decrement('total_votes', $votes->count());
        });

        return ['success' => true];
    }

    public function getResults(int $pollId): array
    {
        $poll = ForumPoll::with('options')->findOrFail($pollId);

        return [
            'id' => $poll->id,
            'question' => $poll->question,
            'total_votes' => $poll->total_votes,
            'is_expired' => $poll->isExpired(),
            'options' => $poll->options->map(fn ($o) => [
                'id' => $o->id,
                'text' => $o->text,
                'vote_count' => $o->vote_count,
                'percentage' => $poll->total_votes > 0 ? round($o->vote_count / $poll->total_votes * 100, 1) : 0,
            ]),
        ];
    }
}
