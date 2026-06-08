<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\Category\Repositories\CategoryRepository;

class CategoryManageController extends Controller
{
    public function __construct(protected CategoryRepository $categoryRepository) {}

    public function list(Request $request): JsonResponse
    {
        $query = \Webkul\Category\Models\Category::with(['translation']);

        if ($search = $request->input('search')) {
            $query->whereHas('translation', fn ($q) => $q->where('name', 'like', "%{$search}%"));
        }

        if ($parent = $request->input('parent_id')) {
            $query->where('parent_id', $parent);
        }

        $categories = $query->orderBy('position')->get()->map(fn ($cat) => [
            'id' => $cat->id,
            'name' => $cat->name,
            'slug' => $cat->slug,
            'position' => $cat->position,
            'status' => (bool) $cat->status,
            'parent_id' => $cat->parent_id,
            'products_count' => $cat->products()->count(),
        ]);

        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    public function tree(): JsonResponse
    {
        $categories = $this->categoryRepository->getVisibleCategoryTree(null);

        return response()->json(['status' => 'success', 'data' => $categories]);
    }

    public function detail(int $id): JsonResponse
    {
        $cat = \Webkul\Category\Models\Category::with(['translation', 'children'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $cat->id,
                'name' => $cat->name,
                'slug' => $cat->slug,
                'description' => $cat->description,
                'position' => $cat->position,
                'status' => (bool) $cat->status,
                'display_mode' => $cat->display_mode,
                'parent_id' => $cat->parent_id,
                'image_url' => $cat->image_url,
                'children' => $cat->children->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug]),
                'meta_title' => $cat->meta_title,
                'meta_description' => $cat->meta_description,
                'meta_keywords' => $cat->meta_keywords,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:category_translations,slug',
            'parent_id' => 'sometimes|integer|exists:categories,id',
            'position' => 'sometimes|integer',
            'status' => 'sometimes|boolean',
            'description' => 'sometimes|string',
            'meta_title' => 'sometimes|string|max:255',
            'meta_description' => 'sometimes|string',
            'meta_keywords' => 'sometimes|string',
        ]);

        $locale = app()->getLocale();
        $category = $this->categoryRepository->create([
            'status' => $data['status'] ?? 1,
            'position' => $data['position'] ?? 0,
            'parent_id' => $data['parent_id'] ?? 1,
            'display_mode' => 'products_and_description',
            $locale => [
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? '',
                'meta_title' => $data['meta_title'] ?? '',
                'meta_description' => $data['meta_description'] ?? '',
                'meta_keywords' => $data['meta_keywords'] ?? '',
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created',
            'data' => ['id' => $category->id, 'name' => $data['name'], 'slug' => $data['slug']],
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'parent_id' => 'sometimes|integer|exists:categories,id',
            'position' => 'sometimes|integer',
            'status' => 'sometimes|boolean',
            'description' => 'sometimes|string',
            'meta_title' => 'sometimes|string|max:255',
            'meta_description' => 'sometimes|string',
            'meta_keywords' => 'sometimes|string',
        ]);

        $locale = app()->getLocale();
        $updateData = array_filter([
            'status' => $data['status'] ?? null,
            'position' => $data['position'] ?? null,
            'parent_id' => $data['parent_id'] ?? null,
        ], fn ($v) => $v !== null);

        $localeData = array_filter([
            'name' => $data['name'] ?? null,
            'slug' => $data['slug'] ?? null,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
        ], fn ($v) => $v !== null);

        if (!empty($localeData)) {
            $updateData[$locale] = $localeData;
        }

        $this->categoryRepository->update($updateData, $id);

        return response()->json(['status' => 'success', 'message' => 'Category updated']);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryRepository->delete($id);

        return response()->json(['status' => 'success', 'message' => 'Category deleted']);
    }

    public function massDestroy(Request $request): JsonResponse
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        foreach ($ids as $id) {
            $this->categoryRepository->delete($id);
        }

        return response()->json(['status' => 'success', 'message' => count($ids) . ' categories deleted']);
    }

    public function massUpdate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'status' => 'required|boolean',
        ]);

        \Webkul\Category\Models\Category::whereIn('id', $data['ids'])->update(['status' => $data['status']]);

        return response()->json(['status' => 'success', 'message' => count($data['ids']) . ' categories updated']);
    }
}
