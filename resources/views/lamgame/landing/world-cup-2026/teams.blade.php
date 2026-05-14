{{-- Teams Section --}}
<section class="wc26-teams" id="teams">
    <div class="container">
        <h2 class="wc26-section__title">🌍 Đội tuyển tham dự</h2>
        <p class="wc26-section__desc">48 đội tuyển từ 6 liên đoàn châu lục</p>

        {{-- Filter by confederation --}}
        <div class="wc26-teams__filter">
            <button class="wc26-filter-btn wc26-filter-btn--active" data-conf="all">Tất cả (48)</button>
            <button class="wc26-filter-btn" data-conf="uefa">UEFA (16)</button>
            <button class="wc26-filter-btn" data-conf="conmebol">CONMEBOL (6)</button>
            <button class="wc26-filter-btn" data-conf="concacaf">CONCACAF (6)</button>
            <button class="wc26-filter-btn" data-conf="afc">AFC (8)</button>
            <button class="wc26-filter-btn" data-conf="caf">CAF (9)</button>
            <button class="wc26-filter-btn" data-conf="ofc">OFC (1)</button>
        </div>

        <div class="wc26-teams__grid" id="wc26-teams-grid">
            @php
            $allTeams = [
                ['name' => 'Mỹ', 'flag' => '🇺🇸', 'conf' => 'concacaf', 'rank' => 11, 'titles' => 0],
                ['name' => 'Argentina', 'flag' => '🇦🇷', 'conf' => 'conmebol', 'rank' => 1, 'titles' => 3],
                ['name' => 'Pháp', 'flag' => '🇫🇷', 'conf' => 'uefa', 'rank' => 2, 'titles' => 2],
                ['name' => 'Brazil', 'flag' => '🇧🇷', 'conf' => 'conmebol', 'rank' => 3, 'titles' => 5],
                ['name' => 'Anh', 'flag' => '🏴󠁧󠁢󠁥󠁮󠁧󠁿', 'conf' => 'uefa', 'rank' => 4, 'titles' => 1],
                ['name' => 'Tây Ban Nha', 'flag' => '🇪🇸', 'conf' => 'uefa', 'rank' => 5, 'titles' => 1],
                ['name' => 'Bồ Đào Nha', 'flag' => '🇵🇹', 'conf' => 'uefa', 'rank' => 6, 'titles' => 0],
                ['name' => 'Hà Lan', 'flag' => '🇳🇱', 'conf' => 'uefa', 'rank' => 7, 'titles' => 0],
                ['name' => 'Bỉ', 'flag' => '🇧🇪', 'conf' => 'uefa', 'rank' => 8, 'titles' => 0],
                ['name' => 'Ý', 'flag' => '🇮🇹', 'conf' => 'uefa', 'rank' => 9, 'titles' => 4],
                ['name' => 'Đức', 'flag' => '🇩🇪', 'conf' => 'uefa', 'rank' => 10, 'titles' => 4],
                ['name' => 'Croatia', 'flag' => '🇭🇷', 'conf' => 'uefa', 'rank' => 12, 'titles' => 0],
                ['name' => 'Colombia', 'flag' => '🇨🇴', 'conf' => 'conmebol', 'rank' => 13, 'titles' => 0],
                ['name' => 'Mexico', 'flag' => '🇲🇽', 'conf' => 'concacaf', 'rank' => 14, 'titles' => 0],
                ['name' => 'Uruguay', 'flag' => '🇺🇾', 'conf' => 'conmebol', 'rank' => 15, 'titles' => 2],
                ['name' => 'Nhật Bản', 'flag' => '🇯🇵', 'conf' => 'afc', 'rank' => 16, 'titles' => 0],
                ['name' => 'Hàn Quốc', 'flag' => '🇰🇷', 'conf' => 'afc', 'rank' => 22, 'titles' => 0],
                ['name' => 'Senegal', 'flag' => '🇸🇳', 'conf' => 'caf', 'rank' => 18, 'titles' => 0],
                ['name' => 'Morocco', 'flag' => '🇲🇦', 'conf' => 'caf', 'rank' => 13, 'titles' => 0],
                ['name' => 'Việt Nam', 'flag' => '🇻🇳', 'conf' => 'afc', 'rank' => 96, 'titles' => 0],
                ['name' => 'Ả Rập Saudi', 'flag' => '🇸🇦', 'conf' => 'afc', 'rank' => 56, 'titles' => 0],
                ['name' => 'Úc', 'flag' => '🇦🇺', 'conf' => 'afc', 'rank' => 24, 'titles' => 0],
                ['name' => 'Canada', 'flag' => '🇨🇦', 'conf' => 'concacaf', 'rank' => 40, 'titles' => 0],
                ['name' => 'Indonesia', 'flag' => '🇮🇩', 'conf' => 'afc', 'rank' => 90, 'titles' => 0],
            ];
            @endphp

            @foreach($allTeams as $team)
            <div class="wc26-team-card" data-conf="{{ $team['conf'] }}">
                <span class="wc26-team-card__flag">{{ $team['flag'] }}</span>
                <span class="wc26-team-card__name">{{ $team['name'] }}</span>
                <div class="wc26-team-card__meta">
                    <span class="wc26-team-card__rank">#{{ $team['rank'] }}</span>
                    @if($team['titles'] > 0)
                    <span class="wc26-team-card__titles">🏆×{{ $team['titles'] }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
