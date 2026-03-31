<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'short_name', 'logo_url', 'sport_id', 'country', 'venue', 'founded'];

    public function sport() { return $this->belongsTo(Sport::class); }
    public function leagues() { return $this->belongsToMany(League::class, 'league_team'); }
}
