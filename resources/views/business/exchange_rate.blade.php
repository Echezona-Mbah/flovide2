@include('business.head')

<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  @include('business.sidebar')
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">Exchange</h1>
      @include('business.header_notifical')
    </header>

    <section class="mx-auto max-w-6xl">
      <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">
        <div class="px-6 md:px-10 py-8 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 text-slate-900 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Exchange</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Wallet Exchange</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                Convert balances instantly with live rates.
              </p>
            </div>
            <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
              Environment: <span class="font-semibold">Live/Test</span>
            </div>
          </div>
        </div>

        <div class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-[1.2fr_360px] gap-10">
          <!-- Exchange Form -->
          <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-slate-900">Exchange</h3>
              <span id="rateText" class="text-sm text-slate-500">Loading...</span>
            </div>

            <!-- From -->
            <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
              <p class="text-xs uppercase tracking-wider text-slate-500 mb-2">From Wallet</p>
              <div class="flex items-center gap-3">
                <input id="fromAmount" type="number" value="100"
                  class="flex-1 bg-transparent text-2xl font-bold outline-none" />
                <select id="fromWallet" class="bg-white border border-slate-200 rounded-full px-4 py-2 text-sm">
                  @foreach($balances as $bal)
                    <option value="{{ $bal->currency }}" data-symbol="{{ $bal->currency_meta['symbol'] }}">
                      {{ $bal->currency }} — {{ $bal->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <p class="text-xs text-slate-500 mt-2">Balance: {{ number_format($balances[0]->amount ?? 0,2) }}</p>
            </div>

            <!-- Swap -->
            <div class="flex justify-center">
              <button id="swapBtn"
                class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center shadow hover:bg-blue-700 transition">
                <i class="fas fa-exchange-alt text-sm"></i>
              </button>
            </div>

            <!-- To -->
            <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50">
              <p class="text-xs uppercase tracking-wider text-slate-500 mb-2">To Wallet</p>
              <div class="flex items-center gap-3">
                <input id="toAmount" type="number" value="0" readonly
                  class="flex-1 bg-transparent text-2xl font-bold outline-none" />
                <select id="toWallet" class="bg-white border border-slate-200 rounded-full px-4 py-2 text-sm">
                  @foreach($balances as $bal)
                    <option value="{{ $bal->currency }}" data-symbol="{{ $bal->currency_meta['symbol'] }}">
                      {{ $bal->currency }} — {{ $bal->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <p class="text-xs text-slate-500 mt-2">Balance: {{ number_format($balances[1]->amount ?? 0,2) }}</p>
            </div>

            <!-- Fee -->
            <div class="rounded-2xl border border-slate-200 p-4 bg-white">
              <div class="flex justify-between text-sm">
                <span class="text-slate-500">Estimated Fee</span>
                <span id="feeText" class="font-semibold text-slate-900">$0.00</span>
              </div>
            </div>

            <button class="w-full bg-slate-900 text-white py-3 rounded-xl hover:bg-slate-800 transition">
              Exchange Now
            </button>
          </section>

          <!-- Summary -->
          <aside class="bg-slate-50 border border-slate-100 rounded-2xl p-6 space-y-4">
            <h4 class="font-semibold text-slate-900">Summary</h4>
            <div class="text-sm text-slate-600 space-y-2">
              <div class="flex justify-between">
                <span>From</span>
                <span id="summaryFrom">USD</span>
              </div>
              <div class="flex justify-between">
                <span>To</span>
                <span id="summaryTo">NGN</span>
              </div>
              <div class="flex justify-between">
                <span>Amount</span>
                <span id="summaryAmount">100.00</span>
              </div>
              <div class="flex justify-between">
                <span>Receive</span>
                <span id="summaryReceive">0.00</span>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>
  </main>

  <script>
    const fromAmount = document.getElementById('fromAmount');
    const toAmount = document.getElementById('toAmount');
    const fromWallet = document.getElementById('fromWallet');
    const toWallet = document.getElementById('toWallet');
    const rateText = document.getElementById('rateText');
    const feeText = document.getElementById('feeText');

    async function fetchRate() {
      const from = fromWallet.value;
      const to = toWallet.value;
      const amount = parseFloat(fromAmount.value || 0);

      if (!amount || amount <= 0) {
        toAmount.value = 0;
        rateText.textContent = '';
        return;
      }

      try {
        const res = await fetch(`/dashboard/exchange-rate?from=${from}&to=${to}&amount=${amount}`);
        const data = await res.json();

        if (data && data.rate) {
          toAmount.value = (amount * data.rate).toFixed(2);
          rateText.textContent = `1 ${from} = ${data.rate} ${to}`;
          feeText.textContent = data.fee ? `${data.fee}` : '$0.00';
        }
      } catch (e) {
        rateText.textContent = 'Rate unavailable';
      }

      document.getElementById('summaryFrom').textContent = from;
      document.getElementById('summaryTo').textContent = to;
      document.getElementById('summaryAmount').textContent = amount.toFixed(2);
      document.getElementById('summaryReceive').textContent = toAmount.value;
    }

    document.getElementById('swapBtn').addEventListener('click', () => {
      const temp = fromWallet.value;
      fromWallet.value = toWallet.value;
      toWallet.value = temp;
      fetchRate();
    });

    [fromAmount, fromWallet, toWallet].forEach(el => el.addEventListener('input', fetchRate));
    fetchRate();
  </script>
</body>
</html>
