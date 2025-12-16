import Quill from 'quill';
import 'quill/dist/quill.snow.css';

console.log('Job editor script loaded');

// XSS Protection: Whitelist allowed tags and attributes
const allowedTags = ['p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'ol', 'ul', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'a'];
const allowedAttributes = {
    'a': ['href', 'title', 'target', 'rel'],
    'p': ['class'],
    'h1': ['class'],
    'h2': ['class'],
    'h3': ['class'],
    'h4': ['class'],
    'h5': ['class'],
    'h6': ['class']
};

// Sanitize HTML to prevent XSS
function sanitizeHTML(html) {
    const div = document.createElement('div');
    div.innerHTML = html;
    
    // Remove script tags
    const scripts = div.querySelectorAll('script');
    scripts.forEach(script => script.remove());
    
    // Remove event handlers
    const allElements = div.querySelectorAll('*');
    allElements.forEach(el => {
        // Remove all event attributes
        Array.from(el.attributes).forEach(attr => {
            if (attr.name.startsWith('on')) {
                el.removeAttribute(attr.name);
            }
        });
        
        // Sanitize links
        if (el.tagName === 'A') {
            const href = el.getAttribute('href');
            if (href && !href.match(/^https?:\/\//i)) {
                el.removeAttribute('href');
            }
            // Add security attributes
            el.setAttribute('rel', 'noopener noreferrer');
            if (el.getAttribute('target') === '_blank') {
                el.setAttribute('target', '_blank');
            }
        }
        
        // Remove dangerous attributes
        ['src', 'action', 'formaction', 'data'].forEach(attr => {
            if (el.hasAttribute(attr)) {
                const value = el.getAttribute(attr);
                if (value && (value.includes('javascript:') || value.includes('data:'))) {
                    el.removeAttribute(attr);
                }
            }
        });
    });
    
    return div.innerHTML;
}

// Character counter
function updateCounter(editor, counterId, maxLength = null) {
    const counter = document.getElementById(counterId);
    if (!counter) return;
    
    const text = editor.getText().trim();
    const length = text.length;
    
    if (maxLength) {
        counter.textContent = `${length}/${maxLength} ký tự`;
        if (length > maxLength) {
            counter.style.color = '#ef4444';
        } else {
            counter.style.color = '#6b7280';
        }
    } else {
        counter.textContent = `${length} ký tự`;
    }
}

// Initialize Quill editors
document.addEventListener('DOMContentLoaded', function() {
    console.log('Job editor: DOMContentLoaded');
    
    // Short Description Editor (Basic)
    const shortDescContainer = document.getElementById('short_description');
    console.log('Short desc container:', shortDescContainer);
    
    if (shortDescContainer) {
        // Hide original textarea
        shortDescContainer.style.display = 'none';
        
        // Create editor container
        const editorDiv = document.createElement('div');
        editorDiv.id = 'short-desc-editor';
        editorDiv.className = 'quill-editor-short';
        shortDescContainer.parentNode.insertBefore(editorDiv, shortDescContainer.nextSibling);
        
        // Create counter
        const counterDiv = document.createElement('div');
        counterDiv.id = 'short-desc-counter';
        counterDiv.className = 'editor-counter';
        editorDiv.parentNode.insertBefore(counterDiv, editorDiv.nextSibling);
        
        const shortDescEditor = new Quill('#short-desc-editor', {
            theme: 'snow',
            placeholder: 'Nhập mô tả ngắn (tối đa 200 ký tự)...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['clean']
                ],
                history: {
                    delay: 1000,
                    maxStack: 50,
                    userOnly: true
                }
            }
        });
        
        // Set initial value
        if (shortDescContainer.value) {
            shortDescEditor.root.innerHTML = shortDescContainer.value;
        }
        
        // Update counter initially
        updateCounter(shortDescEditor, 'short-desc-counter', 200);
        
        // Sync with textarea on change
        shortDescEditor.on('text-change', function() {
            const html = sanitizeHTML(shortDescEditor.root.innerHTML);
            shortDescContainer.value = html;
            
            // Update counter
            updateCounter(shortDescEditor, 'short-desc-counter', 200);
            
            // Character limit
            const text = shortDescEditor.getText();
            if (text.length > 200) {
                shortDescEditor.deleteText(200, text.length);
            }
        });
    }
    
    // Full Description Editor (Enhanced)
    const descContainer = document.getElementById('description');
    if (descContainer) {
        // Hide original textarea
        descContainer.style.display = 'none';
        
        // Create editor container
        const editorDiv = document.createElement('div');
        editorDiv.id = 'desc-editor';
        editorDiv.className = 'quill-editor-full';
        descContainer.parentNode.insertBefore(editorDiv, descContainer.nextSibling);
        
        // Create counter
        const counterDiv = document.createElement('div');
        counterDiv.id = 'desc-counter';
        counterDiv.className = 'editor-counter';
        editorDiv.parentNode.insertBefore(counterDiv, editorDiv.nextSibling);
        
        const descEditor = new Quill('#desc-editor', {
            theme: 'snow',
            placeholder: 'Nhập mô tả chi tiết công việc...',
            modules: {
                toolbar: [
                    // Headers
                    [{ 'header': [1, 2, 3, 4, false] }],
                    
                    // Text formatting
                    ['bold', 'italic', 'underline', 'strike'],
                    
                    // Lists
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    
                    // Alignment
                    [{ 'align': [] }],
                    
                    // Blockquote & Link
                    ['blockquote', 'link'],
                    
                    // Clean
                    ['clean']
                ],
                history: {
                    delay: 1000,
                    maxStack: 50,
                    userOnly: true
                }
            }
        });
        
        // Set initial value
        if (descContainer.value) {
            descEditor.root.innerHTML = descContainer.value;
        }
        
        // Update counter initially
        updateCounter(descEditor, 'desc-counter');
        
        // Sync with textarea on change
        descEditor.on('text-change', function() {
            const html = sanitizeHTML(descEditor.root.innerHTML);
            descContainer.value = html;
            
            // Update counter
            updateCounter(descEditor, 'desc-counter');
        });
    }
    
    // Form submission: Final sanitization
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (shortDescContainer) {
                shortDescContainer.value = sanitizeHTML(shortDescContainer.value);
            }
            if (descContainer) {
                descContainer.value = sanitizeHTML(descContainer.value);
            }
        });
    }
});
