<x-mail::message>
# 🎮 Chào mừng {{ $customer->first_name }} {{ $customer->last_name }}!

Chúc mừng bạn đã tham gia thành công cộng đồng **LAMGAME** - nơi khơi nguồn đam mê lập trình game!

## 🚀 Những gì bạn có thể làm ngay bây giờ:

✨ **Khám phá khóa học** - Truy cập hàng trăm video bài giảng từ cơ bản đến nâng cao  
🎯 **Tải source code game** - Download miễn phí các project game hoàn chỉnh  
💬 **Tham gia forum** - Kết nối và thảo luận với cộng đồng developer  

<x-mail::button :url="$loginUrl" color="success">
🔑 Đăng nhập ngay
</x-mail::button>

## 🎮 Về LAMGAME

LAMGAME là cộng đồng lập trình game lớn nhất Việt Nam với:
- 🏆 Hơn 10,000+ thành viên tích cực
- 📖 200+ khóa học chất lượng cao  
- 🎯 1000+ source code game miễn phí

Trân trọng,<br>
**{{ config('app.name') }} Team** 🎮
</x-mail::message>