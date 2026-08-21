@include('admin.head')

@php
    $user = $personal;
@endphp

<style>
    :root {
        --surface-muted: #f8fafc;
        --border-soft: #e2e8f0;
        --text-main: #1e293b;
        --text-soft: #64748b;
        --blue-deep: #0f2c73;
        --paper: #ffffff;
        --paper-soft: #f6f8fc;
        --surface: #ffffff;
        --surface-soft: #f8fafc;
        --shadow-soft: 0 20px 45px rgba(15, 23, 42, 0.08);
        --success-soft: rgba(22, 163, 74, 0.12);
        --warning-soft: rgba(245, 158, 11, 0.14);
        --danger-soft: rgba(220, 38, 38, 0.12);
        --ink: #0f172a;
        --ink-soft: #475569;
        --line: #e2e8f0;
        --shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
    }

    .personal-shell {
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

    .balance-display-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .balance-display-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.12) !important;
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
                    <div class="personal-shell">

                        <!-- Hero Banner -->
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
                                                    <i class="fa-solid fa-user"></i>
                                                    Personal Account Statement Dashboard
                                                </div>
                                                <h2 class="mb-1 text-white">{{ $user->firstname . ' ' . $user->lastname }}</h2>
                                                <p class="mb-0 text-white-50">{{ $user->email }}</p>
                                            </div>
                                        </div>

                                        <p class="text-white-50 mb-0" style="max-width: 720px; line-height: 1.8;">
                                            Generate and print full account statements for any active currency balance wallet belonging to this personal user.
                                        </p>
                                    </div>

                                    <div class="col-lg-4 mt-4 mt-lg-0">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-mini">
                                                    <small>User ID</small>
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
                                                    <small>Phone Number</small>
                                                    <strong>{{ $user->person_phone ?? $user->phone ?? 'Not provided' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Balances & Wallets Section -->
                        <section class="section-card">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <div class="section-title d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-wallet text-primary"></i> Account Balances & Wallets
                                    </div>
                                    <div class="section-subtitle mb-0">Overview of active user-added balances and currency wallets.</div>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 fw-bold" style="background: rgba(37, 99, 235, 0.1);">
                                    {{ $user->balances ? $user->balances->count() : 0 }} Total Wallets
                                </span>
                            </div>

                            <div class="row g-4 mt-1">
                                @forelse($user->balances ?? [] as $bal)
                                    @php
                                        $flagGradients = [
                                            'NGN' => 'linear-gradient(135deg, #059669 0%, #047857 100%)',
                                            'USD' => 'linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%)',
                                            'EUR' => 'linear-gradient(135deg, #4f46e5 0%, #3730a3 100%)',
                                            'GBP' => 'linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%)',
                                            'KES' => 'linear-gradient(135deg, #d97706 0%, #b45309 100%)',
                                            'GHS' => 'linear-gradient(135deg, #e11d48 0%, #be123c 100%)',
                                            'CAD' => 'linear-gradient(135deg, #dc2626 0%, #991b1b 100%)',
                                            'ZAR' => 'linear-gradient(135deg, #0d9488 0%, #0f766e 100%)',
                                        ];
                                        $gradient = $flagGradients[strtoupper($bal->currency)] ?? 'linear-gradient(135deg, #475569 0%, #1e293b 100%)';
                                    @endphp
                                    <div class="col-md-6 col-xl-4">
                                        <div class="card h-70 border-0 shadow-sm balance-display-card" style="border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; overflow: hidden;">
                                            <!-- Card Header Strip -->
                                            <div class="p-3 text-white d-flex align-items-center justify-content-between" style="background: {{ $gradient }};">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold bg-white text-dark shadow-sm" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                                        {{ strtoupper(substr($bal->currency, 0, 3)) }}
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.95rem;">{{ $bal->name ?? ($bal->currency . ' Wallet') }}</h6>
                                                        <span class="text-white-50" style="font-size: 0.75rem;">{{ strtoupper($bal->currency) }}</span>
                                                    </div>
                                                </div>
                                                @if($bal->is_locked)
                                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                        <i class="fa-solid fa-lock me-1"></i> Locked
                                                    </span>
                                                @else
                                                    <span class="badge bg-white text-dark rounded-pill px-2 py-1 fw-bold" style="font-size: 0.7rem;">
                                                        <i class="fa-solid fa-circle-check text-success me-1"></i> Active
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Card Body -->
                                            <div class="card-body p-4 d-flex flex-column justify-content-between" style="background-color: #ffffff;">
                                                <div class="mb-3 p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                                                    <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Current Balance</small>
                                                    <div class="h3 fw-bold text-dark mb-0" style="letter-spacing: -0.02em;">
                                                        <span class="text-primary me-1" style="font-size: 1.1rem;">{{ $bal->currency }}</span>{{ number_format($bal->amount, 2) }}
                                                    </div>
                                                </div>

                                                <div class="pt-2 border-top d-flex justify-content-between align-items-center text-muted" style="font-size: 0.82rem;">
                                                    @if($bal->virtual_account_number)
                                                        <span class="text-truncate me-2"><i class="fa-solid fa-building-columns me-1 text-primary"></i>{{ $bal->virtual_account_bank ?? 'Virtual Bank' }}</span>
                                                        <span class="font-monospace fw-bold text-dark">{{ $bal->virtual_account_number }}</span>
                                                    @else
                                                        <span><i class="fa-solid fa-shield-halved me-1 text-secondary"></i> Mode</span>
                                                        <span class="badge bg-light text-dark fw-bold border text-uppercase" style="font-size: 0.72rem;">{{ $bal->mode ?? 'Live' }}</span>
                                                    @endif
                                                </div>

                                                @if($bal->is_locked && $bal->locked_reason)
                                                    <div class="mt-2 p-2 rounded-2 bg-danger-subtle text-danger small" style="font-size: 0.78rem;">
                                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> <strong>Reason:</strong> {{ $bal->locked_reason }}
                                                    </div>
                                                @endif

                                                <div class="mt-3 pt-3 border-top">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary btn-sm w-100 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 py-2 print-balance-statement-btn"
                                                            data-balance-id="{{ $bal->id }}"
                                                            data-balance-name="{{ $bal->name ?? ($bal->currency . ' Wallet') }}"
                                                            data-currency="{{ strtoupper($bal->currency) }}"
                                                            data-amount="{{ number_format($bal->amount, 2) }}">
                                                        <i class="fa-solid fa-print"></i> Print Statement
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5 px-4 rounded-4" style="background-color: #ffffff; border: 2px dashed #cbd5e1;">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-muted mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                                <i class="fa-solid fa-wallet"></i>
                                            </div>
                                            <h5 class="fw-bold text-dark mb-1">No Balances Found</h5>
                                            <p class="text-muted small mb-0">This personal user has not created any currency wallets yet.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Statement Modal -->
    <div class="modal fade" id="printStatementModal" tabindex="-1" aria-labelledby="printStatementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered shadow-none">
            <div class="modal-content border-0 shadow-none" style="border-radius: 24px; overflow: hidden;">
                <div class="modal-header border-0 text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 44px; height: 44px; font-size: 1.2rem;">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="printStatementModalLabel">Print Account Statement</h5>
                            <small class="text-white-50" id="modalWalletInfo">Generate PDF or printable statement for this wallet</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="printStatementForm" method="GET" action="" target="_blank">
                    <div class="modal-body p-4" style="background-color: #f8fafc;">
                        
                        <!-- Wallet Details Summary Box -->
                        <div class="p-3 mb-4 rounded-3 border bg-white d-flex align-items-center justify-content-between shadow-sm">
                            <div>
                                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">Selected Wallet</small>
                                <span class="fw-bold text-dark fs-6" id="modalWalletName">Wallet</span>
                            </div>
                            <div class="text-end">
                                <small class="text-muted fw-bold text-uppercase d-block mb-1" style="font-size: 0.68rem; letter-spacing: 0.05em;">Current Balance</small>
                                <span class="fw-bold text-primary fs-6" id="modalWalletBalance">0.00</span>
                            </div>
                        </div>

                        <!-- Quick Range Presets -->
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold small text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Quick Presets</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-sm btn-white border rounded-pill fw-semibold preset-btn px-3" data-days="30">Last 30 Days</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill fw-semibold preset-btn px-3" data-days="90">Last 90 Days</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill fw-semibold preset-btn px-3" data-preset="this_month">This Month</button>
                                <button type="button" class="btn btn-sm btn-white border rounded-pill fw-semibold preset-btn px-3" data-preset="this_year">This Year</button>
                            </div>
                        </div>

                        <!-- Custom Date Selection -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="statement_start_date" class="form-label fw-semibold text-dark small">Start Date</label>
                                <input type="date" class="form-control form-control-lg border-1 bg-white" id="statement_start_date" name="start_date" required style="border-radius: 12px; font-size: 0.9rem;">
                            </div>
                            <div class="col-md-6">
                                <label for="statement_end_date" class="form-label fw-semibold text-dark small">End Date</label>
                                <input type="date" class="form-control form-control-lg border-1 bg-white" id="statement_end_date" name="end_date" required style="border-radius: 12px; font-size: 0.9rem;">
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer border-top bg-white p-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-print"></i> Generate & Print Statement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Print Statement Modal trigger logic
        const printBtns = document.querySelectorAll('.print-balance-statement-btn');
        const modalEl = document.getElementById('printStatementModal');
        if (modalEl) {
            const statementModal = new bootstrap.Modal(modalEl);
            const form = document.getElementById('printStatementForm');
            const modalWalletName = document.getElementById('modalWalletName');
            const modalWalletBalance = document.getElementById('modalWalletBalance');
            const startDateInput = document.getElementById('statement_start_date');
            const endDateInput = document.getElementById('statement_end_date');

            function formatDate(date) {
                const d = new Date(date);
                let month = '' + (d.getMonth() + 1);
                let day = '' + d.getDate();
                const year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            }

            function setDefaultDates() {
                const today = new Date();
                const thirtyDaysAgo = new Date();
                thirtyDaysAgo.setDate(today.getDate() - 30);

                endDateInput.value = formatDate(today);
                startDateInput.value = formatDate(thirtyDaysAgo);
            }

            printBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    const balanceId = this.dataset.balanceId;
                    const balanceName = this.dataset.balanceName;
                    const currency = this.dataset.currency;
                    const amount = this.dataset.amount;

                    form.action = `/admin/balance/${balanceId}/statement`;
                    modalWalletName.textContent = `${balanceName}`;
                    modalWalletBalance.textContent = `${currency} ${amount}`;

                    setDefaultDates();
                    statementModal.show();
                });
            });

            // Preset buttons logic
            const presetBtns = document.querySelectorAll('.preset-btn');
            presetBtns.forEach(pBtn => {
                pBtn.addEventListener('click', function () {
                    const today = new Date();
                    let start = new Date();

                    if (this.dataset.days) {
                        const days = parseInt(this.dataset.days, 10);
                        start.setDate(today.getDate() - days);
                    } else if (this.dataset.preset === 'this_month') {
                        start = new Date(today.getFullYear(), today.getMonth(), 1);
                    } else if (this.dataset.preset === 'this_year') {
                        start = new Date(today.getFullYear(), 0, 1);
                    }

                    startDateInput.value = formatDate(start);
                    endDateInput.value = formatDate(today);
                });
            });
        }
    });
</script>

@include('admin.footer')