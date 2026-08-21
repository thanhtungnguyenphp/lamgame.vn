@extends('layouts.master')

@section('page_title', 'Chính sách chỉnh sửa — LamGame.vn')
@section('page_description', 'Quy trình xử lý yêu cầu chỉnh sửa và cập nhật nội dung tại LamGame.vn')

@section('content')
<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Chính sách chỉnh sửa</h1>
                <p class="policy-intro">
                    Chúng tôi cam kết duy trì tính chính xác của nội dung. Nếu bạn phát hiện lỗi hoặc 
                    thông tin không chính xác, chúng tôi sẽ xem xét và sửa đổi kịp thời.
                </p>
            </header>

            <section class="policy-section">
                <h2>📝 Các loại chỉnh sửa</h2>
                
                <div class="correction-type">
                    <h3>Chỉnh sửa nhỏ</h3>
                    <p>Lỗi chính tả, ngữ pháp, formatting — được sửa ngay khi phát hiện, không cần ghi chú.</p>
                </div>
                
                <div class="correction-type">
                    <h3>Chỉnh sửa nội dung</h3>
                    <p>
                        Lỗi kỹ thuật, thông tin sai, code không hoạt động — được sửa và ghi chú 
                        "Cập nhật [ngày]: [mô tả thay đổi]" ở cuối bài.
                    </p>
                </div>
                
                <div class="correction-type">
                    <h3>Chỉnh sửa quan trọng</h3>
                    <p>
                        Thay đổi đáng kể về quan điểm, kết luận hoặc khuyến nghị — được ghi chú rõ ràng 
                        với lý do thay đổi và highlight ở đầu bài viết.
                    </p>
                </div>
            </section>

            <section class="policy-section">
                <h2>🔧 Quy trình xử lý</h2>
                <ol>
                    <li>
                        <strong>Tiếp nhận yêu cầu</strong>
                        <p>Bạn gửi yêu cầu qua form liên hệ hoặc comment bài viết.</p>
                    </li>
                    <li>
                        <strong>Xác minh</strong>
                        <p>Đội ngũ biên tập xác minh thông tin trong vòng 2-3 ngày làm việc.</p>
                    </li>
                    <li>
                        <strong>Chỉnh sửa</strong>
                        <p>Nếu xác nhận có lỗi, nội dung được sửa ngay. Nếu không, chúng tôi sẽ giải thích lý do.</p>
                    </li>
                    <li>
                        <strong>Phản hồi</strong>
                        <p>Bạn sẽ nhận được email xác nhận khi chỉnh sửa hoàn tất.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📋 Cách gửi yêu cầu chỉnh sửa</h2>
                <p>Để yêu cầu được xử lý nhanh nhất, vui lòng cung cấp:</p>
                <ul>
                    <li>Link đến bài viết cần chỉnh sửa</li>
                    <li>Vị trí cụ thể của nội dung sai (đoạn văn, code block, v.v.)</li>
                    <li>Mô tả lỗi và đề xuất sửa đổi</li>
                    <li>Nguồn tham khảo (nếu có)</li>
                </ul>
                
                <div class="cta-box">
                    <p><strong>Gửi yêu cầu chỉnh sửa:</strong></p>
                    <a href="{{ route('lamgame.lien-he') }}?subject=yeu-cau-chinh-sua" class="btn btn-primary">
                        Liên hệ đội ngũ biên tập
                    </a>
                </div>
            </section>

            <section class="policy-section">
                <h2>⏱️ Thời gian xử lý</h2>
                <table class="timeline-table">
                    <thead>
                        <tr>
                            <th>Loại yêu cầu</th>
                            <th>Thời gian xử lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lỗi chính tả, formatting</td>
                            <td>1-2 ngày làm việc</td>
                        </tr>
                        <tr>
                            <td>Lỗi code, thông tin kỹ thuật</td>
                            <td>2-5 ngày làm việc</td>
                        </tr>
                        <tr>
                            <td>Yêu cầu cập nhật nội dung lớn</td>
                            <td>5-10 ngày làm việc</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="policy-section">
                <h2>🚫 Những gì chúng tôi KHÔNG chỉnh sửa</h2>
                <ul>
                    <li>Quan điểm cá nhân hợp lệ của tác giả</li>
                    <li>Thông tin đã chính xác tại thời điểm xuất bản</li>
                    <li>Nội dung không vi phạm quy định pháp luật</li>
                    <li>Yêu cầu không có cơ sở hoặc nguồn tham khảo</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📜 Lịch sử chỉnh sửa</h2>
                <p>
                    Các chỉnh sửa quan trọng được ghi lại ở cuối mỗi bài viết với format:
                </p>
                <div class="example-box">
                    <p><em>— Cập nhật 15/08/2026: Sửa lỗi code mẫu Unity 6 không hoạt động với Input System mới</em></p>
                    <p><em>— Cập nhật 10/08/2026: Bổ sung hướng dẫn cho macOS</em></p>
                </div>
            </section>

            <footer class="policy-footer">
                <p><strong>Cập nhật lần cuối:</strong> {{ now()->format('d/m/Y') }}</p>
                <p>
                    Xem thêm: <a href="{{ route('lamgame.chinh-sach-bien-tap') }}">Chính sách biên tập</a>
                </p>
            </footer>
        </article>
    </div>
</div>

<style>
.policy-page {
    padding: 2rem 0;
}

.policy-content {
    max-width: 800px;
    margin: 0 auto;
}

.policy-header {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.policy-header h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.policy-intro {
    font-size: 1.125rem;
    color: #666;
    line-height: 1.7;
}

.policy-section {
    margin-bottom: 2rem;
}

.policy-section h2 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #333;
}

.policy-section h3 {
    font-size: 1rem;
    margin-bottom: 0.5rem;
    color: #444;
}

.policy-section p {
    line-height: 1.7;
    margin-bottom: 1rem;
}

.policy-section ul,
.policy-section ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}

.policy-section li {
    margin-bottom: 0.75rem;
    line-height: 1.6;
}

.policy-section li p {
    margin: 0.25rem 0 0;
    color: #666;
    font-size: 0.95rem;
}

.policy-section a {
    color: #667eea;
}

.correction-type {
    background: #f9fafb;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.correction-type h3 {
    margin-top: 0;
}

.correction-type p {
    margin-bottom: 0;
}

.cta-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    text-align: center;
    margin: 1.5rem 0;
}

.cta-box p {
    margin-bottom: 1rem;
}

.cta-box .btn {
    background: white;
    color: #667eea;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
}

.cta-box .btn:hover {
    background: #f3f4f6;
}

.timeline-table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.timeline-table th,
.timeline-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.timeline-table th {
    background: #f9fafb;
    font-weight: 600;
}

.example-box {
    background: #f9fafb;
    padding: 1rem;
    border-left: 3px solid #667eea;
    margin: 1rem 0;
}

.example-box p {
    margin: 0.5rem 0;
    font-size: 0.9rem;
    color: #666;
}

.policy-footer {
    margin-top: 3rem;
    padding-top: 1.5rem;
    border-top: 1px solid #eee;
    color: #888;
    font-size: 0.9rem;
}

.policy-footer a {
    color: #667eea;
}
</style>
@endsection
