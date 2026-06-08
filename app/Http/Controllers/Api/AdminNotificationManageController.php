<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminNotificationManageController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $query = DB::table('admin_notifications')->orderBy('created_at', 'desc');

        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'status' => 'success',
            'data' => collect($notifications->items())->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->type ?? 'order',
                'order_id' => $n->order_id ?? null,
                'read' => !is_null($n->read_at ?? null),
                'created_at' => $n->created_at,
            ]),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
                'unread_count' => DB::table('admin_notifications')->whereNull('read_at')->count(),
            ],
        ]);
    }

    public function unreadCount(): JsonResponse
    {
        $count = DB::table('admin_notifications')->whereNull('read_at')->count();
        return response()->json(['status' => 'success', 'data' => ['unread_count' => $count]]);
    }

    public function markRead(int $id): JsonResponse
    {
        DB::table('admin_notifications')->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['status' => 'success', 'message' => 'Notification marked as read']);
    }

    public function markAllRead(): JsonResponse
    {
        $count = DB::table('admin_notifications')->whereNull('read_at')->update(['read_at' => now()]);
        return response()->json(['status' => 'success', 'message' => "{$count} notifications marked as read"]);
    }
}
