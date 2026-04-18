<?php

namespace App\Http\Controllers;

use App\Models\MiniGame;
use Illuminate\Http\Request;

class MiniGameController extends Controller
{
    public function index(Request $request)
    {
        $query = MiniGame::active()->orderBy('sort_order')->orderBy('title');

        if ($cat = $request->get('category')) {
            $query->where('category', $cat);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $games = $query->paginate(48);

        return view('lamgame.pages.mini-game.index', [
            'games'      => $games,
            'categories' => MiniGame::CATEGORIES,
            'current'    => $cat,
            'search'     => $search,
        ]);
    }

    public function show(string $slug)
    {
        $game = MiniGame::active()->where('slug', $slug)->firstOrFail();

        $game->increment('play_count');

        $related = MiniGame::active()
            ->where('category', $game->category)
            ->where('id', '!=', $game->id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('lamgame.pages.mini-game.show', compact('game', 'related'));
    }
}
