<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Models\UserTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSyncController extends Controller
{
    public function sync(Request $request): JsonResponse
    {
        $request->validate([
            'tickets'              => 'required|array|max:100',
            'tickets.*.id'         => 'required|string',
            'tickets.*.numbers'    => 'required|array|min:1',
            'tickets.*.numbers.*'  => 'required|string|regex:/^\d{2,6}$/',
            'tickets.*.region'     => 'required|in:mien-nam,mien-trung,mien-bac',
            'tickets.*.draw_date'  => 'required|date_format:Y-m-d',
            'tickets.*.province_code' => 'nullable|string|max:10',
            'tickets.*.status'     => 'nullable|in:pending,won,lost',
            'last_sync'            => 'nullable|date',
        ]);

        $uid = $request->input('firebase_uid');
        $synced = 0;

        foreach ($request->input('tickets') as $t) {
            UserTicket::updateOrCreate(
                ['firebase_uid' => $uid, 'client_id' => $t['id']],
                [
                    'ticket_id'     => $t['id'],
                    'numbers'       => $t['numbers'],
                    'region'        => $t['region'],
                    'province_code' => $t['province_code'] ?? null,
                    'draw_date'     => $t['draw_date'],
                    'status'        => $t['status'] ?? 'pending',
                ]
            );
            $synced++;
        }

        // Trả về vé từ server mà client có thể chưa có
        $serverTickets = UserTicket::where('firebase_uid', $uid)
            ->when($request->input('last_sync'), fn ($q, $v) => $q->where('updated_at', '>', $v))
            ->orderByDesc('draw_date')
            ->limit(100)
            ->get()
            ->map(fn ($t) => [
                'id'             => $t->client_id ?? $t->ticket_id,
                'numbers'        => $t->numbers,
                'region'         => $t->region,
                'province_code'  => $t->province_code,
                'draw_date'      => $t->draw_date->toDateString(),
                'status'         => $t->status,
                'matched_prizes' => $t->matched_prizes,
            ]);

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'synced'         => $synced,
                'server_tickets' => $serverTickets,
                'last_sync'      => now()->toIso8601String(),
            ],
        ]);
    }
}
