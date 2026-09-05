@extends('layouts.master')

@section('page_title', 'AI Game Assistant - Làm Game')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<style>
*{box-sizing:border-box}
.ai-chat-page{display:flex;flex-direction:column;height:calc(100vh - 70px);max-width:900px;margin:0 auto;padding:0 16px}
.ai-header{padding:16px 0;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #e2e8f0}
.ai-header h1{font-size:1.2rem;font-weight:700;margin:0}
.ai-quota{font-size:.8rem;color:#64748b;background:#f1f5f9;padding:4px 10px;border-radius:12px}
.ai-messages{flex:1;overflow-y:auto;padding:20px 0;display:flex;flex-direction:column;gap:16px}
.ai-msg{max-width:85%;padding:12px 16px;border-radius:16px;font-size:.95rem;line-height:1.6;word-wrap:break-word}
.ai-msg.user{align-self:flex-end;background:#3b82f6;color:#fff;border-bottom-right-radius:4px}
.ai-msg.bot{align-self:flex-start;background:#f1f5f9;color:#1e293b;border-bottom-left-radius:4px}
.ai-msg.bot pre{background:#0f172a;color:#e2e8f0;padding:12px;border-radius:8px;overflow-x:auto;margin:8px 0;position:relative}
.ai-msg.bot pre code{font-size:.85rem}
.ai-msg.bot img{max-width:100%;border-radius:8px;margin:8px 0}
.ai-msg.bot p{margin:6px 0}
.ai-msg.bot ul,.ai-msg.bot ol{margin:6px 0;padding-left:20px}
.ai-typing{align-self:flex-start;padding:12px 20px;background:#f1f5f9;border-radius:16px;display:none}
.ai-typing span{display:inline-block;width:8px;height:8px;background:#94a3b8;border-radius:50%;margin:0 2px;animation:bounce .6s infinite alternate}
.ai-typing span:nth-child(2){animation-delay:.2s}
.ai-typing span:nth-child(3){animation-delay:.4s}
@keyframes bounce{to{transform:translateY(-6px)}}
.ai-input-area{padding:16px 0;border-top:1px solid #e2e8f0}
.ai-input-wrap{display:flex;gap:8px;align-items:flex-end}
.ai-input{flex:1;resize:none;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px;font-size:1rem;font-family:inherit;min-height:48px;max-height:150px;outline:none;transition:border .2s}
.ai-input:focus{border-color:#3b82f6}
.ai-send{width:44px;height:44px;border:none;border-radius:50%;background:#3b82f6;color:#fff;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s}
.ai-send:hover{background:#2563eb}
.ai-send:disabled{background:#94a3b8;cursor:not-allowed}
.ai-suggestions{display:flex;flex-wrap:wrap;gap:8px;padding:12px 0}
.ai-sug{padding:8px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:20px;font-size:.85rem;cursor:pointer;transition:.2s;color:#475569}
.ai-sug:hover{border-color:#3b82f6;color:#3b82f6}
.copy-btn{position:absolute;top:6px;right:6px;padding:4px 8px;background:#334155;color:#e2e8f0;border:none;border-radius:4px;font-size:.7rem;cursor:pointer}
.copy-btn:hover{background:#475569}
.ai-welcome{text-align:center;padding:40px 20px;color:#64748b}
.ai-welcome h2{font-size:1.5rem;color:#1e293b;margin-bottom:8px}
.ai-welcome p{margin-bottom:24px}
@media(max-width:768px){.ai-chat-page{height:calc(100vh - 60px)}.ai-msg{max-width:92%}}
</style>
@endpush

@section('content')
<div class="ai-chat-page">
    <div class="ai-header">
        <h1>🎮 AI Game Assistant</h1>
        <span class="ai-quota" id="ai-quota">Loading...</span>
    </div>

    <div class="ai-messages" id="ai-messages">
        <div class="ai-welcome" id="ai-welcome">
            <h2>Xin chào! Tôi là AI trợ lý game dev 🚀</h2>
            <p>Hỏi bất kỳ điều gì: tạo concept, sinh code, debug, review, tạo ảnh pixel art...</p>
            <div class="ai-suggestions">
                <div class="ai-sug" onclick="useSuggestion(this)">💡 Tạo concept game bắn trứng casual</div>
                <div class="ai-sug" onclick="useSuggestion(this)">💻 Code player controller Godot 4</div>
                <div class="ai-sug" onclick="useSuggestion(this)">🎨 Tạo pixel art hiệp sĩ 32x32</div>
                <div class="ai-sug" onclick="useSuggestion(this)">🐛 Debug lỗi collision Phaser</div>
                <div class="ai-sug" onclick="useSuggestion(this)">📋 Review code Unity PlayerController</div>
                <div class="ai-sug" onclick="useSuggestion(this)">🧪 Tạo unit test cho inventory system</div>
            </div>
        </div>
    </div>

    <div class="ai-typing" id="ai-typing"><span></span><span></span><span></span></div>

    <div class="ai-input-area">
        <div class="ai-input-wrap">
            <textarea class="ai-input" id="ai-input" placeholder="Hỏi AI bất cứ điều gì về game dev..." rows="1" onkeydown="handleKey(event)"></textarea>
            <button class="ai-send" id="ai-send" onclick="sendMessage()">➤</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/12.0.0/marked.min.js"></script>
<script>
const CHAT_API = '{{ url("/api/v1/ai-chat/message") }}';
const DASHBOARD_API = '{{ url("/api/v1/ai-tools/dashboard") }}';
const AUTH_TOKEN = @json($token);
let sessionId = null;
let isLoading = false;

const messagesEl = document.getElementById('ai-messages');
const inputEl = document.getElementById('ai-input');
const sendBtn = document.getElementById('ai-send');
const typingEl = document.getElementById('ai-typing');
const welcomeEl = document.getElementById('ai-welcome');

// Auto-resize textarea
inputEl.addEventListener('input', () => {
    inputEl.style.height = 'auto';
    inputEl.style.height = Math.min(inputEl.scrollHeight, 150) + 'px';
});

function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}

function useSuggestion(el) {
    inputEl.value = el.textContent.replace(/^[^\s]+\s/, '');
    sendMessage();
}

async function sendMessage() {
    const msg = inputEl.value.trim();
    if (!msg || isLoading) return;

    // Hide welcome
    if (welcomeEl) welcomeEl.style.display = 'none';

    // Add user message
    addMessage(msg, 'user');
    inputEl.value = '';
    inputEl.style.height = 'auto';

    // Show typing
    isLoading = true;
    sendBtn.disabled = true;
    typingEl.style.display = '';
    scrollBottom();

    const startedAt = performance.now();
    window.trackRevenueEvent?.('ai_tool_run', {tool_type: 'chat'});

    try {
        const r = await fetch(CHAT_API, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + AUTH_TOKEN
            },
            body: JSON.stringify({message: msg, session_id: sessionId, persona: 'game'})
        });

        const d = await r.json();

        if (r.ok) {
            sessionId = d.session_id;
            // Check events for images
            let imageHtml = '';
            (d.events || []).forEach(e => {
                if (e.type === 'tool_result' && e.content?.result?.type === 'image') {
                    const path = e.content.result.path.replace('./', '/');
                    imageHtml += `\n\n![Generated](${'/ohha-ai' + path})`;
                }
            });
            addMessage(d.response + imageHtml, 'bot');
            window.trackRevenueEvent?.('ai_tool_success', {
                tool_type: 'chat',
                duration_ms: Math.round(performance.now() - startedAt)
            });
            loadQuota();
        } else {
            window.trackRevenueEvent?.(r.status === 403 ? 'ai_quota_blocked' : 'ai_tool_error', {
                tool_type: 'chat',
                error_code: d.error || ('http_' + r.status)
            });
            addMessage('⚠️ ' + (d.detail || d.message || 'Lỗi. Vui lòng thử lại.'), 'bot');
        }
    } catch(e) {
        window.trackRevenueEvent?.('ai_tool_error', {tool_type: 'chat', error_code: 'network'});
        addMessage('⚠️ Không kết nối được. Vui lòng thử lại.', 'bot');
    }

    typingEl.style.display = 'none';
    isLoading = false;
    sendBtn.disabled = false;
    inputEl.focus();
}

function addMessage(text, role) {
    const div = document.createElement('div');
    div.className = 'ai-msg ' + role;

    if (role === 'bot') {
        // Fix image URLs to go through proxy
        const parsed = text.replace(/!\[([^\]]*)\]\(\/assets\//g, '![$1](/ohha-ai/assets/');
        div.innerHTML = marked.parse(parsed);
        // Add copy buttons to code blocks
        setTimeout(() => {
            div.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
            div.querySelectorAll('pre').forEach(pre => {
                const btn = document.createElement('button');
                btn.className = 'copy-btn';
                btn.textContent = 'Copy';
                btn.onclick = () => { navigator.clipboard.writeText(pre.textContent); btn.textContent = '✓'; setTimeout(()=>btn.textContent='Copy',2000); };
                pre.appendChild(btn);
            });
        }, 0);
    } else {
        div.textContent = text;
    }

    messagesEl.appendChild(div);
    scrollBottom();
}

function scrollBottom() {
    setTimeout(() => messagesEl.scrollTop = messagesEl.scrollHeight, 50);
}

// Load quota
async function loadQuota() {
    try {
        const r = await fetch(DASHBOARD_API, {
            headers: {'Accept': 'application/json', 'Authorization': 'Bearer ' + AUTH_TOKEN}
        });
        if (r.ok) {
            const d = await r.json();
            const quota = d.quota?.ai_concept;
            if (quota) {
                const limit = quota.limit === -1 ? '∞' : quota.limit;
                document.getElementById('ai-quota').textContent = `Chat: ${quota.used}/${limit} tháng này`;
            }
        }
    } catch(e) {}
}
window.trackRevenueEvent?.('view_ai_dashboard', {}, 'ai-dashboard-view');
loadQuota();
inputEl.focus();
</script>
@endpush
