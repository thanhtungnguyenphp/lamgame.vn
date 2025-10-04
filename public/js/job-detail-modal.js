// Job Detail Modal Functions - Standalone JavaScript File

// Define modal functions immediately - available for onclick
window.openApplyModal = function() {
    const modal = document.getElementById('applyModal');
    
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Auto-fill form if user is logged in (with delay for DOM)
    setTimeout(() => {
        if (typeof autoFillFormData === 'function') {
            autoFillFormData();
        }
    }, 100);
};

window.closeApplyModal = function() {
    const modal = document.getElementById('applyModal');
    
    if (!modal) {
        return;
    }
    
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
};

// Show message function
function showMessage(message, type = 'info') {
    const messageEl = document.createElement('div');
    messageEl.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#667eea'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        font-weight: 500;
        animation: slideIn 0.3s ease;
    `;
    messageEl.textContent = message;
    
    // Add animation styles
    if (!document.querySelector('#messageStyles')) {
        const style = document.createElement('style');
        style.id = 'messageStyles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(messageEl);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        messageEl.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (messageEl.parentNode) {
                messageEl.parentNode.removeChild(messageEl);
            }
        }, 300);
    }, 3000);
}

// Toggle save job functionality
window.toggleSaveJob = function(button) {
    const icon = button.querySelector('i');
    const text = button.querySelector('span');
    
    if (icon.classList.contains('fa-heart-o')) {
        // Save job
        icon.classList.remove('fa-heart-o');
        icon.classList.add('fa-heart');
        button.classList.add('saved');
        if (text) text.textContent = 'Đã lưu';
        
        // Show success message
        showMessage('Đã lưu việc làm vào danh sách yêu thích!', 'success');
    } else {
        // Unsave job
        icon.classList.remove('fa-heart');
        icon.classList.add('fa-heart-o');
        button.classList.remove('saved');
        if (text) text.textContent = 'Lưu việc làm';
        
        showMessage('Đã xóa khỏi danh sách yêu thích!', 'info');
    }
};

// Auto-fill form data function
window.autoFillFormData = function() {
    if (window.isLoggedIn && window.customerData) {
        const customer = window.customerData;
        
        // Fill form fields
        const fullNameField = document.getElementById('full_name');
        const emailField = document.getElementById('email');
        const phoneField = document.getElementById('phone');
        
        if (fullNameField && customer.full_name) {
            fullNameField.value = customer.full_name;
            fullNameField.style.backgroundColor = '#f8f9fa';
            fullNameField.style.borderColor = '#e2e8f0';
            fullNameField.title = 'Thông tin từ tài khoản đã đăng nhập';
            fullNameField.readOnly = true;
        }
        
        if (emailField && customer.email) {
            emailField.value = customer.email;
            emailField.style.backgroundColor = '#f8f9fa';
            emailField.style.borderColor = '#e2e8f0';
            emailField.title = 'Thông tin từ tài khoản đã đăng nhập';
            emailField.readOnly = true;
        }
        
        if (phoneField && customer.phone) {
            phoneField.value = customer.phone;
            phoneField.style.borderColor = '#10b981';
            phoneField.title = 'Thông tin từ tài khoản đã đăng nhập (có thể chỉnh sửa)';
        }
        
        // Clear any previous validation errors since we have valid data
        if (typeof clearFieldError === 'function') {
            clearFieldError('full_name');
            clearFieldError('email');
            if (customer.phone) {
                clearFieldError('phone');
            }
        }
        
        // Show a subtle indication that data has been auto-filled
        showAutoFillNotification();
    }
};

function showAutoFillNotification() {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #10b981;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1001;
        font-weight: 500;
        font-size: 14px;
    `;
    notification.innerHTML = '<i class="fa fa-check-circle"></i> Đã tự động điền thông tin từ tài khoản';
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 3000);
}

