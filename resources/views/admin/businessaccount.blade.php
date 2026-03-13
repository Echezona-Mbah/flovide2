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
                                        Business Account
                                        <div class="page-title-subheading">
                                            View and manage all your business transactions, including status, amounts, and payment methods.
                                            Use the search box to quickly find any transaction linked to your business account.
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
        
                    
                    
   
            


            

<div class="row">
    <div class="col-md-12">

        <div class="main-card mb-3 card">

            <!-- Header -->
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

                <h5 class="mb-2 mb-md-0 fw-bold">
                    Active Users
                </h5>

                <form method="GET" action="" class="d-flex align-items-center gap-2">

                    <input 
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control form-control-sm"
                        placeholder="Search user..."
                        style="width:220px;"
                    >

                    <button class="btn btn-primary btn-sm">
                        <i class="fa fa-search"></i>
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-light btn-sm border">
                        Clear
                    </a>

                </form>

            </div>


            <!-- Table -->
            <div class="table-responsive">
                @if(session('success'))
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6'
                });
                </script>
                @endif

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px;">User</th>
                            <th>Email</th>
                            <th class="d-none d-md-table-cell">City</th>
                            <th class="d-none d-lg-table-cell">Balance</th>
                            <th class="d-none d-lg-table-cell">Currency</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($allUser as $user)

                            <tr>

                                <!-- User -->
                                <td>
                                    <div class="d-flex align-items-center">

                                            <img 
                                            src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
                                            class="rounded-circle me-2"
                                            width="38"
                                            height="38"
                                            >

                                        <div>
                                            <div class="fw-semibold">
                                                {{ $user->business_name ?? $user->firstname.' '.$user->lastname }}
                                            </div>

                                            <small class="text-muted">
                                                {{ $user->business_phone ?? 'N/A' }}
                                            </small>
                                        </div>

                                    </div>
                                </td>


                                <!-- Email -->
                                <td>
                                    {{ $user->email ?? 'N/A' }}
                                </td>


                                <!-- City -->
                                <td class="d-none d-md-table-cell">
                                    {{ $user->city ?? 'N/A' }}
                                </td>


                                <!-- Balance -->
                                <td class="d-none d-lg-table-cell">
                                    {{ $user->balance ?? '0' }}
                                </td>


                                <!-- Currency -->
                                <td class="d-none d-lg-table-cell">
                                    {{ $user->currency ?? 'N/A' }}
                                </td>


                                <!-- Status -->
                                <td>
                                    @if($user->deletestatus == 'active')
                                        <span class="badge bg-success">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>
                                    @endif
                                </td>


                                <!-- Actions -->
                                <td class="text-end">

                                    <div class="btn-group btn-group-sm">

                                        <a 
                                            href="{{ url('/admin/business-account/'.$user->id) }}"
                                            class="btn btn-info"
                                            title="View"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a 
                                            href="{{ url('/admin/business-account/edit/'.$user->id) }}"
                                            class="btn btn-warning"
                                            title="Edit"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button 
                                        class="btn btn-secondary toggleStatus"
                                        data-id="{{ $user->id }}"
                                        title="Activate/Deactivate"
                                        >
                                        @if($user->deletestatus == 'active')
                                        <i class="fa fa-ban"></i>
                                        @else
                                        <i class="fa fa-check"></i>
                                        @endif
                                        </button>

                                        <button 
                                        type="button"
                                        class="btn btn-danger deleteUser"
                                        data-id="{{ $user->id }}"
                                        title="Delete"
                                        >
                                        <i class="fa fa-trash"></i>
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <!-- Pagination -->
            <div class="p-3">
                {{ $allUser->links('pagination::bootstrap-5') }}
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

document.addEventListener("DOMContentLoaded", function () {

    // ACTIVATE / DEACTIVATE
    document.querySelectorAll('.toggleStatus').forEach(button => {

        button.addEventListener('click', function () {

            let id = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change this user's status?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = "/admin/business-account/deactivate/" + id;
                }

            });

        });

    });


    // DELETE USER
    document.querySelectorAll('.deleteUser').forEach(button => {

        button.addEventListener('click', function () {

            let id = this.dataset.id;

            Swal.fire({
                title: 'Delete User?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'No'
            }).then((result) => {

                if (result.isConfirmed) {

                    let form = document.createElement('form');
                    form.method = "POST";
                    form.action = "/admin/business-account/delete/" + id;

                    let csrf = document.createElement('input');
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    let method = document.createElement('input');
                    method.type = "hidden";
                    method.name = "_method";
                    method.value = "DELETE";

                    form.appendChild(csrf);
                    form.appendChild(method);

                    document.body.appendChild(form);
                    form.submit();
                }

            });

        });

    });

});
</script>
