@extends('layouts.master')

@section('page_title', 'AI Game Assistant - Làm Game')

@push('styles')
<style>
.ai-chat-wrap{max-width:900px;margin:0 auto;padding:16px;height:calc(100vh - 120px);display:flex;flex-direction:column}
.ai-chat-header{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #e2e8f0}
.ai-chat-header h1{font-size:1.2rem;font-weight:700;margin:0}
.ai-chat-header .badge{background:#10b981;color:#fff;font-size:.7rem;padding:2px 8px;border-radius:12px}
.ai-messages{flex:1;overflow-y:auto;padding:16px 0;display:flex;flex-direction:column;gap:12px}
.ai-msg{max-width:85%;padding:12px 16px;border-radius:12px;font-size:.95rem;line-height:1.6;white-space:pre-wrap;word-break:break-word}
.ai-msg.user{align-self:flex-end;background:#3b82f6;color:#fff;border-bottom-right-radius:4px}
.ai-msg.assistant{align-self:flex-start;background:#f1f5f9;color:#1e293b;border-bottom-left-radius:4px}
.ai-msg.tool{align-self:flex-start;background:#fefce8;color:#713f12;font-size:.85rem;border-left:3px solid #eab308}
.ai-msg.thinking{align-self:flex-start;background:#f0fdf4;color:#166534;font-size:.85rem;font-style:italic;border-left:3px solid #22c55e}
.ai-msg.error{align-self:flex-start;background:#fef2f2;color:#991b1b;border-left:3px solid #ef4444}
.ai-input-wrap{display:flex;gap:8px;padding-top:12px;border-top:1px solid #e2e8f0}
.ai-input{flex:1;padding:12px 16px;border:1px solid #e2e8f0;border-radius:24px;font-size:1rem;outline:none;font-family:inherit}
.ai-input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.1)}
.ai-send{padding:12px 24px;background:#3b82f6;color:#fff;border:none;border-radius:24px;font-weight:600;cursor:pointer}
.ai-send:hover{background:#2563eb}
.ai-send:disabled{opacity:.5;cursor:not-allowed}
.ai-typing{font-size:.85rem;color:#64748b;padding:4px 0;display:none}
</style>
@endpush

@section('content')
<div class="ai-chat-wrap">
    <div class="ai-chat-header">
        <h1>🎮 AI Game Assistant</h1>
        <span class="badge" id="ai-status">Connecting...</span>
    </div>
    <div class="ai-messages" id="ai-messages"></div>
    <div class="ai-typing" id="ai-typing">AI đang suy nghĩ...</div>
    <div class="ai-input-wrap">
        <input type="text" class="ai-input" id="ai-input" placeholder="Hỏi về game dev, code review, tạo GDD..." autocomplete="off">
        <button class="ai-send" id="ai-send" disabled>Gửi</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const messages = document.getElementById('ai-messages');
    const input = document.getElementById('ai-input');
    const sendBtn = document.getElementById('ai-send');
    const status = document.getElementById('ai-status');
    const typing = document.getElementById('ai-typing');

    const wsProto = location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = wsProto + '//' + location.host + '/ws/ai';
    let ws, currentAssistantMsg = null;

    function connect() {
        ws = new WebSocket(wsUrl);
        ws.onopen = () => {
            status.textContent = 'Online';
            status.style.background = '#10b981';
            sendBtn.disabled = false;
        };
        ws.onclose = () => {
            status.textContent = 'Offline';
            status.style.background = '#ef4444';
            sendBtn.disabled = true;
            setTimeout(connect, 3000);
        };
        ws.onerror = () => ws.close();
        ws.onmessage = (e) => handleEvent(JSON.parse(e.data));
    }

    function handleEvent(ev) {
        switch (ev.type) {
            case 'thinking':
                typing.style.display = 'block';
                addMsg('thinking', ev.content?.text || '...');
                break;
            case 'response':
                typing.style.display = 'none';
                appendAssistant(ev.content?.text || ev.content || '');
                break;
            case 'tool_call':
                addMsg('tool', '🔧 ' + (ev.content?.tool_name || 'tool') + ': ' + (ev.content?.summary || JSON.stringify(ev.content?.input || '').slice(0, 100)));
                break;
            case 'tool_result':
                addMsg('tool', '✅ ' + (ev.content?.text || '').slice(0, 300));
                break;
            case 'complete':
            case 'status_update':
                typing.style.display = 'none';
                currentAssistantMsg = null;
                sendBtn.disabled = false;
                break;
            case 'error':
                addMsg('error', '❌ ' + (ev.content?.message || ev.content || 'Error'));
                typing.style.display = 'none';
                sendBtn.disabled = false;
                break;
        }
        messages.scrollTop = messages.scrollHeight;
    }

    function addMsg(cls, text) {
        const div = document.createElement('div');
        div.className = 'ai-msg ' + cls;
        div.textContent = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function appendAssistant(text) {
        if (!currentAssistantMsg) {
            currentAssistantMsg = document.createElement('div');
            currentAssistantMsg.className = 'ai-msg assistant';
            messages.appendChild(currentAssistantMsg);
        }
        currentAssistantMsg.textContent += text;
    }

    function send() {
        const text = input.value.trim();
        if (!text || ws.readyState !== 1) return;
        addMsg('user', text);
        ws.send(JSON.stringify({ type: 'message', content: text, resume: true }));
        input.value = '';
        sendBtn.disabled = true;
        typing.style.display = 'block';
        currentAssistantMsg = null;
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keydown', (e) => { if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); } });

    connect();
})();
</script>
@endpush
