{{-- AI Chatbot Popup Widget - Represents Gopi K --}}
<style>
#chatbot-btn {
    position: fixed;
    bottom: 28px;
    right: 28px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    cursor: pointer;
    z-index: 9998;
    box-shadow: 0 4px 24px rgba(99,102,241,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, box-shadow 0.2s;
}
#chatbot-btn:hover { transform: scale(1.08); box-shadow: 0 6px 32px rgba(99,102,241,0.7); }
#chatbot-btn .cb-icon-open { display: flex; }
#chatbot-btn .cb-icon-close { display: none; }
#chatbot-btn.open .cb-icon-open { display: none; }
#chatbot-btn.open .cb-icon-close { display: flex; }

#chatbot-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    background: #ef4444;
    border-radius: 50%;
    font-size: 10px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    display: none;
}

#chatbot-window {
    position: fixed;
    bottom: 100px;
    right: 28px;
    width: 380px;
    max-width: calc(100vw - 40px);
    height: 560px;
    max-height: calc(100vh - 120px);
    background: #0f172a;
    border: 1px solid #334155;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.6);
    z-index: 9999;
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: chatSlideUp 0.3s ease;
}
#chatbot-window.open { display: flex; }
@keyframes chatSlideUp {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

#chatbot-header {
    background: linear-gradient(135deg, #1e1b4b, #312e81);
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.875rem;
    border-bottom: 1px solid #334155;
    flex-shrink: 0;
}
#chatbot-header .cb-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #fff;
    font-size: 1.1rem;
    flex-shrink: 0;
    position: relative;
}
#chatbot-header .cb-avatar::after {
    content: '';
    position: absolute;
    bottom: 1px;
    right: 1px;
    width: 10px;
    height: 10px;
    background: #10b981;
    border-radius: 50%;
    border: 2px solid #1e1b4b;
}
#chatbot-header .cb-info h4 { color: #fff; font-size: 0.95rem; font-weight: 700; margin: 0; }
#chatbot-header .cb-info p  { color: #a5b4fc; font-size: 0.75rem; margin: 0.1rem 0 0; }

#chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    scroll-behavior: smooth;
}
#chatbot-messages::-webkit-scrollbar { width: 4px; }
#chatbot-messages::-webkit-scrollbar-track { background: transparent; }
#chatbot-messages::-webkit-scrollbar-thumb { background: #334155; border-radius: 2px; }

.cb-msg {
    display: flex;
    gap: 0.5rem;
    align-items: flex-end;
    max-width: 90%;
}
.cb-msg.user { align-self: flex-end; flex-direction: row-reverse; }
.cb-msg.bot  { align-self: flex-start; }

.cb-msg-bubble {
    padding: 0.625rem 0.875rem;
    border-radius: 14px;
    font-size: 0.875rem;
    line-height: 1.55;
    word-break: break-word;
}
.cb-msg.bot  .cb-msg-bubble { background: #1e293b; color: #e2e8f0; border-bottom-left-radius: 4px; border: 1px solid #334155; }
.cb-msg.user .cb-msg-bubble { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; border-bottom-right-radius: 4px; }

.cb-msg-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.cb-typing {
    display: flex;
    gap: 4px;
    padding: 0.625rem 0.875rem;
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 14px;
    border-bottom-left-radius: 4px;
    width: fit-content;
}
.cb-typing span {
    width: 6px;
    height: 6px;
    background: #6366f1;
    border-radius: 50%;
    animation: cbTyping 1.2s infinite;
}
.cb-typing span:nth-child(2) { animation-delay: 0.2s; }
.cb-typing span:nth-child(3) { animation-delay: 0.4s; }
@keyframes cbTyping {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30% { transform: translateY(-6px); opacity: 1; }
}

#chatbot-contact-form {
    padding: 1rem;
    border-top: 1px solid #334155;
    background: #0f172a;
    flex-shrink: 0;
}
#chatbot-contact-form h5 { color: #fff; font-size: 0.875rem; margin: 0 0 0.75rem; font-weight: 600; }
#chatbot-contact-form input {
    width: 100%;
    background: #1e293b;
    border: 1px solid #334155;
    color: #fff;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
    box-sizing: border-box;
}
#chatbot-contact-form input::placeholder { color: #64748b; }
#chatbot-contact-form button {
    width: 100%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    border: none;
    padding: 0.6rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    margin-top: 0.25rem;
}

