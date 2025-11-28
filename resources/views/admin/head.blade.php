<!doctype html>
<html lang="en">


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/dashboards-minimal-1.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 14 Oct 2025 11:54:43 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Minimal Dashboard - Examples of just how powerful ArchitectUI really is!</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Examples of just how powerful ArchitectUI really is!">

    <!-- Page loader disabled - ensure content is visible immediately -->
    <style>
        body .app-container {
          opacity: 1 !important;
          visibility: visible !important;
        }
    </style>
    <script>
        // Mark as loaded immediately when loader is disabled
        document.addEventListener('DOMContentLoaded', function() {
          document.body.classList.add('app-loaded');
        });
    </script>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> --}}

</head>
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
