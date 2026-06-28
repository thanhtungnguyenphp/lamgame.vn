<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class GameLeaderboardController extends Controller
{
    private const LEADERBOARD_PREFIX = 'game:leaderboard:';
    private const MAX_ENTRIES = 100;

    /**
     * GET /api/games/{gameKey}/leaderboard
     */
    public function index(string $gameKey, Request $request): JsonResponse
    {
        $limit = min((int) $request->get('limit', 20), self::MAX_ENTRIES);
        $key = self::LEADERBOARD_PREFIX . $gameKey;

        $scores = Redis::zrevrange($key, 0, $limit - 1, 'WITHSCORES');

        $leaderboard = [];
        $rank = 1;
        foreach ($scores as $player => $score) {
            $leaderboard[] = [
                'rank' => $rank++,
                'player' => $player,
                'score' => (int) $score,
            ];
        }

        return response()->json([
            'game' => $gameKey,
            'total' => Redis::zcard($key),
            'data' => $leaderboard,
        ]);
    }

    /**
     * POST /api/games/{gameKey}/leaderboard
     */
    public function store(string $gameKey, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'player' => 'required|string|max:30',
            'score' => 'required|integer|min:0|max:999999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $player = $request->input('player');
        $score = (int) $request->input('score');
        $key = self::LEADERBOARD_PREFIX . $gameKey;

        // Only update if new score is higher
        $current = Redis::zscore($key, $player);
        if ($current === null || $score > (int) $current) {
            Redis::zadd($key, $score, $player);
        }

        // Trim to MAX entries
        $total = Redis::zcard($key);
        if ($total > self::MAX_ENTRIES) {
            Redis::zremrangebyrank($key, 0, $total - self::MAX_ENTRIES - 1);
        }

        $rank = Redis::zrevrank($key, $player);

        return response()->json([
            'player' => $player,
            'score' => $score,
            'rank' => $rank !== null ? $rank + 1 : null,
            'is_new_high' => $current === null || $score > (int) $current,
        ]);
    }

    /**
     * GET /api/games/{gameKey}/leaderboard/player/{player}
     */
    public function player(string $gameKey, string $player): JsonResponse
    {
        $key = self::LEADERBOARD_PREFIX . $gameKey;
        $score = Redis::zscore($key, $player);

        if ($score === null) {
            return response()->json(['error' => 'Player not found'], 404);
        }

        $rank = Redis::zrevrank($key, $player);

        return response()->json([
            'player' => $player,
            'score' => (int) $score,
            'rank' => $rank + 1,
        ]);
    }
}
