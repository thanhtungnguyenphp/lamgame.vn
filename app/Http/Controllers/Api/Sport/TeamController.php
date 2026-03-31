<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\Team;
use App\Models\Sport\SportMatch;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function show(string $id)
    {
        $team = Team::with('leagues:id,name,logo_url')->findOrFail($id);
        return response()->json(['data' => $team]);
    }

    public function matches(Request $request, string $id)
    {
        Team::findOrFail($id);
        $limit = min((int) $request->input('limit', 20), 50);
        $status = $request->input('status', 'all');

        $query = SportMatch::where(fn ($q) => $q->where('home_team_id', $id)->orWhere('away_team_id', $id))
            ->with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url', 'league:id,name,logo_url']);

        if ($status === 'scheduled') $query->scheduled();
        elseif ($status === 'finished') $query->finished();

        $paginated = $query->orderByDesc('start_time')->paginate($limit);

        return response()->json([
            'data'       => $paginated->items(),
            'pagination' => ['page' => $paginated->currentPage(), 'limit' => $paginated->perPage(), 'total' => $paginated->total()],
        ]);
    }
}
