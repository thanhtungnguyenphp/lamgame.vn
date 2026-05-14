<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $blogs = Blog::latest()->paginate($request->get('per_page', 15));
        return response()->json(['success' => true, 'data' => $blogs]);
    }

    public function statistics(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => [
            'total' => Blog::count(),
            'published' => Blog::where('status', 'published')->count(),
            'draft' => Blog::where('status', 'draft')->count(),
        ]]);
    }

    public function detail(int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'in:draft,published,scheduled',
        ]);
        $blog = Blog::create($validated);
        return response()->json(['success' => true, 'data' => $blog], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->update($request->only(['title', 'content', 'status', 'meta_title', 'meta_description']));
        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function destroy(int $id): JsonResponse
    {
        Blog::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }

    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $blog = Blog::findOrFail($id);
        $blog->update(['status' => $request->input('status', 'published')]);
        return response()->json(['success' => true, 'data' => $blog]);
    }

    public function bulkChangeStatus(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        Blog::whereIn('id', $ids)->update(['status' => $request->input('status')]);
        return response()->json(['success' => true, 'updated' => count($ids)]);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $ids = $request->input('ids', []);
        Blog::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'deleted' => count($ids)]);
    }

    public function categoryList(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => BlogCategory::withCount('blogs')->get()]);
    }

    public function categoryStore(Request $request): JsonResponse
    {
        $cat = BlogCategory::create($request->validate(['name' => 'required|string|max:100']));
        return response()->json(['success' => true, 'data' => $cat], 201);
    }

    public function categoryUpdate(Request $request, int $id): JsonResponse
    {
        $cat = BlogCategory::findOrFail($id);
        $cat->update($request->only(['name', 'slug']));
        return response()->json(['success' => true, 'data' => $cat]);
    }

    public function categoryDestroy(int $id): JsonResponse
    {
        BlogCategory::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }

    public function tagList(): JsonResponse
    {
        return response()->json(['success' => true, 'data' => BlogTag::withCount('blogs')->get()]);
    }

    public function tagStore(Request $request): JsonResponse
    {
        $tag = BlogTag::create($request->validate(['name' => 'required|string|max:50']));
        return response()->json(['success' => true, 'data' => $tag], 201);
    }

    public function tagDestroy(int $id): JsonResponse
    {
        BlogTag::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Deleted']);
    }
}
