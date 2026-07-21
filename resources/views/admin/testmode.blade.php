@include('admin.head')

<style>
    :root {
        --brand-dark: #0f172a;
        --brand-deep: #1d4ed8;
        --brand-sky: #0ea5e9;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --border-soft: #e2e8f0;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
        --success-soft: rgba(22, 163, 74, 0.12);
        --warning-soft: rgba(245, 158, 11, 0.14);
        --danger-soft: rgba(220, 38, 38, 0.12);
    }

    .dashboard-shell {
        padding-bottom: 32px;
    }

    .merchant-hero {
        border: 0;
        border-radius: 30px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.14), transparent 24%),
            radial-gradient(circle at bottom left, rgba(56,189,248,0.12), transparent 28%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: 0 28px 65px rgba(29, 78, 216, 0.18);
    }

    .merchant-hero .card-body {
        padding: 34px;
    }

    .merchant-avatar {
        width: 100px;
        height: 100px;
        border-radius: 28px;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.16);
        box-shadow: 0 12px 24px rgba(0,0,0,0.16);
        background: rgba(255,255,255,0.08);
        flex-shrink: 0;
    }

    .merchant-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .hero-mini {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 18px;
        padding: 16px;
        color: #fff;
        height: 100%;
    }

    .hero-mini small {
        display: block;
        margin-bottom: 6px;
        color: rgba(255,255,255,0.72);
    }


    /* Custom premium styling for Blog Editor */
    .blog-title-input {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        border: none;
        border-bottom: 2px solid #e2e8f0;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        transition: all 0.3s ease;
        background: transparent;
    }
    .blog-title-input:focus {
        box-shadow: none;
        border-color: #3f51b5;
        background: transparent;
    }
    
    .editor-toolbar {
        background: #f8fafc;
        border: 1px solid #ced4da;
        border-bottom: none;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        padding: 8px 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .editor-btn {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 4px;
        color: #495057;
        padding: 5px 10px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .editor-btn:hover {
        background: #e9ecef;
        color: #3f51b5;
        border-color: #adb5bd;
    }
    
    .editor-textarea {
        border: 1px solid #ced4da;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        padding: 15px;
        font-size: 1rem;
        line-height: 1.6;
        min-height: 350px;
        color: #495057;
        resize: vertical;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .editor-textarea:focus {
        border-color: #3f51b5;
        box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.1);
    }
    
    /* Dropzone Custom Styling */
    .image-dropzone {
        border: 2px dashed #ced4da;
        border-radius: 8px;
        padding: 30px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .image-dropzone:hover {
        border-color: #3f51b5;
        background: #f1f3f9;
    }
    
    .image-preview-container {
        display: none;
        width: 100%;
        height: 180px;
        border-radius: 6px;
        background-size: cover;
        background-position: center;
        position: relative;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .remove-image-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .remove-image-btn:hover {
        background: rgba(220, 53, 69, 1);
    }
    
    /* SEO Search Snippet Preview */
    .google-preview-card {
        background: white;
        border: 1px solid #ced4da;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    
    .google-preview-title {
        color: #1a0dab;
        font-size: 19px;
        line-height: 1.3;
        margin-bottom: 3px;
        font-family: Arial, sans-serif;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .google-preview-title:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    
    .google-preview-url {
        color: #202124;
        font-size: 14px;
        line-height: 1.3;
        margin-bottom: 4px;
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .google-preview-description {
        color: #4d5156;
        font-size: 14px;
        line-height: 1.58;
        font-family: Arial, sans-serif;
        word-wrap: break-word;
    }
    
    /* Tags styling */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
    }
    
    .tag-badge {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        animation: scaleIn 0.2s ease;
    }
    
    .tag-badge .remove-tag {
        cursor: pointer;
        color: #6c757d;
        font-weight: bold;
        transition: color 0.2s ease;
    }
    .tag-badge .remove-tag:hover {
        color: #dc3545;
    }
    
    @keyframes scaleIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    
    /* Custom button styling */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #3f51b5 0%, #2196f3 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #2196f3 0%, #3f51b5 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        transform: translateY(-1px);
    }
    
    .btn-gradient-secondary {
        background: #ffffff;
        color: #495057;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }
    .btn-gradient-secondary:hover {
        background: #f8f9fa;
        color: #212529;
        border-color: #b1b5ba;
    }
    
    /* Character counts */
    .char-counter {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: right;
        margin-top: 4px;
        display: block;
    }
    
    /* Status Badge styling */
    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
    }
    .status-draft { background-color: #f7b924; }
    .status-published { background-color: #3ac47d; }
    .status-scheduled { background-color: #16aaff; }

    .badge-success {
        background-color: #099244 !important;
    }

    .badge-debit {
        background-color: #dc3545 !important;
    }

    .badge-warning {
        background-color: #ffc107 !important;
    }

    .badge-pending {
        background-color: #f7b924 !important;
    }

    .modal {
        z-index: 9999 !important;
    }

    .modal-backdrop {
        z-index: 9998 !important;
    }

    .dashboard-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        padding: 0;
        margin: 24px 0;
        list-style: none;
    }

    .dashboard-tabs .nav-link {
        border: 0;
        border-radius: 16px;
        background: #fff;
        color: var(--text-soft);
        font-weight: 700;
        padding: 12px 22px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
    }

    .dashboard-tabs .nav-link.active,
    .dashboard-tabs .nav-link.show {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        box-shadow: 0 16px 30px rgba(37, 99, 235, 0.18);
    }

    .dashboard-card {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: var(--surface);
        box-shadow: var(--shadow-soft);
        margin-bottom: 24px;
    }

    .dashboard-card .card-header {
        border: 0;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        padding: 22px 24px;
    }

    .dashboard-card .card-body {
        padding: 24px;
    }

    .dashboard-card .card-footer {
        border-top: 1px solid var(--border-soft);
        background: #fff;
        padding: 18px 24px;
    }

    /* Test-Mode Banner */
    .test-mode-banner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 14px 22px;
        margin-bottom: 20px;
        border-radius: 16px;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 40%, #fde68a 100%);
        border: 1.5px solid #f59e0b;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.18), 0 1px 4px rgba(245, 158, 11, 0.10);
        position: relative;
        overflow: hidden;
    }

    .test-mode-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            -45deg,
            transparent,
            transparent 18px,
            rgba(245, 158, 11, 0.06) 18px,
            rgba(245, 158, 11, 0.06) 36px
        );
        pointer-events: none;
    }

    .test-mode-left {
        display: flex;
        align-items: center;
        gap: 14px;
        z-index: 1;
    }

    .test-mode-icon-wrap {
        position: relative;
        width: 44px;
        height: 44px;
        flex-shrink: 0;
    }

    .test-mode-icon-wrap .icon-bg {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.40);
        color: #fff;
        font-size: 18px;
    }

    .test-mode-pulse {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #ef4444;
        border: 2px solid #fff;
        animation: tmPulse 1.6s ease-in-out infinite;
    }

    @keyframes tmPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.55); }
        50%       { box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
    }

    .test-mode-text h6 {
        margin: 0 0 2px;
        font-size: 0.93rem;
        font-weight: 700;
        color: #92400e;
        letter-spacing: 0.3px;
    }

    .test-mode-text p {
        margin: 0;
        font-size: 0.82rem;
        color: #b45309;
        line-height: 1.45;
    }

    .test-mode-badges {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        z-index: 1;
    }

    .tm-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .tm-chip-main {
        background: #f59e0b;
        color: #fff;
        box-shadow: 0 2px 8px rgba(245,158,11,0.35);
    }

    .tm-chip-env {
        background: rgba(180, 83, 9, 0.12);
        color: #92400e;
        border: 1px solid rgba(180, 83, 9, 0.22);
    }

    .test-mode-dismiss {
        background: none;
        border: 1.5px solid rgba(180, 83, 9, 0.30);
        border-radius: 8px;
        color: #92400e;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 6px 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        z-index: 1;
        flex-shrink: 0;
    }

    .test-mode-dismiss:hover {
        background: rgba(180, 83, 9, 0.08);
        border-color: #b45309;
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

                    <div class="dashboard-shell">

                        <section class="test-mode-banner" id="testModeBanner">
                            <div class="test-mode-left">
                                <div class="test-mode-icon-wrap">
                                    <div class="icon-bg">
                                        <i class="fa-solid fa-flask"></i>
                                    </div>
                                    <span class="test-mode-pulse"></span>
                                </div>

                                <div class="test-mode-text">
                                    <h6><i class="fa-solid fa-triangle-exclamation me-1"></i> Test Mode Active</h6>
                                    <p>You are currently viewing this merchant's profile under a simulated test environment. No real transactions are being processed.</p>
                                </div>
                            </div>

                            <div class="test-mode-badges">
                                <span class="tm-chip tm-chip-main">
                                    <i class="fa-solid fa-circle" style="font-size:7px;"></i>
                                    Test Mode
                                </span>
                                <span class="tm-chip tm-chip-env">
                                    <i class="fa-solid fa-server" style="font-size:10px;"></i>
                                    Sandbox Env
                                </span>
                            </div>

                            <button class="test-mode-dismiss" onclick="document.getElementById('testModeBanner').style.display='none'">
                                <i class="fa-solid fa-xmark me-1"></i> Dismiss
                            </button>
                        </section>

                        <div class="card merchant-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-center flex-wrap gap-3 mb-4">
                                            <div class="merchant-avatar">
                                                <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('admin/assets/images/avatars/user33.png') }}" alt="Profile">
                                            </div>

                                            <div class="text-white">
                                                <div class="hero-pill mb-2">
                                                    <i class="fa-solid fa-building"></i>
                                                    Business Merchant Dashboard
                                                </div>
                                                <h2 class="mb-1 text-white">{{ $user->business_name ?? ($user->firstname.' '.$user->lastname) }}</h2>
                                                <p class="mb-0 text-white-50">{{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 720px; line-height: 1.8;">
                                            A cleaner overview of this merchant’s profile, verification documents, balances, virtual cards, team structure, and banking relationships.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Merchant ID</small>
                                                    <strong>#{{ $user->id }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>Email Status</small>
                                                    <strong>{{ $user->email_verified_status == 'yes' ? 'Verified' : 'Not Verified' }}</strong>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="hero-mini">
                                                    <small>Business Phone</small>
                                                    <strong>{{ $user->business_phone ?? 'Not provided' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <ul class="dashboard-tabs nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active show" data-tab="Transactions" href="javascript:void(0);">Transactions</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" data-tab="Balances & Cards" href="javascript:void(0);">Balances & Cards</a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" data-tab="Beneficiaries" href="javascript:void(0);">Beneficiaries</a>
                            </li>
                        </ul>

                        <!-- TAB PANES   -->
                        <div id="pane-Transactions" class="tab-pane-content">
                            <div class="card dashboard-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 fw-bold" style="color:var(--text-main)">
                                        <i class="fa-solid fa-receipt me-2 text-primary"></i>Transaction History
                                    </h5>
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill" style="font-size:0.78rem;">
                                        <i class="fa-solid fa-clock-rotate-left me-1"></i> Test Records
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    @if($transactions->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-table-list fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-1 fw-semibold">No test transactions found</p>
                                            <small>Transactions made in test mode will appear here.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                                                <thead style="background:#f8fafc;">
                                                    <tr>
                                                        <th class="px-4 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Reference</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Type</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Sender</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Recipient</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Amount</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Status</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($transactions as $tx)
                                                        <tr>
                                                            <td class="px-4 py-3">
                                                                <code class="text-primary" style="font-size:0.78rem;">{{ $tx->reference ?? $tx->payment_reference ?? '—' }}</code>
                                                            </td>
                                                            <td class="px-3 py-3">
                                                                <span class="badge rounded-pill" style="background:rgba(37,99,235,.1);color:#1d4ed8;font-size:0.72rem;">
                                                                    {{ ucfirst($tx->transaction_type ?? $tx->type ?? '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3" style="color:var(--text-main);">{{ $tx->sender ?? '—' }}</td>
                                                            <td class="px-3 py-3" style="color:var(--text-main);">{{ $tx->recipient_account_name ?? '—' }}</td>
                                                            <td class="px-3 py-3 fw-semibold" style="color:var(--text-main);">
                                                                {{ $tx->currency }} {{ number_format($tx->amount, 2) }}
                                                            </td>
                                                            <td class="px-3 py-3">
                                                                @php
                                                                    $s = strtolower($tx->status ?? '');
                                                                    $statusMap = [
                                                                        'success'   => ['bg'=>'#dcfce7','color'=>'#15803d'],
                                                                        'completed' => ['bg'=>'#dcfce7','color'=>'#15803d'],
                                                                        'pending'   => ['bg'=>'#fef9c3','color'=>'#92400e'],
                                                                        'failed'    => ['bg'=>'#fee2e2','color'=>'#b91c1c'],
                                                                    ];
                                                                    $style = $statusMap[$s] ?? ['bg'=>'#f1f5f9','color'=>'#64748b'];
                                                                @endphp
                                                                <span class="badge rounded-pill px-3" style="background:{{ $style['bg'] }};color:{{ $style['color'] }};font-size:0.72rem;">
                                                                    {{ ucfirst($tx->status ?? '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3" style="color:var(--text-soft);white-space:nowrap;">
                                                                {{ $tx->created_at?->format('d M Y, H:i') ?? '—' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($transactions->hasPages())
                                            <div class="px-4 py-3 border-top" style="background:#fff;border-radius:0 0 24px 24px;">
                                                {{ $transactions->appends(request()->except('transactions_page'))->links('pagination::bootstrap-5') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>




                        {{-- Balances & Cards --}}
                        <div id="pane-Balances-Cards" class="tab-pane-content d-none">
                            <div class="card dashboard-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 fw-bold" style="color:var(--text-main)">
                                        <i class="fa-solid fa-wallet me-2" style="color:#0ea5e9;"></i>Balances &amp; Cards
                                    </h5>
                                    <span class="badge px-3 py-2 rounded-pill" style="background:rgba(14,165,233,.12);color:#0369a1;font-size:0.78rem;">
                                        <i class="fa-solid fa-credit-card me-1"></i> Account Overview
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    @if($balances->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-wallet fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-1 fw-semibold">No test balances found</p>
                                            <small>Balances created in test mode will appear here.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                                                <thead style="background:#f8fafc;">
                                                    <tr>
                                                        <th class="px-4 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">#</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Name</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Currency</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Amount</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Created</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($balances as $i => $bal)
                                                        <tr>
                                                            <td class="px-4 py-3" style="color:var(--text-soft);">{{ $balances->firstItem() + $i }}</td>
                                                            <td class="px-3 py-3 fw-semibold" style="color:var(--text-main);">{{ $bal->name ?? '—' }}</td>
                                                            <td class="px-3 py-3">
                                                                <span class="badge rounded-pill px-3" style="background:rgba(14,165,233,.1);color:#0369a1;font-size:0.72rem;">
                                                                    {{ strtoupper($bal->currency ?? '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3 fw-bold" style="color:#0f172a;">
                                                                {{ number_format($bal->amount, 2) }}
                                                            </td>
                                                            <td class="px-3 py-3" style="color:var(--text-soft);white-space:nowrap;">
                                                                {{ $bal->created_at?->format('d M Y, H:i') ?? '—' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($balances->hasPages())
                                            <div class="px-4 py-3 border-top" style="background:#fff;border-radius:0 0 24px 24px;">
                                                {{ $balances->appends(request()->except('balances_page'))->links('pagination::bootstrap-5') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>





                        {{-- Beneficiaries --}}
                        <div id="pane-Beneficiaries" class="tab-pane-content d-none">
                            <div class="card dashboard-card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 fw-bold" style="color:var(--text-main)">
                                        <i class="fa-solid fa-users me-2" style="color:#16a34a;"></i>Beneficiaries
                                    </h5>
                                    <span class="badge px-3 py-2 rounded-pill" style="background:rgba(22,163,74,.12);color:#15803d;font-size:0.78rem;">
                                        <i class="fa-solid fa-address-book me-1"></i> Saved Recipients
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    @if($beneficiaries->isEmpty())
                                        <div class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-address-book fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-1 fw-semibold">No test beneficiaries found</p>
                                            <small>Beneficiaries saved in test mode will appear here.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" style="font-size:0.875rem;">
                                                <thead style="background:#f8fafc;">
                                                    <tr>
                                                        <th class="px-4 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Name</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Type</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Bank</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Account No.</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Currency</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Country</th>
                                                        <th class="px-3 py-3 text-uppercase" style="font-size:0.72rem;color:var(--text-soft);font-weight:700;letter-spacing:.6px;">Added</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($beneficiaries as $ben)
                                                        <tr>
                                                            <td class="px-4 py-3">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                                         style="width:34px;height:34px;background:rgba(22,163,74,.12);color:#15803d;font-weight:700;font-size:0.8rem;">
                                                                        {{ strtoupper(substr($ben->beneficiary_name ?? $ben->account_name ?? '?', 0, 1)) }}
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-semibold" style="color:var(--text-main);">{{ $ben->beneficiary_name ?? $ben->account_name ?? '—' }}</div>
                                                                        @if($ben->alias)
                                                                            <small style="color:var(--text-soft);">{{ $ben->alias }}</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-3 py-3">
                                                                <span class="badge rounded-pill px-3" style="background:rgba(22,163,74,.1);color:#15803d;font-size:0.72rem;">
                                                                    {{ ucfirst($ben->type ?? '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3" style="color:var(--text-main);">{{ $ben->bank ?? '—' }}</td>
                                                            <td class="px-3 py-3">
                                                                <code style="font-size:0.78rem;color:var(--text-soft);">{{ $ben->account_number ?? '—' }}</code>
                                                            </td>
                                                            <td class="px-3 py-3">
                                                                <span class="badge rounded-pill px-3" style="background:rgba(14,165,233,.1);color:#0369a1;font-size:0.72rem;">
                                                                    {{ strtoupper($ben->currency ?? '—') }}
                                                                </span>
                                                            </td>
                                                            <td class="px-3 py-3" style="color:var(--text-soft);">{{ $ben->country ?? '—' }}</td>
                                                            <td class="px-3 py-3" style="color:var(--text-soft);white-space:nowrap;">
                                                                {{ $ben->created_at?->format('d M Y') ?? '—' }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($beneficiaries->hasPages())
                                            <div class="px-4 py-3 border-top" style="background:#fff;border-radius:0 0 24px 24px;">
                                                {{ $beneficiaries->appends(request()->except('beneficiaries_page'))->links('pagination::bootstrap-5') }}
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>{{-- /.dashboard-shell --}}

                </div>
            </div>
        </div>

    </div>




    @include('admin.footer')

    <script>
    (function () {
        /* Map tab label → pane element ID */
        const paneMap = {
            'Transactions'     : 'pane-Transactions',
            'Balances & Cards' : 'pane-Balances-Cards',
            'Beneficiaries'    : 'pane-Beneficiaries',
        };

        const tabLinks = document.querySelectorAll('.dashboard-tabs .nav-link');
        const panes    = document.querySelectorAll('.tab-pane-content');

        tabLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                const target = this.dataset.tab;

                /* Update nav active state */
                tabLinks.forEach(function (l) { l.classList.remove('active', 'show'); });
                this.classList.add('active', 'show');

                /* Fade-out all panes, then show the chosen one */
                panes.forEach(function (pane) {
                    pane.classList.add('d-none');
                    pane.style.opacity = '0';
                });

                const chosen = document.getElementById(paneMap[target]);
                if (chosen) {
                    chosen.classList.remove('d-none');
                    /* Micro-animation: fade in */
                    requestAnimationFrame(function () {
                        chosen.style.transition = 'opacity 0.25s ease';
                        chosen.style.opacity    = '1';
                    });
                }
            });
        });

        /* Initialise opacity on visible pane */
        document.querySelectorAll('.tab-pane-content:not(.d-none)').forEach(function (p) {
            p.style.opacity = '1';
        });
    })();
    </script>
</body>
</html>