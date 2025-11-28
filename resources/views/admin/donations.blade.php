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
                                Donations
                                <div class="page-title-subheading">
                                    View and manage all donation campaigns, including total goals, contributors, and currency.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


    <div class="main-card mb-3 card"> 
        <div class="card-header">
            <div class="card-header-title font-size-lg fw-normal">
                Donation
            </div>
        </div>

        <div class="mb-3">
            <input type="text" id="transactionSearch" class="form-control" placeholder="Search transactions...">
        </div>

        <div class="table-responsive">
            <table class="align-middle table table-borderless table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">Title</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Currency</th>
                        <th class="text-center">Visibility</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($donations as $donation)
                    <tr>
                        <td class="text-center">{{ $donation->title }}</td>
                        <td class="text-center">{{ number_format($donation->amount) }}</td>
                        <td class="text-center">{{ strtoupper($donation->currency) }}</td>
                        <td class="text-center">{{ ucfirst($donation->visibility) }}</td>



                        <td>
                            <a href="{{ url('/admin/donation/'.$donation->id) }}" 
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
            {{ $donations->links('pagination::bootstrap-5') }}
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
