<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Subscriptions | Flovide Admin Dashboard
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
            <a aria-current="page" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-wallet text-base">
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
            <a class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="#">
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
                Refunds </h1>
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
                <div class="max-w-[100vw] mx-auto">
                    <!-- Top Stats -->

                    <!-- Main Content -->
                    <section class="bg-white text-gray-900">
                        <div class="flex flex-col lg:flex-row min-h-screen md:max-w-[76vw] md:mx-auto">
                            <!-- Left side -->
                            <section class="flex-1 p-6 lg:p-10 lg:w-[750px]">
                                <form class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6">
                                    <label for="search" class="sr-only">Search requests</label>
                                    <input id="search" type="search" placeholder="Search requests"
                                        class="flex-1 border border-gray-300 rounded-lg py-2 px-4 text-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <select aria-label="Filter requests"
                                        class="border border-gray-300 rounded-lg py-2 px-4 text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option>All requests</option>
                                        <option>Approved</option>
                                        <option>Rejected</option>
                                        <option>Pending</option>
                                    </select>
                                </form>
                                <section class="overflow-x-auto w-full">

                                    <table class="w-full border-collapse text-sm ">
                                        <thead>
                                            <tr class="text-gray-600 font-semibold text-left border-b border-gray-200">
                                                <th class="pb-3 pl-2 pr-6">Full Name</th>
                                                <th class="pb-3 pr-6">Amount</th>
                                                <th class="pb-3 pr-6">Approve/Reject</th>
                                                <th class="pb-3 pr-2">Status</th>
                                                <th class="sr-only">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-blue-50">
                                                <td class="font-semibold pl-2 pr-6 py-3">Jane Do</td>
                                                <td class="pr-6 py-3 font-semibold">100 GBP</td>
                                                <td class="pr-6 py-3">
                                                    <select
                                                        class="border border-gray-300 rounded-lg py-1.5 px-3 text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        aria-label="Select approval status">
                                                        <option>Select</option>
                                                        <option>Approved</option>
                                                        <option>Rejected</option>
                                                    </select>
                                                </td>
                                                <td class="pr-2 py-3 text-gray-600">Pending</td>
                                                <td
                                                    class="pr-2 py-3 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-100">
                                                <td class="pl-2 pr-6 py-4 font-normal">Arlene McCoy</td>
                                                <td class="pr-6 py-4 font-normal">10,000 NGN</td>
                                                <td class="pr-6 py-4">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 border border-green-500 text-green-600 rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                        Approved <i class="fas fa-chevron-down text-xs"></i>
                                                    </button>
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-blue-700 font-semibold cursor-pointer select-none">
                                                    Processing
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-100">
                                                <td class="pl-2 pr-6 py-4 font-normal">Arlene McCoy</td>
                                                <td class="pr-6 py-4 font-normal">1000 GBP</td>
                                                <td class="pr-6 py-4">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 border border-red-500 text-red-600 rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                        Rejected <i class="fas fa-chevron-down text-xs"></i>
                                                    </button>
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-green-700 font-semibold cursor-pointer select-none">
                                                    Successful
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-100">
                                                <td class="pl-2 pr-6 py-4 font-normal">Arlene McCoy</td>
                                                <td class="pr-6 py-4 font-normal">20,000 NGN</td>
                                                <td class="pr-6 py-4">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 border border-green-500 text-green-600 rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                        Approved <i class="fas fa-chevron-down text-xs"></i>
                                                    </button>
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-red-600 font-semibold cursor-pointer select-none">
                                                    Failed
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-100">
                                                <td class="pl-2 pr-6 py-4 font-normal">Arlene McCoy</td>
                                                <td class="pr-6 py-4 font-normal">10,000 NGN</td>
                                                <td class="pr-6 py-4">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 border border-green-500 text-green-600 rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                        Approved <i class="fas fa-chevron-down text-xs"></i>
                                                    </button>
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-green-700 font-semibold cursor-pointer select-none">
                                                    Successful
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>

                                            <tr class="border-b border-gray-100">
                                                <td class="pl-2 pr-6 py-4 font-normal">Arlene McCoy</td>
                                                <td class="pr-6 py-4 font-normal">100 GBP</td>
                                                <td class="pr-6 py-4">
                                                    <button type="button"
                                                        class="inline-flex items-center gap-1 border border-red-500 text-red-600 rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                        Rejected <i class="fas fa-chevron-down text-xs"></i>
                                                    </button>
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-green-700 font-semibold cursor-pointer select-none">
                                                    Successful
                                                </td>
                                                <td
                                                    class="pr-2 py-4 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                    View details
                                                    <i class="fas fa-external-link-alt text-xs"></i>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                            </section>

                            <!-- Right side -->
                            <section class="w-full lg:w-[24vw] border-l bg-white p-6 lg:p-8 bg-gray-50">
                                <div
                                    class="flex  justify-between items-center h-10 mb-6 bg-gray-200 w-full text-[12px] rounded-md p-1 w-[20vw] font-semibold">
                                    <!-- <button class=" bg-white rounded-lg px-5 py-2 font-semibold shadow-md
                                    text-gray-900" aria-current="page">
                                    Transaction Details
                                    </button>
                                    <button class="rounded-lg px-5 py-2 font-semibold text-gray-700 hover:bg-gray-200">
                                        Refund Tracker
                                    </button> -->
                                    <div class="bg-white px-2 py-2 rounded-md">Transaction Details</div>
                                    <div class="px-2 py-2 rounded-md">Refund Tracker</div>

                                </div>

                                <h2 class="text-2xl font-normal mb-6">100 GBP</h2>

                                <div class="mb-6">
                                    <p class="text-gray-600 mb-1">Transaction reference no.</p>
                                    <div class="flex items-center gap-2">
                                        <a href="#"
                                            class="text-blue-800 font-semibold text-sm break-all">12345678901234567890</a>
                                        <button aria-label="Copy transaction reference number"
                                            class="text-green-600 hover:text-green-700">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <p class="text-gray-600 mb-1">Type</p>
                                    <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                        <i class="fas fa-arrow-down-left text-green-600"></i>
                                        <span>Deposit</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <p class="text-gray-600 mb-1">Status</p>
                                    <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                        <i class="fas fa-check-double text-green-700"></i>
                                        <span>Success</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <p class="text-gray-600 mb-1">Time &amp; date</p>
                                    <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                        <span>12:15:14</span>
                                        <span class="border-l border-gray-400 pl-2">Aug 6, 2025</span>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <p class="text-gray-600 mb-1">Sender</p>
                                    <p class="font-semibold text-gray-900">Jane Do</p>
                                </div>

                                <div>
                                    <p class="text-gray-600 mb-1">Recipient</p>
                                    <a href="#" class="text-blue-800 font-semibold">Self</a>
                                </div>
                            </section>
                        </div>
                    </section>

                </div>
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