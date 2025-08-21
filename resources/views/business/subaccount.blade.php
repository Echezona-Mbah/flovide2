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
                            <div class="text-red-600 mb-2">
                                <ul class="list-disc pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="text-green-600 mb-2">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('business.store') }}" class="space-y-6">
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

                            <div>
                                <label for="bank" class="block text-gray-600 text-sm mb-1 font-semibold">Bank</label>
                                <select id="bank" name="bank_name" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
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
                                <input type="text" id="account-number" name="account_number" placeholder="12345678" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                            </div>
                
                            <div>
                                <label for="account-name" class="block text-gray-600 text-sm mb-1 font-semibold">
                                    Bank account name
                                </label>
                                <input type="text" id="account-name" name="account_name" placeholder="John Doe" class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                            </div>
                
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
                                            $accountNumber = Crypt::decryptString($account->account_number);
                                        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                                            $accountNumber = 'Invalid or Unencrypted';
                                        }
                                    @endphp
                                    <li class="flex justify-between items-center bg-white rounded-xl px-5 py-3 text-gray-900 font-normal text-base">
                                        <div class="flex items-center gap-3">
                                            <span>{{ $accountNumber }}</span>
                                            <span class="bg-gray-300 text-gray-700 text-xs font-semibold rounded-full px-2 py-0.5 select-none">{{ $account->bank_name }}</span>
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

    <script>

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