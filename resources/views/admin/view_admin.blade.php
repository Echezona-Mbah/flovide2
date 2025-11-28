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
                                      {{$admin->name}}  Profile  
                                        <div class="page-title-subheading">
                                            View and manage {{$admin->name}} Profile.  
                                        </div>
                                    </div>

                            </div>
                            
                        </div>
                    </div> 

                                              <div id="toast-container"></div>


                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ $admin->name }}" class="form-control" placeholder="Enter full name" readonly>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ $admin->email }}" class="form-control" placeholder="Enter email" readonly>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control" placeholder="Enter phone number" readonly>
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Admin Role</label>
                                <input type="text" name="role_id" value="{{ $admin->role }}" class="form-control" placeholder="Enter role" readonly>

                            </div>

                            <!-- Skills -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Skills</label>
                                <textarea name="skills" readonly class="form-control" rows="2" placeholder="e.g., Laravel, UI/UX, Marketing">{{ $admin->skills }}</textarea>
                            </div>

                            <!-- Experience -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Experience</label>
                                <textarea name="experience" readonly class="form-control" rows="3" placeholder="Describe work experience...">{{ $admin->experience }}</textarea>
                            </div>

                            <!-- Profile Picture -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control" readonly>

                                @if($admin->profile_picture)
                                    <img src="{{ asset('storage/'.$admin->profile_picture) }}" 
                                        style="width: 80px; height: 80px; margin-top: 10px; border-radius: 8px;" readonly>
                                @endif
                            </div>

                            <!-- Languages Spoken -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Languages Spoken</label>
                                <input type="text" name="languages_spoken" value="{{ $admin->languages_spoken }}" class="form-control" placeholder="e.g., English, Hausa" readonly>
                            </div>

                            <!-- Portfolio Links -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Portfolio Links</label>
                                <input type="text" readonly name="portfolio_links" value="{{ $admin->portfolio_links }}" class="form-control" placeholder="https://portfolio.com/username" readonly>
                            </div>

                            <!-- Social Media Accounts -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Social Media Accounts Managed</label>
                                <textarea  readonly name="social_media_accounts" class="form-control" rows="2" placeholder="e.g., Instagram: @brand, Twitter: @brand">{{ $admin->social_media_accounts }} </textarea>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Emergency Contact</label>
                                <input type="text" name="emergency_contact" value="{{ $admin->emergency_contact }}" class="form-control" placeholder="Enter emergency contact">
                            </div>

                            <!-- LinkedIn Profile -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">LinkedIn Profile</label>
                                <input type="text" name="linkedin_profile" value="{{ $admin->linkedin_profile }}" class="form-control" placeholder="https://linkedin.com/in/profile">
                            </div>

                            <!-- Update Button -->
                            {{-- <div class="col-12 mt-3">
                                <button class="btn btn-primary btn-lg w-100">Update Profile</button>
                            </div> --}}

                        </div>
                    </form>

        
                    
                    
   
        
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


