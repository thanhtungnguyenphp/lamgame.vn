@extends('layouts.master')

@section('page_title', 'Tin nhắn - Forum')

@push('styles')
<style>
.msg-layout{display:flex;height:calc(100vh - 180px);max-width:1000px;margin:1.5rem auto;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#fff}
.msg-sidebar{width:300px;border-right:1px solid #e2e8f0;display:flex;flex-direction:column}
.msg-sidebar-hdr{padding:16px;border-bottom:1px solid #e2e8f0;font-weight:700;font-size:1.1rem}
.msg-list{flex:1;overflow-y:auto}
.msg-item{padding:12px 16px;border-bottom:1px solid #f1f5f9;cursor:pointer;display:flex;gap:10px;align-items:center}
.msg-item:hover,.msg-item.active{background:#eff6ff}
.msg-item .avatar{width:36px;height:36px;border-radius:50%;background:#6a4c93;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0}
.msg-item .info{flex:1;min-width:0}
.msg-item .name{font-weight:600;font-size:.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.msg-item .preview{font-size:.8rem;color:#64748b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.msg-item .time{font-size:.75rem;color:#94a3b8}
.msg-chat{flex:1;display:flex;flex-direction:column}
.msg-chat-hdr{padding:14px 16px;border-bottom:1px solid #e2e8f0;font-weight:600}
.msg-messages{flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:8px}
.msg-bubble{max-width:70%;padding:10px 14px;border-radius:12px;font-size:.9rem;line-height:1.4;word-break:break-word}
.msg-bubble.mine{align-self:flex-end;background:#6a4c93;color:#fff;border-bottom-right-radius:4px}
.msg-bubble.theirs{align-self:flex-start;background:#f1f5f9;color:#1e293b;border-bottom-left-radius:4px}
.msg-input{padding:12px 16px;border-top:1px solid #e2e8f0;display:flex;gap:8px}
.msg-input input{flex:1;padding:10px 14px;border:1px solid #e2e8f0;border-radius:8px;font-size:.9rem}
.msg-input button{padding:10px 20px;background:#6a4c93;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer}
.msg-empty{display:flex;align-items:center;justify-content:center;flex:1;color:#94a3b8;font-size:1rem}
@media(max-width:768px){.msg-sidebar{width:100%;position:absolute;z-index:2;background:#fff}.msg-chat{display:none}.msg-layout{position:relative}}
</style>
@endpush

@section('content')
<div class="msg-layout">
    <div class="msg-sidebar">
        <div class="msg-sidebar-hdr">💬 Tin nhắn</div>
        <div class="msg-list" id="convList"><div style="padding:20px;text-align:center;color:#94a3b8">Đang tải...</div></div>
    </div>
    <div class="msg-chat" id="chatPanel">
        <div class="msg-empty">Chọn cuộc trò chuyện để bắt đầu</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const API_BASE = '/api/v1/forum';
const headers = {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''};
let activeConv = null;

async function loadConversations() {
    const r = await fetch(API_BASE + '/conversations', {headers});
    const data = await r.json();
    const list = document.getElementById('convList');
    if (!data.length) { list.innerHTML = '<div style="padding:20px;text-align:center;color:#94a3b8">Chưa có tin nhắn</div>'; return; }
    list.innerHTML = data.map(c => `
        <div class="msg-item" onclick="openConv(${c.id}, '${c.other_user?.name||'User'}')">
            <div class="avatar">${(c.other_user?.name||'U')[0].toUpperCase()}</div>
            <div class="info"><div class="name">${c.other_user?.name||'User'}</div><div class="preview">${c.last_message?.content||''}</div></div>
            <div class="time">${c.last_message ? timeAgo(c.last_message.created_at) : ''}</div>
        </div>`).join('');
}

async function openConv(id, name) {
    activeConv = id;
    const panel = document.getElementById('chatPanel');
    panel.innerHTML = `<div class="msg-chat-hdr">${name}</div><div class="msg-messages" id="msgList"></div>
        <div class="msg-input"><input id="msgInput" placeholder="Nhập tin nhắn..." onkeydown="if(event.key==='Enter')sendMsg()"><button onclick="sendMsg()">Gửi</button></div>`;
    const r = await fetch(API_BASE + '/conversations/' + id + '/messages', {headers});
    const msgs = await r.json();
    const list = document.getElementById('msgList');
    list.innerHTML = (msgs.data||msgs).map(m => `<div class="msg-bubble ${m.is_mine?'mine':'theirs'}">${escHtml(m.content)}</div>`).join('');
    list.scrollTop = list.scrollHeight;
    fetch(API_BASE + '/conversations/' + id + '/read', {method:'PATCH', headers});
}

async function sendMsg() {
    const input = document.getElementById('msgInput');
    const content = input.value.trim();
    if (!content || !activeConv) return;
    input.value = '';
    const list = document.getElementById('msgList');
    list.innerHTML += `<div class="msg-bubble mine">${escHtml(content)}</div>`;
    list.scrollTop = list.scrollHeight;
    await fetch(API_BASE + '/conversations/' + activeConv + '/messages', {method:'POST', headers, body: JSON.stringify({content})});
}

function escHtml(t){const d=document.createElement('div');d.textContent=t;return d.innerHTML;}
function timeAgo(d){const m=Math.floor((Date.now()-new Date(d))/60000);return m<60?m+'m':m<1440?Math.floor(m/60)+'h':Math.floor(m/1440)+'d';}

loadConversations();
</script>
@endpush
