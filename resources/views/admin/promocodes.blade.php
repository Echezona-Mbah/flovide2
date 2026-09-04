@include('admin.head')

<style>
    :root {
        --ink: #14213d;
        --ink-soft: #5b6475;
        --paper: #ffffff;
        --paper-soft: #f6f8fc;
        --line: #e7ecf3;
        --blue: #1d4ed8;
        --blue-deep: #0f2c73;
        --cyan: #0ea5e9;
        --green: #16a34a;
        --red: #dc2626;
        --amber: #d97706;
        --purple: #7c3aed;
        --shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .business-page { padding-bottom: 32px; }

    .business-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(124,58,237,0.18), transparent 30%),
            linear-gradient(135deg, #1a0c30 0%, #4c1d95 52%, #7c3aed 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }

    .business-hero .card-body { padding: 34px; }

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
        letter-spacing: .02em;
    }

    .hero-title {
        color: #fff;
        font-size: 2rem;
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

    .hero-metric small { display: block; color: rgba(255,255,255,0.70); margin-bottom: 6px; }
    .hero-metric strong { font-size: 1.2rem; font-weight: 800; }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin: 24px 0;
    }

    .stat-box {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--shadow);
    }

    .stat-icon {
        width: 54px; height: 54px;
        border-radius: 18px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .stat-blue { background: rgba(29, 78, 216, 0.12); color: var(--blue); }
    .stat-green { background: rgba(22, 163, 74, 0.12); color: var(--green); }
    .stat-red { background: rgba(220, 38, 38, 0.12); color: var(--red); }
    .stat-purple { background: rgba(124, 58, 237, 0.12); color: var(--purple); }

    .stat-label { font-size: 13px; color: var(--ink-soft); margin-bottom: 6px; }
    .stat-value { font-size: 26px; font-weight: 800; color: var(--ink); line-height: 1.1; }

    .directory-card, .notification-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--paper);
        box-shadow: var(--shadow);
    }

    .directory-head, .notification-head {
        padding: 24px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .directory-title, .notification-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        margin-bottom: 4px;
    }

    .directory-subtitle, .notification-subtitle {
        color: var(--ink-soft);
        margin-bottom: 0;
        font-size: 13px;
    }

    .btn-brand, .btn-soft, .btn-action {
        border: 0;
        border-radius: 14px;
        font-weight: 700;
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--purple), #5b21b6);
        color: #fff;
        padding: 11px 16px;
    }

    .btn-soft { background: #eef3fa; color: #334155; padding: 11px 16px; }
    .btn-action { padding: 8px 11px; }

    .btn-view { background: rgba(29, 78, 216, 0.12); color: var(--blue); }
    .btn-toggle { background: rgba(100, 116, 139, 0.12); color: #475569; }
    .btn-delete { background: rgba(220, 38, 38, 0.12); color: var(--red); }
    .btn-copy { background: rgba(124, 58, 237, 0.12); color: var(--purple); }

    .business-table thead th {
        background: #fbfcff;
        color: var(--ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--line);
        padding: 16px 18px;
        white-space: nowrap;
    }

    .business-table tbody td { padding: 18px; border-top: 1px solid #f1f5f9; vertical-align: middle; }
    .business-table tbody tr:hover { background: #fbfdff; }

    .merchant-cell { display: flex; align-items: center; gap: 12px; }

    .merchant-avatar {
        width: 46px; height: 46px;
        border-radius: 16px;
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 800;
        font-size: 15px;
        color: #fff;
        flex-shrink: 0;
    }

    .avatar-business { background: linear-gradient(135deg, #1d4ed8, #0f2c73); }
    .avatar-personal { background: linear-gradient(135deg, #0ea5e9, #0369a1); }

    .merchant-name { font-weight: 800; color: var(--ink); line-height: 1.2; }
    .merchant-meta { font-size: 12px; color: var(--ink-soft); margin-top: 3px; }

    .promo-code-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f6f4ff;
        border: 1px dashed #c4b5fd;
        border-radius: 12px;
        padding: 7px 12px;
        font-family: 'Courier New', monospace;
        font-weight: 800;
        letter-spacing: .05em;
        color: var(--purple);
        font-size: 13px;
    }

    .promo-code-pill button {
        border: 0; background: transparent; color: var(--purple);
        cursor: pointer; font-size: 12px;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-active { background: rgba(22, 163, 74, 0.12); color: var(--green); }
    .status-inactive { background: rgba(220, 38, 38, 0.12); color: var(--red); }

    .owner-type-chip {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .owner-business { background: rgba(29, 78, 216, 0.10); color: var(--blue); }
    .owner-personal { background: rgba(14, 165, 233, 0.10); color: var(--cyan); }

    .actions { display: inline-flex; gap: 8px; justify-content: flex-end; flex-wrap: wrap; }
    .empty-row { padding: 48px 24px !important; text-align: center; color: var(--ink-soft); }

    .filter-bar { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    .filter-search { position: relative; display: flex; align-items: center; min-width: 280px; }

    .filter-search-icon {
        position: absolute; left: 16px;
        font-size: 13px; color: var(--ink-soft);
        pointer-events: none;
    }

    .filter-search-input {
        width: 100%; height: 46px;
        border: 1px solid var(--line);
        border-radius: 15px;
        background: #fbfcff;
        padding: 0 40px 0 40px;
        font-size: 14px;
        color: var(--ink);
        transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }

    .filter-search-input::placeholder { color: var(--ink-soft); }

    .filter-search-input:focus {
        outline: none;
        border-color: var(--purple);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08);
    }

    .filter-search-clear {
        position: absolute; right: 12px;
        width: 22px; height: 22px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--ink-soft);
        font-size: 11px;
        background: #eef1f6;
        transition: all 0.2s ease;
    }

    .filter-search-clear:hover { background: var(--red); color: #fff; }

    .filter-select-wrap { position: relative; display: flex; align-items: center; }

    .filter-select {
        appearance: none;
        height: 46px;
        border: 1px solid var(--line);
        border-radius: 15px;
        background: #fbfcff;
        padding: 0 34px 0 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--ink);
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .filter-select:hover { border-color: var(--purple); background: #fff; }
    .filter-select:focus { outline: none; border-color: var(--purple); box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.08); }

    .filter-select-icon { position: absolute; right: 14px; font-size: 10px; color: var(--ink-soft); pointer-events: none; }

    .filter-submit {
        display: inline-flex; align-items: center; gap: 8px;
        height: 46px; padding: 0 20px;
        border: 0; border-radius: 15px;
        background: linear-gradient(135deg, var(--purple), #5b21b6);
        color: #fff;
        font-size: 13.5px; font-weight: 700;
        box-shadow: 0 8px 18px rgba(124, 58, 237, 0.18);
        transition: opacity 0.2s ease, transform 0.15s ease;
    }

    .filter-submit:hover { opacity: 0.92; }
    .filter-submit:active { transform: scale(0.98); }

    .form-label-strong { color: var(--ink); font-weight: 700; }

    .form-control-pill {
        border-radius: 15px !important;
        border-color: #dbe3ee !important;
        height: 46px;
    }

    .form-control-pill:focus {
        border-color: var(--purple) !important;
        box-shadow: 0 0 0 0.18rem rgba(124, 58, 237, 0.10) !important;
    }

    @media (max-width: 767px) {
        .filter-search { min-width: 100%; }
        .filter-bar { width: 100%; }
        .filter-select-wrap, .filter-submit { flex: 1; }
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
                    <div class="business-page">

                        <!-- Hero -->
                        <div class="card business-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-ticket"></i>
                                            Promo Code Management
                                        </div>
                                        <h1 class="hero-title">Promo Codes</h1>
                                        <p class="hero-copy">
                                            Generate referral promo codes for business and personal accounts, track redemptions, and manage code status from one place.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Codes</small>
                                                    <strong>{{ $promoCodes->total() ?? $promoCodes->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Showing</small>
                                                    <strong>{{ $promoCodes->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Current Filter</small>
                                                    <strong>{{ $search ?: 'All promo codes' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="stats-row">
                            <div class="stat-box">
                                <div class="stat-icon stat-purple"><i class="fa-solid fa-ticket"></i></div>
                                <div class="stat-label">Total Promo Codes</div>
                                <div class="stat-value">{{ $promoCodes->total() ?? $promoCodes->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-green"><i class="fa-solid fa-circle-check"></i></div>
                                <div class="stat-label">Active Codes</div>
                                <div class="stat-value">{{ $promoCodes->where('status', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-red"><i class="fa-solid fa-circle-xmark"></i></div>
                                <div class="stat-label">Inactive Codes</div>
                                <div class="stat-value">{{ $promoCodes->where('status', '!=', 'active')->count() }}</div>
                            </div>

                            <div class="stat-box">
                                <div class="stat-icon stat-blue"><i class="fa-solid fa-coins"></i></div>
                                <div class="stat-label">Total Redemptions</div>
                                <div class="stat-value">{{ $promoCodes->sum('redemption_count') }}</div>
                            </div>
                        </div>

                        @if(session('success'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: @json(session('success')),
                                        confirmButtonColor: '#7c3aed'
                                    });
                                });
                            </script>
                        @endif

                        @if ($errors->any())
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Something went wrong',
                                        text: @json($errors->first()),
                                        confirmButtonColor: '#dc2626'
                                    });
                                });
                            </script>
                        @endif

                        <!-- Generate Promo Code -->
                        <section class="mb-4">
                            <div class="card notification-card">
                                <div class="notification-head">
                                    <h5 class="notification-title"><i class="fa-solid fa-plus text-primary me-2" style="color: var(--purple) !important;"></i>Generate New Promo Code</h5>
                                    <p class="notification-subtitle">Create a referral promo code for a business or personal account by their registered email.</p>
                                </div>
                                <div class="card-body p-4">
                                    <form method="POST" action="{{ route('admin.promocodes.store') }}">
                                        @csrf

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label form-label-strong">Owner Type</label>
                                                <select name="owner_type" class="form-select form-control-pill" required>
                                                    <option value="">Select type</option>
                                                    <option value="business" {{ old('owner_type') === 'business' ? 'selected' : '' }}>Business</option>
                                                    <option value="personal" {{ old('owner_type') === 'personal' ? 'selected' : '' }}>Personal</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label form-label-strong">Owner Email</label>
                                                <input type="email" name="owner_email" value="{{ old('owner_email') }}"
                                                    class="form-control form-control-pill" placeholder="owner@example.com" required>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label form-label-strong">Reward Type</label>
                                                <select name="reward_type" id="rewardType" class="form-select form-control-pill" required>
                                                    <option value="">Select</option>
                                                    <option value="percent" {{ old('reward_type') === 'percent' ? 'selected' : '' }}>Percent %</option>
                                                    <option value="fixed" {{ old('reward_type') === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label form-label-strong">Reward Value</label>
                                                <input type="number" step="0.01" min="0" name="reward_value" id="rewardValue"
                                                    value="{{ old('reward_value') }}" class="form-control form-control-pill" placeholder="e.g. 10" required>
                                                <small class="text-muted" id="rewardHint"></small>
                                            </div>

                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="submit" class="btn btn-brand w-100" style="height: 46px;" title="Generate">
                                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </section>

                        <!-- Promo Code Table -->
                        <div class="card directory-card">
                            <div class="directory-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <h5 class="directory-title">Promo Codes</h5>
                                    <p class="directory-subtitle">All generated codes with owner, reward, and redemption details.</p>
                                </div>

                                <form method="GET" action="" class="filter-bar">
                                    <div class="filter-search">
                                        <i class="fa fa-magnifying-glass filter-search-icon"></i>
                                        <input
                                            type="text"
                                            name="search"
                                            value="{{ $search }}"
                                            class="filter-search-input"
                                            placeholder="Search promo code...">
                                        @if($search)
                                            <a href="{{ url()->current() }}?per_page={{ $perPage }}" class="filter-search-clear" title="Clear search">
                                                <i class="fa fa-xmark"></i>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="filter-select-wrap">
                                        <select name="per_page" class="filter-select" onchange="this.form.submit()">
                                            @foreach($allowedPerPage as $option)
                                                <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>
                                                    {{ $option }} / page
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fa fa-chevron-down filter-select-icon"></i>
                                    </div>

                                    <button type="submit" class="filter-submit">
                                        <i class="fa fa-magnifying-glass"></i>
                                        <span>Search</span>
                                    </button>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table business-table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th style="min-width:220px;">Owner</th>
                                            <th>Reward</th>
                                            <th class="d-none d-lg-table-cell">Redemptions</th>
                                            <th class="d-none d-lg-table-cell">Total Rewarded</th>
                                            <th>Status</th>
                                            <th class="d-none d-lg-table-cell">Created</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($promoCodes as $promo)
                                            @php
                                                $owner = $promo->owner;
                                                $ownerName = $owner
                                                    ? ($promo->owner_type === 'business'
                                                        ? ($owner->business_name ?? $owner->name ?? 'N/A')
                                                        : trim(($owner->firstname ?? '') . ' ' . ($owner->lastname ?? '')))
                                                    : 'Unknown Owner';
                                                $ownerEmail = $owner->email ?? 'N/A';
                                                $initials = strtoupper(substr($ownerName ?: 'N', 0, 2));
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="promo-code-pill">
                                                        <span>{{ $promo->code }}</span>
                                                        <button type="button" class="copyCode" data-code="{{ $promo->code }}" title="Copy code">
                                                            <i class="fa-regular fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="merchant-cell">
                                                        <div class="merchant-avatar {{ $promo->owner_type === 'business' ? 'avatar-business' : 'avatar-personal' }}">
                                                            {{ $initials }}
                                                        </div>
                                                        <div>
                                                            <div class="merchant-name">{{ $ownerName }}</div>
                                                            <div class="merchant-meta">
                                                                <span class="owner-type-chip {{ $promo->owner_type === 'business' ? 'owner-business' : 'owner-personal' }}">
                                                                    {{ ucfirst($promo->owner_type) }}
                                                                </span>
                                                                {{ $ownerEmail }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    @if($promo->reward_type === 'percent')
                                                        <span class="fw-bold" style="color: var(--purple);">{{ rtrim(rtrim(number_format($promo->reward_value, 2), '0'), '.') }}%</span>
                                                    @else
                                                        <span class="fw-bold" style="color: var(--purple);">{{ number_format($promo->reward_value, 2) }} (fixed)</span>
                                                    @endif
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    <span class="fw-bold">{{ $promo->redemption_count }}</span>
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    {{ number_format($promo->total_rewarded ?? 0, 2) }}
                                                </td>

                                                <td>
                                                    @if($promo->status === 'active')
                                                        <span class="status-chip status-active">Active</span>
                                                    @else
                                                        <span class="status-chip status-inactive">Inactive</span>
                                                    @endif
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    {{ $promo->created_at ? $promo->created_at->format('d M Y H:i') : 'N/A' }}
                                                </td>

                                                <td class="text-end">
                                                    <div class="actions">
                                                        <button
                                                            type="button"
                                                            class="btn btn-action btn-toggle toggleStatus"
                                                            data-id="{{ $promo->id }}"
                                                            data-code="{{ $promo->code }}"
                                                            title="{{ $promo->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                            @if($promo->status === 'active')
                                                                <i class="fa fa-ban"></i>
                                                            @else
                                                                <i class="fa fa-check"></i>
                                                            @endif
                                                        </button>

                                                        <button
                                                            type="button"
                                                            class="btn btn-action btn-delete deletePromo"
                                                            data-id="{{ $promo->id }}"
                                                            data-code="{{ $promo->code }}"
                                                            title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="empty-row">
                                                    No promo codes found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-4 border-top">
                                {{ $promoCodes->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ── Reward value hint (percent vs fixed) ──────────────────────────────
    const rewardType  = document.getElementById('rewardType');
    const rewardHint  = document.getElementById('rewardHint');
    const rewardValue = document.getElementById('rewardValue');

    function updateRewardHint() {
        if (rewardType.value === 'percent') {
            rewardHint.textContent = 'Enter a value between 0 and 100';
            rewardValue.setAttribute('max', '100');
        } else if (rewardType.value === 'fixed') {
            rewardHint.textContent = 'Fixed amount in the owner\'s reward currency';
            rewardValue.removeAttribute('max');
        } else {
            rewardHint.textContent = '';
            rewardValue.removeAttribute('max');
        }
    }

    rewardType?.addEventListener('change', updateRewardHint);
    updateRewardHint();

    // ── Copy promo code to clipboard ───────────────────────────────────────
    document.querySelectorAll('.copyCode').forEach(button => {
        button.addEventListener('click', function () {
            const code = this.dataset.code;
            navigator.clipboard.writeText(code).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `"${code}" copied to clipboard`,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            });
        });
    });

    // ── Toggle status ──────────────────────────────────────────────────────
    document.querySelectorAll('.toggleStatus').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const code = this.dataset.code;

            Swal.fire({
                title: 'Change promo code status?',
                text: `This will activate or deactivate "${code}".`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Continue',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#7c3aed'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/promo-codes/${id}/toggle`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";

                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

    // ── Delete promo code ────────────────────────────────────────────────
    document.querySelectorAll('.deletePromo').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const code = this.dataset.code;

            Swal.fire({
                title: 'Delete promo code?',
                text: `"${code}" will be permanently deleted. This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/promo-codes/${id}`;

                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = "{{ csrf_token() }}";

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });

});
</script>