<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class Standing extends Model
{
    protected $fillable = ['league_id', 'team_id', 'rank', 'played', 'won', 'drawn', 'lost', 'goals_for', 'goals_against', 'goal_difference', 'points', 'form'];
    protected $casts = ['form' => 'array'];

    public function league() { return $this->belongsTo(League::class); }
    public function team() { return $this->belongsTo(Team::class); }
}
