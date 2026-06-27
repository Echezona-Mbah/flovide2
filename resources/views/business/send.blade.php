@include('business.head')
<body class="bg-[#EEF2F7] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')
  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">

    <!-- Page Header -->
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Payments</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Send Money</h1>
        <p class="mt-1 text-sm text-slate-500">Choose a saved beneficiary and send funds instantly.</p>
      </div>
      @include('business.header_notifical')
    </header>

    @if ($errors->any())
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          Swal.fire({ toast:true, position:'top-end', icon:'error', title:@json($errors->first()), showConfirmButton:false, timer:4000, timerProgressBar:true });
        });
      </script>
    @endif
    @if (session('error'))
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          Swal.fire({ toast:true, position:'top-end', icon:'error', title:@json(session('error')), showConfirmButton:false, timer:4000, timerProgressBar:true });
        });
      </script>
    @endif
    @if (session('success'))
      <script>
        document.addEventListener("DOMContentLoaded", () => {
          Swal.fire({ toast:true, position:'top-end', icon:'success', title:@json(session('success')), showConfirmButton:false, timer:4000, timerProgressBar:true });
        });
      </script>
    @endif

    <div class="max-w-7xl mx-auto">
      <div class="rounded-[28px] bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.3)] border border-slate-100 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 md:px-10 py-8 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Beneficiaries</p>
              <h2 class="mt-1 text-2xl md:text-3xl font-black tracking-tight text-slate-900">My Beneficiaries</h2>
              <p class="mt-1 text-sm text-slate-500">Select a recipient to send money.</p>
            </div>
            <div class="flex items-center gap-3">
              <span class="inline-flex items-center gap-2 bg-white/80 border border-sky-200 text-slate-700 text-xs font-semibold px-4 py-2 rounded-full">
                <span class="w-2 h-2 rounded-full {{ $mode === 'live' ? 'bg-emerald-500' : 'bg-amber-400' }}"></span>
                {{ ucfirst($mode) }} Mode
              </span>
              <a href="{{ route('add_beneficias.create') }}"
                class="inline-flex items-center gap-2 bg-slate-900 text-white text-xs font-semibold px-4 py-2 rounded-full hover:bg-slate-700 transition">
                <i class="fas fa-plus"></i> Add Beneficiary
              </a>
            </div>
          </div>
        </div>

        <!-- Search -->
        <div class="px-6 md:px-10 py-5 border-b border-slate-100 bg-slate-50/50">
          <div class="relative max-w-md">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input
              id="beneficiarySearch"
              type="search"
              placeholder="Search by name, account or bank..."
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky-300 bg-white"
            />
          </div>
        </div>

        <!-- Beneficiaries Grid -->
        <div class="p-6 md:p-10">
          @if($beneficiaries->isEmpty())
            <div class="text-center py-20">
              <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-slate-400 text-2xl"></i>
              </div>
              <p class="text-slate-500 font-medium">No beneficiaries yet.</p>
              <p class="text-sm text-slate-400 mt-1">Add a beneficiary to start sending money.</p>
            </div>
          @else
            <div id="beneficiaryGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
              @foreach($beneficiaries as $beneficiary)
                @php
                  $initials  = strtoupper(substr($beneficiary->account_name ?? 'N', 0, 2));
                  $isMobile  = strtolower($beneficiary->transfer_method ?? '') === 'mobile';
                  $isInterac = !empty($beneficiary->interac_email);
                  $dest      = $isMobile ? $beneficiary->phone : $beneficiary->account_number;
                  $colors    = ['bg-blue-100 text-blue-700','bg-purple-100 text-purple-700','bg-emerald-100 text-emerald-700','bg-rose-100 text-rose-700','bg-amber-100 text-amber-700','bg-sky-100 text-sky-700'];
                  $color     = $colors[$loop->index % count($colors)];
                @endphp

                <div
                  class="beneficiary-card group relative bg-white border border-slate-100 rounded-2xl p-5 cursor-pointer hover:border-sky-300 hover:shadow-lg transition-all duration-200"
                  data-id="{{ $beneficiary->recipient_id }}"
                  data-account-name="{{ $beneficiary->account_name }}"
                  data-account-number="{{ $beneficiary->account_number }}"
                  data-bank="{{ $beneficiary->bank }}"
                  data-currency="{{ $beneficiary->currency }}"
                  data-country="{{ $beneficiary->country }}"
                  data-phone="{{ $beneficiary->phone }}"
                  data-sortcode="{{ $beneficiary->bank_code }}"
                  data-transfermethod="{{ $beneficiary->transfer_method }}"
                  data-interac-email="{{ $beneficiary->email ?? '' }}"
                  data-interac-first-name="{{ $beneficiary->interac_first_name ?? '' }}"
                  data-interac-last-name="{{ $beneficiary->interac_last_name ?? '' }}"
                  onclick="openSendModal(this)">

                  <div class="selected-ring hidden absolute inset-0 rounded-2xl border-2 border-sky-500 pointer-events-none"></div>

                  <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-xl {{ $color }} flex items-center justify-center font-bold text-sm flex-shrink-0">
                      {{ $initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-bold text-slate-800 truncate">{{ $beneficiary->account_name }}</p>
                      <p class="text-xs text-slate-500 truncate mt-0.5">{{ $dest ?? 'N/A' }}</p>
                      <p class="text-xs text-slate-400 truncate">{{ $beneficiary->bank }}</p>
                    </div>
                    <span class="text-[11px] font-bold bg-slate-100 text-slate-600 px-2 py-1 rounded-lg flex-shrink-0">
                      {{ $beneficiary->currency }}
                    </span>
                  </div>

                  <div class="mt-3 flex flex-wrap gap-1.5">
                    @if($isMobile)
                      <span class="text-[10px] font-semibold bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full">
                        <i class="fas fa-mobile-alt mr-1"></i>Mobile
                      </span>
                    @endif
                    @if($isInterac)
                      <span class="text-[10px] font-semibold bg-red-50 text-red-500 px-2 py-0.5 rounded-full">
                        <i class="fas fa-bolt mr-1"></i>Interac
                      </span>
                    @endif
                    <span class="text-[10px] font-semibold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full">
                      <i class="fas fa-check-circle mr-1"></i>Saved
                    </span>
                  </div>

                  <div class="mt-4 flex justify-end">
                    <span class="w-8 h-8 rounded-full bg-slate-100 group-hover:bg-sky-500 flex items-center justify-center transition-all">
                      <i class="fas fa-arrow-right text-xs text-slate-500 group-hover:text-white"></i>
                    </span>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- ── Send Modal ──────────────────────────────────────────────────────── -->
    <div id="sendModal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
      <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative overflow-hidden">

        <div class="px-6 pt-6 pb-4 bg-gradient-to-r from-sky-50 to-blue-50 border-b border-slate-100">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div id="modalAvatar" class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm"></div>
              <div>
                <p id="modalName" class="font-bold text-slate-800 text-sm"></p>
                <p id="modalBank" class="text-xs text-slate-500"></p>
              </div>
            </div>
            <button onclick="closeSendModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>
        </div>

        <div class="p-6 space-y-5">

          <!-- Amount input -->
          <div>
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 block">You Send</label>

            <div class="flex items-center border-2 border-slate-200 rounded-2xl overflow-hidden focus-within:border-sky-400 transition">
              <span id="modalCurrencySymbol" class="px-4 text-lg font-bold text-slate-600 bg-slate-50 border-r border-slate-200 py-3 flex-shrink-0">₦</span>
              <input
                type="number"
                id="modalAmount"
                class="flex-1 px-4 py-3 text-xl font-bold focus:outline-none w-full"
                value="100"
                min="1"
                placeholder="0.00"
              />
            </div>

            @if(!empty($balanceList))
            <div class="mt-2 flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5">
              <img id="modalCurrencyFlag" src="https://flagcdn.com/24x18/ng.png" class="w-5 h-auto rounded shadow flex-shrink-0" alt="flag"/>
              <select id="modalCurrency" class="flex-1 bg-transparent text-sm font-semibold focus:outline-none cursor-pointer">
                @foreach($balanceList as $bal)
                  <option
                    value="{{ $bal->currency }}"
                    data-id="{{ $bal->id }}"
                    data-symbol="{{ $bal->currency_meta['symbol'] ?? '' }}"
                    data-country="{{ $bal->currency_meta['country'] ?? 'us' }}"
                    data-amount="{{ $bal->amount }}">
                    {{ $bal->currency }} — {{ $bal->currency_meta['symbol'] ?? '' }}{{ number_format($bal->amount, 2) }} available
                  </option>
                @endforeach
              </select>
              <i class="fas fa-chevron-down text-slate-400 text-xs flex-shrink-0"></i>
            </div>
            @endif

            <p id="balanceHint" class="text-xs text-slate-400 mt-1.5 ml-1"></p>
          </div>

          <!-- Rate card -->
          <div class="bg-slate-50 rounded-2xl p-4 space-y-2.5 text-sm">
            <div class="flex justify-between items-center">
              <span class="text-slate-500 flex items-center gap-2">
                <i class="fas fa-exchange-alt text-xs text-sky-500"></i> Exchange Rate
              </span>
              <span id="modalRate" class="font-semibold text-slate-700">--</span>
            </div>
            {{-- Transfer fee hidden for now --}}
            {{-- <div class="flex justify-between items-center">
              <span class="text-slate-500 flex items-center gap-2">
                <i class="fas fa-receipt text-xs text-amber-500"></i> Transfer Fee
              </span>
              <span id="modalFee" class="font-semibold text-slate-700">--</span>
            </div> --}}
            <div class="border-t border-slate-200 pt-2.5 flex justify-between items-center">
              <span class="text-slate-500 flex items-center gap-2">
                <i class="fas fa-clock text-xs text-slate-400"></i> Delivery
              </span>
              <span class="text-slate-600 text-xs">~15 min (up to 2 hrs)</span>
            </div>
          </div>

          <!-- Recipient gets -->
          <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-2">Beneficiary Receives</p>
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4 text-emerald-500 hidden" id="spinnerIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span id="modalRecipientAmount" class="text-2xl font-black text-emerald-700">0.00</span>
              </div>
              <div class="flex items-center gap-2">
                <img id="modalRecipientFlag" src="https://flagcdn.com/24x18/us.png" class="w-5 h-auto rounded shadow" alt="flag"/>
                <span id="modalRecipientCurrency" class="font-bold text-slate-700"></span>
              </div>
            </div>
          </div>

          <!-- Interac info -->
          <div id="interacInfo" class="hidden bg-red-50 border border-red-100 rounded-2xl p-3 text-xs text-red-600">
            <i class="fas fa-bolt mr-1"></i>
            Interac e-Transfer to: <strong id="interacEmailDisplay"></strong>
          </div>

          <!-- Send button -->
          <button id="proceedBtn"
            class="w-full bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold py-3.5 rounded-2xl hover:opacity-90 transition flex items-center justify-center gap-2">
            <i class="fas fa-paper-plane"></i> Review & Send
          </button>
        </div>
      </div>
    </div>

    <!-- ── Confirm Modal ───────────────────────────────────────────────────── -->
    <form method="POST" action="{{ route('send') }}">
      @csrf
      <input type="hidden" name="bank_code"          id="sort_codeInput">
      <input type="hidden" name="transfer_method"    id="transfermethodInput">
      <input type="hidden" name="bank"               id="bankInput">
      <input type="hidden" name="recipient_id"       id="recipientIdInput">
      <input type="hidden" name="balance_id"         id="balanceIdInput">
      <input type="hidden" name="amount"             id="amountInput">
      <input type="hidden" name="reference"          value="For invoice">
      <input type="hidden" name="transfer_fee"       id="transferFeeInput">
      <input type="hidden" name="total_amount"       id="totalAmountInput">
      <input type="hidden" name="exchange_rate"      id="exchangeRateInput">
      <input type="hidden" name="recipient_amount"   id="recipientAmountInput">
      <input type="hidden" name="account_number"     id="accountNumberInput">
      <input type="hidden" name="account_name"       id="accountNameInput">
      <input type="hidden" name="interac_email"      id="interacEmailInput">
      <input type="hidden" name="interac_first_name" id="interacFirstNameInput">
      <input type="hidden" name="interac_last_name"  id="interacLastNameInput">

      <div id="confirmModal" class="fixed inset-0 z-[60] bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative">

          <div class="px-6 pt-6 pb-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-lg font-black text-slate-800">Confirm Transfer</h2>
            <button type="button" onclick="closeConfirmModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-500">
              <i class="fas fa-times text-sm"></i>
            </button>
          </div>

          <div class="p-6 space-y-4">

            <!-- Recipient -->
            <div class="bg-slate-50 rounded-2xl p-4 space-y-2 text-sm">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Recipient</p>
              <div class="flex justify-between">
                <span class="text-slate-500">Name</span>
                <span id="cfmName" class="font-semibold text-slate-800"></span>
              </div>
              <div class="flex justify-between" id="cfmAccountRow">
                <span class="text-slate-500">Account</span>
                <span id="cfmAccount" class="font-semibold text-slate-800"></span>
              </div>
              <div class="flex justify-between hidden" id="cfmInteracRow">
                <span class="text-slate-500">Interac Email</span>
                <span id="cfmInteracEmail" class="font-semibold text-slate-800"></span>
              </div>
              <div class="flex justify-between">
                <span class="text-slate-500">Bank</span>
                <span id="cfmBank" class="font-semibold text-slate-800"></span>
              </div>
            </div>

            <!-- Transaction -->
            <div class="bg-slate-50 rounded-2xl p-4 space-y-2 text-sm">
              <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Transaction</p>
              <div class="flex justify-between">
                <span class="text-slate-500">Amount Sent</span>
                <span id="cfmAmount" class="font-semibold text-slate-800"></span>
              </div>
              {{-- Transfer fee hidden for now --}}
              {{-- <div class="flex justify-between">
                <span class="text-slate-500">Transfer Fee</span>
                <span id="cfmFee" class="font-semibold text-amber-600"></span>
              </div>
              <div class="flex justify-between border-t border-slate-200 pt-2">
                <span class="text-slate-600 font-semibold">Total Deducted</span>
                <span id="cfmTotal" class="font-bold text-slate-900"></span>
              </div> --}}
              <div class="flex justify-between">
                <span class="text-slate-500">Exchange Rate</span>
                <span id="cfmRate" class="font-semibold text-slate-800 text-xs"></span>
              </div>
            </div>

            <!-- They receive -->
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex justify-between items-center">
              <span class="text-emerald-600 font-semibold text-sm">They Receive</span>
              <span id="cfmReceive" class="font-black text-emerald-700 text-lg"></span>
            </div>

            <button type="submit"
              class="w-full bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold py-3.5 rounded-2xl hover:opacity-90 transition flex items-center justify-center gap-2">
              <i class="fas fa-check-circle"></i> Confirm & Send
            </button>
          </div>
        </div>
      </div>
    </form>

  </main>

  <style>
    .beneficiary-card.selected { border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56,189,248,0.2); }
  </style>

  <script>
  document.addEventListener("DOMContentLoaded", function () {

    const symbols = {
      USD:"$",NGN:"₦",EUR:"€",GBP:"£",GHS:"₵",KES:"KSh",ZAR:"R",
      XOF:"CFA",XAF:"FCFA",UGX:"USh",TZS:"TSh",CAD:"C$",AUD:"A$",
      INR:"₹",CNY:"¥",JPY:"¥",BRL:"R$",MXN:"Mex$",AED:"د.إ",
      SAR:"﷼",THB:"฿",MYR:"RM",IDR:"Rp",PHP:"₱",KRW:"₩",CHF:"Fr",
    };

    let selectedBeneficiary = null;
    let rateDebounce        = null;

    const modalAmount   = document.getElementById("modalAmount");
    const modalCurrency = document.getElementById("modalCurrency");

    // ── Search ──────────────────────────────────────────────────────────────
    document.getElementById("beneficiarySearch")?.addEventListener("input", function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll(".beneficiary-card").forEach(card => {
        card.style.display = card.innerText.toLowerCase().includes(q) ? "" : "none";
      });
    });

    // ── Open modal ──────────────────────────────────────────────────────────
    window.openSendModal = function (el) {
      document.querySelectorAll(".beneficiary-card").forEach(c => {
        c.classList.remove("selected");
        c.querySelector(".selected-ring")?.classList.add("hidden");
      });

      el.classList.add("selected");
      el.querySelector(".selected-ring")?.classList.remove("hidden");
      selectedBeneficiary = el;

      const name     = el.dataset.accountName  || "N/A";
      const bank     = el.dataset.bank         || "";
      const currency = el.dataset.currency     || "USD";
      const country  = el.dataset.country      || "us";
      const iEmail   = el.dataset.interacEmail || "";

      document.getElementById("modalAvatar").textContent             = name.substring(0, 2).toUpperCase();
      document.getElementById("modalName").textContent               = name;
      document.getElementById("modalBank").textContent               = bank;
      document.getElementById("modalRecipientCurrency").textContent  = currency;
      document.getElementById("modalRecipientFlag").src              = `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;

      const interacBox = document.getElementById("interacInfo");
      if (iEmail) {
        document.getElementById("interacEmailDisplay").textContent = iEmail;
        interacBox.classList.remove("hidden");
      } else {
        interacBox.classList.add("hidden");
      }

      // Reset amount
      modalAmount.value = "100";

      updateBalanceHint();
      updateRate();

      document.getElementById("sendModal").classList.remove("hidden");
      document.getElementById("sendModal").classList.add("flex");
    };

    window.closeSendModal = function () {
      document.getElementById("sendModal").classList.add("hidden");
      document.getElementById("sendModal").classList.remove("flex");
    };

    window.closeConfirmModal = function () {
      document.getElementById("confirmModal").classList.add("hidden");
      document.getElementById("confirmModal").classList.remove("flex");
    };

    // ── Balance hint ────────────────────────────────────────────────────────
    function updateBalanceHint() {
      const opt = modalCurrency?.selectedOptions[0];
      if (!opt) return;

      const bal  = parseFloat(opt.dataset.amount || 0);
      const sym  = opt.dataset.symbol || "";
      const hint = document.getElementById("balanceHint");

      hint.textContent = `Available: ${sym}${bal.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      })}`;

      const country = opt.dataset.country || "us";
      document.getElementById("modalCurrencyFlag").src        = `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;
      document.getElementById("modalCurrencySymbol").textContent = opt.dataset.symbol || "₦";
    }

    // ── Fetch rate ──────────────────────────────────────────────────────────
    async function updateRate() {
      if (!selectedBeneficiary) return;

      const opt          = modalCurrency?.selectedOptions[0];
      const fromCurrency = opt?.value || "NGN";
      const symbol       = opt?.dataset.symbol || "₦";
      const country      = opt?.dataset.country || "ng";
      const amount       = parseFloat(modalAmount?.value || 0);
      const toCurrency   = selectedBeneficiary.dataset.currency || "USD";

      document.getElementById("modalCurrencySymbol").textContent = symbol;
      document.getElementById("modalCurrencyFlag").src           = `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;

      if (!amount || amount <= 0) {
        document.getElementById("modalRate").textContent            = "--";
        document.getElementById("modalRecipientAmount").textContent = "0.00";
        return;
      }

      document.getElementById("spinnerIcon").classList.remove("hidden");

      if (fromCurrency === toCurrency) {
        document.getElementById("modalRate").textContent            = `1.00 ${fromCurrency} = 1.00 ${toCurrency}`;
        document.getElementById("modalRecipientAmount").textContent = amount.toFixed(2);
        document.getElementById("spinnerIcon").classList.add("hidden");
        return;
      }

      try {
        const res  = await fetch(
          `/dashboard/exchange-rate?from_currency=${fromCurrency}&to_currency=${toCurrency}&amount=${amount}`,
          { headers: { Accept: "application/json" } }
        );
        const data = await res.json();

        if (data?.success && data?.data) {
          const converted = parseFloat(data.data.converted || 0);
          // const fee = parseFloat(data.data.transfer_fee || 0); // fee disabled for now

          document.getElementById("modalRate").textContent =
            `${amount.toFixed(2)} ${fromCurrency} = ${converted.toFixed(2)} ${toCurrency}`;

          document.getElementById("modalRecipientAmount").textContent =
            converted.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        } else {
          document.getElementById("modalRate").textContent            = data?.message || "Rate unavailable";
          document.getElementById("modalRecipientAmount").textContent = "0.00";
        }

      } catch (e) {
        document.getElementById("modalRate").textContent            = "Error fetching rate";
        document.getElementById("modalRecipientAmount").textContent = "0.00";
      }

      document.getElementById("spinnerIcon").classList.add("hidden");
    }

    modalAmount?.addEventListener("input", () => {
      clearTimeout(rateDebounce);
      rateDebounce = setTimeout(updateRate, 500);
    });

    modalCurrency?.addEventListener("change", () => {
      updateBalanceHint();
      updateRate();
    });

    // ── Proceed to confirm ──────────────────────────────────────────────────
    document.getElementById("proceedBtn")?.addEventListener("click", () => {
      if (!selectedBeneficiary) {
        Swal.fire({ icon: "error", title: "Select a beneficiary first" });
        return;
      }

      const opt        = modalCurrency?.selectedOptions[0];
      const symbol     = opt?.dataset.symbol || "₦";
      const amount     = parseFloat(modalAmount?.value || 0);
      const rateText   = document.getElementById("modalRate").textContent;
      const receive    = document.getElementById("modalRecipientAmount").textContent.replace(/,/g, "");
      const currency   = selectedBeneficiary.dataset.currency;

      if (!amount || amount <= 0) {
        Swal.fire({ icon: "error", title: "Enter a valid amount" });
        return;
      }

      if (rateText.toLowerCase().includes("unavailable") || rateText === "--") {
        Swal.fire({ icon: "error", title: "Rate not available", text: "Please wait for a valid exchange rate." });
        return;
      }

      const fee   = 0; // fee temporarily disabled
      const total = amount + fee;

      const name    = selectedBeneficiary.dataset.accountName    || "";
      const account = selectedBeneficiary.dataset.accountNumber  || selectedBeneficiary.dataset.phone || "";
      const bank    = selectedBeneficiary.dataset.bank           || "";
      const iEmail  = selectedBeneficiary.dataset.interacEmail   || "";

      // Fill confirm modal display
      document.getElementById("cfmName").textContent    = name;
      document.getElementById("cfmAccount").textContent = account;
      document.getElementById("cfmBank").textContent    = bank;
      document.getElementById("cfmAmount").textContent  = `${symbol}${amount.toFixed(2)}`;
      document.getElementById("cfmRate").textContent    = rateText;
      document.getElementById("cfmReceive").textContent = `${currency} ${parseFloat(receive).toFixed(2)}`;

      if (iEmail) {
        document.getElementById("cfmInteracEmail").textContent = iEmail;
        document.getElementById("cfmInteracRow").classList.remove("hidden");
        document.getElementById("cfmAccountRow").classList.add("hidden");
      } else {
        document.getElementById("cfmInteracRow").classList.add("hidden");
        document.getElementById("cfmAccountRow").classList.remove("hidden");
      }

      // Fill hidden form inputs
      document.getElementById("amountInput").value           = amount.toFixed(2);
      document.getElementById("transferFeeInput").value      = fee.toFixed(2);      // 0.00
      document.getElementById("totalAmountInput").value      = total.toFixed(2);    // same as amount
      document.getElementById("exchangeRateInput").value     = rateText;
      document.getElementById("recipientAmountInput").value  = parseFloat(receive).toFixed(2);
      document.getElementById("accountNumberInput").value    = account;
      document.getElementById("accountNameInput").value      = name;
      document.getElementById("bankInput").value             = bank;
      document.getElementById("sort_codeInput").value        = selectedBeneficiary.dataset.sortcode      || "";
      document.getElementById("transfermethodInput").value   = selectedBeneficiary.dataset.transfermethod || "";
      document.getElementById("recipientIdInput").value      = selectedBeneficiary.dataset.id            || "";
      document.getElementById("balanceIdInput").value        = opt?.dataset.id                           || "";
      document.getElementById("interacEmailInput").value     = iEmail;
      document.getElementById("interacFirstNameInput").value = selectedBeneficiary.dataset.interacFirstName || "";
      document.getElementById("interacLastNameInput").value  = selectedBeneficiary.dataset.interacLastName  || "";

      document.getElementById("confirmModal").classList.remove("hidden");
      document.getElementById("confirmModal").classList.add("flex");
    });

    // ── Sidebar ─────────────────────────────────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const openBtn = document.getElementById('openSidebarBtn');
    const closeBtn = document.getElementById('closeSidebarBtn');
    const overlay  = document.getElementById('overlay');

    openBtn?.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    });
    closeBtn?.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    });
    overlay?.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.style.overflow = '';
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    });

  });
  </script>
</body>
</html>