<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class UserSportReminder extends Model
{
    protected $fillable = ['user_sport_profile_id', 'match_id', 'remind_before_minutes', 'sent'];
    protected $casts = ['sent' => 'boolean'];

    public function profile() { return $this->belongsTo(UserSportProfile::class, 'user_sport_profile_id'); }
    public function match() { return $this->belongsTo(SportMatch::class, 'match_id'); }
}
