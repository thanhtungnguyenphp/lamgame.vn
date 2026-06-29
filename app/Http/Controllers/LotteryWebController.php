<?php

namespace App\Http\Controllers;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Services\Lottery\LotteryStatisticsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LotteryWebController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $data = Cache::remember("lottery:web:index:{$today}", 300, function () use ($today) {
            return [
                'mienBac'  => LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-bac')->where('date', $today)->first(),
                'mienTrung' => LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-trung')->where('date', $today)->get(),
                'mienNam'  => LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-nam')->where('date', $today)->get(),
                'vietlot'  => LotteryDraw::with('result')->where('type', 'vietlot')->where('date', $today)->whereIn('game', ['power655', 'mega645', 'max3d'])->get(),
            ];
        });

        return view('lamgame.pages.lottery.index', [
            'data'  => $data,
            'date'  => $today,
            'title' => 'Kết quả xổ số hôm nay ' . Carbon::today()->format('d/m/Y'),
            'description' => 'Kết quả xổ số hôm nay - KQXS miền Bắc, miền Trung, miền Nam và Vietlott (Power 6/55, Mega 6/45, Keno) cập nhật nhanh nhất.',
        ]);
    }

    public function mienBac()
    {
        $draws = Cache::remember('lottery:web:mien-bac', 300, fn () =>
            LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-bac')
                ->orderByDesc('date')->limit(7)->get()
        );

        return view('lamgame.pages.lottery.region', [
            'draws'  => $draws,
            'region' => 'mien-bac',
            'title'  => 'Kết quả xổ số miền Bắc - XSMB hôm nay',
            'description' => 'XSMB - Kết quả xổ số miền Bắc hôm nay, trực tiếp nhanh nhất. Xem KQXS miền Bắc 30 ngày.',
        ]);
    }

    public function mienTrung()
    {
        $draws = Cache::remember('lottery:web:mien-trung', 300, fn () =>
            LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-trung')
                ->orderByDesc('date')->limit(14)->get()
        );

        return view('lamgame.pages.lottery.region', [
            'draws'  => $draws,
            'region' => 'mien-trung',
            'title'  => 'Kết quả xổ số miền Trung - XSMT hôm nay',
            'description' => 'XSMT - Kết quả xổ số miền Trung hôm nay, cập nhật lúc 17:15 hàng ngày.',
        ]);
    }

    public function mienNam()
    {
        $draws = Cache::remember('lottery:web:mien-nam', 300, fn () =>
            LotteryDraw::with(['results.province'])->where('type', 'traditional')->where('region', 'mien-nam')
                ->orderByDesc('date')->limit(14)->get()
        );

        return view('lamgame.pages.lottery.region', [
            'draws'  => $draws,
            'region' => 'mien-nam',
            'title'  => 'Kết quả xổ số miền Nam - XSMN hôm nay',
            'description' => 'XSMN - Kết quả xổ số miền Nam hôm nay, cập nhật lúc 16:15 hàng ngày.',
        ]);
    }

    public function vietlott()
    {
        $data = Cache::remember('lottery:web:vietlott', 300, fn () => [
            'power655' => LotteryDraw::with('result')->where('game', 'power655')->orderByDesc('date')->first(),
            'mega645'  => LotteryDraw::with('result')->where('game', 'mega645')->orderByDesc('date')->first(),
            'max3d'    => LotteryDraw::with('result')->where('game', 'max3d')->orderByDesc('date')->first(),
            'keno'     => LotteryDraw::with('result')->where('game', 'keno')->orderByDesc('date')->first(),
        ]);

        return view('lamgame.pages.lottery.vietlott', [
            'data'  => $data,
            'title' => 'Kết quả Vietlott hôm nay - Power 6/55, Mega 6/45, Keno',
            'description' => 'Kết quả xổ số Vietlott hôm nay - Power 6/55, Mega 6/45, Max 3D, Keno. Cập nhật Jackpot mới nhất.',
        ]);
    }

    public function power655()
    {
        $draws = Cache::remember('lottery:web:power655', 300, fn () =>
            LotteryDraw::with('result')->where('game', 'power655')->orderByDesc('date')->limit(10)->get()
        );

        return view('lamgame.pages.lottery.game', [
            'draws' => $draws,
            'game'  => 'power655',
            'title' => 'Kết quả Vietlott Power 6/55 - Xổ số Power hôm nay',
            'description' => 'Kết quả xổ số Vietlott Power 6/55 mới nhất. Cập nhật Jackpot, thống kê số hot.',
        ]);
    }

    public function mega645()
    {
        $draws = Cache::remember('lottery:web:mega645', 300, fn () =>
            LotteryDraw::with('result')->where('game', 'mega645')->orderByDesc('date')->limit(10)->get()
        );

        return view('lamgame.pages.lottery.game', [
            'draws' => $draws,
            'game'  => 'mega645',
            'title' => 'Kết quả Vietlott Mega 6/45 - Xổ số Mega hôm nay',
            'description' => 'Kết quả xổ số Vietlott Mega 6/45 mới nhất. Cập nhật Jackpot, thống kê tần suất.',
        ]);
    }

    public function kenoResult()
    {
        $draws = Cache::remember('lottery:web:keno', 120, fn () =>
            LotteryDraw::with('result')->where('game', 'keno')
                ->orderByDesc('date')->orderByDesc('period')->limit(20)->get()
        );

        return view('lamgame.pages.lottery.game', [
            'draws' => $draws,
            'game'  => 'keno',
            'title' => 'Kết quả Vietlott Keno - Xổ số Keno trực tiếp',
            'description' => 'Kết quả xổ số Keno Vietlott trực tiếp hôm nay. Cập nhật mỗi 10 phút từ 6h-22h.',
        ]);
    }

    // ─── SEO Landing Pages ───────────────────────────────────────────

    public function statistics(Request $request, LotteryStatisticsService $statsService)
    {
        $region = $request->get('region', 'mien-bac');
        if (!in_array($region, ['mien-bac', 'mien-trung', 'mien-nam'])) {
            $region = 'mien-bac';
        }
        $days = 30;

        $stats = $statsService->getStatistics($region, null, $days, 'all');

        $regionLabels = ['mien-bac' => 'Miền Bắc', 'mien-trung' => 'Miền Trung', 'mien-nam' => 'Miền Nam'];

        return view('lamgame.pages.lottery.statistics', [
            'stats'       => $stats,
            'region'      => $region,
            'days'        => $days,
            'title'       => "Thống kê xổ số {$regionLabels[$region]} - Soi cầu lô đề hôm nay",
            'description' => "Thống kê tần suất xổ số {$regionLabels[$region]} 30 ngày. Soi cầu lô gan, lô về nhiều, phân tích đầu đuôi — dữ liệu cập nhật hàng ngày.",
        ]);
    }

    public function check()
    {
        return view('lamgame.pages.lottery.check', [
            'title'       => 'Dò vé số online - Kiểm tra kết quả xổ số nhanh nhất',
            'description' => 'Dò vé số online miễn phí — kiểm tra kết quả xổ số miền Bắc, Trung, Nam và Vietlott. Nhập số trên vé để biết ngay kết quả.',
        ]);
    }

    public function schedule()
    {
        $dayLabels = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'Chủ nhật'];
        $drawTimes = ['mien-nam' => '16:15', 'mien-trung' => '17:15', 'mien-bac' => '18:15'];
        $currentDay = Carbon::now()->dayOfWeekIso; // 1=Mon...7=Sun

        $weekSchedule = Cache::remember('lottery:web:schedule', 86400, function () use ($drawTimes) {
            $provinces = LotteryProvince::with('schedules')->orderBy('sort_order')->get();
            $schedule = [];

            for ($day = 1; $day <= 7; $day++) {
                $schedule[$day] = ['mien-nam' => [], 'mien-trung' => [], 'mien-bac' => []];
            }

            foreach ($provinces as $p) {
                foreach ($p->schedules as $s) {
                    $schedule[$s->day_of_week][$p->region][] = [
                        'name' => $p->name,
                        'slug' => $p->code,
                    ];
                }
            }

            return $schedule;
        });

        // Today schedule with extra info
        $todaySchedule = [];
        foreach ($weekSchedule[$currentDay] ?? [] as $region => $provinces) {
            $regionLabels = ['mien-nam' => 'Miền Nam', 'mien-trung' => 'Miền Trung', 'mien-bac' => 'Miền Bắc'];
            foreach ($provinces as $p) {
                $todaySchedule[] = array_merge($p, [
                    'region_label' => $regionLabels[$region],
                    'time'         => $drawTimes[$region],
                ]);
            }
        }

        return view('lamgame.pages.lottery.schedule', [
            'weekSchedule'  => $weekSchedule,
            'todaySchedule' => $todaySchedule,
            'currentDay'    => $currentDay,
            'todayLabel'    => $dayLabels[$currentDay] . ' ' . Carbon::now()->format('d/m'),
            'dayLabels'     => $dayLabels,
            'title'         => 'Lịch quay thưởng xổ số hàng tuần - Lịch xổ số 3 miền & Vietlott',
            'description'   => 'Lịch quay thưởng xổ số miền Bắc, Trung, Nam và Vietlott. Biết trước đài nào quay mỗi ngày, giờ mở thưởng chính xác.',
        ]);
    }

    public function province(string $code)
    {
        $province = Cache::remember("lottery:province:{$code}", 86400, fn () =>
            LotteryProvince::with('schedules')->where('code', strtoupper($code))->first()
        );

        if (!$province) {
            abort(404);
        }

        $draws = Cache::remember("lottery:web:province:{$code}", 300, fn () =>
            LotteryDraw::with('result')
                ->where('type', 'traditional')
                ->where('region', $province->region)
                ->whereHas('result', fn ($q) => $q->where('province_id', $province->id))
                ->orderByDesc('date')
                ->limit(10)
                ->get()
        );

        $relatedProvinces = Cache::remember("lottery:provinces:{$province->region}", 86400, fn () =>
            LotteryProvince::where('region', $province->region)->orderBy('sort_order')->get()
        );

        $dayLabels = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'Chủ nhật'];
        $drawTimes = ['mien-nam' => '16:15', 'mien-trung' => '17:15', 'mien-bac' => '18:15'];
        $regionLabels = ['mien-nam' => 'Miền Nam', 'mien-trung' => 'Miền Trung', 'mien-bac' => 'Miền Bắc'];

        $drawDays = $province->schedules->map(fn ($s) => $dayLabels[$s->day_of_week])->implode(', ');

        return view('lamgame.pages.lottery.province', [
            'province'         => $province,
            'draws'            => $draws,
            'relatedProvinces' => $relatedProvinces,
            'regionLabel'      => $regionLabels[$province->region],
            'drawDays'         => $drawDays,
            'drawTime'         => $drawTimes[$province->region],
            'title'            => "Kết quả xổ số {$province->name} (XS" . strtoupper($province->code) . ") - KQXS {$province->name} hôm nay",
            'description'      => "Kết quả xổ số {$province->name} hôm nay — XS" . strtoupper($province->code) . " mới nhất. Quay thưởng {$drawDays} lúc {$drawTimes[$province->region]}. Xem kết quả 10 kỳ gần nhất.",
        ]);
    }
}
