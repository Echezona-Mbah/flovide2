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
                                        Account  
                                        <div class="page-title-subheading">
                                            View and manage all Account.  
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 
        
                    
                    
   
            


            

                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">All Account
        
                                    </div>
                                    <div class="mb-3">
                                <input type="text" id="transactionSearch" class="form-control" placeholder="Search transactions...">
                            </div>
                                <div class="table-responsive" id="beneficiaTable">
                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Account Name</th>
                                                <th class="text-center">Bank</th>
                                                <th class="text-center">Account Number</th>
                                                <th class="text-center">Country</th>
                                                <th class="text-center">Currency</th>
                                                <th class="text-center">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allaccount as $b)
                                            <tr>
                                                <td class="text-center">{{ $b->account_name }}</td>
                                                <td class="text-center">{{ $b->bank }}</td>
                                                <td class="text-center">{{ $b->account_number }}</td>
                                                <td class="text-center">{{ $b->country }}</td>
                                                <td class="text-center">{{ $b->currency }}</td>
                                                <td class="text-center">{{ $b->type }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="mt-3">
                                        {{ $allaccount->links('pagination::bootstrap-5') }}
                                    </div>
                                </div>
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

