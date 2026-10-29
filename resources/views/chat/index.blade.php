@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 360px 1fr;
        height: 720px;
        max-height: 720px;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
    }

    @media (max-width: 900px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: calc(100vh - 120px);
            max-height: 720px;
            min-height: 550px;
        }
        .chat-sidebar {
            display: {{ $activeConversation ? 'none' : 'flex' }} !important;
        }
        .chat-main {
            display: {{ $activeConversation ? 'flex' : 'none' }} !important;
        }
    }

    /* Sidebar Conversations */
    .chat-sidebar {
        border-right: 1.5px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        background: #FFFFFF;
        height: 100%;
        max-height: 100%;
        overflow: hidden;
    }

    .chat-sidebar-header {
        padding: 1.5rem 1.75rem 1rem 1.75rem;
        background: #FFFFFF;
        flex-shrink: 0;
    }

    .chat-sidebar-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #000000;
        letter-spacing: -0.5px;
    }

    .chat-conv-list {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }

    .chat-conv-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.75rem;
        border-bottom: 1px solid #F1F5F9;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s ease;
    }

    .chat-conv-item:hover {
        background: #F8FAFC;
    }

    .chat-conv-item.active {
        background: #F8FAFC;
    }

    .chat-avatar-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #E07A7A;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #FFF;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .chat-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .chat-unread-badge-red {
        background: #EF4444;
        color: #FFF;
        font-size: 0.7rem;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        flex-shrink: 0;
    }

    /* Main Chat Panel */
    .chat-main {
        display: flex;
        flex-direction: column;
        background: #EFEFEF;
        position: relative;
        height: 100%;
        max-height: 100%;
        min-height: 0;
        overflow: hidden;
    }

    .chat-header {
        padding: 1rem 1.75rem;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        z-index: 10;
        flex-shrink: 0;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .chat-messages-body {
        flex: 1 1 auto;
        overflow-y: auto;
        min-height: 0;
        max-height: 100%;
        padding: 2rem 1.75rem;
        background: #EFEFEF;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        scroll-behavior: smooth;
        overscroll-behavior: contain;
    }

    /* Custom subtle scrollbar */
    .chat-messages-body::-webkit-scrollbar,
    .chat-conv-list::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages-body::-webkit-scrollbar-track,
    .chat-conv-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages-body::-webkit-scrollbar-thumb,
    .chat-conv-list::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 10px;
    }

    .chat-messages-body::-webkit-scrollbar-thumb:hover,
    .chat-conv-list::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }

    .chat-bubble {
        max-width: 60%;
        padding: 1.1rem 1.5rem;
        border-radius: 20px;
        font-size: 0.95rem;
        line-height: 1.5;
        position: relative;
        word-break: break-word;
        animation: chatFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        flex-shrink: 0;
    }

    @keyframes chatFadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .chat-bubble.me {
        align-self: flex-end;
        background: #000000;
        color: #FFFFFF;
        border-top-right-radius: 4px;
        font-weight: 700;
    }

    .chat-bubble.other {
        align-self: flex-start;
        background: #FFFFFF;
        color: #000000;
        border: 1px solid #E2E8F0;
        border-top-left-radius: 4px;
        font-weight: 600;
    }

    .chat-footer {
        padding: 1.25rem 1.75rem;
        background: #FFFFFF;
        border-top: 1px solid #E2E8F0;
        flex-shrink: 0;
    }

    .chat-form {
        display: flex;
        gap: 0.85rem;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        background: #FFFFFF;
        border: 1.5px solid #CBD5E1;
        border-radius: 50px;
        padding: 0.85rem 1.5rem;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.15s ease;
        font-family: inherit;
        color: #000;
    }

    .chat-input:focus {
        border-color: #000000;
    }

    .chat-input::placeholder {
        color: #94A3B8;
    }

    .chat-send-btn {
        background: #000000;
        color: #FFFFFF;
        border: none;
        border-radius: 50px;
        padding: 0.85rem 1.75rem;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.1s ease, opacity 0.15s ease;
        font-family: inherit;
    }

    .chat-send-btn:hover {
        opacity: 0.85;
    }

    .chat-send-btn:active {
        transform: scale(0.96);
    }
