<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Webkul\CMS\Models\Page;

class CreateContactPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emsaigon:create-contact-page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create EMSAIGON contact page with content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contactContent = '
<div class="info-grid">
    <div class="info-card">
        <h4>📍 Địa chỉ trung tâm</h4>
        <p>123 Nguyễn Văn Cừ, Phường Tân Phú<br>Quận 7, TP. Hồ Chí Minh<br><strong>Landmark:</strong> Gần Big C Nguyễn Văn Cừ</p>
    </div>
    <div class="info-card">
        <h4>📞 Hotline tư vấn</h4>
        <p><strong>0908 123 456</strong><br>Hỗ trợ tư vấn 24/7<br>Miễn phí cuộc gọi</p>
    </div>
    <div class="info-card">
        <h4>✉️ Email hỗ trợ</h4>
        <p><strong>hello@emsaigon.com</strong><br>info@emsaigon.com<br>Phản hồi trong 24h</p>
    </div>
    <div class="info-card">
        <h4>⏰ Giờ làm việc</h4>
        <p><strong>Thứ 2 - Chủ nhật</strong><br>8:00 - 12:00 & 13:30 - 20:00<br>Không nghỉ lễ Tết</p>
    </div>
</div>

<h2>💬 Gửi tin nhắn cho chúng tôi</h2>
<p>
    Bạn có thắc mắc về khóa học, dịch vụ hoặc muốn được tư vấn cá nhân? 
    Hãy để lại thông tin bên dưới, đội ngũ chuyên gia của EMSAIGON sẽ liên hệ và hỗ trợ bạn trong vòng 24 giờ.
</p>

<div class="contact-form-section">
    <form class="contact-form" action="#" method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="name">Họ và tên *</label>
                <input type="text" id="name" name="name" required placeholder="Nguyễn Văn A">
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại *</label>
                <input type="tel" id="phone" name="phone" required placeholder="0908 123 456">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="email@example.com">
            </div>
            <div class="form-group">
                <label for="subject">Chủ đề quan tâm</label>
                <select id="subject" name="subject">
                    <option value="">Chọn chủ đề</option>
                    <option value="course">Khóa học đào tạo</option>
                    <option value="service">Dịch vụ chăm sóc</option>
                    <option value="career">Cơ hội nghề nghiệp</option>
                    <option value="partnership">Hợp tác kinh doanh</option>
                    <option value="other">Khác</option>
                </select>
            </div>
        </div>
        <div class="form-group full-width">
            <label for="message">Nội dung tin nhắn *</label>
            <textarea id="message" name="message" rows="5" required placeholder="Xin chào NONGSANNGON, tôi muốn tìm hiểu về..."></textarea>
        </div>
        <div class="form-group full-width">
            <button type="submit" class="btn btn-primary">📤 Gửi tin nhắn</button>
        </div>
    </form>
</div>

<div class="highlight-box">
    <h3>🚀 Tư vấn miễn phí - Cam kết hỗ trợ</h3>
    <p>
        <strong>Đội ngũ chuyên gia của NONGSANNGON sẵn sàng tư vấn miễn phí về:</strong><br>
        • <strong>Lộ trình học</strong> phù hợp với mục tiêu và thời gian của bạn<br>
        • <strong>Cơ hội việc làm</strong> sau khi tốt nghiệp khóa học<br>
        • <strong>Hỗ trợ khởi nghiệp</strong> spa mini tại nhà<br>
        • <strong>Chương trình ưu đãi</strong> và hỗ trợ tài chính<br>
        • <strong>Kết nối đối tác</strong> spa và resort uy tín
    </p>
</div>

<h2>🗺️ Hướng dẫn đường đi</h2>
<p>Trung tâm NONGSANNGON nằm tại vị trí thuận lợi, dễ dàng di chuyển:</p>

<h3>🚌 Phương tiện công cộng</h3>
<ul>
    <li><strong>Xe bus:</strong> Tuyến 01, 02, 18, 88 - Dừng tại bến Nguyễn Văn Cừ</li>
    <li><strong>Metro:</strong> Tuyến 1 (đang xây dựng) - Ga Tân Cảng</li>
    <li><strong>Taxi/Grab:</strong> Dễ dàng tìm thấy địa chỉ "123 Nguyễn Văn Cừ, Q7"</li>
