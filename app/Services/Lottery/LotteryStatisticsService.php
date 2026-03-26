<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;
use Illuminate\Support\Facades\Cache;

class LotteryStatisticsService
{
    public function getStatistics(string $region, ?string $provinceCode, int $days, string $type): array
    {
        $cacheKey = "lottery:stats:{$region}:{$provinceCode}:{$days}:{$type}";

        return Cache::remember($cacheKey, 300, function () use ($region, $provinceCode, $days, $type) {
            $pairs = $this->extractLotoPairs($region, $provinceCode, $days);

            $data = ['region' => $region, 'province_code' => $provinceCode, 'days' => $days];

            if (in_array($type, ['all', 'frequency'])) {
                $data['frequency'] = $this->calcFrequency($pairs);
            }
            if (in_array($type, ['all', 'streak'])) {
                $data['streaks'] = $this->calcStreaks($pairs);
            }
            if (in_array($type, ['all', 'head_tail'])) {
                $data['head_tail'] = $this->calcHeadTail($pairs);
            }

            return $data;
        });
    }

    /**
     * Trích xuất cặp lô 2 số cuối từ tất cả giải, nhóm theo ngày.
     *
     * @return array<string, string[]> ['2026-03-25' => ['36','82',...], ...]
     */
    private function extractLotoPairs(string $region, ?string $provinceCode, int $days): array
    {
        $draws = LotteryDraw::traditional()
            ->forRegion($region)
            ->completed()
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->with('results.province')
            ->orderBy('date')
            ->get();

        $grouped = [];

        foreach ($draws as $draw) {
            $date = $draw->date->toDateString();
            $results = $draw->results;

            if ($provinceCode) {
                $results = $results->filter(fn ($r) => $r->province?->code === strtoupper($provinceCode));
            }

            $pairs = [];
            foreach ($results as $result) {
                foreach ($result->prize_data as $numbers) {
                    if (!is_array($numbers)) continue;
                    foreach ($numbers as $num) {
                        $pairs[] = str_pad((string) substr((string) $num, -2), 2, '0', STR_PAD_LEFT);
                    }
                }
            }

            $grouped[$date] = $pairs;
        }

        return $grouped;
    }

    private function calcFrequency(array $pairsByDate): array
    {
        $counts = [];
        $lastSeen = [];

        foreach ($pairsByDate as $date => $pairs) {
            foreach ($pairs as $p) {
                $counts[$p] = ($counts[$p] ?? 0) + 1;
                $lastSeen[$p] = $date;
            }
        }

        arsort($counts);

        $top = array_values(array_map(fn ($n) => [
            'number' => (string) $n, 'count' => $counts[$n], 'last_seen' => $lastSeen[$n],
        ], array_slice(array_keys($counts), 0, 20)));

        asort($counts);
        $cold = array_values(array_map(fn ($n) => [
            'number' => (string) $n, 'count' => $counts[$n], 'last_seen' => $lastSeen[$n],
        ], array_slice(array_keys($counts), 0, 20)));

        return ['top_pairs' => $top, 'cold_pairs' => $cold];
    }

    private function calcStreaks(array $pairsByDate): array
    {
        $dates = array_keys($pairsByDate);
        if (empty($dates)) {
            return ['current' => [], 'longest' => []];
        }

        // Track consecutive appearances per number
        $current = [];
        $longest = [];

        // Build presence map: number => [dates it appeared]
        $presence = [];
        foreach ($pairsByDate as $date => $pairs) {
            foreach (array_unique($pairs) as $p) {
                $presence[$p][] = $date;
            }
        }

        $allDates = $dates; // already sorted asc

        foreach ($presence as $number => $appearedDates) {
            $set = array_flip($appearedDates);
            $streak = 0;
            $maxStreak = 0;
            $maxFrom = $maxTo = null;
            $curFrom = null;

            foreach ($allDates as $i => $date) {
                if (isset($set[$date])) {
                    if ($streak === 0) $curFrom = $date;
                    $streak++;
                    if ($streak > $maxStreak) {
                        $maxStreak = $streak;
                        $maxFrom = $curFrom;
                        $maxTo = $date;
                    }
                } else {
                    $streak = 0;
                }
            }

            // Current streak = streak ending at last date
            $lastDate = end($allDates);
            $curStreak = 0;
            for ($i = count($allDates) - 1; $i >= 0; $i--) {
                if (isset($set[$allDates[$i]])) {
                    $curStreak++;
                } else {
                    break;
                }
            }

            if ($curStreak >= 2) {
                $current[$number] = $curStreak;
            }
            if ($maxStreak >= 2) {
                $longest[$number] = ['days' => $maxStreak, 'from' => $maxFrom, 'to' => $maxTo];
            }
        }

        arsort($current);
        uasort($longest, fn ($a, $b) => $b['days'] <=> $a['days']);

        return [
            'current' => array_values(array_map(fn ($n) => [
                'number' => (string) $n, 'consecutive_days' => $current[$n],
            ], array_slice(array_keys($current), 0, 20))),
            'longest' => array_values(array_map(fn ($n) => [
                'number' => (string) $n, 'consecutive_days' => $longest[$n]['days'],
                'from' => $longest[$n]['from'], 'to' => $longest[$n]['to'],
            ], array_slice(array_keys($longest), 0, 20))),
        ];
    }

    private function calcHeadTail(array $pairsByDate): array
    {
        $heads = array_fill(0, 10, 0);
        $tails = array_fill(0, 10, 0);

        foreach ($pairsByDate as $pairs) {
            foreach ($pairs as $p) {
                $heads[(int) $p[0]]++;
                $tails[(int) $p[1]]++;
            }
        }

        $toMap = fn ($arr) => (object) array_combine(
            array_map('strval', array_keys($arr)),
            array_values($arr)
        );

        return ['heads' => $toMap($heads), 'tails' => $toMap($tails)];
    }
}
