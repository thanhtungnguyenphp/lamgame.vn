<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\M7Match;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show(string $slug)
    {
        $page = LandingPage::where('slug', $slug)->active()->firstOrFail();

        $page->increment('views');

        $templateView = "lamgame.landing.{$page->template}";

        if (!view()->exists($templateView)) {
            $templateView = 'lamgame.landing.general';
        }

        $data = [
            'page'             => $page,
            'page_title'       => $page->meta_title ?: $page->name,
            'page_description' => $page->meta_description ?: $page->hero_subtitle,
        ];

        // Pass matches for mini-game template
        if ($page->template === 'mini-game') {
            $data['matches'] = M7Match::forPage($page->id)->orderBy('match_at')->get();
        }

        return view($templateView, $data);
    }
}
