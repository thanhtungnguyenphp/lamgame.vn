<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function show(string $slug)
    {
        $page = LandingPage::where('slug', $slug)->active()->firstOrFail();

        $page->increment('views');

        $templateView = "lamgame.landing.{$page->template}";

        // Fallback to general if template view doesn't exist
        if (!view()->exists($templateView)) {
            $templateView = 'lamgame.landing.general';
        }

        return view($templateView, [
            'page'             => $page,
            'page_title'       => $page->meta_title ?: $page->name,
            'page_description' => $page->meta_description ?: $page->hero_subtitle,
        ]);
    }
}