#chatbot-input-area {
    padding: 0.875rem 1rem;
    border-top: 1px solid #334155;
    display: flex;
    gap: 0.5rem;
    align-items: flex-end;
    background: #0f172a;
    flex-shrink: 0;
}
#chatbot-input {
    flex: 1;
    background: #1e293b;
    border: 1px solid #334155;
    color: #fff;
    padding: 0.6rem 0.875rem;
    border-radius: 12px;
    font-size: 0.875rem;
    resize: none;
    outline: none;
    max-height: 100px;
    line-height: 1.4;
    font-family: inherit;
}
#chatbot-input::placeholder { color: #64748b; }
#chatbot-input:focus { border-color: #6366f1; }
#chatbot-send {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: opacity 0.2s;
}
#chatbot-send:hover { opacity: 0.85; }
#chatbot-send svg { width: 18px; height: 18px; fill: #fff; }

#chatbot-powered {
    text-align: center;
    padding: 0.4rem;
    font-size: 0.65rem;
    color: #475569;
    background: #0f172a;
    flex-shrink: 0;
}
</style>

{{-- Toggle Button --}}
<button id="chatbot-btn" onclick="toggleChatbot()" title="Chat with Gopi AI">
    <div id="chatbot-badge">1</div>
    <span class="cb-icon-open">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
    </span>
    <span class="cb-icon-close">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </span>
</button>

{{-- Chat Window --}}
<div id="chatbot-window">
    {{-- Header --}}
    <div id="chatbot-header">
        <div class="cb-avatar">G</div>
        <div class="cb-info">
            <h4>Gopi AI Assistant</h4>
            <p>Ask me anything • I'm here to help</p>
        </div>
    </div>

    {{-- Messages --}}
    <div id="chatbot-messages"></div>

    {{-- Contact Form (shown initially if not logged in) --}}
    <div id="chatbot-contact-form" style="display:none;">
        <h5><i class="fas fa-user-circle" style="color:#6366f1;margin-right:6px;"></i>Let's get started</h5>
        <input type="text"   id="cb-name"   placeholder="Your full name *" required>
        <input type="email"  id="cb-email"  placeholder="Email address *" required>
        <input type="tel"    id="cb-mobile" placeholder="Mobile number (optional)">
        <div id="cb-form-error" style="color:#f87171;font-size:0.75rem;margin-bottom:0.4rem;display:none;"></div>
        <button onclick="submitContactForm()">Start Conversation →</button>
    </div>

    {{-- Input Area --}}
    <div id="chatbot-input-area" style="display:none;">
        <textarea id="chatbot-input" placeholder="Type your message..." rows="1"
            onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendMessage();}"
            oninput="this.style.height='auto';this.style.height=Math.min(this.scrollHeight,100)+'px'"></textarea>
        <button id="chatbot-send" onclick="sendMessage()">
            <svg viewBox="0 0 24 24"><path d="M22 2L11 13M22 2L15 22 11 13 2 9l20-7z"/></svg>
        </button>
    </div>

    <div id="chatbot-powered">Powered by Gopi AI · gopi.blog</div>
</div>

