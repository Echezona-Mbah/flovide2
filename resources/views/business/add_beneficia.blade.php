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
            <a aria-current="page"
                class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="#">
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
        <section class=" relative w-full">
           <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
            <section class="flex flex-col md:flex-row gap-10 mx-auto max-w-full min-h-screen">
                <!-- Left side: Table and search -->
                <section class="flex-1 overflow-auto ">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 md:gap-10">
                        <div class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full sm:w-[400px]">
                            <i class="fas fa-search text-gray-400 mr-3"></i>
                            <input type="search" placeholder="Search beneficiaries"
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
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($beneficiaries as $beneficia)

                            <tr class="bg-blue-50 font-semibold text-gray-900">
                                <td class="py-3 pl-4">{{ $beneficia->account_name }}</td>
                                <td class="py-3 px-4">{{ $beneficia->bank }}</td>
                                <td class="py-3 px-4">{{ $beneficia->account_number }}</td>
                                <td class="py-3 pr-4 pl-6">{{ $beneficia->country->name }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
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
                        
                        <!-- Country -->
                        <label for="country" class="font-normal">In what country is your bank located?</label>
                        <select id="country" name="country_id"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option selected disabled>Select country</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" data-code="{{ $c->code }}" {{ old('country_id', $beneficia->country_id) == $c->id ? 'selected' : '' }}>
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
                                    {{ old('bank', $beneficia->bank) == $b->name ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>

                
                        <label for="account-number" class="font-normal">Bank account number</label>
                        <input type="text" id="account-number" name="account_number" placeholder="12345678"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
            
                        <label for="account-name" class="font-normal">Bank account name</label>
                        <input type="text" id="account-name" name="account_name" placeholder="John Doe"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
            
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