<?php

namespace App\Services\AI;

use App\Models\Blog;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductImage;
use Webkul\MagicAI\Facades\MagicAI;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ThumbnailGenerationService
{
    /**
     * Generate thumbnail for blog post using AI
     *
     * @param Blog $blog
     * @param array $options
     * @return array
     */
    public function generateBlogThumbnail(Blog $blog, array $options = [])
    {
        try {
            // Create prompt based on blog content
            $prompt = $this->createBlogThumbnailPrompt($blog);
            
            // Configure AI image generation options
            $aiOptions = array_merge([
                'size' => '1024x1024',
                'quality' => 'standard',
                'context' => 'blog',
                'optimize' => true,
                'quality_level' => 'medium',
                'use_case' => 'thumbnail'
            ], $options);
            
            Log::info('Generating AI thumbnail for blog', [
                'blog_id' => $blog->id,
                'blog_name' => $blog->name,
                'prompt' => $prompt
            ]);
            
            // Generate image using AI
            $images = MagicAI::setModel('dall-e-2')
                ->setPrompt($prompt)
                ->images($aiOptions);
            
            if (empty($images)) {
                throw new \Exception('No images generated from AI');
            }
            
            // Save the first generated image as blog thumbnail
            $imageData = $images[0];
            $imagePath = $this->saveBlogThumbnail($blog, $imageData);
            
            // Update blog with new thumbnail
            $blog->src = $imagePath;
            $blog->save();
            
            return [
                'success' => true,
                'image_path' => $imagePath,
                'image_url' => Storage::disk('public')->url($imagePath),
                'blog_id' => $blog->id,
                'prompt' => $prompt,
                'ai_info' => $imageData['optimization_info'] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to generate blog thumbnail', [
                'blog_id' => $blog->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'blog_id' => $blog->id
            ];
        }
    }
    
    /**
     * Generate thumbnail for source game product using AI
     *
     * @param Product $product
     * @param array $options
     * @return array
     */
    public function generateProductThumbnail(Product $product, array $options = [])
    {
        try {
            // Create prompt based on product content
            $prompt = $this->createProductThumbnailPrompt($product);
            
            // Configure AI image generation options
            $aiOptions = array_merge([
                'size' => '1024x1024',
                'quality' => 'standard',
                'context' => 'product',
                'optimize' => true,
                'quality_level' => 'high',
                'use_case' => 'thumbnail'
            ], $options);
            
            Log::info('Generating AI thumbnail for product', [
                'product_id' => $product->id,
                'product_sku' => $product->sku,
                'prompt' => $prompt
            ]);
            
            // Generate image using AI
            $images = MagicAI::setModel('dall-e-2')
                ->setPrompt($prompt)
                ->images($aiOptions);
            
            if (empty($images)) {
                throw new \Exception('No images generated from AI');
            }
            
            // Save the first generated image as product thumbnail
            $imageData = $images[0];
            $savedImages = $this->saveProductThumbnail($product, $imageData);
            
            return [
                'success' => true,
                'images' => $savedImages,
                'product_id' => $product->id,
                'product_sku' => $product->sku,
                'prompt' => $prompt,
                'ai_info' => $imageData['optimization_info'] ?? null
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to generate product thumbnail', [
                'product_id' => $product->id,
                'product_sku' => $product->sku,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'product_id' => $product->id,
                'product_sku' => $product->sku
            ];
        }
    }
    
    /**
     * Create AI prompt for blog thumbnail
     *
     * @param Blog $blog
     * @return string
     */
    protected function createBlogThumbnailPrompt(Blog $blog)
    {
        $name = $blog->name;
        $description = $blog->short_description ?? strip_tags($blog->description ?? '');
        $description = Str::limit($description, 200);
        
        // Extract key terms for better prompts
        $gameTerms = $this->extractGameTerms($name . ' ' . $description);
        
        $prompts = [
            "Create a professional game development blog thumbnail for: '{$name}'. Modern, clean design with vibrant colors, game development icons, and programming elements. Style: digital art, professional, eye-catching.",
            
            "Design a thumbnail image for a game development article about '{$name}'. Include game controller icons, code snippets overlay, Unity or game engine logos, vibrant gradient background. Style: modern tech illustration.",
            
            "Generate a compelling blog post thumbnail about '{$name}'. Features: {$gameTerms}, gaming aesthetics, professional layout, bright engaging colors. Style: digital marketing, modern game development theme.",
            
            "Create an attractive thumbnail for game development content: '{$name}'. Include: pixel art elements, game development tools, modern UI design, technology theme. Style: vibrant, professional, gaming industry focused."
        ];
        
        // Choose prompt based on content type
        if (stripos($name, 'unity') !== false) {
            return "Unity game development thumbnail: '{$name}'. Include Unity logo, C# code elements, game objects, modern interface design. Style: professional, vibrant, technology-focused.";
        }
        
        if (stripos($name, 'unreal') !== false) {
            return "Unreal Engine thumbnail: '{$name}'. Include Unreal Engine logo, Blueprint nodes, 3D elements, modern design. Style: high-tech, professional, game development.";
        }
        
        if (stripos($name, 'source') !== false || stripos($name, 'code') !== false) {
            return "Source code thumbnail: '{$name}'. Include code editor interface, programming languages, file structures, modern developer tools. Style: clean, professional, coding theme.";
        }
        
        // Default gaming prompt
        return array_rand(array_flip($prompts));
    }
    
    /**
     * Create AI prompt for product thumbnail
     *
     * @param Product $product
     * @return string
     */
    protected function createProductThumbnailPrompt(Product $product)
    {
        $flat = $product->product_flats->first();
        if (!$flat) {
            return "Game development source code thumbnail, modern design, professional look";
        }
        
        $name = $flat->name;
        $description = $flat->short_description ?? strip_tags($flat->description ?? '');
        $description = Str::limit($description, 200);
        
        // Extract game type from name/description
        $gameType = $this->detectGameType($name, $description);
        
        $prompts = [
            'platformer' => "2D platformer game screenshot style thumbnail: '{$name}'. Include colorful platforms, character sprites, side-scrolling environment, retro gaming aesthetic. Style: pixel art, vibrant colors.",
            
            'shooter' => "Space shooter game thumbnail: '{$name}'. Include spacecraft, laser beams, space background, enemy ships, action-packed scene. Style: sci-fi, dynamic, colorful effects.",
            
            'rpg' => "RPG game thumbnail: '{$name}'. Include fantasy characters, inventory interface, medieval/fantasy environment, magical effects. Style: fantasy art, detailed, atmospheric.",
            
            'puzzle' => "Puzzle game thumbnail: '{$name}'. Include colorful game pieces, grid layout, matching elements, clean interface design. Style: minimalist, bright colors, casual gaming.",
            
            'racing' => "Racing game thumbnail: '{$name}'. Include sports cars, racing track, speed effects, checkered flag. Style: dynamic, fast-paced, motorsport aesthetic.",
            
            'adventure' => "Adventure game thumbnail: '{$name}'. Include exploration elements, diverse environments, character interaction, story elements. Style: cinematic, adventurous, immersive.",
            
            'strategy' => "Strategy game thumbnail: '{$name}'. Include tactical interface, resource management, military/city building elements. Style: strategic, organized, commanding view.",
            
            'casual' => "Casual game thumbnail: '{$name}'. Include simple gameplay elements, friendly interface, accessible design. Style: cartoon, cheerful, family-friendly."
        ];
        
        if (isset($prompts[$gameType])) {
            return $prompts[$gameType];
        }
        
        // Default source game prompt
        return "Source code game project thumbnail: '{$name}'. Include game development interface, code editor, game assets, modern development tools. Style: professional, technical, game development focused.";
    }
    
    /**
     * Extract game-related terms from text
     *
     * @param string $text
     * @return string
     */
    protected function extractGameTerms($text)
    {
        $gameTerms = [
            'unity', 'unreal', 'godot', 'game engine',
            'c#', 'javascript', 'python', 'blueprint',
            '2d', '3d', 'platformer', 'shooter', 'rpg',
            'mobile', 'pc', 'console', 'vr', 'ar',
            'multiplayer', 'single player', 'indie',
            'pixel art', 'low poly', 'realistic'
        ];
        
        $foundTerms = [];
        $lowerText = strtolower($text);
        
        foreach ($gameTerms as $term) {
            if (stripos($lowerText, $term) !== false) {
                $foundTerms[] = $term;
            }
        }
        
        return implode(', ', array_slice($foundTerms, 0, 5));
    }
    
    /**
     * Detect game type from name and description
     *
     * @param string $name
     * @param string $description
     * @return string
     */
    protected function detectGameType($name, $description)
    {
        $text = strtolower($name . ' ' . $description);
        
        $gameTypes = [
            'platformer' => ['mario', 'platform', 'jump', 'side-scroll', 'platformer'],
            'shooter' => ['shooter', 'space', 'bullet', 'enemy', 'shoot', 'laser'],
            'rpg' => ['rpg', 'character', 'level', 'inventory', 'quest', 'fantasy'],
            'puzzle' => ['puzzle', 'match', 'brain', 'logic', 'solve'],
            'racing' => ['racing', 'car', 'speed', 'race', 'vehicle'],
            'adventure' => ['adventure', 'explore', 'story', 'journey'],
            'strategy' => ['strategy', 'tactical', 'resource', 'management', 'build'],
            'casual' => ['casual', 'simple', 'easy', 'family', 'relaxing']
        ];
        
        foreach ($gameTypes as $type => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($text, $keyword) !== false) {
                    return $type;
                }
            }
        }
        
        return 'casual'; // Default fallback
    }
    
    /**
     * Save blog thumbnail to storage
     *
     * @param Blog $blog
     * @param array $imageData
     * @return string
     */
    protected function saveBlogThumbnail(Blog $blog, array $imageData)
    {
        // If the image is already optimized and saved, return the path
        if (isset($imageData['path']) && $imageData['path']) {
            return $imageData['path'];
        }
        
        // Otherwise, save base64 data
        $base64Image = $imageData['url'];
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        
        $filename = 'blog-thumbnail-' . $blog->id . '-' . time() . '.jpg';
        $path = 'blogs/thumbnails/' . date('Y/m/d') . '/' . $filename;
        
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }
    
    /**
     * Save product thumbnail to storage
     *
     * @param Product $product
     * @param array $imageData
     * @return array
     */
    protected function saveProductThumbnail(Product $product, array $imageData)
    {
        // If the image is already optimized and saved, use that
        if (isset($imageData['path']) && $imageData['path']) {
            $path = $imageData['path'];
            $url = Storage::disk('public')->url($path);
        } else {
            // Otherwise, save base64 data
            $base64Image = $imageData['url'];
            $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            
            $filename = 'product-thumbnail-' . $product->id . '-' . time() . '.jpg';
            $path = 'products/thumbnails/' . date('Y/m/d') . '/' . $filename;
            
            Storage::disk('public')->put($path, $decodedImage);
            $url = Storage::disk('public')->url($path);
        }
        
        // Create ProductImage record
        $productImage = ProductImage::create([
            'product_id' => $product->id,
            'path' => $path,
            'type' => 'image'
        ]);
        
        return [
            'id' => $productImage->id,
            'path' => $path,
            'url' => $url,
            'type' => 'thumbnail'
        ];
    }
    
    /**
     * Generate thumbnails for multiple items in batch
     *
     * @param string $type 'blog' or 'product'
     * @param array $ids
     * @param array $options
     * @return array
     */
    public function batchGenerate($type, array $ids = [], array $options = [])
    {
        $results = [];
        $totalCount = 0;
        $successCount = 0;
        
        if ($type === 'blog') {
            $query = Blog::query();
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } else {
                // Generate for blogs without thumbnails
                $query->where(function($q) {
                    $q->whereNull('src')
                      ->orWhere('src', '')
                      ->orWhere('src', 'like', '%placeholder%');
                });
            }
            
            $items = $query->get();
            $totalCount = $items->count();
            
            foreach ($items as $item) {
                $result = $this->generateBlogThumbnail($item, $options);
                $results[] = $result;
                if ($result['success']) {
                    $successCount++;
                }
            }
            
        } elseif ($type === 'product') {
            $query = Product::where('type', 'downloadable');
            if (!empty($ids)) {
                $query->whereIn('id', $ids);
            } else {
                // Generate for products without images
                $query->whereDoesntHave('images');
            }
            
            $items = $query->get();
            $totalCount = $items->count();
            
            foreach ($items as $item) {
                $result = $this->generateProductThumbnail($item, $options);
                $results[] = $result;
                if ($result['success']) {
                    $successCount++;
                }
            }
        }
        
        return [
            'total_processed' => $totalCount,
            'success_count' => $successCount,
            'failed_count' => $totalCount - $successCount,
            'results' => $results
        ];
    }
}
