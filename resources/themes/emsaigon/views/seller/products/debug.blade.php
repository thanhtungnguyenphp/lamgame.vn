@extends('shop::seller.layouts.master')

@section('page_title', 'Debug Products')

@section('content')
<div style="background: #f8f9fa; min-height: 100vh; padding: 2rem 0;">
    <div class="container" style="max-width: 1200px;">
        <h1 style="margin-bottom: 2rem;">🔍 Debug Products</h1>

        @php
            $seller = auth()->guard('customer')->user()->seller;
            $allProducts = \Webkul\Product\Models\Product::where('company_id', $seller->id)->get();
            $downloadableProducts = \Webkul\Product\Models\Product::where('company_id', $seller->id)->where('type', 'downloadable')->get();
        @endphp

        <div style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h2>Seller Info</h2>
            <ul>
                <li><strong>Seller ID:</strong> {{ $seller->id }}</li>
                <li><strong>Shop Name:</strong> {{ $seller->shop_name }}</li>
                <li><strong>Status:</strong> {{ $seller->status }}</li>
            </ul>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h2>Products Count</h2>
            <ul>
                <li><strong>All Products:</strong> {{ $allProducts->count() }}</li>
                <li><strong>Downloadable Products:</strong> {{ $downloadableProducts->count() }}</li>
            </ul>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 12px; margin-bottom: 1.5rem;">
            <h2>All Products ({{ $allProducts->count() }})</h2>
            @if($allProducts->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 0.75rem; text-align: left;">ID</th>
                            <th style="padding: 0.75rem; text-align: left;">SKU</th>
                            <th style="padding: 0.75rem; text-align: left;">Type</th>
                            <th style="padding: 0.75rem; text-align: left;">Company ID</th>
                            <th style="padding: 0.75rem; text-align: left;">Status</th>
                            <th style="padding: 0.75rem; text-align: left;">Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allProducts as $product)
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 0.75rem;">{{ $product->id }}</td>
                                <td style="padding: 0.75rem;">{{ $product->sku }}</td>
                                <td style="padding: 0.75rem;">
                                    <span style="padding: 0.25rem 0.75rem; background: {{ $product->type == 'downloadable' ? '#d1fae5' : '#fee2e2' }}; border-radius: 9999px; font-size: 0.875rem;">
                                        {{ $product->type }}
                                    </span>
                                </td>
                                <td style="padding: 0.75rem;">{{ $product->company_id }}</td>
                                <td style="padding: 0.75rem;">{{ $product->status }}</td>
                                <td style="padding: 0.75rem;">{{ $product->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="color: #6b7280;">Không có sản phẩm nào</p>
            @endif
        </div>

        <div style="background: white; padding: 2rem; border-radius: 12px;">
            <h2>Product Flat Data</h2>
            @foreach($allProducts as $product)
                <div style="padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 1rem;">
                    <strong>Product #{{ $product->id }}</strong>
                    @if($product->flat)
                        <ul>
                            <li><strong>Name:</strong> {{ $product->flat->name ?? 'N/A' }}</li>
                            <li><strong>URL Key:</strong> {{ $product->flat->url_key ?? 'N/A' }}</li>
                            <li><strong>Price:</strong> {{ $product->flat->price ?? 'N/A' }}</li>
                        </ul>
                    @else
                        <p style="color: #dc2626;">⚠️ Không có flat data</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div style="margin-top: 2rem;">
            <a href="{{ route('seller.products.index') }}" style="padding: 0.75rem 1.5rem; background: #2c5f41; color: white; border-radius: 8px; text-decoration: none;">
                ← Quay lại Products
            </a>
        </div>
    </div>
</div>
@endsection
