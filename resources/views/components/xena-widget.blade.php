{{--
  Xena — Xenoraa's AI Sales & Support Assistant
  Embedded on xenoraa.com (main SaaS site)
  Features: name/email/mobile capture, sales/support routing, intent detection
--}}
<style>
/* ── Xena Widget ───────────────────────────────────────────────────── */
:root {
    --xena-primary: #6366f1;
    --xena-secondary: #8b5cf6;
    --xena-dark: #0f172a;
    --xena-card: #1e293b;
    --xena-border: #334155;
    --xena-text: #e2e8f0;
    --xena-muted: #94a3b8;
    --xena-green: #10b981;
    --xena-amber: #f59e0b;
    --xena-radius: 16px;
}
#xena-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary));
    border: none;
    cursor: pointer;
    z-index: 9998;
    box-shadow: 0 8px 32px rgba(99,102,241,.45);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform .2s, box-shadow .2s;
    animation: xenaPulse 3s infinite;
}
@keyframes xenaPulse {
    0%,100% { box-shadow: 0 8px 32px rgba(99,102,241,.45); }
    50%      { box-shadow: 0 8px 48px rgba(99,102,241,.7); }
}
#xena-btn:hover { transform: scale(1.08); }
#xena-btn svg { width: 28px; height: 28px; fill: #fff; transition: opacity .2s; }
#xena-btn .xena-icon-open  { display: flex; }
#xena-btn .xena-icon-close { display: none; }
#xena-btn.open .xena-icon-open  { display: none; }
#xena-btn.open .xena-icon-close { display: flex; }
#xena-badge {
    position: absolute;
    top: -4px; right: -4px;
    width: 20px; height: 20px;
    background: #ef4444;
    border-radius: 50%;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #0f172a;
    animation: xenaBadge 2s ease-in-out infinite;
}
@keyframes xenaBadge { 0%,100%{transform:scale(1)} 50%{transform:scale(1.2)} }
#xena-window {
    position: fixed;
    bottom: 100px;
    right: 28px;
    width: 380px;
    max-height: 620px;
    background: var(--xena-dark);
    border: 1px solid var(--xena-border);
    border-radius: var(--xena-radius);
    box-shadow: 0 24px 80px rgba(0,0,0,.6);
    display: none;
    flex-direction: column;
    z-index: 9997;
    overflow: hidden;
    animation: xenaSlideIn .25s ease;
}
@keyframes xenaSlideIn {
    from { opacity:0; transform:translateY(20px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
#xena-window.open { display: flex; }
@media (max-width: 480px) {
    #xena-window { bottom:0; right:0; left:0; width:100%; max-height:100dvh; border-radius:var(--xena-radius) var(--xena-radius) 0 0; }
    #xena-btn { bottom:16px; right:16px; }
}
/* Header */
#xena-header {
    background: linear-gradient(135deg, #312e81, #4c1d95);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: .75rem;
    flex-shrink: 0;
    border-bottom: 1px solid rgba(255,255,255,.08);
}
.xena-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
    box-shadow: 0 0 0 3px rgba(99,102,241,.3);
}
.xena-header-info { flex: 1; }
.xena-header-name { font-size: .95rem; font-weight: 700; color: #fff; }
.xena-header-status { font-size: .72rem; color: rgba(255,255,255,.65); display: flex; align-items: center; gap: .35rem; }
.xena-online-dot { width: 7px; height: 7px; background: var(--xena-green); border-radius: 50%; animation: xenaOnline 2s infinite; }
@keyframes xenaOnline { 0%,100%{opacity:1} 50%{opacity:.4} }
#xena-close-btn { background: none; border: none; color: rgba(255,255,255,.6); cursor: pointer; padding: .25rem; border-radius: 6px; }
#xena-close-btn:hover { color: #fff; background: rgba(255,255,255,.1); }
/* Intent selector */
#xena-intent-bar {
    background: #0f172a;
    padding: .75rem 1.25rem;
    display: flex;
    gap: .5rem;
    border-bottom: 1px solid var(--xena-border);
    flex-shrink: 0;
}
.xena-intent-btn {
    flex: 1;
    padding: .45rem .5rem;
    border-radius: 8px;
    font-size: .75rem;
    font-weight: 600;
    border: 1px solid var(--xena-border);
    background: transparent;
    color: var(--xena-muted);
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .3rem;
}
.xena-intent-btn.active-sales   { background: rgba(16,185,129,.15); border-color: #10b981; color: #10b981; }
.xena-intent-btn.active-support { background: rgba(245,158,11,.15); border-color: #f59e0b; color: #f59e0b; }
.xena-intent-btn:hover { border-color: var(--xena-primary); color: var(--xena-text); }
/* Messages */
#xena-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: .75rem;
    scroll-behavior: smooth;
}
#xena-messages::-webkit-scrollbar { width: 4px; }
#xena-messages::-webkit-scrollbar-track { background: transparent; }
#xena-messages::-webkit-scrollbar-thumb { background: var(--xena-border); border-radius: 2px; }
.xena-msg { display: flex; gap: .6rem; max-width: 88%; }
.xena-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.xena-msg.bot  { align-self: flex-start; }
.xena-msg-avatar {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary));
    display: flex; align-items: center; justify-content: center;
    font-size: .65rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
    margin-top: .15rem;
}
.xena-msg-bubble {
    padding: .65rem .9rem;
    border-radius: 12px;
    font-size: .85rem;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
}
.xena-msg.bot  .xena-msg-bubble { background: var(--xena-card); color: var(--xena-text); border: 1px solid var(--xena-border); border-radius: 4px 12px 12px 12px; }
.xena-msg.user .xena-msg-bubble { background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary)); color: #fff; border-radius: 12px 4px 12px 12px; }
/* Typing indicator */
.xena-typing span {
    width: 6px; height: 6px;
    background: var(--xena-primary);
    border-radius: 50%;
    display: inline-block;
    animation: xenaTyping 1.2s infinite;
}
.xena-typing span:nth-child(2) { animation-delay: .2s; }
.xena-typing span:nth-child(3) { animation-delay: .4s; }
@keyframes xenaTyping { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-6px)} }
/* Contact form */
#xena-contact-form {
    background: var(--xena-card);
    border-top: 1px solid var(--xena-border);
    padding: 1rem 1.25rem;
    flex-shrink: 0;
}
#xena-contact-form h5 {
    color: var(--xena-text);
    font-size: .85rem;
    font-weight: 700;
    margin: 0 0 .75rem;
    display: flex; align-items: center; gap: .4rem;
}
#xena-contact-form input {
    width: 100%;
    background: var(--xena-dark);
    border: 1px solid var(--xena-border);
    color: var(--xena-text);
    border-radius: 8px;
    padding: .55rem .85rem;
    font-size: .82rem;
    margin-bottom: .5rem;
    box-sizing: border-box;
    transition: border-color .15s;
}
#xena-contact-form input:focus { outline: none; border-color: var(--xena-primary); }
#xena-contact-form input::placeholder { color: #475569; }
#xena-contact-form button {
    width: 100%;
    background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary));
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: .65rem;
    font-size: .85rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: .25rem;
    transition: opacity .15s;
}
#xena-contact-form button:hover { opacity: .88; }
#xena-form-error { color: #f87171; font-size: .75rem; margin-top: .25rem; display: none; }
/* Input area */
#xena-input-area {
    background: var(--xena-card);
    border-top: 1px solid var(--xena-border);
    padding: .75rem 1rem;
    display: flex;
    gap: .5rem;
    align-items: flex-end;
    flex-shrink: 0;
}
#xena-input {
    flex: 1;
    background: var(--xena-dark);
    border: 1px solid var(--xena-border);
    color: var(--xena-text);
    border-radius: 10px;
    padding: .6rem .9rem;
    font-size: .85rem;
    resize: none;
    min-height: 40px;
    max-height: 100px;
    overflow-y: auto;
    line-height: 1.4;
    transition: border-color .15s;
}
#xena-input:focus { outline: none; border-color: var(--xena-primary); }
#xena-input::placeholder { color: #475569; }
#xena-send {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--xena-primary), var(--xena-secondary));
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    transition: opacity .15s, transform .15s;
}
#xena-send:hover { opacity: .85; transform: scale(1.05); }
#xena-send svg { width: 18px; height: 18px; fill: #fff; }
/* Powered by */
#xena-powered {
    text-align: center;
    padding: .4rem;
    font-size: .68rem;
    color: #334155;
    background: var(--xena-dark);
    border-top: 1px solid var(--xena-border);
    flex-shrink: 0;
}
#xena-powered a { color: var(--xena-primary); text-decoration: none; }
</style>

