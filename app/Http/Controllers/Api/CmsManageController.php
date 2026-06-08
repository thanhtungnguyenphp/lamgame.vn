<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Webkul\CMS\Repositories\PageRepository;

class CmsManageController extends Controller
{
    public function __construct(protected PageRepository $pageRepository) {}

    public function list(Request $request): JsonResponse
    {
        $query = \Webkul\CMS\Models\Page::with(['translations']);

        if ($search = $request->input('search')) {
            $query->whereHas('translations', fn ($q) => $q->where('page_title', 'like', "%{$search}%")->orWhere('url_key', 'like', "%{$search}%"));
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate($request->integer('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $pages->map(fn ($p) => [
                'id' => $p->id,
                'title' => $p->page_title,
                'url_key' => $p->url_key,
                'layout' => $p->layout,
                'created_at' => $p->created_at?->toIso8601String(),
            ]),
            'meta' => ['current_page' => $pages->currentPage(), 'last_page' => $pages->lastPage(), 'total' => $pages->total()],
        ]);
    }

    public function detail(int $id): JsonResponse
    {
        $page = \Webkul\CMS\Models\Page::with(['translations'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $page->id,
                'layout' => $page->layout,
                'title' => $page->page_title,
                'url_key' => $page->url_key,
                'html_content' => $page->html_content,
                'meta_title' => $page->meta_title,
                'meta_description' => $page->meta_description,
                'meta_keywords' => $page->meta_keywords,
                'created_at' => $page->created_at?->toIso8601String(),
                'updated_at' => $page->updated_at?->toIso8601String(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'page_title' => 'required|string|max:255',
            'url_key' => 'required|string|max:255|unique:cms_page_translations,url_key',
            'html_content' => 'sometimes|string',
            'meta_title' => 'sometimes|string|max:255',
            'meta_description' => 'sometimes|string',
            'meta_keywords' => 'sometimes|string',
            'layout' => 'sometimes|string',
        ]);

        $locale = app()->getLocale();
        $page = $this->pageRepository->create([
            'layout' => $data['layout'] ?? null,
            $locale => [
                'page_title' => $data['page_title'],
                'url_key' => $data['url_key'],
                'html_content' => $data['html_content'] ?? '',
                'meta_title' => $data['meta_title'] ?? '',
                'meta_description' => $data['meta_description'] ?? '',
                'meta_keywords' => $data['meta_keywords'] ?? '',
            ],
            'channels' => [1],
        ]);

        return response()->json(['status' => 'success', 'message' => 'Page created', 'data' => ['id' => $page->id]], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'page_title' => 'sometimes|string|max:255',
            'url_key' => 'sometimes|string|max:255',
            'html_content' => 'sometimes|string',
            'meta_title' => 'sometimes|string|max:255',
            'meta_description' => 'sometimes|string',
            'meta_keywords' => 'sometimes|string',
            'layout' => 'sometimes|string',
        ]);

        $locale = app()->getLocale();
        $updateData = ['layout' => $data['layout'] ?? null];
        $updateData[$locale] = array_filter([
            'page_title' => $data['page_title'] ?? null,
            'url_key' => $data['url_key'] ?? null,
            'html_content' => $data['html_content'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => $data['meta_keywords'] ?? null,
        ], fn ($v) => $v !== null);

        $this->pageRepository->update($updateData, $id);
        return response()->json(['status' => 'success', 'message' => 'Page updated']);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->pageRepository->delete($id);
        return response()->json(['status' => 'success', 'message' => 'Page deleted']);
    }

    public function massDestroy(Request $request): JsonResponse
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];
        foreach ($ids as $id) {
            $this->pageRepository->delete($id);
        }
        return response()->json(['status' => 'success', 'message' => count($ids) . ' pages deleted']);
    }
}