<script>
(function() {
    let sessionId = null;
    let leadId = @auth {{ auth()->id() ? 'null' : 'null' }} @else null @endauth;
    let contactSaved = @auth true @else false @endauth;
    let isOpen = false;
    let initialized = false;

    // Show badge after 3 seconds to attract attention
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

        if (isOpen && !initialized) {
            initialized = true;
            initChat();
        }
        if (isOpen) {
            setTimeout(() => document.getElementById('chatbot-input')?.focus(), 300);
        }
    };

    async function initChat() {
        try {
            const res = await fetch('/chatbot/init', {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            sessionId = data.session_id;

            appendMessage('bot', data.greeting);

            @auth
            // Logged in — show input directly
            showInputArea();
            @else
            // Guest — show contact form
            document.getElementById('chatbot-contact-form').style.display = 'block';
            @endauth
        } catch(e) {
            appendMessage('bot', "Hi! I'm Gopi's AI assistant. I'm having a small connection issue — please refresh and try again.");
        }
    }

    window.submitContactForm = async function() {
        const name   = document.getElementById('cb-name').value.trim();
        const email  = document.getElementById('cb-email').value.trim();
        const mobile = document.getElementById('cb-mobile').value.trim();
        const errEl  = document.getElementById('cb-form-error');

        errEl.style.display = 'none';
        if (!name) { errEl.textContent = 'Please enter your full name.'; errEl.style.display = 'block'; return; }
        if (!email || !/^[^@]+@[^@]+\.[^@]+$/.test(email)) { errEl.textContent = 'Please enter a valid email address.'; errEl.style.display = 'block'; return; }

        try {
            const res = await fetch('/chatbot/contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ session_id: sessionId, name, mobile, email }),
            });
            const data = await res.json();
            if (data.success) {
                leadId = data.lead_id;
                contactSaved = true;
                document.getElementById('chatbot-contact-form').style.display = 'none';
                showInputArea();
                appendMessage('user', `${name} (${email})`);
                // Trigger AI greeting with context
                showTyping();
                const chatRes = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message: `Hi, my name is ${name} and my email is ${email}. I'm here to discuss my business needs.`, session_id: sessionId, lead_id: leadId }),
                });
                const chatData = await chatRes.json();
                removeTyping();
                if (chatData.lead_id) leadId = chatData.lead_id;
                appendMessage('bot', chatData.reply);
            } else {
                errEl.textContent = data.message || 'Something went wrong. Please try again.';
                errEl.style.display = 'block';
            }
        } catch(e) {
            errEl.textContent = 'Something went wrong. Please try again.';
            errEl.style.display = 'block';
        }
    };

    window.sendMessage = async function() {
        const input = document.getElementById('chatbot-input');
        const msg = input.value.trim();
        if (!msg || !sessionId) return;

        input.value = '';
        input.style.height = 'auto';
        appendMessage('user', msg);
        showTyping();

        try {
            const res = await fetch('/chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: msg, session_id: sessionId, lead_id: leadId }),
            });
            const data = await res.json();
            removeTyping();
            if (data.lead_id) leadId = data.lead_id;
            appendMessage('bot', data.reply);
        } catch(e) {
            removeTyping();
            appendMessage('bot', "I'm having a connection issue. Please try again in a moment.");
        }
    };

    function appendMessage(role, text) {
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = `cb-msg ${role}`;
        const formattedText = text.replace(/\n/g, '<br>');
        if (role === 'bot') {
            div.innerHTML = `<div class="cb-msg-avatar">G</div><div class="cb-msg-bubble">${formattedText}</div>`;
        } else {
            div.innerHTML = `<div class="cb-msg-bubble">${formattedText}</div>`;
        }
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function showTyping() {
        const container = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'cb-msg bot';
        div.id = 'cb-typing-indicator';
        div.innerHTML = `<div class="cb-msg-avatar">G</div><div class="cb-typing"><span></span><span></span><span></span></div>`;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }

    function removeTyping() {
        document.getElementById('cb-typing-indicator')?.remove();
    }

    function showInputArea() {
        document.getElementById('chatbot-input-area').style.display = 'flex';
    }
})();
</script>
