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
        <h1 class="text-2xl font-extrabold leading-tight">Interac Auto Deposit</h1>
      </div>
      @include('business.header_notifical')
    </header>

    {{-- Mobile header --}}
    <div class="flex items-center gap-3 mb-5 md:hidden">
      <a href="{{ url('/add-money') }}"
        class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200">
        <i class="fas fa-arrow-left text-gray-600 text-sm"></i>
      </a>
      <h1 class="text-lg font-bold">Interac Auto Deposit</h1>
    </div>

    <section class="w-full">
      <div class="mx-auto max-w-lg">

        {{-- Hero amount banner --}}
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 mb-4 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-8 translate-x-8"></div>
          <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/5 rounded-full translate-y-6 -translate-x-6"></div>
          <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fas fa-bolt text-white text-sm"></i>
              </div>
              <span class="text-sm font-medium text-blue-100">Auto Deposit · No security question</span>
            </div>
            <p class="text-blue-200 text-xs mb-1">You are depositing</p>
            <p class="text-4xl font-extrabold tracking-tight" id="amountDisplay">—</p>
            <p class="text-blue-200 text-xs mt-2 flex items-center gap-1">
              <i class="fas fa-shield-alt text-xs"></i>
              Funds arrive automatically once payer approves
            </p>
          </div>
        </div>

        {{-- How it works strip --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-4 mb-4">
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

        {{-- Email form card --}}
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden mb-4">
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

        {{-- Submit button --}}
        <button type="button" id="submitBtn" onclick="submitInterac()" disabled
          class="w-full py-4 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 active:scale-[0.99] transition-all flex items-center justify-center gap-2 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed">
          <i class="fas fa-paper-plane" id="submitIcon"></i>
          <span id="submitLabel">Enter payer email to continue</span>
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

  {{-- Page JS --}}
  <script>
    const params   = new URLSearchParams(window.location.search);
    const amount   = params.get('amount')   || '0';
    const currency = params.get('currency') || '';
    const symbol   = decodeURIComponent(params.get('symbol') || '');

    // ── Populate amount banner ───────────────────────────────────────────
    const fmt = v => parseFloat(v).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('amountDisplay').textContent = symbol + fmt(amount) + ' ' + currency;

    // ── Email validation ─────────────────────────────────────────────────
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

    // ── Submit ───────────────────────────────────────────────────────────
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
          body: JSON.stringify({ amount: parseFloat(amount), email, currency,balance_id: params.get('balance_id') }),
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
  </script>

</body>
</html>