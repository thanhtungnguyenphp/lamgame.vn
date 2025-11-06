# Job Management API - CURL Templates

## 1. Tạo Job Mới (Create Job)

### Basic Job Creation
```bash
curl -X POST https://lamgame.localhost/api/jobs \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "title": "Senior Unity Developer",
    "company_name": "GameStudio VN",
    "description": "<p>Chúng tôi đang tìm kiếm Unity Developer có kinh nghiệm để tham gia đội ngũ phát triển game mobile.</p><ul><li>Phát triển game Unity 3D/2D</li><li>Tối ưu performance</li><li>Code review và mentoring</li></ul>",
    "short_description": "Tuyển Unity Developer kinh nghiệm phát triển game mobile, mức lương hấp dẫn từ 30-50 triệu.",
    "job_type": "full-time",
    "experience_level": "senior",
    "salary_range": "30m-50m",
    "job_location": "Ho Chi Minh",
    "company_size": "50-100",
    "required_skills": ["Unity", "C#", "Git", "Agile"],
    "job_benefits": ["Bảo hiểm sức khỏe", "Thưởng hiệu suất", "Làm việc từ xa"],
    "contact_email": "hr@gamestudio.vn",
    "contact_phone": "0901234567",
    "application_deadline": "2025-12-31",
    "application_method": "email",
    "is_urgent": true,
    "is_featured": false,
    "categories": [102]
  }'
```

### Complete Job with All Fields
```bash
curl -X POST https://lamgame.localhost/api/jobs \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "title": "Lead Game Developer",
    "company_name": "Epic Games Vietnam",
    "description": "<h3>Mô tả công việc</h3><p>Chúng tôi đang tìm kiếm Lead Game Developer để dẫn dắt đội ngũ phát triển game AAA.</p><h4>Trách nhiệm chính:</h4><ul><li>Lãnh đạo team 5-8 developers</li><li>Thiết kế kiến trúc game</li><li>Code review và mentoring</li><li>Phối hợp với các team khác</li></ul><h4>Yêu cầu:</h4><ul><li>5+ năm kinh nghiệm Unity/Unreal</li><li>Kinh nghiệm lead team</li><li>Thành thạo C#/C++</li></ul>",
    "short_description": "Tuyển Lead Game Developer dẫn dắt team phát triển game AAA, mức lương 50-80 triệu + bonus.",
    "job_type": "full-time",
    "experience_level": "lead",
    "salary_range": "50m-80m",
    "job_location": "Ho Chi Minh",
    "company_size": "100-500",
    "required_skills": ["Unity", "Unreal Engine", "C#", "C++", "Leadership", "Agile", "Git"],
    "education_level": "Đại học",
    "english_level": "Tốt",
    "job_benefits": [
      "Bảo hiểm sức khỏe",
      "Bảo hiểm xã hội", 
      "Thưởng hiệu suất",
      "Du lịch hàng năm",
      "Đào tạo & phát triển",
      "Máy tính/laptop công ty",
      "Team building"
    ],
    "contact_email": "careers@epicgames.vn",
    "contact_phone": "028-1234-5678",
    "company_website": "https://epicgames.vn",
    "application_deadline": "2025-12-15",
    "application_method": "online",
    "is_urgent": false,
    "is_featured": true,
    "categories": [102],
    "meta_title": "Lead Game Developer - Epic Games Vietnam",
    "meta_description": "Cơ hội nghề nghiệp tuyệt vời tại Epic Games Vietnam. Tuyển Lead Game Developer với mức lương hấp dẫn.",
    "meta_keywords": "lead developer, game developer, unity, unreal engine, epic games"
  }'
```

### Mobile Game Developer Job
```bash
curl -X POST https://lamgame.localhost/api/jobs \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "title": "Mobile Game Developer",
    "company_name": "VNG Corporation",
    "description": "<p>Tham gia phát triển game mobile hàng đầu Việt Nam tại VNG Corporation.</p><ul><li>Phát triển game mobile Unity</li><li>Tối ưu performance cho mobile</li><li>Implement game mechanics</li><li>Debug và fix bugs</li></ul>",
    "short_description": "Tuyển Mobile Game Developer tại VNG, làm việc với các dự án game mobile triệu download.",
    "job_type": "full-time",
    "experience_level": "middle",
    "salary_range": "20m-30m",
    "job_location": "Ho Chi Minh",
    "company_size": "500+",
    "required_skills": ["Unity", "C#", "Mobile Development", "Android", "iOS"],
    "job_benefits": ["Bảo hiểm sức khỏe", "Nghỉ phép có lương", "Game room"],
    "contact_email": "tuyendung@vng.com.vn",
    "application_deadline": "2025-11-30",
    "application_method": "email",
    "is_urgent": false,
    "is_featured": false,
    "categories": [102]
  }'
```

