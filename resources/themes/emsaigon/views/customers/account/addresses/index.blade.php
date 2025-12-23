<x-layouts.account>
    <x-slot:title>Địa chỉ của tôi</x-slot>

    <div class="addresses-container">
        <!-- Header -->
        <div class="addresses-header">
            <div>
                <h1 class="addresses-title">Địa chỉ của tôi</h1>
                <p class="addresses-subtitle">Quản lý địa chỉ giao hàng của bạn</p>
            </div>
            <a href="{{ route('shop.customers.account.addresses.create') }}" class="btn-add-address">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Thêm địa chỉ mới
            </a>
        </div>

        @if (! $addresses->isEmpty())
            <!-- Address Cards Grid -->
            <div class="addresses-grid">
                @foreach ($addresses as $address)
                    <div class="address-card {{ $address->default_address ? 'default' : '' }}">
                        <!-- Default Badge -->
                        @if ($address->default_address)
                            <div class="default-badge">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Mặc định
                            </div>
                        @endif

                        <!-- Address Info -->
                        <div class="address-info">
                            <div class="address-name">
                                {{ $address->first_name }} {{ $address->last_name }}
                            </div>
                            @if ($address->company_name)
                                <div class="address-company">{{ $address->company_name }}</div>
                            @endif
                            <div class="address-phone">{{ $address->phone }}</div>
                            <div class="address-detail">
                                {{ $address->address }}, {{ $address->city }}, {{ $address->state }}, {{ $address->country }}, {{ $address->postcode }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="address-actions">
                            <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}" class="btn-action btn-edit">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                                Sửa
                            </a>

                            @if (! $address->default_address)
                                <form method="POST" action="{{ route('shop.customers.account.addresses.update.default', $address->id) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-action btn-default">
                                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        Đặt mặc định
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('shop.customers.account.addresses.delete', $address->id) }}" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="120" height="120" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Chưa có địa chỉ nào</h3>
                <p class="empty-desc">Thêm địa chỉ giao hàng để thanh toán nhanh hơn</p>
                <a href="{{ route('shop.customers.account.addresses.create') }}" class="btn-add-first">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Thêm địa chỉ đầu tiên
                </a>
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .addresses-container { max-width: 100%; }
        .addresses-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .addresses-title { font-size: 1.75rem; font-weight: 700; color: #1f2937; margin: 0; }
        .addresses-subtitle { color: #6b7280; margin: 0.25rem 0 0 0; font-size: 0.875rem; }
        .btn-add-address { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border-radius: 8px; text-decoration: none; font-weight: 500; transition: all 0.2s; }
        .btn-add-address:hover { background: #1e4530; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,95,65,0.2); }
        
        .addresses-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 1.5rem; }
        .address-card { position: relative; background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; transition: all 0.2s; }
        .address-card:hover { border-color: #2c5f41; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .address-card.default { border-color: #2c5f41; background: linear-gradient(to bottom, #f0f9f4 0%, white 100%); }
        
        .default-badge { position: absolute; top: 1rem; right: 1rem; display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; background: #2c5f41; color: white; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        
        .address-info { margin-bottom: 1rem; }
        .address-name { font-size: 1.125rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem; }
        .address-company { font-size: 0.875rem; color: #6b7280; margin-bottom: 0.25rem; font-style: italic; }
        .address-phone { font-size: 0.875rem; color: #2c5f41; font-weight: 500; margin-bottom: 0.5rem; }
        .address-detail { font-size: 0.875rem; color: #6b7280; line-height: 1.5; }
        
        .address-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; padding-top: 1rem; border-top: 1px solid #e5e7eb; }
        .btn-action { display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 0.875rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.2s; text-decoration: none; background: white; }
        .btn-action:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .btn-edit { color: #2563eb; border-color: #2563eb; }
        .btn-edit:hover { background: #eff6ff; }
        .btn-default { color: #f59e0b; border-color: #f59e0b; }
        .btn-default:hover { background: #fffbeb; }
        .btn-delete { color: #dc2626; border-color: #dc2626; }
        .btn-delete:hover { background: #fef2f2; }
        
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; text-align: center; }
        .empty-icon { color: #d1d5db; margin-bottom: 1.5rem; }
        .empty-title { font-size: 1.5rem; font-weight: 600; color: #1f2937; margin: 0 0 0.5rem 0; }
        .empty-desc { font-size: 1rem; color: #6b7280; margin: 0 0 2rem 0; }
        .btn-add-first { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.875rem 1.75rem; background: #2c5f41; color: white; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 1rem; transition: all 0.2s; }
        .btn-add-first:hover { background: #1e4530; transform: translateY(-2px); box-shadow: 0 6px 16px rgba(44,95,65,0.3); }
        
        @media (max-width: 768px) {
            .addresses-header { flex-direction: column; align-items: stretch; }
            .addresses-title { font-size: 1.5rem; }
            .btn-add-address { width: 100%; justify-content: center; }
            .addresses-grid { grid-template-columns: 1fr; }
            .address-card { padding: 1.25rem; }
            .address-actions { flex-direction: column; }
            .btn-action { width: 100%; justify-content: center; }
        }
    </style>
    @endpush
</x-layouts.account>
