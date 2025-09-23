@extends('layouts.master')

@section('page_title', 'Đăng nhập - LAMGAME')
@section('page_description', 'Đăng nhập vào tài khoản LAMGAME để truy cập các khóa học lập trình game và tham gia cộng đồng.')

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
        max-width: 400px;
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
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-check input[type="checkbox"] {
        margin: 0;
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
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>🎮 Đăng nhập</h1>
            <p>Chào mừng bạn quay trở lại LAMGAME!</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form id="loginForm" action="{{ route('auth.login') }}" method="POST">
            @csrf
            
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
                       placeholder="Nhập mật khẩu"
                       required>
                @if($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>
            </div>

            <button type="submit" class="btn-primary" id="loginBtn">
                Đăng nhập
            </button>
        </form>

        <div class="divider">hoặc</div>

        <div class="auth-links">
            <p>Chưa có tài khoản? <a href="{{ route('auth.register') }}">Đăng ký ngay</a></p>
            <p><a href="{{ route('auth.forgot-password') }}">Quên mật khẩu?</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');

    loginForm.addEventListener('submit', function(e) {
        loginBtn.disabled = true;
        loginBtn.textContent = 'Đang đăng nhập...';
        
        // Re-enable button after 5 seconds if form hasn't been submitted
        setTimeout(() => {
            if (loginBtn.disabled) {
                loginBtn.disabled = false;
                loginBtn.textContent = 'Đăng nhập';
            }
        }, 5000);
    });

    // Show/hide password functionality
    const togglePassword = document.createElement('button');
    togglePassword.type = 'button';
    togglePassword.innerHTML = '👁️';
    togglePassword.style.cssText = 'position: absolute; right: 10px; top: 50%; transform: translateY(-50%); border: none; background: none; cursor: pointer;';
    
    const passwordField = document.getElementById('password');
    passwordField.parentElement.style.position = 'relative';
    passwordField.parentElement.appendChild(togglePassword);
    
    togglePassword.addEventListener('click', function() {
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            togglePassword.innerHTML = '🙈';
        } else {
            passwordField.type = 'password';
            togglePassword.innerHTML = '👁️';
        }
    });
});
</script>
@endpush