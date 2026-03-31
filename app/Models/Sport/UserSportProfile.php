<?php
namespace App\Models\Sport;
use Illuminate\Database\Eloquent\Model;

class UserSportProfile extends Model
{
    protected $fillable = ['firebase_uid', 'display_name', 'email', 'photo_url', 'favorite_teams', 'favorite_sports', 'notification_settings', 'is_premium'];
    protected $casts = ['favorite_teams' => 'array', 'favorite_sports' => 'array', 'notification_settings' => 'array', 'is_premium' => 'boolean'];

    public function reminders() { return $this->hasMany(UserSportReminder::class); }
    public function fcmTokens() { return $this->hasMany(UserSportFcmToken::class); }
}
