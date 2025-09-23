<x-mail::message>
<div style="text-align: center; margin-bottom: 30px;">
    <h1 style="color: #6a4c93; font-size: 2.5rem; margin-bottom: 10px;">🎮 Chào mừng đến LAMGAME!</h1>
    <p style="color: #666; font-size: 1.1rem;">Cộng đồng lập trình game hàng đầu Việt Nam</p>
</div>

# Xin chào {{ $customer->first_name }} {{ $customer->last_name }}! 👋

Chúc mừng bạn đã tham gia thành công cộng đồng **LAMGAME** - nơi khơi nguồn đam mê lập trình game!

## 🚀 Những gì bạn có thể làm ngay bây giờ:

✨ **Khám phá khóa học** - Truy cập hàng trăm video bài giảng từ cơ bản đến nâng cao  
🎯 **Tải source code game** - Download miễn phí các project game hoàn chỉnh  
💬 **Tham gia forum** - Kết nối và thảo luận với cộng đồng developer  
📚 **Đọc blog kỹ thuật** - Cập nhật xu hướng và kỹ thuật mới nhất  
💼 **Tìm việc làm** - Khám phá cơ hội nghề nghiệp trong ngành game  

## 🎯 Bắt đầu hành trình của bạn:

<x-mail::button :url="$loginUrl" color="success">
🔑 Đăng nhập ngay
</x-mail::button>

<x-mail::panel>
**💡 Mẹo nhỏ:** Đừng quên hoàn thiện thông tin profile để nhận được những khuyến mãi và nội dung phù hợp nhất!
</x-mail::panel>

## 🎮 Về LAMGAME

LAMGAME là cộng đồng lập trình game lớn nhất Việt Nam với:
- 🏆 Hơn 10,000+ thành viên tích cực
- 📖 200+ khóa học chất lượng cao  
- 🎯 1000+ source code game miễn phí
- 👥 Cộng đồng developer nhiệt tình hỗ trợ

---

### 📞 Liên hệ hỗ trợ:
- **Email:** support@lamgame.localhost  
- **Website:** [lamgame.localhost]({{ $homeUrl }})  
- **Hotline:** 0908 123 456

<div style="text-align: center; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <p style="margin: 0; color: #6a4c93; font-weight: 600;">🌟 Chào mừng bạn đến với gia đình LAMGAME! 🌟</p>
    <p style="margin: 5px 0 0 0; color: #666; font-size: 0.9rem;">Hãy bắt đầu hành trình chinh phục thế giới lập trình game cùng chúng tôi!</p>
</div>

Trân trọng,<br>
**{{ config('app.name') }} Team** 🎮
</x-mail::message>
