<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class PillarController extends Controller
{
    /**
     * Unity Pillar Page
     */
    public function unity()
    {
        // Get Unity articles
        $unityArticles = Blog::published()
            ->where(function ($q) {
                $q->where('default_category', 1) // Unity Development
                    ->orWhere('name', 'like', '%Unity%');
            })
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        // Get Unity source games
        $unitySources = DB::table('product_flat')
            ->where('engine', 'Unity')
            ->whereNotNull('url_key')
            ->select('id', 'name as title', 'url_key', 'price')
            ->limit(4)
            ->get()
            ->map(function ($s) {
                return [
                    'title' => $s->title,
                    'url' => route('lamgame.source-game.detail', $s->url_key),
                    'thumbnail' => asset('images/placeholder-game.svg'),
                    'price' => $s->price,
                    'is_free' => $s->price <= 0,
                ];
            });

        // Get Unity jobs count
        $jobCount = DB::table('job_postings')
            ->where('status', 'active')
            ->where('is_game_related', true)
            ->where(function ($q) {
                $q->where('title', 'like', '%Unity%')
                    ->orWhere('description', 'like', '%Unity%');
            })
            ->count();

        return view('lamgame.pages.learn.unity', [
            'unityArticles' => $unityArticles,
            'unitySources' => $unitySources,
            'articleCount' => $unityArticles->count(),
            'sourceCount' => $unitySources->count(),
            'jobCount' => $jobCount,
        ]);
    }

    /**
     * Godot Pillar Page
     */
    public function godot()
    {
        $articles = Blog::published()
            ->where(function ($q) {
                $q->where('default_category', 66) // Godot
                    ->orWhere('name', 'like', '%Godot%');
            })
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        $sourceCount = DB::table('product_flat')
            ->where('engine', 'Godot')
            ->whereNotNull('url_key')
            ->count();

        return view('lamgame.pages.learn.godot', [
            'articles' => $articles,
            'articleCount' => $articles->count(),
            'sourceCount' => $sourceCount,
        ]);
    }

    /**
     * AI Game Dev Pillar Page
     */
    public function aiGameDev()
    {
        $articles = Blog::published()
            ->where(function ($q) {
                $q->where('default_category', 63) // AI Game Dev
                    ->orWhere('name', 'like', '%AI%game%')
                    ->orWhere('name', 'like', '%AI%dev%');
            })
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        return view('lamgame.pages.learn.ai-game-dev', [
            'articles' => $articles,
            'articleCount' => $articles->count(),
        ]);
    }

    /**
     * Game Developer Career Pillar Page
     */
    public function career()
    {
        $articles = Blog::published()
            ->where(function ($q) {
                $q->where('default_category', 64) // Game Developer Career
                    ->orWhere('name', 'like', '%career%')
                    ->orWhere('name', 'like', '%lương%')
                    ->orWhere('name', 'like', '%việc làm%');
            })
            ->orderBy('published_at', 'desc')
            ->take(10)
            ->get();

        $jobs = DB::table('job_postings')
            ->where('status', 'active')
            ->where('is_game_related', true)
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        $jobCount = DB::table('job_postings')
            ->where('status', 'active')
            ->where('is_game_related', true)
            ->count();

        return view('lamgame.pages.learn.career', [
            'articles' => $articles,
            'articleCount' => $articles->count(),
            'jobs' => $jobs,
            'jobCount' => $jobCount,
        ]);
    }
}
