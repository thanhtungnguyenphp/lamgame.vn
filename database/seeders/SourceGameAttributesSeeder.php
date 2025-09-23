<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeTranslation;
use Webkul\Attribute\Models\AttributeGroup;
use Webkul\Attribute\Models\AttributeGroupMapping;

class SourceGameAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create attribute group for source games
        $sourceGameGroup = AttributeGroup::create([
            'name' => 'Source Game Info',
            'position' => 10,
            'is_user_defined' => 1,
            'attribute_family_id' => 1, // Default attribute family
        ]);

        // Create attributes for source game products
        $attributes = [
            [
                'code' => 'game_engine',
                'admin_name' => 'Game Engine',
                'type' => 'select',
                'validation' => null,
                'position' => 1,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
                'options' => [
                    ['admin_name' => 'Unity', 'sort_order' => 1],
                    ['admin_name' => 'Unreal Engine', 'sort_order' => 2],
                    ['admin_name' => 'Godot', 'sort_order' => 3],
                    ['admin_name' => 'Cocos2D', 'sort_order' => 4],
                    ['admin_name' => 'GameMaker', 'sort_order' => 5],
                    ['admin_name' => 'Construct 3', 'sort_order' => 6],
                ]
            ],
            [
                'code' => 'programming_language',
                'admin_name' => 'Programming Language',
                'type' => 'select',
                'validation' => null,
                'position' => 2,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
                'options' => [
                    ['admin_name' => 'C#', 'sort_order' => 1],
                    ['admin_name' => 'C++', 'sort_order' => 2],
                    ['admin_name' => 'JavaScript', 'sort_order' => 3],
                    ['admin_name' => 'Python', 'sort_order' => 4],
                    ['admin_name' => 'Blueprint', 'sort_order' => 5],
                    ['admin_name' => 'GDScript', 'sort_order' => 6],
                ]
            ],
            [
                'code' => 'file_size',
                'admin_name' => 'File Size',
                'type' => 'text',
                'validation' => null,
                'position' => 3,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 0,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
            ],
            [
                'code' => 'downloads_count',
                'admin_name' => 'Downloads Count',
                'type' => 'text',
                'validation' => 'numeric',
                'position' => 4,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 0,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
                'default_value' => '0',
            ],
            [
                'code' => 'rating',
                'admin_name' => 'Rating',
                'type' => 'text',
                'validation' => 'decimal',
                'position' => 5,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 0,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
                'default_value' => '0.0',
            ],
            [
                'code' => 'source_category',
                'admin_name' => 'Source Category',
                'type' => 'select',
                'validation' => null,
                'position' => 6,
                'is_required' => 0,
                'is_unique' => 0,
                'value_per_locale' => 0,
                'value_per_channel' => 0,
                'is_filterable' => 1,
                'is_configurable' => 0,
                'is_user_defined' => 1,
                'is_visible_on_front' => 1,
                'is_comparable' => 1,
                'enable_wysiwyg' => 0,
                'options' => [
                    ['admin_name' => 'Classic', 'sort_order' => 1],
                    ['admin_name' => 'Modern', 'sort_order' => 2],
                    ['admin_name' => '2D', 'sort_order' => 3],
                    ['admin_name' => '3D', 'sort_order' => 4],
                    ['admin_name' => 'Mobile', 'sort_order' => 5],
                ]
            ],
        ];

        foreach ($attributes as $attributeData) {
            // Check if attribute already exists
            $existingAttribute = Attribute::where('code', $attributeData['code'])->first();
            
            if ($existingAttribute) {
                echo "Attribute {$attributeData['code']} already exists, skipping...\n";
                continue;
            }

            // Create attribute
            $attribute = Attribute::create($attributeData);

            // Create attribute group mapping
            AttributeGroupMapping::create([
                'attribute_id' => $attribute->id,
                'attribute_group_id' => $sourceGameGroup->id,
                'position' => $attributeData['position'],
            ]);

            echo "Created attribute: {$attributeData['code']}\n";
        }

        echo "Source Game attributes seeder completed successfully!\n";
    }
}
