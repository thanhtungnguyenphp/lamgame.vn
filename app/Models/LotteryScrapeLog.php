<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryScrapeLog extends Model
{
    protected $table = 'lottery_scrape_logs';

    public $timestamps = false;

    protected $fillable = ['source', 'url', 'status', 'response_time_ms', 'error_message', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];
}
