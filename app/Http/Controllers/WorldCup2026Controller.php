<?php

namespace App\Http\Controllers;

use App\Models\Sport\SportArticle;
use Illuminate\Http\Request;

class WorldCup2026Controller extends Controller
{
    public function show()
    {
        $articles = SportArticle::where('sport_id', 'football')
            ->where(function ($q) {
                $q->where('title', 'like', '%World Cup%')
                    ->orWhere('title', 'like', '%world cup%')
                    ->orWhere('title', 'like', '%bóng đá%')
                    ->orWhere('title', 'like', '%tuyển%');
            })
            ->latest()
            ->take(6)
            ->get();

        return view('lamgame.landing.world-cup-2026', compact('articles'));
    }
}
