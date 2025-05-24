<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Beneficiaries
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Outfit", sans-serif;
        }
    </style>
</head>

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
    </header>
    <!-- Sidebar -->
    <aside aria-label="Sidebar" id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-[#E9E9E9] flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0">
        <div class="flex items-center justify-between py-8 px-6 flex-shrink-0">
            <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
                height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
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

            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-user-friends text-base">
                </i>
                Payout accounts
            </a>

            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-layer-group text-base">
                </i>
                Subaccounts
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-history text-base">
                </i>
                Transaction History
            </a>

            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-users text-base">
                </i>
                Beneficiaries
            </a>

            <a aria-current="page"
                class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="#">
                <i class="fas fa-wallet text-base">
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
                Customers
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
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <section class="flex flex-col md:flex-row gap-10 mx-auto max-w-full min-h-screen">
                    <!-- Left side: Table and search -->
                    <div class="flex-1 p-6 md:p-10 max-w-full md:max-w-4xl">
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 mb-6">
                            <div class="flex-1 max-w-md w-full">
                                <label for="search" class="sr-only">Search customers</label>
                                <div class="relative text-gray-400 focus-within:text-gray-600">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input id="search" type="search" placeholder="Search customers"
                                        class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300" />
                                </div>
                            </div>
                            <button type="button"
                                class="mt-4 md:mt-0 inline-flex items-center space-x-2 rounded-lg border border-blue-300 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                <i class="fas fa-file-export text-sm"></i>
                                <span>Export CSV</span>
                            </button>
                            <a href="{{ route('add_customer.create') }}">
                                <button type="button"
                                    class="mt-4 md:mt-0 ml-3 inline-flex items-center space-x-2 rounded-lg bg-blue-100 px-4 py-2 text-sm text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <i class="fas fa-plus text-sm"></i>
                                    <span>Add Customer</span>
                                </button>
                            </a>
                        </div>
                    <section class="overflow-x-auto w-full">
                        <table class="w-full text-left text-[10px] md:text-sm border-collapse text-gray-600  border-spacing-y-1">
                            <thead class="text-gray-500 font-semibold">
                                <tr>
                                    <th class="pl-4 pb-2">Customer name</th>
                                    <th class="pb-2">Email</th>
                                    <th class="pb-2">Phone</th>
                                    <th class="pr-4 pb-2 text-right">Date Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="bg-blue-50 font-semibold rounded-lg">
                                    <td class="pl-4 py-3">John Awesome</td>
                                    <td class="py-3 max-w-[120px] truncate">janedo@gmail.com</td>
                                    <td class="py-3">1234567890</td>
                                    <td class="pr-4 py-3 text-right">Aug 6, 2025</td>
                                </tr>
                                @foreach ($customers as $customer)

                                <tr class="bg-blue-50 font-semibold text-gray-900">
                                    <td class="pl-4 py-3">{{ $customer->customer_name }}</td>
                                    <td class="py-3 max-w-[120px] truncate">{{ $customer->email }}</td>
                                    <td class="py-3">{{ $customer->phone }}</td>
                                    <td class="pr-4 py-3 text-right">{{ \Carbon\Carbon::parse($customer->created_at)->format('M d, Y') }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </section>
                    </div>

                    <!-- Right side: Form -->
                    <div class="w-full md:w-96 border-l border-gray-200 p-6 md:p-10">
                        <h2 class="font-semibold text-lg mb-6">Add a Customer</h2>
                        <h2 class="text-2xl font-normal mb-6">Jane Awesome</h2>
                        <form method="POST" action="{{ route('add_customer.store') }}" class="flex flex-col gap-5 text-gray-600 text-sm">

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


                            <div class="flex flex-col gap-1">
                                <label for="customerName" class="font-semibold text-gray-800 text-sm">Customer name (or
                                    nickname)</label>
                                <input id="customerName" name="customer_name" type="text" placeholder="John Doe"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="email" class="font-semibold text-gray-800 text-sm">Email</label>
                                <input id="email" type="email" name="email" placeholder="janedo@gmail.com"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="phone" class="font-semibold text-gray-800 text-sm">Phone</label>
                                <input id="phone" type="tel" name="phone" placeholder="1234567890"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                            </div>
                            <!-- Country -->
                            <label for="country" class="font-normal">In what country is your bank located?</label>
                            <select id="country" name="country_id"
                                class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                <option selected disabled>Select country</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->id }}" data-code="{{ $c->code }}" {{ old('country_id', $customer->country_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                    
                        <!-- Bank -->
                            <label for="bank" class="font-normal">Bank</label>
                            <select id="bank" name="bank"
                                class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                <option selected disabled>Select your bank</option>
                                @foreach($banks as $b)
                                    <option value="{{ $b->name }}"
                                        data-country="{{ $b->country_code }}"
                                        {{ old('bank', $customer->bank) == $b->name ? 'selected' : '' }}>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="flex flex-col gap-1">
                                <label for="accountNumber" class="font-semibold text-gray-800 text-sm">Bank account number</label>
                                <input id="accountNumber" name="account_number" type="text" placeholder="12345678"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label for="accountName" class="font-semibold text-gray-800 text-sm">Bank account name</label>
                                <input id="accountName" name="account_name" type="text" placeholder="John Doe"
                                    class="rounded-lg border border-gray-300 px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                            </div>
                            <div class="flex gap-4 mt-4">
                                <button type="submit"
                                    class="rounded-lg bg-blue-200 px-6 py-2 text-blue-800 font-semibold hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Add Customer
                                </button>
                                <button type="button"
                                    class="rounded-lg border border-gray-300 px-6 py-2 text-gray-900 font-normal hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Cancel
                                </button>
                            </div>     </form>
                    </div>
                </section>
            </section>
    </main>

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
</body>

</html>