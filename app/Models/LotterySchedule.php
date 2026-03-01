<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotterySchedule extends Model
{
    protected $table = 'lottery_schedules';

    protected $fillable = ['province_id', 'day_of_week'];

    public function province()
    {
        return $this->belongsTo(LotteryProvince::class, 'province_id');
    }

    public function scopeByDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }
}
