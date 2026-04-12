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
          <div class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            <div class="w-full md:max-w-md">
              <label class="sr-only" for="search">Search transactions</label>
              <input
                type="search"
                id="search"
                placeholder="Search transactions"
                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200"
              />
            </div>

            <select
              class="w-full md:w-auto border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200">
              <option>All transactions</option>
              <option>Deposits</option>
              <option>Withdrawals</option>
              <option>Successful</option>
              <option>Failed</option>
            </select>
          </div>
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
                <th class="px-4 py-4 text-left">Status</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
              @forelse($transactions as $transaction)
                <tr onclick="openTransaction(this)"
                  class="cursor-pointer hover:bg-sky-50 transition"
                  data-amount="{{ $transaction->amount }}"
                  data-currency="{{ $transaction->currency }}"
                  data-reference="{{ $transaction->reference }}"
                  data-method="{{ $transaction->method }}"
                  data-status="{{ $transaction->status }}"
                  data-sender="{{ $transaction->sender }}"
                  data-recipient="{{ $transaction->recipient }}"
                  data-date="{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s M j, Y') }}"
                >
                  <td class="px-4 py-4">
                    @if($transaction->method == "deposit")
                      <span class="inline-flex items-center gap-2 text-emerald-700">
                        <i class="fas fa-arrow-down"></i> Deposit
                      </span>
                    @else
                      <span class="inline-flex items-center gap-2 text-rose-700">
                        <i class="fas fa-arrow-up"></i> Withdrawal
                      </span>
                    @endif
                  </td>

                  <td class="px-4 py-4">
                    {{ \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y') }}
                  </td>

                  <td class="px-4 py-4 hidden md:table-cell">
                    {{ $transaction->sender }}
                  </td>

                  <td class="px-4 py-4 hidden md:table-cell">
                    {{ $transaction->recipient_account_name }}
                  </td>

                  <td class="px-4 py-4 font-semibold">
                    {{ $transaction->amount }} {{ $transaction->currency }}
                  </td>

                  <td class="px-4 py-4">
                    @if($transaction->status == "success")
                      <span class="status success">Success</span>
                    @elseif($transaction->status == "failed")
                      <span class="status failed">Failed</span>
                    @else
                      <span class="status processing">Processing</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center py-8 text-slate-500">No transactions found</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

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
    <div id="transactionModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50">
      <div id="transactionPanel"
        class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition duration-300 flex flex-col">

        <div class="flex items-center justify-between p-5 border-b">
          <h2 class="font-bold text-lg">Transaction details</h2>
          <button onclick="closeTransaction()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-4">
          <div class="info-row"><span>Amount</span><p id="t-amount"></p></div>
          <div class="info-row"><span>Reference</span><p id="t-reference"></p></div>
          <div class="info-row"><span>Type</span><p id="t-method"></p></div>
          <div class="info-row"><span>Status</span><p id="t-status"></p></div>
          <div class="info-row"><span>Sender</span><p id="t-sender"></p></div>
          <div class="info-row"><span>Recipient</span><p id="t-recipient"></p></div>
          <div class="info-row"><span>Date</span><p id="t-date"></p></div>
        </div>
      </div>
    </div>
  </main>

  <style>
    .info-row { background:#f9fafb; padding:14px; border-radius:12px; }
    .info-row span { font-size:12px; color:#6b7280; }
    .info-row p { font-weight:600; margin-top:4px; }
    .status { padding:4px 10px; border-radius:999px; font-size:12px; font-weight:600; }
    .status.success { background:#dcfce7; color:#15803d; }
    .status.failed { background:#fee2e2; color:#b91c1c; }
    .status.processing { background:#fef9c3; color:#a16207; }
  </style>

  <script>
    function openTransaction(row) {
      document.getElementById("t-amount").textContent = row.dataset.amount + " " + row.dataset.currency;
      document.getElementById("t-reference").textContent = row.dataset.reference;
      document.getElementById("t-method").textContent = row.dataset.method;
      document.getElementById("t-status").textContent = row.dataset.status;
      document.getElementById("t-sender").textContent = row.dataset.sender;
      document.getElementById("t-recipient").textContent = row.dataset.recipient;
      document.getElementById("t-date").textContent = row.dataset.date;

      const modal = document.getElementById("transactionModal");
      const panel = document.getElementById("transactionPanel");

      modal.classList.remove("hidden");
      document.body.style.overflow = "hidden";
      setTimeout(() => panel.classList.remove("translate-x-full"), 10);
    }

    function closeTransaction() {
      const modal = document.getElementById("transactionModal");
      const panel = document.getElementById("transactionPanel");

      panel.classList.add("translate-x-full");
      setTimeout(() => {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
      }, 300);
    }

    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('overlay');

    function openSidebar() {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

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
