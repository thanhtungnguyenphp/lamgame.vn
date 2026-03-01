<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryDraw extends Model
{
    protected $table = 'lottery_draws';

    protected $fillable = [
        'type', 'game', 'region', 'date', 'draw_time',
        'draw_id', 'period', 'status', 'source', 'scraped_at',
    ];

    protected $casts = [
        'date'       => 'date',
        'scraped_at' => 'datetime',
    ];

    public function results()
    {
        return $this->hasMany(LotteryResult::class, 'draw_id');
    }

    public function scopeTraditional($query)
    {
        return $query->where('type', 'traditional');
    }

    public function scopeVietlot($query)
    {
        return $query->where('type', 'vietlot');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('date', $date);
    }

    public function scopeForRegion($query, string $region)
    {
        return $query->where('region', $region);
    }

    public function scopeForGame($query, string $game)
    {
        return $query->where('game', $game);
    }
}
