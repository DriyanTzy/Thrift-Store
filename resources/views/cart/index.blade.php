@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 3rem;">

    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: flex-start;">
        <!-- Left: Cart Items Card -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="padding: 1.5rem 2rem 1rem 2rem;">
                <h1 style="font-size: 1.6rem; font-weight: 800; color: #000; margin: 0 0 0.25rem 0;">Keranjang</h1>
                <p id="cart-summary-text" style="font-size: 0.85rem; color: #94A3B8; margin: 0;">0 produk dari 0 toko</p>
            </div>

            <!-- List by Store / Items container -->
            <div id="cart-page-items-container">
                <!-- Dynamically rendered by JS -->
            </div>
        </div>

        <!-- Right: Ringkasan Belanja Box -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 24px; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1.25rem; font-weight: 800; color: #000; margin: 0 0 1.25rem 0;">Ringkasan Belanja</h2>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.85rem; font-size: 0.95rem;">
                <span id="subtotal-label" style="color: #94A3B8;">Subtotal (0 barang)</span>
                <span id="subtotal-value" style="font-weight: 800; color: #000;">Rp 0</span>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; font-size: 0.95rem;">
                <span style="color: #94A3B8;">Ongkos kirim</span>
                <span id="shipping-value" style="font-weight: 800; color: #000;">Rp 12.000</span>
            </div>

            <!-- Promo Code Input -->
            <div style="margin-bottom: 1.5rem;">
                <div style="position: relative;">
                    <input type="text" id="cart-promo-input" placeholder="pakai kode promo" 
                           style="width: 100%; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.9rem; color: #0F172A; outline: none; transition: border-color 0.2s;">
                    <button type="button" onclick="applyPromoCode()" 
                            style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: #F1F5F9; border: none; padding: 0.35rem 0.75rem; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; color: #0F172A;">
                        Gunakan
                    </button>
                </div>
                <div id="promo-message" style="font-size: 0.75rem; margin-top: 0.4rem; display: none;"></div>
            </div>

            <div style="border-top: 1.5px solid #E2E8F0; padding-top: 1.25rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 1.05rem; font-weight: 700; color: #000;">Total</span>
                <span id="total-with-shipping" style="font-size: 1.2rem; font-weight: 900; color: #000;">Rp 0</span>
            </div>

            <button type="button" onclick="proceedCheckoutFromCartPage()" 
                    style="width: 100%; background: #000000; color: #FFFFFF; font-weight: 800; font-size: 1rem; padding: 0.85rem 1rem; border-radius: 50px; border: none; cursor: pointer; transition: opacity 0.2s; text-align: center;">
                checkout
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script>
    let appliedDiscount = 0;
    let appliedVoucherCode = '';
    const SHIPPING_COST = 12000;

    function renderCartPage() {
        const cart = getCart();
        const container = document.getElementById('cart-page-items-container');
        const summaryText = document.getElementById('cart-summary-text');
        const subtotalLabel = document.getElementById('subtotal-label');
        const subtotalValue = document.getElementById('subtotal-value');
        const totalValue = document.getElementById('total-with-shipping');
        const shippingValue = document.getElementById('shipping-value');

        if (!container) return;

        if (cart.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 4rem 2rem; color: #94A3B8;">
                    <i class="fa-solid fa-cart-shopping fa-3x" style="color: #CBD5E1; margin-bottom: 1rem; display: block;"></i>
                    <h3 style="font-weight: 800; color: #0F172A; font-size: 1.2rem; margin-bottom: 0.35rem;">Keranjang Anda Masih Kosong</h3>
                    <p style="font-size: 0.9rem; margin-bottom: 1.25rem;">Yuk temukan barang thrifting vintage impian Anda!</p>
                    <a href="{{ route('products.index') }}" style="background: #000; color: #FFF; font-weight: 800; padding: 0.65rem 1.5rem; border-radius: 50px; text-decoration: none; font-size: 0.85rem; display: inline-block;">Mulai Belanja</a>
                </div>
            `;
            summaryText.innerText = '0 produk dari 0 toko';
            subtotalLabel.innerText = 'Subtotal (0 barang)';
            subtotalValue.innerText = 'Rp 0';
            shippingValue.innerText = 'Rp 0';
            totalValue.innerText = 'Rp 0';
            return;
        }

        // Group by store
        const grouped = {};
        let totalSelectedQty = 0;
        let totalSelectedPrice = 0;
        let storeCount = 0;

        cart.forEach(item => {
            const store = item.store_name || 'Ruang Lemari Store';
            if (!grouped[store]) {
                grouped[store] = [];
                storeCount++;
            }
            grouped[store].push(item);

            if (item.selected) {
                totalSelectedQty += item.qty;
                totalSelectedPrice += (item.price * item.qty);
            }
        });

        summaryText.innerText = `${cart.length} produk dari ${storeCount} toko`;
        subtotalLabel.innerText = `Subtotal (${totalSelectedQty} barang)`;
        subtotalValue.innerText = formatRupiah(totalSelectedPrice);

        const currentShipping = totalSelectedQty > 0 ? SHIPPING_COST : 0;
        shippingValue.innerText = formatRupiah(currentShipping);

        const finalTotal = Math.max(0, totalSelectedPrice + currentShipping - appliedDiscount);
        totalValue.innerText = formatRupiah(finalTotal);

        let html = '';

        for (const [storeName, items] of Object.entries(grouped)) {
            html += `
                <div style="background: #E2E8F0; padding: 0.65rem 2rem; font-weight: 700; font-size: 0.9rem; color: #000;">
                    ${storeName}
                </div>
            `;

            items.forEach((item, idx) => {
                const borderBottom = (idx === items.length - 1) ? '' : 'border-bottom: 1px solid #E2E8F0;';
                const imgTag = item.image 
                    ? `<img src="${item.image}" alt="${item.name}" style="width: 100%; height: 100%; object-fit: contain;">`
                    : `<i class="fa-solid fa-shirt" style="font-size: 1.5rem; color: #64748B;"></i>`;

                html += `
                    <div style="padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; ${borderBottom}">
                        <div style="display: flex; align-items: center; gap: 1.25rem; flex: 1; min-width: 0;">
                            <!-- Checkbox -->
                            <input type="checkbox" ${item.selected ? 'checked' : ''} onchange="toggleCartPageItemSelection(${item.id})" 
                                   style="width: 20px; height: 20px; accent-color: #000; border-radius: 4px; cursor: pointer;">

                            <!-- Thumbnail -->
                            <div style="width: 68px; height: 68px; border: 1.5px solid #CBD5E1; border-radius: 16px; background: #FFF; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 4px; flex-shrink: 0;">
                                ${imgTag}
                            </div>

                            <!-- Info -->
                            <div style="min-width: 0;">
                                <div style="font-weight: 800; font-size: 0.95rem; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    ${item.name}
                                </div>
                                <div style="font-size: 0.78rem; color: #C2410C; font-weight: 600; margin-top: 0.15rem;">
                                    ${item.variant || item.size || 'Standard Edition'}
                                </div>
                            </div>
                        </div>

                        <!-- Stepper & Price -->
                        <div style="display: flex; align-items: center; gap: 2rem;">
                            <!-- Stepper -->
                            <div style="display: inline-flex; align-items: center; border: 1.5px solid #CBD5E1; border-radius: 50px; padding: 2px 10px; gap: 12px; background: #FFF;">
                                <button type="button" onclick="updateCartQty(${item.id}, -1); renderCartPage();" style="border: none; background: transparent; font-size: 1rem; font-weight: 700; color: #000; cursor: pointer;">-</button>
                                <span style="font-weight: 700; font-size: 0.85rem; color: #000; min-width: 12px; text-align: center;">${item.qty}</span>
                                <button type="button" onclick="updateCartQty(${item.id}, 1); renderCartPage();" style="border: none; background: transparent; font-size: 1rem; font-weight: 700; color: #000; cursor: pointer;">+</button>
                            </div>

                            <!-- Price -->
                            <div style="font-weight: 800; font-size: 1rem; color: #000; min-width: 110px; text-align: right;">
                                ${formatRupiah(item.price * item.qty)}
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        container.innerHTML = html;
    }

    function toggleCartPageItemSelection(id) {
        let cart = getCart();
        let item = cart.find(i => i.id === id);
        if (item) {
            item.selected = !item.selected;
            saveCart(cart);
            renderCartPage();
        }
    }

    function applyPromoCode() {
        const codeInput = document.getElementById('cart-promo-input');
        const code = codeInput ? codeInput.value.trim() : '';
        const msgEl = document.getElementById('promo-message');

        if (!code) {
            msgEl.style.display = 'block';
            msgEl.style.color = '#EF4444';
            msgEl.innerText = 'Masukkan kode promo terlebih dahulu.';
            return;
        }

        const cart = getCart();
        const selected = cart.filter(i => i.selected);
        const subtotal = selected.reduce((acc, i) => acc + (i.price * i.qty), 0);
        const sellerIds = selected.map(i => i.seller_id).filter(Boolean);

        fetch("{{ route('api.vouchers.check') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: code, subtotal: subtotal, seller_ids: sellerIds })
        })
        .then(r => r.json())
        .then(res => {
            const data = res.data || res;
            if (data.success) {
                appliedDiscount = data.discount_amount || 0;
                appliedVoucherCode = data.code;
                msgEl.style.display = 'block';
                msgEl.style.color = '#10B981';
                msgEl.innerText = data.message || 'Kode promo berhasil digunakan!';
                renderCartPage();
            } else {
                appliedDiscount = 0;
                appliedVoucherCode = '';
                msgEl.style.display = 'block';
                msgEl.style.color = '#EF4444';
                msgEl.innerText = data.message || 'Kode promo tidak valid.';
                renderCartPage();
            }
        }).catch(() => {
            msgEl.style.display = 'block';
            msgEl.style.color = '#EF4444';
            msgEl.innerText = 'Terjadi kesalahan saat memverifikasi kode promo.';
        });
    }

    function proceedCheckoutFromCartPage() {
        const cart = getCart();
        const selectedItems = cart.filter(i => i.selected);

        if (selectedItems.length === 0) {
            alert('Silakan centang minimal 1 barang di keranjang untuk checkout.');
            return;
        }

        const itemsPayload = selectedItems.map(i => ({ id: i.id, qty: i.qty }));
        let url = `{{ route('orders.checkout') }}?items=` + encodeURIComponent(JSON.stringify(itemsPayload));
        if (appliedVoucherCode) {
            url += `&voucher_code=` + encodeURIComponent(appliedVoucherCode);
        }
        window.location.href = url;
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCartPage();
    });
</script>
@endpush
@endsection
