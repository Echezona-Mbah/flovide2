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

    .merchant-hero {
        border: 0;
        border-radius: 30px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.14), transparent 24%),
            radial-gradient(circle at bottom left, rgba(56,189,248,0.12), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: 0 28px 65px rgba(29, 78, 216, 0.18);
    }

    .merchant-hero .card-body {
        padding: 32px;
    }

    .merchant-avatar {
        width: 88px;
        height: 88px;
        border-radius: 24px;
        overflow: hidden;
        border: 3.5px solid rgba(255,255,255,0.22);
        box-shadow: 0 12px 24px rgba(0,0,0,0.16);
        background: rgba(255,255,255,0.1);
        flex-shrink: 0;
    }

    .merchant-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
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
        font-size: 14.5px;
        font-weight: 700;
    }

    .dashboard-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 0;
        margin: 24px 0;
        list-style: none;
    }

    .dashboard-tabs .nav-link {
        border: 0;
        border-radius: 16px;
        background: #fff;
        color: var(--text-soft);
        font-weight: 700;
        padding: 12px 22px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .dashboard-tabs .nav-link:hover {
        color: var(--blue);
        transform: translateY(-1px);
    }

    .dashboard-tabs .nav-link.active,
    .dashboard-tabs .nav-link.show {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.18);
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
        font-size: 22px;
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
        font-size: 17.5px;
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

                        <!-- Merchant Hero Card -->
                        <div class="card merchant-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3 mb-3">
                                            <div class="merchant-avatar">
                                                <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('admin/assets/images/avatars/user33.png') }}" alt="Profile">
                                            </div>

                                            <div class="text-white">
                                                <div class="hero-pill mb-2">
                                                    <i class="fa-solid fa-bullhorn"></i>
                                                    Broadcast Mail & Notifications Hub
                                                </div>
                                                <h2 class="mb-1 text-white font-weight-bold">{{ $user->business_name ?? ($user->firstname.' '.$user->lastname) }}</h2>
                                                <p class="mb-0 text-white-50"><i class="fa-solid fa-envelope me-1"></i> {{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 680px; line-height: 1.6; font-size: 13.5px;">
                                            Review the historical log of automated and manual emails or push notifications dispatched to this business account. Monitor delivery status, failure diagnostics, and timestamps.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Merchant ID</small>
                                                    <strong>#{{ $user->id }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Account Type</small>
                                                    <strong class="text-capitalize">{{ $user->typeofuser ?? 'Business' }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="hero-mini d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <small>Recipient Email</small>
                                                        <strong class="text-truncate d-inline-block" style="max-width: 220px;">{{ $user->email }}</strong>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 py-1 font-weight-bold" data-bs-toggle="modal" data-bs-target="#newBroadcastModal">
                                                        <i class="fa-solid fa-paper-plane me-1"></i> New
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Tabs -->
                        <ul class="dashboard-tabs nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.business-account.find', $user->id) }}">
                                    <i class="fa-solid fa-gauge"></i> Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.alltransactions.history', $user->id) }}">
                                    <i class="fa-solid fa-receipt"></i> Transactions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.testmode', $user->id) }}">
                                    <i class="fa-solid fa-flask-vial"></i> Test Mode
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link active show" href="{{ route('admin.broadcast.index', $user->id) }}">
                                    <i class="fa-solid fa-bullhorn"></i> Broadcast History
                                </a>
                            </li>
                        </ul>

                        <!-- Key Stats Grid -->
                        <div class="stat-grid mb-4">
                            <div class="stat-card">
                                <div class="stat-icon icon-blue">
                                    <i class="fa-solid fa-envelope-open-text"></i>
                                </div>
                                <div class="stat-label">Total Broadcasts</div>
                                <div class="stat-value">{{ number_format($totalCount) }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-green">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="stat-label">Delivered Successfully</div>
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
                                        <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>
                                        Sent Broadcasts & Email Logs
                                    </div>
                                    <div class="section-subtitle">
                                        Historical record of push messages and broadcast emails dispatched to {{ $user->email }}.
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <!-- Compose Broadcast Button -->
                                    <button type="button" class="btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#newBroadcastModal">
                                        <i class="fa-solid fa-plus"></i> Compose Broadcast
                                    </button>
                                </div>
                            </div>

                            <!-- Filter & Search Toolbar -->
                            <div class="p-3 bg-white border-bottom border-slate-100 d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <!-- Status Filter Pills -->
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="text-muted small font-weight-bold me-1">Status:</span>
                                    <a href="{{ route('admin.broadcast.index', array_merge(['id' => $user->id], request()->except('status', 'page'))) }}" 
                                       class="filter-pill {{ empty($status) ? 'active' : '' }}">
                                        All ({{ $totalCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.index', array_merge(['id' => $user->id, 'status' => 'sent'], request()->except('status', 'page'))) }}" 
                                       class="filter-pill {{ $status === 'sent' ? 'active' : '' }}">
                                        Sent ({{ $sentCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.index', array_merge(['id' => $user->id, 'status' => 'pending'], request()->except('status', 'page'))) }}" 
                                       class="filter-pill {{ $status === 'pending' ? 'active' : '' }}">
                                        Pending ({{ $pendingCount }})
                                    </a>
                                    <a href="{{ route('admin.broadcast.index', array_merge(['id' => $user->id, 'status' => 'failed'], request()->except('status', 'page'))) }}" 
                                       class="filter-pill {{ $status === 'failed' ? 'active' : '' }}">
                                        Failed ({{ $failedCount }})
                                    </a>
                                </div>

                                <!-- Search Form -->
                                <form action="{{ route('admin.broadcast.index', $user->id) }}" method="GET" class="d-flex align-items-center gap-2">
                                    @if(request('status'))
                                        <input type="hidden" name="status" value="{{ request('status') }}">
                                    @endif
                                    <div class="input-group input-group-sm" style="max-width: 260px;">
                                        <input type="text" name="search" value="{{ $search }}" placeholder="Search subject or content..." class="form-control rounded-start">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>
                                        @if($search)
                                            <a href="{{ route('admin.broadcast.index', array_merge(['id' => $user->id], request()->except('search', 'page'))) }}" class="btn btn-outline-secondary" title="Clear search">
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
                                            <th>Subject & Excerpt</th>
                                            <th>Recipient</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Sent Date</th>
                                            <th class="text-end" style="width: 140px;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($broadcasts as $key => $broadcast)
                                            <tr>
                                                <td class="text-center text-muted font-weight-bold">
                                                    {{ $broadcasts->firstItem() + $key }}
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

                                                <td>
                                                    <div class="font-weight-600 text-dark small">{{ $broadcast->recipient_email }}</div>
                                                    @if($broadcast->user_id)
                                                        <span class="badge badge-light text-muted border font-weight-normal mt-0.5" style="font-size: 10px;">ID #{{ $broadcast->user_id }}</span>
                                                    @endif
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
                                                        <span class="custom-status status-failed" title="{{ $broadcast->error_message ?? 'Failed to send' }}">
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
                                                        <p class="text-muted small mb-3" style="max-width: 400px; margin: 0 auto;">
                                                            @if($search || $status)
                                                                No emails or push broadcasts matched your filter criteria. Try resetting the filters.
                                                            @else
                                                                No emails or broadcasts have been recorded for this user yet. You can compose and dispatch one right now.
                                                            @endif
                                                        </p>
                                                        @if($search || $status)
                                                            <a href="{{ route('admin.broadcast.index', $user->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Filters
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn-gradient-primary btn-sm" data-bs-toggle="modal" data-bs-target="#newBroadcastModal">
                                                                <i class="fa-solid fa-paper-plane me-1"></i> Send First Broadcast
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

    <!-- Compose New Broadcast Modal -->
    <div class="modal fade" id="newBroadcastModal" tabindex="-1" aria-labelledby="newBroadcastModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <div>
                        <h5 class="modal-title font-weight-bold text-dark" id="newBroadcastModalLabel">
                            <i class="fa-solid fa-paper-plane text-primary me-2"></i> Compose Broadcast to Merchant
                        </h5>
                        <p class="text-muted small mb-0">Dispatch an email and in-app message directly to {{ $user->business_name ?? $user->email }}.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="composeBroadcastForm" data-user-id="{{ $user->id }}">
                    <div class="modal-body p-4">
                        
                        <!-- Target Recipient Info -->
                        <div class="p-3 mb-3 rounded-3 bg-blue-50 border border-blue-100 d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa-solid fa-user-check text-blue-600"></i>
                                <span class="text-dark font-weight-bold small">Recipient:</span>
                                <span class="text-blue-700 small font-weight-600">{{ $user->business_name ?? ($user->firstname.' '.$user->lastname) }} ({{ $user->email }})</span>
                            </div>
                            <span class="badge bg-blue-600 text-white font-weight-normal px-2.5 py-1">Merchant ID: #{{ $user->id }}</span>
                        </div>

                        <!-- Delivery Channels -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold text-dark small mb-2">Delivery Channels *</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="channel-card active" id="cardEmail">
                                        <input type="checkbox" id="modalChannelEmail" class="form-check-input me-3" checked>
                                        <div>
                                            <div class="channel-name"><i class="fa-solid fa-envelope text-primary me-1"></i> Email Dispatch</div>
                                            <div class="channel-desc">Send branded HTML email to {{ $user->email }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="channel-card active" id="cardInApp">
                                        <input type="checkbox" id="modalChannelInApp" class="form-check-input me-3" checked>
                                        <div>
                                            <div class="channel-name"><i class="fa-solid fa-bell text-warning me-1"></i> In-App Notification</div>
                                            <div class="channel-desc">Push instant notification to merchant dashboard</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="modalBroadcastSubject" class="form-label font-weight-bold text-dark small">Broadcast Subject / Title *</label>
                            <input type="text" id="modalBroadcastSubject" class="form-control form-control-lg rounded-3 fs-6" placeholder="e.g., Important Security Notice regarding your Merchant Account..." required>
                        </div>

                        <!-- Message Body -->
                        <div class="mb-2">
                            <label for="modalBroadcastMessage" class="form-label font-weight-bold text-dark small">Broadcast Message Body *</label>
                            <textarea id="modalBroadcastMessage" rows="6" class="form-control rounded-3" placeholder="Write your full broadcast message here..." required></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">Supports standard plain text & formatted notification body.</small>
                                <small class="text-muted font-weight-bold" id="charCount">0 / 500 characters</small>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer px-4 py-3 bg-light border-top d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="modalSubmitBroadcastBtn" class="btn-gradient-primary rounded-pill px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send Broadcast Now
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
                                <small class="text-muted">Record ID: #{{ $broadcast->id }}</small>
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
                                    <small class="text-muted d-block font-weight-bold">Recipient Email</small>
                                    <span class="text-dark font-weight-600">{{ $broadcast->recipient_email }}</span>
                                </div>
                                <div class="col-sm-6 mt-2">
                                    <small class="text-muted d-block font-weight-bold">Status</small>
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
            // Channel Card Toggling inside Compose Modal
            const cardEmail = document.getElementById('cardEmail');
            const cardInApp = document.getElementById('cardInApp');
            const chkEmail = document.getElementById('modalChannelEmail');
            const chkInApp = document.getElementById('modalChannelInApp');

            function syncChannelCard(card, checkbox) {
                if (checkbox.checked) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }

            if (cardEmail && chkEmail) {
                cardEmail.addEventListener('click', function (e) {
                    if (e.target !== chkEmail) {
                        chkEmail.checked = !chkEmail.checked;
                    }
                    syncChannelCard(cardEmail, chkEmail);
                });
            }

            if (cardInApp && chkInApp) {
                cardInApp.addEventListener('click', function (e) {
                    if (e.target !== chkInApp) {
                        chkInApp.checked = !chkInApp.checked;
                    }
                    syncChannelCard(cardInApp, chkInApp);
                });
            }

            // Live Character Counter
            const msgInput = document.getElementById('modalBroadcastMessage');
            const charCountEl = document.getElementById('charCount');
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

            // Handle Broadcast Form Submission
            const form = document.getElementById('composeBroadcastForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const userId = this.dataset.userId;
                    const subject = document.getElementById('modalBroadcastSubject').value.trim();
                    const message = document.getElementById('modalBroadcastMessage').value.trim();
                    const inApp = chkInApp ? chkInApp.checked : false;
                    const email = chkEmail ? chkEmail.checked : false;

                    if (!subject) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Subject Required',
                            text: 'Please enter a broadcast subject.'
                        });
                        return;
                    }

                    if (!message) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Message Body Required',
                            text: 'Please enter the broadcast message body.'
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
                        title: 'Confirm Broadcast',
                        html: `Are you sure you want to send this broadcast to <strong>{{ $user->business_name ?? $user->email }}</strong>?<br><br>` +
                              `<div class="text-start small p-2 rounded bg-light border">` +
                              `<strong>Subject:</strong> ${subject}<br>` +
                              `<strong>Channels:</strong> ${channels.join(', ')}` +
                              `</div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Send Now',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#1d4ed8'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const submitBtn = document.getElementById('modalSubmitBroadcastBtn');
                            if (submitBtn) {
                                submitBtn.disabled = true;
                                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Sending...';
                            }

                            Swal.fire({
                                title: 'Sending Broadcast...',
                                text: 'Dispatched to target recipient.',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch(`/admin/business-pushnotification/${userId}`, {
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
                                    title: 'Sent Successfully!',
                                    text: data.message || 'Notification sent successfully.',
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
                                    title: 'Sending Failed',
                                    text: errMsg
                                });
                            })
                            .finally(() => {
                                if (submitBtn) {
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Send Broadcast Now';
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