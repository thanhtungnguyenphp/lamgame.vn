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
                <div style="background: rgba(248,113,113,.1); border: 1px solid rgba(248,113,113,.3); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach ($errors->all() as $error)
                            <li style="color: #F87171;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div style="background: rgba(52,211,153,.1); border: 1px solid rgba(52,211,153,.3); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #34D399;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('warning'))
                <div style="background: rgba(251,191,36,.1); border: 1px solid rgba(251,191,36,.3); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #FBBF24;">
                    {{ session('warning') }}
                </div>
            @endif

            <!-- Basic Info -->
            <div class="form-card">
                <h2 class="form-section-title">👤 Thông tin cơ bản</h2>
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
                <h2 class="form-section-title">📞 Thông tin liên hệ</h2>
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

                <label class="checkbox-wrapper" style="margin-top:1.25rem">
                    <input type="checkbox" name="subscribed_to_news_letter" value="1" {{ $customer->subscribed_to_news_letter ? 'checked' : '' }}>
                    <span class="checkbox-label">Đăng ký nhận bản tin</span>
                </label>
            </div>

            <!-- Actions (chỉ lưu thông tin, KHÔNG đụng mật khẩu) -->
            <div class="form-actions">
                <a href="{{ route('shop.customers.account.profile.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-save">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Lưu thông tin
                </button>
            </div>
        </form>

        <!-- ĐỔI MẬT KHẨU — form riêng biệt -->
        <form method="POST" action="{{ route('shop.customers.account.profile.update') }}" style="margin-top:2.5rem">
            @csrf
            {{-- Mang theo thông tin hiện tại để qua được validation required của ProfileRequest --}}
            <input type="hidden" name="first_name" value="{{ $customer->first_name }}">
            <input type="hidden" name="last_name" value="{{ $customer->last_name }}">
            <input type="hidden" name="gender" value="{{ $customer->gender ?: 'Other' }}">
            <input type="hidden" name="email" value="{{ $customer->email }}">
            <input type="hidden" name="phone" value="{{ $customer->phone }}">
            @if($customer->subscribed_to_news_letter)
                <input type="hidden" name="subscribed_to_news_letter" value="1">
            @endif

            <div class="form-card">
                <h2 class="form-section-title">🔒 Đổi mật khẩu</h2>
                <p class="form-section-desc">Chỉ điền khi bạn muốn thay đổi mật khẩu.</p>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="form-input" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-input" placeholder="••••••••" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="form-input" placeholder="••••••••" autocomplete="new-password">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Đổi mật khẩu
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        :root {
            --ef-surface: rgba(255,255,255,.03); --ef-border: rgba(124,92,255,.15);
            --ef-text: #F5F7FA; --ef-text-2: #B7C0D1; --ef-muted: #7A8599;
            --ef-accent: #7C5CFF; --ef-accent-2: #00D1FF; --ef-input-bg: #070B14;
        }
        .edit-profile-container { max-width: 100%; color: var(--ef-text); }
        .edit-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .edit-title { font-size: 1.6rem; font-weight: 800; color: var(--ef-text); margin: 0; }
        .edit-subtitle { color: var(--ef-muted); margin: 0.25rem 0 0 0; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.3rem; background: transparent; color: var(--ef-text-2); border: 1px solid var(--ef-border); border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.2s; }
        .btn-back:hover { background: rgba(124,92,255,.1); color: var(--ef-text); border-color: var(--ef-accent); }
        .form-card { background: var(--ef-surface); border: 1px solid var(--ef-border); border-radius: 14px; padding: 1.75rem; margin-bottom: 1.25rem; }
        .form-section-title {
            font-size: 1.05rem; font-weight: 700; color: var(--ef-text); margin: 0 0 1.25rem 0;
            padding-bottom: 0.6rem; border-bottom: 1px solid var(--ef-border);
        }
        .form-section-desc { font-size: 0.85rem; color: var(--ef-muted); margin: -0.75rem 0 1.25rem 0; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: var(--ef-text-2); margin: 0; }
        .form-input {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--ef-border); border-radius: 10px;
            font-size: 1rem; transition: all 0.2s; background: var(--ef-input-bg); color: var(--ef-text);
        }
        .form-input::placeholder { color: var(--ef-muted); }
        .form-input:focus { outline: none; border-color: var(--ef-accent); box-shadow: 0 0 0 3px rgba(124,92,255,0.15); }
        .form-input option { background: var(--ef-input-bg); color: var(--ef-text); }
        .checkbox-wrapper { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
        .checkbox-wrapper input { width: 18px; height: 18px; accent-color: var(--ef-accent); }
        .checkbox-label { font-size: 0.95rem; color: var(--ef-text-2); margin: 0; }
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; }
        .btn-cancel { padding: 0.75rem 1.5rem; background: transparent; color: var(--ef-text-2); border: 1px solid var(--ef-border); border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.2s; }
        .btn-cancel:hover { background: rgba(124,92,255,.1); color: var(--ef-text); }
        .btn-save { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.75rem; background: linear-gradient(135deg,var(--ef-accent),var(--ef-accent-2)); color: #fff; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; transition: opacity 0.2s; }
        .btn-save:hover { opacity: .9; }
        .btn-save:disabled { opacity: .6; cursor: not-allowed; }
        @media (max-width: 768px) {
            .edit-header { flex-direction: column; align-items: flex-start; }
            .edit-title { font-size: 1.4rem; }
            .form-card { padding: 1.25rem; }
            .form-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column-reverse; }
            .btn-cancel, .btn-save { width: 100%; justify-content: center; }
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Áp cho CẢ 2 form (thông tin + mật khẩu): disable nút khi submit tránh double submit
            document.querySelectorAll('.edit-profile-container form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    const btn = form.querySelector('.btn-save');
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span>Đang lưu...</span>';
                    }
                });
            });
        });
    </script>
    @endpush
</x-layouts.account>
