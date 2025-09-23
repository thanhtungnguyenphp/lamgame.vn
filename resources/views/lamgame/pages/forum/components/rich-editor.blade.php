<!-- Rich Text Editor Component -->
<div class="rich-editor-component">
    <div class="editor-toolbar">
        <div class="toolbar-group">
            <button type="button" onclick="formatText('bold')" class="editor-btn" title="Bold (Ctrl+B)" data-command="bold">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" onclick="formatText('italic')" class="editor-btn" title="Italic (Ctrl+I)" data-command="italic">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" onclick="formatText('underline')" class="editor-btn" title="Underline (Ctrl+U)" data-command="underline">
                <i class="fas fa-underline"></i>
            </button>
            <button type="button" onclick="formatText('strikeThrough')" class="editor-btn" title="Strikethrough" data-command="strikeThrough">
                <i class="fas fa-strikethrough"></i>
            </button>
        </div>

        <div class="toolbar-separator"></div>

        <div class="toolbar-group">
            <button type="button" onclick="insertHeading('h2')" class="editor-btn" title="Heading 2" data-command="h2">
                <i class="fas fa-heading"></i>
                <sub>2</sub>
            </button>
            <button type="button" onclick="insertHeading('h3')" class="editor-btn" title="Heading 3" data-command="h3">
                <i class="fas fa-heading"></i>
                <sub>3</sub>
            </button>
        </div>

        <div class="toolbar-separator"></div>

        <div class="toolbar-group">
            <button type="button" onclick="insertList('ul')" class="editor-btn" title="Bullet List" data-command="ul">
                <i class="fas fa-list-ul"></i>
            </button>
            <button type="button" onclick="insertList('ol')" class="editor-btn" title="Numbered List" data-command="ol">
                <i class="fas fa-list-ol"></i>
            </button>
            <button type="button" onclick="changeIndent('outdent')" class="editor-btn" title="Decrease Indent" data-command="outdent">
                <i class="fas fa-outdent"></i>
            </button>
            <button type="button" onclick="changeIndent('indent')" class="editor-btn" title="Increase Indent" data-command="indent">
                <i class="fas fa-indent"></i>
            </button>
        </div>

        <div class="toolbar-separator"></div>

        <div class="toolbar-group">
            <button type="button" onclick="insertLink()" class="editor-btn" title="Insert Link (Ctrl+L)" data-command="link">
                <i class="fas fa-link"></i>
            </button>
            <button type="button" onclick="insertImage()" class="editor-btn" title="Insert Image" data-command="image">
                <i class="fas fa-image"></i>
            </button>
            <button type="button" onclick="insertCode()" class="editor-btn" title="Code Block" data-command="code">
                <i class="fas fa-code"></i>
            </button>
            <button type="button" onclick="insertQuote()" class="editor-btn" title="Quote Block" data-command="quote">
                <i class="fas fa-quote-right"></i>
            </button>
        </div>

        <div class="toolbar-separator"></div>

        <div class="toolbar-group">
            <button type="button" onclick="formatText('removeFormat')" class="editor-btn" title="Clear Formatting" data-command="removeFormat">
                <i class="fas fa-eraser"></i>
            </button>
        </div>

        <div class="toolbar-group toolbar-right">
            <button type="button" onclick="togglePreview()" class="editor-btn" title="Toggle Preview" id="previewToggle">
                <i class="fas fa-eye"></i>
            </button>
            <button type="button" onclick="toggleFullscreen()" class="editor-btn" title="Fullscreen" id="fullscreenToggle">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>

    <div class="editor-container" id="editorContainer">
        <div class="editor-main">
            <div class="editor" id="{{ $editorId ?? 'contentEditor' }}" 
                 contenteditable="true" 
                 data-placeholder="{{ $placeholder ?? 'Viết nội dung tại đây...' }}"
                 data-max-length="{{ $maxLength ?? 10000 }}">
                {!! $content ?? '' !!}
            </div>
            <textarea name="{{ $name ?? 'content' }}" 
                      id="{{ $textareaId ?? 'contentTextarea' }}" 
                      style="display: none;" 
                      {{ $required ?? true ? 'required' : '' }}>{{ $content ?? '' }}</textarea>
        </div>

        <div class="editor-preview" id="editorPreview" style="display: none;">
            <div class="preview-content" id="previewContent"></div>
        </div>

        <div class="editor-status">
            <div class="word-count">
                <span id="wordCount">0</span> từ, 
                <span id="charCount">0</span> ký tự
                <span id="charLimit" style="display: none;">/ {{ $maxLength ?? 10000 }}</span>
            </div>
        </div>
    </div>
