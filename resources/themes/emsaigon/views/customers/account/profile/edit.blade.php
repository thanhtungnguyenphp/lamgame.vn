<x-layouts.account>
    <x-slot:title>Chỉnh sửa thông tin</x-slot>

    <div class="edit-profile-container">
        <div class="edit-header">
            <div>
                <h1 class="edit-title">Chỉnh sửa thông tin cá nhân</h1>
                <p class="edit-subtitle">Cập nhật thông tin của bạn</p>
            </div>
            <a href="{{ route('shop.customers.account.profile.index') }}" class="btn-back">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Quay lại
            </a>
        </div>

        <form method="POST" action="{{ route('shop.customers.account.profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            @if ($errors->any())
                <div class="alert alert-danger" style="background: #fee; border: 1px solid #fcc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li style="color: #c00;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="background: #efe; border: 1px solid #cfc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #060;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning" style="background: #ffc; border: 1px solid #fc6; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #630;">
                    {{ session('warning') }}
                </div>
            @endif
            
            <!-- Basic Info -->
            <div class="form-card">
                <h2 class="form-section-title">Thông tin cơ bản</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Họ *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" required class="form-input" placeholder="Nhập họ">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tên *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" required class="form-input" placeholder="Nhập tên">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <select name="gender" class="form-input">
                            <option value="">Chọn giới tính</option>
                            <option value="Male" {{ old('gender', $customer->gender) == 'Male' ? 'selected' : '' }}>Nam</option>
                            <option value="Female" {{ old('gender', $customer->gender) == 'Female' ? 'selected' : '' }}>Nữ</option>
                            <option value="Other" {{ old('gender', $customer->gender) == 'Other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ngày sinh</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $customer->date_of_birth) }}" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="form-card">
                <h2 class="form-section-title">Thông tin liên hệ</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $customer->email) }}" required class="form-input" placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="form-input" placeholder="0912345678">
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="form-card">
                <h2 class="form-section-title">Đổi mật khẩu</h2>
                <p class="form-section-desc">Để trống nếu không muốn thay đổi mật khẩu</p>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-input" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-input" placeholder="••••••••">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-input" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="form-card">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="subscribed_to_news_letter" value="1" {{ $customer->subscribed_to_news_letter ? 'checked' : '' }}>
                    <span class="checkbox-label">Đăng ký nhận bản tin</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('shop.customers.account.profile.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-save">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .edit-profile-container { max-width: 100%; }
        .edit-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .edit-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0; }
        .edit-subtitle { color: #6b7280; margin: 0.25rem 0 0 0; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: white; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .btn-back:hover { background: #f9fafb; border-color: #d1d5db; }
        .form-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .form-section-title { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0; }
        .form-section-desc { font-size: 0.875rem; color: #6b7280; margin: 0 0 1.5rem 0; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-label { font-size: 0.875rem; font-weight: 500; color: #374151; margin: 0; }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: #2c5f41; box-shadow: 0 0 0 3px rgba(44,95,65,0.1); }
        .checkbox-wrapper { display: flex; align-items: center; gap: 0.75rem; }
        .checkbox-label { font-size: 0.875rem; color: #374151; margin: 0; }
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; }
        .btn-cancel { padding: 0.75rem 1.5rem; background: white; color: #6b7280; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .btn-cancel:hover { background: #f9fafb; }
        .btn-save { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-save:hover { background: #1e4530; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,95,65,0.2); }
        .btn-save:active { transform: translateY(0); }
        @media (max-width: 768px) {
            .edit-header { flex-direction: column; align-items: flex-start; }
            .edit-title { font-size: 1.5rem; }
            .form-card { padding: 1.5rem; }
            .form-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column-reverse; }
            .btn-cancel, .btn-save { width: 100%; justify-content: center; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.querySelector('.btn-save');
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...', {
                        action: form.action,
                        method: form.method,
                        data: new FormData(form)
                    });
                    
                    // Disable button to prevent double submit
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span>Đang lưu...</span>';
                    }
                });
            }
            
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    console.log('Submit button clicked');
                });
            }
        });
    </script>
    @endpush
</x-layouts.account>
