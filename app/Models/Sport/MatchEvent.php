<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    protected $fillable = ['match_id', 'type', 'minute', 'extra_minute', 'team_side', 'player_name', 'player_id', 'assist_name', 'assist_id', 'player_in_name', 'player_out_name', 'detail'];

    public function match() { return $this->belongsTo(SportMatch::class, 'match_id'); }
}
