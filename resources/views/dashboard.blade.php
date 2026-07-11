@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('business.sidebar')

  {{-- <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div> --}}
    <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
        {{ __('Dashboard') }}
      </h1>
      @include('business.header_notifical')
    </header>

    {{-- <section class="mx-auto max-w-6xl"> --}}
    <section class="w-full">

      @if (!auth()->user()->isFullyVerified())
      <div class="relative overflow-hidden rounded-2xl border border-blue-200 bg-blue-50 p-5 mb-6">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-200 rounded-full opacity-30"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-700">
                    <i class="fas fa-shield-alt text-lg"></i>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-blue-900">
                        Account verification required
                    </h4>

                    <p class="text-sm text-blue-800 mt-1 leading-relaxed">
                        For your safety and compliance, some features are temporarily unavailable.
                        Please complete your verification to unlock full access.
                    </p>
                </div>
            </div>

            <a href="{{ url('/compliance') }}"
              class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                <i class="fas fa-arrow-right"></i>
                Complete Verification
            </a>
        </div>
      </div>
      @endif

      <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">
        <div class="px-6 md:px-10 py-8 bg-[#215F9C] text-white border-b border-[#215F9C]/20">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-100">Wallet</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-white">Balances & Activity</h2>
              <p class="mt-2 text-sm text-slate-200 max-w-2xl">
                Overview of balances, live exchange rates, and recent transactions.
              </p>
            </div>
            <!-- <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
              Environment: <span class="font-semibold">Live/Test</span>
            </div> -->
          </div>
        </div>

        <div class="p-6 md:p-10 space-y-10">
          <!-- Balance Header -->
          <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full gap-4">
            <div>
              <p class="text-gray-500 text-sm mb-1">{{ __('Total Balance') }}</p>
              <h1 class="font-extrabold text-2xl md:text-3xl text-slate-900">
                {{-- {{ $balance }}{{ number_format($balance) }} --}}
              </h1>
            </div>

            <div class="flex flex-wrap justify-start gap-3 w-full md:w-auto">
              <a href="{{ route('send') }}" class="flex-1 md:flex-none">
                <button class="w-full md:w-auto flex items-center justify-center gap-2 rounded-full border border-blue-300 bg-blue-100 px-4 py-2 text-blue-700 text-sm font-medium hover:bg-blue-200 transition">
                  <i class="fas fa-file-invoice text-xs"></i>
                  {{ __('Send Money') }}
                </button>
              </a>

            <a href="{{ route('exchangesend') }}" class="flex-1 md:flex-none w-full md:w-auto">
              <button type="button"
                class="w-full md:w-auto flex items-center justify-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-gray-900 text-sm font-medium hover:bg-gray-50 transition">
                <i class="fas fa-cube text-xs"></i>
                {{ __('Exchange') }}
              </button>
            </a>


              <a href="{{ route('add_money') }}" class="flex-1 md:flex-none w-full md:w-auto">
                <button class="w-full md:w-auto flex items-center justify-center gap-2 rounded-full border border-gray-300 px-4 py-2 text-sm font-medium transition
                  {{ request()->routeIs('add_money')
                      ? 'bg-gray-100 text-gray-900'
                      : 'bg-white text-gray-900 hover:bg-gray-50' }}">
                  <i class="far fa-file-alt text-xs"></i>
                  {{ __('Add Money') }}
                </button>
              </a>
            </div>
          </div>

          <!-- Balances -->
          <!-- Balances -->
          <section>
            <div class="flex justify-between items-center mb-5">
              <h2 class="font-semibold text-lg">{{ __('Your Balances') }}</h2>
              <a href="{{ route('add_account.create') }}"
                class="text-sm px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                {{ __('Add Balance') }}
              </a>
            </div>

            @if($mode === 'test')
  <div class="flex gap-4 overflow-x-auto pb-2 sm:grid sm:grid-cols-2 lg:grid-cols-5 sm:overflow-visible">
    @foreach($balances as $balance)
    <a href="{{ route('balance.show', $balance->id) }}"
      class="min-w-[260px] sm:min-w-0 border border-amber-200 rounded-xl p-5 bg-amber-50 shadow-sm relative overflow-hidden hover:shadow-md transition cursor-pointer block">
      <span class="absolute top-2 right-2 text-[9px] font-bold uppercase tracking-widest text-amber-500 bg-amber-100 px-2 py-0.5 rounded-full">Test</span>

      <div class="flex items-center gap-2 mb-3">
        <img
          src="https://flagcdn.com/w20/{{ strtolower($balance->currency_meta['country']) }}.png"
          alt="Flag"
          width="20" height="15" class="rounded-sm"
        />
        <span class="text-sm font-semibold uppercase tracking-wide text-amber-700">
          {{ $balance->currency }}
        </span>
      </div>
      <p class="text-xs text-amber-500 mb-1">{{ $balance->name }}</p>
      <p class="text-xl font-bold text-amber-700">
        {{ $balance->currency_meta['symbol'] ?? '' }}{{ number_format($balance->amount, 2) }}
      </p>
    </a>
    @endforeach
  </div>
  <p class="mt-3 text-xs text-amber-600 font-medium">
    <i class="fas fa-flask text-xs mr-1"></i>
    You're viewing sandbox test balances.
  </p>

