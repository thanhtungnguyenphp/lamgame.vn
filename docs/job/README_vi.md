# Tài liệu chức năng Tuyển dụng

Tài liệu này mô tả chức năng liên quan đến các bài đăng tuyển dụng trên nền tảng lamgame.vn.

## 1. Tổng quan

Cổng tuyển dụng cho phép các công ty và người dùng đăng các tin tuyển dụng trong ngành phát triển game. Nó cung cấp các tính năng để liệt kê, tìm kiếm, xem và quản lý hồ sơ ứng tuyển.

## 2. Trang danh sách việc làm (`/viec-lam-game`)

Trang này hiển thị danh sách các vị trí tuyển dụng có sẵn.

*   **Nguồn dữ liệu:** Các công việc chủ yếu được lấy từ bảng `products`, được lọc theo `type = 'job'` và `sku LIKE 'JOB_%'`. Thông tin chi tiết như tên, mô tả, trạng thái và khóa URL được lấy từ bảng `product_flat` đã được địa phương hóa (sử dụng locale `vi`).
*   **Thông tin công ty:** Kết nối với bảng `companies` để hiển thị tên công ty, logo và các chi tiết khác.
*   **Lọc & Sắp xếp:** Người dùng có thể lọc công việc theo từ khóa, địa điểm và sắp xếp chúng theo ngày (mới nhất trước), mức lương (từ cao đến thấp) hoặc tên công ty. Các tham số này được lấy từ chuỗi truy vấn của yêu cầu.
*   **Thuộc tính:** Các thuộc tính công việc chính như loại công việc, cấp độ kinh nghiệm, phạm vi lương, địa điểm, kỹ năng yêu cầu và lợi ích được lấy từ `product_attribute_values` và các bảng liên quan.
*   **Hình ảnh thu nhỏ:** Mỗi danh sách công việc hiển thị một hình ảnh thu nhỏ. Hình ảnh này có thể được lấy từ hình ảnh công việc được liên kết trong `product_images` hoặc sử dụng hình ảnh tuyển dụng mặc định làm phương án dự phòng.
*   **Khóa URL:** Các công việc được xác định bằng khóa URL (slug) trong `product_flat` cho các URL thân thiện, ví dụ: `/viec-lam-game/ten-cong-viec-cua-ban-123`.

## 3. Trang chi tiết công việc (`/viec-lam/{slug}`)

Trang này hiển thị thông tin chi tiết về một tin tuyển dụng cụ thể.

*   **Lấy dữ liệu:** Lấy dữ liệu công việc dựa trên `url_key` (slug) từ `product_flat` và các bảng liên quan, bao gồm mô tả chi tiết, thông tin công ty, thuộc tính và hình ảnh.
*   **Công việc liên quan:** Đề xuất các tin tuyển dụng tương tự dựa trên danh mục.
*   **Chi tiết công ty:** Hiển thị thông tin chi tiết về công ty tuyển dụng, bao gồm mô tả, trang web, thông tin liên hệ và logo.
*   **Biểu mẫu ứng tuyển:** Cung cấp giao diện để người dùng ứng tuyển vào công việc, điền sẵn thông tin nếu người dùng đã đăng nhập.

## 4. Chức năng đăng tuyển dụng

Các bài đăng tuyển dụng có thể được tạo và quản lý thông qua Bảng quản trị (Admin Panel) hoặc qua các API endpoints.

### 4.1. Bảng quản trị (`/admin/jobs`)

Giao diện quản trị cung cấp một cách thân thiện với người dùng để quản lý các bài đăng tuyển dụng.

*   **Routes:** Được xử lý bởi `Admin/JobController` với các routes như `GET /admin/jobs` (index), `GET /admin/jobs/create`, `POST /admin/jobs`, `GET /admin/jobs/{id}/edit`, `PUT /admin/jobs/{id}`, `DELETE /admin/jobs/{id}`.
*   **Luồng hoạt động (`store` & `update` methods):
    *   **Xác thực:** Các trường nhập liệu như tiêu đề, mô tả, email liên hệ và tên công ty được xác thực.
    *   **Quản lý công ty:** Quản trị viên có thể tạo công ty mới hoặc liên kết công việc với công ty hiện có (nếu quản trị viên đã được liên kết với một công ty). Việc tải lên logo công ty được xử lý.
    *   **Tạo Sản phẩm & Flat:** Tạo các bản ghi trong bảng `products` (type: `job`, SKU: `JOB_%`) và `product_flat`.
    *   **Lưu Thuộc tính:** Các thuộc tính cụ thể của công việc (loại công việc, cấp độ kinh nghiệm, lương, kỹ năng, lợi ích, v.v.) được lưu vào `product_attribute_values` và các bảng pivot (`job_skills`, `job_benefits`).
    *   **Quyền sở hữu:** Các công việc do quản trị viên đăng được liên kết với người dùng quản trị viên thông qua `created_by_admin_id`.

### 4.2. API Endpoints

