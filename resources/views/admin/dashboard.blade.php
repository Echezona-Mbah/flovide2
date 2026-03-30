@include('admin.head')

<style>
    :root {
        --dash-ink: #132238;
        --dash-ink-soft: #64748b;
        --dash-paper: #ffffff;
        --dash-paper-soft: #f8fafc;
        --dash-line: #e2e8f0;
        --dash-blue: #2563eb;
        --dash-blue-deep: #0f172a;
        --dash-cyan: #06b6d4;
        --dash-green: #16a34a;
        --dash-red: #dc2626;
        --dash-amber: #d97706;
        --dash-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
    }

    .admin-dashboard {
        padding-bottom: 32px;
    }

    .dash-hero {
        border: 0;
        border-radius: 30px;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.16), transparent 25%),
            radial-gradient(circle at bottom left, rgba(6,182,212,0.14), transparent 30%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 58%, #0891b2 100%);
        box-shadow: 0 26px 60px rgba(37, 99, 235, 0.16);
    }

    .dash-hero .card-body {
        padding: 34px;
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
        color: rgba(255,255,255,0.84);
        line-height: 1.8;
        margin-bottom: 0;
        max-width: 760px;
    }

    .hero-side-card {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 20px;
        padding: 16px 18px;
        color: #fff;
        height: 100%;
    }

    .hero-side-card small {
        display: block;
        color: rgba(255,255,255,0.7);
        margin-bottom: 6px;
    }

    .hero-side-card strong {
        font-size: 1.15rem;
        font-weight: 800;
    }

    .welcome-banner {
        border: 1px solid rgba(37, 99, 235, 0.10);
        background: linear-gradient(180deg, #eff6ff, #f8fbff);
        color: #1e3a8a;
        border-radius: 22px;
        padding: 18px 20px;
        box-shadow: var(--dash-shadow);
        margin: 24px 0;
    }

    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .metric-card {
        background: var(--dash-paper);
        border: 1px solid var(--dash-line);
        border-radius: 24px;
        padding: 22px;
        box-shadow: var(--dash-shadow);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.1);
    }

    .metric-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 14px;
    }

    .metric-blue { background: rgba(37, 99, 235, 0.12); color: var(--dash-blue); }
    .metric-red { background: rgba(220, 38, 38, 0.12); color: var(--dash-red); }
    .metric-amber { background: rgba(217, 119, 6, 0.12); color: var(--dash-amber); }
    .metric-green { background: rgba(22, 163, 74, 0.12); color: var(--dash-green); }

    .metric-label {
        color: var(--dash-ink-soft);
        font-size: 13px;
        margin-bottom: 6px;
    }

    .metric-value {
        color: var(--dash-ink);
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
    }

    .metric-sub {
        color: var(--dash-ink-soft);
        font-size: 12px;
        margin-top: 6px;
    }

    .dashboard-card {
        border: 0;
        border-radius: 28px;
        background: var(--dash-paper);
        box-shadow: var(--dash-shadow);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .dashboard-card .card-header {
        border: 0;
        padding: 22px 24px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
    }

    .dashboard-card .card-body {
        padding: 24px;
    }

    .dashboard-card .card-footer {
        border-top: 1px solid var(--dash-line);
        background: #fff;
        padding: 18px 24px;
    }

    .section-title {
        color: var(--dash-ink);
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .section-subtitle {
        color: var(--dash-ink-soft);
        font-size: 13px;
        margin-bottom: 0;
    }

    .chart-wrap {
        min-height: 350px;
    }

    .income-panel {
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        border: 1px solid var(--dash-line);
        border-radius: 24px;
        padding: 22px;
        height: 100%;
    }

    .income-ring-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 240px;
    }

    .income-meta {
        border-top: 1px solid var(--dash-line);
        padding-top: 16px;
        margin-top: 8px;
    }

    .income-percent {
        font-size: 30px;
        font-weight: 800;
        color: var(--dash-amber);
        line-height: 1;
    }

    .table-modern thead th {
        background: #fbfcff;
        color: var(--dash-ink-soft);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .05em;
        border-bottom: 1px solid var(--dash-line);
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

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .status-success {
        background: rgba(22, 163, 74, 0.12);
        color: var(--dash-green);
    }

    .status-danger {
        background: rgba(220, 38, 38, 0.12);
        color: var(--dash-red);
    }

    .status-warning {
        background: rgba(217, 119, 6, 0.12);
        color: var(--dash-amber);
    }

    .btn-brand {
        background: linear-gradient(135deg, var(--dash-blue), #1d4ed8);
        color: #fff;
        border: 0;
        border-radius: 14px;
        font-weight: 700;
        padding: 11px 18px;
    }

    .btn-brand:hover {
        color: #fff;
        opacity: 0.96;
    }

    .btn-soft-dark {
        background: #111827;
        color: #fff;
        border: 0;
        border-radius: 14px;
        font-weight: 700;
        padding: 11px 18px;
    }

    .btn-soft-dark:hover {
        color: #fff;
        opacity: 0.96;
    }

    .map-card {
        min-height: 320px;
    }

    #map {
        height: 320px;
        width: 100%;
        border-radius: 20px;
        overflow: hidden;
    }

    .transaction-btn {
        border-radius: 12px;
        font-weight: 700;
        padding: 8px 14px;
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

    .modal-body .info-row {
        padding: 12px 0;
        border-bottom: 1px solid var(--dash-line);
    }

    .modal-body .info-row:last-child {
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
                    <div class="admin-dashboard">

                        <div class="card dash-hero">
                            <div class="card-body">
                                <div class="row align-items-end g-4">
                                    <div class="col-lg-8">
                                        <div class="hero-badge">
                                            <i class="lnr-apartment"></i>
                                            Admin Overview
                                        </div>
                                        <h1 class="hero-title">Dashboard</h1>
                                        <p class="hero-copy">
                                            Monitor growth, review recent transaction activity, follow performance trends, and keep an eye on platform health from one clean control center.
                                        </p>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="hero-side-card">
                                                    <small>Total Users</small>
                                                    <strong>{{ $finalTotal }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="hero-side-card">
                                                    <small>Business Users</small>
                                                    <strong>{{ $totalUsers }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="hero-side-card">
                                                    <small>Selected Period</small>
                                                    <select id="custom-inp-top" class="form-select mt-2" style="border-radius:14px;">
                                                        <option>Select period...</option>
                                                        <option>Last Week</option>
                                                        <option>Last Month</option>
                                                        <option>Last Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="welcome-banner">
                            <span class="me-2"><i class="fa fa-question-circle"></i></span>
                            Welcome back, Admin. Wishing you a productive and successful day ahead.
                        </div>

                        <div class="metric-grid">
                            <div class="metric-card">
                                <div class="metric-icon metric-blue">
                                    <i class="pe-7s-users"></i>
                                </div>
                                <div class="metric-label">Total Users</div>
                                <div class="metric-value">{{ $finalTotal }}</div>
                                <div class="metric-sub">Combined business and personal accounts</div>
                            </div>

                            <div class="metric-card">
                                <div class="metric-icon metric-red">
                                    <i class="pe-7s-portfolio"></i>
                                </div>
                                <div class="metric-label">Business Accounts</div>
                                <div class="metric-value">{{ $totalUsers }}</div>
                                <div class="metric-sub">Registered business customers</div>
                            </div>

                            <div class="metric-card">
                                <div class="metric-icon metric-amber">
                                    <i class="pe-7s-user"></i>
                                </div>
                                <div class="metric-label">Personal Accounts</div>
                                <div class="metric-value">{{ $totalPersonals }}</div>
                                <div class="metric-sub">Registered personal customers</div>
                            </div>

                            <div class="metric-card">
                                <div class="metric-icon metric-green">
                                    <i class="pe-7s-graph3"></i>
                                </div>
                                <div class="metric-label">New Employees</div>
                                <div class="metric-value">34</div>
                                <div class="metric-sub">Latest hires across the team</div>
                            </div>
                        </div>

                        @php
                            $dates = $transactions->pluck('date');
                            $totals = $transactions->pluck('total');
                        @endphp

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="dashboard-card">
                                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div>
                                            <div class="section-title">Transaction History Overview</div>
                                            <div class="section-subtitle">Track recent volume trends over time.</div>
                                        </div>
                                        <button class="btn btn-brand">Actions</button>
                                    </div>
                                    <div class="card-body">
                                        <div id="chart-combined" class="chart-wrap"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="dashboard-card h-100">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="section-title">Income</div>
                                            <div class="section-subtitle">Current progress against target.</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="income-panel">
                                            <div id="chart-radial" class="income-ring-wrap"></div>

                                            <div class="income-meta">
                                                <div class="d-flex align-items-center gap-3 mb-3">
                                                    <div class="income-percent">{{ number_format($incomePercent, 0) }}%</div>
                                                    <div class="text-muted">
                                                        Income performance ratio
                                                    </div>
                                                </div>

                                                <div class="progress mb-3" style="height: 8px; border-radius: 999px;">
                                                    <div class="progress-bar bg-warning"
                                                        role="progressbar"
                                                        aria-valuenow="{{ $incomePercent }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100"
                                                        style="width: {{ $incomePercent }}%;">
                                                    </div>
                                                </div>

                                                <div class="text-muted" style="line-height: 1.8;">
                                                    Total Income: ₦{{ number_format($totalIncome, 2) }}<br>
                                                    Target: ₦100,000
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dashboard-card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <div class="section-title">Last Transaction History</div>
                                    <div class="section-subtitle">Most recent transactions across the platform.</div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-modern align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Currency</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lastTransactions as $item)
                                            <tr>
                                                <td class="text-center">{{ $item->sender ?? 'N/A' }}</td>
                                                <td class="text-center">{{ ucfirst($item->type ?? 'N/A') }}</td>
                                                <td class="text-center">
                                                    @if ($item->status == 'success')
                                                        <span class="status-pill status-success">Success</span>
                                                    @elseif ($item->status == 'failed')
                                                        <span class="status-pill status-danger">Failed</span>
                                                    @else
                                                        <span class="status-pill status-warning">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $item->created_at->format('d M Y') }}</td>
                                                <td class="text-center">{{ number_format($item->amount, 2) }}</td>
                                                <td class="text-center">{{ strtoupper($item->currency ?? 'N/A') }}</td>
                                                <td class="text-center">
                                                    <button type="button"
                                                        class="btn btn-brand transaction-btn view-transaction-btn"
                                                        data-bs-toggle="modal" data-bs-target="#transactionModal"
                                                        data-sender="{{ $item->sender }}"
                                                        data-recipient="{{ $item->recipient }}"
                                                        data-amount="{{ number_format($item->amount, 2) }}"
                                                        data-currency="{{ $item->currency }}"
                                                        data-status="{{ $item->status }}"
                                                        data-method="{{ $item->method }}"
                                                        data-reference="{{ $item->reference }}"
                                                        data-type="{{ $item->transaction_type }}"
                                                        data-created="{{ $item->created_at->format('d M Y H:i') }}">
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer text-center">
                                <a href="{{ route('admin.transactionhistory') }}" class="btn btn-soft-dark">
                                    View Transaction History
                                </a>
                            </div>
                        </div>

                        <div class="dashboard-card map-card">
                            <div class="card-header">
                                <div class="section-title">Login Locations</div>
                                <div class="section-subtitle">Recent sign-in activity mapped by location.</div>
                            </div>
                            <div class="card-body">
                                <div id="map"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')

<div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row info-row">
                    <div class="col-md-6"><strong>Sender:</strong> <span id="modal-sender"></span></div>
                    <div class="col-md-6"><strong>Recipient:</strong> <span id="modal-recipient"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Amount:</strong> <span id="modal-amount"></span> <span id="modal-currency"></span></div>
                    <div class="col-md-6"><strong>Status:</strong> <span id="modal-status" class="status-pill"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Method:</strong> <span id="modal-method"></span></div>
                    <div class="col-md-6"><strong>Reference:</strong> <span id="modal-reference"></span></div>
                </div>
                <div class="row info-row">
                    <div class="col-md-6"><strong>Transaction Type:</strong> <span id="modal-type"></span></div>
                    <div class="col-md-6"><strong>Created At:</strong> <span id="modal-created"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        chart: {
            type: 'line',
            height: 350,
            toolbar: { show: true }
        },
        series: [{
            name: "Total Transactions",
            data: @json($totals)
        }],
        xaxis: {
            categories: @json($dates),
            labels: { rotate: -45 }
        },
        stroke: {
            width: 4,
            curve: 'smooth'
        },
        markers: { size: 5 },
        colors: ['#16a34a'],
        grid: {
            borderColor: '#eef2f7'
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart-combined"), options);
    chart.render();

    var radialOptions = {
        chart: {
            type: 'radialBar',
            height: 260
        },
        series: [{{ (float) $incomePercent }}],
        colors: ['#f59e0b'],
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '68%'
                },
                track: {
                    background: '#f1f5f9'
                },
                dataLabels: {
                    name: {
                        show: true,
                        color: '#64748b',
                        offsetY: 20
                    },
                    value: {
                        show: true,
                        fontSize: '26px',
                        fontWeight: 800,
                        color: '#14213d',
                        offsetY: -14,
                        formatter: function (val) {
                            return parseInt(val) + '%';
                        }
                    }
                }
            }
        },
        labels: ['Income']
    };

    var radialChart = new ApexCharts(document.querySelector("#chart-radial"), radialOptions);
    radialChart.render();
</script>

<script>
    var map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var locations = @json($loginLocations);
    var markers = [];

    locations.forEach(function(loc) {
        if (loc.latitude && loc.longitude) {
            var lat = parseFloat(loc.latitude);
            var lng = parseFloat(loc.longitude);

            var marker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(
                    "<b>IP:</b> " + (loc.ip_address || 'N/A') + "<br>" +
                    "<b>Country:</b> " + (loc.country || 'N/A') + "<br>" +
                    "<b>City:</b> " + (loc.city || 'N/A')
                );

            markers.push(marker);
        }
    });

    if (markers.length > 0) {
        var group = L.featureGroup(markers);
        map.fitBounds(group.getBounds().pad(0.2));
    }
</script>

<script>
    const modal = document.getElementById('transactionModal');
    const senderEl = modal.querySelector('#modal-sender');
    const recipientEl = modal.querySelector('#modal-recipient');
    const amountEl = modal.querySelector('#modal-amount');
    const currencyEl = modal.querySelector('#modal-currency');
    const statusEl = modal.querySelector('#modal-status');
    const methodEl = modal.querySelector('#modal-method');
    const referenceEl = modal.querySelector('#modal-reference');
    const typeEl = modal.querySelector('#modal-type');
    const createdEl = modal.querySelector('#modal-created');

    document.querySelectorAll('.view-transaction-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            senderEl.textContent = this.dataset.sender || 'N/A';
            recipientEl.textContent = this.dataset.recipient || 'N/A';
            amountEl.textContent = this.dataset.amount || '0.00';
            currencyEl.textContent = this.dataset.currency || '';
            methodEl.textContent = this.dataset.method || 'N/A';
            referenceEl.textContent = this.dataset.reference || 'N/A';
            typeEl.textContent = this.dataset.type || 'N/A';
            createdEl.textContent = this.dataset.created || 'N/A';

            const status = (this.dataset.status || '').toLowerCase();
            statusEl.textContent = status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
            statusEl.className = 'status-pill';

            if (status === 'success') statusEl.classList.add('status-success');
            else if (status === 'failed') statusEl.classList.add('status-danger');
            else statusEl.classList.add('status-warning');
        });
    });
</script>
