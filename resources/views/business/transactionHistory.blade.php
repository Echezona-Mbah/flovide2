@include('business.head')
<body class="bg-[#EEF2F7] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Transactions</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Transaction History</h1>
        <p class="mt-1 text-sm text-slate-500">Track, filter, and review transaction activity.</p>
      </div>
      @include('business.header_notifical')
    </header>

    <section class="w-full">
      <div class="rounded-[28px] bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 md:px-10 py-7 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Activity</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">All Transactions</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">Search and filter by status, date, and type.</p>
            </div>
          </div>
        </div>

        <!-- Filters -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/40">
          <form method="GET" action="{{ request()->url() }}"
                class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            <div class="w-full md:max-w-md">
              <input
                type="search"
                name="search"
                id="search"
                value="{{ request('search') }}"
                placeholder="Search by reference, sender, recipient..."
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200"
              />
            </div>

            <div class="flex gap-3 flex-wrap">
              <select name="filter"
                class="w-full md:w-auto border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200"
                onchange="this.form.submit()">
                <option value="">All transactions</option>
                <option value="credit"     {{ request('filter') == 'credit'     ? 'selected' : '' }}>Credit</option>
                <option value="withdrawal" {{ request('filter') == 'withdrawal' ? 'selected' : '' }}>Withdrawals</option>
                <option value="swap"       {{ request('filter') == 'swap'       ? 'selected' : '' }}>Swap</option>
                <option value="success"    {{ request('filter') == 'success'    ? 'selected' : '' }}>Successful</option>
                <option value="failed"     {{ request('filter') == 'failed'     ? 'selected' : '' }}>Failed</option>
                <option value="pending"    {{ request('filter') == 'pending'    ? 'selected' : '' }}>Pending</option>
              </select>

              <button type="submit"
                class="bg-slate-900 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-slate-800 transition">
                Search
              </button>

              @if(request('search') || request('filter'))
                <a href="{{ request()->url() }}"
                  class="bg-slate-100 text-slate-700 px-5 py-2.5 rounded-xl text-sm hover:bg-slate-200 transition">
                  Clear
                </a>
              @endif
            </div>
          </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-white text-slate-500 border-b border-slate-100">
              <tr>
                <th class="px-4 py-4 text-left">Type</th>
                <th class="px-4 py-4 text-left">Date</th>
                <th class="px-4 py-4 text-left hidden md:table-cell">Sender</th>
                <th class="px-4 py-4 text-left hidden md:table-cell">Recipient</th>
                <th class="px-4 py-4 text-left">Amount</th>
                <th class="px-4 py-4 text-left">Fee</th>
                <th class="px-4 py-4 text-left">Status</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
              @forelse($transactions as $transaction)
                @php
                  $type = strtolower($transaction->type ?? $transaction->method ?? '');
                @endphp
                <tr onclick="openTransaction(this)"
                  class="cursor-pointer hover:bg-sky-50 transition"
                  data-type="{{ $type }}"
                  data-amount="{{ number_format($transaction->amount, 2) }}"
                  data-currency="{{ $transaction->currency }}"
                  data-reference="{{ $transaction->reference }}"
                  data-method="{{ $transaction->method }}"
                  data-status="{{ $transaction->status }}"
                  data-sender="{{ $transaction->sender ?? 'N/A' }}"
                  data-recipient="{{ $transaction->recipient_account_name ?? 'N/A' }}"
                  data-recipient-number="{{ $transaction->recipient_account_number ?? 'N/A' }}"
                  data-bank="{{ $transaction->recipient_bank_name ?? 'N/A' }}"
                  data-fees="{{ number_format($transaction->fees ?? 0, 2) }}"
                  data-recipient-amount="{{ number_format($transaction->recipient_amount ?? 0, 2) }}"
                  data-recipient-currency="{{ $transaction->recipient_bank_currency ?? $transaction->currency }}"
                  data-swap-from="{{ $transaction->swap_from_currency ?? '' }}"
                  data-swap-to="{{ $transaction->swap_to_currency ?? '' }}"
                  data-swap-from-amount="{{ number_format($transaction->swap_from_amount ?? 0, 2) }}"
                  data-swap-to-amount="{{ number_format($transaction->swap_to_amount ?? 0, 2) }}"
                  data-swap-rate="{{ $transaction->swap_rate ?? '' }}"
                  data-date="{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s M j, Y') }}"
                >
                  <!-- Type column -->
                  <td class="px-4 py-4">
                    @if($type === 'credit')
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                        <i class="fas fa-arrow-down text-[10px]"></i> Credit
                      </span>
                    @elseif($type === 'swap')
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700">
                        <i class="fas fa-exchange-alt text-[10px]"></i> Swap
                      </span>
                    @else
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                        <i class="fas fa-arrow-up text-[10px]"></i> Withdrawal
                      </span>
                    @endif
                  </td>

                  <td class="px-4 py-4 text-slate-500">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y') }}
                  </td>

                  <td class="px-4 py-4 hidden md:table-cell">
                    {{ $transaction->sender ?? 'N/A' }}
                  </td>

                  <td class="px-4 py-4 hidden md:table-cell">
                    @if($type === 'swap')
                      <span class="text-purple-600 font-medium">
                        {{ $transaction->swap_from_currency }} → {{ $transaction->swap_to_currency }}
                      </span>
                    @else
                      {{ $transaction->recipient_account_name ?? 'N/A' }}
                    @endif
                  </td>

                  <td class="px-4 py-4 font-semibold">
                    @if($type === 'swap')
                      {{ number_format($transaction->swap_from_amount ?? $transaction->amount, 2) }}
                      {{ $transaction->swap_from_currency ?? $transaction->currency }}
                    @else
                      {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                    @endif
                  </td>

                  <td class="px-4 py-4 text-slate-500">
                    {{ number_format($transaction->fees ?? 0, 2) }} {{ $transaction->to_currency }}
                  </td>

                  <td class="px-4 py-4">
                    @if($transaction->status === 'success')
                      <span class="status success">Success</span>
                    @elseif($transaction->status === 'failed')
                      <span class="status failed">Failed</span>
                    @else
                      <span class="status processing">Processing</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-12 text-slate-400">
                    <i class="fas fa-inbox text-3xl mb-3 block"></i>
                    No transactions found
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($transactions, 'links'))
          <div class="px-6 md:px-10 py-5 border-t border-slate-100 bg-slate-50/40">
            <div class="flex flex-col md:flex-row items-start md:items-center md:justify-between gap-3">
              <p class="text-sm text-slate-600">
                Showing
                <span class="font-semibold">{{ $transactions->firstItem() ?? 0 }}</span>
                to
                <span class="font-semibold">{{ $transactions->lastItem() ?? 0 }}</span>
                of
                <span class="font-semibold">{{ $transactions->total() ?? 0 }}</span>
                transactions
              </p>
              <div class="pagination-wrapper">
                {{ $transactions->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </section>

    <!-- Drawer -->
    <div id="transactionModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50"
         onclick="if(event.target===this) closeTransaction()">
      <div id="transactionPanel"
        class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition duration-300 flex flex-col">

        <div class="flex items-center justify-between p-5 border-b">
          <h2 class="font-bold text-lg">Transaction Details</h2>
          <button onclick="closeTransaction()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-3">

          <!-- Common rows -->
          <div class="info-row"><span>Type</span><p id="t-method"></p></div>
          <div class="info-row"><span>Status</span><p id="t-status"></p></div>
          <div class="info-row"><span>Reference</span><p id="t-reference" class="break-all text-xs"></p></div>
          <div class="info-row"><span>Date</span><p id="t-date"></p></div>
          <div class="info-row"><span>Fee</span><p id="t-fees"></p></div>

          <!-- Transfer rows (hidden for swap) -->
          <div id="transfer-rows">
            <div class="info-row mt-3"><span>Amount Sent</span><p id="t-amount"></p></div>
            <div class="info-row mt-3"><span>Recipient Gets</span><p id="t-recipient-amount"></p></div>
            <div class="info-row mt-3"><span>Sender</span><p id="t-sender"></p></div>
            <div class="info-row mt-3"><span>Recipient Name</span><p id="t-recipient"></p></div>
            <div class="info-row mt-3"><span>Account Number</span><p id="t-recipient-number"></p></div>
            <div class="info-row mt-3"><span>Bank</span><p id="t-bank"></p></div>
          </div>

          <!-- Swap rows (hidden for transfer/credit) -->
          <div id="swap-rows" class="hidden">
            <div class="info-row mt-3" style="background:#f5f3ff;">
              <span>You Swapped</span>
              <p id="t-swap-from" class="text-purple-700"></p>
            </div>
            <div class="info-row mt-3" style="background:#f5f3ff;">
              <span>You Received</span>
              <p id="t-swap-to" class="text-purple-700"></p>
            </div>
            <div class="info-row mt-3" style="background:#f5f3ff;">
              <span>Exchange Rate</span>
              <p id="t-swap-rate" class="text-purple-700"></p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <style>
    .info-row { background:#f9fafb; padding:14px; border-radius:12px; }
    .info-row span { font-size:12px; color:#6b7280; }
    .info-row p { font-weight:600; margin-top:4px; color:#111827; }
    .status { padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
    .status.success { background:#dcfce7; color:#15803d; }
    .status.failed  { background:#fee2e2; color:#b91c1c; }
    .status.processing { background:#fef9c3; color:#a16207; }
  </style>

  <script>
    function openTransaction(row) {
      const d      = row.dataset;
      const isSwap = d.type === 'swap';

      // Common
      document.getElementById("t-fees").textContent      = d.fees + " " + d.currency;
      document.getElementById("t-reference").textContent = d.reference;
      document.getElementById("t-date").textContent      = d.date;
      document.getElementById("t-status").textContent    = d.status.charAt(0).toUpperCase() + d.status.slice(1);

      if (isSwap) {
        document.getElementById("t-method").textContent   = "Swap";
        document.getElementById("t-swap-from").textContent = d.swapFromAmount + " " + d.swapFrom;
        document.getElementById("t-swap-to").textContent   = d.swapToAmount   + " " + d.swapTo;
        document.getElementById("t-swap-rate").textContent =
          "1 " + d.swapFrom + " = " + d.swapRate + " " + d.swapTo;

        document.getElementById("swap-rows").classList.remove("hidden");
        document.getElementById("transfer-rows").classList.add("hidden");

      } else {
        const typeLabel = d.type === 'credit' ? 'Credit' : 'Withdrawal';
        document.getElementById("t-method").textContent          = typeLabel;
        document.getElementById("t-amount").textContent          = d.amount + " " + d.currency;
        document.getElementById("t-recipient-amount").textContent = d.recipientAmount + " " + d.recipientCurrency;
        document.getElementById("t-sender").textContent          = d.sender;
        document.getElementById("t-recipient").textContent       = d.recipient;
        document.getElementById("t-recipient-number").textContent = d.recipientNumber;
        document.getElementById("t-bank").textContent            = d.bank;

        document.getElementById("swap-rows").classList.add("hidden");
        document.getElementById("transfer-rows").classList.remove("hidden");
      }

      const modal = document.getElementById("transactionModal");
      const panel = document.getElementById("transactionPanel");
      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
      setTimeout(() => panel.classList.remove("translate-x-full"), 10);
    }

    function closeTransaction() {
      const panel = document.getElementById("transactionPanel");
      panel.classList.add("translate-x-full");
      setTimeout(() => {
        document.getElementById("transactionModal").classList.add("hidden");
        document.body.style.overflow = "auto";
      }, 300);
    }

    // Sidebar
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('overlay');

    openBtn.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
    closeBtn.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    });
    overlay.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    });
  </script>
</body>
</html>