<?php

namespace App\Services\Lottery;

use App\Models\LotteryDraw;
use App\Models\LotteryProvince;

class LotteryCheckService
{
    /**
     * Dò danh sách số với kết quả xổ số.
     *
     * @return array{matches: array, total_matches: int}
     */
    public function check(array $numbers, string $region, string $date, ?string $provinceCode = null): array
    {
        $draw = LotteryDraw::traditional()
            ->forRegion($region)
            ->forDate($date)
            ->completed()
            ->with('results.province')
            ->first();

        if (!$draw) {
            return ['matches' => [], 'total_matches' => 0];
        }

        $results = $draw->results;
        if ($provinceCode) {
            $results = $results->filter(fn ($r) => $r->province?->code === strtoupper($provinceCode));
        }

        $matches = [];

        foreach ($results as $result) {
            $provinceName = $result->province?->name ?? '';
            $code = $result->province?->code ?? '';
            $prizeData = $result->prize_data;

            $prizeNames = [
                'giai_db' => 'Giải ĐB',
                'giai_1'  => 'Giải 1',
                'giai_2'  => 'Giải 2',
                'giai_3'  => 'Giải 3',
                'giai_4'  => 'Giải 4',
                'giai_5'  => 'Giải 5',
                'giai_6'  => 'Giải 6',
                'giai_7'  => 'Giải 7',
                'giai_8'  => 'Giải 8',
            ];

            foreach ($numbers as $number) {
                $numStr = ltrim($number, '0') ?: '0';
                $len = strlen($number);

                foreach ($prizeData as $prizeKey => $prizeNumbers) {
                    if (!is_array($prizeNumbers)) continue;

                    foreach ($prizeNumbers as $fullNumber) {
                        // So sánh N chữ số cuối
                        $suffix = substr($fullNumber, -$len);
                        if ($suffix === $number) {
                            $matches[] = [
                                'number'           => $number,
                                'province'         => $provinceName,
                                'province_code'    => $code,
                                'prize'            => $prizeKey,
                                'prize_name'       => $prizeNames[$prizeKey] ?? $prizeKey,
                                'matched_number'   => $number,
                                'full_prize_number' => $fullNumber,
                            ];
                        }
                    }
                }
            }
        }

        return [
            'matches'       => $matches,
            'total_matches' => count($matches),
        ];
    }
}
