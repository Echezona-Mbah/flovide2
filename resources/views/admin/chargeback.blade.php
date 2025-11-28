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
                                        All Chargeback History
                                        <div class="page-title-subheading">
                                            View and manage all your chargebacks, including status, amounts, and payment methods. 
                                            Use the search box to quickly find specific chargebacks.
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
                                <div class="card-header-title font-size-lg text-capitalize fw-normal">All Chargeback
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
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Currency</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Deadline</th>
                                        <th class="text-center">Transaction Reference</th>
                                        <th class="text-center">Reason</th>
                                        <th class="text-center">Evidence_path</th>
                                        <th class="text-center">Note</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Chargebacks as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->user_id->name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->name ?? 'N/A' }}</td>
                                            <td class="text-center">{{ ucfirst($item->currency ?? 'N/A') }}</td>
                                            <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                                        <td class="text-center">
                                            @php
                                                $status = strtolower($item->status ?? 'unknown');
                                                $statusColors = [
                                                    'new' => 'bg-secondary text-white',          // Gray
                                                    'resolved' => 'bg-success text-white',       // Green
                                                    'evidence submitted' => 'bg-primary text-white', // Blue
                                                    'evidence rejected' => 'bg-danger text-white',   // Red
                                                ];
                                                $badgeClass = $statusColors[$status] ?? 'bg-warning text-dark';
                                            @endphp

                                            <div class="badge rounded-pill {{ $badgeClass }}">
                                                {{ ucfirst($item->status) }}
                                            </div>
                                        </td>

                                            <td class="text-center">{{ $item->deadline }}</td>
                                            <td class="text-center">{{ $item->transaction_reference ?? 'N/A' }}</td>
                                            <td class="text-center">{{ $item->reason ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                @if($item->evidence_path && file_exists(storage_path('app/public/' . $item->evidence_path)))
                                                    @php
                                                        $extension = pathinfo($item->evidence_path, PATHINFO_EXTENSION);
                                                    @endphp

                                                    @if(in_array(strtolower($extension), ['jpg','jpeg','png']))
                                                        <a href="{{ asset('storage/' . $item->evidence_path) }}" target="_blank">
                                                            <img
                                                                src="{{ asset('storage/' . $item->evidence_path) }}"
                                                                alt="Evidence"
                                                                width="60"
                                                                style="cursor:pointer;"
                                                            >
                                                        </a>
                                                    @elseif(strtolower($extension) === 'pdf')
                                                        <a href="{{ asset('storage/' . $item->evidence_path) }}" download class="badge bg-primary text-white">
                                                            Download PDF
                                                        </a>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Unsupported file</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-danger">No File</span>
                                                @endif
                                            </td>

                                            <td class="text-center">{{ $item->note ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown d-inline-block">
                                                        <button type="button" data-bs-toggle="dropdown"
                                                                class="mb-2 me-2 dropdown-toggle btn btn-primary">
                                                            Action
                                                        </button>

                                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-xl">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item">
                                                                    <a href="#"
                                                                    class="nav-link change-status"
                                                                    data-status="resolved"
                                                                    data-id="{{ $item->id }}">
                                                                        Resolved
                                                                    </a>
                                                                </li>

                                                                <li class="nav-item">
                                                                    <a href="#"
                                                                    class="nav-link change-status text-danger"
                                                                    data-status="evidence rejected"
                                                                    data-id="{{ $item->id }}">
                                                                        Evidence Rejected
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination links -->
                            <div class="mt-3">
                                {{ $Chargebacks->links('pagination::bootstrap-5') }}
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
