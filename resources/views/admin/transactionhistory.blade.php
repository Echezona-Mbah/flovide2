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
        padding: 0 24px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
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
        border-color: var(--tx-blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10) !important;
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

                            <div class="search-toolbar">
                                <input type="text" id="transactionSearch" class="form-control search-input" placeholder="Search sender, type, status, amount, currency, or reference...">
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
            <div class="modal-header">
                <h5 class="modal-title">Full Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-12">
                        <h6 class="fw-bold text-primary mb-2">Core Information</h6>
                    </div>

                    <div class="col-md-6 info-row"><strong>ID:</strong> <span id="modal-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Status:</strong> <span id="modal-status" class="status-chip"></span></div>
                    <div class="col-md-6 info-row"><strong>User ID:</strong> <span id="modal-user-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Personal ID:</strong> <span id="modal-personal-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Type:</strong> <span id="modal-type"></span></div>
                    <div class="col-md-6 info-row"><strong>Transaction Type:</strong> <span id="modal-transaction-type"></span></div>
                    <div class="col-md-6 info-row"><strong>Method:</strong> <span id="modal-method"></span></div>
                    <div class="col-md-6 info-row"><strong>Payment Method:</strong> <span id="modal-payment-method"></span></div>
                    <div class="col-md-6 info-row"><strong>Payment Provider:</strong> <span id="modal-payment-provider"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">Amount Information</h6>
                    </div>

                    <div class="col-md-4 info-row"><strong>Amount:</strong> <span id="modal-amount"></span></div>
                    <div class="col-md-4 info-row"><strong>Total Amount:</strong> <span id="modal-total-amount"></span></div>
                    <div class="col-md-4 info-row"><strong>Recipient Amount:</strong> <span id="modal-recipient-amount"></span></div>
                    <div class="col-md-4 info-row"><strong>Currency:</strong> <span id="modal-currency"></span></div>
                    <div class="col-md-4 info-row"><strong>To Currency:</strong> <span id="modal-to-currency"></span></div>
                    <div class="col-md-4 info-row"><strong>Fees:</strong> <span id="modal-fees"></span></div>
                    <div class="col-md-6 info-row"><strong>Exchange Rate:</strong> <span id="modal-exchange-rate"></span></div>
                    <div class="col-md-6 info-row"><strong>Single Rate:</strong> <span id="modal-single-rate"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">Sender & Recipient</h6>
                    </div>

                    <div class="col-md-6 info-row"><strong>Sender:</strong> <span id="modal-sender"></span></div>
                    <div class="col-md-6 info-row"><strong>Sender ID:</strong> <span id="modal-sender-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient:</strong> <span id="modal-recipient"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient ID:</strong> <span id="modal-recipient-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient Country:</strong> <span id="modal-recipient-country"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient Type:</strong> <span id="modal-recipient-type"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient Alias:</strong> <span id="modal-recipient-alias"></span></div>
                    <div class="col-md-6 info-row"><strong>Default Reference:</strong> <span id="modal-recipient-default-reference"></span></div>
                    <div class="col-md-6 info-row"><strong>Recipient Created At:</strong> <span id="modal-recipient-created-at"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">Recipient Bank Details</h6>
                    </div>

                    <div class="col-md-6 info-row"><strong>Account Name:</strong> <span id="modal-recipient-account-name"></span></div>
                    <div class="col-md-6 info-row"><strong>Account Number:</strong> <span id="modal-recipient-account-number"></span></div>
                    <div class="col-md-6 info-row"><strong>Bank Name:</strong> <span id="modal-recipient-bank-name"></span></div>
                    <div class="col-md-6 info-row"><strong>Bank Currency:</strong> <span id="modal-recipient-bank-currency"></span></div>
                    <div class="col-md-6 info-row"><strong>Sort Code:</strong> <span id="modal-recipient-sort-code"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">References</h6>
                    </div>

                    <div class="col-md-6 info-row"><strong>Reference:</strong> <span id="modal-reference"></span></div>
                    <div class="col-md-6 info-row"><strong>Payment Reference:</strong> <span id="modal-payment-reference"></span></div>
                    <div class="col-md-6 info-row"><strong>Order ID:</strong> <span id="modal-order-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Balance ID:</strong> <span id="modal-balance-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Virtual Account ID:</strong> <span id="modal-virtual-account-id"></span></div>
                    <div class="col-md-6 info-row"><strong>Beneficiary ID:</strong> <span id="modal-beneficias-id"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">Card Details</h6>
                    </div>

                    <div class="col-md-4 info-row"><strong>Card Number:</strong> <span id="modal-card-number"></span></div>
                    <div class="col-md-4 info-row"><strong>Expiry Month:</strong> <span id="modal-expiry-month"></span></div>
                    <div class="col-md-4 info-row"><strong>Expiry Year:</strong> <span id="modal-expiry-year"></span></div>
                    <div class="col-md-4 info-row"><strong>CVV:</strong> <span id="modal-cvv"></span></div>

                    <div class="col-12 mt-3">
                        <h6 class="fw-bold text-primary mb-2">Failure & Dates</h6>
                    </div>

                    <div class="col-md-12 info-row"><strong>Failure Reason:</strong> <span id="modal-failure-reason"></span></div>
                    <div class="col-md-4 info-row"><strong>External Created At:</strong> <span id="modal-created-at-external"></span></div>
                    <div class="col-md-4 info-row"><strong>Created At:</strong> <span id="modal-created"></span></div>
                    <div class="col-md-4 info-row"><strong>Updated At:</strong> <span id="modal-updated"></span></div>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Close</button>
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

            const statusEl = modal.querySelector('#modal-status');
            const status = (this.getAttribute('data-status') || '').toLowerCase();

            statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
            statusEl.className = 'status-chip';

            if (status === 'success') {
                statusEl.classList.add('status-success');
            } else if (status === 'failed') {
                statusEl.classList.add('status-danger');
            } else {
                statusEl.classList.add('status-warning');
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
</script>

