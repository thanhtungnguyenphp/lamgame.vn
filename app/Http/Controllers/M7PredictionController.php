<?php

namespace App\Http\Controllers;

use App\Models\M7Match;
use App\Models\M7Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class M7PredictionController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('shop.customer.session.create', ['redirect' => url()->previous()]);
        }

        $request->validate([
            'landing_page_id' => 'required|exists:landing_pages,id',
            'type'            => 'required|in:champion,mvp,match',
            'pick'            => 'required|string|max:100',
            'match_id'        => 'nullable|exists:m7_matches,id',
        ]);

        $userId = Auth::guard('customer')->id();
        $pageId = $request->landing_page_id;

        if ($request->type === 'match' && $request->match_id) {
            $match = M7Match::findOrFail($request->match_id);
            if (!$match->isUpcoming()) {
                return back()->with('error', 'Trận đấu đã bắt đầu, không thể dự đoán.');
            }
        }

        $existing = M7Prediction::where('landing_page_id', $pageId)
            ->where('user_id', $userId)
            ->where('type', $request->type);

        if ($request->match_id) {
            $existing->where('match_id', $request->match_id);
        }

        if ($existing->exists()) {
            $existing->update(['pick' => $request->pick]);
            return back()->with('success', 'Đã cập nhật dự đoán!');
        }

        M7Prediction::create([
            'landing_page_id' => $pageId,
            'user_id'         => $userId,
            'match_id'        => $request->match_id,
            'type'            => $request->type,
            'pick'            => $request->pick,
        ]);

        return back()->with('success', 'Dự đoán thành công! 🎉');
    }

    public function leaderboard(Request $request)
    {
        $pageId = $request->query('page_id');

        $query = M7Prediction::where('points', '>', 0)->whereNotNull('user_id');
        if ($pageId) {
            $query->where('landing_page_id', $pageId);
        }

        return response()->json(
            $query->selectRaw('user_id, SUM(points) as total_points, COUNT(*) as total_picks')
                ->groupBy('user_id')
                ->orderByDesc('total_points')
                ->limit(20)
                ->get()
        );
    }
}
