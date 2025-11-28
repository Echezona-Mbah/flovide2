@include('admin.head')

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
     @include('admin.header')

        @include('admin.ui-setting')
        
        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')
            
            
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-inner-layout">
                        <div class="app-inner-layout__header-boxed p-0">
                            <div class="app-inner-layout__header page-title-icon-rounded text-white bg-premium-dark mb-4">
                                <div class="app-page-title">
                                    <div class="page-title-wrapper">
                                        <div class="page-title-heading">
                                            <div class="page-title-icon"><i class="pe-7s-umbrella icon-gradient bg-sunny-morning"></i></div>
                                            <div>
                                                <h4 class="mb-1 fw-bold">Personal Account Dashboard</h4>
                                                <div class="page-title-subheading">
                                                    A complete overview of this merchant’s activities, balances, virtual cards operations.
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
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <div class="container">
                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-rounded-lg">
                                <li class="nav-item">
                                    <a role="tab" class="nav-link active show" data-tab="sales" href="javascript:void(0);">
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-tab="activity" href="javascript:void(0);">
                                        <span>Balance</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-tab="profile" href="javascript:void(0);">
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a role="tab" class="nav-link" data-tab="accounts" href="javascript:void(0);">
                                        <span>Accounts</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- {{dd($user->profile_picture)}} --}}


  <!-- PROFILE CONTENT -->
<div class="tab-content-section" id="tab-sales">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Business Profile Details</h5>

            <div id="profileDetails" class="d-none p-3 border rounded bg-light">

                <div class="row">

                    <!-- Profile -->
                    <div class="col-md-3 text-center mb-3">
                        <img src="{{ asset($user->profile_picture)  ?? asset('admin/assets/images/avatars/user33.png') }}"
                            class="rounded-circle img-fluid" width="100" />
                        <h6 class="mt-2 mb-0">{{ $user->firstname }} {{ $user->lastname }}</h6>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>

                    <!-- Info -->
                    <div class="col-md-9">
                        <table class="table table-sm">
                            {{-- <tr><th>Business Name</th><td>{{ $user->business_name ?? 'N/A' }}</td></tr>
                            <tr><th>Industry</th><td>{{ $user->industry ?? 'N/A' }}</td></tr>
                            <tr><th>Type</th><td>{{ $user->business_type ?? 'N/A' }}</td></tr> --}}
                            <tr><th>Country</th><td>{{ $user->country ?? 'N/A' }}</td></tr>
                            <tr><th>Address</th><td>{{ $user->street_address ?? 'N/A' }}</td></tr>
                            <tr><th>Personal Number</th><td>{{ $user->person_phone ?? 'N/A' }}</td></tr>

                            <tr><th>City</th><td>{{ $user->city ?? 'N/A' }}</td></tr>
                            <tr><th>State</th><td>{{ $user->state ?? 'N/A' }}</td></tr>
                        </table>
                    </div>

                </div>

            </div>
        </div>

        <div class="card-footer text-center">
            <button class="btn btn-dark btn-sm" id="toggleProfileBtn">
                Show / Hide Profile Details
            </button>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="g-0 row">
            <div class="col-md-6 col-xl-4">
                <div class="widget-content">
                    <div class="widget-content-wrapper">
                            <img src=" {{asset('admin/assets/images/avatars/images55.png') }}"
                            class="rounded-circle img-fluid" width="30" style="margin: 20px" />
                        <div class="widget-content-left">
                            <div class="widget-heading">Account Status</div>
                            <div class="widget-subheading text-capitalize">
                                {{-- {{ $user->email_verified_status }} --}}
                            </div>
                            <div class="widget-content-right ms-0 me-3">
                            @if($user->email_verified_status == 'yes')
                                <span style="width: 12px; height: 12px; background: #28a745; border-radius: 50%; display: inline-block;"></span>
                            @else
                                <span style="width: 12px; height: 12px; background: #dc3545; border-radius: 50%; display: inline-block;"></span>
                            @endif
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="g-0 row">
            <div class="col-md-6 col-xl-4">
                <div class="widget-content">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-right ms-0 me-3">
                            <div class="widget-numbers text-success">1896</div>
                        </div>
                        <div class="widget-content-left">
                            <div class="widget-heading">Total Orders</div>
                            <div class="widget-subheading">Last year expenses</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Your other Sales widgets -->
        </div>
    </div>
</div>

