@include('admin.head')

<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    @include('admin.header')
    @include('admin.ui-setting')

    <div class="app-main MainAnimation-appear">
        @include('admin.sidebar')

        <div class="app-main__outer">
            <div class="app-main__inner">

                <h1 class="mb-4">Payment Providers</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @foreach($providers as $provider)
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $provider->name }}</strong>
                                <span class="text-muted ms-2">({{ $provider->key }})</span>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <!-- Priority -->
                                <form method="POST" action="{{ route('admin.payment-providers.priority', $provider) }}" class="d-flex align-items-center gap-2">
                                    @csrf
                                    <label class="mb-0 small text-muted">Priority</label>
                                    <input type="number" name="priority" value="{{ $provider->priority }}"
                                           class="form-control form-control-sm" style="width:70px"
                                           onchange="this.form.submit()">
                                </form>

                                <!-- Master toggle -->
                                <form method="POST" action="{{ route('admin.payment-providers.toggle', $provider) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $provider->is_enabled ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $provider->is_enabled ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="text-muted small mb-3">
                                Currencies this provider handles. Toggle any on/off, or add a new one below.
                                Leave "Method" blank to apply to both bank and mobile.
                            </p>

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Currency</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($provider->currencies as $c)
                                        <tr>
                                            <td>{{ $c->currency }}</td>
                                            <td>{{ $c->transfer_method ?? 'Any' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.payment-providers.currency.toggle', $provider) }}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="currency" value="{{ $c->currency }}">
                                                    <input type="hidden" name="transfer_method" value="{{ $c->transfer_method }}">
                                                    <button type="submit" class="btn btn-sm {{ $c->is_enabled ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $c->is_enabled ? 'On' : 'Off' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('admin.payment-providers.currency.remove', [$provider, $c->id]) }}" class="d-inline"
                                                      onsubmit="return confirm('Remove this currency mapping?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-muted">No currencies mapped yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Add new currency mapping -->
                            <form method="POST" action="{{ route('admin.payment-providers.currency.toggle', $provider) }}" class="d-flex gap-2 align-items-end mt-3">
                                @csrf
                                <div>
                                    <label class="form-label small mb-1">Add Currency</label>
                                    <select name="currency" class="form-select form-select-sm" required>
                                        <option value="">Select</option>
                                        @foreach($allCurrencies as $cur)
                                            <option value="{{ $cur }}">{{ $cur }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label small mb-1">Method</label>
                                    <select name="transfer_method" class="form-select form-select-sm">
                                        <option value="">Any</option>
                                        <option value="bank">Bank</option>
                                        <option value="mobile">Mobile</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Add / Enable</button>
                            </form>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@include('admin.footer')
</body>
</html>