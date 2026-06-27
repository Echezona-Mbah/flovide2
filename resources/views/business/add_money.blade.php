@include('business.head')

<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
        {{ __('Add Money') }}
      </h1>
      @include('business.header_notifical')
    </header>

    <section class="w-full">
      <div class="mx-auto max-w-xl">

        {{-- Step progress bar --}}
        <div class="flex items-center gap-2 mb-6">
          <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-medium shrink-0" id="pip1">1</div>
          <div class="flex-1 h-px bg-gray-300"></div>
          <div class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0" id="pip2">2</div>
          <div class="flex-1 h-px bg-gray-300"></div>
          <div class="w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0" id="pip3">3</div>
        </div>

        {{-- Panel 1: Choose wallet --}}
        <div class="bg-white border border-gray-200 rounded-2xl mb-3 overflow-hidden">
          <div class="flex items-center justify-between px-4 py-4 cursor-pointer" onclick="togglePanel(1)">
            <div class="flex items-center gap-3">
              <div class="w-6 h-6 rounded-full border-2 border-blue-600 bg-blue-600 flex items-center justify-center text-white text-xs font-medium shrink-0" id="num1">1</div>
              <div>
                <p class="text-sm font-semibold text-gray-900">Choose wallet</p>
                <p class="text-xs text-gray-400" id="sub1">Which wallet are you funding?</p>
              </div>
            </div>
            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" id="chev1"></i>
          </div>
          <div class="px-4 pb-4 border-t border-gray-100" id="body1">
            <div class="flex flex-wrap gap-2 pt-3">
              @foreach($balances as $balance)
              @php
                $meta    = $balance->currency_meta ?? [];
                $symbol  = $meta['symbol']  ?? $balance->currency;
                $country = strtolower($meta['country'] ?? '');
              @endphp
              <div class="ctab flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full cursor-pointer bg-gray-50 hover:border-gray-300 transition {{ $loop->first ? 'active !border-2 !border-blue-600 !bg-blue-50' : '' }}"
                data-code="{{ $balance->currency }}"
                data-id="{{ $balance->id }}" 
                data-symbol="{{ $symbol }}"
                data-flag="{{ $country }}"
                onclick="selectCur(this)">
                @if($country)
                  <img src="https://flagcdn.com/w40/{{ $country }}.png" class="w-5 h-3.5 rounded-sm" />
                @endif
                <span class="text-sm font-medium text-gray-800">{{ $balance->currency }}</span>
              </div>
              @endforeach
            </div>
            {{-- CAD notice --}}
            <div id="cadNotice" class="hidden mt-3 flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 text-xs px-3 py-2 rounded-xl">
              <i class="fas fa-info-circle shrink-0"></i>
              CAD wallet uses <strong class="mx-1">Interac Auto Deposit</strong> — the payer receives an email request from Blaaiz.
            </div>
          </div>
        </div>

        {{-- Panel 2: Enter amount --}}
        <div class="bg-white border border-gray-200 rounded-2xl mb-3 overflow-hidden">
          <div class="flex items-center justify-between px-4 py-4 cursor-pointer" onclick="togglePanel(2)">
            <div class="flex items-center gap-3">
              <div class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0" id="num2">2</div>
              <div>
                <p class="text-sm font-semibold text-gray-900">Enter amount</p>
                <p class="text-xs text-gray-400" id="sub2">How much do you want to add?</p>
              </div>
            </div>
            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" id="chev2"></i>
          </div>
          <div class="px-4 pb-4 border-t border-gray-100 hidden" id="body2">
            <div class="text-center mt-4">
              <p class="text-xs text-gray-400 mb-2">Amount in <span id="curLabel">—</span></p>
              <div class="flex items-center justify-center gap-1">
                <span class="text-3xl font-semibold text-gray-400" id="amtSymbol">—</span>
                <input type="number" id="amtInput" placeholder="0" min="0"
                  class="text-5xl font-semibold text-gray-900 bg-transparent border-none outline-none w-48 text-center"
                  oninput="onAmt()" />
                <span class="text-base font-medium text-gray-400 self-end pb-1" id="amtCode">—</span>
              </div>
              <div class="flex gap-2 justify-center mt-3 flex-wrap">
                <button type="button" onclick="setAmt(50)"  class="qa px-4 py-1.5 text-xs border border-gray-200 rounded-full text-gray-500 bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition"></button>
                <button type="button" onclick="setAmt(100)" class="qa px-4 py-1.5 text-xs border border-gray-200 rounded-full text-gray-500 bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition"></button>
                <button type="button" onclick="setAmt(250)" class="qa px-4 py-1.5 text-xs border border-gray-200 rounded-full text-gray-500 bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition"></button>
                <button type="button" onclick="setAmt(500)" class="qa px-4 py-1.5 text-xs border border-gray-200 rounded-full text-gray-500 bg-gray-50 hover:border-blue-500 hover:text-blue-600 transition"></button>
              </div>
              <div id="amtConfirm" class="hidden mt-4 inline-flex items-center gap-2 bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium px-4 py-2 rounded-full">
                <i class="fas fa-check-circle text-blue-500"></i>
                <span id="amtConfirmText"></span>
              </div>
              <div class="mt-4">
                <button type="button" id="amtNextBtn" onclick="confirmAmt()" disabled
                  class="w-full py-3 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
                  Continue to Payment Method
                </button>
              </div>
            </div>
          </div>
        </div>

        {{-- Panel 3: Payment method (all currencies) → email appears below grid if CAD + Interac --}}
        <div class="bg-white border border-gray-200 rounded-2xl mb-3 overflow-hidden">
          <div class="flex items-center justify-between px-4 py-4 cursor-pointer" onclick="togglePanel(3)">
            <div class="flex items-center gap-3">
              <div class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0" id="num3">3</div>
              <div>
                <p class="text-sm font-semibold text-gray-900" id="panel3Title">Payment method</p>
                <p class="text-xs text-gray-400" id="sub3">How are you paying?</p>
              </div>
            </div>
            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" id="chev3"></i>
          </div>
          <div class="px-4 pb-5 border-t border-gray-100 hidden" id="body3">

            {{-- Payment method grid — always visible for all currencies --}}
            <div id="paymentMethodSection" class="grid grid-cols-2 gap-2 pt-3">
              <div class="pm-option active border-2 border-blue-600 bg-blue-50 rounded-xl p-3 cursor-pointer transition" id="pm-interac" onclick="selectPm('interac')">
                <i class="fas fa-paper-plane text-blue-600 text-lg mb-1"></i>
                <p class="text-sm font-medium text-gray-900">Interac</p>
                <p class="text-xs text-blue-500 font-medium">Available</p>
              </div>
              <div class="relative border border-gray-200 bg-gray-100 rounded-xl p-3 opacity-50 cursor-not-allowed select-none">
                <span class="absolute top-2 right-2 text-[10px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded-full font-medium">Soon</span>
                <i class="fas fa-credit-card text-gray-400 text-lg mb-1"></i>
                <p class="text-sm font-medium text-gray-400">Debit card</p>
                <p class="text-xs text-gray-400">Coming soon</p>
              </div>
              <div class="pm-option border border-gray-200 bg-white rounded-xl p-3 cursor-pointer transition" id="pm-bank" onclick="selectPm('bank')">
                <i class="fas fa-university text-blue-600 text-lg mb-1"></i>
                <p class="text-sm font-medium text-gray-900">Bank transfer</p>
                <p class="text-xs text-blue-500 font-medium">Available</p>
              </div>
              <div class="relative border border-gray-200 bg-gray-100 rounded-xl p-3 opacity-50 cursor-not-allowed select-none">
                <span class="absolute top-2 right-2 text-[10px] bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded-full font-medium">Soon</span>
                <i class="fab fa-bitcoin text-gray-400 text-lg mb-1"></i>
                <p class="text-sm font-medium text-gray-400">Crypto</p>
                <p class="text-xs text-gray-400">Coming soon</p>
              </div>
            </div>



          </div>
        </div>

        {{-- CTA --}}
        <button type="button" id="ctaBtn" disabled onclick="onSubmit()"
          class="w-full mt-2 py-4 rounded-xl bg-blue-600 text-white text-base font-semibold flex items-center justify-center gap-2 hover:bg-blue-700 transition disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
          <i class="fas fa-paper-plane" id="ctaIcon"></i>
          <span id="ctaLabel">Complete steps above</span>
        </button>
        <p class="text-center text-xs text-gray-400 mt-3 flex items-center justify-center gap-1">
          <i class="fas fa-shield-alt"></i> 256-bit encrypted · SSL secured
        </p>

      </div>
    </section>
  </main>

  {{-- Sidebar JS --}}
  <script>
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const overlay = document.getElementById('overlay');
    function openSidebar() { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); document.body.style.overflow = ''; }
    openBtn.addEventListener('click', openSidebar);
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) { sidebar.classList.remove('-translate-x-full'); overlay.classList.add('hidden'); document.body.style.overflow = ''; }
      else { sidebar.classList.add('-translate-x-full'); }
    });
  </script>

  {{-- Add Money JS --}}
  <script>
    let selCur = null;
    let selPm  = null;
    let isCAD  = false;

    // ── Accordion ─────────────────────────────────────────────────────────
    function togglePanel(n) {
      const body = document.getElementById('body' + n);
      const chev = document.getElementById('chev' + n);
      const isOpen = !body.classList.contains('hidden');
      body.classList.toggle('hidden', isOpen);
      chev.style.transform = isOpen ? '' : 'rotate(180deg)';
    }
    function openPanel(n) {
      document.getElementById('body' + n).classList.remove('hidden');
      document.getElementById('chev' + n).style.transform = 'rotate(180deg)';
    }
    function closePanel(n) {
      document.getElementById('body' + n).classList.add('hidden');
      document.getElementById('chev' + n).style.transform = '';
    }

    // ── Step 1: Wallet ─────────────────────────────────────────────────────
    function selectCur(el) {
      document.querySelectorAll('.ctab').forEach(t => {
        t.classList.remove('active', '!border-2', '!border-blue-600', '!bg-blue-50');
        t.classList.add('border-gray-200', 'bg-gray-50');
      });
      el.classList.add('active', '!border-2', '!border-blue-600', '!bg-blue-50');
      el.classList.remove('border-gray-200');

      selCur = {id: el.dataset.id, code: el.dataset.code, symbol: el.dataset.symbol };
      isCAD  = selCur.code === 'CAD';
      selPm  = null; // reset

      // Update amount panel labels
      document.getElementById('amtSymbol').textContent = selCur.symbol;
      document.getElementById('amtCode').textContent   = selCur.code;
      document.getElementById('curLabel').textContent  = selCur.code;
      document.querySelectorAll('.qa').forEach((btn, i) => {
        const vals = [50, 100, 250, 500];
        btn.textContent = selCur.symbol + vals[i].toLocaleString();
      });

      // Show/hide CAD notice in panel 1
      document.getElementById('cadNotice').classList.toggle('hidden', !isCAD);

      // Reset panel 3 title & subtitle
      document.getElementById('panel3Title').textContent = 'Payment method';
      document.getElementById('sub3').textContent = 'How are you paying?';

      // Reset step 3 pip & number badge
      const pip3 = document.getElementById('pip3');
      pip3.className = 'w-7 h-7 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0';
      pip3.textContent = '3';
      const num3 = document.getElementById('num3');
      num3.className = 'w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 text-xs font-medium shrink-0';
      num3.textContent = '3';

      // Clear payment method highlight
      document.querySelectorAll('.pm-option').forEach(opt => {
        opt.classList.remove('border-2', 'border-blue-600', 'bg-blue-50', 'active');
        opt.classList.add('border', 'border-gray-200', 'bg-white');
      });

      markDone(1, selCur.code + ' wallet selected');
      closePanel(1);
      openPanel(2);
      updateCta();
    }

    // ── Step 2: Amount ─────────────────────────────────────────────────────
    function setAmt(v) {
      document.getElementById('amtInput').value = v;
      onAmt();
    }

    function onAmt() {
      const amt        = parseFloat(document.getElementById('amtInput').value);
      const confirm    = document.getElementById('amtConfirm');
      const confirmTxt = document.getElementById('amtConfirmText');
      const nextBtn    = document.getElementById('amtNextBtn');

      if (!amt || amt <= 0) {
        confirm.classList.add('hidden');
        nextBtn.disabled = true;
        updateCta();
        return;
      }

      const fmt = v => parseFloat(v).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      confirmTxt.textContent = 'Adding ' + (selCur?.symbol ?? '') + fmt(amt) + ' ' + (selCur?.code ?? '') + ' to your wallet';
      confirm.classList.remove('hidden');
      nextBtn.disabled = false;

      updateCta();
    }

    function confirmAmt() {
      const amt = parseFloat(document.getElementById('amtInput').value);
      if (!amt || amt <= 0) return;

      const fmt = v => parseFloat(v).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      markDone(2, (selCur?.symbol ?? '') + fmt(amt) + ' ' + (selCur?.code ?? ''));
      closePanel(2);
      openPanel(3);
      updateCta();
    }

    // ── Step 3: Payment method selection ──────────────────────────────────
   function selectPm(type) {
    selPm = type;

    document.querySelectorAll('.pm-option').forEach(opt => {
      opt.classList.remove('border-2', 'border-blue-600', 'bg-blue-50', 'active');
      opt.classList.add('border', 'border-gray-200', 'bg-white');
    });
    const selected = document.getElementById('pm-' + type);
    if (selected) {
      selected.classList.add('active', 'border-2', 'border-blue-600', 'bg-blue-50');
      selected.classList.remove('border', 'border-gray-200', 'bg-white');
    }

    if (type === 'interac' || type === 'bank') {
      const amt      = parseFloat(document.getElementById('amtInput').value) || 0;
      const currency = selCur?.code   ?? '';
      const symbol   = encodeURIComponent(selCur?.symbol ?? '');
      const interacType = isCAD ? 'standard' : 'auto';

      const url = `{{ url('/add-money/interac') }}?type=${interacType}&method=${type}&amount=${amt}&currency=${currency}&symbol=${symbol}&balance_id=${selCur.id}`;
      window.location.href = url;

    } else {
      const labels = { card: 'Debit card', crypto: 'Crypto' };
      markDone(3, (labels[type] ?? type) + ' selected');
      updateCta();
    }
  }

    // ── Helpers ────────────────────────────────────────────────────────────
    function markDone(n, subtitle) {
      const num = document.getElementById('num' + n);
      const pip = document.getElementById('pip' + n);
      num.className = 'w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-medium shrink-0';
      num.innerHTML = '<i class="fas fa-check text-xs"></i>';
      pip.className = 'w-7 h-7 rounded-full bg-green-500 flex items-center justify-center text-white text-xs font-medium shrink-0';
      pip.textContent = '✓';
      document.getElementById('sub' + n).textContent = subtitle;
      if (n < 3) {
        document.getElementById('pip' + (n + 1)).className = 'w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-medium shrink-0';
        document.getElementById('num' + (n + 1)).className = 'w-6 h-6 rounded-full border-2 border-blue-600 bg-blue-600 flex items-center justify-center text-white text-xs font-medium shrink-0';
      }
    }

    function updateCta() {
      const amt = parseFloat(document.getElementById('amtInput').value);
      const btn = document.getElementById('ctaBtn');
      const lbl = document.getElementById('ctaLabel');
      const fmt = v => parseFloat(v).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

      if (!selCur || !(amt > 0) || !selPm) {
        btn.disabled    = true;
        lbl.textContent = 'Complete steps above';
        return;
      }

      btn.disabled    = false;
      lbl.textContent = 'Add ' + selCur.symbol + fmt(amt) + ' to ' + selCur.code + ' wallet';
    }

    // ── Submit (only reached for non-Interac methods) ──────────────────────
    function onSubmit() {
      Swal.fire({
        icon: 'info',
        title: 'Coming Soon',
        text: 'This payment method is not yet available for ' + (selCur?.code ?? '') + '.',
        confirmButtonColor: '#2563eb',
      });
    }
  </script>

</body>
</html>