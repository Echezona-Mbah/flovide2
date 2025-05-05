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
                Subaccounts
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
        <section class=" relative  w-full">
            <section
                class="flex w-full flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
                <!-- Left form -->
                <section class="max-w-7xl mx-auto flex w-full flex-col md:flex-row gap-14">
                    <!-- Left form section -->
                    <section class="flex-1 max-w-lg">
                        <h1 class="font-semibold text-2xl text-gray-900 mb-2">Add a Subaccount</h1>
                        <p class="text-gray-500 mb-8">
                            You can split out-going funds between subaccounts and your payout account.
                        </p>
                
                        <form class="space-y-6">
                            <div>
                                <label for="bank" class="block text-gray-600 text-sm mb-1 font-semibold">Bank</label>
                                <select id="bank" name="bank"
                                    class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <option selected disabled>Select your bank</option>
                                    <option>GTB</option>
                                    <option>Zenith</option>
                                    <option>UBA</option>
                                </select>
                            </div>
                
                            <div>
                                <label for="country" class="block text-gray-600 text-sm mb-1 font-semibold">
                                    In what country is your bank located?
                                </label>
                                <select id="country" name="country"
                                    class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <option selected disabled>Select country</option>
                                    <option>Nigeria</option>
                                    <option>Ghana</option>
                                    <option>Kenya</option>
                                </select>
                            </div>
                
                            <div>
                                <label for="account-number" class="block text-gray-600 text-sm mb-1 font-semibold">
                                    Bank account number
                                </label>
                                <input type="text" id="account-number" name="account-number" placeholder="12345678"
                                    class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                            </div>
                
                            <div>
                                <label for="account-name" class="block text-gray-600 text-sm mb-1 font-semibold">
                                    Bank account name
                                </label>
                                <input type="text" id="account-name" name="account-name" placeholder="John Doe"
                                    class="w-full border border-gray-300 rounded-md py-2 px-3 text-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                            </div>
                
                            <button type="submit"
                                class="bg-blue-200 text-blue-900 font-semibold rounded-full px-6 py-2 hover:bg-blue-300 transition">
                                Add Account
                            </button>
                        </form>
                    </section>
                
                    <!-- Right subaccounts section -->
                    <section class="flex-1 max-w-lg bg-gray-100 rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="font-semibold text-gray-900">Subaccounts</h2>
                            <button type="button"
                                class="flex items-center gap-2 text-red-600 bg-white rounded-full px-4 py-1 text-sm font-semibold hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt"></i> Delete All
                            </button>
                        </div>
                
                        <ul class="space-y-3">
                            <li
                                class="flex justify-between items-center bg-white rounded-xl px-5 py-3 text-gray-900 font-normal text-base">
                                <div class="flex items-center gap-3">
                                    <span>2100048486</span>
                                    <span
                                        class="bg-gray-300 text-gray-700 text-xs font-semibold rounded-full px-2 py-0.5 select-none">GTB</span>
                                </div>
                                <div class="flex items-center gap-4 text-gray-400">
                                    <button aria-label="Edit 2100048486 GTB" class="hover:text-gray-600">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button aria-label="Delete 2100048486 GTB" class="hover:text-gray-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </li>
                
                            <li
                                class="flex justify-between items-center bg-white rounded-xl px-5 py-3 text-gray-900 font-normal text-base">
                                <div class="flex items-center gap-3">
                                    <span>2100048486</span>
                                    <span
                                        class="bg-gray-300 text-gray-700 text-xs font-semibold rounded-full px-2 py-0.5 select-none">Zenith</span>
                                </div>
                                <div class="flex items-center gap-4 text-gray-400">
                                    <button aria-label="Edit 2100048486 Zenith" class="hover:text-gray-600">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button aria-label="Delete 2100048486 Zenith" class="hover:text-gray-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </li>
                
                            <li
                                class="flex justify-between items-center bg-white rounded-xl px-5 py-3 text-gray-900 font-normal text-base">
                                <div class="flex items-center gap-3">
                                    <span>12345678901</span>
                                    <span
                                        class="bg-gray-300 text-gray-700 text-xs font-semibold rounded-full px-2 py-0.5 select-none">UBA</span>
                                </div>
                                <div class="flex items-center gap-4 text-gray-400">
                                    <button aria-label="Edit 12345678901 UBA" class="hover:text-gray-600">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button aria-label="Delete 12345678901 UBA" class="hover:text-gray-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </section>
                </section>
            </section>
    </main>
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