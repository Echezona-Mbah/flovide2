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
                Subaccounts
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative  w-full">
            <section class="flex w-full flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
                <!-- Left form -->
                <section class="max-w-7xl mx-auto flex w-full flex-col md:flex-row gap-14">
                    <!-- Left form section -->
                    <section class="flex-1 max-w-lg">
                        <h1 class="font-semibold text-2xl text-gray-900 mb-2">Add a Subaccount</h1>
                        <p class="text-gray-500 mb-8">
                            You can split out-going funds between subaccounts and your payout account.
                        </p>

                        @if($errors->any())
                            <script>
                                @if ($errors->any())
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Please fix the following errors:',
                                        html: `
                                            <ul style="padding-left: 1.2em; margin: 0;">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        `,
                                        showConfirmButton: false,
                                        timer: 5000,
                                        timerProgressBar: true,
                                        customClass: {
                                            popup: 'text-sm'
                                        }
                                    });
                                @endif
                            </script>
                        @endif
                        
                        @if(session('success'))
                            {{-- <div class="text-green-600 mb-2">{{ session('success') }}</div> --}}
                            <script>
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: '{{ session('success') }}',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                            </script>
                        @endif

                        <form id="subAccountForm" method="POST" action="" class="space-y-6">
                            @csrf
                            
                            <div>
                                <label for="country" class="block text-gray-600 text-sm mb-1 font-semibold">
                                    In what country is your bank located?
                                </label>
                                <select id="country" name="bank_country" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <option value="" selected disabled>Select country</option>
                                    @foreach($countries as $country)
                                        <option 
                                            value="{{ $country['country_name'] }}"
                                            data-fullCurrency="{{ $country['alpha2'] }}_{{ $country['default_currency'] }}" 
                                            data-currency="{{ $country['default_currency'] }}" data-alpha2="{{ $country['alpha2'] }}"
                                        >
                                            {{ $country['country_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div id="staticFields">
                                <div>
                                    <label for="bank" class="block text-gray-600 text-sm mb-1 font-semibold">Bank</label>
                                    <select id="bankBB" name="bank_name" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="" selected disabled>Select your bank</option>
                                        @foreach($banks as $bank)
                                            <option value="{{ $bank->name }}" data-code="{{ $bank->country_code  }}">
                                                {{ $bank->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                    
                                <div>
                                    <label for="account-number" class="block text-gray-600 text-sm mb-1 font-semibold">
                                        Bank account number
                                    </label>
                                    <input type="text" id="account-number" name="account_number" placeholder="12345678" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                </div>
                    
                                <div>
                                    <div class="flex items-center gap-2">
                                        <label for="account-name" class="block text-gray-600 text-sm mb-1 font-semibold">
                                            Bank account name
                                        </label>
                                        <!-- Spinner (hidden by default) -->
                                        <svg id="account_spinner" class="animate-spin h-4 w-4 text-blue-500 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="account-name" disabled name="account_name" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                </div>
                            </div>
                

                            <!-- Dynamic fields for other countries -->
                            <div id="dynamicFields" class="flex flex-col gap-3"></div>

                            <button type="submit" class="bg-blue-200 text-blue-900 font-semibold rounded-full px-6 py-2 hover:bg-blue-300 transition">
                                Add Account
                            </button>
                        </form>
                    </section>
                
                    <!-- Right subaccounts section -->
                    <section class="flex-1 max-w-lg bg-gray-100 rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-gray-900">Subaccounts</h2>
                            <button type="button" class="delete-all-btn flex items-center gap-2 text-red-600 bg-white rounded-full px-4 py-1 text-sm font-semibold hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt"></i> Delete All
                            </button>
                        </div>
                
                        <ul class="space-y-3">
                            @if($subaccounts->isEmpty())
                                <p>No Sub bank account found.</p>
                            @else
                                @foreach($subaccounts as $account)
                                    @php
                                        try {
                                            // $accountNumber = Crypt::decryptString($account->account_number);
                                            $accountNumber = ($account->account_number) ? Crypt::decryptString($account->account_number) : Crypt::decryptString($account->iban);
                                        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                            $accountNumber = 'Invalid or Unencrypted';
                                        }
                                    @endphp
                                    <li class="flex justify-between items-center bg-white rounded-xl px-5 py-3 text-gray-900 font-normal text-base">
                                        <div class="flex items-center gap-3">
                                            <span>{{ $accountNumber }}</span>
                                            <span class="bg-gray-300 text-gray-700 text-xs font-semibold rounded-full px-2 py-0.5 select-none">
                                                {{ ($account->bank_name) ? $account->bank_name : 'INTERNATIONAL' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4 text-gray-400">
                                            <button aria-label="" class="hover:text-gray-600">
                                                <a href="{{ route('business.subaccountEdit', $account->id) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </button>
                                            <button aria-label="" class="hover:text-gray-600 delete-icon" data-id="{{ $account->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        
                    </section>
                </section>
            </section>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        //for searching of country name
        document.addEventListener("DOMContentLoaded", () => { 
            const countrySelect = document.querySelector("#country");
            // Enable searchable select
            if (!countrySelect.tomselect) {
                new TomSelect("#country", {
                    create: false,
                    sortField: { field: "text", direction: "asc" }
                });
            }
        });


        const dynamicFields = {
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


        };


        document.addEventListener('DOMContentLoaded', function () {
            const allowedCurrencies = ['NGN', 'GHS', 'KES'];

            const countrySelect = document.getElementById('country');
            const staticFields = document.getElementById('staticFields');
            const dynamicFieldsContainer = document.getElementById('dynamicFields');

            countrySelect.addEventListener('change', function () {
                // const selectedValue = this.value; // e.g. "NG_USD"
                const selectedValue = this.selectedOptions[0].dataset.fullcurrency; // e.g. "NG_USD"
                const currencyOnly = this.selectedOptions[0].dataset.currency; // e.g. "USD"

                dynamicFieldsContainer.innerHTML = ''; // Clear old dynamic fields

                if (allowedCurrencies.includes(currencyOnly)) {
                    staticFields.style.display = 'block';
                    //call the function to fetch banks
                    Fetch_updateBankOptions();
                } else {
                    staticFields.style.display = 'none';

                    const fields = dynamicFields[selectedValue] || [];
                    fields.forEach(field => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'flex flex-col gap-1';

                        const label = document.createElement('label');
                        label.setAttribute('for', field.name);
                        label.textContent = field.label;

                        const input = document.createElement('input');
                        input.className = 'border rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]';
                        input.id = field.name;
                        input.name = field.name;
                        input.type = field.type;
                        if (field.length) input.maxLength = field.length;

                        wrapper.appendChild(label);
                        wrapper.appendChild(input);
                        dynamicFieldsContainer.appendChild(wrapper);
                    });
                }
            });
        });

        //fetch bank
        function Fetch_updateBankOptions() {
            const countrySelect = document.getElementById('country');
            // const selectedValue = countrySelect.value;  
            const countryCurrencyCode = countrySelect.options[countrySelect.selectedIndex].dataset.alpha2;
            const currencyCode = countrySelect.options[countrySelect.selectedIndex].dataset.currency;

            if(!currencyCode || !countryCurrencyCode) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    title: 'Please select a valid country'
                });
                return;
            }
            const bankSelect = document.getElementById('bankBB');
            // Clear existing options first
            bankSelect.innerHTML = `<option value="" disabled selected>Select your bank</option>`;

            console.log(`Fetching banks for ${countryCurrencyCode} / ${currencyCode}...`);

            fetch(`{{ route('subaccounts.fetch.localbanks') }}?countryCurrency=${countryCurrencyCode}&currency=${currencyCode}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.fields?.[0]?.options) {
                    let banks = data.fields[0].options;

                    banks.forEach(bank => {
                        let option = document.createElement('option');
                        option.value = bank.value; // use the numeric value or bank_code
                        option.textContent = bank.label; // display name in dropdown
                        option.setAttribute('data-code', bank.bank_code ?? '');
                        option.setAttribute('data-nibss', bank.bank_nibss_code ?? '');
                        option.setAttribute('data-id', bank.value ?? '');
                        option.setAttribute('data-label', bank.label ?? '');
                        bankSelect.appendChild(option);
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        title: 'No valid banks returned.'
                    });
                    return;
                }
            })
            .catch(err => {
                console.error('Error fetching banks:', err);
            });
        }


        document.getElementById("account-number").addEventListener("input", function () {
            let accountNumber = this.value;
            if(accountNumber.length === 10) {
                document.getElementById("account_spinner").classList.remove("hidden");
                validateAccount();
            }
        });

        //validate payout account name
        function validateAccount() {
            const countrySelect = document.getElementById('country');
            const country = countrySelect.options[countrySelect.selectedIndex].dataset.alpha2;
            const currency = countrySelect.options[countrySelect.selectedIndex].dataset.currency;
            
            const selectElement = document.querySelector("#bankBB");
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const bankId = selectedOption.getAttribute("data-id");

            const accountName = document.querySelector("#account-name");
            const accountNumber = document.querySelector("#account-number").value;
            //get the spinner
            let account_spinner = document.getElementById("account_spinner");

            if (!country || !currency || !bankId || !accountNumber || accountNumber.length < 6) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    title: 'Validation failed, Required data missing.'
                });
                return;
            }

            const formData = new FormData();
            formData.append('country', country);
            formData.append('currency', currency);
            formData.append('bank_id', String(bankId));
            formData.append('account_number', accountNumber);

            // Make the API call to validate the account name
            fetch('{{ route("subaccounts.validatePayoutAccountName") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                console.log('Account validation response:', data);
                if (data.status === 'success' && data.account_name) {
                    accountName.value = data.account_name;
                    account_spinner.classList.add("hidden");
                } else {
                    accountName.value = '';
                    console.warn('No account name returned.');
                    account_spinner.classList.add("hidden");
                }
            })
            .catch(err => {
                accountName.value = '';
                account_spinner.classList.add("hidden");
                console.error('Error validating account:', err);
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    title: 'Error validating account'
                });
                return;
            });
        }

        //submit form
        const form = document.getElementById('subAccountForm');
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const allowedCurrenciesFrom = ['NGN', 'GHS', 'KES'];
            const countrySelect = document.getElementById('country');
            const selectedValue = countrySelect.value;  
            const fullCurrency = countrySelect.options[countrySelect.selectedIndex].dataset.fullCurrency;
            const currencyOnlyForm = countrySelect.options[countrySelect.selectedIndex].dataset.currency;
            
            if(!currencyOnlyForm) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    title: 'Please select a valid country'
                });
                return;
            }

            if (allowedCurrenciesFrom.includes(currencyOnlyForm)) {
                // Static fields validation
                const country = document.querySelector("#country").value;
                const selectElement = document.querySelector("#bankBB");
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const bank = selectedOption.dataset.label;
                const account_number = document.querySelector("#account-number").value;
                const account_name = document.querySelector("#account-name").value;
                console.log("Selected bank:", bank);

                if (!country || !bank || !account_number || !account_name) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        title: 'All fields are required'
                    });
                    return;
                }

                const formData = new FormData();
                formData.append('account_name', account_name);
                formData.append('account_number', account_number);
                formData.append('bank_country', country);
                formData.append('bank_name', bank);
                formData.append('currency', currencyOnlyForm);
                formData.append('type', "local");
                formData.append('formDynamicFields', false);

                //proceed with the form submission
                // form.submit();
                validateForm(formData);
                console.log("local data: ", Object.fromEntries(formData.entries()));

            }else{

                const formData = new FormData(this);
                formData.append('formDynamicFields', true);
                formData.append('bank_country', selectedValue);
                formData.append('currency', currencyOnlyForm);
                formData.append('type', "foreign");
                const values = {};

                for (let [key, val] of formData.entries()) {
                    values[key] = val.trim();
                }
                // Run dynamic inputs validation
                if (!validateDynamicFormInputs(values, fullCurrency)) {
                    return;
                }
                // If valid, proceed with the form submission
                validateForm(formData);
                console.log(values);
            }

            //validate dynamic form inputs
            function validateDynamicFormInputs(values, countryCurrencyKey) {
                let errors = [];

                // get only the dynamic fields for the selected country/currency
                const fields = dynamicFields[countryCurrencyKey] || [];

                fields.forEach(field => {
                    const key = field.name;
                    if (!values[key]) {
                        errors.push(`${field.label} is required`);
                    }
                });

                if (errors.length > 0) {
                    Swal.fire({
                        toast: true,
                        icon: 'error',
                        title: errors[0], // show first error only
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    return false;
                }
                return true;
            }



            function validateForm(data) {
                fetch('{{ route("subaccounts.store") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: data
                })
                .then(response => response.json()).then(data => {
                    if (data.status == "success") {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Added!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didClose: () => {
                                location.reload();
                            }
                        });

                    } else {
                       Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            // didClose: () => {
                            //     location.reload();
                            // }
                        });

                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        });


        document.querySelector('.delete-all-btn').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete all your saved Sub bank accounts!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete all!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/business/delete-subaccounts/delete-all', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire('Deleted!', 'All your bank accounts have been deleted.', 'success').then(() => {
                            location.reload();
                        });
                    })
                    .catch(err => {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                        console.log(err);
                    });
                }
            });
        });


        document.querySelectorAll('.delete-icon').forEach((button) => {
            button.addEventListener('click', function() {
                const accountId = this.getAttribute('data-id');  
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/business/deleteSubaccount/${accountId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your bank account has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting your bank account.',
                                    'error'
                                );
                                console.log(data);
                            }
                        })
                        .catch(error => console.log('Error:', error));
                    }
                });
            });
        });


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
</body>

</html>