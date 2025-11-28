<!doctype html>
<html lang="en">


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/pages-register-boxed.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Oct 2025 11:54:47 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register Boxed - ArchitectUI HTML Bootstrap 5 Dashboard Template</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="ArchitectUI HTML Bootstrap 5 Dashboard Template">

<script defer src="{{asset('admin/assets/scripts/vendors.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/main.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/demo.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/ladda.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/blockui.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/circle_progress.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/count_up.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset("admin/assets/scripts/toastr.46b0e993b2652496c5e9.js")}}"></script>
<script defer src="{{asset("admin/assets/scripts/sweet_alerts.46b0e993b2652496c5e9.js")}}"></script>
<script defer src="{{asset("admin/assets/scripts/scrollbar.46b0e993b2652496c5e9.js")}}"></script>
<script defer src="{{asset('admin/assets/scripts/sticky_elements.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/carousel_slider.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/fullcalendar.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/treeview.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/maps.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/rating.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/image_crop.46b0e993b2652496c5e9.j')}}s"></script>
<script defer src="{{asset('admin/assets/scripts/guided_tours.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/tables.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/form_validation.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/form_wizard.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/clipboard.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/datepicker.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/input_mask.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/input_select.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/range_slider.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset("admin/assets/scripts/textarea_autosize.46b0e993b2652496c5e9.js")}}"></script>
<script defer src="{{asset('assets/scripts/toggle_switch.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/chart_js.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/apex_charts.46b0e993b2652496c5e9.js')}}"></script>
<script defer src="{{asset('admin/assets/scripts/sparklines.46b0e993b2652496c5e9.js')}}"></script>
<link href="{{asset('admin/assets/styles/vendors.46b0e993b2652496c5e9.css')}}" rel="stylesheet">
<link href="{{asset('admin/assets/styles/main.46b0e993b2652496c5e9.css')}}" rel="stylesheet">
</head>

<body>
       <!-- Toastr CSS -->
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
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100 bg-premium-dark bg-animation">
                <div class="d-flex h-100 justify-content-center align-items-center py-5">
                    <div class="mx-auto col-sm-11 col-md-9 col-lg-8 col-xl-7">
                        <!-- Ultra Modern Register Card -->
                        <div class="card border-0" style="border-radius: 28px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                            <div class="card-body p-5">
                                <!-- Logo and Brand with Premium Styling -->
                                <div class="text-center mb-5">
                                    <div class="app-logo mx-auto mb-4"></div>
                                    <h1 class="fw-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -0.02em;">Create Your Account</h1>
                                    <p class="text-muted fs-5 mb-0">Admin Login</p>
                                </div>
        
                                <!-- Enhanced Registration Form -->
                                 <div id="toast-container"></div>
                                 <form action="{{ route('admin.login.submit') }}" method="POST">
                                    @csrf
                                    <div class="row g-4">
                                        <!-- Email Field -->
                                                <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input name="email" type="email" class="form-control form-control-lg" >
                                                    <label>Email Address *</label>
                                                </div>
                                            </div>
        

        
                                        <!-- Password Field -->
                                              <div class="col-md-6">
            <div class="form-floating">
                <input name="password" type="password" class="form-control form-control-lg" >
                <label>Password *</label>
            </div>
        </div>
        
                                    <!-- Enhanced Terms and Conditions -->
                                    <div class="mt-4 p-4 rounded-3" style="background: rgba(34, 197, 94, 0.05); border: 1px solid rgba(34, 197, 94, 0.1);">
                                        <div class="form-check">
                                            <input name="check" id="exampleCheck" type="checkbox" class="form-check-input" style="transform: scale(1.3);" required>
                                            <label for="exampleCheck" class="form-check-label text-dark fw-medium">
                                                I agree to the 
                                                <a href="javascript:void(0);" class="text-success text-decoration-none fw-semibold">Terms of Service</a> 
                                                and 
                                                <a href="javascript:void(0);" class="text-success text-decoration-none fw-semibold">Privacy Policy</a>
                                            </label>
                                        </div>
                                    </div>
        
                                    <!-- Premium Gradient Submit Button -->
                                    <button type="submit" class="btn btn-gradient-success btn-shadow btn-lg w-100 mt-4 mb-4 fw-semibold" 
                                            style="border-radius: 16px; height: 58px; font-size: 1.1rem;">
                                        <i class="fa fa-user-plus me-2"></i>Create My Account
                                    </button>
                                </form>
        
                                <!-- Enhanced Divider -->
                                {{-- <div class="position-relative mb-4">
                                    <hr class="text-muted">
                                    <span class="position-absolute top-50 start-50 translate-middle bg-white px-4 text-muted fw-medium">
                                        Or sign up with
                                    </span>
                                </div> --}}
        
                                <!-- Premium Social Signup -->
                                {{-- <div class="d-flex justify-content-center gap-3 mb-4">
                                    <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative overflow-hidden" 
                                            style="width: 65px; height: 65px; border-radius: 18px; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                        <i class="fab fa-google text-danger fs-4"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative overflow-hidden" 
                                            style="width: 65px; height: 65px; border-radius: 18px; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                        <i class="fab fa-github text-dark fs-4"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center position-relative overflow-hidden" 
                                            style="width: 65px; height: 65px; border-radius: 18px; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                        <i class="fab fa-microsoft text-primary fs-4"></i>
                                    </button>
                                </div> --}}
        
                                <!-- Sign in link with better styling -->
                                <div class="text-center">
                                    <p class="text-muted mb-0 fs-6">
                                        Already have an account? 
                                        <a href="javascript:void(0);" class="text-success text-decoration-none fw-semibold">
                                            Sign in here
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
        
                        <!-- Enhanced Copyright -->
                        <div class="text-center mt-4">
                            <p class="text-white mb-0" style="font-size: 0.9rem; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                                Copyright © 2025
                            </p>
                        </div>
                    </div>
                </div>
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
</body>


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/pages-register-boxed.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Oct 2025 11:54:47 GMT -->
</html>