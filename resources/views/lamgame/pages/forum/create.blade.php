@extends('layouts.master')

@section('page_title', 'Tạo bài viết mới - Forum')
@section('page_description', 'Chia sẻ kiến thức và ý tưởng với cộng đồng game developer')

@section('content')
<div class="forum-create-page">
    <div class="container">
        <!-- Header -->
        <div class="create-header">
            <div class="header-content">
                <h1>✍️ Tạo bài viết mới</h1>
                <p>Chia sẻ kiến thức, ý tưởng hoặc câu hỏi với cộng đồng</p>
            </div>
            <a href="{{ route('forum.index') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Quay lại Forum
            </a>
        </div>

        <!-- Form -->
        <div class="create-form-container">
            <form action="{{ route('forum.posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form" id="postForm">
                @csrf
                
                <!-- Post Type & Category -->
                <div class="form-section">
                    <h3>Loại bài viết</h3>
                    <div class="post-types">
                        <label class="type-option {{ $selectedType === 'discussion' ? 'active' : '' }}">
                            <input type="radio" name="type" value="discussion" {{ $selectedType === 'discussion' ? 'checked' : '' }}>
                            <div class="type-card">
                                <div class="type-icon">💬</div>
                                <div class="type-info">
                                    <h4>Thảo luận</h4>
                                    <p>Thảo luận chung về game development</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="type-option {{ $selectedType === 'idea' ? 'active' : '' }}">
                            <input type="radio" name="type" value="idea" {{ $selectedType === 'idea' ? 'checked' : '' }}>
                            <div class="type-card">
                                <div class="type-icon">💡</div>
                                <div class="type-info">
                                    <h4>Ý tưởng</h4>
                                    <p>Chia sẻ ý tưởng game mới</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="type-option {{ $selectedType === 'question' ? 'active' : '' }}">
                            <input type="radio" name="type" value="question" {{ $selectedType === 'question' ? 'checked' : '' }}>
                            <div class="type-card">
                                <div class="type-icon">❓</div>
                                <div class="type-info">
                                    <h4>Câu hỏi</h4>
                                    <p>Đặt câu hỏi và tìm giải đáp</p>
                                </div>
                            </div>
                        </label>
                        
                        <label class="type-option {{ $selectedType === 'showcase' ? 'active' : '' }}">
                            <input type="radio" name="type" value="showcase" {{ $selectedType === 'showcase' ? 'checked' : '' }}>
                            <div class="type-card">
                                <div class="type-icon">🎯</div>
                                <div class="type-info">
                                    <h4>Showcase</h4>
                                    <p>Khoe dự án và nhận feedback</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Category -->
                <div class="form-section">
                    <h3>Danh mục</h3>
                    <select name="category_id" required class="form-select">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $selectedCategory === $category->slug ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Title -->
                <div class="form-section">
                    <h3>Tiêu đề <span class="required">*</span></h3>
                    <input type="text" name="title" required class="form-input" 
                           placeholder="Nhập tiêu đề hấp dẫn cho bài viết..." 
                           maxlength="255" id="titleInput">
                    <div class="form-help">
                        <span id="titleCount">0</span>/255 ký tự
                    </div>
                </div>

                <!-- Content -->
                <div class="form-section">
                    <h3>Nội dung <span class="required">*</span></h3>
                    <div class="editor-toolbar">
                        <button type="button" onclick="formatText('bold')" class="editor-btn" title="Bold">
                            <i class="fas fa-bold"></i>
                        </button>
                        <button type="button" onclick="formatText('italic')" class="editor-btn" title="Italic">
                            <i class="fas fa-italic"></i>
                        </button>
                        <button type="button" onclick="formatText('underline')" class="editor-btn" title="Underline">
                            <i class="fas fa-underline"></i>
                        </button>
                        <div class="toolbar-separator"></div>
                        <button type="button" onclick="insertList('ul')" class="editor-btn" title="Bullet List">
                            <i class="fas fa-list-ul"></i>
                        </button>
                        <button type="button" onclick="insertList('ol')" class="editor-btn" title="Numbered List">
                            <i class="fas fa-list-ol"></i>
                        </button>
                        <button type="button" onclick="insertLink()" class="editor-btn" title="Link">
                            <i class="fas fa-link"></i>
                        </button>
                        <button type="button" onclick="insertCode()" class="editor-btn" title="Code">
                            <i class="fas fa-code"></i>
                        </button>
                    </div>
                    <div class="editor-container">
                        <div class="editor" id="contentEditor" contenteditable="true" 
                             data-placeholder="Viết nội dung bài viết tại đây..."></div>
                        <textarea name="content" id="contentTextarea" style="display: none;" required></textarea>
                    </div>
                </div>

                <!-- Tags -->
                <div class="form-section">
                    <h3>Tags</h3>
                    <div class="tags-input-container">
                        <div class="selected-tags" id="selectedTags"></div>
                        <input type="text" id="tagInput" placeholder="Nhập tag và nhấn Enter..." class="tag-input">
                        <input type="hidden" name="tags" id="tagsValue">
                    </div>
                    <div class="popular-tags">
                        <span class="tags-label">Tags phổ biến:</span>
                        @foreach($tags->take(10) as $tag)
                        <button type="button" class="popular-tag" onclick="addTag('{{ $tag->name }}')">
                            {{ $tag->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <!-- Author Info Display -->
                <div class="form-section">
                    <h3>Đăng bởi</h3>
                    <div class="author-display">
                        <div class="user-info">
                            <div class="user-avatar">
                                <span class="avatar-text">{{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}</span>
                            </div>
                            <div class="user-details">
                                <div class="user-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="saveDraft()">
                        <i class="fas fa-save"></i>
                        Lưu nháp
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        Đăng bài viết
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.forum-create-page {
    background: #f8fafc;
    min-height: 100vh;
    padding: 2rem 0;
}

.create-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.header-content p {
    color: #718096;
    font-size: 1.1rem;
}

.btn-back {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    color: #4a5568;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-back:hover {
    border-color: #667eea;
    color: #667eea;
}

.create-form-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.form-section {
    margin-bottom: 2rem;
}

.form-section h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 1rem;
}

.required {
    color: #e53e3e;
}

.post-types {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.type-option {
    cursor: pointer;
}

.type-option input {
    display: none;
}

.type-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s ease;
    background: white;
}

.type-option:hover .type-card {
    border-color: #667eea;
    background: #667eea05;
}

.type-option.active .type-card {
    border-color: #667eea;
    background: #667eea10;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.type-icon {
    font-size: 2rem;
}

.type-info h4 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 0.25rem;
}

.type-info p {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
}

.form-select, .form-input {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s ease;
}

.form-select:focus, .form-input:focus {
    outline: none;
    border-color: #667eea;
}

.form-help {
    margin-top: 0.5rem;
    color: #718096;
    font-size: 0.85rem;
}

.editor-toolbar {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem;
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-bottom: none;
    border-radius: 8px 8px 0 0;
}

.editor-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    border-radius: 4px;
    cursor: pointer;
    color: #4a5568;
    transition: all 0.2s ease;
}

