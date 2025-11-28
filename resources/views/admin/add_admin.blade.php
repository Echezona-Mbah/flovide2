<!doctype html>
<html lang="en">


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/pages-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Oct 2025 11:54:47 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register - ArchitectUI HTML Bootstrap 5 Dashboard Template</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="ArchitectUI HTML Bootstrap 5 Dashboard Template">

<script defer src="assets/scripts/vendors.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/main.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/demo.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/ladda.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/blockui.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/circle_progress.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/count_up.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/toastr.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/sweet_alerts.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/scrollbar.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/sticky_elements.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/carousel_slider.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/fullcalendar.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/treeview.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/maps.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/rating.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/image_crop.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/guided_tours.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/tables.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/form_validation.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/form_wizard.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/clipboard.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/datepicker.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/input_mask.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/input_select.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/range_slider.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/textarea_autosize.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/toggle_switch.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/chart_js.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/apex_charts.46b0e993b2652496c5e9.js"></script><script defer src="assets/scripts/sparklines.46b0e993b2652496c5e9.js"></script><link href="assets/styles/vendors.46b0e993b2652496c5e9.css" rel="stylesheet"><link href="assets/styles/main.46b0e993b2652496c5e9.css" rel="stylesheet"></head>
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
    <div class="app-container app-theme-white body-tabs-shadow">
        <div class="app-container">
            <div class="h-100">
                <div class="h-100 g-0 row">
                    <div
                        class="h-100 d-md-flex d-sm-block bg-white justify-content-center align-items-center col-md-12 col-lg-7">
                        <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                            <div class="app-logo"></div>
                            <h4>
                             <div>Add New Admin</div>
                                <span>It only takes a <span class="text-success">few seconds</span> to set up an admin account</span>

                            </h4>
                            <div>
                                              <div id="toast-container"></div>
                                    <form action="{{ route('admin.add_admin.store') }}" method="POST">
                                        @csrf

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="position-relative mb-3">
                                                    <label class="form-label"><span class="text-danger">*</span> Name</label>
                                                    <input name="name" type="type" class="form-control" placeholder="Name here..." required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative mb-3">
                                                    <label class="form-label"><span class="text-danger">*</span> Email</label>
                                                    <input name="email" type="email" class="form-control" placeholder="Email here..." required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative mb-3">
                                                    <label class="form-label"><span class="text-danger">*</span> Admin Role</label>
                                                    <select name="role_id" class="form-control" >
                                                        <option value="">Select Role</option>
                                                        @foreach($roles as $id => $role)
                                                            <option value="{{ $role }}">{{ $role }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative mb-3">
                                                    <label class="form-label"><span class="text-danger">*</span> Password</label>
                                                    <input name="password" type="password" class="form-control" placeholder="Password here..." >
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative mb-3">
                                                    <label class="form-label"><span class="text-danger">*</span> Repeat Password</label>
                                                    <input name="passwordrep" type="password" class="form-control" placeholder="Repeat Password here..." >
                                                </div>
                                            </div>

                                        </div>

                                        <button class="btn-wide btn-pill btn-shadow btn-hover-shine btn btn-primary btn-lg">
                                            Create Account
                                        </button>
                                    </form>

                            </div>
                        </div>
                    </div>
                    <div class="d-lg-flex d-xs-none col-lg-5">
                        <div class="slider-light">
                            <div class="slick-slider slick-initialized">
                                <div>
                                    <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark"
                                        tabindex="-1">
                                        <div class="slide-img-bg"
                                            style="background-image: url('assets/images/originals/citynights.jpg');"></div>
                                            <div class="slider-content">
                                                <h3>Manage Your Team Efficiently</h3>
                                                <p>Create and assign admin roles to keep your platform organized, secure,
                                                and professionally managed.
                                                </p>
                                            </div>

                                    </div>
                                </div>
                            </div>
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


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/pages-register.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Oct 2025 11:54:47 GMT -->
</html>