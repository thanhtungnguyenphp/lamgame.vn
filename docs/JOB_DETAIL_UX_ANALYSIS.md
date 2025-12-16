# Phân tích UX/UI Job Detail - Editor vs Display

## Vấn đề hiện tại

### Editor (Input)
- ✅ Có cấu trúc rõ ràng với heading **"Mô Tả công việc"** (bold)
- ✅ Phần mô tả có *italic* cho tiêu đề phụ
- ✅ Danh sách số thứ tự (1-6) rõ ràng
- ✅ Phần **"Quyền lợi"** (bold) tách biệt
- ✅ Danh sách bullet points (•) cho quyền lợi

### Display (Output) - Vấn đề
- ❌ Mất format: bullet points (•) chuyển thành số thứ tự (1-7)
- ❌ Tất cả nội dung bị gộp chung thành 1 danh sách dài
- ❌ Không phân biệt được 2 section "Mô tả" và "Quyền lợi"
- ❌ Thiếu khoảng cách giữa các section
- ❌ Heading "Quyền lợi" không nổi bật
- ❌ Mất style italic, bold từ editor

### Nguyên nhân
```blade
{!! nl2br($job->description) !!}
```
Chỉ convert `\n` → `<br>` mà không giữ HTML tags từ editor.

## Giải pháp tối ưu

### 1. CSS cho Editor Content
Tạo class wrapper để style nội dung từ editor giống như trong editor:

```css
.editor-content {
    /* Reset list styles */
    ol, ul {
        margin: 1em 0;
        padding-left: 2em;
    }
    
    ol {
        list-style-type: decimal;
    }
    
    ul {
        list-style-type: disc;
    }
    
    /* Headings */
    h1, h2, h3, h4, h5, h6 {
        font-weight: 600;
        margin: 1.5em 0 0.5em;
        line-height: 1.3;
    }
    
    h2 { font-size: 1.5em; }
    h3 { font-size: 1.25em; }
    
    /* Paragraphs */
    p {
        margin: 0.75em 0;
        line-height: 1.6;
    }
    
    /* Text styles */
    strong, b {
        font-weight: 600;
    }
    
    em, i {
        font-style: italic;
    }
    
    /* List items */
    li {
        margin: 0.5em 0;
        line-height: 1.6;
    }
    
    /* Nested lists */
    li > ul,
    li > ol {
        margin: 0.5em 0;
    }
}
```

### 2. Sanitize HTML an toàn
Sử dụng HtmlSanitizer đã có sẵn:

```php
// app/Helpers/HtmlSanitizer.php (đã tồn tại)
use Enshrined\SvgSanitize\Sanitizer;

public static function sanitize($html)
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'p,br,strong,b,em,i,u,ul,ol,li,h1,h2,h3,h4,h5,h6');
    $config->set('HTML.AllowedAttributes', '');
    
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}
```

### 3. Update Blade Template
```blade
<!-- Job Description -->
<div class="content-section">
    <h2 class="section-title">Mô tả công việc</h2>
    <div class="section-content editor-content">
        @if($job->description)
            {!! \App\Helpers\HtmlSanitizer::sanitize($job->description) !!}
        @else
            <p>Thông tin mô tả công việc sẽ được cập nhật sớm.</p>
        @endif
    </div>
</div>
```

### 4. Tối ưu block bọc ngoài
```css
.section-content {
    /* Container styles */
    background: #fff;
    padding: 1.5rem;
    border-radius: 8px;
    
    /* Prevent overflow */
    overflow-wrap: break-word;
    word-wrap: break-word;
    word-break: break-word;
    
    /* Clear floats */
    &::after {
        content: "";
        display: table;
        clear: both;
    }
}

/* Prevent affecting other blocks */
.section-content.editor-content {
    /* Isolate styles */
    isolation: isolate;
    
    /* Reset margins for first/last elements */
    > *:first-child {
        margin-top: 0;
    }
    
    > *:last-child {
        margin-bottom: 0;
    }
}
```

## Kết quả mong đợi

### Hiển thị sau khi fix:
```
Mô Tả công việc

Tham gia vào các dự án game của công ty.

Chuyển thể các thiết kế của Game Artist và Game Design thành các chức năng của game.
Thiết kế, xây dựng và duy trì Code hiệu quả, có thể tái sử dụng và có thể tái sử dụng và mở rộng.
Làm việc theo hướng dẫn và phân công của leader.

1. Sinh viên năm thứ 4 - 5 các trường đại học chính quy ngành Công nghệ thông tin hoặc tương đương.
2. Ưu tiên các bạn học Đại học Bách Khoa, Đại học Công Nghệ, Học viện Bưu Chính Viễn Thông,...có giải các kỳ thi Tỉnh, Quốc Gia,...
3. Yêu cầu đi làm tối thiểu 8 buổi/tuần.
4. Có kinh nghiệm lập trình C#.
5. Tư duy logic tốt, ham học hỏi và sẵn sàng trải nghiệm những thử thách mới
6. Ưu tiên có kinh nghiệm về Unity, hoặc đã nghiên cứu về Unity.

Quyền lợi

• Cơ hội lên chính thức sau thực tập.
• Khám sức khỏe định kỳ 1 lần/năm.
• Hỗ trợ cơm trưa, tea-break,...
• Hỗ trợ đóng đầu thực tập.
• Du lịch, team building: 3 lần/năm.
• Môi trường làm việc vô cùng sáng tạo, năng động, được thoải mái chơi các trò chơi tại công ty: bida, PS, board game,...
• Thời gian làm việc: 8:15 - 17:45 (7,5 giờ/ngày; thứ Hai - thứ Sáu)
```

## Implementation Steps

1. ✅ Tạo CSS file mới: `resources/css/editor-content.css`
2. ✅ Update blade template: `resources/views/lamgame/pages/job-detail.blade.php`
3. ✅ Compile assets: `npm run build`
4. ✅ Test với nhiều loại nội dung khác nhau

## Security Notes

- ✅ Sử dụng HTMLPurifier để sanitize
- ✅ Chỉ cho phép các tags an toàn
- ✅ Không cho phép attributes (class, id, style inline)
- ✅ Prevent XSS attacks
