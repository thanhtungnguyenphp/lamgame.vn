@extends('layouts.master')

@section('page_title', 'Thông tin cá nhân - LAMGAME')
@section('page_description', 'Quản lý thông tin tài khoản của bạn tại LAMGAME.')

@push('styles')
<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.auth-card {
    background: white;
    padding: 3rem 2rem;
    border-radius: 15px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    width: 100%;
    max-width: 500px;
    margin: 2rem 1rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    color: #6a4c93;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: #666;
    margin-bottom: 0;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    outline: none;
    border-color: #6a4c93;
    background: white;
    box-shadow: 0 0 0 3px rgba(106, 76, 147, 0.1);
}

.form-control.error {
    border-color: #e74c3c;
}

.error-message {
    color: #e74c3c;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: block;
}

.success-message {
    color: #27ae60;
    font-size: 0.875rem;
    margin-bottom: 1rem;
    text-align: center;
    padding: 0.75rem;
    background: #d4edda;
    border-radius: 8px;
}

.btn-primary, .btn-secondary, .btn-outline {
    width: 100%;
    border: none;
    padding: 0.875rem 1.5rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    margin-bottom: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #6a4c93, #8b6bb1);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(106, 76, 147, 0.3);
}

.btn-secondary {
    background: #e74c3c;
    color: white;
}

.btn-secondary:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: #6a4c93;
    border: 2px solid #6a4c93;
}

.btn-outline:hover {
    background: #6a4c93;
    color: white;
}

.divider {
    text-align: center;
    margin: 2rem 0 1rem 0;
    color: #666;
    font-weight: 600;
    position: relative;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #ddd;
    z-index: 1;
}

.divider::after {
    content: attr(data-text);
    background: white;
    padding: 0 1rem;
    position: relative;
    z-index: 2;
}

.account-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    margin-bottom: 0;
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #495057;
}

.info-value {
    color: #6c757d;
}

.status-active, .status-verified {
    color: #28a745;
    font-weight: 600;
}

.status-inactive, .status-unverified {
    color: #dc3545;
    font-weight: 600;
}

.profile-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .auth-container {
        padding: 1rem 0;
    }
    
    .auth-card {
        padding: 2rem 1.5rem;
        margin: 1rem;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .profile-actions {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card" style="max-width: 600px;">
        <div class="auth-header">
            <h1>👤 Thông tin cá nhân</h1>
            <p>Quản lý thông tin tài khoản của bạn</p>
        </div>

        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form id="profileForm" action="{{ route('auth.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">Họ</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           class="form-control @error('first_name') error @enderror"
                           value="{{ old('first_name', $customer->first_name) }}"
                           placeholder="Nguyễn"
                           required>
                    @error('first_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="last_name">Tên</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           class="form-control @error('last_name') error @enderror"
                           value="{{ old('last_name', $customer->last_name) }}"
                           placeholder="Văn A"
                           required>
                    @error('last_name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control @error('email') error @enderror"
                       value="{{ old('email', $customer->email) }}"
                       placeholder="nhap@email.com"
                       required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" 
                       id="phone" 
                       name="phone" 
                       class="form-control @error('phone') error @enderror"
                       value="{{ old('phone', $customer->phone) }}"
                       placeholder="0901234567">
                @error('phone')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary" id="profileBtn">
                Cập nhật thông tin
            </button>
        </form>

        <div class="divider">Thông tin tài khoản</div>

        <div class="account-info">
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value {{ $customer->status ? 'status-active' : 'status-inactive' }}">
                    {{ $customer->status ? '✅ Hoạt động' : '❌ Tạm khóa' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Xác thực email:</span>
                <span class="info-value {{ $customer->is_verified ? 'status-verified' : 'status-unverified' }}">
                    {{ $customer->is_verified ? '✅ Đã xác thực' : '⏳ Chưa xác thực' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Nhóm khách hàng:</span>
                <span class="info-value">{{ $customer->group->name ?? 'Không xác định' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày tham gia:</span>
                <span class="info-value">{{ $customer->created_at->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="profile-actions">
            <a href="{{ route('auth.logout') }}" class="btn-secondary"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                🚪 Đăng xuất
            </a>
            <a href="{{ route('shop.home.index') }}" class="btn-outline">
                🏠 Về trang chủ
            </a>
        </div>

        <!-- Hidden logout form -->
        <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileForm = document.getElementById('profileForm');
    const profileBtn = document.getElementById('profileBtn');

    if (profileForm && profileBtn) {
        profileForm.addEventListener('submit', function(e) {
            profileBtn.disabled = true;
            profileBtn.textContent = 'Đang cập nhật...';
            
            // Re-enable button after 5 seconds if form hasn't been submitted
            setTimeout(() => {
                if (profileBtn.disabled) {
                    profileBtn.disabled = false;
                    profileBtn.textContent = 'Cập nhật thông tin';
                }
            }, 5000);
        });
    }
});
</script>
@endpush
@endsection