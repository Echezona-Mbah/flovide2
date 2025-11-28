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
                                        Profile  
                                        <div class="page-title-subheading">
                                            View and manage Your Profile.  
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
                                <input type="text" name="name" value="{{ $admin->name }}" class="form-control" placeholder="Enter full name">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ $admin->email }}" class="form-control" placeholder="Enter email" readonly>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control" placeholder="Enter phone number">
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Admin Role</label>
                                <input type="text" name="role_id" value="{{ $admin->role }}" class="form-control" placeholder="Enter role" readonly>

                            </div>

                            <!-- Skills -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Skills</label>
                                <textarea name="skills" class="form-control" rows="2" placeholder="e.g., Laravel, UI/UX, Marketing">{{ $admin->skills }}</textarea>
                            </div>

                            <!-- Experience -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Experience</label>
                                <textarea name="experience" class="form-control" rows="3" placeholder="Describe work experience...">{{ $admin->experience }}</textarea>
                            </div>

                            <!-- Profile Picture -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control">

                                @if($admin->profile_picture)
                                    <img src="{{ asset('storage/'.$admin->profile_picture) }}" 
                                        style="width: 80px; height: 80px; margin-top: 10px; border-radius: 8px;">
                                @endif
                            </div>

                            <!-- Languages Spoken -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Languages Spoken</label>
                                <input type="text" name="languages_spoken" value="{{ $admin->languages_spoken }}" class="form-control" placeholder="e.g., English, Hausa">
                            </div>

                            <!-- Portfolio Links -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Portfolio Links</label>
                                <input type="text" name="portfolio_links" value="{{ $admin->portfolio_links }}" class="form-control" placeholder="https://portfolio.com/username">
                            </div>

                            <!-- Social Media Accounts -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Social Media Accounts Managed</label>
                                <textarea name="social_media_accounts" class="form-control" rows="2" placeholder="e.g., Instagram: @brand, Twitter: @brand">{{ $admin->social_media_accounts }}</textarea>
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
                            <div class="col-12 mt-3">
                                <button class="btn btn-primary btn-lg w-100">Update Profile</button>
                            </div>

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


