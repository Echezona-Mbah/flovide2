@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-blue: #2563eb;
        --brand-sky: #0ea5e9;
        --brand-soft: #eff6ff;
        --surface: #ffffff;
        --surface-muted: #f8fafc;
        --border-soft: #e2e8f0;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --success: #16a34a;
        --warning: #f59e0b;
        --danger: #dc2626;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
    }

    .business-shell {
        padding-bottom: 32px;
    }

    .hero-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.14), transparent 24%),
            radial-gradient(circle at bottom left, rgba(56,189,248,0.12), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: 0 24px 60px rgba(37, 99, 235, 0.16);
    }

    .hero-card .card-body {
        padding: 30px;
    }

    .hero-avatar-wrap {
        width: 96px;
        height: 96px;
        border-radius: 24px;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.15);
        box-shadow: 0 12px 24px rgba(0,0,0,0.16);
        background: rgba(255,255,255,0.08);
        flex-shrink: 0;
    }

    .hero-avatar-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .hero-mini {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 18px;
        padding: 14px 16px;
        color: #fff;
        height: 100%;
    }

    .hero-mini small {
        display: block;
        color: rgba(255,255,255,0.7);
        margin-bottom: 6px;
    }

    .edit-card {
        border: 0;
        border-radius: 24px;
        background: var(--surface);
        box-shadow: var(--shadow-soft);
        overflow: hidden;
    }

    .edit-card .card-header {
        border: 0;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 22px 24px;
    }

    .edit-card .card-body {
        padding: 24px;
    }

    .section-card {
        border: 1px solid var(--border-soft);
        border-radius: 22px;
        background: var(--surface-muted);
        padding: 22px;
        margin-bottom: 22px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .section-subtitle {
        font-size: 13px;
        color: var(--text-soft);
        margin-bottom: 18px;
    }

    .profile-upload-panel {
        border: 1px solid var(--border-soft);
        border-radius: 22px;
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        padding: 22px;
        text-align: center;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .profile-preview {
        width: 132px;
        height: 132px;
        margin: 0 auto 16px;
        border-radius: 999px;
        overflow: hidden;
        border: 5px solid #eaf2ff;
        box-shadow: 0 12px 26px rgba(37, 99, 235, 0.12);
        background: #fff;
    }

    .profile-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .custom-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .custom-input,
    .custom-select,
    .custom-file {
        min-height: 48px;
        border-radius: 14px !important;
        border: 1px solid #dbe3ee !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    .custom-input:focus,
    .custom-select:focus,
    .custom-file:focus {
        border-color: var(--brand-blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10) !important;
    }

    .readonly-field {
        background: #f1f5f9 !important;
        color: #64748b !important;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-active {
        background: rgba(22, 163, 74, 0.12);
        color: var(--success);
    }

    .status-inactive {
        background: rgba(220, 38, 38, 0.12);
        color: var(--danger);
    }

    .action-bar {
        border-top: 1px solid var(--border-soft);
        padding-top: 22px;
        margin-top: 10px;
    }

    .btn-soft-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: 0;
        color: #fff;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(37, 99, 235, 0.16);
    }

    .btn-soft-primary:hover {
        color: #fff;
        opacity: 0.96;
    }

    .btn-soft-light {
        background: #eef2f7;
        border: 0;
        color: #334155;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 600;
    }

    .summary-card {
        border: 0;
        border-radius: 24px;
        background: #fff;
        box-shadow: var(--shadow-soft);
        height: 100%;
    }

    .summary-card .card-body {
        padding: 24px;
    }

    .summary-item {
        padding: 14px 0;
        border-bottom: 1px solid var(--border-soft);
    }

    .summary-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .summary-item span {
        display: block;
        font-size: 12px;
        color: var(--text-soft);
        margin-bottom: 5px;
    }

    .summary-item strong {
        color: var(--text-main);
        font-size: 14px;
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
                                    <i class="pe-7s-portfolio icon-gradient bg-ripe-malin"></i>
                                </div>
                                <div>
                                    Business Account
                                    <div class="page-title-subheading">
                                        Review and update business owner details, company information, and profile settings.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="business-shell">
                        @if(session('success'))
                            <script>
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: "{{ session('success') }}",
                                    confirmButtonColor: '#2563eb'
                                });
                            </script>
                        @endif

                        <div class="card hero-card mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                                            <div class="hero-avatar-wrap">
                                                <img
                                                    src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
                                                    alt="Profile"
                                                    id="previewImage">
                                            </div>

                                            <div class="text-white">
                                                <div class="hero-badge mb-2">
                                                    <i class="pe-7s-id"></i>
                                                    Business User Profile
                                                </div>
                                                <h2 class="mb-1 text-white">{{ $user->firstname }} {{ $user->lastname }}</h2>
                                                <p class="mb-0 text-white-50">{{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 700px; line-height: 1.8;">
                                            Manage personal and business information for this account in one place, with clearer sections and a cleaner editing experience.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Account Status</small>
                                                    @if($user->deletestatus == 'active')
                                                        <span class="status-pill status-active">Active</span>
                                                    @else
                                                        <span class="status-pill status-inactive">Inactive</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>User ID</small>
                                                    <strong>#{{ $user->id }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="hero-mini">
                                                    <small>Business Name</small>
                                                    <strong>{{ $user->business_name ?? 'Not provided' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ url('/admin/business-account/update/'.$user->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-xl-8">
                                    <div class="card edit-card">
                                        <div class="card-header">
                                            <h4 class="mb-1">Edit User</h4>
                                            <p class="mb-0 text-muted">Update personal, business, and account details below.</p>
                                        </div>

                                        <div class="card-body">

                                            <div class="section-card">
                                                <div class="section-title">Personal Information</div>
                                                <div class="section-subtitle">Basic user identity and contact details.</div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">First Name</label>
                                                        <input type="text" name="firstname" class="form-control custom-input" value="{{ $user->firstname }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Last Name</label>
                                                        <input type="text" name="lastname" class="form-control custom-input" value="{{ $user->lastname }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Email Address</label>
                                                        <input type="email" name="email" class="form-control custom-input readonly-field" value="{{ $user->email }}" readonly>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Personal Phone</label>
                                                        <input type="text" name="person_phone" class="form-control custom-input" value="{{ $user->person_phone }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-card">
                                                <div class="section-title">Business Information</div>
                                                <div class="section-subtitle">Core company profile and registration details.</div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Business Name</label>
                                                        <input type="text" name="business_name" class="form-control custom-input" value="{{ $user->business_name }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Business Phone</label>
                                                        <input type="text" name="business_phone" class="form-control custom-input" value="{{ $user->business_phone }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Registration Number</label>
                                                        <input type="text" name="registration_number" class="form-control custom-input" value="{{ $user->registration_number }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Business Type</label>
                                                        <input type="text" name="business_type" class="form-control custom-input" value="{{ $user->business_type }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Industry</label>
                                                        <input type="text" name="industry" class="form-control custom-input" value="{{ $user->industry }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Company URL</label>
                                                        <input type="text" name="company_url" class="form-control custom-input" value="{{ $user->company_url }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Incorporation Date</label>
                                                        <input type="text" name="incorporation_date" class="form-control custom-input" value="{{ $user->incorporation_date }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Annual Turnover</label>
                                                        <input type="text" name="annual_turnover" class="form-control custom-input" value="{{ $user->annual_turnover }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-card">
                                                <div class="section-title">Address & Location</div>
                                                <div class="section-subtitle">Operational and physical business address information.</div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Street Address</label>
                                                        <input type="text" name="street_address" class="form-control custom-input" value="{{ $user->street_address }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Trading Address</label>
                                                        <input type="text" name="trading_address" class="form-control custom-input" value="{{ $user->trading_address }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">City</label>
                                                        <input type="text" name="city" class="form-control custom-input" value="{{ $user->city }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">State</label>
                                                        <input type="text" name="state" class="form-control custom-input" value="{{ $user->state }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Country</label>
                                                        <input type="text" name="countries_id" class="form-control custom-input" value="{{ $user->countries_id }}">
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Currency</label>
                                                        <input type="text" name="currency" class="form-control custom-input" value="{{ $user->currency }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="section-card mb-0">
                                                <div class="section-title">Referral & Account State</div>
                                                <div class="section-subtitle">Manage referral data and user availability.</div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Status</label>
                                                        <select name="deletestatus" class="form-control custom-select">
                                                            <option value="active" {{ $user->deletestatus == 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="deactivated" {{ $user->deletestatus == 'deactivated' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="custom-label">Referral Code</label>
                                                        <input type="text" name="referral_code" class="form-control custom-input" value="{{ $user->referral_code }}">
                                                    </div>

                                                    <div class="col-md-12 mb-0">
                                                        <label class="custom-label">Referral Link</label>
                                                        <input type="text" name="referral_link" class="form-control custom-input" value="{{ $user->referral_link }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="action-bar d-flex flex-wrap justify-content-end gap-2">
                                                <a href="{{ url()->previous() }}" class="btn btn-soft-light">Cancel</a>
                                                <button type="submit" class="btn btn-soft-primary">Update User</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-4 mt-4 mt-xl-0">
                                    <div class="profile-upload-panel mb-4">
                                        <div class="profile-preview">
                                            <img
                                                src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('asserts/dashboard/circle-dot.png') }}"
                                                alt="Profile"
                                                id="previewImageSide">
                                        </div>

                                        <h5 class="mb-1">{{ $user->firstname }} {{ $user->lastname }}</h5>
                                        <p class="text-muted mb-3">{{ $user->business_name ?? 'No business name' }}</p>

                                        <label class="custom-label text-start">Profile Picture</label>
                                        <input type="file" name="profile_picture" class="form-control custom-file" id="profilePictureInput">
                                    </div>

                                    <div class="card summary-card">
                                        <div class="card-body">
                                            <h5 class="section-title">Quick Summary</h5>

                                            <div class="summary-item">
                                                <span>Email</span>
                                                <strong>{{ $user->email }}</strong>
                                            </div>

                                            <div class="summary-item">
                                                <span>Business Phone</span>
                                                <strong>{{ $user->business_phone ?? 'Not provided' }}</strong>
                                            </div>

                                            <div class="summary-item">
                                                <span>Industry</span>
                                                <strong>{{ $user->industry ?? 'Not provided' }}</strong>
                                            </div>

                                            <div class="summary-item">
                                                <span>Country</span>
                                                <strong>{{ $user->countries_id ?? 'Not provided' }}</strong>
                                            </div>

                                            <div class="summary-item">
                                                <span>Currency</span>
                                                <strong>{{ $user->currency ?? 'Not provided' }}</strong>
                                            </div>

                                            <div class="summary-item">
                                                <span>Status</span>
                                                <strong>{{ ucfirst($user->deletestatus ?? 'active') }}</strong>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('profilePictureInput');
        const previewMain = document.getElementById('previewImage');
        const previewSide = document.getElementById('previewImageSide');

        if (fileInput) {
            fileInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewMain) previewMain.src = e.target.result;
                    if (previewSide) previewSide.src = e.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    });
</script>

@include('admin.footer')
