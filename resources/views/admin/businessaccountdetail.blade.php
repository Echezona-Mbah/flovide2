@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-deep: #1d4ed8;
        --brand-sky: #0ea5e9;
        --blue: #1d4ed8;
        --blue-deep: #0f2c73;
        --paper: #ffffff;
        --paper-soft: #f6f8fc;
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
        --shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
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
        padding: 34px;
    }

    .merchant-avatar {
        width: 100px;
        height: 100px;
        border-radius: 28px;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.16);
        box-shadow: 0 12px 24px rgba(0,0,0,0.16);
        background: rgba(255,255,255,0.08);
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
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .hero-mini {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 18px;
        padding: 16px;
        color: #fff;
        height: 100%;
    }

    .hero-mini small {
        display: block;
        margin-bottom: 6px;
        color: rgba(255,255,255,0.72);
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
    }

    .dashboard-tabs .nav-link.active,
    .dashboard-tabs .nav-link.show {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.18);
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
        padding: 22px 24px;
    }

    .dashboard-card .card-body {
        padding: 24px;
    }

    .dashboard-card .card-footer {
        border-top: 1px solid var(--border-soft);
        background: #fff;
        padding: 18px 24px;
    }

    .soft-panel {
        border: 1px solid var(--border-soft);
        border-radius: 22px;
        background: var(--surface-soft);
        padding: 22px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border-soft);
        border-radius: 22px;
        padding: 20px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        font-size: 22px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: #2563eb; }
    .icon-green { background: rgba(22, 163, 74, 0.12); color: #16a34a; }
    .icon-orange { background: rgba(245, 158, 11, 0.14); color: #d97706; }
    .icon-red { background: rgba(220, 38, 38, 0.12); color: #dc2626; }

    .stat-label {
        font-size: 13px;
        color: var(--text-soft);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: var(--text-main);
    }

    .profile-card {
        border: 1px solid var(--border-soft);
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        padding: 24px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 20px;
    }

    .profile-avatar {
        width: 88px;
        height: 88px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.08);
        border: 4px solid #eaf2ff;
        flex-shrink: 0;
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-table {
        width: 100%;
    }

    .profile-table tr:not(:last-child) {
        border-bottom: 1px solid var(--border-soft);
    }

    .profile-table th,
    .profile-table td {
        padding: 12px 0;
        font-size: 14px;
        vertical-align: top;
    }

    .profile-table th {
        color: var(--text-soft);
        font-weight: 700;
        width: 220px;
    }

    .profile-table td {
        color: var(--text-main);
        font-weight: 600;
    }

    .custom-status {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-success {
        background: var(--success-soft);
        color: #16a34a;
    }

    .status-warning {
        background: var(--warning-soft);
        color: #d97706;
    }

    .status-danger {
        background: var(--danger-soft);
        color: #dc2626;
    }

    .status-muted {
        background: rgba(100, 116, 139, 0.12);
        color: #64748b;
    }

    .compliance-thumb {
        width: 58px;
        height: 58px;
        border-radius: 14px;
        object-fit: cover;
        border: 1px solid var(--border-soft);
        background: #fff;
        padding: 4px;
    }

    .currency-card {
        border-radius: 22px;
        padding: 22px;
        color: #fff;
        min-height: 130px;
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
        position: relative;
        overflow: hidden;
    }

    .currency-card::after {
        content: "";
        position: absolute;
        width: 130px;
        height: 130px;
        border-radius: 999px;
        background: rgba(255,255,255,0.14);
        top: -30px;
        right: -30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .section-subtitle {
        font-size: 13px;
        color: var(--text-soft);
        margin-bottom: 18px;
    }

    .table-modern thead th {
        border-bottom: 1px solid var(--border-soft);
        color: var(--text-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .table-modern tbody tr {
        border-top: 1px solid #f1f5f9;
    }

    .table-modern td,
    .table-modern th {
        padding: 16px 14px;
        vertical-align: middle;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        border: 1px dashed var(--border-soft);
        border-radius: 18px;
        color: var(--text-soft);
        background: var(--surface-soft);
    }

    .btn-soft-dark {
        background: #111827;
        border: 0;
        color: #fff;
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-soft-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: 0;
        color: #fff;
        border-radius: 14px;
        padding: 10px 18px;
        font-weight: 600;
    }

    .btn-soft-dark:hover,
    .btn-soft-primary:hover {
        color: #fff;
        opacity: 0.96;
    }

    .dropdown-menu {
        border-radius: 16px;
        border: 1px solid var(--border-soft);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    }

    .dropdown-item {
        padding: 10px 16px;
    }
    .balance-actions .btn {
    flex: 1;
    white-space: nowrap;
}

.bank-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 18px;
}

.bank-tile {
    border: 1px solid var(--border-soft);
    border-radius: 22px;
    padding: 22px;
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    display: flex;
    flex-direction: column;
    gap: 16px;
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}

.bank-tile:hover {
    box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    transform: translateY(-2px);
}

.bank-tile-active {
    border-color: rgba(22, 163, 74, 0.35);
    background: linear-gradient(180deg, #ffffff, #f0fdf4);
}

.bank-tile-top {
    display: flex;
    align-items: center;
    gap: 14px;
}

.bank-flag-badge {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    background: var(--surface-soft);
    border: 1px solid var(--border-soft);
    flex-shrink: 0;
}

.bank-tile-name {
    font-weight: 800;
    font-size: 15px;
    color: var(--text-main);
}

.bank-tile-region {
    font-size: 12px;
    color: var(--text-soft);
}

.bank-tile-pulse {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #cbd5e1;
    margin-left: auto;
    flex-shrink: 0;
}

.bank-tile-pulse.pulse-on {
    background: #16a34a;
    box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.6);
    animation: bankPulse 2s infinite;
}

@keyframes bankPulse {
    0% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0.45); }
    70% { box-shadow: 0 0 0 8px rgba(22, 163, 74, 0); }
    100% { box-shadow: 0 0 0 0 rgba(22, 163, 74, 0); }
}

.bank-tile-body {
    flex: 1;
}

.bank-tile-empty {
    font-size: 13px;
    color: var(--text-soft);
    line-height: 1.6;
}

.bank-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid var(--border-soft);
    font-size: 13px;
}

.bank-detail-row:last-child {
    border-bottom: 0;
}

.bank-detail-row span {
    color: var(--text-soft);
}

.bank-detail-row strong {
    color: var(--text-main);
}

.bank-tile-status {
    align-self: flex-start;
}

.banking-integrations-card .card-header .custom-status i {
    margin-right: 4px;
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









/* Verification Control */

.verification-control-card {
    display: flex;
    gap: 18px;
    padding: 22px;
    border: 1px solid var(--line);
    border-radius: 20px;
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    transition: all 0.2s ease;
}

.verification-control-card:hover {
    border-color: rgba(29, 78, 216, 0.25);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
}

.verification-icon {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 21px;
}

.verification-icon.identity {
    background: rgba(37, 99, 235, 0.12);
    color: #2563eb;
}

.verification-icon.selfie {
    background: rgba(124, 58, 237, 0.12);
    color: #7c3aed;
}

.verification-content {
    flex: 1;
    min-width: 0;
}

.verification-title {
    margin: 0 0 5px;
    font-size: 15px;
    font-weight: 800;
    color: var(--ink);
}

.verification-description {
    margin: 0;
    color: var(--ink-soft);
    font-size: 12px;
    line-height: 1.5;
}

.verification-action {
    margin-top: 18px;
}

.verification-label {
    display: block;
    margin-bottom: 7px;
    font-size: 11px;
    font-weight: 800;
    color: var(--ink-soft);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

.verification-status-select {
    border-radius: 12px;
    border-color: var(--line);
    height: 44px;
    font-size: 13px;
    font-weight: 600;
}

.verification-status-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.08);
}

@media (max-width: 767px) {

    .verification-control-card {
        padding: 18px;
    }

    .verification-control-card .d-flex {
        flex-direction: column;
    }

    .verification-control-card .custom-status {
        align-self: flex-start;
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
                    <div class="dashboard-shell">

                        <div class="card merchant-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                                            <div class="merchant-avatar">
                                                <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('admin/assets/images/avatars/user33.png') }}" alt="Profile">
                                            </div>

                                            <div class="text-white">
                                                <div class="hero-pill mb-2">
                                                    <i class="fa-solid fa-building"></i>
                                                    Business Merchant Dashboard
                                                </div>
                                                <h2 class="mb-1 text-white">{{ $user->business_name ?? ($user->firstname.' '.$user->lastname) }}</h2>
                                                <p class="mb-0 text-white-50">{{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 720px; line-height: 1.8;">
                                            A cleaner overview of this merchant’s profile, verification documents, balances, virtual cards, team structure, and banking relationships.
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
                                                    <small>Email Status</small>
                                                    <strong>{{ $user->email_verified_status == 'yes' ? 'Verified' : 'Not Verified' }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="hero-mini">
                                                    <small>Business Phone</small>
                                                    <strong>{{ $user->business_phone ?? 'Not provided' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="dashboard-tabs nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active show" data-tab="sales" href="javascript:void(0);">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" data-tab="activity" href="javascript:void(0);">Balances & Cards</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" data-tab="profile" href="javascript:void(0);">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" data-tab="accounts" href="javascript:void(0);">Accounts</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.alltransactions.history', $user->id) }}">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.testmode', $user->id) }}">Test Mode</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.broadcast.index', $user->id) }}">Broadcast</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.businessstatement', $user->id) }}">Statement</a>
                            </li>
                        </ul>

                        <div class="tab-content-section" id="tab-sales">
                            <div class="stat-grid mb-4">
                                <div class="stat-card">
                                    <div class="stat-icon icon-blue">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div class="stat-label">Business Name</div>
                                    <div class="stat-value">{{ $user->business_name ?? 'N/A' }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-green">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                    <div class="stat-label">Email Verification</div>
                                    <div class="stat-value">{{ $user->email_verified_status == 'yes' ? 'Verified' : 'Pending' }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-orange">
                                        <i class="fa-solid fa-wallet"></i>
                                    </div>
                                    <div class="stat-label">Wallet Count</div>
                                    <div class="stat-value">{{ $balances->count() }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-red">
                                        <i class="fa-solid fa-users"></i>
                                    </div>
                                    <div class="stat-label">Team Members</div>
                                    <div class="stat-value">{{ $teamMembers->count() }}</div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="section-title">Business Profile</div>
                                        <div class="section-subtitle">Core merchant identity and operating information.</div>
                                    </div>
                                    <button class="btn btn-soft-dark btn-sm" id="toggleProfileBtn">Show / Hide Details</button>
                                </div>

                                <div class="card-body">
                                    <div id="profileDetails" class="profile-card d-none">
                                        <div class="profile-header">
                                            <div class="profile-avatar">
                                                <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('admin/assets/images/avatars/user33.png') }}" alt="Profile">
                                            </div>

                                            <div>
                                                <h4 class="mb-1">{{ $user->firstname }} {{ $user->lastname }}</h4>
                                                <p class="mb-1 text-muted">{{ $user->email }}</p>
                                                @if($user->email_verified_status == 'yes')
                                                    <span class="custom-status status-success">Verified</span>
                                                @else
                                                    <span class="custom-status status-danger">Not Verified</span>
                                                @endif
                                            </div>
                                        </div>

                                        <table class="profile-table">
                                            <tr><th>Business Name</th><td>{{ $user->business_name ?? 'N/A' }}</td></tr>
                                            <tr><th>Industry</th><td>{{ $user->industry ?? 'N/A' }}</td></tr>
                                            <tr><th>Business Type</th><td>{{ $user->business_type ?? 'N/A' }}</td></tr>
                                            <tr><th>Registration Number</th><td>{{ $user->registration_number ?? 'N/A' }}</td></tr>
                                            <tr><th>Business Phone</th><td>{{ $user->business_phone ?? 'N/A' }}</td></tr>
                                            <tr><th>Personal Phone</th><td>{{ $user->person_phone ?? 'N/A' }}</td></tr>
                                            <tr><th>City</th><td>{{ $user->city ?? 'N/A' }}</td></tr>
                                            <tr><th>State</th><td>{{ $user->state ?? 'N/A' }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Compliance Documents</div>
                                    <div class="section-subtitle">Review uploaded verification files and update approval status.</div>
                                </div>

                                <div class="card-body">
                                    {{-- @php
                                        $documents = [
                                            ['label' => 'CAC Certificate', 'file' => $user->cac_certificate, 'status' => $user->cac_status, 'field' => 'cac_status'],
                                            ['label' => 'Valid ID', 'file' => $user->valid_id, 'status' => $user->valid_id_status, 'field' => 'valid_id_status'],
                                            ['label' => 'TIN Document', 'file' => $user->tin, 'status' => $user->tin_status, 'field' => 'tin_status'],
                                            ['label' => 'Utility Bill', 'file' => $user->utility_bill, 'status' => $user->utility_bill_status, 'field' => 'utility_bill_status'],
                                            ['label' => 'Proof Of Identity', 'file' => $user->proof_of_identity, 'status' => $user->proof_of_identity_status, 'field' => 'proof_of_identity_status'],
                                            ['label' => 'Ownership Document', 'file' => $user->ownership_document, 'status' => $user->ownership_status, 'field' => 'ownership_status'],
                                            ['label' => 'Organisational Chart', 'file' => $user->organisational_chart, 'status' => $user->organisational_chart_status, 'field' => 'organisational_chart_status'],
                                            ['label' => 'Register Of Directors', 'file' => $user->register_of_directors, 'status' => $user->register_of_directors_status, 'field' => 'register_of_directors_status'],
                                            ['label' => 'Formation Document', 'file' => $user->formation_document, 'status' => $user->formation_document_status, 'field' => 'formation_document_status'],
                                        ];
                                    @endphp --}}
                                    @php
                                        $documents = [
                                            ['label' => 'NIN', 'file' => $user->nin, 'file_field' => 'nin', 'status' => $user->nin_status, 'field' => 'nin_status'],
                                            ['label' => 'BVN', 'file' => $user->bvn, 'file_field' => 'bvn', 'status' => $user->bvn_status, 'field' => 'bvn_status'],
                                            ['label' => 'CAC Certificate',       'file' => $user->cac_certificate,       'file_field' => 'cac_certificate',       'status' => $user->cac_status,                   'field' => 'cac_status'],
                                            ['label' => 'Valid ID',               'file' => $user->valid_id,               'file_field' => 'valid_id',               'status' => $user->valid_id_status,              'field' => 'valid_id_status'],
                                            ['label' => 'TIN Document',           'file' => $user->tin,                    'file_field' => 'tin',                    'status' => $user->tin_status,                   'field' => 'tin_status'],
                                            ['label' => 'Utility Bill',           'file' => $user->utility_bill,           'file_field' => 'utility_bill',           'status' => $user->utility_bill_status,          'field' => 'utility_bill_status'],
                                            ['label' => 'Proof Of Identity',      'file' => $user->proof_of_identity,      'file_field' => 'proof_of_identity',      'status' => $user->proof_of_identity_status,     'field' => 'proof_of_identity_status'],
                                            ['label' => 'Ownership Document',     'file' => $user->ownership_document,     'file_field' => 'ownership_document',     'status' => $user->ownership_status,             'field' => 'ownership_status'],
                                            ['label' => 'Organisational Chart',   'file' => $user->organisational_chart,   'file_field' => 'organisational_chart',   'status' => $user->organisational_chart_status,  'field' => 'organisational_chart_status'],
                                            ['label' => 'Register Of Directors',  'file' => $user->register_of_directors,  'file_field' => 'register_of_directors',  'status' => $user->register_of_directors_status, 'field' => 'register_of_directors_status'],
                                            ['label' => 'Formation Document',     'file' => $user->formation_document,     'file_field' => 'formation_document',     'status' => $user->formation_document_status,    'field' => 'formation_document_status'],
                                        ];
                                    @endphp

                                    <div class="table-responsive">
                                        <table class="table table-modern align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Document</th>
                                                    <th class="text-center">Preview</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($documents as $doc)
                                                    <tr>
                                                        <td>
                                                            <div class="fw-bold">{{ $doc['label'] }}</div>
                                                            <div class="text-muted small">{{ ucfirst(str_replace('_',' ', $doc['field'])) }}</div>
                                                        </td>

                                                        <!-- Preview -->
                                                        <td class="text-center">
                                                            @if($doc['file'])
                                                                @php
                                                                    $filePath  = 'storage/' . $doc['file'];
                                                                    $extension = strtolower(pathinfo($doc['file'], PATHINFO_EXTENSION));
                                                                @endphp

                                                                <a href="{{ route('admin.document.download', ['id' => $user->id, 'field' => $doc['file_field']]) }}" target="_blank">
                                                                    @if(in_array($extension, ['jpg','jpeg','png','webp']))
                                                                        <img src="{{ asset($filePath) }}" class="compliance-thumb"
                                                                            onerror="this.src='{{ asset('assets/dashboard/file.png') }}'">
                                                                    @else
                                                                        <i class="fa fa-file-pdf fa-2x text-danger"></i>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <span class="custom-status status-danger">No File</span>
                                                            @endif
                                                        </td>

                                                        <!-- Status -->
                                                        <td class="text-center">
                                                            @php
                                                                $status = $doc['status'] ?? 'not submitted';
                                                                $badgeClass = 'status-muted';
                                                                if ($status === 'confirmed') $badgeClass = 'status-success';
                                                                elseif ($status === 'under review') $badgeClass = 'status-warning';
                                                                elseif ($status === 'rejected') $badgeClass = 'status-danger';
                                                            @endphp
                                                            <span class="custom-status status-badge {{ $badgeClass }}" data-status-field="{{ $doc['field'] }}">
                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                            </span>
                                                        </td>

                                                        <!-- Action -->
                                                        <td class="text-center">
                                                            <div class="dropdown d-inline-block">
                                                                <button type="button" data-bs-toggle="dropdown" class="btn btn-soft-primary btn-sm">
                                                                    Update
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="#" class="dropdown-item change-status" data-status="under review" data-field="{{ $doc['field'] }}" data-id="{{ $user->id }}">Under Review</a>
                                                                    <a href="#" class="dropdown-item change-status" data-status="confirmed" data-field="{{ $doc['field'] }}" data-id="{{ $user->id }}">Confirmed</a>
                                                                    <a href="#" class="dropdown-item change-status text-danger" data-status="rejected" data-field="{{ $doc['field'] }}" data-id="{{ $user->id }}">Rejected</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <section class="mb-4">
                                <div class="card notification-card">

                                    {{-- Header --}}
                                    <div class="notification-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div>
                                            <h5 class="notification-title">
                                                <i class="fa-solid fa-shield-halved text-primary me-2"></i>
                                                Verification Status
                                            </h5>

                                            <p class="notification-subtitle">
                                                Manually update this user's identity and selfie verification status.
                                            </p>
                                        </div>

                                        <span class="custom-status status-muted">
                                            <i class="fa-solid fa-user-shield me-1"></i>
                                            Admin Control
                                        </span>
                                    </div>


                                    <div class="card-body p-4">

                                        <div class="row g-4">

                                            {{-- Identity Verification --}}
                                            <div class="col-md-6">
                                                <div class="verification-control-card">

                                                    <div class="verification-icon identity">
                                                        <i class="fa-solid fa-id-card"></i>
                                                    </div>

                                                    <div class="verification-content">

                                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                                            <div>
                                                                <h6 class="verification-title">
                                                                    Identity Verification
                                                                </h6>

                                                                <p class="verification-description">
                                                                    Controls whether the user's identity documents have been verified.
                                                                </p>
                                                            </div>

                                                            @php
                                                                $identityStatus = $user->identity_verification_status ?? 'pending';

                                                                $identityBadge = match ($identityStatus) {
                                                                    'confirmed', 'verified' => 'status-success',
                                                                    'under review', 'pending' => 'status-warning',
                                                                    'rejected' => 'status-danger',
                                                                    default => 'status-muted',
                                                                };
                                                            @endphp

                                                            <span id="identity-status-badge"
                                                                class="custom-status {{ $identityBadge }}">
                                                                {{ ucfirst(str_replace('_', ' ', $identityStatus)) }}
                                                            </span>
                                                        </div>


                                                        <div class="verification-action">

                                                            <label class="verification-label">
                                                                Update Status
                                                            </label>

                                                            <select
                                                                class="form-select verification-status-select"
                                                                data-field="identity_verification_status"
                                                                data-user-id="{{ $user->id }}"
                                                                data-badge="identity-status-badge"
                                                            >
                                                                <option value="pending"
                                                                    {{ $identityStatus === 'pending' ? 'selected' : '' }}>
                                                                    Pending
                                                                </option>

                                                                <option value="under review"
                                                                    {{ $identityStatus === 'under review' ? 'selected' : '' }}>
                                                                    Under Review
                                                                </option>

                                                                <option value="confirmed"
                                                                    {{ $identityStatus === 'confirmed' ? 'selected' : '' }}>
                                                                    Confirmed
                                                                </option>

                                                                <option value="rejected"
                                                                    {{ $identityStatus === 'rejected' ? 'selected' : '' }}>
                                                                    Rejected
                                                                </option>
                                                            </select>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>


                                            {{-- Selfie Verification --}}
                                            <div class="col-md-6">
                                                <div class="verification-control-card">

                                                    <div class="verification-icon selfie">
                                                        <i class="fa-solid fa-camera"></i>
                                                    </div>

                                                    <div class="verification-content">

                                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                                            <div>
                                                                <h6 class="verification-title">
                                                                    Selfie Verification
                                                                </h6>

                                                                <p class="verification-description">
                                                                    Controls whether the user's selfie verification has been completed.
                                                                </p>
                                                            </div>

                                                            @php
                                                                $selfieStatus = $user->selfie_verification_status ?? 'pending';

                                                                $selfieBadge = match ($selfieStatus) {
                                                                    'confirmed', 'verified' => 'status-success',
                                                                    'under review', 'pending' => 'status-warning',
                                                                    'rejected' => 'status-danger',
                                                                    default => 'status-muted',
                                                                };
                                                            @endphp

                                                            <span id="selfie-status-badge"
                                                                class="custom-status {{ $selfieBadge }}">
                                                                {{ ucfirst(str_replace('_', ' ', $selfieStatus)) }}
                                                            </span>
                                                        </div>


                                                        <div class="verification-action">

                                                            <label class="verification-label">
                                                                Update Status
                                                            </label>

                                                            <select
                                                                class="form-select verification-status-select"
                                                                data-field="selfie_verification_status"
                                                                data-user-id="{{ $user->id }}"
                                                                data-badge="selfie-status-badge"
                                                            >
                                                                <option value="pending"
                                                                    {{ $selfieStatus === 'pending' ? 'selected' : '' }}>
                                                                    Pending
                                                                </option>

                                                                <option value="under review"
                                                                    {{ $selfieStatus === 'under review' ? 'selected' : '' }}>
                                                                    Under Review
                                                                </option>

                                                                <option value="confirmed"
                                                                    {{ $selfieStatus === 'confirmed' ? 'selected' : '' }}>
                                                                    Confirmed
                                                                </option>

                                                                <option value="rejected"
                                                                    {{ $selfieStatus === 'rejected' ? 'selected' : '' }}>
                                                                    Rejected
                                                                </option>
                                                            </select>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </section>

                            <section class="mb-4">
                                <div class="card notification-card">
                                    <div class="notification-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        <div>
                                            <h5 class="notification-title"><i class="fa-solid fa-bullhorn text-primary me-2"></i>Send Broadcast Notification</h5>
                                            <p class="notification-subtitle">Broadcast system alerts, updates, or email promotions to all registered business users.</p>
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="broadcastForm" data-user-id="{{ $user->id }}">
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







                        </div>

                        <div class="tab-content-section d-none" id="tab-activity">
                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Balances</div>
                                    <div class="section-subtitle">Current currency wallets and available balances.</div>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        @foreach($balances as $bal)
                                            @php
                                                $flagColors = [
                                                    'NGN' => 'background: linear-gradient(to right, #008751, #ffffff, #008751);',
                                                    'USD' => 'background-color:#3C3B6E;',
                                                    'KES' => 'background: linear-gradient(to right, black, red, #006600);',
                                                    'GHS' => 'background: linear-gradient(to right, red, gold, green);',
                                                    'ZAR' => 'background-color: green;',
                                                    'GBP' => 'background-color: #00247D;',
                                                    'EUR' => 'background-color: #003399;',
                                                    'CAD' => 'background: linear-gradient(to right, white, red, white);',
                                                    'AUD' => 'background-color: #00008B;',
                                                    'JPY' => 'background: linear-gradient(to right, white, #BC002D, white);',
                                                    'CNY' => 'background-color: #DE2910;',
                                                    'INR' => 'background: linear-gradient(to right, #FF9933, white, #138808);',
                                                    'BRL' => 'background: linear-gradient(to right, green, yellow);',
                                                    'MXN' => 'background: linear-gradient(to right, green, white, red);',
                                                    'AED' => 'background: linear-gradient(to right, #000000, #FF0000, #00732F);',
                                                    'SAR' => 'background-color: #006C35;',
                                                    'TRY' => 'background-color: #E30A17;',
                                                    'RUB' => 'background: linear-gradient(to right, white, blue, red);',
                                                    'CHF' => 'background-color: #FF0000;',
                                                    'SEK' => 'background: linear-gradient(to right, #006AA7, #FECC00);',
                                                    'NOK' => 'background: linear-gradient(to right, red, white, blue);',
                                                    'DKK' => 'background: linear-gradient(to right, red, white);',
                                                    'PLN' => 'background: linear-gradient(to bottom, white, red);',
                                                    'THB' => 'background: linear-gradient(to right, red, white, blue);',
                                                    'MYR' => 'background: linear-gradient(to right, blue, yellow);',
                                                    'IDR' => 'background: linear-gradient(to bottom, red, white);',
                                                    'PHP' => 'background: linear-gradient(to right, blue, red);',
                                                    'PKR' => 'background: linear-gradient(to right, green, white);',
                                                    'BDT' => 'background: linear-gradient(to right, green, red);',
                                                    'EGP' => 'background: linear-gradient(to right, black, red, gold);',
                                                    'TWD' => 'background: linear-gradient(to right, blue, red);',
                                                    'HKD' => 'background-color: red;',
                                                    'SGD' => 'background: linear-gradient(to bottom, red, white);',
                                                    'NZD' => 'background-color: #00247D;',
                                                ];
                                                $bg = $flagColors[$bal->currency] ?? 'background-color:#334155;';
                                            @endphp

                                           <div class="col-md-6 col-xl-3 mb-4">
                                                <div class="currency-card d-flex flex-column" style="{{ $bg }}">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="small text-white-50 mb-2">Wallet</div>
                                                        @if($bal->is_locked)
                                                            <span class="custom-status status-danger" style="background: rgba(255,255,255,0.2); color:#fff;">
                                                                <i class="fa-solid fa-lock"></i> Locked
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <h5 class="mb-2">{{ $bal->name }}</h5>
                                                    <div class="h4 mb-0">{{ $bal->currency }} {{ number_format($bal->amount, 2) }}</div>

                                                    @if($bal->is_locked && $bal->locked_reason)
                                                        <div class="small text-white-50 mt-1">Reason: {{ $bal->locked_reason }}</div>
                                                    @endif

                                                    <div class="mt-3 d-flex flex-row gap-2 balance-actions">
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-soft-primary balance-action-btn"
                                                            data-mode="add"
                                                            data-user-id="{{ $user->id }}"
                                                            data-balance-id="{{ $bal->id }}"
                                                            data-balance-name="{{ $bal->name }}"
                                                            data-currency="{{ $bal->currency }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#balanceActionModal"
                                                            {{ $bal->is_locked ? 'disabled' : '' }}>
                                                            Add Money
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-soft-dark balance-action-btn"
                                                            data-mode="remove"
                                                            data-user-id="{{ $user->id }}"
                                                            data-balance-id="{{ $bal->id }}"
                                                            data-balance-name="{{ $bal->name }}"
                                                            data-currency="{{ $bal->currency }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#balanceActionModal"
                                                            {{ $bal->is_locked ? 'disabled' : '' }}>
                                                            Remove Money
                                                        </button>

                                                        <form method="POST" action="{{ route('admin.business.balance.toggle-lock', [$user->id, $bal->id]) }}" class="lock-toggle-form">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm {{ $bal->is_locked ? 'btn-soft-primary' : 'btn-soft-dark' }}">
                                                                <i class="fa-solid {{ $bal->is_locked ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                                                {{ $bal->is_locked ? 'Unlock' : 'Lock' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>


                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Virtual Cards</div>
                                    <div class="section-subtitle">Issued cards and their current status.</div>
                                </div>

                                <div class="card-body">
                                    @if($virtualCards->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-modern text-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Card ID</th>
                                                        <th>Type</th>
                                                        <th>Currency</th>
                                                        <th>Amount</th>
                                                        <th>Card Number</th>
                                                        <th>CVV</th>
                                                        <th>Expiry</th>
                                                        <th>Balance</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($virtualCards as $index => $card)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $card->card_id }}</td>
                                                            <td>{{ $card->card_type }}</td>
                                                            <td>{{ $card->currency }}</td>
                                                            <td>{{ number_format($card->amount, 2) }}</td>
                                                            <td>{{ $card->card_number }}</td>
                                                            <td>{{ $card->cvv }}</td>
                                                            <td>{{ $card->expiry_month }}/{{ $card->expiry_year }}</td>
                                                            <td>{{ number_format($card->balance, 2) }}</td>
                                                            <td>
                                                                @if($card->status == 'active')
                                                                    <span class="custom-status status-success">Active</span>
                                                                @elseif($card->status == 'inactive')
                                                                    <span class="custom-status status-muted">Inactive</span>
                                                                @elseif($card->status == 'blocked')
                                                                    <span class="custom-status status-danger">Blocked</span>
                                                                @else
                                                                    <span class="custom-status status-warning">Unknown</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="empty-state">No virtual cards found for this user.</div>
                                    @endif
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Team Members</div>
                                    <div class="section-subtitle">Internal members, permissions, and invite state.</div>
                                </div>

                                <div class="card-body">
                                    @if($teamMembers->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-modern text-center mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Permissions</th>
                                                        <th>Status</th>
                                                        <th>Invite Token</th>
                                                        <th>Token Expires</th>
                                                        <th>Used At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($teamMembers as $index => $member)
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $member->email }}</td>
                                                            <td>{{ $member->role }}</td>
                                                            <td>
                                                                @if($member->permissions)
                                                                    <span class="custom-status status-warning">
                                                                        {{ implode(', ', json_decode($member->permissions, true)) }}
                                                                    </span>
                                                                @else
                                                                    <span class="custom-status status-muted">None</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($member->status === 'active')
                                                                    <span class="custom-status status-success">Active</span>
                                                                @elseif($member->status === 'pending')
                                                                    <span class="custom-status status-warning">Pending</span>
                                                                @elseif($member->status === 'inactive')
                                                                    <span class="custom-status status-muted">Inactive</span>
                                                                @else
                                                                    <span class="custom-status status-danger">Unknown</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $member->invite_token ?? '—' }}</td>
                                                            <td>{{ $member->invite_token_expires_at ?? '—' }}</td>
                                                            <td>{{ $member->invite_token_used_at ?? '—' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="empty-state">No team members found.</div>
                                    @endif
                                </div>
                            </div>

                        
                        </div>

                        <div class="tab-content-section d-none" id="tab-profile">
                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Merchant Profile</div>
                                    <div class="section-subtitle">Deeper profile information can live here.</div>
                                </div>
                                <div class="card-body">
                                    <div class="empty-state">Add more merchant profile widgets, notes, timelines, or recent activity here.</div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content-section d-none" id="tab-accounts">
                            


                            @php
                                $ngnBalance = $balances->firstWhere('currency', 'NGN');
                            @endphp

                            <div class="dashboard-card banking-integrations-card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="section-title">Banking Integrations</div>
                                        <div class="section-subtitle">Submit this business's details to regional banking partners to provision settlement accounts.</div>
                                    </div>
                                    <span class="custom-status status-muted">
                                        <i class="pe-7s-link"></i> {{ ($ngnBalance && $ngnBalance->virtual_account_number ? 1 : 0) + ($user->blaaiz_id ? 1 : 0) }}/2 Connected
                                    </span>
                                </div>

                                <div class="card-body">
                                    <div class="bank-grid">

                                        {{-- Fidelity Bank (Nigeria) --}}
                                        <div class="bank-tile {{ $ngnBalance && $ngnBalance->virtual_account_number ? 'bank-tile-active' : '' }}">
                                            <div class="bank-tile-top">
                                                <div class="bank-flag-badge flag-ng">🇳🇬</div>
                                                <div>
                                                    <div class="bank-tile-name">Fidelity Bank</div>
                                                    <div class="bank-tile-region">Nigeria · NGN Settlements</div>
                                                </div>
                                                <div class="bank-tile-pulse {{ $ngnBalance && $ngnBalance->virtual_account_number ? 'pulse-on' : '' }}"></div>
                                            </div>

                                            @if($ngnBalance && $ngnBalance->virtual_account_number)
                                                <div class="bank-tile-body">
                                                    <div class="bank-detail-row">
                                                        <span>Account Number</span>
                                                        <strong>{{ $ngnBalance->virtual_account_number }}</strong>
                                                    </div>
                                                    <div class="bank-detail-row">
                                                        <span>Account Name</span>
                                                        <strong>{{ $ngnBalance->virtual_account_name ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="bank-detail-row">
                                                        <span>Bank</span>
                                                        <strong>{{ $ngnBalance->virtual_account_bank ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="bank-detail-row">
                                                        <span>Linked Wallet</span>
                                                        <strong>{{ $ngnBalance->name }}</strong>
                                                    </div>
                                                </div>
                                                <span class="custom-status status-success bank-tile-status">
                                                    <i class="pe-7s-check"></i> Virtual Account Active
                                                </span>
                                            @else
                                                <div class="bank-tile-body bank-tile-empty">
                                                    No virtual account has been provisioned for this business yet. Submitting will create a permanent NGN settlement account via Fidelity Bank.
                                                </div>
                                                <form method="POST" action="{{ route('admin.business.submit-fidelity', $user->id) }}" class="confirm-submit-form" data-confirm="Submit this business's details to Fidelity Bank to generate a virtual account?">
                                                    @csrf
                                                    <button type="submit" class="btn btn-soft-primary btn-sm w-100">
                                                        <i class="fa-solid fa-cloud-upload"></i> Submit to Fidelity Bank
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Blaaiz Interac (Canada) — unchanged, still on User --}}
                                        <div class="bank-tile {{ $user->blaaiz_id ? 'bank-tile-active' : '' }}">
                                            ... (leave as-is)
                                        </div>

                                    </div>
                                </div>
                            </div>


                            {{-- ── Currency Fees Card ─────────────────────────────────────────── --}}
                            <div class="dashboard-card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="section-title">Currency Fees</div>
                                        <div class="section-subtitle">
                                            Set collection and payout fees per currency for this merchant.
                                        </div>
                                    </div>
                                    <span class="custom-status status-muted">
                                        <i class="pe-7s-config"></i>
                                        {{ $currencyFees->where('collection_enabled', true)->count() +
                                        $currencyFees->where('payout_enabled', true)->count() }} active fee rules
                                    </span>
                                </div>

                                <div class="card-body">

                                    {{-- Currency Pills --}}
                                    <div class="d-flex flex-wrap gap-2 mb-4" id="currencyPills">
                                        @foreach (\App\Models\UserCurrencyFee::CURRENCIES as $cur)
                                            @php
                                                $fee = $currencyFees->get($cur);
                                                $isActive = $fee && ($fee->collection_enabled || $fee->payout_enabled);
                                            @endphp
                                            <button
                                                type="button"
                                                class="btn btn-sm currency-pill {{ $isActive ? 'btn-soft-primary' : 'btn-outline-secondary' }}"
                                                data-currency="{{ $cur }}"
                                                style="border-radius: 999px; font-weight: 700; min-width: 64px;">
                                                {{ $cur }}
                                                @if($isActive)
                                                    <span style="font-size:10px;">●</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- Fee Panels per Currency --}}
                                    @foreach (\App\Models\UserCurrencyFee::CURRENCIES as $cur)
                                        @php
                                            $fee = $currencyFees->get($cur);
                                        @endphp

                                        <div class="currency-fee-panel d-none" id="fee-panel-{{ $cur }}">
                                            <div class="soft-panel mb-0">

                                                <div class="row g-4">

                                                    {{-- Collection --}}
                                                    <div class="col-md-6">
                                                        <div style="border: 1px solid var(--border-soft); border-radius: 18px; padding: 20px; background: #fff;">
                                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                                <div>
                                                                    <div class="fw-bold" style="font-size:15px;">
                                                                        <i class="pe-7s-cash text-success me-1"></i> Collection
                                                                    </div>
                                                                    <div class="text-muted" style="font-size:12px;">
                                                                        Fees charged when {{ $cur }} is collected
                                                                    </div>
                                                                </div>
                                                                <div class="form-check form-switch mb-0">
                                                                    <input
                                                                        class="form-check-input fee-toggle"
                                                                        type="checkbox"
                                                                        id="col_enabled_{{ $cur }}"
                                                                        data-currency="{{ $cur }}"
                                                                        data-type="collection"
                                                                        {{ $fee && $fee->collection_enabled ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        % Fee
                                                                    </label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input
                                                                            type="number"
                                                                            class="form-control fee-input"
                                                                            id="col_percent_{{ $cur }}"
                                                                            step="0.0001" min="0" max="100"
                                                                            placeholder="0.00"
                                                                            value="{{ $fee->collection_percent ?? 0 }}">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Fixed Fee
                                                                    </label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">{{ $cur }}</span>
                                                                        <input
                                                                            type="number"
                                                                            class="form-control fee-input"
                                                                            id="col_fixed_{{ $cur }}"
                                                                            step="0.01" min="0"
                                                                            placeholder="0.00"
                                                                            value="{{ $fee->collection_fixed ?? 0 }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Min Amount
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control form-control-sm fee-input"
                                                                        id="col_min_{{ $cur }}"
                                                                        step="0.01" min="0"
                                                                        placeholder="0.00"
                                                                        value="{{ $fee->collection_min ?? 0 }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Max Amount
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control form-control-sm fee-input"
                                                                        id="col_max_{{ $cur }}"
                                                                        step="0.01" min="0"
                                                                        placeholder="0.00"
                                                                        value="{{ $fee->collection_max ?? 0 }}">
                                                                </div>
                                                            </div>

                                                            {{-- Live Fee Preview --}}
                                                            <div class="mt-3 p-2 rounded" style="background: #f0fdf4; font-size:12px;">
                                                                <span class="text-muted">Preview on</span>
                                                                <input
                                                                    type="number"
                                                                    class="form-control form-control-sm d-inline-block mx-1 fee-preview-input"
                                                                    style="width:90px;"
                                                                    placeholder="amount"
                                                                    data-currency="{{ $cur }}"
                                                                    data-type="collection">
                                                                <strong class="text-success fee-preview-result" id="col_preview_{{ $cur }}">
                                                                    Fee: —
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Payout --}}
                                                    <div class="col-md-6">
                                                        <div style="border: 1px solid var(--border-soft); border-radius: 18px; padding: 20px; background: #fff;">
                                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                                <div>
                                                                    <div class="fw-bold" style="font-size:15px;">
                                                                        <i class="pe-7s-upload text-primary me-1"></i> Payout
                                                                    </div>
                                                                    <div class="text-muted" style="font-size:12px;">
                                                                        Fees charged when {{ $cur }} is paid out
                                                                    </div>
                                                                </div>
                                                                <div class="form-check form-switch mb-0">
                                                                    <input
                                                                        class="form-check-input fee-toggle"
                                                                        type="checkbox"
                                                                        id="pay_enabled_{{ $cur }}"
                                                                        data-currency="{{ $cur }}"
                                                                        data-type="payout"
                                                                        {{ $fee && $fee->payout_enabled ? 'checked' : '' }}>
                                                                </div>
                                                            </div>

                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        % Fee
                                                                    </label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input
                                                                            type="number"
                                                                            class="form-control fee-input"
                                                                            id="pay_percent_{{ $cur }}"
                                                                            step="0.0001" min="0" max="100"
                                                                            placeholder="0.00"
                                                                            value="{{ $fee->payout_percent ?? 0 }}">
                                                                        <span class="input-group-text">%</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Fixed Fee
                                                                    </label>
                                                                    <div class="input-group input-group-sm">
                                                                        <span class="input-group-text">{{ $cur }}</span>
                                                                        <input
                                                                            type="number"
                                                                            class="form-control fee-input"
                                                                            id="pay_fixed_{{ $cur }}"
                                                                            step="0.01" min="0"
                                                                            placeholder="0.00"
                                                                            value="{{ $fee->payout_fixed ?? 0 }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Min Amount
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control form-control-sm fee-input"
                                                                        id="pay_min_{{ $cur }}"
                                                                        step="0.01" min="0"
                                                                        placeholder="0.00"
                                                                        value="{{ $fee->payout_min ?? 0 }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label" style="font-size:12px; font-weight:700;">
                                                                        Max Amount
                                                                    </label>
                                                                    <input
                                                                        type="number"
                                                                        class="form-control form-control-sm fee-input"
                                                                        id="pay_max_{{ $cur }}"
                                                                        step="0.01" min="0"
                                                                        placeholder="0.00"
                                                                        value="{{ $fee->payout_max ?? 0 }}">
                                                                </div>
                                                            </div>

                                                            {{-- Live Fee Preview --}}
                                                            <div class="mt-3 p-2 rounded" style="background: #eff6ff; font-size:12px;">
                                                                <span class="text-muted">Preview on</span>
                                                                <input
                                                                    type="number"
                                                                    class="form-control form-control-sm d-inline-block mx-1 fee-preview-input"
                                                                    style="width:90px;"
                                                                    placeholder="amount"
                                                                    data-currency="{{ $cur }}"
                                                                    data-type="payout">
                                                                <strong class="text-primary fee-preview-result" id="pay_preview_{{ $cur }}">
                                                                    Fee: —
                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- Save Button --}}
                                                <div class="mt-4 d-flex align-items-center gap-3">
                                                    <button
                                                        type="button"
                                                        class="btn btn-soft-primary save-fee-btn"
                                                        data-currency="{{ $cur }}"
                                                        data-user-id="{{ $user->id }}">
                                                        <i class="pe-7s-diskette me-1"></i> Save {{ $cur }} Fees
                                                    </button>
                                                    <span class="save-feedback text-success d-none" id="feedback_{{ $cur }}" style="font-size:13px; font-weight:600;">
                                                        ✓ Saved successfully
                                                    </span>
                                                    <span class="save-error text-danger d-none" id="error_{{ $cur }}" style="font-size:13px; font-weight:600;">
                                                        ✗ Save failed
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>





                            @php
                                $accountCollections = [
                                    'Beneficia' => $beneficia,
                                    // 'Customers' => $customer,
                                    // 'Bank Accounts' => $bankAccount,
                                    // 'Subaccounts' => $Subaccount
                                ];
                            @endphp

                            @foreach ($accountCollections as $title => $collection)
                                <div class="dashboard-card">
                                    <div class="card-header">
                                        <div class="section-title">{{ $title }}</div>
                                        <div class="section-subtitle">Linked account records under {{ strtolower($title) }}.</div>
                                    </div>

                                    <div class="card-body">
                                        @if ($collection->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-modern mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th class="text-center">Account Name</th>
                                                            <th class="text-center">Bank</th>
                                                            <th class="text-center">Account Number</th>
                                                            <th class="text-center">Country</th>
                                                            <th class="text-center">Currency</th>
                                                            <th class="text-center">Type</th>
                                                            <th>Created</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($collection as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td class="text-center">{{ $item->account_name }}</td>
                                                                <td class="text-center">{{ $item->bank }}</td>
                                                                <td class="text-center">{{ $item->account_number }}</td>
                                                                <td class="text-center">{{ $item->country }}</td>
                                                                <td class="text-center">{{ $item->currency }}</td>
                                                                <td class="text-center">{{ $item->type }}</td>
                                                                <td>{{ $item->created_at ?? 'N/A' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="empty-state">No {{ $title }} found.</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach


                            @include('admin.referrals-list')
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Balance Action Modal -->
    <div class="modal fade" id="balanceActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered shadow-none">
            <div class="modal-content border-0 shadow-none" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
                <form id="balanceActionForm" method="POST">
                    @csrf
                    
                    <!-- Header -->
                    <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 60%, #0ea5e9 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 text-white" style="width: 44px; height: 44px; background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); font-size: 1.15rem;">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0" id="balanceActionTitle">Update Balance</h5>
                                <small class="text-white-50" style="font-size: 0.78rem;">Manage business account balance</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body p-4" style="background-color: #ffffff;">
                        
                        <!-- Meta Description Banner -->
                        <div class="p-3 mb-4 rounded-3 d-flex align-items-center gap-2" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                            <i class="fa-solid fa-circle-info text-primary fs-5 flex-shrink-0"></i>
                            <span class="fw-semibold text-dark small" id="balanceActionMeta"></span>
                        </div>

                        <!-- Amount Input -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small mb-2">
                                <i class="fa-solid fa-money-bill-wave me-1 text-primary"></i> Amount
                            </label>
                            <div class="input-group input-group-lg" style="box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);">
                                <span class="input-group-text bg-light border-end-0 text-muted fw-bold" style="border-top-left-radius: 12px; border-bottom-left-radius: 12px; border-color: #cbd5e1;">
                                    <i class="fa-solid fa-coins"></i>
                                </span>
                                <input type="number" name="amount" step="0.01" min="0.01" class="form-control form-control-lg border-start-0 fw-bold text-dark" placeholder="0.00" style="border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-color: #cbd5e1; font-size: 1.1rem;" required>
                            </div>
                        </div>

                        <!-- Note Input -->
                        <div class="mb-2">
                            <label class="form-label fw-bold text-dark small mb-2">
                                <i class="fa-solid fa-note-sticky me-1 text-secondary"></i> Note <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <textarea name="note" class="form-control p-3" rows="2" maxlength="255" placeholder="Reason or description for this action..." style="border-radius: 12px; border-color: #cbd5e1; resize: none; font-size: 0.9rem;"></textarea>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-top-0 px-4 pb-4 pt-2" style="background-color: #ffffff;">
                        <button type="button" class="btn btn-light rounded-3 px-4 fw-bold text-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="balanceActionSubmitBtn" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #2563eb, #1d4ed8); border: 0;">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@include('admin.footer')

<script>
document.querySelectorAll('.balance-action-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
        const mode = this.dataset.mode;
        const userId = this.dataset.userId;
        const balanceId = this.dataset.balanceId;
        const balanceName = this.dataset.balanceName;
        const currency = this.dataset.currency;

        const form = document.getElementById('balanceActionForm');
        const title = document.getElementById('balanceActionTitle');
        const meta = document.getElementById('balanceActionMeta');
        const submitBtn = document.getElementById('balanceActionSubmitBtn');

        const addUrl = "{{ url('/admin/business-account') }}/" + userId + "/balance/" + balanceId + "/add-money";
        const removeUrl = "{{ url('/admin/business-account') }}/" + userId + "/balance/" + balanceId + "/remove-money";

        if (mode === 'remove') {
            form.action = removeUrl;
            title.textContent = 'Remove Money';
            meta.textContent = `Deduct from ${balanceName} (${currency})`;
            submitBtn.textContent = 'Remove';
        } else {
            form.action = addUrl;
            title.textContent = 'Add Money';
            meta.textContent = `Fund ${balanceName} (${currency})`;
            submitBtn.textContent = 'Add';
        }
    });
});
</script>

<script>
    const tabs = document.querySelectorAll('.dashboard-tabs .nav-link');
    const sections = document.querySelectorAll('.tab-content-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active', 'show'));
            this.classList.add('active', 'show');
            sections.forEach(sec => sec.classList.add('d-none'));
            const target = this.getAttribute('data-tab');
            document.getElementById('tab-' + target).classList.remove('d-none');
        });
    });
