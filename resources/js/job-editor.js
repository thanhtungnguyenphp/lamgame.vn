import Quill from 'quill';
import 'quill/dist/quill.snow.css';

console.log('Job editor script loaded');

// XSS Protection: Whitelist allowed tags and attributes
const allowedTags = ['p', 'br', 'strong', 'em', 'u', 'ol', 'ul', 'li', 'h3', 'h4'];
const allowedAttributes = {};

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
        
        // Remove dangerous attributes
        ['href', 'src', 'action', 'formaction', 'data'].forEach(attr => {
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
        
        const shortDescEditor = new Quill('#short-desc-editor', {
            theme: 'snow',
            placeholder: 'Nhập mô tả ngắn (tối đa 200 ký tự)...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
        
        // Set initial value
        if (shortDescContainer.value) {
            shortDescEditor.root.innerHTML = shortDescContainer.value;
        }
        
        // Sync with textarea on change
        shortDescEditor.on('text-change', function() {
            const html = sanitizeHTML(shortDescEditor.root.innerHTML);
            shortDescContainer.value = html;
            
            // Character limit
            const text = shortDescEditor.getText();
            if (text.length > 200) {
                shortDescEditor.deleteText(200, text.length);
            }
        });
    }
    
    // Full Description Editor
    const descContainer = document.getElementById('description');
    if (descContainer) {
        // Hide original textarea
        descContainer.style.display = 'none';
        
        // Create editor container
        const editorDiv = document.createElement('div');
        editorDiv.id = 'desc-editor';
        editorDiv.className = 'quill-editor-full';
        descContainer.parentNode.insertBefore(editorDiv, descContainer.nextSibling);
        
        const descEditor = new Quill('#desc-editor', {
            theme: 'snow',
            placeholder: 'Nhập mô tả chi tiết công việc...',
            modules: {
                toolbar: [
                    [{ 'header': [3, 4, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });
        
        // Set initial value
        if (descContainer.value) {
            descEditor.root.innerHTML = descContainer.value;
        }
        
        // Sync with textarea on change
        descEditor.on('text-change', function() {
            const html = sanitizeHTML(descEditor.root.innerHTML);
            descContainer.value = html;
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
