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
                                            <i class="pe-7s-graph"></i>
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
                                    <i class="pe-7s-graph1"></i>
                                </div>
                                <div class="summary-label">All Transactions</div>
                                <div class="summary-value">{{ method_exists($lastTransactions, 'total') ? $lastTransactions->total() : $lastTransactions->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-green">
                                    <i class="pe-7s-check"></i>
                                </div>
                                <div class="summary-label">Successful</div>
                                <div class="summary-value">{{ collect($lastTransactions)->where('status', 'success')->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-red">
                                    <i class="pe-7s-close-circle"></i>
                                </div>
                                <div class="summary-label">Failed</div>
                                <div class="summary-value">{{ collect($lastTransactions)->where('status', 'failed')->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-amber">
                                    <i class="pe-7s-search"></i>
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
                                                            data-bs-toggle="modal" data-bs-target="#transactionModal"
                                                            data-sender="{{ $item->sender }}"
                                                            data-recipient="{{ $item->recipient }}"
                                                            data-amount="{{ number_format($item->amount, 2) }}"
                                                            data-currency="{{ $item->currency }}"
                                                            data-status="{{ $item->status }}"
                                                            data-method="{{ $item->method }}"
                                                            data-reference="{{ $item->reference }}"
                                                            data-type="{{ $item->transaction_type }}"
                                                            data-created="{{ $item->created_at->format('d M Y H:i') }}">
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row info-row">
                    <div class="col-md-6"><strong>Sender:</strong> <span id="modal-sender"></span></div>
                    <div class="col-md-6"><strong>Recipient:</strong> <span id="modal-recipient"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Amount:</strong> <span id="modal-amount"></span> <span id="modal-currency"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modal-status" class="status-chip"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Method:</strong> <span id="modal-method"></span></div>
                    <div class="col-md-6"><strong>Reference:</strong> <span id="modal-reference"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Transaction Type:</strong> <span id="modal-type"></span></div>
                    <div class="col-md-6"><strong>Created At:</strong> <span id="modal-created"></span></div>
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
    const modal = document.getElementById('transactionModal');
    const senderEl = modal.querySelector('#modal-sender');
    const recipientEl = modal.querySelector('#modal-recipient');
    const amountEl = modal.querySelector('#modal-amount');
    const currencyEl = modal.querySelector('#modal-currency');
    const statusEl = modal.querySelector('#modal-status');
    const methodEl = modal.querySelector('#modal-method');
    const referenceEl = modal.querySelector('#modal-reference');
    const typeEl = modal.querySelector('#modal-type');
    const createdEl = modal.querySelector('#modal-created');

    document.querySelectorAll('.view-transaction-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            senderEl.textContent = this.dataset.sender || 'N/A';
            recipientEl.textContent = this.dataset.recipient || 'N/A';
            amountEl.textContent = this.dataset.amount || '0.00';
            currencyEl.textContent = this.dataset.currency || '';
            methodEl.textContent = this.dataset.method || 'N/A';
            referenceEl.textContent = this.dataset.reference || 'N/A';
            typeEl.textContent = this.dataset.type || 'N/A';
            createdEl.textContent = this.dataset.created || 'N/A';

            const status = (this.dataset.status || '').toLowerCase();
            statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
            statusEl.className = 'status-chip';

            if (status === 'success') statusEl.classList.add('status-success');
            else if (status === 'failed') statusEl.classList.add('status-danger');
            else statusEl.classList.add('status-warning');
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
<<<<<<< HEAD
=======

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

>>>>>>> 5c913cbb5167c597eacb641074d84a6c839d8162
