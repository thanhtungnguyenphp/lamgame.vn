<x-layouts.account>
    <x-slot:title>Thêm địa chỉ mới</x-slot>

    <div class="address-form-container">
        <!-- Header -->
        <div class="form-header">
            <div>
                <a href="{{ route('shop.customers.account.addresses.index') }}" class="btn-back">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    Quay lại
                </a>
                <h1 class="form-title">Thêm địa chỉ mới</h1>
                <p class="form-subtitle">Điền thông tin địa chỉ giao hàng của bạn</p>
            </div>
        </div>

        <form method="POST" action="{{ route('shop.customers.account.addresses.store') }}">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Contact Information -->
            <div class="form-section">
                <h2 class="section-title">Thông tin liên hệ</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label required">Họ</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required class="form-input" placeholder="Nguyễn">
                        @error('first_name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Tên</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required class="form-input" placeholder="Văn A">
                        @error('last_name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Số điện thoại</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="form-input" placeholder="0912345678">
                        @error('phone')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->guard('customer')->user()->email) }}" required class="form-input" placeholder="email@example.com">
                        @error('email')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Tên công ty (tùy chọn)</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-input" placeholder="Tên công ty">
                        @error('company_name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <!-- Address Details -->
            <div class="form-section">
                <h2 class="section-title">Địa chỉ chi tiết</h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label required">Địa chỉ cụ thể</label>
                        <input type="text" name="address[]" value="{{ old('address.0') }}" required class="form-input" placeholder="Số nhà, tên đường (VD: 123 Nguyễn Huệ)">
                        @error('address.0')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Tỉnh/Thành phố</label>
                        <select name="state" required class="form-input" id="province">
                            <option value="">Chọn Tỉnh/Thành phố</option>
                            <option value="Hồ Chí Minh" {{ old('state') == 'Hồ Chí Minh' ? 'selected' : '' }}>TP. Hồ Chí Minh</option>
                            <option value="Hà Nội" {{ old('state') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                            <option value="Đà Nẵng" {{ old('state') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                            <option value="Cần Thơ" {{ old('state') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                            <option value="Hải Phòng" {{ old('state') == 'Hải Phòng' ? 'selected' : '' }}>Hải Phòng</option>
                            <option value="An Giang" {{ old('state') == 'An Giang' ? 'selected' : '' }}>An Giang</option>
                            <option value="Bà Rịa - Vũng Tàu" {{ old('state') == 'Bà Rịa - Vũng Tàu' ? 'selected' : '' }}>Bà Rịa - Vũng Tàu</option>
                            <option value="Bắc Giang" {{ old('state') == 'Bắc Giang' ? 'selected' : '' }}>Bắc Giang</option>
                            <option value="Bắc Kạn" {{ old('state') == 'Bắc Kạn' ? 'selected' : '' }}>Bắc Kạn</option>
                            <option value="Bạc Liêu" {{ old('state') == 'Bạc Liêu' ? 'selected' : '' }}>Bạc Liêu</option>
                            <option value="Bắc Ninh" {{ old('state') == 'Bắc Ninh' ? 'selected' : '' }}>Bắc Ninh</option>
                            <option value="Bến Tre" {{ old('state') == 'Bến Tre' ? 'selected' : '' }}>Bến Tre</option>
                            <option value="Bình Định" {{ old('state') == 'Bình Định' ? 'selected' : '' }}>Bình Định</option>
                            <option value="Bình Dương" {{ old('state') == 'Bình Dương' ? 'selected' : '' }}>Bình Dương</option>
                            <option value="Bình Phước" {{ old('state') == 'Bình Phước' ? 'selected' : '' }}>Bình Phước</option>
                            <option value="Bình Thuận" {{ old('state') == 'Bình Thuận' ? 'selected' : '' }}>Bình Thuận</option>
                            <option value="Cà Mau" {{ old('state') == 'Cà Mau' ? 'selected' : '' }}>Cà Mau</option>
                            <option value="Cao Bằng" {{ old('state') == 'Cao Bằng' ? 'selected' : '' }}>Cao Bằng</option>
                            <option value="Đắk Lắk" {{ old('state') == 'Đắk Lắk' ? 'selected' : '' }}>Đắk Lắk</option>
                            <option value="Đắk Nông" {{ old('state') == 'Đắk Nông' ? 'selected' : '' }}>Đắk Nông</option>
                            <option value="Điện Biên" {{ old('state') == 'Điện Biên' ? 'selected' : '' }}>Điện Biên</option>
                            <option value="Đồng Nai" {{ old('state') == 'Đồng Nai' ? 'selected' : '' }}>Đồng Nai</option>
                            <option value="Đồng Tháp" {{ old('state') == 'Đồng Tháp' ? 'selected' : '' }}>Đồng Tháp</option>
                            <option value="Gia Lai" {{ old('state') == 'Gia Lai' ? 'selected' : '' }}>Gia Lai</option>
                            <option value="Hà Giang" {{ old('state') == 'Hà Giang' ? 'selected' : '' }}>Hà Giang</option>
                            <option value="Hà Nam" {{ old('state') == 'Hà Nam' ? 'selected' : '' }}>Hà Nam</option>
                            <option value="Hà Tĩnh" {{ old('state') == 'Hà Tĩnh' ? 'selected' : '' }}>Hà Tĩnh</option>
                            <option value="Hải Dương" {{ old('state') == 'Hải Dương' ? 'selected' : '' }}>Hải Dương</option>
                            <option value="Hậu Giang" {{ old('state') == 'Hậu Giang' ? 'selected' : '' }}>Hậu Giang</option>
                            <option value="Hòa Bình" {{ old('state') == 'Hòa Bình' ? 'selected' : '' }}>Hòa Bình</option>
                            <option value="Hưng Yên" {{ old('state') == 'Hưng Yên' ? 'selected' : '' }}>Hưng Yên</option>
                            <option value="Khánh Hòa" {{ old('state') == 'Khánh Hòa' ? 'selected' : '' }}>Khánh Hòa</option>
                            <option value="Kiên Giang" {{ old('state') == 'Kiên Giang' ? 'selected' : '' }}>Kiên Giang</option>
                            <option value="Kon Tum" {{ old('state') == 'Kon Tum' ? 'selected' : '' }}>Kon Tum</option>
                            <option value="Lai Châu" {{ old('state') == 'Lai Châu' ? 'selected' : '' }}>Lai Châu</option>
                            <option value="Lâm Đồng" {{ old('state') == 'Lâm Đồng' ? 'selected' : '' }}>Lâm Đồng</option>
                            <option value="Lạng Sơn" {{ old('state') == 'Lạng Sơn' ? 'selected' : '' }}>Lạng Sơn</option>
                            <option value="Lào Cai" {{ old('state') == 'Lào Cai' ? 'selected' : '' }}>Lào Cai</option>
                            <option value="Long An" {{ old('state') == 'Long An' ? 'selected' : '' }}>Long An</option>
                            <option value="Nam Định" {{ old('state') == 'Nam Định' ? 'selected' : '' }}>Nam Định</option>
                            <option value="Nghệ An" {{ old('state') == 'Nghệ An' ? 'selected' : '' }}>Nghệ An</option>
                            <option value="Ninh Bình" {{ old('state') == 'Ninh Bình' ? 'selected' : '' }}>Ninh Bình</option>
                            <option value="Ninh Thuận" {{ old('state') == 'Ninh Thuận' ? 'selected' : '' }}>Ninh Thuận</option>
                            <option value="Phú Thọ" {{ old('state') == 'Phú Thọ' ? 'selected' : '' }}>Phú Thọ</option>
                            <option value="Phú Yên" {{ old('state') == 'Phú Yên' ? 'selected' : '' }}>Phú Yên</option>
                            <option value="Quảng Bình" {{ old('state') == 'Quảng Bình' ? 'selected' : '' }}>Quảng Bình</option>
                            <option value="Quảng Nam" {{ old('state') == 'Quảng Nam' ? 'selected' : '' }}>Quảng Nam</option>
                            <option value="Quảng Ngãi" {{ old('state') == 'Quảng Ngãi' ? 'selected' : '' }}>Quảng Ngãi</option>
                            <option value="Quảng Ninh" {{ old('state') == 'Quảng Ninh' ? 'selected' : '' }}>Quảng Ninh</option>
                            <option value="Quảng Trị" {{ old('state') == 'Quảng Trị' ? 'selected' : '' }}>Quảng Trị</option>
                            <option value="Sóc Trăng" {{ old('state') == 'Sóc Trăng' ? 'selected' : '' }}>Sóc Trăng</option>
                            <option value="Sơn La" {{ old('state') == 'Sơn La' ? 'selected' : '' }}>Sơn La</option>
                            <option value="Tây Ninh" {{ old('state') == 'Tây Ninh' ? 'selected' : '' }}>Tây Ninh</option>
                            <option value="Thái Bình" {{ old('state') == 'Thái Bình' ? 'selected' : '' }}>Thái Bình</option>
                            <option value="Thái Nguyên" {{ old('state') == 'Thái Nguyên' ? 'selected' : '' }}>Thái Nguyên</option>
                            <option value="Thanh Hóa" {{ old('state') == 'Thanh Hóa' ? 'selected' : '' }}>Thanh Hóa</option>
                            <option value="Thừa Thiên Huế" {{ old('state') == 'Thừa Thiên Huế' ? 'selected' : '' }}>Thừa Thiên Huế</option>
                            <option value="Tiền Giang" {{ old('state') == 'Tiền Giang' ? 'selected' : '' }}>Tiền Giang</option>
                            <option value="Trà Vinh" {{ old('state') == 'Trà Vinh' ? 'selected' : '' }}>Trà Vinh</option>
                            <option value="Tuyên Quang" {{ old('state') == 'Tuyên Quang' ? 'selected' : '' }}>Tuyên Quang</option>
                            <option value="Vĩnh Long" {{ old('state') == 'Vĩnh Long' ? 'selected' : '' }}>Vĩnh Long</option>
                            <option value="Vĩnh Phúc" {{ old('state') == 'Vĩnh Phúc' ? 'selected' : '' }}>Vĩnh Phúc</option>
                            <option value="Yên Bái" {{ old('state') == 'Yên Bái' ? 'selected' : '' }}>Yên Bái</option>
                        </select>
                        @error('state')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Quận/Huyện</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="form-input" placeholder="VD: Quận 1, Huyện Củ Chi">
                        @error('city')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phường/Xã (tùy chọn)</label>
                        <input type="text" name="address[1]" value="{{ old('address.1') }}" class="form-input" placeholder="VD: Phường Bến Nghé">
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Mã bưu điện</label>
                        <input type="text" name="postcode" value="{{ old('postcode') }}" required class="form-input" placeholder="VD: 700000">
                        @error('postcode')<span class="error-text">{{ $message }}</span>@enderror
                    </div>

                    <input type="hidden" name="country" value="VN">
                </div>
            </div>

            <!-- Default Address -->
            <div class="form-section">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="default_address" value="1" {{ old('default_address') ? 'checked' : '' }}>
                    <span class="checkbox-label">Đặt làm địa chỉ mặc định</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="{{ route('shop.customers.account.addresses.index') }}" class="btn-cancel">Hủy</a>
                <button type="submit" class="btn-save">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Lưu địa chỉ
                </button>
            </div>
        </form>
    </div>

    @push('styles')
    <style>
        .address-form-container { max-width: 900px; margin: 0 auto; }
        .form-header { margin-bottom: 2rem; }
        .btn-back { display: inline-flex; align-items: center; gap: 0.5rem; color: #6b7280; text-decoration: none; font-size: 0.875rem; margin-bottom: 1rem; transition: color 0.2s; }
        .btn-back:hover { color: #2c5f41; }
        .form-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0.5rem 0 0.25rem 0; }
        .form-subtitle { color: #6b7280; font-size: 0.875rem; margin: 0; }
        
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; }
        .alert-danger { background: #fee; border: 1px solid #fcc; }
        .alert ul { margin: 0; padding-left: 1.5rem; color: #c00; }
        
        .form-section { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .section-title { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin: 0 0 1.5rem 0; padding-bottom: 0.75rem; border-bottom: 2px solid #e5e7eb; }
        
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group.full-width { grid-column: 1 / -1; }
        
        .form-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
        .form-label.required::after { content: ' *'; color: #dc2626; }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 1px solid #d1d5db; border-radius: 8px; font-size: 1rem; transition: all 0.2s; }
        .form-input:focus { outline: none; border-color: #2c5f41; box-shadow: 0 0 0 3px rgba(44,95,65,0.1); }
        .error-text { font-size: 0.75rem; color: #dc2626; }
        
        .checkbox-wrapper { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; }
        .checkbox-wrapper input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; }
        .checkbox-label { font-size: 0.875rem; color: #374151; }
        
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; }
        .btn-cancel { padding: 0.75rem 1.5rem; background: white; color: #6b7280; border: 1px solid #d1d5db; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .btn-cancel:hover { background: #f9fafb; }
        .btn-save { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .btn-save:hover { background: #1e4530; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,95,65,0.2); }
        
        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-section { padding: 1.5rem; }
            .form-actions { flex-direction: column-reverse; }
            .btn-cancel, .btn-save { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-layouts.account>
