<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MarketingCommManageController extends Controller
{
    // === Email Templates ===
    public function templateList(): JsonResponse
    {
        $data = DB::table('email_templates')->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function templateDetail(int $id): JsonResponse
    {
        $t = DB::table('email_templates')->where('id', $id)->first();
        if (!$t) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        return response()->json(['status' => 'success', 'data' => $t]);
    }

    public function templateStore(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'status' => 'sometimes|in:active,inactive,draft', 'content' => 'required|string']);
        $id = DB::table('email_templates')->insertGetId(array_merge($data, ['status' => $data['status'] ?? 'draft', 'created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function templateUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['name' => 'sometimes|string|max:255', 'status' => 'sometimes|in:active,inactive,draft', 'content' => 'sometimes|string']);
        DB::table('email_templates')->where('id', $id)->update(array_merge($data, ['updated_at' => now()]));
        return response()->json(['status' => 'success', 'message' => 'Updated']);
    }

    public function templateDestroy(int $id): JsonResponse
    {
        DB::table('email_templates')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Events ===
    public function eventList(): JsonResponse
    {
        $data = DB::table('events')->orderBy('date', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function eventStore(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'description' => 'sometimes|string', 'date' => 'required|date']);
        $id = DB::table('events')->insertGetId(array_merge($data, ['created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function eventDestroy(int $id): JsonResponse
    {
        DB::table('events')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Campaigns ===
    public function campaignList(): JsonResponse
    {
        $data = DB::table('campaigns')->orderBy('created_at', 'desc')->get();
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function campaignDetail(int $id): JsonResponse
    {
        $c = DB::table('campaigns')->where('id', $id)->first();
        if (!$c) return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        return response()->json(['status' => 'success', 'data' => $c]);
    }

    public function campaignStore(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'subject' => 'required|string', 'status' => 'sometimes|boolean', 'channel_id' => 'sometimes|integer', 'customer_group_id' => 'sometimes|integer', 'email_template_id' => 'sometimes|integer']);
        $id = DB::table('campaigns')->insertGetId(array_merge($data, ['status' => $data['status'] ?? 0, 'created_at' => now(), 'updated_at' => now()]));
        return response()->json(['status' => 'success', 'data' => ['id' => $id]], 201);
    }

    public function campaignUpdate(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['name' => 'sometimes|string|max:255', 'subject' => 'sometimes|string', 'status' => 'sometimes|boolean']);
        DB::table('campaigns')->where('id', $id)->update(array_merge($data, ['updated_at' => now()]));
        return response()->json(['status' => 'success', 'message' => 'Updated']);
    }

    public function campaignDestroy(int $id): JsonResponse
    {
        DB::table('campaigns')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }

    // === Subscribers ===
    public function subscriberList(Request $request): JsonResponse
    {
        $query = DB::table('subscribers_list')->orderBy('created_at', 'desc');
        if ($search = $request->input('search')) {
            $query->where('email', 'like', "%{$search}%");
        }
        $data = $query->paginate($request->integer('per_page', 20));
        return response()->json(['status' => 'success', 'data' => $data->items(), 'meta' => ['total' => $data->total(), 'current_page' => $data->currentPage(), 'last_page' => $data->lastPage()]]);
    }

    public function subscriberDestroy(int $id): JsonResponse
    {
        DB::table('subscribers_list')->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Deleted']);
    }
}
