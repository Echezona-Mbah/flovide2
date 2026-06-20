@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-deep: #1d4ed8;
        --brand-sky: #0ea5e9;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --border-soft: #e2e8f0;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
        --success-soft: rgba(22, 163, 74, 0.12);
        --warning-soft: rgba(245, 158, 11, 0.14);
        --danger-soft: rgba(220, 38, 38, 0.12);
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
                                                    <i class="pe-7s-portfolio"></i>
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
                        </ul>

                        <div class="tab-content-section" id="tab-sales">
                            <div class="stat-grid mb-4">
                                <div class="stat-card">
                                    <div class="stat-icon icon-blue">
                                        <i class="pe-7s-id"></i>
                                    </div>
                                    <div class="stat-label">Business Name</div>
                                    <div class="stat-value">{{ $user->business_name ?? 'N/A' }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-green">
                                        <i class="pe-7s-check"></i>
                                    </div>
                                    <div class="stat-label">Email Verification</div>
                                    <div class="stat-value">{{ $user->email_verified_status == 'yes' ? 'Verified' : 'Pending' }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-orange">
                                        <i class="pe-7s-cash"></i>
                                    </div>
                                    <div class="stat-label">Wallet Count</div>
                                    <div class="stat-value">{{ $balances->count() }}</div>
                                </div>

                                <div class="stat-card">
                                    <div class="stat-icon icon-red">
                                        <i class="pe-7s-users"></i>
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

                                                        <td class="text-center">
                                                            {{-- @if($doc['file'])
                                                                @php
                                                                    $filePath = 'storage/' . $doc['file'];
                                                                    $extension = pathinfo($doc['file'], PATHINFO_EXTENSION);
                                                                @endphp

                                                                <a href="{{ asset($filePath) }}" target="_blank" download>
                                                                    @if(in_array(strtolower($extension), ['jpg','jpeg','png','webp']))
                                                                        <img src="{{ asset($filePath) }}" class="compliance-thumb" onerror="this.src='{{ asset('assets/dashboard/file.png') }}'">
                                                                    @else
                                                                        <i class="fa fa-file-pdf fa-2x text-danger"></i>
                                                                    @endif
                                                                </a>
                                                            @else
                                                                <span class="custom-status status-danger">No File</span>
                                                            @endif --}}

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
                                                        </td>

                                                        <td class="text-center">
                                                            @php
                                                                $status = $doc['status'] ?? 'not submitted';
                                                                $badgeClass = 'status-muted';
                                                                if ($status === 'confirmed') $badgeClass = 'status-success';
                                                                elseif ($status === 'under_review') $badgeClass = 'status-warning';
                                                                elseif ($status === 'rejected') $badgeClass = 'status-danger';
                                                            @endphp
                                                            <span class="custom-status status-badge {{ $badgeClass }}">
                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                            </span>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="dropdown d-inline-block">
                                                                <button type="button" data-bs-toggle="dropdown" class="btn btn-soft-primary btn-sm">
                                                                    Update
                                                                </button>

                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a href="#" class="dropdown-item change-status" data-status="under_review" data-field="{{ $doc['field'] }}" data-id="{{ $user->id }}">Under Review</a>
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
                                                    <div class="small text-white-50 mb-2">Wallet</div>
                                                    <h5 class="mb-2">{{ $bal->name }}</h5>
                                                    <div class="h4 mb-0">{{ $bal->currency }} {{ number_format($bal->amount, 2) }}</div>

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
                                                            data-bs-target="#balanceActionModal">
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
                                                            data-bs-target="#balanceActionModal">
                                                            Remove Money
                                                        </button>
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


                            <div class="dashboard-card banking-integrations-card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="section-title">Banking Integrations</div>
                                        <div class="section-subtitle">Submit this business's details to regional banking partners to provision settlement accounts.</div>
                                    </div>
                                    <span class="custom-status status-muted">
                                        <i class="pe-7s-link"></i> {{ ($user->virtual_account_number ? 1 : 0) + ($user->blaaiz_id ? 1 : 0) }}/2 Connected
                                    </span>
                                </div>

                                <div class="card-body">
                                    <div class="bank-grid">

                                        {{-- Fidelity Bank (Nigeria) --}}
                                        <div class="bank-tile {{ $user->virtual_account_number ? 'bank-tile-active' : '' }}">
                                            <div class="bank-tile-top">
                                                <div class="bank-flag-badge flag-ng">🇳🇬</div>
                                                <div>
                                                    <div class="bank-tile-name">Fidelity Bank</div>
                                                    <div class="bank-tile-region">Nigeria · NGN Settlements</div>
                                                </div>
                                                <div class="bank-tile-pulse {{ $user->virtual_account_number ? 'pulse-on' : '' }}"></div>
                                            </div>

                                            @if($user->virtual_account_number)
                                                <div class="bank-tile-body">
                                                    <div class="bank-detail-row">
                                                        <span>Account Number</span>
                                                        <strong>{{ $user->virtual_account_number }}</strong>
                                                    </div>
                                                    <div class="bank-detail-row">
                                                        <span>Account Name</span>
                                                        <strong>{{ $user->virtual_account_name ?? 'N/A' }}</strong>
                                                    </div>
                                                    <div class="bank-detail-row">
                                                        <span>Bank</span>
                                                        <strong>{{ $user->virtual_account_bank ?? 'N/A' }}</strong>
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
                                                        <i class="pe-7s-cloud-upload"></i> Submit to Fidelity Bank
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Blaaiz Interac (Canada) --}}
                                        <div class="bank-tile {{ $user->blaaiz_id ? 'bank-tile-active' : '' }}">
                                            <div class="bank-tile-top">
                                                <div class="bank-flag-badge flag-ca">🇨🇦</div>
                                                <div>
                                                    <div class="bank-tile-name">Blaaiz Interac</div>
                                                    <div class="bank-tile-region">Canada · CAD Settlements</div>
                                                </div>
                                                <div class="bank-tile-pulse {{ $user->blaaiz_id ? 'pulse-on' : '' }}"></div>
                                            </div>

                                            @if($user->blaaiz_id)
                                                <div class="bank-tile-body">
                                                    <div class="bank-detail-row">
                                                        <span>Blaaiz Customer ID</span>
                                                        <strong>{{ $user->blaaiz_id }}</strong>
                                                    </div>
                                                </div>
                                                <span class="custom-status status-success bank-tile-status">
                                                    <i class="pe-7s-check"></i> Registered with Blaaiz
                                                </span>
                                            @else
                                                <div class="bank-tile-body bank-tile-empty">
                                                    This business has not been registered with Blaaiz yet. Submitting will enable Interac e-Transfer collections for this account.
                                                </div>
                                                <form method="POST" action="{{ route('admin.business.submit-blaaiz', $user->id) }}" class="confirm-submit-form" data-confirm="Submit this business's details to Blaaiz to enable Interac transfers?">
                                                    @csrf
                                                    <button type="submit" class="btn btn-soft-dark btn-sm w-100">
                                                        <i class="pe-7s-cloud-upload"></i> Submit to Blaaiz Interac
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                    </div>
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
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="balanceActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px;">
            <form id="balanceActionForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="balanceActionTitle">Update Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-2 text-muted" id="balanceActionMeta"></p>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Note (optional)</label>
                        <input type="text" name="note" class="form-control" maxlength="255">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="balanceActionSubmitBtn" class="btn btn-soft-primary">Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
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
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    field: field,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let badge = this.closest('tr').querySelector('.status-badge');
                    badge.innerHTML = data.label;

                    badge.className = 'custom-status status-badge ';
                    if (data.class.includes('success')) {
                        badge.className += 'status-success';
                    } else if (data.class.includes('warning')) {
                        badge.className += 'status-warning';
                    } else if (data.class.includes('danger')) {
                        badge.className += 'status-danger';
                    } else {
                        badge.className += 'status-muted';
                    }
                }
            });
        });
    });
</script>
<script>
    document.querySelectorAll('.confirm-submit-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            const message = this.dataset.confirm || 'Are you sure you want to submit this?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
</script>