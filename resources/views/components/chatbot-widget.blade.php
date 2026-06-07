{{--
    Chatbot Widget — Theme-aware, fully mobile-responsive
    Reads $tenantAccent from the layout and applies it via CSS custom properties
--}}
@php
    $cbLayoutTenant = $layoutTenant ?? null;
    $cbTenantId = $cbLayoutTenant?->id ?? 0;
    $cbAiName    = \App\Models\SiteSetting::getValueForTenant($cbTenantId, 'ai_assistant_name', ($cbLayoutTenant?->name ?? 'AI') . ' Assistant');
    $cbAiTagline = \App\Models\SiteSetting::getValueForTenant($cbTenantId, 'ai_assistant_tagline', 'Ask me anything • I\'m here to help');
    $cbSiteName  = $cbLayoutTenant?->custom_domain ?? ($cbLayoutTenant?->username ? 'xenoraa.com/' . $cbLayoutTenant->username : 'xenoraa.com');
    $cbInitial   = strtoupper(substr($cbAiName, 0, 1));
    $cbAccent    = $tenantAccent ?? '#6366f1';
@endphp
<style>
/* ══ Chatbot Widget — Theme-aware, Mobile-first ══ */
:root {
    --cb-accent: {{ $cbAccent }};
    --cb-bg: #0f172a;
    --cb-surface: #1e293b;
    --cb-border: #334155;
    --cb-text: #e2e8f0;
    --cb-muted: #94a3b8;
    --cb-user-bubble: var(--cb-accent);
    --cb-bot-bubble: #1e293b;
}

/* Toggle button */
#chatbot-btn {
    position: fixed;
    bottom: 24px;
    right: 20px;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--cb-accent);
    border: none;
    cursor: pointer;
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 24px rgba(0,0,0,0.35), 0 0 0 4px color-mix(in srgb, var(--cb-accent) 20%, transparent);
    transition: transform 0.2s, box-shadow 0.2s;
}
#chatbot-btn:hover { transform: scale(1.08); }
#chatbot-btn .cb-icon-open { display: flex; }
#chatbot-btn .cb-icon-close { display: none; }
#chatbot-btn.open .cb-icon-open { display: none; }
#chatbot-btn.open .cb-icon-close { display: flex; }

#chatbot-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    width: 18px;
    height: 18px;
    background: #ef4444;
    border-radius: 50%;
    font-size: 10px;
    color: #fff;
    display: none;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    border: 2px solid #fff;
}

/* Chat window */
#chatbot-window {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: min(380px, calc(100vw - 24px));
    height: min(580px, calc(100dvh - 110px));
    background: var(--cb-bg);
    border: 1px solid var(--cb-border);
    border-radius: 20px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.55);
    z-index: 9999;
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: cbSlideUp 0.28s cubic-bezier(0.34,1.56,0.64,1);
}
#chatbot-window.open { display: flex; }

/* On very small screens: full-screen overlay */
@media (max-width: 480px) {
    #chatbot-window {
        bottom: 0;
        right: 0;
        width: 100vw;
        height: 100dvh;
        border-radius: 0;
        border: none;
    }
    #chatbot-btn { bottom: 16px; right: 16px; }
}

@keyframes cbSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.94); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
#chatbot-header {
    background: linear-gradient(135deg, color-mix(in srgb, var(--cb-accent) 30%, #0f172a), color-mix(in srgb, var(--cb-accent) 15%, #0f172a));
    border-bottom: 1px solid var(--cb-border);
    padding: 0.875rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}
.cb-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--cb-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    color: #fff;
    font-size: 1rem;
    flex-shrink: 0;
    position: relative;
}
.cb-avatar::after {
    content: '';
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid #0f172a;
}
.cb-info { flex: 1; min-width: 0; }
.cb-info h4 { color: #fff; font-size: 0.9rem; font-weight: 700; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cb-info p  { color: color-mix(in srgb, var(--cb-accent) 80%, #fff); font-size: 0.72rem; margin: 0.1rem 0 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#cb-close-btn {
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: color 0.2s;
}
#cb-close-btn:hover { color: #fff; }

