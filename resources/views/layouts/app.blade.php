<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Lemari — Marketplace Thrifting & Preloved Fashion Terpercaya</title>
    <meta name="description" content="Ruang Lemari — platform marketplace thrifting terpercaya untuk jaket, crewneck, t-shirt vintage, denim, dan sneakers original.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --rp-white: #FFFFFF;
            --rp-bg: #F8FAFC;
            --rp-card: #FFFFFF;
            --rp-border: #E2E8F0;
            --rp-purple: #0F172A;
            --rp-purple-dark: #020617;
            --rp-purple-light: #F1F5F9;
            --rp-purple-mid: #334155;
            --rp-accent: #7C3AED;
            --rp-text: #0F172A;
            --rp-text-muted: #64748B;
            --rp-text-light: #94A3B8;
            --rp-green: #10B981;
            --rp-red: #EF4444;
            --rp-shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --rp-shadow: 0 4px 16px rgba(0,0,0,0.06);
            --rp-shadow-lg: 0 12px 32px rgba(0,0,0,0.10);
            --rp-radius: 16px;
            --rp-radius-sm: 10px;
            --rp-radius-lg: 24px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Plus Jakarta Sans', sans-serif;
            background-color: var(--rp-bg);
            color: var(--rp-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        /* NAVBAR */
        .rp-navbar {
            background: var(--rp-white);
            border-bottom: 1px solid var(--rp-border);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 0;
        }

        .rp-nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }

        /* Logo */
        .rp-logo {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .rp-logo-badge {
            width: 40px;
            height: 40px;
            background: #0F172A;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            font-weight: 900;
            color: #FFF;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.25);
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: -0.5px;
        }

        .rp-logo-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .rp-logo-name {
            font-size: 1.05rem;
            font-weight: 900;
            color: var(--rp-text);
            letter-spacing: -0.3px;
        }

        .rp-logo-tagline {
            font-size: 0.68rem;
            color: var(--rp-text-muted);
            font-weight: 600;
        }

        /* Search */
        .rp-search {
            flex: 1;
            max-width: 480px;
            position: relative;
        }

        .rp-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rp-text-light);
            font-size: 0.85rem;
        }

        .rp-search input {
            width: 100%;
            background: var(--rp-bg);
            border: 1px solid var(--rp-border);
            border-radius: 50px;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            font-size: 0.85rem;
            color: var(--rp-text);
            outline: none;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .rp-search input::placeholder {
            color: var(--rp-text-light);
            text-transform: uppercase;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
        }

        .rp-search input:focus {
            border-color: #0F172A;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
            background: var(--rp-white);
        }

        .rp-search-clear {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rp-text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            transition: all 0.2s;
        }

        .rp-search-clear:hover {
            color: #EF4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Nav Right */
        .rp-nav-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-shrink: 0;
        }

        .rp-nav-icon-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--rp-bg);
            border: 1px solid var(--rp-border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--rp-text);
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .rp-nav-icon-btn:hover {
            background: #0F172A;
            border-color: #0F172A;
            color: #FFFFFF;
        }

        .rp-cart-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background: #EF4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.15rem 0.45rem;
            border-radius: 20px;
            border: 2px solid #FFF;
            min-width: 20px;
            text-align: center;
            line-height: 1;
        }

        .rp-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #0F172A;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            border: 2px solid var(--rp-border);
            text-decoration: none;
            transition: all 0.2s;
        }

        .rp-avatar:hover {
            border-color: #0F172A;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.15);
        }

        /* ============================
           UPGRADED USER DROPDOWN NAVBAR
        ============================ */
        .rp-user-dropdown {
            position: relative;
        }

        .rp-user-trigger-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 30px;
            padding: 0.35rem 0.85rem 0.35rem 0.4rem;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
            color: #0F172A;
            font-family: inherit;
        }

        .rp-user-trigger-btn:hover,
        .rp-user-dropdown.is-open .rp-user-trigger-btn {
            background: #F8FAFC;
            border-color: #0F172A;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .rp-user-trigger-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #0F172A;
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.85rem;
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .rp-user-trigger-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rp-user-trigger-name {
            font-size: 0.85rem;
            font-weight: 800;
            color: #0F172A;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rp-user-trigger-chevron {
            font-size: 0.72rem;
            color: #64748B;
            transition: transform 0.25s ease;
        }

        .rp-user-dropdown.is-open .rp-user-trigger-chevron {
            transform: rotate(180deg);
            color: #0F172A;
        }

        /* Dropdown Menu Container */
        .rp-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #FFFFFF;
            border: 1.5px solid #E2E8F0;
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12), 0 4px 12px rgba(0,0,0,0.04);
            width: 280px;
            padding: 0.75rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
            z-index: 1000;
            pointer-events: none;
        }

        /* Invisible bridge to prevent mouse-leave gaps (anti hilang-hilangan) */
        .rp-dropdown-menu::before {
            content: "";
            position: absolute;
            top: -14px;
            left: 0;
            right: 0;
            height: 14px;
            display: block;
        }

        /* Show on Hover OR when toggled via Click */
        .rp-user-dropdown:hover .rp-dropdown-menu,
        .rp-user-dropdown.is-open .rp-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .rp-dropdown-user-header {
            padding: 0.85rem;
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .rp-dropdown-user-info {
            overflow: hidden;
            flex: 1;
        }

        .rp-dropdown-user-name {
            font-weight: 800;
            font-size: 0.88rem;
            color: #0F172A;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rp-dropdown-user-email {
            font-size: 0.74rem;
            color: #64748B;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rp-dropdown-store-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            font-weight: 800;
            color: #16A34A;
            background: #DCFCE7;
            padding: 0.15rem 0.5rem;
            border-radius: 20px;
            margin-top: 0.35rem;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rp-dropdown-nav-group {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .rp-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            color: #1E293B;
            text-decoration: none;
            font-size: 0.84rem;
            font-weight: 700;
            border-radius: 10px;
            transition: all 0.18s ease;
        }

        .rp-dropdown-item i.item-icon {
            width: 22px;
            font-size: 0.95rem;
            text-align: center;
            flex-shrink: 0;
        }

        .rp-dropdown-item:hover {
            background: #F1F5F9;
            color: #0F172A;
            transform: translateX(3px);
        }

        .rp-dropdown-item.danger {
            color: #DC2626;
        }

        .rp-dropdown-item.danger:hover {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .rp-dropdown-divider {
            height: 1px;
            background: #F1F5F9;
            margin: 0.45rem 0;
        }

        /* Nav Auth Buttons */
        .rp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-weight: 700;
            font-size: 0.875rem;
            padding: 0.55rem 1.25rem;
            border-radius: 50px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .rp-btn-primary {
            background: #0F172A;
            color: #FFF;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.25);
        }

        .rp-btn-primary:hover {
            background: #020617;
            transform: translateY(-1px);
        }

        .rp-btn-ghost {
            background: transparent;
            color: var(--rp-text-muted);
            border: 1px solid var(--rp-border);
        }

        .rp-btn-ghost:hover {
            background: var(--rp-bg);
            color: var(--rp-text);
            border-color: #CBD5E1;
        }

        /* Admin badge */
        .rp-admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #F1F5F9;
            color: #0F172A;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            border: 1px solid #CBD5E1;
            text-decoration: none;
            transition: all 0.2s;
        }

        /* MAIN CONTENT */
        main {
            flex: 1;
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 2rem 2rem;
        }

        /* ALERTS */
        .rp-alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--rp-radius-sm);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            animation: slideDown 0.3s ease-out;
        }

        .rp-alert-success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #059669;
        }

        .rp-alert-error {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #DC2626;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FOOTER */
        footer {
            background: var(--rp-white);
            border-top: 1px solid var(--rp-border);
            padding: 2.5rem 2rem;
            margin-top: 3rem;
        }

        .rp-footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .rp-footer-brand {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .rp-footer-text {
            color: var(--rp-text-muted);
            font-size: 0.85rem;
        }

        .rp-footer-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .rp-footer-links a {
            color: var(--rp-text-muted);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color 0.2s;
        }

        .rp-footer-links a:hover {
            color: #0F172A;
        }

        /* ============================================
           CART SIDE DRAWER MODAL OVERLAY
        ============================================ */
        .rp-cart-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .rp-cart-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .rp-cart-drawer {
            position: fixed;
            top: 0;
            right: 0;
            width: 440px;
            max-width: 100vw;
            height: 100vh;
            background: #0F172A;
            color: #FFFFFF;
            z-index: 1000;
            box-shadow: -10px 0 40px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .rp-cart-drawer.open {
            transform: translateX(0);
        }

        /* Cart Drawer Top Header */
        .rp-drawer-header {
            padding: 1.25rem 1.5rem;
            background: #020617;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rp-drawer-header-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .rp-drawer-close-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #FFF;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .rp-drawer-close-btn:hover {
            background: rgba(255,255,255,0.25);
        }

        /* Promo Bar */
        .rp-promo-bar {
            background: #020617;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.8rem;
            color: #94A3B8;
        }

        .rp-promo-badge {
            background: #22C55E;
            color: #000;
            font-weight: 900;
            font-size: 0.75rem;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
        }

        /* Cart Items List */
        .rp-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.25rem 1.5rem;
            background: #0F172A;
        }

        .rp-select-all-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            font-size: 0.85rem;
            color: #94A3B8;
            font-weight: 600;
        }

        .rp-cart-item {
            background: #1E293B;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .rp-cart-item-check {
            margin-top: 0.25rem;
            accent-color: #22C55E;
            transform: scale(1.2);
            cursor: pointer;
        }

        .rp-cart-item-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #334155, #1E293B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: #FFF;
            flex-shrink: 0;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .rp-cart-item-info {
            flex: 1;
            min-width: 0;
        }

        .rp-cart-item-name {
            font-weight: 800;
            font-size: 0.95rem;
            color: #FFF;
            margin-bottom: 0.2rem;
            line-height: 1.3;
        }

        .rp-cart-item-price {
            font-size: 0.9rem;
            font-weight: 800;
            color: #22C55E;
            margin-bottom: 0.75rem;
        }

        /* Qty controls */
        .rp-qty-stepper {
            display: inline-flex;
            align-items: center;
            background: #020617;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            overflow: hidden;
        }

        .rp-qty-btn {
            background: none;
            border: none;
            color: #FFF;
            width: 28px;
            height: 28px;
            cursor: pointer;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rp-qty-btn:hover {
            background: rgba(255,255,255,0.15);
        }

        .rp-qty-val {
            padding: 0 0.6rem;
            font-size: 0.85rem;
            font-weight: 800;
            color: #FFF;
        }

        .rp-item-remove {
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            font-size: 0.85rem;
            padding: 0.25rem;
            margin-left: auto;
            transition: color 0.2s;
        }

        .rp-item-remove:hover {
            color: #EF4444;
        }

        .rp-cart-bonus-box {
            background: rgba(255,255,255,0.04);
            border-radius: 10px;
            padding: 0.6rem 0.85rem;
            margin-top: 0.75rem;
            font-size: 0.75rem;
            color: #94A3B8;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Cart Footer Bottom Bar */
        .rp-drawer-footer {
            padding: 1.25rem 1.5rem;
            background: #020617;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .rp-drawer-total-label {
            font-size: 0.75rem;
            color: #94A3B8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rp-drawer-total-price {
            font-size: 1.35rem;
            font-weight: 900;
            color: #FFF;
        }

        .rp-drawer-checkout-btn {
            background: #FFFFFF;
            color: #0F172A;
            font-weight: 800;
            font-size: 0.95rem;
            padding: 0.85rem 1.75rem;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
            transition: all 0.2s;
        }

        .rp-drawer-checkout-btn:hover {
            background: #F1F5F9;
            transform: translateY(-1px);
        }

        .rp-cart-empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #94A3B8;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- ThriftStore Navbar -->
    <header class="rp-navbar">
        <div class="rp-nav-inner">
            <!-- Logo -->
            <a href="{{ route('products.index') }}" class="rp-logo">
                <div class="rp-logo-badge">RL</div>
                <div class="rp-logo-info">
                    <span class="rp-logo-name">Ruang Lemari</span>
                    <span class="rp-logo-tagline">Marketplace Thrifting & Preloved Original</span>
                </div>
            </a>

            <!-- Search Bar (Navbar Global Search) -->
            <form action="{{ route('products.index') }}" method="GET" class="rp-search" id="navbar-search-form" style="margin: 0;">
                <i class="fa-solid fa-magnifying-glass rp-search-icon"></i>
                <input type="text" name="q" id="search-input" value="{{ request('q') }}" placeholder="CARI BARANG, NAMA TOKO, ATAU PENGGUNA..." autocomplete="off">
                @if(request('q'))
                    <a href="{{ route('products.index') }}" class="rp-search-clear" title="Reset pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>

            <!-- Right Section -->
            <nav class="rp-nav-right">
                <!-- Shopping Cart Icon Trigger -->
                <button class="rp-nav-icon-btn" onclick="openCartDrawer()" title="Keranjang Belanja">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span id="rp-cart-badge" class="rp-cart-badge">0</span>
                </button>

                @auth
                    <!-- Live Chat Icon Trigger -->
                    <a href="{{ route('chat.index') }}" class="rp-nav-icon-btn" title="Pesan & Obrolan">
                        <i class="fa-regular fa-comment-dots"></i>
                        <span id="rp-chat-badge" class="rp-cart-badge" style="background: #22C55E; display: none;">0</span>
                    </a>

                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.conductor') }}" id="conductor" class="rp-admin-badge" title="Conductor Control Center">
                            <i class="fa-solid fa-sliders" style="color: #22C55E;"></i> Conductor
                        </a>
                    @endif

                    <a href="{{ route('seller.store') }}" class="rp-btn rp-btn-ghost" style="padding: 0.45rem 1rem; border-radius: 12px; font-weight: 800; font-size: 0.8rem; color: #0F172A; border: 1px solid #CBD5E1;" title="Etalase Toko Saya">
                        <i class="fa-solid fa-store" style="color: #22C55E;"></i> Toko Saya
                    </a>

                    <a href="{{ route('orders.history') }}" class="rp-nav-icon-btn" title="Riwayat Pesanan">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </a>

                    <div class="rp-user-dropdown" id="user-nav-dropdown">
                        <button type="button" class="rp-user-trigger-btn" id="user-nav-trigger" aria-haspopup="true" aria-expanded="false" title="Menu Akun & Toko">
                            <div class="rp-user-trigger-avatar">
                                @if(auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span class="rp-user-trigger-name">{{ Str::words(auth()->user()->name, 1, '') }}</span>
                            <i class="fa-solid fa-chevron-down rp-user-trigger-chevron"></i>
                        </button>

                        <div class="rp-dropdown-menu" id="user-nav-menu" role="menu">
                            <!-- User & Store Identity Card -->
                            <div class="rp-dropdown-user-header">
                                <div class="rp-user-trigger-avatar" style="width: 42px; height: 42px; font-size: 1.1rem;">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
                                    @else
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="rp-dropdown-user-info">
                                    <div class="rp-dropdown-user-name">{{ auth()->user()->name }}</div>
                                    <div class="rp-dropdown-user-email">{{ auth()->user()->email }}</div>
                                    @if(auth()->user()->store_name)
                                        <div class="rp-dropdown-store-badge">
                                            <i class="fa-solid fa-store"></i> {{ auth()->user()->store_name }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="rp-dropdown-nav-group">
                                <!-- Toko & Jualan -->
                                <a href="{{ route('seller.store') }}" class="rp-dropdown-item" role="menuitem">
                                    <i class="fa-solid fa-store item-icon" style="color: #22C55E;"></i>
                                    <span>Etalase Toko Saya</span>
                                </a>
                                <a href="{{ route('seller.products.create') }}" class="rp-dropdown-item" role="menuitem">
                                    <i class="fa-solid fa-circle-plus item-icon" style="color: #10B981;"></i>
                                    <span>Upload Barang Baru</span>
                                </a>

                                <div class="rp-dropdown-divider"></div>

                                <!-- Aktivitas Pembeli & Akun -->
                                <a href="{{ route('chat.index') }}" class="rp-dropdown-item" role="menuitem">
                                    <i class="fa-regular fa-comments item-icon" style="color: #3B82F6;"></i>
                                    <span>Pesan & Chat</span>
                                </a>
                                <a href="{{ route('orders.history') }}" class="rp-dropdown-item" role="menuitem">
                                    <i class="fa-solid fa-receipt item-icon" style="color: #F59E0B;"></i>
                                    <span>Riwayat & Struk</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="rp-dropdown-item" role="menuitem">
                                    <i class="fa-solid fa-user-gear item-icon" style="color: #8B5CF6;"></i>
                                    <span>Pengaturan Akun & Toko</span>
                                </a>

                                @if(auth()->user()->is_admin)
                                    <div class="rp-dropdown-divider"></div>
                                    <a href="{{ route('admin.conductor') }}" class="rp-dropdown-item" role="menuitem" style="background: rgba(34,197,94,0.08); color: #15803D;">
                                        <i class="fa-solid fa-sliders item-icon" style="color: #22C55E;"></i>
                                        <span>Conductor Control Center</span>
                                    </a>
                                @endif

                                <div class="rp-dropdown-divider"></div>

                                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="rp-dropdown-item danger" role="menuitem" style="width:100%; background:none; border:none; cursor:pointer; text-align:left;">
                                        <i class="fa-solid fa-right-from-bracket item-icon"></i>
                                        <span>Keluar (Logout)</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="rp-btn rp-btn-ghost">Masuk</a>
                    <a href="{{ route('register') }}" class="rp-btn rp-btn-primary">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="rp-alert rp-alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rp-alert rp-alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="rp-footer-inner">
            <div class="rp-footer-brand">
                <div style="width:28px;height:28px;background:#0F172A;border-radius:7px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:0.8rem;">RL</div>
                <span class="rp-footer-text">© 2026 <strong style="color:var(--rp-text);">Ruang Lemari</strong> — Marketplace Thrifting & Preloved Original</span>
            </div>
            <div class="rp-footer-links">
                <a href="{{ route('products.index') }}"><i class="fa-solid fa-shirt"></i> Katalog Produk</a>
                <a href="{{ route('orders.history') }}"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Order</a>
                <a href="#"><i class="fa-solid fa-circle-question"></i> Bantuan</a>
            </div>
        </div>
    </footer>

    <!-- CART SIDE DRAWER OVERLAY -->
    <div id="rp-cart-backdrop" class="rp-cart-backdrop" onclick="closeCartDrawer()"></div>
    <div id="rp-cart-drawer" class="rp-cart-drawer">
        <!-- Top Header -->
        <div class="rp-drawer-header">
            <div class="rp-drawer-header-title">
                <i class="fa-solid fa-bag-shopping" style="color:#22C55E;"></i>
                <span id="rp-drawer-title-count">0 item</span>
            </div>
            <button class="rp-drawer-close-btn" onclick="closeCartDrawer()">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Promo Bar -->
        <div class="rp-promo-bar">
            <span class="rp-promo-badge">PROMO</span>
            <span>Gunakan Kode Voucher Ruang Lemari</span>
        </div>

        <!-- Cart Body -->
        <div class="rp-drawer-body">
            <div class="rp-select-all-bar">
                <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer;">
                    <input type="checkbox" id="rp-select-all-cb" onchange="toggleSelectAllCart(this.checked)" checked style="accent-color:#22C55E; transform:scale(1.2);">
                    <span>Pilih Semua Barang</span>
                </label>
                <span id="rp-selected-count-label" style="color:#22C55E;">0 dipilih</span>
            </div>

            <!-- Items container -->
            <div id="rp-cart-items-container">
                <!-- Rendered by JS -->
            </div>
        </div>

        <!-- Bottom Footer -->
        <div class="rp-drawer-footer">
            <div>
                <div class="rp-drawer-total-label">Total Pembayaran</div>
                <div id="rp-drawer-total-price" class="rp-drawer-total-price">Rp 0</div>
            </div>
            <button id="rp-drawer-checkout-btn" onclick="submitCartCheckout()" class="rp-drawer-checkout-btn">
                <i class="fa-solid fa-check-circle"></i> Bayar Sekarang
            </button>
        </div>
    </div>

    <!-- CART JAVASCRIPT LOGIC -->
    <script>
        // User Scoped Cart State Management in localStorage
        const CURRENT_USER_CART_KEY = 'rp_cart_' + "{{ auth()->check() ? auth()->id() : 'guest' }}";

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(CURRENT_USER_CART_KEY)) || [];
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(CURRENT_USER_CART_KEY, JSON.stringify(cart));
            renderCartUI();
        }

        function clearCart() {
            localStorage.removeItem(CURRENT_USER_CART_KEY);
            renderCartUI();
        }

        function addToCart(product) {
            let cart = getCart();
            let existing = cart.find(item => item.id === product.id);

            if (existing) {
                existing.qty = (existing.qty || 1) + 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    badge: product.badge || '',
                    category: product.category || 'Thrifting',
                    qty: 1,
                    selected: true
                });
            }

            saveCart(cart);
            openCartDrawer();
        }

        function removeFromCart(productId) {
            let cart = getCart().filter(item => item.id !== productId);
            saveCart(cart);
        }

        function updateCartQty(productId, delta) {
            let cart = getCart();
            let item = cart.find(i => i.id === productId);
            if (item) {
                item.qty = (item.qty || 1) + delta;
                if (item.qty < 1) {
                    cart = cart.filter(i => i.id !== productId);
                }
            }
            saveCart(cart);
        }

        function toggleSelectCartItem(productId) {
            let cart = getCart();
            let item = cart.find(i => i.id === productId);
            if (item) {
                item.selected = !item.selected;
            }
            saveCart(cart);
        }

        function toggleSelectAllCart(checked) {
            let cart = getCart();
            cart.forEach(item => item.selected = checked);
            saveCart(cart);
        }

        function openCartDrawer() {
            document.getElementById('rp-cart-backdrop').classList.add('open');
            document.getElementById('rp-cart-drawer').classList.add('open');
            renderCartUI();
        }

        function closeCartDrawer() {
            document.getElementById('rp-cart-backdrop').classList.remove('open');
            document.getElementById('rp-cart-drawer').classList.remove('open');
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number).replace('IDR', 'Rp');
        }

        function renderCartUI() {
            const cart = getCart();
            const container = document.getElementById('rp-cart-items-container');
            const cartBadge = document.getElementById('rp-cart-badge');
            const headerTitle = document.getElementById('rp-drawer-title-count');
            const selectedCountLabel = document.getElementById('rp-selected-count-label');
            const totalPriceEl = document.getElementById('rp-drawer-total-price');
            const selectAllCb = document.getElementById('rp-select-all-cb');

            const totalItems = cart.reduce((acc, item) => acc + item.qty, 0);
            cartBadge.innerText = totalItems;
            headerTitle.innerText = `${totalItems} barang`;

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="rp-cart-empty-state">
                        <i class="fa-solid fa-bag-shopping fa-3x" style="color:#475569; margin-bottom:1rem; display:block;"></i>
                        <h4 style="font-weight:700; color:#FFF; font-size:1.1rem;">Keranjang Thrifting Kosong</h4>
                        <p style="font-size:0.85rem; margin-top:0.4rem;">Silakan pilih barang thrifting favorit Anda!</p>
                    </div>
                `;
                totalPriceEl.innerText = 'Rp 0';
                selectedCountLabel.innerText = '0 dipilih';
                if (selectAllCb) selectAllCb.checked = false;
                return;
            }

            let html = '';
            let calculatedTotal = 0;
            let selectedCount = 0;
            let allSelected = true;

            cart.forEach(item => {
                if (item.selected) {
                    calculatedTotal += (item.price * item.qty);
                    selectedCount += item.qty;
                } else {
                    allSelected = false;
                }

                html += `
                    <div class="rp-cart-item">
                        <input type="checkbox" class="rp-cart-item-check" ${item.selected ? 'checked' : ''} onchange="toggleSelectCartItem(${item.id})">
                        <div class="rp-cart-item-icon">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <div class="rp-cart-item-info">
                            <div class="rp-cart-item-name">${item.name}</div>
                            <div class="rp-cart-item-price">${formatRupiah(item.price)}</div>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div class="rp-qty-stepper">
                                    <button class="rp-qty-btn" onclick="updateCartQty(${item.id}, -1)">-</button>
                                    <span class="rp-qty-val">${item.qty}</span>
                                    <button class="rp-qty-btn" onclick="updateCartQty(${item.id}, 1)">+</button>
                                </div>
                                <button class="rp-item-remove" onclick="removeFromCart(${item.id})" title="Hapus barang">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                            <div class="rp-cart-bonus-box">
                                <i class="fa-solid fa-shield-halved" style="color:#22C55E;"></i>
                                <span>100% Authentic & Checked Condition</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            totalPriceEl.innerText = formatRupiah(calculatedTotal);
            selectedCountLabel.innerText = `${selectedCount} dipilih`;
            if (selectAllCb) selectAllCb.checked = allSelected && cart.length > 0;
        }

        function submitCartCheckout() {
            const cart = getCart();
            const selectedItems = cart.filter(i => i.selected);

            if (selectedItems.length === 0) {
                alert('Silakan centang minimal 1 barang di keranjang untuk dibayar.');
                return;
            }

            const itemsPayload = selectedItems.map(i => ({ id: i.id, qty: i.qty }));
            const url = `{{ route('orders.checkout') }}?items=` + encodeURIComponent(JSON.stringify(itemsPayload));
            window.location.href = url;
        }

        // Initialize UI on load
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('clear_cart'))
                clearCart();
            @else
                renderCartUI();
            @endif
        });

        // Enhanced Global & Live Search Bar
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('navbar-search-form');

        if (searchInput) {
            // Live client-side instant filter if cards exist on current page
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const cards = document.querySelectorAll('[data-searchable]');
                const sections = document.querySelectorAll('.category-section');

                if (cards.length > 0) {
                    cards.forEach(card => {
                        const text = card.getAttribute('data-searchable').toLowerCase();
                        if (!query || text.includes(query)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });

                    // Hide empty category sections dynamically
                    sections.forEach(sec => {
                        const visibleCards = sec.querySelectorAll('.product-ref-card:not([style*="display: none"])');
                        sec.style.display = (visibleCards.length > 0) ? 'block' : 'none';
                    });
                }
            });

            // Enter key submits server-side search across all pages
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (searchForm) {
                        searchForm.submit();
                    }
                }
            });
        }

        // Live unread chat count polling for navbar
        @auth
        function updateUnreadChatBadge() {
            fetch("{{ route('chat.unread') }}")
                .then(r => r.json())
                .then(d => {
                    const badge = document.getElementById('rp-chat-badge');
                    if (badge) {
                        if (d.unread_count > 0) {
                            badge.textContent = d.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                }).catch(() => {});
        }
        updateUnreadChatBadge();
        setInterval(updateUnreadChatBadge, 10000);

        // Upgraded User Dropdown Navbar (Anti Hilang-Hilangan: Click-to-toggle + Outside Click)
        const userNavDropdown = document.getElementById('user-nav-dropdown');
        const userNavTrigger = document.getElementById('user-nav-trigger');
        if (userNavDropdown && userNavTrigger) {
            userNavTrigger.addEventListener('click', function(e) {
                e.stopPropagation();
                const isOpen = userNavDropdown.classList.toggle('is-open');
                userNavTrigger.setAttribute('aria-expanded', isOpen);
            });

            // Tutup dropdown jika user klik di luar area menu
            document.addEventListener('click', function(e) {
                if (!userNavDropdown.contains(e.target)) {
                    userNavDropdown.classList.remove('is-open');
                    userNavTrigger.setAttribute('aria-expanded', 'false');
                }
            });

            // Tutup dropdown jika menekan tombol Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    userNavDropdown.classList.remove('is-open');
                    userNavTrigger.setAttribute('aria-expanded', 'false');
                }
            });
        }
        @endauth
    </script>

    @stack('scripts')
</body>
</html>
