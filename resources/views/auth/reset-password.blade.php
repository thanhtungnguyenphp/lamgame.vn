@extends('layouts.master')

@section('page_title', 'Đặt lại mật khẩu - LAMGAME')
@section('page_description', 'Đặt lại mật khẩu tài khoản LAMGAME của bạn.')

@section('content')
<div class="auth-page">
    <div class="auth-page__bg"></div>
    <div class="auth-card">
        <div class="auth-card__header">
            <a href="/" class="auth-logo">LAMGAME<span>.VN</span></a>
            <h1>Đặt lại mật khẩu</h1>
            <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
        </div>

        @if(session('success'))
        <div class="auth-alert auth-alert--success">✅ {{ session('success') }}</div>
        @endif

        <form id="resetForm" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="auth-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email"
                       class="auth-input {{ $errors->has('email') ? 'auth-input--error' : '' }}"
                       value="{{ old('email', request('email')) }}" placeholder="nhap@email.com" required>
                @if($errors->has('email'))
                <span class="auth-error">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="auth-field">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password"
                       class="auth-input {{ $errors->has('password') ? 'auth-input--error' : '' }}"
                       placeholder="Tối thiểu 6 ký tự" required minlength="6">
                @if($errors->has('password'))
                <span class="auth-error">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="auth-field">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="auth-input" placeholder="Nhập lại mật khẩu" required minlength="6">
            </div>

            <button type="submit" class="auth-btn" id="resetBtn">Đặt lại mật khẩu</button>
        </form>

        <div class="auth-links">
            <a href="{{ route('auth.login') }}">← Quay lại đăng nhập</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@push('scripts')
<script>
document.getElementById('resetForm').addEventListener('submit', function() {
    var btn = document.getElementById('resetBtn');
    btn.disabled = true; btn.textContent = 'Đang xử lý...';
    setTimeout(function() { btn.disabled = false; btn.textContent = 'Đặt lại mật khẩu'; }, 10000);
});
</script>
@endpush
