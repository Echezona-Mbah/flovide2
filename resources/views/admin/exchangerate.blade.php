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
        --shadow: 0 18px 45px rgba(20, 33, 61, 0.08);
    }

    .business-page { padding-bottom: 32px; }
    .business-hero { border: 0; border-radius: 32px; overflow: hidden;
      background: radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
                  radial-gradient(circle at bottom left, rgba(14,165,233,0.15), transparent 30%),
                  linear-gradient(135deg, #0c1630 0%, #123b9f 52%, #0891b2 100%);
      box-shadow: 0 26px 70px rgba(17, 24, 39, 0.18);
    }
    .business-hero .card-body { padding: 34px; }
    .hero-tag { display:inline-flex; align-items:center; gap:8px; padding:9px 14px;
      border-radius:999px; background:rgba(255,255,255,0.12); color:#fff; font-size:12px; font-weight:700; }
    .hero-title { color:#fff; font-size:2rem; font-weight:800; margin:14px 0 10px; }
    .hero-copy { color:rgba(255,255,255,0.82); max-width:760px; line-height:1.8; }
    .hero-metric { background:rgba(255,255,255,0.10); border:1px solid rgba(255,255,255,0.12);
      border-radius:20px; padding:16px 18px; color:#fff; }

    .stats-row { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));
      gap:16px; margin:24px 0; }
    .stat-box { background:var(--paper); border:1px solid var(--line); border-radius:24px; padding:22px; box-shadow:var(--shadow); }
    .stat-icon { width:54px; height:54px; border-radius:18px; display:inline-flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:14px; }
    .stat-blue { background:rgba(29, 78, 216, 0.12); color:var(--blue); }
    .stat-green { background:rgba(22, 163, 74, 0.12); color:var(--green); }
    .stat-red { background:rgba(220, 38, 38, 0.12); color:var(--red); }
    .stat-amber { background:rgba(217, 119, 6, 0.12); color:var(--amber); }
    .stat-label { font-size:13px; color:var(--ink-soft); margin-bottom:6px; }
    .stat-value { font-size:26px; font-weight:800; color:var(--ink); }

    .directory-card { border:0; border-radius:28px; overflow:hidden; background:var(--paper); box-shadow:var(--shadow); }
    .directory-head { padding:24px; border-bottom:1px solid var(--line); background:linear-gradient(180deg, #ffffff, #f9fbff); }
    .directory-title { font-size:20px; font-weight:800; color:var(--ink); margin-bottom:4px; }
    .directory-subtitle { color:var(--ink-soft); margin-bottom:0; font-size:13px; }
    .search-wrap { display:flex; flex-wrap:wrap; gap:10px; align-items:center; }
    .search-input { min-width:260px; height:46px; border-radius:15px !important; border:1px solid #dbe3ee !important; }
    .btn-brand { background:linear-gradient(135deg, var(--blue), var(--blue-deep)); color:#fff; padding:11px 16px; border-radius:14px; font-weight:700; border:0; }
    .btn-soft { background:#eef3fa; color:#334155; padding:11px 16px; border-radius:14px; font-weight:700; border:0; }

    .business-table thead th { background:#fbfcff; color:var(--ink-soft); font-size:12px; text-transform:uppercase; letter-spacing:.05em; border-bottom:1px solid var(--line); padding:16px 18px; }
    .business-table tbody td { padding:18px; border-top:1px solid #f1f5f9; vertical-align:middle; }
    .business-table tbody tr:hover { background:#fbfdff; }

    .actions { display:inline-flex; gap:8px; justify-content:flex-end; }
    .btn-action { padding:8px 11px; border-radius:14px; border:0; font-weight:700; }
    .btn-edit { background:rgba(217, 119, 6, 0.12); color:var(--amber); }
    .btn-delete { background:rgba(220, 38, 38, 0.12); color:var(--red); }

    .empty-row { padding:48px 24px !important; text-align:center; color:var(--ink-soft); }
</style>

<body>

<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    @include('admin.header')
    @include('admin.ui-setting')

    <div class="app-main MainAnimation-appear">
        @include('admin.sidebar')

        <div class="app-main__outer">
            <div class="business-page">

                <div class="card business-hero mb-4">
                    <div class="card-body">
                        <div class="row align-items-end g-4">
                            <div class="col-lg-8">
                                <div class="hero-tag">
                                    <i class="fa-solid fa-exchange"></i>
                                    Exchange Directory
                                </div>
                                <h1 class="hero-title">Exchange Rates</h1>
                                <p class="hero-copy">
                                    Manage exchange rates, transfer fees, and currencies from one place.
                                </p>
                            </div>

                            <div class="col-lg-4">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="hero-metric">
                                            <small>Total Rates</small>
                                            <strong>{{ $exchangerates->count() }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="hero-metric">
                                            <small>Showing</small>
                                            <strong>{{ $exchangerates->count() }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="hero-metric">
                                            <small>Filter</small>
                                            <strong>{{ request('search') ?: 'All rates' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card directory-card mb-4">
                    <div class="directory-head d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="directory-title">Exchange Rates</h5>
                            <p class="directory-subtitle">Pick a currency to see its FROM rates</p>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#addCurrencyModal">+ Add Currency</button>
                            <button class="btn btn-brand" data-bs-toggle="modal" data-bs-target="#addRateModal">+ Add Exchange Rate</button>
                        </div>
                    </div>

                    <div class="row g-4 p-4">
                        <div class="col-md-4">
                            <div class="card directory-card">
                                <div class="directory-head">
                                    <h5 class="directory-title">Currencies</h5>
                                    <p class="directory-subtitle">Click currency to view its rates</p>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach($currencies as $cur)
                                        <a href="{{ route('admin.exchangerate', ['currency' => $cur->code]) }}"
                                           class="list-group-item list-group-item-action {{ $selectedCode === $cur->code ? 'active' : '' }}">
                                            {{ $cur->name }} ({{ $cur->code }})
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card directory-card">
                                <div class="directory-head">
                                    <h5 class="directory-title">Rates FROM {{ $selectedCode }}</h5>
                                    <p class="directory-subtitle">Only rates where {{ $selectedCode }} is the source</p>
                                </div>

                                <div class="table-responsive">
                                    <table class="table business-table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Rate</th>
                                                <th>Transfer Fee</th>
                                                <th>Collection Fee</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($exchangerates as $item)
                                                <tr>
                                                    <td>{{ $item->fromCurrency->code ?? 'N/A' }}</td>
                                                    <td>{{ $item->toCurrency->code ?? 'N/A' }}</td>
                                                    <td>{{ $item->rate ?? 'N/A' }}</td>
                                                    <td>{{ $item->transfer_fee ?? 'N/A' }}</td>
                                                    <td>{{ $item->collection_fee ?? 'N/A' }}</td>
                                                    <td class="text-end">
                                                        <div class="actions">
                                                            <a href="{{ route('admin.exchangerate.edit', $item->id) }}" class="btn btn-action btn-edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('admin.exchangerate.destroy', $item->id) }}" method="POST" style="display:inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-action btn-delete" onclick="return confirm('Delete this rate?');">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="empty-row">No rates for {{ $selectedCode }}</td>
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
        </div>
    </div>
</div>

@include('admin.footer')

<!-- Add Currency Modal -->
<div class="modal fade" id="addCurrencyModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.currency.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Currency</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input class="form-control mb-3" name="code" placeholder="Code (e.g. USD)" required>
        <input class="form-control mb-3" name="name" placeholder="Currency Name" required>
        <input class="form-control mb-3" name="symbol" placeholder="Symbol (optional)">
        <input class="form-control mb-3" name="country_code" placeholder="Country Code (e.g. us)">
      </div>
      <div class="modal-footer">
        <button class="btn btn-brand">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Exchange Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.exchangerate.store') }}" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Add Exchange Rate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <select class="form-control mb-3" name="from_currency_id" required>
          <option value="">From Currency</option>
          @foreach($currencies as $c)
            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
          @endforeach
        </select>
        <select class="form-control mb-3" name="to_currency_id" required>
          <option value="">To Currency</option>
          @foreach($currencies as $c)
            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->code }})</option>
          @endforeach
        </select>
        <input class="form-control mb-3" name="rate" placeholder="Rate" required>
        <input class="form-control mb-3" name="transfer_fee" placeholder="Transfer Fee (optional)">
        <input class="form-control mb-3" name="collection_fee" placeholder="Collection Fee (optional)">
      </div>
      <div class="modal-footer">
        <button class="btn btn-brand">Save</button>
      </div>
    </form>
  </div>
</div>


@include('admin.footer')

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
