<?php

namespace App\Models\Sport;

use Illuminate\Database\Eloquent\Model;

class SportCrawlLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['crawler', 'source', 'status', 'items_fetched', 'items_created', 'items_updated', 'items_skipped', 'error_message', 'duration_ms'];
}
