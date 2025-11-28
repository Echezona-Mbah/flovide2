@include('admin.head')
  <style>
  #toast-container {
    position: fixed !important;
    bottom: 20px !important;   /* move to bottom */
    right: 20px !important;    /* stay aligned right */
    display: flex !important;
    flex-direction: column !important;
    gap: 10px;
    z-index: 999999 !important;
}


.toast {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    min-width: 280px !important;
    max-width: 350px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    color: white;
    font-weight: 500;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 3.5s forwards;
    opacity: 1;
}

.toast.success {
    background: #28a745; /* Green */
}

.toast.error {
    background: #dc3545; /* Red */
}

.toast .toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.7);
    width: 100%;
    animation: progressBar 3s linear forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOut {
    from { opacity: 1; transform: translateY(0); }
    to { opacity: 0; transform: translateY(-20px); }
}

@keyframes progressBar {
    from { width: 100%; }
    to { width: 0%; }
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

                    <div class="main-card mb-3 card">
                            <div class="card-header">
                                <div class="card-header-title font-size-lg text-capitalize fw-normal">Add New Exchange Rate
                                </div>
                            </div>


                       <!-- Blade: admin/exchangerate.blade.php -->
                                              <div id="toast-container"></div>

<div class="main-card mb-3 card">
    <div class="card-header">
        <h5 class="card-title">Add New Exchange Rate</h5>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.exchangerate.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="country_name" class="form-label">Country Name</label>
                <input type="text" name="country_name" id="country_name" class="form-control" placeholder="Enter full country name" required>
            </div>

            <div class="mb-3">
                <label for="currency_code" class="form-label">Currency Code</label>
                <input type="text" name="currency_code" id="currency_code" class="form-control" placeholder="e.g., NGN, USD" required>
            </div>

            <div class="mb-3">
                <label for="rate" class="form-label">Exchange Rate</label>
                <input type="number" step="0.0001" name="rate" id="rate" class="form-control" placeholder="Enter rate" required>
            </div>

            <div class="mb-3">
                <label for="transfer_fee" class="form-label">Transfer Fee</label>
                <input type="number" step="0.01" name="transfer_fee" id="transfer_fee" class="form-control" placeholder="Enter transfer fee" required>
            </div>

            <button type="submit" class="btn btn-success">Add Exchange Rate</button>
            <a href="{{ route('admin.exchangerate') }}" class="btn btn-secondary">Cancel</a>
        </form>
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
