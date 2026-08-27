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

    .business-page {
        padding-bottom: 32px;
    }

    .business-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(14,165,233,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }

    .business-hero .card-body {
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

    .business-table thead th {
        background: #fbfcff;
        color: var(--ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--line);
        padding: 16px 18px;
        white-space: nowrap;
    }

    .business-table tbody td {
        padding: 18px;
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .business-table tbody tr:hover {
        background: #fbfdff;
    }

    .merchant-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .merchant-avatar {
        width: 46px;
        height: 46px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid #edf3fb;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        flex-shrink: 0;
    }

    .merchant-name {
        font-weight: 800;
        color: var(--ink);
        line-height: 1.2;
    }

    .merchant-meta {
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

    /* Broadcast Notification Styles */
    .notification-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--paper);
        box-shadow: var(--shadow);
    }

    .notification-head {
        padding: 24px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .notification-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .notification-subtitle {
        color: var(--ink-soft);
        margin-bottom: 0;
        font-size: 13px;
    }

    .channel-card {
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fbfdff;
    }

    .channel-card:hover {
        border-color: var(--blue);
        background: rgba(29, 78, 216, 0.02);
    }

    .channel-card.active {
        border-color: var(--blue);
        background: rgba(29, 78, 216, 0.05);
        box-shadow: 0 4px 12px rgba(29, 78, 216, 0.08);
    }

    .channel-card input[type="checkbox"] {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        accent-color: var(--blue);
        cursor: pointer;
    }

    .channel-name {
        font-weight: 700;
        color: var(--ink);
        font-size: 14px;
    }

    .channel-desc {
        font-size: 11px;
        color: var(--ink-soft);
    }

    .filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-search {
    position: relative;
    display: flex;
    align-items: center;
    min-width: 280px;
}

.filter-search-icon {
    position: absolute;
    left: 16px;
    font-size: 13px;
    color: var(--ink-soft);
    pointer-events: none;
}

.filter-search-input {
    width: 100%;
    height: 46px;
    border: 1px solid var(--line);
    border-radius: 15px;
    background: #fbfcff;
    padding: 0 40px 0 40px;
    font-size: 14px;
    color: var(--ink);
    transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
}

.filter-search-input::placeholder {
    color: var(--ink-soft);
}

.filter-search-input:focus {
    outline: none;
    border-color: var(--blue);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08);
}

.filter-search-clear {
    position: absolute;
    right: 12px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--ink-soft);
    font-size: 11px;
    background: #eef1f6;
    transition: all 0.2s ease;
}

.filter-search-clear:hover {
    background: var(--red);
    color: #fff;
}

.filter-select-wrap {
    position: relative;
    display: flex;
    align-items: center;
}

.filter-select {
    appearance: none;
    height: 46px;
    border: 1px solid var(--line);
    border-radius: 15px;
    background: #fbfcff;
    padding: 0 34px 0 16px;
    font-size: 13px;
    font-weight: 700;
    color: var(--ink);
    cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease;
}

.filter-select:hover {
    border-color: var(--blue);
    background: #fff;
}

.filter-select:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08);
}

.filter-select-icon {
    position: absolute;
    right: 14px;
    font-size: 10px;
    color: var(--ink-soft);
    pointer-events: none;
}

.filter-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    height: 46px;
    padding: 0 20px;
    border: 0;
    border-radius: 15px;
    background: linear-gradient(135deg, var(--blue), var(--blue-deep));
    color: #fff;
    font-size: 13.5px;
    font-weight: 700;
    box-shadow: 0 8px 18px rgba(29, 78, 216, 0.18);
    transition: opacity 0.2s ease, transform 0.15s ease;
}

.filter-submit:hover {
    opacity: 0.92;
}

.filter-submit:active {
    transform: scale(0.98);
}

