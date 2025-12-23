<x-layouts.account>
    <x-slot:title>
        Thông tin cá nhân
    </x-slot>

    <div class="profile-container">
        <!-- Header -->
        <div class="profile-header">
            <div>
                <h1 class="profile-title">Thông tin cá nhân</h1>
                <p class="profile-subtitle">Quản lý thông tin cá nhân của bạn</p>
            </div>
            <a href="{{ route('shop.customers.account.profile.edit') }}" class="btn-edit-profile">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                </svg>
                Chỉnh sửa
            </a>
        </div>

        <!-- Profile Info Card -->
        <div class="profile-card">
            <div class="profile-section">
                <h2 class="section-title">Thông tin cơ bản</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Họ</label>
                        <p class="info-value">{{ $customer->first_name }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Tên</label>
                        <p class="info-value">{{ $customer->last_name }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Giới tính</label>
                        <p class="info-value">
                            @if($customer->gender === 'Male')
                                Nam
                            @elseif($customer->gender === 'Female')
                                Nữ
                            @else
                                Chưa cập nhật
                            @endif
                        </p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Ngày sinh</label>
                        <p class="info-value">{{ $customer->date_of_birth ? date('d/m/Y', strtotime($customer->date_of_birth)) : 'Chưa cập nhật' }}</p>
                    </div>
                </div>
            </div>

            <div class="profile-divider"></div>

            <div class="profile-section">
                <h2 class="section-title">Thông tin liên hệ</h2>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label class="info-label">Email</label>
                        <p class="info-value">{{ $customer->email }}</p>
                    </div>

                    <div class="info-item">
                        <label class="info-label">Số điện thoại</label>
                        <p class="info-value">{{ $customer->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="danger-zone">
            <div class="danger-zone-content">
                <div>
                    <h3 class="danger-title">Xóa tài khoản</h3>
                    <p class="danger-description">Xóa vĩnh viễn tài khoản và tất cả dữ liệu của bạn. Hành động này không thể hoàn tác.</p>
                </div>
                
                <x-shop::form action="{{ route('shop.customers.account.profile.destroy') }}">
                    <x-shop::modal>
                        <x-slot:toggle>
                            <button type="button" class="btn-danger">
                                Xóa tài khoản
                            </button>
                        </x-slot>

                        <x-slot:header>
                            <h2 class="text-xl font-semibold">Xác nhận xóa tài khoản</h2>
                        </x-slot>

                        <x-slot:content>
                            <p class="mb-4 text-gray-600">Vui lòng nhập mật khẩu để xác nhận xóa tài khoản.</p>
                            
                            <x-shop::form.control-group class="!mb-0">
                                <x-shop::form.control-group.control
                                    type="password"
                                    name="password"
                                    class="px-4 py-3 border rounded-lg"
                                    rules="required"
                                    placeholder="Nhập mật khẩu"
                                />
                                <x-shop::form.control-group.error control-name="password" />
                            </x-shop::form.control-group>
                        </x-slot>

                        <x-slot:footer>
                            <button type="submit" class="px-6 py-3 text-white bg-red-600 rounded-lg hover:bg-red-700">
                                Xác nhận xóa
                            </button>
                        </x-slot>
                    </x-shop::modal>
                </x-shop::form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .profile-container {
            max-width: 100%;
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .profile-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .profile-subtitle {
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        .btn-edit-profile {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #2c5f41;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-edit-profile:hover {
            background: #1e4530;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 95, 65, 0.2);
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .profile-section {
            margin-bottom: 1.5rem;
        }

        .profile-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 1.5rem 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin: 0;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: #1f2937;
            margin: 0;
        }

        .profile-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 2rem 0;
        }

        .danger-zone {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .danger-zone-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .danger-title {
            font-size: 1rem;
            font-weight: 600;
            color: #991b1b;
            margin: 0 0 0.25rem 0;
        }

        .danger-description {
            font-size: 0.875rem;
            color: #7f1d1d;
            margin: 0;
        }

        .btn-danger {
            padding: 0.625rem 1.25rem;
            background: white;
            color: #dc2626;
            border: 1px solid #dc2626;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .profile-title {
                font-size: 1.5rem;
            }

            .profile-card {
                padding: 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .danger-zone-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-danger {
                width: 100%;
            }
        }
    </style>
    @endpush
</x-layouts.account>
