@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-blue: #2563eb;
        --brand-sky: #38bdf8;
        --brand-green: #16a34a;
        --brand-orange: #f59e0b;
        --brand-red: #dc2626;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --border-soft: #e2e8f0;
        --shadow-soft: 0 18px 45px rgba(15, 23, 42, 0.08);
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
        border-radius: 10px;
        font-size: 13px;
        color: white;
        font-weight: 500;
        box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.18);
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

    .settings-page {
        padding-bottom: 30px;
    }

    .hero-panel {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.12), transparent 24%),
            radial-gradient(circle at bottom left, rgba(56,189,248,0.16), transparent 26%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: 0 24px 60px rgba(37, 99, 235, 0.18);
    }

    .hero-panel .card-body {
        padding: 32px;
    }

    .admin-avatar-xxl {
        width: 92px;
        height: 92px;
        border-radius: 24px;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.16);
        box-shadow: 0 10px 24px rgba(0,0,0,0.18);
        background: rgba(255,255,255,0.08);
    }

    .admin-avatar-xxl img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .hero-stat {
        border-radius: 20px;
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.10);
        padding: 16px 18px;
        color: #fff;
        height: 100%;
    }

    .hero-stat small {
        color: rgba(255,255,255,0.70);
        display: block;
        margin-bottom: 6px;
    }

    .info-card,
    .action-card,
    .danger-card {
        border: 0;
        border-radius: 22px;
        background: var(--surface);
        box-shadow: var(--shadow-soft);
    }

    .info-card .card-body,
    .action-card .card-body,
    .danger-card .card-body {
        padding: 24px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 18px;
    }

    .meta-grid {
        display: grid;
        gap: 14px;
    }

    .meta-item {
        border: 1px solid var(--border-soft);
        background: var(--surface-soft);
        border-radius: 16px;
        padding: 14px 16px;
    }

    .meta-item span {
        display: block;
        font-size: 12px;
        color: var(--text-soft);
        margin-bottom: 5px;
    }

    .meta-item strong {
        color: var(--text-main);
        font-size: 14px;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 999px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-chip.active {
        background: rgba(22, 163, 74, 0.12);
        color: var(--brand-green);
    }

    .status-chip.locked {
        background: rgba(220, 38, 38, 0.12);
        color: var(--brand-red);
    }

    .status-chip.inactive {
        background: rgba(245, 158, 11, 0.12);
        color: var(--brand-orange);
    }

    .action-card {
        transition: 0.25s ease;
        height: 100%;
    }

    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 50px rgba(15, 23, 42, 0.12);
    }

    .action-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
    .icon-yellow { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .icon-gray { background: rgba(100, 116, 139, 0.12); color: #475569; }
    .icon-cyan { background: rgba(6, 182, 212, 0.12); color: #0891b2; }
    .icon-red { background: rgba(220, 38, 38, 0.12); color: #dc2626; }

    .action-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .action-copy {
        color: var(--text-soft);
        font-size: 13px;
        line-height: 1.75;
        min-height: 65px;
        margin-bottom: 18px;
    }

    .custom-control-field {
        height: 48px !important;
        border-radius: 14px !important;
        border: 1px solid #dbe3ee !important;
        box-shadow: none !important;
    }

    .custom-control-field:focus {
        border-color: var(--brand-blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10) !important;
    }

    .btn-soft-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: 0;
        color: #fff;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 600;
    }

    .btn-soft-primary:hover {
        color: #fff;
        opacity: 0.95;
    }

    .btn-soft-dark,
    .btn-soft-warning,
    .btn-soft-secondary,
    .btn-soft-info,
    .btn-soft-danger {
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 600;
        border: 0;
    }

    .btn-soft-dark { background: #111827; color: #fff; }
    .btn-soft-warning { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .btn-soft-secondary { background: linear-gradient(135deg, #64748b, #475569); color: #fff; }
    .btn-soft-info { background: linear-gradient(135deg, #06b6d4, #0891b2); color: #fff; }
    .btn-soft-danger { background: linear-gradient(135deg, #dc2626, #b91c1c); color: #fff; }

    .btn-soft-dark:hover,
    .btn-soft-warning:hover,
    .btn-soft-secondary:hover,
    .btn-soft-info:hover,
    .btn-soft-danger:hover {
        color: #fff;
        opacity: 0.95;
    }

    .danger-card {
        background: linear-gradient(180deg, #fff, #fff7f7);
        border: 1px solid rgba(220, 38, 38, 0.14);
    }

    .modal-content {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .modal-header {
        border-bottom: 1px solid #eef2f7;
    }

    .modal-footer {
        border-top: 1px solid #eef2f7;
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

                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="pe-7s-settings icon-gradient bg-ripe-malin"></i>
                            </div>
                            <div>
                                Admin Setting
                                <div class="page-title-subheading">
                                    Manage roles, security controls, password recovery, and critical account actions.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="toast-container"></div>

                <div class="settings-page">
                    <div class="card hero-panel mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                                        <div class="admin-avatar-xxl">
                                            <img src="{{ $admin->profile_picture ? asset('storage/'.$admin->profile_picture) : asset('assets/images/avatars/default.png') }}" alt="Profile">
                                        </div>

                                        <div class="text-white">
                                            <div class="hero-pill mb-2">
                                                <i class="pe-7s-id"></i>
                                                {{ ucfirst($admin->role ?? 'admin') }}
                                            </div>
                                            <h2 class="mb-1 text-white">{{ $admin->name }}</h2>
                                            <p class="mb-0 text-white-50">{{ $admin->email ?? 'No email available' }}</p>
                                        </div>
                                    </div>

                                    <p class="text-white-50 mb-0" style="max-width: 680px; line-height: 1.8;">
                                        Use this control panel to manage this administrator’s account privileges, access level, and security status with stronger protection for sensitive actions.
                                    </p>
                                </div>

                                <div class="col-lg-4 mt-4 mt-lg-0">
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="hero-stat">
                                                <small>Status</small>
                                                @if(isset($admin->status) && $admin->status === 'inactive')
                                                    <span class="status-chip inactive">Inactive</span>
                                                @elseif($admin->locked_until && $admin->locked_until->isFuture())
                                                    <span class="status-chip locked">Locked</span>
                                                @else
                                                    <span class="status-chip active">Active</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="hero-stat">
                                                <small>Admin ID</small>
                                                <strong>#{{ $admin->id }}</strong>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="hero-stat">
                                                <small>Locked Until</small>
                                                <strong>
                                                    @if($admin->locked_until && $admin->locked_until->isFuture())
                                                        {{ $admin->locked_until->format('d M Y, h:i A') }}
                                                    @else
                                                        Not locked
                                                    @endif
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-8">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card action-card">
                                        <div class="card-body">
                                            <div class="action-icon icon-blue">
                                                <i class="pe-7s-users"></i>
                                            </div>
                                            <h5 class="action-title">Change Role</h5>
                                            <p class="action-copy">
                                                Promote or update this admin’s permission level and redefine platform-wide administrative access.
                                            </p>

                                            <form action="{{ route('admin.changeRole', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Change Admin Role" data-message="Are you sure you want to change this admin's role?" data-confirm="Yes, Update Role">
                                                @csrf
                                                <div class="mb-3">
                                                    <select name="role" class="form-control custom-control-field role-select" required>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->name }}" {{ $admin->role == $role->name ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-soft-primary btn-block">Update Role</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card action-card">
                                        <div class="card-body">
                                            <div class="action-icon icon-yellow">
                                                <i class="pe-7s-lock"></i>
                                            </div>
                                            <h5 class="action-title">Lock or Unlock</h5>
                                            <p class="action-copy">
                                                Temporarily prevent login access or restore the account when the security review is complete.
                                            </p>

                                            @if($admin->locked_until && $admin->locked_until->isFuture())
                                                <form action="{{ route('admin.unlock', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Unlock Admin Account" data-message="Are you sure you want to unlock {{ $admin->name }}? This will restore login access." data-confirm="Yes, Unlock Admin">
                                                    @csrf
                                                    <button type="submit" class="btn btn-soft-warning btn-block">Unlock Admin</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.lock', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Lock Admin Account" data-message="Are you sure you want to lock {{ $admin->name }}? This will block login access." data-confirm="Yes, Lock Admin">
                                                    @csrf
                                                    <button type="submit" class="btn btn-soft-dark btn-block">Lock Admin</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card action-card">
                                        <div class="card-body">
                                            <div class="action-icon icon-gray">
                                                <i class="pe-7s-power"></i>
                                            </div>
                                            <h5 class="action-title">Deactivate Account</h5>
                                            <p class="action-copy">
                                                Mark this administrator as inactive without permanently removing their profile from the system.
                                            </p>

                                            <form action="{{ route('admin.deactivate', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Deactivate Admin" data-message="Are you sure you want to deactivate {{ $admin->name }}? The account will become inactive." data-confirm="Yes, Deactivate">
                                                @csrf
                                                <button type="submit" class="btn btn-soft-secondary btn-block">Deactivate Admin</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card action-card">
                                        <div class="card-body">
                                            <div class="action-icon icon-cyan">
                                                <i class="pe-7s-refresh-2"></i>
                                            </div>
                                            <h5 class="action-title">Reset Password</h5>
                                            <p class="action-copy">
                                                Issue a fresh password for this account and restore access through a controlled reset process.
                                            </p>

                                            <form action="{{ route('admin.resetPassword', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Reset Password" data-message="Are you sure you want to reset the password for {{ $admin->name }}?" data-confirm="Yes, Reset Password">
                                                @csrf
                                                <button type="submit" class="btn btn-soft-info btn-block">Reset Password</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card danger-card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-lg-8">
                                                    <div class="action-icon icon-red">
                                                        <i class="pe-7s-trash"></i>
                                                    </div>
                                                    <h5 class="action-title text-danger">Delete Admin Permanently</h5>
                                                    <p class="action-copy mb-lg-0">
                                                        This removes the admin account from the system permanently. This action should only be used when absolutely necessary.
                                                    </p>
                                                </div>
                                                <div class="col-lg-4 mt-3 mt-lg-0">
                                                    <form action="{{ route('admin.delete', $admin->id) }}" method="POST" class="confirm-action-form" data-title="Delete Admin" data-message="Are you sure you want to permanently delete {{ $admin->name }}? This action cannot be undone." data-confirm="Yes, Delete Admin">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-soft-danger btn-block">Delete Admin</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4">
                            <div class="card info-card mb-4">
                                <div class="card-body">
                                    <h5 class="section-title">Admin Summary</h5>

                                    <div class="meta-grid">
                                        <div class="meta-item">
                                            <span>Full Name</span>
                                            <strong>{{ $admin->name }}</strong>
                                        </div>

                                        <div class="meta-item">
                                            <span>Email Address</span>
                                            <strong>{{ $admin->email ?? 'No email available' }}</strong>
                                        </div>

                                        <div class="meta-item">
                                            <span>Current Role</span>
                                            <strong>{{ ucfirst($admin->role ?? 'admin') }}</strong>
                                        </div>

                                        <div class="meta-item">
                                            <span>Current Access State</span>
                                            <strong>
                                                @if(isset($admin->status) && $admin->status === 'inactive')
                                                    Inactive
                                                @elseif($admin->locked_until && $admin->locked_until->isFuture())
                                                    Locked
                                                @else
                                                    Active
                                                @endif
                                            </strong>
                                        </div>

                                        <div class="meta-item">
                                            <span>Account ID</span>
                                            <strong>#{{ $admin->id }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card info-card">
                                <div class="card-body">
                                    <h5 class="section-title">Security Notes</h5>
                                    <div class="meta-grid">
                                        <div class="meta-item">
                                            <span>Password Reset</span>
                                            <strong>Only perform when identity is verified.</strong>
                                        </div>
                                        <div class="meta-item">
                                            <span>Role Changes</span>
                                            <strong>Promotions should be limited to trusted personnel.</strong>
                                        </div>
                                        <div class="meta-item">
                                            <span>Deletion</span>
                                            <strong>Deletion is permanent and should require confirmation.</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="actionConfirmModal" tabindex="-1" aria-labelledby="actionConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="actionConfirmModalLabel" class="modal-title">Confirm Action</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="actionConfirmMessage" class="mb-0">Are you sure you want to continue?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmActionBtn" class="btn btn-soft-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let pendingForm = null;
    const modalEl = document.getElementById('actionConfirmModal');
    const confirmBtn = document.getElementById('confirmActionBtn');
    const modalTitle = document.getElementById('actionConfirmModalLabel');
    const modalMessage = document.getElementById('actionConfirmMessage');
    const modalInstance = new bootstrap.Modal(modalEl);

    function showToast(message, type = "success") {
        const toastContainer = document.getElementById("toast-container");
        if (!toastContainer) return;

        const toast = document.createElement("div");
        toast.className = "toast " + type;
        toast.textContent = message;

        const progress = document.createElement("div");
        progress.className = "toast-progress";

        toast.appendChild(progress);
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }

    document.querySelectorAll('.confirm-action-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            pendingForm = this;

            const title = this.dataset.title || 'Confirm Action';
            let message = this.dataset.message || 'Are you sure you want to continue?';
            const confirmText = this.dataset.confirm || 'Confirm';

            const roleSelect = this.querySelector('.role-select');
            if (roleSelect) {
                const selectedRole = roleSelect.options[roleSelect.selectedIndex].text;
                message = `Are you sure you want to change this admin's role to "${selectedRole}"?`;
            }

            modalTitle.textContent = title;
            modalMessage.textContent = message;
            confirmBtn.textContent = confirmText;

            modalInstance.show();
        });
    });

    confirmBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        modalInstance.hide();
        pendingForm.submit();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        pendingForm = null;
    });

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
