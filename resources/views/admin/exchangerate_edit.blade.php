@include('admin.head')

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
                                <div class="card-header-title font-size-lg text-capitalize fw-normal">Edit Exchange Rates
                                </div>
                            </div>


                       <!-- Blade: admin/exchangerate.blade.php -->
                                              <div id="toast-container"></div>

<div class="container">
    <h3>Edit Exchange Rate</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.exchangerate.update', $rate->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="country_name" class="form-label">Country Name</label>
            <input type="text" name="country_name" id="country_name" class="form-control" value="{{ old('country_name', $rate->country_name) }}">
            @error('country_name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="currency_code" class="form-label">Currency Code</label>
            <input type="text" name="currency_code" id="currency_code" class="form-control" value="{{ old('currency_code', $rate->currency_code) }}">
            @error('currency_code') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="rate" class="form-label">Exchange Rate</label>
            <input type="number" step="0.0001" name="rate" id="rate" class="form-control" value="{{ old('rate', $rate->rate) }}">
            @error('rate') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="transfer_fee" class="form-label">Transfer Fee</label>
            <input type="number" step="0.01" name="transfer_fee" id="transfer_fee" class="form-control" value="{{ old('transfer_fee', $rate->transfer_fee) }}">
            @error('transfer_fee') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Rate</button>
            <a href="{{ route('admin.exchangerate') }}" class="btn btn-secondary">Cancel</a>
    </form>
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
document.addEventListener('DOMContentLoaded', function() {
    const statusLinks = document.querySelectorAll('.change-status');

    statusLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const chargebackId = this.dataset.id;
            const newStatus = this.dataset.status;

            Swal.fire({
                title: 'Are you sure?',
                text: `Change status to "${newStatus}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if(result.isConfirmed) {
                    fetch(`/admin/chargeback/${chargebackId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ status: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            Swal.fire('Updated!', data.message, 'success').then(() => {
                                location.reload(); // Reload to see updated status
                            });
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    });
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
