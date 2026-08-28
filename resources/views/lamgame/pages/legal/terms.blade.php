@extends('layouts.master')

@section('page_title', 'Điều khoản Sử dụng - LamGame.vn')
@section('page_description', 'Điều khoản và điều kiện sử dụng dịch vụ LamGame.vn - Marketplace source code game và công cụ AI.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Điều khoản Sử dụng",
    "description": "Điều khoản và điều kiện sử dụng dịch vụ LamGame.vn",
    "url": "{{ url('/dieu-khoan-su-dung') }}",
    "inLanguage": "vi",
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
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Terms of Service
            </span>
            <h1>Điều khoản Sử dụng</h1>
            <p>Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Mục lục</h3>
                    <ul>
                        <li><a href="#chap-nhan">1. Chấp nhận điều khoản</a></li>
                        <li><a href="#dich-vu">2. Mô tả dịch vụ</a></li>
                        <li><a href="#tai-khoan">3. Tài khoản</a></li>
                        <li><a href="#mua-hang">4. Mua hàng & Thanh toán</a></li>
                        <li><a href="#san-pham-so">5. Sản phẩm số</a></li>
                        <li><a href="#noi-dung">6. Nội dung người dùng</a></li>
                        <li><a href="#cam-ket">7. Cam kết sử dụng</a></li>
                        <li><a href="#trach-nhiem">8. Giới hạn trách nhiệm</a></li>
                        <li><a href="#cham-dut">9. Chấm dứt</a></li>
                        <li><a href="#lien-he">10. Liên hệ</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="chap-nhan">
                        <h2>1. Chấp nhận điều khoản</h2>
                        <p>Bằng việc truy cập và sử dụng website LamGame.vn ("Dịch vụ"), bạn đồng ý tuân thủ các điều khoản này. Nếu bạn không đồng ý, vui lòng không sử dụng Dịch vụ.</p>
                        <p>Các điều khoản bổ sung có thể áp dụng cho các dịch vụ cụ thể:</p>
                        <ul>
                            <li><a href="{{ url('/dieu-khoan-marketplace') }}">Điều khoản Marketplace</a> - Cho người mua/bán source code</li>
                            <li><a href="{{ url('/dieu-khoan-ai') }}">Điều khoản AI</a> - Cho các công cụ AI</li>
                            <li><a href="{{ url('/chinh-sach-hoan-tien') }}">Chính sách Hoàn tiền</a></li>
                        </ul>
                    </section>

                    <section id="dich-vu">
                        <h2>2. Mô tả dịch vụ</h2>
                        <p>LamGame.vn cung cấp:</p>
                        <ul>
                            <li><strong>Marketplace:</strong> Nền tảng mua bán source code game, assets, templates</li>
                            <li><strong>AI Tools:</strong> Công cụ AI hỗ trợ phát triển game (GDD Generator, Code Assistant, Asset Generator)</li>
                            <li><strong>Learning:</strong> Blog, tutorials, khóa học về game development</li>
                            <li><strong>Community:</strong> Diễn đàn trao đổi kiến thức</li>
                        </ul>
                        <p>Chúng tôi có quyền thay đổi, tạm dừng hoặc ngừng cung cấp bất kỳ phần nào của Dịch vụ với thông báo hợp lý.</p>
                    </section>

                    <section id="tai-khoan">
                        <h2>3. Tài khoản người dùng</h2>
                        
                        <h3>3.1. Đăng ký</h3>
                        <ul>
                            <li>Bạn phải từ 18 tuổi trở lên hoặc có sự đồng ý của phụ huynh</li>
                            <li>Cung cấp thông tin chính xác và cập nhật</li>
                            <li>Mỗi người chỉ được sử dụng một tài khoản</li>
                        </ul>

                        <h3>3.2. Bảo mật tài khoản</h3>
                        <ul>
                            <li>Bạn chịu trách nhiệm bảo mật mật khẩu</li>
                            <li>Thông báo ngay nếu phát hiện truy cập trái phép</li>
                            <li>Không chia sẻ tài khoản với người khác</li>
                        </ul>

                        <h3>3.3. Đình chỉ tài khoản</h3>
                        <p>Chúng tôi có quyền đình chỉ hoặc xóa tài khoản nếu bạn vi phạm điều khoản, bao gồm:</p>
                        <ul>
                            <li>Cung cấp thông tin sai</li>
                            <li>Gian lận, lừa đảo</li>
                            <li>Vi phạm bản quyền</li>
                            <li>Spam hoặc quấy rối người dùng khác</li>
                        </ul>
                    </section>

                    <section id="mua-hang">
                        <h2>4. Mua hàng & Thanh toán</h2>
                        
                        <h3>4.1. Giá cả</h3>
                        <ul>
                            <li>Giá hiển thị bằng VND, đã bao gồm VAT (nếu có)</li>
                            <li>Giá có thể thay đổi mà không cần thông báo trước</li>
                            <li>Giá áp dụng là giá tại thời điểm đặt hàng</li>
                        </ul>

                        <h3>4.2. Phương thức thanh toán</h3>
                        <ul>
                            <li><strong>PayPal:</strong> Thanh toán quốc tế (Visa, Mastercard, PayPal balance)</li>
                            <li><strong>LemonSqueezy:</strong> Apple Pay, Google Pay, Cards</li>
                        </ul>
                        <p>Tất cả thanh toán được xử lý qua đối tác bên thứ ba. Chúng tôi không lưu trữ thông tin thẻ.</p>

                        <h3>4.3. Xác nhận đơn hàng</h3>
                        <p>Đơn hàng được xác nhận qua email sau khi thanh toán thành công. Sản phẩm số được giao ngay lập tức.</p>
                    </section>

                    <section id="san-pham-so">
                        <h2>5. Sản phẩm số & License</h2>
                        
                        <h3>5.1. Giao hàng</h3>
                        <ul>
                            <li>Sản phẩm số được giao qua download link trong tài khoản</li>
                            <li>License key (nếu có) được gửi qua email</li>
                            <li>Link download có giới hạn số lần tải</li>
                        </ul>

                        <h3>5.2. Quyền sử dụng</h3>
                        <p>Khi mua sản phẩm, bạn được cấp quyền sử dụng theo loại license:</p>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>License</th>
                                    <th>Quyền</th>
                                    <th>Giới hạn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Personal</td>
                                    <td>Sử dụng cho 1 dự án cá nhân</td>
                                    <td>Không thương mại, không bán lại</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td>Sử dụng cho 1 dự án thương mại</td>
                                    <td>Không bán lại source</td>
                                </tr>
                                <tr>
                                    <td>Extended</td>
                                    <td>Sử dụng không giới hạn dự án</td>
                                    <td>Không bán lại source nguyên bản</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>5.3. Nghiêm cấm</h3>
                        <ul>
                            <li>Bán lại, phân phối lại source code</li>
                            <li>Chia sẻ license/download link</li>
                            <li>Xóa thông tin bản quyền trong code</li>
                            <li>Sử dụng cho mục đích bất hợp pháp</li>
                        </ul>
                    </section>

                    <section id="noi-dung">
                        <h2>6. Nội dung người dùng</h2>
                        
                        <h3>6.1. Nội dung bạn đăng tải</h3>
                        <p>Khi đăng nội dung (bài viết, đánh giá, bình luận), bạn:</p>
                        <ul>
                            <li>Giữ quyền sở hữu nội dung của mình</li>
                            <li>Cấp cho chúng tôi quyền hiển thị, lưu trữ nội dung</li>
                            <li>Chịu trách nhiệm về tính hợp pháp của nội dung</li>
                        </ul>

                        <h3>6.2. Nội dung bị cấm</h3>
                        <ul>
                            <li>Vi phạm bản quyền, nhãn hiệu</li>
                            <li>Nội dung bất hợp pháp, khiêu dâm, bạo lực</li>
                            <li>Spam, quảng cáo trái phép</li>
                            <li>Thông tin sai lệch, gây hiểu nhầm</li>
                            <li>Xúc phạm, quấy rối người khác</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Cam kết sử dụng</h2>
                        <p>Bạn cam kết:</p>
                        <ul>
                            <li>Tuân thủ pháp luật Việt Nam</li>
                            <li>Không gây hại cho hệ thống (hack, DDoS, malware)</li>
                            <li>Không thu thập thông tin người dùng khác</li>
                            <li>Không mạo danh người khác hoặc tổ chức</li>
                            <li>Không can thiệp vào hoạt động của Dịch vụ</li>
                        </ul>
                    </section>

                    <section id="trach-nhiem">
                        <h2>8. Giới hạn trách nhiệm</h2>
                        
                        <h3>8.1. Dịch vụ "nguyên trạng"</h3>
                        <p>Dịch vụ được cung cấp "nguyên trạng" (as is). Chúng tôi không đảm bảo:</p>
                        <ul>
                            <li>Dịch vụ không bị gián đoạn hoặc lỗi</li>
                            <li>Sản phẩm phù hợp với mục đích cụ thể của bạn</li>
                            <li>Kết quả từ AI Tools chính xác 100%</li>
                        </ul>

                        <h3>8.2. Giới hạn bồi thường</h3>
                        <p>Trong mọi trường hợp, trách nhiệm của chúng tôi không vượt quá số tiền bạn đã thanh toán trong 12 tháng gần nhất.</p>

                        <h3>8.3. Sản phẩm từ Seller</h3>
                        <p>Chúng tôi là nền tảng trung gian. Seller chịu trách nhiệm về chất lượng sản phẩm của họ. Chúng tôi hỗ trợ giải quyết tranh chấp theo <a href="{{ url('/chinh-sach-hoan-tien') }}">Chính sách Hoàn tiền</a>.</p>
                    </section>

                    <section id="cham-dut">
                        <h2>9. Chấm dứt</h2>
                        <p>Bạn có thể ngừng sử dụng Dịch vụ bất cứ lúc nào.</p>
                        <p>Chúng tôi có thể chấm dứt hoặc đình chỉ tài khoản của bạn nếu:</p>
                        <ul>
                            <li>Vi phạm điều khoản này</li>
                            <li>Hành vi gian lận hoặc bất hợp pháp</li>
                            <li>Yêu cầu từ cơ quan có thẩm quyền</li>
                        </ul>
                        <p>Sau khi chấm dứt:</p>
                        <ul>
                            <li>Quyền truy cập Dịch vụ bị thu hồi</li>
                            <li>License đã mua vẫn có hiệu lực (nếu không vi phạm)</li>
                            <li>Số dư chưa rút được xử lý theo chính sách</li>
                        </ul>
                    </section>

                    <section id="lien-he">
                        <h2>10. Liên hệ & Luật áp dụng</h2>
                        
                        <h3>10.1. Liên hệ</h3>
                        <div class="lg-legal__contact">
                            <p><strong>LamGame.vn</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Website: <a href="{{ url('/lien-he') }}">lamgame.vn/lien-he</a></p>
                        </div>

                        <h3>10.2. Luật áp dụng</h3>
                        <p>Điều khoản này được điều chỉnh bởi pháp luật Việt Nam. Mọi tranh chấp sẽ được giải quyết tại Tòa án có thẩm quyền tại Việt Nam.</p>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Bằng việc tiếp tục sử dụng LamGame.vn, bạn xác nhận đã đọc và đồng ý với các điều khoản này.</p>
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
