<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryResult extends Model
{
    protected $table = 'lottery_results';

    protected $fillable = ['draw_id', 'province_id', 'prize_data', 'jackpot_data', 'stats_data'];

    protected $casts = [
        'prize_data'   => 'array',
        'jackpot_data' => 'array',
        'stats_data'   => 'array',
    ];

    public function draw()
    {
        return $this->belongsTo(LotteryDraw::class, 'draw_id');
    }

    public function province()
    {
        return $this->belongsTo(LotteryProvince::class, 'province_id');
    }
}
