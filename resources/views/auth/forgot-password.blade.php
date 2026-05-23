@extends('layouts.master')

@section('page_title', 'Quên mật khẩu - LAMGAME')
@section('page_description', 'Khôi phục mật khẩu tài khoản LAMGAME của bạn.')

@section('content')
<div class="auth-page">
    <div class="auth-page__bg"></div>
    <div class="auth-card">
        <div class="auth-card__header">
            <a href="/" class="auth-logo">LAMGAME<span>.VN</span></a>
            <h1>Quên mật khẩu</h1>
            <p>Nhập email để nhận link khôi phục mật khẩu</p>
        </div>

        @if(session('success'))
        <div class="auth-alert auth-alert--success">✅ {{ session('success') }}</div>
        @endif

        <form id="forgotForm" action="{{ route('auth.forgot-password') }}" method="POST">
            @csrf
            <div class="auth-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="auth-input {{ $errors->has('email') ? 'auth-input--error' : '' }}"
                       value="{{ old('email') }}" placeholder="nhap@email.com" required>
                @if($errors->has('email'))
                <span class="auth-error">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <button type="submit" class="auth-btn" id="forgotBtn">Gửi link khôi phục</button>
        </form>

        <div class="auth-links">
            <a href="{{ route('auth.login') }}">← Quay lại đăng nhập</a>
            <a href="{{ route('auth.register') }}">Đăng ký tài khoản</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@push('scripts')
<script>
document.getElementById('forgotForm').addEventListener('submit', function() {
    var btn = document.getElementById('forgotBtn');
    btn.disabled = true; btn.textContent = 'Đang gửi...';
    setTimeout(function() { btn.disabled = false; btn.textContent = 'Gửi link khôi phục'; }, 10000);
});
</script>
@endpush
