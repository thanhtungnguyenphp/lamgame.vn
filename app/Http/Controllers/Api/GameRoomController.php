<?php

namespace App\Http\Controllers\Api;

use App\Events\GameMoveEvent;
use App\Http\Controllers\Controller;
use App\Models\GameRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GameRoomController extends Controller
{
    private const BOARD_SIZE = 15;
    private const WIN_LENGTH = 5;
    private const MATCHMAKING_KEY = 'game:matchmaking:caro';
    private const ROOM_TTL = 1800; // 30 minutes

    /**
     * POST /api/games/rooms — Create room or auto-matchmake
     */
    public function create(Request $request): JsonResponse
    {
        $player = substr($request->input('player', 'Player' . rand(100, 999)), 0, 30);
        $mode = $request->input('mode', 'create'); // 'create' or 'matchmake'

        if ($mode === 'matchmake') {
            return $this->matchmake($player);
        }

        $room = GameRoom::create([
            'code' => GameRoom::generateCode(),
            'game_type' => 'caro',
            'player_x' => $player,
            'board_state' => array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, null)),
            'status' => 'waiting',
        ]);

        // Set TTL for auto-expiry
        Redis::setex('game:room:' . $room->code, self::ROOM_TTL, '1');

        return response()->json(['code' => $room->code, 'player' => 'x', 'room' => $room]);
    }

    /**
     * Auto-matchmaking: find waiting room or create new one
     */
    private function matchmake(string $player): JsonResponse
    {
        // Try to find a waiting room
        $room = GameRoom::where('status', 'waiting')
            ->where('game_type', 'caro')
            ->where('updated_at', '>', now()->subMinutes(5))
            ->first();

        if ($room) {
            $room->update(['player_o' => $player, 'status' => 'playing']);
            Redis::setex('game:room:' . $room->code, self::ROOM_TTL, '1');
            broadcast(new GameMoveEvent($room->code, -1, -1, 'system', 'x', null, 'playing'));
            return response()->json(['code' => $room->code, 'player' => 'o', 'room' => $room->fresh(), 'matched' => true]);
        }

        // No waiting room → create new and wait
        $room = GameRoom::create([
            'code' => GameRoom::generateCode(),
            'game_type' => 'caro',
            'player_x' => $player,
            'board_state' => array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, null)),
            'status' => 'waiting',
        ]);
        Redis::setex('game:room:' . $room->code, self::ROOM_TTL, '1');

        return response()->json(['code' => $room->code, 'player' => 'x', 'room' => $room, 'matched' => false]);
    }

    /**
     * POST /api/games/rooms/{code}/join
     */
    public function join(string $code, Request $request): JsonResponse
    {
        $room = GameRoom::where('code', $code)->firstOrFail();

        if ($room->status !== 'waiting') {
            return response()->json(['error' => 'Room is not available'], 422);
        }

        $player = substr($request->input('player', 'Player2'), 0, 30);
        $room->update(['player_o' => $player, 'status' => 'playing']);
        Redis::setex('game:room:' . $room->code, self::ROOM_TTL, '1');

        // Notify player X that opponent joined
        broadcast(new GameMoveEvent($code, -1, -1, 'system', 'x', null, 'playing'));

        return response()->json(['code' => $room->code, 'player' => 'o', 'room' => $room->fresh()]);
    }

    /**
     * GET /api/games/rooms/{code}
     */
    public function show(string $code): JsonResponse
    {
        $room = GameRoom::where('code', $code)->firstOrFail();

        // Check expiry
        if (!Redis::exists('game:room:' . $code) && $room->status !== 'finished') {
            $room->update(['status' => 'finished', 'winner' => 'expired']);
            return response()->json(['error' => 'Room expired', 'status' => 'finished'], 410);
        }

        return response()->json($room);
    }

    /**
     * POST /api/games/rooms/{code}/move
     */
    public function move(string $code, Request $request): JsonResponse
    {
        $request->validate([
            'row' => 'required|integer|min:0|max:14',
            'col' => 'required|integer|min:0|max:14',
            'player' => 'required|in:x,o',
        ]);

        $room = GameRoom::where('code', $code)->firstOrFail();

        if ($room->status !== 'playing') {
            return response()->json(['error' => 'Game not in progress'], 422);
        }
        if ($room->current_turn !== $request->input('player')) {
            return response()->json(['error' => 'Not your turn'], 422);
        }

        $row = $request->input('row');
        $col = $request->input('col');
        $board = $room->board_state;

        if ($board[$row][$col] !== null) {
            return response()->json(['error' => 'Cell occupied'], 422);
        }

        $board[$row][$col] = $room->current_turn;
        $nextTurn = $room->current_turn === 'x' ? 'o' : 'x';
        $winner = $this->checkWin($board, $row, $col, $room->current_turn);
        $status = $winner ? 'finished' : 'playing';

        $room->update([
            'board_state' => $board,
            'current_turn' => $nextTurn,
            'winner' => $winner ? $room->current_turn : null,
            'status' => $status,
        ]);

        // Refresh TTL on activity
        Redis::setex('game:room:' . $code, self::ROOM_TTL, '1');

        broadcast(new GameMoveEvent($code, $row, $col, $room->current_turn, $nextTurn, $winner ? $room->current_turn : null, $status));

        return response()->json(['success' => true, 'winner' => $winner ? $room->current_turn : null, 'next_turn' => $nextTurn]);
    }

    /**
     * POST /api/games/rooms/{code}/rematch
     */
    public function rematch(string $code, Request $request): JsonResponse
    {
        $oldRoom = GameRoom::where('code', $code)->firstOrFail();

        if ($oldRoom->status !== 'finished') {
            return response()->json(['error' => 'Game not finished'], 422);
        }

        // Swap X/O for fairness
        $room = GameRoom::create([
            'code' => GameRoom::generateCode(),
            'game_type' => 'caro',
            'player_x' => $oldRoom->player_o,
            'player_o' => $oldRoom->player_x,
            'board_state' => array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, null)),
            'status' => 'playing',
            'current_turn' => 'x',
        ]);
        Redis::setex('game:room:' . $room->code, self::ROOM_TTL, '1');

        // Notify old room about rematch
        broadcast(new GameMoveEvent($code, -1, -1, 'rematch', 'x', null, 'rematch:' . $room->code));

        return response()->json(['code' => $room->code, 'room' => $room]);
    }

    private function checkWin(array $board, int $row, int $col, string $player): bool
    {
        $directions = [[0,1],[1,0],[1,1],[1,-1]];
        foreach ($directions as [$dr, $dc]) {
            $count = 1;
            for ($i = 1; $i < self::WIN_LENGTH; $i++) {
                $r = $row + $dr * $i;
                $c = $col + $dc * $i;
                if ($r < 0 || $r >= self::BOARD_SIZE || $c < 0 || $c >= self::BOARD_SIZE || ($board[$r][$c] ?? null) !== $player) break;
                $count++;
            }
            for ($i = 1; $i < self::WIN_LENGTH; $i++) {
                $r = $row - $dr * $i;
                $c = $col - $dc * $i;
                if ($r < 0 || $r >= self::BOARD_SIZE || $c < 0 || $c >= self::BOARD_SIZE || ($board[$r][$c] ?? null) !== $player) break;
                $count++;
            }
            if ($count >= self::WIN_LENGTH) return true;
        }
        return false;
    }
}
