@include('admin.head')

<style>
    :root {
        --admin-ink: #14213d;
        --admin-ink-soft: #64748b;
        --admin-paper: #ffffff;
        --admin-paper-soft: #f8fafc;
        --admin-line: #e2e8f0;
        --admin-blue: #2563eb;
        --admin-cyan: #0891b2;
        --admin-green: #16a34a;
        --admin-red: #dc2626;
        --admin-amber: #d97706;
        --admin-shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    #toast-container {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
        display: flex !important;
        flex-direction: column !important;
        gap: 10px;
        z-index: 999999 !important;
    }

    .toast {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        min-width: 280px !important;
        max-width: 350px;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13px;
        color: white;
        font-weight: 600;
        box-shadow: 0px 10px 24px rgba(0, 0, 0, 0.18);
        position: relative;
        animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 3.5s forwards;
        opacity: 1;
    }

    .toast.success {
        background: linear-gradient(135deg, #16a34a, #10b981);
    }

    .toast.error {
        background: linear-gradient(135deg, #dc2626, #f87171);
    }

    .toast .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(255, 255, 255, 0.7);
        width: 100%;
        animation: progressBar 3s linear forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-20px); }
    }

    @keyframes progressBar {
        from { width: 100%; }
        to { width: 0%; }
    }

    .admin-directory-page {
        padding-bottom: 32px;
    }

    .admin-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(8,145,178,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
        margin-bottom: 24px;
    }

    .admin-hero .card-body {
        padding: 34px;
    }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .hero-title {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin: 14px 0 10px;
    }

    .hero-copy {
        color: rgba(255,255,255,0.82);
        max-width: 760px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .hero-metric {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 16px 18px;
        color: #fff;
        height: 100%;
    }

    .hero-metric small {
        display: block;
        color: rgba(255,255,255,0.70);
        margin-bottom: 6px;
    }

    .hero-metric strong {
        font-size: 1.15rem;
        font-weight: 800;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: var(--admin-paper);
        border: 1px solid var(--admin-line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--admin-shadow);
    }

    .summary-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: var(--admin-blue); }
    .icon-green { background: rgba(22, 163, 74, 0.12); color: var(--admin-green); }
    .icon-red { background: rgba(220, 38, 38, 0.12); color: var(--admin-red); }
    .icon-amber { background: rgba(217, 119, 6, 0.12); color: var(--admin-amber); }

    .summary-label {
        font-size: 13px;
        color: var(--admin-ink-soft);
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--admin-ink);
        line-height: 1.1;
    }

    .admin-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--admin-paper);
        box-shadow: var(--admin-shadow);
        transition: transform .2s ease, box-shadow .2s ease;
        height: 100%;
    }

    .admin-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.10);
    }

    .admin-card-top {
        position: relative;
        padding: 24px;
        background:
            linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02)),
            linear-gradient(135deg, #0f172a, #1d4ed8 65%, #0891b2);
        color: #fff;
        min-height: 170px;
    }

    .admin-card-top::after {
        content: "";
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 999px;
        background: rgba(255,255,255,0.10);
        top: -30px;
        right: -30px;
    }

    .admin-avatar {
        width: 72px;
        height: 72px;
        border-radius: 22px;
        object-fit: cover;
        border: 4px solid rgba(255,255,255,0.16);
        box-shadow: 0 12px 24px rgba(0,0,0,0.16);
        background: rgba(255,255,255,0.08);
    }

    .admin-role-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        background: rgba(255,255,255,0.14);
        color: #fff;
        margin-top: 10px;
    }

    .admin-card-body {
        padding: 22px;
    }

    .admin-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-active {
        background: rgba(22, 163, 74, 0.12);
        color: var(--admin-green);
    }

    .status-locked {
        background: rgba(220, 38, 38, 0.12);
        color: var(--admin-red);
    }

    .admin-meta {
        color: var(--admin-ink-soft);
        font-size: 13px;
        line-height: 1.7;
        margin: 14px 0 18px;
    }

    .admin-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .admin-action-btn {
        border: 0;
        border-radius: 16px;
        padding: 14px 12px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        transition: all .2s ease;
        display: block;
        text-decoration: none;
    }

    .admin-action-btn:hover {
        transform: translateY(-1px);
    }

    .btn-view {
        background: rgba(37, 99, 235, 0.12);
        color: var(--admin-blue);
    }

    .btn-activity {
        background: rgba(15, 23, 42, 0.08);
        color: #334155;
    }

    .btn-settings {
        background: rgba(22, 163, 74, 0.12);
        color: var(--admin-green);
    }

    .btn-edit {
        background: rgba(217, 119, 6, 0.12);
        color: var(--admin-amber);
    }

    .unlock-box {
        margin-top: 14px;
        padding: 14px;
        border-radius: 18px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
    }

    .unlock-btn {
        border: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: #fff;
        padding: 10px 14px;
        font-weight: 700;
        width: 100%;
    }
