{{-- FOOTER REDESIGN — Modern layout + social links + newsletter --}}
<footer class="footer-redesign">
    <div class="footer-redesign__container">
        {{-- Newsletter --}}
        <div class="footer-redesign__newsletter">
            <div class="footer-redesign__newsletter-text">
                <h3>Nhận tin mới từ cộng đồng</h3>
                <p>Việc làm hot, bài viết hay, source code mới — gửi thẳng vào inbox</p>
            </div>
            <form class="footer-redesign__newsletter-form" onsubmit="event.preventDefault();">
                <input type="email" class="footer-redesign__newsletter-input" placeholder="Email của bạn..." aria-label="Email đăng ký newsletter">
                <button type="submit" class="footer-redesign__newsletter-btn">Đăng ký</button>
            </form>
        </div>

        {{-- Grid --}}
        <div class="footer-redesign__grid">
            {{-- Brand --}}
            <div class="footer-redesign__col">
                <div class="footer-redesign__brand-logo">
                    <img src="{{ asset('assets/logos/png/logo-horizontal-200.png') }}" alt="LamGame.vn" height="36" style="height:36px;width:auto;">
                </div>
                <p class="footer-redesign__brand-desc">
                    Cộng đồng Game Developer Việt Nam. Kết nối developer, chia sẻ kiến thức, tìm việc làm và phát triển game cùng nhau.
                </p>
                <div class="footer-redesign__socials">
                    <a href="https://facebook.com/lamgamevn" class="footer-redesign__social" aria-label="Facebook" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="https://www.youtube.com/channel/UCv2lripWdZDKtlrRy1J0dBw" class="footer-redesign__social" aria-label="YouTube" target="_blank"><i class="fa fa-youtube-play"></i></a>
                    <a href="https://tiktok.com/@lamgamevn" class="footer-redesign__social" aria-label="TikTok" target="_blank"><i class="fa fa-music"></i></a>
                    <a href="https://github.com/lamgamevn" class="footer-redesign__social" aria-label="GitHub" target="_blank"><i class="fa fa-github"></i></a>
                </div>
            </div>

            {{-- Sản phẩm --}}
            <div class="footer-redesign__col">
                <h4>Sản phẩm</h4>
                <ul class="footer-redesign__links">
                    <li><a href="{{ route('lamgame.source-game') }}">Source Game</a></li>
                    <li><a href="{{ route('lamgame.ai-tools') }}">AI Tools</a></li>
                    <li><a href="{{ route('mini-game.index') }}">Chơi Game</a></li>
                    <li><a href="{{ route('lamgame.viec-lam-game') }}">Việc làm</a></li>
                    <li><a href="{{ route('forum.index') }}">Forum</a></li>
                </ul>
            </div>

            {{-- Chính sách --}}
            <div class="footer-redesign__col">
                <h4>Chính sách</h4>
                <ul class="footer-redesign__links">
                    <li><a href="/page/chinh-sach-bao-mat">Bảo mật</a></li>
                    <li><a href="/page/dieu-khoan-dich-vu">Điều khoản</a></li>
                    <li><a href="/page/chinh-sach-thanh-toan-rut-tien">Thanh toán</a></li>
                    <li><a href="/page/chinh-sach-hoan-tien-tranh-chap">Hoàn tiền</a></li>
                </ul>
            </div>

            {{-- Liên hệ --}}
            <div class="footer-redesign__col">
                <h4>Liên hệ</h4>
                <div class="footer-redesign__contact-item"><i class="fa fa-map-marker"></i><span>E.Town Central, 11 Đoàn Văn Bơ, P.13, Q.4, TP.HCM</span></div>
                <div class="footer-redesign__contact-item"><i class="fa fa-phone"></i><span>09.1111.8300</span></div>
                <div class="footer-redesign__contact-item"><i class="fa fa-envelope-o"></i><span>salegamevui@gmail.com</span></div>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="footer-redesign__bottom">
            <span>&copy; {{ date('Y') }} LamGame.vn — Cộng đồng Game Developer Việt Nam</span>
            <a href="{{ route('lamgame.lien-he') }}">Hỗ trợ</a>
        </div>
    </div>
</footer>
