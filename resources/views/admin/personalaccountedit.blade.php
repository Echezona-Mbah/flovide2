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
                                        Personal Account Edit

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




<div class="container-fluid">

<div class="card shadow-sm">

<div class="card-header bg-white">
<h4 class="mb-0">Edit User</h4>
</div>

<div class="card-body">
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
<form action="{{ url('/admin/personal-account/update/'.$user->id) }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row">

<!-- Profile Image -->
<div class="col-md-3 text-center">

<img
src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
class="rounded-circle mb-3"
width="120"
height="120"
id="previewImage"
>

<input type="file" name="profile_picture" class="form-control">

</div>


<div class="col-md-9">

<div class="row">


<!-- First Name -->
<div class="col-md-6 mb-3">
<label class="form-label">First Name</label>
<input type="text" name="firstname" class="form-control"
value="{{ $user->firstname }}">
</div>

<!-- Last Name -->
<div class="col-md-6 mb-3">
<label class="form-label">Last Name</label>
<input type="text" name="lastname" class="form-control"
value="{{ $user->lastname }}">
</div>

<!-- Email -->
<div class="col-md-6 mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="{{ $user->email }}" readonly>
</div>

<!-- Phone -->
<div class="col-md-6 mb-3">
<label>Person Phone</label>
<input type="text" name="person_phone" class="form-control"
value="{{ $user->person_phone }}">
</div>


<!-- Street Address -->
<div class="col-md-6 mb-3">
<label>Street Address</label>
<input type="text" name="street_address" class="form-control"
value="{{ $user->street_address }}">
</div>


<!-- City -->
<div class="col-md-6 mb-3">
<label>City</label>
<input type="text" name="city" class="form-control"
value="{{ $user->city }}">
</div>

<!-- State -->
<div class="col-md-6 mb-3">
<label>State</label>
<input type="text" name="state" class="form-control"
value="{{ $user->state }}">
</div>

<!-- Country -->
<div class="col-md-6 mb-3">
<label>Country</label>
<input type="text" name="country" class="form-control"
value="{{ $user->country }}">
</div>

<!-- Currency -->
<div class="col-md-6 mb-3">
<label>Currency</label>
<input type="text" name="currency" class="form-control"
value="{{ $user->currency }}">
</div>



<!-- Status -->
<div class="col-md-6 mb-3">
<label>Status</label>

<select name="deletestatus" class="form-control">

<option value="active"
{{ $user->deletestatus == 'active' ? 'selected':'' }}>
Active
</option>

<option value="deactivated"
{{ $user->deletestatus == 'deactivated' ? 'selected':'' }}>
Inactive
</option>

</select>

</div>


<!-- Currency -->
{{-- <div class="col-md-6 mb-3">
<label>Referral code</label>
<input type="text" name="referral_code" class="form-control"
value="{{ $user->referral_code }}">
</div>

<!-- Currency -->
<div class="col-md-6 mb-3">
<label>Referral link</label>
<input type="text" name="referral_link" class="form-control"
value="{{ $user->referral_link }}">
</div> --}}


</div>

</div>

</div>

<hr>

<div class="text-end">

<button class="btn btn-primary">
Update User
</button>

<a href="{{ url()->previous() }}" class="btn btn-secondary">
Cancel
</a>

</div>

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