@media (max-width: 767px) {
    .filter-search {
        min-width: 100%;
    }

    .filter-bar {
        width: 100%;
    }

    .filter-select-wrap,
    .filter-submit {
        flex: 1;
    }
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
                    <div class="business-page">

                        <div class="card business-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-building"></i>
                                            Merchant Directory
                                        </div>
                                        <h1 class="hero-title">Business Accounts</h1>
                                        <p class="hero-copy">
                                            Track and manage business users from one place, including account status, balances, location, and quick admin actions. Search by business name, email, or phone to move faster.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Users</small>
                                                    <strong>{{ $allUser->total() ?? $allUser->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Showing</small>
                                                    <strong>{{ $allUser->count() }}</strong>
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
                                <div class="stat-label">All Business Users</div>
                                <div class="stat-value">{{ $allUser->total() ?? $allUser->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-green">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="stat-label">Active Users</div>
                                <div class="stat-value">{{ $allUser->where('deletestatus', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-red">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </div>
                                <div class="stat-label">Inactive Users</div>
                                <div class="stat-value">{{ $allUser->where('deletestatus', '!=', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-amber">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <div class="stat-label">Search Result</div>
                                <div class="stat-value">{{ $allUser->count() }}</div>
                            </div>
                        </div>

                        <section class="mb-3 d-flex justify-content-end align-items-center flex-wrap gap-2">
                            <a href="{{ route('admin.broadcast.all') }}" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0; font-size: 13.5px; box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);">
                                <i class="fa-solid fa-tower-broadcast"></i> View All Broadcast Records
                                <i class="fa-solid fa-arrow-right ms-1"></i>
                            </a>
                        </section>

                        <section class="mb-4">
                            <div class="card notification-card">
                                <div class="notification-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                                    <div>
                                        <h5 class="notification-title"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Send Broadcast Notification</h5>
                                        <p class="notification-subtitle">Broadcast system alerts, updates, or email promotions to all registered business users.</p>
                                    </div>
                                    <a href="{{ route('admin.broadcast.all') }}" class="btn btn-outline-primary rounded-pill px-3 py-1.5 font-weight-bold d-inline-flex align-items-center gap-2" style="font-size: 13px;">
                                        <i class="fa-solid fa-clock-rotate-left"></i> Broadcast Logs
                                    </a>
                                </div>
                                <div class="card-body p-4">
                                    <form id="broadcastForm">
                                        <div class="row">
                                            <!-- Subject -->
                                            <div class="col mb-3">
                                                <label for="broadcastSubject" class="form-label font-weight-bold" style="color: var(--ink); font-weight: 700;">Notification Title / Subject</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-white" style="border-radius: 15px 0 0 15px; border-right: 0; border-color: #dbe3ee;"><i class="fa-solid fa-heading text-muted"></i></span>
                                                    <input type="text" id="broadcastSubject" class="form-control" placeholder="e.g., Scheduled Maintenance Update" style="border-radius: 0 15px 15px 0; border-left: 0; border-color: #dbe3ee; height: 46px;" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delivery Channels -->
                                        <div class="mb-4">
                                            <label class="form-label font-weight-bold" style="color: var(--ink); font-weight: 700;">Delivery Channels</label>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <div class="channel-card active" data-channel="inapp">
                                                        <input type="checkbox" id="channelInApp" checked>
                                                        <div class="ms-2">
                                                            <div class="channel-name"><i class="fa-solid fa-bell text-primary me-1"></i> In-App Notification</div>
                                                            <div class="channel-desc">Appears in user dashboard and alerts feed</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="channel-card" data-channel="email">
                                                        <input type="checkbox" id="channelEmail">
                                                        <div class="ms-2">
                                                            <div class="channel-name"><i class="fa-solid fa-envelope text-primary me-1"></i> Email Broadcast</div>
                                                            <div class="channel-desc">Sends direct email to registered address</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Message Content -->
                                        <div class="mb-3">
                                            <label for="broadcastMessage" class="form-label font-weight-bold" style="color: var(--ink); font-weight: 700;">Message Content</label>
                                            <textarea id="broadcastMessage" class="form-control" rows="4" placeholder="Type your broadcast message here..." style="border-radius: 15px; border-color: #dbe3ee;" required></textarea>
                                        </div>

                                        <!-- Submit button and stats -->
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                            <div class="text-muted small"></div>
                                            <button type="submit" class="btn btn-brand px-4 py-2" style="font-size: 15px; border-radius: 15px;">
                                                <i class="fa-solid fa-paper-plane me-2"></i>Send Broadcast Now
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

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
                                    <h5 class="directory-title">Business Users</h5>
                                    <p class="directory-subtitle">Review merchant records and take action directly from the table.</p>
                                </div>

                                <form method="GET" action="" class="filter-bar">
                                    <div class="filter-search">
                                        <i class="fa fa-magnifying-glass filter-search-icon"></i>
                                        <input
                                            type="text"
                                            name="search"
                                            value="{{ request('search') }}"
                                            class="filter-search-input"
                                            placeholder="Search business, email, phone...">
                                        @if(request('search'))
                                            <a href="{{ url()->current() }}?per_page={{ $perPage }}" class="filter-search-clear" title="Clear search">
                                                <i class="fa fa-xmark"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="filter-select-wrap">
                                        <select name="per_page" class="filter-select" onchange="this.form.submit()">
                                            @foreach($allowedPerPage as $option)
                                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                                    {{ $option }} / page
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fa fa-chevron-down filter-select-icon"></i>
                                    </div>

                                    <button type="submit" class="filter-submit">
                                        <i class="fa fa-magnifying-glass"></i>
                                        <span>Search</span>
                                    </button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table business-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th style="min-width:260px;">Merchant</th>
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
                                        @forelse ($allUser as $user)
                                            <tr>
                                                <td>
                                                    <div class="merchant-cell">
                                                        <img
                                                            src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
                                                            class="merchant-avatar"
                                                            alt="User">

                                                        <div>
                                                            <div class="merchant-name">
                                                                {{ $user->business_name ?? $user->firstname.' '.$user->lastname }}
                                                            </div>
                                                            <div class="merchant-meta">
                                                                {{ $user->business_phone ?? 'N/A' }}
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
                                                        <a href="{{ url('/admin/business-account/'.$user->id) }}"
                                                           class="btn btn-action btn-view"
                                                           title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <a href="{{ url('/admin/business-account/edit/'.$user->id) }}"
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
                                                    No business users found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-4 border-top">
                                {{ $allUser->links('pagination::bootstrap-5') }}
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
                text: "This will activate or deactivate the selected business account.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#1d4ed8'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/admin/business-account/deactivate/" + id;
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
                    form.action = "/admin/business-account/delete/" + id;

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

    // Target Audience Count Dynamic Update
    const targetSelect = document.getElementById('broadcastTarget');
    if (targetSelect) {
        targetSelect.addEventListener('change', function() {
            const val = this.value;
            const targetCountShow = document.getElementById('targetCountShow');
            if (val === 'all') {
                targetCountShow.innerText = "{{ $allUser->total() ?? $allUser->count() }} recipients";
            } else if (val === 'active') {
                targetCountShow.innerText = "{{ $allUser->where('deletestatus', 'active')->count() }} recipients";
            } else if (val === 'inactive') {
                targetCountShow.innerText = "{{ $allUser->where('deletestatus', '!=', 'active')->count() }} recipients";
            }
        });
    }

    // Channel Card Toggling
    document.querySelectorAll('.channel-card').forEach(card => {
        card.addEventListener('click', function (e) {
            const checkbox = this.querySelector('input[type="checkbox"]');
            if (e.target !== checkbox) {
                checkbox.checked = !checkbox.checked;
            }
            if (checkbox.checked) {
                this.classList.add('active');
            } else {
                this.classList.remove('active');
            }
        });
    });

    // Handle Broadcast Submit
    const broadcastForm = document.getElementById('broadcastForm');
    if (broadcastForm) {
        broadcastForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const subject = document.getElementById('broadcastSubject').value;
            const message = document.getElementById('broadcastMessage').value;
            const inApp = document.getElementById('channelInApp').checked;
            const email = document.getElementById('channelEmail').checked;

            // Validate Subject
            if (subject === "") {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Notification title is required.',
                    showConfirmButton: false,
                    timer: 3000
                });

                return;
            }

            // Validate Message
            if (message === "") {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Notification message is required.',
                    showConfirmButton: false,
                    timer: 3000
                });

                return;
            }

            if (!inApp && !email) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please choose at least one delivery channel (In-App or Email).',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                return;
            }

            // Build Channels Array
            const channels = [];

            if (inApp) channels.push('inapp');
            if (email) channels.push('email');

            Swal.fire({
                title: 'Confirm Broadcast',
                html: `Are you sure you want to send this notification?<br><br>` +
                        `<div class="text-start small" style="background: #f1f5f9; padding: 12px; border-radius: 8px;">` +
                        `<strong>Target:</strong> All Business Users<br>` +
                        `<strong>Subject:</strong> ${subject}<br>` +
                        `<strong>Channels:</strong> ${channels.join(', ')}` +
                        `</div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Send',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#1d4ed8'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sending Notification...',
                        text: 'Broadcasting messages to recipients.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch("/admin/business-pushnotification", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            subject: subject,
                            message: message,
                            channels: channels
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok || data.success === false) {
                            throw data;
                        }
                        return data;
                    })
                    .then(data => {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: data.message,
                            confirmButtonColor: "#1d4ed8"
                        });
                        // Reset Form
                        broadcastForm.reset();
                        
                        // Default InApp Selected
                        document.querySelectorAll(".channel-card").forEach(card => {
                            const checkbox = card.querySelector("input");
                            if (checkbox.id === "channelInApp") {
                                checkbox.checked = true;
                                card.classList.add("active");
                            } else {
                                checkbox.checked = false;
                                card.classList.remove("active");
                            }
                        });
                    })
                    .catch(error => {
                        let message = "Something went wrong.";
                        if (error.message) {
                            message = error.message;
                        }
                        if (error.errors) {
                            message = Object.values(error.errors)
                                .flat()
                                .join("\n");
                        }

                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: message
                        });
                    });
                }
            });
        });
    }
});
</script>
