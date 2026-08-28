@extends('layouts.master')

@section('page_title', 'Chính sách Bảo mật - LamGame.vn')
@section('page_description', 'Chính sách bảo mật thông tin cá nhân của LamGame.vn. Cam kết bảo vệ quyền riêng tư của bạn.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Chính sách Bảo mật",
    "description": "Chính sách bảo mật thông tin cá nhân của LamGame.vn",
    "url": "{{ url('/chinh-sach-bao-mat') }}",
    "inLanguage": "vi",
    "isPartOf": {
        "@type": "WebSite",
        "name": "LamGame.vn",
        "url": "{{ url('/') }}"
    },
    "datePublished": "2026-01-01",
    "dateModified": "{{ date('Y-m-d') }}"
}
</script>
@endpush

@section('content')
<div class="lg-legal">
    <div class="lg-legal__hero">
        <div class="lg-v2-container">
            <span class="lg-legal__badge">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                Privacy Policy
            </span>
            <h1>Chính sách Bảo mật</h1>
            <p>Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Mục lục</h3>
                    <ul>
                        <li><a href="#gioi-thieu">1. Giới thiệu</a></li>
                        <li><a href="#thu-thap">2. Thông tin thu thập</a></li>
                        <li><a href="#su-dung">3. Mục đích sử dụng</a></li>
                        <li><a href="#chia-se">4. Chia sẻ thông tin</a></li>
                        <li><a href="#bao-mat">5. Bảo mật dữ liệu</a></li>
                        <li><a href="#cookie">6. Cookie</a></li>
                        <li><a href="#quyen-loi">7. Quyền của bạn</a></li>
                        <li><a href="#lien-he">8. Liên hệ</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Giới thiệu</h2>
                        <p>Chào mừng bạn đến với LamGame.vn ("chúng tôi", "của chúng tôi"). Chúng tôi cam kết bảo vệ quyền riêng tư và thông tin cá nhân của bạn.</p>
                        <p>Chính sách này giải thích cách chúng tôi thu thập, sử dụng và bảo vệ thông tin khi bạn sử dụng website LamGame.vn và các dịch vụ liên quan, bao gồm:</p>
                        <ul>
                            <li>Marketplace mua bán source code game</li>
                            <li>Công cụ AI cho game developers</li>
                            <li>Blog và tài liệu học tập</li>
                            <li>Diễn đàn cộng đồng</li>
                        </ul>
                    </section>

                    <section id="thu-thap">
                        <h2>2. Thông tin chúng tôi thu thập</h2>
                        
                        <h3>2.1. Thông tin bạn cung cấp trực tiếp</h3>
                        <ul>
                            <li><strong>Thông tin tài khoản:</strong> Họ tên, email, số điện thoại khi đăng ký</li>
                            <li><strong>Thông tin thanh toán:</strong> Xử lý qua LemonSqueezy/PayPal - chúng tôi không lưu trữ thông tin thẻ</li>
                            <li><strong>Thông tin seller:</strong> Tên shop, thông tin ngân hàng (cho việc thanh toán)</li>
                            <li><strong>Nội dung người dùng:</strong> Bài viết forum, đánh giá sản phẩm, tin nhắn liên hệ</li>
                        </ul>

                        <h3>2.2. Thông tin thu thập tự động</h3>
                        <ul>
                            <li><strong>Dữ liệu thiết bị:</strong> Loại trình duyệt, hệ điều hành, địa chỉ IP</li>
                            <li><strong>Dữ liệu sử dụng:</strong> Trang đã xem, thời gian truy cập, nguồn truy cập</li>
                            <li><strong>Dữ liệu AI:</strong> Prompts và responses khi sử dụng công cụ AI (để cải thiện dịch vụ)</li>
                        </ul>
                    </section>

                    <section id="su-dung">
                        <h2>3. Mục đích sử dụng thông tin</h2>
                        <p>Chúng tôi sử dụng thông tin của bạn để:</p>
                        <ul>
                            <li>Cung cấp và duy trì dịch vụ</li>
                            <li>Xử lý giao dịch và gửi xác nhận đơn hàng</li>
                            <li>Giao sản phẩm số (source code, license key)</li>
                            <li>Hỗ trợ khách hàng và phản hồi yêu cầu</li>
                            <li>Gửi thông báo về đơn hàng, cập nhật sản phẩm</li>
                            <li>Phân tích và cải thiện dịch vụ</li>
                            <li>Ngăn chặn gian lận và bảo vệ an ninh</li>
                            <li>Tuân thủ nghĩa vụ pháp lý</li>
                        </ul>
                    </section>

                    <section id="chia-se">
                        <h2>4. Chia sẻ thông tin</h2>
                        <p>Chúng tôi <strong>không bán</strong> thông tin cá nhân của bạn. Chúng tôi chỉ chia sẻ trong các trường hợp:</p>
                        
                        <h3>4.1. Đối tác dịch vụ</h3>
                        <ul>
                            <li><strong>LemonSqueezy/PayPal:</strong> Xử lý thanh toán</li>
                            <li><strong>SMTP2GO:</strong> Gửi email</li>
                            <li><strong>OpenAI/Google/Anthropic:</strong> Cung cấp dịch vụ AI (chỉ gửi nội dung prompt)</li>
                            <li><strong>Cloudflare:</strong> CDN và bảo mật</li>
                        </ul>

                        <h3>4.2. Seller</h3>
                        <p>Khi bạn mua sản phẩm, seller nhận được: Tên, email để hỗ trợ sản phẩm.</p>

                        <h3>4.3. Yêu cầu pháp lý</h3>
                        <p>Khi được yêu cầu bởi cơ quan có thẩm quyền theo quy định pháp luật Việt Nam.</p>
                    </section>

                    <section id="bao-mat">
                        <h2>5. Bảo mật dữ liệu</h2>
                        <p>Chúng tôi áp dụng các biện pháp bảo mật:</p>
                        <ul>
                            <li>Mã hóa SSL/TLS cho tất cả kết nối</li>
                            <li>Mã hóa mật khẩu bằng bcrypt</li>
                            <li>Lưu trữ file download trong private storage</li>
                            <li>Giám sát và phát hiện xâm nhập 24/7</li>
                            <li>Backup dữ liệu định kỳ</li>
                        </ul>
                        <div class="lg-legal__notice">
                            <p><strong>Lưu ý:</strong> Không có phương thức truyền tải qua Internet nào an toàn 100%. Chúng tôi nỗ lực bảo vệ dữ liệu nhưng không thể đảm bảo tuyệt đối.</p>
                        </div>
                    </section>

                    <section id="cookie">
                        <h2>6. Cookie và công nghệ theo dõi</h2>
                        <p>Chúng tôi sử dụng cookies để:</p>
                        <ul>
                            <li><strong>Cookies thiết yếu:</strong> Duy trì phiên đăng nhập, giỏ hàng</li>
                            <li><strong>Cookies phân tích:</strong> Google Analytics để hiểu hành vi người dùng</li>
                            <li><strong>Cookies chức năng:</strong> Ghi nhớ tùy chọn của bạn</li>
                        </ul>
                        <p>Bạn có thể tắt cookies trong trình duyệt, nhưng một số tính năng có thể không hoạt động.</p>
                    </section>

                    <section id="quyen-loi">
                        <h2>7. Quyền của bạn</h2>
                        <p>Theo quy định pháp luật Việt Nam và thông lệ quốc tế, bạn có quyền:</p>
                        <ul>
                            <li><strong>Truy cập:</strong> Yêu cầu bản sao dữ liệu cá nhân của bạn</li>
                            <li><strong>Chỉnh sửa:</strong> Cập nhật thông tin không chính xác</li>
                            <li><strong>Xóa:</strong> Yêu cầu xóa tài khoản và dữ liệu (ngoại trừ dữ liệu cần giữ theo luật)</li>
                            <li><strong>Hủy đăng ký:</strong> Từ chối nhận email marketing</li>
                            <li><strong>Xuất dữ liệu:</strong> Nhận dữ liệu ở định dạng có thể đọc được</li>
                        </ul>
                        <p>Để thực hiện các quyền này, vui lòng liên hệ: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                    </section>

                    <section id="lien-he">
                        <h2>8. Liên hệ</h2>
                        <p>Nếu bạn có câu hỏi về chính sách này, vui lòng liên hệ:</p>
                        <div class="lg-legal__contact">
                            <p><strong>LamGame.vn</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Website: <a href="{{ url('/lien-he') }}">lamgame.vn/lien-he</a></p>
                        </div>
                        <p>Chúng tôi sẽ phản hồi trong vòng 7 ngày làm việc.</p>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Chính sách này có thể được cập nhật định kỳ. Thay đổi quan trọng sẽ được thông báo qua email hoặc trên website.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal.css') }}">
@endpush