// Form validation functions
window.validateFormBeforeSubmit = function() {
    let isValid = true;
    
    // Get form values  
    const fullName = document.getElementById('full_name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const cv = document.getElementById('cv').files[0];
    
    // Validate full name
    if (!fullName) {
        showFieldError('full_name', 'Vui lòng nhập họ và tên');
        isValid = false;
    } else if (fullName.length < 2) {
        showFieldError('full_name', 'Họ và tên phải có ít nhất 2 ký tự');
        isValid = false;
    } else if (!/^[\p{L}\s\-\.\']+$/u.test(fullName)) {
        showFieldError('full_name', 'Họ và tên chỉ được chứa chữ cái, khoảng trắng, dấu gạch ngang và dấu chấm');
        isValid = false;
    }
    
    // Validate email
    if (!email) {
        showFieldError('email', 'Vui lòng nhập email');
        isValid = false;
    } else if (!isValidEmail(email)) {
        showFieldError('email', 'Email không đúng định dạng');
        isValid = false;
    }
    
    // Validate phone
    if (!phone) {
        showFieldError('phone', 'Vui lòng nhập số điện thoại');
        isValid = false;
    } else if (!isValidVietnamesePhone(phone)) {
        showFieldError('phone', 'Số điện thoại không đúng định dạng Việt Nam');
        isValid = false;
    }
    
    // Validate CV file
    if (!cv) {
        showFieldError('cv', 'Vui lòng chọn file CV');
        isValid = false;
    } else {
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!allowedTypes.includes(cv.type)) {
            showFieldError('cv', 'Chỉ chấp nhận file PDF, DOC hoặc DOCX');
            isValid = false;
        } else if (cv.size > maxSize) {
            showFieldError('cv', 'Kích thước file không được vượt quá 5MB');
            isValid = false;
        } else if (cv.size === 0) {
            showFieldError('cv', 'File CV không được để trống');
            isValid = false;
        }
    }
    
    // Scroll to first error if any
    if (!isValid) {
        const firstError = document.querySelector('.field-error:not(:empty)');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
    
    return isValid;
};

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isValidVietnamesePhone(phone) {
    // Clean phone number
    phone = phone.replace(/[\s\-\.\(\)]/g, '');
    // Convert +84 to 0 for validation
    if (phone.startsWith('+84')) {
        phone = '0' + phone.substring(3);
    } else if (phone.startsWith('84') && phone.length >= 10) {
        phone = '0' + phone.substring(2);
    }
    return /^0(3[2-9]|5[6|8|9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$/.test(phone);
}

window.handleFileSelection = function(file) {
    const fileName = document.getElementById('fileName');
    
    if (file) {
        // Validate file type and size
        const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!allowedTypes.includes(file.type)) {
            showFieldError('cv', 'Chỉ chấp nhận file PDF, DOC hoặc DOCX');
            return;
        }
        
        if (file.size > maxSize) {
            showFieldError('cv', 'Kích thước file không được vượt quá 5MB');
            return;
        }
        
        if (file.size === 0) {
            showFieldError('cv', 'File CV không được để trống');
            return;
        }
        
        // Clear any previous errors
        clearFieldError('cv');
        
        // Show file info
        fileName.innerHTML = `
            <div style="color: #059669; font-weight: 600;">
                <i class="fas fa-check-circle"></i> Đã chọn: ${file.name}
            </div>
            <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">
                Kích thước: ${formatFileSize(file.size)} | Loại: ${getFileExtension(file.name).toUpperCase()}
            </div>
        `;
        fileName.style.display = 'block';
    }
};

function formatFileSize(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getFileExtension(filename) {
    return filename.split('.').pop() || '';
}

window.showFieldError = function(fieldId, message) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('error');
        
        // Find or create error element
        let errorEl = field.parentNode.querySelector('.field-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'field-error';
            field.parentNode.appendChild(errorEl);
        }
        
        errorEl.textContent = message;
        errorEl.style.color = '#dc2626';
        errorEl.style.fontSize = '12px';
        errorEl.style.marginTop = '4px';
    }
};

window.clearFieldError = function(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('error');
        const errorEl = field.parentNode.querySelector('.field-error');
        if (errorEl) {
            errorEl.textContent = '';
        }
    }
};

window.clearAllFormErrors = function() {
    document.querySelectorAll('.field-error').forEach(el => el.textContent = '');
    document.querySelectorAll('input, textarea, select').forEach(el => el.classList.remove('error'));
};

window.displayValidationErrors = function(errors) {
    Object.keys(errors).forEach(field => {
        const messages = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
        if (messages.length > 0) {
            showFieldError(field, messages[0]);
        }
    });
};

window.setFormLoadingState = function(loading) {
    const submitBtn = document.querySelector('.btn-submit');
    const form = document.getElementById('applyForm');
    
    if (loading) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang gửi...';
        form.style.opacity = '0.7';
        form.style.pointerEvents = 'none';
    } else {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Gửi hồ sơ ứng tuyển';
        form.style.opacity = '1';
        form.style.pointerEvents = 'auto';
    }
};

window.showSuccessMessage = function(result) {
    const message = `
        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 48px; margin-bottom: 15px;">✅</div>
            <h4 style="color: #059669; margin: 0 0 10px 0;">Hồ sơ đã được gửi thành công!</h4>
            <p style="color: #6b7280; margin: 0 0 15px 0;">${result.message || 'Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất.'}</p>
            ${result.data?.application_code ? `<p style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 6px; padding: 10px; margin: 10px 0; font-size: 14px;"><strong>Mã đơn:</strong> ${result.data.application_code}</p>` : ''}
            <p style="font-size: 12px; color: #9ca3af;">Modal sẽ đóng tự động sau 2 giây...</p>
        </div>
    `;
    
    const modalBody = document.querySelector('.modal-body');
    modalBody.innerHTML = message;
};

window.showErrorMessage = function(message) {
    showToastMessage(message, 'error');
};

window.resetForm = function() {
    const form = document.getElementById('applyForm');
    const fileName = document.getElementById('fileName');
    
    form.reset();
    fileName.style.display = 'none';
    clearAllFormErrors();
};

window.showToastMessage = function(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? '#dc2626' : type === 'success' ? '#10b981' : '#667eea'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1001;
        font-weight: 500;
        animation: slideIn 0.3s ease;
        max-width: 300px;
        font-size: 14px;
    `;
    toast.innerHTML = `<i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i> ${message}`;
    
    // Add animation styles if not already present
    if (!document.querySelector('#toastStyles')) {
        const style = document.createElement('style');
        style.id = 'toastStyles';
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(toast);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 4000);
};