<!-- BALANCE -->
<div class="tab-content-section d-none" id="tab-activity">
    {{-- balance --}}
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="pb-2 card-title">Balances</h5>
                <div class="row">
                @foreach($balances as $bal)

                    @php
                        $flagColors = [
                            'NGN' => 'background: linear-gradient(to right, #008751, #ffffff, #008751);',
                            'USD' => 'background-color:#3C3B6E;',
                            'KES' => 'background: linear-gradient(to right, black, red, #006600);',
                            'GHS' => 'background: linear-gradient(to right, red, gold, green);',
                            'ZAR' => 'background-color: green;',
                            'GBP' => 'background-color: #00247D;',
                            'EUR' => 'background-color: #003399;',
                            'CAD' => 'background: linear-gradient(to right, white, red, white);',
                            'AUD' => 'background-color: #00008B;',
                            'JPY' => 'background: linear-gradient(to right, white, #BC002D, white);',
                            'CNY' => 'background-color: #DE2910;',
                            'INR' => 'background: linear-gradient(to right, #FF9933, white, #138808);',
                            'BRL' => 'background: linear-gradient(to right, green, yellow);',
                            'MXN' => 'background: linear-gradient(to right, green, white, red);',
                            'AED' => 'background: linear-gradient(to right, #000000, #FF0000, #00732F);',
                            'SAR' => 'background-color: #006C35;',
                            'TRY' => 'background-color: #E30A17;',
                            'RUB' => 'background: linear-gradient(to right, white, blue, red);',
                            'CHF' => 'background-color: #FF0000;',
                            'SEK' => 'background: linear-gradient(to right, #006AA7, #FECC00);',
                            'NOK' => 'background: linear-gradient(to right, red, white, blue);',
                            'DKK' => 'background: linear-gradient(to right, red, white);',
                            'PLN' => 'background: linear-gradient(to bottom, white, red);',
                            'THB' => 'background: linear-gradient(to right, red, white, blue);',
                            'MYR' => 'background: linear-gradient(to right, blue, yellow);',
                            'IDR' => 'background: linear-gradient(to bottom, red, white);',
                            'PHP' => 'background: linear-gradient(to right, blue, red);',
                            'PKR' => 'background: linear-gradient(to right, green, white);',
                            'BDT' => 'background: linear-gradient(to right, green, red);',
                            'EGP' => 'background: linear-gradient(to right, black, red, gold);',
                            'TWD' => 'background: linear-gradient(to right, blue, red);',
                            'HKD' => 'background-color: red;',
                            'SGD' => 'background: linear-gradient(to bottom, red, white);',
                            'NZD' => 'background-color: #00247D;',
                        ];

                        $bg = $flagColors[$bal->currency] ?? 'background-color:#444;';
                    @endphp

                    <div class="col-md-3 mb-3">
                        <div class="dropdown-menu-header">
                            <div class="dropdown-menu-header-inner" style="{{ $bg }} border-radius:10px; padding:20px;">
                                <div class="menu-header-content text-white">
                                    <h5 class="menu-header-title">{{ $bal->name }}</h5>
                                    <h6 class="menu-header-subtitle">
                                        {{ $bal->currency }} — {{ number_format($bal->amount, 2) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach

                </div>
            </div>
        </div>
    </div>
    {{-- virtual --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">User Virtual Cards</h5>
        </div>

        <div class="card-body">
            @if($virtualCards->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Card ID</th>
                            <th>Type</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Card Number</th>
                            <th>CVV</th>
                            <th>Expiry</th>
                            <th>Balance</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($virtualCards as $index => $card)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $card->card_id }}</td>
                            <td>{{ $card->card_type }}</td>
                            <td>{{ $card->currency }}</td>
                            <td>{{ number_format($card->amount, 2) }}</td>
                            <td>{{ $card->card_number }}</td>
                            <td>{{ $card->cvv }}</td>
                            <td>{{ $card->expiry_month }}/{{ $card->expiry_year }}</td>
                            <td>{{ number_format($card->balance, 2) }}</td>
                            <td>
                                @if($card->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($card->status == 'inactive')
                                    <span class="badge bg-secondary">Inactive</span>
                                @elseif($card->status == 'blocked')
                                    <span class="badge bg-danger">Blocked</span>
                                @else
                                    <span class="badge bg-warning">Unknown</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @else
                <div class="alert alert-warning text-center">No virtual cards found for this user.</div>
            @endif
        </div>
    </div>


</div>



<div class="tab-content-section d-none" id="tab-profile">
    <h4>Profile Information</h4>
    <p>All profile details go here...</p>
</div>

<!-- ACCOUNTS CONTENT -->
<div class="tab-content-section d-none" id="tab-accounts">

    <!-- Loop all account collections -->
    @php
        $accountCollections = [
            'Beneficia' => $beneficia,
            'Customers' => $customer,
            'Bank Accounts' => $bankAccount,
            'Subaccounts' => $Subaccount
        ];
    @endphp

    @foreach ($accountCollections as $title => $collection)
        <div class="card mb-4">
            <div class="card-header">
                <h5>{{ $title }}</h5>
            </div>

            <div class="card-body table-responsive">
                @if ($collection->count() > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                 <th class="text-center">Account Name</th>
                                <th class="text-center">Bank</th>
                                <th class="text-center">Account Number</th>
                                <th class="text-center">Country</th>
                                <th class="text-center">Currency</th>
                                <th class="text-center">Type</th>
                                <th>Created</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($collection as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $item->account_name }}</td>
                                    <td class="text-center">{{ $item->bank }}</td>
                                    <td class="text-center">{{ $item->account_number }}</td>
                                    <td class="text-center">{{ $item->country }}</td>
                                    <td class="text-center">{{ $item->currency }}</td>
                                    <td class="text-center">{{ $item->type }}</td>
                                    <td>{{ $item->created_at ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <p>No {{ $title }} found.</p>
                @endif
            </div>
        </div>
    @endforeach
</div>










                    </div>
                </div>
                        


           
            </div>

        </div>
    </div>

@include('admin.footer')

<script>
    const tabs = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('.tab-content-section');

    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove active tab
            tabs.forEach(t => t.classList.remove('active', 'show'));

            // Add active state
            this.classList.add('active', 'show');

            // Hide all sections
            sections.forEach(sec => sec.classList.add('d-none'));

            // Show selected section
            const target = this.getAttribute('data-tab');
            document.getElementById('tab-' + target).classList.remove('d-none');
        });
    });
</script>
<script>
    document.getElementById("toggleProfileBtn").onclick = function () {
        document.getElementById("profileDetails").classList.toggle("d-none");
    };
</script>


