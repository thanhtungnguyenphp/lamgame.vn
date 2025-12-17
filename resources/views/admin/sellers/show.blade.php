@extends('admin::layouts.master')

@section('page_title')
    Chi tiết Seller
@endsection

@section('content-wrapper')
    <div class="content full-page">
        <div class="page-header">
            <div class="page-title">
                <h1>
                    <i class="icon angle-left-icon back-link" onclick="history.back()"></i>
                    Chi tiết Seller: {{ $seller->shop_name }}
                </h1>
            </div>
            <div class="page-action">
                @if($seller->isPending())
                    <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-success" onclick="return confirm('Duyệt seller này?')">
                            ✓ Duyệt
                        </button>
                    </form>
                    <button type="button" class="btn btn-lg btn-danger" onclick="showRejectModal()">
                        ✗ Từ chối
                    </button>
                @elseif($seller->isActive())
                    <form action="{{ route('admin.sellers.suspend', $seller->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-warning" onclick="return confirm('Tạm ngưng seller này?')">
                            Tạm ngưng
                        </button>
                    </form>
                @elseif($seller->isSuspended())
                    <form action="{{ route('admin.sellers.activate', $seller->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-lg btn-success" onclick="return confirm('Kích hoạt lại seller này?')">
                            Kích hoạt
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="sale-container">
                <!-- Shop Info -->
                <accordian title="Thông tin Shop" :active="true">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Shop Name</label>
                                            <p><strong>{{ $seller->shop_name }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Shop Slug</label>
                                            <p>{{ $seller->shop_slug }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Mô tả</label>
                                    <p>{{ $seller->shop_description ?: 'Chưa có mô tả' }}</p>
                                </div>
                                <div class="row">
                                    @if($seller->shop_logo)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Logo</label><br>
                                            <img src="{{ $seller->logo_url }}" alt="Logo" style="max-width: 200px; border: 1px solid #ddd; padding: 10px;">
                                        </div>
                                    </div>
                                    @endif
                                    @if($seller->shop_banner)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Banner</label><br>
                                            <img src="{{ $seller->banner_url }}" alt="Banner" style="max-width: 400px; border: 1px solid #ddd; padding: 10px;">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <!-- Contact Info -->
                <accordian title="Thông tin liên hệ" :active="true">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <p>{{ $seller->contact_email }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <p>{{ $seller->contact_phone ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Website</label>
                                            <p>{{ $seller->website ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <!-- Business Info -->
                <accordian title="Thông tin doanh nghiệp" :active="true">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Loại hình</label>
                                            <p>
                                                <span class="badge badge-{{ $seller->business_type == 'company' ? 'info' : 'secondary' }}">
                                                    {{ $seller->business_type == 'company' ? 'Công ty' : 'Cá nhân' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    @if($seller->business_type == 'company')
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Mã số thuế</label>
                                            <p>{{ $seller->tax_id }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <!-- Bank Info -->
                <accordian title="Thông tin ngân hàng" :active="true">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Ngân hàng</label>
                                            <p>{{ $seller->bank_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Số tài khoản</label>
                                            <p>{{ $seller->bank_account }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Chủ tài khoản</label>
                                            <p>{{ $seller->bank_holder }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <!-- Status & Stats -->
                <accordian title="Trạng thái & Thống kê" :active="true">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <p>
                                                <span class="badge badge-{{ $seller->status == 'active' ? 'success' : ($seller->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($seller->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Tổng sản phẩm</label>
                                            <p><strong>{{ $seller->total_products }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Tổng đơn hàng</label>
                                            <p><strong>{{ $seller->total_sales }}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label>Doanh thu</label>
                                            <p><strong>{{ number_format($seller->total_revenue, 0, ',', '.') }}đ</strong></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Ngày đăng ký</label>
                                            <p>{{ $seller->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @if($seller->verified_at)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Ngày duyệt</label>
                                            <p>{{ $seller->verified_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>

                <!-- Customer Info -->
                <accordian title="Thông tin khách hàng" :active="false">
                    <div slot="body">
                        <div class="sale-section">
                            <div class="section-content">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Tên</label>
                                            <p>{{ $seller->customer->first_name }} {{ $seller->customer->last_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <p>{{ $seller->customer->email }}</p>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <p>{{ $seller->customer->phone ?: 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </accordian>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <modal id="rejectModal" :is-open="modalOpen">
        <h3 slot="header">Từ chối Seller</h3>
        <div slot="body">
            <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" id="rejectForm">
                @csrf
                <div class="form-group">
                    <label for="reason">Lý do từ chối <span class="required">*</span></label>
                    <textarea name="reason" id="reason" class="control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-lg btn-primary">Xác nhận từ chối</button>
            </form>
        </div>
    </modal>

    <script>
        function showRejectModal() {
            window.modalOpen = true;
        }
    </script>
@endsection
