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
                Personal
            </a>
        </li>
        <li>
            <a class="{{ Route::currentRouteName() === 'business' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
              href="{{ route('business') }}">
                Business
            </a>
        </li>
        <li>
            <a class="{{ Route::currentRouteName() === 'developer' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
              href="">
                Developer
            </a>
        </li>
        <li>
            <a class="{{ Route::currentRouteName() === 'blog' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
              href="">
                Blog
            </a>
        </li>
        <li>
            <a class="{{ Route::currentRouteName() === 'contact' ? 'text-[#1D4ED8] font-semibold' : 'hover:text-gray-900' }}"
              href="">
                Contact Us
            </a>
        </li>
    </ul>


      <!-- Right: Country selector, Sign In, Get Started -->
      <div class="flex items-center space-x-6 text-sm font-medium text-gray-800">
        <!-- Country selector -->
        <button aria-label="Select country"
          class="flex items-center space-x-2 border border-[#1D4ED8] rounded-full px-3 py-1 text-[#252525] hover:bg-[#E0E7FF] focus:outline-none"
          type="button">
          <img alt="Flag of Nigeria" class="w-5 h-5 rounded-full object-cover" decoding="async" height="14"
            src="{{asset("../asserts/homepage/ng.svg")}}" width="20" />
          <span> NG </span>
          <i class="fas fa-chevron-down text-xs"></i>
        </button>
        <!-- Sign In -->
        <a class="hover:text-gray-900" href="{{ route('login') }}"> Sign In </a>
        <!-- Divider -->
        <span class="text-gray-300 select-none"> | </span>
        <!-- Get Started button -->
        <a class="bg-[#215F9C] text-white rounded-full px-5 py-2 text-sm font-semibold hover:bg-[#1E40AF] transition-colors"
          href="{{ route('register.saveStepData') }}">
          Get Started
        </a>
      </div>
    </section>
  </nav>