Các API có sẵn cho việc quản lý công việc theo chương trình.

*   **Public API (`/api/jobs`)
    *   `GET /api/jobs`:** Liệt kê các bài đăng tuyển dụng với nhiều bộ lọc khác nhau (tìm kiếm, loại hình, địa điểm, lương, v.v.).
    *   `GET /api/jobs/{id}`:** Lấy chi tiết cho một công việc cụ thể.
    *   `POST /api/jobs`:** Tạo một bài đăng tuyển dụng mới. Yêu cầu xác thực (thường là token `sanctum`). Nếu người dùng quản trị viên được xác thực, nó sẽ xử lý liên kết công ty.
    *   `PUT /api/jobs/{id}`:** Cập nhật một bài đăng tuyển dụng hiện có.
    *   `DELETE /api/jobs/{id}`:** Xóa một bài đăng tuyển dụng.
    *   `POST /api/jobs/{id}/publish` & `POST /api/jobs/{id}/unpublish`:** Chuyển đổi trạng thái đăng của một công việc.

*   **API dành riêng cho người dùng (`/api/user/jobs`)
    *   Phần này được bảo vệ bởi `auth:sanctum` và dành cho những người dùng đã xác thực (có thể là người đăng tin hoặc nhà tuyển dụng).
    *   `GET /api/user/jobs`:** Liệt kê các công việc do người dùng đã xác thực tạo.
    *   `POST /api/user/jobs`:** Tạo một bài đăng tuyển dụng mới cho người dùng đã xác thực. Nó đảm bảo công việc được liên kết với danh mục chính xác (`viec-lam`) và ID người dùng.
    *   `PUT /api/user/jobs/{id}`:** Cập nhật một công việc thuộc sở hữu của người dùng.
    *   `DELETE /api/user/jobs/{id}`:** Xóa một công việc thuộc sở hữu của người dùng.
    *   `PATCH /api/user/jobs/{id}/toggle-status`:** Kích hoạt/vô hiệu hóa một công việc.
    *   `POST /api/user/jobs/{id}/duplicate`:** Nhân bản một công việc hiện có.
    *   `POST /api/user/jobs/from-template/{templateId}`:** Tạo một công việc từ mẫu đã lưu.

## 5. Điểm nổi bật về cấu trúc cơ sở dữ liệu

*   **Bảng `products`:** Bảng cốt lõi cho tất cả các loại sản phẩm, bao gồm cả công việc. Lưu trữ `id`, `sku`, `type` (ví dụ: 'job'), `created_by_admin_id`, `company_id`, dấu thời gian.
*   **Bảng `product_flat`:** Lưu trữ dữ liệu sản phẩm theo ngôn ngữ. Đối với công việc, điều này bao gồm `name`, `description`, `short_description`, `status`, `visible_individually`, `url_key`, `meta_title`, `meta_description` và `locale`.
*   **`categories` & `category_translations`:** Được sử dụng để phân loại sản phẩm, với `viec-lam` là danh mục chính cho công việc.
*   **Bảng `attributes`:** Định nghĩa các thuộc tính có sẵn cho sản phẩm (ví dụ: `job_type`, `experience_level`, `required_skills`).
*   **Bảng `product_attribute_values`:** Liên kết các sản phẩm với giá trị thuộc tính. Đối với công việc, lưu trữ `text_value` (cho chuỗi/ID), `integer_value`, `date_value`, v.v., liên quan đến `attribute_id`.
*   **Bảng `job_skills` & `job_benefits`:** Bảng pivot cho các mối quan hệ nhiều-nhiều, liên kết công việc (`product_id`) với các tùy chọn kỹ năng (`skill_option_id`) hoặc tùy chọn lợi ích (`benefit_option_id`) cụ thể.
*   **Bảng `companies`:** Lưu trữ thông tin về các công ty đăng tuyển.
*   **Bảng `job_applications`:** Lưu trữ các hồ sơ ứng tuyển đã gửi cho các tin tuyển dụng.

## 6. Các Controller và Service chính

*   **`LamGamePageController`:** Xử lý hiển thị danh sách và chi tiết công việc ở frontend.
*   **`Admin/JobController`:** Quản lý các bài đăng tuyển dụng thông qua bảng quản trị.
*   **`Api/JobController`:** Cung cấp các API endpoints cho dữ liệu công việc công khai và tạo/quản lý công việc chung.
*   **`Api/UserJobController`:** Cung cấp các API endpoints cho người dùng đã xác thực để quản lý bài đăng tuyển dụng của riêng họ.
*   **`JobService`:** Chứa logic kinh doanh cốt lõi để tạo, cập nhật, nhân bản và quản lý công việc.
*   **`JobFilterService`:** Xử lý việc lấy các danh mục và thuộc tính công việc cho biểu mẫu.
*   **`JobSearchService`:** Triển khai logic tìm kiếm và lọc nâng cao cho danh sách công việc và quản lý công việc của người dùng.
