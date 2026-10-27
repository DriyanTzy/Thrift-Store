@extends('layouts.app')

@section('content')
@push('styles')
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 340px 1fr;
        height: 720px;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        margin-bottom: 2rem;
    }

    @media (max-width: 900px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: auto;
            min-height: 600px;
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
        background: #F8FAFC;
    }

    .chat-sidebar-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #E2E8F0;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-sidebar-title {
        font-size: 1.2rem;
        font-weight: 900;
        color: #0F172A;
        letter-spacing: -0.3px;
    }

    .chat-search-wrap {
        padding: 0.85rem 1.25rem;
        background: #FFFFFF;
        border-bottom: 1px solid #E2E8F0;
    }

    .chat-search-input {
        width: 100%;
        background: #F1F5F9;
        border: 1px solid #CBD5E1;
        border-radius: 50px;
        padding: 0.55rem 1rem 0.55rem 2.25rem;
        font-size: 0.85rem;
        outline: none;
        box-sizing: border-box;
    }

    .chat-conv-list {
        flex: 1;
        overflow-y: auto;
    }

    .chat-conv-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #F1F5F9;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s ease;
    }

    .chat-conv-item:hover {
        background: #F1F5F9;
    }

    .chat-conv-item.active {
        background: #FFFFFF;
        border-left: 4px solid #0F172A;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
    }

    .chat-avatar {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #0F172A;
        color: #22C55E;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 1.1rem;
        flex-shrink: 0;
        position: relative;
    }

    .chat-avatar.user-avatar {
        background: #3B82F6;
        color: #FFF;
    }

    .chat-unread-badge {
        background: #EF4444;
        color: #FFF;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        margin-left: auto;
        flex-shrink: 0;
    }

    /* Main Chat Panel */
    .chat-main {
        display: flex;
        flex-direction: column;
        background: #FFFFFF;
        position: relative;
    }

    .chat-header {
        padding: 1.1rem 1.5rem;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #FFFFFF;
        z-index: 10;
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .chat-product-bar {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 0.75rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .chat-product-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #EEF2F6;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .chat-messages-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .chat-bubble {
        max-width: 65%;
        padding: 0.85rem 1.15rem;
        border-radius: 16px;
        font-size: 0.9rem;
        line-height: 1.45;
        position: relative;
        word-break: break-word;
    }

    .chat-bubble.me {
        align-self: flex-end;
        background: #0F172A;
        color: #FFFFFF;
        border-bottom-right-radius: 4px;
        box-shadow: 0 4px 12px rgba(15,23,42,0.15);
    }

    .chat-bubble.other {
        align-self: flex-start;
        background: #FFFFFF;
        color: #0F172A;
        border: 1px solid #E2E8F0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    .chat-bubble-time {
        font-size: 0.68rem;
        margin-top: 0.35rem;
        text-align: right;
        opacity: 0.75;
    }

    .chat-bubble.me .chat-bubble-time {
        color: #94A3B8;
    }

    .chat-bubble.other .chat-bubble-time {
        color: #64748B;
    }

    .chat-footer {
        padding: 1rem 1.5rem;
        background: #FFFFFF;
        border-top: 1px solid #E2E8F0;
    }

    .chat-form {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        background: #F8FAFC;
        border: 1.5px solid #CBD5E1;
        border-radius: 50px;
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
        font-family: inherit;
    }

    .chat-input:focus {
        border-color: #0F172A;
        background: #FFFFFF;
    }

    .chat-send-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: #0F172A;
        color: #FFFFFF;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(15,23,42,0.25);
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .chat-send-btn:hover {
        background: #1E293B;
        transform: scale(1.05);
    }
</style>
@endpush

<div style="max-width: 1200px; margin: 0 auto;">

    <!-- Top Breadcrumb -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748B; font-weight: 700;">
            <a href="{{ route('products.index') }}" style="color: #64748B; text-decoration: none;"><i class="fa-solid fa-house"></i> Home</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i>
            <span style="color: #0F172A; font-weight: 800;">Pesan & Chat Toko</span>
        </div>
        <div style="font-size: 0.8rem; font-weight: 800; color: #16A34A; background: #DCFCE7; padding: 0.3rem 0.85rem; border-radius: 30px;">
            <i class="fa-solid fa-comments"></i> Live Chat Buyer & Seller
        </div>
    </div>

    <!-- Main Chat Container -->
    <div class="chat-container">

        <!-- 1. LEFT SIDEBAR: Conversation List -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <span class="chat-sidebar-title">Obrolan ({{ $conversations->count() }})</span>
                <span style="font-size: 0.75rem; font-weight: 800; color: #64748B; background: #F1F5F9; padding: 0.2rem 0.55rem; border-radius: 8px;">
                    1 Akun 2 Profil
                </span>
            </div>

            <div class="chat-search-wrap">
                <div style="position: relative;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 0.8rem;"></i>
                    <input type="text" id="conv-search" class="chat-search-input" placeholder="Cari nama toko / pengguna..." onkeyup="filterConversations()">
                </div>
            </div>

            <div class="chat-conv-list" id="conv-list">
                @forelse($conversations as $conv)
                    @php
                        $isMeBuyer = ($conv->buyer_id === auth()->id());
                        $counterparty = $isMeBuyer ? $conv->seller : $conv->buyer;
                        // Jika counterparty adalah penjual, tampilkan nama tokonya!
                        $displayName = $isMeBuyer ? $counterparty->store_name : $counterparty->name;
                        $displaySub = $isMeBuyer ? 'Toko Penjual' : 'Pembeli';
                        $unread = $conv->unreadCountFor(auth()->id());
                        $latest = $conv->latestMessage;
                        $isActive = $activeConversation && ($activeConversation->id === $conv->id);
                    @endphp

                    <a href="{{ route('chat.index', ['conversation_id' => $conv->id]) }}" 
                       class="chat-conv-item {{ $isActive ? 'active' : '' }}"
                       data-name="{{ strtolower($displayName) }}">
                        
                        <div class="chat-avatar {{ $isMeBuyer ? '' : 'user-avatar' }}">
                            <i class="fa-solid {{ $isMeBuyer ? 'fa-store' : 'fa-user' }}"></i>
                        </div>

                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.2rem;">
                                <div style="font-weight: 800; font-size: 0.9rem; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;" title="{{ $displayName }}">
                                    {{ $displayName }}
                                </div>
                                <span style="font-size: 0.7rem; color: #94A3B8;">
                                    {{ $latest ? $latest->created_at->format('H:i') : '' }}
                                </span>
                            </div>

                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                                <p style="font-size: 0.78rem; color: #64748B; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $latest ? $latest->message : 'Mulai percakapan...' }}
                                </p>
                                @if($unread > 0)
                                    <span class="chat-unread-badge">{{ $unread }}</span>
                                @endif
                            </div>
                        </div>

                    </a>
                @empty
                    <div style="padding: 3rem 1.5rem; text-align: center; color: #94A3B8;">
                        <i class="fa-regular fa-comments fa-3x" style="color: #CBD5E1; margin-bottom: 0.75rem; display: block;"></i>
                        <div style="font-weight: 800; font-size: 0.95rem; color: #0F172A; margin-bottom: 0.25rem;">Belum ada pesan</div>
                        <p style="font-size: 0.8rem; margin: 0;">Mulai obrolan dengan mengklik tombol "Chat Penjual" di halaman barang atau etalase toko.</p>
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
                    $partnerType = $isMeBuyer ? 'Penjual (Toko Resmi)' : 'Pembeli';
                    $partnerCity = $counterparty->store_city ?? 'Indonesia';
                    $product = $activeConversation->product;
                @endphp

                <!-- Header -->
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div class="chat-avatar {{ $isMeBuyer ? '' : 'user-avatar' }}" style="width: 44px; height: 44px;">
                            <i class="fa-solid {{ $isMeBuyer ? 'fa-store' : 'fa-user' }}"></i>
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <h3 style="font-size: 1.05rem; font-weight: 900; color: #0F172A; margin: 0;">
                                    {{ $partnerName }}
                                </h3>
                                <span style="background: rgba(34,197,94,0.12); color: #16A34A; font-size: 0.7rem; font-weight: 800; padding: 2px 7px; border-radius: 12px;">
                                    {{ $partnerType }}
                                </span>
                            </div>
                            <div style="font-size: 0.75rem; color: #64748B; margin-top: 0.15rem;">
                                <i class="fa-solid fa-location-dot" style="color: #22C55E;"></i> {{ $partnerCity }} • Aktif Baru Saja
                            </div>
                        </div>
                    </div>

                    @if($isMeBuyer && $counterparty->id)
                        <a href="{{ route('shop.store', $counterparty->id) }}" style="background: #F1F5F9; color: #0F172A; font-weight: 800; font-size: 0.8rem; padding: 0.5rem 1rem; border-radius: 10px; text-decoration: none; border: 1px solid #CBD5E1; display: inline-flex; align-items: center; gap: 0.35rem;">
                            <i class="fa-solid fa-store" style="color: #22C55E;"></i> Kunjungi Toko
                        </a>
                    @endif
                </div>

                <!-- Product Reference Bar (If conversation is about a product) -->
                @if($product)
                    @php
                        $prodImg = $product->images->count() > 0 ? asset('storage/' . $product->images->first()->image_path) : ($product->image ? asset('storage/' . $product->image) : null);
                    @endphp
                    <div class="chat-product-bar">
                        <div style="display: flex; align-items: center; gap: 0.85rem; flex: 1; min-width: 0;">
                            <div class="chat-product-thumb">
                                @if($prodImg)
                                    <img src="{{ $prodImg }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-shirt" style="color: #64748B;"></i>
                                @endif
                            </div>
                            <div style="min-width: 0;">
                                <div style="font-weight: 800; font-size: 0.88rem; color: #0F172A; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $product->name }}
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.15rem;">
                                    <span style="font-weight: 900; font-size: 0.85rem; color: #0F172A;">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->stock <= 0)
                                        <span style="background: #FEE2E2; color: #DC2626; font-size: 0.7rem; font-weight: 800; padding: 1px 6px; border-radius: 4px;">
                                            Stok Habis
                                        </span>
                                    @else
                                        <span style="background: #DCFCE7; color: #16A34A; font-size: 0.7rem; font-weight: 800; padding: 1px 6px; border-radius: 4px;">
                                            Stok: {{ $product->stock }} Ready
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" style="background: #0F172A; color: #FFF; font-weight: 800; font-size: 0.78rem; padding: 0.45rem 0.9rem; border-radius: 8px; text-decoration: none; white-space: nowrap;">
                            Buka Barang <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem; margin-left: 0.2rem;"></i>
                        </a>
                    </div>
                @endif

                <!-- Chat Messages Body -->
                <div class="chat-messages-body" id="messages-container">
                    @forelse($activeMessages as $msg)
                        @php
                            $isMe = ($msg->sender_id === auth()->id());
                        @endphp
                        <div class="chat-bubble {{ $isMe ? 'me' : 'other' }}" data-id="{{ $msg->id }}">
                            <div>{{ $msg->message }}</div>
                            <div class="chat-bubble-time">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isMe)
                                    <i class="fa-solid fa-check-double" style="margin-left: 0.25rem; font-size: 0.65rem; color: {{ $msg->is_read ? '#4ADE80' : '#94A3B8' }};"></i>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 3rem 1rem; color: #94A3B8;">
                            <i class="fa-regular fa-comment-dots fa-3x" style="color: #CBD5E1; margin-bottom: 0.5rem; display: block;"></i>
                            <p style="margin: 0; font-size: 0.85rem;">Mulai percakapan Anda dengan mengirimkan pesan di bawah ini.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Footer Form -->
                <div class="chat-footer">
                    <form id="chat-form" action="{{ route('chat.message', $activeConversation->id) }}" method="POST" class="chat-form" onsubmit="submitChatMessage(event)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product?->id }}">
                        <input type="text" id="chat-text-input" name="message" class="chat-input" placeholder="Ketik pesan Anda di sini..." autocomplete="off" required>
                        <button type="submit" class="chat-send-btn" title="Kirim Pesan">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>

            @else
                <!-- No Conversation Selected State -->
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 2rem; text-align: center; color: #94A3B8;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: #F1F5F9; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; color: #CBD5E1; margin-bottom: 1.25rem;">
                        <i class="fa-regular fa-comments"></i>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 900; color: #0F172A; margin: 0 0 0.4rem;">Pilih Obrolan</h3>
                    <p style="font-size: 0.9rem; max-width: 360px; margin: 0; line-height: 1.5;">
                        Pilih salah satu kontak percakapan di bilah kiri atau kunjungi produk/toko untuk memulai chat baru.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script>
    // Scroll chat to bottom
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    scrollToBottom();

    // Filter conversations on sidebar
    function filterConversations() {
        const query = document.getElementById('conv-search').value.toLowerCase();
        document.querySelectorAll('.chat-conv-item').forEach(el => {
            const name = el.getAttribute('data-name') || '';
            el.style.display = name.includes(query) ? 'flex' : 'none';
        });
    }

    @if($activeConversation)
    // AJAX message submission for instant smooth messaging
    async function submitChatMessage(e) {
        e.preventDefault();
        const input = document.getElementById('chat-text-input');
        const text = input.value.trim();
        if (!text) return;

        input.value = '';

        // Optimistically render message
        const container = document.getElementById('messages-container');
        const tempBubble = document.createElement('div');
        tempBubble.className = 'chat-bubble me';
        const now = new Date();
        const timeStr = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        tempBubble.innerHTML = `<div>${escapeHtml(text)}</div><div class="chat-bubble-time">${timeStr} <i class="fa-solid fa-check" style="margin-left: 0.25rem; font-size: 0.65rem;"></i></div>`;
        container.appendChild(tempBubble);
        scrollToBottom();

        try {
            const formData = new FormData(document.getElementById('chat-form'));
            formData.set('message', text);

            const res = await fetch("{{ route('chat.message', $activeConversation->id) }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success && data.message) {
                tempBubble.setAttribute('data-id', data.message.id);
            }
        } catch (err) {
            console.error('Chat error:', err);
        }
    }

    // Polling new incoming messages every 3 seconds
    let lastMsgId = 0;
    const existingBubbles = document.querySelectorAll('.chat-bubble');
    if (existingBubbles.length > 0) {
        const lastEl = existingBubbles[existingBubbles.length - 1];
        lastMsgId = parseInt(lastEl.getAttribute('data-id') || '0', 10);
    }

    setInterval(async () => {
        try {
            const res = await fetch("{{ route('chat.fetch', $activeConversation->id) }}?after_id=" + lastMsgId);
            const data = await res.json();
            if (data.messages && data.messages.length > 0) {
                const container = document.getElementById('messages-container');
                data.messages.forEach(m => {
                    if (m.id > lastMsgId) {
                        lastMsgId = m.id;
                        if (!m.is_me) {
                            const b = document.createElement('div');
                            b.className = 'chat-bubble other';
                            b.setAttribute('data-id', m.id);
                            b.innerHTML = `<div>${escapeHtml(m.message)}</div><div class="chat-bubble-time">${m.created_at}</div>`;
                            container.appendChild(b);
                            scrollToBottom();
                        }
                    }
                });
            }
        } catch (e) {
            // Ignore background polling errors
        }
    }, 3000);

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    @endif
</script>
@endpush
@endsection
