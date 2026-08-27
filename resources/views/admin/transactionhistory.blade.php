@include('admin.head')

<style>
    :root {
        --tx-ink: #14213d;
        --tx-ink-soft: #64748b;
        --tx-paper: #ffffff;
        --tx-paper-soft: #f8fafc;
        --tx-line: #e2e8f0;
        --tx-blue: #2563eb;
        --tx-blue-deep: #0f2c73;
        --tx-cyan: #0891b2;
        --tx-green: #16a34a;
        --tx-red: #dc2626;
        --tx-amber: #d97706;
        --tx-shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .tx-page {
        padding-bottom: 32px;
    }

    .tx-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(8,145,178,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }

    .tx-hero .card-body {
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
        margin: 24px 0;
    }

    .summary-card {
        background: var(--tx-paper);
        border: 1px solid var(--tx-line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--tx-shadow);
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

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: var(--tx-blue); }
    .icon-green { background: rgba(22, 163, 74, 0.12); color: var(--tx-green); }
    .icon-red { background: rgba(220, 38, 38, 0.12); color: var(--tx-red); }
    .icon-amber { background: rgba(217, 119, 6, 0.12); color: var(--tx-amber); }

    .summary-label {
        font-size: 13px;
        color: var(--tx-ink-soft);
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--tx-ink);
        line-height: 1.1;
    }

    .tx-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--tx-paper);
        box-shadow: var(--tx-shadow);
    }

    .tx-card .card-header {
        border: 0;
        padding: 22px 24px;
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--tx-ink);
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: var(--tx-ink-soft);
        font-size: 13px;
        margin-bottom: 0;
    }

    .search-toolbar {
    padding: 0 24px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 280px;
}

.search-box i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--tx-ink-soft);
    font-size: 15px;
}

.search-input {
    width: 100%;
    height: 52px;
    border-radius: 18px !important;
    border: 1px solid #dbe3ee !important;
    background: linear-gradient(180deg, #ffffff, #f8fbff);
    padding-left: 46px;
    padding-right: 18px;
    color: var(--tx-ink);
    font-weight: 600;
    box-shadow: 0 10px 28px rgba(20, 33, 61, 0.06) !important;
}

.search-input::placeholder {
    color: #94a3b8;
    font-weight: 500;
}

.search-input:focus {
    border-color: var(--tx-blue) !important;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12) !important;
}

.per-page-control {
    height: 52px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 7px 8px 7px 16px;
    border: 1px solid #dbe3ee;
    border-radius: 18px;
    background: #ffffff;
    box-shadow: 0 10px 28px rgba(20, 33, 61, 0.06);
}

.per-page-control label {
    margin: 0;
    color: var(--tx-ink-soft);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    white-space: nowrap;
}

.per-page-select {
    height: 38px;
    min-width: 82px;
    border: 0 !important;
    border-radius: 13px !important;
    background-color: #eef3fa;
    color: var(--tx-ink);
    font-weight: 800;
    box-shadow: none !important;
    cursor: pointer;
}

.per-page-select:focus {
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
}