/* Messages */
#chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    scroll-behavior: smooth;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
}
#chatbot-messages::-webkit-scrollbar { width: 4px; }
#chatbot-messages::-webkit-scrollbar-track { background: transparent; }
#chatbot-messages::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

.cb-msg { display: flex; gap: 0.5rem; align-items: flex-end; max-width: 90%; }
.cb-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.cb-msg.bot  { align-self: flex-start; }
.cb-msg-bubble {
    padding: 0.625rem 0.875rem;
    border-radius: 14px;
    font-size: 0.875rem;
    line-height: 1.55;
    word-break: break-word;
}
.cb-msg.bot  .cb-msg-bubble { background: var(--cb-surface); color: var(--cb-text); border-bottom-left-radius: 4px; border: 1px solid var(--cb-border); }
.cb-msg.user .cb-msg-bubble { background: var(--cb-accent); color: #fff; border-bottom-right-radius: 4px; }
.cb-msg-avatar {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: var(--cb-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.65rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

/* Typing indicator */
.cb-typing { display: flex; gap: 4px; padding: 0.625rem 0.875rem; background: var(--cb-surface); border: 1px solid var(--cb-border); border-radius: 14px; border-bottom-left-radius: 4px; width: fit-content; }
.cb-typing span { width: 6px; height: 6px; background: var(--cb-accent); border-radius: 50%; animation: cbTyping 1.2s infinite; }
.cb-typing span:nth-child(2) { animation-delay: 0.2s; }
.cb-typing span:nth-child(3) { animation-delay: 0.4s; }
@keyframes cbTyping {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
    30% { transform: translateY(-6px); opacity: 1; }
}

/* Contact form */
#chatbot-contact-form {
    padding: 1rem;
    border-top: 1px solid var(--cb-border);
    background: var(--cb-bg);
    flex-shrink: 0;
}
#chatbot-contact-form h5 { color: #fff; font-size: 0.875rem; margin: 0 0 0.75rem; font-weight: 600; }
#chatbot-contact-form input {
    width: 100%;
    background: var(--cb-surface);
    border: 1px solid var(--cb-border);
    color: var(--cb-text);
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.82rem;
    margin-bottom: 0.5rem;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s;
}
#chatbot-contact-form input:focus { border-color: var(--cb-accent); }
#chatbot-contact-form input::placeholder { color: #475569; }
#chatbot-contact-form button {
    width: 100%;
    background: var(--cb-accent);
    color: #fff;
    border: none;
    padding: 0.65rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 0.25rem;
    transition: opacity 0.2s;
}
#chatbot-contact-form button:hover { opacity: 0.88; }
#cb-form-error { color: #f87171; font-size: 0.75rem; margin-bottom: 0.4rem; display: none; }

/* Input area */
#chatbot-input-area {
    padding: 0.75rem 0.875rem;
    border-top: 1px solid var(--cb-border);
    display: flex;
    gap: 0.5rem;
    align-items: flex-end;
    background: var(--cb-bg);
    flex-shrink: 0;
}
#chatbot-input {
    flex: 1;
    background: var(--cb-surface);
    border: 1px solid var(--cb-border);
    color: var(--cb-text);
    padding: 0.6rem 0.875rem;
    border-radius: 12px;
    font-size: 0.875rem;
    resize: none;
    outline: none;
    max-height: 100px;
    line-height: 1.4;
    font-family: inherit;
    transition: border-color 0.2s;
    -webkit-appearance: none;
}
#chatbot-input::placeholder { color: #475569; }
#chatbot-input:focus { border-color: var(--cb-accent); }
#chatbot-send {
    width: 40px;
    height: 40px;
    background: var(--cb-accent);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: opacity 0.2s, transform 0.2s;
}
#chatbot-send:hover { opacity: 0.85; transform: scale(1.05); }
#chatbot-send svg { width: 18px; height: 18px; fill: #fff; }

