@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            
            <div class="app-main__outer">
                <div class="app-main__inner">
<!-- Donation Records Page Title -->
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-note icon-gradient bg-ripe-malin"></i>
            </div>

            <div>
                Payment Records
                <div class="page-title-subheading">
                    Review all contributions for a specific Payment campaign, including donor details, amounts, and statuses.
                </div>
            </div>

        </div>
    </div>
</div>


 <div class="main-card mb-3 card"> 
    <div class="card-header">
        <div class="card-header-title font-size-lg fw-normal">
            Payment Records            {{ $payment->title }}
        Total Goal: {{ number_format($payment->amount) }} {{ $payment->currency }}
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
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Reference</th>
                </tr>
            </thead>

           <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="text-center">{{ $record->name }}</td>
                        <td class="text-center">{{ $record->email }}</td>
                        <td class="text-center">{{ $record->phone }}</td>
                        <td class="text-center">{{ number_format($record->amount) }}</td>
                        <td class="text-center">{{ strtoupper($record->currency) }}</td>
                        <td class="text-center">{{ ucfirst($record->status) }}</td>
                        <td class="text-center">{{ $record->reference }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No donations yet.</td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- ⬇️ ADD PAGINATION HERE -->
    <div class="mt-3">
        {{ $records->links('pagination::bootstrap-5') }}
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
