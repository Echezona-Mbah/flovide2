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

                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-graph icon-gradient bg-ripe-malin"></i>
                                </div>
                                  <div>
                                        All Admin  
                                        <div class="page-title-subheading">
                                            View and manage All Admin
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 

                    <div id="toast-container"></div>



 <div class="row">
@foreach($admins as $admin)
    <div class="col-sm-12 col-lg-12 col-xl-4">
        <div class="mb-3 profile-responsive card">
            <div class="dropdown-menu-header">
                <div class="dropdown-menu-header-inner bg-dark">
                    <div class="menu-header-image opacity-1"
                        style="background-image: url('assets/images/dropdown-header/abstract3.jpg');"></div>
                    <div class="menu-header-content btn-pane-right">

                        <div class="avatar-icon-wrapper me-3 avatar-icon-xl btn-hover-shine">
                            <div class="avatar-icon rounded">
                                <img 
                                    src="{{ $admin->profile_picture ? asset('storage/'.$admin->profile_picture) : asset('assets/images/avatars/default.png') }}" 
                                    alt="Profile">
                            </div>
                        </div>

                        <div>
                            <h5 class="menu-header-title">{{ $admin->name }}</h5>
                            <h6 class="menu-header-subtitle">{{ $admin->role ?? 'Admin' }}</h6>
                        </div>

                        <div class="menu-header-btn-pane">
                            <a href="{{ route('admin.view', $admin->id) }}" class="btn btn-success">View Profile</a>
                        </div>

                    </div>
                </div>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="widget-content pt-4 pb-4 pe-1 ps-1">
                        <div class="text-center">
                            <h5 class="mb-0">
                                <span class="pe-1">
                                    <b class="text-danger">{{ rand(5, 20) }}</b> tasks,
                                </span>
                                <span><b class="text-success">Active</b></span>
                            </h5>
                        </div>
                    </div>
                </li>

                <li class="p-0 list-group-item">
                    <div class="grid-menu grid-menu-2col">
                        <div class="g-0 row">
                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-dark">
                                        <i class="lnr-lighter text-dark opacity-7 btn-icon-wrapper mb-2"></i> Activity
                                    </button>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                        <i class="lnr-construction text-danger opacity-7 btn-icon-wrapper mb-2"></i> Reports
                                    </button>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-success">
                                        <i class="lnr-bus text-success opacity-7 btn-icon-wrapper mb-2"></i> Settings
                                    </button>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="p-1">
                                    <button class="btn-icon-vertical btn-transition-text btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-focus">
                                        <i class="lnr-gift text-focus opacity-7 btn-icon-wrapper mb-2"></i> Edit
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </div>
@endforeach
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
@include('admin.footer')


