<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\MagicAI\Facades\MagicAI;
use Webkul\MagicAI\Services\ImageOptimizationService;

class MagicAIController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function content(): JsonResponse
    {
        $this->validate(request(), [
            'model'  => 'required',
            'prompt' => 'required',
        ]);

        try {
            $response = MagicAI::setModel(request()->input('model'))
                ->setPrompt(request()->input('prompt'))
                ->ask();

            return new JsonResponse([
                'content' => $response,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function image(): JsonResponse
    {
        config([
            'openai.api_key'      => core()->getConfigData('general.magic_ai.settings.api_key'),
            'openai.organization' => core()->getConfigData('general.magic_ai.settings.organization'),
        ]);

        // Get model for size validation
        $model = request()->input('model');

        // Define valid sizes based on model
        $validSizes = [];
        if ($model === 'dall-e-2') {
            $validSizes = ['1024x1024', '512x512', '256x256'];
        } elseif ($model === 'dall-e-3') {
            $validSizes = ['1024x1024', '1024x1792', '1792x1024'];
        }

        $this->validate(request(), [
            'prompt'        => 'required',
            'model'         => 'required|in:dall-e-2,dall-e-3',
            'n'             => 'required_if:model,dall-e-2|integer|min:1|max:10',
            'size'          => 'required|in:' . implode(',', $validSizes),
            'quality'       => 'nullable|required_if:model,dall-e-3|in:standard,hd',
            // Optimization parameters
            'context'       => 'sometimes|in:blog,product,general',
            'optimize'      => 'sometimes|boolean',
            'quality_level' => 'sometimes|in:low,medium,high',
            'use_case'      => 'sometimes|in:thumbnail,hero,social',
        ]);

        try {
            // Build options based on model type
            $options = [
                'size' => request()->input('size'),
            ];

            // Add model-specific parameters
            if (request()->input('model') === 'dall-e-2') {
                $options['n'] = request()->input('n', 1);
            } elseif (request()->input('model') === 'dall-e-3') {
                if (request()->has('quality')) {
                    $options['quality'] = request()->input('quality');
                }
            }

            $images = MagicAI::setModel(request()->input('model'))
                ->setPrompt(request()->input('prompt'))
                ->images($options);


            return new JsonResponse([
                'images' => $images,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Optimize images using ImageOptimizationService
     *
     * @param array $images
     * @param string $qualityLevel
     * @param string $context
     * @param array $options
     * @return array
     */
    protected function optimizeImages($images, $qualityLevel, $context, $options = [])
    {
        $optimizationService = new ImageOptimizationService();

        $optimizedImages = [];

        foreach ($images as $index => $image) {
            $result = $optimizationService->optimizeAIImage(
                $image['url'], // Base64 image data
                $qualityLevel,
                $context,
                $options
            );

            if ($result['success']) {
                $optimizedImages[] = [
                    'url' => $result['url'],
                    'path' => $result['path'],
                    'filename' => $result['filename'],
                    'optimized' => true,
                    'optimization_info' => [
                        'original_size' => $result['original_size'],
                        'optimized_size' => $result['optimized_size'],
                        'compression_ratio' => $result['compression_ratio'],
                        'format' => $result['format'],
                        'quality_level' => $result['quality_level'],
                        'context' => $result['context']
                    ]
                ];
            } else {
                // Fallback to original image if optimization fails
                $optimizedImages[] = [
                    'url' => $image['url'],
                    'optimized' => false,
                    'error' => $result['error'] ?? 'Optimization failed'
                ];
            }
        }

        return $optimizedImages;
    }
}
