# Editor Tools Guide - Hướng dẫn sử dụng

## Toolbar Layout

```
┌─────────────────────────────────────────────────────────────────┐
│ [H1▼] [B] [I] [U] [S] │ [1.] [•] [◄] [►] │ [≡] [🔗] ["] │ [✕]  │
└─────────────────────────────────────────────────────────────────┘
   Headers  Text Style    Lists  Indent   Align Link Quote Clean
```

## 1. Headers (Tiêu đề)

### Cách dùng:
1. Chọn text
2. Click dropdown Headers
3. Chọn H1, H2, H3, hoặc H4

### Khi nào dùng:
- **H1:** Tiêu đề chính (VD: "Mô Tả Công Việc")
- **H2:** Tiêu đề phụ (VD: "Yêu Cầu")
- **H3:** Tiêu đề nhỏ (VD: "Kỹ năng bắt buộc")
- **H4:** Tiêu đề chi tiết

### Ví dụ:
```
# Mô Tả Công Việc (H1)

## Trách nhiệm chính (H2)

### Công việc hàng ngày (H3)
```

---

## 2. Text Formatting

### Bold (B) - In đậm
**Khi nào dùng:** Nhấn mạnh từ khóa quan trọng
```
Yêu cầu: **3 năm kinh nghiệm**
```

### Italic (I) - In nghiêng
**Khi nào dùng:** Ghi chú, phụ đề
```
*Ưu tiên ứng viên có kinh nghiệm Unity*
```

### Underline (U) - Gạch chân
**Khi nào dùng:** Highlight thông tin đặc biệt
```
Deadline: <u>31/12/2025</u>
```

### Strike (S) - Gạch ngang
**Khi nào dùng:** Hiển thị giá cũ, thông tin đã hết hạn
```
Lương: <s>15 triệu</s> → 20 triệu
```

---

## 3. Lists (Danh sách)

### Ordered List (1.) - Danh sách số
**Khi nào dùng:** Các bước, thứ tự ưu tiên
```
Quy trình ứng tuyển:
1. Nộp CV
2. Phỏng vấn HR
3. Phỏng vấn kỹ thuật
4. Nhận offer
```

### Bullet List (•) - Danh sách gạch đầu dòng
**Khi nào dùng:** Liệt kê không theo thứ tự
```
Quyền lợi:
• Lương 13 tháng
• Bảo hiểm đầy đủ
• Du lịch hàng năm
```

---

## 4. Indent/Outdent (Lề)

### Indent (►) - Tăng lề
**Khi nào dùng:** Tạo sub-items, phân cấp
```
Yêu cầu:
• Kỹ năng lập trình
    • C# (bắt buộc)
    • Unity (ưu tiên)
• Kỹ năng mềm
    • Teamwork
    • Communication
```

### Outdent (◄) - Giảm lề
**Khi nào dùng:** Quay lại level trước

---

## 5. Alignment (Căn chỉnh)

### Left (≡) - Căn trái (mặc định)
**Khi nào dùng:** Nội dung thông thường

### Center (≡) - Căn giữa
**Khi nào dùng:** Tiêu đề chính, slogan
```
                MÔ TẢ CÔNG VIỆC
```

### Right (≡) - Căn phải
**Khi nào dùng:** Ngày tháng, chữ ký

### Justify (≡) - Căn đều
**Khi nào dùng:** Đoạn văn dài, nội dung chính

---

## 6. Link (🔗)

### Cách thêm link:
1. Chọn text
2. Click icon Link (🔗)
3. Nhập URL (phải có http:// hoặc https://)
4. Click OK

### Ví dụ:
```
Xem thêm tại [website công ty](https://company.com)
Nộp CV tại [form ứng tuyển](https://forms.company.com)
```

### Lưu ý:
- ✅ Link tự động mở tab mới
- ✅ Chỉ cho phép http/https (bảo mật)
- ❌ Không cho phép javascript:, data:

---

## 7. Blockquote (") - Trích dẫn

### Cách dùng:
1. Chọn text
2. Click icon Blockquote (")

### Khi nào dùng:
- Highlight thông tin quan trọng
- Quote từ công ty
- Lưu ý đặc biệt

### Ví dụ:
```
> Chúng tôi tìm kiếm những người đam mê game development
> và sẵn sàng học hỏi công nghệ mới!
```

### Hiển thị:
```
┌─────────────────────────────────────────────┐
│ Chúng tôi tìm kiếm những người đam mê game  │
│ development và sẵn sàng học hỏi công nghệ   │
│ mới!                                        │
└─────────────────────────────────────────────┘
```

---

## 8. Clean (✕) - Xóa format

### Cách dùng:
1. Chọn text đã format
2. Click icon Clean (✕)
3. Text trở về plain text

### Khi nào dùng:
- Copy/paste từ Word có format lỗi
- Muốn reset về text thuần

---

## 9. Character Counter

### Hiển thị:
```
┌─────────────────────────────────────┐
│ [Editor content here...]            │
│                                     │
└─────────────────────────────────────┘
                        1,234 ký tự
```

### Short Description:
- Hiển thị: `X/200 ký tự`
- Màu đỏ khi vượt 200

### Full Description:
- Hiển thị: `X ký tự`
- Không giới hạn

---

## 10. Undo/Redo

### Shortcuts:
- **Undo:** Ctrl+Z (Windows) / Cmd+Z (Mac)
- **Redo:** Ctrl+Y (Windows) / Cmd+Shift+Z (Mac)

### Lưu ý:
- Lưu tối đa 50 bước
- Delay 1 giây giữa các bước

---

## Best Practices

### ✅ Nên:
1. Dùng Headers để phân cấp nội dung
2. Dùng Lists cho các items
3. Dùng Blockquote cho thông tin quan trọng
4. Dùng Link cho references
5. Dùng Bold cho keywords
6. Kiểm tra character count

### ❌ Không nên:
1. Dùng quá nhiều colors (chưa support)
2. Dùng quá nhiều font sizes khác nhau
3. Paste trực tiếp từ Word (dùng Clean trước)
4. Dùng link không an toàn (javascript:, data:)
5. Quá nhiều formatting (gây rối)

---

## Examples

### Job Description Template

```markdown
# Mô Tả Công Việc

*Tham gia vào các dự án game của công ty*

Chuyển thể các thiết kế của Game Artist và Game Design thành các chức năng của game.

## Yêu Cầu

1. Sinh viên năm thứ 4-5 ngành CNTT
2. Có kinh nghiệm lập trình **C#**
3. Ưu tiên có kinh nghiệm **Unity**

## Quyền Lợi

> Môi trường làm việc năng động, sáng tạo!

• Lương: **15-20 triệu**
• Bảo hiểm đầy đủ
• Du lịch 3 lần/năm

Xem thêm tại [website công ty](https://company.com)
```

---

## Troubleshooting

### Vấn đề: Link không hoạt động
**Giải pháp:** Đảm bảo URL có `http://` hoặc `https://`

### Vấn đề: Format bị lỗi khi paste từ Word
**Giải pháp:** Chọn text → Click Clean (✕) → Format lại

### Vấn đề: Character counter không update
**Giải pháp:** Refresh page hoặc clear cache

### Vấn đề: Undo không hoạt động
**Giải pháp:** Đợi 1 giây giữa các thay đổi

---

## Support

Nếu có vấn đề, check:
- `docs/EDITOR_ENHANCEMENT_PROPOSAL.md` - Chi tiết features
- `docs/EDITOR_ENHANCEMENT_DONE.md` - Implementation details
