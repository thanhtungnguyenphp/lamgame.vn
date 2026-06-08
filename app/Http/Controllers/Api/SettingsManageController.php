<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsManageController extends Controller
{
    // === Locales ===
    public function localeList(): JsonResponse
    {
        $locales = DB::table('locales')->orderBy('name')->get();
        return response()->json(['status' => 'success', 'data' => $locales]);
    }

    public function localeStore(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => 'required|string|max:10|unique:locales,code', 'name' => 'required|string|max:255', 'direction' => 'sometimes|in:ltr,rtl']);
        $id = DB::table('locales')->insertGetId(array_merge($data, ['direction' => $data['direction'] ?? 'ltr']));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function localeUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['name' => 'sometimes|string|max:255', 'direction' => 'sometimes|in:ltr,rtl']);
        DB::table('locales')->where('id', $id)->update($data);
        return response()->json(['status' => 'success', 'message' => 'Locale updated']);
    }

    public function localeDestroy(int $id): JsonResponse
    {
        DB::table('locales')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Locale deleted']);
    }

    // === Currencies ===
    public function currencyList(): JsonResponse
    {
        $currencies = DB::table('currencies')->orderBy('name')->get();
        return response()->json(['status' => 'success', 'data' => $currencies]);
    }

    public function currencyStore(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => 'required|string|max:5|unique:currencies,code', 'name' => 'required|string|max:255', 'symbol' => 'sometimes|string|max:10']);
        $id = DB::table('currencies')->insertGetId($data);
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function currencyUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['name' => 'sometimes|string|max:255', 'symbol' => 'sometimes|string|max:10']);
        DB::table('currencies')->where('id', $id)->update($data);
        return response()->json(['status' => 'success', 'message' => 'Currency updated']);
    }

    public function currencyDestroy(int $id): JsonResponse
    {
        DB::table('currencies')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Currency deleted']);
    }

    // === Exchange Rates ===
    public function exchangeRateList(): JsonResponse
    {
        $rates = DB::table('currency_exchange_rates')->get();
        return response()->json(['status' => 'success', 'data' => $rates]);
    }

    public function exchangeRateStore(Request $request): JsonResponse
    {
        $data = $request->validate(['target_currency' => 'required|integer|exists:currencies,id', 'rate' => 'required|numeric|min:0']);
        $id = DB::table('currency_exchange_rates')->insertGetId($data);
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function exchangeRateUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['rate' => 'required|numeric|min:0']);
        DB::table('currency_exchange_rates')->where('id', $id)->update($data);
        return response()->json(['status' => 'success', 'message' => 'Rate updated']);
    }

    public function exchangeRateDestroy(int $id): JsonResponse
    {
        DB::table('currency_exchange_rates')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Rate deleted']);
    }

    // === Channels ===
    public function channelList(): JsonResponse
    {
        $channels = DB::table('channels')->get();
        return response()->json(['status' => 'success', 'data' => $channels]);
    }

    public function channelDetail(int $id): JsonResponse
    {
        $channel = DB::table('channels')->where('id', $id)->first();
        if (!$channel) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        return response()->json(['status' => 'success', 'data' => $channel]);
    }

    // === Inventory Sources ===
    public function inventorySourceList(): JsonResponse
    {
        $sources = DB::table('inventory_sources')->orderBy('name')->get();
        return response()->json(['status' => 'success', 'data' => $sources]);
    }

    // === Tax Categories ===
    public function taxCategoryList(): JsonResponse
    {
        $cats = DB::table('tax_categories')->get();
        return response()->json(['status' => 'success', 'data' => $cats]);
    }

    public function taxCategoryStore(Request $request): JsonResponse
    {
        $data = $request->validate(['code' => 'required|string|unique:tax_categories,code', 'name' => 'required|string|max:255', 'description' => 'sometimes|string']);
        $id = DB::table('tax_categories')->insertGetId($data);
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    // === Tax Rates ===
    public function taxRateList(): JsonResponse
    {
        $rates = DB::table('tax_rates')->get();
        return response()->json(['status' => 'success', 'data' => $rates]);
    }

    public function taxRateStore(Request $request): JsonResponse
    {
        $data = $request->validate(['identifier' => 'required|string|unique:tax_rates,identifier', 'country' => 'required|string', 'tax_rate' => 'required|numeric', 'is_zip' => 'sometimes|boolean']);
        $id = DB::table('tax_rates')->insertGetId($data);
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    // === Users ===
    public function userList(): JsonResponse
    {
        $users = DB::table('admins')->select('id', 'name', 'email', 'role_id', 'status', 'created_at')->get();
        return response()->json(['status' => 'success', 'data' => $users]);
    }

    public function userDetail(int $id): JsonResponse
    {
        $user = DB::table('admins')->where('id', $id)->first();
        if (!$user) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        unset($user->password);
        return response()->json(['status' => 'success', 'data' => $user]);
    }

    // === Roles ===
    public function roleList(): JsonResponse
    {
        $roles = DB::table('roles')->get();
        return response()->json(['status' => 'success', 'data' => $roles]);
    }

    public function roleDetail(int $id): JsonResponse
    {
        $role = DB::table('roles')->where('id', $id)->first();
        if (!$role) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        return response()->json(['status' => 'success', 'data' => $role]);
    }

    // === Themes ===
    public function themeList(): JsonResponse
    {
        $themes = DB::table('theme_customizations')->orderBy('sort_order')->get();
        return response()->json(['status' => 'success', 'data' => $themes]);
    }
}
