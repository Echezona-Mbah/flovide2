<!-- Sidebar -->
<aside aria-label="Sidebar" id="sidebar"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-blue-100 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0">
    
    <div class="flex items-center justify-between py-8 px-6 flex-shrink-0">
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <button aria-label="Close sidebar" id="closeSidebarBtn" class="text-[#1E1E1E] focus:outline-none md:hidden">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-6 pb-8 space-y-3 text-sm font-normal text-[#4B4B4B]">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('dashboard') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-tachometer-alt text-base"></i>
            {{ __('Dashboard') }}
        </a>

        {{-- <!-- Payout accounts -->
        <a href="{{ route('payouts') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('payouts') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-user-friends text-base"></i>
            {{ __('Payout accounts') }}
        </a>

        <!-- Subaccounts -->
        <a href="{{ route('subaccount') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('subaccount') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-layer-group text-base"></i>
            {{ __('Subaccounts') }}
        </a> --}}

        <!-- Transaction History -->
        <a href="{{ route('transactionHistory') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('transactionHistory') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-history text-base"></i>
            {{ __('Transaction History') }}
        </a>

       <!-- Accounts Dropdown -->
        {{-- <div class="space-y-1">
            <button id="accountsDropdownBtn"
                    class="flex items-center justify-between w-full py-2 px-3 hover:bg-white rounded-md cursor-pointer focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users text-base"></i>
                    Accounts
                </div>
                <i id="accountsChevron" class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
            </button>

            <div id="accountsDropdownMenu" class="hidden flex-col pl-10 space-y-1">
                <a href="{{ route('beneficias') }}"
                class="flex items-center gap-3 py-2 px-3 hover:bg-white rounded-md">
                    <i class="fas fa-user-friends text-base"></i>
                    Beneficiaries
                </a>

                <a href="{{ route('customer') }}"
                class="flex items-center gap-3 py-2 px-3 hover:bg-white rounded-md">
                    <i class="fas fa-wallet text-base"></i>
                    Customers
                </a>
            </div>
        </div> --}}

         <a href="{{ route('beneficias') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('beneficias.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice text-base"></i>
            {{ __('Beneficiaries') }}
        </a>
        {{-- 
        <!-- Invoices -->
        <a href="{{ route('beneficias') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('beneficias.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice text-base"></i>
            {{ __('Beneficiaries') }}
        </a>

        <!-- Payment page -->
        <a href="{{ route('payment.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('payment.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            {{ __('Payment page') }}
        </a>

        <!-- Subscriptions -->
        <a href="{{ route('subscriptions') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('subscriptions') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-sync-alt text-base"></i>
            {{ __('Subscriptions') }}
        </a>

        <!-- Bills payment -->
        <a href="{{ route('bill_payment') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('bill_payment') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            {{ __('Bills payment') }}
        </a>

        <!-- Remita -->
        <a href="{{ route('remita.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('remita.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-exchange-alt text-base"></i>
            {{ __('Remita') }}
        </a>

        <!-- Donation page -->
        <a href="{{ route('donation.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('donation.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            {{ __('Donation') }}
        </a> --}}

        <!-- Refunds -->
        <a href="{{ route('refunds.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('refunds.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-undo text-base"></i>
            {{ __('Refunds') }}
        </a>

        <!-- Chargebacks -->
        {{-- <a href="{{ route('chargeback') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('chargeback') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-ban text-base"></i>
            {{ __('Chargebacks') }}
        </a> --}}

        <!-- Virtual Cards -->
        {{-- <a href="{{ route('allvirtualcard') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('allvirtualcard') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-credit-card text-base"></i>
            {{ __('Virtual Cards') }}
        </a> --}}

        <!-- Compliance -->
        <a href="{{ route('compliance') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('compliance') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-balance-scale text-base"></i>
            {{ __('Compliance') }}
        </a>

        <!-- Referral -->
        <a href="{{ route('referral') }}" class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('referral') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-gift text-base"></i>
            {{ __('Referral') }}
        </a>

        <!-- Settings Dropdown -->
        <div class="space-y-1">
            <!-- Settings Button -->
            <button id="settingsDropdownBtn" class="flex items-center justify-between w-full py-2 px-3 hover:bg-white rounded-md cursor-pointer focus:outline-none">
                <div class="flex items-center gap-3">
                    <i class="fas fa-cog text-base"></i>
                    Settings
                </div>
                <i id="settingsChevron" class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
            </button>

            <!-- Dropdown Items -->
            <div id="settingsDropdownMenu" class="hidden flex-col pl-10 space-y-1">
                <a href="{{ route('webhook') }}" class="flex items-center gap-3 py-2 px-3 hover:bg-white rounded-md">
                    <i class="fas fa-code-branch text-base"></i>
                    {{ __('Webhooks') }}
                </a>

                <a href="{{ route('organization') }}"  class="flex items-center gap-3 py-2 px-3 hover:bg-white rounded-md">
                    <i class="fas fa-building text-base"></i>
                    {{ __('Organization') }}
                </a>
            </div>
        </div>


        <!-- Logout Form Styled as Sidebar Nav -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"  class="flex items-center gap-3 py-2 px-3 mt-5 bg-red-500 text-white rounded-full w-full hover:bg-red-600 transition" aria-label="Logout">
                <i class="fas fa-sign-out-alt text-base"></i>
                Logout
            </button>
        </form>




    </nav>

    <!-- Language Switcher -->
    <div class="px-6 pb-8">
        @php
            $flags = [
                'en' => 'gb.svg',
                'es' => 'es.svg',
                'fr' => 'fr.svg',
                'lg' => 'ug.svg',
            ];

            $currentLocale = app()->getLocale();
            $currentFlag = $flags[$currentLocale] ?? 'gb.svg';
        @endphp
        <div class="relative">
            <button onclick="toggleLang_sidebar()" class="flex items-center space-x-2 border border-[#1D4ED8] rounded-full px-3 py-1 text-[#252525] bg-white w-full justify-center">
                <span>{{ strtoupper($currentLocale) }} </span>
                <img class="w-6 h-6 rounded-full object-cover" src="{{ asset('../asserts/homepage/' . $currentFlag) }}" alt="Flag" />
                <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <ul id="langMenu_sidebar" class="absolute hidden bg-white shadow-md rounded-lg p-2 mt-2 w-full bottom-full mb-2 z-50">
                <li><a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2"><img src="{{ asset('../asserts/homepage/gb.svg') }}" class="w-5 h-5 rounded-full"> English</a></li>
                <li><a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2"><img src="{{ asset('../asserts/homepage/es.svg') }}" class="w-5 h-5 rounded-full"> Spanish</a></li>
                <li><a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2"><img src="{{ asset('../asserts/homepage/fr.svg') }}" class="w-5 h-5 rounded-full"> French</a></li>
                <li><a href="{{ route('lang.switch', 'lg') }}" class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2"><img src="{{ asset('../asserts/homepage/ug.svg') }}" class="w-5 h-5 rounded-full"> Luganda</a></li>
            </ul>
        </div>
    </div>


<script>
function setupDropdown(buttonId, menuId, chevronId) {
    const btn = document.getElementById(buttonId);
    const menu = document.getElementById(menuId);
    const chevron = document.getElementById(chevronId);

    // ✅ Only run if elements exist
    if (btn && menu && chevron) {
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        });
    }
}

setupDropdown('accountsDropdownBtn', 'accountsDropdownMenu', 'accountsChevron');
setupDropdown('settingsDropdownBtn', 'settingsDropdownMenu', 'settingsChevron');
</script>

    <script>
        function toggleLang_sidebar() {
            const menu = document.getElementById('langMenu_sidebar');
            menu.classList.toggle('hidden');
        }

        // Close menu if clicked outside
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('langMenu_sidebar');
            const button = menu.previousElementSibling;

            if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
</aside>
