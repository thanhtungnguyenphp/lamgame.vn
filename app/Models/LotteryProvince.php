<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryProvince extends Model
{
    protected $table = 'lottery_provinces';

    protected $fillable = ['code', 'name', 'region', 'sort_order'];

    public function schedules()
    {
        return $this->hasMany(LotterySchedule::class, 'province_id');
    }

    public function results()
    {
        return $this->hasMany(LotteryResult::class, 'province_id');
    }

    public function scopeByRegion($query, string $region)
    {
        return $query->where('region', $region);
    }
}
