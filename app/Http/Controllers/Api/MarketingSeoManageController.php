<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingSeoManageController extends Controller
{
    // === URL Rewrites ===
    public function urlRewriteList(Request $request): JsonResponse
    {
        $query = DB::table('url_rewrites')->orderBy('created_at', 'desc');
        if ($search = $request->input('search')) {
            $query->where('url', 'like', "%{$search}%")->orWhere('redirect_url', 'like', "%{$search}%");
        }
        $data = $query->paginate($request->integer('per_page', 20));
        return response()->json(['status' => 'success', 'data' => $data->items(), 'meta' => ['total' => $data->total(), 'current_page' => $data->currentPage(), 'last_page' => $data->lastPage()]]);
    }

    public function urlRewriteStore(Request $request): JsonResponse
    {
        $data = $request->validate(['url' => 'required|string', 'redirect_url' => 'required|string', 'locale' => 'sometimes|string']);
        $id = DB::table('url_rewrites')->insertGetId(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function urlRewriteUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['url' => 'sometimes|string', 'redirect_url' => 'sometimes|string']);
        DB::table('url_rewrites')->where('id', $id)->update(array_merge($data, ['updated_at' => now()]));
        return response()->json(['status' => 'success', 'message' => 'Updated']);
    }

    public function urlRewriteDestroy(int $id): JsonResponse
    {
        DB::table('url_rewrites')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Search Terms ===
    public function searchTermList(Request $request): JsonResponse
    {
        $data = DB::table('search_terms')->orderBy('uses', 'desc')->paginate($request->integer('per_page', 20));
        return response()->json(['status' => 'success', 'data' => $data->items(), 'meta' => ['total' => $data->total(), 'current_page' => $data->currentPage(), 'last_page' => $data->lastPage()]]);
    }

    public function searchTermStore(Request $request): JsonResponse
    {
        $data = $request->validate(['term' => 'required|string|max:255', 'redirect_url' => 'sometimes|string', 'channel_id' => 'sometimes|integer', 'locale' => 'sometimes|string']);
        $id = DB::table('search_terms')->insertGetId(array_merge($data, ['uses' => 0, 'created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function searchTermDestroy(int $id): JsonResponse
    {
        DB::table('search_terms')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Search Synonyms ===
    public function searchSynonymList(Request $request): JsonResponse
    {
        $data = DB::table('search_synonyms')->paginate($request->integer('per_page', 20));
        return response()->json(['status' => 'success', 'data' => $data->items(), 'meta' => ['total' => $data->total(), 'current_page' => $data->currentPage(), 'last_page' => $data->lastPage()]]);
    }

    public function searchSynonymStore(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'mapping' => 'required|string']);
        $id = DB::table('search_synonyms')->insertGetId(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function searchSynonymDestroy(int $id): JsonResponse
    {
        DB::table('search_synonyms')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Sitemaps ===
    public function sitemapList(): JsonResponse
    {
        $data = DB::table('sitemaps')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function sitemapStore(Request $request): JsonResponse
    {
        $data = $request->validate(['file_name' => 'required|string', 'path' => 'required|string']);
        $id = DB::table('sitemaps')->insertGetId(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function sitemapDestroy(int $id): JsonResponse
    {
        DB::table('sitemaps')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }
}
