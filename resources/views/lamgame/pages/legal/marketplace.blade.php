@extends('layouts.master')

@section('page_title', 'Điều khoản Marketplace - LamGame.vn')
@section('page_description', 'Điều khoản cho người mua và người bán trên Marketplace LamGame.vn - Nền tảng mua bán source code game.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Điều khoản Marketplace",
    "description": "Điều khoản cho người mua và người bán trên Marketplace LamGame.vn",
    "url": "{{ url('/dieu-khoan-marketplace') }}",
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
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Marketplace Terms
            </span>
            <h1>Điều khoản Marketplace</h1>
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
                        <li><a href="#nguoi-mua">2. Điều khoản Người mua</a></li>
                        <li><a href="#nguoi-ban">3. Điều khoản Người bán</a></li>
                        <li><a href="#san-pham">4. Yêu cầu sản phẩm</a></li>
                        <li><a href="#gia-phi">5. Giá & Phí</a></li>
                        <li><a href="#thanh-toan">6. Thanh toán Seller</a></li>
                        <li><a href="#ban-quyen">7. Bản quyền & License</a></li>
                        <li><a href="#tranh-chap">8. Giải quyết tranh chấp</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Giới thiệu</h2>
                        <p>LamGame Marketplace là nền tảng kết nối người mua và người bán source code game, assets, templates. Điều khoản này bổ sung cho <a href="{{ url('/dieu-khoan-su-dung') }}">Điều khoản Sử dụng</a> chung.</p>
                        
                        <h3>Các bên liên quan:</h3>
                        <ul>
                            <li><strong>LamGame:</strong> Nền tảng trung gian, cung cấp hạ tầng</li>
                            <li><strong>Seller (Người bán):</strong> Tạo và bán sản phẩm</li>
                            <li><strong>Buyer (Người mua):</strong> Mua và sử dụng sản phẩm</li>
                        </ul>
                    </section>

                    <section id="nguoi-mua">
                        <h2>2. Điều khoản Người mua</h2>
                        
                        <h3>2.1. Quyền lợi</h3>
                        <ul>
                            <li>Nhận sản phẩm đúng như mô tả</li>
                            <li>Hỗ trợ từ seller trong 30 ngày đầu</li>
                            <li>Hoàn tiền theo <a href="{{ url('/chinh-sach-hoan-tien') }}">Chính sách Hoàn tiền</a></li>
                            <li>Cập nhật miễn phí (tùy chính sách seller)</li>
                            <li>Đánh giá và review sản phẩm</li>
                        </ul>

                        <h3>2.2. Trách nhiệm</h3>
                        <ul>
                            <li>Đọc kỹ mô tả, demo trước khi mua</li>
                            <li>Kiểm tra tương thích với môi trường của bạn</li>
                            <li>Tuân thủ license sản phẩm</li>
                            <li>Không chia sẻ/bán lại source code</li>
                            <li>Đánh giá trung thực, không spam</li>
                        </ul>

                        <h3>2.3. Hỗ trợ sản phẩm</h3>
                        <ul>
                            <li>Seller hỗ trợ: Cài đặt, bug trong source, hướng dẫn sử dụng</li>
                            <li>Seller KHÔNG hỗ trợ: Customize theo yêu cầu riêng, dạy code từ đầu</li>
                            <li>Thời gian phản hồi: Tùy seller, thường 24-72h</li>
                        </ul>
                    </section>

                    <section id="nguoi-ban">
                        <h2>3. Điều khoản Người bán</h2>
                        
                        <h3>3.1. Đăng ký Seller</h3>
                        <ul>
                            <li>Đăng ký tài khoản seller tại <a href="{{ url('/seller/register') }}">lamgame.vn/seller/register</a></li>
                            <li>Cung cấp thông tin chính xác: Tên, email, thông tin ngân hàng</li>
                            <li>Chờ duyệt: 1-3 ngày làm việc</li>
                        </ul>

                        <h3>3.2. Quyền lợi Seller</h3>
                        <ul>
                            <li>Đăng bán sản phẩm trên marketplace</li>
                            <li>Tự định giá sản phẩm</li>
                            <li>Nhận 70% doanh thu (LamGame giữ 30%)</li>
                            <li>Truy cập analytics và thống kê bán hàng</li>
                            <li>Hỗ trợ marketing từ LamGame</li>
                        </ul>

                        <h3>3.3. Trách nhiệm Seller</h3>
                        <ul>
                            <li>Đảm bảo sản phẩm hoạt động đúng mô tả</li>
                            <li>Hỗ trợ buyer trong 30 ngày</li>
                            <li>Cập nhật sản phẩm khi có lỗi nghiêm trọng</li>
                            <li>Phản hồi yêu cầu hỗ trợ trong 72h</li>
                            <li>Tuân thủ luật bản quyền</li>
                        </ul>

                        <h3>3.4. Hành vi bị cấm</h3>
                        <ul>
                            <li>Bán sản phẩm vi phạm bản quyền</li>
                            <li>Bán malware, backdoor, mã độc</li>
                            <li>Mô tả sai lệch sản phẩm</li>
                            <li>Fake reviews, manipulate ratings</li>
                            <li>Liên hệ buyer để giao dịch ngoài platform</li>
                        </ul>
                    </section>

                    <section id="san-pham">
                        <h2>4. Yêu cầu sản phẩm</h2>
                        
                        <h3>4.1. Chất lượng tối thiểu</h3>
                        <ul>
                            <li>Code chạy được với engine version đã ghi</li>
                            <li>Đầy đủ file để build/compile</li>
                            <li>Documentation hoặc README cơ bản</li>
                            <li>Screenshots/video demo thực tế</li>
                        </ul>

                        <h3>4.2. Sản phẩm bị cấm</h3>
                        <ul>
                            <li>Vi phạm bản quyền (stolen assets, code)</li>
                            <li>Nội dung người lớn, bạo lực quá mức</li>
                            <li>Chứa malware, backdoor</li>
                            <li>Gambling (cờ bạc) không có giấy phép</li>
                            <li>Clone game có bản quyền</li>
                        </ul>

                        <h3>4.3. Quy trình duyệt</h3>
                        <ol>
                            <li>Seller submit sản phẩm</li>
                            <li>Admin review (1-5 ngày)</li>
                            <li>Approved → Live trên marketplace</li>
                            <li>Rejected → Nhận feedback để sửa</li>
                        </ol>
                    </section>

                    <section id="gia-phi">
                        <h2>5. Giá & Phí</h2>
                        
                        <h3>5.1. Giá sản phẩm</h3>
                        <ul>
                            <li>Seller tự định giá, từ 50,000 VND trở lên</li>
                            <li>Có thể tạo nhiều license tier (Personal, Commercial, Extended)</li>
                            <li>Có thể chạy khuyến mãi, giảm giá</li>
                        </ul>

                        <h3>5.2. Phí Marketplace</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Hạng mục</th>
                                    <th>Phí</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Phí hoa hồng</td>
                                    <td>30% mỗi giao dịch</td>
                                </tr>
                                <tr>
                                    <td>Phí thanh toán</td>
                                    <td>Đã bao gồm trong 30%</td>
                                </tr>
                                <tr>
                                    <td>Phí rút tiền</td>
                                    <td>Miễn phí (chuyển khoản VN)</td>
                                </tr>
                            </tbody>
                        </table>
                        <p><strong>Seller nhận:</strong> 70% giá bán × số lượng bán</p>
                    </section>

                    <section id="thanh-toan">
                        <h2>6. Thanh toán cho Seller</h2>
                        
                        <h3>6.1. Điều kiện rút tiền</h3>
                        <ul>
                            <li>Số dư tối thiểu: 100,000 VND</li>
                            <li>Tài khoản đã xác minh thông tin ngân hàng</li>
                            <li>Không có tranh chấp/refund pending</li>
                        </ul>

                        <h3>6.2. Lịch thanh toán</h3>
                        <ul>
                            <li>Yêu cầu rút tiền: Bất cứ lúc nào</li>
                            <li>Xử lý: 3-5 ngày làm việc</li>
                            <li>Tiền giữ (hold): 14 ngày sau giao dịch (để xử lý refund nếu có)</li>
                        </ul>

                        <h3>6.3. Phương thức thanh toán</h3>
                        <ul>
                            <li>Chuyển khoản ngân hàng Việt Nam</li>
                            <li>Yêu cầu: Tên chủ tài khoản khớp với tên đăng ký</li>
                        </ul>
                    </section>

                    <section id="ban-quyen">
                        <h2>7. Bản quyền & License</h2>
                        
                        <h3>7.1. Quyền sở hữu</h3>
                        <ul>
                            <li>Seller giữ quyền sở hữu trí tuệ sản phẩm</li>
                            <li>Buyer nhận quyền sử dụng theo license đã mua</li>
                            <li>LamGame không sở hữu sản phẩm của seller</li>
                        </ul>

                        <h3>7.2. Loại License</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>License</th>
                                    <th>Sử dụng</th>
                                    <th>Bán lại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Personal</td>
                                    <td>1 dự án, không thương mại</td>
                                    <td>❌</td>
                                </tr>
                                <tr>
                                    <td>Commercial</td>
                                    <td>1 dự án thương mại</td>
                                    <td>❌</td>
                                </tr>
                                <tr>
                                    <td>Extended</td>
                                    <td>Không giới hạn dự án</td>
                                    <td>❌ (không bán source)</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>7.3. Vi phạm license</h3>
                        <p>Nếu phát hiện vi phạm:</p>
                        <ul>
                            <li>Cảnh cáo lần 1</li>
                            <li>Khóa tài khoản nếu tái phạm</li>
                            <li>Seller có quyền yêu cầu bồi thường</li>
                        </ul>
                    </section>

                    <section id="tranh-chap">
                        <h2>8. Giải quyết tranh chấp</h2>
                        
                        <h3>8.1. Quy trình</h3>
                        <ol>
                            <li><strong>Trao đổi trực tiếp:</strong> Buyer và Seller thương lượng</li>
                            <li><strong>Hỗ trợ LamGame:</strong> Nếu không giải quyết được, liên hệ support</li>
                            <li><strong>Quyết định cuối:</strong> LamGame đưa ra quyết định dựa trên bằng chứng</li>
                        </ol>

                        <h3>8.2. Trường hợp thường gặp</h3>
                        <ul>
                            <li><strong>Sản phẩm không hoạt động:</strong> Seller sửa hoặc hoàn tiền</li>
                            <li><strong>Thiếu support:</strong> Cảnh cáo seller, gia hạn support cho buyer</li>
                            <li><strong>Vi phạm bản quyền:</strong> Gỡ sản phẩm, hoàn tiền buyer</li>
                        </ul>

                        <div class="lg-legal__contact">
                            <p><strong>Liên hệ giải quyết tranh chấp</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Tiêu đề: [DISPUTE] Mã đơn hàng - Mô tả ngắn</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Điều khoản này có thể được cập nhật. Thay đổi quan trọng sẽ được thông báo cho seller qua email.</p>
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
