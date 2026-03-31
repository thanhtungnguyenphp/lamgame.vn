<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    protected $table = 'sport_highlights';
    protected $fillable = ['title', 'thumbnail_url', 'video_url', 'duration', 'view_count', 'sport_id', 'match_id', 'league_id'];

    public function sport() { return $this->belongsTo(Sport::class); }
    public function match() { return $this->belongsTo(SportMatch::class, 'match_id'); }
    public function league() { return $this->belongsTo(League::class); }
}
