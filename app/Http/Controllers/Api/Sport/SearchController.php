<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\Team;
use App\Models\Sport\League;
use App\Models\Sport\SportMatch;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['q' => 'required|string|min:2|max:100']);
        $q = $request->q;
        $type = $request->input('type', 'all');

        $teams = ($type === 'all' || $type === 'team')
            ? Team::where('name', 'LIKE', "%{$q}%")->limit(10)->get(['id', 'name', 'logo_url', 'sport_id'])
            : [];

        $leagues = ($type === 'all' || $type === 'league')
            ? League::where('name', 'LIKE', "%{$q}%")->limit(10)->get(['id', 'name', 'logo_url', 'sport_id'])
            : [];

        $matches = [];
        if ($type === 'all' || $type === 'match') {
            $teamIds = Team::where('name', 'LIKE', "%{$q}%")->pluck('id');
            if ($teamIds->isNotEmpty()) {
                $matches = SportMatch::where(fn ($qb) => $qb->whereIn('home_team_id', $teamIds)->orWhereIn('away_team_id', $teamIds))
                    ->with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url'])
                    ->orderByDesc('start_time')->limit(10)->get();
            }
        }

        return response()->json(['data' => compact('teams', 'leagues', 'matches')]);
    }
}
