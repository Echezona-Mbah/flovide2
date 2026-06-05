@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<style>
/* Wrapper for safety */
.select-wrapper {
  width: 100%;
}

/* Force TomSelect to full width and big height */
.select-wrapper .ts-control {
  min-height: 40px !important;   /* always big */
  height: 40px !important;       /* force exact height */
  padding: 0 16px !important;    /* padding inside */
  font-size: 16px !important;    /* readable font */
  display: flex !important;
  align-items: center !important;
  border-radius: 12px !important;
  border: 1px solid #e5e7eb !important;
  box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
  transition: all 0.2s ease;
}

/* Hover and focus */
.select-wrapper .ts-control:hover {
  border-color: #6366f1 !important;
}

.select-wrapper .ts-control.focus {
  border-color: #4f46e5 !important;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.2) !important;
}
    /* ✅ Styles for account validation */
    #accountHolder.validated {
        background-color: #e0f7e9; /* light green */
        border: 2px solid #28a745; /* green border */
        color: #155724; /* dark green text */
        font-weight: 500;
    }

    #accountHolder.invalid {
        background-color: #fbeaea; /* light red */
        border: 2px solid #dc3545; /* red border */
        color: #721c24; /* dark red text */
        font-weight: 500;
    }

</style>


    <!-- Mobile menu button -->
  @include('business.header')

    <!-- Sidebar -->
   @include('business.sidebar')
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Payout accounts
            </h1>
            @include('business.header_notifical')
        </header>
     {{-- <section class="w-full rounded-3xl bg-white min-h-screen p-4 md:p-8"> --}}

 {{-- <section class="min-h-screen bg-white py-4 px-2">
  <div class="max-w-4xl mx-auto bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-6 md:p-12 space-y-8">

    <!-- Header -->
    <div class="text-center space-y-1">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Add New Beneficiary</h2>
      <p class="text-gray-500 text-sm md:text-base">Securely add an international IBAN beneficiary</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('add_beneficias.store') }}" class="space-y-6 md:space-y-8" autocomplete="off">
      @csrf

      <!-- Alerts -->
      @if ($errors->any())
        <script>
          Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'error',
              title: @json($errors->first()),
              showConfirmButton: false,
              timer: 4000,
              timerProgressBar: true,
          });
        </script>
      @endif

      @if (session('success'))
        <script>
          Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: @json(session('success')),
              showConfirmButton: false,
              timer: 4000,
              timerProgressBar: true,
          });
        </script>
      @endif

      @if (session('api_error'))
        <script>
          Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'error',
              title: @json(session('api_error')),
              showConfirmButton: false,
              timer: 4000,
              timerProgressBar: true,
          });
        </script>
      @endif

      <!-- Beneficiary Information -->
      <div class="bg-gray-50 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-base md:text-lg">👤 Beneficiary Information</h3>

        <!-- Country & Currency -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        
        

            <!-- Country -->
            <div>
            <label class="text-sm font-medium mb-1 block">Country</label>
            <div class="select-wrapper">
                <select id="countrySelect" name="bank[country]">
                <option value=""></option>
                @foreach($countries as $country)
                    <option value="{{ $country->country_iso }}">{{ $country->country_name }}</option>
                @endforeach
                </select>
            </div>
            </div>


          <!-- Currency -->
          <div>
            <label class="text-sm font-medium mb-1 block">Currency</label>
            <div class="select-wrapper">
              <select id="currencySelect" name="bank[currency]">
                <option value=""></option>
                @foreach($countries->unique('currency_iso') as $country)
                  <option value="{{ $country->currency_iso }}">{{ $country->currency_iso }}</option>
                @endforeach
              </select>
            </div>
          </div>



        </div>

        <!-- Beneficiary Type -->
        <select id="beneficiaryType" name="type" class="w-full mt-2 border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base">
          <option value="">Select Type</option>
          <option value="individual">Individual</option>
          <option value="corporate">Corporate</option>
        </select>

        <!-- Individual Fields -->
        <div id="individualFields" class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2 hidden">
          <input autocomplete="new-password" type="text" name="firstNames" placeholder="First Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          <input autocomplete="new-password" type="text" name="lastName" placeholder="Last Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
        </div>

        <!-- Corporate Fields -->
        <div id="corporateFields" class="mt-2 hidden">
          <input autocomplete="new-password" type="text" name="name" placeholder="Company Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
        </div>

      </div>

      <!-- Bank Details -->
      <div class="bg-gray-50 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-base md:text-lg">🏦 Bank Details</h3>

        <select id="transferMethod" name="transfer_method" class="w-full border rounded-xl px-3 py-2 hidden">
        <option value="">Select Transfer Method</option>
        <option value="mobile">Mobile Money</option>
        <option value="bank">Bank Transfer</option>
        </select>


        <!-- Bank Fields -->
        <input type="text" id="iban" name="bank[iban]" placeholder="IBAN" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input type="text" id="accountNumber" name="bank[accountNumber]" placeholder="Account Number" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input type="text" id="sortCode" name="bank[sortCode]" placeholder="Sort Code" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input type="text" id="bankCode" name="bank[bankCode]" placeholder="Bank Code" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input type="text" id="swiftBic" name="bank[swiftBic]" placeholder="SWIFT / BIC" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input type="text" id="routing" name="bank[routing]" placeholder="Routing Number" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
        <input id="mobileNumber" name="bank[mobileNumber]" placeholder="Mobile Number"class="w-full border rounded-xl px-3 py-2 hidden"/>


            <select id="bankSelect" name="bank[bankCode]" class="w-full border rounded-xl px-3 py-2 hidden">
                <option value="">Select Bank</option>
            </select>

        <!-- Account Type -->
        <select id="accountType" name="bank[accountType]" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden">
          <option value="">Account Type</option>
          <option value="checking">Checking</option>
          <option value="savings">Savings</option>
        </select>

         <input 
            id="accountHolder"
            type="text" 
            name="bank[accountHolder]" 
            placeholder="Account Holder"
            class="w-full border rounded-xl px-3 py-2 transition-all duration-300"
            />
            <div id="accountLoading" class="text-sm text-gray-500 hidden">
            ⏳ Fetching account name…
            </div>
            <div id="accountManualHint" class="text-sm text-red-600 hidden">
            We couldn’t verify this account name. Please enter it manually and ensure it is correct.
            </div>

      </div>

      <!-- Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 mt-2">
        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 rounded-xl shadow-lg hover:scale-[1.02] hover:shadow-xl transition">
          Create Beneficiary
        </button>
        <a href="{{ route('beneficias') }}" type="button" class="flex-1 border py-3 rounded-xl text-center hover:bg-gray-100 transition">
            Cancel
        </a>
      </div>

    </form>
  </div>
