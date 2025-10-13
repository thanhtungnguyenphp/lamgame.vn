<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // This resource can handle different types of option data
        $data = is_array($this->resource) ? $this->resource : $this->resource->toArray();
        
        // Check if this is a paginated response
        if (isset($data['data']) && isset($data['meta'])) {
            return [
                'data' => $this->formatOptions($data['data']),
                'meta' => $data['meta'],
                'links' => $data['links'] ?? null,
            ];
        }
        
        // Handle direct array of options
        return $this->formatOptions($data);
    }

    /**
     * Format options data consistently
     * 
     * @param array $options
     * @return array
     */
    protected function formatOptions(array $options): array
    {
        if (empty($options)) {
            return [];
        }

        // If it's a multi-dimensional array with named keys (like filter options)
        if (isset($options['job_types']) || isset($options['experience_levels'])) {
            return $this->formatFilterOptions($options);
        }

        // If it's a simple array of options
        return collect($options)->map(function ($option) {
            return $this->formatSingleOption($option);
        })->toArray();
    }

    /**
     * Format filter options (complex structure)
     * 
     * @param array $filterOptions
     * @return array
     */
    protected function formatFilterOptions(array $filterOptions): array
    {
        $formatted = [];
        
        foreach ($filterOptions as $type => $options) {
            $formatted[$type] = collect($options)->map(function ($option) {
                return $this->formatSingleOption($option);
            })->toArray();
        }
        
        return $formatted;
    }

    /**
     * Format a single option item
     * 
     * @param mixed $option
     * @return array
     */
    protected function formatSingleOption($option): array
    {
        // If option is already an array with proper structure
        if (is_array($option)) {
            return [
                'id' => $option['id'] ?? null,
                'value' => $option['value'] ?? $option['label'] ?? $option['name'] ?? '',
                'label' => $option['label'] ?? $option['value'] ?? $option['name'] ?? '',
                'count' => $option['count'] ?? $option['job_count'] ?? null,
                'sort_order' => $option['sort_order'] ?? $option['position'] ?? null,
                'slug' => $option['slug'] ?? null,
                'metadata' => $this->extractMetadata($option),
            ];
        }

        // If option is a simple string
        if (is_string($option)) {
            return [
                'id' => null,
                'value' => $option,
                'label' => $option,
                'count' => null,
                'sort_order' => null,
                'slug' => null,
                'metadata' => null,
            ];
        }

        // If option is an object (e.g., Model instance)
        if (is_object($option)) {
            return [
                'id' => $option->id ?? null,
                'value' => $option->value ?? $option->name ?? $option->label ?? '',
                'label' => $option->label ?? $option->name ?? $option->value ?? '',
                'count' => $option->count ?? $option->job_count ?? null,
                'sort_order' => $option->sort_order ?? $option->position ?? null,
                'slug' => $option->slug ?? null,
                'metadata' => $this->extractMetadata($option),
            ];
        }

        return [
            'id' => null,
            'value' => (string) $option,
            'label' => (string) $option,
            'count' => null,
            'sort_order' => null,
            'slug' => null,
            'metadata' => null,
        ];
    }

    /**
     * Extract additional metadata from option
     * 
     * @param mixed $option
     * @return array|null
     */
    protected function extractMetadata($option): ?array
    {
        $metadata = [];
        
        if (is_array($option)) {
            // Extract additional fields that are not standard
            $standardFields = ['id', 'value', 'label', 'name', 'count', 'job_count', 'sort_order', 'position', 'slug'];
            
            foreach ($option as $key => $value) {
                if (!in_array($key, $standardFields) && $value !== null) {
                    $metadata[$key] = $value;
                }
            }
        } elseif (is_object($option)) {
            // Handle object properties
            $properties = get_object_vars($option);
            $standardFields = ['id', 'value', 'label', 'name', 'count', 'job_count', 'sort_order', 'position', 'slug'];
            
            foreach ($properties as $key => $value) {
                if (!in_array($key, $standardFields) && $value !== null) {
                    $metadata[$key] = $value;
                }
            }
        }
        
        return empty($metadata) ? null : $metadata;
    }

    /**
     * Add additional metadata to the response
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'generated_at' => now()->toISOString(),
                'cache_ttl' => 3600, // 1 hour cache
                'version' => '1.0',
            ],
        ];
    }

    /**
     * Create collection response for search results
     * 
     * @param array $searchResults
     * @return array
     */
    public static function searchCollection(array $searchResults): array
    {
        $formatted = [];
        
        foreach ($searchResults as $type => $results) {
            $formatted[$type] = collect($results)->map(function ($result) {
                return (new static($result))->formatSingleOption($result);
            })->toArray();
        }
        
        return $formatted;
    }

    /**
     * Create form data response
     * 
     * @param array $formData
     * @return array
     */
    public static function formDataCollection(array $formData): array
    {
        $formatted = [];
        
        foreach ($formData as $section => $data) {
            if ($section === 'attributes') {
                $formatted[$section] = collect($data)->map(function ($attribute, $code) {
                    return [
                        'code' => $code,
                        'name' => $attribute['name'] ?? $code,
                        'type' => $attribute['type'] ?? 'text',
                        'is_required' => $attribute['is_required'] ?? false,
                        'is_filterable' => $attribute['is_filterable'] ?? false,
                        'options' => collect($attribute['options'] ?? [])->map(function ($option) {
                            return (new static($option))->formatSingleOption($option);
                        })->toArray(),
                    ];
                })->toArray();
            } else {
                $formatted[$section] = collect($data)->map(function ($item) {
                    return (new static($item))->formatSingleOption($item);
                })->toArray();
            }
        }
        
        return $formatted;
    }
}