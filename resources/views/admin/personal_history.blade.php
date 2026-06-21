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
                                    <i class="pe-7s-note2 icon-gradient bg-happy-itmeo"></i>
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
                                    <i class="header-icon pe-7s-menu icon-gradient bg-ripe-malin"></i>
                                    {{ $personal->name ?? ($personal->firstname . ' ' . $personal->lastname) }}'s Transactions
                                </div>
                                <div class="btn-actions-pane-right">
                                    <form action="{{ route('admin.personal-transactions.history', $personal->id) }}" method="GET">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..." class="form-control">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary"><i class="pe-7s-search"></i></button>
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
                                    <tbody id="transactionTableBody">
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
                                                    <i class="pe-7s-info d-block mb-2" style="font-size: 2rem;"></i>
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




    <!-- Transaction Modals -->
    @foreach($transactions as $transaction)
        <div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel{{ $transaction->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionModalLabel{{ $transaction->id }}">Transaction Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Reference
                                <span class="font-weight-bold">{{ $transaction->reference ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Amount
                                <span class="font-weight-bold">{{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Type
                                <span class="font-weight-bold text-capitalize">{{ $transaction->type }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Status
                                <span class="font-weight-bold text-capitalize">{{ $transaction->status }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Method
                                <span class="font-weight-bold">{{ $transaction->method ?? 'N/A' }}</span>
                            </li>
                            @if($transaction->recipient_account_name || $transaction->recipient_alias)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Recipient
                                    <span class="font-weight-bold">{{ $transaction->recipient_account_name ?? $transaction->recipient_alias ?? 'N/A' }}</span>
                                </li>
                            @endif
                            @if($transaction->recipient_bank_name)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Bank Name
                                    <span class="font-weight-bold">{{ $transaction->recipient_bank_name ?? 'N/A' }}</span>
                                </li>
                            @endif
                            @if($transaction->recipient_account_number)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Account Number
                                    <span class="font-weight-bold">{{ $transaction->recipient_account_number ?? 'N/A' }}</span>
                                </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Date
                                <span class="font-weight-bold">{{ $transaction->created_at->format('M d, Y h:i A') }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    
    
    @include('admin.footer')

    <script>

    </script>
</body>
</html>