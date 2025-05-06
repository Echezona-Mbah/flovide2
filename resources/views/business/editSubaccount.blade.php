<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Payout Accounts
    </title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: "Outfit", sans-serif;
        }
    </style>
</head>

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
    </header>
    <!-- Sidebar -->
    <aside aria-label="Sidebar" id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-[#E9E9E9] flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0">
        <div class="flex items-center justify-between py-8 px-6 flex-shrink-0">
            <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
                height="40" src="../asserts/dashboard/admin-logo.svg" width="120" />
            <button aria-label="Close sidebar" id="closeSidebarBtn" class="text-[#1E1E1E] focus:outline-none md:hidden">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto px-6 pb-8 space-y-3 text-sm font-normal text-[#4B4B4B]">
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-tachometer-alt text-base">
                </i>
                Dashboard
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="{{ route("business.payouts") }}">
                <i class="fas fa-layer-group text-base">
                </i>
                Payout accounts
            </a>
    
            <a aria-current="page"
                class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="{{ route("business.subaccount") }}">
                <i class="fas fa-wallet text-base">
                </i>
                Subaccounts
            </a>
    
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-history text-base">
                </i>
                Transaction History
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-user-friends text-base">
                </i>
                Beneficiaries
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-users text-base">
                </i>
                Customers
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice text-base">
                </i>
                Invoices
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-sync-alt text-base">
                </i>
                Subscriptions
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice-dollar text-base">
                </i>
                Bills payment
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-exchange-alt text-base">
                </i>
                Remita
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-undo text-base">
                </i>
                Refunds
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-ban text-base">
                </i>
                Chargebacks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-credit-card text-base">
                </i>
                Virtual Cards
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-balance-scale text-base">
                </i>
                Compliance
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-code-branch text-base">
                </i>
                Webhooks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-building text-base">
                </i>
                Your organization
            </a>
        </nav>
    </aside>
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Payout accounts
            </h1>
            <div class="flex items-center gap-4 flex-wrap">
                <button aria-label="Notifications"
                    class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-[#4B4B4B] text-lg">
                    </i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]">
                    </span>
                </button>
                <button aria-label="Select organization Nexus Global"
                    class="bg-white rounded-full flex items-center gap-2 py-2 px-4 text-sm font-normal text-[#1E1E1E] whitespace-nowrap">
                    <i class="fas fa-bullseye text-[#1E1E1E]">
                    </i>
                    Nexus Global
                    <i class="fas fa-chevron-right text-[#1E1E1E]">
                    </i>
                </button>
                <button aria-label="Logout" class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-[#1E1E1E] text-lg">
                    </i>
                </button>
            </div>
        </header>
        <section class=" relative w-full ">
            <section
                class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden w-full ">
                <!-- Left form -->
                <section class="flex flex-col lg:flex-row gap-14 justify-between bg-white rounded-tl-3xl md:p-6 p-2 ">
                    <section class="flex-1 bg-white rounded-xl md:p-6 p-2 max-w-full lg:max-w-lg ">
                        <h1 class="font-semibold text-2xl text-gray-900 mb-2">Edit Subaccount</h1>
                        <p class="text-gray-500 mb-8 max-w-md">
                            You can make withdrawals directly into any payout account of your choice.
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
                        <form method="POST" action="{{ route('business.updateSubAccount', $subaccount->id) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="bank" class="block text-gray-600 text-sm font-semibold mb-1">Bank</label>
                                <select id="bank" name="bank_name" class="w-full rounded-lg border border-gray-300 text-gray-900 text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="{{ old('bank_name', $subaccount->bank_name) }}">{{ old('bank_name', $subaccount->bank_name) }}</option>
                                    <option>GTBank</option>
                                    <option>UBA</option>
                                    <option>Zenith</option>
                                </select>
                            </div>
                        
                            <div>
                                <label for="country" class="block text-gray-600 text-sm font-semibold mb-1">In what country is your bank located?</label>
                                <select id="country" name="bank_country" class="w-full rounded-lg border border-gray-300 text-gray-900 text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="{{ old('bank_country', $subaccount->bank_country) }}">{{ old('bank_country', $subaccount->bank_country) }}</option>
                                    <option>Nigeria</option>
                                    <option>Ghana</option>
                                    <option>USA</option>
                                </select>
                            </div>
                        
                            <div>
                                <label for="account-number" class="block text-gray-600 text-sm font-semibold mb-1">Bank account number</label>
                                <input type="text" id="account-number" name="account_number" value="{{ old('account_number', Crypt::decryptString($subaccount->account_number)) }}" class="w-full rounded-lg border border-gray-300 text-gray-900 text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            </div>
                        
                            <div>
                                <label for="account-name" class="block text-gray-600 text-sm font-semibold mb-1">Bank account name</label>
                                <input type="text" id="account-name" name="account_name" value="{{ old('account_name', $subaccount->account_name) }}" class="w-full rounded-lg border border-gray-300 text-gray-900 text-sm px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            </div>
                        
                            <div class="flex gap-4">
                                <button type="submit" class="bg-blue-300 text-blue-800 font-semibold text-sm rounded-full px-6 py-2 hover:bg-blue-400 transition">
                                    Update Account
                                </button>
                                <a href="{{ route("business.subaccount") }}">
                                    <button type="button" class="border border-gray-300 rounded-full px-6 py-2 text-sm font-normal text-black hover:bg-gray-100 transition">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </form>
                    </section>
                    <!-- Right payout accounts list -->
                    <section class="flex-1 max-w-lg bg-gray-100 rounded-2xl p-6  flex flex-col gap-4">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-gray-900 text-base">Payout Accounts</h2>
                            <button type="button" class="delete-all-btn flex items-center gap-2 bg-white rounded-full px-4 py-2 text-red-600 text-sm font-semibold hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt"></i> Delete All
                            </button>
                        </div>
                    
                        <ul class="flex flex-col gap-3">
                            @foreach($allUserSubAccounts as $account)
                                @if($account->default)
                                    <!-- Account 1 -->
                                    <li class="flex items-center gap-4 border border-blue-500 rounded-xl bg-white px-4 py-3">
                                        <div class="flex justify-center items-center w-10 h-10 rounded-lg bg-green-100 text-green-700">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="font-semibold text-gray-900 text-base">{{ Crypt::decryptString($account->account_number) }}</span>
                                        <span class="bg-gray-200 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $account->bank_name }}</span>
                                        @if ($account->id !== $subaccount->id)
                                            <button type="button" class="ml-auto text-gray-600 hover:text-gray-900" aria-label="Edit account">
                                                <a href="{{ route('business.subaccountEdit', $account->id) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </button>
                                            <button type="button" class="text-gray-600 hover:text-gray-900 delete-icon"  data-id="{{ $account->id }}" aria-label="Delete account">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </li>
                                @else
                                    <!-- Account 2 -->
                                    <li class="flex items-center gap-4 bg-white rounded-xl px-4 py-3">
                                        <div data-id="{{ $account->id }}" class="set-default-btn flex justify-center items-center w-10 h-10 rounded-lg bg-gray-300 text-gray-600">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <span class="font-semibold text-gray-900 text-base">{{ Crypt::decryptString($account->account_number) }}</span>
                                        <span class="bg-gray-200 text-gray-700 text-xs rounded-full px-2 py-0.5">{{ $account->bank_name }}</span>
                                        @if ($account->id !== $subaccount->id)
                                            <button type="button" class="ml-auto text-gray-600 hover:text-gray-900" aria-label="Edit account">
                                                <a href="{{ route('business.subaccountEdit', $account->id) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </button>
                                            <button type="button" class="text-gray-600 hover:text-gray-900 delete-icon"  data-id="{{ $account->id }}" aria-label="Delete account">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endif
                                    </li>
                                @endif
                            @endforeach
                    
                        </ul>
                    </section>
                </section>
            </section>
    </main>


    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.set-default-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;  // Get the account ID
        
                // Send AJAX request to set the default
                fetch(`/business/bankSubAccounts/${id}/set-default`, {
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