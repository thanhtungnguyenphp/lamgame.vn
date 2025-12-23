<x-layouts.account>
    <x-slot:title>
        Chỉnh sửa thông tin
    </x-slot>

    <div class="edit-profile-container">
        <!-- Header -->
        <div class="edit-header">
            <div>
                <h1 class="edit-title">Chỉnh sửa thông tin cá nhân</h1>
                <p class="edit-subtitle">Cập nhật thông tin của bạn</p>
            </div>
            <a href="{{ route('shop.customers.account.profile.index') }}" class="btn-back">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
                Quay lại
            </a>
        </div>

        <x-shop::form :action="route('shop.customers.account.profile.update')" enctype="multipart/form-data">
            <!-- Basic Info Card -->
            <div class="form-card">
                <h2 class="form-section-title">Thông tin cơ bản</h2>
                
                <div class="form-grid">
                    <!-- First Name -->
                    <div class="form-group">
                        <label class="form-label">Họ *</label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="first_name"
                            :value="old('first_name') ?? $customer->first_name"
                            rules="required"
                            :label="trans('shop::app.customers.account.profile.edit.first-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.first-name')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="first_name" />
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label class="form-label">Tên *</label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="last_name"
                            :value="old('last_name') ?? $customer->last_name"
                            rules="required"
                            :label="trans('shop::app.customers.account.profile.edit.last-name')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.last-name')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="last_name" />
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label class="form-label">Giới tính</label>
                        <x-shop::form.control-group.control
                            type="select"
                            name="gender"
                            :value="old('gender') ?? $customer->gender"
                            :label="trans('shop::app.customers.account.profile.edit.gender')"
                            class="form-input"
                        >
                            <option value="">Chọn giới tính</option>
                            <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Nam</option>
                            <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Nữ</option>
                            <option value="Other" {{ $customer->gender == 'Other' ? 'selected' : '' }}>Khác</option>
                        </x-shop::form.control-group.control>
                        <x-shop::form.control-group.error control-name="gender" />
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label class="form-label">Ngày sinh</label>
                        <x-shop::form.control-group.control
                            type="date"
                            name="date_of_birth"
                            :value="old('date_of_birth') ?? $customer->date_of_birth"
                            :label="trans('shop::app.customers.account.profile.edit.dob')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.dob')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="date_of_birth" />
                    </div>
                </div>
            </div>

            <!-- Contact Info Card -->
            <div class="form-card">
                <h2 class="form-section-title">Thông tin liên hệ</h2>
                
                <div class="form-grid">
                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            :value="old('email') ?? $customer->email"
                            rules="required|email"
                            :label="trans('shop::app.customers.account.profile.edit.email')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.email')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="email" />
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            :value="old('phone') ?? $customer->phone"
                            :label="trans('shop::app.customers.account.profile.edit.phone')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.phone')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="phone" />
                    </div>
                </div>
            </div>

            <!-- Password Card -->
            <div class="form-card">
                <h2 class="form-section-title">Đổi mật khẩu</h2>
                <p class="form-section-desc">Để trống nếu không muốn thay đổi mật khẩu</p>
                
                <div class="form-grid">
                    <!-- Current Password -->
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại</label>
                        <x-shop::form.control-group.control
                            type="password"
                            name="old_password"
                            :label="trans('shop::app.customers.account.profile.edit.current-password')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.current-password')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="old_password" />
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới</label>
                        <x-shop::form.control-group.control
                            type="password"
                            name="password"
                            :label="trans('shop::app.customers.account.profile.edit.new-password')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.new-password')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="password" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <x-shop::form.control-group.control
                            type="password"
                            name="password_confirmation"
                            :label="trans('shop::app.customers.account.profile.edit.confirm-password')"
                            :placeholder="trans('shop::app.customers.account.profile.edit.confirm-password')"
                            class="form-input"
                        />
                        <x-shop::form.control-group.error control-name="password_confirmation" />
                    </div>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="form-card">
                <div class="checkbox-group">
                    <x-shop::form.control-group.control
                        type="checkbox"
                        name="subscribed_to_news_letter"
                        value="1"
                        :checked="(bool) $customer->subscribed_to_news_letter"
                        :label="trans('shop::app.customers.account.profile.edit.subscribe-to-newsletter')"
                    />
                    <label class="checkbox-label">Đăng ký nhận bản tin</label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="{{ route('shop.customers.account.profile.index') }}" class="btn-cancel">
                    Hủy
                </a>
                <button type="submit" class="btn-save">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                    </svg>
                    Lưu thay đổi
                </button>
            </div>
        </x-shop::form>
    </div>

    @push('styles')
    <style>
        .edit-profile-container {
            max-width: 100%;
        }

        .edit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .edit-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .edit-subtitle {
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }

        .form-section-desc {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0 0 1.5rem 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin: 0;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #2c5f41;
            box-shadow: 0 0 0 3px rgba(44, 95, 65, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: #374151;
            margin: 0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-cancel {
            padding: 0.75rem 1.5rem;
            background: white;
            color: #6b7280;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #f9fafb;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #2c5f41;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: #1e4530;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 95, 65, 0.2);
        }

        @media (max-width: 768px) {
            .edit-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .edit-title {
                font-size: 1.5rem;
            }

            .form-card {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @endpush
</x-layouts.account>