/* Powered by */
#chatbot-powered {
    text-align: center;
    padding: 0.35rem;
    font-size: 0.62rem;
    color: #475569;
    background: var(--cb-bg);
    flex-shrink: 0;
}

/* Quick reply chips */
.cb-quick-replies { display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.5rem; }
.cb-quick-chip {
    background: color-mix(in srgb, var(--cb-accent) 15%, transparent);
    border: 1px solid color-mix(in srgb, var(--cb-accent) 40%, transparent);
    color: var(--cb-accent);
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.cb-quick-chip:hover { background: color-mix(in srgb, var(--cb-accent) 25%, transparent); }
</style>

{{-- Toggle Button --}}
<button id="chatbot-btn" onclick="toggleChatbot()" title="Chat with {{ $cbAiName }}" aria-label="Open chat">
    <div id="chatbot-badge" aria-hidden="true">1</div>
    <span class="cb-icon-open" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </span>
    <span class="cb-icon-close" aria-hidden="true">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </span>
</button>

{{-- Chat Window --}}
<div id="chatbot-window" role="dialog" aria-label="{{ $cbAiName }} Chat">
    <div id="chatbot-header">
        <div class="cb-avatar">{{ $cbInitial }}</div>
        <div class="cb-info">
            <h4>{{ $cbAiName }}</h4>
            <p>{{ $cbAiTagline }}</p>
        </div>
        <button id="cb-close-btn" onclick="toggleChatbot()" aria-label="Close chat">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <div id="chatbot-messages" aria-live="polite"></div>
    <div id="chatbot-contact-form" style="display:none;">
        <h5><i class="fas fa-user-circle" style="color:var(--cb-accent);margin-right:6px;"></i>Let's get started</h5>
        <input type="text"   id="cb-name"   placeholder="Your full name *" required autocomplete="name">
        <input type="email"  id="cb-email"  placeholder="Email address *" required autocomplete="email">
        <input type="tel"    id="cb-mobile" placeholder="Mobile number (optional)" autocomplete="tel">
        <div id="cb-form-error"></div>
        <button onclick="submitContactForm()">Start Conversation →</button>
    </div>
    <div id="chatbot-input-area" style="display:none;">
        <textarea id="chatbot-input" placeholder="Type your message…" rows="1"
            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"
            oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,100)+'px'"
            aria-label="Chat message"></textarea>
        <button id="chatbot-send" onclick="sendMessage()" aria-label="Send message">
            <svg viewBox="0 0 24 24"><path d="M22 2L11 13M22 2L15 22 11 13 2 9l20-7z"/></svg>
        </button>
    </div>
    <div id="chatbot-powered">Powered by {{ $cbAiName }} · {{ $cbSiteName }}</div>
</div>

