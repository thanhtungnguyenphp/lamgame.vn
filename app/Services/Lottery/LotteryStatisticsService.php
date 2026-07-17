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
            if (in_array($type, ['all', 'gap'])) {
                $data['gap'] = $this->calcGap($pairs);
            }
            if (in_array($type, ['all', 'prediction'])) {
                $data['prediction'] = $this->calcPrediction($pairs);
            }
            if (in_array($type, ['all', 'pattern'])) {
                $data['pattern'] = $this->calcPattern($pairs);
            }
            if (in_array($type, ['all', 'special'])) {
                $data['special'] = $this->calcSpecialPrize($region, $provinceCode, $days);
            }
            if (in_array($type, ['all', 'summary'])) {
                $data['summary'] = $this->calcSummary($pairs);
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

    /**
     * Phân tích GAP — số ngày chưa xuất hiện (lô gan)
     */
    private function calcGap(array $pairsByDate): array
    {
        $dates = array_keys($pairsByDate);
        if (empty($dates)) return ['longest_absent' => [], 'just_returned' => []];

        $lastDate = end($dates);
        $lastSeen = [];

        foreach ($pairsByDate as $date => $pairs) {
            foreach (array_unique($pairs) as $p) {
                $lastSeen[$p] = $date;
            }
        }

        // Tính số ngày chưa xuất hiện tính từ hôm nay
        $gaps = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            if (isset($lastSeen[$num])) {
                $daysSince = (int) ((\Carbon\Carbon::parse($lastDate)->diffInDays(\Carbon\Carbon::parse($lastSeen[$num]))));
                $gaps[$num] = ['days' => $daysSince, 'last_seen' => $lastSeen[$num]];
            } else {
                $gaps[$num] = ['days' => count($dates), 'last_seen' => null];
            }
        }

        // Lô gan lâu nhất (chưa về lâu nhất)
        uasort($gaps, fn ($a, $b) => $b['days'] <=> $a['days']);
        $longestAbsent = array_values(array_map(fn ($n) => [
            'number' => $n, 'absent_days' => $gaps[$n]['days'], 'last_seen' => $gaps[$n]['last_seen'],
        ], array_slice(array_keys($gaps), 0, 20)));

        // Lô vừa trở lại (absent lâu rồi mới xuất hiện ngày cuối)
        $justReturned = [];
        $latestPairs = end($pairsByDate) ?: [];
        foreach (array_unique($latestPairs) as $p) {
            // Tìm lần xuất hiện trước đó (không phải ngày cuối)
            $prevDates = array_keys(array_filter($pairsByDate, fn ($pairs, $date) => $date !== $lastDate && in_array($p, $pairs), ARRAY_FILTER_USE_BOTH));
            if (!empty($prevDates)) {
                $prevLast = end($prevDates);
                $gapBefore = (int) \Carbon\Carbon::parse($lastDate)->diffInDays(\Carbon\Carbon::parse($prevLast));
                if ($gapBefore >= 5) {
                    $justReturned[] = ['number' => $p, 'gap_before_return' => $gapBefore, 'returned_on' => $lastDate];
                }
            }
        }
        usort($justReturned, fn ($a, $b) => $b['gap_before_return'] <=> $a['gap_before_return']);

        return [
            'longest_absent' => $longestAbsent,
            'just_returned' => array_slice($justReturned, 0, 10),
        ];
    }

    /**
     * Dự đoán — dựa trên tần suất + pattern (không đảm bảo chính xác, chỉ tham khảo)
     */
    private function calcPrediction(array $pairsByDate): array
    {
        $dates = array_keys($pairsByDate);
        if (count($dates) < 7) return ['hot_candidates' => [], 'due_candidates' => [], 'disclaimer' => 'Cần ít nhất 7 ngày data'];

        // Hot candidates: xuất hiện nhiều trong 7 ngày gần nhất + đang streak
        $last7 = array_slice($pairsByDate, -7, 7, true);
        $countLast7 = [];
        foreach ($last7 as $pairs) {
            foreach ($pairs as $p) {
                $countLast7[$p] = ($countLast7[$p] ?? 0) + 1;
            }
        }
        arsort($countLast7);
        $hotCandidates = array_values(array_map(fn ($n) => [
            'number' => $n, 'appearances_7d' => $countLast7[$n], 'reason' => 'Xuất hiện nhiều trong 7 ngày gần đây',
        ], array_slice(array_keys($countLast7), 0, 10)));

        // Due candidates: lô gan lâu nhất (sắp về theo xác suất)
        $lastSeen = [];
        foreach ($pairsByDate as $date => $pairs) {
            foreach (array_unique($pairs) as $p) {
                $lastSeen[$p] = $date;
            }
        }

        $lastDate = end($dates);
        $dueCandidates = [];
        for ($i = 0; $i <= 99; $i++) {
            $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $daysSince = isset($lastSeen[$num]) ? (int) \Carbon\Carbon::parse($lastDate)->diffInDays(\Carbon\Carbon::parse($lastSeen[$num])) : 999;
            if ($daysSince >= 10) {
                $dueCandidates[] = ['number' => $num, 'absent_days' => $daysSince, 'reason' => "Chưa xuất hiện {$daysSince} ngày"];
            }
        }
        usort($dueCandidates, fn ($a, $b) => $b['absent_days'] <=> $a['absent_days']);

        return [
            'hot_candidates' => $hotCandidates,
            'due_candidates' => array_slice($dueCandidates, 0, 10),
            'disclaimer' => 'Thống kê tham khảo, không đảm bảo kết quả. Xổ số là ngẫu nhiên.',
        ];
    }

    /**
     * Pattern — cặp số hay đi cùng nhau, chẵn/lẻ, tổng
     */
    private function calcPattern(array $pairsByDate): array
    {
        // Cặp hay đi cùng nhau (giới hạn combo tính toán)
        $pairCombos = [];
        foreach ($pairsByDate as $pairs) {
            $unique = array_values(array_unique($pairs));
            $count = min(count($unique), 30); // Giới hạn 30 số/ngày để tránh O(n²) quá lớn
            for ($i = 0; $i < $count - 1; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $combo = $unique[$i] < $unique[$j] ? $unique[$i] . '-' . $unique[$j] : $unique[$j] . '-' . $unique[$i];
                    $pairCombos[$combo] = ($pairCombos[$combo] ?? 0) + 1;
                }
            }
        }
        arsort($pairCombos);
        $topCombos = array_values(array_map(fn ($k) => [
            'pair' => $k, 'together_count' => $pairCombos[$k],
        ], array_slice(array_keys($pairCombos), 0, 15)));

        // Số chẵn vs lẻ theo ngày
        $evenOdd = [];
        foreach (array_slice($pairsByDate, -7, 7, true) as $date => $pairs) {
            $even = count(array_filter($pairs, fn ($p) => (int) $p % 2 === 0));
            $odd = count($pairs) - $even;
            $evenOdd[] = ['date' => $date, 'even' => $even, 'odd' => $odd];
        }

        // Tổng 2 chữ số distribution
        $sumDistribution = array_fill(0, 19, 0); // sum 0-18
        foreach ($pairsByDate as $pairs) {
            foreach ($pairs as $p) {
                $sum = (int) $p[0] + (int) $p[1];
                $sumDistribution[$sum]++;
            }
        }

        return [
            'frequent_pairs' => $topCombos,
            'even_odd_trend' => $evenOdd,
            'sum_distribution' => $sumDistribution,
        ];
    }

    /**
     * Thống kê giải Đặc Biệt riêng
     */
    private function calcSpecialPrize(string $region, ?string $provinceCode, int $days): array
    {
        $draws = LotteryDraw::traditional()
            ->forRegion($region)
            ->completed()
            ->where('date', '>=', now()->subDays($days)->toDateString())
            ->with('results.province')
            ->orderBy('date')
            ->get();

        $dbNumbers = []; // full ĐB numbers
        $dbLast2 = []; // 2 số cuối ĐB

        foreach ($draws as $draw) {
            $results = $draw->results;
            if ($provinceCode) {
                $results = $results->filter(fn ($r) => $r->province?->code === strtoupper($provinceCode));
            }

            foreach ($results as $result) {
                $db = $result->prize_data['giai_db'] ?? [];
                foreach ($db as $num) {
                    $dbNumbers[] = ['date' => $draw->date->toDateString(), 'number' => $num, 'province' => $result->province?->name];
                    $dbLast2[] = str_pad(substr($num, -2), 2, '0', STR_PAD_LEFT);
                }
            }
        }

        // Tần suất 2 số cuối ĐB
        $dbFreq = array_count_values($dbLast2);
        arsort($dbFreq);

        // ĐB chưa xuất hiện lâu
        $allNums = range(0, 99);
        $dbAbsent = [];
        foreach ($allNums as $n) {
            $num = str_pad((string) $n, 2, '0', STR_PAD_LEFT);
            if (!isset($dbFreq[$num])) {
                $dbAbsent[] = $num;
            }
        }

        return [
            'recent_special' => array_slice(array_reverse($dbNumbers), 0, 20),
            'special_frequency' => array_values(array_map(fn ($n) => ['number' => $n, 'count' => $dbFreq[$n]], array_slice(array_keys($dbFreq), 0, 15))),
            'never_appeared' => array_slice($dbAbsent, 0, 20),
            'total_draws' => count($draws),
        ];
    }

    /**
     * Tổng hợp nhanh — overview cho dashboard
     */
    private function calcSummary(array $pairsByDate): array
    {
        $totalDays = count($pairsByDate);
        $totalPairs = array_sum(array_map('count', $pairsByDate));
        $allPairs = array_merge(...array_values($pairsByDate));
        $uniqueCount = count(array_unique($allPairs));

        // Top 5 hot
        $counts = array_count_values($allPairs);
        arsort($counts);
        $top5 = array_slice($counts, 0, 5, true);

        // Bottom 5 cold
        asort($counts);
        $bottom5 = array_slice($counts, 0, 5, true);

        // Avg pairs per day
        $avgPerDay = $totalDays > 0 ? round($totalPairs / $totalDays, 1) : 0;

        return [
            'total_days' => $totalDays,
            'total_numbers_drawn' => $totalPairs,
            'unique_numbers_appeared' => $uniqueCount,
            'avg_numbers_per_day' => $avgPerDay,
            'coverage_percent' => round($uniqueCount / 100 * 100, 1),
            'top_5_hot' => array_values(array_map(fn ($n) => ['number' => $n, 'count' => $top5[$n]], array_keys($top5))),
            'top_5_cold' => array_values(array_map(fn ($n) => ['number' => $n, 'count' => $bottom5[$n]], array_keys($bottom5))),
        ];
    }
}
