{{-- Groups Section — Updated with real FIFA World Cup 2026 results (as of 27/06/2026) --}}
<section class="wc26-groups" id="groups">
    <div class="container">
        <h2 class="wc26-section__title">⚽ Bảng Đấu & Xếp Hạng</h2>
        <p class="wc26-section__desc">12 bảng đấu • 48 đội tuyển • Cập nhật sau mỗi lượt trận</p>

        <div class="wc26-groups__grid">
            @php
            // Data cập nhật 27/06/2026 — Bảng A-I hoàn tất (3 trận), Bảng J-K-L sau lượt 2
            $groups = [
                'A' => [
                    ['team' => '🇲🇽 Mexico', 'p' => 3, 'w' => 3, 'd' => 0, 'l' => 0, 'gf' => 6, 'ga' => 0, 'pts' => 9],
                    ['team' => '🇿🇦 Nam Phi', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 2, 'ga' => 3, 'pts' => 4],
                    ['team' => '🇰🇷 Hàn Quốc', 'p' => 3, 'w' => 1, 'd' => 0, 'l' => 2, 'gf' => 2, 'ga' => 3, 'pts' => 3],
                    ['team' => '🇨🇿 Czechia', 'p' => 3, 'w' => 0, 'd' => 1, 'l' => 2, 'gf' => 2, 'ga' => 6, 'pts' => 1],
                ],
                'B' => [
                    ['team' => '🇨🇭 Thụy Sĩ', 'p' => 3, 'w' => 2, 'd' => 1, 'l' => 0, 'gf' => 7, 'ga' => 3, 'pts' => 7],
                    ['team' => '🇨🇦 Canada', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 8, 'ga' => 2, 'pts' => 4],
                    ['team' => '🇧🇦 Bosnia', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 5, 'ga' => 6, 'pts' => 4],
                    ['team' => '🇶🇦 Qatar', 'p' => 3, 'w' => 0, 'd' => 1, 'l' => 2, 'gf' => 2, 'ga' => 11, 'pts' => 1],
                ],
                'C' => [
                    ['team' => '🇧🇷 Brazil', 'p' => 3, 'w' => 2, 'd' => 1, 'l' => 0, 'gf' => 7, 'ga' => 1, 'pts' => 7],
                    ['team' => '🇲🇦 Morocco', 'p' => 3, 'w' => 2, 'd' => 1, 'l' => 0, 'gf' => 6, 'ga' => 3, 'pts' => 7],
                    ['team' => '🏴 Scotland', 'p' => 3, 'w' => 1, 'd' => 0, 'l' => 2, 'gf' => 1, 'ga' => 4, 'pts' => 3],
                    ['team' => '🇭🇹 Haiti', 'p' => 3, 'w' => 0, 'd' => 0, 'l' => 3, 'gf' => 2, 'ga' => 8, 'pts' => 0],
                ],
                'D' => [
                    ['team' => '🇺🇸 Mỹ', 'p' => 3, 'w' => 2, 'd' => 0, 'l' => 1, 'gf' => 8, 'ga' => 4, 'pts' => 6],
                    ['team' => '🇦🇺 Australia', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 2, 'ga' => 2, 'pts' => 4],
                    ['team' => '🇵🇾 Paraguay', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 2, 'ga' => 4, 'pts' => 4],
                    ['team' => '🇹🇷 Thổ Nhĩ Kỳ', 'p' => 3, 'w' => 1, 'd' => 0, 'l' => 2, 'gf' => 3, 'ga' => 5, 'pts' => 3],
                ],
                'E' => [
                    ['team' => '🇩🇪 Đức', 'p' => 3, 'w' => 2, 'd' => 0, 'l' => 1, 'gf' => 10, 'ga' => 4, 'pts' => 6],
                    ['team' => '🇨🇮 Bờ Biển Ngà', 'p' => 3, 'w' => 2, 'd' => 0, 'l' => 1, 'gf' => 3, 'ga' => 2, 'pts' => 6],
                    ['team' => '🇪🇨 Ecuador', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 2, 'ga' => 1, 'pts' => 4],
                    ['team' => '🇨🇼 Curaçao', 'p' => 3, 'w' => 0, 'd' => 1, 'l' => 2, 'gf' => 1, 'ga' => 9, 'pts' => 1],
                ],
                'F' => [
                    ['team' => '🇳🇱 Hà Lan', 'p' => 3, 'w' => 2, 'd' => 1, 'l' => 0, 'gf' => 10, 'ga' => 4, 'pts' => 7],
                    ['team' => '🇯🇵 Nhật Bản', 'p' => 3, 'w' => 1, 'd' => 2, 'l' => 0, 'gf' => 7, 'ga' => 3, 'pts' => 5],
                    ['team' => '🇸🇪 Thụy Điển', 'p' => 3, 'w' => 1, 'd' => 1, 'l' => 1, 'gf' => 7, 'ga' => 7, 'pts' => 4],
                    ['team' => '🇹🇳 Tunisia', 'p' => 3, 'w' => 0, 'd' => 0, 'l' => 3, 'gf' => 2, 'ga' => 12, 'pts' => 0],
                ],
                'G' => [
                    ['team' => '🇧🇪 Bỉ', 'p' => 3, 'w' => 1, 'd' => 2, 'l' => 0, 'gf' => 6, 'ga' => 2, 'pts' => 5],
                    ['team' => '🇪🇬 Ai Cập', 'p' => 3, 'w' => 1, 'd' => 2, 'l' => 0, 'gf' => 5, 'ga' => 3, 'pts' => 5],
                    ['team' => '🇮🇷 Iran', 'p' => 3, 'w' => 0, 'd' => 3, 'l' => 0, 'gf' => 3, 'ga' => 3, 'pts' => 3],
                    ['team' => '🇳🇿 New Zealand', 'p' => 3, 'w' => 0, 'd' => 1, 'l' => 2, 'gf' => 4, 'ga' => 10, 'pts' => 1],
                ],
                'H' => [
                    ['team' => '🇪🇸 Tây Ban Nha', 'p' => 3, 'w' => 2, 'd' => 1, 'l' => 0, 'gf' => 5, 'ga' => 0, 'pts' => 7],
                    ['team' => '🇨🇻 Cape Verde', 'p' => 3, 'w' => 0, 'd' => 3, 'l' => 0, 'gf' => 2, 'ga' => 2, 'pts' => 3],
                    ['team' => '🇺🇾 Uruguay', 'p' => 3, 'w' => 0, 'd' => 2, 'l' => 1, 'gf' => 3, 'ga' => 4, 'pts' => 2],
                    ['team' => '🇸🇦 Saudi Arabia', 'p' => 3, 'w' => 0, 'd' => 2, 'l' => 1, 'gf' => 1, 'ga' => 5, 'pts' => 2],
                ],
                'I' => [
                    ['team' => '🇫🇷 Pháp', 'p' => 3, 'w' => 3, 'd' => 0, 'l' => 0, 'gf' => 10, 'ga' => 2, 'pts' => 9],
                    ['team' => '🇳🇴 Na Uy', 'p' => 3, 'w' => 2, 'd' => 0, 'l' => 1, 'gf' => 8, 'ga' => 7, 'pts' => 6],
                    ['team' => '🇸🇳 Senegal', 'p' => 3, 'w' => 1, 'd' => 0, 'l' => 2, 'gf' => 8, 'ga' => 6, 'pts' => 3],
                    ['team' => '🇮🇶 Iraq', 'p' => 3, 'w' => 0, 'd' => 0, 'l' => 3, 'gf' => 1, 'ga' => 12, 'pts' => 0],
                ],
                'J' => [
                    ['team' => '🇦🇷 Argentina', 'p' => 2, 'w' => 2, 'd' => 0, 'l' => 0, 'gf' => 5, 'ga' => 0, 'pts' => 6],
                    ['team' => '🇩🇿 Algeria', 'p' => 2, 'w' => 1, 'd' => 0, 'l' => 1, 'gf' => 2, 'ga' => 4, 'pts' => 3],
                    ['team' => '🇦🇹 Áo', 'p' => 2, 'w' => 1, 'd' => 0, 'l' => 1, 'gf' => 3, 'ga' => 3, 'pts' => 3],
                    ['team' => '🇯🇴 Jordan', 'p' => 2, 'w' => 0, 'd' => 0, 'l' => 2, 'gf' => 2, 'ga' => 5, 'pts' => 0],
                ],
                'K' => [
                    ['team' => '🇨🇴 Colombia', 'p' => 2, 'w' => 2, 'd' => 0, 'l' => 0, 'gf' => 4, 'ga' => 1, 'pts' => 6],
                    ['team' => '🇵🇹 Bồ Đào Nha', 'p' => 2, 'w' => 1, 'd' => 1, 'l' => 0, 'gf' => 6, 'ga' => 1, 'pts' => 4],
                    ['team' => '🇨🇩 DR Congo', 'p' => 2, 'w' => 0, 'd' => 1, 'l' => 1, 'gf' => 1, 'ga' => 2, 'pts' => 1],
                    ['team' => '🇺🇿 Uzbekistan', 'p' => 2, 'w' => 0, 'd' => 0, 'l' => 2, 'gf' => 1, 'ga' => 8, 'pts' => 0],
                ],
                'L' => [
                    ['team' => '🏴 Anh', 'p' => 2, 'w' => 1, 'd' => 1, 'l' => 0, 'gf' => 4, 'ga' => 2, 'pts' => 4],
                    ['team' => '🇬🇭 Ghana', 'p' => 2, 'w' => 1, 'd' => 1, 'l' => 0, 'gf' => 1, 'ga' => 0, 'pts' => 4],
                    ['team' => '🇭🇷 Croatia', 'p' => 2, 'w' => 1, 'd' => 0, 'l' => 1, 'gf' => 3, 'ga' => 4, 'pts' => 3],
                    ['team' => '🇵🇦 Panama', 'p' => 2, 'w' => 0, 'd' => 0, 'l' => 2, 'gf' => 0, 'ga' => 2, 'pts' => 0],
                ],
            ];
            @endphp

            @foreach($groups as $letter => $teams)
            <div class="wc26-group">
                <div class="wc26-group__header">Bảng {{ $letter }}</div>
                <table class="wc26-group__table">
                    <thead>
                        <tr><th></th><th>Đội</th><th>M</th><th>T</th><th>H</th><th>B</th><th>BT</th><th>Đ</th></tr>
                    </thead>
                    <tbody>
                    @foreach($teams as $i => $team)
                    <tr class="{{ $i < 2 ? 'wc26-group__team--qualify' : '' }}">
                        <td class="wc26-group__pos">{{ $i + 1 }}</td>
                        <td class="wc26-group__name">{{ $team['team'] }}</td>
                        <td>{{ $team['p'] }}</td>
                        <td>{{ $team['w'] }}</td>
                        <td>{{ $team['d'] }}</td>
                        <td>{{ $team['l'] }}</td>
                        <td>{{ $team['gf'] }}-{{ $team['ga'] }}</td>
                        <td class="wc26-group__pts"><strong>{{ $team['pts'] }}</strong></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>

        <p class="wc26-groups__note">
            ✅ Đã vào vòng 32 (đầu/nhì bảng): Mexico, Nam Phi, Thụy Sĩ, Canada, Brazil, Morocco, Mỹ, Australia, Đức, Bờ Biển Ngà, Hà Lan, Nhật Bản, Bỉ, Ai Cập, Tây Ban Nha, Cape Verde, Pháp, Na Uy, Argentina, Colombia<br>
            ✅ Vào vòng 32 (hạng 3 tốt nhất): Ecuador, Thụy Điển, Senegal, Paraguay...<br>
            ❌ Đã bị loại: Haiti, Qatar, Tunisia, Curaçao, Iraq, New Zealand, Panama<br>
            * Cập nhật: 27/06/2026 — Bảng A-I hoàn tất. Bảng J, K, L đấu lượt cuối hôm nay.
        </p>
    </div>
</section>
