<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportProducts extends Command
{
    protected $signature = 'products:import {file}';
    protected $description = 'Import products from CSV file';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Starting import from: {$filePath}");
        
        $csvData = array_map('str_getcsv', file($filePath));
        $headers = array_shift($csvData); // Remove header row
        
        $this->info("Headers: " . implode(', ', $headers));
        $this->info("Found " . count($csvData) . " rows to import");
        
        foreach ($csvData as $index => $row) {
            try {
                $data = array_combine($headers, $row);
                
                if ($data['type'] === 'configurable') {
                    $this->importConfigurableProduct($data);
                } else {
                    $this->importVariantProduct($data);
                }
                
                $this->info("Imported row " . ($index + 1) . ": " . $data['sku']);
                
            } catch (\Exception $e) {
                $this->error("Error importing row " . ($index + 1) . ": " . $e->getMessage());
            }
        }
        
        $this->info("Import completed!");
        return 0;
    }
    
    private function importConfigurableProduct($data)
    {
        // Get attribute family ID
        $attributeFamily = DB::table('attribute_families')
            ->where('code', $data['attribute_family'])
            ->first();
        
        if (!$attributeFamily) {
            throw new \Exception("Attribute family not found: " . $data['attribute_family']);
        }
        
        // Insert product
        $productId = DB::table('products')->insertGetId([
            'sku' => $data['sku'],
            'type' => $data['type'],
            'attribute_family_id' => $attributeFamily->id,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Insert product attribute values
        $this->insertProductAttributeValues($productId, $data);
        
        // Insert product categories
        $this->insertProductCategories($productId, $data['categories']);
        
        // Insert product inventory
        $this->insertProductInventory($productId, $data);
        
        // Insert product images
        $this->insertProductImages($productId, $data['images']);
        
        return $productId;
    }
    
    private function importVariantProduct($data)
    {
        // Get parent product
        $parentProduct = DB::table('products')
            ->where('sku', $data['parent_sku'])
            ->first();
        
        if (!$parentProduct) {
            throw new \Exception("Parent product not found: " . $data['parent_sku']);
        }
        
        // Get attribute family ID
        $attributeFamily = DB::table('attribute_families')
            ->where('code', $data['attribute_family'])
            ->first();
        
        if (!$attributeFamily) {
            throw new \Exception("Attribute family not found: " . $data['attribute_family']);
        }
        
        // Insert variant product
        $productId = DB::table('products')->insertGetId([
            'sku' => $data['sku'],
            'type' => $data['type'],
            'attribute_family_id' => $attributeFamily->id,
            'parent_id' => $parentProduct->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Insert product attribute values
        $this->insertProductAttributeValues($productId, $data);
        
        // Insert product categories
        $this->insertProductCategories($productId, $data['categories']);
        
        // Insert product inventory
        $this->insertProductInventory($productId, $data);
        
        // Insert product images
        $this->insertProductImages($productId, $data['images']);
        
        return $productId;
    }
    
    private function insertProductAttributeValues($productId, $data)
    {
        // Define attribute mappings
        $attributes = [
            'sku' => 'sku',
            'name' => 'name', 
            'price' => 'price',
            'status' => 'status',
            'visible_individually' => 'visible_individually',
            'short_description' => 'short_description',
            'description' => 'description',
            'url_key' => 'url_key',
            'service_duration' => 'service_duration',
            'room_type' => 'room_type',
            'modality' => 'modality',
        ];
        
        foreach ($attributes as $attrCode => $dataKey) {
            if (!isset($data[$dataKey]) || $data[$dataKey] === '') {
                continue;
            }
            
            $attribute = DB::table('attributes')->where('code', $attrCode)->first();
            if (!$attribute) {
                continue;
            }
            
            $value = $data[$dataKey];
            
            // Handle select attributes (need to get option ID)
            if ($attribute->type === 'select' && in_array($attrCode, ['service_duration', 'room_type', 'modality'])) {
                $option = DB::table('attribute_options as ao')
                    ->join('attribute_option_translations as aot', 'ao.id', '=', 'aot.attribute_option_id')
                    ->where('ao.attribute_id', $attribute->id)
                    ->where('aot.label', $value)
                    ->first();
                
                if ($option) {
                    $value = $option->id;
                } else {
                    $this->warn("Option not found for {$attrCode}: {$value}");
                    continue;
                }
            }
            
            // Insert attribute value
            DB::table('product_attribute_values')->insert([
                'product_id' => $productId,
                'attribute_id' => $attribute->id,
                'locale' => 'vi',
                'channel' => 'default',
                'text_value' => $attribute->type === 'select' ? null : $value,
                'integer_value' => in_array($attribute->type, ['select', 'boolean']) ? (int)$value : null,
                'float_value' => $attribute->type === 'price' ? (float)$value : null,
                'boolean_value' => $attribute->type === 'boolean' ? (bool)$value : null,
            ]);
        }
    }
    
    private function insertProductCategories($productId, $categorySlugs)
    {
        $slugs = explode(',', str_replace('"', '', $categorySlugs));
        
        foreach ($slugs as $slug) {
            $slug = trim($slug);
            $category = DB::table('categories as c')
                ->join('category_translations as ct', 'c.id', '=', 'ct.category_id')
                ->where('ct.slug', $slug)
                ->first();
            
            if ($category) {
                DB::table('product_categories')->insert([
                    'product_id' => $productId,
                    'category_id' => $category->id,
                ]);
            }
        }
    }
    
    private function insertProductInventory($productId, $data)
    {
        // Get default inventory source
        $inventorySource = DB::table('inventory_sources')->first();
        
        if ($inventorySource) {
            DB::table('product_inventories')->insert([
                'qty' => (int)$data['inventories'],
                'product_id' => $productId,
                'inventory_source_id' => $inventorySource->id,
            ]);
        }
    }
    
    private function insertProductImages($productId, $images)
    {
        $imageFiles = explode(',', str_replace('"', '', $images));
        $position = 0;
        
        foreach ($imageFiles as $image) {
            $image = trim($image);
            if ($image) {
                DB::table('product_images')->insert([
                    'product_id' => $productId,
                    'type' => 'image',
                    'path' => "product/{$productId}/{$image}",
                    'position' => $position++,
                ]);
            }
        }
    }
}
