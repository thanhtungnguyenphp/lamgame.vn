<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use App\Models\LotteryResult;
use App\Models\LotterySchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LotteryService
{
    public function getTraditional(string $region, ?string $date = null, ?string $provinceCode = null): ?array
    {
        $date = $date ?: Carbon::today()->toDateString();
        $cacheKey = "lottery:traditional:{$region}:{$date}" . ($provinceCode ? ":{$provinceCode}" : '');
        $ttl = $this->getCacheTtl($date);

        return Cache::remember($cacheKey, $ttl, function () use ($region, $date, $provinceCode) {
            $draw = LotteryDraw::traditional()
                ->forRegion($region)
                ->forDate($date)
                ->completed()
                ->with(['results.province'])
                ->first();

            if (!$draw) {
                return null;
            }

            $results = $draw->results;
            if ($provinceCode) {
                $results = $results->filter(fn ($r) => $r->province?->code === strtoupper($provinceCode));
            }

            $regionNames = ['mien-nam' => 'Miền Nam', 'mien-trung' => 'Miền Trung', 'mien-bac' => 'Miền Bắc'];

            return [
                'date'        => $draw->date->toDateString(),
                'region'      => $region,
                'region_name' => $regionNames[$region] ?? $region,
                'draw_time'   => $draw->draw_time,
                'results'     => $results->map(fn ($r) => [
                    'province'      => $r->province?->name,
                    'province_code' => $r->province?->code,
                    'prizes'        => $r->prize_data,
                ])->values()->toArray(),
            ];
        });
    }

    public function getVietlot(string $game, ?string $date = null, ?string $period = null): array|null
    {
        $date = $date ?: Carbon::today()->toDateString();
        $cacheKey = "lottery:vietlot:{$game}:{$date}" . ($period ? ":{$period}" : '');
        $ttl = $game === 'keno' ? config('lottery.cache.ttl_keno') : $this->getCacheTtl($date);

        return Cache::remember($cacheKey, $ttl, function () use ($game, $date, $period) {
            $query = LotteryDraw::vietlot()->forGame($game)->completed();

            if ($game === 'keno' && $period && $period !== 'latest') {
                $query->where('period', $period);
            } elseif ($game === 'keno' && $period === 'latest') {
                $query->forDate($date)->latest('id');
            } else {
                $query->forDate($date);
            }

            $draw = $query->with('results')->first();
            if (!$draw) {
                return null;
            }

            $result = $draw->results->first();
            if (!$result) {
                return null;
            }

            $gameNames = [
                'mega645'    => 'Mega 6/45',
                'power655'   => 'Power 6/55',
                'max3d'      => 'Max 3D',
                'max3d_pro'  => 'Max 3D Pro',
                'keno'       => 'Keno',
            ];

            $data = [
                'game'      => $game,
                'game_name' => $gameNames[$game] ?? $game,
                'date'      => $draw->date->toDateString(),
                'draw_time' => $draw->draw_time,
            ];

            if ($draw->draw_id) $data['draw_id'] = $draw->draw_id;
            if ($draw->period)  $data['period'] = $draw->period;

            $data = array_merge($data, $result->prize_data);

            if ($result->jackpot_data) {
                $data = array_merge($data, $result->jackpot_data);
            }
            if ($result->stats_data) {
                $data['stats'] = $result->stats_data;
            }

            return $data;
        });
    }

    public function getKenoPeriods(string $date): array|null
    {
        $cacheKey = "lottery:vietlot:keno:periods:{$date}";

        return Cache::remember($cacheKey, config('lottery.cache.ttl_keno'), function () use ($date) {
            $draws = LotteryDraw::vietlot()
                ->forGame('keno')
                ->forDate($date)
                ->completed()
                ->with('results')
                ->orderByDesc('id')
                ->get();

            if ($draws->isEmpty()) {
                return null;
            }

            return [
                'game'          => 'keno',
                'date'          => $date,
                'total_periods' => $draws->count(),
                'periods'       => $draws->map(function ($draw) {
                    $result = $draw->results->first();
                    return array_filter([
                        'period'    => $draw->period,
                        'draw_time' => $draw->draw_time,
                        'numbers'   => $result?->prize_data['numbers'] ?? [],
                        'stats'     => $result?->stats_data,
                    ]);
                })->values()->toArray(),
            ];
        });
    }

    public function getLatest(?string $include = 'all'): array
    {
        $cacheKey = "lottery:latest:{$include}";

        return Cache::remember($cacheKey, config('lottery.cache.ttl_live'), function () use ($include) {
            $data = ['updated_at' => now()->toIso8601String()];

            if (in_array($include, ['all', 'traditional'])) {
                $data['traditional'] = [];
                foreach (config('lottery.regions') as $region) {
                    $data['traditional'][$this->regionKey($region)] = $this->getTraditional($region);
                }
            }

            if (in_array($include, ['all', 'vietlot'])) {
                $data['vietlot'] = [];
                foreach (config('lottery.games') as $game) {
                    $result = $this->getVietlot($game);
                    if ($result) {
                        $data['vietlot'][$game] = $result;
                    }
                }
            }

            return $data;
        });
    }

    public function getSchedule(?string $date = null, ?string $type = 'all'): array
    {
        $date = $date ?: Carbon::today()->toDateString();
        $cacheKey = "lottery:schedule:{$date}:{$type}";

        return Cache::remember($cacheKey, config('lottery.cache.ttl_schedule'), function () use ($date, $type) {
            $carbon = Carbon::parse($date);
            $dayOfWeek = $carbon->dayOfWeekIso; // 1=Mon...7=Sun
            $dayNames = [1 => 'Thứ Hai', 2 => 'Thứ Ba', 3 => 'Thứ Tư', 4 => 'Thứ Năm', 5 => 'Thứ Sáu', 6 => 'Thứ Bảy', 7 => 'Chủ Nhật'];
            $dayNamesEn = [1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday'];

            $data = [
                'date'           => $date,
                'day_of_week'    => $dayNamesEn[$dayOfWeek],
                'day_of_week_vi' => $dayNames[$dayOfWeek],
            ];

            if (in_array($type, ['all', 'traditional'])) {
                $schedules = LotterySchedule::byDay($dayOfWeek)->with('province')->get();
                $grouped = $schedules->groupBy(fn ($s) => $s->province->region);

                $drawTimes = config('lottery.draw_times');
                foreach (config('lottery.regions') as $region) {
                    $key = $this->regionKey($region);
                    $provinces = ($grouped[$region] ?? collect())->map(fn ($s) => [
                        'name' => $s->province->name,
                        'code' => $s->province->code,
                    ])->sortBy('name')->values()->toArray();

                    $data['traditional'][$key] = [
                        'draw_time' => $drawTimes[$region],
                        'provinces' => $provinces,
                    ];
                }
            }

            if (in_array($type, ['all', 'vietlot'])) {
                $vietlotSchedule = [
                    'mega645'    => [3, 5, 7],
                    'power655'   => [2, 4, 6],
                    'max3d'      => [1, 2, 3, 4, 5, 6, 7],
                    'max3d_pro'  => [2, 4, 6],
                    'keno'       => [1, 2, 3, 4, 5, 6, 7],
                ];

                foreach ($vietlotSchedule as $game => $days) {
                    $hasDraw = in_array($dayOfWeek, $days);
                    $entry = ['has_draw' => $hasDraw];

                    if ($hasDraw) {
                        if ($game === 'keno') {
                            $entry['draw_times'] = config('lottery.draw_times.keno_start') . '–' . config('lottery.draw_times.keno_end');
                            $entry['interval'] = config('lottery.draw_times.keno_interval') . ' phút';
                        } else {
                            $entry['draw_time'] = config('lottery.draw_times.vietlot');
                        }
                    } else {
                        // Find next draw date
                        $next = $carbon->copy();
                        for ($i = 1; $i <= 7; $i++) {
                            $next->addDay();
                            if (in_array($next->dayOfWeekIso, $days)) {
                                $entry['next_draw'] = $next->toDateString();
                                break;
                            }
                        }
                    }

                    $data['vietlot'][$game] = $entry;
                }
            }

            return $data;
        });
    }

    public function getHealth(): array
    {
        $sources = ['xoso.com.vn', 'vietlott.vn'];
        $sourceStatus = [];

        foreach ($sources as $source) {
            $lastLog = \App\Models\LotteryScrapeLog::where('source', $source)
                ->latest('created_at')
                ->first();

            $sourceStatus[str_replace('.', '_', $source)] = $lastLog && $lastLog->status === 'success' ? 'healthy' : 'unknown';
        }

        return [
            'status'  => 'ok',
            'version' => '1.0.0',
            'sources' => $sourceStatus,
        ];
    }

    private function getCacheTtl(string $date): int
    {
        $isToday = Carbon::parse($date)->isToday();
        return $isToday ? config('lottery.cache.ttl_live') : config('lottery.cache.ttl_history');
    }

    private function regionKey(string $region): string
    {
        return str_replace('-', '_', $region);
    }
}