</script>

<script>
    document.getElementById("toggleProfileBtn").onclick = function () {
        document.getElementById("profileDetails").classList.toggle("d-none");
    };
</script>

<script>
    //
    document.querySelectorAll('.change-status').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            let status = this.dataset.status;
            let field = this.dataset.field;
            let userId = this.dataset.id;

            fetch("{{ url('/admin/business-account-status') }}/" + userId, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    field: field,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Find the correct badge directly using the field
                    const badge = document.querySelector(
                        `.status-badge[data-status-field="${field}"]`
                    );

                    if (!badge) {
                        console.error('Could not find .status-badge inside the row.', field);
                        return;
                    }
                    
                    // Update badge text
                    badge.textContent = data.label;
                    badge.className = 'custom-status status-badge ';
                    if (data.class.includes('success')) {
                        badge.classList.add('status-success');
                    } else if (data.class.includes('warning')) {
                        badge.classList.add('status-warning');
                    } else if (data.class.includes('danger')) {
                        badge.classList.add('status-danger');
                    } else {
                        badge.classList.add('status-muted');
                    }
                }
            })
            .catch(error => {
                console.error('Status update error:', error);
            });
        });
    });










    document.querySelectorAll('.confirm-submit-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const message = this.dataset.confirm || 'Are you sure you want to submit this?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
</script>

<script>
// ── Currency Pill Toggle ───────────────────────────────────────────────────
document.querySelectorAll('.currency-pill').forEach(pill => {
    pill.addEventListener('click', function () {
        const cur = this.dataset.currency;

        // Hide all panels
        document.querySelectorAll('.currency-fee-panel').forEach(p => p.classList.add('d-none'));

        // Deactivate all pills (keep active style if fee is on)
        document.querySelectorAll('.currency-pill').forEach(p => {
            p.classList.remove('btn-soft-primary');
            p.classList.add('btn-outline-secondary');
        });

        // Show selected panel
        document.getElementById('fee-panel-' + cur).classList.remove('d-none');

        // Highlight selected pill
        this.classList.remove('btn-outline-secondary');
        this.classList.add('btn-soft-primary');
    });
});

// ── Live Fee Preview ───────────────────────────────────────────────────────
document.querySelectorAll('.fee-preview-input').forEach(input => {
    input.addEventListener('input', function () {
        const cur    = this.dataset.currency;
        const type   = this.dataset.type; // collection | payout
        const amount = parseFloat(this.value) || 0;
        const prefix = type === 'collection' ? 'col' : 'pay';

        const pct   = parseFloat(document.getElementById(prefix + '_percent_' + cur)?.value) || 0;
        const fixed = parseFloat(document.getElementById(prefix + '_fixed_' + cur)?.value) || 0;
        const fee   = ((amount * pct) / 100) + fixed;

        const resultEl = document.getElementById(prefix + '_preview_' + cur);
        if (resultEl) {
            resultEl.textContent = 'Fee: ' + cur + ' ' + fee.toFixed(2)
                + ' → Net: ' + cur + ' ' + (amount - fee).toFixed(2);
        }
    });
});

// ── Save Fee ───────────────────────────────────────────────────────────────
document.querySelectorAll('.save-fee-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const cur    = this.dataset.currency;
        const userId = this.dataset.userId;

        const payload = {
            collection_enabled : document.getElementById('col_enabled_' + cur)?.checked ? 1 : 0,
            collection_percent : document.getElementById('col_percent_' + cur)?.value || 0,
            collection_fixed   : document.getElementById('col_fixed_'   + cur)?.value || 0,
            collection_min     : document.getElementById('col_min_'     + cur)?.value || 0,
            collection_max     : document.getElementById('col_max_'     + cur)?.value || 0,

            payout_enabled     : document.getElementById('pay_enabled_' + cur)?.checked ? 1 : 0,
            payout_percent     : document.getElementById('pay_percent_' + cur)?.value || 0,
            payout_fixed       : document.getElementById('pay_fixed_'   + cur)?.value || 0,
            payout_min         : document.getElementById('pay_min_'     + cur)?.value || 0,
            payout_max         : document.getElementById('pay_max_'     + cur)?.value || 0,

            _method: 'PUT',
        };

        const feedbackEl = document.getElementById('feedback_' + cur);
        const errorEl    = document.getElementById('error_'    + cur);
        feedbackEl.classList.add('d-none');
        errorEl.classList.add('d-none');

        btn.disabled = true;
        btn.textContent = 'Saving…';

        fetch(`/admin/business-account/${userId}/currency-fee/${cur}`, {
            method: 'POST',
            headers: {
                'Content-Type'  : 'application/json',
                'X-CSRF-TOKEN'  : '{{ csrf_token() }}',
            },
            body: JSON.stringify(payload),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                feedbackEl.classList.remove('d-none');
                setTimeout(() => feedbackEl.classList.add('d-none'), 3000);
            } else {
                errorEl.classList.remove('d-none');
            }
        })
        .catch(() => errorEl.classList.remove('d-none'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="pe-7s-diskette me-1"></i> Save ' + cur + ' Fees';
        });
    });
});
</script>

