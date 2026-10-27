@extends('layouts.admin')

@section('content')
<div id="conductor" style="max-width: 1300px; margin: 0 auto; padding-bottom: 3.5rem;">

    <!-- 1. CONDUCTOR COMMAND HEADER -->
    <div style="background: linear-gradient(135deg, #020617 0%, #0F172A 50%, #1E293B 100%); border-radius: 24px; padding: 2.25rem; color: #FFFFFF; margin-bottom: 2rem; position: relative; overflow: hidden; box-shadow: 0 16px 40px rgba(15,23,42,0.25); border: 1.5px solid rgba(255,255,255,0.08);">
        <div style="position: absolute; top: -50px; right: -50px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(34,197,94,0.15) 0%, transparent 70%); pointer-events: none;"></div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; position: relative; z-index: 2;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <span style="background: rgba(34, 197, 94, 0.2); color: #4ADE80; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 20px; border: 1px solid rgba(34, 197, 94, 0.35); display: inline-flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #22C55E; box-shadow: 0 0 8px #22C55E; display: inline-block;"></span>
                        SYSTEM ACTIVE • CONDUCTOR ONLINE
                    </span>
                    <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 700;">
                        ID: <code style="color: #F8FAFC; background: rgba(255,255,255,0.1); padding: 0.15rem 0.45rem; border-radius: 4px;">conductor</code>
                    </span>
                </div>

                <h1 style="font-size: 2.2rem; font-weight: 900; letter-spacing: -0.5px; margin: 0 0 0.4rem; color: #FFFFFF;">
                    Conductor Control Center
                </h1>
                <p style="color: #94A3B8; font-size: 0.92rem; margin: 0; max-width: 650px;">
                    Pusat komando dan orkestrasi marketplace <strong>Ruang Lemari</strong>. Pantau kesehatan inventaris, mutasi stok, integritas transaksi, dan kendali operasional platform.
                </p>
            </div>

            <!-- Header Quick Diagnostics Widget -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <div style="background: rgba(255,255,255,0.06); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.75rem 1.25rem; text-align: right;">
                    <span style="font-size: 0.68rem; color: #94A3B8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Waktu Server</span>
                    <div style="font-size: 0.85rem; font-weight: 800; color: #FFFFFF; font-family: monospace;">{{ $diagnostics['server_time'] }}</div>
                </div>
                <div style="background: rgba(255,255,255,0.06); backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.75rem 1.25rem; text-align: right;">
                    <span style="font-size: 0.68rem; color: #94A3B8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Memory Usage</span>
                    <div style="font-size: 0.85rem; font-weight: 800; color: #38BDF8; font-family: monospace;">{{ $diagnostics['memory_usage'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. QUICK ACTION TOOLBAR (COMMANDS) -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.25rem 1.75rem; margin-bottom: 2rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
            <div style="width: 36px; height: 36px; border-radius: 10px; background: #0F172A; color: #22C55E; display: flex; align-items: center; justify-content: center; font-size: 1rem;">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <span style="font-weight: 800; font-size: 0.95rem; color: #0F172A; display: block;">Operasi Sistem Cepat</span>
                <span style="font-size: 0.75rem; color: #64748B;">Eksekusi utilitas pemeliharaan dan audit platform</span>
            </div>
        </div>

        <div style="display: flex; gap: 0.65rem; flex-wrap: wrap;">
            <!-- Clear Cache -->
            <form action="{{ route('admin.conductor.action') }}" method="POST" style="margin: 0;">
                @csrf
                <input type="hidden" name="action" value="clear_cache">
                <button type="submit" style="background: #F1F5F9; color: #0F172A; border: 1.5px solid #CBD5E1; font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem; transition: all 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                    <i class="fa-solid fa-broom" style="color: #6366F1;"></i> Bersihkan Cache
                </button>
            </form>

            <!-- Stock Audit -->
            <form action="{{ route('admin.conductor.action') }}" method="POST" style="margin: 0;">
                @csrf
                <input type="hidden" name="action" value="stock_audit">
                <button type="submit" style="background: #F1F5F9; color: #0F172A; border: 1.5px solid #CBD5E1; font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem; transition: all 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                    <i class="fa-solid fa-clipboard-check" style="color: #22C55E;"></i> Audit Stok Ledger
                </button>
            </form>

            <!-- Storage Link -->
            <form action="{{ route('admin.conductor.action') }}" method="POST" style="margin: 0;">
                @csrf
                <input type="hidden" name="action" value="link_storage">
                <button type="submit" style="background: #F1F5F9; color: #0F172A; border: 1.5px solid #CBD5E1; font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1rem; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 0.45rem; transition: all 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                    <i class="fa-solid fa-link" style="color: #3B82F6;"></i> Periksa Storage Link
                </button>
            </form>

            <!-- Jump to Event Tab -->
            <a href="#eventVoucherSection" style="background: rgba(124, 58, 237, 0.1); color: #7C3AED; border: 1.5px solid rgba(124, 58, 237, 0.25); font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.45rem;">
                <i class="fa-solid fa-ticket" style="color: #7C3AED;"></i> Tab Event & Voucher (Aplikator)
            </a>
        </div>
    </div>

    <!-- 3. TOP KPI METRICS + BUSINESS LOGIC BREAKDOWN -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        
        <!-- Revenue Card -->
        <div class="admin-card" style="border-left: 4px solid #22C55E; background: #FFFFFF;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Gross Revenue (Terbayar)</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(34,197,94,0.12); color: #16A34A; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
            </div>
            <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
                Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.78rem; color: #16A34A; font-weight: 700; margin-top: 0.5rem; display: flex; align-items: center; gap: 0.35rem;">
                <i class="fa-solid fa-check-circle"></i> {{ $completedOrdersCount }} pesanan selesai dari {{ $totalOrdersCount }} total order
            </div>
        </div>

        <!-- Net Revenue Card (After 12% deduction) -->
        <div class="admin-card" style="border-left: 4px solid #10B981; background: linear-gradient(135deg, #F0FDF4 0%, #FFFFFF 100%);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Pendapatan Bersih (88%)</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <div style="font-size: 1.85rem; font-weight: 900; color: #059669; letter-spacing: -0.5px;">
                Rp {{ number_format($netRevenue, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.78rem; color: #64748B; font-weight: 600; margin-top: 0.5rem;">
                Setelah potongan 12% dari Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}
            </div>
        </div>

        <!-- Inventory Valuation Card -->
        <div class="admin-card" style="border-left: 4px solid #3B82F6; background: #FFFFFF;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Valuasi Stok Aktif</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(59,130,246,0.12); color: #2563EB; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
                Rp {{ number_format($inventoryMarketValue, 0, ',', '.') }}
            </div>
            <div style="font-size: 0.78rem; color: #64748B; font-weight: 600; margin-top: 0.5rem;">
                Modal Dasar: <strong>Rp {{ number_format($inventoryCostValue, 0, ',', '.') }}</strong> ({{ $totalUnitsInStock }} unit barang)
            </div>
        </div>

        <!-- Catalog Health Card -->
        <div class="admin-card" style="border-left: 4px solid #EAB308; background: #FFFFFF;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Katalog Produk</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(234,179,8,0.12); color: #CA8A04; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
            <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
                {{ $totalProducts }} Produk
            </div>
            <div style="font-size: 0.78rem; color: #64748B; font-weight: 700; margin-top: 0.5rem; display: flex; gap: 0.75rem;">
                <span style="color: #16A34A;"><i class="fa-solid fa-circle-check"></i> {{ $inStockProducts }} Tersedia</span>
                <span style="color: #EF4444;"><i class="fa-solid fa-circle-xmark"></i> {{ $outOfStockProducts }} Habis</span>
            </div>
        </div>

        <!-- Ecosystem Users Card -->
        <div class="admin-card" style="border-left: 4px solid #8B5CF6; background: #FFFFFF;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                <span style="font-size: 0.8rem; color: #64748B; font-weight: 800; text-transform: uppercase;">Total Pengguna</span>
                <div style="width: 38px; height: 38px; border-radius: 10px; background: rgba(139,92,246,0.12); color: #7C3AED; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div style="font-size: 1.85rem; font-weight: 900; color: #0F172A; letter-spacing: -0.5px;">
                {{ $totalUsers }} Akun
            </div>
            <div style="font-size: 0.78rem; color: #64748B; font-weight: 600; margin-top: 0.5rem;">
                <span title="Dihitung 1 akun unik per pengguna walau memiliki etalase toko"><i class="fa-solid fa-circle-info" style="color: #7C3AED;"></i> 1 Akun unik/orang ({{ $totalSellers }} punya etalase toko)</span>
            </div>
        </div>

    </div>

    <!-- BUSINESS LOGIC BREAKDOWN: 100% - 12% -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
            <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-calculator" style="color: #6366F1;"></i> Struktur Pendapatan — Bisnis Logic
            </h3>
            <span style="font-size: 0.72rem; font-weight: 800; background: #EEF2F6; color: #475569; padding: 0.2rem 0.6rem; border-radius: 6px;">
                100% — 12% Potongan = 88% Bersih
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <!-- Gross (100%) -->
            <div style="background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: 14px; padding: 1rem 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: #0F172A; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">100</div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #475569; text-transform: uppercase;">Pendapatan Kotor (100%)</span>
                </div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #0F172A;">Rp {{ number_format($totalGrossRevenue, 0, ',', '.') }}</div>
            </div>

            <!-- Profit Aplikator (5%) -->
            <div style="background: #FFFBEB; border: 1.5px solid #FDE68A; border-radius: 14px; padding: 1rem 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: #F59E0B; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">5%</div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #92400E; text-transform: uppercase;">Profit Aplikator</span>
                </div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #B45309;">- Rp {{ number_format($applicatorProfit, 0, ',', '.') }}</div>
            </div>

            <!-- Pajak (3%) -->
            <div style="background: #FEF2F2; border: 1.5px solid #FECACA; border-radius: 14px; padding: 1rem 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: #EF4444; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">3%</div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #991B1B; text-transform: uppercase;">Pajak</span>
                </div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #DC2626;">- Rp {{ number_format($taxAmount, 0, ',', '.') }}</div>
            </div>

            <!-- Operasional (4%) -->
            <div style="background: #EFF6FF; border: 1.5px solid #BFDBFE; border-radius: 14px; padding: 1rem 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <div style="width: 28px; height: 28px; border-radius: 7px; background: #3B82F6; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">4%</div>
                    <span style="font-size: 0.75rem; font-weight: 800; color: #1E40AF; text-transform: uppercase;">Operasional</span>
                </div>
                <div style="font-size: 1.35rem; font-weight: 900; color: #2563EB;">- Rp {{ number_format($operationalCost, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Summary Bar -->
        <div style="background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%); border: 1.5px solid #86EFAC; border-radius: 14px; padding: 1.15rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-size: 0.78rem; font-weight: 800; color: #166534; text-transform: uppercase;">💰 Pendapatan Bersih untuk Penjual (88%)</span>
                <div style="font-size: 1.65rem; font-weight: 900; color: #15803D; margin-top: 0.2rem;">Rp {{ number_format($netRevenue, 0, ',', '.') }}</div>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 0.72rem; font-weight: 700; color: #DC2626; display: block;">Total Potongan (12%): Rp {{ number_format($totalDeductions, 0, ',', '.') }}</span>
                <span style="font-size: 0.68rem; color: #64748B; margin-top: 0.15rem; display: block;">5% aplikator + 3% pajak + 4% operasional</span>
            </div>
        </div>
    </div>

    <!-- 4. TWO-COLUMN SPLIT: SERVER DIAGNOSTICS & INVENTORY HEALTH -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Server Diagnostics Card -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-server" style="color: #6366F1;"></i> Diagnostik Lingkungan Server
                </h3>
                <span style="font-size: 0.72rem; font-weight: 800; background: #EEF2F6; color: #475569; padding: 0.2rem 0.6rem; border-radius: 6px;">
                    PHP {{ $diagnostics['php_version'] }}
                </span>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <tbody>
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.65rem 0; color: #64748B; font-weight: 600;">Versi Laravel</td>
                        <td style="padding: 0.65rem 0; font-weight: 800; color: #0F172A; text-align: right;">Laravel v{{ $diagnostics['laravel_version'] }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.65rem 0; color: #64748B; font-weight: 600;">Environment</td>
                        <td style="padding: 0.65rem 0; font-weight: 800; color: #0F172A; text-align: right; text-transform: uppercase;">
                            <span style="background: #DCFCE7; color: #16A34A; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">{{ $diagnostics['app_env'] }}</span>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.65rem 0; color: #64748B; font-weight: 600;">Status Database</td>
                        <td style="padding: 0.65rem 0; font-weight: 800; color: #0F172A; text-align: right;">
                            <span style="color: #16A34A;"><i class="fa-solid fa-circle-check"></i> {{ strtoupper($diagnostics['database_driver']) }}</span> ({{ $diagnostics['database_name'] }})
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #F1F5F9;">
                        <td style="padding: 0.65rem 0; color: #64748B; font-weight: 600;">Public Storage Link</td>
                        <td style="padding: 0.65rem 0; font-weight: 800; text-align: right;">
                            @if($diagnostics['storage_linked'])
                                <span style="color: #16A34A;"><i class="fa-solid fa-check"></i> Terhubung (Active)</span>
                            @else
                                <span style="color: #EF4444;"><i class="fa-solid fa-triangle-exclamation"></i> Belum Terhubung</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.65rem 0; color: #64748B; font-weight: 600;">Chat & Komunikasi</td>
                        <td style="padding: 0.65rem 0; font-weight: 800; color: #0F172A; text-align: right;">
                            {{ $totalConversations }} percakapan / {{ $totalMessages }} pesan terkirim
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Inventory Integrity & Critical Stock Card -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-shield-virus" style="color: #EF4444;"></i> Integritas & Barang Habis
                </h3>
                <span style="font-size: 0.72rem; font-weight: 800; background: {{ $negativeStockCount > 0 ? '#FEE2E2' : '#DCFCE7' }}; color: {{ $negativeStockCount > 0 ? '#DC2626' : '#16A34A' }}; padding: 0.2rem 0.6rem; border-radius: 6px;">
                    {{ $negativeStockCount }} Stok Negatif
                </span>
            </div>

            @if($criticalStockProducts->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($criticalStockProducts as $cp)
                        <div style="display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 0.65rem 0.85rem;">
                            <div style="display: flex; align-items: center; gap: 0.6rem; overflow: hidden;">
                                <div style="width: 32px; height: 32px; border-radius: 8px; background: #0F172A; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; flex-shrink: 0;">
                                    <i class="fa-solid fa-shirt"></i>
                                </div>
                                <div style="overflow: hidden;">
                                    <a href="{{ route('products.show', $cp->slug) }}" target="_blank" style="font-weight: 800; font-size: 0.82rem; color: #0F172A; text-decoration: none; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $cp->name }}
                                    </a>
                                    <span style="font-size: 0.72rem; color: #64748B;">
                                        Toko: {{ $cp->user ? $cp->user->store_name : 'Ruang Lemari' }}
                                    </span>
                                </div>
                            </div>
                            <span style="font-size: 0.72rem; font-weight: 800; background: #FEE2E2; color: #EF4444; padding: 0.2rem 0.5rem; border-radius: 6px; white-space: nowrap;">
                                Stok: {{ $cp->stock }} (Habis)
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2.5rem 1rem; color: #16A34A;">
                    <i class="fa-solid fa-circle-check fa-3x" style="margin-bottom: 0.75rem; display: block;"></i>
                    <div style="font-weight: 800; font-size: 1rem; color: #0F172A;">Seluruh Stok Aman</div>
                    <div style="font-size: 0.82rem; color: #64748B; margin-top: 0.2rem;">Tidak ada barang yang kehabisan stok saat ini.</div>
                </div>
            @endif
        </div>

    </div>

    <!-- 5. REAL-TIME ACTIVITY & MUTASI LEDGER FEED -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- Live Stock Movement Ledger Feed -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-list-check" style="color: #10B981;"></i> Mutasi Stok Terbaru (Ledger)
                </h3>
                <span style="font-size: 0.75rem; color: #64748B; font-weight: 700;">8 Terakhir</span>
            </div>

            @if($recentStockMovements->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($recentStockMovements as $sm)
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 0.68rem; font-weight: 900; text-transform: uppercase; padding: 0.15rem 0.45rem; border-radius: 4px; {{ $sm->type === 'in' ? 'background: #DCFCE7; color: #16A34A;' : 'background: #FEE2E2; color: #DC2626;' }}">
                                        {{ $sm->type === 'in' ? '+ MASUK' : '- KELUAR' }}
                                    </span>
                                    <span style="font-weight: 800; font-size: 0.85rem; color: #0F172A;">
                                        {{ $sm->product ? $sm->product->name : 'Item Terhapus' }}
                                    </span>
                                </div>
                                <div style="font-size: 0.72rem; color: #64748B; margin-top: 0.2rem;">
                                    {{ $sm->notes ?? 'Tidak ada catatan' }} • Sisa: <strong>{{ $sm->stock_after }}</strong>
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-weight: 900; font-size: 0.95rem; {{ $sm->type === 'in' ? 'color: #16A34A;' : 'color: #DC2626;' }}">
                                    {{ $sm->type === 'in' ? '+' : '-' }}{{ $sm->quantity }}
                                </div>
                                <span style="font-size: 0.68rem; color: #94A3B8;">{{ $sm->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem 1rem; color: #94A3B8; font-size: 0.85rem;">
                    Belum ada riwayat mutasi stok tercatat.
                </div>
            @endif
        </div>

        <!-- Recent Orders Feed -->
        <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem;">
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-cart-shopping" style="color: #3B82F6;"></i> Transaksi & Pesanan Masuk
                </h3>
                <a href="{{ route('admin.orders.index') }}" style="font-size: 0.75rem; color: #3B82F6; font-weight: 800; text-decoration: none;">
                    Kelola Semua <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if($recentOrders->count() > 0)
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    @foreach($recentOrders as $ro)
                        <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                            <div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-family: monospace; font-weight: 800; font-size: 0.82rem; color: #0F172A;">
                                        {{ $ro->order_number }}
                                    </span>
                                    <span style="font-size: 0.68rem; font-weight: 800; text-transform: uppercase; padding: 0.15rem 0.45rem; border-radius: 4px; {{ $ro->payment_status === 'paid' ? 'background: #DCFCE7; color: #16A34A;' : 'background: #FEF3C7; color: #D97706;' }}">
                                        {{ $ro->payment_status }}
                                    </span>
                                </div>
                                <div style="font-size: 0.72rem; color: #64748B; margin-top: 0.2rem;">
                                    Oleh: <strong>{{ $ro->user ? $ro->user->name : 'Tamu' }}</strong> • {{ $ro->items->count() }} item
                                </div>
                            </div>

                            <div style="text-align: right;">
                                <div style="font-weight: 900; font-size: 0.95rem; color: #0F172A;">
                                    Rp {{ number_format($ro->total_price, 0, ',', '.') }}
                                </div>
                                <span style="font-size: 0.68rem; color: #94A3B8;">{{ $ro->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem 1rem; color: #94A3B8; font-size: 0.85rem;">
                    Belum ada transaksi pesanan yang masuk.
                </div>
            @endif
        </div>

    </div>

    <!-- TAB EVENT & VOUCHER PROMO (APLIKATOR & SELLER) -->
    <div id="eventVoucherSection" style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02); margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-ticket" style="color: #EC4899;"></i> Tab Event — Kelola Voucher & Promo (Aplikator)
                </h3>
                <span style="font-size: 0.75rem; color: #64748B;">Atur promo dan diskon tingkat platform (Aplikator) maupun pantau voucher etalase toko seller</span>
            </div>
            <button type="button" onclick="document.getElementById('addVoucherCard').style.display = document.getElementById('addVoucherCard').style.display === 'none' ? 'block' : 'none';" style="background: #0F172A; color: #FFFFFF; border: none; border-radius: 10px; font-weight: 800; font-size: 0.82rem; padding: 0.55rem 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-plus" style="color: #22C55E;"></i> Terbitkan Voucher Aplikator
            </button>
        </div>

        <!-- Form Tambah Voucher Aplikator -->
        <div id="addVoucherCard" style="display: none; background: #F8FAFC; border: 1.5px solid #CBD5E1; border-radius: 14px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <div style="font-size: 0.92rem; font-weight: 800; color: #0F172A; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                <i class="fa-solid fa-bullhorn" style="color: #3B82F6;"></i> Form Penerbitan Voucher Promo Aplikator (Platform-Wide)
            </div>
            <form action="{{ route('admin.conductor.action') }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="create_voucher">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Kode Voucher (Kapital)</label>
                        <input type="text" name="code" placeholder="Contoh: HEMAT10, GAJIAN50" required style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800; text-transform: uppercase;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Nama Event / Promo</label>
                        <input type="text" name="name" placeholder="Contoh: Flash Sale Ruang Lemari" required style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 600;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Tipe Diskon</label>
                        <select name="discount_type" style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                            <option value="percent">Persentase (%)</option>
                            <option value="fixed">Nominal Tetap (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Nilai Diskon (Angka)</label>
                        <input type="number" name="discount_value" placeholder="Contoh: 15 (untuk 15%) atau 20000" min="1" step="any" required style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Minimal Belanja (Rp)</label>
                        <input type="number" name="min_spend" value="0" min="0" style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Maksimal Diskon (Rp, Jika %)</label>
                        <input type="number" name="max_discount" placeholder="Kosongkan jika bebas" min="0" style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: #334155; margin-bottom: 0.3rem;">Kuota Penggunaan</label>
                        <input type="number" name="quota" value="100" min="1" required style="width: 100%; padding: 0.6rem; border: 1.5px solid #CBD5E1; border-radius: 8px; font-weight: 800;">
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                    <button type="button" onclick="document.getElementById('addVoucherCard').style.display='none';" style="background: #E2E8F0; color: #475569; font-weight: 800; padding: 0.6rem 1.25rem; border-radius: 8px; border: none; cursor: pointer;">
                        Batal
                    </button>
                    <button type="submit" style="background: #16A34A; color: #FFFFFF; font-weight: 800; padding: 0.6rem 1.5rem; border-radius: 8px; border: none; cursor: pointer;">
                        <i class="fa-solid fa-check"></i> Rilis Voucher Promo
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Daftar Voucher -->
        <div style="overflow-x: auto;">
            <table class="sc-table" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #F8FAFC;">
                        <th>Kode Voucher</th>
                        <th>Nama Event / Promo</th>
                        <th>Kategori Voucher</th>
                        <th>Nilai Diskon</th>
                        <th>Min. Belanja</th>
                        <th>Kuota / Terpakai</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi Conductor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $v)
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight: 900; color: #0F172A; background: #EEF2F6; padding: 0.25rem 0.55rem; border-radius: 6px; letter-spacing: 0.5px;">
                                    {{ $v->code }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #0F172A;">{{ $v->name }}</span>
                            </td>
                            <td>
                                @if($v->type === 'platform')
                                    <span style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; font-weight: 800; font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 20px;">
                                        <i class="fa-solid fa-crown"></i> Aplikator (Platform)
                                    </span>
                                @else
                                    <span style="background: #DCFCE7; color: #16A34A; font-weight: 800; font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 20px;">
                                        <i class="fa-solid fa-store"></i> Seller: {{ $v->user ? $v->user->store_name : 'Toko' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 800; color: #0F172A;">
                                    {{ $v->discount_type === 'percent' ? $v->discount_value.'%' : 'Rp '.number_format($v->discount_value, 0, ',', '.') }}
                                </span>
                                @if($v->max_discount)
                                    <span style="font-size: 0.7rem; color: #64748B; display: block;">Maks. Rp {{ number_format($v->max_discount, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>
                                <span style="color: #64748B; font-size: 0.8rem;">
                                    {{ $v->min_spend > 0 ? 'Rp '.number_format($v->min_spend, 0, ',', '.') : 'Tanpa Min.' }}
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 800; color: {{ $v->used_count >= $v->quota ? '#DC2626' : '#0F172A' }};">
                                    {{ $v->used_count }} / {{ $v->quota }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $v->is_active ? 'status-valid' : 'status-invalid' }}">
                                    {{ $v->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; align-items: center; gap: 0.35rem;">
                                    <!-- Toggle Active -->
                                    <form action="{{ route('admin.conductor.action') }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <input type="hidden" name="action" value="toggle_voucher">
                                        <input type="hidden" name="voucher_id" value="{{ $v->id }}">
                                        <button type="submit" style="background: none; border: 1px solid #CBD5E1; border-radius: 6px; padding: 0.25rem 0.5rem; font-size: 0.72rem; font-weight: 700; cursor: pointer; color: {{ $v->is_active ? '#D97706' : '#16A34A' }};">
                                            {{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Delete Voucher -->
                                    <form action="{{ route('admin.conductor.action') }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus voucher {{ $v->code }}?');">
                                        @csrf
                                        <input type="hidden" name="action" value="delete_voucher">
                                        <input type="hidden" name="voucher_id" value="{{ $v->id }}">
                                        <button type="submit" style="background: none; border: 1px solid #FECACA; color: #DC2626; border-radius: 6px; padding: 0.25rem 0.45rem; font-size: 0.72rem; cursor: pointer;">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem 1rem; color: #94A3B8;">
                                Belum ada kode promo / voucher yang diterbitkan. Klik 'Terbitkan Voucher Aplikator' untuk membuat event promo baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 6. USER & ROLES MANAGEMENT ROSTER -->
    <div style="background: #FFFFFF; border: 1.5px solid #E2E8F0; border-radius: 20px; padding: 1.75rem; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 1px solid #F1F5F9; padding-bottom: 0.85rem; flex-wrap: wrap; gap: 0.5rem;">
            <div>
                <h3 style="font-size: 1.1rem; font-weight: 900; color: #0F172A; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-users-gear" style="color: #7C3AED;"></i> Roster Pengguna & Manajemen Otoritas Admin
                </h3>
                <span style="font-size: 0.75rem; color: #64748B;">Daftar akun ekosistem dan toggle hak akses administrator</span>
            </div>
            <span style="font-size: 0.75rem; font-weight: 800; color: #475569; background: #EEF2F6; padding: 0.25rem 0.65rem; border-radius: 6px;">
                Total: {{ $totalUsers }} Pengguna Terdaftar
            </span>
        </div>

        <div style="overflow-x: auto;">
            <table class="sc-table" style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #F8FAFC;">
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Profil Toko</th>
                        <th>Aktivitas</th>
                        <th>Status Role</th>
                        <th style="text-align: right;">Aksi Conductor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $u)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.65rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #0F172A; color: #FFF; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.8rem;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #0F172A;">{{ $u->name }}</div>
                                        <div style="font-size: 0.72rem; color: #94A3B8;">Bergabung: {{ $u->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-family: monospace; color: #475569;">{{ $u->email }}</td>
                            <td>
                                @if($u->store_name)
                                    <span style="color: #16A34A; font-weight: 800; background: #DCFCE7; padding: 0.15rem 0.5rem; border-radius: 6px; font-size: 0.75rem;">
                                        <i class="fa-solid fa-store"></i> {{ $u->store_name }}
                                    </span>
                                @else
                                    <span style="color: #94A3B8; font-size: 0.75rem;">Belum Buka Toko</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-size: 0.78rem; color: #475569;">
                                    <strong>{{ $u->products_count }}</strong> produk • <strong>{{ $u->orders_count }}</strong> order
                                </span>
                            </td>
                            <td>
                                @if($u->is_admin)
                                    <span style="background: rgba(124, 58, 237, 0.12); color: #7C3AED; font-weight: 800; font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 20px; border: 1px solid rgba(124, 58, 237, 0.25);">
                                        <i class="fa-solid fa-crown"></i> ADMIN
                                    </span>
                                @else
                                    <span style="background: #F1F5F9; color: #64748B; font-weight: 700; font-size: 0.72rem; padding: 0.2rem 0.55rem; border-radius: 20px;">
                                        User
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.conductor.action') }}" method="POST" style="display: inline;" onsubmit="return confirm('Ubah status admin untuk {{ $u->name }}?')">
                                        @csrf
                                        <input type="hidden" name="action" value="toggle_admin">
                                        <input type="hidden" name="user_id" value="{{ $u->id }}">
                                        <button type="submit" style="background: none; border: 1px solid #CBD5E1; border-radius: 8px; padding: 0.3rem 0.65rem; font-size: 0.75rem; font-weight: 700; cursor: pointer; color: {{ $u->is_admin ? '#EF4444' : '#16A34A' }};">
                                            {{ $u->is_admin ? 'Turunkan dari Admin' : 'Jadikan Admin' }}
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 0.72rem; color: #94A3B8; font-style: italic;">(Akun Anda)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
