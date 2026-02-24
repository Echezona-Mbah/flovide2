@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
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
     <section class="w-full rounded-3xl bg-white min-h-screen p-4 md:p-8">

 <section class="min-h-screen bg-white py-4 px-2">
  <div class="max-w-4xl mx-auto bg-white/90 backdrop-blur rounded-3xl shadow-2xl p-6 md:p-12 space-y-8">

    <!-- Header -->
    <div class="text-center space-y-1">
      <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Add New Beneficiary</h2>
      <p class="text-gray-500 text-sm md:text-base">Securely add an international IBAN beneficiary</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('add_beneficias.store') }}" class="space-y-6 md:space-y-8">
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
          <input type="text" name="firstNames" placeholder="First Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          <input type="text" name="lastName" placeholder="Last Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
        </div>

        <!-- Corporate Fields -->
        <div id="corporateFields" class="mt-2 hidden">
          <input type="text" name="name" placeholder="Company Name" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
        </div>

      </div>

      <!-- Address -->
      <div class="bg-gray-50 rounded-2xl p-4 md:p-6 shadow-sm space-y-4">
        <h3 class="font-semibold text-gray-800 flex items-center gap-2 text-base md:text-lg">📍 Beneficiary Address</h3>

        <input type="text" name="address[addressLine1]" placeholder="Street address" class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
          <input type="text" name="address[city]" placeholder="City" class="border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          <input type="text" name="address[state]" placeholder="State / Province (Optional)" class="border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
          <input type="text" name="address[postcode]" placeholder="Postal Code" class="border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400 text-sm md:text-base" />
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
        <select id="bankSelect" name="bank[sortCode]" class="w-full border rounded-xl px-3 py-2 hidden">
            <option value="">Select Bank</option>
            @foreach($banks as $bank)
                <option value="{{ $bank->sort_code }}">{{ $bank->name }}</option>
            @endforeach
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
            class="w-full border rounded-xl px-3 py-2"
            />
            <div id="accountLoading" class="text-sm text-gray-500 hidden">
            ⏳ Fetching account name…
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
</section>


</section>


    </main>





<script>
    document.addEventListener("DOMContentLoaded", function () {

    const currencySelect  = document.getElementById("currencySelect");
    const transferMethod  = document.getElementById("transferMethod");
    const accountInput    = document.getElementById("accountNumber");
    const mobileInput     = document.getElementById("mobileNumber");
    const bankSelect      = document.getElementById("bankSelect");
    const holderInput     = document.getElementById("accountHolder");
    const loadingText     = document.getElementById("accountLoading");

    const SERVICES = @json($pivotServices); // ⭐ from .env

    let timer;

    async function validateUGX(){

    const currency = currencySelect.value;
    if(currency !== "UGX") return;

    const method = transferMethod.value;
    let payload = null;

    console.log("Validation started", {currency, method});
    console.log("Services from ENV", SERVICES);


    // ================= MOBILE =================
    if(method === "mobile"){

    const mobile = mobileInput.value;
    if(!mobile) return;

    payload = {
    serviceCode: SERVICES.ugx_mobile_service, // ⭐ dynamic
    accountNumber: mobile,
    msisdn: mobile
    };

    console.log("Mobile payload", payload);
    }


    // ================= BANK =================
    if(method === "bank"){

    const account = accountInput.value;
    const sortCode = bankSelect.value;
    const msisdn = mobileInput.value || account;

    if(!account || !sortCode) return;

    payload = {
    serviceCode: SERVICES.ugx_bank_service, // ⭐ dynamic
    accountNumber: account,
    msisdn: msisdn,
    extraData:{
    bankSortCode: sortCode,
    amount:"0"
    }
    };

    console.log("Bank payload", payload);
    }

    if(!payload) return;

    try{

    loadingText.classList.remove("hidden");
    holderInput.value = "";
    holderInput.setAttribute("readonly", true);

    const res = await fetch("{{ route('pivot.account.validation') }}",{
    method:"POST",
    headers:{
    "Content-Type":"application/json",
    "X-CSRF-TOKEN":"{{ csrf_token() }}"
    },
    body:JSON.stringify(payload)
    });

    const data = await res.json();
    console.log("Pivot response", data);

    loadingText.classList.add("hidden");

    if(data.accountName){
    holderInput.value = data.accountName;
    holderInput.removeAttribute("readonly");
    }else{
    holderInput.value = "Account not found";
    }

    }catch(e){
    loadingText.classList.add("hidden");
    console.error("Validation error", e);
    }

    }


    // 🔥 debounce listeners
    accountInput?.addEventListener("input",()=>{
    clearTimeout(timer);
    timer=setTimeout(validateUGX,800);
    });

    mobileInput?.addEventListener("input",()=>{
    clearTimeout(timer);
    timer=setTimeout(validateUGX,800);
    });

    bankSelect?.addEventListener("change", validateUGX);
    transferMethod?.addEventListener("change", validateUGX);

    });
