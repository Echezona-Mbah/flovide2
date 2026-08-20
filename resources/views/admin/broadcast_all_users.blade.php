@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-deep: #1d4ed8;
        --brand-sky: #0ea5e9;
        --blue: #1d4ed8;
        --blue-deep: #0f2c73;
        --paper: #ffffff;
        --paper-soft: #f8fafc;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --border-soft: #e2e8f0;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
        --success-soft: rgba(22, 163, 74, 0.12);
        --warning-soft: rgba(245, 158, 11, 0.14);
        --danger-soft: rgba(220, 38, 38, 0.12);
        --ink: #0f172a;
        --ink-soft: #475569;
        --line: #e2e8f0;
    }

    .dashboard-shell {
        padding-bottom: 32px;
    }

    .broadcast-hero {
        border: 0;
        border-radius: 30px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.14), transparent 24%),
            radial-gradient(circle at bottom left, rgba(56,189,248,0.12), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: 0 28px 65px rgba(29, 78, 216, 0.18);
    }

    .broadcast-hero .card-body {
        padding: 32px;
    }

    .hero-icon-box {
        width: 78px;
        height: 78px;
        border-radius: 22px;
        background: rgba(255,255,255,0.14);
        border: 2px solid rgba(255,255,255,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #fff;
        backdrop-filter: blur(6px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.14);
        flex-shrink: 0;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        backdrop-filter: blur(4px);
    }

    .hero-mini {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 18px;
        padding: 14px 16px;
        color: #fff;
        height: 100%;
        backdrop-filter: blur(4px);
    }

    .hero-mini small {
        display: block;
        margin-bottom: 4px;
        color: rgba(255,255,255,0.72);
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .hero-mini strong {
        font-size: 15px;
        font-weight: 700;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border-soft);
        border-radius: 20px;
        padding: 18px 20px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 20px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.1); color: #2563eb; }
    .icon-green { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
    .icon-orange { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .icon-red { background: rgba(220, 38, 38, 0.1); color: #dc2626; }

    .stat-label {
        font-size: 12.5px;
        color: var(--text-soft);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.02em;
    }

    .dashboard-card {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: var(--surface);
        box-shadow: var(--shadow-soft);
        margin-bottom: 24px;
    }

    .dashboard-card .card-header {
        border: 0;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 20px 24px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 3px;
    }

    .section-subtitle {
        font-size: 13px;
        color: var(--text-soft);
        margin-bottom: 0;
    }

    .table-modern thead th {
        border-bottom: 1px solid var(--border-soft);
        color: var(--text-soft);
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        background: #f8fafc;
    }

    .table-modern tbody tr {
        border-top: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fbff;
    }

    .table-modern td {
        padding: 16px;
        vertical-align: middle;
    }

    .custom-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .status-sent {
        background: rgba(22, 163, 74, 0.12);
        color: #15803d;
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .status-pending {
        background: rgba(245, 158, 11, 0.12);
        color: #b45309;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-failed {
        background: rgba(220, 38, 38, 0.12);
        color: #b91c1c;
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .filter-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12.5px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .filter-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .filter-pill.active {
        background: var(--blue);
        color: #fff;
    }

    .channel-card {
        border: 1.5px solid var(--border-soft);
        border-radius: 16px;
        padding: 14px 16px;
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
        font-size: 13.5px;
    }

    .channel-desc {
        font-size: 11px;
        color: var(--ink-soft);
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border: 0;
        border-radius: 12px;
        padding: 9px 18px;
        font-weight: 600;
        font-size: 13.5px;
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-gradient-primary:hover {
        color: #fff;
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.32);
    }

    .btn-soft {
        background: #f1f5f9;
        border: 1px solid var(--border-soft);
        color: #334155;
        border-radius: 10px;
        padding: 6px 12px;
        font-size: 12.5px;
        font-weight: 600;
        transition: all 0.15s ease;
    }

    .btn-soft:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .empty-state-box {
        text-align: center;
        padding: 50px 20px;
        background: #fafcff;
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
        margin: 20px;
    }

    .user-badge-link {
        color: #1d4ed8;
        font-weight: 700;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .user-badge-link:hover {
        color: #0f2c73;
        text-decoration: underline;
    }

    .modal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    .swal2-container {
        z-index: 10000 !important;
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
                    <div class="dashboard-shell">

                        <!-- Hero Banner -->
                        <div class="card broadcast-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                                            <div class="hero-icon-box">
                                                <i class="fa-solid fa-tower-broadcast"></i>
                                            </div>

                                            <div class="text-white">
                                                <div class="hero-pill mb-2">
                                                    <i class="fa-solid fa-bullhorn"></i>
                                                    Global Communications Center
                                                </div>
                                                <h2 class="mb-1 text-white font-weight-bold">All Users Broadcast & Email Records</h2>
                                                <p class="mb-0 text-white-50"><i class="fa-solid fa-chart-line me-1"></i> Centralized delivery history, audit logs, and notification analytics</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 700px; line-height: 1.6; font-size: 13.5px;">
                                            Audit and trace all outgoing email broadcasts and push notifications dispatched to business accounts. Track delivery rates, failure diagnostics, and dispatch direct announcements.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Total Logs</small>
                                                    <strong>{{ number_format($totalCount) }} Dispatched</strong>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Delivery Rate</small>
                                                    <strong>
                                                        @if($totalCount > 0)
                                                            {{ round(($sentCount / $totalCount) * 100, 1) }}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="hero-mini d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <small>Global Action</small>
                                                        <strong>Broadcast to All</strong>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 py-1 font-weight-bold" data-bs-toggle="modal" data-bs-target="#newBroadcastAllModal">
                                                        <i class="fa-solid fa-paper-plane me-1"></i> Compose
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Key Stats Row -->
                        <div class="stat-grid mb-4">
                            <div class="stat-card">
                                <div class="stat-icon icon-blue">
                                    <i class="fa-solid fa-envelope-open-text"></i>
                                </div>
                                <div class="stat-label">Total Recorded Broadcasts</div>
                                <div class="stat-value">{{ number_format($totalCount) }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-green">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="stat-label">Successfully Delivered</div>
                                <div class="stat-value text-success">{{ number_format($sentCount) }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-orange">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </div>
                                <div class="stat-label">Pending Dispatch</div>
                                <div class="stat-value text-warning">{{ number_format($pendingCount) }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-red">
                                    <i class="fa-solid fa-circle-exclamation"></i>
                                </div>
                                <div class="stat-label">Failed Deliveries</div>
                                <div class="stat-value text-danger">{{ number_format($failedCount) }}</div>
                            </div>
                        </div>

                        <!-- Broadcast History Table Card -->
                        <div class="dashboard-card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <div class="section-title">
                                        <i class="fa-solid fa-list-check text-primary me-2"></i>
                                        All Broadcast Logs & Delivery Records
                                    </div>
                                    <div class="section-subtitle">
                                        Search and filter comprehensive email notifications and message dispatches across all users.
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <a href="{{ route('admin.business-account') }}" class="btn btn-outline-secondary rounded-pill px-3 py-1.5 font-weight-600" style="font-size: 13px;">
                                        <i class="fa-solid fa-building me-1"></i> Business Accounts
                                    </a>
                                    <button type="button" class="btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#newBroadcastAllModal">
                                        <i class="fa-solid fa-plus"></i> Broadcast to All Users
                                    </button>
                                </div>
                            </div>

                            <!-- Filter & Search Toolbar -->
                            <div class="p-3 bg-white border-bottom border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <!-- Status Filter Pills -->
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="text-muted small font-weight-bold me-1">Filter Status:</span>
                                    <a href="{{ route('admin.broadcast.all', request()->except('status', 'page')) }}" 
                                       class="filter-pill {{ empty($status) ? 'active' : '' }}">
                                        All ({{ $totalCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.all', array_merge(request()->except('status', 'page'), ['status' => 'sent'])) }}" 
                                       class="filter-pill {{ $status === 'sent' ? 'active' : '' }}">
                                        Sent ({{ $sentCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.all', array_merge(request()->except('status', 'page'), ['status' => 'pending'])) }}" 
                                       class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">
                                        Pending ({{ $pendingCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.all', array_merge(request()->except('status', 'page'), ['status' => 'failed'])) }}" 
                                       class="filter-pill {{ $status === 'failed' ? 'active' : '' }}">
                                        Failed ({{ $failedCount }})
                                    </a>
                                </div>

                                <!-- Search Form -->
                                <form action="{{ route('admin.broadcast.all') }}" method="GET" class="d-flex align-items-center gap-2">
                                    @if(request('status'))
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    @endif
                                    <div class="input-group input-group-sm" style="max-width: 280px;">
                                        <input type="text" name="search" value="{{ $search }}" placeholder="Search subject, email, or merchant..." class="form-control rounded-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                        @if($search)
                                            <a href="{{ route('admin.broadcast.all', request()->except('search', 'page')) }}" class="btn btn-outline-secondary" title="Clear search">
                                                <i class="fa-solid fa-xmark"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>

                            <!-- Logs Table -->
                            <div class="table-responsive">
                                <table class="table table-modern align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px;">#</th>
                                            <th>Recipient & Merchant</th>
                                            <th>Subject & Message Preview</th>
                                            <th class="text-center">Delivery Status</th>
                                            <th class="text-center">Dispatched Date</th>
                                            <th class="text-end" style="width: 130px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($broadcasts as $key => $broadcast)
                                            <tr>
                                                <td class="text-center text-muted font-weight-bold">
                                                    {{ $broadcasts->firstItem() + $key }}
                                                </td>

                                                <td>
                                                    @if($broadcast->user)
                                                        <div>
                                                            <a href="{{ route('admin.business-account.find', $broadcast->user->id) }}" class="user-badge-link small">
                                                                {{ $broadcast->user->business_name ?? ($broadcast->user->firstname . ' ' . $broadcast->user->lastname) }}
                                                            </a>
                                                        </div>
                                                        <div class="text-muted small">{{ $broadcast->recipient_email }}</div>
                                                        <span class="badge bg-slate-100 text-muted border font-weight-normal mt-0.5" style="font-size: 10px;">ID #{{ $broadcast->user->id }}</span>
                                                    @else
                                                        <div class="font-weight-600 text-dark small">{{ $broadcast->recipient_email }}</div>
                                                        <span class="badge bg-light text-muted font-weight-normal" style="font-size: 10px;">Direct Recipient</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="d-flex align-items-start gap-2.5">
                                                        <div class="p-2 rounded-lg bg-blue-50 text-blue-600 mt-0.5 shrink-0" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                            <i class="fa-solid fa-envelope text-xs"></i>
                                                        </div>
                                                        <div style="min-width: 0;">
                                                            <div class="font-weight-bold text-dark text-truncate" style="max-width: 380px;" title="{{ $broadcast->subject }}">
                                                                {{ $broadcast->subject }}
                                                            </div>
                                                            <div class="text-muted small text-truncate mt-0.5" style="max-width: 380px;" title="{{ strip_tags($broadcast->message) }}">
                                                                {{ \Illuminate\Support\Str::limit(strip_tags($broadcast->message), 75) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    @if($broadcast->status === 'sent')
                                                        <span class="custom-status status-sent">
                                                            <i class="fa-solid fa-circle-check"></i> Sent
                                                        </span>
                                                    @elseif($broadcast->status === 'pending')
                                                        <span class="custom-status status-pending">
                                                            <i class="fa-solid fa-clock"></i> Pending
                                                        </span>
                                                    @else
                                                        <span class="custom-status status-failed" title="{{ $broadcast->error_message ?? 'Delivery error' }}">
                                                            <i class="fa-solid fa-triangle-exclamation"></i> Failed
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center">
                                                    <div class="font-weight-600 text-dark small">
                                                        {{ $broadcast->sent_at ? $broadcast->sent_at->format('M d, Y') : $broadcast->created_at->format('M d, Y') }}
                                                    </div>
                                                    <div class="text-muted" style="font-size: 11px;">
                                                        {{ $broadcast->sent_at ? $broadcast->sent_at->format('h:i A') : $broadcast->created_at->format('h:i A') }}
                                                        ({{ ($broadcast->sent_at ?? $broadcast->created_at)->diffForHumans() }})
                                                    </div>
                                                </td>

                                                <td class="text-end">
                                                    <button type="button" class="btn-soft" data-bs-toggle="modal" data-bs-target="#viewBroadcastModal{{ $broadcast->id }}">
                                                        <i class="fa-solid fa-eye me-1"></i> View
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-0 border-0">
                                                    <div class="empty-state-box">
                                                        <div class="stat-icon icon-blue mx-auto mb-3" style="width: 56px; height: 56px; font-size: 24px;">
                                                            <i class="fa-solid fa-inbox"></i>
                                                        </div>
                                                        <h5 class="font-weight-bold text-dark mb-1">No Broadcast Records Found</h5>
                                                        <p class="text-muted small mb-3" style="max-width: 420px; margin: 0 auto;">
                                                            @if($search || $status)
                                                                No emails or push notifications matched your search/filter criteria.
                                                            @else
                                                                No global broadcast records have been logged in the system yet.
                                                            @endif
                                                        </p>
                                                        @if($search || $status)
                                                            <a href="{{ route('admin.broadcast.all') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newBroadcastAllModal">
                                                                <i class="fa-solid fa-paper-plane me-1"></i> Broadcast to All Users
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            @if($broadcasts->hasPages())
                                <div class="d-flex justify-content-center p-3 border-top border-slate-100 bg-white">
                                    {{ $broadcasts->links('pagination::bootstrap-4') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Global Broadcast to All Businesses Modal -->
    <div class="modal fade" id="newBroadcastAllModal" tabindex="-1" aria-labelledby="newBroadcastAllModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold text-dark" id="newBroadcastAllModalLabel">
                            <i class="fa-solid fa-bullhorn text-primary me-2"></i> Broadcast Notification to All Business Accounts
                        </h5>
                        <p class="text-muted small mb-0">Dispatch system updates, announcements, or notifications to all registered business merchants.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="globalBroadcastForm">
                    <div class="modal-body p-4">
                        
                        <!-- Target Audience Info Banner -->
                        <div class="p-3 mb-3 rounded-3 bg-blue-50 border border-blue-100 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-users text-blue-600"></i>
                                <span class="text-dark font-weight-bold small">Target Audience:</span>
                                <span class="text-blue-700 small font-weight-600">All Registered Business Users</span>
                            </div>
                            <span class="badge bg-blue-600 text-white font-weight-normal px-2.5 py-1">Bulk Dispatch</span>
                        </div>

                        <!-- Delivery Channels -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-dark small mb-2">Delivery Channels *</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="channel-card active" id="globalCardEmail">
                                        <input type="checkbox" id="globalChannelEmail" class="form-check-input me-3" checked>
                                        <div>
                                            <div class="channel-name"><i class="fa-solid fa-envelope text-primary me-1"></i> Email Broadcast</div>
                                            <div class="channel-desc">Dispatches branded email to all business inboxes</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="channel-card active" id="globalCardInApp">
                                        <input type="checkbox" id="globalChannelInApp" class="form-check-input me-3" checked>
                                        <div>
                                            <div class="channel-name"><i class="fa-solid fa-bell text-warning me-1"></i> In-App Notification</div>
                                            <div class="channel-desc">Pushes instant notification to merchant dashboards</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="globalBroadcastSubject" class="form-label font-weight-bold text-dark small">Notification Title / Subject *</label>
                            <input type="text" id="globalBroadcastSubject" class="form-control form-control-lg rounded-3 fs-6" placeholder="e.g., Important System Update & Scheduled Maintenance" required>
                        </div>

                        <!-- Message Body -->
                        <div class="mb-2">
                            <label for="globalBroadcastMessage" class="form-label font-weight-bold text-dark small">Notification Message *</label>
                            <textarea id="globalBroadcastMessage" rows="6" class="form-control rounded-3" placeholder="Enter your detailed broadcast message here..." required></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">Plain text and paragraphs supported.</small>
                                <small class="text-muted font-weight-bold" id="globalCharCount">0 / 500 characters</small>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer px-4 py-3 bg-light border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="globalSubmitBroadcastBtn" class="btn-gradient-primary rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send Broadcast to All
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Broadcast Detail Modals -->
    @foreach($broadcasts as $broadcast)
        <div class="modal fade" id="viewBroadcastModal{{ $broadcast->id }}" tabindex="-1" aria-labelledby="viewBroadcastModalLabel{{ $broadcast->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-lg bg-blue-50 text-blue-600" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px;">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h5 class="modal-title font-weight-bold text-dark mb-0" id="viewBroadcastModalLabel{{ $broadcast->id }}">
                                    Broadcast Details
                                </h5>
                                <small class="text-muted">Log Record ID: #{{ $broadcast->id }}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <!-- Meta Details Card -->
                        <div class="card border rounded-3 p-3 bg-light mb-3">
                            <div class="row g-2">
                                <div class="col-sm-6">
                                    <small class="text-muted d-block font-weight-bold">Subject</small>
                                    <span class="text-dark font-weight-700">{{ $broadcast->subject }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted d-block font-weight-bold">Recipient</small>
                                    <span class="text-dark font-weight-600">{{ $broadcast->recipient_email }}</span>
                                    @if($broadcast->user)
                                        <div class="small text-muted mt-0.5">
                                            Account: <strong>{{ $broadcast->user->business_name ?? ($broadcast->user->firstname.' '.$broadcast->user->lastname) }}</strong> (ID #{{ $broadcast->user->id }})
                                        </div>
                                    @endif
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <small class="text-muted d-block font-weight-bold">Delivery Status</small>
                                    <div>
                                        @if($broadcast->status === 'sent')
                                            <span class="custom-status status-sent">
                                                <i class="fa-solid fa-circle-check"></i> Successfully Sent
                                            </span>
                                        @elseif($broadcast->status === 'pending')
                                            <span class="custom-status status-pending">
                                                <i class="fa-solid fa-clock"></i> Pending
                                            </span>
                                        @else
                                            <span class="custom-status status-failed">
                                                <i class="fa-solid fa-triangle-exclamation"></i> Failed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <small class="text-muted d-block font-weight-bold">Timestamp</small>
                                    <span class="text-dark font-weight-600">
                                        {{ ($broadcast->sent_at ?? $broadcast->created_at)->format('F d, Y \a\t h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message if failed -->
                        @if($broadcast->status === 'failed' && $broadcast->error_message)
                            <div class="alert alert-danger rounded-3 mb-3 p-3">
                                <div class="font-weight-bold small mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Delivery Error Diagnostics:</div>
                                <div class="small font-monospace text-break">{{ $broadcast->error_message }}</div>
                            </div>
                        @endif

                        <!-- Message Body -->
                        <div>
                            <label class="font-weight-bold text-dark small mb-2 d-block">Message Content</label>
                            <div class="p-3.5 bg-white border rounded-3 text-dark" style="white-space: pre-wrap; line-height: 1.7; font-size: 13.5px; max-height: 360px; overflow-y: auto;">{{ $broadcast->message }}</div>
                        </div>
                    </div>

                    <div class="modal-footer px-4 py-3 bg-light border-top">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @include('admin.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Channel Card Toggling inside Global Compose Modal
            const globalCardEmail = document.getElementById('globalCardEmail');
            const globalCardInApp = document.getElementById('globalCardInApp');
            const globalChkEmail = document.getElementById('globalChannelEmail');
            const globalChkInApp = document.getElementById('globalChannelInApp');

            function syncChannelCard(card, checkbox) {
                if (checkbox.checked) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }

            if (globalCardEmail && globalChkEmail) {
                globalCardEmail.addEventListener('click', function (e) {
                    if (e.target !== globalChkEmail) {
                        globalChkEmail.checked = !globalChkEmail.checked;
                    }
                    syncChannelCard(globalCardEmail, globalChkEmail);
                });
            }

            if (globalCardInApp && globalChkInApp) {
                globalCardInApp.addEventListener('click', function (e) {
                    if (e.target !== globalChkInApp) {
                        globalChkInApp.checked = !globalChkInApp.checked;
                    }
                    syncChannelCard(globalCardInApp, globalChkInApp);
                });
            }

            // Live Character Counter
            const msgInput = document.getElementById('globalBroadcastMessage');
            const charCountEl = document.getElementById('globalCharCount');
            if (msgInput && charCountEl) {
                msgInput.addEventListener('input', function () {
                    const len = this.value.length;
                    charCountEl.textContent = `${len} / 500 characters`;
                    if (len > 500) {
                        charCountEl.classList.add('text-danger');
                    } else {
                        charCountEl.classList.remove('text-danger');
                    }
                });
            }

            // Handle Global Broadcast Form Submission
            const form = document.getElementById('globalBroadcastForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const subject = document.getElementById('globalBroadcastSubject').value.trim();
                    const message = document.getElementById('globalBroadcastMessage').value.trim();
                    const inApp = globalChkInApp ? globalChkInApp.checked : false;
                    const email = globalChkEmail ? globalChkEmail.checked : false;

                    if (!subject) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Subject Required',
                            text: 'Please enter a broadcast subject/title.'
                        });
                        return;
                    }

                    if (!message) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Message Body Required',
                            text: 'Please enter the broadcast message content.'
                        });
                        return;
                    }

                    if (!inApp && !email) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Select Delivery Channel',
                            text: 'Please select at least one delivery channel (Email or In-App).'
                        });
                        return;
                    }

                    const channels = [];
                    if (inApp) channels.push('inapp');
                    if (email) channels.push('email');

                    Swal.fire({
                        title: 'Confirm Bulk Broadcast',
                        html: `Are you sure you want to broadcast this notification to <strong>ALL Business Accounts</strong>?<br><br>` +
                              `<div class="text-start small p-2 rounded bg-light border">` +
                              `<strong>Target:</strong> All Business Users<br>` +
                              `<strong>Subject:</strong> ${subject}<br>` +
                              `<strong>Channels:</strong> ${channels.join(', ')}` +
                              `</div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Broadcast to All',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#1d4ed8'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const submitBtn = document.getElementById('globalSubmitBroadcastBtn');
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Broadcasting...';
                            }

                            Swal.fire({
                                title: 'Broadcasting Notification...',
                                text: 'Dispatched to all business accounts.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch(`/admin/business-pushnotification`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    subject: subject,
                                    message: message,
                                    channels: channels
                                })
                            })
                            .then(async res => {
                                const data = await res.json();
                                if (!res.ok || data.success === false) {
                                    throw data;
                                }
                                return data;
                            })
                            .then(data => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Broadcast Dispatched!',
                                    text: data.message || 'Notification broadcast sent successfully.',
                                    confirmButtonColor: '#1d4ed8'
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(err => {
                                let errMsg = 'Failed to dispatch broadcast.';
                                if (err && err.message) {
                                    errMsg = err.message;
                                }
                                if (err && err.errors) {
                                    errMsg = Object.values(err.errors).flat().join('\n');
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Broadcast Failed',
                                    text: errMsg
                                });
                            })
                            .finally(() => {
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Send Broadcast to All';
                                }
                            });
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>