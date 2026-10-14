@include('admin.head')

<style>
    /* Custom premium styling for Blog Editor */
    .blog-title-input {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        border: none;
        border-bottom: 2px solid #e2e8f0;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        transition: all 0.3s ease;
        background: transparent;
    }
    .blog-title-input:focus {
        box-shadow: none;
        border-color: #3f51b5;
        background: transparent;
    }
    
    .editor-toolbar {
        background: #f8fafc;
        border: 1px solid #ced4da;
        border-bottom: none;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 8px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .editor-btn {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 4px;
        color: #495057;
        padding: 5px 10px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .editor-btn:hover {
        background: #e9ecef;
        color: #3f51b5;
        border-color: #adb5bd;
    }
    
    .editor-textarea {
        border: 1px solid #ced4da;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        padding: 15px;
        font-size: 1rem;
        line-height: 1.6;
        min-height: 350px;
        color: #495057;
        resize: vertical;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .editor-textarea:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
    }
    
    /* Dropzone Custom Styling */
    .image-dropzone {
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .image-dropzone:hover {
        border-color: #3f51b5;
        background: #f1f3f9;
    }
    
    .image-preview-container {
        display: none;
        width: 100%;
        height: 180px;
        border-radius: 6px;
        background-size: cover;
        background-position: center;
        position: relative;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .remove-image-btn:hover {
        background: rgba(220, 53, 69, 1);
    }
    
    /* SEO Search Snippet Preview */
    .google-preview-card {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .google-preview-title {
        color: #1a0dab;
        font-size: 19px;
        line-height: 1.3;
        margin-bottom: 3px;
        font-family: Arial, sans-serif;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .google-preview-title:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    
    .google-preview-url {
        color: #202124;
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 4px;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .google-preview-description {
        color: #4d5156;
        font-size: 14px;
        line-height: 1.58;
        font-family: Arial, sans-serif;
        word-wrap: break-word;
    }
    
    /* Tags styling */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .tag-badge {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: scaleIn 0.2s ease;
    }
    
    .tag-badge .remove-tag {
        cursor: pointer;
        color: #6c757d;
        font-weight: bold;
        transition: color 0.2s ease;
    }
    .tag-badge .remove-tag:hover {
        color: #dc3545;
    }
    
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    /* Custom button styling */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #2196f3 0%, #3f51b5 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        transform: translateY(-1px);
    }
    
    .btn-gradient-secondary {
        background: #ffffff;
        color: #495057;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .btn-gradient-secondary:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #b1b5ba;
    }
    
    /* Character counts */
    .char-counter {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
        margin-top: 4px;
        display: block;
    }
    
    /* Status Badge styling */
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .status-draft { background-color: #f7b924; }
    .status-published { background-color: #3ac47d; }
    .status-scheduled { background-color: #16aaff; }

    .badge-success {
        background-color: #099244 !important;
    }

    .badge-debit {
        background-color: #dc3545 !important;
    }

    .badge-warning {
        background-color: #ffc107 !important;
    }

    .badge-pending {
        background-color: #f7b924 !important;
    }

    .modal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    /* ── Premium Transaction Modal ── */
    .txn-modal .modal-dialog {
        max-width: 620px;
    }
    .txn-modal .modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,.22);
    }
    .txn-modal-header {
        background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 60%, #a855f7 100%);
        padding: 24px 28px 20px;
        position: relative;
    }
    .txn-modal-header .close-btn {
        position: absolute;
        top: 14px;
        right: 16px;
        background: rgba(255,255,255,.15);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        color: #fff;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .2s;
    }
    .txn-modal-header .close-btn:hover { background: rgba(255,255,255,.28); }
    .txn-modal-header .ref-label {
        color: rgba(255,255,255,.7);
        font-size: .75rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .txn-modal-header .ref-value {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: .04em;
    }
    .txn-modal-header .amount-display {
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
        margin-top: 10px;
    }
    .txn-modal-header .amount-display small {
        font-size: 1rem;
        font-weight: 500;
        opacity: .8;
        margin-left: 4px;
    }
    .txn-badge-row {
        display: flex;
        gap: 8px;
        margin-top: 12px;
        flex-wrap: wrap;
    }
    .txn-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .txn-badge-credit  { background: rgba(52,211,153,.22); color: #6ee7b7; border: 1px solid rgba(52,211,153,.35); }
    .txn-badge-debit   { background: rgba(248,113,113,.22); color: #fca5a5; border: 1px solid rgba(248,113,113,.35); }
    .txn-badge-type    { background: rgba(255,255,255,.15); color: #e9d5ff; border: 1px solid rgba(255,255,255,.2); }
    .txn-badge-success { background: rgba(52,211,153,.22); color: #6ee7b7; border: 1px solid rgba(52,211,153,.35); }
    .txn-badge-pending { background: rgba(251,191,36,.22); color: #fde68a; border: 1px solid rgba(251,191,36,.35); }
    .txn-badge-failed  { background: rgba(248,113,113,.22); color: #fca5a5; border: 1px solid rgba(248,113,113,.35); }
    /* Tabs */
    .txn-tabs {
        display: flex;
        background: #f8f6ff;
        border-bottom: 1px solid #e8e0f8;
    }
    .txn-tab-btn {
        flex: 1;
        padding: 11px 6px;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        font-size: .78rem;
        font-weight: 600;
        color: #6b7280;
        cursor: pointer;
        transition: all .2s;
        letter-spacing: .03em;
        text-transform: uppercase;
    }
    .txn-tab-btn:hover { color: #7c3aed; }
    .txn-tab-btn.active { color: #7c3aed; border-bottom-color: #7c3aed; background: #fff; }
    .txn-tab-pane { display: none; padding: 20px 24px; }
    .txn-tab-pane.active { display: block; }
    /* Row items */
    .txn-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f3f0ff;
        gap: 12px;
    }
    .txn-row:last-child { border-bottom: none; }
    .txn-row-label {
        color: #9ca3af;
        font-size: .78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .txn-row-value {
        color: #1f2937;
        font-size: .88rem;
        font-weight: 600;
        text-align: right;
        word-break: break-all;
    }
    .txn-row-value .pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 700;
    }
    .pill-success { background: #d1fae5; color: #065f46; }
    .pill-warning { background: #fef3c7; color: #92400e; }
    .pill-danger  { background: #fee2e2; color: #991b1b; }
    .pill-credit  { background: #ede9fe; color: #5b21b6; }
    .pill-debit   { background: #fee2e2; color: #991b1b; }
    .pill-neutral { background: #f3f4f6; color: #374151; }
    .txn-section-title {
        font-size: .7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #7c3aed;
        margin: 0 0 8px;
        padding-bottom: 6px;
        border-bottom: 2px solid #ede9fe;
    }
    .txn-modal-footer {
        padding: 14px 24px;
        background: #fafafa;
        border-top: 1px solid #e8e0f8;
        display: flex;
        justify-content: flex-end;
    }
    .txn-close-btn {
        background: linear-gradient(135deg, #5b21b6, #7c3aed);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 28px;
        font-size: .88rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity .2s;
    }
    .txn-close-btn:hover { opacity: .88; }
</style>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')

        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            <div class="app-main__outer">
                <div class="app-main__inner">

                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="fa-solid fa-note-sticky icon-gradient bg-happy-itmeo"></i>
                                </div>
                                <div>
                                    Transactions History
                                    <div class="page-title-subheading">
                                        Check all transactions history.
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>

                    <section class="mb-5">
                        <div class="main-card mb-3 card">
                            <div class="card-header">
                                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                    <i class="fa-solid fa-note-sticky icon-gradient bg-ripe-malin"></i>
                                    {{ $user->name ?? ($user->firstname . ' ' . $user->lastname) }}'s Transactions
                                </div>
                                <div class="btn-actions-pane-right">
                                    <form action="{{ route('admin.alltransactions.history', $user->id) }}" method="GET">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="form-control">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Reference</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $key => $transaction)
                                        <tr>
                                            <td class="text-center text-muted">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left flex2">
                                                            <div class="widget-heading">{{ $transaction->reference ?? 'N/A' }}</div>
                                                            <div class="widget-subheading opacity-7">{{ $transaction->payment_provider ?? $transaction->method ?? 'Transfer' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if(strtolower($transaction->type) === 'credit')
                                                    <div class="badge badge-success">Credit</div>
                                                @elseif(strtolower($transaction->type) === 'debit')
                                                    <div class="badge badge-danger">Debit</div>
                                                @else
                                                    <div class="badge badge-info">{{ ucfirst($transaction->type) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="font-weight-bold">
                                                    {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if(in_array(strtolower($transaction->status), ['completed', 'success', 'successful']))
                                                    <div class="badge badge-success">{{ ucfirst($transaction->status) }}</div>
                                                @elseif(in_array(strtolower($transaction->status), ['pending', 'processing']))
                                                    <div class="badge badge-warning">{{ ucfirst($transaction->status) }}</div>
                                                @else
                                                    <div class="badge badge-danger">{{ ucfirst($transaction->status) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted">{{ $transaction->created_at->format('M d, Y') }}</span><br>
                                                <span class="text-muted" style="font-size: 0.85em;">{{ $transaction->created_at->format('h:i A') }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transactionModal{{ $transaction->id }}">
                                                    Details
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted" style="font-size: 1.2rem;">
                                                    <i class="fa-solid fa-circle-info d-block mb-2" style="font-size: 2rem;"></i>
                                                    No transactions found for this user.
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($transactions->hasPages())
                            <div class="d-block p-4 text-center card-footer d-flex justify-content-center">
                                {{ $transactions->links('pagination::bootstrap-4') }}
                            </div>
                            @endif
                        </div>
                    </section>

                </div>
            </div>
        </div>

    </div>




    <!-- Transaction Modals (Redesigned) -->
    @foreach($transactions as $transaction)
        @php
            $txStatus = strtolower($transaction->status ?? '');
            $txType   = strtolower($transaction->type ?? '');
            $statusPill = in_array($txStatus, ['completed','success','successful']) ? 'pill-success'
                        : (in_array($txStatus, ['pending','processing']) ? 'pill-warning' : 'pill-danger');
            $statusBadge = in_array($txStatus, ['completed','success','successful']) ? 'txn-badge-success'
                         : (in_array($txStatus, ['pending','processing']) ? 'txn-badge-pending' : 'txn-badge-failed');
            $typePill = $txType === 'credit' ? 'pill-credit' : ($txType === 'debit' ? 'pill-debit' : 'pill-neutral');
            $typeBadge = $txType === 'credit' ? 'txn-badge-credit' : ($txType === 'debit' ? 'txn-badge-debit' : 'txn-badge-type');
        @endphp
        <div class="modal fade txn-modal" id="transactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="txnLabel{{ $transaction->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered shadow-none modal-dialog-scrollable">
                <div class="modal-content shadow-none">

                    {{-- ── Gradient Header ── --}}
                    <div class="txn-modal-header">
                        <button class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                        <div class="ref-label">Transaction Reference</div>
                        <div class="ref-value">{{ $transaction->reference ?? '—' }}</div>
                        <div class="amount-display">
                            {{ number_format($transaction->amount, 2) }}<small>{{ strtoupper($transaction->currency ?? '') }}</small>
                        </div>
                        <div class="txn-badge-row">
                            <span class="txn-badge {{ $typeBadge }}">
                                <i class="fa-solid fa-{{ $txType === 'credit' ? 'arrow-down-left' : 'arrow-up-right' }}"></i>
                                {{ ucfirst($transaction->type ?? 'N/A') }}
                            </span>
                            <span class="txn-badge {{ $statusBadge }}">
                                <i class="fa-solid fa-circle" style="font-size:.5rem;"></i>
                                {{ ucfirst($transaction->status ?? 'N/A') }}
                            </span>
                            @if($transaction->method)
                            <span class="txn-badge txn-badge-type">
                                <i class="fa-solid fa-credit-card"></i>
                                {{ ucfirst($transaction->method) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- ── Tab Nav ── --}}
                    <div class="txn-tabs" id="txnTabNav{{ $transaction->id }}">
                        <button class="txn-tab-btn active" data-tab="overview-{{ $transaction->id }}">
                            <i class="fa-solid fa-chart-pie me-1"></i>Overview
                        </button>
                        <button class="txn-tab-btn" data-tab="recipient-{{ $transaction->id }}">
                            <i class="fa-solid fa-user me-1"></i>Recipient
                        </button>
                        <button class="txn-tab-btn" data-tab="payment-{{ $transaction->id }}">
                            <i class="fa-solid fa-coins me-1"></i>Payment
                        </button>
                        <button class="txn-tab-btn" data-tab="promo-{{ $transaction->id }}">
                            <i class="fa-solid fa-tag me-1"></i>Promo
                        </button>
                    </div>

                    {{-- ── Tab Panes ── --}}
                    <div>
                        {{-- Overview --}}
                        <div class="txn-tab-pane active" id="overview-{{ $transaction->id }}">
                            <p class="txn-section-title">Core Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Reference</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->reference ?? '—' }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Amount</span>
                                <span class="txn-row-value">{{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @if($transaction->recipient_amount)
                            <div class="txn-row">
                                <span class="txn-row-label">Recipient Amount</span>
                                <span class="txn-row-value">{{ number_format($transaction->recipient_amount, 2) }} {{ strtoupper($transaction->to_currency ?? $transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            @if($transaction->total_amount)
                            <div class="txn-row">
                                <span class="txn-row-label">Total Charged</span>
                                <span class="txn-row-value">{{ number_format($transaction->total_amount, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            @if($transaction->fees)
                            <div class="txn-row">
                                <span class="txn-row-label">Fees</span>
                                <span class="txn-row-value">{{ number_format($transaction->fees, 2) }} {{ strtoupper($transaction->currency ?? '') }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Type</span>
                                <span class="txn-row-value"><span class="pill {{ $typePill }}">{{ ucfirst($transaction->type ?? '—') }}</span></span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Status</span>
                                <span class="txn-row-value"><span class="pill {{ $statusPill }}">{{ ucfirst($transaction->status ?? '—') }}</span></span>
                            </div>
                            @if($transaction->failure_reason)
                            <div class="txn-row">
                                <span class="txn-row-label">Failure Reason</span>
                                <span class="txn-row-value" style="color:#dc2626;">{{ $transaction->failure_reason }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Date &amp; Time</span>
                                <span class="txn-row-value">{{ $transaction->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $transaction->created_at->format('h:i:s A') }}</small></span>
                            </div>
                            @if($transaction->created_at_external)
                            <div class="txn-row">
                                <span class="txn-row-label">External Date</span>
                                <span class="txn-row-value">{{ $transaction->created_at_external }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Recipient --}}
                        <div class="txn-tab-pane" id="recipient-{{ $transaction->id }}">
                            <p class="txn-section-title">Sender</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Sender</span>
                                <span class="txn-row-value">{{ $transaction->sender ?? '—' }}</span>
                            </div>
                            <p class="txn-section-title mt-3">Recipient</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Name</span>
                                <span class="txn-row-value">{{ $transaction->recipient_account_name ?? $transaction->recipient_alias ?? $transaction->recipient ?? '—' }}</span>
                            </div>
                            @if($transaction->recipient_alias)
                            <div class="txn-row">
                                <span class="txn-row-label">Alias</span>
                                <span class="txn-row-value">{{ $transaction->recipient_alias }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_type)
                            <div class="txn-row">
                                <span class="txn-row-label">Recipient Type</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->recipient_type) }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_country)
                            <div class="txn-row">
                                <span class="txn-row-label">Country</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->recipient_country) }}</span>
                            </div>
                            @endif
                            <p class="txn-section-title mt-3">Bank Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Name</span>
                                <span class="txn-row-value">{{ $transaction->recipient_bank_name ?? '—' }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Account Number</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->recipient_account_number ?? '—' }}</span>
                            </div>
                            @if($transaction->recipient_sort_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Sort Code</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->recipient_sort_code }}</span>
                            </div>
                            @endif
                            @if($transaction->bank_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Code</span>
                                <span class="txn-row-value">{{ $transaction->bank_code }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_bank_currency)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Currency</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->recipient_bank_currency) }}</span>
                            </div>
                            @endif
                            @if($transaction->recipient_default_reference)
                            <div class="txn-row">
                                <span class="txn-row-label">Bank Reference</span>
                                <span class="txn-row-value">{{ $transaction->recipient_default_reference }}</span>
                            </div>
                            @endif
                        </div>

                        {{-- Payment --}}
                        <div class="txn-tab-pane" id="payment-{{ $transaction->id }}">
                            <p class="txn-section-title">Payment Method</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->method ?? '—') }}</span>
                            </div>
                            @if($transaction->transfer_method)
                            <div class="txn-row">
                                <span class="txn-row-label">Transfer Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->transfer_method) }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_method)
                            <div class="txn-row">
                                <span class="txn-row-label">Payment Method</span>
                                <span class="txn-row-value">{{ ucfirst($transaction->payment_method) }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_provider)
                            <div class="txn-row">
                                <span class="txn-row-label">Provider</span>
                                <span class="txn-row-value">{{ $transaction->payment_provider }}</span>
                            </div>
                            @endif
                            @if($transaction->payment_reference)
                            <div class="txn-row">
                                <span class="txn-row-label">Payment Ref</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->payment_reference }}</span>
                            </div>
                            @endif
                            @if($transaction->order_id)
                            <div class="txn-row">
                                <span class="txn-row-label">Order ID</span>
                                <span class="txn-row-value" style="font-family:monospace;">{{ $transaction->order_id }}</span>
                            </div>
                            @endif
                            @if($transaction->exchange_rate)
                            <p class="txn-section-title mt-3">Exchange</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Exchange Rate</span>
                                <span class="txn-row-value">{{ $transaction->exchange_rate }}</span>
                            </div>
                            @if($transaction->single_rate)
                            <div class="txn-row">
                                <span class="txn-row-label">Single Rate</span>
                                <span class="txn-row-value">{{ $transaction->single_rate }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">To Currency</span>
                                <span class="txn-row-value">{{ strtoupper($transaction->to_currency ?? '—') }}</span>
                            </div>
                            @endif
                            @if($transaction->card_number)
                            <p class="txn-section-title mt-3">Card Details</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Card Number</span>
                                <span class="txn-row-value" style="font-family:monospace;">**** **** **** {{ substr($transaction->card_number, -4) }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="txn-row-label">Expiry</span>
                                <span class="txn-row-value">{{ $transaction->expiry_month ?? '—' }} / {{ $transaction->expiry_year ?? '—' }}</span>
                            </div>
                            @endif
                            @if($transaction->interac_email)
                            <p class="txn-section-title mt-3">Interac</p>
                            <div class="txn-row">
                                <span class="txn-row-label">Interac Email</span>
                                <span class="txn-row-value">{{ $transaction->interac_email }}</span>
                            </div>
                            @if($transaction->interac_first_name)
                            <div class="txn-row">
                                <span class="txn-row-label">Interac Name</span>
                                <span class="txn-row-value">{{ $transaction->interac_first_name }} {{ $transaction->interac_last_name }}</span>
                            </div>
                            @endif
                            @endif
                        </div>

                        {{-- Promo --}}
                        <div class="txn-tab-pane" id="promo-{{ $transaction->id }}">
                            <p class="txn-section-title">Promo Code</p>
                            @if($transaction->promo_code)
                            <div class="txn-row">
                                <span class="txn-row-label">Code</span>
                                <span class="txn-row-value">
                                    <span class="pill pill-credit" style="font-family:monospace;letter-spacing:.08em;">{{ $transaction->promo_code }}</span>
                                </span>
                            </div>
                            @if($transaction->promo_code_id)
                            <div class="txn-row">
                                <span class="txn-row-label">Code ID</span>
                                <span class="txn-row-value">#{{ $transaction->promo_code_id }}</span>
                            </div>
                            @endif
                            <div class="txn-row">
                                <span class="txn-row-label">Fee Waived</span>
                                <span class="txn-row-value">
                                    @if($transaction->promo_fee_waived)
                                        <span class="pill pill-success"><i class="fa-solid fa-check"></i> Yes</span>
                                    @else
                                        <span class="pill pill-neutral">No</span>
                                    @endif
                                </span>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fa-solid fa-tag" style="font-size:2rem;color:#d8b4fe;"></i>
                                <p class="mt-2 mb-0" style="color:#9ca3af;font-size:.85rem;">No promo code was applied to this transaction.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="txn-modal-footer">
                        <button class="txn-close-btn" data-bs-dismiss="modal">
                            <i class="fa-solid fa-xmark me-1"></i> Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    
    @include('admin.footer')

    <script>
    // Tab switching for transaction modals
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.txn-tab-btn');
        if (!btn) return;
        const tabId = btn.dataset.tab;
        const nav   = btn.closest('.txn-tabs');
        const modal = btn.closest('.modal-content');
        nav.querySelectorAll('.txn-tab-btn').forEach(b => b.classList.remove('active'));
        modal.querySelectorAll('.txn-tab-pane').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        const pane = modal.querySelector('#' + tabId);
        if (pane) pane.classList.add('active');
    });

    // Reset tab to Overview when modal opens
    document.querySelectorAll('.txn-modal').forEach(function (modal) {
        modal.addEventListener('show.bs.modal', function () {
            modal.querySelectorAll('.txn-tab-btn').forEach((b, i) => b.classList.toggle('active', i === 0));
            modal.querySelectorAll('.txn-tab-pane').forEach((p, i) => p.classList.toggle('active', i === 0));
        });
    });
    </script>
</body>
</html>