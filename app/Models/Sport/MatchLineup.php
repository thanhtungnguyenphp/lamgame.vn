<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class MatchLineup extends Model
{
    protected $fillable = ['match_id', 'team_side', 'formation', 'starting', 'substitutes'];
    protected $casts = ['starting' => 'array', 'substitutes' => 'array'];

    public function match() { return $this->belongsTo(SportMatch::class, 'match_id'); }
}
