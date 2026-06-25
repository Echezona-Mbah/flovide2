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

    .btn-soft-dark:hover {
        color: #fff;
        opacity: 0.96;
    }
    .balance-actions .btn { flex: 1; white-space: nowrap; }
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
                                                    <i class="fa-solid fa-user"></i>
                                                    Personal Account Dashboard
                                                </div>
                                                <h2 class="mb-1 text-white">{{ $user->firstname }} {{ $user->lastname }}</h2>
                                                <p class="mb-0 text-white-50">{{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 720px; line-height: 1.8;">
                                            A better view of this user’s profile, balances, cards, and account-linked records with a cleaner and more modern layout.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>User ID</small>
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
                                                    <small>Phone Number</small>
                                                    <strong>{{ $user->person_phone ?? 'Not provided' }}</strong>
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
                                <a role="tab" class="nav-link" href="{{ route('admin.personal-transactions.history', $user->id) }}">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" href="{{ route('admin.personal-testmode', $user->id) }}">Test Mode</a>
                            </li>
                        </ul>

                        <div class="tab-content-section" id="tab-sales">
                            <div class="stat-grid mb-4">
                                <div class="stat-card">
                                    <div class="stat-icon icon-blue">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div class="stat-label">Full Name</div>
                                    <div class="stat-value">{{ $user->firstname }} {{ $user->lastname }}</div>
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
                                        <i class="fa-solid fa-credit-card"></i>
                                    </div>
                                    <div class="stat-label">Virtual Cards</div>
                                    <div class="stat-value">{{ $virtualCards->count() }}</div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <div class="section-title">Personal Profile</div>
                                        <div class="section-subtitle">Identity and location details for this user.</div>
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
                                            <tr><th>Country</th><td>{{ $user->country ?? 'N/A' }}</td></tr>
                                            <tr><th>Address</th><td>{{ $user->street_address ?? 'N/A' }}</td></tr>
                                            <tr><th>Personal Number</th><td>{{ $user->person_phone ?? 'N/A' }}</td></tr>
                                            <tr><th>City</th><td>{{ $user->city ?? 'N/A' }}</td></tr>
                                            <tr><th>State</th><td>{{ $user->state ?? 'N/A' }}</td></tr>
                                            <tr><th>Currency</th><td>{{ $user->currency ?? 'N/A' }}</td></tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Account Status</div>
                                    <div class="section-subtitle">Quick verification and account summary.</div>
                                </div>

                                <div class="card-body">
                                    <div class="stat-grid">
                                        <div class="stat-card">
                                            <div class="stat-icon icon-green">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <div class="stat-label">Verification Status</div>
                                            <div class="stat-value">{{ $user->email_verified_status == 'yes' ? 'Verified' : 'Pending' }}</div>
                                        </div>

                                        <div class="stat-card">
                                            <div class="stat-icon icon-blue">
                                                <i class="fa-solid fa-map-marker"></i>
                                            </div>
                                            <div class="stat-label">Country</div>
                                            <div class="stat-value">{{ $user->country ?? 'N/A' }}</div>
                                        </div>

                                        <div class="stat-card">
                                            <div class="stat-icon icon-orange">
                                                <i class="fa-solid fa-wallet"></i>
                                            </div>
                                            <div class="stat-label">Currency</div>
                                            <div class="stat-value">{{ $user->currency ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content-section d-none" id="tab-activity">
                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Balances</div>
                                    <div class="section-subtitle">Current wallet balances across supported currencies.</div>
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
                class="btn btn-sm btn-soft-primary personal-balance-action-btn"
                data-mode="add"
                data-user-id="{{ $user->id }}"
                data-balance-id="{{ $bal->id }}"
                data-balance-name="{{ $bal->name }}"
                data-currency="{{ $bal->currency }}"
                data-bs-toggle="modal"
                data-bs-target="#personalBalanceActionModal">
                Add Money
            </button>

            <button
                type="button"
                class="btn btn-sm btn-soft-dark personal-balance-action-btn"
                data-mode="remove"
                data-user-id="{{ $user->id }}"
                data-balance-id="{{ $bal->id }}"
                data-balance-name="{{ $bal->name }}"
                data-currency="{{ $bal->currency }}"
                data-bs-toggle="modal"
                data-bs-target="#personalBalanceActionModal">
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
                                    <div class="section-subtitle">Issued cards, balances, and current status.</div>
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
                        </div>

                        <div class="tab-content-section d-none" id="tab-profile">
                            <div class="dashboard-card">
                                <div class="card-header">
                                    <div class="section-title">Profile Information</div>
                                    <div class="section-subtitle">Use this space for more personal account data, notes, or activity history.</div>
                                </div>
                                <div class="card-body">
                                    <div class="empty-state">Add profile widgets, transaction notes, or verification history here.</div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content-section d-none" id="tab-accounts">
                            @php
                                $accountCollections = [
                                    'Beneficia' => $beneficia,
                                    'Customers' => $customer,
                                    'Bank Accounts' => $bankAccount,
                                    'Subaccounts' => $Subaccount
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
<div class="modal fade" id="personalBalanceActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
            <form id="personalBalanceActionForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="personalBalanceActionTitle">Update Balance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2 text-muted" id="personalBalanceActionMeta"></p>
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
                    <button type="submit" id="personalBalanceActionSubmitBtn" class="btn btn-soft-primary">Submit</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
document.querySelectorAll('.personal-balance-action-btn').forEach((btn) => {
    btn.addEventListener('click', function () {
        const mode = this.dataset.mode;
        const userId = this.dataset.userId;
        const balanceId = this.dataset.balanceId;
        const balanceName = this.dataset.balanceName;
        const currency = this.dataset.currency;

        const form = document.getElementById('personalBalanceActionForm');
        const title = document.getElementById('personalBalanceActionTitle');
        const meta = document.getElementById('personalBalanceActionMeta');
        const submitBtn = document.getElementById('personalBalanceActionSubmitBtn');

        const addUrl = "{{ url('/admin/personal-account') }}/" + userId + "/balance/" + balanceId + "/add-money";
        const removeUrl = "{{ url('/admin/personal-account') }}/" + userId + "/balance/" + balanceId + "/remove-money";

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