### Freelance Game Developer
```bash
curl -X POST https://lamgame.localhost/api/jobs \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "title": "Freelance Unity Developer",
    "company_name": "Indie Game Studio",
    "description": "<p>Tìm kiếm Unity Developer freelance cho dự án game indie 3-6 tháng.</p><ul><li>Phát triển game 2D platformer</li><li>Implement game mechanics</li><li>UI/UX integration</li><li>Performance optimization</li></ul>",
    "short_description": "Dự án freelance Unity Developer 3-6 tháng, làm việc remote, thanh toán theo milestone.",
    "job_type": "freelance",
    "experience_level": "junior",
    "salary_range": "negotiable",
    "job_location": "Remote",
    "company_size": "1-10",
    "required_skills": ["Unity", "C#", "2D Game Development"],
    "job_benefits": ["Làm việc từ xa", "Giờ làm việc linh hoạt"],
    "contact_email": "contact@indiegame.studio",
    "application_deadline": "2025-11-20",
    "application_method": "email",
    "is_urgent": true,
    "is_featured": false,
    "categories": [102]
  }'
```

## 2. Lấy Job Options (Get Form Data)

```bash
curl -X GET https://lamgame.localhost/api/jobs/options/form-data \
  -H "Accept: application/json" \
  -k
```

## 3. Lấy Danh Sách Jobs (Get Jobs List)

### Basic Listing
```bash
curl -X GET "https://lamgame.localhost/api/jobs" \
  -H "Accept: application/json" \
  -k
```

### With Filters
```bash
curl -X GET "https://lamgame.localhost/api/jobs?search=unity&job_type=full-time&location=Ho%20Chi%20Minh&per_page=10" \
  -H "Accept: application/json" \
  -k
```

### Advanced Filters
```bash
curl -X GET "https://lamgame.localhost/api/jobs?experience_level=senior&salary_range=30m-50m&is_urgent=true&order_by=created_at&order_direction=desc" \
  -H "Accept: application/json" \
  -k
```

## 4. Cập Nhật Job (Update Job)

```bash
curl -X PUT https://lamgame.localhost/api/jobs/25 \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "title": "Senior Unity Developer (Updated)",
    "salary_range": "50m-80m",
    "experience_level": "senior",
    "is_featured": true,
    "application_deadline": "2025-12-15"
  }'
```

## 5. Xem Chi Tiết Job (Get Job Details)

```bash
curl -X GET https://lamgame.localhost/api/jobs/25 \
  -H "Accept: application/json" \
  -k
```

## 6. Xóa Job (Delete Job)

```bash
curl -X DELETE https://lamgame.localhost/api/jobs/25 \
  -H "Accept: application/json" \
  -k
```

## 7. Bulk Operations

### Bulk Update Status
```bash
curl -X POST https://lamgame.localhost/api/jobs/bulk \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "job_ids": [25, 26, 27],
    "action": "activate"
  }'
```

### Bulk Feature Jobs
```bash
curl -X POST https://lamgame.localhost/api/jobs/bulk \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -k \
  -d '{
    "job_ids": [25, 26],
    "action": "feature"
  }'
```

## 8. Job Analytics

```bash
curl -X GET https://lamgame.localhost/api/analytics/jobs/25/analytics \
  -H "Accept: application/json" \
  -k
```

## Valid Values Reference

### Job Types
- `full-time`
- `part-time` 
- `contract`
- `freelance`
- `internship`
- `remote`
- `hybrid`

### Experience Levels
- `fresher`
- `junior`
- `middle`
- `senior`
- `lead`
- `director`

### Salary Ranges
- `under-10m`
- `10m-20m`
- `20m-30m`
- `30m-50m`
- `50m-80m`
- `over-80m`
- `negotiable`

### Application Methods
- `email`
- `online`
- `direct`
- `website`

### Common Skills
- `Unity`
- `Unreal Engine`
- `C#`
- `C++`
- `JavaScript`
- `Python`
- `Java`
- `Swift`
- `Kotlin`
- `HTML5/CSS3`
- `React Native`
- `Flutter`
- `Photoshop`
- `3ds Max`
- `Maya`
- `Blender`
- `Git`
- `Agile/Scrum`
- `Game Design`
- `Level Design`

### Common Benefits
- `Bảo hiểm sức khỏe`
- `Bảo hiểm xã hội`
- `Thưởng hiệu suất`
- `Du lịch hàng năm`
- `Nghỉ phép có lương`
- `Đào tạo & phát triển`
- `Làm việc từ xa`
- `Giờ làm việc linh hoạt`
- `Máy tính/laptop công ty`
- `Phụ cấp ăn trua`
- `Team building`
- `Game room`
