@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <section class=" relative w-full">
           <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
            <section class="flex flex-col md:flex-row gap-10 mx-auto max-w-full min-h-screen">
                <!-- Left side: Table and search -->
                <section class="flex-1 overflow-auto ">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 md:gap-10">
                        <div class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full sm:w-[400px] mb-4">
                            <i class="fas fa-search text-gray-400 mr-3"></i>
                            <input type="search" id="search-input" placeholder="Search beneficiaries"
                                class="w-full text-sm text-gray-600 placeholder-gray-400 focus:outline-none" />
                        </div>
                        <a href="{{ route('add_beneficias.create') }}"
                            class="flex items-center justify-center gap-1 rounded-full bg-blue-200 text-blue-800 text-[12px] font-semibold px-4 py-2 min-w-[140px] hover:bg-blue-300 transition">
                                <i class="fas fa-plus"></i> Add Beneficiary
                            </a>

                    </div>
                    <table class="w-full border-collapse text-sm ">
                        <thead>
                            <tr class="text-gray-600 font-semibold text-left border-b border-gray-300">
                                <th class="py-3 pl-4 ">Full Name</th>
                                <th class="py-3 px-4">Bank</th>
                                <th class="py-3 px-4">Bank Account no.</th>
                                <th class="py-3 pr-4 pl-6">Bank country</th>
                            </tr>
                        </thead>
                        <tbody id="beneficiaries-table" class="divide-y divide-gray-200">
                            @foreach ($beneficiaries as $beneficia)
                            <tr onclick="window.location='{{ route('beneficias') }}'"
                                class="bg-blue-50 font-semibold text-gray-900 cursor-pointer hover:bg-blue-100 transition">
                                <td class="py-3 pl-4">{{ $beneficia->account_name }}</td>
                                <td class="py-3 px-4">{{ $beneficia->bank }}</td>
                                <td class="py-3 px-4">{{ $beneficia->account_number }}</td>
                                <td class="py-3 pr-4 pl-6">{{ $beneficia->country }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $beneficiaries->links() }}
                    </div>
                </section>
            
                <!-- Right side: Form -->
                <section class="w-full max-w-md border-l border-gray-200 pl-8">
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
                        
            
                        <!-- Hidden input for country -->

                        <div x-data="countrySelector()" x-init="init()">
                            <input type="hidden" name="country" :value="selectedCountry?.alpha2">
                            <input type="hidden" name="currency" :value="selectedCurrency">
                            <input type="hidden" name="account_name" :value="accountName">
                            <input type="hidden" name="bank_id" :value="selectedBankId">
                            <input type="hidden" name="account_number" :value="accountNumber">

                            <!-- COUNTRY SELECTOR -->
                            <label class="block font-medium mb-1">In what country is your bank located?</label>
                            <button @click="open = !open" type="button"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-left flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <template x-if="selectedCountry">
                                        <img :src="'https://flagcdn.com/24x18/' + selectedCountry.alpha2.toLowerCase() + '.png'" class="w-5 h-auto" alt="">
                                    </template>
                                    <span x-text="selectedCountry ? selectedCountry.country_name : 'Select country'"></span>
                                </div>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        
                            <!-- Country Dropdown -->
                            <div x-show="open" class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto shadow-lg">
                                <div @click.stop>
                                    <div class="p-2">
                                        <input type="text" x-model="search" placeholder="Search country..." class="w-full px-3 py-1 border rounded">
                                    </div>
                                    <ul>
                                        <template x-for="country in filteredCountries()" :key="country.alpha2">
                                            <li @click="selectCountry(country)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2">
                                                <img :src="'https://flagcdn.com/24x18/' + country.alpha2.toLowerCase() + '.png'" class="w-5 h-auto" alt="">
                                                <span x-text="country.country_name"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <!-- CURRENCY SELECTOR -->
                            <div class="mt-4" x-show="currencies.length > 0" x-cloak>
                                <label class="block font-medium mb-1">Select Currency</label>
                                <div class="relative">
                                    <button @click="currencyOpen = !currencyOpen" type="button"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-left flex items-center justify-between">
                                        <span x-text="selectedCurrency ? getCurrencySymbol(selectedCurrency) + ' ' + selectedCurrency : 'Choose currency'"></span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="currencyOpen" @click.outside="currencyOpen = false"
                                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow max-h-60 overflow-y-auto">
                                        <ul>
                                            <template x-for="currency in currencies" :key="currency">
                                                <li @click="selectedCurrency = currency; currencyOpen = false; updateBankOptions();"
                                                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                                    <span x-text="getCurrencySymbol(currency) + ' ' + currency"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- NON-NGN (USD, EUR, GBP) DYNAMIC FIELDS -->
                            <div x-show="selectedCurrency !== 'NGN' && computedFields().length > 0" x-cloak class="mt-4 space-y-4">
                                <template x-for="field in computedFields()" :key="field.name">
                                    <div>
                                        <label class="block font-medium mb-1" x-text="field.label"></label>
                                        <input 
                                            :type="field.type" 
                                            :name="field.name" 
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2" 
                                            :maxlength="field.length || null"
                                        />
                                    </div>
                                </template>
                            </div>


                        
                            <!-- NGN SECTION -->
                            {{-- <div x-show="selectedCurrency === 'NGN'" x-cloak class="mt-4 space-y-4">
                                <!-- BANK SELECTOR -->
                                <div>
                                    <label class="block font-medium mb-1">Bank</label>
                                    <div class="relative">
                                        <button @click="bankOpen = !bankOpen" type="button"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-left flex items-center justify-between">
                                            <span x-text="selectedBankName || 'Select your bank'"></span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                        
                                        <div x-show="bankOpen" @click.outside="bankOpen = false"
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow max-h-60 overflow-y-auto">
                                            <div class="p-2 border-b">
                                                <input type="text" x-model="bankSearch" placeholder="Search bank..." class="w-full px-3 py-1 border rounded" />
                                            </div>
                                            <ul>
                                                <template x-for="bank in filteredBanks()" :key="bank.value">
                                                    <li @click="selectBank(bank)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                                        <span x-text="bank.label"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                        
                                <!-- ACCOUNT NUMBER -->
                                <input type="text" id="account-number" name="account_number_input"
                                x-model="accountNumber"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                placeholder="Enter your account number"
                                @input.debounce.500ms="validateAccount">
                         

                        
                                <!-- ACCOUNT NAME -->
                                <div x-show="accountName">
                                    <p class="text-sm mt-2 text-green-600 font-semibold" x-text="accountName"></p>
                                </div>
                        
                                <!-- LOADING SPINNER -->
                                <div x-show="isLoading" class="mt-2 text-blue-500 text-sm flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Validating account number...
                                </div>
                            </div> --}}
                            <div x-show="selectedCurrency === 'NGN' || selectedCurrency === 'GHS' || selectedCurrency === 'KES'" x-cloak class="mt-4 space-y-4">
                                <!-- BANK SELECTOR -->
                                <div>
                                    <label class="block font-medium mb-1">Bank</label>
                                    <div class="relative">
                                        <button @click="bankOpen = !bankOpen" type="button"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 text-left flex items-center justify-between">
                                            <span x-text="selectedBankName || 'Select your bank'"></span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                            
                                        <div x-show="bankOpen" @click.outside="bankOpen = false"
                                            class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow max-h-60 overflow-y-auto">
                                            <div class="p-2 border-b">
                                                <input type="text" x-model="bankSearch" placeholder="Search bank..." class="w-full px-3 py-1 border rounded" />
                                            </div>
                                            <ul>
                                                <template x-for="bank in filteredBanks()" :key="bank.value">
                                                    <li @click="selectBank(bank)" class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                                        <span x-text="bank.label"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            
                                <!-- ACCOUNT NUMBER -->
                                <input type="text" id="account-number" name="account_number_input"
                                    x-model="accountNumber"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                                    placeholder="Enter your account number"
                                    @input.debounce.500ms="validateAccount">
                            
                                <!-- ACCOUNT NAME -->
                                <div x-show="accountName">
                                    <p class="text-sm mt-2 text-green-600 font-semibold" x-text="accountName"></p>
                                </div>
                            
                                <!-- LOADING SPINNER -->
                                <div x-show="isLoading" class="mt-2 text-blue-500 text-sm flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    Validating account number...
                                </div>
                            </div>
                            
                            
    


                        </div>
                        
                        
        

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
                </section>
            </section>
           </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('search-input');
            const tableBody = document.getElementById('beneficiaries-table');
    
            input.addEventListener('keyup', function () {
                const query = this.value;
    
                fetch(`/beneficiaries/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        let rows = '';
    
                        if (data.length === 0) {
                            rows = '<tr><td colspan="4" class="text-center py-4 text-gray-500">No results found</td></tr>';
                        } else {
                            data.forEach(b => {
                                rows += `
                                    <tr onclick="window.location='/beneficias/${b.id}/edit'" 
                                        class="bg-blue-50 font-semibold text-gray-900 cursor-pointer hover:bg-blue-100 transition">
                                        <td class="py-3 pl-4">${b.account_name}</td>
                                        <td class="py-3 px-4">${b.bank}</td>
                                        <td class="py-3 px-4">${b.account_number}</td>
                                        <td class="py-3 pr-4 pl-6">${b.country.name}</td>
                                    </tr>
                                `;
                            });
                        }
    
                        tableBody.innerHTML = rows;
                    });
            });
        });
    </script>
    
    
    
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
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NG_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'NG_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
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
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AS_GBP': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
                    { name: 'address', label: 'Recipient address', type: 'text' },
                    { name: 'city', label: 'City', type: 'text' },
                    { name: 'state', label: 'State', type: 'text' },
                    { name: 'zipcode', label: 'Zipcode', type: 'text' }
                ],
                'AS_EUR': [
                    { name: 'account_name', label: 'Account name', type: 'text' },
                    { name: 'bic', label: 'BIC', type: 'text' },
                    { name: 'account_number', label: 'Account number', type: 'number', length: 10 },
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
                        if (data.status === 'success' && data.fields?.[0]?.options) {
                            this.banks = data.fields[0].options;
                            console.log('Banks updated:', this.banks);
                        } else {
                            this.banks = [];
                            console.warn('No valid banks returned.');
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