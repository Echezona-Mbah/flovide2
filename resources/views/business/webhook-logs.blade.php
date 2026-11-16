@include('business.head')

<body class="min-h-screen bg-[#f3f6fb] text-slate-900 md:flex">
    @include('business.header')
    @include('business.sidebar')
    <div id="overlay" class="fixed inset-0 z-20 hidden bg-slate-950/40 md:hidden"></div>

    <main class="min-w-0 flex-1 overflow-auto p-4 md:p-8">
        <div class="mx-auto max-w-7xl">
            <header class="mb-8 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <a href="{{ route('business.webhooks') }}" class="mb-3 inline-flex items-center gap-2 text-sm font-semibold text-[#215F9C] hover:text-blue-800">
                        <span aria-hidden="true">&larr;</span> Webhook settings
                    </a>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">Developer tools</p>
                    <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 md:text-4xl">Webhook activity</h1>
                    <p class="mt-2 text-sm text-slate-500">Inspect deliveries, review responses, and safely replay an event.</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold shadow-sm">
                    <span class="h-2 w-2 rounded-full {{ $mode === 'live' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ ucfirst($mode) }} environment
                </span>
            </header>

            @if (session('success'))
                <div role="status" class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div role="alert" class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <section class="mb-7 overflow-hidden rounded-[28px] bg-gradient-to-br from-[#16395f] via-[#215F9C] to-[#357bb6] p-6 text-white shadow-xl shadow-blue-950/10 md:p-8">
                <div class="flex flex-wrap items-end justify-between gap-5">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-100">Delivery monitor</p>
                        <h2 class="mt-2 text-2xl font-black md:text-3xl">Every event, one clear timeline.</h2>
                        <p class="mt-2 text-sm leading-6 text-blue-100">
                            Review the exact payload and receiver response. Replays keep the original event ID and add a new delivery attempt.
                        </p>
                    </div>
                    <a href="{{ route('business.webhooks') }}" class="rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-bold transition hover:bg-white/20">
                        Manage endpoints
                    </a>
                </div>
            </section>

            <section class="mb-7 grid gap-4 sm:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-slate-500">Total events</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ number_format($webhookStats['total']) }}</p>
                    <p class="mt-1 text-xs text-slate-400">In this environment</p>
                </article>
                <article class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-emerald-700">Delivered</p>
                    <p class="mt-2 text-3xl font-black text-emerald-700">{{ number_format($webhookStats['successful']) }}</p>
                    <p class="mt-1 text-xs text-slate-400">Receiver returned a 2xx response</p>
                </article>
                <article class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
                    <p class="text-sm font-semibold text-rose-700">Needs attention</p>
                    <p class="mt-2 text-3xl font-black text-rose-700">{{ number_format($webhookStats['failed']) }}</p>
                    <p class="mt-1 text-xs text-slate-400">Failed delivery attempts</p>
                </article>
            </section>

            <section class="overflow-hidden rounded-[26px] border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 md:px-7">
                    <div>
                        <h2 class="text-lg font-black text-slate-900">Event log</h2>
                        <p class="mt-1 text-sm text-slate-500">Showing {{ $webhookLogs->count() }} of {{ $webhookLogs->total() }} events</p>
                    </div>
                    <form method="GET" action="{{ route('business.webhooks.logs') }}" class="grid w-full grid-cols-1 gap-2 sm:w-auto sm:grid-cols-2 lg:flex">
                        <label class="sr-only" for="mode">Environment</label>
                        <select id="mode" name="mode" class="min-w-0 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="live" @selected($mode === 'live')>Live</option>
                            <option value="test" @selected($mode === 'test')>Test</option>
                        </select>
                        <label class="sr-only" for="type">Event type</label>
                        <select id="type" name="type" class="min-w-0 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">All event types</option>
                            <option value="COLLECTION" @selected(request('type') === 'COLLECTION')>Collections</option>
                            <option value="PAYOUT" @selected(request('type') === 'PAYOUT')>Payouts</option>
                        </select>
                        <label class="sr-only" for="delivery_status">Delivery status</label>
                        <select id="delivery_status" name="delivery_status" class="min-w-0 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <option value="">All delivery statuses</option>
                            <option value="SUCCESSFUL" @selected(request('delivery_status') === 'SUCCESSFUL')>Delivered</option>
                            <option value="FAILED" @selected(request('delivery_status') === 'FAILED')>Failed</option>
                            <option value="PENDING" @selected(request('delivery_status') === 'PENDING')>Pending</option>
                        </select>
                        <button class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">Apply filters</button>
                    </form>
                </div>

                @if ($webhookLogs->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-[#215F9C]" aria-hidden="true">&#8635;</div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">No webhook events found</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                            There are no events matching these filters. Try another environment or event type, or create an API transaction after configuring a webhook URL.
                        </p>
                    </div>
                @else
                    <div class="space-y-4 p-4 md:p-6">
                        @foreach ($webhookLogs as $log)
                            @php
                                $delivered = $log->delivery_status === 'SUCCESSFUL';
                                $eventReference = $log->businessTransaction?->reference ?: $log->business_transaction_id;
                            @endphp
                            <article class="rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-blue-200 hover:shadow-md md:p-5">
                                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-lg px-2.5 py-1 text-xs font-black tracking-wide {{ $log->type === 'COLLECTION' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">
                                                {{ $log->type }}
                                            </span>
                                            <span class="rounded-lg px-2.5 py-1 text-xs font-bold {{ $delivered ? 'bg-emerald-50 text-emerald-700' : ($log->delivery_status === 'PENDING' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                                {{ $log->delivery_status }}
                                            </span>
                                            <span class="text-xs text-slate-400">{{ $mode === 'live' ? 'Live' : 'Test' }}</span>
                                        </div>
                                        <h3 class="mt-3 truncate font-bold text-slate-900">{{ $eventReference ?: 'Transaction event' }}</h3>
                                        <p class="mt-1 break-all text-xs text-slate-500">{{ $log->webhook_url }}</p>
                                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-400">
                                            <span>Transaction status: <strong class="text-slate-600">{{ $log->status ?: 'N/A' }}</strong></span>
                                            <span>{{ $log->histories->count() }} {{ \Illuminate\Support\Str::plural('attempt', $log->histories->count()) }}</span>
                                            <span>{{ optional($log->created_at)->format('M j, Y · H:i:s') }}</span>
                                        </div>
                                    </div>

                                    <div class="grid w-full shrink-0 grid-cols-2 gap-2 sm:w-auto">
                                        <button type="button" class="open-webhook-log rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-center text-sm font-bold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200 sm:px-4" aria-haspopup="dialog" aria-controls="webhook-log-{{ $log->id }}" data-dialog="webhook-log-{{ $log->id }}">
                                            View log
                                        </button>
                                        <form method="POST" action="{{ route('business.webhooks.logs.replay', $log->id) }}" onsubmit="return confirm('Replay this webhook? The same event ID will be sent again, so your endpoint should handle duplicate events safely.');">
                                            @csrf
                                            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#215F9C] px-3 py-2.5 text-sm font-bold text-white transition hover:bg-[#174b7d] focus:outline-none focus:ring-2 focus:ring-blue-200 sm:px-4">
                                                <span aria-hidden="true">&#8635;</span> Replay
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                            <dialog id="webhook-log-{{ $log->id }}" class="webhook-log-dialog m-auto max-h-[92dvh] w-[calc(100%-1rem)] max-w-3xl overflow-hidden rounded-2xl border border-slate-200 bg-white p-0 text-slate-900 shadow-2xl backdrop:bg-slate-950/60 backdrop:backdrop-blur-sm">
                                <div class="flex max-h-[92dvh] flex-col">
                                    <header class="flex items-start justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-6">
                                        <div class="min-w-0">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="rounded-md px-2 py-1 text-[11px] font-black {{ $log->type === 'COLLECTION' ? 'bg-violet-50 text-violet-700' : 'bg-blue-50 text-blue-700' }}">{{ $log->type }}</span>
                                                <span class="text-xs font-semibold text-slate-500">{{ $log->delivery_status }}</span>
                                            </div>
                                            <h2 class="mt-2 break-all text-base font-black sm:text-lg">Event details</h2>
                                            <p class="mt-1 break-all font-mono text-[11px] text-slate-400 sm:text-xs">{{ $log->id }}</p>
                                        </div>
                                        <button type="button" class="close-webhook-log flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xl text-slate-600 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-200" aria-label="Close event details">&times;</button>
                                    </header>

                                    <div class="grid grid-cols-2 gap-3 border-b border-slate-100 bg-slate-50 px-4 py-4 sm:grid-cols-4 sm:px-6">
                                        <div class="min-w-0"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Transaction</p><p class="mt-1 truncate text-xs font-semibold text-slate-700">{{ $eventReference ?: '—' }}</p></div>
                                        <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Status</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $log->status ?: 'N/A' }}</p></div>
                                        <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Attempts</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $log->histories->count() }}</p></div>
                                        <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Latest HTTP</p><p class="mt-1 text-xs font-semibold text-slate-700">{{ $log->histories->last()?->http_status ?? '—' }}</p></div>
                                    </div>

                                    <nav class="flex gap-1 overflow-x-auto border-b border-slate-100 px-3 py-2 sm:px-5" aria-label="Event log sections">
                                        @foreach (['payload' => 'Payload', 'response' => 'Response', 'attempts' => 'Attempts'] as $tabKey => $tabLabel)
                                            <button type="button" class="webhook-log-tab shrink-0 rounded-lg px-3 py-2 text-xs font-bold transition sm:text-sm {{ $loop->first ? 'bg-blue-50 text-[#215F9C]' : 'text-slate-500 hover:bg-slate-50' }}" data-target="webhook-log-{{ $log->id }}-{{ $tabKey }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                {{ $tabLabel }}
                                            </button>
                                        @endforeach
                                    </nav>

                                    <div class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-3 sm:p-6">
                                        <section id="webhook-log-{{ $log->id }}-payload" class="webhook-log-panel">
                                            <div class="mb-2 flex items-center justify-between gap-3">
                                                <div><h3 class="text-sm font-bold text-slate-800">Request payload</h3><p class="mt-1 text-xs text-slate-500">JSON sent to your endpoint.</p></div>
                                                <button type="button" class="copy-webhook-json shrink-0 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50" data-copy="webhook-log-{{ $log->id }}-payload-json">Copy JSON</button>
                                            </div>
                                            <pre id="webhook-log-{{ $log->id }}-payload-json" class="max-h-[55dvh] overflow-auto whitespace-pre-wrap break-words rounded-xl bg-slate-950 p-3 text-[11px] leading-5 text-emerald-100 [overflow-wrap:anywhere] sm:p-5 sm:text-xs">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </section>
                                        <section id="webhook-log-{{ $log->id }}-response" class="webhook-log-panel hidden">
                                            <div class="mb-2 flex items-center justify-between gap-3">
                                                <div><h3 class="text-sm font-bold text-slate-800">Latest response</h3><p class="mt-1 text-xs text-slate-500">Most recent delivery result.</p></div>
                                                <button type="button" class="copy-webhook-json shrink-0 rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50" data-copy="webhook-log-{{ $log->id }}-response-json">Copy JSON</button>
                                            </div>
                                            <pre id="webhook-log-{{ $log->id }}-response-json" class="max-h-[55dvh] overflow-auto whitespace-pre-wrap break-words rounded-xl bg-slate-950 p-3 text-[11px] leading-5 text-blue-100 [overflow-wrap:anywhere] sm:p-5 sm:text-xs">{{ json_encode($log->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </section>
                                        <section id="webhook-log-{{ $log->id }}-attempts" class="webhook-log-panel hidden">
                                            <h3 class="mb-3 text-sm font-bold text-slate-800">Delivery attempts</h3>
                                            <div class="space-y-3">
                                                @forelse ($log->histories as $history)
                                                    <article class="rounded-xl border border-slate-200 p-3 sm:p-4">
                                                        <div class="flex flex-wrap items-start justify-between gap-2">
                                                            <div><p class="text-sm font-bold text-slate-800">Attempt {{ $history->metadata['attempt'] ?? $loop->iteration }}</p><p class="mt-1 text-xs text-slate-500">{{ optional($history->created_at)->format('M j, Y · H:i:s') }}</p></div>
                                                            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-bold text-slate-700">HTTP {{ $history->http_status ?? 'N/A' }}</span>
                                                        </div>
                                                        <p class="mt-3 break-all text-xs text-slate-500">{{ $history->metadata['url'] ?? $log->webhook_url }}</p>
                                                        <pre class="mt-3 max-h-48 overflow-auto whitespace-pre-wrap break-words rounded-lg bg-slate-50 p-3 text-[11px] leading-5 text-slate-600 [overflow-wrap:anywhere]">{{ json_encode($history->response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </article>
                                                @empty
                                                    <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No delivery attempt details recorded.</p>
                                                @endforelse
                                            </div>
                                        </section>
                                    </div>

                                    <footer class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-white px-4 py-3 sm:flex-row sm:justify-end sm:px-6">
                                        <button type="button" class="close-webhook-log rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">Close</button>
                                        <form method="POST" action="{{ route('business.webhooks.logs.replay', $log->id) }}" onsubmit="return confirm('Replay this webhook? The same event ID will be sent again, so your endpoint should handle duplicate events safely.');">
                                            @csrf
                                            <button type="submit" class="w-full rounded-xl bg-[#215F9C] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#174b7d]">
                                                Replay event
                                            </button>
                                        </form>
                                    </footer>
                                </div>
                            </dialog>
                        @endforeach
                    </div>
                    <div class="border-t border-slate-100 px-5 py-5 md:px-7">
                        {{ $webhookLogs->links() }}
                    </div>
                @endif
            </section>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.open-webhook-log').forEach((button) => {
                button.addEventListener('click', () => {
                    const dialog = document.getElementById(button.dataset.dialog);
                    if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
                });
            });

            document.querySelectorAll('.close-webhook-log').forEach((button) => {
                button.addEventListener('click', () => button.closest('dialog')?.close());
            });

            document.querySelectorAll('.webhook-log-dialog').forEach((dialog) => {
                dialog.addEventListener('click', (event) => {
                    if (event.target === dialog) dialog.close();
                });

                dialog.querySelectorAll('.webhook-log-tab').forEach((button) => {
                    button.addEventListener('click', () => {
                        dialog.querySelectorAll('.webhook-log-tab').forEach((tab) => {
                            tab.setAttribute('aria-selected', 'false');
                            tab.classList.remove('bg-blue-50', 'text-[#215F9C]');
                            tab.classList.add('text-slate-500');
                        });
                        dialog.querySelectorAll('.webhook-log-panel').forEach((panel) => panel.classList.add('hidden'));
                        button.setAttribute('aria-selected', 'true');
                        button.classList.add('bg-blue-50', 'text-[#215F9C]');
                        button.classList.remove('text-slate-500');
                        document.getElementById(button.dataset.target)?.classList.remove('hidden');
                    });
                });

                dialog.querySelectorAll('.copy-webhook-json').forEach((button) => {
                    button.addEventListener('click', async () => {
                        const content = document.getElementById(button.dataset.copy)?.textContent ?? '';
                        try {
                            await navigator.clipboard.writeText(content);
                            button.textContent = 'Copied';
                            window.setTimeout(() => { button.textContent = 'Copy JSON'; }, 1500);
                        } catch (error) {
                            console.error('Unable to copy webhook JSON:', error);
                            button.textContent = 'Copy failed';
                            window.setTimeout(() => { button.textContent = 'Copy JSON'; }, 2000);
                        }
                    });
                });
            });
        });
    </script>
</body>
