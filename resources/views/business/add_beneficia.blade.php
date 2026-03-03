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
        {{-- <select id="bankSelect" name="bank[sortCode]" class="w-full border rounded-xl px-3 py-2 hidden">
            <option value="">Select Bank</option>
            @foreach($banks as $bank)
                <option value="{{ $bank->sort_code }}">{{ $bank->name }}</option>
            @endforeach
            </select> --}}

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
document.addEventListener("DOMContentLoaded", function() {

    const currencySelect  = document.getElementById("currencySelect");
    const transferMethod  = document.getElementById("transferMethod");
    const accountNumber   = document.getElementById("accountNumber");
    const bankSelect      = document.getElementById("bankSelect");
    const holderInput     = document.getElementById("accountHolder");
    const loadingText     = document.getElementById("accountLoading");
    const mobileInput     = document.getElementById("mobileNumber");
    const countrySelect   = document.getElementById("countrySelect");
    const SERVICES        = @json($pivotServices);

    let timer;

    const banksFilterUrl = "{{ route('banks.filter') }}";
    const PAYAZA_CURRENCIES = ["NGN","GHS","TZS","KES","XOF","XAF","ZAR","UGX"];

    let payazaProviders = { bank: [], mobile: [] };

    const pivotEnabled  = @json(filter_var(env('PIVOT_ENABLED'), FILTER_VALIDATE_BOOLEAN));
    const payazaEnabled = @json(filter_var(env('PAYAZA_ENABLED'), FILTER_VALIDATE_BOOLEAN));

    /* ================= FIELD TOGGLER ================= */
    function toggleField(field, show, required=false){
        if(!field) return;

        if(show){
            field.classList.remove("hidden");
            field.disabled = false;
            if(required) field.setAttribute("required","required");
        } else {
            field.classList.add("hidden");
            field.disabled = true;
            field.removeAttribute("required");
            field.value = "";
        }
    }

    /* ================= LOAD PIVOT BANKS ================= */
    function loadPivotBanks(){
        const country = countrySelect.value;
        if(!country) return;

        fetch(`${banksFilterUrl}?country=${country}&provider=pivot`)
        .then(r=>r.json())
        .then(list=>{
            let html='<option value="">Select Bank</option>';
            list.forEach(b=>{
                html+=`<option value="${b.sort_code || b.bank_code}">${b.name}</option>`;
            });
            bankSelect.innerHTML=html;
        });
    }

    /* ================= LOAD PAYAZA ================= */
    function loadPayazaProviders(){

        const country  = countrySelect.value;
        const currency = currencySelect.value;

        if(!country || !PAYAZA_CURRENCIES.includes(currency) || !payazaEnabled) return;

        // If UGX and Pivot is ON, stop Payaza
        if(currency === "UGX" && pivotEnabled) return;

        fetch(`${banksFilterUrl}?country=${country}&currency=${currency}&provider=payaza`)
        .then(r=>r.json())
        .then(res=>{

            const list = res.data || res;

            payazaProviders.bank   = [];
            payazaProviders.mobile = [];

            list.forEach(item=>{
                if(item.type === "mobile_money"){
                    payazaProviders.mobile.push(item);
                } else {
                    payazaProviders.bank.push(item);
                }
            });

            showTransferMethods();
        })
        .catch(err=>{
            console.error("Payaza fetch error:", err);
        });
    }

    /* ================= SHOW METHODS ================= */
    function showTransferMethods(){

        let html='<option value="">Select Method</option>';

        if(payazaProviders.bank.length)
            html+='<option value="bank">Bank</option>';

        if(payazaProviders.mobile.length)
            html+='<option value="mobile">Mobile</option>';

        transferMethod.innerHTML=html;

        if(payazaProviders.bank.length || payazaProviders.mobile.length){
            toggleField(transferMethod,true,true);
        } else {
            toggleField(transferMethod,false);
        }

        showFieldsByMethod();
    }

    /* ================= CURRENCY CHANGE ================= */
    function showFieldsByCurrency(){

        toggleField(transferMethod,false);
        toggleField(bankSelect,false);
        toggleField(accountNumber,false);
        toggleField(mobileInput,false);

        const currency = currencySelect.value;

        if(currency === "UGX"){

            if(pivotEnabled){
                toggleField(transferMethod,true,true);
            } 
            else if(payazaEnabled){
                loadPayazaProviders();
            }

        } else if(PAYAZA_CURRENCIES.includes(currency)){
            loadPayazaProviders();
        }
    }

    /* ================= METHOD CHANGE ================= */
    function showFieldsByMethod(){

        toggleField(bankSelect,false);
        toggleField(accountNumber,false);
        toggleField(mobileInput,false);

        const currency = currencySelect.value;
        const method   = transferMethod.value;

        /* ===== PIVOT ===== */
        if(currency==="UGX" && pivotEnabled){

            if(method==="mobile"){
                toggleField(mobileInput,true,true);
            }

            if(method==="bank"){
                loadPivotBanks();
                toggleField(bankSelect,true,true);
                toggleField(accountNumber,true,true);
            }
        }

        /* ===== PAYAZA ===== */
        else if(PAYAZA_CURRENCIES.includes(currency)){

            if(method==="bank"){

                let html='<option value="">Select Bank</option>';

                payazaProviders.bank.forEach(b=>{
                    html+=`<option value="${b.code || b.bank_code}">${b.name}</option>`;
                });

                bankSelect.innerHTML=html;

                toggleField(bankSelect,true,true);
                toggleField(accountNumber,true,true);
            }

            if(method==="mobile"){

                let html='<option value="">Select Provider</option>';

                payazaProviders.mobile.forEach(m=>{
                    html+=`<option value="${m.code || m.bank_code}">${m.name}</option>`;
                });

                bankSelect.innerHTML=html;

                toggleField(bankSelect,true,true);
                toggleField(mobileInput,true,true);
            }
        }
    }

    /* ================= ACCOUNT VALIDATION ================= */
    async function validateAccount(){

        const currency = currencySelect.value;
        const method   = transferMethod.value;

        let payload = null;
        let route   = "";

        /* ===== PIVOT ===== */
        if(currency==="UGX" && pivotEnabled){

            if(method==="mobile"){
                const mobile = mobileInput.value.trim();
                if(!mobile) return;

                payload = {
                    serviceCode: SERVICES.ugx_mobile_service,
                    accountNumber: mobile,
                    msisdn: mobile
                };
            }

            if(method==="bank"){
                const acc  = accountNumber.value.trim();
                const code = bankSelect.value;
                if(!acc || !code) return;

                payload = {
                    serviceCode: SERVICES.ugx_bank_service,
                    accountNumber: acc,
                    msisdn: acc,
                    extraData: { bankSortCode: code, amount: "0" }
                };
            }

            route = "{{ route('pivot.account.validation') }}";
        }

        /* ===== PAYAZA ===== */
        else if(PAYAZA_CURRENCIES.includes(currency)){

            if(method==="bank"){
                const acc  = accountNumber.value.trim();
                const code = bankSelect.value;
                if(!acc || !code) return;

                payload = { currency, account_number: acc, bank_code: code, type:"bank" };
            }

            if(method==="mobile"){
                const mobile = mobileInput.value.trim();
                const code   = bankSelect.value;
                if(!mobile || !code) return;

                payload = { currency, account_number: mobile, bank_code: code, type:"mobile" };
            }

            route = "{{ route('payaza.account-enquiry') }}";
        }

        if(!payload) return;

        try{

            loadingText.classList.remove("hidden");
            holderInput.value="";
            holderInput.classList.remove("border-green-500","bg-green-100");
            holderInput.setAttribute("readonly",true);

            const res = await fetch(route,{
                method:"POST",
                headers:{
                    "Content-Type":"application/json",
                    "X-CSRF-TOKEN":"{{ csrf_token() }}"
                },
                body:JSON.stringify(payload)
            });

            const data = await res.json();
            loadingText.classList.add("hidden");

            let name="Account not found";
            let valid=false;

            if(currency==="UGX" && pivotEnabled){
                if(data.accountName){
                    name=data.accountName;
                    valid=true;
                }
            }
            else if(data.success && data.data?.response_content?.account_name){
                name=data.data.response_content.account_name;
                valid=true;
            }

            holderInput.value=name;

            if(valid){
                holderInput.classList.add("border-green-500","bg-green-100");
            }

        } catch(e){
            console.error(e);
            loadingText.classList.add("hidden");
            holderInput.value="Validation failed";
        }
    }

    /* ================= EVENTS ================= */
    currencySelect.addEventListener("change",showFieldsByCurrency);
    transferMethod.addEventListener("change",showFieldsByMethod);

    accountNumber.addEventListener("input",()=>{
        clearTimeout(timer);
        timer=setTimeout(validateAccount,700);
    });

    mobileInput.addEventListener("input",()=>{
        clearTimeout(timer);
        timer=setTimeout(validateAccount,700);
    });

    bankSelect.addEventListener("change",validateAccount);

    countrySelect.addEventListener("change",showFieldsByCurrency);

    showFieldsByCurrency();

});
</script>




    {{-- for both country and currency logo --}}
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