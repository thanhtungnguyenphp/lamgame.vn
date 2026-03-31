<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'icon', 'order'];

    public function leagues() { return $this->hasMany(League::class); }
    public function teams() { return $this->hasMany(Team::class); }
}
