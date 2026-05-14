{{-- Schedule Section --}}
<section class="wc26-schedule" id="schedule">
    <div class="container">
        <h2 class="wc26-section__title">📅 Lịch thi đấu</h2>
        <p class="wc26-section__desc">Cập nhật lịch thi đấu, kết quả trực tiếp World Cup 2026</p>

        {{-- Phase tabs --}}
        <div class="wc26-tabs" role="tablist">
            <button class="wc26-tab wc26-tab--active" data-tab="group-stage" role="tab">Vòng bảng</button>
            <button class="wc26-tab" data-tab="round-32" role="tab">Vòng 32</button>
            <button class="wc26-tab" data-tab="round-16" role="tab">Vòng 16</button>
            <button class="wc26-tab" data-tab="quarter" role="tab">Tứ kết</button>
            <button class="wc26-tab" data-tab="semi" role="tab">Bán kết</button>
            <button class="wc26-tab" data-tab="final" role="tab">Chung kết</button>
        </div>

        {{-- Match list --}}
        <div class="wc26-matches" id="wc26-matches">
            <div class="wc26-matches__loading">
                <div class="wc26-spinner"></div>
                <p>Đang tải lịch thi đấu...</p>
            </div>
        </div>

        {{-- Match template (rendered by JS) --}}
        <template id="wc26-match-template">
            <div class="wc26-match">
                <div class="wc26-match__time">
                    <span class="wc26-match__date"></span>
                    <span class="wc26-match__hour"></span>
                    <span class="wc26-match__status"></span>
                </div>
                <div class="wc26-match__teams">
                    <div class="wc26-match__team wc26-match__team--home">
                        <img class="wc26-match__flag" alt="" loading="lazy">
                        <span class="wc26-match__name"></span>
                        <span class="wc26-match__score"></span>
                    </div>
                    <span class="wc26-match__vs">VS</span>
                    <div class="wc26-match__team wc26-match__team--away">
                        <span class="wc26-match__score"></span>
                        <span class="wc26-match__name"></span>
                        <img class="wc26-match__flag" alt="" loading="lazy">
                    </div>
                </div>
                <div class="wc26-match__venue"></div>
            </div>
        </template>
    </div>
</section>
