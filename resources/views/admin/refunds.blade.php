@include('admin.head')

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
                                    <i class="pe-7s-graph icon-gradient bg-ripe-malin"></i>
                                </div>
                                    <div>
                                        All Refund History
                                        <div class="page-title-subheading">
                                            View and manage all your refunds, including status, amounts, and payment methods. 
                                            Use the search box to quickly find specific refunds.
                                        </div>
                                    </div>

                            </div>
                            {{-- <div class="page-title-actions">
                                <button type="button" data-bs-toggle="tooltip" title="Example Tooltip" data-bs-placement="bottom"
                                    class="btn-shadow me-3 btn btn-dark">
                                    <i class="fa fa-star"></i>
                                </button>
                                <div class="d-inline-block dropdown">
                                    <button type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                                        <span class="btn-icon-wrapper pe-2 opacity-7">
                                            <i class="fa fa-business-time fa-w-20"></i>
                                        </span>
                                        Buttons
                                    </button>
                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link">
                                                    <i class="nav-link-icon lnr-inbox"></i>
                                                    <span> Inbox</span>
                                                    <div class="ms-auto badge rounded-pill bg-secondary">86</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">
                                                    <i class="nav-link-icon lnr-book"></i>
                                                    <span> Book</span>
                                                    <div class="ms-auto badge rounded-pill bg-danger">5</div>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link">
                                                    <i class="nav-link-icon lnr-picture"></i>
                                                    <span> Picture</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a disabled class="nav-link disabled">
                                                    <i class="nav-link-icon lnr-file-empty"></i>
                                                    <span> File Disabled</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>     --}}
                        </div>
                    </div> 
        
                    
                    
   
            


            

                    <div class="main-card mb-3 card">
                            <div class="card-header">
                                <div class="card-header-title font-size-lg text-capitalize fw-normal">All Refund
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" id="transactionSearch" class="form-control" placeholder="Search transactions...">
                            </div>

                        <div class="table-responsive">
                            <table class="align-middle text-truncate mb-0 table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Due Date</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Currency</th>
                                        <th class="text-center">Reference</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($refunds as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                                            <td class="text-center">
                                                @if ($item->status == 'success')
                                                    <div class="badge rounded-pill bg-success">Success</div>
                                                @elseif ($item->status == 'failed')
                                                    <div class="badge rounded-pill bg-danger">Failed</div>
                                                @else
                                                    <div class="badge rounded-pill bg-warning">{{ ucfirst($item->status) }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->referenceNumber ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->action ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->transaction_ref_number  ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->reason ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->type ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->time_date->format('d M Y') }}</td>
                                            <td class="text-center">{{ $item->recipient ?? 'N/A' }}</td>
                                            <td class="text-center">{{ ucfirst($item->currency ?? 'N/A') }}</td>
                                            <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination links -->
                            <div class="mt-3">
                                {{ $refunds->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
                        {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                              
                                    <button type="button" class="btn me-2 mb-2 btn-primary" data-bs-toggle="modal"
                                        data-bs-target=".bd-example-modal-lg">Large modal</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}


            </div>

        </div>
    </div>

@include('admin.footer')

<!-- Single Modal outside the loop -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Sender:</strong> <span id="modal-sender"></span></div>
                    <div class="col-md-6"><strong>Recipient:</strong> <span id="modal-recipient"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Amount:</strong> <span id="modal-amount"></span> <span id="modal-currency"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modal-status" class="badge"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Method:</strong> <span id="modal-method"></span></div>
                    <div class="col-md-6"><strong>Reference:</strong> <span id="modal-reference"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Transaction Type:</strong> <span id="modal-type"></span></div>
                    <div class="col-md-6"><strong>Created At:</strong> <span id="modal-created"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JS to fill modal dynamically -->
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

    const viewButtons = document.querySelectorAll('.view-transaction-btn');

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            senderEl.textContent = this.dataset.sender || 'N/A';
            recipientEl.textContent = this.dataset.recipient || 'N/A';
            amountEl.textContent = this.dataset.amount || '0.00';
            currencyEl.textContent = this.dataset.currency || '';
            methodEl.textContent = this.dataset.method || 'N/A';
            referenceEl.textContent = this.dataset.reference || 'N/A';
            typeEl.textContent = this.dataset.type || 'N/A';
            createdEl.textContent = this.dataset.created || 'N/A';

            // Set status badge color
            const status = this.dataset.status;
            statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            statusEl.className = 'badge';
            if(status === 'success') statusEl.classList.add('bg-success');
            else if(status === 'failed') statusEl.classList.add('bg-danger');
            else statusEl.classList.add('bg-warning');
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const transactionId = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // Blue
                cancelButtonColor: 'danger',     // Red
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If you want to delete via form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/transactionhistory/${transactionId}`; // Update route
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
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const match = cells.some(td => td.textContent.toLowerCase().includes(query));
            row.style.display = match ? '' : 'none';
        });
    });
});
</script>
