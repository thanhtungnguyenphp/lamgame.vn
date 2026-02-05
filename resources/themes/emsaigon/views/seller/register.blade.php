@if($isEdit)
    @extends('shop::seller.layouts.master')
@else
    @extends('layouts.master')
@endif

@section('page_title', $page_title)

@section('content')
<div class="seller-register-page" style="background: #f8f9fa; padding: 3rem 0; min-height: 80vh;">
    <div class="container" style="max-width: 800px;">
        <!-- Header -->
        <div class="register-header" style="text-align: center; margin-bottom: 3rem;">
            <h1 style="color: #2c5f41; font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem;">
                {{ $isEdit ? '⚙️ Cài đặt Shop' : '🎮 Đăng ký Seller' }}
            </h1>
            <p style="color: #666; font-size: 1.1rem;">
                {{ $isEdit ? 'Cập nhật thông tin shop của bạn' : 'Bắt đầu bán source code game của bạn trên Làm Game' }}
            </p>
        </div>

        <!-- Form Card -->
        <div class="register-card" style="background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <form action="{{ route('seller.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Shop Information -->
                <div class="form-section" style="margin-bottom: 2rem;">
                    <h3 style="color: #2c5f41; margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700;">
                        📝 Thông tin Shop
                    </h3>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Tên Shop <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $seller->shop_name ?? '') }}" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                            placeholder="VD: GameDev Studio">
                        @error('shop_name')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Mô tả Shop
                        </label>
                        <textarea name="shop_description" rows="4"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                            placeholder="Giới thiệu về shop của bạn...">{{ old('shop_description', $seller->shop_description ?? '') }}</textarea>
                        @error('shop_description')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Logo Shop
                            </label>
                            @if($seller && $seller->shop_logo)
                                <div style="margin-bottom: 0.5rem;">
                                    <img src="{{ Storage::url($seller->shop_logo) }}" alt="Logo hiện tại" style="max-width: 80px; border-radius: 8px;">
                                    <small style="display: block; color: #666;">Logo hiện tại</small>
                                </div>
                            @endif
                            <input type="file" name="shop_logo" accept="image/*"
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px;">
                            <small style="color: #666;">Max 2MB, JPG/PNG {{ $isEdit ? '(Để trống nếu không đổi)' : '' }}</small>
                            @error('shop_logo')
                                <span style="color: red; font-size: 0.9rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Banner Shop
                            </label>
                            @if($seller && $seller->shop_banner)
                                <div style="margin-bottom: 0.5rem;">
                                    <img src="{{ Storage::url($seller->shop_banner) }}" alt="Banner hiện tại" style="max-width: 150px; border-radius: 8px;">
                                    <small style="display: block; color: #666;">Banner hiện tại</small>
                                </div>
                            @endif
                            <input type="file" name="shop_banner" accept="image/*"
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px;">
                            <small style="color: #666;">Max 5MB, JPG/PNG {{ $isEdit ? '(Để trống nếu không đổi)' : '' }}</small>
                            @error('shop_banner')
                                <span style="color: red; font-size: 0.9rem; display: block;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section" style="margin-bottom: 2rem;">
                    <h3 style="color: #2c5f41; margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700;">
                        📞 Thông tin liên hệ
                    </h3>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Email <span style="color: red;">*</span>
                            </label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $seller->contact_email ?? $customer->email) }}" required
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                            @error('contact_email')
                                <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Số điện thoại
                            </label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $seller->contact_phone ?? $customer->phone) }}"
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                            @error('contact_phone')
                                <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Website
                        </label>
                        <input type="url" name="website" value="{{ old('website', $seller->website ?? '') }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                            placeholder="https://yourwebsite.com">
                        @error('website')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Business Information -->
                <div class="form-section" style="margin-bottom: 2rem;">
                    <h3 style="color: #2c5f41; margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700;">
                        🏢 Thông tin doanh nghiệp
                    </h3>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Loại hình <span style="color: red;">*</span>
                        </label>
                        <select name="business_type" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                            <option value="individual" {{ old('business_type', $seller->business_type ?? '') == 'individual' ? 'selected' : '' }}>Cá nhân</option>
                            <option value="company" {{ old('business_type', $seller->business_type ?? '') == 'company' ? 'selected' : '' }}>Công ty</option>
                        </select>
                        @error('business_type')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group" id="tax-id-group" style="margin-bottom: 1.5rem; display: none;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Mã số thuế
                        </label>
                        <input type="text" name="tax_id" value="{{ old('tax_id', $seller->tax_id ?? '') }}"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                        @error('tax_id')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="form-section" style="margin-bottom: 2rem;">
                    <h3 style="color: #2c5f41; margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700;">
                        🏦 Thông tin ngân hàng
                    </h3>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                            Tên ngân hàng <span style="color: red;">*</span>
                        </label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $seller->bank_name ?? '') }}" required
                            style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                            placeholder="VD: Vietcombank">
                        @error('bank_name')
                            <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Số tài khoản <span style="color: red;">*</span>
                            </label>
                            <input type="text" name="bank_account" value="{{ old('bank_account', $seller->bank_account ?? '') }}" required
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                            @error('bank_account')
                                <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                Chủ tài khoản <span style="color: red;">*</span>
                            </label>
                            <input type="text" name="bank_holder" value="{{ old('bank_holder', $seller->bank_holder ?? $customer->first_name . ' ' . $customer->last_name) }}" required
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                            @error('bank_holder')
                                <span style="color: red; font-size: 0.9rem;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms (only for new registration) -->
                @if(!$isEdit)
                <div class="form-section" style="margin-bottom: 2rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="terms_accepted" value="1" required
                            style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="color: #333;">
                            Tôi đồng ý với <a href="#" style="color: #2c5f41; text-decoration: underline;">Điều khoản dịch vụ</a> và 
                            <a href="#" style="color: #2c5f41; text-decoration: underline;">Chính sách bán hàng</a>
                        </span>
                    </label>
                    @error('terms_accepted')
                        <span style="color: red; font-size: 0.9rem; display: block; margin-top: 0.5rem;">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" 
                    style="width: 100%; padding: 1rem; background: linear-gradient(135deg, #2c5f41 0%, #1e4530 100%); color: white; border: none; border-radius: 15px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s;">
                    {{ $isEdit ? '💾 Cập nhật thông tin' : '🚀 Đăng ký Seller' }}
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const businessTypeSelect = document.querySelector('select[name="business_type"]');
    const taxIdGroup = document.getElementById('tax-id-group');
    
    function toggleTaxId() {
        if (businessTypeSelect.value === 'company') {
            taxIdGroup.style.display = 'block';
        } else {
            taxIdGroup.style.display = 'none';
        }
    }
    
    businessTypeSelect.addEventListener('change', toggleTaxId);
    toggleTaxId(); // Run on page load
});
</script>
@endsection
