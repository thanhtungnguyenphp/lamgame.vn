<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    protected $fillable = ['code', 'game_type', 'player_x', 'player_o', 'board_state', 'status', 'current_turn', 'winner'];

    protected $casts = [
        'board_state' => 'array',
    ];

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 6));
        } while (static::where('code', $code)->exists());
        return $code;
    }
}
