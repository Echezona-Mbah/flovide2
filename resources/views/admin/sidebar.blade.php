<style>
    :root {
        --sb-bg: #ffffff;
        --sb-border: #eef2f6;
        --sb-text: #475569;
        --sb-text-muted: #94a3b8;
        --sb-text-dark: #0f172a;
        --sb-brand: #2563eb;
        --sb-brand-deep: #1d4ed8;
        --sb-brand-light: #eff6ff;
        --sb-accent-cyan: #06b6d4;
        --sb-danger: #dc2626;
        --sb-danger-bg: #fef2f2;
        --sb-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        --sb-radius: 12px;
    }

    /* ── Main Sidebar Shell ───────────────────────────── */
    .app-sidebar {
        background: var(--sb-bg) !important;
        border-right: 1px solid var(--sb-border) !important;
        box-shadow: 8px 0 28px rgba(15, 23, 42, 0.03) !important;
        display: flex;
        flex-direction: column;
        height: 100vh;
        z-index: 15;
        transition: width 0.24s cubic-bezier(0.4, 0, 0.2, 1),
                    transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    /* ── Logo Header ─────────────────────────────────── */
    .app-sidebar .app-header__logo {
        height: 78px;
        min-height: 78px;
        padding: 0 18px;
        background: #ffffff;
        border-bottom: 1px solid var(--sb-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-shrink: 0;
        z-index: 16;
    }

    .app-sidebar .logo-src {
        display: flex !important;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        background: none !important;
        width: auto !important;
        height: auto !important;
        min-height: unset !important;
    }

    .sidebar-brand-box {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .sidebar-brand-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--sb-brand), var(--sb-accent-cyan));
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        flex-shrink: 0;
    }

    .sidebar-brand-info {
        display: flex;
        flex-direction: column;
        line-height: 1.15;
    }

    .sidebar-brand-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--sb-text-dark);
        letter-spacing: -0.03em;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sidebar-brand-tag {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 6px;
        background: var(--sb-brand-light);
        color: var(--sb-brand-deep);
        font-size: 9.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .sidebar-brand-subtitle {
        font-size: 11px;
        color: var(--sb-text-muted);
        font-weight: 600;
        letter-spacing: 0.02em;
        margin-top: 2px;
    }

    /* ── Hamburger Toggles ───────────────────────────── */
    .app-sidebar .hamburger {
        padding: 6px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid var(--sb-border);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .app-sidebar .hamburger:hover {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .app-sidebar .hamburger-inner,
    .app-sidebar .hamburger-inner::before,
    .app-sidebar .hamburger-inner::after {
        background-color: #334155 !important;
        width: 18px;
        height: 2px;
        border-radius: 2px;
    }

    .app-sidebar .hamburger-box {
        width: 18px;
        height: 14px;
    }

    /* ── Scrollable Sidebar Body ─────────────────────── */
    .app-sidebar .scrollbar-sidebar {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    .app-sidebar .scrollbar-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .app-sidebar .scrollbar-sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .app-sidebar .scrollbar-sidebar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 999px;
    }

    .app-sidebar .scrollbar-sidebar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    .app-sidebar__inner {
        padding: 16px 14px 28px !important;
    }

    /* ── Navigation Menu Base ────────────────────────── */
    .vertical-nav-menu {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    /* Section Headings */
    .vertical-nav-menu .app-sidebar__heading {
        color: var(--sb-text-muted);
        font-size: 10.5px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.10em;
        margin: 18px 8px 8px;
        padding: 0;
        list-style: none;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .vertical-nav-menu .app-sidebar__heading::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #f1f5f9;
        margin-left: 6px;
    }

    /* First heading top margin */
    .vertical-nav-menu > li:first-child.app-sidebar__heading {
        margin-top: 4px;
    }

    /* ── Top-Level Links ─────────────────────────────── */
    .vertical-nav-menu li {
        position: relative;
        list-style: none;
    }

    .vertical-nav-menu li > a {
        display: flex;
        align-items: center;
        color: var(--sb-text);
        border-radius: var(--sb-radius);
        margin-bottom: 3px;
        padding: 10px 14px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.18s ease, color 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
        overflow: hidden;
        white-space: nowrap;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    .vertical-nav-menu li > a:hover {
        background: var(--sb-brand-light);
        color: var(--sb-text-dark);
        transform: translateX(2px);
    }

    /* Menu Text Label */
    .vertical-nav-menu li > a > .nav-label {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 13px;
        font-weight: 600;
    }

    /* Chevron Dropdown Arrow */
    .vertical-nav-menu li > a > .metismenu-state-icon {
        flex: 0 0 auto;
        margin-left: auto;
        padding-left: 8px;
        font-size: 10.5px;
        color: var(--sb-text-muted);
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1), color 0.2s ease;
    }

    /* Open/Active Accordion Rotation */
    .vertical-nav-menu li.mm-active > a > .metismenu-state-icon {
        transform: rotate(180deg);
        color: var(--sb-brand);
    }

    /* ── Active State (Top-Level) ─────────────────────── */
    .vertical-nav-menu li.mm-active > a,
    .vertical-nav-menu li > a.mm-active,
    .vertical-nav-menu li > a.active-link {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(37, 99, 235, 0.24);
    }

    .vertical-nav-menu li.mm-active > a > .metismenu-state-icon,
    .vertical-nav-menu li > a.mm-active > .metismenu-state-icon,
    .vertical-nav-menu li > a.active-link > .metismenu-state-icon {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    /* Sub-menu parent active state when one of its children is active */
    .vertical-nav-menu li.parent-has-active > a {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fbff 100%) !important;
        color: var(--sb-brand-deep) !important;
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.15) !important;
    }

    .vertical-nav-menu li.parent-has-active > a > .metismenu-state-icon {
        color: var(--sb-brand) !important;
    }

    /* ── Sub-Menu List ───────────────────────────────── */
    .vertical-nav-menu ul {
        list-style: none;
        padding: 4px 0 6px 12px;
        margin: 2px 0 6px 16px;
        border-left: 2px solid #e2e8f0;
        position: relative;
    }

    .vertical-nav-menu ul li > a {
        padding: 8px 12px;
        font-size: 12.5px;
        color: var(--sb-text);
        border-radius: 10px;
        font-weight: 500;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
    }

    .vertical-nav-menu ul li > a:hover {
        color: var(--sb-text-dark);
        background: #f8fafc;
        transform: translateX(2px);
    }

    /* Active Sub-item */
    .vertical-nav-menu ul li > a.sub-active,
    .vertical-nav-menu ul li.mm-active > a,
    .vertical-nav-menu ul li > a.mm-active {
        background: var(--sb-brand-light) !important;
        color: var(--sb-brand-deep) !important;
        font-weight: 700 !important;
        box-shadow: none !important;
    }

    /* ── Admin User Card in Sidebar ──────────────────── */
    .sidebar-user-card {
        margin-top: 18px;
        padding: 12px;
        border-radius: 14px;
        background: #ffffff;
        border: 1px solid var(--sb-border);
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03);
    }

    .sidebar-user-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sidebar-user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        object-fit: cover;
        border: 1.5px solid #e2e8f0;
        flex-shrink: 0;
    }

    .sidebar-user-meta {
        min-width: 0;
        flex: 1;
    }

    .sidebar-user-name {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--sb-text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    .sidebar-user-role {
        font-size: 10.5px;
        color: var(--sb-text-muted);
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Logout Button ───────────────────────────────── */
    .sidebar-logout a {
        margin-top: 12px;
        background: var(--sb-danger-bg);
        color: var(--sb-danger) !important;
        border: 1px solid rgba(220, 38, 38, 0.15);
    }

    .sidebar-logout a:hover {
        background: #fee2e2 !important;
        color: #b91c1c !important;
        border-color: rgba(220, 38, 38, 0.25);
    }

    /* ═══════════════════════════════════════════════════
       DESKTOP VIEW (Screens >= 992px):
       No icons on menu items for clean minimal typography
       ═══════════════════════════════════════════════════ */
    @media (min-width: 992px) {
        .vertical-nav-menu li > a > .metismenu-icon {
            display: none !important;
        }

        .vertical-nav-menu li > a {
            gap: 0;
            padding: 10px 14px;
        }
    }

    /* ═══════════════════════════════════════════════════
       MOBILE VIEW (Screens <= 991.98px):
       Fixed Positioned Sidebar Drawer & Backdrop Overlay
       ═══════════════════════════════════════════════════ */
    @media (max-width: 991.98px) {
        .app-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            height: 100dvh !important;
            width: 290px !important;
            max-width: 85vw !important;
            z-index: 1050 !important;
            box-shadow: 14px 0 45px rgba(15, 23, 42, 0.22) !important;
            transform: translateX(-100%) !important;
            display: flex !important;
            flex-direction: column !important;
            overflow: hidden !important;
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
            visibility: visible !important;
        }

        /* Fixed open state on mobile */
        .sidebar-mobile-open .app-sidebar,
        .app-container.sidebar-mobile-open .app-sidebar,
        body.sidebar-mobile-open .app-sidebar {
            transform: translateX(0) !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            bottom: 0 !important;
        }

        .app-sidebar .app-header__logo {
            display: flex !important;
            padding: 16px 18px;
            height: 70px;
            min-height: 70px;
            flex-shrink: 0;
        }

        .app-sidebar .scrollbar-sidebar {
            flex: 1 1 auto !important;
            height: calc(100vh - 70px) !important;
            height: calc(100dvh - 70px) !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
        }

        /* Show icons on mobile view */
        .vertical-nav-menu li > a > .metismenu-icon {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            flex: 0 0 32px;
            width: 32px;
            height: 32px;
            font-size: 14px;
            border-radius: 9px;
            background: #f1f5f9;
            color: #475569;
            transition: all 0.2s ease;
            margin-right: 10px;
        }

        .vertical-nav-menu li > a:hover > .metismenu-icon {
            background: var(--sb-brand-light);
            color: var(--sb-brand);
        }

        .vertical-nav-menu li.mm-active > a > .metismenu-icon,
        .vertical-nav-menu li > a.mm-active > .metismenu-icon,
        .vertical-nav-menu li > a.active-link > .metismenu-icon {
            background: rgba(255, 255, 255, 0.22) !important;
            color: #ffffff !important;
        }

        .vertical-nav-menu li.parent-has-active > a > .metismenu-icon {
            background: rgba(37, 99, 235, 0.12) !important;
            color: var(--sb-brand) !important;
        }

        .sidebar-logout a .metismenu-icon {
            background: rgba(220, 38, 38, 0.1) !important;
            color: var(--sb-danger) !important;
        }

        .sidebar-logout a:hover .metismenu-icon {
            background: rgba(220, 38, 38, 0.18) !important;
            color: #b91c1c !important;
        }

        /* Submenu bullet indicator on mobile */
        .vertical-nav-menu ul li > a::before {
            content: "";
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #cbd5e1;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .vertical-nav-menu ul li > a:hover::before,
        .vertical-nav-menu ul li > a.sub-active::before {
            background: var(--sb-brand);
        }

        /* Blur Backdrop Overlay on Mobile */
        .sidebar-mobile-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            height: 100dvh !important;
            background: rgba(15, 23, 42, 0.45) !important;
            backdrop-filter: blur(3px) !important;
            -webkit-backdrop-filter: blur(3px) !important;
            z-index: 1040 !important;
            display: none;
            cursor: pointer;
        }

        .sidebar-mobile-open .sidebar-mobile-overlay,
        .app-container.sidebar-mobile-open .sidebar-mobile-overlay,
        body.sidebar-mobile-open .sidebar-mobile-overlay {
            display: block !important;
        }
    }

    /* ── Collapsed Sidebar Support on Desktop (.closed-sidebar) ── */
    @media (min-width: 992px) {
        .closed-sidebar .app-sidebar {
            width: 80px !important;
            min-width: 80px !important;
        }

        .closed-sidebar .app-sidebar .sidebar-brand-info,
        .closed-sidebar .app-sidebar .nav-label,
        .closed-sidebar .app-sidebar .metismenu-state-icon,
        .closed-sidebar .app-sidebar .sidebar-user-card {
            display: none !important;
        }

        .closed-sidebar .app-sidebar .vertical-nav-menu li > a > .metismenu-icon {
            display: inline-flex !important;
            margin: 0 auto;
        }

        .closed-sidebar .app-sidebar .app-sidebar__heading {
            text-indent: -9999px;
            margin: 14px 0 8px;
        }

        .closed-sidebar .app-sidebar .app-sidebar__heading::after {
            margin-left: 0;
        }

        .closed-sidebar .app-sidebar .vertical-nav-menu li > a {
            padding: 8px 0;
            justify-content: center;
        }

        .closed-sidebar .app-sidebar .app-header__logo {
            padding: 0;
            justify-content: center;
        }

        .closed-sidebar .app-sidebar .header__pane {
            display: none;
        }

        /* Hover Expansion when Collapsed */
        .closed-sidebar .app-sidebar:hover {
            width: 280px !important;
        }

        .closed-sidebar .app-sidebar:hover .sidebar-brand-info,
        .closed-sidebar .app-sidebar:hover .nav-label,
        .closed-sidebar .app-sidebar:hover .metismenu-state-icon,
        .closed-sidebar .app-sidebar:hover .sidebar-user-card {
            display: flex !important;
        }

        .closed-sidebar .app-sidebar:hover .sidebar-user-meta {
            display: block !important;
        }

        .closed-sidebar .app-sidebar:hover .vertical-nav-menu li > a > .metismenu-icon {
            display: none !important;
        }

        .closed-sidebar .app-sidebar:hover .app-sidebar__heading {
            text-indent: initial;
        }

        .closed-sidebar .app-sidebar:hover .vertical-nav-menu li > a {
            padding: 10px 14px;
            justify-content: flex-start;
        }

        .closed-sidebar .app-sidebar:hover .app-header__logo {
            padding: 0 18px;
            justify-content: space-between;
        }

        .closed-sidebar .app-sidebar:hover .header__pane {
            display: block;
        }
    }
</style>

@php
    $currentAdmin = Auth::guard('admin')->user();
    
    // Route Active State Helpers
    $isDashActive = request()->routeIs('admin.dashboard');
    $isBusinessActive = request()->routeIs('admin.business-account*');
    $isPersonalActive = request()->routeIs('admin.personal-account*');
    $isAccountTypesActive = $isBusinessActive || $isPersonalActive;
    
    $isBeneficiasActive = request()->routeIs('admin.allbeneficias*');
    $isAccountsActive = request()->routeIs('admin.allaccount*');
    $isSubaccountsActive = request()->routeIs('admin.allsubaccount*');
    $isCustomersActive = request()->routeIs('admin.allcustomer*');
    $isAccountMgmtActive = $isBeneficiasActive || $isAccountsActive || $isSubaccountsActive || $isCustomersActive;

    $isTxHistoryActive = request()->routeIs('admin.transactionhistory*') || request()->routeIs('admin.alltransactions*') || request()->routeIs('admin.personal-transactions*');

    $isChargebackActive = request()->routeIs('admin.chargeback*');
    $isRefundActive = request()->routeIs('admin.refund*');
    $isDisputesActive = $isChargebackActive || $isRefundActive;
@endphp

<div class="app-sidebar sidebar-shadow">
    <!-- Header / Brand Area -->
    <div class="app-header__logo">
        <a href="{{ route('admin.dashboard') }}" class="logo-src">
            <div class="sidebar-brand-box">
                <div class="sidebar-brand-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="sidebar-brand-info">
                    <div class="sidebar-brand-title">
                        Flovide
                        <span class="sidebar-brand-tag">Admin</span>
                    </div>
                    <span class="sidebar-brand-subtitle">Fintech Hub</span>
                </div>
            </div>
        </a>

        <div class="header__pane ms-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic mobile-toggle-nav" data-class="closed-sidebar" title="Toggle Sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Toggles -->
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>

    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </span>
            </button>
        </span>
    </div>

    <!-- Scrollable Navigation Area -->
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">

            <!-- Navigation Links -->
            <ul class="vertical-nav-menu mt-4">
                
                <!-- Main Section -->
                <li class="app-sidebar__heading">Overview</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ $isDashActive ? 'active-link' : '' }}">
                        <i class="metismenu-icon fa-solid fa-chart-line"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>

                <!-- Accounts Section -->
                <li class="app-sidebar__heading">Accounts & Users</li>

                <li class="{{ $isAccountTypesActive ? 'mm-active parent-has-active' : '' }}">
                    <a href="#" aria-expanded="{{ $isAccountTypesActive ? 'true' : 'false' }}">
                        <i class="metismenu-icon fa-solid fa-users-gear"></i>
                        <span class="nav-label">Account Types</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="{{ $isAccountTypesActive ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('admin.business-account') }}" class="{{ $isBusinessActive ? 'sub-active' : '' }}">
                                <span class="nav-label">Business Account</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.personal-account') }}" class="{{ $isPersonalActive ? 'sub-active' : '' }}">
                                <span class="nav-label">Personal Account</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ $isAccountMgmtActive ? 'mm-active parent-has-active' : '' }}">
                    <a href="#" aria-expanded="{{ $isAccountMgmtActive ? 'true' : 'false' }}">
                        <i class="metismenu-icon fa-solid fa-sliders"></i>
                        <span class="nav-label">Account Management</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="{{ $isAccountMgmtActive ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('admin.allbeneficias') }}" class="{{ $isBeneficiasActive ? 'sub-active' : '' }}">
                                <span class="nav-label">Beneficias</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Transactions & Financials -->
                <li class="app-sidebar__heading">Transactions</li>

                <li>
                    <a href="{{ route('admin.transactionhistory') }}" class="{{ $isTxHistoryActive ? 'active-link' : '' }}">
                        <i class="metismenu-icon fa-solid fa-arrow-right-arrow-left"></i>
                        <span class="nav-label">Transaction History</span>
                    </a>
                </li>

                <!-- Disputes & Resolutions -->
                <li class="app-sidebar__heading">Disputes & Risk</li>

                <li class="{{ $isDisputesActive ? 'mm-active parent-has-active' : '' }}">
                    <a href="#" aria-expanded="{{ $isDisputesActive ? 'true' : 'false' }}">
                        <i class="metismenu-icon fa-solid fa-scale-balanced"></i>
                        <span class="nav-label">Disputes / Refunds</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down"></i>
                    </a>
                    <ul class="{{ $isDisputesActive ? 'mm-show' : '' }}">
                        <li>
                            <a href="{{ route('admin.chargeback') }}" class="{{ $isChargebackActive ? 'sub-active' : '' }}">
                                <span class="nav-label">Chargeback</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.refund') }}" class="{{ $isRefundActive ? 'sub-active' : '' }}">
                                <span class="nav-label">Refund</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Session & Security -->
                <li class="app-sidebar__heading">Session</li>

                <li class="sidebar-logout">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="metismenu-icon fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="nav-label">Logout</span>
                    </a>

                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>

            <!-- Bottom Mini Profile Card -->
            @if($currentAdmin)
                <div class="sidebar-user-card">
                    <div class="sidebar-user-content">
                        @if($currentAdmin->profile_picture)
                            <img src="{{ asset('storage/'.$currentAdmin->profile_picture) }}" alt="Avatar" class="sidebar-user-avatar">
                        @else
                            <img src="{{ asset('asserts/dashboard/circle-dot.png') }}" alt="Avatar" class="sidebar-user-avatar">
                        @endif
                        <div class="sidebar-user-meta">
                            <div class="sidebar-user-name">{{ $currentAdmin->name ?? 'Administrator' }}</div>
                            <div class="sidebar-user-role">{{ $currentAdmin->role ?? 'Operations' }}</div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<!-- Mobile Sidebar Backdrop Overlay -->
<div class="sidebar-mobile-overlay"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var mobileOverlay = document.querySelector('.sidebar-mobile-overlay');
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                var container = document.querySelector('.app-container');
                if (container) {
                    container.classList.remove('sidebar-mobile-open');
                }
                var toggles = document.querySelectorAll('.mobile-toggle-nav');
                toggles.forEach(function(btn) {
                    btn.classList.remove('is-active');
                });
            });
        }
    });
</script>
