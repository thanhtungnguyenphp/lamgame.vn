<?php

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Models\LotteryResult;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $json = file_get_contents(database_path('seeders/data/lottery_all_20260304_20260305.json'));
        $draws = json_decode($json, true);
        $provinceMap = LotteryProvince::pluck('id', 'code')->toArray();

        foreach ($draws as $d) {
            $draw = LotteryDraw::updateOrCreate(
                ['type' => $d['type'], 'region' => $d['region'], 'date' => $d['date'], 'game' => null],
                [
                    'draw_time'  => $d['draw_time'],
                    'status'     => $d['status'],
                    'source'     => $d['source'],
                    'scraped_at' => now(),
                ]
            );

            foreach ($d['results'] as $r) {
                $provinceId = $provinceMap[$r['province_code']] ?? null;
                if (!$provinceId) continue;

                LotteryResult::updateOrCreate(
                    ['draw_id' => $draw->id, 'province_id' => $provinceId],
                    ['prize_data' => $r['prize_data']]
                );
            }
        }
    }

    public function down(): void
    {
        $dates = ['2026-03-04', '2026-03-05'];
        $regions = config('lottery.regions');

        LotteryDraw::where('type', 'traditional')
            ->whereIn('region', $regions)
            ->whereIn('date', $dates)
            ->each(fn ($draw) => $draw->delete());
    }
};