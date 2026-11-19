@include('admin.head')

<style>
    .provider-errors-page { padding-bottom: 32px; }
    .provider-errors-hero {
        overflow: hidden;
        border: 0;
        border-radius: 24px;
        color: #fff;
        background:
            radial-gradient(circle at 88% 8%, rgba(255,255,255,.16), transparent 25%),
            linear-gradient(125deg, #25143f 0%, #642d55 55%, #a34a3a 100%);
        box-shadow: 0 18px 45px rgba(58, 31, 57, .18);
    }
    .provider-errors-hero .card-body { padding: 28px; }
    .provider-errors-eyebrow { color: #f2d8e5; font-size: 11px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .provider-errors-title { margin: 8px 0; color: #fff; font-size: 27px; font-weight: 800; }
    .provider-errors-copy { max-width: 760px; margin: 0; color: #f5eaf0; }
    .provider-error-stat {
        height: 100%;
        padding: 17px;
        border: 1px solid #e8ebf1;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 6px 20px rgba(15, 23, 42, .04);
    }
    .provider-error-stat span { display: block; color: #718096; font-size: 11px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .provider-error-stat strong { display: block; margin-top: 5px; color: #172033; font-size: 26px; font-weight: 800; }
    .provider-error-card { overflow: hidden; border: 1px solid #e5e9f1; border-radius: 15px; background: #fff; }
    .provider-error-card + .provider-error-card { margin-top: 14px; }
    .provider-error-card-head { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 12px; padding: 17px 19px; background: #fffafa; border-bottom: 1px solid #f1e7e8; }
    .provider-error-card-title { margin: 0; color: #273248; font-size: 15px; font-weight: 800; overflow-wrap: anywhere; }
    .provider-error-time { margin-top: 5px; color: #78869a; font-size: 12px; }
    .provider-error-card-body { padding: 18px 19px; }
    .provider-error-message { padding: 12px 14px; border-left: 3px solid #dc5a5a; border-radius: 7px; background: #fff5f5; color: #6f2930; overflow-wrap: anywhere; }
    .provider-error-facts { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-top: 16px; }
    .provider-error-fact { min-width: 0; }
    .provider-error-fact small { display: block; color: #8490a2; font-size: 10px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .provider-error-fact strong { display: block; margin-top: 4px; color: #344054; font-size: 13px; overflow-wrap: anywhere; }
    .provider-error-details { margin-top: 16px; }
    .provider-error-details summary { color: #4b3b7a; cursor: pointer; font-size: 13px; font-weight: 700; }
    .provider-error-details pre { max-height: 340px; overflow: auto; margin: 10px 0 0; padding: 14px; border-radius: 9px; background: #111827; color: #e2e8f0; font-size: 11px; white-space: pre-wrap; overflow-wrap: anywhere; }
    .provider-error-empty { padding: 52px 20px; border: 1px dashed #cfd6e2; border-radius: 15px; color: #718096; text-align: center; background: #fff; }
    @media (max-width: 767.98px) {
        .provider-errors-hero .card-body { padding: 22px; }
        .provider-errors-title { font-size: 22px; }
        .provider-error-facts { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
</style>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        @include('admin.header')
        @include('admin.ui-setting')

        <div class="app-main MainAnimation-appear">
            @include('admin.sidebar')

            <div class="app-main__outer">
                <div class="app-main__inner provider-errors-page">
                    <section class="provider-errors-hero card mb-4">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <span class="provider-errors-eyebrow">Admin monitoring</span>
                                <h1 class="provider-errors-title">Payment provider errors</h1>
                                <p class="provider-errors-copy">Review collection and provider failures reported by Flovide, inspect the affected account and provider response, and mark investigated incidents as resolved.</p>
                            </div>
                            <div class="mt-3 mt-md-0 text-md-right">
                                <span class="provider-errors-eyebrow">Error inbox</span>
                                <div class="h5 text-white font-weight-bold mb-0">Internal use only</div>
                            </div>
                        </div>
                    </section>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @unless($storageAvailable)
                        <div class="alert alert-warning" role="alert">
                            Provider error storage is unavailable. Errors are still written to the application log, but cannot appear in this inbox until the existing <code>admin_notifications</code> table is available.
                        </div>
                    @else
                        <div class="row mb-2">
                            <div class="col-4 mb-3">
                                <div class="provider-error-stat"><span>Total incidents</span><strong>{{ number_format($counts['total']) }}</strong></div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="provider-error-stat"><span>Open</span><strong class="text-danger">{{ number_format($counts['open']) }}</strong></div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="provider-error-stat"><span>Resolved</span><strong class="text-success">{{ number_format($counts['resolved']) }}</strong></div>
                            </div>
                        </div>

                        <section class="main-card card mb-4">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
                                <div>
                                    <h2 class="h5 mb-1 font-weight-bold">Error reports</h2>
                                    <small class="text-muted">Provider error details are restricted to authenticated administrators.</small>
                                </div>
                                <form method="GET" action="{{ route('admin.provider-errors.index') }}" class="d-flex flex-wrap mt-2 mt-md-0">
                                    <select name="status" class="form-control form-control-sm mr-2 mb-2 mb-sm-0" aria-label="Filter by resolution status">
                                        <option value="open" {{ request('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                                    </select>
                                    <div class="input-group input-group-sm">
                                        <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search provider or error">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                @forelse($providerErrors as $error)
                                    @php($details = is_array($error->data) ? $error->data : [])
                                    <article class="provider-error-card">
                                        <div class="provider-error-card-head">
                                            <div>
                                                <h3 class="provider-error-card-title">{{ $error->title }}</h3>
                                                <div class="provider-error-time">{{ optional($error->created_at)->format('M d, Y · h:i:s A') }} <span class="mx-1">·</span> {{ optional($error->created_at)->diffForHumans() }}</div>
                                            </div>
                                            @if($error->read_at)
                                                <span class="badge badge-success align-self-start">Resolved {{ $error->read_at->diffForHumans() }}</span>
                                            @else
                                                <span class="badge badge-danger align-self-start">Open</span>
                                            @endif
                                        </div>
                                        <div class="provider-error-card-body">
                                            <div class="provider-error-message"><strong>Error code:</strong> {{ $details['error'] ?? $error->body }}</div>
                                            <div class="provider-error-facts">
                                                <div class="provider-error-fact"><small>Provider</small><strong>{{ $details['provider'] ?? 'Unknown' }}</strong></div>
                                                <div class="provider-error-fact"><small>Operation</small><strong>{{ $details['operation'] ?? '—' }}</strong></div>
                                                <div class="provider-error-fact"><small>Account</small><strong>{{ ucfirst($details['account_type'] ?? '—') }} {{ $details['account_id'] ?? '' }}</strong></div>
                                                <div class="provider-error-fact"><small>Amount / currency</small><strong>{{ isset($details['amount']) ? number_format((float) $details['amount'], 2) : '—' }} {{ $details['currency'] ?? '' }}</strong></div>
                                                <div class="provider-error-fact"><small>Mode</small><strong>{{ ucfirst($details['mode'] ?? '—') }}</strong></div>
                                                <div class="provider-error-fact"><small>Business email</small><strong>{{ $details['business_email'] ?? $details['account_email'] ?? '—' }}</strong></div>
                                                <div class="provider-error-fact"><small>Payer email</small><strong>{{ $details['payer_email'] ?? '—' }}</strong></div>
                                                <div class="provider-error-fact"><small>Provider status</small><strong>{{ $details['provider_status'] ?? '—' }}</strong></div>
                                            </div>
                                            @if(!$error->read_at)
                                                <form method="POST" action="{{ route('admin.provider-errors.resolve', $error->id) }}" class="mt-3 text-right">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-check mr-1"></i>Mark resolved</button>
                                                </form>
                                            @endif
                                            <details class="provider-error-details">
                                                <summary>View full provider error context</summary>
                                                <pre>{{ json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </details>
                                        </div>
                                    </article>
                                @empty
                                    <div class="provider-error-empty">
                                        <i class="fa-solid fa-shield-check fa-2x mb-3"></i>
                                        <strong class="d-block text-dark">No provider errors found</strong>
                                        <span>New provider errors will appear here when the application reports them.</span>
                                    </div>
                                @endforelse
                            </div>
                            @if($providerErrors->hasPages())
                                <div class="card-footer d-flex justify-content-center">{{ $providerErrors->links('pagination::bootstrap-4') }}</div>
                            @endif
                        </section>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</body>
</html>
