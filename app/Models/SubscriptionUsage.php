<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionUsage extends Model
{
    protected $fillable = ['user_id', 'feature', 'used', 'period'];

    /**
     * Tăng usage cho 1 feature. Return false nếu đã hết quota.
     */
    public static function incrementUsage(int $userId, string $feature, int $limit): bool
    {
        // -1 = unlimited
        if ($limit === -1) return true;
        if ($limit === 0) return false;

        $period = now()->format('Y-m');

        $usage = static::firstOrCreate(
            ['user_id' => $userId, 'feature' => $feature, 'period' => $period],
            ['used' => 0]
        );

        if ($usage->used >= $limit) {
            return false;
        }

        $usage->increment('used');
        return true;
    }

    /**
     * Lấy usage hiện tại cho 1 feature.
     */
    public static function getUsed(int $userId, string $feature): int
    {
        return static::where('user_id', $userId)
            ->where('feature', $feature)
            ->where('period', now()->format('Y-m'))
            ->value('used') ?? 0;
    }
}
