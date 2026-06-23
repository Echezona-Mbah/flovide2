<!-- Sidebar -->
<aside aria-label="Sidebar" id="sidebar"
  class="fixed inset-y-0 left-0 z-30 w-72 bg-gradient-to-b from-sky-100 via-sky-50 to-white flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0 border-r border-sky-100">


  <!-- Brand -->
  <div class="flex items-center justify-between py-7 px-6 border-b border-sky-100">
    <img alt="Flovide logo black text with circular orbit design"
      class="w-[120px] h-[40px] object-contain"
      height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
    <button aria-label="Close sidebar" id="closeSidebarBtn" class="text-slate-700 focus:outline-none md:hidden">
      <i class="fas fa-times text-2xl"></i>
    </button>
  </div>

  <!-- Nav -->
  <nav class="flex-1 overflow-y-auto px-5 py-6 space-y-2 text-sm text-slate-600">
    

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('dashboard') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-tachometer-alt text-base"></i>
      {{ __('Dashboard') }}
    </a>

    <!-- Transaction History -->
    <a href="{{ route('transactionHistory') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('transactionHistory') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-history text-base"></i>
      {{ __('Transaction History') }}
    </a>

    <!-- Beneficiaries -->
    <a href="{{ route('beneficias') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('beneficias.*') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-file-invoice text-base"></i>
      {{ __('Beneficiaries') }}
    </a>

    <!-- Refunds -->
    {{-- <a href="{{ route('refunds.index') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('refunds.*') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-undo text-base"></i>
      {{ __('Refunds') }}
    </a> --}}

    <!-- Compliance -->
    <a href="{{ route('compliance') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('compliance') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-balance-scale text-base"></i>
      {{ __('Compliance') }}
    </a>

    <!-- Referral -->
    <a href="{{ route('referral') }}"
      class="flex items-center gap-3 py-2.5 px-3 rounded-full
        {{ request()->routeIs('referral') ? 'bg-white shadow-sm font-semibold text-slate-900' : 'hover:bg-white/80' }}">
      <i class="fas fa-gift text-base"></i>
      {{ __('Referral') }}
    </a>

    <!-- Settings Dropdown -->
    <div class="space-y-1">
      <button id="settingsDropdownBtn"
        class="flex items-center justify-between w-full py-2.5 px-3 hover:bg-white/80 rounded-full cursor-pointer focus:outline-none">
        <div class="flex items-center gap-3">
          <i class="fas fa-cog text-base"></i>
          Settings
        </div>
        <i id="settingsChevron" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
      </button>

      <div id="settingsDropdownMenu" class="hidden flex-col pl-8 space-y-1">
        <a href="{{ route('business.webhooks') }}" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white/80">
          <i class="fas fa-code-branch text-base"></i>
          {{ __('Webhooks') }}
        </a>
        <a href="{{ route('organization') }}" class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white/80">
          <i class="fas fa-building text-base"></i>
          {{ __('Organization') }}
        </a>
      </div>
    </div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit"
        class="flex items-center gap-3 py-2.5 px-4 mt-6 bg-red-500 text-white rounded-full w-full hover:bg-red-600 transition"
        aria-label="Logout">
        <i class="fas fa-sign-out-alt text-base"></i>
        Logout
      </button>
    </form>
  </nav>

  <!-- Language Switcher -->
  <div class="px-5 pb-8">
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
      <button onclick="toggleLang_sidebar()"
        class="flex items-center space-x-2 border border-blue-300 rounded-full px-3 py-1 text-slate-700 bg-white w-full justify-center">
        <span>{{ strtoupper($currentLocale) }}</span>
        <img class="w-6 h-6 rounded-full object-cover" src="{{ asset('../asserts/homepage/' . $currentFlag) }}" alt="Flag" />
        <i class="fas fa-chevron-down text-xs"></i>
      </button>

      <ul id="langMenu_sidebar"
        class="absolute hidden bg-white shadow-md rounded-lg p-2 mt-2 w-full bottom-full mb-2 z-50 border border-slate-100">
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
    document.addEventListener('click', function(e) {
      const menu = document.getElementById('langMenu_sidebar');
      const button = menu.previousElementSibling;
      if (menu && button && !button.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
      }
    });
  </script>
</aside>
