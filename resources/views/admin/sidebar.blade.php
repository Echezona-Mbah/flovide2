<style>
    .app-sidebar {
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        box-shadow: 8px 0 24px rgba(15, 23, 42, 0.04);
        overflow-x: hidden;
    }

    .app-sidebar .app-header__logo {
        height: auto;
        min-height: 74px;
        padding: 16px 18px;
        background: #ffffff;
        border-bottom: 1px solid #eef2f7;
    }

    .app-sidebar .logo-src {
        width: 100%;
        height: auto;
        min-height: 42px;
        background: none !important;
        display: flex;
        align-items: center;
        position: relative;
    }

    .app-sidebar .logo-src::before {
        content: "Flovide Admin";
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .app-sidebar .logo-src::after {
        content: "Control Center";
        position: absolute;
        left: 0;
        top: 24px;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .app-sidebar .header__pane .hamburger-inner,
    .app-sidebar .header__pane .hamburger-inner::before,
    .app-sidebar .header__pane .hamburger-inner::after,
    .app-sidebar .app-header__mobile-menu .hamburger-inner,
    .app-sidebar .app-header__mobile-menu .hamburger-inner::before,
    .app-sidebar .app-header__mobile-menu .hamburger-inner::after {
        background: #334155;
    }

    .app-sidebar__inner {
        padding: 18px 10px 28px;
    }

    /* ── Nav list ───────────────────────────── */
    .vertical-nav-menu {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    /* Every menu link is a flex row */
    .vertical-nav-menu li > a {
        display: flex;
        align-items: center;
        gap: 0;                      /* spacing handled by icon width */
        color: #475569;
        border-radius: 14px;
        margin-bottom: 4px;
        padding: 10px 12px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s ease, color 0.2s ease, transform 0.18s ease;
        overflow: hidden;            /* prevent any child overflow */
        white-space: nowrap;         /* keep everything on one line */
    }

    .vertical-nav-menu li > a:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(2px);
    }

    /* ── Leading icon (fixed width, never shrinks) ── */
    .vertical-nav-menu li > a > .metismenu-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;              /* never compress */
        width: 28px;                 /* fixed column */
        height: 28px;
        font-size: 14px;
        color: #2563eb;
        margin-right: 8px;          /* gap between icon and text */
    }

    /* ── Menu label (takes all remaining space) ── */
    .vertical-nav-menu li > a > .nav-label {
        flex: 1;
        min-width: 0;               /* allow text to be clipped if needed */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Caret icon (pushed to far right, never shrinks) ── */
    .vertical-nav-menu li > a > .metismenu-state-icon {
        flex-shrink: 0;
        margin-left: auto;
        padding-left: 6px;
        font-size: 11px;
        color: #94a3b8;
        transition: transform 0.22s ease;
    }

    /* Rotate caret when submenu is open */
    .vertical-nav-menu li.mm-active > a > .metismenu-state-icon {
        transform: rotate(180deg);
        color: #2563eb;
    }

    /* ── Section headings ── */
    .vertical-nav-menu .app-sidebar__heading {
        color: #94a3b8;
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin: 18px 8px 8px;
        padding: 0;
        list-style: none;
        white-space: nowrap;
    }

    /* ── Active state ── */
    .vertical-nav-menu li.mm-active > a,
    .vertical-nav-menu li > a.mm-active {
        background: linear-gradient(135deg, #eff6ff, #f0f9ff);
        color: #0f172a;
        box-shadow: inset 0 0 0 1px rgba(37, 99, 235, 0.10);
    }

    .vertical-nav-menu li.mm-active > a .metismenu-icon,
    .vertical-nav-menu li > a.mm-active .metismenu-icon {
        color: #1d4ed8;
    }

    /* ── Sub-menu list ── */
    .vertical-nav-menu ul {
        list-style: none;
        padding: 4px 0 6px 14px;
        margin: 0 0 4px 14px;
        border-left: 2px solid #e2e8f0;
    }

    .vertical-nav-menu ul li > a {
        padding: 8px 10px;
        font-size: 12.5px;
        color: #64748b;
        border-radius: 12px;
        font-weight: 500;
    }

    .vertical-nav-menu ul li > a:hover {
        color: #0f172a;
        background: #f8fafc;
    }

    /* Bullet dot for sub-items (replaces empty icon) */
    .vertical-nav-menu ul li > a > .metismenu-icon {
        flex-shrink: 0;
        width: 18px;
        height: 18px;
        font-size: 10px;
        color: #cbd5e1;
        margin-right: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Empty metismenu-icon → render as a small dot */
    .vertical-nav-menu ul li > a > .metismenu-icon:empty::before {
        content: "";
        display: block;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    /* ── Logout ── */
    .sidebar-logout a {
        margin-top: 10px;
        background: #fff1f2;
        color: #dc2626 !important;
    }

    .sidebar-logout a:hover {
        background: #ffe4e6;
        color: #b91c1c !important;
    }

    .sidebar-logout a .metismenu-icon {
        color: #dc2626 !important;
    }

    /* ── Scroll note ── */
    .sidebar-scroll-note {
        margin: 0 8px 14px;
        padding: 10px 12px;
        border-radius: 14px;
        background: linear-gradient(180deg, #f8fbff, #f1f5f9);
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 11.5px;
        line-height: 1.6;
        white-space: normal;        /* allow wrapping inside the note */
    }
</style>


<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ms-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

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
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>

    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">

            <br>
            <div class="sidebar-scroll-note">
                Manage accounts, transactions, refunds, and platform operations from one place.
            </div>

            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Overview</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="metismenu-icon fa-solid fa-chart-line"></i>
                        <span class="nav-label">Dashboard</span>
                    </a>
                </li>

                <li class="app-sidebar__heading">Accounts</li>

                <li class="mm-active">
                    <a href="#">
                        <i class="metismenu-icon fa-solid fa-rocket"></i>
                        <span class="nav-label">Account Types</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down caret-left"></i>
                    </a>
                    <ul class="mm-show">
                        <li>
                            <a href="{{ route('admin.business-account') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Business Account</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.personal-account') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Personal Account</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon fa-solid fa-sliders"></i>
                        <span class="nav-label">Account Management</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down caret-left"></i>
                    </a>
                    <ul>
                        {{-- <li>
                            <a href="{{ route('admin.allaccount') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Account</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.allsubaccount') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Sub Account</span>
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('admin.allbeneficias') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Beneficias</span>
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.allcustomer') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Customer</span>
                            </a>
                        </li> --}}
                    </ul>
                </li>

                <li class="app-sidebar__heading">Transactions</li>

                <li>
                    <a href="{{ route('admin.transactionhistory') }}">
                        <i class="metismenu-icon fa-solid fa-chart-bar"></i>
                        <span class="nav-label">Transaction History</span>
                    </a>
                </li>

                <li class="app-sidebar__heading">Disputes</li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon fa-solid fa-file-invoice"></i>
                        <span class="nav-label">Chargeback / Refund</span>
                        <i class="metismenu-state-icon fa-solid fa-chevron-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.chargeback') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Chargeback</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.refund') }}">
                                <i class="metismenu-icon"></i>
                                <span class="nav-label">Refund</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="app-sidebar__heading">Session</li>

                <li class="sidebar-logout">
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="metismenu-icon fa-solid fa-power-off"></i>
                        <span class="nav-label">Logout</span>
                    </a>

                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
