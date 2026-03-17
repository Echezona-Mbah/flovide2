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
            {{-- <div class="card-header d-flex justify-content-between align-items-center flex-wrap">

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

            </div> --}}


           <div class="container-fluid">

    <!-- Admin Header -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body d-flex align-items-center">

            <img src="{{ $admin->profile_picture ? asset('storage/'.$admin->profile_picture) : asset('assets/images/avatars/default.png') }}"
                 class="rounded-circle me-3"
                 width="70"
                 height="70">

            <div>
                <h4 class="mb-1">{{ $admin->name }}</h4>
                <small class="text-muted">{{ $admin->email }}</small>
            </div>

            <div class="ms-auto text-end">
                <span class="badge bg-dark">Admin Activity Center</span>
            </div>

        </div>
    </div>


    <!-- Statistics -->
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Logins</h6>
                    <h3 class="text-success">{{ $logins->total() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Activities Recorded</h6>
                    <h3 class="text-primary">{{ $activities->total() }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <h6 class="text-muted">Last Login</h6>
                    <h5 class="text-dark">
                        {{ optional($logins->first())->created_at?->diffForHumans() ?? 'N/A' }}
                    </h5>
                </div>
            </div>
        </div>

    </div>


    <!-- Login Logs -->
    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="fas fa-sign-in-alt me-2 text-success"></i>
                Login Logs
            </h5>
        </div>

        <div class="table-responsive">

            <table class="table align-middle table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>IP Address</th>
                        <th>Device</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($logins as $login)

                    <tr>
                        <td>
                            <span class="fw-bold">{{ $login->ip_address }}</span>
                        </td>

                        <td>
                            <i class="fas fa-desktop text-muted me-1"></i>
                            {{ $login->device }}
                        </td>

                        <td>
                            {{ $login->country ?? 'Unknown' }}
                        </td>

                        
                        <td>
                            {{ $login->city ?? 'Unknown' }}
                        </td>

                        <td>
                            {{ $login->latitude ?? 'Unknown' }}
                        </td>

                        
                        <td>
                            {{ $login->longitude ?? 'Unknown' }}
                        </td>

                        <td>
                            @if($login->success)
                                <span class="badge bg-success">Success</span>
                            @else
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>

                        <td>
                            {{ $login->created_at->format('d M Y, H:i') }}
                        </td>
                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $logins->links('pagination::bootstrap-5') }}
        </div>

    </div>


    <!-- Activity Logs -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-0">
            <h5 class="mb-0">
                <i class="fas fa-user-shield me-2 text-primary"></i>
                Admin Activities
            </h5>
        </div>

        <div class="table-responsive">

            <table class="table align-middle table-striped mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Activity</th>
                        <th>IP Address</th>
                        <th>Device</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($activities as $activity)

                    <tr>

                        <td>
                            <span class="fw-semibold">{{ $activity->activity }}</span>
                        </td>

                        <td>{{ $activity->ip_address }}</td>

                        <td>
                            <i class="fas fa-laptop text-muted me-1"></i>
                            {{ $activity->device }}
                        </td>

                        <td>
                            {{ $activity->created_at->format('d M Y, H:i') }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white">
            {{ $activities->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>


            <!-- Pagination -->
            {{-- <div class="p-3">
                {{ $allUser->links('pagination::bootstrap-5') }}
            </div> --}}

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


