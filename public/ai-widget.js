/**
 * OHHA Chat Widget - Embeddable chat for lamgame.vn and ohha.com.vn
 *
 * Usage:
 *   <script src="https://your-ohha-server.com/widget.js"
 *           data-host="https://your-ohha-server.com"
 *           data-persona="game"
 *           data-api-key="your-key"
 *           data-position="bottom-right"
 *           data-title="OHHA Game AI">
 *   </script>
 */
(function () {
  'use strict';

  const script = document.currentScript;
  const CONFIG = {
    host: script?.getAttribute('data-host') || window.location.origin,
    persona: script?.getAttribute('data-persona') || 'default',
    apiKey: script?.getAttribute('data-api-key') || '',
    position: script?.getAttribute('data-position') || 'bottom-right',
    title: script?.getAttribute('data-title') || 'OHHA AI',
    theme: script?.getAttribute('data-theme') || 'dark',
  };

  let sessionId = null;
  let isOpen = false;
  let isLoading = false;

  // --- Styles ---
  const STYLES = `
    #ohha-widget-container * { box-sizing: border-box; margin: 0; padding: 0; }
    #ohha-widget-container {
      position: fixed;
      ${CONFIG.position.includes('right') ? 'right: 20px;' : 'left: 20px;'}
      ${CONFIG.position.includes('bottom') ? 'bottom: 20px;' : 'top: 20px;'}
      z-index: 99999;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    #ohha-toggle {
      width: 56px; height: 56px; border-radius: 50%;
      background: #00d4ff; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      box-shadow: 0 4px 12px rgba(0,212,255,0.3);
      transition: transform 0.2s;
    }
    #ohha-toggle:hover { transform: scale(1.1); }
    #ohha-toggle svg { width: 28px; height: 28px; fill: #000; }
    #ohha-chat-panel {
      display: none; position: absolute;
      ${CONFIG.position.includes('bottom') ? 'bottom: 70px;' : 'top: 70px;'}
      ${CONFIG.position.includes('right') ? 'right: 0;' : 'left: 0;'}
      width: 380px; height: 520px;
      background: ${CONFIG.theme === 'dark' ? '#1a1a2e' : '#fff'};
      border-radius: 12px; overflow: hidden;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      display: none; flex-direction: column;
    }
    #ohha-chat-panel.open { display: flex; }
    #ohha-chat-header {
      padding: 12px 16px; display: flex; align-items: center; justify-content: space-between;
      background: ${CONFIG.theme === 'dark' ? '#0f0f1a' : '#f5f5f5'};
      border-bottom: 1px solid ${CONFIG.theme === 'dark' ? '#333' : '#ddd'};
    }
    #ohha-chat-header h3 {
      font-size: 14px; font-weight: 600;
      color: ${CONFIG.theme === 'dark' ? '#00d4ff' : '#333'};
    }
    #ohha-close-btn {
      background: none; border: none; cursor: pointer; font-size: 20px;
      color: ${CONFIG.theme === 'dark' ? '#888' : '#666'};
    }
    #ohha-messages {
      flex: 1; overflow-y: auto; padding: 12px;
      display: flex; flex-direction: column; gap: 8px;
      background: ${CONFIG.theme === 'dark' ? '#0f0f0f' : '#fafafa'};
    }
    .ohha-msg {
      max-width: 85%; padding: 8px 12px; border-radius: 10px;
      font-size: 13px; line-height: 1.5; word-wrap: break-word; white-space: pre-wrap;
    }
    .ohha-msg.user {
      align-self: flex-end;
      background: ${CONFIG.theme === 'dark' ? '#1a3a5c' : '#007bff'};
      color: ${CONFIG.theme === 'dark' ? '#e0e0e0' : '#fff'};
      border-bottom-right-radius: 3px;
    }
    .ohha-msg.agent {
      align-self: flex-start;
      background: ${CONFIG.theme === 'dark' ? '#1e1e2e' : '#e9ecef'};
      color: ${CONFIG.theme === 'dark' ? '#e0e0e0' : '#333'};
      border: 1px solid ${CONFIG.theme === 'dark' ? '#333' : '#ddd'};
      border-bottom-left-radius: 3px;
    }
    .ohha-msg.tool {
      align-self: flex-start; font-size: 11px; font-family: monospace;
      color: #888; background: ${CONFIG.theme === 'dark' ? '#111' : '#f0f0f0'};
      border: 1px solid ${CONFIG.theme === 'dark' ? '#222' : '#ddd'};
    }
    #ohha-input-area {
      padding: 10px 12px; display: flex; gap: 8px;
      background: ${CONFIG.theme === 'dark' ? '#1a1a2e' : '#fff'};
      border-top: 1px solid ${CONFIG.theme === 'dark' ? '#333' : '#ddd'};
    }
    #ohha-input {
      flex: 1; border: 1px solid ${CONFIG.theme === 'dark' ? '#333' : '#ddd'};
      background: ${CONFIG.theme === 'dark' ? '#111' : '#fff'};
      color: ${CONFIG.theme === 'dark' ? '#e0e0e0' : '#333'};
      padding: 8px 12px; border-radius: 8px; font-size: 13px;
      outline: none; font-family: inherit;
    }
    #ohha-input:focus { border-color: #00d4ff; }
    #ohha-send {
      background: #00d4ff; color: #000; border: none;
      padding: 0 16px; border-radius: 8px; font-weight: 600;
      cursor: pointer; font-size: 13px;
    }
    #ohha-send:disabled { background: #333; color: #666; cursor: not-allowed; }
    .ohha-typing { color: #888; font-size: 12px; font-style: italic; padding: 4px 12px; }
    @media (max-width: 480px) {
      #ohha-chat-panel { width: calc(100vw - 40px); height: 60vh; }
    }
  `;

  // --- Build DOM ---
  function init() {
    const style = document.createElement('style');
    style.textContent = STYLES;
    document.head.appendChild(style);

    const container = document.createElement('div');
    container.id = 'ohha-widget-container';
    container.innerHTML = `
      <div id="ohha-chat-panel">
        <div id="ohha-chat-header">
          <h3>🤖 ${CONFIG.title}</h3>
          <button id="ohha-close-btn">&times;</button>
        </div>
        <div id="ohha-messages"></div>
        <div id="ohha-input-area">
          <input id="ohha-input" type="text" placeholder="Hỏi gì đó..." />
          <button id="ohha-send">Gửi</button>
        </div>
      </div>
      <button id="ohha-toggle">
        <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
      </button>
    `;
    document.body.appendChild(container);

    document.getElementById('ohha-toggle').addEventListener('click', togglePanel);
    document.getElementById('ohha-close-btn').addEventListener('click', togglePanel);
    document.getElementById('ohha-send').addEventListener('click', sendMessage);
    document.getElementById('ohha-input').addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });
  }

  function togglePanel() {
    isOpen = !isOpen;
    document.getElementById('ohha-chat-panel').classList.toggle('open', isOpen);
  }

  function addMessage(text, type) {
    const el = document.getElementById('ohha-messages');
    const div = document.createElement('div');
    div.className = `ohha-msg ${type}`;
    div.textContent = text;
    el.appendChild(div);
    el.scrollTop = el.scrollHeight;
  }

  function addImageMsg(url) {
    const el = document.getElementById('ohha-messages');
    const div = document.createElement('div');
    div.className = 'ohha-msg agent';
    div.innerHTML = `<img src="${url}" style="max-width:100%;border-radius:8px;cursor:pointer" onclick="window.open('${url}','_blank')"/>`;
    el.appendChild(div);
    el.scrollTop = el.scrollHeight;
  }

  function setLoading(loading) {
    isLoading = loading;
    document.getElementById('ohha-send').disabled = loading;
    const existing = document.querySelector('.ohha-typing');
    if (loading && !existing) {
      const el = document.getElementById('ohha-messages');
      const div = document.createElement('div');
      div.className = 'ohha-typing';
      div.textContent = 'Đang suy nghĩ...';
      el.appendChild(div);
      el.scrollTop = el.scrollHeight;
    } else if (!loading && existing) {
      existing.remove();
    }
  }

  async function sendMessage() {
    const input = document.getElementById('ohha-input');
    const text = input.value.trim();
    if (!text || isLoading) return;

    addMessage(text, 'user');
    input.value = '';
    setLoading(true);

    try {
      const res = await fetch(`${CONFIG.host}/api/chat`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          ...(CONFIG.apiKey ? { 'X-API-Key': CONFIG.apiKey } : {}),
        },
        body: JSON.stringify({
          message: text,
          session_id: sessionId,
          persona: CONFIG.persona,
        }),
      });

      if (!res.ok) {
        const err = await res.json().catch(() => ({}));
        throw new Error(err.detail || `HTTP ${res.status}`);
      }

      const data = await res.json();
      sessionId = data.session_id;

      // Show tool events
      for (const evt of data.events || []) {
        if (evt.type === 'tool_call') {
          addMessage(`🔧 ${evt.content.tool_name}`, 'tool');
        } else if (evt.type === 'tool_result' && evt.content.result) {
          const text = typeof evt.content.result === 'string' ? evt.content.result : JSON.stringify(evt.content.result);
          const imgMatch = text.match(/(https?:\/\/[^\s"]+\.(png|jpg|jpeg|gif|webp))/i);
          if (imgMatch && (evt.content.tool_name === 'generate_image' || evt.content.tool_name === 'remove_background')) {
            addImageMsg(imgMatch[1]);
          }
        }
      }

      // Show response
      if (data.response) {
        addMessage(data.response, 'agent');
      }
    } catch (err) {
      addMessage(`Lỗi: ${err.message}`, 'agent');
    } finally {
      setLoading(false);
    }
  }

  // --- Expose global API ---
  window.OhhaWidget = {
    open: () => { if (!isOpen) togglePanel(); },
    close: () => { if (isOpen) togglePanel(); },
    send: (msg) => { document.getElementById('ohha-input').value = msg; sendMessage(); },
    reset: () => { sessionId = null; document.getElementById('ohha-messages').innerHTML = ''; },
  };

  // Init on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