<script>
(function() {
    let sessionId = null;
    let contactSaved = @auth true @else false @endauth;
    let isOpen = false;
    let initialized = false;
    const cbTenantUsername = '{{ $cbLayoutTenant?->username ?? '' }}';

    setTimeout(() => {
        const badge = document.getElementById('chatbot-badge');
        if (badge && !isOpen) badge.style.display = 'flex';
    }, 3000);

    window.toggleChatbot = function() {
        isOpen = !isOpen;
        const btn = document.getElementById('chatbot-btn');
        const win = document.getElementById('chatbot-window');
        const badge = document.getElementById('chatbot-badge');
        btn.classList.toggle('open', isOpen);
        win.classList.toggle('open', isOpen);
        if (badge) badge.style.display = 'none';
        if (isOpen && !initialized) { initialized = true; initChat(); }
        if (isOpen) {
            // Prevent body scroll on mobile when chat is open
            if (window.innerWidth <= 480) document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('chatbot-input')?.focus(), 300);
        } else {
            document.body.style.overflow = '';
        }
    };

    async function initChat() {
        try {
            const initUrl = cbTenantUsername
                ? '/chatbot/init?tenant_username=' + encodeURIComponent(cbTenantUsername)
                : '/chatbot/init';
            const res = await fetch(initUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            sessionId = data.session_id;
            appendMessage('bot', data.greeting);
            @auth
            showInputArea();
            @else
            document.getElementById('chatbot-contact-form').style.display = 'block';
            @endauth
        } catch(e) {
            appendMessage('bot', "Hi! I'm {{ $cbAiName }}. I'm having a small connection issue — please refresh and try again.");
        }
    }

    window.submitContactForm = async function() {
        const name   = document.getElementById('cb-name').value.trim();
        const email  = document.getElementById('cb-email').value.trim();
        const mobile = document.getElementById('cb-mobile').value.trim();
        const errEl  = document.getElementById('cb-form-error');
        if (!name || !email) { errEl.textContent = 'Name and email are required.'; errEl.style.display = 'block'; return; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { errEl.textContent = 'Please enter a valid email.'; errEl.style.display = 'block'; return; }
        errEl.style.display = 'none';
        try {
            const res = await fetch('/chatbot/save-contact', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ name, email, mobile, session_id: sessionId, tenant_username: cbTenantUsername })
            });
            const data = await res.json();
            if (data.success) {
                contactSaved = true;
                document.getElementById('chatbot-contact-form').style.display = 'none';
                showInputArea();
                appendMessage('bot', data.message || 'Thanks ' + name + '! How can I help you today?');
            } else {
                errEl.textContent = data.message || 'Something went wrong. Please try again.';
                errEl.style.display = 'block';
            }
        } catch(e) {
            errEl.textContent = 'Connection error. Please try again.';
            errEl.style.display = 'block';
        }
    };

    window.sendMessage = async function() {
        const input = document.getElementById('chatbot-input');
        const msg = input.value.trim();
        if (!msg) return;
        input.value = '';
        input.style.height = 'auto';
        appendMessage('user', msg);
        const typingId = showTyping();
        try {
            const res = await fetch('/chatbot/message', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ message: msg, session_id: sessionId, tenant_username: cbTenantUsername })
            });
            const data = await res.json();
            removeTyping(typingId);
            if (data.reply) appendMessage('bot', data.reply);
        } catch(e) {
            removeTyping(typingId);
            appendMessage('bot', 'Sorry, I encountered an error. Please try again.');
        }
    };

    function appendMessage(role, text) {
        const container = document.getElementById('chatbot-messages');
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg ' + role;
        if (role === 'bot') {
            const av = document.createElement('div');
            av.className = 'cb-msg-avatar';
            av.textContent = '{{ $cbInitial }}';
            wrap.appendChild(av);
        }
        const bubble = document.createElement('div');
        bubble.className = 'cb-msg-bubble';
        bubble.textContent = text;
        wrap.appendChild(bubble);
        container.appendChild(wrap);
        container.scrollTop = container.scrollHeight;
    }

    function showTyping() {
        const container = document.getElementById('chatbot-messages');
        const id = 'typing-' + Date.now();
        const wrap = document.createElement('div');
        wrap.className = 'cb-msg bot';
        wrap.id = id;
        const av = document.createElement('div');
        av.className = 'cb-msg-avatar';
        av.textContent = '{{ $cbInitial }}';
        wrap.appendChild(av);
        const typing = document.createElement('div');
        typing.className = 'cb-typing';
        typing.innerHTML = '<span></span><span></span><span></span>';
        wrap.appendChild(typing);
        container.appendChild(wrap);
        container.scrollTop = container.scrollHeight;
        return id;
    }

    function removeTyping(id) {
        document.getElementById(id)?.remove();
    }

    function showInputArea() {
        document.getElementById('chatbot-input-area').style.display = 'flex';
        document.getElementById('chatbot-contact-form').style.display = 'none';
    }

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isOpen) toggleChatbot();
    });
})();
</script>
