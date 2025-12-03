<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoMetaRobots
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Noindex auth pages
        if ($request->is('auth/*') || $request->is('index.php/auth/*')) {
            $this->addMetaRobots($response, 'noindex, nofollow');
        }
        
        // Noindex pagination pages > 1
        if ($request->has('page') && $request->get('page') > 1) {
            $this->addMetaRobots($response, 'noindex, follow');
        }
        
        return $response;
    }
    
    private function addMetaRobots($response, $content)
    {
        if (method_exists($response, 'getContent')) {
            $html = $response->getContent();
            $meta = '<meta name="robots" content="' . $content . '">';
            $html = str_replace('</head>', $meta . '</head>', $html);
            $response->setContent($html);
        }
    }
}