@else
  <div class="flex gap-4 overflow-x-auto pb-2 sm:grid sm:grid-cols-2 lg:grid-cols-5 sm:overflow-visible">
    @foreach($balances as $balance)
    <a href="{{ route('balance.show', $balance->id) }}"
      class="min-w-[260px] sm:min-w-0 border border-gray-200 rounded-xl p-5 bg-white shadow-sm hover:shadow-md hover:border-blue-300 transition cursor-pointer block">
      <div class="flex items-center gap-2 mb-3">
        <img
          src="https://flagcdn.com/w20/{{ strtolower($balance->currency_meta['country']) }}.png"
          alt="Flag of {{ strtoupper($balance->currency_meta['country']) }}"
          width="20" height="15" class="rounded-sm"
        />
        <span class="text-sm font-semibold uppercase tracking-wide">
          {{ $balance->currency }}
        </span>
        <i class="fas fa-chevron-right text-xs text-gray-300 ml-auto"></i>
      </div>
      <p class="text-xs text-gray-400 mb-1">{{ $balance->name }}</p>
      <p class="text-xl font-bold text-gray-900">
        {{ $balance->currency_meta['symbol'] }}{{ number_format($balance->amount, 2) }}
      </p>
    </a>
    @endforeach
  </div>
@endif
          </section>
          <!-- Exchange + Transactions -->
          <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
              <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Exchange Rate</h3>
                <span id="rateText" class="text-sm text-gray-500">Loading...</span>
              </div>

              <div class="bg-gray-50 rounded-xl p-4 mb-3">
                <div class="flex justify-between items-center gap-3">
                  <input id="fromAmount" type="number" value="100" class="bg-transparent text-2xl font-bold outline-none w-1/2" />
                  <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector relative w-full sm:w-auto" data-type="from">
                    <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 rounded-sm flag" />
                    <span class="font-medium code">GBP</span>
                    <i class="fas fa-chevron-down text-xs"></i>

                    <div class="currency-dropdown hidden absolute left-0 top-full w-full sm:w-64 bg-white border rounded shadow mt-2 z-50 max-h-48 overflow-y-auto p-2">
                      <input type="text" class="currency-search w-full border rounded px-2 py-1 mb-2 text-sm" placeholder="Search..." />
                      @foreach($allCurrencies as $c)
                        <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100"
                          data-code="{{ $c['code'] }}" data-flag="{{ $c['flag'] }}">
                          <img src="{{ $c['flag'] }}" class="w-5 h-4 rounded-sm" /> {{ $c['country_name'] }} {{ $c['code'] }}
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="flex justify-center my-2">
                <button class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white shadow hover:bg-blue-700 transition swap-btn">
                  <i class="fas fa-exchange-alt text-sm"></i>
                </button>
              </div>

              <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex justify-between items-center gap-3">
                  <input id="toAmount" type="number" value="0" class="bg-transparent text-2xl font-bold outline-none w-1/2" />
                  <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector relative w-full sm:w-auto" data-type="to">
                    <img src="https://flagcdn.com/w20/ng.png" class="w-5 h-4 rounded-sm flag" />
                    <span class="font-medium code">NGN</span>
                    <i class="fas fa-chevron-down text-xs"></i>

                    <div class="currency-dropdown hidden absolute left-0 top-full w-full sm:w-64 bg-white border rounded shadow mt-2 z-50 max-h-48 overflow-y-auto p-2">
                      <input type="text" class="currency-search w-full border rounded px-2 py-1 mb-2 text-sm" placeholder="Search..." />
                      @foreach($allCurrencies as $c)
                        <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100"
                          data-code="{{ $c['code'] }}" data-flag="{{ $c['flag'] }}">
                          <img src="{{ $c['flag'] }}" class="w-5 h-4 rounded-sm" /> {{ $c['country_name'] }} {{ $c['code'] }}
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="h-32 bg-gray-50 rounded-xl flex items-center justify-center text-sm text-gray-400">
                Exchange rate trend chart
              </div>
            </div>

            <div>
              <div class="flex items-center justify-between mb-5">
                <h3 class="font-semibold text-lg">{{ __('Transactions') }}</h3>
                <button class="flex items-center gap-1 text-gray-700 text-sm font-semibold hover:text-gray-900">
                  {{ __('See All') }}
                  <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                </button>
              </div>
              <div class="space-y-3 bg-gray-50 rounded-xl p-4 md:p-6 max-w-full overflow-x-auto">
                @forelse ($transactions as $tx)
                <div class="flex items-center justify-between bg-white rounded-lg p-3 md:p-4">
                  <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg 
                      {{ $tx->transaction_type === 'payment' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                      <i class="fas {{ $tx->transaction_type === 'payment' ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }}"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium">{{ $tx->sender ?? 'No name' }}</p>
                      <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y') }}</p>
                    </div>
                  </div>
                  <p class="font-semibold text-sm md:text-base">{{ number_format($tx->amount, 2) }} {{ $tx->currency }}</p>
                </div>
                @empty
                <p class="text-gray-400">{{ __('No transactions yet.') }}</p>
                @endforelse
              </div>
            </div>
          </section>


        </div>
      </div>
    </section>
  </main>


  {{-- <script type="module">
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.12.1/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.12.1/firebase-analytics.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyD3eJnJHIMq1HWO9ZJQWto5wt4aSiLW-2c",
    authDomain: "flovide-3331b.firebaseapp.com",
    projectId: "flovide-3331b",
    storageBucket: "flovide-3331b.firebasestorage.app",
    messagingSenderId: "796614246447",
    appId: "1:796614246447:web:89b781e09fcf87d8f246da",
    measurementId: "G-HE52EMSVDV"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script> --}}

  <!-- existing JS stays unchanged -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
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

<script>
document.addEventListener('DOMContentLoaded', () => {

  const swapBtn    = document.querySelector('.swap-btn');
  const selectors  = document.querySelectorAll('.currency-selector');
  const fromAmount = document.getElementById('fromAmount');
  const toAmount   = document.getElementById('toAmount');
  const rateText   = document.getElementById('rateText');

  let activeInput  = 'from'; // 'from' or 'to'
  let lastRate     = null;   // how many `to` units per 1 `from` unit
  let requestSeq   = 0;      // guards against stale/out-of-order responses
  let debounceTimer = null;

  function getCodes() {
    const from = document.querySelector('.currency-selector[data-type="from"] .code')?.innerText.trim();
    const to   = document.querySelector('.currency-selector[data-type="to"] .code')?.innerText.trim();
    return { from, to };
  }

  async function calculate() {
    const { from, to } = getCodes();
    const mySeq = ++requestSeq;

    const fromAmt = parseFloat(fromAmount.value || 0);
    const toAmt   = parseFloat(toAmount.value || 0);
    const amount  = activeInput === 'from' ? fromAmt : toAmt;

    if (!amount || amount <= 0) {
      if (mySeq !== requestSeq) return;
      if (activeInput === 'from') toAmount.value = 0;
      if (activeInput === 'to')   fromAmount.value = 0;
      rateText.innerText = '';
      return;
    }

    // Same currency — 1:1
    if (from === to) {
      if (mySeq !== requestSeq) return;
      lastRate = 1;
      if (activeInput === 'from') toAmount.value = fromAmt.toFixed(2);
      if (activeInput === 'to')   fromAmount.value = toAmt.toFixed(2);
      rateText.innerText = `1.00 ${from} = 1.00 ${to}`;
      return;
    }

    rateText.innerText = 'Loading...';

    try {
      // Always fetch using the from→to direction to derive the rate
      const fetchAmount = activeInput === 'from' ? fromAmt : 1;
      const url = `/dashboard/exchange-rate?from_currency=${from}&to_currency=${to}&amount=${fetchAmount}`;

      const res  = await fetch(url);
      const data = await res.json();

      // Drop this response if a newer request has since been fired
      if (mySeq !== requestSeq) return;

      if (data?.success && data?.data) {
        const converted = Number(data.data.converted || 0);
        lastRate = fetchAmount > 0 ? converted / fetchAmount : null;

        if (activeInput === 'from') {
          toAmount.value = converted.toFixed(2);
          rateText.innerText = `${fromAmt.toFixed(2)} ${from} → ${converted.toFixed(2)} ${to}`;
        } else {
          if (lastRate && lastRate > 0) {
            const computedFrom = toAmt / lastRate;
            fromAmount.value = computedFrom.toFixed(2);
            rateText.innerText = `${computedFrom.toFixed(2)} ${from} → ${toAmt.toFixed(2)} ${to}`;
          }
        }
      } else {
        rateText.innerText = data?.message || 'Rate not found';
      }
    } catch (err) {
      if (mySeq !== requestSeq) return;
      rateText.innerText = 'Error fetching rate';
    }
  }

  selectors.forEach(sel => {
    const dropdown = sel.querySelector('.currency-dropdown');
    const searchInput = sel.querySelector('.currency-search');

    sel.addEventListener('click', e => {
      e.stopPropagation();
      dropdown.classList.toggle('hidden');
      searchInput?.focus();
    });

    searchInput?.addEventListener('input', () => {
      const filter = searchInput.value.toLowerCase();
      dropdown.querySelectorAll('.currency-item').forEach(item => {
        const show = item.innerText.toLowerCase().includes(filter);
        item.style.display = show ? 'flex' : 'none';
      });
    });

    sel.querySelectorAll('.currency-item').forEach(item => {
      item.addEventListener('click', e => {
        e.stopPropagation();
        sel.querySelector('.code').textContent = item.dataset.code;
        sel.querySelector('.flag').src = item.dataset.flag;
        dropdown.classList.add('hidden');
        lastRate = null;
        calculate();
      });
    });
  });

  swapBtn.addEventListener('click', () => {
    const from = document.querySelector('.currency-selector[data-type="from"]');
    const to   = document.querySelector('.currency-selector[data-type="to"]');

    [from.querySelector('.code').innerText, to.querySelector('.code').innerText] =
    [to.querySelector('.code').innerText, from.querySelector('.code').innerText];

    [from.querySelector('.flag').src, to.querySelector('.flag').src] =
    [to.querySelector('.flag').src, from.querySelector('.flag').src];

    lastRate = null;
    activeInput = 'from';
    calculate();
  });

  // ── From input ──────────────────────────────────────────────────────────
  fromAmount.addEventListener('focus', () => { activeInput = 'from'; });
  fromAmount.addEventListener('input', () => {
    activeInput = 'from';
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(calculate, 400);
  });

  // ── To input ────────────────────────────────────────────────────────────
  toAmount.addEventListener('focus', () => { activeInput = 'to'; });
  toAmount.addEventListener('input', () => {
    activeInput = 'to';
    clearTimeout(debounceTimer);

    // Instant local calc if we already have a rate, no need to hit the API again
    if (lastRate && lastRate > 0) {
      requestSeq++; // invalidate any pending fetch, this instant calc wins
      const toAmt = parseFloat(toAmount.value || 0);
      if (toAmt > 0) {
        const { from, to } = getCodes();
        const computedFrom = toAmt / lastRate;
        fromAmount.value = computedFrom.toFixed(2);
        rateText.innerText = `${computedFrom.toFixed(2)} ${from} → ${toAmt.toFixed(2)} ${to}`;
        return;
      }
    }

    debounceTimer = setTimeout(calculate, 400);
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.currency-dropdown').forEach(drop => drop.classList.add('hidden'));
  });

  calculate();
});
</script>




</body>
</html>
