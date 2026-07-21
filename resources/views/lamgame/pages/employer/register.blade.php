@extends('layouts.master')
@section('page_title', 'Đăng ký Employer - Làm Game')

@section('content')
<div class="emp-page">
    <div class="emp-container" style="max-width:600px">
        <div class="emp-register-card">
            <h1>🏢 Đăng ký Employer</h1>
            <p class="emp-register__desc">Đăng ký tài khoản nhà tuyển dụng để đăng tin tuyển dụng và tìm kiếm talent game developer.</p>

            @if(session('info'))
            <div class="emp-alert emp-alert--info">{{ session('info') }}</div>
            @endif

            @if($errors->any())
            <div class="emp-alert emp-alert--error">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('employer.register.submit') }}" class="emp-form">
                @csrf
                <div class="emp-field">
                    <label>Tên công ty *</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" required placeholder="VD: LamGame Studio">
                </div>

                <div class="emp-field">
                    <label>Lĩnh vực</label>
                    <input type="text" name="industry" value="{{ old('industry', 'Game Development') }}" placeholder="Game Development, IT, Design...">
                </div>

                <div class="emp-field">
                    <label>Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://company.com">
                </div>

                <div class="emp-field">
                    <label>Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Quận 1, TP.HCM">
                </div>

                <div class="emp-field">
                    <label>Mô tả công ty</label>
                    <textarea name="description" rows="4" placeholder="Giới thiệu ngắn về công ty...">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="emp-btn emp-btn--primary" style="width:100%">🚀 Đăng ký Employer</button>
            </form>

            <p class="emp-register__note">Sau khi đăng ký, admin sẽ duyệt tài khoản trong 24h. Bạn sẽ nhận email thông báo.</p>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.emp-page{background:#070B14;min-height:80vh;padding:60px 0}
.emp-container{margin:0 auto;padding:0 20px}
.emp-register-card{background:rgba(17,24,39,.6);border:1px solid rgba(124,92,255,.15);border-radius:16px;padding:36px}
.emp-register-card h1{font-size:1.5rem;font-weight:700;color:#F5F7FA;margin-bottom:8px}
.emp-register__desc{color:#7A8599;font-size:.9rem;margin-bottom:24px}
.emp-register__note{color:#5A6577;font-size:.82rem;margin-top:16px;text-align:center}
.emp-form{display:flex;flex-direction:column;gap:14px}
.emp-field{display:flex;flex-direction:column;gap:5px}
.emp-field label{font-size:.82rem;color:#7A8599;font-weight:500}
.emp-field input,.emp-field textarea{padding:10px 14px;background:rgba(10,15,30,.8);border:1px solid rgba(124,92,255,.15);border-radius:8px;color:#F5F7FA;font-size:.9rem}
.emp-field input:focus,.emp-field textarea:focus{outline:none;border-color:#6C63FF}
.emp-btn{padding:12px;border-radius:8px;font-weight:600;font-size:.95rem;border:none;cursor:pointer}
.emp-btn--primary{background:#6C63FF;color:#fff}.emp-btn--primary:hover{background:#5a52e0}
.emp-alert--info{background:rgba(59,130,246,.1);border:1px solid rgba(59,130,246,.3);color:#60A5FA;padding:10px;border-radius:8px;margin-bottom:16px;font-size:.85rem}
.emp-alert--error{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#F87171;padding:10px;border-radius:8px;margin-bottom:16px;font-size:.85rem}
</style>
@endpush