</div>

<style>
.rich-editor-component {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    overflow: hidden;
}

.editor-toolbar {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 0.25rem;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.toolbar-right {
    margin-left: auto;
}

.editor-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    background: none;
    border: 1px solid transparent;
    border-radius: 4px;
    cursor: pointer;
    color: #4a5568;
    transition: all 0.2s ease;
    min-width: 32px;
    height: 32px;
    position: relative;
}

.editor-btn:hover {
    background: #e2e8f0;
    color: #667eea;
    border-color: #cbd5e0;
}

.editor-btn.active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.editor-btn sub {
    position: absolute;
    bottom: 2px;
    right: 2px;
    font-size: 8px;
    line-height: 1;
}

.toolbar-separator {
    width: 1px;
    height: 20px;
    background: #cbd5e0;
    margin: 0 0.25rem;
}

.editor-container {
    position: relative;
    min-height: 300px;
    max-height: 600px;
    overflow: hidden;
}

.editor-container.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: 9999;
    max-height: none;
    border-radius: 0;
}

.editor-main {
    display: block;
    height: 100%;
}

.editor {
    min-height: 300px;
    padding: 1rem;
    font-size: 1rem;
    line-height: 1.6;
    outline: none;
    background: white;
    overflow-y: auto;
    max-height: 500px;
}

.editor-container.fullscreen .editor {
    min-height: calc(100vh - 100px);
    max-height: calc(100vh - 100px);
}

.editor:focus {
    background: #fafbfc;
}

.editor[data-placeholder]:empty:before {
    content: attr(data-placeholder);
    color: #a0aec0;
    font-style: italic;
}

.editor-preview {
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    border-left: 1px solid #e2e8f0;
    background: white;
}

.editor-container.preview-mode .editor-main {
    width: 50%;
}

.preview-content {
    padding: 1rem;
    height: 100%;
    overflow-y: auto;
    font-size: 1rem;
    line-height: 1.6;
}

.preview-content h1,
.preview-content h2,
.preview-content h3 {
    color: #1a202c;
    margin: 1em 0 0.5em;
    font-weight: 700;
}

.preview-content h1 { font-size: 1.5em; }
.preview-content h2 { font-size: 1.3em; }
.preview-content h3 { font-size: 1.1em; }

.preview-content p {
    margin: 0.5em 0;
}

.preview-content ul,
.preview-content ol {
    margin: 0.5em 0;
    padding-left: 2em;
}

.preview-content blockquote {
    margin: 1em 0;
    padding: 0.5em 1em;
    border-left: 4px solid #667eea;
    background: #f8fafc;
    font-style: italic;
}

