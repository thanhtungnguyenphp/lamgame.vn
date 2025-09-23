<?php

namespace Webkul\MagicAI\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageOptimizationService
{
    /**
     * Quality levels configuration
     */
    const QUALITY_LEVELS = [
        'low' => [
            'jpeg' => 60,
            'webp' => 50,
            'png' => 6,
            'max_dimension' => 800
        ],
        'medium' => [
            'jpeg' => 80,
            'webp' => 70,
            'png' => 7,
            'max_dimension' => 1200
        ],
        'high' => [
            'jpeg' => 90,
            'webp' => 85,
            'png' => 8,
            'max_dimension' => 1600
        ]
    ];

    /**
     * Blog context specific settings
     */
    const BLOG_SETTINGS = [
        'thumbnail' => [
            'max_width' => 800,
            'max_height' => 600,
            'quality' => 'medium'
        ],
        'hero' => [
            'max_width' => 1792,
            'max_height' => 1024,
            'quality' => 'high'
        ],
        'social' => [
            'max_width' => 1200,
            'max_height' => 630,
            'quality' => 'medium'
        ]
    ];

    /**
     * Optimize AI generated image based on context and quality level
     *
     * @param string $base64Image
     * @param string $qualityLevel
     * @param string $context
     * @param array $options
     * @return array
     */
    public function optimizeAIImage($base64Image, $qualityLevel = 'medium', $context = 'blog', $options = [])
    {
        try {
            // Decode base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
            
            // Create intervention image instance
            $image = Image::make($imageData);
            
            // Get optimization settings
            $qualitySettings = self::QUALITY_LEVELS[$qualityLevel] ?? self::QUALITY_LEVELS['medium'];
            
            // Apply blog-specific optimizations
            if ($context === 'blog') {
                $image = $this->applyBlogOptimizations($image, $options, $qualitySettings);
            }
            
            // Convert to best available format (WebP or JPEG)
            $optimizedData = $this->convertToBestFormat($image, $qualitySettings);
            
            // Generate filename
            $filename = $this->generateFilename($context, $qualityLevel, $optimizedData['format']);
            
            // Save optimized image
            $path = $this->saveOptimizedImage($optimizedData['image'], $filename);
            
            // Get file info
            $fileInfo = $this->getFileInfo($path, $imageData);
            
            return [
                'success' => true,
                'path' => $path,
                'url' => route('ai.images', $path),
                'filename' => $filename,
                'original_size' => strlen($imageData),
                'optimized_size' => $fileInfo['size'],
                'compression_ratio' => $fileInfo['compression_ratio'],
                'format' => $optimizedData['format'],
                'quality_level' => $qualityLevel,
                'context' => $context,
                'webp_supported' => function_exists('imagewebp')
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Apply blog-specific optimizations
     *
     * @param \Intervention\Image\Image $image
     * @param array $options
     * @param array $qualitySettings
     * @return \Intervention\Image\Image
     */
    protected function applyBlogOptimizations($image, $options, $qualitySettings)
    {
        // Resize based on intended use
        $useCase = $options['use_case'] ?? 'thumbnail';
        $blogSettings = self::BLOG_SETTINGS[$useCase] ?? self::BLOG_SETTINGS['thumbnail'];
        
        // Maintain aspect ratio while fitting within max dimensions
        $image->resize($blogSettings['max_width'], $blogSettings['max_height'], function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Prevent upsizing
        });
        
        // Apply sharpening for better web display
        $image->sharpen(10);
        
        // Optimize for web (remove EXIF data, optimize colors)
        $image->orientate(); // Fix orientation
        
        return $image;
    }

    /**
     * Convert image to best available format (WebP or JPEG fallback)
     *
     * @param \Intervention\Image\Image $image
     * @param array $qualitySettings
     * @return array
     */
    protected function convertToBestFormat($image, $qualitySettings)
    {
        // Check if WebP is supported
        if (function_exists('imagewebp')) {
            try {
                $encodedImage = $image->encode('webp', $qualitySettings['webp']);
                return [
                    'image' => $encodedImage,
                    'format' => 'webp'
                ];
            } catch (\Exception $e) {
                // If WebP encoding fails, fallback to JPEG
            }
        }
        
        // Fallback to JPEG
        $encodedImage = $image->encode('jpg', $qualitySettings['jpeg']);
        return [
            'image' => $encodedImage,
            'format' => 'jpg'
        ];
    }

    /**
     * Generate optimized filename
     *
     * @param string $context
     * @param string $qualityLevel
     * @param string $format
     * @return string
     */
    protected function generateFilename($context, $qualityLevel, $format = 'jpg')
    {
        $timestamp = time();
        $random = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
        
        return "{$context}-{$qualityLevel}-{$timestamp}-{$random}.{$format}";
    }

    /**
     * Save optimized image to storage
     *
     * @param \Intervention\Image\Image $image
     * @param string $filename
     * @return string
     */
    protected function saveOptimizedImage($image, $filename)
    {
        $path = 'magic-ai/optimized/' . date('Y/m/d') . '/' . $filename;
        
        Storage::disk('public')->put($path, $image->getEncoded());
        
        return $path;
    }

    /**
     * Get file information
     *
     * @param string $path
     * @param string $originalData
     * @return array
     */
    protected function getFileInfo($path, $originalData)
    {
        $size = Storage::disk('public')->size($path);
        $originalSize = strlen($originalData);
        
        $compressionRatio = round((1 - ($size / $originalSize)) * 100, 2);
        
        return [
            'size' => $size,
            'compression_ratio' => $compressionRatio
        ];
    }

    /**
     * Optimize multiple images in batch
     *
     * @param array $images
     * @param string $qualityLevel
     * @param string $context
     * @param array $options
     * @return array
     */
    public function batchOptimize(array $images, $qualityLevel = 'medium', $context = 'blog', $options = [])
    {
        $results = [];
        
        foreach ($images as $index => $image) {
            $result = $this->optimizeAIImage($image, $qualityLevel, $context, $options);
            $results[$index] = $result;
        }
        
        return $results;
    }

    /**
     * Get available quality levels
     *
     * @return array
     */
    public function getQualityLevels()
    {
        return [
            'low' => [
                'label' => 'Low Quality (Fastest)',
                'description' => 'Smallest file size, good for thumbnails',
                'estimated_size' => '~50KB'
            ],
            'medium' => [
                'label' => 'Medium Quality (Recommended)',
                'description' => 'Balanced quality and file size',
                'estimated_size' => '~100KB'
            ],
            'high' => [
                'label' => 'High Quality (Best)',
                'description' => 'Best quality, larger file size',
                'estimated_size' => '~200KB'
            ]
        ];
    }

    /**
     * Get blog context options
     *
     * @return array
     */
    public function getBlogContexts()
    {
        return [
            'thumbnail' => [
                'label' => 'Blog Thumbnail',
                'description' => 'For blog post previews and cards',
                'recommended_size' => '800x600'
            ],
            'hero' => [
                'label' => 'Hero Banner',
                'description' => 'Large header images for blog posts',
                'recommended_size' => '1792x1024'
            ],
            'social' => [
                'label' => 'Social Media',
                'description' => 'Open Graph and social sharing',
                'recommended_size' => '1200x630'
            ]
        ];
    }
}