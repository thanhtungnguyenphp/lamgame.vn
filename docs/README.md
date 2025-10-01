# API Dashboard Documentation

## Tổng quan

Đây là tài liệu hướng dẫn tích hợp và sử dụng API Dashboard cho hệ thống quản lý tuyển dụng LamGame.vn. API này cho phép employers quản lý job postings và applications một cách hiệu quả.

## 📁 Cấu trúc tài liệu

```
docs/
├── README.md                           # Tài liệu này
├── api-dashboard-integration.md        # Chi tiết API endpoints và cách sử dụng
└── dashboard-frontend-integration.md   # Hướng dẫn tích hợp frontend
```

## 🚀 Quick Start

### 1. API Backend
- **File:** [`api-dashboard-integration.md`](./api-dashboard-integration.md)
- **Nội dung:** Chi tiết về tất cả endpoints, authentication, error handling và examples
- **Dành cho:** Backend developers, API integrators

### 2. Frontend Integration  
- **File:** [`dashboard-frontend-integration.md`](./dashboard-frontend-integration.md)
- **Nội dung:** HTML templates, JavaScript implementation, CSS styling và deployment
- **Dành cho:** Frontend developers, UI/UX implementers

## 📊 Tính năng chính

### 🎯 Dashboard Overview API
- Lấy 5 jobs mới nhất của employer
- Hiển thị 5 applications mới nhất  
- Statistics tổng quan (total jobs, applications, pending, etc.)
- Real-time data với authentication

### 👥 Job Applications Management
- Xem chi tiết applications cho từng job
- Cập nhật trạng thái applications (pending → reviewed → shortlisted → accepted/rejected)
- Employer notes và feedback system
- Comprehensive filtering và sorting

### 🔐 Security Features
- Laravel Sanctum authentication
- Rate limiting (60 requests/minute)
- Input validation và sanitization
- Error handling với proper HTTP status codes

## 🛠 Tech Stack

### Backend
- **Framework:** Laravel 11
- **Authentication:** Laravel Sanctum
- **Database:** MySQL với Bagisto EAV system
- **API Style:** RESTful JSON API
- **Rate Limiting:** Built-in throttling middleware

### Frontend
- **HTML5, CSS3, JavaScript (ES6+)**
- **Bootstrap 5** hoặc **Tailwind CSS**
- **Axios** cho HTTP requests
- **Responsive design** với mobile-first approach

## 📱 Mobile Support

API và frontend được thiết kế hoàn toàn responsive:
- ✅ Mobile-first CSS với Bootstrap breakpoints
- ✅ Touch-friendly UI elements
- ✅ Optimized modals và navigation cho mobile
- ✅ Performance optimization cho mobile networks

## 🔗 API Endpoints Summary

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/dashboard/` | Dashboard overview với jobs và applications mới nhất |
| `GET` | `/api/dashboard/jobs/{id}/applications` | Chi tiết applications cho một job |
| `PUT` | `/api/dashboard/applications/{id}/status` | Cập nhật trạng thái application |

**Base URL:** `https://lamgame.localhost/api/dashboard/`

**Authentication:** Bearer token (Laravel Sanctum)

## 📈 Database Schema

### Job Applications Table
```sql
CREATE TABLE job_applications (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    job_id INT UNSIGNED NOT NULL,
    applicant_user_id INT UNSIGNED NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_email VARCHAR(255) NOT NULL,
    applicant_phone VARCHAR(255) NULL,
    cover_letter TEXT NULL,
    resume_file_path VARCHAR(255) NULL,
    additional_info JSON NULL,
    status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'accepted') DEFAULT 'pending',
    employer_notes TEXT NULL,
    applied_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (job_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (applicant_user_id) REFERENCES customers(id) ON DELETE CASCADE,
    
    UNIQUE KEY unique_application (job_id, applicant_user_id)
);
```

## 🎯 Status Flow

```
pending → reviewed → shortlisted → accepted
   ↓         ↓           ↓
rejected  rejected    rejected
```

## 🧪 Testing

### Automated Tests
- Unit tests cho API endpoints
- Integration tests cho database operations  
- Frontend unit tests cho JavaScript functions

### Manual Testing
- Postman collection cho API testing
- Browser testing cho responsive design
- Performance testing cho mobile devices

### Test Files
- `tests/Feature/Api/DashboardApiTest.php` - API endpoint tests
- Manual testing với cURL commands trong documentation

## 🚀 Deployment

### Environment Setup
```env
APP_URL=https://lamgame.localhost
SANCTUM_STATEFUL_DOMAINS=lamgame.localhost
SESSION_DOMAIN=.lamgame.localhost
```

### Docker Support
- Sử dụng Docker Compose setup có sẵn
- Database migrations tự động
- Asset compilation với Vite

### Production Checklist
- [ ] Environment variables configured
- [ ] Database migrations applied
- [ ] Assets compiled và minified
- [ ] HTTPS enabled
- [ ] Rate limiting configured
- [ ] Error logging setup

## 🔧 Development

### Prerequisites
- Laravel 11+
- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Docker & Docker Compose

### Setup Commands
```bash
# Clone repository
git clone [repository-url]

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
docker-compose up -d mysql
php artisan migrate

# Start development server
docker-compose up -d
```

## 📞 Support & Contact

### Documentation
- **API Documentation:** [`api-dashboard-integration.md`](./api-dashboard-integration.md)
- **Frontend Guide:** [`dashboard-frontend-integration.md`](./dashboard-frontend-integration.md)
- **Troubleshooting:** Xem section "Troubleshooting" trong mỗi file documentation

### Common Issues
1. **401 Unauthorized:** Check token validity và Bearer prefix
2. **404 Not Found:** Verify endpoint URLs và resource IDs
3. **422 Validation Error:** Check request body format và required fields
4. **429 Rate Limit:** Implement exponential backoff trong client

## 🔄 Changelog

### Version 1.0.0 (2025-10-01)
- ✅ Initial release
- ✅ Dashboard overview endpoint
- ✅ Job applications management
- ✅ Application status updates  
- ✅ Authentication với Sanctum
- ✅ Rate limiting implementation
- ✅ Complete frontend integration guide
- ✅ Mobile responsive design
- ✅ Comprehensive documentation

## 📋 Next Steps

### Planned Features
- [ ] Real-time notifications cho new applications
- [ ] Bulk operations (accept/reject multiple applications)
- [ ] Advanced filtering và search
- [ ] Application analytics và reporting
- [ ] File upload cho resumes
- [ ] Email notifications cho status changes

### Integration Opportunities
- [ ] Integration với HR systems
- [ ] Third-party job boards synchronization
- [ ] Calendar integration cho interviews
- [ ] Chat system với applicants

---

## 📖 Cách sử dụng tài liệu

1. **Developers mới:** Đọc README này trước, sau đó đi tới file phù hợp với vai trò
2. **Backend Developers:** Focus vào `api-dashboard-integration.md`
3. **Frontend Developers:** Focus vào `dashboard-frontend-integration.md`  
4. **Full-stack Developers:** Đọc cả hai files để có overview đầy đủ

## 🎓 Learning Resources

### Laravel & API Development
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [RESTful API Design](https://restfulapi.net/)

### Frontend Development
- [Bootstrap 5](https://getbootstrap.com/docs/5.3/)
- [Axios Documentation](https://axios-http.com/docs/intro)
- [JavaScript ES6+](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

---

**📝 Lưu ý:** Tài liệu này được cập nhật liên tục. Vui lòng kiểm tra version mới nhất trước khi implement.