// Job Detail Modal Functions

window.getJobIdFromPage = function() {
    var meta = document.querySelector('meta[name="job-id"]');
    if (meta) {
        var id = parseInt(meta.getAttribute('content'));
        if (!isNaN(id)) return id;
    }
    if (typeof window.currentJobId !== 'undefined') return window.currentJobId;
    return null;
};

window.getJobId = window.getJobIdFromPage;

window.openApplyModal = function() {
    var modal = document.getElementById('applyModal');
    if (!modal) return;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    if (typeof window.trackEvent === 'function') {
        window.trackEvent('job_application_modal_open', {
            'event_category': 'jobs',
            'job_id': getJobIdFromPage(),
            'value': 1
        });
    }
    setTimeout(function() {
        if (typeof autoFillFormData === 'function') autoFillFormData();
    }, 100);
};

window.closeApplyModal = function() {
    var modal = document.getElementById('applyModal');
    if (!modal) return;
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
};

function showMessage(message, type) {
    type = type || 'info';
    var el = document.createElement('div');
    el.style.cssText = 'position:fixed;top:20px;right:20px;background:' + (type === 'success' ? '#10b981' : type === 'error' ? '#dc2626' : '#667eea') + ';color:white;padding:12px 20px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:10001;font-weight:500;max-width:350px;font-size:14px;';
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(function() { if (el.parentNode) el.parentNode.removeChild(el); }, 3500);
}

window.showToastMessage = showMessage;
window.showErrorMessage = function(msg) { showMessage(msg, 'error'); };

window.toggleSaveJob = function(button) {
    var icon = button.querySelector('i');
    var text = button.querySelector('span');
    if (icon.classList.contains('fa-heart-o')) {
        icon.classList.remove('fa-heart-o');
        icon.classList.add('fa-heart');
        button.classList.add('saved');
        if (text) text.textContent = 'Đã lưu';
        showMessage('Đã lưu việc làm vào danh sách yêu thích!', 'success');
    } else {
        icon.classList.remove('fa-heart');
        icon.classList.add('fa-heart-o');
        button.classList.remove('saved');
        if (text) text.textContent = 'Lưu việc làm';
        showMessage('Đã xóa khỏi danh sách yêu thích!', 'info');
    }
};

window.autoFillFormData = function() {
    if (window.isLoggedIn && window.customerData) {
        var c = window.customerData;
        var nameField = document.getElementById('full_name');
        var emailField = document.getElementById('email');
        var phoneField = document.getElementById('phone');
        if (nameField && c.full_name) { nameField.value = c.full_name; nameField.readOnly = true; nameField.style.backgroundColor = '#f8f9fa'; }
        if (emailField && c.email) { emailField.value = c.email; emailField.readOnly = true; emailField.style.backgroundColor = '#f8f9fa'; }
        if (phoneField && c.phone) { phoneField.value = c.phone; }
    }
};

window.showFieldError = function(fieldId, message) {
    var field = document.getElementById(fieldId);
    if (!field) return;
    field.classList.add('error');
    var errorEl = field.parentNode.querySelector('.field-error');
    if (!errorEl) {
        errorEl = document.createElement('div');
        errorEl.className = 'field-error';
        errorEl.style.cssText = 'color:#dc2626;font-size:12px;margin-top:4px;';
        field.parentNode.appendChild(errorEl);
    }
    errorEl.textContent = message;
};

window.clearFieldError = function(fieldId) {
    var field = document.getElementById(fieldId);
    if (!field) return;
    field.classList.remove('error');
    var errorEl = field.parentNode.querySelector('.field-error');
    if (errorEl) errorEl.textContent = '';
};

window.clearAllFormErrors = function() {
    document.querySelectorAll('.field-error').forEach(function(el) { el.textContent = ''; });
    document.querySelectorAll('input, textarea').forEach(function(el) { el.classList.remove('error'); });
};

window.validateFormBeforeSubmit = function() {
    var valid = true;
    var fullName = document.getElementById('full_name').value.trim();
    var email = document.getElementById('email').value.trim();
    var phone = document.getElementById('phone').value.trim();
    var cv = document.getElementById('cv').files[0];

    if (!fullName || fullName.length < 2) { showFieldError('full_name', 'Vui lòng nhập họ và tên (ít nhất 2 ký tự)'); valid = false; }
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showFieldError('email', 'Email không đúng định dạng'); valid = false; }
    if (!phone) { showFieldError('phone', 'Vui lòng nhập số điện thoại'); valid = false; }
    if (cv) {
        var allowed = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (allowed.indexOf(cv.type) === -1) { showFieldError('cv', 'Chỉ chấp nhận file PDF, DOC hoặc DOCX'); valid = false; }
        else if (cv.size > 5 * 1024 * 1024) { showFieldError('cv', 'Kích thước file không được vượt quá 5MB'); valid = false; }
    }
    return valid;
};

