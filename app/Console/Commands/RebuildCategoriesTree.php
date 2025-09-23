<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Category\Models\Category;

class RebuildCategoriesTree extends Command
{
    protected $signature = 'categories:rebuild-tree';
    protected $description = 'Rebuild categories nested set tree structure';

    public function handle()
    {
        $this->info('Starting categories tree rebuild...');
        
        try {
            $this->rebuildTree();
            $this->info('Categories tree rebuilt successfully!');
        } catch (\Exception $e) {
            $this->error('Error rebuilding tree: ' . $e->getMessage());
        }
    }

    private function rebuildTree($parentId = null, $left = 1)
    {
        $right = $left + 1;
        
        // Get all children of current parent
        $children = Category::where('parent_id', $parentId)
                           ->orderBy('position')
                           ->orderBy('id')
                           ->get();
        
        foreach ($children as $child) {
            // Recursively rebuild for children
            $right = $this->rebuildTree($child->id, $right);
        }
        
        // Update current node
        if ($parentId !== null) {
            Category::where('id', $parentId)->update([
                '_lft' => $left,
                '_rgt' => $right
            ]);
            
            $this->line("Updated category {$parentId}: lft={$left}, rgt={$right}");
        }
        
        return $right + 1;
    }
}
