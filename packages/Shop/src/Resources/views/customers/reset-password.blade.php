@extends('layouts.master')

@section('page_title', 'Đặt lại mật khẩu - LAMGAME')

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
    .auth-header p { color: #666; }
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
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: #6a4c93;
        background: white;
        box-shadow: 0 0 0 3px rgba(106, 76, 147, 0.1);
    }
    .form-control.error { border-color: #e74c3c; }
    .error-message {
        color: #e74c3c;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: block;
    }
    .success-message {
        color: #27ae60;
        text-align: center;
        padding: 0.75rem;
        background: #d4edda;
        border-radius: 8px;
        margin-bottom: 1rem;
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
    .btn-primary:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }
    .auth-links { text-align: center; margin-top: 2rem; }
    .auth-links a { color: #6a4c93; text-decoration: none; font-weight: 500; }
    .auth-links a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>🔑 Đặt lại mật khẩu</h1>
            <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
        </div>

        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="error-message" style="text-align:center;margin-bottom:1rem;padding:0.75rem;background:#f8d7da;border-radius:8px">{{ session('error') }}</div>
        @endif

        <form id="resetForm" action="{{ route('shop.customers.reset_password.store') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="form-control {{ $errors->has('email') ? 'error' : '' }}"
                       value="{{ old('email', $email ?? request('email')) }}"
                       placeholder="nhap@email.com" required>
                @if($errors->has('email'))
                    <span class="error-message">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                       placeholder="Tối thiểu 6 ký tự" required minlength="6">
                @if($errors->has('password'))
                    <span class="error-message">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Nhập lại mật khẩu" required minlength="6">
            </div>

            <button type="submit" class="btn-primary" id="resetBtn">Đặt lại mật khẩu</button>
        </form>

        <div class="auth-links">
            <p><a href="{{ route('shop.customer.session.index') }}">← Quay lại đăng nhập</a></p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('resetForm');
    var btn = document.getElementById('resetBtn');
    form.addEventListener('submit', function() {
        btn.disabled = true;
        btn.textContent = 'Đang xử lý...';
        setTimeout(function() { btn.disabled = false; btn.textContent = 'Đặt lại mật khẩu'; }, 10000);
    });
});
</script>
@endpush
