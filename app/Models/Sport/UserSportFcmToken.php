<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class UserSportFcmToken extends Model
{
    protected $fillable = ['user_sport_profile_id', 'token', 'platform'];

    public function profile() { return $this->belongsTo(UserSportProfile::class, 'user_sport_profile_id'); }
}
