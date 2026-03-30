<style>
    .app-sidebar {
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        box-shadow: 8px 0 24px rgba(15, 23, 42, 0.04);
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
        padding: 18px 14px 28px;
    }

    .vertical-nav-menu {
        margin: 0;
        padding: 0;
    }

    .vertical-nav-menu li a {
        color: #475569;
        border-radius: 16px;
        margin-bottom: 6px;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.22s ease;
        display: flex;
        align-items: center;
    }

    .vertical-nav-menu li a:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(2px);
    }

    .vertical-nav-menu li a .metismenu-icon {
        color: #2563eb;
        opacity: 1;
        font-size: 18px;
        min-width: 26px;
    }

    .vertical-nav-menu .metismenu-state-icon,
    .vertical-nav-menu .caret-left {
        color: #94a3b8;
    }

    .vertical-nav-menu .app-sidebar__heading {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin: 18px 10px 10px;
        padding: 0;
    }

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

    .vertical-nav-menu ul {
        padding: 6px 0 8px 10px;
        margin: 0 0 8px 8px;
        border-left: 1px dashed #dbe3ee;
    }

    .vertical-nav-menu ul li a {
        padding: 10px 12px;
        font-size: 13px;
        color: #64748b;
        border-radius: 14px;
    }

    .vertical-nav-menu ul li a:hover {
        color: #0f172a;
        background: #f8fafc;
    }

    .vertical-nav-menu ul li a .metismenu-icon {
        min-width: 14px;
        width: 14px;
        font-size: 8px;
        color: #94a3b8;
    }

    .sidebar-logout a {
        margin-top: 12px;
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

    .sidebar-scroll-note {
        margin: 0 10px 16px;
        padding: 12px 14px;
        border-radius: 18px;
        background: linear-gradient(180deg, #f8fbff, #f1f5f9);
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
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
            <div class="sidebar-scroll-note">
                Manage accounts, transactions, refunds, and platform operations from one place.
            </div>

            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Overview</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="metismenu-icon pe-7s-graph2"></i>
                        Dashboard
                    </a>
                </li>

                <li class="app-sidebar__heading">Accounts</li>

                <li class="mm-active">
                    <a href="#">
                        <i class="metismenu-icon pe-7s-rocket"></i>
                        Account Types
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-show">
                        <li>
                            <a href="{{ route('admin.business-account') }}">
                                <i class="metismenu-icon"></i>
                                Business Account
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.personal-account') }}">
                                <i class="metismenu-icon"></i>
                                Personal Account
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-light"></i>
                        Account Management
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        {{-- <li>
                            <a href="{{ route('admin.allaccount') }}">
                                <i class="metismenu-icon"></i>
                                Account
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.allsubaccount') }}">
                                <i class="metismenu-icon"></i>
                                Sub Account
                            </a>
                        </li> --}}
                        <li>
                            <a href="{{ route('admin.allbeneficias') }}">
                                <i class="metismenu-icon"></i>
                                Beneficias
                            </a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('admin.allcustomer') }}">
                                <i class="metismenu-icon"></i>
                                Customer
                            </a>
                        </li> --}}
                    </ul>
                </li>

                <li class="app-sidebar__heading">Transactions</li>

                <li>
                    <a href="{{ route('admin.transactionhistory') }}">
                        <i class="metismenu-icon pe-7s-graph"></i>
                        Transaction History
                    </a>
                </li>

                <li class="app-sidebar__heading">Disputes</li>

                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-browser"></i>
                        Chargeback / Refund
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="{{ route('admin.chargeback') }}">
                                <i class="metismenu-icon"></i>
                                Chargeback
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.refund') }}">
                                <i class="metismenu-icon"></i>
                                Refund
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="app-sidebar__heading">Session</li>

                <li class="sidebar-logout">
                    <a href="#"
                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                        <i class="metismenu-icon pe-7s-power"></i>
                        Logout
                    </a>

                    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
