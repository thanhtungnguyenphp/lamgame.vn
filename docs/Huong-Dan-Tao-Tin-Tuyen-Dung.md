# 📋 Hướng Dẫn Tạo Sản Phẩm "Tin Tuyển Dụng" - LamGame

## 🎯 Tổng Quan

Tài liệu này hướng dẫn chi tiết cách tạo và quản lý sản phẩm "Tin Tuyển Dụng" trên nền tảng LamGame sử dụng Bagisto E-commerce. Mỗi tin tuyển dụng được xử lý như một sản phẩm với các thuộc tính đặc biệt phù hợp cho việc tuyển dụng trong ngành game development.

---

## 🗂️ Danh Mục Việc Làm

### **Cấu trúc danh mục chính:**

```
🎮 Việc Làm (Jobs)
├── 💻 Lập Trình Game (Game Programming)
├── 🎨 Thiết Kế Game (Game Design)  
├── 🖼️ Game Art & Graphics
├── 🧪 QA & Testing
├── 📊 Quản Lý Dự Án (Project Management)
├── 📈 Marketing & Publishing
├── 📱 Mobile Game
├── 🌐 Web Game
├── 💼 Freelance
└── 🎓 Thực Tập (Internship)
```

---

## 📝 Các Bước Tạo Tin Tuyển Dụng

### **Bước 1: Truy cập Admin Panel**

1. Đăng nhập vào Admin Panel: `http://lamgame.localhost/admin`
2. Chọn **Catalog** → **Products** → **Add Product**
3. Chọn **Product Type**: Simple Product

### **Bước 2: Thông Tin Cơ Bản**

#### **General Tab:**
- **SKU**: `JOB_[COMPANY]_[POSITION]_[YEAR]`
  - Ví dụ: `JOB_VNG_UNITY_DEV_2025`
- **Product Name**: `[Vị trí] - [Tên công ty]`
  - Ví dụ: `Senior Unity Developer - VNG Corporation`
- **URL Key**: Tự động generate từ product name
- **Status**: Enable (để hiển thị công khai)

#### **Price:**
- **Price**: Nhập mức lương trung bình (VND)
  - Ví dụ: `65000000` (65 triệu VND)
- **Special Price**: Để trống (không áp dụng cho tin tuyển dụng)

### **Bước 3: Thông Tin Mô Tả**

#### **Description Tab:**

**Short Description** (Mô tả ngắn - 160 ký tự):
```
VNG tuyển Senior Unity Developer 5+ năm kinh nghiệm. Lương 50-80 triệu + equity, làm việc với team quốc tế.
```

**Description** (Mô tả chi tiết - HTML):
```html
<h2>🎮 VNG Corporation - Senior Unity Developer</h2>
<p><strong>VNG Corporation</strong> là một trong những công ty game hàng đầu Việt Nam đang tìm kiếm Senior Unity Developer tài năng để tham gia phát triển các dự án game mobile AAA.</p>

<h3>📋 Mô tả công việc:</h3>
<ul>
<li>🎯 Phát triển và maintain mobile games sử dụng Unity Engine</li>
<li>💻 Architect và implement game systems phức tạp</li>
<li>🚀 Optimize game performance cho các thiết bị mobile</li>
<li>👥 Mentor junior developers và review code</li>
<li>🔧 Research và implement new technologies</li>
</ul>

<h3>✅ Yêu cầu:</h3>
<ul>
<li>🎓 Bằng Đại học Khoa học máy tính hoặc tương đương</li>
<li>⭐ 5+ năm kinh nghiệm Unity development</li>
<li>💡 Expert level C# và design patterns</li>
<li>📱 Shipped ít nhất 2 mobile games</li>
</ul>

<h3>🎁 Benefits Package:</h3>
<ul>
<li>💰 Lương 50-80 triệu + equity + bonuses</li>
<li>🏥 Premium healthcare cho cả gia đình</li>
<li>🏖️ 20 ngày nghỉ phép + 5 ngày sick leave</li>
<li>🎮 Latest MacBook Pro + gaming setup</li>
</ul>
```

### **Bước 4: SEO & Meta Tags**

