<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\League;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LeagueController extends Controller
{
    public function index(Request $request)
    {
        $query = League::active();
        if ($request->filled('sport'))   $query->where('sport_id', $request->sport);
        if ($request->filled('country')) $query->where('country', $request->country);
        if ($request->filled('season'))  $query->where('season', $request->season);

        return response()->json(['data' => $query->orderBy('order')->get()]);
    }

    public function standings(string $leagueId)
    {
        $league = League::findOrFail($leagueId);
        $standings = $league->standings()->with('team:id,name,short_name,logo_url')->get();

        return response()->json([
            'league' => ['id' => $league->id, 'name' => $league->name, 'season' => $league->season],
            'data'   => $standings,
        ]);
    }

    public function topScorers(string $leagueId)
    {
        // Placeholder — sẽ implement khi có data source
        return response()->json(['data' => []]);
    }
}
