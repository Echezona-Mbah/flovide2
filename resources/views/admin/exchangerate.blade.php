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
    All Exchange Rates
    <div class="page-title-subheading">
        View and manage all your exchange rates, including rates, transfer fees, and currencies.
        Use the search box to quickly find specific exchange rates.
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
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="card-header-title font-size-lg text-capitalize fw-normal">
            All Exchange Rates
        </div>
        <a href="{{ route('admin.exchangerate.create') }}" class="btn btn-success">
            + Add New Exchange Rate
        </a>
    </div>

<form method="GET" action="{{ route('admin.exchangerate') }}" class="mb-3">
    <input type="text" name="search" class="form-control" placeholder="Search transactions..." value="{{ request('search') }}">
</form>
                                              <div id="toast-container"></div>

    <div class="table-responsive">
        <table class="align-middle text-truncate mb-0 table table-borderless table-hover">
            <thead>
                <tr>
                    <th class="text-center">Country</th>
                    <th class="text-center">Currency</th>
                    <th class="text-center">Rate</th>
                    <th class="text-center">Transfer Fee</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exchangerates as $item)
                    <tr>
                        <td class="text-center">{{ $item->country_name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->currency_code ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->rate ?? 'N/A' }}</td>
                        <td class="text-center">{{ $item->transfer_fee ?? 'N/A' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.exchangerate.edit', $item->id) }}" class="mb-2 me-2 btn btn-primary">
                                Update
                            </a>

                            <form action="{{ route('admin.exchangerate.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="mb-2 me-2 btn btn-danger" onclick="return confirm('Are you sure you want to delete this exchange rate?');">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        <div class="mt-3">
            {{ $exchangerates->links('pagination::bootstrap-5') }}
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
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    function showToast(message, type = "success") { 
        var toastContainer = document.getElementById("toast-container");
        if (!toastContainer) return;

        var toast = document.createElement("div");
        toast.className = "toast " + type;
        toast.textContent = message;

        var progress = document.createElement("div");
        progress.className = "toast-progress";

        toast.appendChild(progress);
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 4000);
    }

    // Laravel Validation Errors
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showToast("{{ $error }}", "error");
        @endforeach
    @endif

    // Success Toast
    @if (session('success'))
        showToast("{{ session('success') }}", "success");
    @endif

    // Error Toast
    @if (session('error'))
        showToast("{{ session('error') }}", "error");
    @endif

});
</script>