@include('admin.head')

<style>
    :root {
        --bf-ink: #14213d;
        --bf-ink-soft: #64748b;
        --bf-paper: #ffffff;
        --bf-paper-soft: #f8fafc;
        --bf-line: #e2e8f0;
        --bf-blue: #2563eb;
        --bf-blue-deep: #0f2c73;
        --bf-cyan: #0891b2;
        --bf-green: #16a34a;
        --bf-amber: #d97706;
        --bf-shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .beneficia-page {
        padding-bottom: 32px;
    }

    .beneficia-hero {
        border: 0;
        border-radius: 32px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(8,145,178,0.15), transparent 30%),
            linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
        box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }

    .beneficia-hero .card-body {
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

    .hero-metric small {
        display: block;
        color: rgba(255,255,255,0.70);
        margin-bottom: 6px;
    }

    .hero-metric strong {
        font-size: 1.15rem;
        font-weight: 800;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin: 24px 0;
    }

    .summary-card {
        background: var(--bf-paper);
        border: 1px solid var(--bf-line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--bf-shadow);
    }

    .summary-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .icon-blue { background: rgba(37, 99, 235, 0.12); color: var(--bf-blue); }
    .icon-green { background: rgba(22, 163, 74, 0.12); color: var(--bf-green); }
    .icon-amber { background: rgba(217, 119, 6, 0.12); color: var(--bf-amber); }
    .icon-cyan { background: rgba(8, 145, 178, 0.12); color: var(--bf-cyan); }

    .summary-label {
        font-size: 13px;
        color: var(--bf-ink-soft);
        margin-bottom: 6px;
    }

    .summary-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--bf-ink);
        line-height: 1.1;
    }

    .beneficia-card {
        border: 0;
        border-radius: 28px;
        overflow: hidden;
        background: var(--bf-paper);
        box-shadow: var(--bf-shadow);
    }

    .beneficia-card .card-header {
        border: 0;
        padding: 22px 24px;
        background: linear-gradient(180deg, #ffffff, #f9fbff);
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--bf-ink);
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: var(--bf-ink-soft);
        font-size: 13px;
        margin-bottom: 0;
    }

    .search-toolbar {
        padding: 0 24px 20px;
    }

    .search-input {
        max-width: 360px;
        height: 46px;
        border-radius: 15px !important;
        border: 1px solid #dbe3ee !important;
        box-shadow: none !important;
    }

    .search-input:focus {
        border-color: var(--bf-blue) !important;
        box-shadow: 0 0 0 0.18rem rgba(37, 99, 235, 0.10) !important;
    }

    .table-modern thead th {
        background: #fbfcff;
        color: var(--bf-ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--bf-line);
        padding: 16px 18px;
        white-space: nowrap;
    }

    .table-modern tbody td {
        padding: 18px;
        border-top: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-modern tbody tr:hover {
        background: #fbfdff;
    }

    .acct-name {
        font-weight: 800;
        color: var(--bf-ink);
    }

    .muted-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        background: rgba(37, 99, 235, 0.12);
        color: var(--bf-blue);
    }

    .bank-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(8, 145, 178, 0.12);
        color: var(--bf-cyan);
        font-size: 12px;
        font-weight: 700;
    }

    .empty-row {
        padding: 48px 24px !important;
        text-align: center;
        color: var(--bf-ink-soft);
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
                    <div class="beneficia-page">

                        <div class="card beneficia-hero mb-4">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-tag">
                                            <i class="fa-solid fa-id-card-clip"></i>
                                            Beneficiary Directory
                                        </div>
                                        <h1 class="hero-title">Beneficia</h1>
                                        <p class="hero-copy">
                                            Review all beneficiary records in one place, search quickly, and inspect linked banking details including account name, bank, account number, country, currency, and account type.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Total Records</small>
                                                    <strong>{{ method_exists($allbeneficia, 'total') ? $allbeneficia->total() : $allbeneficia->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-metric">
                                                    <small>Showing</small>
                                                    <strong>{{ $allbeneficia->count() }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-metric">
                                                    <small>Live Search</small>
                                                    <strong id="searchMeta">No filter applied</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="summary-grid">
                            <div class="summary-card">
                                <div class="summary-icon icon-blue">
                                    <i class="fa-solid fa-id-card-clip"></i>
                                </div>
                                <div class="summary-label">All Beneficia</div>
                                <div class="summary-value">{{ method_exists($allbeneficia, 'total') ? $allbeneficia->total() : $allbeneficia->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-cyan">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="summary-label">Visible Rows</div>
                                <div class="summary-value" id="visibleCount">{{ $allbeneficia->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-green">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="summary-label">Currencies</div>
                                <div class="summary-value">{{ collect($allbeneficia)->pluck('currency')->filter()->unique()->count() }}</div>
                            </div>

                            <div class="summary-card">
                                <div class="summary-icon icon-amber">
                                    <i class="fa-solid fa-earth-americas"></i>
                                </div>
                                <div class="summary-label">Countries</div>
                                <div class="summary-value">{{ collect($allbeneficia)->pluck('country')->filter()->unique()->count() }}</div>
                            </div>
                        </div>

                        <div class="beneficia-card">
                            <div class="card-header">
                                <div class="section-title">All Beneficia</div>
                                <div class="section-subtitle">Search and review beneficiary account records below.</div>
                            </div>

                            <div class="search-toolbar">
                                <input type="text" id="transactionSearch" class="form-control search-input" placeholder="Search account name, bank, account number, country, currency, or type...">
                            </div>

                            <div class="table-responsive" id="beneficiaTable">
                                <table class="table table-modern align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Account Name</th>
                                            <th class="text-center">Bank</th>
                                            <th class="text-center">Account Number</th>
                                            <th class="text-center">Country</th>
                                            <th class="text-center">Currency</th>
                                            <th class="text-center">Type</th>
                                        </tr>
                                    </thead>
                                    <tbody id="beneficiaTableBody">
                                        @forelse ($allbeneficia as $b)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="acct-name">{{ $b->account_name }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="bank-pill">{{ $b->bank }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="muted-pill">{{ $b->account_number }}</span>
                                                </td>
                                                <td class="text-center">{{ $b->country }}</td>
                                                <td class="text-center">{{ strtoupper($b->currency) }}</td>
                                                <td class="text-center">{{ $b->type }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="empty-row">No beneficiary records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="p-4 border-top">
                                    {{ $allbeneficia->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('transactionSearch');
    const tableBody = document.getElementById('beneficiaTableBody');
    const rows = tableBody.querySelectorAll('tr');
    const visibleCount = document.getElementById('visibleCount');
    const searchMeta = document.getElementById('searchMeta');

    function updateVisibleCount() {
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none').length;
        visibleCount.textContent = visibleRows;
    }

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase().trim();
        searchMeta.textContent = query ? query : 'No filter applied';

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll('td'));
            const match = cells.some(td => td.textContent.toLowerCase().includes(query));
            row.style.display = match ? '' : 'none';
        });

        updateVisibleCount();
    });

    updateVisibleCount();
});
</script>
