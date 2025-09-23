<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\ThumbnailGenerationService;
use App\Models\Blog;
use Webkul\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ThumbnailController extends Controller
{
    protected $thumbnailService;

    public function __construct()
    {
        $this->thumbnailService = new ThumbnailGenerationService();
    }

    public function generateBlogThumbnail(Request $request): JsonResponse
    {
        $request->validate([
            'blog_id' => 'required|integer|exists:blogs,id',
            'quality_level' => 'sometimes|in:low,medium,high',
            'force' => 'sometimes|boolean'
        ]);

        try {
            $blog = Blog::findOrFail($request->blog_id);
            
            if (!$request->get('force', false) && $blog->src && !empty($blog->src)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog already has a thumbnail. Use force=true to regenerate.',
                    'current_thumbnail' => $blog->src
                ]);
            }

            $options = [
                'quality_level' => $request->get('quality_level', 'medium'),
                'context' => 'blog',
                'use_case' => 'thumbnail'
            ];

            $result = $this->thumbnailService->generateBlogThumbnail($blog, $options);
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate blog thumbnail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateProductThumbnail(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quality_level' => 'sometimes|in:low,medium,high',
            'force' => 'sometimes|boolean'
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            
            if (!$request->get('force', false) && $product->images->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product already has images. Use force=true to regenerate.',
                    'current_images_count' => $product->images->count()
                ]);
            }

            $options = [
                'quality_level' => $request->get('quality_level', 'high'),
                'context' => 'product',
                'use_case' => 'thumbnail'
            ];

            $result = $this->thumbnailService->generateProductThumbnail($product, $options);
            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate product thumbnail: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatistics(): JsonResponse
    {
        try {
            $totalBlogs = Blog::count();
            $blogsWithThumbnails = Blog::where(function($q) {
                $q->whereNotNull('src')
                  ->where('src', '!=', '')
                  ->where('src', 'not like', '%placeholder%');
            })->count();
            
            $totalProducts = Product::where('type', 'downloadable')->count();
            $productsWithImages = Product::where('type', 'downloadable')->has('images')->count();

            return response()->json([
                'success' => true,
                'statistics' => [
                    'blogs' => [
                        'total' => $totalBlogs,
                        'with_thumbnails' => $blogsWithThumbnails,
                        'needing_thumbnails' => $totalBlogs - $blogsWithThumbnails,
                        'completion_rate' => $totalBlogs > 0 ? round(($blogsWithThumbnails / $totalBlogs) * 100, 1) : 0
                    ],
                    'products' => [
                        'total' => $totalProducts,
                        'with_images' => $productsWithImages,
                        'needing_thumbnails' => $totalProducts - $productsWithImages,
                        'completion_rate' => $totalProducts > 0 ? round(($productsWithImages / $totalProducts) * 100, 1) : 0
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