@media (max-width: 576px) {
    .search-toolbar {
        padding: 0 16px 18px;
        align-items: stretch;
    }

    .search-box,
    .per-page-control {
        width: 100%;
    }

    .per-page-control {
        justify-content: space-between;
    }

    .per-page-select {
        min-width: 110px;
    }
}

    .btn-brand,
    .btn-soft,
    .btn-view,
    .btn-charge,
    .btn-delete {
        border: 0;
        border-radius: 14px;
        font-weight: 700;
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--tx-blue), var(--tx-blue-deep));
        color: #fff;
        padding: 11px 16px;
    }

    .btn-soft {
        background: #eef3fa;
        color: #334155;
        padding: 11px 16px;
    }

    .btn-view {
        background: rgba(37, 99, 235, 0.12);
        color: var(--tx-blue);
        padding: 8px 14px;
    }

    .btn-charge {
        background: rgba(217, 119, 6, 0.12);
        color: var(--tx-amber);
        padding: 8px 14px;
    }

    .btn-delete {
        background: rgba(220, 38, 38, 0.12);
        color: var(--tx-red);
        padding: 8px 14px;
    }

    .table-modern thead th {
        background: #fbfcff;
        color: var(--tx-ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--tx-line);
        padding: 16px 18px;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 18px;
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-modern tbody tr:hover {
        background: #fbfdff;
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

    .status-success {
        background: rgba(22, 163, 74, 0.12);
        color: var(--tx-green);
    }

    .status-danger {
        background: rgba(220, 38, 38, 0.12);
        color: var(--tx-red);
    }

    .status-warning {
        background: rgba(217, 119, 6, 0.12);
        color: var(--tx-amber);
    }

    .ref-text {
        font-family: monospace;
        font-size: 12px;
        color: var(--tx-ink-soft);
    }

    .action-wrap {
        display: inline-flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .modal-header {
        background: linear-gradient(135deg, #1d4ed8, #0891b2);
        color: #fff;
        border-bottom: 0;
    }

    .modal-body .info-row {
        padding: 12px 0;
        border-bottom: 1px solid var(--tx-line);
    }

    .modal-body .info-row:last-child {
        border-bottom: 0;
    }

    /* Transaction Modal Redesign */
    #transactionModal .modal-content {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 25px 70px rgba(15, 23, 42, 0.25);
        background: #f8fafc;
    }

    #transactionModal .tx-modal-header {
        background: linear-gradient(135deg, #0c1630 0%, #123b9f 55%, #0891b2 100%);
        color: #ffffff;
        position: relative;
    }

    .tx-modal-icon-badge {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 18px;
    }

    .tx-modal-hero-banner {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(12px);
    }

    .tx-avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb, #0891b2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .tx-header-amount {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .text-cyan-200 {
        color: #a5f3fc;
    }

    .fs-7 {
        font-size: 0.75rem;
    }

    .font-mono {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
    }

    #transactionModal .modal-body {
        padding: 24px;
        background-color: #f8fafc;
    }

    .tx-section-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    .tx-section-card:last-child {
        margin-bottom: 0;
    }

    .tx-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }

    .tx-section-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
    }

    .tx-section-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.01em;
        margin: 0;
    }

    .tx-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 16px;
        height: 100%;
        transition: all 0.2s ease-in-out;
    }

    .tx-info-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .tx-info-card.primary-highlight {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(8, 145, 178, 0.05));
        border: 1.5px solid rgba(37, 99, 235, 0.25);
    }

    .tx-info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #64748b;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .tx-info-value {
        font-size: 0.925rem;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    .tx-info-value.amount-large {
        font-size: 1.35rem;
        font-weight: 800;
        color: #2563eb;
    }

    .tx-flow-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
    }

    .tx-failure-alert {
        background: rgba(220, 38, 38, 0.06);
        border: 1px solid rgba(220, 38, 38, 0.2);
        border-radius: 14px;
        padding: 14px 18px;
        color: #991b1b;
    }

    #transactionModal .modal-footer {
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
        padding: 16px 24px;
        border-bottom-left-radius: 28px;
        border-bottom-right-radius: 28px;
    }
