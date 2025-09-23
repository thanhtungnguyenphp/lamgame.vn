@extends('layouts.master')

@section('page_title', 'Quên mật khẩu - LAMGAME')
@section('page_description', 'Khôi phục mật khẩu tài khoản LAMGAME của bạn.')

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
            <h1>🔒 Quên mật khẩu</h1>
            <p>Nhập email để nhận link khôi phục mật khẩu</p>
        </div>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <form id="forgotForm" action="{{ route('auth.forgot-password') }}" method="POST">
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

            <button type="submit" class="btn-primary" id="forgotBtn">
                Gửi link khôi phục
            </button>
        </form>

        <div class="auth-links">
            <p><a href="{{ route('auth.login') }}">← Quay lại đăng nhập</a></p>
            <p>Chưa có tài khoản? <a href="{{ route('auth.register') }}">Đăng ký ngay</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const forgotBtn = document.getElementById('forgotBtn');

    forgotForm.addEventListener('submit', function(e) {
        forgotBtn.disabled = true;
        forgotBtn.textContent = 'Đang gửi...';
        
        // Re-enable button after 10 seconds if form hasn't been submitted
        setTimeout(() => {
            if (forgotBtn.disabled) {
                forgotBtn.disabled = false;
                forgotBtn.textContent = 'Gửi link khôi phục';
            }
        }, 10000);
    });
});
</script>
@endpush