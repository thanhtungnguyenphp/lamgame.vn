@extends('layouts.master')

@section('page_title', 'Đăng ký - LAMGAME')
@section('page_description', 'Tạo tài khoản LAMGAME để tham gia cộng đồng lập trình game và truy cập các khóa học chất lượng.')

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
    
    .btn-primary {
        width: 100%;
        background: linear-gradient(135deg, #6a4c93, #8b6bb1);
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(106, 76, 147, 0.3);
    }
    
    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .auth-links {
        text-align: center;
        margin-top: 2rem;
    }
    
    .auth-links a {
        color: #6a4c93;
        text-decoration: none;
        font-weight: 500;
    }
    
    .auth-links a:hover {
        text-decoration: underline;
    }
    
    .divider {
        text-align: center;
        margin: 1.5rem 0;
        color: #666;
    }
    
    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    
    .strength-weak { color: #e74c3c; }
    .strength-medium { color: #f39c12; }
    .strength-strong { color: #27ae60; }
    
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
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>🚀 Đăng ký</h1>
            <p>Tham gia cộng đồng LAMGAME ngay hôm nay!</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form id="registerForm" action="{{ route('auth.register') }}" method="POST">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">Họ</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           class="form-control {{ $errors->has('first_name') ? 'error' : '' }}"
                           value="{{ old('first_name') }}"
                           placeholder="Nguyễn"
                           required>
                    @if($errors->has('first_name'))
                        <span class="error-message">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="last_name">Tên</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           class="form-control {{ $errors->has('last_name') ? 'error' : '' }}"
                           value="{{ old('last_name') }}"
                           placeholder="Văn A"
                           required>
                    @if($errors->has('last_name'))
                        <span class="error-message">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email') }}"
                       placeholder="nhap@email.com"
                       required>
                @if($errors->has('email'))
                    <span class="error-message">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="Tối thiểu 6 ký tự"
                       required>
                <div id="passwordStrength" class="password-strength"></div>
                @if($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       class="form-control {{ $errors->has('password_confirmation') ? 'error' : '' }}"
                       placeholder="Nhập lại mật khẩu"
                       required>
                @if($errors->has('password_confirmation'))
                    <span class="error-message">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <button type="submit" class="btn-primary" id="registerBtn">
                Tạo tài khoản
            </button>
        </form>

        <div class="divider">hoặc</div>

        <div class="auth-links">
            <p>Đã có tài khoản? <a href="{{ route('auth.login') }}">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerBtn');
    const passwordField = document.getElementById('password');
    const passwordConfirmField = document.getElementById('password_confirmation');
    const strengthIndicator = document.getElementById('passwordStrength');

    // Form submission handling
    registerForm.addEventListener('submit', function(e) {
        registerBtn.disabled = true;
        registerBtn.textContent = 'Đang tạo tài khoản...';
        
        // Re-enable button after 10 seconds if form hasn't been submitted
        setTimeout(() => {
            if (registerBtn.disabled) {
                registerBtn.disabled = false;
                registerBtn.textContent = 'Tạo tài khoản';
            }
        }, 10000);
    });

    // Password strength checking
    passwordField.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        strengthIndicator.textContent = strength.message;
        strengthIndicator.className = `password-strength ${strength.class}`;
    });

    // Password confirmation matching
    passwordConfirmField.addEventListener('input', function() {
        const password = passwordField.value;
        const confirmPassword = this.value;
        
        if (confirmPassword && password !== confirmPassword) {
            this.classList.add('error');
        } else {
            this.classList.remove('error');
        }
    });

    function checkPasswordStrength(password) {
        if (password.length < 6) {
            return { message: 'Mật khẩu quá ngắn', class: 'strength-weak' };
        }
        
        let score = 0;
        
        // Length
        if (password.length >= 8) score += 1;
        if (password.length >= 12) score += 1;
        
        // Character types
        if (/[a-z]/.test(password)) score += 1;
        if (/[A-Z]/.test(password)) score += 1;
        if (/[0-9]/.test(password)) score += 1;
        if (/[^A-Za-z0-9]/.test(password)) score += 1;
        
        if (score < 3) {
            return { message: 'Mật khẩu yếu', class: 'strength-weak' };
        } else if (score < 5) {
            return { message: 'Mật khẩu trung bình', class: 'strength-medium' };
        } else {
            return { message: 'Mật khẩu mạnh', class: 'strength-strong' };
        }
    }

    // Show/hide password functionality for both fields
    function addPasswordToggle(fieldId) {
        const field = document.getElementById(fieldId);
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.innerHTML = '👁️';
        toggleButton.style.cssText = 'position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;';
        
        field.parentElement.style.position = 'relative';
        field.parentElement.appendChild(toggleButton);
        
        toggleButton.addEventListener('click', function() {
            if (field.type === 'password') {
                field.type = 'text';
                toggleButton.innerHTML = '🙈';
            } else {
                field.type = 'password';
                toggleButton.innerHTML = '👁️';
            }
        });
    }
    
    addPasswordToggle('password');
    addPasswordToggle('password_confirmation');
});
</script>
@endpush