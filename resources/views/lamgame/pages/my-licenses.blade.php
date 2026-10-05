{{-- My Licenses — Danh sách license key người dùng đã mua --}}
@extends('layouts.master')

@section('page_title', 'License của tôi')

@push('meta')
<meta name="robots" content="noindex, nofollow">
@endpush

@section('content')
<div style="max-width: 960px; margin: 0 auto; padding: 2rem 1rem;">
    <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem;">🔑 License của tôi</h1>
    <p style="color: #7A8599; margin-bottom: 1.5rem;">Danh sách giấy phép (license key) cho các source game bạn đã mua.</p>

    @forelse($licenses as $license)
        <div style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: 1rem; background: #fff;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                <div style="font-weight: 700; font-size: 1.05rem;">
                    @if($license->product)
                        <a href="{{ url('source-game/' . $license->product->url_key) }}" style="color: #2c5f41; text-decoration: none;">
                            {{ $license->product->name }}
                        </a>
                    @else
                        Sản phẩm #{{ $license->product_id }}
                    @endif
                </div>
                <span style="background: #ede9fe; color: #6d28d9; padding: 2px 10px; border-radius: 12px; font-size: 0.8rem;">
                    {{ $license->licenseType->name ?? 'Standard' }}
                </span>
            </div>

            <div style="margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <code id="lk-{{ $license->id }}" style="background: #f3f4f6; padding: 6px 12px; border-radius: 6px; font-size: 0.95rem; letter-spacing: 0.5px;">{{ $license->key }}</code>
                <button type="button" onclick="lgCopyLicense('lk-{{ $license->id }}', this)" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; background: #fff; cursor: pointer; font-size: 0.85rem;">Sao chép</button>
            </div>

            <div style="margin-top: 0.5rem; color: #9ca3af; font-size: 0.8rem;">
                Kích hoạt: {{ optional($license->activated_at)->format('d/m/Y') ?? '—' }}
                @if($license->expires_at) · Hết hạn: {{ $license->expires_at->format('d/m/Y') }} @else · Vĩnh viễn @endif
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 3rem 1rem; color: #9ca3af;">
            <p>Bạn chưa có license nào. Khi mua source game, license key sẽ xuất hiện ở đây.</p>
            <a href="{{ route('lamgame.source-game') }}" style="display: inline-block; margin-top: 1rem; color: #2c5f41; font-weight: 600;">Khám phá Source Game →</a>
        </div>
    @endforelse

    @if($licenses->hasPages())
        <div style="margin-top: 1.5rem;">{{ $licenses->links() }}</div>
    @endif
</div>

<script>
function lgCopyLicense(id, btn){
    const el = document.getElementById(id);
    if(!el) return;
    navigator.clipboard.writeText(el.textContent.trim()).then(() => {
        const orig = btn.textContent;
        btn.textContent = 'Đã sao chép ✓';
        setTimeout(() => { btn.textContent = orig; }, 1500);
    });
}
</script>
@endsection
