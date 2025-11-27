<nav class="">
  <!-- Desktop Navigation -->
  <section class="hidden md:flex items-center justify-between px-6 py-4 max-w-[1200px] mx-auto">
    <!-- Left: Logo -->
    <a class="flex items-center space-x-1" href="{{ route('personal') }}">
      <img src="{{asset('../asserts/homepage/Logo.png')}}" alt="Logo" />
    </a>

    <!-- Center: Navigation Links -->
    <ul class="hidden md:flex space-x-8 text-sm font-medium text-gray-800">
      <li>
        <a class="{{ Route::currentRouteName() === 'personal' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
          href="{{ route('personal') }}">
          {{ __('Personal') }}
        </a>
      </li>
      <li>
        <a class="{{ Route::currentRouteName() === 'business' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
          href="{{ route('business') }}">
          {{ __('Business') }}
        </a>
      </li>
      <li>
        <a class="{{ Route::currentRouteName() === 'developer' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
          href="">
          {{ __('Developer') }}
        </a>
      </li>
      <li>
        <a class="{{ Route::currentRouteName() === 'blog' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
          href="">
          {{ __('Blog') }}
        </a>
      </li>
      <li>
        <a class="{{ Route::currentRouteName() === 'contact' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
          href="">
          {{ __('Contact Us') }}
        </a>
      </li>
    </ul>


    <!-- Right: Language selector, Sign In, Get Started -->
    <div class="flex items-center space-x-6 text-sm font-medium text-gray-800">
      <!--  Language selector -->
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
        <button onclick="toggleLang()" class="flex items-center space-x-2 border border-[#1D4ED8] rounded-full px-3 py-1 text-[#252525]">
          <span>{{ strtoupper(app()->getLocale()) }} </span>
          <img class="w-6 h-6 rounded-full object-cover" src="{{asset('../asserts/homepage/' . $currentFlag) }}" alt="Flag" />
          <i class="fas fa-chevron-down text-xs"></i>
        </button>

        <ul id="langMenu" class="absolute hidden bg-white shadow-md rounded-lg p-2 mt-2 right-0">
          <li><a href="{{ route('lang.switch', 'en') }}" class="block px-4 py-2 hover:bg-gray-100">English</a></li>
          <li><a href="{{ route('lang.switch', 'es') }}" class="block px-4 py-2 hover:bg-gray-100">Spanish</a></li>
          <li><a href="{{ route('lang.switch', 'fr') }}" class="block px-4 py-2 hover:bg-gray-100">French</a></li>
          <li><a href="{{ route('lang.switch', 'lg') }}" class="block px-4 py-2 hover:bg-gray-100">Luganda</a></li>
        </ul>
      </div>
      <!-- Sign In -->
      <a class="hover:text-gray-900" href="{{ route('login') }}"> {{ __('Sign In') }} </a>
      <!-- Divider -->
      <span class="text-gray-300 select-none"> | </span>
      <!-- Get Started button -->
      <a class="bg-[#215F9C] text-white rounded-full px-5 py-2 text-sm font-semibold hover:bg-[#1E40AF] transition-colors" href="{{ route('register.saveStepData') }}">
        {{ __('Get Started') }}
      </a>
    </div>
  </section>
</nav>


<!-- <script>
  function toggleLang() {
    const menu = document.getElementById('langMenu');
    menu.classList.toggle('hidden');
  }

  // Optional: Close menu if clicked outside
  window.addEventListener('click', function(e){
    const menu = document.getElementById('langMenu');
    const button = menu.previousElementSibling;
    if(!button.contains(e.target) && !menu.contains(e.target)){
      menu.classList.add('hidden');
    }
  });
</script> -->