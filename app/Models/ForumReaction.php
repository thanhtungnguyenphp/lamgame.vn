<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumReaction extends Model
{
    protected $fillable = ['reactable_type', 'reactable_id', 'customer_id', 'voter_identifier', 'type'];

    public function reactable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function toggle(string $reactableType, int $reactableId, string $voterIdentifier, string $type, ?int $customerId = null): array
    {
        $existing = static::where('reactable_type', $reactableType)
            ->where('reactable_id', $reactableId)
            ->where('voter_identifier', $voterIdentifier)
            ->first();

        if ($existing) {
            if ($existing->type === $type) {
                $existing->delete();
                $action = 'removed';
            } else {
                $existing->update(['type' => $type]);
                $action = 'changed';
            }
        } else {
            static::create([
                'reactable_type' => $reactableType,
                'reactable_id' => $reactableId,
                'voter_identifier' => $voterIdentifier,
                'customer_id' => $customerId,
                'type' => $type,
            ]);
            $action = 'added';
        }

        $counts = static::where('reactable_type', $reactableType)
            ->where('reactable_id', $reactableId)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return ['action' => $action, 'counts' => $counts, 'total' => array_sum($counts)];
    }
}
