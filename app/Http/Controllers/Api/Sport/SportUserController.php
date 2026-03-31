<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\UserSportProfile;
use App\Models\Sport\UserSportReminder;
use App\Models\Sport\UserSportFcmToken;
use Illuminate\Http\Request;

class SportUserController extends Controller
{
    public function profile(Request $request)
    {
        $profile = $this->resolveProfile($request);
        return response()->json(['data' => $profile]);
    }

    public function updateFavorites(Request $request)
    {
        $request->validate([
            'favorite_teams'  => 'nullable|array',
            'favorite_sports' => 'nullable|array',
        ]);

        $profile = $this->resolveProfile($request);
        $profile->update($request->only('favorite_teams', 'favorite_sports'));

        return response()->json(['success' => true]);
    }

    public function updateNotificationSettings(Request $request)
    {
        $request->validate([
            'live_score'          => 'nullable|boolean',
            'match_reminder'      => 'nullable|boolean',
            'highlights'          => 'nullable|boolean',
            'favorite_teams_only' => 'nullable|boolean',
        ]);

        $profile = $this->resolveProfile($request);
        $profile->update(['notification_settings' => $request->all()]);

        return response()->json(['success' => true]);
    }

    public function storeReminder(Request $request)
    {
        $request->validate([
            'match_id'              => 'required|string|exists:sport_matches,id',
            'remind_before_minutes' => 'nullable|integer|min:1|max:60',
        ]);

        $profile = $this->resolveProfile($request);
        $reminder = $profile->reminders()->updateOrCreate(
            ['match_id' => $request->match_id],
            ['remind_before_minutes' => $request->input('remind_before_minutes', 15)]
        );

        return response()->json(['success' => true, 'data' => $reminder], 201);
    }

    public function destroyReminder(Request $request, string $matchId)
    {
        $profile = $this->resolveProfile($request);
        $profile->reminders()->where('match_id', $matchId)->delete();

        return response()->json(['success' => true]);
    }

    public function reminders(Request $request)
    {
        $profile = $this->resolveProfile($request);
        $reminders = $profile->reminders()->with('match.homeTeam:id,name,short_name,logo_url', 'match.awayTeam:id,name,short_name,logo_url')->get();

        return response()->json(['data' => $reminders]);
    }

    public function registerFcmToken(Request $request)
    {
        $request->validate([
            'token'    => 'required|string|max:500',
            'platform' => 'nullable|string|in:android,ios',
        ]);

        $profile = $this->resolveProfile($request);
        $profile->fcmTokens()->updateOrCreate(
            ['token' => $request->token],
            ['platform' => $request->input('platform', 'android')]
        );

        return response()->json(['success' => true]);
    }

    public function deleteAccount(Request $request)
    {
        $profile = $this->resolveProfile($request);
        $profile->delete();

        return response()->json(['success' => true]);
    }

    private function resolveProfile(Request $request): UserSportProfile
    {
        return UserSportProfile::firstOrCreate(
            ['firebase_uid' => $request->firebase_uid],
            ['notification_settings' => ['live_score' => true, 'match_reminder' => true, 'highlights' => true, 'favorite_teams_only' => false]]
        );
    }
}
