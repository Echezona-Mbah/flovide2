<header class="relative">
  <section class="bg-[#0F243D] md:h-[750px] md:mx-10 md:rounded-2xl" id="mobileMenuButton">
    <!-- mobile menu -->
    <section class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
      <section class="flex justify-between items-center w-full">
        <div>
          <img src="{{asset('../asserts/mobileLogo.svg')}}" alt="" />
        </div>
        <div id="openSidebarBtn">
          <img src="{{asset('../asserts/menu-icon.svg')}}" alt="" />
        </div>
      </section>
    </section>

    <!-- Mobile Dropdown Menu -->
    <section class="md:hidden px-4 py-3 text-white w-full flex justify-center items-center">
      <!-- Dropdown Content -->
      <div id="mobileMenuContent"
        class="mt-2 absolute top-[13vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
        <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[20px] font-medium space-y-6 ">
          <a href="{{ route('personal') }}"
            class="block px-4 py-2 text-white hover:bg-[#3B82F6] border border-[#3380C4] p-4 bg-[#1E5186] rounded-xl">
            {{ __('Personal') }}
          </a>
          <a href="{{ route('business') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
            {{ __('Business') }}
          </a>
          <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
            {{ __('Developer') }}
          </a>
          <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
            {{ __('Blog') }}
          </a>
           <a href="{{ route('careers')}}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
              {{ __('Career') }}
            </a>
          <a href="{{ route('contactUs')}}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
            {{ __('Contact Us') }}
          </a>

          <button
            class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
            <a href="#" class=""> {{ __('Login') }} </a>
          </button>

          <button class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
            <a href="#" class=""> {{ __('Get Started') }} </a>
          </button>
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
            <button onclick="toggleLang_mobile()" class="flex items-center space-x-2 border border-[#1D4ED8] rounded-full px-3 py-1 text-[#252525]">
              <span>{{ strtoupper(app()->getLocale()) }} </span>
              <img class="w-6 h-6 rounded-full object-cover" src="{{asset('../asserts/homepage/' . $currentFlag) }}" alt="Flag" />
              <i class="fas fa-chevron-down text-xs"></i>
            </button>

            <ul id="langMenu_mobile" class="absolute hidden bg-white shadow-md rounded-lg p-2 mt-2 right-0">
              <li><a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 hover:bg-gray-100">English</a></li>
              <li><a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 hover:bg-gray-100">Spanish</a></li>
              <li><a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 hover:bg-gray-100">French</a></li>
              <li><a href="{{ route('lang.switch', 'lg') }}" class="block px-4 py-2 hover:bg-gray-100">Luganda</a></li>
            </ul>
          </div>
          
        </ul>
      </div>
    </section>

    <div class="md:max-w-7xl w-full flex flex-col lg:flex-row justify-between pt-16 pb-20 md:pb-0 px-4 md:px-10 gap-8">
      <div class="md:max-w-xl text-white">
        <h1 class="font-semibold md:text-[3.5rem] text-4xl w-[87vw] md:full flex flex-wrap text-center md:text-left leading-[1.1] mb-6">
          {{ __('Do business like a local in 190+ countries') }}
        </h1>
        <p class="md:text-[0.875rem] text-xl text-center md:text-left font-normal mb-8 max-w-md">
          {{ __('Create business accounts and move your money around for less. 190+ countries, 40+ currencies.') }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 items-center justify-center w-full gap-y-4 md:w-[60%] md:space-x-14 md:items-center md:justify-normal px-10 md:px-0">

          <a href="{{ route('login') }}">
            <button
              class="bg-[#2D6BCF] text-white text-lg md:w-[15vw] font-medium rounded-full px-6 py-2.5 hover:bg-[#1f4e9e] transition">
              {{ __('Sign In') }}
            </button>
          </a>

          <a href="{{ route('register.saveStepData') }}">
            <button
              class="border md:w-[18vw] border-white border-opacity-40 text-white text-lg font-medium rounded-full px-6 md:px-4 py-2.5 hover:bg-white hover:bg-opacity-10 transition">
              {{ __('Open a Free Account') }}
            </button>
          </a>

        </div>
        <div>
          <div class="bg-white border border-gray-200 mt-5 rounded-xl p-5">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
              <h3 class="font-semibold text-gray-800">Exchange Rate Calculator</h3>
            </div>

            <!-- From -->
            <div class="bg-gray-50 rounded-xl p-4 mb-3">
              <div class="flex justify-between items-center">
                <input id="fromAmount" type="number" value="100" class="bg-transparent text-2xl text-gray-700 font-bold outline-none w-1/2" />
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector" data-type="from">
                  <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 rounded-sm flag" />
                  <span class="font-medium code text-gray-700">GBP</span>
                  <i class="fas fa-chevron-down text-xs"></i>

                  <!-- Dropdown -->
                  <div class="currency-dropdown text-gray-700 hidden absolute bg-white border rounded shadow mt-1 z-50 max-h-48 overflow-y-auto">
                    
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="USD" data-flag="https://flagcdn.com/w20/us.png">
                      <img src="https://flagcdn.com/w20/us.png" class="w-5 h-4 rounded-sm"/> USD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="EUR" data-flag="https://flagcdn.com/w20/eu.png">
                      <img src="https://flagcdn.com/w20/eu.png" class="w-5 h-4 rounded-sm"/> EUR
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="NGN" data-flag="https://flagcdn.com/w20/ng.png">
                      <img src="https://flagcdn.com/w20/ng.png" class="w-5 h-4 rounded-sm"/> NGN
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="CAD" data-flag="https://flagcdn.com/w20/ca.png">
                      <img src="https://flagcdn.com/w20/ca.png" class="w-5 h-4 rounded-sm"/> CAD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="AUD" data-flag="https://flagcdn.com/w20/au.png">
                      <img src="https://flagcdn.com/w20/au.png" class="w-5 h-4 rounded-sm"/> AUD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="JPY" data-flag="https://flagcdn.com/w20/jp.png">
                      <img src="https://flagcdn.com/w20/jp.png" class="w-5 h-4 rounded-sm"/> JPY
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="GBP" data-flag="https://flagcdn.com/w20/gb.png">
                      <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 rounded-sm"/> GBP
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <!-- Swap button -->
            <div class="flex justify-center my-2">
              <button class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white shadow hover:bg-blue-700 transition swap-btn">
                <i class="fas fa-exchange-alt text-sm"></i>
              </button>
            </div>

            <!-- To -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
              <div class="flex justify-between items-center">
                <input id="toAmount" type="number" value="0" class="bg-transparent text-2xl text-gray-700 font-bold outline-none w-1/2" readonly/>
                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector " data-type="to">
                  <img src="https://flagcdn.com/w20/ng.png" class="w-5 h-4 rounded-sm flag" />
                  <span class="font-medium code text-gray-700">NGN</span>
                  <i class="fas fa-chevron-down text-xs"></i>

                  <!-- Dropdown -->
                  <div class="currency-dropdown text-gray-700 hidden absolute bg-white border rounded shadow mt-1 z-50 max-h-48 overflow-y-auto">
                    
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="USD" data-flag="https://flagcdn.com/w20/us.png">
                      <img src="https://flagcdn.com/w20/us.png" class="w-5 h-4 rounded-sm"/> USD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="EUR" data-flag="https://flagcdn.com/w20/eu.png">
                      <img src="https://flagcdn.com/w20/eu.png" class="w-5 h-4 rounded-sm"/> EUR
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="NGN" data-flag="https://flagcdn.com/w20/ng.png">
                      <img src="https://flagcdn.com/w20/ng.png" class="w-5 h-4 rounded-sm"/> NGN
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="CAD" data-flag="https://flagcdn.com/w20/ca.png">
                      <img src="https://flagcdn.com/w20/ca.png" class="w-5 h-4 rounded-sm"/> CAD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="AUD" data-flag="https://flagcdn.com/w20/au.png">
                      <img src="https://flagcdn.com/w20/au.png" class="w-5 h-4 rounded-sm"/> AUD
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="JPY" data-flag="https://flagcdn.com/w20/jp.png">
                      <img src="https://flagcdn.com/w20/jp.png" class="w-5 h-4 rounded-sm"/> JPY
                    </div>
                    <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" data-code="GBP" data-flag="https://flagcdn.com/w20/gb.png">
                      <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 rounded-sm"/> GBP
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="flex flex-col gap-4 max-w-md w-full justify-center items-center">
        <div>
          <img src="{{asset('../asserts/homepage/header_img1.png')}}" alt="" width="350px" height="100px" />
        </div>

        <div>
          <img src="{{asset('../asserts/homepage/header_img2.png')}}" alt="" width="350px" height="100px" />
        </div>
      </div>
    </div>
  </section>

  <section class="hidden md:flex items-center justify-center p-8 min-h-screen relative top-[-30vh] right-0 left-0 mx-auto">
    <img src="{{asset('../asserts/homepage/Dashboard.png')}}" alt="" class="w-[70vw] h-auto" />
  </section>
</header>

<script>

  document.addEventListener('DOMContentLoaded', () => {
      const swapBtn = document.querySelector('.swap-btn');
      const selectors = document.querySelectorAll('.currency-selector');

      // Toggle dropdown
      selectors.forEach(sel => {
          sel.addEventListener('click', e => {
              e.stopPropagation();
              const dropdown = sel.querySelector('.currency-dropdown');
              dropdown.classList.toggle('hidden');
          });

          // Select currency from dropdown
          sel.querySelectorAll('.currency-item').forEach(item => {
              item.addEventListener('click', e => {
                  const code = item.dataset.code;
                  const flag = item.dataset.flag;

                  sel.querySelector('.code').textContent = code;
                  sel.querySelector('.flag').src = flag;

                  sel.querySelector('.currency-dropdown').classList.add('hidden');
              });
          });
      });

      // Swap currencies and flags
      swapBtn.addEventListener('click', () => {
          const from = document.querySelector('.currency-selector[data-type="from"]');
          const to = document.querySelector('.currency-selector[data-type="to"]');

          // Swap code
          const tempCode = from.querySelector('.code').textContent;
          from.querySelector('.code').textContent = to.querySelector('.code').textContent;
          to.querySelector('.code').textContent = tempCode;

          // Swap flag
          const tempFlag = from.querySelector('.flag').src;
          from.querySelector('.flag').src = to.querySelector('.flag').src;
          to.querySelector('.flag').src = tempFlag;
      });

      // Close dropdown if clicked outside
      document.addEventListener('click', () => {
          document.querySelectorAll('.currency-dropdown').forEach(drop => drop.classList.add('hidden'));
      });
  });


</script>