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
                            <i class="fa-solid fa-chart-line icon-gradient bg-ripe-malin"></i>
                        </div>

                        <div>
                            Bill Payments
                            <div class="page-title-subheading">
                                Review and manage all bill payment transactions including amounts, services, and statuses.
                            </div>
                        </div>

                    </div>
                </div>
            </div>


 <div class="main-card mb-3 card"> 
    <div class="card-header">
        <div class="card-header-title font-size-lg fw-normal">
            Recent Bill Payments
        </div>
    </div>

<div class="mb-3">
    <input type="text" id="transactionSearch" class="form-control" placeholder="Search transactions...">
</div>

    <div class="table-responsive">
        <table class="align-middle table table-borderless table-hover mb-0">
            <thead>
                <tr>
                    <th class="text-center">Service</th>
                    <th class="text-center">Biller Code</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($allbillpayment as $bill)
                <tr>
                    <td class="text-center">{{ $bill->service_id }}</td>
                    <td class="text-center">{{ $bill->billers_code ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($bill->amount, 2) }}</td>
                    <td class="text-center">{{ $bill->phone }}</td>
                    <td class="text-center">{{ strtoupper($bill->currency) }}</td>

                    <td class="text-center">
                        @if ($bill->status == 'success')
                            <span class="badge bg-success">Success</span>
                        @elseif ($bill->status == 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-warning">{{ ucfirst($bill->status) }}</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <button class="btn btn-primary btn-sm view-response-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#billModal"
                                data-response='@json($bill->response)'
                                data-requestid="{{ $bill->request_id }}"
                                data-service="{{ $bill->service_id }}"
                                data-phone="{{ $bill->phone }}"
                                data-amount="{{ number_format($bill->amount, 2) }}"
                                data-status="{{ $bill->status }}">
                            View
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ⬇️ ADD PAGINATION HERE -->
    <div class="mt-3">
        {{ $allbillpayment->links('pagination::bootstrap-5') }}
    </div>
        
                    
                    
   
            


            

                            
                </div>
            


               
            </div>

        </div>
    </div>

@include('admin.footer')


<!-- =============================
     BILL PAYMENT MODAL
============================= -->
<div class="modal fade" id="billModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Bill Payment Details</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <h6><strong>Request ID:</strong> <span id="modal-requestid"></span></h6>
                <h6><strong>Service:</strong> <span id="modal-service"></span></h6>
                <h6><strong>Phone:</strong> <span id="modal-phone"></span></h6>
                <h6><strong>Amount:</strong> ₦<span id="modal-amount"></span></h6>
                <h6><strong>Status:</strong>
                    <span id="modal-status" class="badge"></span>
                </h6>

                <hr>

                <h6><strong>Full VTpass Response:</strong></h6>
                <pre id="modal-response-box" 
                     style="background:#f8f9fa;padding:15px;border-radius:8px;
                            max-height:350px;overflow:auto;font-size:14px;"></pre>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>


<!-- =============================
     SHOW JSON RESPONSE IN MODAL
============================= -->
<script>
document.querySelectorAll('.view-response-btn').forEach(btn => {
    btn.addEventListener('click', function () {

        // Fill fields
        document.getElementById('modal-requestid').textContent = this.dataset.requestid;
        document.getElementById('modal-service').textContent = this.dataset.service;
        document.getElementById('modal-phone').textContent = this.dataset.phone;
        document.getElementById('modal-amount').textContent = this.dataset.amount;

        // Status badge color
        const statusEl = document.getElementById('modal-status');
        const status = this.dataset.status;

        statusEl.textContent = status.toUpperCase();
        statusEl.className = 'badge';

        if(status === 'success') statusEl.classList.add('bg-success');
        else if(status === 'failed') statusEl.classList.add('bg-danger');
        else statusEl.classList.add('bg-warning');

        // Response JSON
        let raw = this.dataset.response;
        let box = document.getElementById('modal-response-box');

        try {
            let parsed = JSON.parse(raw);
            box.textContent = JSON.stringify(parsed, null, 4);
        } catch (e) {
            box.textContent = raw;
        }
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