.editor-btn:hover {
    background: #e2e8f0;
    color: #667eea;
}

.toolbar-separator {
    width: 1px;
    height: 20px;
    background: #cbd5e0;
    margin: 0 0.5rem;
}

.editor-container {
    position: relative;
}

.editor {
    min-height: 300px;
    padding: 1rem;
    border: 2px solid #e2e8f0;
    border-top: none;
    border-radius: 0 0 8px 8px;
    font-size: 1rem;
    line-height: 1.6;
    outline: none;
    background: white;
}

.editor:focus {
    border-color: #667eea;
}

.editor[data-placeholder]:empty:before {
    content: attr(data-placeholder);
    color: #a0aec0;
    font-style: italic;
}

.tags-input-container {
    position: relative;
    min-height: 50px;
    padding: 0.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
    background: white;
}

.tags-input-container:focus-within {
    border-color: #667eea;
}

.selected-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.selected-tag {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    background: #667eea;
    color: white;
    border-radius: 12px;
    font-size: 0.85rem;
}

.remove-tag {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0;
    margin-left: 0.25rem;
}

.tag-input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 1rem;
    min-width: 120px;
}

.popular-tags {
    margin-top: 0.75rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.tags-label {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
}

.popular-tag {
    background: #f7fafc;
    border: 1px solid #e2e8f0;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.popular-tag:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
}

.author-display {
    background: #f8fafc;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.user-details {
    flex: 1;
}

.user-name {
    font-weight: 700;
    color: #1a202c;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.user-email {
    color: #718096;
    font-size: 0.9rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: 2px solid transparent;
}

.btn-outline {
    background: white;
    color: #4a5568;
    border-color: #e2e8f0;
}

.btn-outline:hover {
    border-color: #cbd5e0;
    background: #f8fafc;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .create-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .post-types {
        grid-template-columns: 1fr;
    }
    
    .user-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Character counter
document.getElementById('titleInput').addEventListener('input', function() {
    document.getElementById('titleCount').textContent = this.value.length;
});

// Post type selection
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.type-option').forEach(option => {
            option.classList.remove('active');
        });
        this.closest('.type-option').classList.add('active');
    });
});