</style>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')

            
            @if ($errors->any())
                <script>
                    Swal.fire({
                        toast:true,
                        position:'top-end',
                        icon:'error',
                        title:@json($errors->first()),
                        showConfirmButton:false,
                        timer:4000,
                        timerProgressBar:true
                    });
                </script>
            @endif

            @if (session('success'))
                <script>
                    Swal.fire({
                        toast:true,
                        position:'top-end',
                        icon:'success',
                        title:@json(session('success')),
                        showConfirmButton:false,
                        timer:4000,
                        timerProgressBar:true
                    });
                </script>
            @endif

            
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="tx-page">

                        <div class="card tx-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-chart-line"></i>
                                            Transaction Monitor
                                        </div>
                                        <h1 class="hero-title">All Transaction History</h1>
                                        <p class="hero-copy">
                                            Review every transaction in one place, inspect payment flow, track status changes, and take action quickly with search, detail view, and chargeback support.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Records</small>
                                                    <strong>{{ method_exists($lastTransactions, 'total') ? $lastTransactions->total() : $lastTransactions->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Showing</small>
                                                    <strong>{{ $lastTransactions->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Search Filter</small>
                                                    <strong id="searchMeta">Live table search</strong>
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
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <div class="summary-label">All Transactions</div>
                                <div class="summary-value">{{ method_exists($lastTransactions, 'total') ? $lastTransactions->total() : $lastTransactions->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-green">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="summary-label">Successful</div>
                                <div class="summary-value">{{ collect($lastTransactions)->where('status', 'success')->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-red">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </div>
                                <div class="summary-label">Failed</div>
                                <div class="summary-value">{{ collect($lastTransactions)->where('status', 'failed')->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-amber">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </div>
                                <div class="summary-label">Visible Rows</div>
                                <div class="summary-value" id="visibleCount">{{ $lastTransactions->count() }}</div>
                            </div>
                        </div>

                        <div class="tx-card">
                            <div class="card-header">
                                <div class="section-title">All Transaction History</div>
                                <div class="section-subtitle">Search, inspect, charge back, or remove transaction records.</div>
                            </div>

                            {{-- <div class="search-toolbar">
                                <input type="text" id="transactionSearch" class="form-control search-input" placeholder="Search sender, type, status, amount, currency, or reference...">
                            </div> --}}

                            <div class="search-toolbar">
                                <div class="search-box">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input
                                        type="text"
                                        id="transactionSearch"
                                        class="form-control search-input"
                                        placeholder="Search sender, type, status, amount, currency, or reference..."
                                    >
                                </div>

                                <div class="per-page-control">
                                    <label for="perPageSelect">Show</label>
                                    <select id="perPageSelect" class="form-select per-page-select">
                                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                                        <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                                        <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                                    </select>
                                </div>
                            </div>

                            <div class="table-responsive" style="overflow-x:auto;">
                                <table class="table table-modern align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Currency</th>
                                            <th class="text-center">Reference</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                     <tbody id="transactionTableBody">
                                        @foreach ($lastTransactions as $item)
                                            @php
                                                $userName = 'N/A';
                                                if ($item->user) {
                                                    $userName = trim(($item->user->firstname ?? '') . ' ' . ($item->user->lastname ?? '')) ?: ($item->user->business_name ?? ($item->user->name ?? 'N/A'));
                                                } elseif ($item->personal) {
                                                    $userName = trim(($item->personal->firstname ?? '') . ' ' . ($item->personal->lastname ?? '')) ?: ($item->personal->name ?? 'N/A');
                                                }
                                                if ($userName === 'N/A' && !empty($item->sender)) {
                                                    $userName = $item->sender;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ $item->sender ?? 'N/A' }}</td>
                                                <td class="text-center">{{ ucfirst($item->type ?? 'N/A') }}</td>
                                                <td class="text-center">
                                                    @if ($item->status == 'success')
                                                        <span class="status-chip status-success">Success</span>
                                                    @elseif ($item->status == 'failed')
                                                        <span class="status-chip status-danger">Failed</span>
                                                    @else
                                                        <span class="status-chip status-warning">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                                                <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                                                <td class="text-center">{{ strtoupper($item->currency ?? 'N/A') }}</td>
                                                <td class="text-center">
                                                    <span class="ref-text">{{ $item->reference ?? 'N/A' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="action-wrap">
                                                        @if($item->status === 'pending')
                                                        <form method="POST" action="{{ route('transactionhistory.process', $item->id) }}" class="process-form" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-brand">Process</button>
                                                            </form>
                                                        @endif

                                                        @if ($item->status === 'pending')
                                                        <form method="POST" action="{{ route('transactionhistory.refund', $item->id) }}" class="refund-form" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-delete">Refund</button>
                                                        </form>

                                                        @endif


                                                        <button type="button" class="btn btn-view view-transaction-btn"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#transactionModal"

                                                            data-id="{{ $item->id }}"
                                                            data-user-name="{{ $userName }}"
                                                            data-user-id="{{ $item->user_id }}"
                                                            data-personal-id="{{ $item->personal_id }}"
                                                            data-type="{{ $item->type }}"
                                                            data-transaction-type="{{ $item->transaction_type }}"
                                                            data-sender="{{ $item->sender }}"
                                                            data-sender-id="{{ $item->sender_id }}"
                                                            data-recipient="{{ $item->recipient }}"
                                                            data-recipient-id="{{ $item->recipient_id }}"
                                                            data-method="{{ $item->method }}"
                                                            data-payment-method="{{ $item->payment_method }}"
                                                            data-payment-provider="{{ $item->payment_provider }}"
                                                            data-status="{{ $item->status }}"
                                                            data-amount="{{ number_format((float) $item->amount, 2) }}"
                                                            data-total-amount="{{ number_format((float) $item->total_amount, 2) }}"
                                                            data-recipient-amount="{{ number_format((float) $item->recipient_amount, 2) }}"
                                                            data-currency="{{ $item->currency }}"
                                                            data-to-currency="{{ $item->to_currency }}"
                                                            data-fees="{{ number_format((float) $item->fees, 2) }}"
                                                            data-exchange-rate="{{ $item->exchange_rate }}"
                                                            data-single-rate="{{ $item->single_rate }}"
                                                            data-balance-id="{{ $item->balance_id }}"
                                                            data-virtual-account-id="{{ $item->virtual_account_id }}"
                                                            data-order-id="{{ $item->order_id }}"
                                                            data-reference="{{ $item->reference }}"
                                                            data-payment-reference="{{ $item->payment_reference }}"
                                                            data-failure-reason="{{ $item->failure_reason }}"
                                                            data-beneficias-id="{{ $item->beneficias_id }}"

                                                            data-recipient-country="{{ $item->recipient_country }}"
                                                            data-recipient-default-reference="{{ $item->recipient_default_reference }}"
                                                            data-recipient-alias="{{ $item->recipient_alias }}"
                                                            data-recipient-type="{{ $item->recipient_type }}"
                                                            data-recipient-created-at="{{ $item->recipient_created_at }}"
                                                            data-recipient-account-name="{{ $item->recipient_account_name }}"
                                                            data-recipient-sort-code="{{ $item->recipient_sort_code }}"
                                                            data-recipient-account-number="{{ $item->recipient_account_number }}"
                                                            data-recipient-bank-name="{{ $item->recipient_bank_name }}"
                                                            data-recipient-bank-currency="{{ $item->recipient_bank_currency }}"

                                                            data-card-number="{{ $item->card_number }}"
                                                            data-expiry-month="{{ $item->expiry_month }}"
                                                            data-expiry-year="{{ $item->expiry_year }}"
                                                            data-cvv="{{ $item->cvv ? '***' : '' }}"

                                                            data-created-at-external="{{ $item->created_at_external }}"
                                                            data-created="{{ $item->created_at?->format('d M Y H:i') }}"
                                                            data-updated="{{ $item->updated_at?->format('d M Y H:i') }}">
                                                            View
                                                        </button>

                                                        <button type="button"
                                                                class="btn btn-charge chargeback-btn"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#chargebackModal"
                                                                data-transaction-id="{{ $item->id }}"
                                                                data-user-id="{{ $item->user_id }}"
                                                                data-reference="{{ $item->reference }}"
                                                                data-amount="{{ number_format($item->amount, 2) }}"
                                                                data-currency="{{ $item->currency }}">
                                                            Chargeback
                                                        </button>

                                                        <button class="btn btn-delete delete-btn" data-id="{{ $item->id }}">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="p-4 border-top">
                                    {{ $lastTransactions->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<div class="modal fade" id="chargebackModal" tabindex="-1" aria-labelledby="chargebackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chargebackModalLabel">Submit Chargeback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.chargeback.submitEvidence') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="transaction_id" id="cbTransactionId">
                    <input type="hidden" name="user_id" id="cbUserId">
                    <input type="hidden" name="currency" id="cbCurrency">

                    <div class="mb-3">
                        <label class="form-label">Transaction Reference</label>
                        <input type="text" class="form-control" id="cbReference" name="cbReference" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" class="form-control" id="cbAmount" name="amount" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="deadline" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea name="reason" class="form-control" rows="3" placeholder="Explain reason for chargeback"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-brand">Submit Chargeback</button>
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            
            <!-- Modern Fintech Header -->
            <div class="modal-header tx-modal-header border-0 p-4">
                <div class="w-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="tx-modal-icon-badge">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-white mb-0" id="transactionModalLabel">Transaction Details</h5>
                                <small class="text-white-50">Full payment inspection & financial records</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Summary Hero Card inside Header -->
                    <div class="tx-modal-hero-banner p-3 p-md-4 rounded-4">
                        <div class="row align-items-center g-3">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="tx-avatar-circle">
                                        <i class="fa-solid fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="tx-user-name fw-bold text-white fs-5" id="modal-header-user-name">N/A</div>
                                        <div class="tx-user-id text-white-50 small font-mono">User ID: <span id="modal-header-user-id">N/A</span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 text-md-center">
                                <div class="tx-header-amount-wrap">
                                    <small class="text-white-50 text-uppercase fw-semibold d-block fs-7">Transaction Amount</small>
                                    <div class="tx-header-amount fw-extrabold text-white my-1">
                                        <span id="modal-header-amount">0.00</span>
                                        <span id="modal-header-currency" class="fs-6 font-mono ms-1 text-cyan-200">USD</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 text-md-end">
                                <div class="mb-2">
                                    <span id="modal-header-status" class="status-chip status-warning">
                                        <i class="fa-solid fa-clock"></i> <span>Pending</span>
                                    </span>
                                </div>
                                <div class="tx-header-ref text-white-50 small font-mono text-truncate">
                                    Ref: <span id="modal-header-reference" class="text-white">N/A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Body with Organized Information Cards -->
            <div class="modal-body p-4">
                
                <!-- Alert box for failure reason if populated -->
                <div id="modal-failure-card" class="tx-failure-alert mb-4" style="display: none;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation fs-4 text-danger mt-1"></i>
                        <div>
                            <strong class="d-block text-danger mb-1">Transaction Failed</strong>
                            <span id="modal-failure-reason-alert" class="small"></span>
                        </div>
                    </div>
                </div>

                <!-- 1. Transaction Overview -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-blue">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h6 class="tx-section-title">Transaction Overview</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-hashtag me-1"></i> Transaction ID</div>
                                <div class="tx-info-value font-mono" id="modal-id">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-sliders me-1"></i> Transaction Type</div>
                                <div class="tx-info-value" id="modal-transaction-type">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-tag me-1"></i> Type</div>
                                <div class="tx-info-value" id="modal-type">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-signal me-1"></i> Status</div>
                                <div class="tx-info-value"><span id="modal-status" class="status-chip">N/A</span></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-gear me-1"></i> Method</div>
                                <div class="tx-info-value" id="modal-method">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-wallet me-1"></i> Payment Method</div>
                                <div class="tx-info-value" id="modal-payment-method">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-server me-1"></i> Payment Provider</div>
                                <div class="tx-info-value text-uppercase" id="modal-payment-provider">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. User Information -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-blue">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <h6 class="tx-section-title">User Information</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card primary-highlight">
                                <div class="tx-info-label"><i class="fa-solid fa-user me-1"></i> User Name</div>
                                <div class="tx-info-value text-primary fw-bold" id="modal-user-name">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-id-badge me-1"></i> User ID</div>
                                <div class="tx-info-value font-mono" id="modal-user-id">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-id-card me-1"></i> Personal ID</div>
                                <div class="tx-info-value font-mono" id="modal-personal-id">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Amount & Fees -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-green">
                            <i class="fa-solid fa-money-bill-wave"></i>
                        </div>
                        <h6 class="tx-section-title">Amount & Fees</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card primary-highlight">
                                <div class="tx-info-label"><i class="fa-solid fa-money-bill me-1"></i> Amount</div>
                                <div class="tx-info-value amount-large" id="modal-amount">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-calculator me-1"></i> Total Amount</div>
                                <div class="tx-info-value font-mono" id="modal-total-amount">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-hand-holding-dollar me-1"></i> Recipient Amount</div>
                                <div class="tx-info-value font-mono" id="modal-recipient-amount">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-coins me-1"></i> Currency</div>
                                <div class="tx-info-value font-mono" id="modal-currency">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-globe me-1"></i> To Currency</div>
                                <div class="tx-info-value font-mono" id="modal-to-currency">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-percent me-1"></i> Fees</div>
                                <div class="tx-info-value font-mono text-danger" id="modal-fees">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-arrows-split-up-and-left me-1"></i> Exchange Rate</div>
                                <div class="tx-info-value font-mono" id="modal-exchange-rate">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-chart-pie me-1"></i> Single Rate</div>
                                <div class="tx-info-value font-mono" id="modal-single-rate">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Sender & Recipient -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-blue">
                            <i class="fa-solid fa-arrows-between-lines"></i>
                        </div>
                        <h6 class="tx-section-title">Sender & Recipient</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="tx-flow-card">
                                <div class="tx-info-label text-primary"><i class="fa-solid fa-paper-plane me-1"></i> Sender Details</div>
                                <div class="row g-2 mt-1">
                                    <div class="col-12">
                                        <div class="text-muted small">Sender Name</div>
                                        <div class="fw-bold text-dark" id="modal-sender">N/A</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-muted small">Sender ID</div>
                                        <div class="font-mono text-dark" id="modal-sender-id">N/A</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="tx-flow-card">
                                <div class="tx-info-label text-cyan"><i class="fa-solid fa-user-check me-1"></i> Recipient Details</div>
                                <div class="row g-2 mt-1">
                                    <div class="col-12">
                                        <div class="text-muted small">Recipient Name</div>
                                        <div class="fw-bold text-dark" id="modal-recipient">N/A</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-muted small">Recipient ID</div>
                                        <div class="font-mono text-dark" id="modal-recipient-id">N/A</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-earth-americas me-1"></i> Recipient Country</div>
                                <div class="tx-info-value" id="modal-recipient-country">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-shapes me-1"></i> Recipient Type</div>
                                <div class="tx-info-value" id="modal-recipient-type">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-at me-1"></i> Recipient Alias</div>
                                <div class="tx-info-value" id="modal-recipient-alias">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-bookmark me-1"></i> Default Reference</div>
                                <div class="tx-info-value font-mono" id="modal-recipient-default-reference">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-calendar-plus me-1"></i> Recipient Created At</div>
                                <div class="tx-info-value" id="modal-recipient-created-at">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Recipient Bank Details -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-blue">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <h6 class="tx-section-title">Recipient Bank Details</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-user-tag me-1"></i> Account Name</div>
                                <div class="tx-info-value text-dark" id="modal-recipient-account-name">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="tx-info-card primary-highlight">
                                <div class="tx-info-label"><i class="fa-solid fa-credit-card me-1"></i> Account Number</div>
                                <div class="tx-info-value font-mono fs-5 text-primary" id="modal-recipient-account-number">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-bank me-1"></i> Bank Name</div>
                                <div class="tx-info-value" id="modal-recipient-bank-name">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-money-bill-transfer me-1"></i> Bank Currency</div>
                                <div class="tx-info-value font-mono" id="modal-recipient-bank-currency">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-code-branch me-1"></i> Sort Code</div>
                                <div class="tx-info-value font-mono" id="modal-recipient-sort-code">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 6. Payment & References -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-amber">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <h6 class="tx-section-title">Payment & References</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-hashtag me-1"></i> Reference</div>
                                <div class="tx-info-value font-mono text-break" id="modal-reference">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-receipt me-1"></i> Payment Reference</div>
                                <div class="tx-info-value font-mono text-break" id="modal-payment-reference">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-cart-shopping me-1"></i> Order ID</div>
                                <div class="tx-info-value font-mono text-break" id="modal-order-id">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-scale-balanced me-1"></i> Balance ID</div>
                                <div class="tx-info-value font-mono text-break" id="modal-balance-id">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-vault me-1"></i> Virtual Account ID</div>
                                <div class="tx-info-value font-mono text-break" id="modal-virtual-account-id">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-user-check me-1"></i> Beneficiary ID</div>
                                <div class="tx-info-value font-mono text-break" id="modal-beneficias-id">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. Card Details -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-amber">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <h6 class="tx-section-title">Card Details</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-credit-card me-1"></i> Card Number</div>
                                <div class="tx-info-value font-mono" id="modal-card-number">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-calendar me-1"></i> Expiry Month</div>
                                <div class="tx-info-value font-mono" id="modal-expiry-month">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-calendar-days me-1"></i> Expiry Year</div>
                                <div class="tx-info-value font-mono" id="modal-expiry-year">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-lock me-1"></i> CVV</div>
                                <div class="tx-info-value font-mono text-muted" id="modal-cvv">***</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 8. Failure & Timeline -->
                <div class="tx-section-card">
                    <div class="tx-section-header">
                        <div class="tx-section-icon icon-red">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h6 class="tx-section-title">Failure & Timeline</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-triangle-exclamation me-1"></i> Failure Reason</div>
                                <div class="tx-info-value text-danger" id="modal-failure-reason">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-globe me-1"></i> External Created At</div>
                                <div class="tx-info-value" id="modal-created-at-external">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-calendar-check me-1"></i> Created At</div>
                                <div class="tx-info-value" id="modal-created">N/A</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="tx-info-card">
                                <div class="tx-info-label"><i class="fa-solid fa-clock me-1"></i> Updated At</div>
                                <div class="tx-info-value" id="modal-updated">N/A</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-soft px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const chargebackButtons = document.querySelectorAll('.chargeback-btn');

    chargebackButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('cbTransactionId').value = btn.getAttribute('data-transaction-id');
            document.getElementById('cbUserId').value = btn.getAttribute('data-user-id');
            document.getElementById('cbCurrency').value = btn.getAttribute('data-currency');
            document.getElementById('cbReference').value = btn.getAttribute('data-reference');
            document.getElementById('cbAmount').value = `${btn.getAttribute('data-amount')} ${btn.getAttribute('data-currency')}`;
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('transactionModal');

    function setText(id, value) {
        const el = modal.querySelector(`#${id}`);
        if (el) {
            el.textContent = value !== null && value !== undefined && value !== '' ? value : 'N/A';
        }
    }

    document.querySelectorAll('.view-transaction-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const fields = [
                'id',
                'user-name',
                'user-id',
                'personal-id',
                'type',
                'transaction-type',
                'sender',
                'sender-id',
                'recipient',
                'recipient-id',
                'method',
                'payment-method',
                'payment-provider',
                'amount',
                'total-amount',
                'recipient-amount',
                'currency',
                'to-currency',
                'fees',
                'exchange-rate',
                'single-rate',
                'balance-id',
                'virtual-account-id',
                'order-id',
                'reference',
                'payment-reference',
                'failure-reason',
                'beneficias-id',
                'recipient-country',
                'recipient-default-reference',
                'recipient-alias',
                'recipient-type',
                'recipient-created-at',
                'recipient-account-name',
                'recipient-sort-code',
                'recipient-account-number',
                'recipient-bank-name',
                'recipient-bank-currency',
                'card-number',
                'expiry-month',
                'expiry-year',
                'cvv',
                'created-at-external',
                'created',
                'updated'
            ];

            fields.forEach(field => {
                setText(`modal-${field}`, this.getAttribute(`data-${field}`));
            });

            // Populate Modal Header Summary
            const userName = this.getAttribute('data-user-name') || 'N/A';
            const userId = this.getAttribute('data-user-id') || 'N/A';
            const amount = this.getAttribute('data-amount') || '0.00';
            const currency = (this.getAttribute('data-currency') || 'USD').toUpperCase();
            const reference = this.getAttribute('data-reference') || 'N/A';
            const failureReason = this.getAttribute('data-failure-reason');

            setText('modal-header-user-name', userName);
            setText('modal-header-user-id', userId);
            setText('modal-header-amount', amount);
            setText('modal-header-currency', currency);
            setText('modal-header-reference', reference);

            // Handle Failure Alert Banner
            const failureCard = modal.querySelector('#modal-failure-card');
            const failureReasonAlert = modal.querySelector('#modal-failure-reason-alert');
            if (failureReason && failureReason !== 'N/A' && failureReason.trim() !== '') {
                if (failureReasonAlert) failureReasonAlert.textContent = failureReason;
                if (failureCard) failureCard.style.display = 'block';
            } else {
                if (failureCard) failureCard.style.display = 'none';
            }

            // Handle Status Badges
            const status = (this.getAttribute('data-status') || '').toLowerCase();
            const statusLabel = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';

            // Modal Body Status Chip
            const statusEl = modal.querySelector('#modal-status');
            if (statusEl) {
                statusEl.textContent = statusLabel;
                statusEl.className = 'status-chip';
                if (status === 'success') {
                    statusEl.classList.add('status-success');
                } else if (status === 'failed') {
                    statusEl.classList.add('status-danger');
                } else {
                    statusEl.classList.add('status-warning');
                }
            }

            // Header Status Badge
            const headerStatusEl = modal.querySelector('#modal-header-status');
            if (headerStatusEl) {
                headerStatusEl.className = 'status-chip';
                let iconClass = 'fa-clock';
                if (status === 'success') {
                    headerStatusEl.classList.add('status-success');
                    iconClass = 'fa-circle-check';
                } else if (status === 'failed') {
                    headerStatusEl.classList.add('status-danger');
                    iconClass = 'fa-circle-xmark';
                } else {
                    headerStatusEl.classList.add('status-warning');
                    iconClass = 'fa-clock';
                }
                headerStatusEl.innerHTML = `<i class="fa-solid ${iconClass} me-1"></i> ${statusLabel}`;
            }
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.dataset.id;

            Swal.fire({
                title: 'Delete transaction?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/transactionhistory/${transactionId}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('transactionSearch');
    const tableBody = document.getElementById('transactionTableBody');
    const rows = tableBody.querySelectorAll('tr');
    const visibleCount = document.getElementById('visibleCount');
    const searchMeta = document.getElementById('searchMeta');

    function updateVisibleCount() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none').length;
        visibleCount.textContent = visibleRows;
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        searchMeta.textContent = query ? query : 'Live table search';

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const match = cells.some(td => td.textContent.toLowerCase().includes(query));
            row.style.display = match ? '' : 'none';
        });

        updateVisibleCount();
    });

    updateVisibleCount();
});
</script>

<script>
document.querySelectorAll('.process-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Process transaction?',
      text: 'This will send the payment to provider.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#2563eb',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Yes, process'
    }).then((result) => {
      if (result.isConfirmed) form.submit();
    });
  });
});

document.querySelectorAll('.refund-form').forEach(form => {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Refund transaction?',
      text: 'This will return the amount to the user immediately.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#64748b',
      confirmButtonText: 'Yes, refund'
    }).then((result) => {
      if (result.isConfirmed) form.submit();
    });
  });
});


document.addEventListener('DOMContentLoaded', function () {
    const perPageSelect = document.getElementById('perPageSelect');

    perPageSelect.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        url.searchParams.delete('page'); // reset to page 1 when page size changes
        window.location.href = url.toString();
    });
});


</script>

