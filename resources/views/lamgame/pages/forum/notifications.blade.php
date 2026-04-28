@extends('layouts.master')

@section('page_title', 'Thông báo - Forum')

@section('content')
<div class="fm-page">
    <div class="fm-hdr">
        <div class="container">
            <div class="fm-hdr-row">
                <div>
                    <h1 class="fm-hdr-title">🔔 Thông báo</h1>
                    <p class="fm-hdr-desc">{{ $unreadCount }} thông báo chưa đọc</p>
                </div>
                <div style="display:flex;gap:0.75rem;align-items:center;">
                    @if($unreadCount > 0)
                    <button onclick="markAllRead()" class="fm-btn-create" style="background:#64748b;font-size:0.85rem;padding:0.5rem 1rem;">
                        <i class="fas fa-check-double"></i> Đọc tất cả
                    </button>
                    @endif
                    <a href="{{ route('forum.index') }}" class="fm-btn-create" style="background:#64748b;font-size:0.85rem;padding:0.5rem 1rem;">
                        <i class="fas fa-arrow-left"></i> Forum
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="fn-list" style="max-width:700px;margin:1.5rem auto;">
            @forelse($notifications as $notif)
            <a href="{{ $notif->url }}" class="fn-item {{ $notif->read_at ? '' : 'fn-unread' }}" onclick="markRead(event, {{ $notif->id }})">
                <div class="fn-icon">
                    @switch($notif->type)
                        @case('reply_post') <i class="fas fa-comment" style="color:#6a4c93;"></i> @break
                        @case('reply_comment') <i class="fas fa-reply" style="color:#3b82f6;"></i> @break
                        @case('best_answer') <i class="fas fa-check-circle" style="color:#10b981;"></i> @break
                        @case('mention') <i class="fas fa-at" style="color:#f59e0b;"></i> @break
                        @default <i class="fas fa-bell" style="color:#94a3b8;"></i>
                    @endswitch
                </div>
                <div class="fn-content">
                    <div class="fn-title">{{ $notif->title }}</div>
                    @if($notif->body)<div class="fn-body">{{ $notif->body }}</div>@endif
                    <div class="fn-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notif->read_at)<span class="fn-dot"></span>@endif
            </a>
            @empty
            <div style="text-align:center;padding:3rem 1rem;color:#94a3b8;">
                <div style="font-size:2.5rem;margin-bottom:0.75rem;">🔕</div>
                <h3 style="color:#475569;">Chưa có thông báo nào</h3>
                <p>Bạn sẽ nhận thông báo khi có người trả lời hoặc nhắc đến bạn.</p>
            </div>
            @endforelse

            @if($notifications->hasPages())
            <div style="margin-top:1.5rem;">{{ $notifications->links() }}</div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.fm-page { min-height: 60vh; }
.fm-hdr { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; padding: 1.5rem 0; }
.fm-hdr-row { display: flex; justify-content: space-between; align-items: center; }
.fm-hdr-title { font-size: 1.5rem; font-weight: 700; margin: 0; }
.fm-hdr-desc { color: #a0aec0; font-size: 0.9rem; margin: 0.25rem 0 0; }
.fm-btn-create { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; font-size: 0.9rem; background: linear-gradient(135deg, #6a4c93, #9b5de5); color: #fff; text-decoration: none; border: none; cursor: pointer; }

.fn-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: background .15s; position: relative; }
.fn-item:hover { background: #f8fafc; }
.fn-unread { background: #eff6ff; }
.fn-icon { width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.9rem; }
.fn-content { flex: 1; min-width: 0; }
.fn-title { font-size: 0.9rem; color: #1a202c; font-weight: 500; line-height: 1.4; }
.fn-body { font-size: 0.8rem; color: #64748b; margin-top: 0.2rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.fn-time { font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem; }
.fn-dot { width: 8px; height: 8px; border-radius: 50%; background: #3b82f6; flex-shrink: 0; margin-top: 0.5rem; }
</style>
@endpush

@push('scripts')
<script>
function markRead(e, id) {
    fetch('{{ route("forum.notifications.read") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
        body: JSON.stringify({id: id}),
    });
}
function markAllRead() {
    fetch('{{ route("forum.notifications.read") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json'},
        body: JSON.stringify({all: true}),
    }).then(() => location.reload());
}
</script>
@endpush
@endsection
