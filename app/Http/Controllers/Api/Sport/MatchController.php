<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\SportMatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function live(Request $request)
    {
        $query = SportMatch::live()->with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url', 'league:id,name,logo_url']);
        if ($request->filled('sport')) $query->where('sport_id', $request->sport);

        return response()->json(['data' => $query->orderBy('start_time')->get()]);
    }

    public function schedule(Request $request)
    {
        $request->validate(['date' => 'required|date_format:Y-m-d']);
        $date = $request->date;

        $query = SportMatch::whereDate('start_time', $date)
            ->with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url', 'league:id,name,logo_url']);

        if ($request->filled('sport'))     $query->where('sport_id', $request->sport);
        if ($request->filled('league_id')) $query->where('league_id', $request->league_id);

        $matches = $query->orderBy('start_time')->get()->groupBy('league_id');

        $data = $matches->map(fn ($items, $leagueId) => [
            'league'  => $items->first()->league,
            'matches' => $items->makeHidden('league'),
        ])->values();

        return response()->json(['date' => $date, 'data' => $data]);
    }

    public function results(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $limit = min((int) $request->input('limit', 20), 50);

        $query = SportMatch::finished()->whereDate('start_time', $date)
            ->with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url', 'league:id,name,logo_url']);

        if ($request->filled('sport'))     $query->where('sport_id', $request->sport);
        if ($request->filled('league_id')) $query->where('league_id', $request->league_id);

        $paginated = $query->orderByDesc('start_time')->paginate($limit);

        return response()->json([
            'data'       => $paginated->items(),
            'pagination' => ['page' => $paginated->currentPage(), 'limit' => $paginated->perPage(), 'total' => $paginated->total()],
        ]);
    }

    public function show(string $id)
    {
        $match = SportMatch::with(['homeTeam:id,name,short_name,logo_url', 'awayTeam:id,name,short_name,logo_url', 'league:id,name,logo_url'])
            ->findOrFail($id);
        return response()->json(['data' => $match]);
    }

    public function events(string $id)
    {
        $match = SportMatch::findOrFail($id);
        return response()->json(['data' => $match->events]);
    }

    public function lineups(string $id)
    {
        $match = SportMatch::findOrFail($id);
        $lineups = $match->lineups->keyBy('team_side');

        return response()->json(['data' => [
            'home' => $lineups->get('home'),
            'away' => $lineups->get('away'),
        ]]);
    }

    public function h2h(string $id)
    {
        $match = SportMatch::findOrFail($id);
        $t1 = $match->home_team_id;
        $t2 = $match->away_team_id;

        $history = SportMatch::finished()
            ->where(fn ($q) => $q->where(fn ($q2) => $q2->where('home_team_id', $t1)->where('away_team_id', $t2))
                                 ->orWhere(fn ($q2) => $q2->where('home_team_id', $t2)->where('away_team_id', $t1)))
            ->orderByDesc('start_time')->limit(10)->get();

        $homeWins = $history->filter(fn ($m) => ($m->home_team_id === $t1 && $m->home_score > $m->away_score) || ($m->away_team_id === $t1 && $m->away_score > $m->home_score))->count();
        $awayWins = $history->filter(fn ($m) => ($m->home_team_id === $t2 && $m->home_score > $m->away_score) || ($m->away_team_id === $t2 && $m->away_score > $m->home_score))->count();
        $draws = $history->filter(fn ($m) => $m->home_score === $m->away_score)->count();

        return response()->json(['data' => [
            'total_matches' => $history->count(),
            'home_wins'     => $homeWins,
            'away_wins'     => $awayWins,
            'draws'         => $draws,
            'recent_matches' => $history,
        ]]);
    }
}