{{-- Xena Button --}}
<button id="xena-btn" onclick="toggleXena()" title="Chat with Xena — Xenoraa AI" aria-label="Open Xena AI chat">
    <div id="xena-badge" aria-hidden="true">1</div>
    <div class="xena-icon-open">
        <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.85.5 3.58 1.37 5.07L2 22l4.93-1.37C8.42 21.5 10.15 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2zm5 13.5h-3l-1 2-1-2H8c-.55 0-1-.45-1-1v-5c0-.55.45-1 1-1h9c.55 0 1 .45 1 1v5c0 .55-.45 1-1 1z"/></svg>
    </div>
    <div class="xena-icon-close">
        <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
    </div>
</button>

{{-- Xena Chat Window --}}
<div id="xena-window" role="dialog" aria-label="Xena AI Chat">
    {{-- Header --}}
    <div id="xena-header">
        <div class="xena-avatar">X</div>
        <div class="xena-header-info">
            <div class="xena-header-name">Xena — Xenoraa AI</div>
            <div class="xena-header-status">
                <span class="xena-online-dot"></span>
                Online · Sales & Support
            </div>
        </div>
        <button id="xena-close-btn" onclick="toggleXena()" aria-label="Close chat">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
        </button>
    </div>

    {{-- Intent Selector --}}
    <div id="xena-intent-bar">
        <button class="xena-intent-btn active-sales" id="xena-intent-sales" onclick="setXenaIntent('sales')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            Sales
        </button>
        <button class="xena-intent-btn" id="xena-intent-support" onclick="setXenaIntent('support')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/></svg>
            Support
        </button>
    </div>

    {{-- Messages --}}
    <div id="xena-messages" aria-live="polite"></div>

    {{-- Contact Form --}}
    <div id="xena-contact-form" style="display:none;">
        <h5>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#6366f1"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            Quick intro — so I can help better
        </h5>
        <input type="text"   id="xena-name"   placeholder="Your full name *" autocomplete="name">
        <input type="email"  id="xena-email"  placeholder="Work email *" autocomplete="email">
        <input type="tel"    id="xena-mobile" placeholder="Mobile number (optional)" autocomplete="tel">
        <div id="xena-form-error"></div>
        <button onclick="xenaSubmitContact()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right:.4rem;"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Start Chatting
        </button>
    </div>

    {{-- Input Area --}}
    <div id="xena-input-area" style="display:none;">
        <textarea id="xena-input" placeholder="Ask about plans, features, or get support..." rows="1"
                  onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();xenaSend();}"
                  oninput="this.style.height='auto';this.style.height=this.scrollHeight+'px';"></textarea>
        <button id="xena-send" onclick="xenaSend()" aria-label="Send">
            <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
        </button>
    </div>

    {{-- Powered by --}}
    <div id="xena-powered">Powered by <a href="https://xenoraa.com" target="_blank">Xenoraa AI</a></div>
