@extends('layouts.master')

@section('page_title', 'Chính sách Hoàn tiền - LamGame.vn')
@section('page_description', 'Chính sách hoàn tiền và đổi trả cho sản phẩm số trên LamGame.vn. Bảo vệ quyền lợi người mua.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Chính sách Hoàn tiền",
    "description": "Chính sách hoàn tiền cho sản phẩm số trên LamGame.vn",
    "url": "{{ url('/chinh-sach-hoan-tien') }}",
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
                    <polyline points="23,4 23,10 17,10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Refund Policy
            </span>
            <h1>Chính sách Hoàn tiền</h1>
            <p>Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
        </div>
    </div>

    <div class="lg-legal__content">
        <div class="lg-v2-container">
            <div class="lg-legal__grid">
                <nav class="lg-legal__nav">
                    <h3>Mục lục</h3>
                    <ul>
                        <li><a href="#tong-quan">1. Tổng quan</a></li>
                        <li><a href="#dieu-kien">2. Điều kiện hoàn tiền</a></li>
                        <li><a href="#khong-hoan">3. Trường hợp không hoàn</a></li>
                        <li><a href="#quy-trinh">4. Quy trình yêu cầu</a></li>
                        <li><a href="#thoi-gian">5. Thời gian xử lý</a></li>
                        <li><a href="#subscription">6. Hoàn tiền Subscription</a></li>
                        <li><a href="#tranh-chap">7. Giải quyết tranh chấp</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="tong-quan">
                        <h2>1. Tổng quan</h2>
                        <p>Tại LamGame.vn, chúng tôi cam kết sự hài lòng của khách hàng. Do đặc thù sản phẩm số (source code, digital assets), chính sách hoàn tiền được áp dụng trong các trường hợp cụ thể.</p>
                        
                        <div class="lg-legal__notice">
                            <p><strong>⏰ Thời hạn yêu cầu hoàn tiền: 7 ngày</strong> kể từ ngày mua hàng.</p>
                        </div>
                    </section>

                    <section id="dieu-kien">
                        <h2>2. Điều kiện được hoàn tiền</h2>
                        <p>Bạn được hoàn tiền <strong>100%</strong> trong các trường hợp sau:</p>
                        
                        <h3>2.1. Sản phẩm không đúng mô tả</h3>
                        <ul>
                            <li>Thiếu tính năng được liệt kê trong mô tả</li>
                            <li>Phiên bản engine/framework khác với mô tả</li>
                            <li>File bị hỏng, không thể giải nén</li>
                        </ul>

                        <h3>2.2. Sản phẩm không hoạt động</h3>
                        <ul>
                            <li>Lỗi compile/build không thể sửa</li>
                            <li>Project không chạy được với engine version đã ghi</li>
                            <li>Thiếu file quan trọng để chạy project</li>
                        </ul>

                        <h3>2.3. Vi phạm bản quyền</h3>
                        <ul>
                            <li>Sản phẩm chứa nội dung vi phạm bản quyền</li>
                            <li>Seller không có quyền bán sản phẩm</li>
                        </ul>

                        <h3>2.4. Mua trùng lặp</h3>
                        <ul>
                            <li>Mua cùng sản phẩm 2 lần do lỗi hệ thống</li>
                            <li>Thanh toán bị tính phí 2 lần</li>
                        </ul>
                    </section>

                    <section id="khong-hoan">
                        <h2>3. Trường hợp KHÔNG được hoàn tiền</h2>
                        <div class="lg-legal__warning">
                            <p>⚠️ Các trường hợp sau sẽ không được hoàn tiền:</p>
                        </div>
                        
                        <ul>
                            <li><strong>Đổi ý:</strong> Mua rồi không muốn dùng nữa</li>
                            <li><strong>Không phù hợp:</strong> Sản phẩm không phù hợp với dự án của bạn (đã mô tả đầy đủ)</li>
                            <li><strong>Thiếu kỹ năng:</strong> Không biết cách sử dụng source code</li>
                            <li><strong>Đã tải và sử dụng:</strong> Đã download nhiều lần và sử dụng trong dự án</li>
                            <li><strong>Quá thời hạn:</strong> Yêu cầu sau 7 ngày kể từ ngày mua</li>
                            <li><strong>Vi phạm license:</strong> Đã chia sẻ hoặc bán lại source code</li>
                            <li><strong>Sản phẩm khuyến mãi:</strong> Sản phẩm miễn phí hoặc giảm giá trên 70%</li>
                        </ul>
                    </section>

                    <section id="quy-trinh">
                        <h2>4. Quy trình yêu cầu hoàn tiền</h2>
                        
                        <h3>Bước 1: Liên hệ hỗ trợ</h3>
                        <p>Gửi email đến <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a> với thông tin:</p>
                        <ul>
                            <li>Mã đơn hàng (Order ID)</li>
                            <li>Email đăng ký tài khoản</li>
                            <li>Lý do yêu cầu hoàn tiền</li>
                            <li>Bằng chứng (screenshot, video nếu có)</li>
                        </ul>

                        <h3>Bước 2: Xác minh</h3>
                        <p>Chúng tôi sẽ xem xét yêu cầu trong vòng <strong>2-3 ngày làm việc</strong>:</p>
                        <ul>
                            <li>Kiểm tra thông tin đơn hàng</li>
                            <li>Xác minh lý do hoàn tiền</li>
                            <li>Liên hệ seller nếu cần</li>
                        </ul>

                        <h3>Bước 3: Quyết định</h3>
                        <p>Chúng tôi sẽ thông báo kết quả qua email:</p>
                        <ul>
                            <li><strong>Chấp nhận:</strong> Tiến hành hoàn tiền</li>
                            <li><strong>Từ chối:</strong> Giải thích lý do cụ thể</li>
                            <li><strong>Cần thêm thông tin:</strong> Yêu cầu bổ sung bằng chứng</li>
                        </ul>

                        <h3>Bước 4: Hoàn tiền</h3>
                        <p>Tiền được hoàn về phương thức thanh toán gốc trong <strong>5-10 ngày làm việc</strong>.</p>
                    </section>

                    <section id="thoi-gian">
                        <h2>5. Thời gian xử lý</h2>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Phương thức thanh toán</th>
                                    <th>Thời gian hoàn tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PayPal</td>
                                    <td>3-5 ngày làm việc</td>
                                </tr>
                                <tr>
                                    <td>LemonSqueezy (Card)</td>
                                    <td>5-10 ngày làm việc</td>
                                </tr>
                                <tr>
                                    <td>Apple Pay / Google Pay</td>
                                    <td>5-10 ngày làm việc</td>
                                </tr>
                            </tbody>
                        </table>
                        <p><em>Thời gian thực tế có thể khác tùy thuộc vào ngân hàng/đối tác thanh toán.</em></p>
                    </section>

                    <section id="subscription">
                        <h2>6. Hoàn tiền Subscription (AI Tools)</h2>
                        
                        <h3>6.1. Gói tháng</h3>
                        <ul>
                            <li>Hủy bất cứ lúc nào, không bị tính phí tháng tiếp theo</li>
                            <li>Không hoàn tiền cho tháng hiện tại đã sử dụng</li>
                            <li>Quyền truy cập duy trì đến hết chu kỳ thanh toán</li>
                        </ul>

                        <h3>6.2. Gói năm</h3>
                        <ul>
                            <li>Hoàn tiền theo tỷ lệ trong 30 ngày đầu nếu chưa sử dụng quá 20% quota</li>
                            <li>Sau 30 ngày: không hoàn tiền, có thể hủy để không gia hạn</li>
                        </ul>

                        <h3>6.3. Cách hủy subscription</h3>
                        <ol>
                            <li>Đăng nhập tài khoản</li>
                            <li>Vào <strong>Tài khoản → Subscription</strong></li>
                            <li>Nhấn <strong>Hủy subscription</strong></li>
                            <li>Xác nhận hủy</li>
                        </ol>
                    </section>

                    <section id="tranh-chap">
                        <h2>7. Giải quyết tranh chấp</h2>
                        <p>Nếu không đồng ý với quyết định hoàn tiền:</p>
                        
                        <h3>7.1. Khiếu nại lần 2</h3>
                        <p>Gửi email khiếu nại với bằng chứng bổ sung. Chúng tôi sẽ xem xét lại trong 5 ngày làm việc.</p>

                        <h3>7.2. Chargeback</h3>
                        <p>Bạn có quyền yêu cầu chargeback qua ngân hàng/PayPal. Tuy nhiên:</p>
                        <ul>
                            <li>Chargeback gian lận sẽ dẫn đến khóa tài khoản vĩnh viễn</li>
                            <li>Chúng tôi sẽ cung cấp bằng chứng giao hàng cho đối tác thanh toán</li>
                        </ul>

                        <h3>7.3. Liên hệ</h3>
                        <div class="lg-legal__contact">
                            <p><strong>Hỗ trợ hoàn tiền</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Tiêu đề: [REFUND] Mã đơn hàng - Tên của bạn</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Chính sách này áp dụng cho tất cả giao dịch trên LamGame.vn. Chúng tôi có quyền từ chối hoàn tiền nếu phát hiện lạm dụng chính sách.</p>
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
