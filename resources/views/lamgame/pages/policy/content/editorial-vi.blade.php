<div class="policy-page">
    <div class="container">
        <article class="policy-content">
            <header class="policy-header">
                <h1>Chính sách biên tập</h1>
                <p class="policy-intro">
                    Tại LamGame.vn, chúng tôi cam kết cung cấp nội dung chính xác, hữu ích và đáng tin cậy 
                    cho cộng đồng game developer Việt Nam.
                </p>
            </header>

            <section class="policy-section">
                <h2>🎯 Sứ mệnh nội dung</h2>
                <p>
                    Mục tiêu của chúng tôi là tạo ra nguồn tài liệu game development chất lượng cao bằng tiếng Việt, 
                    giúp developer Việt Nam tiếp cận kiến thức mới nhất trong ngành công nghiệp game toàn cầu.
                </p>
            </section>

            <section class="policy-section">
                <h2>✍️ Đội ngũ tác giả</h2>
                <p>Nội dung trên LamGame.vn được viết bởi:</p>
                <ul>
                    <li><strong>Đội ngũ biên tập LamGame</strong> — Các chuyên gia game development với nhiều năm kinh nghiệm</li>
                    <li><strong>Tác giả khách mời</strong> — Developer và chuyên gia từ các studio game trong và ngoài nước</li>
                    <li><strong>Cộng tác viên</strong> — Thành viên cộng đồng có chuyên môn được xác minh</li>
                </ul>
                <p>
                    Mỗi tác giả đều có trang profile riêng với thông tin về kinh nghiệm và chuyên môn. 
                    <a href="{{ route('authors.index') }}">Xem danh sách tác giả →</a>
                </p>
            </section>

            <section class="policy-section">
                <h2>📋 Quy trình biên tập</h2>
                <p>Mỗi bài viết trên LamGame.vn đều trải qua quy trình nghiêm ngặt:</p>
                <ol>
                    <li>
                        <strong>Nghiên cứu & Viết bài</strong>
                        <p>Tác giả nghiên cứu kỹ lưỡng và viết nội dung dựa trên kinh nghiệm thực tế và tài liệu chính thống.</p>
                    </li>
                    <li>
                        <strong>Kiểm tra kỹ thuật</strong>
                        <p>Code samples được test trên các phiên bản engine/framework được đề cập. Hướng dẫn được verify hoạt động đúng.</p>
                    </li>
                    <li>
                        <strong>Review biên tập</strong>
                        <p>Biên tập viên kiểm tra tính chính xác, rõ ràng và consistency của nội dung.</p>
                    </li>
                    <li>
                        <strong>Xuất bản & Cập nhật</strong>
                        <p>Bài viết được xuất bản với ngày tháng rõ ràng. Nội dung được cập nhật khi có thay đổi từ engine/framework.</p>
                    </li>
                </ol>
            </section>

            <section class="policy-section">
                <h2>📚 Nguồn tham khảo</h2>
                <p>Chúng tôi ưu tiên sử dụng các nguồn đáng tin cậy:</p>
                <ul>
                    <li>Documentation chính thức từ Unity, Unreal Engine, Godot, v.v.</li>
                    <li>Nghiên cứu và bài viết từ GDC, Gamasutra, Game Developer Magazine</li>
                    <li>Kinh nghiệm thực tế từ các dự án game đã ship</li>
                    <li>Phỏng vấn và chia sẻ từ các developer có uy tín</li>
                </ul>
                <p>Các nguồn tham khảo được ghi rõ trong mỗi bài viết khi cần thiết.</p>
            </section>

            <section class="policy-section">
                <h2>🔄 Cập nhật nội dung</h2>
                <p>
                    Game development là lĩnh vực phát triển nhanh. Chúng tôi thường xuyên review và cập nhật 
                    các bài viết để đảm bảo tính chính xác:
                </p>
                <ul>
                    <li>Bài viết tutorial được review khi engine/framework ra phiên bản mới</li>
                    <li>Thông tin API được cập nhật theo documentation chính thức</li>
                    <li>Ngày "Cập nhật lần cuối" được hiển thị trên mỗi bài viết</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>⚠️ Tuyên bố miễn trừ</h2>
                <ul>
                    <li>Code samples chỉ mang tính minh họa, cần điều chỉnh cho production</li>
                    <li>Performance và kết quả có thể khác nhau tùy môi trường</li>
                    <li>Chúng tôi không chịu trách nhiệm về thiệt hại từ việc áp dụng nội dung</li>
                    <li>Thông tin giá cả và licensing có thể thay đổi theo nhà cung cấp</li>
                </ul>
            </section>

            <section class="policy-section">
                <h2>📧 Phản hồi & Đóng góp</h2>
                <p>
                    Chúng tôi hoan nghênh mọi phản hồi để cải thiện chất lượng nội dung:
                </p>
                <ul>
                    <li>Phát hiện lỗi kỹ thuật? <a href="{{ route('lamgame.lien-he') }}">Báo cáo cho chúng tôi</a></li>
                    <li>Muốn đóng góp bài viết? <a href="{{ route('lamgame.lien-he') }}">Liên hệ đội ngũ biên tập</a></li>
                    <li>Yêu cầu chỉnh sửa nội dung? Xem <a href="{{ route('lamgame.chinh-sach-chinh-sua') }}">Chính sách chỉnh sửa</a></li>
                </ul>
            </section>

            <footer class="policy-footer">
                <p><strong>Cập nhật lần cuối:</strong> {{ now()->format('d/m/Y') }}</p>
            </footer>
        </article>
    </div>
</div>

