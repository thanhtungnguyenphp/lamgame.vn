<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeGroup;

class SourceGameAttributesSeeder extends Seeder
{
    public function run(): void
    {
        // Find or create the Source Game Info group
        $sourceGameGroup = AttributeGroup::firstOrCreate(
            ['name' => 'Source Game Info', 'attribute_family_id' => 1],
            ['position' => 10, 'is_user_defined' => 1]
        );

        $attributes = [
            [
                'code' => 'game_engine',
                'admin_name' => 'Game Engine',
                'type' => 'select',
                'position' => 1,
                'is_filterable' => 1,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'is_comparable' => 1,
                'options' => ['Unity', 'Unreal Engine', 'Godot', 'Cocos2D', 'GameMaker', 'Construct 3'],
            ],
            [
                'code' => 'programming_language',
                'admin_name' => 'Programming Language',
                'type' => 'select',
                'position' => 2,
                'is_filterable' => 1,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'is_comparable' => 1,
                'options' => ['C#', 'C++', 'JavaScript', 'Python', 'Blueprint', 'GDScript'],
            ],
            [
                'code' => 'file_size',
                'admin_name' => 'File Size',
                'type' => 'text',
                'position' => 3,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'is_comparable' => 1,
            ],
            [
                'code' => 'downloads_count',
                'admin_name' => 'Downloads Count',
                'type' => 'text',
                'validation' => 'numeric',
                'position' => 4,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'default_value' => '0',
            ],
            [
                'code' => 'source_rating',
                'admin_name' => 'Source Rating',
                'type' => 'text',
                'validation' => 'decimal',
                'position' => 5,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'default_value' => '0.0',
            ],
            [
                'code' => 'source_category',
                'admin_name' => 'Source Category',
                'type' => 'select',
                'position' => 6,
                'is_filterable' => 1,
                'is_visible_on_front' => 1,
                'is_user_defined' => 1,
                'is_comparable' => 1,
                'options' => ['Classic', 'Modern', '2D', '3D', 'Mobile'],
            ],
        ];

        foreach ($attributes as $data) {
            $options = $data['options'] ?? [];
            unset($data['options']);

            $attribute = Attribute::where('code', $data['code'])->first();

            if ($attribute) {
                echo "Attribute {$data['code']} already exists (id={$attribute->id}), skipping...\n";
            } else {
                $attribute = Attribute::create($data);
                echo "Created attribute: {$data['code']} (id={$attribute->id})\n";
            }

            // Create options if they don't exist
            foreach ($options as $i => $optionName) {
                $exists = AttributeOption::where('attribute_id', $attribute->id)
                    ->where('admin_name', $optionName)->exists();
                if (! $exists) {
                    AttributeOption::create([
                        'attribute_id' => $attribute->id,
                        'admin_name' => $optionName,
                        'sort_order' => $i + 1,
                    ]);
                }
            }

            // Map to group if not already mapped
            $mapped = DB::table('attribute_group_mappings')
                ->where('attribute_id', $attribute->id)
                ->where('attribute_group_id', $sourceGameGroup->id)
                ->exists();

            if (! $mapped) {
                DB::table('attribute_group_mappings')->insert([
                    'attribute_id' => $attribute->id,
                    'attribute_group_id' => $sourceGameGroup->id,
                    'position' => $data['position'],
                ]);
            }
        }

        echo "SourceGameAttributesSeeder completed!\n";
    }
}
