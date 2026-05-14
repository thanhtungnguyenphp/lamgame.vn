{{-- Groups Section --}}
<section class="wc26-groups" id="groups">
    <div class="container">
        <h2 class="wc26-section__title">⚽ Bảng đấu & Xếp hạng</h2>
        <p class="wc26-section__desc">12 bảng đấu — Mỗi bảng 4 đội, 2 đội đầu bảng đi tiếp</p>

        <div class="wc26-groups__grid">
            @php
            $groups = [
                'A' => ['🇺🇸 Mỹ', '🇲🇦 Morocco', '🇸🇪 Thụy Điển', '🇳🇬 Nigeria'],
                'B' => ['🇦🇷 Argentina', '🇪🇬 Ai Cập', '🇨🇴 Colombia', '🇺🇿 Uzbekistan'],
                'C' => ['🇫🇷 Pháp', '🇦🇺 Úc', '🇮🇩 Indonesia', '🇪🇨 Ecuador'],
                'D' => ['🇧🇷 Brazil', '🇳🇱 Hà Lan', '🇨🇲 Cameroon', '🇳🇿 New Zealand'],
                'E' => ['🇩🇪 Đức', '🇯🇵 Nhật Bản', '🇹🇷 Thổ Nhĩ Kỳ', '🇰🇪 Kenya'],
                'F' => ['🇪🇸 Tây Ban Nha', '🇰🇷 Hàn Quốc', '🇷🇸 Serbia', '🇨🇱 Chile'],
                'G' => ['🏴󠁧󠁢󠁥󠁮󠁧󠁿 Anh', '🇸🇳 Senegal', '🇺🇾 Uruguay', '🇸🇦 Ả Rập Saudi'],
                'H' => ['🇵🇹 Bồ Đào Nha', '🇲🇽 Mexico', '🇬🇭 Ghana', '🇮🇷 Iran'],
                'I' => ['🇮🇹 Ý', '🇨🇦 Canada', '🇵🇱 Ba Lan', '🇹🇳 Tunisia'],
                'J' => ['🇧🇪 Bỉ', '🇨🇷 Costa Rica', '🇩🇰 Đan Mạch', '🇿🇦 Nam Phi'],
                'K' => ['🇭🇷 Croatia', '🇻🇳 Việt Nam', '🇨🇭 Thụy Sĩ', '🇵🇪 Peru'],
                'L' => ['🇺🇦 Ukraine', '🇵🇦 Panama', '🇦🇹 Áo', '🇶🇦 Qatar'],
            ];
            @endphp

            @foreach($groups as $letter => $teams)
            <div class="wc26-group">
                <div class="wc26-group__header">Bảng {{ $letter }}</div>
                <div class="wc26-group__teams">
                    @foreach($teams as $i => $team)
                    <div class="wc26-group__team {{ $i < 2 ? 'wc26-group__team--qualify' : '' }}">
                        <span class="wc26-group__pos">{{ $i + 1 }}</span>
                        <span class="wc26-group__name">{{ $team }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <p class="wc26-groups__note">
            * Bảng đấu dự kiến — FIFA sẽ công bố chính thức sau lễ bốc thăm (tháng 12/2025)
        </p>
    </div>
</section>
