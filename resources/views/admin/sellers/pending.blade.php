<x-admin::layouts>
    <x-slot:title>
        Seller Đang Chờ Duyệt
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            Seller Đang Chờ Duyệt
        </p>

        <div class="flex gap-x-2.5 items-center">
            <a href="{{ route('admin.sellers.index') }}" class="primary-button">
                Tất cả Sellers
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mt-4">
        @forelse($sellers as $seller)
            <div class="box-shadow rounded bg-white dark:bg-gray-900 p-4 mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $seller->shop_name }}</h3>
                        <p class="text-gray-600">{{ $seller->shop_slug }}</p>
                        <p class="mt-2">
                            <strong>Customer:</strong> {{ $seller->customer->first_name }} {{ $seller->customer->last_name }}<br>
                            <strong>Email:</strong> {{ $seller->contact_email }}<br>
                            <strong>Type:</strong> {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}<br>
                            <strong>Ngày đăng ký:</strong> {{ $seller->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('admin.sellers.show', $seller->id) }}" class="primary-button">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-gray-600">Không có seller nào đang chờ duyệt.</p>
            </div>
        @endforelse

        {{ $sellers->links() }}
    </div>
</x-admin::layouts>

@section('page_title')
    Seller Đang Chờ Duyệt
@endsection

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.back()"></i>
                    Seller Đang Chờ Duyệt
                </h1>
            </div>
            <div class="page-action">
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-lg btn-primary">
                    Tất cả Sellers
                </a>
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Shop Name</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Business Type</th>
                            <th>Ngày đăng ký</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sellers as $seller)
                            <tr>
                                <td>{{ $seller->id }}</td>
                                <td>
                                    <strong>{{ $seller->shop_name }}</strong><br>
                                    <small>{{ $seller->shop_slug }}</small>
                                </td>
                                <td>{{ $seller->customer->first_name }} {{ $seller->customer->last_name }}</td>
                                <td>{{ $seller->contact_email }}</td>
                                <td>
                                    <span class="badge badge-{{ $seller->business_type == 'company' ? 'info' : 'secondary' }}">
                                        {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}
                                    </span>
                                </td>
                                <td>{{ $seller->created_at->format('d/m/Y H:i') }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.sellers.show', $seller->id) }}" class="btn btn-sm btn-primary">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 2rem;">
                                    <p>Không có seller nào đang chờ duyệt.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $sellers->links() }}
            </div>
        </div>
    </div>
@endsection
