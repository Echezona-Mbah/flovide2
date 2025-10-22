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

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('dashboard') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-tachometer-alt text-base"></i>
            Dashboard
        </a>

        <!-- Payout accounts -->
        <a href="{{ route('payouts') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('payouts') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-user-friends text-base"></i>
            Payout accounts
        </a>

        <!-- Subaccounts -->
        <a href="{{ route('subaccount') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('subaccount') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-layer-group text-base"></i>
            Subaccounts
        </a>

        <!-- Transaction History -->
        <a href="{{ route('transactionHistory') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('transactionHistory') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-history text-base"></i>
            Transaction History
        </a>

        <!-- Beneficiaries -->
        <a href="{{ route('beneficias') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('beneficias') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-users text-base"></i>
            Beneficiaries
        </a>

        <!-- Customers -->
        <a href="{{ route('customer') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('customer') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-wallet text-base"></i>
            Customers
        </a>

        <!-- Invoices -->
        <a href="{{ route('invoices.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('invoices.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice text-base"></i>
            Invoices
        </a>

        <!-- Payment page -->
        <a href="{{ route('payment.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('payment.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            Payment page
        </a>

        <!-- Subscriptions -->
        <a href="{{ route('subscriptions') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('subscriptions') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-sync-alt text-base"></i>
            Subscriptions
        </a>

        <!-- Bills payment -->
        <a href="{{ route('bill_payment') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('bill_payment') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            Bills payment
        </a>

        <!-- Remita -->
        <a href="{{ route('remita.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('remita.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-exchange-alt text-base"></i>
            Remita
        </a>

        <!-- Donation page -->
        <a href="{{ route('donation.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('donation.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-file-invoice-dollar text-base"></i>
            Donation
        </a>

        <!-- Refunds -->
        <a href="{{ route('refunds.index') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('refunds.*') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-undo text-base"></i>
            Refunds
        </a>

        <!-- Chargebacks -->
        <a href="{{ route('chargeback') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('chargeback') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-ban text-base"></i>
            Chargebacks
        </a>

        <!-- Virtual Cards -->
        <a href="{{ route('allvirtualcard') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('allvirtualcard') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-credit-card text-base"></i>
            Virtual Cards
        </a>

        <!-- Compliance -->
        <a href="{{ route('compliance') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('compliance') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-balance-scale text-base"></i>
            Compliance
        </a>

        <!-- Webhooks -->
        <a href="#"
           class="flex items-center gap-3 py-2 px-3 
            hover:bg-white rounded-md">
            <i class="fas fa-code-branch text-base"></i>
            Webhooks
        </a>

        <!-- Your Organization -->
        <a href="{{ route('organization') }}"
           class="flex items-center gap-3 py-2 px-3 
            {{ request()->routeIs('organization') ? 'bg-white rounded-full font-semibold text-[#1E1E1E]' : 'hover:bg-white rounded-md' }}">
            <i class="fas fa-building text-base"></i>
            Your organization
        </a>

    </nav>
</aside>
