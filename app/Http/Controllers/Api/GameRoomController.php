<?php

namespace App\Http\Controllers\Api;

use App\Events\GameMoveEvent;
use App\Http\Controllers\Controller;
use App\Models\GameRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameRoomController extends Controller
{
    private const BOARD_SIZE = 15;
    private const WIN_LENGTH = 5;

    public function create(Request $request): JsonResponse
    {
        $player = $request->input('player', 'Player1');
        $room = GameRoom::create([
            'code' => GameRoom::generateCode(),
            'game_type' => 'caro',
            'player_x' => substr($player, 0, 30),
            'board_state' => array_fill(0, self::BOARD_SIZE, array_fill(0, self::BOARD_SIZE, null)),
            'status' => 'waiting',
        ]);

        return response()->json(['code' => $room->code, 'player' => 'x', 'room' => $room]);
    }

    public function join(string $code, Request $request): JsonResponse
    {
        $room = GameRoom::where('code', $code)->firstOrFail();

        if ($room->status !== 'waiting') {
            return response()->json(['error' => 'Room is not available'], 422);
        }

        $player = $request->input('player', 'Player2');
        $room->update(['player_o' => substr($player, 0, 30), 'status' => 'playing']);

        return response()->json(['code' => $room->code, 'player' => 'o', 'room' => $room->fresh()]);
    }

    public function show(string $code): JsonResponse
    {
        return response()->json(GameRoom::where('code', $code)->firstOrFail());
    }

    public function move(string $code, Request $request): JsonResponse
    {
        $request->validate(['row' => 'required|integer|min:0|max:14', 'col' => 'required|integer|min:0|max:14', 'player' => 'required|in:x,o']);

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

        broadcast(new GameMoveEvent($code, $row, $col, $room->current_turn, $nextTurn, $winner ? $room->current_turn : null, $status));

        return response()->json(['success' => true, 'winner' => $winner ? $room->current_turn : null, 'next_turn' => $nextTurn]);
    }

    private function checkWin(array $board, int $row, int $col, string $player): bool
    {
        $directions = [[0,1],[1,0],[1,1],[1,-1]];
        foreach ($directions as [$dr, $dc]) {
            $count = 1;
            for ($i = 1; $i < self::WIN_LENGTH; $i++) {
                $r = $row + $dr * $i; $c = $col + $dc * $i;
                if ($r < 0 || $r >= self::BOARD_SIZE || $c < 0 || $c >= self::BOARD_SIZE || ($board[$r][$c] ?? null) !== $player) break;
                $count++;
            }
            for ($i = 1; $i < self::WIN_LENGTH; $i++) {
                $r = $row - $dr * $i; $c = $col - $dc * $i;
                if ($r < 0 || $r >= self::BOARD_SIZE || $c < 0 || $c >= self::BOARD_SIZE || ($board[$r][$c] ?? null) !== $player) break;
                $count++;
            }
            if ($count >= self::WIN_LENGTH) return true;
        }
        return false;
    }
}