.preview-content code {
    background: #f1f5f9;
    padding: 2px 4px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.preview-content pre {
    background: #2d3748;
    color: #e2e8f0;
    padding: 1rem;
    border-radius: 6px;
    overflow-x: auto;
    margin: 1em 0;
}

.preview-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.editor-status {
    padding: 0.5rem 1rem;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    font-size: 0.85rem;
    color: #718096;
}

.word-count {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .editor-toolbar {
        padding: 0.25rem;
        gap: 0.125rem;
    }
    
    .toolbar-group {
        gap: 0.125rem;
    }
    
    .editor-btn {
        padding: 0.25rem;
        min-width: 28px;
        height: 28px;
    }
    
    .toolbar-separator {
        height: 16px;
        margin: 0 0.125rem;
    }
    
    .editor-preview {
        display: none !important;
    }
    
    .editor-container.preview-mode .editor-main {
        display: none;
    }
    
    .editor-container.preview-mode .editor-preview {
        display: block !important;
        position: static;
        width: 100%;
        border-left: none;
        border-top: 1px solid #e2e8f0;
    }
}
</style>

<script>
// Rich Text Editor Functions
(function() {
    const editorId = '{{ $editorId ?? "contentEditor" }}';
    const textareaId = '{{ $textareaId ?? "contentTextarea" }}';
    const maxLength = {{ $maxLength ?? 10000 }};
    
    let editor, textarea, isPreviewMode = false, isFullscreen = false;
    
    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        editor = document.getElementById(editorId);
        textarea = document.getElementById(textareaId);
        
        if (editor && textarea) {
            initializeEditor();
        }
    });
    
    function initializeEditor() {
        // Sync content on input
        editor.addEventListener('input', syncContent);
        
        // Update word count
        editor.addEventListener('input', updateWordCount);
        
        // Handle keyboard shortcuts
        editor.addEventListener('keydown', handleKeyboardShortcuts);
        
        // Handle paste events
        editor.addEventListener('paste', handlePaste);
        
        // Initialize word count
        updateWordCount();
        
        // Set initial content if any
        if (textarea.value) {
            editor.innerHTML = textarea.value;
            updateWordCount();
        }
    }
    
    function syncContent() {
        textarea.value = editor.innerHTML;
    }
    
    function updateWordCount() {
        const text = editor.innerText || '';
        const words = text.trim() ? text.trim().split(/\s+/).length : 0;
        const chars = text.length;
        
        const wordCountEl = document.getElementById('wordCount');
        const charCountEl = document.getElementById('charCount');
        const charLimitEl = document.getElementById('charLimit');
        
        if (wordCountEl) wordCountEl.textContent = words;
        if (charCountEl) charCountEl.textContent = chars;
        
        // Show character limit if approaching max
        if (charLimitEl && chars > maxLength * 0.8) {
            charLimitEl.style.display = 'inline';
            charLimitEl.style.color = chars > maxLength ? '#e53e3e' : '#f6ad55';
        } else if (charLimitEl) {
            charLimitEl.style.display = 'none';
        }
        
        // Prevent further input if at limit
        if (chars >= maxLength) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0 && selection.toString().length === 0) {
                // Only prevent if not replacing existing text
                return false;
            }
        }
    }
    
    function handleKeyboardShortcuts(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key.toLowerCase()) {
                case 'b':
                    e.preventDefault();
                    formatText('bold');
                    break;
                case 'i':
                    e.preventDefault();
                    formatText('italic');
                    break;
                case 'u':
                    e.preventDefault();
                    formatText('underline');
                    break;
                case 'l':
                    e.preventDefault();
                    insertLink();
                    break;
                case 'enter':
                    if (e.shiftKey) {
                        e.preventDefault();
                        togglePreview();
                    }
                    break;
            }
        }
        
        // Handle Enter key for better formatting
        if (e.key === 'Enter' && !e.shiftKey) {
            const selection = window.getSelection();
            if (selection.rangeCount > 0) {
                const range = selection.getRangeAt(0);
                const parentElement = range.startContainer.nodeType === Node.TEXT_NODE ? 
                    range.startContainer.parentElement : range.startContainer;
                
                // Check if we're in a heading
                if (parentElement.tagName && parentElement.tagName.match(/^H[1-6]$/)) {
                    e.preventDefault();
                    document.execCommand('insertHTML', false, '<br><p><br></p>');
                }
            }
        }
    }
    
    function handlePaste(e) {
        e.preventDefault();
        
        const clipboardData = e.clipboardData || window.clipboardData;
        const pastedData = clipboardData.getData('text/html') || clipboardData.getData('text/plain');
        
        if (pastedData) {
            // Clean pasted content
            const cleanedData = cleanHTML(pastedData);
            document.execCommand('insertHTML', false, cleanedData);
            updateWordCount();
        }
    }
    
    function cleanHTML(html) {
        // Create a temporary element to clean HTML
        const temp = document.createElement('div');
        temp.innerHTML = html;
        
        // Remove unwanted elements
        const unwantedTags = ['script', 'style', 'meta', 'link', 'object', 'embed', 'iframe'];
        unwantedTags.forEach(tag => {
            const elements = temp.querySelectorAll(tag);
            elements.forEach(el => el.remove());
        });
        
        // Clean attributes
        const allElements = temp.querySelectorAll('*');
        allElements.forEach(el => {
            // Keep only essential attributes
            const allowedAttrs = ['href', 'src', 'alt', 'title'];
            const attrs = Array.from(el.attributes);
            attrs.forEach(attr => {
                if (!allowedAttrs.includes(attr.name)) {
                    el.removeAttribute(attr.name);
                }
            });
        });
        
        return temp.innerHTML;
    }
    
    // Export functions to global scope for toolbar buttons
    window.richEditor = {
        formatText: function(command, value = null) {
            editor.focus();
            document.execCommand(command, false, value);
            syncContent();
            updateButtonStates();
        },
        
        insertHeading: function(tag) {
            editor.focus();
            document.execCommand('formatBlock', false, tag);
            syncContent();
            updateButtonStates();
        },
        
        insertList: function(type) {
            editor.focus();
            const command = type === 'ul' ? 'insertUnorderedList' : 'insertOrderedList';
            document.execCommand(command);
            syncContent();
            updateButtonStates();
        },
        
        changeIndent: function(direction) {
            editor.focus();
            document.execCommand(direction);
            syncContent();
        },
        
        insertLink: function() {
            const selection = window.getSelection();
            const selectedText = selection.toString();
            const url = prompt('Nhập URL:', 'https://');
            
            if (url && url !== 'https://') {
                editor.focus();
                if (selectedText) {
                    document.execCommand('createLink', false, url);
                } else {
                    const linkText = prompt('Nhập text hiển thị:', url);
                    document.execCommand('insertHTML', false, `<a href="${url}">${linkText || url}</a>`);
                }
                syncContent();
            }
        },
        
        insertImage: function() {
            const url = prompt('Nhập URL hình ảnh:', 'https://');
            if (url && url !== 'https://') {
                const alt = prompt('Nhập mô tả ảnh (tùy chọn):', '');
                editor.focus();
                document.execCommand('insertHTML', false, `<img src="${url}" alt="${alt}" style="max-width: 100%; height: auto;">`);
                syncContent();
            }
        },
        
        insertCode: function() {
            const selection = window.getSelection();
            const selectedText = selection.toString();
            
            editor.focus();
            if (selectedText) {
                document.execCommand('insertHTML', false, `<code>${selectedText}</code>`);
            } else {
                document.execCommand('insertHTML', false, '<code>code here</code>');
            }
            syncContent();
        },
        
        insertQuote: function() {
            const selection = window.getSelection();
            const selectedText = selection.toString();
            
            editor.focus();
            if (selectedText) {
                document.execCommand('insertHTML', false, `<blockquote>${selectedText}</blockquote>`);
            } else {
                document.execCommand('insertHTML', false, '<blockquote>Quote here</blockquote>');
            }
            syncContent();
        },
        
        togglePreview: function() {
            const container = document.getElementById('editorContainer');
            const preview = document.getElementById('editorPreview');
            const previewContent = document.getElementById('previewContent');
            const toggleBtn = document.getElementById('previewToggle');
            
            if (preview && container && previewContent) {
                isPreviewMode = !isPreviewMode;
                
                if (isPreviewMode) {
                    container.classList.add('preview-mode');
                    preview.style.display = 'block';
                    previewContent.innerHTML = editor.innerHTML;
                    toggleBtn.innerHTML = '<i class="fas fa-edit"></i>';
                    toggleBtn.title = 'Edit Mode';
                } else {
                    container.classList.remove('preview-mode');
                    preview.style.display = 'none';
                    toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
                    toggleBtn.title = 'Preview Mode';
                }
            }
        },
        
        toggleFullscreen: function() {
            const container = document.getElementById('editorContainer');
            const toggleBtn = document.getElementById('fullscreenToggle');
            
            if (container) {
                isFullscreen = !isFullscreen;
                
                if (isFullscreen) {
                    container.classList.add('fullscreen');
                    toggleBtn.innerHTML = '<i class="fas fa-compress"></i>';
                    toggleBtn.title = 'Exit Fullscreen';
                    document.body.style.overflow = 'hidden';
                } else {
                    container.classList.remove('fullscreen');
                    toggleBtn.innerHTML = '<i class="fas fa-expand"></i>';
                    toggleBtn.title = 'Fullscreen';
                    document.body.style.overflow = '';
                }
            }
        }
    };
    
    function updateButtonStates() {
        const buttons = document.querySelectorAll('.editor-btn[data-command]');
        buttons.forEach(btn => {
            const command = btn.getAttribute('data-command');
            try {
                if (document.queryCommandState(command)) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            } catch (e) {
                // Some commands don't support queryCommandState
                btn.classList.remove('active');
            }
        });
    }
    
    // Update button states on selection change
    document.addEventListener('selectionchange', updateButtonStates);
    
})();

// Global functions for backward compatibility
function formatText(command, value) {
    if (window.richEditor) {
        window.richEditor.formatText(command, value);
    }
}

function insertHeading(tag) {
    if (window.richEditor) {
        window.richEditor.insertHeading(tag);
    }
}

function insertList(type) {
    if (window.richEditor) {
        window.richEditor.insertList(type);
    }
}

function changeIndent(direction) {
    if (window.richEditor) {
        window.richEditor.changeIndent(direction);
    }
}

function insertLink() {
    if (window.richEditor) {
        window.richEditor.insertLink();
    }
}

function insertImage() {
    if (window.richEditor) {
        window.richEditor.insertImage();
    }
}

function insertCode() {
    if (window.richEditor) {
        window.richEditor.insertCode();
    }
}

function insertQuote() {
    if (window.richEditor) {
        window.richEditor.insertQuote();
    }
}

function togglePreview() {
    if (window.richEditor) {
        window.richEditor.togglePreview();
    }
}

function toggleFullscreen() {
    if (window.richEditor) {
        window.richEditor.toggleFullscreen();
    }
}
</script>
