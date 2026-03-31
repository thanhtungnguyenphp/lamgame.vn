<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\Highlight;
use App\Models\Sport\SportArticle;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function highlights(Request $request)
    {
        $limit = min((int) $request->input('limit', 20), 50);
        $query = Highlight::with('league:id,name,logo_url');

        if ($request->filled('sport'))     $query->where('sport_id', $request->sport);
        if ($request->filled('league_id')) $query->where('league_id', $request->league_id);
        if ($request->filled('match_id'))  $query->where('match_id', $request->match_id);

        $paginated = $query->orderByDesc('created_at')->paginate($limit);

        return response()->json([
            'data'       => $paginated->items(),
            'pagination' => ['page' => $paginated->currentPage(), 'limit' => $paginated->perPage(), 'total' => $paginated->total()],
        ]);
    }

    public function articles(Request $request)
    {
        $limit = min((int) $request->input('limit', 20), 50);
        $query = SportArticle::query();

        if ($request->filled('type'))  $query->where('type', $request->type);
        if ($request->filled('sport')) $query->where('sport_id', $request->sport);

        $paginated = $query->orderByDesc('created_at')->paginate($limit);

        return response()->json([
            'data'       => $paginated->items(),
            'pagination' => ['page' => $paginated->currentPage(), 'limit' => $paginated->perPage(), 'total' => $paginated->total()],
        ]);
    }

    public function articleShow(string $id)
    {
        return response()->json(['data' => SportArticle::findOrFail($id)]);
    }

    public function discover(Request $request)
    {
        $limit = min((int) $request->input('limit', 20), 50);
        $half = intdiv($limit, 2);

        $hlQuery = Highlight::query();
        $artQuery = SportArticle::query();

        if ($request->filled('sport')) {
            $hlQuery->where('sport_id', $request->sport);
            $artQuery->where('sport_id', $request->sport);
        }

        $highlights = $hlQuery->orderByDesc('created_at')->limit($half)->get()
            ->map(fn ($h) => ['type' => 'highlight', 'item' => $h]);

        $articles = $artQuery->orderByDesc('created_at')->limit($limit - $half)->get()
            ->map(fn ($a) => ['type' => 'article', 'item' => $a]);

        $feed = $highlights->merge($articles)->sortByDesc(fn ($i) => $i['item']->created_at)->values();

        return response()->json(['data' => $feed]);
    }
}
