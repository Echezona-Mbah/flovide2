@include('business.head')
<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
        {{ __('Create A Balance') }}
      </h1>
      @include('business.header_notifical')
    </header>

    <section class="mx-auto max-w-6xl">
      <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 md:px-10 py-8 bg-[#215F9C] text-white border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em]">Balances</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-white">
                {{ __('Create A New Balance') }}
              </h2>
              <p class="mt-2 text-sm text-white max-w-2xl">
                {{ __('You can make withdrawals directly into any bank account of your choice.') }}
              </p>
            </div>
            <!-- <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
              Environment: <span class="font-semibold">Live/Test</span>
            </div> -->
          </div>
        </div>

        <section class="p-6 md:p-10 grid grid-cols-1 lg:grid-cols-2 gap-10">
          <!-- Left form -->
          <section class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h3 class="text-xl font-extrabold mb-1">
              {{ __('Create A New Balance') }}
            </h3>
            <p class="text-slate-500 mb-6 text-sm">
              {{ __('You can make withdrawals directly into any bank account of your choice.') }}
            </p>

            <form method="POST" action="{{ route('ohentpay.createBalance') }}" class="space-y-6" enctype="multipart/form-data">
              @csrf

              @if ($errors->any())
              <script>
                Swal.fire({
                  toast: true, position: 'top-end', icon: 'error',
                  title: @json($errors->first()), showConfirmButton: false,
                  timer: 4000, timerProgressBar: true
                });
              </script>
              @endif

              @if (session('success'))
              <script>
                Swal.fire({
                  toast: true, position: 'top-end', icon: 'success',
                  title: @json(session('success')), showConfirmButton: false,
                  timer: 4000, timerProgressBar: true
                });
              </script>
              @endif

              @php
                $mainCurrency = $currencies->first();
              @endphp

              <div class="relative">
                @if($mainCurrency)
                  <button id="currencyDropdownButton" type="button"
                    class="w-full flex items-center justify-between border border-gray-300 px-4 py-2 text-sm text-gray-900 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <div id="selectedCurrency" class="flex items-center gap-2">
                      <img src="https://flagcdn.com/w40/{{ strtolower($mainCurrency->country_code ?? 'us') }}.png"
                        alt="{{ $mainCurrency->name ?? 'Currency' }} flag"
                        class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                      <span>{{ $mainCurrency->name }} ({{ $mainCurrency->code }})</span>
                    </div>

                    <svg class="w-4 h-4 ml-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </button>

                  <div id="currencyDropdown"
                    class="hidden absolute z-10 w-full bg-white border border-gray-200 rounded-lg shadow mt-2 max-h-60 overflow-y-auto">
                    @foreach ($currencies as $currency)
                      <div class="currency-option flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-gray-100"
                        data-value="{{ strtoupper($currency->code) }}"
                        data-label="{{ $currency->name }} ({{ strtoupper($currency->code) }})"
                        data-flag="https://flagcdn.com/w40/{{ strtolower($currency->country_code ?? 'us') }}.png"
                        data-alt="{{ $currency->name }} flag">
                        <img src="https://flagcdn.com/w40/{{ strtolower($currency->country_code ?? 'us') }}.png"
                          alt="{{ $currency->name }} flag"
                          class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                        <span>{{ $currency->name }} ({{ strtoupper($currency->code) }})</span>
                      </div>
                    @endforeach
                  </div>

                  <input type="hidden" id="currency" name="currency" value="{{ strtoupper($mainCurrency->code) }}">
                @else
                  <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    You have already created balances for all available currencies.
                  </div>

                  <input type="hidden" id="currency" name="currency" value="">
                @endif
              </div>

              <div class="flex flex-col gap-1">
                <label class="text-slate-600" for="account-number">
                  {{ __('Name Your Account') }}
                </label>
                <span class="text-red-500 errornumber"></span>
                <input
                  class="border border-gray-300 rounded-lg py-2 px-3 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300"
                  id="account-number" name="name" placeholder="{{ __('Name') }}" type="text" />
              </div>

              <div class="flex flex-col gap-1">
                <div id="responseMessage"></div>
              </div>

              <button id="bankAccountForm"
                class="self-start bg-blue-600 text-white font-semibold text-sm rounded-full py-2.5 px-6 mt-2 hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                type="submit"
                @disabled($currencies->isEmpty())>
                {{ __('Add Account') }}
              </button>
            </form>
          </section>

          <!-- Right list -->
          <section class="bg-slate-50 rounded-2xl p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
              <h3 class="font-semibold text-base text-slate-900">
                {{ __('Bank Accounts') }}
              </h3>
            </div>

            <div class="bg-white rounded-xl p-2 md:p-4 flex flex-col gap-3 max-w-full">
              <p id="default-message" class="mt-4 text-green-600 font-medium hidden"></p>

              @foreach($balances as $account)
                <div class="flex items-center gap-4 bg-slate-50 rounded-lg py-3 px-4">
                  <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-emerald-50 flex-shrink-0">
                    <i class="fas fa-check text-emerald-600 text-lg" onclick='openBalanceModal(@json($account))'></i>
                  </div>

                  <div class="flex items-center gap-4 bg-white rounded-lg py-3 px-4 w-full">
                    <div class="flex flex-col text-sm text-slate-900 select-none gap-1 flex-1">
                      <div class="flex items-center gap-2">
                        <img 
                          src="https://flagcdn.com/w20/{{ strtolower($account->currency_meta['country']) }}.png" 
                          alt="Flag of {{ strtoupper($account->currency_meta['country']) }}"
                          width="20" height="15" class="rounded-sm"
                        />
                        <span>{{ $account['name'] ?? __('Unnamed Account') }}</span>
                      </div>
                      <span class="text-xs text-slate-500">{{ $account['currency'] ?? '' }}</span>
                    </div>
                    
                    <span class="text-lg font-bold text-slate-900 bg-slate-100 rounded-full py-1 px-3 whitespace-nowrap">
                      {{ $account->currency_meta['symbol'] }}{{ number_format($account['amount'] ?? 0, 2) }}
                    </span>

                    <button type="button"
                      class="ml-auto text-slate-500 hover:text-slate-900 flex-shrink-0"
                      onclick="openEditModal('{{ $account['id'] }}', '{{ $account['name'] }}')">
                      <i class="fas fa-pencil-alt"></i>
                    </button>
                  </div>
                </div>
              @endforeach
            </div>

            <!-- Balance Details Modal -->
            <div id="balanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
              <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative overflow-y-auto max-h-[90vh]">
                <button onclick="closeBalanceModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">
                  &times;
                </button>
                <h2 class="text-xl font-semibold mb-4">{{ __('Balance Details') }}</h2>
                <div id="balanceDetails" class="space-y-4"></div>
              </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
              <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">{{ __('Edit Balance') }}</h2>
                <form id="editBalanceForm" method="POST" action="{{ route('update.balance') }}">
                  @csrf
                  @if ($errors->any())
                  <script>
                    Swal.fire({
                      toast: true, position: 'top-end', icon: 'error',
                      title: @json($errors->first()), showConfirmButton: false,
                      timer: 4000, timerProgressBar: true
                    });
                  </script>
                  @endif
                  @if (session('success'))
                  <script>
                    Swal.fire({
                      toast: true, position: 'top-end', icon: 'success',
                      title: @json(session('success')), showConfirmButton: false,
                      timer: 4000, timerProgressBar: true
                    });
                  </script>
                  @endif

                  <input type="hidden" name="balance_id" id="editBalanceId">
                  <div class="mb-4">
                    <label for="editBalanceName" class="block text-sm font-medium">{{ __('New Name') }}</label>
                    <input type="text" name="name" id="editBalanceName" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
                  </div>
                  <div class="flex justify-end">
                    <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">{{ __('Update') }}</button>
                  </div>
                </form>
              </div>
            </div>

          </section>
        </section>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const dropdownButton = document.getElementById('currencyDropdownButton');
      const dropdown = document.getElementById('currencyDropdown');
      const options = dropdown.querySelectorAll('.currency-option');
      const selectedDisplay = document.getElementById('selectedCurrency');
      const hiddenInput = document.getElementById('currency');

      dropdownButton.addEventListener('click', () => {
        dropdown.classList.toggle('hidden');
      });

      options.forEach(option => {
        option.addEventListener('click', () => {
          const value = option.getAttribute('data-value');
          const label = option.getAttribute('data-label');
          const flag = option.getAttribute('data-flag');
          const alt = option.getAttribute('data-alt');

          selectedDisplay.innerHTML = `
            <img src="${flag}" alt="${alt}" class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
            <span>${label}</span>
          `;

          hiddenInput.value = value;
          dropdown.classList.add('hidden');
        });
      });

      document.addEventListener('click', (e) => {
        if (!dropdownButton.contains(e.target) && !dropdown.contains(e.target)) {
          dropdown.classList.add('hidden');
        }
      });
    });
  </script>

  <script>
    function openBalanceModal(account) {
      const modal = document.getElementById('balanceModal');
      const detailsDiv = document.getElementById('balanceDetails');
      detailsDiv.innerHTML = '';

      const html = `
        <div class="p-4 border rounded-lg shadow-sm bg-gray-50">
          <div class="flex justify-between items-center mb-2">
            <div class="flex items-center gap-2">
              <img src="https://flagcdn.com/24x18/${(account.country || 'us').toLowerCase()}.png"
                   alt="${account.country || 'Flag'}" class="w-5 h-auto rounded"/>
              <span class="font-medium">${account.name || 'Unnamed Account'}</span>
            </div>
            <span class="text-xs text-gray-500">${account.currency || ''}</span>
          </div>
          <p class="text-sm">Balance:
            <span class="font-bold">${account.symbol || ''}${parseFloat(account.balance || 0).toFixed(2)}</span>
          </p>
          ${account.addresses && account.addresses[0] && account.addresses[0].details ? `
            <div class="text-sm mt-2 text-gray-700">
              <p><strong>Bank:</strong> ${account.addresses[0].details.bank_name}</p>
              <p><strong>Account:</strong> ${account.addresses[0].details.account_number}</p>
              <p><strong>Name:</strong> ${account.addresses[0].details.account_name}</p>
            </div>
          ` : ''}
        </div>
      `;

      detailsDiv.innerHTML = html;
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeBalanceModal() {
      const modal = document.getElementById('balanceModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  </script>

  <script>
    function openEditModal(balanceId, balanceName) {
      document.getElementById('editBalanceId').value = balanceId;
      document.getElementById('editBalanceName').value = balanceName;
      const modal = document.getElementById('editModal');
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function closeEditModal() {
      const modal = document.getElementById('editModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }
  </script>
</body>
</html>
