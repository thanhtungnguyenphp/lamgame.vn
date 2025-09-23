<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductFileController extends Controller
{
    public function serve($productId, $filename)
    {
        // Sanitize inputs to prevent directory traversal
        $productId = (int) $productId;
        $filename = basename($filename);
        
        $path = "product/{$productId}/{$filename}";
        
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'File not found');
        }
        
        $fullPath = Storage::disk('local')->path($path);
        $mimeType = mime_content_type($fullPath);
        
        // Security check: only allow image files
        if (!str_starts_with($mimeType, 'image/')) {
            abort(403, 'File type not allowed');
        }
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=2592000', // 30 days cache
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000),
        ]);
    }
}