</div>

<script>
(function() {
    let xenaSessionId = null;
    let xenaLeadId    = null;
    let xenaIntent    = 'sales';
    let xenaIsOpen    = false;
    let xenaContactSaved = false;

    window.toggleXena = function() {
        xenaIsOpen = !xenaIsOpen;
        const btn = document.getElementById('xena-btn');
        const win = document.getElementById('xena-window');
        btn.classList.toggle('open', xenaIsOpen);
        win.classList.toggle('open', xenaIsOpen);
        document.getElementById('xena-badge').style.display = 'none';
        if (xenaIsOpen && !xenaSessionId) xenaInit();
        if (xenaIsOpen && window.innerWidth <= 480) document.body.style.overflow = 'hidden';
        else document.body.style.overflow = '';
        if (xenaIsOpen) setTimeout(() => document.getElementById('xena-input')?.focus(), 300);
    };

    window.setXenaIntent = function(intent) {
        xenaIntent = intent;
        document.getElementById('xena-intent-sales').className   = 'xena-intent-btn' + (intent === 'sales'   ? ' active-sales'   : '');
        document.getElementById('xena-intent-support').className = 'xena-intent-btn' + (intent === 'support' ? ' active-support' : '');
    };

    async function xenaInit() {
        try {
            const res  = await fetch('/chatbot/init?tenant_username=xenoraa', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            xenaSessionId = data.session_id;
            xenaAppendMsg('bot', data.greeting);
            // Show contact form for anonymous visitors
            @guest
            document.getElementById('xena-contact-form').style.display = 'block';
            @else
            xenaContactSaved = true;
            xenaShowInput();
            @endguest
        } catch(e) {
            xenaAppendMsg('bot', "Hi! I'm Xena, Xenoraa's AI assistant. I'm having a small connection issue — please refresh and try again.");
        }
    }

    window.xenaSubmitContact = async function() {
        const name   = document.getElementById('xena-name').value.trim();
        const email  = document.getElementById('xena-email').value.trim();
        const mobile = document.getElementById('xena-mobile').value.trim();
        const errEl  = document.getElementById('xena-form-error');
        if (!name || !email) { errEl.textContent = 'Name and email are required.'; errEl.style.display = 'block'; return; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { errEl.textContent = 'Please enter a valid email.'; errEl.style.display = 'block'; return; }
        errEl.style.display = 'none';
        try {
            const res = await fetch('/chatbot/save-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ name, email, mobile, session_id: xenaSessionId, tenant_username: null, intent: xenaIntent })
            });
            const data = await res.json();
            if (data.success) {
                xenaLeadId = data.lead_id;
                xenaContactSaved = true;
                document.getElementById('xena-contact-form').style.display = 'none';
                xenaShowInput();
                xenaAppendMsg('bot', data.message || `Thanks ${name}! How can I help you today?`);
            } else {
                errEl.textContent = data.message || 'Something went wrong. Please try again.';
                errEl.style.display = 'block';
            }
        } catch(e) {
            errEl.textContent = 'Connection error. Please try again.';
            errEl.style.display = 'block';
        }
    };

    window.xenaSend = async function() {
        const input = document.getElementById('xena-input');
        const msg   = input.value.trim();
        if (!msg || !xenaSessionId) return;
        input.value = '';
        input.style.height = 'auto';
        xenaAppendMsg('user', msg);
        const typingId = xenaShowTyping();
        try {
            const res = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    message: msg,
                    session_id: xenaSessionId,
                    lead_id: xenaLeadId,
                    tenant_username: null,
                    intent: xenaIntent
                })
            });
            const data = await res.json();
            xenaRemoveTyping(typingId);
            if (data.reply) xenaAppendMsg('bot', data.reply);
            if (data.lead_id) xenaLeadId = data.lead_id;
        } catch(e) {
            xenaRemoveTyping(typingId);
            xenaAppendMsg('bot', 'Sorry, I encountered an error. Please try again or email us at support@xenoraa.com');
        }
    };

    function xenaShowInput() {
        document.getElementById('xena-input-area').style.display = 'flex';
    }

    function xenaAppendMsg(role, text) {
        const container = document.getElementById('xena-messages');
        const wrap   = document.createElement('div');
        wrap.className = 'xena-msg ' + role;
        if (role === 'bot') {
            const av = document.createElement('div');
            av.className = 'xena-msg-avatar';
            av.textContent = 'X';
            wrap.appendChild(av);
        }
        const bubble = document.createElement('div');
        bubble.className = 'xena-msg-bubble';
        bubble.textContent = text;
        wrap.appendChild(bubble);
        container.appendChild(wrap);
        container.scrollTop = container.scrollHeight;
    }

    function xenaShowTyping() {
        const container = document.getElementById('xena-messages');
        const id   = 'xena-typing-' + Date.now();
        const wrap = document.createElement('div');
        wrap.className = 'xena-msg bot';
        wrap.id = id;
        const av = document.createElement('div');
        av.className = 'xena-msg-avatar';
        av.textContent = 'X';
        wrap.appendChild(av);
        const bubble = document.createElement('div');
        bubble.className = 'xena-msg-bubble xena-typing';
        bubble.innerHTML = '<span></span><span></span><span></span>';
        wrap.appendChild(bubble);
        container.appendChild(wrap);
        container.scrollTop = container.scrollHeight;
        return id;
    }

    function xenaRemoveTyping(id) {
        document.getElementById(id)?.remove();
    }

    // Show badge after 8 seconds if not opened
    setTimeout(() => {
        if (!xenaIsOpen) {
            document.getElementById('xena-badge').style.display = 'flex';
        }
    }, 8000);
})();
</script>
