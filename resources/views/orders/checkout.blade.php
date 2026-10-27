@extends('layouts.app')

@section('content')
<div style="max-width: 680px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- Top Header: Bayar -->
    <div style="position: relative; text-align: center; margin-bottom: 1.5rem;">
        <a href="{{ route('products.index') }}" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); color: #0F172A; font-size: 1.1rem; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px; background: #F1F5F9; border-radius: 50%;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 800; color: #0F172A; margin: 0;">Bayar</h1>
    </div>

    @php
        $itemsJson = json_encode(array_map(fn($p) => ['id' => $p->id, 'qty' => $p->checkout_qty ?? 1], $products));
        $userName = auth()->check() ? auth()->user()->name : 'Shaw Nouzen';
        $userTag = strtoupper(str_replace(['@', '.'], ['+', ''], $playerTag));
        $sellerIds = array_values(array_filter(array_unique(array_map(fn($p) => $p->user_id, $products))));
    @endphp

    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
        @csrf
        <input type="hidden" name="items" value="{{ $itemsJson }}">
        <input type="hidden" name="player_tag" value="{{ $playerTag }}">
        <input type="hidden" name="voucher_code" id="hiddenVoucherCode" value="">

        <!-- Top Container Box -->
        <div style="background: #F1F5F9; border-radius: 20px; padding: 1.25rem; margin-bottom: 1.5rem;">
            
            <!-- User Profile Badge -->
            <div style="background: #FFFFFF; border-radius: 14px; padding: 0.85rem 1.25rem; display: flex; align-items: center; gap: 1rem; margin-bottom: 0.85rem; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #22C55E, #16A34A); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 1.2rem; font-weight: 800;">
                    <i class="fa-solid fa-user-ninja"></i>
                </div>
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 800; color: #0F172A; margin: 0;">{{ $userName }}</h3>
                    <p style="font-size: 0.75rem; color: #64748B; margin: 0.1rem 0 0 0; font-weight: 700; text-transform: uppercase;">{{ $userTag }}</p>
                </div>
            </div>

            <!-- Voucher / Promo Code Input Box -->
            <div id="voucherCodeBox" style="background: #FFFFFF; border: 2px solid #22C55E; border-radius: 14px; padding: 0.85rem 1.25rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s;" onclick="openVoucherInput()">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 28px; height: 28px; background: #22C55E; border-radius: 8px; color: #FFF; font-weight: 900; font-size: 0.85rem; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <span id="voucherCodeText" style="font-weight: 800; font-size: 0.9rem; color: #0F172A;">+ Gunakan Voucher / Kode Promo</span>
                </div>
                <i class="fa-solid fa-arrow-right" style="color: #0F172A; font-size: 0.9rem;"></i>
            </div>

            <div id="voucherInputForm" style="display: none; background: #FFFFFF; border: 2px solid #22C55E; border-radius: 14px; padding: 1rem 1.25rem; margin-top: 0.5rem;">
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" id="voucherCodeInput" placeholder="Masukkan Kode Promo (Aplikator / Toko)..." style="flex: 1; border: 1.5px solid #CBD5E1; border-radius: 8px; padding: 0.6rem 0.85rem; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; outline: none;">
                    <button type="button" id="btnApplyVoucher" onclick="applyVoucherCode()" style="background: #0F172A; color: #FFF; border: none; border-radius: 8px; padding: 0.6rem 1.25rem; font-size: 0.85rem; font-weight: 800; cursor: pointer;">
                        Terapkan
                    </button>
                </div>
                <div id="voucherMsg" style="display: none; font-size: 0.78rem; font-weight: 700; margin-top: 0.45rem;"></div>
            </div>

        </div>

        <!-- Item Divider Pill -->
        <div style="text-align: center; margin-bottom: 1rem; position: relative;">
            <div style="height: 1px; background: #E2E8F0; position: absolute; top: 50%; left: 0; right: 0; z-index: 1;"></div>
            <span style="position: relative; z-index: 2; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 0.25rem 1rem; border-radius: 20px; font-size: 0.75rem; font-weight: 800; color: #64748B;">
                {{ count($products) }} Item
            </span>
        </div>

        <!-- Items Summary List -->
        <div style="font-size: 0.8rem; color: #64748B; margin-bottom: 1.25rem; text-align: center;">
            @foreach($products as $index => $p)
                <span>{{ $p->checkout_qty }}x {{ $p->name }}</span>@if($index < count($products) - 1), @endif
            @endforeach
        </div>

        <!-- Select Payment Method Header -->
        <div style="background: #E2E8F0; padding: 0.75rem 1.25rem; border-radius: 10px 10px 0 0; font-weight: 800; font-size: 0.85rem; color: #334155; margin-bottom: 0.75rem;">
            Select Payment Method
        </div>

        <!-- 2-Column Payment Options Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 2rem;">

            <!-- 1. QRIS -->
            <div class="payment-card selected" onclick="selectPayment(this, 'qris')" style="background: #FFFFFF; border: 2px solid #0F172A; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="qris" checked style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span style="font-weight: 900; font-size: 1.1rem; color: #0F172A; letter-spacing: -0.5px;">QRIS</span>
                    <span style="background: #EEF2FF; color: #4F46E5; font-size: 0.65rem; font-weight: 800; padding: 0.1rem 0.4rem; border-radius: 4px;">INSTANT</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">QRIS</div>
            </div>

            <!-- 2. DANA -->
            <div class="payment-card" onclick="selectPayment(this, 'dana')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="dana" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <div style="width: 18px; height: 18px; background: #108EE9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.65rem; font-weight: 900;">D</div>
                    <span style="font-weight: 900; font-size: 1rem; color: #108EE9;">DANA</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">DANA</div>
            </div>

            <!-- 3. GO-PAY -->
            <div class="payment-card" onclick="selectPayment(this, 'gopay')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="gopay" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <div style="width: 18px; height: 18px; background: #00AED6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FFF; font-size: 0.65rem; font-weight: 900;">G</div>
                    <span style="font-weight: 900; font-size: 1rem; color: #00AED6;">gopay</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">GO-PAY</div>
            </div>

            <!-- 4. Bank Transfers -->
            <div class="payment-card" onclick="selectPayment(this, 'bank_transfer')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="bank_transfer" style="display: none;">
                <div style="display: flex; gap: 0.25rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                    <span style="background: #005CAA; color: #FFF; font-size: 0.55rem; font-weight: 900; padding: 0.15rem 0.3rem; border-radius: 3px;">BCA</span>
                    <span style="background: #003366; color: #F59E0B; font-size: 0.55rem; font-weight: 900; padding: 0.15rem 0.3rem; border-radius: 3px;">MANDIRI</span>
                    <span style="background: #00529C; color: #FFF; font-size: 0.55rem; font-weight: 900; padding: 0.15rem 0.3rem; border-radius: 3px;">BRI</span>
                    <span style="background: #F15A24; color: #FFF; font-size: 0.55rem; font-weight: 900; padding: 0.15rem 0.3rem; border-radius: 3px;">BNI</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Bank transfers (Indonesia)</div>
            </div>

            <!-- 5. ShopeePay -->
            <div class="payment-card" onclick="selectPayment(this, 'shopeepay')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="shopeepay" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <i class="fa-solid fa-bag-shopping" style="color: #EE4D2D;"></i>
                    <span style="font-weight: 900; font-size: 0.95rem; color: #EE4D2D;">ShopeePay</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Shopee Pay(Indonesia)</div>
            </div>

            <!-- 6. OVO -->
            <div class="payment-card" onclick="selectPayment(this, 'ovo')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="ovo" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <span style="font-weight: 900; font-size: 1.1rem; color: #4C2A86; letter-spacing: 1px;">OVO</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">OVO</div>
            </div>

            <!-- 7. VISA / Mastercard -->
            <div class="payment-card" onclick="selectPayment(this, 'credit_card')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="credit_card" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <span style="font-weight: 900; font-size: 0.95rem; color: #1A1F71; font-style: italic;">VISA</span>
                    <span style="font-weight: 900; font-size: 0.75rem; color: #EB001B;">Mastercard</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Card Payments Indonesia</div>
            </div>

            <!-- 8. Doku Wallet -->
            <div class="payment-card" onclick="selectPayment(this, 'doku')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="doku" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <span style="background: #ED1C24; color: #FFF; font-weight: 900; font-size: 0.7rem; padding: 0.15rem 0.35rem; border-radius: 4px;">DOKU</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Doku Wallet</div>
            </div>

            <!-- 9. Indomaret -->
            <div class="payment-card" onclick="selectPayment(this, 'indomaret')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="indomaret" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <span style="background: #005596; color: #FFCC00; font-weight: 900; font-size: 0.7rem; padding: 0.15rem 0.4rem; border-radius: 4px;">Indomaret</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Indomaret</div>
            </div>

            <!-- 10. PayPal -->
            <div class="payment-card" onclick="selectPayment(this, 'paypal')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="paypal" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <i class="fa-brands fa-paypal fa-lg" style="color: #003087;"></i>
                    <span style="font-weight: 900; font-size: 1rem; color: #003087; font-style: italic;">PayPal</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">PayPal Indonesia</div>
            </div>

            <!-- 11. LinkAja -->
            <div class="payment-card" onclick="selectPayment(this, 'linkaja')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="linkaja" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <span style="background: #ED1C24; color: #FFF; font-weight: 900; font-size: 0.75rem; padding: 0.15rem 0.4rem; border-radius: 4px;">LinkAja!</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">LinkAja</div>
            </div>

            <!-- 12. Alfamart -->
            <div class="payment-card" onclick="selectPayment(this, 'alfamart')" style="background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 14px; padding: 1.25rem; cursor: pointer; transition: all 0.2s; position: relative;">
                <input type="radio" name="payment_method" value="alfamart" style="display: none;">
                <div style="display: flex; align-items: center; gap: 0.35rem; margin-bottom: 0.5rem;">
                    <span style="background: #D71921; color: #FFF; font-weight: 900; font-size: 0.75rem; padding: 0.15rem 0.4rem; border-radius: 4px;">Alfamart</span>
                </div>
                <div style="font-size: 0.75rem; font-weight: 800; color: #0F172A;">Alfamart</div>
            </div>

        </div>

        <!-- Total & Submit Button -->
        <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 18px; padding: 1.5rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748B;">Subtotal Produk:</span>
                <span style="font-size: 0.95rem; font-weight: 800; color: #0F172A;" id="subtotalDisplay">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <div id="voucherDiscountRow" style="display: none; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; color: #16A34A;">
                <span style="font-size: 0.85rem; font-weight: 800; display: flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-tag"></i> Diskon Voucher Promo:
                </span>
                <span style="font-size: 0.95rem; font-weight: 900;" id="discountDisplay">- Rp 0</span>
            </div>

            <div style="height: 1px; background: #E2E8F0; margin: 0.75rem 0;"></div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <span style="font-size: 0.95rem; font-weight: 800; color: #0F172A; display: block;">Total Pembayaran:</span>
                    <span style="font-size: 0.72rem; color: #64748B;">Pesanan akan masuk status Processing (Menunggu Pembayaran)</span>
                </div>
                <span style="font-size: 1.45rem; font-weight: 900; color: #7C3AED;" id="finalTotalDisplay">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>

            <button type="submit" style="width: 100%; padding: 1.1rem; font-size: 1.05rem; font-weight: 900; color: #FFFFFF; background: linear-gradient(135deg, #0F172A, #1E293B); border: none; border-radius: 14px; cursor: pointer; box-shadow: 0 4px 16px rgba(15,23,42,0.25); transition: all 0.2s;" onmouseover="this.style.opacity='0.95'; this.style.transform='translateY(-1px)';" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
                <i class="fa-solid fa-cart-arrow-down" style="color: #22C55E; margin-right: 0.5rem;"></i> Buat Pesanan (Processing)
            </button>
        </div>

    </form>