</style>
@endpush

<div style="max-width: 1200px; margin: 0 auto;">

    <div class="chat-container">

        <!-- 1. LEFT SIDEBAR: Conversations List -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <span class="chat-sidebar-title">Chat</span>
            </div>

            <div class="chat-conv-list" id="conv-list">
                @forelse($conversations as $conv)
                    @php
                        $isMeBuyer = ($conv->buyer_id === auth()->id());
                        $counterparty = $isMeBuyer ? $conv->seller : $conv->buyer;
                        $displayName = $isMeBuyer ? $counterparty->store_name : $counterparty->name;
                        $unread = $conv->unreadCountFor(auth()->id());
                        $latest = $conv->latestMessage;
                        $isActive = $activeConversation && ($activeConversation->id === $conv->id);
                        $bgColors = ['#E07A7A', '#5A1E1E', '#D97706', '#2563EB', '#4F46E5', '#059669'];
                        $assignedColor = $bgColors[abs(crc32($displayName)) % count($bgColors)];
                    @endphp

                    <a href="{{ route('chat.index', ['conversation_id' => $conv->id]) }}" 
                       class="chat-conv-item {{ $isActive ? 'active' : '' }}"
                       data-name="{{ strtolower($displayName) }}">
                        
                        <!-- Circular Avatar -->
                        <div class="chat-avatar-circle" style="background: {{ $assignedColor }};">
                            @php
                                $photoUrl = $counterparty->getProfilePictureForContext($isMeBuyer);
                            @endphp
                            @if($photoUrl)
                                <img src="{{ $photoUrl }}" alt="{{ $displayName }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr($displayName, 0, 1)) }}
                            @endif
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.25rem;">
                                <div style="font-weight: 800; font-size: 0.95rem; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;" title="{{ $displayName }}">
                                    {{ $displayName }}
                                </div>
                                <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 500;">
                                    @if($latest)
                                        {{ $latest->created_at->isToday() ? $latest->created_at->format('H:i') : ($latest->created_at->isYesterday() ? 'Kemarin' : $latest->created_at->format('d/m')) }}
                                    @endif
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                <p style="font-size: 0.85rem; color: #64748B; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $latest ? $latest->message : 'Mulai percakapan...' }}
                                </p>
                                @if($unread > 0)
                                    <span class="chat-unread-badge-red">{{ $unread }}</span>
                                @endif
                            </div>
                        </div>

                    </a>
                @empty
                    <div style="padding: 4rem 1.5rem; text-align: center; color: #94A3B8;">
                        <i class="fa-regular fa-comments fa-3x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                        <div style="font-weight: 800; font-size: 1rem; color: #000; margin-bottom: 0.25rem;">Belum ada pesan</div>
                        <p style="font-size: 0.85rem; margin: 0;">Mulai obrolan dengan penjual di etalase toko.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 2. RIGHT MAIN PANEL: Active Chat -->
        <div class="chat-main">
            @if($activeConversation)
                @php
                    $isMeBuyer = ($activeConversation->buyer_id === auth()->id());
                    $counterparty = $isMeBuyer ? $activeConversation->seller : $activeConversation->buyer;
                    $partnerName = $isMeBuyer ? $counterparty->store_name : $counterparty->name;
                    $bgColors = ['#E07A7A', '#5A1E1E', '#D97706', '#2563EB', '#4F46E5', '#059669'];
                    $headerColor = $bgColors[abs(crc32($partnerName)) % count($bgColors)];
                @endphp

                <!-- Top Header -->
                <div class="chat-header">
                    <div class="chat-header-info">
                        @php
                            $headerPhotoUrl = $counterparty->getProfilePictureForContext($isMeBuyer);
                        @endphp
                        <div class="chat-avatar-circle" style="width: 44px; height: 44px; background: {{ $headerColor }};">
                            @if($headerPhotoUrl)
                                <img src="{{ $headerPhotoUrl }}" alt="{{ $partnerName }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr($partnerName, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h3 style="font-size: 1.05rem; font-weight: 800; color: #000; margin: 0;">
                                {{ $partnerName }}
                            </h3>
                            <div style="font-size: 0.75rem; color: #16A34A; font-weight: 700; margin-top: 0.1rem;">
                                online
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Body -->
                <div class="chat-messages-body" id="messages-container">
                    @forelse($activeMessages as $msg)
                        @php
                            $isMe = ($msg->sender_id === auth()->id());
                        @endphp
                        <div class="chat-bubble {{ $isMe ? 'me' : 'other' }}" data-id="{{ $msg->id }}">
                            <div>{!! nl2br(e($msg->message)) !!}</div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 4rem 1rem; color: #94A3B8;">
                            <i class="fa-regular fa-comment-dots fa-3x" style="color: #CBD5E1; margin-bottom: 0.5rem; display: block;"></i>
                            <p style="margin: 0; font-size: 0.9rem;">Mulai percakapan Anda dengan mengirimkan pesan di bawah ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer Form -->
                <div class="chat-footer">
                    <form id="chat-form" action="{{ route('chat.message', $activeConversation->id) }}" method="POST" class="chat-form" onsubmit="submitChatMessage(event)">
                        @csrf
                        <input type="text" id="chat-text-input" name="message" class="chat-input" placeholder="Tulis pesan..." autocomplete="off" required>
                        <button type="submit" class="chat-send-btn">
                            Kirim
                        </button>
                    </form>
                </div>

            @else
                <!-- No Conversation Selected State -->
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 2rem; text-align: center; color: #94A3B8;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #CBD5E1; margin-bottom: 1.25rem;">
                        <i class="fa-regular fa-comments"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #000; margin: 0 0 0.4rem;">Pilih Obrolan</h3>
                    <p style="font-size: 0.9rem; max-width: 360px; margin: 0; line-height: 1.5;">
                        Pilih salah satu kontak percakapan di bilah kiri untuk membuka chat.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script>
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    scrollToBottom();

    function submitChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chat-text-input');
        const message = input.value.trim();
        if (!message) return;

        const form = document.getElementById('chat-form');
        const url = form.action;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        // Optimistic UI append
        const container = document.getElementById('messages-container');
        const tempId = 'temp_' + Date.now();
        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble me';
        bubble.id = tempId;
        bubble.innerHTML = `<div>${escapeHtml(message)}</div>`;
        container.appendChild(bubble);
        scrollToBottom();

        input.value = '';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.message) {
                const el = document.getElementById(tempId);
                if (el) el.setAttribute('data-id', data.message.id);
            }
        })
        .catch(() => {
            const el = document.getElementById(tempId);
            if (el) el.style.opacity = '0.5';
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    // Polling new messages
    @if($activeConversation)
    let lastMsgId = 0;
    const existingBubbles = document.querySelectorAll('.chat-bubble[data-id]');
    if (existingBubbles.length > 0) {
        lastMsgId = parseInt(existingBubbles[existingBubbles.length - 1].getAttribute('data-id')) || 0;
    }

    function fetchNewMessages() {
        fetch("{{ route('chat.fetch', $activeConversation->id) }}?last_id=" + lastMsgId, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                const container = document.getElementById('messages-container');
                data.messages.forEach(msg => {
                    if (msg.id > lastMsgId) {
                        lastMsgId = msg.id;
                        if (!msg.is_me) {
                            const bubble = document.createElement('div');
                            bubble.className = 'chat-bubble other';
                            bubble.setAttribute('data-id', msg.id);
                            bubble.innerHTML = `<div>${escapeHtml(msg.message)}</div>`;
                            container.appendChild(bubble);
                            scrollToBottom();
                        }
                    }
                });
            }
        })
        .catch(() => {});
    }

    setInterval(fetchNewMessages, 3000);
    @endif
</script>
@endpush
@endsection
