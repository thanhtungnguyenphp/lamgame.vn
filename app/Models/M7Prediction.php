<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M7Prediction extends Model
{
    protected $table = 'm7_predictions';

    protected $fillable = ['landing_page_id', 'user_id', 'match_id', 'type', 'pick', 'correct', 'points'];

    protected $casts = ['correct' => 'boolean', 'points' => 'integer'];

    public function match() { return $this->belongsTo(M7Match::class, 'match_id'); }

    public function scopeForPage($query, $pageId) { return $query->where('landing_page_id', $pageId); }
}