// Initialize Vue.js if available (fallback for when not loaded via CDN)
window.initializeVueApp = function() {
    // Vue app initialization - optional since we're not using complex Vue components
    console.log('Vue app initialization called but not required for job modal');
};

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add click handler for apply buttons
    const applyButtons = document.querySelectorAll('.btn-apply, .btn-apply-bottom');
    
    applyButtons.forEach(button => {
        if (button.tagName.toLowerCase() === 'button') {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openApplyModal();
            });
        }
    });
    
    // Form submission handler
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent any other handlers
            
            // Prevent multiple submissions
            if (window.formSubmitting) {
                console.log('Form already submitting, ignoring duplicate request');
                return;
            }
            window.formSubmitting = true;
            
            try {
                await handleFormSubmission(this);
            } finally {
                window.formSubmitting = false;
            }
        });
    }
    
    // Modal click outside to close
    const modal = document.getElementById('applyModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeApplyModal();
            }
        });
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeApplyModal();
        }
    });
    
    // File upload preview with enhanced functionality
    const cvInput = document.getElementById('cv');
    if (cvInput) {
        cvInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileSelection(e.target.files[0]);
            } else {
                const fileName = document.getElementById('fileName');
                fileName.style.display = 'none';
            }
        });
    }
    
    // Add drag and drop functionality for file upload
    const fileUploadArea = document.querySelector('.file-upload-area');
    const fileInput = document.getElementById('cv');
    
    if (fileUploadArea && fileInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            fileUploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            fileUploadArea.classList.add('drag-over');
        }
        
        function unhighlight() {
            fileUploadArea.classList.remove('drag-over');
        }
        
        fileUploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelection(files[0]);
            }
        }
        
        // Click to upload
        fileUploadArea.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    // Main form submission handler function
    async function handleFormSubmission(form) {
        console.log('Starting form submission');
        
        // Clear previous errors
        clearAllFormErrors();
        
        // Validate form before submission
        if (!validateFormBeforeSubmit()) {
            console.log('Form validation failed');
            return;
        }
        
        // Show loading state
        setFormLoadingState(true);
        
        try {
            // Prepare FormData with file
            const formData = new FormData(form);
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                            document.querySelector('input[name="_token"]')?.value;
            
            // Get job ID from the current page context
            const jobId = getJobIdFromPage();
            if (!jobId) {
                throw new Error('Job ID not found');
            }
            
            console.log(`Submitting application for job ${jobId}`);
            
            // Make API call
            const response = await fetch(`/api/jobs/${jobId}/apply`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const result = await response.json();
            console.log('API Response:', result);
            
            if (response.ok && result.success) {
                // Success - show success message
                showSuccessMessage(result);
                
                // Close modal after short delay
                setTimeout(() => {
                    closeApplyModal();
                    resetForm();
                }, 2000);
                
                // Track success event
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'job_application_success', {
                        'job_id': jobId,
                        'response': result
                    });
                }
                
            } else {
                // Handle validation errors or other errors
                if (result.errors) {
                    displayValidationErrors(result.errors);
                } else {
                    showErrorMessage(result.message || 'Có lỗi xảy ra khi gửi hồ sơ');
                }
                
                // Track error event
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'job_application_error', {
                        'job_id': jobId,
                        'error': result.error || 'unknown'
                    });
                }
            }
            
        } catch (error) {
            console.error('Application submission error:', error);
            
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                showErrorMessage('Không thể kết nối đến server. Vui lòng kiểm tra kết nối internet và thử lại.');
            } else {
                showErrorMessage('Đã xảy ra lỗi không mong muốn. Vui lòng thử lại sau.');
            }
            
            // Track network error
            if (typeof gtag !== 'undefined') {
                gtag('event', 'job_application_network_error', {
                    'error': error.message
                });
            }
        } finally {
            setFormLoadingState(false);
        }
    }
    
    // Helper function to get job ID from current page
    function getJobIdFromPage() {
        // Try multiple ways to get job ID
        const urlParts = window.location.pathname.split('/');
        const jobIdFromUrl = urlParts[urlParts.length - 1];
        
        // Check if it's a number
        if (!isNaN(jobIdFromUrl) && jobIdFromUrl !== '') {
            return parseInt(jobIdFromUrl);
        }
        
        // Try to get from meta tag or data attribute
        const jobIdMeta = document.querySelector('meta[name="job-id"]')?.getAttribute('content');
        if (jobIdMeta && !isNaN(jobIdMeta)) {
            return parseInt(jobIdMeta);
        }
        
        // Try to get from global variable
        if (typeof window.currentJobId !== 'undefined') {
            return window.currentJobId;
        }
        
        console.error('Could not determine job ID');
        return null;
    }
});
