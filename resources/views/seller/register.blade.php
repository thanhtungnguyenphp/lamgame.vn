@extends('layouts.master')

@section('page_title', $page_title)

@push('styles')
<style>
.seller-register-page {
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    padding: 3rem 0;
    min-height: calc(100vh - 200px);
}
.register-card {
    background: white;
    border-radius: 20px;
    padding: 3rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.register-header {
    text-align: center;
    margin-bottom: 3rem;
    color: white;
}
.register-header h1 {
    color: white;
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}
.register-header p {
    color: rgba(255,255,255,0.9);
    font-size: 1.1rem;
}
.form-section h3 {
    color: #2c5f41;
    margin-bottom: 1.5rem;
    font-size: 1.3rem;
    font-weight: 700;
}
.form-group {
    margin-bottom: 1.5rem;
}
.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: #333;
}
.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
}
.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #2c5f41;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}
.btn-submit {
    width: 100%;
    padding: 1rem;
    background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%);
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
}
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(44,95,65,0.3);
}
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<div class="seller-register-page">
    <div class="container">
        <!-- Header -->
        <div class="register-header">
            <h1>🎮 Đăng ký Seller</h1>
            <p>Bắt đầu bán source code game của bạn trên Làm Game</p>
        </div>

        <!-- Form Card -->
        <div class="register-card">
            <form action="{{ route('seller.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Shop Information -->
                <div class="form-section">
                    <h3>📝 Thông tin Shop</h3>

                    <div class="form-group">
                        <label>Tên Shop <span>*</span></label>
                        <input type="text" name="shop_name" value="{{ old('shop_name') }}" required placeholder="VD: GameDev Studio">
                        @error('shop_name')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>
                            Mô tả Shop
                        </label>
                        <textarea name="shop_description" rows="4"
                           
                            placeholder="Giới thiệu về shop của bạn...">{{ old('shop_description') }}</textarea>
                        @error('shop_description')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Logo Shop
                            </label>
                            <input type="file" name="shop_logo" accept="image/*"
                               >
                            <small>Max 2MB, JPG/PNG</small>
                            @error('shop_logo')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>
                                Banner Shop
                            </label>
                            <input type="file" name="shop_banner" accept="image/*"
                               >
                            <small>Max 5MB, JPG/PNG</small>
                            @error('shop_banner')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3>
                        📞 Thông tin liên hệ
                    </h3>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Email <span>*</span>
                            </label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $customer->email) }}" required
                               >
                            @error('contact_email')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>
                                Số điện thoại
                            </label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $customer->phone) }}"
                               >
                            @error('contact_phone')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            Website
                        </label>
                        <input type="url" name="website" value="{{ old('website') }}"
                           
                            placeholder="https://yourwebsite.com">
                        @error('website')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Business Information -->
                <div class="form-section">
                    <h3>
                        🏢 Thông tin doanh nghiệp
                    </h3>

                    <div class="form-group">
                        <label>
                            Loại hình <span>*</span>
                        </label>
                        <select name="business_type" required
                           >
                            <option value="individual" {{ old('business_type') == 'individual' ? 'selected' : '' }}>Cá nhân</option>
                            <option value="company" {{ old('business_type') == 'company' ? 'selected' : '' }}>Công ty</option>
                        </select>
                        @error('business_type')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="tax-id-group">
                        <label>
                            Mã số thuế
                        </label>
                        <input type="text" name="tax_id" value="{{ old('tax_id') }}"
                           >
                        @error('tax_id')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="form-section">
                    <h3>
                        🏦 Thông tin ngân hàng
                    </h3>

                    <div class="form-group">
                        <label>
                            Tên ngân hàng <span>*</span>
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                           
                            placeholder="VD: Vietcombank">
                        @error('bank_name')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>
                                Số tài khoản <span>*</span>
                            </label>
                            <input type="text" name="bank_account" value="{{ old('bank_account') }}" required
                               >
                            @error('bank_account')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>
                                Chủ tài khoản <span>*</span>
                            </label>
                            <input type="text" name="bank_holder" value="{{ old('bank_holder', $customer->first_name . ' ' . $customer->last_name) }}" required
                               >
                            @error('bank_holder')
                                <span>{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div class="form-section">
                    <label>
                        <input type="checkbox" name="terms_accepted" value="1" required
                           >
                        <span>
                            Tôi đồng ý với <a href="#">Điều khoản dịch vụ</a> và 
                            <a href="#">Chính sách bán hàng</a>
                        </span>
                    </label>
                    @error('terms_accepted')
                        <span>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    🚀 Đăng ký Seller
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('select[name="business_type"]').addEventListener('change', function() {
    const taxIdGroup = document.getElementById('tax-id-group');
    if (this.value === 'company') {
        taxIdGroup.style.display = 'block';
    } else {
        taxIdGroup.style.display = 'none';
    }
});

// Trigger on page load
if (document.querySelector('select[name="business_type"]').value === 'company') {
    document.getElementById('tax-id-group').style.display = 'block';
}
</script>
</div>
@endsection
