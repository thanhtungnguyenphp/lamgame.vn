# Editor Enhancement Proposal

## Hiện trạng Editor

### Tools hiện có:
- ✅ Headers (H3, H4)
- ✅ Bold, Italic, Underline
- ✅ Ordered List (1, 2, 3...)
- ✅ Bullet List (•)
- ✅ Clean formatting

### Hạn chế:
- ❌ Không có link
- ❌ Không có alignment (left, center, right)
- ❌ Không có indent/outdent
- ❌ Không có blockquote
- ❌ Không có color/highlight
- ❌ Không có undo/redo
- ❌ Không có character/word count
- ❌ Không có image upload
- ❌ Không có table
- ❌ Không có code block

## Đề xuất cải tiến (Theo mức độ ưu tiên)

### 🔥 Priority 1: Essential Tools (Cần thiết)

#### 1. Link (Hyperlink)
**Use case:** Link đến website công ty, form ứng tuyển, portfolio
```javascript
toolbar: [
    ['bold', 'italic', 'underline', 'link'],
    // ...
]
```

#### 2. Text Alignment
**Use case:** Căn giữa tiêu đề, căn đều nội dung
```javascript
toolbar: [
    [{ 'align': [] }], // left, center, right, justify
]
```

#### 3. Indent/Outdent
**Use case:** Tạo sub-lists, phân cấp nội dung
```javascript
toolbar: [
    [{ 'indent': '-1'}, { 'indent': '+1' }],
]
```

#### 4. Blockquote
**Use case:** Highlight thông tin quan trọng, quote từ công ty
```javascript
toolbar: [
    ['blockquote'],
]
```

#### 5. Character Counter
**Use case:** Kiểm soát độ dài nội dung
```javascript
// Custom module
modules: {
    counter: {
        container: '#counter',
        unit: 'character'
    }
}
```

### ⭐ Priority 2: Nice to Have

#### 6. Text Color & Background
**Use case:** Highlight keywords, tạo emphasis
```javascript
toolbar: [
    [{ 'color': [] }, { 'background': [] }],
]
```

#### 7. Font Size
**Use case:** Điều chỉnh kích thước text
```javascript
toolbar: [
    [{ 'size': ['small', false, 'large', 'huge'] }],
]
```

#### 8. Undo/Redo
**Use case:** Hoàn tác thay đổi
```javascript
modules: {
    history: {
        delay: 1000,
        maxStack: 50,
        userOnly: true
    }
}
```

### 🚀 Priority 3: Advanced (Nâng cao)

#### 9. Image Upload
**Use case:** Thêm logo công ty, hình ảnh minh họa
```javascript
toolbar: [
    ['image'],
]
// Custom image handler
```

#### 10. Code Block
**Use case:** Hiển thị code snippet cho technical jobs
```javascript
toolbar: [
    ['code-block'],
]
```

#### 11. Table
**Use case:** So sánh benefits, salary ranges
```javascript
// Requires quill-better-table module
```

## Recommended Configuration

### Cấu hình tối ưu (Balanced)
```javascript
const descEditor = new Quill('#desc-editor', {
    theme: 'snow',
    placeholder: 'Nhập mô tả chi tiết công việc...',
    modules: {
        toolbar: [
            // Headers
            [{ 'header': [1, 2, 3, 4, false] }],
            
            // Text formatting
            ['bold', 'italic', 'underline', 'strike'],
            
            // Text color & background
            [{ 'color': [] }, { 'background': [] }],
            
            // Lists
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            
            // Alignment
            [{ 'align': [] }],
            
            // Blockquote & Code
            ['blockquote', 'code-block'],
            
            // Link & Image
            ['link', 'image'],
            
            // Clean
            ['clean']
        ],
        
        // History (undo/redo)
        history: {
            delay: 1000,
            maxStack: 50,
            userOnly: true
        }
    }
});
```

### Cấu hình tối giản (Minimal)
```javascript
toolbar: [
    [{ 'header': [3, 4, false] }],
    ['bold', 'italic', 'underline', 'link'],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'align': [] }],
    ['blockquote'],
    ['clean']
]
```

### Cấu hình đầy đủ (Full-featured)
```javascript
toolbar: [
    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
    [{ 'font': [] }],
    [{ 'size': ['small', false, 'large', 'huge'] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ 'color': [] }, { 'background': [] }],
    [{ 'script': 'sub'}, { 'script': 'super' }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],
    [{ 'direction': 'rtl' }],
    [{ 'align': [] }],
    ['blockquote', 'code-block'],
    ['link', 'image', 'video'],
    ['clean']
]
```

## Implementation Plan

### Phase 1: Essential Tools (1-2 hours)
1. ✅ Add link support
2. ✅ Add alignment
3. ✅ Add indent/outdent
4. ✅ Add blockquote
5. ✅ Add character counter
6. ✅ Update CSS for new tools
7. ✅ Update sanitizer to allow new tags

### Phase 2: Nice to Have (2-3 hours)
1. ✅ Add color picker
2. ✅ Add font size
3. ✅ Add undo/redo UI
4. ✅ Add word count
5. ✅ Add preview mode

### Phase 3: Advanced (4-6 hours)
1. ✅ Image upload handler
2. ✅ Image resize/crop
3. ✅ Code block syntax highlighting
4. ✅ Table support
5. ✅ Auto-save draft

## Security Considerations

### Allowed tags cần update:
```php
// app/Helpers/HtmlSanitizer.php
$allowedTags = '<p><br><strong><b><em><i><u><s><ol><ul><li><h1><h2><h3><h4><h5><h6><blockquote><a><img><pre><code><table><tr><td><th>';

$allowedAttributes = [
    'a' => ['href', 'title', 'target'],
    'img' => ['src', 'alt', 'width', 'height'],
    'p' => ['style'], // for alignment
    'h1-h6' => ['style'], // for alignment
    'span' => ['style'], // for color
];
```

### Validation rules:
- Link: Only allow http/https protocols
- Image: Only allow from trusted domains or uploaded files
- Style: Only allow safe CSS properties (text-align, color, background-color)

## Benefits

### Cho Admin/HR:
- ✅ Linh động hơn trong việc format nội dung
- ✅ Tạo job posts chuyên nghiệp hơn
- ✅ Tiết kiệm thời gian với undo/redo
- ✅ Kiểm soát độ dài nội dung

### Cho Candidates:
- ✅ Dễ đọc hơn với alignment và colors
- ✅ Thông tin rõ ràng hơn với blockquote
- ✅ Link trực tiếp đến resources
- ✅ Hình ảnh minh họa (nếu có)

## Recommendation

**Đề xuất implement Phase 1 (Essential Tools) trước:**
- Link, Alignment, Indent, Blockquote, Counter
- Thời gian: 1-2 hours
- Impact: High
- Risk: Low

Sau đó đánh giá feedback và quyết định Phase 2 & 3.
