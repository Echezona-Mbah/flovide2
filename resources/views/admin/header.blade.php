<style>
    .app-header {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        min-height: 78px;
    }

    .app-header__logo {
        background: #ffffff;
        border-right: 1px solid #eef2f7;
        min-width: 260px;
    }

    .app-header__logo .logo-src {
        background: none !important;
        width: 100%;
        height: auto;
        min-height: 42px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .app-header__logo .logo-src::before {
        content: "Flovide Admin";
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.02em;
    }

    .app-header__logo .logo-src::after {
        content: "Operations Panel";
        position: absolute;
        left: 0;
        top: 24px;
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .app-header__content {
        padding: 0 22px;
    }

    .search-wrapper {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 6px 8px !important;
        min-width: 320px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    .search-wrapper .input-holder {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-wrapper .search-input {
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        color: #0f172a;
        font-size: 14px;
    }

    .search-wrapper .search-input::placeholder {
        color: #94a3b8;
    }

    .search-wrapper .search-icon {
        border: 0;
        background: linear-gradient(135deg, #2563eb, #0ea5e9);
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .search-wrapper .search-icon span {
        width: 14px;
        height: 14px;
        border: 2px solid #fff;
        border-radius: 50%;
        display: inline-block;
        position: relative;
    }

    .search-wrapper .search-icon span::after {
        content: "";
        width: 7px;
        height: 2px;
        background: #fff;
        position: absolute;
        right: -6px;
        bottom: -2px;
        transform: rotate(45deg);
        border-radius: 2px;
    }

    .search-wrapper .btn-close {
        border: 0;
        background: transparent;
        color: #64748b;
        font-size: 20px;
        line-height: 1;
        padding: 0 8px;
    }

    .header-megamenu .nav-link {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 11px 16px !important;
        color: #334155;
        font-weight: 700;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
    }

    .header-megamenu .nav-link:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .header-megamenu .nav-link-icon {
        color: #2563eb;
        margin-right: 6px;
    }

    .header-dots .btn.btn-link {
        text-decoration: none !important;
    }

    .icon-wrapper-alt {
        width: 46px;
        height: 46px;
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    }

    .icon-wrapper-bg.bg-danger {
        background: rgba(220, 38, 38, 0.12) !important;
    }

    .icon-wrapper .icon {
        font-size: 20px;
    }

    .header-btn-lg .btn {
        text-decoration: none !important;
    }

    .header-btn-lg .widget-content-wrapper {
        align-items: center;
        gap: 12px;
    }

    .header-user-info .widget-heading {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
    }

    .header-user-info .widget-subheading {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .admin-avatar-trigger {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 18px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
    }

    .admin-avatar-trigger img {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 14px;
        border: 2px solid #eef4ff;
    }

    .header-action-btn {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        border: 0;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.16);
    }

    .dropdown-menu {
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
        overflow: hidden;
    }

    .dropdown-menu-header-inner.bg-info {
        background: linear-gradient(135deg, #1d4ed8, #0891b2) !important;
    }

    .dropdown-menu-header-inner.bg-deep-blue {
        background: linear-gradient(135deg, #0f172a, #1d4ed8) !important;
    }

    .dropdown-menu .nav-link {
        color: #334155 !important;
        font-weight: 600;
    }

    .dropdown-menu .nav-link:hover {
        background: #f8fafc;
    }

    .grid-menu .btn {
        border-radius: 16px;
        font-weight: 700;
        min-height: 84px;
    }

    .btn-pill.btn-focus {
        background: linear-gradient(135deg, #2563eb, #0891b2);
        border: 0;
    }

    .app-header__menu .btn-primary,
    .mobile-toggle-header-nav.btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: 0;
        border-radius: 12px;
    }

    .app-header .hamburger-inner,
    .app-header .hamburger-inner::before,
    .app-header .hamburger-inner::after {
        background: #334155;
    }

    .notification-trigger {
    position: relative;
    text-decoration: none !important;
}

.notification-icon-wrap {
    position: relative;
}

.notification-count {
    position: absolute;
    top: -4px;
    right: -5px;
    min-width: 19px;
    height: 19px;
    padding: 0 5px;
    border-radius: 999px;
    background: #dc2626;
    color: #fff;
    border: 2px solid #fff;
    font-size: 10px;
    font-weight: 800;
    line-height: 15px;
    text-align: center;
}

.notification-menu {
    width: 420px;
    padding: 0;
    border-radius: 20px;
}

.notification-menu-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 20px;
    background: linear-gradient(135deg, #0f172a, #2563eb);
    color: #fff;
}

.notification-menu-header h5 {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
}

.notification-menu-header p {
    margin: 4px 0 0;
    color: rgba(255, 255, 255, 0.78);
    font-size: 12px;
    font-weight: 600;
}

.notification-header-icon {
    width: 42px;
    height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.16);
}

.notification-list {
    max-height: 360px;
    overflow-y: auto;
    padding: 8px;
    background: #f8fafc;
}

.notification-item {
    display: flex;
    gap: 12px;
    padding: 14px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid #e2e8f0;
    margin-bottom: 8px;
}

.notification-item-icon {
    width: 38px;
    height: 38px;
    flex: 0 0 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 13px;
    background: #eff6ff;
    color: #2563eb;
}

.notification-item-body {
    min-width: 0;
    flex: 1;
}

.notification-item-top {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: flex-start;
}

.notification-item-top strong {
    color: #0f172a;
    font-size: 13px;
    line-height: 1.35;
}

.notification-item-top span {
    flex: 0 0 auto;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 700;
}

.notification-item-body p {
    margin: 5px 0 0;
    color: #475569;
    font-size: 12px;
    line-height: 1.45;
}

.notification-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 10px;
}

.notification-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 8px;
    border-radius: 999px;
    background: #f1f5f9;
    color: #334155;
    font-size: 11px;
    font-weight: 700;
}

.notification-empty {
    padding: 34px 18px;
    text-align: center;
    color: #64748b;
}

.notification-empty i {
    display: block;
    margin-bottom: 10px;
    font-size: 28px;
    color: #94a3b8;
}

.notification-empty strong {
    display: block;
    color: #0f172a;
    font-size: 14px;
}

.notification-empty span {
    display: block;
    margin-top: 3px;
    font-size: 12px;
}

.notification-footer {
    padding: 12px;
    text-align: center;
    border-top: 1px solid #e2e8f0;
    background: #fff;
}
</style>

    <div class="app-header header-shadow HeaderAnimation-appear">
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

        <div class="app-header__content">
            <div class="app-header-left">
                <div class="search-wrapper" style="padding: 10px;">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Search menu or pages..." id="navSearch">
                        <button class="search-icon"><span></span></button>
                    </div>
                    <button class="btn-close" onclick="closeSearch()">×</button>
                </div>

                <ul class="header-megamenu nav ms-3">
                    <li class="nav-item">
                        <a href="javascript:void(0);" data-bs-placement="bottom" rel="popover-focus" data-offset="300" data-bs-toggle="popover-custom" class="nav-link">
                            <i class="nav-link-icon fa-solid fa-gift"></i>
                            Quick Setting
                            <i class="fa fa-angle-down ms-2 opacity-5"></i>
                        </a>

                        <div class="rm-max-width">
                            <div class="d-none popover-custom-content">
                                <div class="dropdown-mega-menu">
                                    <div class="grid-menu grid-menu-3col">
                                        <div class="g-0 row">
                                            <div class="col-sm-6 col-xl-4">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Overview</li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.add_admin') }}" class="nav-link">
                                                            <i class="nav-link-icon fa-solid fa-circle-plus"></i>
                                                            <span>Add Admin</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.all_admin') }}" class="nav-link">
                                                            <i class="nav-link-icon fa-solid fa-users"></i>
                                                            <span>Admins</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.exchangerate') }}" class="nav-link">
                                                            <i class="nav-link-icon fa-solid fa-arrows-rotate"></i>
                                                            <span>Exchange Rate</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.career.index') }}" class="nav-link">
                                                            <i class="nav-link-icon fa-solid fa-briefcase"></i>
                                                            <span>Careers</span>
                                                        </a>
                                                    </li>

                                                     <li class="nav-item">
                                                        <a href="{{ route('admin.promocodes.index') }}" class="nav-link">
                                                            <i class="nav-link-icon fa-solid fa-briefcase"></i>
                                                            <span>Promo Code</span>
                                                        </a>
                                                    </li>

                                                </ul>
                                            </div>

                                            <div class="col-sm-6 col-xl-4">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Favourites</li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.personal-account') }}" class="nav-link">Personal Account</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.business-account') }}" class="nav-link">Business Account</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="{{ route('admin.blog') }}" class="nav-link">Blog Post</a>
                                                    </li>
                                                </ul>
                                            </div>

                                        <div class="col-sm-6 col-xl-4">
                                            <ul class="nav flex-column">
                                                <li class="nav-item-header nav-item">Workspace</li>
                                                <li class="nav-item">
                                                    <a href="{{ route('admin.currency.limits') }}" class="nav-link">Currency Limits</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('admin.contact-requests') }}" class="nav-link">Contact Requests</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="javascript:void(0);" class="nav-link">
                                                        Goal Metrics
                                                        <div class="ms-auto badge bg-warning">3</div>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="{{ route('admin.bank-account-requests') }}" class="nav-link">Bank Account Requests</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="app-header-right">
                <div class="header-dots">

                    <div class="dropdown me-2">
                        <button type="button" data-bs-toggle="dropdown" class="p-0 btn btn-link notification-trigger">
                            <span class="icon-wrapper icon-wrapper-alt rounded-circle notification-icon-wrap">
                                <span class="icon-wrapper-bg bg-danger"></span>
                                <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>

                                @if($unreadReferralAlerts->count() > 0)
                                    <span class="notification-count">{{ $unreadReferralAlerts->count() }}</span>
                                @endif
                            </span>
                        </button>

                        <div class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right notification-menu">
                            <div class="notification-menu-header">
                                <div>
                                    <h5>Notifications</h5>
                                    <p>
                                        {{ $unreadReferralAlerts->count() }}
                                        {{ Str::plural('unread referral alert', $unreadReferralAlerts->count()) }}
                                    </p>
                                </div>
                                <span class="notification-header-icon">
                                    <i class="fa-solid fa-bell"></i>
                                </span>
                            </div>

                            <div class="notification-list">
                                @forelse($unreadReferralAlerts as $note)
                                    <div class="notification-item">
                                        <div class="notification-item-icon">
                                            <i class="fa-solid fa-gift"></i>
                                        </div>

                                        <div class="notification-item-body">
                                            <div class="notification-item-top">
                                                <strong>{{ $note->title }}</strong>
                                                <span>{{ optional($note->created_at)->diffForHumans() }}</span>
                                            </div>

                                            <p>{{ $note->body }}</p>

                                            @if(!empty($note->data['referrer_name']) || !empty($note->data['amount']))
                                                <div class="notification-meta">
                                                    @if(!empty($note->data['referrer_name']))
                                                        <span>
                                                            <i class="fa-solid fa-user-check"></i>
                                                            {{ $note->data['referrer_name'] }}
                                                        </span>
                                                    @endif

                                                    @if(!empty($note->data['amount']))
                                                        <span>
                                                            <i class="fa-solid fa-money-bill-wave"></i>
                                                            {{ $note->data['currency'] ?? '' }} {{ number_format($note->data['amount'], 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif

                                            @php
                                                $d = $note->data ?? [];
                                                $referrerRoute = ($d['referrer_type'] ?? 'personal') === 'business'
                                                    ? route('admin.business-account.find', $d['referrer_id'] ?? 0)
                                                    : route('admin.personal-account.find', $d['referrer_id'] ?? 0);
                                                $referredRoute = ($d['referred_type'] ?? 'personal') === 'business'
                                                    ? route('admin.business-account.find', $d['referred_id'] ?? 0)
                                                    : route('admin.personal-account.find', $d['referred_id'] ?? 0);
                                            @endphp

                                            <div class="d-flex gap-2 mt-2">
                                                <a href="{{ $referrerRoute }}#tab-activity" class="btn btn-soft-primary btn-sm">Fund Referrer</a>
                                                <a href="{{ $referredRoute }}#tab-activity" class="btn btn-outline-secondary btn-sm">Fund Referred</a>
                                                <form method="POST" action="{{ route('admin.referral-alerts.resolve', $note->id) }}" class="ms-auto">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link btn-sm text-muted p-0">Dismiss</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="notification-empty">
                                        <i class="fa-regular fa-bell-slash"></i>
                                        <strong>No notifications</strong>
                                        <span>You are all caught up.</span>
                                    </div>
                                @endforelse
                            </div>

                            <div class="notification-footer">
                                <a href="javascript:void(0);" class="btn btn-focus btn-sm btn-pill">
                                    View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>

                </div>


                @php
                    $admin = Auth::guard('admin')->user();
                @endphp

                <div class="header-btn-lg pe-0">
                    <div class="widget-content p-0">
                        <div class="widget-content-wrapper">
                            <div class="widget-content-left">
                                <div class="btn-group">
                                    <a data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn admin-avatar-trigger">
                                        @if($admin && $admin->profile_picture)
                                            <img src="{{ asset('storage/'.$admin->profile_picture) }}" alt="Admin Picture">
                                        @else
                                            <img src="{{ asset('assets/images/avatars/default.jpg') }}" alt="Default Admin">
                                        @endif
                                        <i class="fa fa-angle-down opacity-7"></i>
                                    </a>

                                    <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                        <div class="dropdown-menu-header">
                                            <div class="dropdown-menu-header-inner bg-info">
                                                <div class="menu-header-image opacity-2" style="background-image: url('{{ $admin && $admin->profile_picture ? asset('storage/'.$admin->profile_picture) : asset('assets/images/avatars/default.jpg') }}');"></div>
                                                <div class="menu-header-content text-start">
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left me-3">
                                                                @if($admin && $admin->profile_picture)
                                                                    <img width="46" class="rounded-circle" src="{{ asset('storage/'.$admin->profile_picture) }}" alt="Admin Picture">
                                                                @else
                                                                    <img width="46" class="rounded-circle" src="{{ asset('assets/images/avatars/default.jpg') }}" alt="Default Admin">
                                                                @endif
                                                            </div>

                                                            <div class="widget-content-left">
                                                                <div class="widget-heading">
                                                                    {{ $admin->name ?? 'Admin' }}
                                                                </div>
                                                                <div class="widget-subheading opacity-8">
                                                                    {{ $admin->role ?? 'No Role Assigned' }}
                                                                </div>
                                                            </div>

                                                            <div class="widget-content-right me-2">
                                                                <form id="admin-logout-form"
                                                                    action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                                                    @csrf
                                                                </form>

                                                                <button class="btn-pill btn-shadow btn-shine btn btn-focus"
                                                                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                                                    Logout
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider mb-0 nav-item"></li>
                                        </ul>

                                        <div class="grid-menu grid-menu-2col">
                                            <div class="g-0 row">
                                                <div class="col-sm-6">
                                                    <a href="{{ route('admin.profile') }}"
                                                    class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning"
                                                    style="display: block; text-align: center;">
                                                        <i class="fa-solid fa-user icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i>
                                                        Account
                                                    </a>
                                                </div>

                                                <div class="col-sm-6">
                                                    <a href="{{ route('admin.reset.password') }}"
                                                    class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger"
                                                    style="display: block; text-align: center;">
                                                        <i class="fa-solid fa-rotate btn-icon-wrapper mb-2"></i>
                                                        <b>Reset Password</b>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider nav-item"></li>
                                            <li class="nav-item-btn text-center nav-item">
                                                <button class="btn-wide btn btn-primary btn-sm">Open Messages</button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-content-left ms-3 header-user-info">
                                <div class="widget-heading">{{ $admin->name ?? 'Admin User' }}</div>
                                <div class="widget-subheading">{{ $admin->role ?? 'Administrator' }}</div>
                            </div>

                            <div class="widget-content-right header-user-info ms-3">
                                <button type="button" class="header-action-btn">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navSearch');
    const navItems = document.querySelectorAll('.vertical-nav-menu li');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();

        navItems.forEach(li => {
            const link = li.querySelector('a');
            if (link) {
                const text = link.textContent.toLowerCase();
                if (text.includes(filter)) {
                    li.style.display = 'block';
                    let parent = li.parentElement.closest('li');
                    if (parent) parent.style.display = 'block';
                } else {
                    li.style.display = 'none';
                }
            }
        });
    });
});

function closeSearch() {
    const searchInput = document.getElementById('navSearch');
    const navItems = document.querySelectorAll('.vertical-nav-menu li');

    searchInput.value = '';
    navItems.forEach(li => li.style.display = 'block');
}
</script>