</ul>

<h3>🏍️ Phương tiện cá nhân</h3>
<ul>
    <li><strong>Xe máy:</strong> Chỗ gửi xe miễn phí và an toàn cho học viên</li>
    <li><strong>Ô tô:</strong> Bãi đậu xe rộng rãi, có camera giám sát 24/7</li>
    <li><strong>Xe đạp:</strong> Khu vực để xe đạp riêng biệt</li>
</ul>

<div class="highlight-box">
    <h3>🏢 Cơ sở vật chất hiện đại</h3>
    <p>
        • <strong>Phòng học:</strong> 5 phòng học rộng rãi, máy lạnh, ánh sáng tự nhiên<br>
        • <strong>Phòng thực hành:</strong> 10 giường massage cao cấp, thiết bị hiện đại<br>
        • <strong>Khu vực thư giãn:</strong> Không gian nghỉ ngơi cho học viên<br>
        • <strong>Kho thiết bị:</strong> Đầy đủ dụng cụ và sản phẩm thực hành<br>
        • <strong>Wifi miễn phí:</strong> Tốc độ cao, hỗ trợ học tập online
    </p>
</div>

<h2>📅 Lịch hoạt động hàng tuần</h2>
<div class="schedule-grid">
    <div class="schedule-item">
        <h4>Thứ 2 - 6</h4>
        <p><strong>8:00 - 12:00:</strong> Khóa học sáng<br>
        <strong>13:30 - 17:30:</strong> Khóa học chiều<br>
        <strong>18:00 - 20:00:</strong> Dịch vụ chăm sóc</p>
    </div>
    <div class="schedule-item">
        <h4>Thứ 7 - Chủ nhật</h4>
        <p><strong>8:00 - 12:00:</strong> Khóa học cuối tuần<br>
        <strong>13:30 - 18:00:</strong> Dịch vụ chăm sóc<br>
        <strong>18:00 - 20:00:</strong> Tư vấn & thăm quan</p>
    </div>
</div>

<h2>🎁 Ưu đãi đặc biệt khi liên hệ</h2>
<ul>
    <li>🆓 <strong>Tư vấn miễn phí</strong> và thăm quan cơ sở đào tạo</li>
    <li>💸 <strong>Giảm 10%</strong> học phí khi đăng ký trong tuần</li>
    <li>🎁 <strong>Tặng voucher</strong> trị giá 500.000đ cho dịch vụ chăm sóc</li>
    <li>📚 <strong>Tài liệu học tập</strong> miễn phí khi đến tham quan</li>
    <li>☕ <strong>Trà và bánh kẹo</strong> trong buổi tư vấn</li>
</ul>

<p style="text-align: center; margin-top: 3rem;">
    <a href="tel:0908123456" class="btn btn-primary">📞 Gọi ngay để nhận ưu đãi</a>
    <a href="/" class="btn btn-outline">🏠 Quay về trang chủ</a>
</p>
';

        // Tìm page customer-service để chuyển thành contact page
        $contactPage = Page::find(6);
        if ($contactPage) {
            $contactPage->url_key = 'lien-he';
            $contactPage->html_content = $contactContent;
            $contactPage->meta_title = 'Liên hệ NONGSANNGON - Hotline tư vấn nông sản sạch';
            $contactPage->meta_description = 'Liên hệ NONGSANNGON để được tư vấn miễn phí về nông sản sạch, rau củ quả tươi. Hotline: 0908 123 456. Địa chỉ: Q7, TP.HCM. Ưu đãi đặc biệt khi liên hệ.';
            $contactPage->meta_keywords = 'liên hệ nongsanngon, tư vấn nông sản, hotline nông sản sạch, địa chỉ nongsanngon, đặt hàng';
            $contactPage->save();
            
            $this->info('Contact page updated successfully!');
            $this->info('Access URL: http://nongsanngon.local:8080/page/' . $contactPage->url_key);
        } else {
            $this->error('Page not found');
        }
    }
}
