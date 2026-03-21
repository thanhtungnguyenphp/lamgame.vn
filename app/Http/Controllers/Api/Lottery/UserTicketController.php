<?php

namespace App\Http\Controllers\Api\Lottery;

use App\Http\Controllers\Controller;
use App\Models\UserTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserTicketController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token'     => 'required|string|max:500',
            'numbers'       => 'required|array|min:1|max:20',
            'numbers.*'     => 'required|string|regex:/^\d{2,6}$/',
            'region'        => 'required|in:mien-nam,mien-trung,mien-bac',
            'province_code' => 'nullable|string|max:10',
            'draw_date'     => 'required|date_format:Y-m-d|after_or_equal:today',
        ]);

        $ticket = UserTicket::create([
            'ticket_id'     => 't_' . Str::random(10),
            'fcm_token'     => $request->input('fcm_token'),
            'numbers'       => $request->input('numbers'),
            'region'        => $request->input('region'),
            'province_code' => $request->input('province_code'),
            'draw_date'     => $request->input('draw_date'),
            'status'        => 'pending',
        ]);

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'ticket_id' => $ticket->ticket_id,
                'status'    => $ticket->status,
            ],
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string',
            'status'    => 'nullable|in:pending,won,lost',
        ]);

        $query = UserTicket::where('fcm_token', $request->input('fcm_token'))
            ->orderByDesc('draw_date');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $tickets = $query->limit(50)->get()->map(fn ($t) => [
            'ticket_id'      => $t->ticket_id,
            'numbers'        => $t->numbers,
            'region'         => $t->region,
            'province_code'  => $t->province_code,
            'draw_date'      => $t->draw_date->toDateString(),
            'status'         => $t->status,
            'matched_prizes' => $t->matched_prizes,
        ]);

        return response()->json([
            'status' => 'ok',
            'data'   => $tickets,
        ]);
    }

    public function show(string $ticketId): JsonResponse
    {
        $ticket = UserTicket::where('ticket_id', $ticketId)->first();

        if (!$ticket) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'NOT_FOUND', 'message' => 'Vé không tồn tại.'],
            ], 404);
        }

        return response()->json([
            'status' => 'ok',
            'data'   => [
                'ticket_id'      => $ticket->ticket_id,
                'numbers'        => $ticket->numbers,
                'region'         => $ticket->region,
                'province_code'  => $ticket->province_code,
                'draw_date'      => $ticket->draw_date->toDateString(),
                'status'         => $ticket->status,
                'matched_prizes' => $ticket->matched_prizes,
                'created_at'     => $ticket->created_at->toIso8601String(),
            ],
        ]);
    }

    public function destroy(string $ticketId): JsonResponse
    {
        $deleted = UserTicket::where('ticket_id', $ticketId)->delete();

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'error'  => ['code' => 'NOT_FOUND', 'message' => 'Vé không tồn tại.'],
            ], 404);
        }

        return response()->json(['status' => 'ok']);
    }
}