</script>

    {{-- for both country and currency log --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
  // --- COUNTRY SELECT ---
  const countrySelect = document.querySelector("#countrySelect");
  countrySelect.querySelectorAll("option").forEach(option => {
    if(option.value) {
      option.setAttribute("data-flag", `https://flagcdn.com/w20/${option.value.toLowerCase()}.png`);
    }
  });

  // --- CURRENCY SELECT ---
  const currencySelect = document.querySelector("#currencySelect");
  currencySelect.querySelectorAll("option").forEach(option => {
    if(option.value) {
      // Find country ISO for this currency
      const country = @json($countries->keyBy('currency_iso'));
      const iso = country[option.value] ? country[option.value].country_iso.toLowerCase() : '';
      if(iso) {
        option.setAttribute("data-flag", `https://flagcdn.com/w20/${iso}.png`);
      }
    }
  });

  // Initialize TomSelect for COUNTRY
  new TomSelect("#countrySelect", {
    allowEmptyOption: true,
    render: {
      option: function(item, escape) {
        const flag = item.$option ? item.$option.getAttribute('data-flag') : '';
        return `<div style="display:flex; align-items:center; gap:8px; padding:4px 8px;">
                  ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ''}
                  <span>${escape(item.text)}</span>
                </div>`;
      },
      item: function(item, escape) {
        const flag = item.$option ? item.$option.getAttribute('data-flag') : '';
        return `<div style="display:inline-flex; align-items:center; gap:6px;">
                  ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ''}
                  <span>${escape(item.text)}</span>
                </div>`;
      }
    }
  });

  // Initialize TomSelect for CURRENCY
  new TomSelect("#currencySelect", {
    allowEmptyOption: true,
    render: {
      option: function(item, escape) {
        const flag = item.$option ? item.$option.getAttribute('data-flag') : '';
        return `<div style="display:flex; align-items:center; gap:8px; padding:4px 8px;">
                  ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ''}
                  <span>${escape(item.text)}</span>
                </div>`;
      },
      item: function(item, escape) {
        const flag = item.$option ? item.$option.getAttribute('data-flag') : '';
        return `<div style="display:inline-flex; align-items:center; gap:6px;">
                  ${flag ? `<img src="${flag}" style="width:20px;height:14px;display:inline-block;"/>` : ''}
                  <span>${escape(item.text)}</span>
                </div>`;
      }
    }
  });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const options = {
    allowEmptyOption: true,
  };

  new TomSelect("#countrySelect", options);
  new TomSelect("#currencySelect", options);
});


</script>



