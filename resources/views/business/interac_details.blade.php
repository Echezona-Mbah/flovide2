@include('business.head')

<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">

    {{-- Desktop header --}}
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div class="flex items-center gap-3">
        <a href="{{ url('/add-money') }}"
          class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-50 transition">
          <i class="fas fa-arrow-left text-gray-600 text-sm"></i>
        </a>
        <h1 id="pageTitleDesktop" class="text-2xl font-extrabold leading-tight">Interac Auto Deposit</h1>
      </div>
      @include('business.header_notifical')
    </header>

    {{-- Mobile header --}}
    <div class="flex items-center gap-3 mb-5 md:hidden">
      <a href="{{ url('/add-money') }}"
        class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200">
        <i class="fas fa-arrow-left text-gray-600 text-sm"></i>
      </a>
      <h1 id="pageTitleMobile" class="text-lg font-bold">Interac Auto Deposit</h1>
    </div>

    <section class="w-full">
      <div class="mx-auto max-w-lg">

        {{-- Hero amount banner --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 mb-4 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
          <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
          <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3" id="heroBadge">
              <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-bolt text-white text-sm" id="heroIcon"></i>
              </div>
              <span class="text-sm font-medium text-blue-100" id="heroBadgeText">Auto Deposit · No security question</span>
            </div>
            <p class="text-blue-200 text-xs mb-1">You are depositing</p>
            <p class="text-4xl font-extrabold tracking-tight" id="amountDisplay">—</p>
            <p class="text-blue-200 text-xs mt-2 flex items-center gap-1" id="heroFooterText">
              <i class="fas fa-shield-alt text-xs"></i>
              Funds arrive automatically once payer approves
            </p>
          </div>
        </div>

        {{-- Fee breakdown card (Interac only, CAD) --}}
        @if($amount > 0)
        <div id="feeBreakdownCard" class="bg-white border border-gray-200 rounded-2xl p-4 mb-4">
          <div class="flex justify-between items-center mb-2 text-sm">
            <span class="text-gray-400">Amount</span>
            <span class="font-semibold text-gray-900">{{ number_format($amount, 2) }} {{ $balance->currency }}</span>
          </div>
          <div class="flex justify-between items-center mb-2 text-sm">
            <span class="text-gray-400 flex items-center gap-1.5">
              Collection fee
              @if($feeLabel)
                <span class="text-[10px] bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-full font-medium">{{ $feeLabel }}</span>
              @endif
            </span>
            <span class="font-semibold text-red-500">− {{ number_format($fee, 2) }} {{ $balance->currency }}</span>
          </div>
          <div class="border-t border-dashed border-gray-200 my-2"></div>
          <div class="flex justify-between items-center text-sm">
            <span class="text-gray-700 font-bold">You'll receive</span>
            <span class="font-extrabold text-green-600 text-base">{{ number_format($netAmount, 2) }} {{ $balance->currency }}</span>
          </div>
        </div>
        @endif

        {{-- How it works strip (Interac only) --}}
        <div id="howItWorksCard" class="bg-white border border-gray-200 rounded-2xl p-4 mb-4">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">How it works</p>
          <div class="flex items-center gap-0">
            <div class="flex flex-col items-center text-center flex-1">
              <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-1.5">
                <i class="fas fa-envelope text-blue-500 text-xs"></i>
              </div>
              <p class="text-[10px] font-medium text-gray-700 leading-tight">Enter payer's email</p>
            </div>
            <div class="flex-1 h-px border-t border-dashed border-gray-200 mb-4 mx-1"></div>
            <div class="flex flex-col items-center text-center flex-1">
              <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-1.5">
                <i class="fas fa-paper-plane text-blue-500 text-xs"></i>
              </div>
              <p class="text-[10px] font-medium text-gray-700 leading-tight">Flovide sends request</p>
            </div>
            <div class="flex-1 h-px border-t border-dashed border-gray-200 mb-4 mx-1"></div>
            <div class="flex flex-col items-center text-center flex-1">
              <div class="w-8 h-8 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mb-1.5">
                <i class="fas fa-mobile-alt text-blue-500 text-xs"></i>
              </div>
              <p class="text-[10px] font-medium text-gray-700 leading-tight">Payer approves in bank app</p>
            </div>
            <div class="flex-1 h-px border-t border-dashed border-gray-200 mb-4 mx-1"></div>
            <div class="flex flex-col items-center text-center flex-1">
              <div class="w-8 h-8 rounded-full bg-green-50 border border-green-100 flex items-center justify-center mb-1.5">
                <i class="fas fa-check text-green-500 text-xs"></i>
              </div>
              <p class="text-[10px] font-medium text-gray-700 leading-tight">Wallet funded!</p>
            </div>
          </div>
        </div>

        {{-- Bank Transfer card (shown only when method=bank) --}}
        <div id="bankDetailsCard" class="hidden bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
          <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
              <i class="fas fa-university text-green-600 text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">Bank Transfer Details</p>
              <p class="text-xs text-gray-400">Transfer to this account to fund your wallet</p>
            </div>
          </div>

          <div class="px-5 py-5">
            @if($balance && $balance->virtual_account_number)
              <div class="grid grid-cols-1 gap-y-3 text-sm bg-green-50 border border-green-100 rounded-xl p-4">
                <div class="flex justify-between">
                  <span class="text-gray-400">Account Number</span>
                  <span class="font-bold text-gray-900">{{ $balance->virtual_account_number }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Account Name</span>
                  <span class="font-bold text-gray-900">{{ $balance->virtual_account_name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-gray-400">Bank</span>
                  <span class="font-bold text-gray-900">{{ $balance->virtual_account_bank ?? 'N/A' }}</span>
                </div>
              </div>
              <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
                <i class="fas fa-info-circle"></i>
                Transfers usually reflect within a few minutes.
              </p>
            @else
              <div class="flex flex-col items-center justify-center py-8 text-center border border-dashed border-blue-200 rounded-xl bg-blue-50/40">
                <i class="fas fa-building-columns text-2xl text-blue-300 mb-2"></i>
                <p class="text-sm text-gray-500 mb-4 max-w-xs">No virtual account has been provisioned for this wallet yet.</p>
                <button
                  type="button"
                  id="createFidelityBtn"
                  data-balance-id="{{ $balance->id ?? '' }}"
                  onclick="createFidelityAccount(this)"
                  class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <span>Request Virtual Account</span>
                </button>
              </div>
            @endif
          </div>
        </div>

        {{-- Email form card (shown only when method=interac) --}}
        <div id="interacFormCard" class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
          <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
              <i class="fas fa-envelope text-blue-600 text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-bold text-gray-900">Payer's Email Address</p>
              <p class="text-xs text-gray-400">Who is sending you the money?</p>
            </div>
          </div>

          <div class="px-5 py-5">
            {{-- Email input --}}
            <div id="emailWrap" class="flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3.5 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-50 transition bg-gray-50">
              <i class="fas fa-at text-gray-400 text-sm shrink-0"></i>
              <input
                type="email"
                id="payerEmail"
                placeholder="payer@example.com"
                autocomplete="email"
                class="flex-1 bg-transparent text-sm text-gray-900 outline-none placeholder-gray-400"
                oninput="onEmail()"
              />
              <span id="emailCheck" class="hidden shrink-0">
                <i class="fas fa-check-circle text-green-500 text-base"></i>
              </span>
            </div>

            {{-- Validation message --}}
            <p id="emailHint" class="text-xs text-gray-400 mt-2 flex items-center gap-1">
              <i class="fas fa-info-circle"></i>
              The payer will receive an Interac email from Flovide and approves from their bank app.
            </p>
          </div>
        </div>

        

        {{-- Submit button (shown only when method=interac) --}}
        <button type="button" id="submitBtn" onclick="submitInterac()" disabled
          class="w-full py-4 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 active:scale-[0.99] transition-all flex items-center justify-center gap-2 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
          <i class="fas fa-paper-plane" id="submitIcon"></i>
          <span id="submitLabel">Enter payer email to continue</span>
        </button>

        <p class="text-center text-xs text-gray-400 mt-3 flex items-center justify-center gap-1">
          <i class="fas fa-shield-alt"></i> 256-bit encrypted · SSL secured
        </p>


        {{-- Auto Deposit card (shown only when method=interac & mode=autodeposit) --}}
<div id="autoDepositCard" class="hidden bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
  <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
      <i class="fas fa-bolt text-blue-600 text-sm"></i>
    </div>
    <div>
      <p class="text-sm font-bold text-gray-900">Send Interac e-Transfer To</p>
      <p class="text-xs text-gray-400">Use your own banking app to send the funds</p>
    </div>
  </div>

  <div class="px-5 py-5">
    <div class="flex items-center justify-between gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3.5">
      <span class="text-sm font-bold text-gray-900" id="depositEmailText">payment@flovide.com</span>
      <button type="button" onclick="copyDepositEmail()" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 shrink-0">
        <i class="fas fa-copy"></i> <span id="copyLabel">Copy</span>
      </button>
    </div>
    <p class="text-xs text-gray-400 mt-3 flex items-center gap-1">
      <i class="fas fa-info-circle"></i>
      No security question is required — the transfer is deposited automatically.
    </p>
  </div>
</div>

{{-- Confirming card (shown while polling for webhook confirmation) --}}
<div id="confirmingCard" class="hidden bg-white border border-gray-200 rounded-2xl p-8 mb-4 text-center">
  <div class="w-12 h-12 rounded-full border-4 border-blue-100 border-t-blue-600 animate-spin mx-auto mb-4"></div>
  <p class="text-sm font-bold text-gray-900 mb-1">Confirming your payment…</p>
  <p class="text-xs text-gray-400" id="confirmingSub">This usually takes a few moments once your bank sends the transfer.</p>
</div>

{{-- Auto Deposit submit button --}}
<button type="button" id="autoDepositBtn" onclick="markPaymentMade()"
  class="hidden w-full py-4 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 active:scale-[0.99] transition-all flex items-center justify-center gap-2 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
  <i class="fas fa-check"></i>
  <span>I've Made Payment</span>
</button>

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

  {{-- Page JS --}}
  <script>
    const params   = new URLSearchParams(window.location.search);
    const amount   = params.get('amount')   || '0';
    const currency = params.get('currency') || '';
    const symbol   = decodeURIComponent(params.get('symbol') || '');
    const method   = params.get('method')   || 'interac';

        const serverFee       = {{ $fee ?? 0 }};
    const serverNetAmount = {{ $netAmount ?? $amount ?? 0 }};

    const fmt = v => parseFloat(v).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('amountDisplay').textContent = symbol + fmt(amount) + ' ' + currency;

    // ── Toggle UI based on method ───────────────────────────────────────────
    if (method === 'bank') {
      document.getElementById('bankDetailsCard').classList.remove('hidden');
      document.getElementById('interacFormCard').classList.add('hidden');
      document.getElementById('submitBtn').classList.add('hidden');
      document.getElementById('howItWorksCard').classList.add('hidden');

      document.getElementById('pageTitleDesktop').textContent = 'Bank Transfer';
      document.getElementById('pageTitleMobile').textContent  = 'Bank Transfer';

      document.getElementById('heroIcon').className = 'fas fa-university text-white text-sm';
      document.getElementById('heroBadgeText').textContent = 'Bank Transfer · Instant reflection';
      document.getElementById('heroFooterText').innerHTML =
        '<i class="fas fa-shield-alt text-xs"></i> Funds reflect automatically once received';
    }

    // ── Email validation (Interac) ─────────────────────────────────────────
    function onEmail() {
      const email = document.getElementById('payerEmail').value.trim();
      const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      const wrap  = document.getElementById('emailWrap');
      const check = document.getElementById('emailCheck');
      const hint  = document.getElementById('emailHint');
      const btn   = document.getElementById('submitBtn');
      const lbl   = document.getElementById('submitLabel');

      if (valid) {
        wrap.classList.remove('border-gray-200');
        wrap.classList.add('border-green-400');
        check.classList.remove('hidden');
        hint.innerHTML = '<i class="fas fa-check-circle text-green-500"></i> <span class="text-green-600">Looks good!</span>';
        btn.disabled    = false;
        lbl.textContent = 'Send Interac Auto Deposit Request';
      } else {
        wrap.classList.remove('border-green-400');
        wrap.classList.add('border-gray-200');
        check.classList.add('hidden');
        hint.innerHTML = '<i class="fas fa-info-circle"></i> The payer will receive an Interac email from Flovide and approves from their bank app.';
        btn.disabled    = true;
        lbl.textContent = 'Enter payer email to continue';
      }
    }

        if (method === 'interac' && parseFloat(amount) > 0 && parseFloat(amount) <= serverFee) {
      document.getElementById('submitBtn').disabled = true;
      document.getElementById('submitLabel').textContent = 'Amount too low to cover fee';
      document.getElementById('emailHint').innerHTML =
        '<i class="fas fa-exclamation-triangle text-red-500"></i> <span class="text-red-500">Amount must exceed the ' +
        fmt(serverFee) + ' ' + currency + ' fee.</span>';
    }

    // ── Submit (Interac) ────────────────────────────────────────────────────
    async function submitInterac() {
      const email = document.getElementById('payerEmail').value.trim();
      const btn   = document.getElementById('submitBtn');
      const icon  = document.getElementById('submitIcon');
      const lbl   = document.getElementById('submitLabel');

      btn.disabled    = true;
      icon.className  = 'fas fa-spinner fa-spin';
      lbl.textContent = 'Sending request…';

      try {
        const res  = await fetch('{{ route("blaaiz.interac.initiate") }}', {
          method:  'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept':       'application/json',
          },
          body: JSON.stringify({ amount: parseFloat(amount), email, currency, balance_id: params.get('balance_id') }),
        });

        const data = await res.json();

        if (res.ok && data.success) {
          const d       = data.data ?? {};
          const expires = d.expires_at ? new Date(d.expires_at).toLocaleString() : '48 hours';

          await Swal.fire({
            icon: 'success',
            title: 'Request Sent!',
            html: `
              <div style="text-align:left;font-size:14px;line-height:2">
                <p><span style="color:#9ca3af">Amount:</span> <strong>${symbol}${fmt(amount)} ${currency}</strong></p>
                <p><span style="color:#9ca3af">Sent to:</span> <strong>${email}</strong></p>
                ${d.reference ? `<p><span style="color:#9ca3af">Reference:</span> <code style="background:#f3f4f6;padding:2px 8px;border-radius:6px;font-size:13px">${d.reference}</code></p>` : ''}
                <p><span style="color:#9ca3af">Expires:</span> ${expires}</p>
                <p style="margin-top:8px;font-size:12px;color:#9ca3af">The payer will approve from their bank app — funds arrive automatically.</p>
              </div>
            `,
            confirmButtonColor: '#2563eb',
            confirmButtonText:  'Go to Dashboard',
          });
          window.location.href = '{{ route("dashboard") }}';

        } else {
          const errMsg = data?.data?.message ?? data?.message ?? 'Something went wrong. Please try again.';
          Swal.fire({ icon: 'error', title: 'Failed', text: errMsg, confirmButtonColor: '#2563eb' });
        }

      } catch (err) {
        Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server. Check your connection.', confirmButtonColor: '#2563eb' });
      } finally {
        btn.disabled   = false;
        icon.className = 'fas fa-paper-plane';
        lbl.textContent = 'Send Interac Auto Deposit Request';
      }
    }

    // ── Create virtual account (Bank) ───────────────────────────────────────
    async function createFidelityAccount(btn) {
      const balanceId = btn.dataset.balanceId;
      const icon = btn.querySelector('i');
      const label = btn.querySelector('span');

      btn.disabled = true;
      icon.className = 'fas fa-spinner fa-spin';
      label.textContent = 'Creating account…';

      try {
        const res = await fetch(`/business/balance/${balanceId}/create-fidelity-account`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
        });

        const data = await res.json();

        if (res.ok && data.success) {
          window.location.reload();
        } else {
          btn.disabled = false;
          icon.className = 'fas fa-cloud-upload-alt';
          label.textContent = 'Create Virtual Account';
          Swal.fire({ icon: 'error', title: 'Failed', text: data.message ?? 'Could not create virtual account.' });
        }
      } catch (e) {
        btn.disabled = false;
        icon.className = 'fas fa-cloud-upload-alt';
        label.textContent = 'Create Virtual Account';
        Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server.' });
      }
    }


    const mode = params.get('mode') || 'request';

if (method === 'interac' && mode === 'autodeposit') {
  document.getElementById('interacFormCard').classList.add('hidden');
  document.getElementById('submitBtn').classList.add('hidden');
  document.getElementById('autoDepositCard').classList.remove('hidden');
  document.getElementById('autoDepositBtn').classList.remove('hidden');

  document.getElementById('heroBadgeText').textContent = 'Auto Deposit · Send it yourself';
}

function copyDepositEmail() {
  navigator.clipboard.writeText('payment@flovide.com').then(() => {
    const lbl = document.getElementById('copyLabel');
    lbl.textContent = 'Copied!';
    setTimeout(() => (lbl.textContent = 'Copy'), 1500);
  });
}

let pollTimer = null;
let pollAttempts = 0;
const MAX_POLL_ATTEMPTS = 60; // ~3 minutes at 3s interval

async function markPaymentMade() {
  const btn = document.getElementById('autoDepositBtn');
  btn.disabled = true;
  btn.querySelector('span').textContent = 'Registering…';

  try {
    const res = await fetch('{{ route("blaaiz.interac.autodeposit.initiate") }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept':       'application/json',
      },
      body: JSON.stringify({ amount: parseFloat(amount), currency, balance_id: params.get('balance_id') }),
    });

    const data = await res.json();

    if (!res.ok || !data.success) {
      Swal.fire({ icon: 'error', title: 'Could not start', text: data.message ?? 'Please try again.', confirmButtonColor: '#2563eb' });
      btn.disabled = false;
      btn.querySelector('span').textContent = "I've Made Payment";
      return;
    }

    const reference = data.data.reference;

    document.getElementById('autoDepositCard').classList.add('hidden');
    document.getElementById('autoDepositBtn').classList.add('hidden');
    document.getElementById('howItWorksCard')?.classList.add('hidden');
    document.getElementById('confirmingCard').classList.remove('hidden');

    pollAttempts = 0;
    pollTimer = setInterval(() => pollStatus(reference), 3000);
    pollStatus(reference); // fire immediately too

  } catch (err) {
    Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server.', confirmButtonColor: '#2563eb' });
    btn.disabled = false;
    btn.querySelector('span').textContent = "I've Made Payment";
  }
}

async function pollStatus(reference) {
  pollAttempts++;

  try {
    const res  = await fetch(`{{ url('/add-money/interac/status') }}/${reference}`, {
      headers: { 'Accept': 'application/json' },
    });
    const data = await res.json();

    if (res.ok && data.success) {
      if (data.data.status === 'success') {
        clearInterval(pollTimer);
        await Swal.fire({
          icon: 'success',
          title: 'Payment Confirmed!',
          text: 'Your wallet has been credited.',
          confirmButtonColor: '#2563eb',
          confirmButtonText: 'Go to Dashboard',
        });
        window.location.href = '{{ route("dashboard") }}';
        return;
      }

      if (data.data.status === 'failed') {
        clearInterval(pollTimer);
        Swal.fire({
          icon: 'error',
          title: 'Payment Not Confirmed',
          text: "We couldn't confirm this deposit. If you already sent it, please contact support with your reference.",
          confirmButtonColor: '#2563eb',
        });
        return;
      }
    }
  } catch (err) {
    // silently retry on network hiccups
  }

  if (pollAttempts >= MAX_POLL_ATTEMPTS) {
    clearInterval(pollTimer);
    document.getElementById('confirmingSub').innerHTML =
      'Still waiting on confirmation — this can take a little longer. We\'ll notify you once it\'s done. <a href="{{ route("dashboard") }}" class="text-blue-600 font-semibold">Return to Dashboard</a>';
  }
}
  </script>

</body>
</html>