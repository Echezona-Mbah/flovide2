@include('admin.head')

<style>
    :root {
        --profile-ink: #14213d;
        --profile-ink-soft: #64748b;
        --profile-paper: #ffffff;
        --profile-paper-soft: #f8fafc;
        --profile-line: #e2e8f0;
        --profile-blue: #2563eb;
        --profile-blue-deep: #1d4ed8;
        --profile-cyan: #0891b2;
        --profile-green: #16a34a;
        --profile-red: #dc2626;
        --profile-amber: #d97706;
        --profile-shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    #toast-container {
        position: fixed !important;
        bottom: 20px !important;
        right: 20px !important;
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
        border-radius: 12px;
        font-size: 13px;
        color: white;
        font-weight: 600;
        box-shadow: 0px 10px 24px rgba(0, 0, 0, 0.18);
        position: relative;
        animation: fadeIn 0.5s ease-in-out, fadeOut 0.5s ease-in-out 3.5s forwards;
        opacity: 1;
    }

    .toast.success {
        background: linear-gradient(135deg, #16a34a, #10b981);
    }

    .toast.error {
        background: linear-gradient(135deg, #dc2626, #f87171);
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

    .profile-page {
        padding-bottom: 32px;
    }

    .profile-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(8,145,178,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
        margin-bottom: 24px;
    }

    .profile-hero .card-body {
        padding: 34px;
    }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .hero-title {
        color: #fff;
        font-size: 2.1rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin: 14px 0 10px;
    }

    .hero-copy {
        color: rgba(255,255,255,0.82);
        max-width: 760px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .hero-metric {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 20px;
        padding: 16px 18px;
        color: #fff;
        height: 100%;
    }

    .hero-metric small {
        display: block;
        color: rgba(255,255,255,0.70);
        margin-bottom: 6px;
    }

    .hero-metric strong {
        font-size: 1.15rem;
        font-weight: 800;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--profile-paper);
        border: 1px solid var(--profile-line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--profile-shadow);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: var(--profile-blue); }
    .icon-green { background: rgba(22, 163, 74, 0.12); color: var(--profile-green); }
    .icon-amber { background: rgba(217, 119, 6, 0.12); color: var(--profile-amber); }
    .icon-cyan { background: rgba(8, 145, 178, 0.12); color: var(--profile-cyan); }

    .stat-label {
        font-size: 13px;
        color: var(--profile-ink-soft);
        margin-bottom: 6px;
    }

    .stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--profile-ink);
        line-height: 1.1;
    }

    .profile-side {
        border: 0;
        border-radius: 28px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 24px;
        box-shadow: var(--profile-shadow);
        position: sticky;
        top: 20px;
    }

    .profile-avatar-wrap {
        text-align: center;
        margin-bottom: 18px;
    }

    .profile-avatar {
        width: 128px;
        height: 128px;
        border-radius: 30px;
        object-fit: cover;
        border: 5px solid #eef4ff;
        box-shadow: 0 16px 28px rgba(15, 23, 42, 0.10);
    }

    .profile-name {
        color: var(--profile-ink);
        font-size: 22px;
        font-weight: 800;
        margin-bottom: 4px;
        text-align: center;
    }

    .profile-role {
        color: var(--profile-ink-soft);
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 18px;
    }

    .profile-meta-box {
        margin-top: 12px;
        padding: 14px 16px;
        border-radius: 18px;
        background: var(--profile-paper-soft);
        border: 1px solid var(--profile-line);
    }

    .profile-meta-box small {
        display: block;
        color: var(--profile-ink-soft);
        margin-bottom: 4px;
    }

    .profile-meta-box strong {
        color: var(--profile-ink);
        font-size: 14px;
    }

    .completion-card {
        margin-top: 18px;
        border-radius: 20px;
        padding: 16px;
        background: linear-gradient(135deg, #eff6ff, #ecfeff);
        border: 1px solid #dbeafe;
    }

    .completion-title {
        color: var(--profile-ink);
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .completion-progress {
        height: 10px;
        border-radius: 999px;
        background: #dbeafe;
        overflow: hidden;
    }

    .completion-progress span {
        display: block;
        height: 100%;
        background: linear-gradient(135deg, var(--profile-blue), var(--profile-cyan));
        border-radius: 999px;
    }

    .profile-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--profile-paper);
        box-shadow: var(--profile-shadow);
    }

    .profile-card .card-header {
        border: 0;
        padding: 22px 24px;
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .profile-card .card-body {
        padding: 24px;
    }

    .section-block {
        border: 1px solid var(--profile-line);
        border-radius: 24px;
        padding: 22px;
        background: var(--profile-paper-soft);
        margin-bottom: 20px;
    }

    .section-title {
        color: var(--profile-ink);
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: var(--profile-ink-soft);
        font-size: 13px;
        margin-bottom: 18px;
    }

    .form-label {
        color: var(--profile-ink);
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .form-control {
        min-height: 48px;
        border-radius: 15px !important;
        border: 1px solid #dbe3ee !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    textarea.form-control {
        min-height: 110px;
    }

    .form-control:focus {
        border-color: var(--profile-blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10) !important;
    }

    .readonly-box {
        background: #f1f5f9 !important;
        color: #64748b !important;
    }

    .profile-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .profile-nav a {
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid var(--profile-line);
        color: var(--profile-ink-soft);
        font-weight: 700;
        font-size: 13px;
    }

    .profile-nav a:hover {
        background: #f8fbff;
        color: var(--profile-ink);
    }

    .btn-save {
        background: linear-gradient(135deg, var(--profile-blue), var(--profile-cyan));
        border: 0;
        color: #fff;
        border-radius: 16px;
        font-weight: 800;
        min-height: 54px;
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.16);
    }

    .btn-save:hover {
        color: #fff;
        opacity: 0.96;
    }

    .btn-cancel {
        background: #eef2f7;
        border: 0;
        color: #334155;
        border-radius: 16px;
        font-weight: 700;
        min-height: 54px;
    }

    .modal-content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
    }

    .modal-header {
        background: linear-gradient(135deg, #1d4ed8, #0891b2);
        color: #fff;
        border-bottom: 0;
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

                    <div id="toast-container"></div>

                    @php
                        $profileFields = [
                            $admin->name,
                            $admin->email,
                            $admin->phone,
                            $admin->skills,
                            $admin->experience,
                            $admin->languages_spoken,
                            $admin->portfolio_links,
                            $admin->social_media_accounts,
                            $admin->emergency_contact,
                            $admin->linkedin_profile,
                            $admin->profile_picture,
                        ];

                        $filledCount = collect($profileFields)->filter(fn($item) => !empty($item))->count();
                        $completionPercent = round(($filledCount / count($profileFields)) * 100);
                    @endphp

                    <div class="profile-page">

                        <div class="card profile-hero">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-user"></i>
                                            Personal Workspace
                                        </div>
                                        <h1 class="hero-title">Profile</h1>
                                        <p class="hero-copy">
                                            Upgrade your admin identity with a richer profile setup, organized information blocks, and a cleaner editing experience for work details, social links, and contact data.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Name</small>
                                                    <strong>{{ $admin->name }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Role</small>
                                                    <strong>{{ $admin->role }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Profile Completion</small>
                                                    <strong>{{ $completionPercent }}%</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-stats">
                            <div class="stat-card">
                                <div class="stat-icon icon-blue">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="stat-label">Full Name</div>
                                <div class="stat-value">{{ $admin->name }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-cyan">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                                <div class="stat-label">Email</div>
                                <div class="stat-value" style="font-size:18px;">{{ $admin->email }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-green">
                                    <i class="fa-solid fa-medal"></i>
                                </div>
                                <div class="stat-label">Admin Role</div>
                                <div class="stat-value">{{ $admin->role }}</div>
                            </div>

                            <div class="stat-card">
                                <div class="stat-icon icon-amber">
                                    <i class="fa-solid fa-user-large"></i>
                                </div>
                                <div class="stat-label">Completion</div>
                                <div class="stat-value">{{ $completionPercent }}%</div>
                            </div>
                        </div>

                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                            @csrf

                            <div class="row">
                                <div class="col-xl-4 mb-4">
                                    <div class="profile-side">
                                        <div class="profile-avatar-wrap">
                                            @if($admin->profile_picture)
                                                <img src="{{ asset('storage/'.$admin->profile_picture) }}" class="profile-avatar" id="profilePreview">
                                            @else
                                                <img src="{{ asset('assets/images/avatars/default.png') }}" class="profile-avatar" id="profilePreview">
                                            @endif
                                        </div>

                                        <div class="profile-name">{{ $admin->name }}</div>
                                        <div class="profile-role">{{ $admin->role ?? 'Admin' }}</div>

                                        <div class="mt-4">
                                            <label class="form-label">Profile Picture</label>
                                            <input type="file" name="profile_picture" class="form-control" id="profileInput">
                                        </div>

                                        <div class="profile-meta-box">
                                            <small>Email Address</small>
                                            <strong>{{ $admin->email }}</strong>
                                        </div>

                                        <div class="profile-meta-box">
                                            <small>Phone Number</small>
                                            <strong>{{ $admin->phone ?? 'Not added yet' }}</strong>
                                        </div>

                                        <div class="profile-meta-box">
                                            <small>Languages</small>
                                            <strong>{{ $admin->languages_spoken ?? 'Not added yet' }}</strong>
                                        </div>

                                        <div class="completion-card">
                                            <div class="completion-title">Profile Completion</div>
                                            <div class="completion-progress">
                                                <span style="width: {{ $completionPercent }}%;"></span>
                                            </div>
                                            <div class="mt-2 text-muted small">{{ $completionPercent }}% of profile details completed</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-8">
                                    <div class="profile-card">
                                        <div class="card-header">
                                            <div class="section-title">Manage Profile</div>
                                            <div class="section-subtitle">Organize and update your professional admin identity.</div>
                                        </div>

                                        <div class="card-body">
                                            <div class="profile-nav">
                                                <a href="#basic-info">Basic Info</a>
                                                <a href="#professional-info">Professional</a>
                                                <a href="#links-info">Links & Accounts</a>
                                            </div>

                                            <div class="section-block" id="basic-info">
                                                <div class="section-title">Basic Information</div>
                                                <div class="section-subtitle">Core personal account details.</div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Full Name</label>
                                                        <input type="text" name="name" value="{{ $admin->name }}" class="form-control" placeholder="Enter full name">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Email Address</label>
                                                        <input type="email" name="email" value="{{ $admin->email }}" class="form-control readonly-box" readonly>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Phone Number</label>
                                                        <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control" placeholder="Enter phone number">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Admin Role</label>
                                                        <input type="text" name="role_id" value="{{ $admin->role }}" class="form-control readonly-box" readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-block" id="professional-info">
                                                <div class="section-title">Professional Details</div>
                                                <div class="section-subtitle">Skills, experience, and work profile information.</div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Skills</label>
                                                        <textarea name="skills" class="form-control" rows="2" placeholder="e.g., Laravel, UI/UX, Marketing">{{ $admin->skills }}</textarea>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Experience</label>
                                                        <textarea name="experience" class="form-control" rows="3" placeholder="Describe work experience...">{{ $admin->experience }}</textarea>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Languages Spoken</label>
                                                        <input type="text" name="languages_spoken" value="{{ $admin->languages_spoken }}" class="form-control" placeholder="e.g., English, Hausa">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Emergency Contact</label>
                                                        <input type="text" name="emergency_contact" value="{{ $admin->emergency_contact }}" class="form-control" placeholder="Enter emergency contact">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-block mb-0" id="links-info">
                                                <div class="section-title">Links & Accounts</div>
                                                <div class="section-subtitle">Public links and social account information.</div>

                                                <div class="row">
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label">Portfolio Links</label>
                                                        <input type="text" name="portfolio_links" value="{{ $admin->portfolio_links }}" class="form-control" placeholder="https://portfolio.com/username">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">LinkedIn Profile</label>
                                                        <input type="text" name="linkedin_profile" value="{{ $admin->linkedin_profile }}" class="form-control" placeholder="https://linkedin.com/in/profile">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label">Social Media Accounts Managed</label>
                                                        <textarea name="social_media_accounts" class="form-control" rows="2" placeholder="e.g., Instagram: @brand, Twitter: @brand">{{ $admin->social_media_accounts }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-md-4 mb-2">
                                                    <a href="{{ url()->previous() }}" class="btn btn-cancel w-100">Cancel</a>
                                                </div>
                                                <div class="col-md-8 mb-2">
                                                    <button type="button" class="btn btn-save btn-lg w-100" data-bs-toggle="modal" data-bs-target="#confirmProfileUpdateModal">
                                                        Update Profile
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="confirmProfileUpdateModal" tabindex="-1" aria-labelledby="confirmProfileUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmProfileUpdateModalLabel">Confirm Profile Update</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to save the changes made to your admin profile?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-save" id="confirmProfileUpdateBtn">Yes, Update Profile</button>
            </div>
        </div>
    </div>
</div>

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

    const profileInput = document.getElementById('profileInput');
    const profilePreview = document.getElementById('profilePreview');
    const profileForm = document.getElementById('profileForm');
    const confirmBtn = document.getElementById('confirmProfileUpdateBtn');

    if (profileInput) {
        profileInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            profileForm.submit();
        });
    }

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showToast("{{ $error }}", "error");
        @endforeach
    @endif

    @if (session('success'))
        showToast("{{ session('success') }}", "success");
    @endif

    @if (session('error'))
        showToast("{{ session('error') }}", "error");
    @endif
});
</script>

@include('admin.footer')
