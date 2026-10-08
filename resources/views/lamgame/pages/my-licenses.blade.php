<x-layouts.account>
    <x-slot:title>
        License của tôi
    </x-slot>

    <style>
        .lic-head { font-size: 1.5rem; font-weight: 800; color: #F5F7FA; margin-bottom: 0.25rem; }
        .lic-sub { color: #7A8599; margin-bottom: 1.5rem; }
        .lic-card {
            background: rgba(255,255,255,.03);
            border: 1px solid rgba(124,92,255,.15);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
        }
        .lic-card__top { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
        .lic-card__name { font-weight: 700; font-size: 1.05rem; }
        .lic-card__name a { color: #F5F7FA; text-decoration: none; }
        .lic-card__name a:hover { color: #00D1FF; }
        .lic-type {
            background: rgba(124,92,255,.15); color: #a78bfa;
            padding: 3px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;
            border: 1px solid rgba(124,92,255,.25);
        }
        .lic-key-row { margin-top: 1rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .lic-key {
            background: #070B14; border: 1px solid rgba(124,92,255,.2); color: #00D1FF;
            padding: 8px 14px; border-radius: 8px; font-size: 0.95rem; letter-spacing: 0.5px;
            font-family: 'Space Grotesk', monospace; word-break: break-all;
        }
        .lic-copy {
            padding: 8px 14px; border: 1px solid rgba(124,92,255,.25); border-radius: 8px;
            background: transparent; color: #B7C0D1; cursor: pointer; font-size: 0.85rem;
            transition: all .18s; white-space: nowrap;
        }
        .lic-copy:hover { background: rgba(124,92,255,.1); color: #fff; }
        .lic-meta { margin-top: 0.75rem; color: #7A8599; font-size: 0.82rem; }
        .lic-empty { text-align: center; padding: 3rem 1rem; color: #7A8599; border: 1px dashed rgba(124,92,255,.2); border-radius: 14px; }
        .lic-empty a { color: #00D1FF; font-weight: 600; text-decoration: none; }
    </style>

    <div class="lic-head">{{ __('lamgame.license.title') }}</div>
    <p class="lic-sub">{{ __('lamgame.license.subtitle') }}</p>

    @forelse($licenses as $license)
        <div class="lic-card">
            <div class="lic-card__top">
                <div class="lic-card__name">
                    @if($license->product)
                        <a href="{{ url('source-game/' . $license->product->url_key) }}">{{ $license->product->name }}</a>
                    @else
                        Sản phẩm #{{ $license->product_id }}
                    @endif
                </div>
                <span class="lic-type">{{ $license->licenseType->name ?? 'Standard' }}</span>
            </div>

            <div class="lic-key-row">
                <code id="lk-{{ $license->id }}" class="lic-key">{{ $license->key }}</code>
                <button type="button" class="lic-copy" onclick="lgCopyLicense('lk-{{ $license->id }}', this)">{{ __('lamgame.license.copy') }}</button>
            </div>

            <div class="lic-meta">
                {{ __('lamgame.license.activated') }}: {{ optional($license->activated_at)->format('d/m/Y') ?? '—' }}
                @if($license->expires_at) · {{ __('lamgame.license.expires') }}: {{ $license->expires_at->format('d/m/Y') }} @else · {{ __('lamgame.license.lifetime') }} @endif
            </div>
        </div>
    @empty
        <div class="lic-empty">
            <p style="margin-bottom:1rem">{{ __('lamgame.license.empty') }}</p>
            <a href="{{ route('lamgame.source-game') }}">{{ __('lamgame.license.explore') }}</a>
        </div>
    @endforelse

    @if($licenses->hasPages())
        <div style="margin-top: 1.5rem;">{{ $licenses->links() }}</div>
    @endif

    <script>
    function lgCopyLicense(id, btn){
        const el = document.getElementById(id);
        if(!el) return;
        navigator.clipboard.writeText(el.textContent.trim()).then(() => {
            const orig = btn.textContent;
            btn.textContent = @json(__('lamgame.license.copied'));
            setTimeout(() => { btn.textContent = orig; }, 1500);
        });
    }
    </script>
</x-layouts.account>
