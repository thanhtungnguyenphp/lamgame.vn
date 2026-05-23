@extends('layouts.master')

@section('page_title', 'AI Tools Dashboard - Làm Game')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
<style>
.ait-layout{display:flex;gap:24px;max-width:1200px;margin:0 auto;padding:24px 16px}
.ait-sidebar{width:220px;flex-shrink:0}
.ait-main{flex:1;min-width:0}
.ait-nav a{display:block;padding:10px 14px;border-radius:8px;color:#475569;text-decoration:none;font-size:.9rem;margin-bottom:2px}
.ait-nav a:hover,.ait-nav a.active{background:#eff6ff;color:#2563eb;font-weight:600}
.ait-nav a.locked{opacity:.5}
.ait-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:20px}
.ait-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:24px}
.ait-tool-card{text-align:center;padding:20px 12px;border-radius:12px;border:1px solid #e2e8f0;cursor:pointer;transition:.2s}
.ait-tool-card:hover{border-color:#3b82f6;box-shadow:0 2px 8px rgba(59,130,246,.15)}
.ait-tool-card .icon{font-size:2rem;margin-bottom:8px}
.ait-tool-card .name{font-weight:600;font-size:.9rem}
.ait-tool-card .quota{font-size:.8rem;color:#64748b;margin-top:4px}
.ait-quota-bar{height:6px;background:#e2e8f0;border-radius:3px;margin-top:6px;overflow:hidden}
.ait-quota-fill{height:100%;border-radius:3px;transition:width .3s}
.ait-textarea{width:100%;min-height:120px;padding:12px;border:1px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:.95rem;resize:vertical}
.ait-textarea:focus{outline:none;border-color:#3b82f6}
.ait-btn{padding:10px 24px;border:none;border-radius:8px;font-size:1rem;font-weight:600;cursor:pointer;background:#3b82f6;color:#fff}
.ait-btn:hover{background:#2563eb}
.ait-btn:disabled{opacity:.5;cursor:not-allowed}
.ait-response{background:#0f172a;color:#e2e8f0;border-radius:8px;padding:16px;margin-top:16px;font-size:.9rem;line-height:1.6;white-space:pre-wrap;overflow-x:auto;display:none}
.ait-select{padding:8px 12px;border:1px solid #e2e8f0;border-radius:6px;font-size:.9rem}
.ait-meta{font-size:.8rem;color:#64748b;margin-top:8px}
.ait-history-item{padding:12px;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;font-size:.9rem}
.ait-upsell{background:#fffbeb;border:1px solid #fbbf24;border-radius:8px;padding:16px;text-align:center;margin-top:16px;display:none}
@media(max-width:768px){.ait-layout{flex-direction:column}.ait-sidebar{width:100%}.ait-nav{display:flex;overflow-x:auto;gap:4px}.ait-nav a{white-space:nowrap}}
</style>
@endpush

@section('content')
<div class="ait-layout">
    {{-- Sidebar --}}
    <aside class="ait-sidebar">
        <div style="margin-bottom:16px">
            <div style="font-weight:700;font-size:1.1rem;margin-bottom:4px">AI Tools</div>
            <div style="font-size:.8rem;color:#64748b" id="ait-plan-label">Loading...</div>
        </div>
        <nav class="ait-nav">
            <a href="#" class="active" data-view="dashboard">📊 Dashboard</a>
            <a href="#" data-view="concept">💡 Game Concept</a>
            <a href="#" data-view="codegen">💻 Code Generate</a>
            <a href="#" data-view="debug">🐛 Debug</a>
            <a href="#" data-view="test">🧪 Unit Test</a>
            <a href="#" data-view="review">📋 Code Review</a>
            <a href="#" data-view="history">📜 History</a>
        </nav>
        <div style="margin-top:16px;padding:12px;background:#f8fafc;border-radius:8px;font-size:.8rem" id="ait-quota-summary"></div>
    </aside>

    {{-- Main Content --}}
    <main class="ait-main">
        {{-- Dashboard View --}}
        <div id="view-dashboard">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:16px">Dashboard</h2>
            <div class="ait-cards" id="ait-tool-cards"></div>
            <div class="ait-card">
                <h3 style="font-size:1rem;font-weight:600;margin-bottom:12px">Recent Activity</h3>
                <div id="ait-recent"></div>
            </div>
        </div>

        {{-- Concept View --}}
        <div id="view-concept" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:4px">💡 AI Game Concept</h2>
            <p style="color:#64748b;font-size:.9rem;margin-bottom:16px">Mô tả ý tưởng game → nhận Game Design Document mini</p>
            <div class="ait-card">
                <div style="display:flex;gap:12px;margin-bottom:12px">
                    <select class="ait-select" id="concept-platform"><option value="">Platform (tùy chọn)</option><option>mobile</option><option>web</option><option>pc</option><option>console</option></select>
                    <select class="ait-select" id="concept-genre"><option value="">Genre (tùy chọn)</option><option>shooter</option><option>rpg</option><option>puzzle</option><option>platformer</option><option>strategy</option><option>simulation</option></select>
                </div>
                <textarea class="ait-textarea" id="concept-prompt" placeholder="Mô tả ý tưởng game của bạn... (ít nhất 10 ký tự)"></textarea>
                <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                    <span class="ait-meta" id="concept-quota"></span>
                    <button class="ait-btn" onclick="submitTool('concept')">Generate ▶</button>
                </div>
                <div class="ait-response" id="concept-response"></div>
                <div class="ait-upsell" id="concept-upsell"></div>
            </div>
        </div>

        {{-- Codegen View --}}
        <div id="view-codegen" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:4px">💻 AI Code Generate</h2>
            <p style="color:#64748b;font-size:.9rem;margin-bottom:16px">Sinh code game production-ready theo engine & ngôn ngữ</p>
            <div class="ait-card">
                <div style="display:flex;gap:12px;margin-bottom:12px">
                    <select class="ait-select" id="codegen-engine" required><option value="">Engine *</option><option value="unity">Unity</option><option value="godot">Godot</option><option value="phaser">Phaser</option><option value="cocos">Cocos</option><option value="pygame">Pygame</option></select>
                    <select class="ait-select" id="codegen-language" required><option value="">Language *</option><option value="csharp">C#</option><option value="gdscript">GDScript</option><option value="javascript">JavaScript</option><option value="typescript">TypeScript</option><option value="python">Python</option></select>
                </div>
                <textarea class="ait-textarea" id="codegen-prompt" placeholder="Mô tả code cần tạo... (vd: Player controller 2D platformer với wall jump)"></textarea>
                <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                    <span class="ait-meta" id="codegen-quota"></span>
                    <button class="ait-btn" onclick="submitTool('codegen')">Generate ▶</button>
                </div>
                <div class="ait-response" id="codegen-response"></div>
                <div class="ait-upsell" id="codegen-upsell"></div>
            </div>
        </div>

        {{-- Debug View --}}
        <div id="view-debug" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:4px">🐛 AI Debug</h2>
            <p style="color:#64748b;font-size:.9rem;margin-bottom:16px">Paste code + mô tả lỗi → nhận root cause + fix</p>
            <div class="ait-card">
                <textarea class="ait-textarea" id="debug-prompt" placeholder="Mô tả lỗi / hành vi mong muốn..." style="min-height:80px"></textarea>
                <textarea class="ait-textarea" id="debug-code" placeholder="Paste code cần debug..." style="min-height:150px;margin-top:8px;font-family:monospace;font-size:.85rem"></textarea>
                <textarea class="ait-textarea" id="debug-error" placeholder="Error log (tùy chọn)..." style="min-height:60px;margin-top:8px;font-family:monospace;font-size:.85rem"></textarea>
                <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                    <span class="ait-meta" id="debug-quota"></span>
                    <button class="ait-btn" onclick="submitTool('debug')">Debug ▶</button>
                </div>
                <div class="ait-response" id="debug-response"></div>
                <div class="ait-upsell" id="debug-upsell"></div>
            </div>
        </div>

        {{-- Test View --}}
        <div id="view-test" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:4px">🧪 AI Unit Test</h2>
            <p style="color:#64748b;font-size:.9rem;margin-bottom:16px">Paste code → nhận test suite hoàn chỉnh</p>
            <div class="ait-card">
                <div style="display:flex;gap:12px;margin-bottom:12px">
                    <select class="ait-select" id="test-engine" required><option value="">Engine *</option><option value="unity">Unity</option><option value="godot">Godot</option><option value="phaser">Phaser</option><option value="cocos">Cocos</option><option value="pygame">Pygame</option></select>
                    <select class="ait-select" id="test-language" required><option value="">Language *</option><option value="csharp">C#</option><option value="gdscript">GDScript</option><option value="javascript">JavaScript</option><option value="typescript">TypeScript</option><option value="python">Python</option></select>
                </div>
                <textarea class="ait-textarea" id="test-code" placeholder="Paste code cần tạo test..." style="min-height:200px;font-family:monospace;font-size:.85rem"></textarea>
                <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                    <span class="ait-meta" id="test-quota"></span>
                    <button class="ait-btn" onclick="submitTool('test')">Generate Tests ▶</button>
                </div>
                <div class="ait-response" id="test-response"></div>
                <div class="ait-upsell" id="test-upsell"></div>
            </div>
        </div>

        {{-- Review View --}}
        <div id="view-review" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:4px">📋 AI Code Review</h2>
            <p style="color:#64748b;font-size:.9rem;margin-bottom:16px">Paste code → nhận review report (score + issues + fix)</p>
            <div class="ait-card">
                <textarea class="ait-textarea" id="review-code" placeholder="Paste code cần review..." style="min-height:250px;font-family:monospace;font-size:.85rem"></textarea>
                <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                    <span class="ait-meta" id="review-quota"></span>
                    <button class="ait-btn" onclick="submitTool('review')">Review ▶</button>
                </div>
                <div class="ait-response" id="review-response"></div>
                <div class="ait-upsell" id="review-upsell"></div>
            </div>
        </div>

        {{-- History View --}}
        <div id="view-history" style="display:none">
            <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:16px">📜 Lịch sử sử dụng</h2>
            <div class="ait-card" id="ait-history-list"><div style="color:#64748b;text-align:center;padding:20px">Loading...</div></div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/12.0.0/marked.min.js"></script>
<script>
const API = '{{ url("/api/v1/ai-tools") }}';
const TOKEN = '{{ $token ?? "" }}';
let dashboardData = null;

const headers = () => ({
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    ...(TOKEN ? {'Authorization': 'Bearer ' + TOKEN} : {})
});

// Navigation
document.querySelectorAll('.ait-nav a').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        document.querySelectorAll('.ait-nav a').forEach(n => n.classList.remove('active'));
        a.classList.add('active');
        const view = a.dataset.view;
        document.querySelectorAll('[id^="view-"]').forEach(v => v.style.display = 'none');
        document.getElementById('view-' + view).style.display = '';
        if (view === 'history') loadHistory();
    });
});

// Load dashboard
async function loadDashboard() {
    try {
        const r = await fetch(API + '/dashboard', {headers: headers()});
        if (!r.ok) { document.getElementById('ait-plan-label').textContent = 'Chưa đăng nhập'; return; }
        dashboardData = await r.json();
        renderDashboard(dashboardData);

        // Auto-subscribe if ?subscribe= param present
        const urlParams = new URLSearchParams(window.location.search);
        const subscribePlan = urlParams.get('subscribe');
        if (subscribePlan && subscribePlan !== 'free' && subscribePlan !== 'enterprise') {
            subscribeToPlan(subscribePlan);
            window.history.replaceState({}, '', window.location.pathname);
        }
    } catch(e) { console.error(e); }
}

async function subscribeToPlan(plan) {
    try {
        const r = await fetch('/api/v1/subscription/subscribe', {
            method: 'POST',
            headers: {...headers(), 'Content-Type': 'application/json'},
            body: JSON.stringify({plan: plan})
        });
        const d = await r.json();
        if (d.status === 'ok' && d.data?.approval_url) {
            window.location.href = d.data.approval_url;
        } else if (d.data?.status === 'active') {
            alert('Đã đăng ký gói ' + plan + ' thành công!');
            location.reload();
        } else {
            alert(d.error?.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    } catch(e) { alert('Vui lòng đăng nhập trước khi đăng ký gói.'); }
}

function renderDashboard(d) {
    // Plan label
    document.getElementById('ait-plan-label').textContent = d.plan ? d.plan.name + ' Plan' : 'Chưa subscribe';

    // Tool cards
    const tools = [
        {key:'ai_concept',name:'Game Concept',icon:'💡',view:'concept'},
        {key:'ai_generate',name:'Code Generate',icon:'💻',view:'codegen'},
        {key:'ai_debug',name:'Debug',icon:'🐛',view:'debug'},
        {key:'ai_test',name:'Unit Test',icon:'🧪',view:'test'},
        {key:'ai_code_review',name:'Code Review',icon:'📋',view:'review'},
    ];
    const cardsHtml = tools.map(t => {
        const q = d.quota[t.key] || {limit:0,used:0,remaining:0};
        const pct = q.limit === -1 ? 100 : (q.limit > 0 ? Math.max(0, (q.remaining/q.limit)*100) : 0);
        const color = pct > 50 ? '#22c55e' : pct > 20 ? '#eab308' : '#ef4444';
        const qText = q.limit === -1 ? '♾️' : q.limit === 0 ? '❌' : q.remaining + '/' + q.limit;
        return `<div class="ait-tool-card" onclick="switchView('${t.view}')">
            <div class="icon">${t.icon}</div>
            <div class="name">${t.name}</div>
            <div class="quota">${qText}</div>
            <div class="ait-quota-bar"><div class="ait-quota-fill" style="width:${pct}%;background:${color}"></div></div>
        </div>`;
    }).join('');
    document.getElementById('ait-tool-cards').innerHTML = cardsHtml;

    // Quota summary sidebar
    const qHtml = tools.map(t => {
        const q = d.quota[t.key] || {limit:0};
        const txt = q.limit === -1 ? '♾️' : q.limit === 0 ? '—' : (q.remaining ?? 0) + '/' + q.limit;
        return `<div style="display:flex;justify-content:space-between;margin-bottom:4px"><span>${t.icon} ${t.name}</span><span>${txt}</span></div>`;
    }).join('');
    document.getElementById('ait-quota-summary').innerHTML = qHtml;

    // Update per-tool quota labels
    tools.forEach(t => {
        const el = document.getElementById(t.view + '-quota');
        if (el) {
            const q = d.quota[t.key] || {limit:0};
            el.textContent = q.limit === -1 ? 'Unlimited' : q.limit === 0 ? 'Không khả dụng' : 'Còn ' + (q.remaining??0) + '/' + q.limit + ' lượt';
        }
    });

    // Recent history
    const recent = (d.recent_history || []).map(h =>
        `<div class="ait-history-item"><span>${toolIcon(h.tool_type)} ${h.prompt.substring(0,60)}...</span><span style="color:#94a3b8;font-size:.8rem">${timeAgo(h.created_at)}</span></div>`
    ).join('') || '<div style="color:#94a3b8;text-align:center;padding:16px">Chưa có hoạt động</div>';
    document.getElementById('ait-recent').innerHTML = recent;
}

function toolIcon(t) { return {concept:'💡',codegen:'💻',debug:'🐛',test:'🧪',review:'📋',asset:'🎨'}[t]||'🤖'; }
function timeAgo(d) { const m=Math.floor((Date.now()-new Date(d))/60000); return m<60?m+'m ago':Math.floor(m/60)+'h ago'; }
function switchView(v) { document.querySelector(`.ait-nav a[data-view="${v}"]`).click(); }

// Submit tool
async function submitTool(tool) {
    const btn = event.target; btn.disabled = true; btn.textContent = '⏳ Đang xử lý...';
    const respEl = document.getElementById(tool + '-response');
    const upsellEl = document.getElementById(tool + '-upsell');
    respEl.style.display = 'none'; upsellEl.style.display = 'none';

    let body = {};
    if (tool === 'concept') {
        body = {prompt: document.getElementById('concept-prompt').value, options: {platform: document.getElementById('concept-platform').value, genre: document.getElementById('concept-genre').value}};
    } else if (tool === 'codegen') {
        body = {prompt: document.getElementById('codegen-prompt').value, options: {engine: document.getElementById('codegen-engine').value, language: document.getElementById('codegen-language').value}};
    } else if (tool === 'debug') {
        body = {prompt: document.getElementById('debug-prompt').value, code: document.getElementById('debug-code').value, error_log: document.getElementById('debug-error').value};
    } else if (tool === 'test') {
        body = {code: document.getElementById('test-code').value, options: {engine: document.getElementById('test-engine').value, language: document.getElementById('test-language').value}};
    } else if (tool === 'review') {
        body = {code: document.getElementById('review-code').value};
    }

    try {
        const r = await fetch(API + '/' + tool, {method:'POST', headers: headers(), body: JSON.stringify(body)});
        const d = await r.json();

        if (d.error) {
            if (d.upsell) {
                upsellEl.innerHTML = `<p style="font-weight:600;margin-bottom:8px">⚡ ${d.message}</p><a href="${d.upsell.url}" style="color:#2563eb;font-weight:600">Nâng cấp ${d.upsell.plan} →</a>`;
                upsellEl.style.display = '';
            } else {
                respEl.textContent = '❌ ' + d.message;
                respEl.style.display = '';
            }
        } else {
            respEl.innerHTML = marked.parse(d.response || '');
            respEl.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
            respEl.style.display = '';
            // Update quota
            const qEl = document.getElementById(tool + '-quota');
            if (qEl && d.quota_remaining !== undefined) qEl.textContent = 'Còn ' + d.quota_remaining + ' lượt';
            // Add copy buttons
            respEl.querySelectorAll('pre').forEach(pre => {
                const cb = document.createElement('button');
                cb.textContent = '📋 Copy';
                cb.style.cssText = 'position:absolute;top:4px;right:4px;padding:4px 8px;background:#334155;color:#e2e8f0;border:none;border-radius:4px;font-size:.75rem;cursor:pointer';
                pre.style.position = 'relative';
                cb.onclick = () => { navigator.clipboard.writeText(pre.textContent); cb.textContent = '✅ Copied'; setTimeout(()=>cb.textContent='📋 Copy',2000); };
                pre.appendChild(cb);
            });
        }
    } catch(e) {
        respEl.textContent = '❌ Lỗi kết nối. Vui lòng thử lại.';
        respEl.style.display = '';
    }
    btn.disabled = false; btn.textContent = btn.textContent.includes('Debug') ? 'Debug ▶' : btn.textContent.includes('Review') ? 'Review ▶' : btn.textContent.includes('Tests') ? 'Generate Tests ▶' : 'Generate ▶';
}

// History
async function loadHistory() {
    try {
        const r = await fetch(API + '/history?per_page=30', {headers: headers()});
        const d = await r.json();
        const items = (d.data || []).map(h =>
            `<div class="ait-history-item" style="cursor:pointer" onclick="showHistoryDetail(${h.id})">
                <div><span>${toolIcon(h.tool_type)}</span> <strong>${h.tool_type}</strong> — ${(h.prompt||'').substring(0,80)}...</div>
                <div style="text-align:right;font-size:.8rem;color:#94a3b8">${h.model_used}<br>${new Date(h.created_at).toLocaleString('vi-VN')}</div>
            </div>`
        ).join('') || '<div style="color:#94a3b8;text-align:center;padding:20px">Chưa có lịch sử</div>';
        document.getElementById('ait-history-list').innerHTML = items;
    } catch(e) { console.error(e); }
}

async function showHistoryDetail(id) {
    try {
        const r = await fetch(API + '/history/' + id, {headers: headers()});
        const d = await r.json();
        const el = document.getElementById('ait-history-list');
        el.innerHTML = `<div style="margin-bottom:12px"><button onclick="loadHistory()" style="background:none;border:none;color:#3b82f6;cursor:pointer;font-size:.9rem">← Quay lại</button></div>
            <div style="margin-bottom:8px"><strong>${toolIcon(d.tool_type)} ${d.tool_type}</strong> — ${d.model_used} — ${d.duration_ms}ms</div>
            <div style="background:#f8fafc;padding:12px;border-radius:8px;margin-bottom:12px;font-size:.9rem"><strong>Prompt:</strong><br>${d.prompt}</div>
            <div class="ait-response" style="display:block">${marked.parse(d.response || 'No response')}</div>`;
        el.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
    } catch(e) { console.error(e); }
}

// Init
loadDashboard();
</script>
@endpush