</style>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            <div class="app-main__outer">
                <div class="app-main__inner">

                    <div id="toast-container"></div>

                    <div class="admin-directory-page">

                        <div class="card admin-hero">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-users"></i>
                                            Team Directory
                                        </div>
                                        <h1 class="hero-title">All Admin</h1>
                                        <p class="hero-copy">
                                            Review every administrator in one place, monitor active and locked accounts, and jump quickly into activity, profile, settings, or account management actions.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Admins</small>
                                                    <strong>{{ $admins->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Locked</small>
                                                    <strong>{{ $admins->filter(fn($a) => $a->locked_until && $a->locked_until->isFuture())->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Active</small>
                                                    <strong>{{ $admins->filter(fn($a) => !($a->locked_until && $a->locked_until->isFuture()))->count() }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-grid">
                            <div class="summary-card">
                                <div class="summary-icon icon-blue">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="summary-label">All Admins</div>
                                <div class="summary-value">{{ $admins->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-green">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <div class="summary-label">Active Admins</div>
                                <div class="summary-value">{{ $admins->filter(fn($a) => !($a->locked_until && $a->locked_until->isFuture()))->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-red">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <div class="summary-label">Locked Admins</div>
                                <div class="summary-value">{{ $admins->filter(fn($a) => $a->locked_until && $a->locked_until->isFuture())->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-amber">
                                    <i class="fa-solid fa-tools"></i>
                                </div>
                                <div class="summary-label">Roles In Use</div>
                                <div class="summary-value">{{ $admins->pluck('role')->filter()->unique()->count() }}</div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($admins as $admin)
                                <div class="col-sm-12 col-lg-6 col-xl-4 mb-4">
                                    <div class="admin-card">

                                        <div class="admin-card-top">
                                            <div class="d-flex align-items-center gap-3">
                                                <img
                                                    src="{{ $admin->profile_picture ? asset('storage/'.$admin->profile_picture) : asset('assets/images/avatars/default.png') }}"
                                                    alt="Profile"
                                                    class="admin-avatar">

                                                <div>
                                                    <h4 class="mb-1 text-white">{{ $admin->name }}</h4>
                                                    <div class="text-white-50 small">{{ $admin->email ?? 'No email available' }}</div>
                                                    <div class="admin-role-pill">{{ $admin->role ?? 'Admin' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="admin-card-body">
                                            @if($admin->locked_until && $admin->locked_until->isFuture())
                                                <span class="admin-status status-locked">Locked Admin</span>
                                            @else
                                                <span class="admin-status status-active">Active Admin</span>
                                            @endif

                                            <div class="admin-meta">
                                                Manage this administrator’s profile, inspect recent activity, adjust settings, or update account access from the options below.
                                            </div>

                                            @if($admin->locked_until && $admin->locked_until->isFuture())
                                                <div class="unlock-box">
                                                    <form action="{{ route('admin.unlock', $admin->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="unlock-btn">Unlock Now</button>
                                                    </form>
                                                </div>
                                            @endif

                                            <div class="admin-actions mt-3">
                                                <a href="{{ route('admin.view', $admin->id) }}" class="admin-action-btn btn-view">
                                                    View Profile
                                                </a>

                                                <a href="{{ route('admin.activity', $admin->id) }}" class="admin-action-btn btn-activity">
                                                    Activity
                                                </a>

                                                <a href="{{ route('admin.settings', $admin->id) }}" class="admin-action-btn btn-settings">
                                                    Settings
                                                </a>

                                                <a href="" class="admin-action-btn btn-edit">
                                                    Edit
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function showToast(message, type = "success") {
        var toastContainer = document.getElementById("toast-container");
        if (!toastContainer) return;

        var toast = document.createElement("div");
        toast.className = "toast " + type;
        toast.textContent = message;

        var progress = document.createElement("div");
        progress.className = "toast-progress";

        toast.appendChild(progress);
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showToast("{{ $error }}", "error");
        @endforeach
    @endif

    @if (session('success'))
        showToast("{{ session('success') }}", "success");
    @endif

    @if (session('error'))
        showToast("{{ session('error') }}", "error");
    @endif
});
</script>

@include('admin.footer')