// Rich text editor
function formatText(command) {
    document.execCommand(command, false, null);
    document.getElementById('contentEditor').focus();
}

function insertList(type) {
    document.execCommand(type === 'ul' ? 'insertUnorderedList' : 'insertOrderedList');
    document.getElementById('contentEditor').focus();
}

function insertLink() {
    const url = prompt('Nhập URL:');
    if (url) {
        document.execCommand('createLink', false, url);
    }
    document.getElementById('contentEditor').focus();
}

function insertCode() {
    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0);
        const code = document.createElement('code');
        code.style.background = '#f1f5f9';
        code.style.padding = '2px 4px';
        code.style.borderRadius = '3px';
        code.style.fontFamily = 'monospace';
        
        try {
            range.surroundContents(code);
        } catch (e) {
            code.textContent = range.toString();
            range.deleteContents();
            range.insertNode(code);
        }
    }
    document.getElementById('contentEditor').focus();
}

// Sync editor content
document.getElementById('contentEditor').addEventListener('input', function() {
    document.getElementById('contentTextarea').value = this.innerHTML;
});

// Tags management
let selectedTags = [];

function updateTagsDisplay() {
    const container = document.getElementById('selectedTags');
    container.innerHTML = selectedTags.map(tag => `
        <span class="selected-tag">
            ${tag}
            <button type="button" class="remove-tag" onclick="removeTag('${tag}')">×</button>
        </span>
    `).join('');
    document.getElementById('tagsValue').value = selectedTags.join(',');
}

function addTag(tagName) {
    tagName = tagName.trim();
    if (tagName && !selectedTags.includes(tagName) && selectedTags.length < 10) {
        selectedTags.push(tagName);
        updateTagsDisplay();
        document.getElementById('tagInput').value = '';
    }
}

function removeTag(tagName) {
    selectedTags = selectedTags.filter(tag => tag !== tagName);
    updateTagsDisplay();
}

document.getElementById('tagInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addTag(this.value);
    }
});

// Form submission
document.getElementById('postForm').addEventListener('submit', function(e) {
    const content = document.getElementById('contentEditor').innerHTML;
    document.getElementById('contentTextarea').value = content;
    
    if (!content.trim()) {
        e.preventDefault();
        alert('Vui lòng nhập nội dung bài viết');
        return;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang đăng...';
    submitBtn.disabled = true;
    
    // Author info is now handled automatically from authenticated user
});

// Auto-save draft functionality
function saveDraft() {
    const formData = new FormData(document.getElementById('postForm'));
    const draftData = {
        title: formData.get('title'),
        content: document.getElementById('contentEditor').innerHTML,
        type: formData.get('type'),
        category_id: formData.get('category_id'),
        tags: selectedTags,
        timestamp: new Date().toISOString()
    };
    
    localStorage.setItem('forum_draft', JSON.stringify(draftData));
    
    // Show success message
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check"></i> Đã lưu';
    setTimeout(() => {
        btn.innerHTML = originalText;
    }, 2000);
}

// Load draft on page load
document.addEventListener('DOMContentLoaded', function() {
    const draft = localStorage.getItem('forum_draft');
    if (draft) {
        const data = JSON.parse(draft);
        const timeDiff = Date.now() - new Date(data.timestamp).getTime();
        
        // Only load draft if it's less than 24 hours old
        if (timeDiff < 24 * 60 * 60 * 1000) {
            if (confirm('Có bản nháp từ lần trước. Bạn muốn khôi phục không?')) {
                document.querySelector('[name="title"]').value = data.title || '';
                document.getElementById('contentEditor').innerHTML = data.content || '';
                document.querySelector(`[name="type"][value="${data.type}"]`).checked = true;
                document.querySelector('[name="category_id"]').value = data.category_id || '';
                
                if (data.tags) {
                    selectedTags = data.tags;
                    updateTagsDisplay();
                }
            }
        }
    }
    
    // Author info is now automatically handled from authenticated user
});
</script>
@endpush
@endsection