</section> --}}

<section class="mx-auto max-w-5xl">
  <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">
    <div class="px-6 md:px-10 py-8 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 text-slate-900 border-b border-sky-200/70">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
        <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Payout Accounts</p>
        <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Add Beneficiary</h2>
        <p class="mt-2 text-sm text-slate-600 max-w-2xl">
            Create a new beneficiary with validated bank details. If validation fails, you can still enter the name manually.
        </p>
        </div>
        <!-- <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
        Environment: <span class="font-semibold">Live/Test</span>
        </div> -->
    </div>
    </div>


    <form method="POST" action="{{ route('add_beneficias.store') }}" class="p-6 md:p-10 space-y-10" autocomplete="off">
      @csrf
       <!-- Alerts -->
    @if(session('error') || session('success') || session('api_error') || $errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    const msg = @json(session('error') ?? session('api_error') ?? session('success') ?? ($errors->any() ? $errors->first() : null));
    const icon = @json(session('error') || session('api_error') || $errors->any() ? 'error' : 'success');

    if (!msg) return;

    if (window.Swal) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: msg,
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true
        });
    } else {
        alert(msg);
    }
});
</script>
@endif

      <!-- Beneficiary Info -->
      <div class="grid lg:grid-cols-2 gap-8">
        <div class="space-y-6">
          <div>
            <h3 class="text-base font-semibold text-slate-900">Beneficiary</h3>
            <p class="text-sm text-slate-500">Country, currency, and identity details.</p>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-medium mb-1 block">Country</label>
              <div class="select-wrapper">
                <select id="countrySelect" name="bank[country]">
                  <option value=""></option>
                  @foreach($countries as $country)
                    <option value="{{ $country->country_iso }}">{{ $country->country_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div>
              <label class="text-sm font-medium mb-1 block">Currency</label>
              <div class="select-wrapper">
                <select id="currencySelect" name="bank[currency]">
                <option value=""></option>
                    @foreach($countries->unique('currency_iso') as $country)
                        <option value="{{ $country->currency_iso }}">{{ $country->currency_iso }}</option>
                    @endforeach
                </select>
              </div>
            </div>
          </div>

          <div>
            <label class="text-sm font-medium mb-1 block">Beneficiary Type</label>
            <select id="beneficiaryType" name="type" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base">
              <option value="">Select Type</option>
              <option value="individual">Individual</option>
              <option value="corporate">Corporate</option>
            </select>
          </div>

          <div id="individualFields" class="grid grid-cols-1 sm:grid-cols-2 gap-3 hidden">
            <input autocomplete="new-password" type="text" name="firstNames" placeholder="First Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
            <input autocomplete="new-password" type="text" name="lastName" placeholder="Last Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          </div>

          <div id="corporateFields" class="hidden">
            <input autocomplete="new-password" type="text" name="name" placeholder="Company Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          </div>
        </div>

        <!-- Bank Details -->
        <div class="space-y-6">
          <div>
            <h3 class="text-base font-semibold text-slate-900">Bank Details</h3>
            <p class="text-sm text-slate-500">Account info and validation.</p>
          </div>

          <select id="transferMethod" name="transfer_method" class="w-full border rounded-xl px-3 py-2 hidden">
            <option value="">Select Transfer Method</option>
            <option value="mobile">Mobile Money</option>
            <option value="bank">Bank Transfer</option>
          </select>

          <div class="grid sm:grid-cols-2 gap-4">
            <input type="text" id="iban" name="bank[iban]" placeholder="IBAN" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
            <input type="text" id="accountNumber" name="bank[accountNumber]" placeholder="Account Number" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
            <input type="text" id="sortCode" name="bank[sortCode]" placeholder="Sort Code" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
            <input type="text" id="bankCode" name="bank[bankCode]" placeholder="Bank Code" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
            <input type="text" id="swiftBic" name="bank[swiftBic]" placeholder="SWIFT / BIC" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
            <input type="text" id="routing" name="bank[routing]" placeholder="Routing Number" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden" />
          </div>

          <input id="mobileNumber" name="bank[mobileNumber]" placeholder="Mobile Number" class="w-full border rounded-xl px-3 py-2 hidden"/>

          <select id="bankSelect" name="bank[bankCode]" class="w-full border rounded-xl px-3 py-2 hidden">
            <option value="">Select Bank</option>
          </select>

          <select id="accountType" name="bank[accountType]" class="bank-field w-full border rounded-xl px-3 py-2 text-sm md:text-base hidden">
            <option value="">Account Type</option>
            <option value="checking">Checking</option>
            <option value="savings">Savings</option>
          </select>

          <div>
            <label class="text-sm font-medium mb-1 block">Account Holder</label>
            <input 
              id="accountHolder"
              type="text" 
              name="bank[accountHolder]" 
              placeholder="Account Holder"
              class="w-full border rounded-xl px-3 py-2 transition-all duration-300"
            />
            <div id="accountLoading" class="text-sm text-gray-500 hidden">⏳ Fetching account name…</div>
           <div id="accountManualHint" class="text-sm text-amber-600 hidden">
            Automatic name verification is not available for this account. Please enter the account holder name carefully.
            </div>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t">
        <button type="submit" class="flex-1 bg-slate-900 text-white font-semibold py-3 rounded-xl hover:bg-slate-800 transition">
          Create Beneficiary
        </button>
        <a href="{{ route('beneficias') }}" class="flex-1 border py-3 rounded-xl text-center hover:bg-slate-50 transition">
          Cancel
        </a>
      </div>
    </form>
  </div>
</section>



    </main>







<script>
document.addEventListener("DOMContentLoaded", function() {
    const countryRules = @json($countryRules);
    const currencyRules = @json($currencyRules);
    const countries = @json($countries->values());
    const SERVICES = @json($pivotServices);

    const countrySelect = document.getElementById("countrySelect");
    const currencySelect = document.getElementById("currencySelect");
    const transferMethod = document.getElementById("transferMethod");
    const accountNumber = document.getElementById("accountNumber");
    const bankSelect = document.getElementById("bankSelect");
    const holderInput = document.getElementById("accountHolder");
    const loadingText = document.getElementById("accountLoading");
    const mobileInput = document.getElementById("mobileNumber");
    const manualHint = document.getElementById("accountManualHint");
    const allRuleFields = document.querySelectorAll(".bank-field");

    const banksFilterUrl = "{{ route('banks.filter') }}";

    const PAYAZA_CURRENCIES = ["NGN", "TZS", "KES", "XOF", "XAF", "ZAR"];
    const XOF_COUNTRIES = ["BJ", "BF", "CI", "GW", "ML", "NE", "SN", "TG"];
    const PROVIDER_CURRENCIES = ["UGX", "NGN", "TZS", "KES", "XOF", "XAF", "ZAR", "GHS"];
    const MUST_VALIDATE_CURRENCIES = ["NGN", "UGX", "GHS"];

    const COUNTRY_DIAL_CODES = {
        BJ: "229",
        BF: "226",
        CI: "225",
        GW: "245",
        ML: "223",
        NE: "227",
        SN: "221",
        TG: "228",
        NG: "234",
        GH: "233",
        UG: "256",
        KE: "254",
        TZ: "255",
        ZA: "27",
        CM: "237"
    };

    const pivotEnabled = @json(filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN));
    const payazaEnabled = @json(filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN));
    const appmobileEnabled = @json(filter_var(env('APP_MOBILE'), FILTER_VALIDATE_BOOLEAN));

    let timer;
    let countryTom = null;
    let currencyTom = null;
    let payazaProviders = { bank: [], mobile: [] };
    let appmobileProvider = { bank: [], mobile: [] };

    const flagUrl = (iso) => iso ? `https://flagcdn.com/w20/${String(iso).toLowerCase()}.png` : "";

    const currencyDefaultCountry = {};
    countries.forEach((country) => {
        if (!currencyDefaultCountry[country.currency_iso]) {
            currencyDefaultCountry[country.currency_iso] = country.country_iso;
        }
    });

    countrySelect.querySelectorAll("option").forEach((option) => {
        if (option.value) {
            option.setAttribute("data-flag", flagUrl(option.value));
        }
    });

    currencySelect.querySelectorAll("option").forEach((option) => {
        if (option.value) {
            option.setAttribute("data-flag", flagUrl(currencyDefaultCountry[option.value]));
        }
    });

    function renderOption(item, escape) {
        const flag = item.flag || (item.$option ? item.$option.getAttribute("data-flag") : "");

        return `<div style="display:flex; align-items:center; gap:8px; padding:4px 8px;">
            ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ""}
            <span>${escape(item.text)}</span>
        </div>`;
    }

    function renderItem(item, escape) {
        const flag = item.flag || (item.$option ? item.$option.getAttribute("data-flag") : "");

        return `<div style="display:inline-flex; align-items:center; gap:6px;">
            ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ""}
            <span>${escape(item.text)}</span>
        </div>`;
    }

    countryTom = new TomSelect("#countrySelect", {
        allowEmptyOption: true,
        render: {
            option: renderOption,
            item: renderItem
        }
    });

    currencyTom = new TomSelect("#currencySelect", {
        allowEmptyOption: true,
        render: {
            option: renderOption,
            item: renderItem
        }
    });

    function setCurrencyFlag(currency, countryIso) {
        if (!currency || !countryIso || !currencyTom.options[currency]) {
            return;
        }

        const flag = flagUrl(countryIso);
        const option = currencySelect.querySelector(`option[value="${currency}"]`);

        if (option) {
            option.setAttribute("data-flag", flag);
        }

        currencyTom.updateOption(currency, {
            ...currencyTom.options[currency],
            flag: flag
        });

        currencyTom.refreshOptions(false);
        currencyTom.refreshItems();
    }

    function normalizeCurrency(currency) {
        return String(currency || "").trim().toUpperCase();
    }

    function mustValidateCurrency(currency = currencySelect.value) {
        return MUST_VALIDATE_CURRENCIES.includes(normalizeCurrency(currency));
    }

    function getValidatedAccountName(data) {
        return data?.accountName
            || data?.account_name
            || data?.response_content?.account_name
            || data?.data?.account_name
            || data?.data?.response_content?.account_name
            || data?.data?.data?.account_name
            || data?.data?.data?.response_content?.account_name
            || data?.data?.name
            || "";
    }

    function getSelectedDialCode() {
        return COUNTRY_DIAL_CODES[countrySelect.value] || "";
    }

    function resetMobileInput() {
        const dialCode = getSelectedDialCode();

        mobileInput.placeholder = dialCode ? `${dialCode} Mobile Number` : "Mobile Number";

        if (!mobileInput.classList.contains("hidden")) {
            mobileInput.value = dialCode;
        }
    }

    function prepareMobileInput() {
        const dialCode = getSelectedDialCode();

        if (!dialCode) {
            mobileInput.placeholder = "Mobile Number";
            return;
        }

        mobileInput.placeholder = `${dialCode} Mobile Number`;

        if (!mobileInput.value.trim()) {
            mobileInput.value = dialCode;
            return;
        }

        if (!mobileInput.value.startsWith("+")) {
            mobileInput.value = `${dialCode}${mobileInput.value.replace(/^0+/, "")}`;
        }
    }

    function resetHolderState() {
        holderInput.classList.remove(
            "border-green-500",
            "bg-green-100",
            "border-red-500",
            "bg-red-100",
            "border-amber-400",
            "bg-amber-50"
        );

        holderInput.value = "";
        holderInput.placeholder = "Account Holder";
        holderInput.readOnly = false;
        manualHint?.classList.add("hidden");
    }

    function setHolderValidated(name) {
        holderInput.value = name;
        holderInput.readOnly = true;
        holderInput.classList.remove("border-red-500", "bg-red-100", "border-amber-400", "bg-amber-50");
        holderInput.classList.add("border-green-500", "bg-green-100");
        manualHint?.classList.add("hidden");
    }

    function setHolderValidationFailed() {
        holderInput.value = "Account not found";
        holderInput.readOnly = false;
        holderInput.classList.remove("border-green-500", "bg-green-100", "border-amber-400", "bg-amber-50");
        holderInput.classList.add("border-red-500", "bg-red-100");

        if (manualHint) {
            manualHint.textContent = "We couldn’t verify this account name. Please check the details and try again.";
            manualHint.classList.remove("hidden", "text-amber-600");
            manualHint.classList.add("text-red-600");
        }
    }

    function setHolderManualFallback(message) {
        if (mustValidateCurrency()) {
            setHolderValidationFailed();
            return;
        }

        holderInput.value = "";
        holderInput.placeholder = "Enter account holder name";
        holderInput.readOnly = false;
        holderInput.classList.remove("border-green-500", "bg-green-100", "border-red-500", "bg-red-100");
        holderInput.classList.add("border-amber-400", "bg-amber-50");

        if (manualHint) {
            manualHint.textContent = message || "Automatic name verification is not available for this account. Please enter the account holder name carefully.";
            manualHint.classList.remove("hidden", "text-red-600");
            manualHint.classList.add("text-amber-600");
        }
    }

    function toggleField(field, show, required = false) {
        if (!field) return;

        if (show) {
            field.classList.remove("hidden");
            field.disabled = false;

            if (required) {
                field.setAttribute("required", "required");
            }
        } else {
            field.classList.add("hidden");
            field.disabled = true;
            field.removeAttribute("required");
            field.value = "";
        }
    }

    function resetProviderFields() {
        payazaProviders = { bank: [], mobile: [] };
        appmobileProvider = { bank: [], mobile: [] };

        transferMethod.innerHTML = '<option value="">Select Method</option>';
        bankSelect.innerHTML = '<option value="">Select Bank</option>';

        toggleField(transferMethod, false);
        toggleField(bankSelect, false);
        toggleField(accountNumber, false);
        toggleField(mobileInput, false);

        resetMobileInput();
        resetHolderState();
    }

    function hideRuleFields() {
        allRuleFields.forEach((field) => {
            field.classList.add("hidden");
            field.removeAttribute("required");
            field.disabled = true;
        });
    }

    function showRuleFields(currency) {
        hideRuleFields();

        if (!currencyRules[currency] || PROVIDER_CURRENCIES.includes(currency)) {
            return;
        }

        const fieldMap = {
            iban: "iban",
            accountNumber: "accountNumber",
            mobileNumber: "mobileNumber",
            sortCode: "sortCode",
            bankCode: "bankCode",
            swiftBic: "swiftBic",
            routing: "routing",
            accountType: "accountType"
        };

        currencyRules[currency].rules.forEach((rule) => {
            const elementId = fieldMap[rule];
            const field = document.getElementById(elementId);

            if (field) {
                field.classList.remove("hidden");
                field.disabled = false;
                field.setAttribute("required", "required");
            }
        });
    }

    function loadPivotBanks() {
        const country = countrySelect.value;

        if (!country) return;

        fetch(`${banksFilterUrl}?country=${country}&provider=pivot`)
            .then((response) => response.json())
            .then((list) => {
                let html = '<option value="">Select Bank</option>';

                list.forEach((bank) => {
                    html += `<option value="${bank.sort_code || bank.bank_code}">${bank.name}</option>`;
                });

                bankSelect.innerHTML = html;
            });
    }

    function loadAppmobileProviders() {
        const currency = normalizeCurrency(currencySelect.value);

        if (currency !== "GHS" || !appmobileEnabled) {
            return;
        }

        const url = new URL(banksFilterUrl, window.location.origin);
        url.searchParams.set("country", "GHA");
        url.searchParams.set("currency", "GHS");
        url.searchParams.set("provider", "app_mobile");

        fetch(url.toString())
            .then((response) => response.json())
            .then((res) => {
                const list = res.data || res;

                appmobileProvider = { bank: [], mobile: [] };

                list.forEach((item) => {
                    if (item.type === "mobile_money") {
                        appmobileProvider.mobile.push(item);
                    } else {
                        appmobileProvider.bank.push(item);
                    }
                });

                showTransferMethods();
            });
    }

    function loadPayazaProviders() {
        const country = countrySelect.value;
        const currency = normalizeCurrency(currencySelect.value);

        if (!country || !PAYAZA_CURRENCIES.includes(currency) || !payazaEnabled) {
            return;
        }

        const url = new URL(banksFilterUrl, window.location.origin);
        url.searchParams.set("country", country);
        url.searchParams.set("currency", currency);
        url.searchParams.set("provider", "payaza");

        fetch(url.toString())
            .then((response) => response.json())
            .then((res) => {
                const list = res.data || res;

                payazaProviders = { bank: [], mobile: [] };

                list.forEach((item) => {
                    if (item.type === "mobile_money") {
                        payazaProviders.mobile.push(item);
                    } else {
                        payazaProviders.bank.push(item);
                    }
                });

                showTransferMethods();
            })
            .catch((error) => {
                console.error("Payaza fetch error:", error);
            });
    }

    function showTransferMethods() {
        const country = countrySelect.value;
        const currency = normalizeCurrency(currencySelect.value);
        let html = '<option value="">Select Method</option>';

        if (country === "GH" && currency === "GHS" && appmobileEnabled) {
            if (appmobileProvider.bank.length) html += '<option value="bank">Bank</option>';
            if (appmobileProvider.mobile.length) html += '<option value="mobile">Mobile</option>';

            transferMethod.innerHTML = html;
            toggleField(transferMethod, appmobileProvider.bank.length || appmobileProvider.mobile.length, true);
            return;
        }

        if (payazaProviders.bank.length) html += '<option value="bank">Bank</option>';
        if (payazaProviders.mobile.length) html += '<option value="mobile">Mobile</option>';

        transferMethod.innerHTML = html;
        toggleField(transferMethod, payazaProviders.bank.length || payazaProviders.mobile.length, true);
    }

    function showFieldsByCurrency() {
        resetProviderFields();

        const currency = normalizeCurrency(currencySelect.value);

        showRuleFields(currency);

        if (currency === "GHS" && appmobileEnabled) {
            loadAppmobileProviders();
            return;
        }

        if (PAYAZA_CURRENCIES.includes(currency)) {
            loadPayazaProviders();
            return;
        }

        if (currency === "UGX" && pivotEnabled) {
            transferMethod.innerHTML = `
                <option value="">Select Method</option>
                <option value="bank">Bank</option>
                <option value="mobile">Mobile</option>
            `;

            toggleField(transferMethod, true, true);
        }
    }

    function showFieldsByMethod() {
        toggleField(bankSelect, false);
        toggleField(accountNumber, false);
        toggleField(mobileInput, false);

        resetHolderState();

        const country = countrySelect.value;
        const currency = normalizeCurrency(currencySelect.value);
        const method = transferMethod.value;

        if (currency === "UGX" && pivotEnabled) {
            if (method === "mobile") {
                toggleField(mobileInput, true, true);
                prepareMobileInput();
            }

            if (method === "bank") {
                loadPivotBanks();
                toggleField(bankSelect, true, true);
                toggleField(accountNumber, true, true);
            }

            return;
        }

        if (country === "GH" && currency === "GHS" && appmobileEnabled) {
            if (method === "bank") {
                let html = '<option value="">Select Bank</option>';

                appmobileProvider.bank.forEach((bank) => {
                    html += `<option value="${bank.bank_code || bank.code}">${bank.name}</option>`;
                });

                bankSelect.innerHTML = html;
                toggleField(bankSelect, true, true);
                toggleField(accountNumber, true, true);
            }

            if (method === "mobile") {
                let html = '<option value="">Select Provider</option>';

                appmobileProvider.mobile.forEach((provider) => {
                    html += `<option value="${provider.bank_code || provider.code}">${provider.name}</option>`;
                });

                bankSelect.innerHTML = html;
                toggleField(bankSelect, true, true);
                toggleField(mobileInput, true, true);
                prepareMobileInput();
            }

            return;
        }

        if (PAYAZA_CURRENCIES.includes(currency)) {
            if (method === "bank") {
                let html = '<option value="">Select Bank</option>';

                payazaProviders.bank.forEach((bank) => {
                    html += `<option value="${bank.code || bank.bank_code}">${bank.name}</option>`;
                });

                bankSelect.innerHTML = html;
                toggleField(bankSelect, true, true);
                toggleField(accountNumber, true, true);
            }

            if (method === "mobile") {
                let html = '<option value="">Select Provider</option>';

                payazaProviders.mobile.forEach((provider) => {
                    html += `<option value="${provider.code || provider.bank_code}">${provider.name}</option>`;
                });

                bankSelect.innerHTML = html;
                toggleField(bankSelect, true, true);
                toggleField(mobileInput, true, true);
                prepareMobileInput();
            }
        }
    }

    function syncCurrencyFromCountry(countryIso) {
        if (!countryIso || !countryRules[countryIso]) {
            return;
        }

        resetMobileInput();

        const currency = XOF_COUNTRIES.includes(countryIso)
            ? "XOF"
            : countryRules[countryIso].currency;

        if (!currency) {
            return;
        }

        currencyTom.setValue(currency, true);
        setCurrencyFlag(currency, countryIso);

        currencySelect.dispatchEvent(new Event("change", { bubbles: true }));
    }

    async function validateAccount() {
        const currency = normalizeCurrency(currencySelect.value);
        const method = transferMethod.value;

        let payload = null;
        let route = "";

        if (currency === "UGX" && pivotEnabled) {
            if (method === "mobile") {
                const mobile = mobileInput.value.trim();
                if (!mobile) return;

                payload = {
                    serviceCode: SERVICES.ugx_mobile_service,
                    accountNumber: mobile,
                    msisdn: mobile
                };
            }

            if (method === "bank") {
                const acc = accountNumber.value.trim();
                const code = bankSelect.value;
                if (!acc || !code) return;

                payload = {
                    serviceCode: SERVICES.ugx_bank_service,
                    accountNumber: acc,
                    msisdn: acc,
                    extraData: {
                        bankSortCode: code,
                        amount: "0"
                    }
                };
            }

            route = "{{ route('pivot.account.validation') }}";
        } else if (PAYAZA_CURRENCIES.includes(currency)) {
            if (method === "bank") {
                const acc = accountNumber.value.trim();
                const code = bankSelect.value;
                if (!acc || !code) return;

                payload = {
                    currency,
                    account_number: acc,
                    bank_code: code
                };
            }

            if (method === "mobile") {
                const mobile = mobileInput.value.trim();
                const code = bankSelect.value;
                if (!mobile || !code) return;

                payload = {
                    currency,
                    account_number: mobile,
                    bank_code: code
                };
            }

            route = "{{ route('payaza.account-enquiry') }}";
        }

        if (countrySelect.value === "GH" && currency === "GHS" && appmobileEnabled) {
            const code = bankSelect.value;

            if (method === "bank") {
                const acc = accountNumber.value.trim();
                if (!acc || !code) return;

                payload = {
                    customer_number: acc,
                    bank_code: code
                };
            }

            if (method === "mobile") {
                const mobile = mobileInput.value.trim();
                if (!mobile || !code) return;

                payload = {
                    customer_number: mobile,
                    bank_code: code
                };
            }

            route = "{{ route('appmobile.account-enquiry') }}";
        }

        if (!payload) return;

        try {
            loadingText.classList.remove("hidden");
            holderInput.value = "";
            holderInput.classList.remove("border-green-500", "bg-green-100", "border-red-500", "bg-red-100", "border-amber-400", "bg-amber-50");
            holderInput.readOnly = true;
            manualHint?.classList.add("hidden");

            const res = await fetch(route, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            loadingText.classList.add("hidden");

            const accountName = getValidatedAccountName(data);

            if (accountName) {
                setHolderValidated(accountName);
            } else if (mustValidateCurrency(currency)) {
                setHolderValidationFailed();
            } else {
                setHolderManualFallback("Automatic name verification is not available for this account. Please enter the account holder name carefully.");
            }
        } catch (error) {
            console.error("Validation error:", error);
            loadingText.classList.add("hidden");

            if (mustValidateCurrency(currency)) {
                setHolderValidationFailed();
            } else {
                setHolderManualFallback("Automatic name verification is not available right now. Please enter the account holder name carefully.");
            }
        }
    }

    countryTom.on("change", syncCurrencyFromCountry);
    currencyTom.on("change", showFieldsByCurrency);
    transferMethod.addEventListener("change", showFieldsByMethod);
    bankSelect.addEventListener("change", validateAccount);

    accountNumber.addEventListener("input", () => {
        clearTimeout(timer);
        timer = setTimeout(validateAccount, 700);
    });

    mobileInput.addEventListener("input", () => {
        clearTimeout(timer);
        timer = setTimeout(validateAccount, 700);
    });

    if (countrySelect.value) {
        syncCurrencyFromCountry(countrySelect.value);
    } else if (currencySelect.value) {
        showFieldsByCurrency();
    }
});
</script>







    
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

        // Close sidebar on window resize if desktop
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

     



<!-- jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    
</body>

</html>