<script>
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

                    const id = document.getElementById('broadcastForm').dataset.userId;

                    fetch(`/admin/business-pushnotification/${id}`, {
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


    // update verification status
    document.querySelectorAll('.verification-status-select').forEach(select => {

        select.addEventListener('change', function () {

            const field = this.dataset.field;
            const userId = this.dataset.userId;
            const badgeId = this.dataset.badge;
            const status = this.value;

            const badge = document.getElementById(badgeId);

            // Disable while updating
            this.disabled = true;

            fetch("{{ url('/admin/business-account-status') }}/" + userId, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    field: field,
                    status: status
                })
            })
            .then(response => response.json())
            .then(data => {

                if (!data.success) {
                    throw new Error(data.message || 'Failed to update status.');
                }

                // Update badge text
                badge.textContent = data.label;

                // Reset badge classes
                badge.className = 'custom-status';

                // Apply correct status color
                if (data.class.includes('success')) {
                    badge.classList.add('status-success');
                } else if (data.class.includes('warning')) {
                    badge.classList.add('status-warning');
                } else if (data.class.includes('danger')) {
                    badge.classList.add('status-danger');
                } else {
                    badge.classList.add('status-muted');
                }

            })
            .catch(error => {

                console.error(error);

                alert('Unable to update verification status.');

                // Reload to restore original value
                location.reload();

            })
            .finally(() => {
                this.disabled = false;
            });

        });

    });
</script>