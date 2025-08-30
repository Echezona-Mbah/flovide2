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
            Edit Payout Account
            </h1>
            @include('business.header_notifical')

        </header>

            <section class=" relative ">
                <section
                    class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
                    
                    <!-- Left form -->
                    <section class="flex flex-col lg:flex-row gap-8 bg-white rounded-tl-3xl md:p-6 p-2 ">
                        <!-- Left form section -->
                        <section class="flex-1 max-w-lg">
                            <h1 class="font-semibold text-2xl mb-2">Edit Payout Account</h1>
                            <p class="text-gray-500 mb-8 text-sm max-w-md">
                                You can make withdrawals directly into any payout account of your choice.
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
                                {{-- <div class="text-red-600 mb-2">
                                    <ul class="list-disc pl-5">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div> --}}
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
                        
                            <form method="POST" action="{{ route('business.update', $bankAccount->id) }}" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="country" class="block text-gray-600 text-sm mb-1 font-medium">
                                        In what country is your bank located?
                                    </label>
                                    <select id="country" name="bank_country" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="{{ old('bank_country', $bankAccount->bank_country) }}">{{ old('bank_country', $bankAccount->bank_country) }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country['country_name'] }}"
                                            data-fullCurrency="{{ $country['alpha2'] }}_{{ $country['default_currency'] }}" 
                                            data-currency="{{ $country['default_currency'] }}"
                                            >
                                                {{ $country['country_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($bankAccount->type === 'foreign')
                                    {{-- <p class="text-red-600 text-sm mt-4">
                                        Note: This is an international payout account. Ensure you have the correct details.
                                    </p> --}}
                                    <div>
                                        <label for="account-name" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Bank account name
                                        </label>
                                        <input type="text" id="account-name" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    {{-- <div>
                                        <label for="account-number" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Bank account number
                                        </label>
                                        <input type="text" id="account-number" name="account_number" value="{{ old('account_number', Crypt::decryptString($bankAccount->iban)) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div> --}}

                                    <div>
                                        <label for="bic" class="block text-gray-600 text-sm mb-1 font-medium">
                                            BIC
                                        </label>
                                        <input type="text" id="bic" name="bic" value="{{ old('bic', Crypt::decryptString($bankAccount->bic)) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    <div>
                                        <label for="iban" class="block text-gray-600 text-sm mb-1 font-medium">
                                            IBAN
                                        </label>
                                        <input type="text" id="iban" name="iban" value="{{ old('iban', Crypt::decryptString($bankAccount->iban)) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    <div>
                                        <label for="city" class="block text-gray-600 text-sm mb-1 font-medium">
                                            City
                                        </label>
                                        <input type="text" id="city" name="city" value="{{ old('city', $bankAccount->city) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    <div>
                                        <label for="state" class="block text-gray-600 text-sm mb-1 font-medium">
                                            State
                                        </label>
                                        <input type="text" id="state" name="state" value="{{ old('state', $bankAccount->state) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    <div>
                                        <label for="recipient_address" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Address
                                        </label>
                                        <input type="text" id="address" name="address" value="{{ old('recipient_address', $bankAccount->recipient_address) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>

                                    <div>
                                        <label for="zipcode" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Zipcode
                                        </label>
                                        <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode', $bankAccount->zipcode) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>
                                    <input type="text" name="type" value="foreign" class="hidden" />
                            
                                @else
                                    

                                    <div>
                                        <label for="bank" class="block text-gray-600 text-sm mb-1 font-medium">Bank</label>
                                        <select id="bank" name="bank_name" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                            <option value="{{ old('bank_name', $bankAccount->bank_name) }}">{{ old('bank_name', $bankAccount->bank_name) }}</option>
                                            @foreach($banks as $bank)
                                                <option value="{{ $bank->name }}" data-code="{{ $bank->country_code  }}">
                                                    {{ $bank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                            
                                    <div>
                                        <label for="account-number" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Bank account number
                                        </label>
                                        <input type="text" id="account-number" name="account_number" value="{{ old('account_number', Crypt::decryptString($bankAccount->account_number ?: $bankAccount->iban)) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>
                            
                                    <div>
                                        <label for="account-name" class="block text-gray-600 text-sm mb-1 font-medium">
                                            Bank account name
                                        </label>
                                        <input type="text" id="account-name" name="account_name" value="{{ old('account_name', $bankAccount->account_name) }}" class="w-full border border-gray-300 rounded-md px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                    </div>
                                    <input type="text" name="type" value="local" class="hidden" />
                            
                                    
                                @endif

                                <div class="flex gap-4">
                                    <button type="submit" class="bg-blue-300 text-blue-800 font-semibold px-6 py-2 rounded-full text-sm hover:bg-blue-400 transition">
                                        Update Account
                                    </button>
                                    <a href="{{ route("business.payouts") }}">
                                        <button type="button" class="border border-gray-300 rounded-full px-6 py-2 text-sm font-normal hover:bg-gray-100 transition">
                                            Cancel
                                        </button>
                                    </a>
                                </div>
                            </form>
                            
                        </section>
                        <!-- Right payout accounts list -->
                        <section class="flex-1 max-w-full lg:max-w-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-base text-[#1E1E1E]">
                                    Payout Accounts
                                </h3>
                                <button aria-label="Delete All Payout Accounts" class="delete-all-btn flex items-center gap-1 text-[#D92D20] text-sm font-semibold rounded-md px-3 py-1 border border-[#D92D20] whitespace-nowrap">
                                    <i class="fas fa-trash-alt">
                                    </i>
                                    Delete All
                                </button>
                            </div>
                            <div aria-label="List of payout accounts" class="bg-[#F7F7F7] rounded-xl p-2 md:p-5 flex flex-col gap-3 max-w-full lg:max-w-lg overflow-x-scroll md:overflow-hidden">
                                <p id="default-message" class="mt-4 text-green-600 font-medium hidden"></p>
                                @foreach($allUserAccounts as $account)
                                    @if($account->default)
                                        <div aria-label="Payout account 2100048486 GTBank payout account selected" class="flex items-center gap-4 bg-white rounded-lg py-3 px-4 max-w-full">
                                            <div aria-hidden="true" class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#E6F4F1] flex-shrink-0">
                                                <i class="fas fa-check text-[#00875F] text-lg">
                                                </i>
                                            </div>
                                    @else
                                        <div aria-label="" class="flex items-center gap-4 bg-[#E9E9E9] rounded-lg py-3 px-4 max-w-full">
                                            <div aria-hidden="true" data-id="{{ $account->id }}" class="set-default-btn flex items-center justify-center w-9 h-9 rounded-lg bg-[#C4C4C4] flex-shrink-0">
                                                <i class="fas fa-check text-[#6B6B6B] text-lg"></i>
                                            </div>
                                    @endif
                                        <span class="font-normal text-base text-[#1E1E1E] select-none">
                                            {{-- {{ Crypt::decryptString($account->account_number) }} --}}
                                            {{ ($account->account_number) ? Crypt::decryptString($account->account_number) : Crypt::decryptString($account->iban)}}
                                        </span>
                                        <span class="text-xs font-normal text-[#4B4B4B] bg-[#E9E9E9] rounded-full py-1 px-2 whitespace-nowrap">
                                            {{ ($account->bank_name) ? $account->bank_name : 'INTERNATIONAL' }}
                                        </span>
                                        @if($account->default)
                                            <span class="text-xs font-semibold text-[#00875F] bg-[#E6F4F1] rounded-full py-1 px-2 flex items-center gap-1 whitespace-nowrap">
                                                <i class="fas fa-university text-xs"></i>
                                                PAYOUT ACCOUNT
                                            </span>
                                        @endif
                                        @if ($account->id !== $bankAccount->id)
                                            <button class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                                <a href="{{ route('business.edit', $account->id) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </button>
                                            <button class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0 delete-icon"  data-id="{{ $account->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                                
                            </div>
                        </section>
                    </section>
                </section>

    </main>

    <!-- Main content end -->
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

        document.querySelectorAll('.set-default-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;  // Get the account ID
        
                // Send AJAX request to set the default
                fetch(`/business/bank-accounts/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // const msg = document.getElementById('default-message');
                    // msg.textContent = data.message;
                    // msg.classList.remove('hidden');
                    Swal.fire('Set!', data.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .catch(err => console.log(err));
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
                        fetch(`/business/delete-account/${accountId}`, {
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


        document.querySelector('.delete-all-btn').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete all your saved bank accounts!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete all!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/business/bank-accounts/delete-all', {
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