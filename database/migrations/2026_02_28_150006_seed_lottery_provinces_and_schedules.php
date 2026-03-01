<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        // --- Provinces ---
        $provinces = [
            // Miền Nam (21 đài)
            ['code' => 'HCM', 'name' => 'TP. Hồ Chí Minh', 'region' => 'mien-nam', 'sort_order' => 1],
            ['code' => 'DT',  'name' => 'Đồng Tháp',       'region' => 'mien-nam', 'sort_order' => 2],
            ['code' => 'CM',  'name' => 'Cà Mau',           'region' => 'mien-nam', 'sort_order' => 3],
            ['code' => 'BT',  'name' => 'Bến Tre',          'region' => 'mien-nam', 'sort_order' => 4],
            ['code' => 'VT',  'name' => 'Vũng Tàu',         'region' => 'mien-nam', 'sort_order' => 5],
            ['code' => 'BL',  'name' => 'Bạc Liêu',         'region' => 'mien-nam', 'sort_order' => 6],
            ['code' => 'DN',  'name' => 'Đồng Nai',         'region' => 'mien-nam', 'sort_order' => 7],
            ['code' => 'CT',  'name' => 'Cần Thơ',          'region' => 'mien-nam', 'sort_order' => 8],
            ['code' => 'ST',  'name' => 'Sóc Trăng',        'region' => 'mien-nam', 'sort_order' => 9],
            ['code' => 'TN',  'name' => 'Tây Ninh',         'region' => 'mien-nam', 'sort_order' => 10],
            ['code' => 'AG',  'name' => 'An Giang',         'region' => 'mien-nam', 'sort_order' => 11],
            ['code' => 'BTH', 'name' => 'Bình Thuận',       'region' => 'mien-nam', 'sort_order' => 12],
            ['code' => 'VL',  'name' => 'Vĩnh Long',        'region' => 'mien-nam', 'sort_order' => 13],
            ['code' => 'BD',  'name' => 'Bình Dương',       'region' => 'mien-nam', 'sort_order' => 14],
            ['code' => 'TV',  'name' => 'Trà Vinh',         'region' => 'mien-nam', 'sort_order' => 15],
            ['code' => 'LA',  'name' => 'Long An',          'region' => 'mien-nam', 'sort_order' => 16],
            ['code' => 'BP',  'name' => 'Bình Phước',       'region' => 'mien-nam', 'sort_order' => 17],
            ['code' => 'HG',  'name' => 'Hậu Giang',       'region' => 'mien-nam', 'sort_order' => 18],
            ['code' => 'TG',  'name' => 'Tiền Giang',       'region' => 'mien-nam', 'sort_order' => 19],
            ['code' => 'KG',  'name' => 'Kiên Giang',       'region' => 'mien-nam', 'sort_order' => 20],
            ['code' => 'DL',  'name' => 'Đà Lạt',           'region' => 'mien-nam', 'sort_order' => 21],
            // Miền Trung (14 đài)
            ['code' => 'TTH', 'name' => 'Thừa Thiên Huế',  'region' => 'mien-trung', 'sort_order' => 1],
            ['code' => 'PY',  'name' => 'Phú Yên',          'region' => 'mien-trung', 'sort_order' => 2],
            ['code' => 'DLK', 'name' => 'Đắk Lắk',         'region' => 'mien-trung', 'sort_order' => 3],
            ['code' => 'QNM', 'name' => 'Quảng Nam',        'region' => 'mien-trung', 'sort_order' => 4],
            ['code' => 'DNG', 'name' => 'Đà Nẵng',          'region' => 'mien-trung', 'sort_order' => 5],
            ['code' => 'KH',  'name' => 'Khánh Hòa',        'region' => 'mien-trung', 'sort_order' => 6],
            ['code' => 'BDI', 'name' => 'Bình Định',        'region' => 'mien-trung', 'sort_order' => 7],
            ['code' => 'QT',  'name' => 'Quảng Trị',        'region' => 'mien-trung', 'sort_order' => 8],
            ['code' => 'QB',  'name' => 'Quảng Bình',       'region' => 'mien-trung', 'sort_order' => 9],
            ['code' => 'GL',  'name' => 'Gia Lai',          'region' => 'mien-trung', 'sort_order' => 10],
            ['code' => 'NT',  'name' => 'Ninh Thuận',       'region' => 'mien-trung', 'sort_order' => 11],
            ['code' => 'QNG', 'name' => 'Quảng Ngãi',       'region' => 'mien-trung', 'sort_order' => 12],
            ['code' => 'DNO', 'name' => 'Đắk Nông',         'region' => 'mien-trung', 'sort_order' => 13],
            ['code' => 'KT',  'name' => 'Kon Tum',          'region' => 'mien-trung', 'sort_order' => 14],
            // Miền Bắc
            ['code' => 'HN',  'name' => 'Hà Nội',           'region' => 'mien-bac', 'sort_order' => 1],
        ];

        foreach ($provinces as &$p) {
            $p['created_at'] = $now;
            $p['updated_at'] = $now;
        }
        DB::table('lottery_provinces')->insert($provinces);

        // --- Schedules (day_of_week: 1=Mon...7=Sun) ---
        $scheduleMap = [
            // Miền Nam
            'HCM' => [1, 6], 'DT' => [1], 'CM' => [1],
            'BT'  => [2], 'VT' => [2], 'BL' => [2],
            'DN'  => [3], 'CT' => [3], 'ST' => [3],
            'TN'  => [4], 'AG' => [4], 'BTH' => [4],
            'VL'  => [5], 'BD' => [5], 'TV' => [5],
            'LA'  => [6], 'BP' => [6], 'HG' => [6],
            'TG'  => [7], 'KG' => [7], 'DL' => [7],
            // Miền Trung
            'TTH' => [1], 'PY' => [1],
            'DLK' => [2], 'QNM' => [2],
            'DNG' => [3, 6], 'KH' => [3, 7],
            'BDI' => [4], 'QT' => [4], 'QB' => [4],
            'GL'  => [5], 'NT' => [5],
            'QNG' => [6], 'DNO' => [6],
            'KT'  => [7],
            // Miền Bắc
            'HN'  => [1, 2, 3, 4, 5, 6, 7],
        ];

        $provinceIds = DB::table('lottery_provinces')->pluck('id', 'code');
        $schedules = [];
        foreach ($scheduleMap as $code => $days) {
            foreach ($days as $day) {
                $schedules[] = [
                    'province_id'  => $provinceIds[$code],
                    'day_of_week'  => $day,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }
        DB::table('lottery_schedules')->insert($schedules);
    }

    public function down(): void
    {
        DB::table('lottery_schedules')->truncate();
        DB::table('lottery_provinces')->truncate();
    }
};
