@include('admin.head')

<style>
    :root {
        --ink: #14213d;
        --ink-soft: #5b6475;
        --paper: #ffffff;
        --paper-soft: #f6f8fc;
        --line: #e7ecf3;
        --blue: #1d4ed8;
        --blue-deep: #0f2c73;
        --cyan: #0ea5e9;
        --green: #16a34a;
        --red: #dc2626;
        --amber: #d97706;
        --shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .personal-page {
        padding-bottom: 32px;
    }

    .personal-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(14,165,233,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }

    .personal-hero .card-body {
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
        letter-spacing: .02em;
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
        font-size: 1.2rem;
        font-weight: 800;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin: 24px 0;
    }

    .stat-box {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .stat-blue { background: rgba(29, 78, 216, 0.12); color: var(--blue); }
    .stat-green { background: rgba(22, 163, 74, 0.12); color: var(--green); }
    .stat-red { background: rgba(220, 38, 38, 0.12); color: var(--red); }
    .stat-amber { background: rgba(217, 119, 6, 0.12); color: var(--amber); }

    .stat-label {
        font-size: 13px;
        color: var(--ink-soft);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--ink);
        line-height: 1.1;
    }

    .directory-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--paper);
        box-shadow: var(--shadow);
    }

    .directory-head {
        padding: 24px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .directory-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .directory-subtitle {
        color: var(--ink-soft);
        margin-bottom: 0;
        font-size: 13px;
    }

    .search-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .search-input {
        min-width: 260px;
        height: 46px;
        border-radius: 15px !important;
        border: 1px solid #dbe3ee !important;
        box-shadow: none !important;
    }

    .search-input:focus {
        border-color: var(--blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(29, 78, 216, 0.10) !important;
    }

    .btn-brand,
    .btn-soft,
    .btn-action {
        border: 0;
        border-radius: 14px;
        font-weight: 700;
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--blue), var(--blue-deep));
        color: #fff;
        padding: 11px 16px;
    }

    .btn-soft {
        background: #eef3fa;
        color: #334155;
        padding: 11px 16px;
    }

    .btn-action {
        padding: 8px 11px;
    }

    .btn-view { background: rgba(29, 78, 216, 0.12); color: var(--blue); }
    .btn-edit { background: rgba(217, 119, 6, 0.12); color: var(--amber); }
    .btn-toggle { background: rgba(100, 116, 139, 0.12); color: #475569; }
    .btn-delete { background: rgba(220, 38, 38, 0.12); color: var(--red); }

    .personal-table thead th {
        background: #fbfcff;
        color: var(--ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--line);
        padding: 16px 18px;
        white-space: nowrap;
    }

    .personal-table tbody td {
        padding: 18px;
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .personal-table tbody tr:hover {
        background: #fbfdff;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid #edf3fb;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 800;
        color: var(--ink);
        line-height: 1.2;
    }

    .user-meta {
        font-size: 12px;
        color: var(--ink-soft);
        margin-top: 3px;
    }

    .status-chip {
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
        color: var(--green);
    }

    .status-inactive {
        background: rgba(220, 38, 38, 0.12);
        color: var(--red);
    }

    .actions {
        display: inline-flex;
        gap: 8px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .empty-row {
        padding: 48px 24px !important;
        text-align: center;
        color: var(--ink-soft);
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
                    <div class="personal-page">

                        <div class="card personal-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-user"></i>
                                            Personal Directory
                                        </div>
                                        <h1 class="hero-title">Personal Accounts</h1>
                                        <p class="hero-copy">
                                            Review and manage personal users from one place, including account status, balances, currency, and quick admin actions. Use search to find a user instantly.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Users</small>
                                                    <strong>{{ $allpersonal->total() ?? $allpersonal->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Showing</small>
                                                    <strong>{{ $allpersonal->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Current Filter</small>
                                                    <strong>{{ request('search') ?: 'All users' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="stats-row">
                            <div class="stat-box">
                                <div class="stat-icon stat-blue">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="stat-label">All Personal Users</div>
                                <div class="stat-value">{{ $allpersonal->total() ?? $allpersonal->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-green">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="stat-label">Active Users</div>
                                <div class="stat-value">{{ $allpersonal->where('deletestatus', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-red">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </div>
                                <div class="stat-label">Inactive Users</div>
                                <div class="stat-value">{{ $allpersonal->where('deletestatus', '!=', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-amber">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <div class="stat-label">Search Result</div>
                                <div class="stat-value">{{ $allpersonal->count() }}</div>
                            </div>
                        </div>

                        @if(session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: "{{ session('success') }}",
                                    confirmButtonColor: '#1d4ed8'
                                });
                            </script>
                        @endif

                        <div class="card directory-card">
                            <div class="directory-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h5 class="directory-title">Personal Users</h5>
                                    <p class="directory-subtitle">Search, review, and take quick action on personal accounts.</p>
                                </div>

                                <form method="GET" action="" class="search-wrap">
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="form-control search-input"
                                        placeholder="Search name, email, phone...">

                                    <button class="btn btn-brand">
                                        <i class="fa fa-search me-1"></i>
                                        Search
                                    </button>

                                    <a href="{{ url()->current() }}" class="btn btn-soft">
                                        Clear
                                    </a>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table personal-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="min-width:260px;">User</th>
                                            <th>Email</th>
                                            <th class="d-none d-md-table-cell">City</th>
                                            <th class="d-none d-lg-table-cell">Balance</th>
                                            <th class="d-none d-lg-table-cell">Currency</th>
                                            <th>Status</th>
                                            <th class="d-none d-lg-table-cell">Created</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($allpersonal as $user)
                                            <tr>
                                                <td>
                                                    <div class="user-cell">
                                                        <img
                                                            src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
                                                            class="user-avatar"
                                                            alt="User">

                                                        <div>
                                                            <div class="user-name">
                                                                {{ $user->firstname.' '.$user->lastname }}
                                                            </div>
                                                            <div class="user-meta">
                                                                {{ $user->person_phone ?? 'N/A' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>{{ $user->email ?? 'N/A' }}</td>

                                                <td class="d-none d-md-table-cell">{{ $user->city ?? 'N/A' }}</td>

                                                <td class="d-none d-lg-table-cell">{{ $user->balance ?? '0' }}</td>

                                                <td class="d-none d-lg-table-cell">{{ $user->currency ?? 'N/A' }}</td>

                                                <td>
                                                    @if($user->deletestatus == 'active')
                                                        <span class="status-chip status-active">Active</span>
                                                    @else
                                                        <span class="status-chip status-inactive">Inactive</span>
                                                    @endif
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    {{ $user->created_at ? $user->created_at->format('d M Y H:i') : 'N/A' }}
                                                </td>

                                                <td class="text-end">
                                                    <div class="actions">
                                                        <a href="{{ url('/admin/personal-account/'.$user->id) }}"
                                                           class="btn btn-action btn-view"
                                                           title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <a href="{{ url('/admin/personal-account/edit/'.$user->id) }}"
                                                           class="btn btn-action btn-edit"
                                                           title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>

                                                        <button
                                                            class="btn btn-action btn-toggle toggleStatus"
                                                            data-id="{{ $user->id }}"
                                                            title="Activate/Deactivate">
                                                            @if($user->deletestatus == 'active')
                                                                <i class="fa fa-ban"></i>
                                                            @else
                                                                <i class="fa fa-check"></i>
                                                            @endif
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-action btn-delete deleteUser"
                                                            data-id="{{ $user->id }}"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="empty-row">
                                                    No personal users found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-4 border-top">
                                {{ $allpersonal->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.toggleStatus').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.dataset.id;

            Swal.fire({
                title: 'Change user status?',
                text: "This will activate or deactivate the selected personal account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#1d4ed8'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/personal-account/deactivate/" + id;
                }
            });
        });
    });

    document.querySelectorAll('.deleteUser').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.dataset.id;

            Swal.fire({
                title: 'Delete user?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = "POST";
                    form.action = "/admin/personal-account/delete/" + id;

                    let csrf = document.createElement('input');
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    let method = document.createElement('input');
                    method.type = "hidden";
                    method.name = "_method";
                    method.value = "DELETE";

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>