#### **Meta Data Tab:**
- **Meta Title**: `[Job Title] - [Company] | LamGame Jobs`
- **Meta Description**: Sao chép Short Description
- **Meta Keywords**: `game jobs, unity developer, mobile game, tuyển dụng game`

### **Bước 5: Danh Mục Sản Phẩm**

#### **Categories Tab:**
- Chọn danh mục phù hợp:
  - **Lập Trình Game**: Cho Unity Developer, Mobile Developer
  - **Thiết Kế Game**: Cho Game Designer, Level Designer
  - **Game Art**: Cho 2D/3D Artist, UI Designer
  - **QA Testing**: Cho Game Tester, QA Engineer
  - **Quản Lý Dự Án**: Cho Project Manager, Producer
  - **Freelance**: Cho công việc remote/freelance

### **Bước 6: Thuộc Tính Công Việc**

#### **Job Information Group:**

**🔹 Loại Hình Công Việc** (`job_type`):
- Full-time, Part-time, Contract, Freelance, Internship, Remote, Hybrid

**🔹 Cấp Độ Kinh Nghiệm** (`experience_level`):
- Fresher (0-1 năm)
- Junior (1-3 năm) 
- Middle (3-5 năm)
- Senior (5+ năm)
- Lead/Manager (7+ năm)
- Director (10+ năm)

**🔹 Mức Lương** (`salary_range`):
- Dưới 10 triệu
- 10-20 triệu
- 20-30 triệu
- 30-50 triệu
- 50-80 triệu
- Trên 80 triệu
- Thỏa thuận

**🔹 Địa Điểm Làm Việc** (`job_location`):
- Hồ Chí Minh
- Hà Nội
- Đà Nẵng
- Remote
- Toàn Quốc

**🔹 Quy Mô Công Ty** (`company_size`):
- Startup (1-10 người)
- Nhỏ (10-50 người)
- Trung bình (50-200 người)
- Lớn (200-1000 người)
- Tập đoàn (1000+ người)

**🔹 Tuyển Gấp** (`is_urgent`): Có/Không

**🔹 Tin Nổi Bật** (`is_featured`): Có/Không

#### **Job Requirements Group:**

**🔹 Kỹ Năng Yêu Cầu** (`required_skills`) - Multiselect:
- Unity, Unreal Engine, C#, C++, JavaScript
- Python, Java, Swift, Kotlin, HTML5/CSS3
- React Native, Flutter, Photoshop, 3ds Max
- Maya, Blender, Git, Agile/Scrum
- Game Design, Level Design

**🔹 Trình Độ Học Vấn** (`education_level`):
- Không yêu cầu
- Trung cấp/Cao đẳng
- Đại học
- Thạc sĩ
- Tiến sĩ

**🔹 Trình Độ Tiếng Anh** (`english_level`):
- Không yêu cầu
- Cơ bản
- Giao tiếp tốt
- Thành thạo
- Bản ngữ

#### **Benefits & Application Group:**

**🔹 Phúc Lợi** (`job_benefits`) - Multiselect:
- Bảo hiểm sức khỏe
- Bảo hiểm xã hội
- Thưởng hiệu suất
- Du lịch hàng năm
- Nghỉ phép có lương
- Đào tạo & phát triển
- Làm việc từ xa
- Giờ làm việc linh hoạt
- Máy tính/laptop công ty
- Phụ cấp ăn trua
- Team building
- Game room

**🔹 Hạn Nộp Hồ Sơ** (`application_deadline`):
- Chọn ngày deadline (format: YYYY-MM-DD)

**🔹 Email Liên Hệ** (`contact_email`):
- Ví dụ: `careers@vng.com.vn`

**🔹 Số Điện Thoại** (`contact_phone`):
- Ví dụ: `028-3835-1234`

**🔹 Website Công Ty** (`company_website`):
- Ví dụ: `https://www.vng.com.vn`

**🔹 Cách Thức Ứng Tuyển** (`application_method`):
- Gửi email
- Ứng tuyển online
- Liên hệ trực tiếp
- Qua website công ty

### **Bước 7: Hình Ảnh & Media**