{{-- when i select currency the bank detall will drop --}}
<script>
document.addEventListener("DOMContentLoaded", function () {

  const currencyRules = @json($currencyRules);

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

  const currencySelect = document.getElementById("currencySelect");
  const countrySelect  = document.getElementById("countrySelect");
  const allFields      = document.querySelectorAll(".bank-field");

  if (!currencySelect) return;

  currencySelect.addEventListener("change", function () {

    const selected = currencyRules[this.value];

    // hide all bank fields first
    allFields.forEach(f => {
      f.classList.add("hidden");
      f.removeAttribute("required");
    });

    if (!selected) return;

    // auto set country
    if (countrySelect) {
      countrySelect.value = selected.country;
    }

    // ⭐ IMPORTANT
    // If UGX → do NOT show rules (payment method will handle)
    if (this.value === "UGX") return;

    // show required fields for other currencies
    selected.rules.forEach(field => {
      const el = document.getElementById(fieldMap[field]);
      if (el) {
        el.classList.remove("hidden");
        el.setAttribute("required", "required");
      }
    });

  });

});
</script>
 {{-- when you select ugx currency the paymentmethod --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {

    const currencySelect  = document.getElementById("currencySelect");
    const transferMethod  = document.getElementById("transferMethod");
    const mobileInput     = document.getElementById("mobileNumber");
    const bankSelect      = document.getElementById("bankSelect");
    const accountNumber   = document.getElementById("accountNumber");

    function hideAll(){
    transferMethod.classList.add("hidden");
    mobileInput.classList.add("hidden");
    bankSelect.classList.add("hidden");
    accountNumber.classList.add("hidden");
    }

    hideAll();

    // ⭐ Currency change
    currencySelect.addEventListener("change", () => {

    if(currencySelect.value === "UGX"){
    transferMethod.classList.remove("hidden"); // only show payment method
    mobileInput.classList.add("hidden");
    bankSelect.classList.add("hidden");
    accountNumber.classList.add("hidden");
    }else{
    hideAll();
    }

    });

    // ⭐ Payment method change
    transferMethod.addEventListener("change", () => {

    const method = transferMethod.value;

    if(method === "mobile"){
    mobileInput.classList.remove("hidden");
    bankSelect.classList.add("hidden");
    accountNumber.classList.add("hidden");
    }

    else if(method === "bank"){
    bankSelect.classList.remove("hidden");
    accountNumber.classList.remove("hidden");
    mobileInput.classList.add("hidden");
    }

    else{
    mobileInput.classList.add("hidden");
    bankSelect.classList.add("hidden");
    accountNumber.classList.add("hidden");
    }

    });

    });
</script>




    <script>
const typeSelect = document.getElementById('beneficiaryType');
const individualFields = document.getElementById('individualFields');
const corporateFields = document.getElementById('corporateFields');

function toggleFields() {

    individualFields.classList.add('hidden');
    corporateFields.classList.add('hidden');

    if (typeSelect.value === 'individual') {
        individualFields.classList.remove('hidden');
    }

    if (typeSelect.value === 'corporate') {
        corporateFields.classList.remove('hidden');
    }
}

// Run on change
typeSelect.addEventListener('change', toggleFields);

// Run once on page load
toggleFields();
</script>


    
                {{-- <section class="w-full max-w-md border-l border-gray-200 pl-8">
                    <h2 class="font-semibold text-gray-900 text-base mb-2">Manually Add a Beneficiary</h2>
                    <p class="text-gray-500 text-sm mb-6 max-w-[320px]">
                        You can also quickly add someone as a beneficiary right after sending money to them.
                    </p>
                    <form method="POST" action="{{ route('add_beneficias.store') }}" class="flex flex-col gap-4 text-sm text-gray-600">
                        @csrf

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



                        <label for="country" class="font-normal">Select Account Type</label>
                        <select id="account_type" name="account_type" class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option selected disabled>Select Account Type</option>
                            <option value="personal">Personal </option>
                            <option value="business">Business</option>
                        </select>


                        <!-- Select Country -->
                        <div>
                            <label class="font-medium mb-1">Select Country</label>
                            <select name="country"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                required>

                                <option value="" disabled selected>Choose country</option>

                                <option value="UG">Uganda 🇺🇬</option>
                                <option value="KE">Kenya 🇰🇪</option>
                                <option value="NG">Nigeria 🇳🇬</option>
                                <option value="CA">Canada 🇨🇦</option>
                                <option value="GH">Ghana 🇬🇭</option>

                            </select>
                        </div>


                        <!-- Service Code -->
                        <div>
                            <label class="font-medium mb-1">Service Code</label>
                            <input type="text" name="serviceCode"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                placeholder="Enter service code (e.g. PS714349)"
                                required>
                        </div>



                        <!-- MSISDN (Phone Number) -->
                        <div>
                            <label class="font-medium mb-1">Phone Number (MSISDN)</label>
                            <input type="text" name="msisdn"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                placeholder="e.g. 256703039553"
                                required>
                        </div>

                        <!-- Bank Sort Code -->
                        <div>
                            <label class="font-medium mb-1">Bank Sort Code</label>
                            <input type="text" name="extraData[bankSortCode]"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                placeholder="Enter bank sort code"
                                required>
                        </div>

                        <!-- Account Number -->
                        <div>
                            <label class="font-medium mb-1">Account Number</label>
                            <input type="text" id="accountNumber" name="accountNumber"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                placeholder="Enter account number"
                                required>
                        </div>

                        <!-- Account Name (Auto Filled) -->
                        <div>
                            <label class="font-medium mb-1">Account Name</label>
                            <input type="text" id="accountName" name="account_name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100"
                                readonly>
                        </div>

                        <!-- Loading -->
                        <div id="loading" class="hidden text-blue-500 text-sm">
                            Validating account...
                        </div>




                        
            
                        <!-- Hidden input for country -->

                        
                        
        

                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="bg-blue-200 text-blue-800 font-semibold rounded-full px-6 py-2 hover:bg-blue-300 transition">
                                Add Beneficiary
                            </button>
                            <button type="button"
                                class="border border-gray-300 rounded-full px-6 py-2 hover:bg-gray-100 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </section> --}}
    
    <script>
        document.getElementById('country').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const selectedCountryCode = selectedOption.getAttribute('data-code');
            const bankOptions = document.querySelectorAll('#bank option');
        
            bankOptions.forEach(option => {
                if (option.value === "" || option.disabled) return; // Skip the placeholder
                option.style.display = option.dataset.country === selectedCountryCode ? 'block' : 'none';
            });
        
            document.getElementById('bank').value = '';
        });
        
        // Trigger filtering on page load if editing
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('country').dispatchEvent(new Event('change'));
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

    <script>
        document.getElementById('country').addEventListener('change', function() {
            let selectedOption = this.options[this.selectedIndex];
            let currency = selectedOption.getAttribute('data-currency');
            console.log("Selected currency:", currency);
            // You can populate another input with this currency if needed
        });
    </script>

<script>
document.getElementById('accountNumber').addEventListener('blur', function () {

    const country = document.querySelector('[name="country"]').value;

    // Only validate for Uganda or Kenya
    if (country !== 'UG' && country !== 'KE') return;

    const data = {
        serviceCode: document.querySelector('[name="serviceCode"]').value,
        accountNumber: this.value,
        msisdn: document.querySelector('[name="msisdn"]').value,
        extraData: {
            bankSortCode: document.querySelector('[name="extraData[bankSortCode]"]').value
        }
    };

    document.getElementById('loading').classList.remove('hidden');

    fetch("{{ route('pivot.account.validation') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {

        document.getElementById('loading').classList.add('hidden');

        if (res.accountName) {
            document.getElementById('accountName').value = res.accountName;
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Validation failed',
                text: res.statusDescription || 'Invalid account'
            });
        }

    })
    .catch(err => {
        document.getElementById('loading').classList.add('hidden');
        console.error(err);
    });

});
</script>




