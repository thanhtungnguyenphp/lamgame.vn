<x-shop::layouts>
    <x-slot:title>
        Đặt lại mật khẩu - LAMGAME
    </x-slot>

    <div class="auth-page">
        <div class="auth-page__bg"></div>
        <div class="auth-card">
            <div class="auth-card__header">
                <a href="/" class="auth-logo">LAMGAME<span>.VN</span></a>
                <h1>Đặt lại mật khẩu</h1>
                <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
            </div>

            <x-shop::form :action="route('shop.customers.reset_password.store')">
                <x-shop::form.control-group>
                    <input type="hidden" name="token" value="{{ $token }}">
                </x-shop::form.control-group>

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           class="auth-input" value="{{ old('email') }}"
                           placeholder="nhap@email.com" required>
                    @error('email')<span class="auth-error">{{ $message }}</span>@enderror
                </div>

                <div class="auth-field">
                    <label for="password">Mật khẩu mới</label>
                    <input type="password" id="password" name="password"
                           class="auth-input" placeholder="Tối thiểu 6 ký tự" required minlength="6">
                    @error('password')<span class="auth-error">{{ $message }}</span>@enderror
                </div>

                <div class="auth-field">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="auth-input" placeholder="Nhập lại mật khẩu" required minlength="6">
                </div>

                <button type="submit" class="auth-btn">Đặt lại mật khẩu</button>
            </x-shop::form>

            <div class="auth-links">
                <a href="{{ route('shop.customer.session.index') }}">← Quay lại đăng nhập</a>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
    /* Hide default header/footer on auth page */
    header,.footer-section,footer,[class*="footer"]{display:none!important}
    main#main{background:#070B14!important;padding:0!important}
    body{background:#070B14!important}

    .auth-page{min-height:100vh;display:flex;align-items:center;justify-content:center;background:#070B14;position:relative;padding:2rem 1rem;font-family:'Inter',sans-serif}
    .auth-page__bg{position:absolute;inset:0;background:radial-gradient(ellipse at 50% 30%,rgba(124,92,255,.12) 0%,transparent 55%),radial-gradient(ellipse at 80% 80%,rgba(0,209,255,.06) 0%,transparent 40%);pointer-events:none}
    .auth-card{position:relative;z-index:1;background:rgba(17,24,39,.7);border:1px solid rgba(124,92,255,.12);border-radius:16px;padding:40px 32px;width:100%;max-width:400px;backdrop-filter:blur(10px)}
    .auth-card__header{text-align:center;margin-bottom:28px}
    .auth-logo{font-size:1.4rem;font-weight:800;color:#F5F7FA;text-decoration:none!important;display:inline-block;margin-bottom:16px}
    .auth-logo span{color:#7C5CFF}
    .auth-card__header h1{font-size:1.5rem;font-weight:700;color:#F5F7FA;margin:0 0 6px}
    .auth-card__header p{color:#7A8599;font-size:.9rem;margin:0}
    .auth-field{margin-bottom:18px}
    .auth-field label{display:block;margin-bottom:6px;color:#B7C0D1;font-size:.85rem;font-weight:500}
    .auth-input{width:100%;padding:12px 14px;background:rgba(7,11,20,.6);border:1px solid rgba(124,92,255,.15);border-radius:8px;color:#F5F7FA;font-size:.9rem;transition:border-color .3s}
    .auth-input::placeholder{color:#7A8599}
    .auth-input:focus{outline:none;border-color:#7C5CFF;box-shadow:0 0 12px rgba(124,92,255,.12)}
    .auth-error{color:#F87171;font-size:.8rem;margin-top:4px;display:block}
    .auth-btn{width:100%;padding:13px;background:linear-gradient(135deg,#7C5CFF,#6B4FE0);color:#fff;border:none;border-radius:8px;font-size:.95rem;font-weight:600;cursor:pointer;transition:all .3s;margin-top:8px}
    .auth-btn:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(124,92,255,.35)}
    .auth-links{display:flex;justify-content:center;margin-top:20px;font-size:.85rem}
    .auth-links a{color:#7C5CFF;text-decoration:none!important;font-weight:500}
    .auth-links a:hover{color:#00D1FF}
    @media(max-width:480px){.auth-card{padding:28px 20px}}
    </style>
    @endpush
</x-shop::layouts>
