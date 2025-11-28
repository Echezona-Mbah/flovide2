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
                                                <h4 class="mb-1 fw-bold">Business Account Dashboard</h4>
                                                <div class="page-title-subheading">
                                                    A complete overview of this merchant’s activities, balances, virtual cards, and team operations.
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
                            <tr><th>Business Name</th><td>{{ $user->business_name ?? 'N/A' }}</td></tr>
                            <tr><th>Industry</th><td>{{ $user->industry ?? 'N/A' }}</td></tr>
                            <tr><th>Type</th><td>{{ $user->business_type ?? 'N/A' }}</td></tr>
                            <tr><th>Reg. Number</th><td>{{ $user->registration_number ?? 'N/A' }}</td></tr>
                            <tr><th>Businesss. Number</th><td>{{ $user->business_phone ?? 'N/A' }}</td></tr>
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

    {{-- compliance --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-card mb-3 card">
                                    <div class="card-header">Compliance
                                    </div>
                                    <div class="table-responsive">
                                @php
                                    $documents = [
                                    [
                                        'label' => 'CAC Certificate',
                                        'file' => $user->cac_certificate,
                                        'status' => $user->cac_status,
                                        'field' => 'cac_status',
                                    ],
                                    [
                                        'label' => 'Valid ID',
                                        'file' => $user->valid_id,
                                        'status' => $user->valid_id_status,
                                        'field' => 'valid_id_status',
                                    ],
                                    [
                                        'label' => 'TIN Document',
                                        'file' => $user->tin,
                                        'status' => $user->tin_status,
                                        'field' => 'tin_status',
                                    ],
                                    [
                                        'label' => 'Utility Bill',
                                        'file' => $user->utility_bill,
                                        'status' => $user->utility_bill_status,
                                        'field' => 'utility_bill_status',
                                    ],
                                ];

                                @endphp


                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Upload</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($documents as $doc)
                                            <tr>
                                                <td>
                                                    <div class="widget-content p-0">
                                                        <div class="widget-content-wrapper">
                                                            <div class="widget-content-left flex2">
                                                                <div class="widget-subheading opacity-7">
                                                                    {{ $doc['label'] }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- File Thumbnail -->

                                                <td class="text-center">
                                                    @if($doc['file'])
                                                        <a href="{{ asset('storage/' . $doc['file']) }}" download>
                                                            <img
                                                                width="60"
                                                                style="cursor:pointer;"
                                                                src="{{ asset('storage/' . $doc['file']) }}"
                                                            >
                                                        </a>
                                                    @else
                                                        <span class="badge bg-danger">No File</span>
                                                    @endif
                                                </td>


                                                <!-- STATUS -->
                                                <td class="text-center">
                                                    <div class="status-badge badge 
                                                        @if($doc['status'] == 'confirmed') bg-success
                                                        @elseif($doc['status'] == 'under_review') bg-warning
                                                        @elseif($doc['status'] == 'rejected') bg-danger
                                                        @else bg-secondary
                                                        @endif
                                                    ">
                                                        {{ ucfirst(str_replace('_',' ', $doc['status'] ?? 'Not Submitted')) }}
                                                    </div>
                                                </td>


                                                <!-- ACTIONS -->
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
                                                                data-status="under_review"
                                                                data-field="{{ $doc['field'] }}"
                                                                data-id="{{ $user->id }}">
                                                                Under Review
                                                                </a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a href="#"
                                                                class="nav-link change-status"
                                                                data-status="confirmed"
                                                                data-field="{{ $doc['field'] }}"
                                                                data-id="{{ $user->id }}">
                                                                Confirmed
                                                                </a>
                                                            </li>

                                                            <li class="nav-item">
                                                                <a href="#"
                                                                class="nav-link change-status text-danger"
                                                                data-status="rejected"
                                                                data-field="{{ $doc['field'] }}"
                                                                data-id="{{ $user->id }}">
                                                                Rejected
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

    <div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Team Members</h5>
    </div>

    <div class="card-body">

        @if($teamMembers->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th>Invite Token</th>
                        <th>Token Expires</th>
                        <th>Used At</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($teamMembers as $index => $member)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $member->email }}</td>
                        <td>{{ $member->role }}</td>

                        <td>
                            @if($member->permissions)
                                <span class="badge bg-info text-dark">
                                    {{ implode(', ', json_decode($member->permissions, true)) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">None</span>
                            @endif
                        </td>

                        <td>
                            @if($member->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($member->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($member->status === 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-danger">Unknown</span>
                            @endif
                        </td>

                        <td>{{ $member->invite_token ?? '—' }}</td>
                        <td>{{ $member->invite_token_expires_at ?? '—' }}</td>
                        <td>{{ $member->invite_token_used_at ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        @else
            <div class="alert alert-warning text-center">No team members found.</div>
        @endif
    </div>
</div>


</div>



<div class="tab-content-section d-none" id="tab-profile">
    <h4>Profile Information</h4>
    <p>All profile details go here...</p>
</div>

<!-- ACCOUNTS CONTENT -->
<!-- ACCOUNTS CONTENT -->
<div class="tab-content-section d-none" id="tab-accounts">
    <h4>Account Settings</h4>
    <p>Bank accounts, wallet, transactions...</p>

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
                        


                {{-- <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <div class="footer-dots">
                                    <div class="dropdown">
                                        <a aria-haspopup="true" aria-expanded="false" data-bs-toggle="dropdown" class="dot-btn-wrapper">
                                            <i class="dot-btn-icon lnr-bullhorn icon-gradient bg-mean-fruit"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm bg-danger">Notifications</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                            class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header mb-0">
                                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                                    <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                                    <div class="menu-header-content text-dark">
                                                        <h5 class="menu-header-title">Notifications</h5>
                                                        <h6 class="menu-header-subtitle">You have <b>21</b> unread messages</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link active" data-bs-toggle="tab" href="#tab-messages-header1">
                                                        <span>Messages</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link" data-bs-toggle="tab" href="#tab-events-header1">
                                                        <span>Events</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a role="tab" class="nav-link" data-bs-toggle="tab" href="#tab-errors-header1">
                                                        <span>System Errors</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab-messages-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="p-3">
                                                                <div class="notifications-box">
                                                                    <div class="vertical-time-simple vertical-without-time vertical-timeline vertical-timeline--one-column">
                                                                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">All Hands Meeting</h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <p>Yet another one, at 
                                                                                        <span class="text-success">15:00 PM</span>
                                                                                    </p>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Build the production release
                                                                                        <span class="badge bg-danger ms-2">NEW</span>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-primary vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Something not important
                                                                                        <div class="avatar-wrapper mt-2 avatar-wrapper-overlap">
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/1.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/2.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/3.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/4.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/5.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/9.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/7.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="avatar-icon-wrapper avatar-icon-sm">
                                                                                                <div class="avatar-icon">
                                                                                                    <img src="assets/images/avatars/8.jpg" alt="">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="avatar-icon-wrapper avatar-icon-sm avatar-icon-add">
                                                                                                <div class="avatar-icon"><i>+</i></div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-info vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">This dot has an info state</h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-danger vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">All Hands Meeting</h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-warning vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <p>Yet another one, at 
                                                                                        <span class="text-success">15:00 PM</span>
                                                                                    </p>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-success vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">Build the production release
                                                                                        <span class="badge bg-danger ms-2">NEW</span>
                                                                                    </h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item dot-dark vertical-timeline-element">
                                                                            <div>
                                                                                <span class="vertical-timeline-element-icon bounce-in"></span>
                                                                                <div class="vertical-timeline-element-content bounce-in">
                                                                                    <h4 class="timeline-title">This dot has a dark state</h4>
                                                                                    <span class="vertical-timeline-element-date"></span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-events-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="p-3">
                                                                <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-success"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">All Hands Meeting</h4>
                                                                                <p>Lorem ipsum dolor sic amet, today at 
                                                                                    <a href="javascript:void(0);">12:00 PM</a>
                                                                                </p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-warning"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <p>Another meeting today, at 
                                                                                    <b class="text-danger">12:00 PM</b>
                                                                                </p>
                                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-danger"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">Build the production release</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor
                                                                                    incididunt ut labore et dolore magna elit enim at
                                                                                    minim veniam quis nostrud
                                                                                </p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-primary"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title text-success">Something not important</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur elit enim at
                                                                                    minim veniam quis nostrud</p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-success"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">All Hands Meeting</h4>
                                                                                <p>Lorem ipsum dolor sic amet, today at 
                                                                                    <a href="javascript:void(0);">12:00 PM</a>
                                                                                </p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-warning"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <p>Another meeting today, at 
                                                                                    <b class="text-danger">12:00 PM</b>
                                                                                </p>
                                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-danger"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title">Build the production release</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor
                                                                                    incididunt ut labore et dolore magna elit enim at
                                                                                    minim veniam quis nostrud
                                                                                </p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div>
                                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                                <i class="badge badge-dot badge-dot-xl bg-primary"></i>
                                                                            </span>
                                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                                <h4 class="timeline-title text-success">Something not important</h4>
                                                                                <p>Lorem ipsum dolor sit amit,consectetur elit enim at
                                                                                    minim veniam quis nostrud
                                                                                </p>
                                                                                <span class="vertical-timeline-element-date"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab-errors-header1" role="tabpanel">
                                                    <div class="scroll-area-sm">
                                                        <div class="scrollbar-container">
                                                            <div class="no-results pt-3 pb-0">
                                                                <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                                    <span class="swal2-success-line-tip"></span>
                                                                    <span class="swal2-success-line-long"></span>
                                                                    <div class="swal2-success-ring"></div>
                                                                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                                </div>
                                                                <div class="results-subtitle">All caught up!</div>
                                                                <div class="results-title">There are no system errors!</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="nav flex-column">
                                                <li class="nav-item-divider nav-item"></li>
                                                <li class="nav-item-btn text-center nav-item">
                                                    <button class="btn-shadow btn-wide btn-pill btn btn-focus btn-sm">View Latest Changes</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="dots-separator"></div>
                                    <div class="dropdown">
                                        <a class="dot-btn-wrapper" aria-haspopup="true" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="dot-btn-icon lnr-earth icon-gradient bg-happy-itmeo"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner pt-4 pb-4 bg-focus">
                                                    <div class="menu-header-image opacity-05" style="background-image: url('assets/images/dropdown-header/city2.jpg');"></div>
                                                    <div class="menu-header-content text-center text-white">
                                                        <h6 class="menu-header-subtitle mt-0"> Choose Language</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <h6 tabindex="-1" class="dropdown-header"> Popular Languages</h6>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="me-3 opacity-8 flag large US"></span> USA
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="me-3 opacity-8 flag large CH"></span> Switzerland
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="me-3 opacity-8 flag large FR"></span>France
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="me-3 opacity-8 flag large ES"></span>Spain
                                            </button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <h6 tabindex="-1" class="dropdown-header">Others</h6>
                                            <button type="button" tabindex="0" class="dropdown-item active">
                                                <span class="me-3 opacity-8 flag large DE"></span>Germany
                                            </button>
                                            <button type="button" tabindex="0" class="dropdown-item">
                                                <span class="me-3 opacity-8 flag large IT"></span> Italy
                                            </button>
                                        </div>
                                    </div>
                                    <div class="dots-separator"></div>
                                    <div class="dropdown">
                                        <a class="dot-btn-wrapper dd-chart-btn-2" aria-haspopup="true" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="dot-btn-icon lnr-pie-chart icon-gradient bg-love-kiss"></i>
                                            <div class="badge badge-dot badge-abs badge-dot-sm bg-warning">Notifications</div>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu">
                                            <div class="dropdown-menu-header">
                                                <div class="dropdown-menu-header-inner bg-premium-dark">
                                                    <div class="menu-header-image" style="background-image: url('assets/images/dropdown-header/abstract4.jpg');"></div>
                                                    <div class="menu-header-content text-white">
                                                        <h5 class="menu-header-title">Users Online</h5>
                                                        <h6 class="menu-header-subtitle">Recent Account Activity Overview</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-chart">
                                                <div class="widget-chart-content">
                                                    <div class="icon-wrapper rounded-circle">
                                                        <div class="icon-wrapper-bg opacity-9 bg-focus"></div>
                                                        <i class="lnr-users text-white"></i>
                                                    </div>
                                                    <div class="widget-numbers">
                                                        <span>344k</span>
                                                    </div>
                                                    <div class="widget-subheading pt-2"> Profile views since last login</div>
                                                    <div class="widget-description text-danger">
                                                        <span class="pe-1"> <span>176%</span></span>
                                                        <i class="fa fa-arrow-left"></i>
                                                    </div>
                                                </div>
                                                <div class="widget-chart-wrapper">
                                                    <div id="dashboard-sparkline-carousel-4-pop"></div>
                                                </div>
                                            </div>
                                            <ul class="nav flex-column">
                                                <li class="nav-item-divider mt-0 nav-item"></li>
                                                <li class="nav-item-btn text-center nav-item">
                                                    <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                                        <i class="fa fa-cog fa-spin me-2"></i> View Details
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="app-footer-right">
                                <ul class="header-megamenu nav">
                                    <li class="nav-item">
                                        <a data-bs-placement="top" rel="popover-focus" data-offset="300" data-bs-toggle="popover-custom" class="nav-link">
                                            Footer Menu
                                            <i class="fa fa-angle-up ms-2 opacity-8"></i>
                                        </a>
                                        <div class="rm-max-width rm-pointers">
                                            <div class="d-none popover-custom-content">
                                                <div class="dropdown-mega-menu dropdown-mega-menu-sm">
                                                    <div class="grid-menu grid-menu-2col">
                                                        <div class="g-0 row">
                                                            <div class="col-sm-6 col-xl-6">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item-header nav-item">Overview</li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link">
                                                                            <i class="nav-link-icon lnr-inbox"></i>
                                                                            <span>Contacts</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link">
                                                                            <i class="nav-link-icon lnr-book"></i>
                                                                            <span>Incidents</span>
                                                                            <div class="ms-auto badge rounded-pill bg-danger">5</div>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link">
                                                                            <i class="nav-link-icon lnr-picture"></i>
                                                                            <span>Companies</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a disabled="" class="nav-link disabled">
                                                                            <i class="nav-link-icon lnr-file-empty"></i>
                                                                            <span>Dashboards</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-sm-6 col-xl-6">
                                                                <ul class="nav flex-column">
                                                                    <li class="nav-item-header nav-item">Sales &amp; Marketing</li>
                                                                    <li class="nav-item"><a class="nav-link">Queues</a></li>
                                                                    <li class="nav-item"><a class="nav-link">Resource Groups</a></li>
                                                                    <li class="nav-item">
                                                                        <a class="nav-link">Goal Metrics
                                                                            <div class="ms-auto badge bg-warning">3</div>
                                                                        </a>
                                                                    </li>
                                                                    <li class="nav-item"><a class="nav-link">Campaigns</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nav-item">
                                        <a data-bs-placement="top" rel="popover-focus" data-offset="300" data-bs-toggle="popover-custom" class="nav-link">
                                            Grid Menu
                                            <div class="badge bg-dark ms-0 ms-1">
                                                <small>NEW</small>
                                            </div>
                                            <i class="fa fa-angle-up ms-2 opacity-8"></i>
                                        </a>
                                        <div class="rm-max-width rm-pointers">
                                            <div class="d-none popover-custom-content">
                                                <div class="dropdown-menu-header">
                                                    <div class="dropdown-menu-header-inner bg-tempting-azure">
                                                        <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city5.jpg');"></div>
                                                        <div class="menu-header-content text-dark">
                                                            <h5 class="menu-header-title">Two Column Grid</h5>
                                                            <h6 class="menu-header-subtitle">Easy grid navigation inside popovers</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid-menu grid-menu-2col">
                                                    <div class="g-0 row">
                                                        <div class="col-sm-6">
                                                            <a href="#" class="btn btn-primary">Automation</a>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <a href="#" class="btn btn-danger">Reports</a>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <a href="#" class="btn btn-success">Activity</a>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <a href="#" class="btn btn-info">Settings</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-divider nav-item"></li>
                                                    <li class="nav-item-btn clearfix nav-item">
                                                        <div class="float-start">
                                                            <button class="btn btn-link btn-sm">Link Button</button>
                                                        </div>
                                                        <div class="float-end">
                                                            <button class="btn-shadow btn btn-info btn-sm">Info Button</button>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> --}}
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
{{-- to change compliance --}}
<script>
    document.querySelectorAll('.change-status').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            let status = this.dataset.status;
            let field = this.dataset.field;
            let userId = this.dataset.id;

            fetch("{{ url('/admin/business-account-status') }}/" + userId, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    field: field,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update badge immediately on the page
                    let badge = this.closest('tr').querySelector('.status-badge');
                    badge.innerHTML = data.label;
                    badge.className = "badge " + data.class;
                }
            });
        });
    });
</script>

