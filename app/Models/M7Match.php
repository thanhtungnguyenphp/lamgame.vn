<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M7Match extends Model
{
    protected $table = 'm7_matches';

    protected $fillable = ['landing_page_id', 'round', 'team_a', 'team_b', 'winner', 'match_at', 'status'];

    protected $casts = ['match_at' => 'datetime', 'status' => 'integer'];

    public function isUpcoming(): bool { return $this->status === 0; }
    public function isFinished(): bool { return $this->status === 2; }

    public function scopeForPage($query, $pageId) { return $query->where('landing_page_id', $pageId); }
}
