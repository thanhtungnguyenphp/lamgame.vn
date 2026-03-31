<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'sport_id', 'country', 'logo_url', 'season', 'is_active', 'order'];
    protected $casts = ['is_active' => 'boolean'];

    public function sport() { return $this->belongsTo(Sport::class); }
    public function teams() { return $this->belongsToMany(Team::class, 'league_team'); }
    public function standings() { return $this->hasMany(Standing::class)->orderBy('rank'); }
    public function matches() { return $this->hasMany(SportMatch::class, 'league_id'); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
