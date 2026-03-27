@extends('layouts.master')
@section('title', 'Quản lý phiên bản - ' . $product->name)

@section('content')
<section style="padding: 2rem 0; min-height: 60vh;">
    <div class="container" style="max-width: 800px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <a href="{{ route('seller.products.index') }}" style="color: #666; text-decoration: none;">← Sản phẩm</a>
                <h1 style="font-size: 1.5rem; font-weight: 700; margin-top: 0.5rem;">📦 Phiên bản: {{ $product->name }}</h1>
            </div>
        </div>

        <!-- Upload New Version -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="margin-bottom: 1rem;">Upload phiên bản mới</h3>
            <form method="POST" action="{{ route('seller.products.versions.store', $product->id) }}" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; margin-bottom: 1rem;">
                    <input type="text" name="version" placeholder="Ví dụ: 2.1.0" required
                           style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                    <input type="file" name="file" required
                           style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;">
                </div>
                <textarea name="changelog" rows="3" placeholder="Changelog: Mô tả thay đổi trong phiên bản này..."
                          style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 1rem; resize: vertical;"></textarea>
                <button type="submit" style="background: #2c5f41; color: white; padding: 0.75rem 2rem; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Upload phiên bản
                </button>
            </form>
        </div>

        <!-- Version History -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 style="margin-bottom: 1rem;">Lịch sử phiên bản</h3>
            @forelse($versions as $v)
                <div style="border-bottom: 1px solid #eee; padding: 1rem 0; {{ $loop->first ? 'border-left: 3px solid #2c5f41; padding-left: 1rem;' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-weight: 700; font-size: 1.1rem;">v{{ $v->version }}</span>
                            @if($loop->first) <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 10px; font-size: 0.75rem; margin-left: 0.5rem;">Mới nhất</span> @endif
                        </div>
                        <span style="color: #999; font-size: 0.85rem;">{{ $v->created_at->format('d/m/Y') }} · {{ number_format($v->file_size / 1048576, 1) }}MB · {{ $v->downloads }} downloads</span>
                    </div>
                    @if($v->changelog)
                        <p style="color: #555; margin-top: 0.5rem; white-space: pre-line;">{{ $v->changelog }}</p>
                    @endif
                </div>
            @empty
                <p style="color: #999; text-align: center; padding: 2rem;">Chưa có phiên bản nào.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
