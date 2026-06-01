@extends('layouts.app')

@section('title', 'Team Chat')

@push('styles')
<style>
.chat-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 1.5rem;
    min-height: calc(100vh - 80px);
}
/* Sidebar */
.chat-sidebar {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    padding: 1.25rem 0;
    height: fit-content;
    position: sticky;
    top: 90px;
}
.chat-sidebar-title {
    font-size: 0.7rem;
    font-weight: 700;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 0 1.25rem;
    margin-bottom: 0.75rem;
}
.chat-channel-btn {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    width: 100%;
    padding: 0.6rem 1.25rem;
    background: none;
    border: none;
    color: #888;
    font-size: 0.9rem;
    cursor: pointer;
    text-align: left;
    transition: all 0.15s;
    border-left: 3px solid transparent;
    text-decoration: none;
}
.chat-channel-btn:hover { color: #fff; background: rgba(255,255,255,0.04); }
.chat-channel-btn.active { color: #fff; background: rgba(255,255,255,0.06); border-left-color: #fff; }
.chat-channel-btn .hash { color: #555; font-size: 1rem; font-weight: 700; }
.chat-channel-btn.active .hash { color: #aaa; }
/* Main Chat */
.chat-main {
    background: #111;
    border: 1px solid #1e1e1e;
    border-radius: 16px;
    display: flex;
    flex-direction: column;
    height: calc(100vh - 140px);
    min-height: 500px;
}
.chat-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #1e1e1e;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}
.chat-header-hash { font-size: 1.2rem; color: #555; font-weight: 700; }
.chat-header-name { font-size: 1rem; font-weight: 700; color: #fff; }
.chat-header-desc { font-size: 0.8rem; color: #666; margin-left: auto; }
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    scroll-behavior: smooth;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-track { background: transparent; }
.chat-messages::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }
.chat-day-divider {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1rem 0 0.5rem;
    color: #555;
    font-size: 0.75rem;
    font-weight: 600;
}
.chat-day-divider::before,
.chat-day-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #1e1e1e;
}
.chat-msg {
    display: flex;
    gap: 0.75rem;
    padding: 0.35rem 0.5rem;
    border-radius: 8px;
    transition: background 0.1s;
    align-items: flex-start;
}
.chat-msg:hover { background: rgba(255,255,255,0.03); }
.chat-msg:hover .msg-delete { opacity: 1; }
.chat-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e3a5f, #2d5a8e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    color: #93c5fd;
    flex-shrink: 0;
    margin-top: 2px;
}
.chat-avatar.mine { background: linear-gradient(135deg, #1a3a1a, #2d5a2d); color: #86efac; }
.chat-msg-body { flex: 1; min-width: 0; }
.chat-msg-meta {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    margin-bottom: 0.2rem;
}
.msg-author { font-size: 0.875rem; font-weight: 700; color: #e0e0e0; }
.msg-author.mine { color: #86efac; }
.msg-time { font-size: 0.7rem; color: #555; }
.msg-text { font-size: 0.9rem; color: #ccc; line-height: 1.5; word-break: break-word; }
.msg-delete {
    opacity: 0;
    background: none;
    border: none;
    color: #ef4444;
    cursor: pointer;
    font-size: 0.75rem;
    padding: 0.2rem 0.4rem;
    border-radius: 4px;
    transition: all 0.15s;
    flex-shrink: 0;
    margin-top: 4px;
}
.msg-delete:hover { background: rgba(239,68,68,0.1); }
/* Input Area */
.chat-input-area {
    padding: 1rem 1.5rem;
    border-top: 1px solid #1e1e1e;
    flex-shrink: 0;
}
.chat-input-form {
    display: flex;
    gap: 0.75rem;
    align-items: flex-end;
}
.chat-input {
    flex: 1;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 12px;
    padding: 0.75rem 1rem;
    color: #fff;
    font-size: 0.9rem;
    resize: none;
    max-height: 120px;
    min-height: 44px;
    line-height: 1.5;
    transition: border-color 0.2s;
    font-family: inherit;
}
.chat-input:focus { outline: none; border-color: #444; }
.chat-input::placeholder { color: #555; }
.chat-send-btn {
    width: 44px;
    height: 44px;
    background: #fff;
    border: none;
    border-radius: 10px;
    color: #000;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}
.chat-send-btn:hover { background: #e0e0e0; transform: scale(1.05); }
.chat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
.online-indicator {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22c55e;
    margin-right: 0.35rem;
}
.empty-chat {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #555;
    text-align: center;
    gap: 0.75rem;
}
.empty-chat i { font-size: 3rem; color: #2a2a2a; }
.empty-chat p { font-size: 0.9rem; }
@media (max-width: 768px) {
    .chat-wrapper { grid-template-columns: 1fr; padding: 1rem; }
    .chat-sidebar { position: static; display: flex; flex-wrap: wrap; gap: 0.25rem; padding: 0.75rem; }
    .chat-sidebar-title { display: none; }
    .chat-channel-btn { width: auto; border-radius: 20px; border-left: none; padding: 0.4rem 0.875rem; font-size: 0.8rem; }
    .chat-channel-btn.active { border: 1px solid #444; }
    .chat-main { height: calc(100vh - 300px); }
}
</style>
@endpush

@section('content')
<div class="chat-wrapper">

    {{-- Channels Sidebar --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-title">Channels</div>
        @php
        $channelMeta = [
            'general'       => ['icon' => 'fas fa-hashtag', 'label' => 'general',       'desc' => 'General discussion'],
            'ai-automation' => ['icon' => 'fas fa-robot',   'label' => 'ai-automation', 'desc' => 'AI & Automation talk'],
            'startups'      => ['icon' => 'fas fa-rocket',  'label' => 'startups',      'desc' => 'Startup ideas & funding'],
            'tech-talk'     => ['icon' => 'fas fa-code',    'label' => 'tech-talk',     'desc' => 'Dev & tech discussion'],
            'off-topic'     => ['icon' => 'fas fa-coffee',  'label' => 'off-topic',     'desc' => 'Casual conversation'],
        ];
        @endphp
        @foreach($channelMeta as $key => $meta)
        <a href="{{ route('chat.index', ['channel' => $key]) }}"
           class="chat-channel-btn {{ $channel === $key ? 'active' : '' }}">
            <span class="hash">#</span> {{ $meta['label'] }}
        </a>
        @endforeach
    </div>

    {{-- Chat Main --}}
    <div class="chat-main">
        <div class="chat-header">
            <span class="chat-header-hash">#</span>
            <span class="chat-header-name">{{ $channel }}</span>
            <span class="chat-header-desc">
                <span class="online-indicator"></span>
                Live chat — messages refresh automatically
            </span>
        </div>

        <div class="chat-messages" id="chatMessages">
            @if($messages->isEmpty())
            <div class="empty-chat">
                <i class="fas fa-comment-slash"></i>
                <p>No messages yet in <strong>#{{ $channel }}</strong>.<br>Be the first to say something!</p>
            </div>
            @else
            <div class="chat-day-divider">Today</div>
            @foreach($messages as $msg)
            <div class="chat-msg" id="msg-{{ $msg['id'] }}">
                <div class="chat-avatar {{ $msg['is_mine'] ? 'mine' : '' }}">
                    {{ strtoupper(substr($msg['user_name'], 0, 1)) }}
                </div>
                <div class="chat-msg-body">
                    <div class="chat-msg-meta">
                        <span class="msg-author {{ $msg['is_mine'] ? 'mine' : '' }}">
                            {{ $msg['user_name'] }}{{ $msg['is_mine'] ? ' (you)' : '' }}
                        </span>
                        <span class="msg-time">{{ $msg['time'] }}</span>
                    </div>
                    <div class="msg-text">{{ $msg['message'] }}</div>
                </div>
                @if($msg['can_delete'])
                <button class="msg-delete" onclick="deleteMessage({{ $msg['id'] }})">
                    <i class="fas fa-trash-alt"></i>
                </button>
                @endif
            </div>
            @endforeach
            @endif
        </div>

        <div class="chat-input-area">
            <form class="chat-input-form" id="chatForm" onsubmit="sendMessage(event)">
                @csrf
                <textarea
                    class="chat-input"
                    id="chatInput"
                    placeholder="Message #{{ $channel }}..."
                    rows="1"
                    maxlength="1000"
                    onkeydown="handleEnter(event)"
                    oninput="autoResize(this)"
                ></textarea>
                <button type="submit" class="chat-send-btn" id="sendBtn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const CHANNEL = '{{ $channel }}';
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
let lastMessageId = {{ $messages->isEmpty() ? 0 : $messages->last()['id'] ?? 0 }};
let isPolling = true;

// Scroll to bottom on load
scrollToBottom();

function scrollToBottom() {
    const container = document.getElementById('chatMessages');
    container.scrollTop = container.scrollHeight;
}

function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}

function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chatForm').dispatchEvent(new Event('submit'));
    }
}

async function sendMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chatInput');
    const btn = document.getElementById('sendBtn');
    const text = input.value.trim();
    if (!text) return;

    btn.disabled = true;
    input.value = '';
    input.style.height = 'auto';

    try {
        const res = await fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message: text, channel: CHANNEL }),
        });
        const data = await res.json();
        if (data.success) {
            appendMessage(data.message);
            lastMessageId = data.message.id;
            scrollToBottom();
        }
    } catch (err) {
        console.error('Send failed:', err);
    } finally {
        btn.disabled = false;
        input.focus();
    }
}

function appendMessage(msg) {
    const container = document.getElementById('chatMessages');
    // Remove empty state if present
    const empty = container.querySelector('.empty-chat');
    if (empty) empty.remove();

    const div = document.createElement('div');
    div.className = 'chat-msg';
    div.id = 'msg-' + msg.id;
    div.innerHTML = `
        <div class="chat-avatar ${msg.is_mine ? 'mine' : ''}">
            ${msg.user_name.charAt(0).toUpperCase()}
        </div>
        <div class="chat-msg-body">
            <div class="chat-msg-meta">
                <span class="msg-author ${msg.is_mine ? 'mine' : ''}">
                    ${msg.user_name}${msg.is_mine ? ' (you)' : ''}
                </span>
                <span class="msg-time">${msg.time}</span>
            </div>
            <div class="msg-text">${escapeHtml(msg.message)}</div>
        </div>
        ${msg.can_delete ? `<button class="msg-delete" onclick="deleteMessage(${msg.id})"><i class="fas fa-trash-alt"></i></button>` : ''}
    `;
    container.appendChild(div);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

async function deleteMessage(id) {
    if (!confirm('Delete this message?')) return;
    try {
        await fetch(`/chat/messages/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });
        const el = document.getElementById('msg-' + id);
        if (el) {
            el.querySelector('.msg-text').textContent = '[Message deleted]';
            el.querySelector('.msg-text').style.color = '#555';
            el.querySelector('.msg-text').style.fontStyle = 'italic';
            const btn = el.querySelector('.msg-delete');
            if (btn) btn.remove();
        }
    } catch (err) {
        console.error('Delete failed:', err);
    }
}

// Long-polling: check for new messages every 3 seconds
async function pollMessages() {
    if (!isPolling) return;
    try {
        const res = await fetch(`{{ route('chat.messages') }}?channel=${CHANNEL}&after_id=${lastMessageId}`, {
            headers: { 'Accept': 'application/json' },
        });
        const data = await res.json();
        if (data.messages && data.messages.length > 0) {
            const container = document.getElementById('chatMessages');
            const isAtBottom = container.scrollHeight - container.scrollTop - container.clientHeight < 80;
            data.messages.forEach(msg => {
                if (!document.getElementById('msg-' + msg.id)) {
                    appendMessage(msg);
                    lastMessageId = msg.id;
                }
            });
            if (isAtBottom) scrollToBottom();
        }
    } catch (err) {
        // Silently fail on network errors
    }
    setTimeout(pollMessages, 3000);
}

// Start polling after 3 seconds
setTimeout(pollMessages, 3000);

// Stop polling when tab is hidden
document.addEventListener('visibilitychange', () => {
    isPolling = !document.hidden;
    if (!document.hidden) setTimeout(pollMessages, 1000);
});
</script>
@endpush