</div>

<script>
    const subtotal = {{ $totalPrice }};
    const sellerIds = @json($sellerIds);

    function selectPayment(element, value) {
        document.querySelectorAll('.payment-card').forEach(card => {
            card.style.border = '1px solid #E2E8F0';
            card.classList.remove('selected');
        });

        element.style.border = '2px solid #0F172A';
        element.classList.add('selected');
        
        const radio = element.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }
    }

    function openVoucherInput() {
        document.getElementById('voucherCodeBox').style.display = 'none';
        document.getElementById('voucherInputForm').style.display = 'block';
        document.getElementById('voucherCodeInput').focus();
    }

    function resetVoucher() {
        document.getElementById('hiddenVoucherCode').value = '';
        document.getElementById('voucherDiscountRow').style.display = 'none';
        document.getElementById('finalTotalDisplay').innerText = 'Rp ' + Number(subtotal).toLocaleString('id-ID');
        document.getElementById('voucherCodeText').innerText = '+ Gunakan Voucher / Kode Promo';
        document.getElementById('voucherCodeText').style.color = '#0F172A';
        document.getElementById('voucherInputForm').style.display = 'none';
        document.getElementById('voucherCodeBox').style.display = 'flex';
        document.getElementById('voucherMsg').style.display = 'none';
    }

    function applyVoucherCode() {
        const code = document.getElementById('voucherCodeInput').value.trim();
        const msgEl = document.getElementById('voucherMsg');
        const btn = document.getElementById('btnApplyVoucher');

        if (!code) {
            msgEl.innerText = 'Masukkan kode promo terlebih dahulu.';
            msgEl.style.color = '#DC2626';
            msgEl.style.display = 'block';
            return;
        }

        btn.innerText = 'Cek...';
        btn.disabled = true;

        fetch('{{ route("api.vouchers.check") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                subtotal: subtotal,
                seller_ids: sellerIds
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerText = 'Terapkan';
            btn.disabled = false;

            if (data.success) {
                document.getElementById('hiddenVoucherCode').value = data.code;
                document.getElementById('voucherDiscountRow').style.display = 'flex';
                document.getElementById('discountDisplay').innerText = '- Rp ' + Number(data.discount_amount).toLocaleString('id-ID');
                document.getElementById('finalTotalDisplay').innerText = 'Rp ' + Number(data.final_total).toLocaleString('id-ID');
                
                document.getElementById('voucherCodeText').innerHTML = `<span style="color: #16A34A;"><i class="fa-solid fa-circle-check"></i> Voucher <strong>${data.code}</strong> Aktif (Hemat Rp ${Number(data.discount_amount).toLocaleString('id-ID')})</span> <span onclick="event.stopPropagation(); resetVoucher();" style="color: #DC2626; font-size: 0.75rem; text-decoration: underline; margin-left: 0.5rem; cursor: pointer;">Hapus</span>`;
                
                document.getElementById('voucherInputForm').style.display = 'none';
                document.getElementById('voucherCodeBox').style.display = 'flex';
            } else {
                msgEl.innerText = data.message;
                msgEl.style.color = '#DC2626';
                msgEl.style.display = 'block';
            }
        })
        .catch(err => {
            btn.innerText = 'Terapkan';
            btn.disabled = false;
            msgEl.innerText = 'Gagal memvalidasi voucher. Coba lagi.';
            msgEl.style.color = '#DC2626';
            msgEl.style.display = 'block';
        });
    }
</script>

<style>
    .payment-card:hover {
        border-color: #94A3B8 !important;
    }
    .payment-card.selected {
        border-color: #0F172A !important;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
</style>
@endsection
