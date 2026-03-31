<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class SportMatch extends Model
{
    protected $table = 'sport_matches';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'home_team_id', 'away_team_id', 'league_id', 'sport_id', 'status', 'minute', 'period', 'home_score', 'away_score', 'start_time', 'venue', 'referee', 'stats'];
    protected $casts = ['start_time' => 'datetime', 'stats' => 'array'];

    public function homeTeam() { return $this->belongsTo(Team::class, 'home_team_id'); }
    public function awayTeam() { return $this->belongsTo(Team::class, 'away_team_id'); }
    public function league() { return $this->belongsTo(League::class); }
    public function sport() { return $this->belongsTo(Sport::class); }
    public function events() { return $this->hasMany(MatchEvent::class, 'match_id')->orderBy('minute'); }
    public function lineups() { return $this->hasMany(MatchLineup::class, 'match_id'); }

    public function scopeLive($q) { return $q->whereIn('status', ['live', 'halftime']); }
    public function scopeFinished($q) { return $q->where('status', 'finished'); }
    public function scopeScheduled($q) { return $q->where('status', 'scheduled'); }
}