<script>
    function countrySelector() {
        return {
            countries: @json($countries),
            selectedCountry: null,
            search: '',
            currencies: [],
            selectedCurrency: null,
            banks: [],
            bankSearch: '',
            open: false,
            currencyOpen: false,
            bankOpen: false,
            accountName: '',
            selectedBankId: null,
            selectedBankName: '',
            accountNumber: '',
            isLoading: false,
    
            dynamicFields: {
                // Nigeria
                'NG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Albania
                'AL_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AL_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AL_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                // 🔽 Add these for American Samoa (AS)
                'AS_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AS_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AS_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 30 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'AD_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'AD_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AD_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'AI_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AI_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AI_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'AQ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AQ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AQ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'AG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'AR_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AR_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AR_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                // Armenia
                'AM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                // Aruba
                'AW_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AW_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AW_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Australia (AU)
                'AU_AUD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bsb', label: 'BSB', type: 'number', length: 6 },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AU_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AU_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AU_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Austria (AT)
                'AT_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'AT_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AT_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Azerbaijan (AZ)
                'AZ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AZ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AZ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bahamas (BS)
                'BS_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BS_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BS_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bahrain (BH)
                'BH_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BH_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BH_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Guernsey (GG)
                'GG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'sort_code', label: 'Sort code', type: 'number', length: 6 },
                    { name: 'account_number', label: 'Account number', type: 'number' }
                ],
                'GG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bangladesh (BD)
                'BD_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BD_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BD_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Barbados (BB)
                'BB_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BB_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BB_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Belgium (BE)
                'BE_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'BE_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BE_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BZ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BZ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BZ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Benin (BJ)
                'BJ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BJ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BJ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bermuda (BM)
                'BM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bhutan (BT)
                'BT_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BT_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BT_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bonaire, Sint Eustatius and Saba (BQ)
                'BQ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BQ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BQ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Botswana (BW)
                'BW_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BW_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BW_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Bouvet Island (BV
                'BV_NOK': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BV_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BV_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BV_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Brazil (BR)
                'BR_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BR_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BR_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // British Indian Ocean Territory (IO)
                'IO_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IO_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IO_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //ritish Virgin Islands (VG)
                'VG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'VG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'VG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Brunei (BN)
                'BN_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BN_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BN_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Bulgaria (BG)
                'BG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'BG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'BG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Cameroon (CM
                'CM_XAF': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'network_id', label: 'Select network', type: 'select', options: ['MTN', 'Orange'] }, // replace with actual options
                    { name: 'phone_number', label: 'Phone number (with country code)', type: 'text' }
                ],
                'CM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Canada (CA)
                'CA_CAD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CA_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CA_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CA_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Cape Verde (CV)
                'CV_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CV_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CV_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Cayman Islands (KY)
                'KY_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KY_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KY_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Chad (TD
                'TD_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'TD_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'TD_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Chile (CL) 
                'CL_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CL_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CL_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //China (CN)
                'CN_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CN_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CN_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Christmas Island (CX
                'CX_AUD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CX_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CX_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CX_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Cocos (Keeling) Islands (CC
                'CC_AUD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CC_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CC_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CC_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Colombia (CO)
                'CO_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CO_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CO_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Comoros (KM)
                'KM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CK_NZD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CK_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CK_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CK_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //  Costa Rica (CR)
                'CR_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CR_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CR_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Croatia (HR)
                'HR_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },

                ],
                'HR_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HR_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Curaçao (CW)
                'CW_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CW_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CW_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Cyprus (CY)
                'CY_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'CY_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Czech Republic (CZ)
                'CZ_CZK': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CZ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CZ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'CZ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                // Denmark (DK)
                'DK_DKK': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'DK_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'DK_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DK_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Djibouti (DJ)
                'DJ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DJ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DJ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Dominica (DM)
                'DM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Dominican Republic (DO)
                'DO_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DO_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DO_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Ecuador (EC)
                'EC_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'EC_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'EC_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Egypt (EG)
                'EG_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'EG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'EG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // El Salvador (SV)
                'SV_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'SV_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'SV_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Equatorial Guinea (GQ)
                'GQ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GQ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GQ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Estonia (EE)
                'EE_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'EE_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'EE_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Falkland Islands (FK)
                'FK_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FK_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FK_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Faroe Islands (FO)
                'FO_DKK': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FO_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FO_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FO_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Fiji (FJ)
                'FJ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FJ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FJ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Finland
                'FI_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'FI_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FI_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // France
                'FR_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'FR_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FR_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // French Guiana
                'GF_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'GF_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GF_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // French Polynesia 
                'PF_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                'PF_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'PF_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //  French Southern Territories (TF)
                'TF_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'TF_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'TF_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Gabon (GA)
                'GA_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GA_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GA_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Gambia (GM)
                'GM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Georgia (GE)
                'GE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                // Germany (DE)
                'DE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'DE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'DE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Ghana (GH)
                'GH_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GH_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GH_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Gibraltar (GI)
                'GI_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'sort_code', label: 'Sort code', type: 'number', length: 6 },
                { name: 'account_number', label: 'Account number', type: 'number', length: 8 }
                ],
                'GI_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GI_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Greece (GR)
                'GR_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'GR_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GR_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Greece (GR)
                'GR_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'GR_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GR_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Grenada (GD)
                'GD_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GD_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GD_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Guam (GU)
                'GU_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GU_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GU_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Guatemala (GT)
                'GT_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GT_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GT_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Guinea (GN)
                'GN_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GN_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'GN_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Honduras (HN)
                'HN_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HN_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HN_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Hong Kong (HK)
                'HK_HKD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HK_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HK_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HK_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Hungary (HU)
                'HU_HUF': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HU_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HU_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'HU_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                //  Iceland (IS)
                'IS_ISK': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IS_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IS_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IS_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                // India (IN)
                'IN_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IN_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IN_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //Indonesia (ID)
                'ID_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ID_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ID_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //🇮🇪 Ireland (IE)
                'IE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'IE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇮🇲 Isle of Man (IM)
                'IM_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'number' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IM_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'number' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IM_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'sort_code', label: 'Sort code', type: 'number', length: 6 },
                { name: 'account_number', label: 'Account number', type: 'number' }
                ],
                // 🇮🇱 Israel (IL)
                'IL_ILS': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IL_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IL_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IL_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇮🇹 Italy (IT)
                'IT_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'IT_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'IT_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],

                // 🇯🇲 Jamaica (JM)
                'JM_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JM_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JM_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇯🇵 Japan (JP)
                'JP_JPY': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JP_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JP_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JP_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇯🇪 Jersey (JE)
                'JE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'sort_code', label: 'Sort code', type: 'number', length: 6 },
                { name: 'account_number', label: 'Account number', type: 'number', length: 8 }
                ],
                'JE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇯🇴 Jordan (JO)
                'JO_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JO_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'JO_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇰🇿 Kazakhstan (KZ)
                'KZ_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KZ_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KZ_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇰🇪 Kenya (KE)
                'KE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇰🇮 Kiribati (KI)
                'KI_AUD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KI_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KI_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KI_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇽🇰 Kosovo (XK)
                'XK_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'XK_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'XK_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇰🇼 Kuwait (KW)
                'KW_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KW_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KW_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇰🇬 Kyrgyzstan (KG)
                'KG_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KG_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'KG_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇱🇻 Latvia (LV)
                'LV_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'LV_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LV_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇱🇸 Lesotho (LS)
                'LS_ZAR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LS_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LS_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LS_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇱🇮 Liechtenstein (LI)
                'LI_CHF': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LI_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LI_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LI_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                // 🇱🇹 Lithuania (LT)
                'LT_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'LT_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LT_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇱🇺 Luxembourg (LU)
                'LU_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'LU_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'LU_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇴 Macau (MO)
                'MO_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MO_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MO_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇬 Madagascar (MG)
                'MG_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MG_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MG_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇼 Malawi (MW)
                'MW_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MW_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MW_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇾 Malaysia (MY)
                'MY_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MY_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MY_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Maldives (MV):
                'MV_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MV_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MV_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇱 Mali (ML)
                'ML_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ML_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ML_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇹 Malta (MT)
                'MT_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'MT_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MT_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇭 Marshall Islands (MH)
                'MH_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MH_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MH_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇶 Martinique (MQ)
                'MQ_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'MQ_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MQ_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇷 Mauritania (MR)
                'MR_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MR_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MR_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇺 Mauritius (MU)
                'MU_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MU_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MU_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇾🇹 Mayotte (YT)
                'YT_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'YT_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'YT_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'iban', label: 'IBAN', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇲🇽 Mexico (MX)
                'MX_MXN': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'clabe', label: 'CLABE', type: 'text', length: 18 },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MX_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'clabe', label: 'CLABE', type: 'text', length: 18 },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MX_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'clabe', label: 'CLABE', type: 'text', length: 18 },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MX_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'clabe', label: 'CLABE', type: 'text', length: 18 },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // 🇫🇲 Micronesia (FM)
                'FM_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FM_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'FM_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // MD (Moldova)
                'MD_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MD_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // MC (Monaco) 
                'MC_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'MC_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MC_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //🇲🇳 Mongolia (MN)
                'MN_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MN_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MN_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // ME (Montenegro)
                'ME_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ME_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'ME_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // MS (Montserrat)
                'MS_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MS_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'MS_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Namibia (NA)
                'NA_ZAR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NA_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NA_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NA_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                 // Nepal (NP)
                'NP_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NP_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NP_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Netherlands (NL)
                'NL_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' }
                ],
                'NL_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NL_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'iban', label: 'IBAN', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // New Caledonia (NC)
                'NC_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NC_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NC_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // New Zealand (NZ)
                'NZ_NZD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NZ_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NZ_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NZ_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Nicaragua (NI)
                'NI_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NI_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NI_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //  Niger (NE)
                'NE_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NE_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NE_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // Niue (NU)
                'NU_NZD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NU_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NU_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NU_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //  NF (Norfolk Island)
                'NF_AUD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NF_USD': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NF_GBP': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NF_EUR': [
                { name: 'account_name', label: 'Account name', type: 'text' },
                { name: 'bic', label: 'BIC', type: 'text' },
                { name: 'account_number', label: 'Account number', type: 'text' },
                { name: 'address', label: 'Recipient address', type: 'text' },
                { name: 'city', label: 'City', type: 'text' },
                { name: 'state', label: 'State', type: 'text' },
                { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                //  North Mac
                "MK_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MK_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MK_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                //  Northan Mallan island
                "MP_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MP_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MP_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- MK (North Macedonia) ---
                "MK_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MK_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MK_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- MP (Northern Mariana Islands) ---
                "MP_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MP_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MP_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- NO (Norway) ---
                "NO_NOK": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "NO_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "NO_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "NO_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" }
                ],
                // --- OM (Oman) ---
                "OM_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "OM_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "OM_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PK (Pakistan) ---
                "PK_PKR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" }
                ],
                "PK_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PK_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PG (Papua New Guinea) ---
                "PG_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PG_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PG_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PY (Paraguay) ---
                "PY_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PY_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PY_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PE (Peru) ---
                "PE_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PE_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PE_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PH (Philippines) ---
                "PH_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PH_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PH_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PN (Pitcairn Islands) ---
                "PN_NZD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PN_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PN_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PN_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PL (Poland) ---
                "PL_PLN": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PL_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PL_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PL_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" }
                ],
                // --- PT (Portugal) ---
                "PT_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" }
                ],
                "PT_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PT_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],

                // --- PR (Puerto Rico) ---
                "PR_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PR_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PR_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- QA (Qatar) ---
                "QA_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "QA_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "QA_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- CG (Congo) ---
                "CG_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "CG_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "CG_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- RO (Romania) ---
                "RO_RON": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RO_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RO_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RO_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                // --- RU (Russia) ---
                "RU_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RU_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RU_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- RW (Rwanda) ---
                "RW_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RW_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RW_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- BL (Saint Barthélemy) ---
                "BL_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "BL_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "BL_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SH (Saint Helena) ---
                "SH_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SH_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SH_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- KN (Saint Kitts and Nevis) ---
                "KN_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "KN_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "KN_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- MF (Saint Martin) ---
                "MF_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MF_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "MF_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- PM (Saint Pierre and Miquelon) ---
                "PM_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                "PM_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "PM_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- VC (Saint Vincent and the Grenadines) ---
                "VC_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VC_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VC_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- WS (Samoa) ---
                "WS_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "WS_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "WS_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SM (San Marino) ---
                "SM_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                "SM_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SM_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- ST (São Tomé and Príncipe) ---
                "ST_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ST_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ST_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SA (Saudi Arabia) ---
                "SA_SAR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SA_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SA_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SN (Senegal) ---
                "SN_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SN_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SN_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- RS (Serbia) ---
                "RS_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RS_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "RS_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SC (Seychelles) ---
                "SC_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SC_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SC_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SG (Singapore) ---
                "SG_SGD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SG_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SG_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SG_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SX (Sint Maarten) ---
                "SX_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SX_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SX_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SK (Slovakia) ---
                "SK_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                "SK_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SK_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // --- SI (Slovenia) ---
                "SI_EUR": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" }
                ],
                "SI_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SI_GBP": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "iban", label: "IBAN", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                //SB (Solomon Islands)
                "SB_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SB_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SB_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // South Africa 
                "ZA_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "number" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ZA_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "number" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ZA_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "number" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // South Georgia and the South Sandwich Islands
                "GS_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "GS_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "GS_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // South Korea
                "KR_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "KR_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "KR_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Spain
                "ES_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                "ES_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ES_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Sri Lanka – LK
                "LK_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "LK_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "LK_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Suriname 
                "SR_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SR_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SR_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Svalbard and Jan Mayen 
                "SJ_NOK": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SJ_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SJ_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SJ_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Sweden
                "SE_SEK": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SE_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SE_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "SE_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                // Switzerland
                "CH_CHF": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "CH_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "CH_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "CH_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                // Taiwan 
                "TW_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TW_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TW_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Tanzania
                "TZ_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TZ_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TZ_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Thailand 
                "TH_THB": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TH_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TH_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Togo 
                "TG_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TG_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TG_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Tokelau 
                "TK_NZD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TK_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TK_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TK_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Tonga 
                "TO_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TO_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TO_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Turkey 
                "TR_TRY": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TR_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TR_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Turkmenistan 
                "TM_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TM_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TM_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                //  Turks and Caicos Islands
                "TC_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TC_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TC_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Tuvalu 
                "TV_AUD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TV_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TV_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "TV_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Uganda 
                "UG_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UG_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UG_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // United Arab Emirates
                "AE_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "purpose_code", label: "Select purpose of payment code", type: "select", options: [/* 111 options */] },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "AE_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "purpose_code", label: "Select purpose of payment code", type: "select", options: [/* 111 options */] },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "AE_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "purpose_code", label: "Select purpose of payment code", type: "select", options: [/* 111 options */] },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // United Kingdom
                "GB_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "sort_code", label: "Sort code", type: "number", length: 6 },
                { name: "account_number", label: "Account number", type: "number", length: 8 }
                ],
                "GB_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "GB_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                //United States (US) 
                'US_USD': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'US_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'US_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'text' },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                // U.S. Minor Outlying Islands
                "UM_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UM_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UM_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
               // Virgin Islands
                "VI_USD": [
                    { name: "account_name", label: "Account name", type: "text" },
                    { name: "bic", label: "BIC", type: "text" },
                    { name: "account_number", label: "Account number", type: "text" },
                    { name: "address", label: "Recipient address", type: "text" },
                    { name: "city", label: "City", type: "text" },
                    { name: "state", label: "State", type: "text" },
                    { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VI_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VI_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Uruguay 
                "UY_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UY_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UY_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                //Uzbekistan 
                "UZ_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UZ_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "UZ_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Vatican City     
                "VA_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VA_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VA_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "iban", label: "IBAN", type: "text" }
                ],
                // Vietnam 
                "VN_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VN_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "VN_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Wallis and Futuna 
                "WF_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "WF_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "WF_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                // Zambia 
                "ZM_USD": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ZM_GBP": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],
                "ZM_EUR": [
                { name: "account_name", label: "Account name", type: "text" },
                { name: "bic", label: "BIC", type: "text" },
                { name: "account_number", label: "Account number", type: "text" },
                { name: "address", label: "Recipient address", type: "text" },
                { name: "city", label: "City", type: "text" },
                { name: "state", label: "State", type: "text" },
                { name: "zipcode", label: "Zipcode", type: "text" }
                ],






































            },
    
            computedFields() {
                console.log('Computing dynamic fields...');
                if (!this.selectedCountry || !this.selectedCurrency) {
                    console.warn('No selected country or currency');
                    return [];
                }
                const key = `${this.selectedCountry.alpha2}_${this.selectedCurrency}`;
                console.log('Computed key:', key);
                const fields = this.dynamicFields[key] || [];
                console.log('Dynamic fields:', fields);
                return fields;
            },
    
            selectCountry(country) {
                console.log('Country selected:', country);
                this.selectedCountry = country;
                this.open = false;
                this.search = '';
                this.currencies = country.currencies || [];
                console.log('Available currencies:', this.currencies);
    
                if (this.currencies.length > 0) {
                    this.selectedCurrency = this.currencies[0];
                    console.log('Auto-selected currency:', this.selectedCurrency);
                    this.updateBankOptions();
                } else {
                    this.selectedCurrency = null;
                    this.banks = [];
                    console.warn('No currencies found. Banks cleared.');
                }
    
                this.selectedBankId = null;
                this.selectedBankName = '';
                this.accountName = '';
            },
    
            selectBank(bank) {
                console.log('Bank selected:', bank);
                this.selectedBankId = bank.value;
                this.selectedBankName = bank.label;
                this.bankOpen = false;
    
                const hiddenBankInput = document.querySelector('input[name="bank_id"]');
                if (hiddenBankInput) {
                    hiddenBankInput.value = bank.value;
                    console.log('Hidden input [bank_id] updated:', hiddenBankInput.value);
                }
            },
    
            filteredCountries() {
                console.log('Filtering countries with search:', this.search);
                if (!this.search) return this.countries;
                const filtered = this.countries.filter(c =>
                    c.country_name.toLowerCase().includes(this.search.toLowerCase())
                );
                console.log('Filtered countries:', filtered);
                return filtered;
            },
    
            filteredBanks() {
                console.log('Filtering banks with search:', this.bankSearch);
                if (!this.bankSearch) return this.banks;
                const filtered = this.banks.filter(b =>
                    b.label.toLowerCase().includes(this.bankSearch.toLowerCase())
                );
                console.log('Filtered banks:', filtered);
                return filtered;
            },
    
            getCurrencySymbol(currency) {
                const symbols = {
                    USD: '💵', EUR: '💶', GBP: '💷', NGN: '₦', XOF: 'CFA',
                    JPY: '¥', INR: '₹', CAD: 'C$', AUD: 'A$',
                };
                const symbol = symbols[currency] || '';
                console.log(`Symbol for ${currency}: ${symbol}`);
                return symbol;
            },
    
            init() {
                console.log('Alpine component initialized.');
                this.$watch('selectedCurrency', () => {
                    console.log('Currency changed:', this.selectedCurrency);
                    this.updateBankOptions();
                });
            },
    
            updateBankOptions() {
                if (!this.selectedCountry || !this.selectedCurrency) {
                    console.warn('Missing country or currency. Bank fetch skipped.');
                    return;
                }
    
                const countryCode = this.selectedCountry.alpha2;
                const currencyCode = this.selectedCurrency;
    
                console.log(`Fetching banks for ${countryCode} / ${currencyCode}...`);
    
                fetch(`/fetch-banks?country=${countryCode}&currency=${currencyCode}`)
                    .then(res => res.json())
                    .then(data => {
                            console.log('Bank fetch response:', data);

                            if (data.status === 'success' && Array.isArray(data.fields)) {
                                const bankField = data.fields.find(
                                    field => field.name === 'bank_id' && Array.isArray(field.options)
                                );

                                if (bankField) {
                                    this.banks = bankField.options;
                                    console.log('Banks updated:', this.banks);
                                } else {
                                    this.banks = [];
                                    console.warn('No bank field found.');
                                }
                            } else {
                                this.banks = [];
                                console.warn('Invalid bank response format.');
                            }
                        })

                    .catch(err => {
                        this.banks = [];
                        console.error('Error fetching banks:', err);
                    });
            },
    
            validateAccount() {
                const country = this.selectedCountry?.alpha2;

    const currency = this.selectedCurrency;
    const bankId = this.selectedBankId;
    const accountNumber = this.accountNumber;

    console.log('Validation values:', { country, currency, bankId, accountNumber });

    this.isLoading = true;

    if (!country || !currency || !bankId || !accountNumber || accountNumber.length < 6) {
        console.warn('Validation failed. Required data missing.', {
            missing: {
                country: !country,
                currency: !currency,
                bankId: !bankId,
                accountNumber: !accountNumber || accountNumber.length < 6
            }
        });
        this.accountName = '';
        this.isLoading = false;
        return;
    }

    
                fetch('/validate-account', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        country,
                        currency,
                        bank_id: String(bankId),
                        account_number: accountNumber
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Account validation response:', data);
                    if (data.account_name) {
                        this.accountName = data.account_name;
                        console.log('Account name set:', this.accountName);
                    } else {
                        this.accountName = '';
                        console.warn('No account name returned.');
                    }
                })
                .catch(err => {
                    this.accountName = '';
                    console.error('Error validating account:', err);
                })
                .finally(() => {
                    this.isLoading = false;
                    console.log('Account validation finished.');
                });
            }
        };
    }
    </script>
    

    
    

<!-- Alpine.js CDN -->
<script src="//unpkg.com/alpinejs" defer></script>

    
</body>

</html>