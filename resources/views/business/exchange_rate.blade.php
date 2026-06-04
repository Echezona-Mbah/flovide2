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
            <!-- <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
              Environment: <span class="font-semibold">Live/Test</span>
            </div> -->
          </div>
        </div>

        <div class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-[1.2fr_360px] gap-10">
          <!-- Exchange Form -->
          <section class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-slate-900">Exchange</h3>
              <span id="rateText" class="text-sm text-slate-500">Loading...</span>
            </div>

            <form method="POST" action="{{ route('exchange.submit') }}" class="space-y-6">
              @csrf
              @if ($errors->any())
              <script>
                Swal.fire({ toast:true, position:'top-end', icon:'error', title:@json($errors->first()), showConfirmButton:false, timer:4000, timerProgressBar:true });
              </script>
              @endif

              @if (session('success'))
              <script>
                Swal.fire({ toast:true, position:'top-end', icon:'success', title:@json(session('success')), showConfirmButton:false, timer:4000, timerProgressBar:true });
              </script>
              @endif

              <input type="hidden" name="from_currency" id="fromCurrencyInput">
              <input type="hidden" name="to_currency" id="toCurrencyInput">
              <input type="hidden" name="amount" id="amountInput">

              <!-- From -->
              <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50 space-y-2">
                <p class="text-xs uppercase tracking-wider text-slate-500 mb-2">From Wallet</p>
                <div class="flex items-center gap-3">
                  <input id="fromAmount" type="number" value="100"
                    class="flex-1 bg-transparent text-2xl font-bold outline-none" />

                  <div class="relative w-56">
                    <button id="fromDropdownBtn" type="button"
                      class="w-full flex items-center gap-2 bg-white border border-slate-200 rounded-full px-3 py-2 text-sm">
                      <img id="fromFlag" src="https://flagcdn.com/w40/us.png" class="w-6 h-4 rounded-sm object-cover" />
                      <span id="fromLabel">USD — Default</span>
                    </button>

                    <div id="fromDropdown" class="hidden absolute z-20 mt-2 w-full bg-white border border-slate-200 rounded-lg shadow max-h-60 overflow-y-auto">
                      @foreach($balances as $bal)
                        <div class="from-option flex items-center gap-2 px-3 py-2 hover:bg-slate-100 cursor-pointer"
                          data-value="{{ $bal->currency }}"
                          data-label="{{ $bal->currency }} — {{ $bal->name }}"
                          data-balance="{{ $bal->amount }}"
                          data-country="{{ $bal->currency_meta['country'] }}">
                          <img src="https://flagcdn.com/w40/{{ strtolower($bal->currency_meta['country']) }}.png"
                            class="w-6 h-4 rounded-sm object-cover" />
                          <span>{{ $bal->currency }} — {{ $bal->name }}</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <p class="text-xs text-slate-500">Balance: <span id="fromBalanceText">0.00</span></p>
              </div>

              <!-- Swap -->
              <div class="flex justify-center">
                <button id="swapBtn" type="button"
                  class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center shadow hover:bg-blue-700 transition">
                  <i class="fas fa-exchange-alt text-sm"></i>
                </button>
              </div>

              <!-- To -->
              <div class="rounded-2xl border border-slate-200 p-4 bg-slate-50 space-y-2">
                <p class="text-xs uppercase tracking-wider text-slate-500 mb-2">To Wallet</p>
                <div class="flex items-center gap-3">
                  <input id="toAmount" type="number" value="0" readonly
                    class="flex-1 bg-transparent text-2xl font-bold outline-none" />

                  <div class="relative w-56">
                    <button id="toDropdownBtn" type="button"
                      class="w-full flex items-center gap-2 bg-white border border-slate-200 rounded-full px-3 py-2 text-sm">
                      <img id="toFlag" src="https://flagcdn.com/w40/ng.png" class="w-6 h-4 rounded-sm object-cover" />
                      <span id="toLabel">NGN — Default</span>
                    </button>

                    <div id="toDropdown" class="hidden absolute z-20 mt-2 w-full bg-white border border-slate-200 rounded-lg shadow max-h-60 overflow-y-auto">
                      @foreach($balances as $bal)
                        <div class="to-option flex items-center gap-2 px-3 py-2 hover:bg-slate-100 cursor-pointer"
                          data-value="{{ $bal->currency }}"
                          data-label="{{ $bal->currency }} — {{ $bal->name }}"
                          data-balance="{{ $bal->amount }}"
                          data-country="{{ $bal->currency_meta['country'] }}">
                          <img src="https://flagcdn.com/w40/{{ strtolower($bal->currency_meta['country']) }}.png"
                            class="w-6 h-4 rounded-sm object-cover" />
                          <span>{{ $bal->currency }} — {{ $bal->name }}</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <p class="text-xs text-slate-500">Balance: <span id="toBalanceText">0.00</span></p>
              </div>

              <!-- Fee -->
              <div class="rounded-2xl border border-slate-200 p-4 bg-white">
                <div class="flex justify-between text-sm">
                  <span class="text-slate-500">Estimated Fee</span>
                  <span id="feeText" class="font-semibold text-slate-900">0.00</span>
                </div>
              </div>

              <button class="w-full bg-slate-900 text-white py-3 rounded-xl hover:bg-slate-800 transition">
                Exchange Now
              </button>
            </form>
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
    const rateText = document.getElementById('rateText');
    const feeText = document.getElementById('feeText');

    let fromCurrency = null;
    let toCurrency = null;

    function updateHiddenInputs(amount) {
      document.getElementById('fromCurrencyInput').value = fromCurrency || '';
      document.getElementById('toCurrencyInput').value = toCurrency || '';
      document.getElementById('amountInput').value = amount || 0;
    }

    function selectFrom(option) {
      fromCurrency = option.dataset.value;
      document.getElementById('fromLabel').textContent = option.dataset.label;
      document.getElementById('fromFlag').src = `https://flagcdn.com/w40/${option.dataset.country.toLowerCase()}.png`;
      document.getElementById('fromBalanceText').textContent = parseFloat(option.dataset.balance).toFixed(2);
      document.getElementById('fromDropdown').classList.add('hidden');
      fetchRate();
    }

    function selectTo(option) {
      toCurrency = option.dataset.value;
      document.getElementById('toLabel').textContent = option.dataset.label;
      document.getElementById('toFlag').src = `https://flagcdn.com/w40/${option.dataset.country.toLowerCase()}.png`;
      document.getElementById('toBalanceText').textContent = parseFloat(option.dataset.balance).toFixed(2);
      document.getElementById('toDropdown').classList.add('hidden');
      fetchRate();
    }

    async function fetchRate() {
      if (!fromCurrency || !toCurrency) return;

      const amount = parseFloat(fromAmount.value || 0);
      if (!amount || amount <= 0) return;

      try {
        const res = await fetch(`/dashboard/exchange-rate?from_currency=${fromCurrency}&to_currency=${toCurrency}&amount=${amount}`);
        const json = await res.json();

        if (json?.success && json?.data) {
          const converted = parseFloat(json.data.converted || 0);
          const fee = parseFloat(json.data.transfer_fee || 0);

          toAmount.value = converted.toFixed(2);

          const ratePerOne = amount > 0 ? (converted / amount) : 0;
          rateText.textContent = `${fromCurrency} 1.00 = ${ratePerOne.toFixed(2)} ${toCurrency}`;

          feeText.textContent = fee.toFixed(2);
        } else {
          rateText.textContent = 'Rate unavailable';
        }
      } catch (e) {
        rateText.textContent = 'Rate unavailable';
      }

      document.getElementById('summaryFrom').textContent = fromCurrency;
      document.getElementById('summaryTo').textContent = toCurrency;
      document.getElementById('summaryAmount').textContent = amount.toFixed(2);
      document.getElementById('summaryReceive').textContent = toAmount.value;

      updateHiddenInputs(amount);
    }

    document.getElementById('fromDropdownBtn').addEventListener('click', () => {
      document.getElementById('fromDropdown').classList.toggle('hidden');
    });

    document.getElementById('toDropdownBtn').addEventListener('click', () => {
      document.getElementById('toDropdown').classList.toggle('hidden');
    });

    document.querySelectorAll('.from-option').forEach(opt => {
      opt.addEventListener('click', () => selectFrom(opt));
    });
    document.querySelectorAll('.to-option').forEach(opt => {
      opt.addEventListener('click', () => selectTo(opt));
    });

    const firstFrom = document.querySelector('.from-option');
    const firstTo = document.querySelector('.to-option');
    if (firstFrom) selectFrom(firstFrom);
    if (firstTo) selectTo(firstTo);

    document.addEventListener('click', (e) => {
      if (!document.getElementById('fromDropdownBtn').contains(e.target)) {
        document.getElementById('fromDropdown').classList.add('hidden');
      }
      if (!document.getElementById('toDropdownBtn').contains(e.target)) {
        document.getElementById('toDropdown').classList.add('hidden');
      }
    });

    fromAmount.addEventListener('input', fetchRate);
  </script>
</body>
</html>
