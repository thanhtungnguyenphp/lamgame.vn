@extends('layouts.master')

@section('page_title', 'Điều khoản AI Tools - LamGame.vn')
@section('page_description', 'Điều khoản sử dụng công cụ AI trên LamGame.vn - GDD Generator, Code Assistant, Asset Generator.')

@push('schema_markup')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Điều khoản AI Tools",
    "description": "Điều khoản sử dụng công cụ AI trên LamGame.vn",
    "url": "{{ url('/dieu-khoan-ai') }}",
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
                    <path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M7.5 13A1.5 1.5 0 1 0 9 14.5 1.5 1.5 0 0 0 7.5 13m9 0a1.5 1.5 0 1 0 1.5 1.5 1.5 1.5 0 0 0-1.5-1.5"/>
                </svg>
                AI Terms
            </span>
            <h1>Điều khoản AI Tools</h1>
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
                        <li><a href="#dich-vu">2. Các công cụ AI</a></li>
                        <li><a href="#su-dung">3. Điều kiện sử dụng</a></li>
                        <li><a href="#du-lieu">4. Dữ liệu & Riêng tư</a></li>
                        <li><a href="#so-huu">5. Quyền sở hữu nội dung</a></li>
                        <li><a href="#gioi-han">6. Giới hạn & Quota</a></li>
                        <li><a href="#cam-ket">7. Cam kết sử dụng</a></li>
                        <li><a href="#mien-tru">8. Miễn trừ trách nhiệm</a></li>
                    </ul>
                </nav>

                <article class="lg-legal__article">
                    <section id="gioi-thieu">
                        <h2>1. Giới thiệu</h2>
                        <p>LamGame AI Tools là bộ công cụ trí tuệ nhân tạo hỗ trợ game developers. Điều khoản này bổ sung cho <a href="{{ url('/dieu-khoan-su-dung') }}">Điều khoản Sử dụng</a> chung.</p>
                        
                        <div class="lg-legal__notice">
                            <p><strong>⚠️ Quan trọng:</strong> AI có thể tạo ra nội dung không chính xác. Luôn kiểm tra và xác minh kết quả trước khi sử dụng.</p>
                        </div>
                    </section>

                    <section id="dich-vu">
                        <h2>2. Các công cụ AI</h2>
                        
                        <h3>2.1. Công cụ hiện có</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Công cụ</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>GDD Generator</td>
                                    <td>Tạo Game Design Document từ ý tưởng</td>
                                </tr>
                                <tr>
                                    <td>Code Generator</td>
                                    <td>Sinh code cho Unity, Godot, Unreal</td>
                                </tr>
                                <tr>
                                    <td>Code Debug</td>
                                    <td>Phân tích và sửa lỗi code</td>
                                </tr>
                                <tr>
                                    <td>Code Review</td>
                                    <td>Đánh giá chất lượng code</td>
                                </tr>
                                <tr>
                                    <td>Test Generator</td>
                                    <td>Tạo unit tests tự động</td>
                                </tr>
                                <tr>
                                    <td>Asset Generator</td>
                                    <td>Tạo mô tả cho game assets</td>
                                </tr>
                            </tbody>
                        </table>

                        <h3>2.2. Mô hình AI sử dụng</h3>
                        <p>Chúng tôi sử dụng các mô hình từ:</p>
                        <ul>
                            <li>OpenAI (GPT-4o, GPT-4o-mini)</li>
                            <li>Google (Gemini)</li>
                            <li>Anthropic (Claude)</li>
                            <li>DeepSeek</li>
                        </ul>
                        <p>Mô hình được chọn tự động dựa trên gói subscription và loại tác vụ.</p>
                    </section>

                    <section id="su-dung">
                        <h2>3. Điều kiện sử dụng</h2>
                        
                        <h3>3.1. Yêu cầu</h3>
                        <ul>
                            <li>Có tài khoản LamGame.vn</li>
                            <li>Đăng ký gói subscription (Free hoặc trả phí)</li>
                            <li>Chấp nhận điều khoản này</li>
                        </ul>

                        <h3>3.2. Gói subscription</h3>
                        <table class="lg-legal__table">
                            <thead>
                                <tr>
                                    <th>Gói</th>
                                    <th>Giá</th>
                                    <th>Quota/tháng</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Free</td>
                                    <td>0đ</td>
                                    <td>10 requests</td>
                                </tr>
                                <tr>
                                    <td>Pro</td>
                                    <td>99,000đ/tháng</td>
                                    <td>500 requests</td>
                                </tr>
                                <tr>
                                    <td>Business</td>
                                    <td>299,000đ/tháng</td>
                                    <td>Unlimited</td>
                                </tr>
                            </tbody>
                        </table>
                    </section>

                    <section id="du-lieu">
                        <h2>4. Dữ liệu & Riêng tư</h2>
                        
                        <h3>4.1. Dữ liệu chúng tôi thu thập</h3>
                        <ul>
                            <li><strong>Prompts:</strong> Nội dung bạn gửi đến AI</li>
                            <li><strong>Responses:</strong> Kết quả AI trả về</li>
                            <li><strong>Metadata:</strong> Thời gian, model sử dụng, tokens</li>
                        </ul>

                        <h3>4.2. Cách sử dụng dữ liệu</h3>
                        <ul>
                            <li>Cung cấp dịch vụ AI</li>
                            <li>Tính quota và billing</li>
                            <li>Cải thiện chất lượng dịch vụ</li>
                            <li>Phát hiện lạm dụng</li>
                        </ul>

                        <h3>4.3. Chia sẻ với AI providers</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ Prompts của bạn được gửi đến các AI provider (OpenAI, Google, Anthropic) để xử lý. Chúng tôi không kiểm soát cách họ sử dụng dữ liệu.</p>
                        </div>
                        <ul>
                            <li>OpenAI: <a href="https://openai.com/policies/privacy-policy" target="_blank">Privacy Policy</a></li>
                            <li>Google: <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a></li>
                            <li>Anthropic: <a href="https://www.anthropic.com/privacy" target="_blank">Privacy Policy</a></li>
                        </ul>

                        <h3>4.4. Khuyến nghị</h3>
                        <ul>
                            <li><strong>KHÔNG</strong> gửi thông tin nhạy cảm (mật khẩu, API keys, dữ liệu cá nhân)</li>
                            <li><strong>KHÔNG</strong> gửi code chứa secrets hoặc credentials</li>
                            <li>Xóa thông tin nhạy cảm trước khi paste code</li>
                        </ul>
                    </section>

                    <section id="so-huu">
                        <h2>5. Quyền sở hữu nội dung</h2>
                        
                        <h3>5.1. Input (Prompts của bạn)</h3>
                        <p>Bạn giữ quyền sở hữu nội dung bạn gửi đến AI.</p>

                        <h3>5.2. Output (Kết quả AI)</h3>
                        <ul>
                            <li>Bạn có quyền sử dụng output cho mục đích cá nhân và thương mại</li>
                            <li>Output có thể tương tự với kết quả của người dùng khác</li>
                            <li>Chúng tôi không đảm bảo output là duy nhất hoặc không vi phạm bản quyền</li>
                        </ul>

                        <h3>5.3. Trách nhiệm kiểm tra</h3>
                        <p>Bạn chịu trách nhiệm:</p>
                        <ul>
                            <li>Kiểm tra output trước khi sử dụng</li>
                            <li>Đảm bảo không vi phạm bản quyền người khác</li>
                            <li>Test code trước khi deploy</li>
                        </ul>
                    </section>

                    <section id="gioi-han">
                        <h2>6. Giới hạn & Quota</h2>
                        
                        <h3>6.1. Quota theo gói</h3>
                        <ul>
                            <li>Mỗi request sử dụng 1 quota</li>
                            <li>Quota reset vào đầu mỗi tháng</li>
                            <li>Quota không cộng dồn sang tháng sau</li>
                        </ul>

                        <h3>6.2. Rate limiting</h3>
                        <ul>
                            <li>Tối đa 10 requests/phút</li>
                            <li>Tối đa 100 requests/giờ</li>
                            <li>Vượt limit → chờ hoặc nâng cấp gói</li>
                        </ul>

                        <h3>6.3. Token limits</h3>
                        <ul>
                            <li>Free: 2,000 tokens/request</li>
                            <li>Pro: 4,000 tokens/request</li>
                            <li>Business: 8,000 tokens/request</li>
                        </ul>
                    </section>

                    <section id="cam-ket">
                        <h2>7. Cam kết sử dụng</h2>
                        
                        <h3>7.1. Sử dụng được phép</h3>
                        <ul>
                            <li>Tạo GDD cho game projects</li>
                            <li>Sinh code cho game development</li>
                            <li>Debug và review code</li>
                            <li>Học tập và nghiên cứu</li>
                        </ul>

                        <h3>7.2. Sử dụng bị cấm</h3>
                        <ul>
                            <li>Tạo malware, virus, mã độc</li>
                            <li>Tạo nội dung bất hợp pháp, thù địch</li>
                            <li>Spam hoặc lạm dụng hệ thống</li>
                            <li>Bypass rate limits hoặc quota</li>
                            <li>Resell hoặc redistribute dịch vụ</li>
                            <li>Reverse engineer API</li>
                        </ul>

                        <h3>7.3. Vi phạm</h3>
                        <p>Vi phạm có thể dẫn đến:</p>
                        <ul>
                            <li>Cảnh cáo</li>
                            <li>Tạm khóa tài khoản</li>
                            <li>Hủy subscription (không hoàn tiền)</li>
                            <li>Khóa vĩnh viễn</li>
                        </ul>
                    </section>

                    <section id="mien-tru">
                        <h2>8. Miễn trừ trách nhiệm</h2>
                        
                        <h3>8.1. Độ chính xác</h3>
                        <div class="lg-legal__warning">
                            <p>⚠️ AI có thể tạo ra nội dung không chính xác, lỗi thời, hoặc không phù hợp. Chúng tôi KHÔNG đảm bảo độ chính xác của output.</p>
                        </div>

                        <h3>8.2. Không đảm bảo</h3>
                        <ul>
                            <li>Code chạy được 100%</li>
                            <li>Không có bugs hoặc security issues</li>
                            <li>Phù hợp với mục đích cụ thể</li>
                            <li>Dịch vụ không bị gián đoạn</li>
                        </ul>

                        <h3>8.3. Giới hạn trách nhiệm</h3>
                        <p>Chúng tôi không chịu trách nhiệm cho:</p>
                        <ul>
                            <li>Thiệt hại do sử dụng output AI</li>
                            <li>Mất dữ liệu hoặc gián đoạn dịch vụ</li>
                            <li>Vi phạm bản quyền do output AI gây ra</li>
                            <li>Quyết định kinh doanh dựa trên AI advice</li>
                        </ul>

                        <h3>8.4. Bồi thường</h3>
                        <p>Trong mọi trường hợp, trách nhiệm tối đa của chúng tôi không vượt quá số tiền bạn đã thanh toán cho subscription trong 3 tháng gần nhất.</p>

                        <div class="lg-legal__contact">
                            <p><strong>Hỗ trợ AI Tools</strong></p>
                            <p>Email: <a href="mailto:salegamevui@gmail.com">salegamevui@gmail.com</a></p>
                            <p>Tiêu đề: [AI SUPPORT] Mô tả vấn đề</p>
                        </div>
                    </section>

                    <section class="lg-legal__footer-note">
                        <p>Bằng việc sử dụng AI Tools, bạn xác nhận đã đọc và đồng ý với điều khoản này.</p>
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
