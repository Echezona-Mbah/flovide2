@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            
            <div class="app-main__outer">
                <div class="app-main__inner">

                <!-- Donations Page Title -->
                <div class="app-page-title">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div class="page-title-icon">
                                <i class="pe-7s-graph icon-gradient bg-ripe-malin"></i>
                            </div>

                            <div>
                                Invoice
                                <div class="page-title-subheading">
                                    View and manage all Invoice campaigns, including total goals, contributors, and currency.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


    <div class="main-card mb-3 card"> 
        <div class="card-header">
            <div class="card-header-title font-size-lg fw-normal">
                Invoice
            </div>
        </div>

        <div class="mb-3">
            <input type="text" id="transactionSearch" class="form-control" placeholder="Search transactions...">
        </div>

        <div class="table-responsive">
            <table class="align-middle table table-borderless table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Invoice Number</th>
                        <th class="text-center">Tracking code</th>
                        <th class="text-center">Billed to</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">Due Date</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Note</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Invoice Receipt link</th>
                        <th class="text-center">Created at</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($invoices as $invoice)
                    <tr>
                        <td class="text-center">{{ $invoice->user_id}}</td>
                        <td class="text-center">{{ number_format($invoice->invoice_number) }}</td>
                        <td class="text-center">{{ strtoupper($invoice->tracking_code) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->billed_to) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->address) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->due_date) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->currency) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->note) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->amount) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->status) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->invoice_receipt_link) }}</td>
                        <td class="text-center">{{ ucfirst($invoice->created_at) }}</td>
                        <td>
                            <a href="{{ url('/admin/invoice/'.$invoice->id) }}" 
                            class="btn btn-primary btn-sm">
                                Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ⬇️ ADD PAGINATION HERE -->
        <div class="mt-3">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
        
                    
                    
   
    </div>
            


               
            </div>

        </div>
    </div>

@include('admin.footer')


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