#### **Images Tab:**
- **Base Image**: Logo công ty hoặc hình ảnh đại diện
- **Small Image**: Thumbnail cho danh sách
- **Thumbnail Image**: Hình nhỏ cho grid view
- **Additional Images**: Hình ảnh văn phòng, team, benefit

### **Bước 8: Inventory & Shipping**

#### **Inventories Tab:**
- **Quantity**: `999999` (không giới hạn vì là tin tuyển dụng)
- **Track Quantity**: Không tick
- **Inventory Source**: Default

#### **Shipping:**
- **Weight**: `0` (tin tuyển dụng không có trọng lượng vật lý)

### **Bước 9: Lưu & Xuất Bản**

1. **Save as Draft**: Để lưu nháp, review sau
2. **Save**: Để xuất bản tin tuyển dụng công khai

---

## 🎯 Tips & Best Practices

### **🔥 Tối Ưu Hóa Tin Tuyển Dụng:**

1. **Tiêu đề hấp dẫn**: Sử dụng format `[Level] [Position] - [Company Name]`

2. **Mô tả chi tiết**: 
   - Sử dụng emoji để làm nổi bật
   - Chia thành các sections rõ ràng
   - Liệt kê benefits cụ thể

3. **Keywords SEO**:
   - Tên vị trí công việc
   - Tên công ty
   - Công nghệ/kỹ năng chính
   - Địa điểm làm việc

4. **Thông tin liên hệ**:
   - Luôn cung cấp email và phone
   - Link đến website/careers page
   - Chỉ rõ cách thức apply

### **🚀 Tăng Hiệu Quả:**

1. **Featured Jobs**: Đánh dấu những tin quan trọng
2. **Urgent Hiring**: Sử dụng cho những vị trí cần tuyển gấp
3. **Regular Updates**: Cập nhật deadline, requirements
4. **Response Management**: Thiết lập quy trình xử lý CV

---

## 📊 Quản Lý Tin Tuyển Dụng

### **Dashboard Analytics:**
- Views count (lượt xem tin)
- Application rate (tỷ lệ apply)
- Popular skills (kỹ năng được tìm nhiều)
- Salary trends (xu hướng lương)

### **Bulk Operations:**
- Update multiple jobs
- Extend deadlines
- Change status (Active/Expired)
- Export job data

### **Automated Features:**
- Auto-expire after deadline
- Email notifications for new applications
- Weekly performance reports
- SEO suggestions

---

## 🔧 Troubleshooting

### **Common Issues:**

**Q: Tin tuyển dụng không hiển thị trên frontend?**
A: Kiểm tra:
- Status = Enabled
- Visibility = Individually Visible  
- Category assignment
- Inventory > 0

**Q: Attributes không hiển thị đầy đủ?**
A: Đảm bảo:
- Attribute được assign vào đúng attribute group
- is_visible_on_front = true
- Attribute có translation tiếng Việt

**Q: Email notification không hoạt động?**
A: Cấu hình:
- SMTP settings trong .env
- Queue processing
- Email templates

---

## 📈 Báo Cáo & Thống Kê

### **Key Metrics:**
- 📊 Tổng số tin tuyển dụng
- 👁️ Lượt xem trung bình
- 📧 Tỷ lệ ứng tuyển
- ⏰ Thời gian tuyển dụng trung bình
- 💰 Mức lương phổ biến theo vị trí
- 📍 Phân bố địa lý của các tin

### **Export Reports:**
- Weekly job posting report
- Monthly hiring statistics  
- Salary benchmark analysis
- Skills demand analysis

---

## 🎯 Kết Luận

Hệ thống "Tin Tuyển Dụng" trên LamGame cung cấp một giải pháp toàn diện cho việc quản lý tuyển dụng trong ngành game development. Với cấu trúc thuộc tính chi tiết và giao diện thân thiện, nhà tuyển dụng có thể dễ dàng đăng tin và quản lý quá trình tuyển dụng hiệu quả.

### **Next Steps:**
- Tích hợp với job application system
- Thêm tính năng chat trực tiếp
- AI matching giữa job và candidate
- Mobile app cho job seekers
- Integration với LinkedIn, Indeed

---

**📞 Support**: Nếu cần hỗ trợ, liên hệ admin@lamgame.vn
