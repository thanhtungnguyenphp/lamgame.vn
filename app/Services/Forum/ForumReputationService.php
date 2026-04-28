<?php

namespace App\Services\Forum;

use App\Models\ForumReputationLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ForumReputationService
{
    const POINTS = [
        'post_created'    => 10,
        'comment_created' => 5,
        'vote_like'       => 2,
        'vote_dislike'    => -1,
        'best_answer'     => 15,
        'post_removed'    => -10,
    ];

    const BADGES = [
        ['min' => 1000, 'name' => 'Legend',      'icon' => '👑'],
        ['min' => 500,  'name' => 'Expert',      'icon' => '⭐'],
        ['min' => 200,  'name' => 'Contributor', 'icon' => '🔥'],
        ['min' => 50,   'name' => 'Active',      'icon' => '⚡'],
        ['min' => 0,    'name' => 'Newcomer',    'icon' => '🌱'],
    ];

    public function award(int $customerId, string $action, ?Model $reference = null): void
    {
        $points = self::POINTS[$action] ?? 0;
        if (!$points || !$customerId) return;

        ForumReputationLog::create([
            'customer_id'    => $customerId,
            'points'         => $points,
            'action'         => $action,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id'   => $reference?->id,
        ]);

        $customerModel = \Webkul\Customer\Models\CustomerProxy::modelClass();
        $customerModel::where('id', $customerId)->increment('reputation', $points);
    }

    public function getBadge(int $reputation): array
    {
        foreach (self::BADGES as $badge) {
            if ($reputation >= $badge['min']) return $badge;
        }
        return end(self::BADGES);
    }

    public function getLeaderboard(string $period = 'all', int $limit = 20): \Illuminate\Support\Collection
    {
        $customerModel = \Webkul\Customer\Models\CustomerProxy::modelClass();

        if ($period === 'all') {
            return $customerModel::where('reputation', '>', 0)
                ->orderByDesc('reputation')
                ->limit($limit)
                ->get(['id', 'first_name', 'last_name', 'email', 'image', 'reputation']);
        }

        // Monthly leaderboard from logs
        $startDate = now()->startOfMonth();

        return ForumReputationLog::select('customer_id', DB::raw('SUM(points) as month_points'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('customer_id')
            ->orderByDesc('month_points')
            ->limit($limit)
            ->with('customer:id,first_name,last_name,email,image,reputation')
            ->get();
    }
}
