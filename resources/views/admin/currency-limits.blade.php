@include('admin.head')
<style>
    :root {
        --ink: #0f172a;
        --ink-soft: #64748b;
        --paper: #ffffff;
        --paper-soft: #f8fafc;
        --line: #e2e8f0;

        --blue: #1d4ed8;
        --blue-deep: #1e3a8a;
        --cyan: #0891b2;
        --green: #16a34a;
        --red: #dc2626;
        --amber: #d97706;

        --shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.06);
        --shadow-main: 0 18px 50px rgba(15, 23, 42, 0.10);
    }

    .limits-page { padding: 20px 20px 36px; }

    .hero {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background:
            radial-gradient(circle at 90% 12%, rgba(255,255,255,.24), transparent 22%),
            radial-gradient(circle at 10% 82%, rgba(14,165,233,.18), transparent 28%),
            linear-gradient(130deg, #0b132b 0%, #1d4ed8 55%, #0ea5e9 100%);
        box-shadow: var(--shadow-main);
        position: relative;
    }

    .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,0));
        pointer-events: none;
    }

    .hero .card-body { padding: 32px; position: relative; z-index: 2; }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .hero-title {
        color: #fff;
        margin: 14px 0 8px;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .hero-copy {
        color: rgba(255,255,255,.86);
        line-height: 1.7;
        max-width: 740px;
        margin: 0;
    }

    .metric {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.16);
        border-radius: 16px;
        padding: 14px 15px;
        color: #fff;
        backdrop-filter: blur(4px);
    }
    .metric small { opacity: .8; font-size: 12px; display: block; margin-bottom: 3px; }
    .metric strong { font-size: 22px; font-weight: 800; }

    .main-card {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: var(--paper);
        box-shadow: var(--shadow-soft);
    }

    .main-card-head {
        padding: 22px 24px;
        border-bottom: 1px solid var(--line);
        background: linear-gradient(180deg, #fff, #f8fbff);
    }

    .main-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--ink);
        margin: 0 0 3px;
    }

    .main-subtitle {
        margin: 0;
        color: var(--ink-soft);
        font-size: 13px;
    }

    .quick-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eef4ff;
        color: #1e40af;
        font-weight: 700;
        font-size: 12px;
        margin-left: 8px;
    }

    .table-wrap { max-height: 72vh; overflow: auto; }

    .limits-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
        background: #f8fafc;
        color: var(--ink-soft);
        border-bottom: 1px solid var(--line);
        font-size: 11px;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-weight: 800;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .limits-table tbody td {
        border-top: 1px solid #f1f5f9;
        padding: 16px;
        vertical-align: middle;
        background: #fff;
    }

    .limits-table tbody tr:hover td { background: #fbfdff; }

    .currency-code {
        font-weight: 800;
        font-size: 16px;
        color: var(--ink);
        line-height: 1.2;
    }
    .currency-name {
        color: var(--ink-soft);
        font-size: 12px;
    }

    .money-input {
        border-radius: 12px !important;
        border: 1px solid #dbe5f2 !important;
        min-height: 42px;
        font-weight: 600;
        background: #fff;
    }
    .money-input:focus {
        border-color: #93c5fd !important;
        box-shadow: 0 0 0 4px rgba(59,130,246,.12) !important;
    }

    .status-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
    }

    .active-chip {
        color: #166534;
        background: rgba(22,163,74,.14);
    }
    .active-chip .dot { background: #16a34a; }

    .inactive-chip {
        color: #991b1b;
        background: rgba(220,38,38,.14);
    }
    .inactive-chip .dot { background: #dc2626; }

    .select-modern {
        border-radius: 12px !important;
        border: 1px solid #dbe5f2 !important;
        min-height: 42px;
        font-weight: 600;
    }

    .btn-save {
        border: 0;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 800;
        font-size: 12px;
        letter-spacing: .03em;
        background: linear-gradient(135deg, var(--blue), var(--blue-deep));
        color: #fff;
        box-shadow: 0 8px 20px rgba(29,78,216,.28);
        transition: .2s ease;
    }

    .btn-save:hover {
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    .empty-state {
        padding: 56px 20px !important;
        text-align: center;
        color: var(--ink-soft);
        font-weight: 600;
    }

    @media (max-width: 991px) {
        .hero-title { font-size: 1.65rem; }
        .hero .card-body { padding: 24px; }
        .limits-page { padding: 14px; }
    }
</style>

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    @include('admin.header')
    @include('admin.ui-setting')

    <div class="app-main MainAnimation-appear">
        @include('admin.sidebar')

        <div class="app-main__outer">
            <div class="limits-page">

                <div class="card hero mb-4">
                    <div class="card-body">
                        <div class="row align-items-end g-4">
                            <div class="col-lg-8">
                                <div class="hero-tag">
                                    <i class="fa-solid fa-money-bill"></i>
                                    Transfer Governance
                                </div>
                                <h1 class="hero-title">Currency Limits</h1>
                                <p class="hero-copy">
                                    Define safe send boundaries per currency. Control minimum and maximum transaction amounts and switch each currency on/off in real time.
                                </p>
                            </div>

                            <div class="col-lg-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="metric">
                                            <small>Total</small>
                                            <strong>{{ $currencies->count() }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="metric">
                                            <small>Active</small>
                                            <strong>{{ $currencies->where('is_active', true)->count() }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="metric">
                                            <small>Inactive</small>
                                            <strong>{{ $currencies->where('is_active', false)->count() }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card main-card">
                    <div class="main-card-head d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="main-title">Manage Per-Currency Limits</h5>
                            <p class="main-subtitle">Each row saves independently. Keep values practical and realistic for fraud/risk controls.</p>
                        </div>
                        <div>
                            <span class="quick-pill"><i class="pe-7s-check"></i> Instant Update</span>
                            <span class="quick-pill"><i class="pe-7s-shield"></i> Risk Friendly</span>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="table limits-table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width:190px;">Currency</th>
                                    <th style="min-width:170px;">Min Amount</th>
                                    <th style="min-width:170px;">Max Amount</th>
                                    <th style="min-width:170px;">Collection Fee</th>
                                    <th style="min-width:180px;">Status</th>
                                    <th class="text-end" style="min-width:120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($currencies as $currency)
                                    <tr>
                                        <form method="POST" action="{{ route('admin.currency.limits.update', $currency->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <td>
                                                <div class="currency-code">{{ $currency->code }}</div>
                                                <div class="currency-name">{{ $currency->name }}</div>
                                            </td>

                                            <td>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="min_amount"
                                                    class="form-control money-input"
                                                    value="{{ old('min_amount', $currency->min_amount) }}"
                                                    required
                                                >
                                            </td>

                                            <td>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="max_amount"
                                                    class="form-control money-input"
                                                    value="{{ old('max_amount', $currency->max_amount) }}"
                                                    required
                                                >
                                            </td>

                                            <td>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    name="collection_fee"
                                                    class="form-control money-input"
                                                    value="{{ old('collection_fee', $currency->collection_fee) }}"
                                                >
                                            </td>

                                            <td>
                                                <select name="is_active" class="form-select select-modern mb-2">
                                                    <option value="1" {{ $currency->is_active ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !$currency->is_active ? 'selected' : '' }}>Inactive</option>
                                                </select>

                                                @if($currency->is_active)
                                                    <span class="status-chip active-chip"><span class="dot"></span> Enabled</span>
                                                @else
                                                    <span class="status-chip inactive-chip"><span class="dot"></span> Disabled</span>
                                                @endif
                                            </td>

                                            <td class="text-end">
                                                <button class="btn-save">Save</button>
                                            </td>
                                        </form>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">No currencies available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('admin.footer')
</body>
