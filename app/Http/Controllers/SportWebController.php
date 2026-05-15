<?php

namespace App\Http\Controllers;

use App\Models\Sport\League;
use App\Models\Sport\SportArticle;
use App\Models\Sport\SportMatch;
use App\Models\Sport\Standing;
use App\Models\Sport\Team;
use Illuminate\Support\Facades\Cache;

class SportWebController extends Controller
{
    public function index()
    {
        $data = Cache::remember('sport:web:home', 300, fn () => [
            'liveMatches' => SportMatch::where('status', 'live')->with(['homeTeam', 'awayTeam'])->get(),
            'upcoming' => SportMatch::where('status', 'scheduled')
                ->where('start_time', '>=', now())
                ->orderBy('start_time')
                ->with(['homeTeam', 'awayTeam', 'league'])
                ->limit(10)->get(),
            'recentArticles' => SportArticle::latest()->limit(5)->get(),
        ]);

        return view('sport.index', $data);
    }

    public function fixtures()
    {
        $date = request('date', today()->toDateString());
        $matches = Cache::remember("sport:web:fixtures:{$date}", 300, fn () =>
            SportMatch::whereDate('start_time', $date)
                ->with(['homeTeam', 'awayTeam', 'league'])
                ->orderBy('start_time')
                ->get()
                ->groupBy('league_id')
        );

        return view('sport.fixtures', compact('matches', 'date'));
    }

    public function standings(string $leagueSlug)
    {
        $league = League::where('id', $leagueSlug)->firstOrFail();
        $standings = Cache::remember("sport:web:standings:{$leagueSlug}", 600, fn () =>
            Standing::where('league_id', $league->id)
                ->with('team')
                ->orderBy('position')
                ->get()
        );

        return view('sport.standings', compact('league', 'standings'));
    }

    public function match(string $id)
    {
        $match = SportMatch::with(['homeTeam', 'awayTeam', 'league', 'events', 'lineups'])->findOrFail($id);
        return view('sport.match', compact('match'));
    }

    public function team(string $slug)
    {
        $team = Team::where('id', $slug)->firstOrFail();
        $fixtures = SportMatch::where('home_team_id', $slug)->orWhere('away_team_id', $slug)
            ->orderByDesc('start_time')->limit(20)->with(['homeTeam', 'awayTeam'])->get();
        $standing = Standing::where('team_id', $slug)->first();

        return view('sport.team', compact('team', 'fixtures', 'standing'));
    }

    public function articles()
    {
        $articles = SportArticle::latest()->paginate(20);
        return view('sport.articles', compact('articles'));
    }

    public function article(string $id)
    {
        $article = SportArticle::findOrFail($id);
        return view('sport.article', compact('article'));
    }
}
