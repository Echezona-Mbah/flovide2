@include('admin.head')


<style>
    :root {
        --ink: #14213d;
        --ink-soft: #5b6475;
        --paper: #ffffff;
        --line: #e7ecf3;
        --blue: #1d4ed8;
        --green: #16a34a;
        --red: #dc2626;
        --amber: #d97706;
        --purple: #7c3aed;
        --shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .bank-stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .stat-icon {
        width: 54px; height: 54px;
        border-radius: 18px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .stat-blue   { background: rgba(29, 78, 216, 0.12);  color: var(--blue); }
    .stat-amber  { background: rgba(217, 119, 6, 0.12);  color: var(--amber); }
    .stat-green  { background: rgba(22, 163, 74, 0.12);  color: var(--green); }
    .stat-red    { background: rgba(220, 38, 38, 0.12);  color: var(--red); }

    .stat-label  { font-size: 13px; color: var(--ink-soft); margin-bottom: 6px; }
    .stat-value  { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1.1; }
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
                                    <i class="fa-solid fa-bank icon-gradient bg-ripe-malin"></i>
                                </div>
                                <div>
                                    Bank Account Requests
                                    <div class="page-title-subheading">
                                        View Bank Account Requests 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 

                    {{-- ── Stats Cards ──────────────────────────────────────── --}}
                    <div class="bank-stats-row">

                        {{-- Total --}}
                        <div class="stat-box">
                            <div class="stat-icon stat-blue"><i class="fa-solid fa-layer-group"></i></div>
                            <div class="stat-label">Total Requests</div>
                            <div class="stat-value">{{ number_format($totalRequests) }}</div>
                        </div>

                        {{-- Pending / Processing --}}
                        <div class="stat-box">
                            <div class="stat-icon stat-amber"><i class="fa-solid fa-hourglass-half"></i></div>
                            <div class="stat-label">Pending / Processing</div>
                            <div class="stat-value">{{ number_format($pendingRequests) }}</div>
                        </div>

                        {{-- Approved / Confirmed --}}
                        <div class="stat-box">
                            <div class="stat-icon stat-green"><i class="fa-solid fa-circle-check"></i></div>
                            <div class="stat-label">Confirmed / Approved</div>
                            <div class="stat-value">{{ number_format($approvedRequests) }}</div>
                        </div>

                        {{-- Rejected --}}
                        <div class="stat-box">
                            <div class="stat-icon stat-red"><i class="fa-solid fa-circle-xmark"></i></div>
                            <div class="stat-label">Rejected</div>
                            <div class="stat-value">{{ number_format($rejectedRequests) }}</div>
                        </div>

                    </div>
        
                    
                    <div class="main-card mb-3 card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">Bank Account Requests</h5>
                            <form method="GET" action="{{ route('admin.bank-account-requests') }}" class="d-flex gap-2 align-items-center">
                                <div class="input-group input-group-sm" style="min-width: 260px;">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fa fa-search text-muted"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="search"
                                        id="bankRequestSearch"
                                        class="form-control border-start-0 ps-0"
                                        placeholder="Search by name or email…"
                                        value="{{ $search ?? '' }}"
                                        autocomplete="off"
                                    >
                                    @if(!empty($search))
                                        <a href="{{ route('admin.bank-account-requests') }}" class="btn btn-outline-secondary btn-sm" title="Clear">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </form>
                        </div>

                        <div id="toast-container"></div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Customer</th>
                                            <th>Email</th>
                                            <th class="text-center">BVN</th>
                                            <th class="text-center">NIN</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Submitted</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bankAccountRequests as $request)
                                            <tr>
                                                <td class="text-center text-muted">{{ $bankAccountRequests->firstItem() + $loop->index }}</td>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-heading">
                                                                    {{ $request->personal->firstname ?? 'N/A' }} {{ $request->personal->lastname ?? '' }}
                                                                </div>
                                                                <div class="widget-subheading opacity-7">Personal Account</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $request->personal->email ?? '—' }}</td>
                                                <td class="text-center">
                                                    <code>{{ '****' . substr($request->bvn, -4) }}</code>
                                                </td>
                                                <td class="text-center">
                                                    <code>{{ '****' . substr($request->nin, -4) }}</code>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusMap = [
                                                            'pending'    => 'badge bg-warning text-dark',
                                                            'processing' => 'badge bg-info text-dark',
                                                            'approved'   => 'badge bg-success',
                                                            'rejected'   => 'badge bg-danger',
                                                        ];
                                                        $badgeClass = $statusMap[$request->status] ?? 'badge bg-secondary';
                                                    @endphp
                                                    <span class="{{ $badgeClass }}">{{ ucfirst($request->status) }}</span>
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $request->created_at->format('d M Y') }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.bank-account-requests.show', $request->id) }}"
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4 text-muted">
                                                    <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                                    No bank account requests found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            @if($bankAccountRequests->hasPages())
                                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                                    <small class="text-muted">
                                        Showing {{ $bankAccountRequests->firstItem() }}–{{ $bankAccountRequests->lastItem() }}
                                        of {{ $bankAccountRequests->total() }} requests
                                    </small>
                                    <div>
                                        {{ $bankAccountRequests->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    

                </div>
                
            </div>

        </div>
    </div>


    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Reply to Contact Request
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="contact_id">
                    <div class="mb-3">
                        <label>Recipient</label>
                        <input type="email" id="recipient_email" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Subject</label>
                        <input type="text" id="reply_subject" class="form-control" placeholder="Re: Your Contact Request">
                    </div>

                    <div class="mb-3">
                        <label>Message</label>
                        <textarea id="reply_message" rows="8" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button id="sendReplyBtn" class="btn btn-success" onclick="sendReply()">
                        <span id="sendReplySpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        <i class="fa fa-paper-plane"></i>
                        <span id="sendReplyText">Send Reply</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

</script>



