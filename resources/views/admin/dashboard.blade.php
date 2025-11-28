@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title app-page-title-simple">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div>
                                    <div class="page-title-head center-elem">
                                        <span class="d-inline-block pe-2">
                                            <i class="lnr-apartment opacity-6"></i>
                                        </span>
                                        <span class="d-inline-block">Dashboard</span>
                                    </div>

                                </div>
                            </div>
                            <div class="page-title-actions">
                                <div class="d-inline-block pe-3">
                                    <select id="custom-inp-top" type="select" class="form-select">
                                        <option>Select period...</option>
                                        <option>Last Week</option>
                                        <option>Last Month</option>
                                        <option>Last Year</option>
                                    </select>
                                </div>
                                {{-- <button type="button" data-bs-toggle="tooltip" data-bs-placement="left" class="btn btn-dark" title="Show a Toastr Notification!">
                                    <i class="fa fa-battery-three-quarters"></i>
                                </button> --}}
                            </div>
                        </div>
                    </div>        <div class="mbg-3 alert alert-info alert-dismissible fade show" role="alert">
                        <span class="pe-2">
                            <i class="fa fa-question-circle"></i>
                        </span>
                        Welcome back, Admin! 👋,Wishing you a productive and successful day ahead.
                    </div>
            
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="widget-chart widget-chart2 text-start mb-3 card-btm-border card-shadow-primary border-primary card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <div class="widget-title opacity-5 text-uppercase">Total Users</div>
                                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                            <div class="widget-chart-flex align-items-center">
                                                <div>
                                                    <span class="opacity-10 text-success pe-2">
                                                        <i class="fa fa-angle-up"></i>
                                                    </span>
                                                   {{$finalTotal}}
                                                    {{-- <small class="opacity-5 ps-1">%</small> --}}
                                                </div>
                                                <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                    <div class="circle-progress circle-progress-gradient-alt-sm d-inline-block">
                                                        <small></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget-chart widget-chart2 text-start mb-3 card-btm-border card-shadow-danger border-danger card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <div class="widget-title opacity-5 text-uppercase">Business Account</div>
                                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                            <div class="widget-chart-flex align-items-center">
                                                <div>
                                                     <span class="opacity-10 text-success pe-2">
                                                        <i class="fa fa-angle-up"></i>
                                                    </span>
                                                    {{$totalUsers}}
                                                    {{-- <small class="opacity-5 ps-1">%</small> --}}
                                                </div>
                                                <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                    <div class="circle-progress circle-progress-danger-sm d-inline-block">
                                                        <small></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget-chart widget-chart2 text-start mb-3 card-btm-border card-shadow-warning border-warning card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <div class="widget-title opacity-5 text-uppercase">Personnal Account</div>
                                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                            <div class="widget-chart-flex align-items-center">
                                                <div>
                                                     <span class="opacity-10 text-success pe-2">
                                                        <i class="fa fa-angle-up"></i>
                                                    </span>
                                                    {{$totalPersonals}}
                                                </div>
                                                <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                    <div class="circle-progress circle-progress-warning-sm d-inline-block">
                                                        <small></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget-chart widget-chart2 text-start mb-3 card-btm-border card-shadow-success border-success card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <div class="widget-title opacity-5 text-uppercase">New Employees</div>
                                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                                            <div class="widget-chart-flex align-items-center">
                                                <div>
                                                    <small class="text-success pe-1">+</small>
                                                    34
                                                    <small class="opacity-5 ps-1">hires</small>
                                                </div>
                                                <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                    <div class="circle-progress circle-progress-success-sm d-inline-block">
                                                        <small></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $dates = $transactions->pluck('date');
                            $totals = $transactions->pluck('total');
                        @endphp

                        <!-- Transaction Chart -->
                        <div class="col-sm-12 col-md-7 col-lg-8">
                            <div class="mb-3 card">
                                <div class="card-header-tab card-header">
                                    <div class="card-header-title font-size-lg text-capitalize fw-normal">
                                        Transaction History Overview
                                    </div>
                                    <div class="btn-actions-pane-right text-capitalize">
                                        <button class="btn btn-warning">Actions</button>
                                    </div>
                                </div>

                                <div class="pt-0 card-body">
                                    <div id="chart-combined"></div>
                                </div>
                            </div>
                        </div>

                        <!-- ApexCharts CDN -->
                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                        <!-- Chart Script -->
                        <script>
                            var options = {
                                chart: {
                                    type: 'line',
                                    height: 350,
                                    toolbar: { show: true }
                                },
                                series: [{
                                    name: "Total Transactions",
                                    data: @json($totals)
                                }],
                                xaxis: {
                                    categories: @json($dates),
                                    labels: { rotate: -45 }
                                },
                                stroke: {
                                    width: 4,
                                    curve: 'smooth'
                                },
                                markers: { size: 5 },
                                colors: ['#28a745']
                            };

                            var chart = new ApexCharts(document.querySelector("#chart-combined"), options);
                            chart.render();
                        </script>

                        <!-- Income Widget -->
                        <div class="col-sm-12 col-md-5 col-lg-4">
                            <div class="mb-3 card">
                                <div class="card-header-tab card-header">
                                    <div class="card-header-title font-size-lg text-capitalize fw-normal">Income</div>

                                    <div class="btn-actions-pane-right text-capitalize actions-icon-btn">
                                        <div class="btn-group">
                                            <button type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                class="btn-icon btn-icon-only btn btn-link">
                                                <i class="lnr-cog btn-icon-wrapper"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link">
                                                <h6 class="dropdown-header">Options</h6>
                                                <button class="dropdown-item"><i class="dropdown-icon lnr-inbox"></i>Menus</button>
                                                <button class="dropdown-item"><i class="dropdown-icon lnr-file-empty"></i>Settings</button>
                                                <button class="dropdown-item"><i class="dropdown-icon lnr-book"></i>Actions</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-0 card-body">
                                    <div id="chart-radial"></div>

                                    <div class="widget-content pt-0 w-100">
                                        <div class="widget-content-outer">
                                            <div class="widget-content-wrapper">

                                                <div class="widget-content-left pe-2 fsize-1">
                                                    <div class="widget-numbers mt-0 fsize-3 text-warning">
                                                        {{ number_format($incomePercent, 0) }}%
                                                    </div>
                                                </div>

                                                <div class="widget-content-right w-100">
                                                    <div class="progress-bar-xs progress">
                                                        <div class="progress-bar bg-warning"
                                                            role="progressbar"
                                                            aria-valuenow="{{ $incomePercent }}"
                                                            aria-valuemin="0"
                                                            aria-valuemax="100"
                                                            style="width: {{ $incomePercent }}%;">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="widget-content-left fsize-1 mt-2">
                                                <div class="text-muted opacity-6">
                                                    Total Income: ₦{{ number_format($totalIncome, 2) }} <br>
                                                    Target: ₦100,000
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <h6 class="widget-subheading">Income</h6>
                                        <div class="widget-chart-flex">
                                            <div class="widget-numbers mb-0 w-100">
                                                <div class="widget-chart-flex">
                                                    <div class="fsize-4">
                                                        <small class="opacity-5">$</small>
                                                        5,456
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                            <span class="text-success ps-2">+14%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <h6 class="widget-subheading">Expenses</h6>
                                        <div class="widget-chart-flex">
                                            <div class="widget-numbers mb-0 w-100">
                                                <div class="widget-chart-flex">
                                                    <div class="fsize-4 text-danger">
                                                        <small class="opacity-5 text-muted">$</small>
                                                        4,764
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                            <span class="text-danger ps-2">
                                                                <span class="pe-1">
                                                                    <i class="fa fa-angle-up"></i>
                                                                </span>
                                                                8%
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <h6 class="widget-subheading">Spendings</h6>
                                        <div class="widget-chart-flex">
                                            <div class="widget-numbers mb-0 w-100">
                                                <div class="widget-chart-flex">
                                                    <div class="fsize-4">
                                                        <span class="text-success pe-2">
                                                            <i class="fa fa-angle-down"></i>
                                                        </span>
                                                        <small class="opacity-5">$</small>
                                                        1.5M
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                            <span class="text-success ps-2">
                                                                <span class="pe-1">
                                                                    <i class="fa fa-angle-down"></i>
                                                                </span>
                                                                15%
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-chat-wrapper-outer">
                                    <div class="widget-chart-content">
                                        <h6 class="widget-subheading">Totals</h6>
                                        <div class="widget-chart-flex">
                                            <div class="widget-numbers mb-0 w-100">
                                                <div class="widget-chart-flex">
                                                    <div class="fsize-4">
                                                        <small class="opacity-5">$</small>
                                                        31,564
                                                    </div>
                                                    <div class="ms-auto">
                                                        <div class="widget-title ms-auto font-size-lg fw-normal text-muted">
                                                            <span class="text-warning ps-2">+76%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
            
                    {{-- <div class="mbg-3 h-auto ps-0 pe-0 bg-transparent no-border card-header">
                        <div class="card-header-title fsize-2 text-capitalize fw-normal">Target Section</div>
                        <div class="btn-actions-pane-right text-capitalize actions-icon-btn">
                            <button class="btn btn-link btn-sm">View Details</button>
                        </div>
                    </div> --}}
            
                    {{-- <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pe-2 fsize-1">
                                                <div class="widget-numbers mt-0 fsize-3 text-danger">71%</div>
                                            </div>
                                            <div class="widget-content-right w-100">
                                                <div class="progress-bar-xs progress">
                                                    <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="71"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 71%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6">Income Target</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pe-2 fsize-1">
                                                <div class="widget-numbers mt-0 fsize-3 text-success">54%</div>
                                            </div>
                                            <div class="widget-content-right w-100">
                                                <div class="progress-bar-xs progress">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="54"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 54%;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6">Expenses Target</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pe-2 fsize-1">
                                                <div class="widget-numbers mt-0 fsize-3 text-warning">32%</div>
                                            </div>
                                            <div class="widget-content-right w-100">
                                                <div class="progress-bar-xs progress">
                                                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="32"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 32%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6">Spendings Target</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card-shadow-primary mb-3 widget-chart widget-chart2 text-start card">
                                <div class="widget-content p-0 w-100">
                                    <div class="widget-content-outer">
                                        <div class="widget-content-wrapper">
                                            <div class="widget-content-left pe-2 fsize-1">
                                                <div class="widget-numbers mt-0 fsize-3 text-info">89%</div>
                                            </div>
                                            <div class="widget-content-right w-100">
                                                <div class="progress-bar-xs progress">
                                                    <div class="progress-bar bg-info" role="progressbar" aria-valuenow="89"
                                                        aria-valuemin="0" aria-valuemax="100" style="width: 89%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-content-left fsize-1">
                                            <div class="text-muted opacity-6">Totals Target</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
            

                    <div class="main-card mb-3 card">
                        <div class="card-header">
                            <div class="card-header-title font-size-lg text-capitalize fw-normal">Last Transaction History
                            </div>
                            {{-- <div class="btn-actions-pane-right">
                                <button type="button" class="btn-icon btn-wide btn-outline-2x btn btn-outline-focus btn-sm d-flex">
                                    Actions Menu
                                    <span class="ps-2 align-middle opacity-7">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                </button>
                            </div> --}}
                        </div>
                        <div class="table-responsive">
                            <table class="align-middle text-truncate mb-0 table table-borderless table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Due Date</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">currency</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($lastTransactions as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->sender ?? 'N/A' }}</td>
                                        <td class="text-center">{{ ucfirst($item->type ?? 'N/A') }}</td>
                                        <td class="text-center">
                                            @if ($item->status == 'success')
                                                <div class="badge rounded-pill bg-success">Success</div>
                                            @elseif ($item->status == 'failed')
                                                <div class="badge rounded-pill bg-danger">Failed</div>
                                            @else
                                                <div class="badge rounded-pill bg-warning">{{ ucfirst($item->status) }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                                        <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                                        <td class="text-center">{{ ucfirst($item->currency ?? 'N/A') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <!-- Button to trigger modal -->
                                                <button type="button" class="btn btn-primary view-transaction-btn"
                                                    data-bs-toggle="modal" data-bs-target="#transactionModal"
                                                    data-sender="{{ $item->sender }}"
                                                    data-recipient="{{ $item->recipient }}"
                                                    data-amount="{{ number_format($item->amount, 2) }}"
                                                    data-currency="{{ $item->currency }}"
                                                    data-status="{{ $item->status }}"
                                                    data-method="{{ $item->method }}"
                                                    data-reference="{{ $item->reference }}"
                                                    data-type="{{ $item->transaction_type }}"
                                                    data-created="{{ $item->created_at->format('d M Y H:i') }}">
                                                    View
                                                </button>
                                                {{-- <button class="btn btn-danger">Delete</button> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach


                                </tbody>
                            

                            </table>
                        </div>

                        
                        <div class="d-block p-4 text-center card-footer">
                            <a href="{{ route('admin.transactionhistory') }}" class="btn-pill btn-shadow btn-wide fsize-1 btn btn-dark btn-lg">
                                <span class="me-2 opacity-7"><i class="fa fa-cog fa-spin"></i></span>
                                <span class="me-1">View Transaction History</span>
                            </a>
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

<!-- Single Modal outside the loop -->
<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Sender:</strong> <span id="modal-sender"></span></div>
                    <div class="col-md-6"><strong>Recipient:</strong> <span id="modal-recipient"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Amount:</strong> <span id="modal-amount"></span> <span id="modal-currency"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modal-status" class="badge"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Method:</strong> <span id="modal-method"></span></div>
                    <div class="col-md-6"><strong>Reference:</strong> <span id="modal-reference"></span></div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Transaction Type:</strong> <span id="modal-type"></span></div>
                    <div class="col-md-6"><strong>Created At:</strong> <span id="modal-created"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- JS to fill modal dynamically -->
<script>
    const modal = document.getElementById('transactionModal');
    const senderEl = modal.querySelector('#modal-sender');
    const recipientEl = modal.querySelector('#modal-recipient');
    const amountEl = modal.querySelector('#modal-amount');
    const currencyEl = modal.querySelector('#modal-currency');
    const statusEl = modal.querySelector('#modal-status');
    const methodEl = modal.querySelector('#modal-method');
    const referenceEl = modal.querySelector('#modal-reference');
    const typeEl = modal.querySelector('#modal-type');
    const createdEl = modal.querySelector('#modal-created');

    const viewButtons = document.querySelectorAll('.view-transaction-btn');

    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            senderEl.textContent = this.dataset.sender || 'N/A';
            recipientEl.textContent = this.dataset.recipient || 'N/A';
            amountEl.textContent = this.dataset.amount || '0.00';
            currencyEl.textContent = this.dataset.currency || '';
            methodEl.textContent = this.dataset.method || 'N/A';
            referenceEl.textContent = this.dataset.reference || 'N/A';
            typeEl.textContent = this.dataset.type || 'N/A';
            createdEl.textContent = this.dataset.created || 'N/A';

            // Set status badge color
            const status = this.dataset.status;
            statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            statusEl.className = 'badge';
            if(status === 'success') statusEl.classList.add('bg-success');
            else if(status === 'failed') statusEl.classList.add('bg-danger');
            else statusEl.classList.add('bg-warning');
        });
    });
</script>