window.handleFileSelection = function(file) {
    var fileName = document.getElementById('fileName');
    if (!file || !fileName) return;
    var allowed = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (allowed.indexOf(file.type) === -1) { showFieldError('cv', 'Chỉ chấp nhận file PDF, DOC hoặc DOCX'); return; }
    if (file.size > 5 * 1024 * 1024) { showFieldError('cv', 'File không được vượt quá 5MB'); return; }
    clearFieldError('cv');
    var sizeMB = (file.size / 1024 / 1024).toFixed(2);
    fileName.innerHTML = '<div style="color:#059669;font-weight:600;"><i class="fa fa-check-circle"></i> ' + file.name + ' (' + sizeMB + ' MB)</div>';
    fileName.style.display = 'block';
};

window.setFormLoadingState = function(loading) {
    var btn = document.querySelector('.btn-submit');
    var form = document.getElementById('applyForm');
    if (loading) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang gửi...';
        form.style.opacity = '0.7';
        form.style.pointerEvents = 'none';
    } else {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-paper-plane"></i> Gửi hồ sơ';
        form.style.opacity = '1';
        form.style.pointerEvents = 'auto';
    }
};

window.showSuccessMessage = function(data) {
    var modalBody = document.querySelector('.modal-body');
    var codeHtml = data.application_code ? '<p style="background:#f0f9ff;border:1px solid #0ea5e9;border-radius:6px;padding:10px;margin:10px 0;font-size:14px;"><strong>Mã đơn:</strong> ' + data.application_code + '</p>' : '';
    modalBody.innerHTML = '<div style="text-align:center;padding:20px;"><div style="font-size:48px;margin-bottom:15px;">✅</div><h4 style="color:#059669;">Hồ sơ đã được gửi thành công!</h4><p style="color:#6b7280;">' + (data.message || '') + '</p>' + codeHtml + '</div>';
};

window.resetForm = function() {
    var form = document.getElementById('applyForm');
    var fileName = document.getElementById('fileName');
    if (form) form.reset();
    if (fileName) fileName.style.display = 'none';
    clearAllFormErrors();
};

window.displayValidationErrors = function(errors) {
    // Map backend field names → frontend field IDs
    var fieldMap = { 'applicant_name': 'full_name', 'applicant_email': 'email', 'applicant_phone': 'phone', 'resume': 'cv' };
    Object.keys(errors).forEach(function(field) {
        var msgs = Array.isArray(errors[field]) ? errors[field] : [errors[field]];
        var frontendField = fieldMap[field] || field;
        if (msgs.length > 0) showFieldError(frontendField, msgs[0]);
    });
};

// Main init
document.addEventListener('DOMContentLoaded', function() {
    if (window.jobModalDOMInitialized) return;
    window.jobModalDOMInitialized = true;

    // Form submit
    var applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (window.formSubmitting) return;
            window.formSubmitting = true;
            try { await handleFormSubmission(this); } finally { window.formSubmitting = false; }
        });
    }

    // ESC to close
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeApplyModal(); });

    // File input change
    var cvInput = document.getElementById('cv');
    if (cvInput) {
        cvInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) handleFileSelection(e.target.files[0]);
        });
    }

    // Drag & drop
    var uploadArea = document.querySelector('.file-upload-area');
    if (uploadArea && cvInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(evt) {
            uploadArea.addEventListener(evt, function(e) { e.preventDefault(); e.stopPropagation(); });
        });
        uploadArea.addEventListener('drop', function(e) {
            var files = e.dataTransfer.files;
            if (files.length > 0) { cvInput.files = files; handleFileSelection(files[0]); }
        });
        uploadArea.addEventListener('click', function(e) { if (e.target !== cvInput) cvInput.click(); });
    }

    async function handleFormSubmission(form) {
        clearAllFormErrors();
        if (!validateFormBeforeSubmit()) return;
        setFormLoadingState(true);

        try {
            var jobId = getJobIdFromPage();
            if (!jobId) throw new Error('Không tìm thấy Job ID');

            // Build FormData with correct backend field names
            var formData = new FormData();
            formData.append('applicant_name', document.getElementById('full_name').value.trim());
            formData.append('applicant_email', document.getElementById('email').value.trim());
            formData.append('applicant_phone', document.getElementById('phone').value.trim());

            var cvFile = document.getElementById('cv').files[0];
            if (cvFile) formData.append('resume', cvFile);

            var coverLetter = document.getElementById('cover_letter').value.trim();
            if (coverLetter) formData.append('cover_letter', coverLetter);

            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            var response = await fetch('/api/v2/jobs/' + jobId + '/apply', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            var result = await response.json();

            if (response.ok) {
                showSuccessMessage(result);
                if (typeof window.trackEvent === 'function') {
                    window.trackEvent('job_application_success', { 'event_category': 'jobs', 'job_id': jobId, 'application_code': result.application_code });
                }
                setTimeout(function() { closeApplyModal(); resetForm(); }, 2500);
            } else if (response.status === 422 && result.errors) {
                displayValidationErrors(result.errors);
            } else {
                showErrorMessage(result.message || 'Có lỗi xảy ra khi gửi hồ sơ.');
            }
        } catch (error) {
            showErrorMessage('Không thể kết nối đến server. Vui lòng thử lại.');
        } finally {
            setFormLoadingState(false);
        }
    }
});
