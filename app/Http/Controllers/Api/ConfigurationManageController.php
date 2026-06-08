<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConfigurationManageController extends Controller
{
    public function index(): JsonResponse
    {
        $configs = DB::table('core_config')->select('code', 'value', 'channel_code', 'locale_code')->get()
            ->groupBy('code')->map(fn ($items) => $items->first()->value);

        return response()->json(['status' => 'success', 'data' => $configs]);
    }

    public function getBySlug(string $slug): JsonResponse
    {
        $configs = DB::table('core_config')->where('code', 'like', "{$slug}%")->get()
            ->map(fn ($c) => ['code' => $c->code, 'value' => $c->value, 'channel' => $c->channel_code, 'locale' => $c->locale_code]);

        return response()->json(['status' => 'success', 'data' => $configs]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'configs' => 'required|array',
            'configs.*.code' => 'required|string',
            'configs.*.value' => 'present',
            'channel_code' => 'sometimes|string',
            'locale_code' => 'sometimes|string',
        ]);

        $channel = $data['channel_code'] ?? null;
        $locale = $data['locale_code'] ?? null;

        foreach ($data['configs'] as $config) {
            DB::table('core_config')->updateOrInsert(
                ['code' => $config['code'], 'channel_code' => $channel, 'locale_code' => $locale],
                ['value' => $config['value']]
            );
        }

        return response()->json(['status' => 'success', 'message' => count($data['configs']) . ' configs updated']);
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $configs = DB::table('core_config')->where('code', 'like', "%{$q}%")->limit(50)->get();
        return response()->json(['status' => 'success', 'data' => $configs]);
    }
}
