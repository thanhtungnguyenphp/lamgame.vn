# Test Guide: Editor Content Display

## Cách test nhanh

### 1. Xem Demo HTML
```bash
open docs/error_screen/editor_content_demo.html
```
So sánh TRƯỚC (sai) vs SAU (đúng)

### 2. Test trên Job Detail Page

#### Bước 1: Tạo/Edit Job với nội dung có format
Vào admin panel → Jobs → Create/Edit

Nhập nội dung trong editor:

```
Mô Tả công việc (chọn Heading 3 hoặc Bold)

Tham gia vào các dự án game của công ty. (chọn Italic)

Chuyển thể các thiết kế của Game Artist và Game Design thành các chức năng của game.
Thiết kế, xây dựng và duy trì Code hiệu quả.
Làm việc theo hướng dẫn và phân công của leader.

1. Sinh viên năm thứ 4 - 5 (chọn Numbered List)
2. Ưu tiên các bạn học Đại học Bách Khoa
3. Yêu cầu đi làm tối thiểu 8 buổi/tuần
4. Có kinh nghiệm lập trình C#
5. Tư duy logic tốt
6. Ưu tiên có kinh nghiệm về Unity

Quyền lợi (chọn Heading 3 hoặc Bold)

• Cơ hội lên chính thức sau thực tập (chọn Bullet List)
• Khám sức khỏe định kỳ 1 lần/năm
• Hỗ trợ cơm trưa, tea-break
• Hỗ trợ đóng đầu thực tập
• Du lịch, team building: 3 lần/năm
• Môi trường làm việc vô cùng sáng tạo
• Thời gian làm việc: 8:15 - 17:45
```

#### Bước 2: Save và xem Job Detail
1. Save job
2. Vào trang job detail
3. Scroll xuống phần "Mô tả công việc"

#### Bước 3: Verify
Kiểm tra các điểm sau:

✅ **Headings**
- "Mô Tả công việc" hiển thị bold, font lớn hơn
- "Quyền lợi" hiển thị bold, font lớn hơn
- Có khoảng cách trên/dưới heading

✅ **Text Formatting**
- Text italic hiển thị nghiêng
- Text bold hiển thị đậm

✅ **Lists**
- Numbered list: 1, 2, 3, 4, 5, 6 (không phải tất cả là 1, 2, 3...)
- Bullet list: • • • • • • • (không phải 1, 2, 3...)
- List items có indent (lùi vào)

✅ **Spacing**
- Có khoảng cách giữa paragraphs
- Có khoảng cách giữa 2 sections
- List items có khoảng cách hợp lý

✅ **Layout**
- Không ảnh hưởng sidebar
- Không ảnh hưởng header/footer
- Không bị overflow

### 3. Test Mobile
1. Mở Chrome DevTools (F12)
2. Toggle device toolbar (Ctrl+Shift+M)
3. Chọn iPhone/Android
4. Verify:
   - Font size phù hợp
   - List indent không quá rộng
   - Không bị horizontal scroll

### 4. Test Security
Thử nhập nội dung có script:
```html
<script>alert('XSS')</script>
<p onclick="alert('XSS')">Click me</p>
<p style="background: red;">Styled</p>
```

Verify:
- ✅ Script tags bị remove
- ✅ Event handlers bị remove
- ✅ Style attributes bị remove
- ✅ Chỉ hiển thị text thuần

## Expected Results

### TRƯỚC (Sai)
```
Mô Tả công việc

Tham gia vào các dự án game của công ty.

Chuyển thể các thiết kế...
1. Sinh viên năm thứ 4 - 5
2. Ưu tiên các bạn học...
3. Yêu cầu đi làm...

Quyền lợi

1. Cơ hội lên chính thức
2. Khám sức khỏe
3. Hỗ trợ cơm trưa
```
❌ Tất cả là text bình thường
❌ Bullet list thành numbered list
❌ Không có spacing

### SAU (Đúng)
```
Mô Tả công việc (BOLD, LARGER)

Tham gia vào các dự án game của công ty. (ITALIC)

Chuyển thể các thiết kế...

  1. Sinh viên năm thứ 4 - 5
  2. Ưu tiên các bạn học...
  3. Yêu cầu đi làm...

Quyền lợi (BOLD, LARGER)

  • Cơ hội lên chính thức
  • Khám sức khỏe
  • Hỗ trợ cơm trưa
```
✅ Headings bold và lớn hơn
✅ Italic text nghiêng
✅ Numbered list đúng
✅ Bullet list đúng
✅ Có spacing hợp lý

## Troubleshooting

### Vấn đề: CSS không load
```bash
# Check file exists
ls -la public/css/editor-content.css

# Copy lại nếu cần
cp resources/css/editor-content.css public/css/editor-content.css

# Clear cache
php artisan cache:clear
php artisan view:clear
```

### Vấn đề: Format vẫn sai
1. Check blade template có class `editor-content`
2. Check có dùng `HtmlSanitizer::sanitize()`
3. Check browser console có lỗi CSS
4. Hard refresh (Ctrl+Shift+R)

### Vấn đề: Ảnh hưởng layout khác
1. Check CSS có `isolation: isolate`
2. Check selector `.section-content.editor-content`
3. Thêm specificity nếu cần

## Contact
Nếu có vấn đề, check:
- `docs/JOB_DETAIL_UX_ANALYSIS.md` - Chi tiết phân tích
- `docs/IMPLEMENTATION_EDITOR_CONTENT.md` - Chi tiết implementation
- `docs/EDITOR_CONTENT_FIX_SUMMARY.md` - Tóm tắt ngắn gọn
