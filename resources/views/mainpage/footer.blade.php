<footer class="flex justify-center items-center w-full md:pt-[10vh]">
  <section class="bg-[#F2F2F2] md:rounded-2xl md:w-[95vw] w-full">
    <div class="px-6 py-12 grid grid-cols-1 md:grid-cols-2 justify-between items-center gap-y-10">
      <div class="space-y-4 md:w-[30vw]">
        <div class="flex items-center gap-2">
          <img src="{{asset('../asserts/footerLogo.svg')}}" alt="Flovide Logo" class="w-50 h-50" />
        </div>
        <p class="text-gray-600 text-sm leading-relaxed">
          {{ __('At Flovide, our mission is to empower individuals and businesses with seamless, secure, and affordable financial solutions.') }}
        </p>

        <div class="mt-6">
          <h3 class="font-semibold text-lg mb-3">
            {{ __('Subscribe To Our Newsletter') }}
          </h3>
          <form class="flex rounded-full border border-gray-300 overflow-hidden bg-white">
            <input type="email" placeholder="{{ __('Enter your email') }}" class="flex-1 px-4 py-2 outline-none text-sm" />
            <span class="p-1 rounded-full bg-white">
              <button type="submit"
                class="flex items-center gap-2 bg-gray-900 rounded-full text-white px-5 py-2 text-sm font-medium hover:bg-gray-800 transition">
                {{ __('Subscribe') }}
                <span>
                  <img src="{{asset('../asserts/homepage/arrow-up.svg')}}" alt="" width="20px" />
                </span>
              </button>
            </span>
          </form>
        </div>
      </div>

      <section class="grid grid-cols-1 gap-y-10  md:grid-cols-3 md:w-[50vw]">
        <div>
          <h4 class="font-semibold mb-4">{{ __('Quick Links') }}</h4>
          <ul class="space-y-2 text-gray-600 text-sm">
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Blog and news') }}</a>
            </li>
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Mobile app') }}</a>
            </li>
            <li>
              <a href="{{ route('careers') }}" class="hover:text-gray-900">{{ __('Careers') }}</a>
            </li>
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Why choose us?') }}</a>
            </li>
            <li>
                <a href="{{ url('/business#plans') }}" class="hover:text-gray-900">
                    {{ __('Pricing plan') }}
                </a>
            </li>

          </ul>
        </div>

        <div>
          <h4 class="font-semibold mb-4">{{ __('Our Services') }}</h4>
          <ul class="space-y-2 text-gray-600 text-sm">
            {{-- <li>
              <a href="#" class="hover:text-gray-900">Mobile banking</a>
            </li> --}}
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Advanced security') }}</a>
            </li>
            {{-- <li>
              <a href="#" class="hover:text-gray-900">Digital wallet</a>
            </li> --}}
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Budgeting tools') }}</a>
            </li>
            <li>
              <a href="#" class="hover:text-gray-900">{{ __('Making transactions') }}</a>
            </li>
          </ul>
        </div>

        <div>
          <h4 class="font-semibold mb-4">{{ __('Get In Touch') }}</h4>
          <ul class="space-y-2 text-gray-600 text-sm">
            {{-- <li>{{ __('United Kingdom') }}</li> --}}
            <li>
              <a href="mailto:info@flovide.com" class="hover:text-gray-900">info@flovide.com</a>
            </li>
            <li>
              <a href="mailto:support@flovide.com" class="hover:text-gray-900">support@flovide.com</a>
            </li>
            <li>
              <a href="mailto:partnership@flovide.com" class="hover:text-gray-900">partnership@flovide.com</a>
            </li>
            {{-- <li>
              <a href="tel:+10000000000" class="hover:text-gray-900">+1 (000) 0000000</a>
            </li> --}}
          </ul>
        </div>
      </section>
    </div>

    <!-- bottom link section -->
    <div class="border-t border-gray-200 mt-8">
      <div
        class="max-w-7xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
        <p>© Flovide 2026</p>
        <div class="flex space-x-4 mt-2 md:mt-0">
          <a href="{{ route('privacy-policy') }}" class="hover:text-gray-700">{{ __('Terms and conditions') }}</a>
          <a href="{{ route('privacy-policy') }}" class="hover:text-gray-700">{{ __('Privacy policy') }}</a>
        </div>
      </div>
    </div>
    <!-- Regulatory & Social Section -->
<div class="border-t border-gray-300 mt-10 pt-8 text-center px-6">

    <!-- Left: Logo -->
    <div class="flex items-center">
      <img src="{{asset('../asserts/footerLogo.svg')}}" alt="OhentPay Logo" class="h-10">
    </div>

    <!-- Right: Copyright -->
    {{-- <div class="text-sm text-gray-600">
      © OhentPay 2026
    </div> --}}

  <!-- Social Icons -->
  <div class="flex justify-center gap-4 mb-6">
    <a href="https://www.facebook.com/profile.php?id=61578341616934" class="text-gray-600 hover:text-black"><i class="fab fa-facebook-f"></i></a>
    <a href="https://x.com/flovide65209" class="text-gray-600 hover:text-black"><i class="fab fa-twitter"></i></a>
    <a href="https://www.instagram.com/flo.vide/" class="text-gray-600 hover:text-black"><i class="fab fa-instagram"></i></a>
    <a href="https://www.linkedin.com/company/108073528/" class="text-gray-600 hover:text-black"><i class="fab fa-linkedin-in"></i></a>
    <a href="https://www.youtube.com/channel/UCnoCw_kCWnHsMIDJ3SwMwOw" class="text-gray-600 hover:text-black"><i class="fab fa-youtube"></i></a>
    {{-- <a href="#" class="text-gray-600 hover:text-black"><i class="fab fa-telegram"></i></a> --}}
  </div>

  <!-- Compliance Text -->
  <div class="max-w-4xl mx-auto text-xs text-gray-500 leading-relaxed space-y-3">
    <p>
      Flovide is duly registered as a Money Services Business (MSB) with the Financial Transactions and Reports Analysis Centre of Canada (FINTRAC), in accordance with applicable Canadian anti-money laundering and counter-terrorist financing regulations, under registration number C100000869.
    </p>
    <p>
            In the United States, Flovide is registered as a Money Services Business with the Financial Crimes Enforcement Network (FinCEN), a bureau of the U.S. Department of the Treasury, under registration number 31000301191487.

    </p>
    <!-- 3 Logos Horizontal -->
    <div class="flex justify-center items-center gap-6 mb-6">
        {{-- <img src="{{asset('../asserts/footerLogo.svg')}}" class="h-10" alt="Logo 1"> --}}
        {{-- <img src="{{asset('../asserts/footerLogo.svg')}}" class="h-10" alt="Logo 2">
        <img src="{{asset('../asserts/footerLogo.svg')}}" class="h-10" alt="Logo 3"> --}}
    </div>

    <!-- Space under logos -->
    <div class="h-6"></div>

  </div>
</div>

  </section>

  
</footer>













<script>
  function toggleLang() {
    const menu = document.getElementById('langMenu');
    menu.classList.toggle('hidden');
  }


  function toggleLang_mobile() {
    const menu = document.getElementById('langMenu_mobile');
    menu.classList.toggle('hidden');
  }

  //Close menu if clicked outside
  window.addEventListener('click', function(e){
    const menu = document.getElementById('langMenu');
    const menu_mobile = document.getElementById('langMenu_mobile');

    const button = menu.previousElementSibling;
    const button_mobile = menu_mobile.previousElementSibling;

    if(!button.contains(e.target) && !menu.contains(e.target)){
      menu.classList.add('hidden');
    }

    if(!button_mobile.contains(e.target) && !menu_mobile.contains(e.target)){
      menu_mobile.classList.add('hidden');
    }
  });
</script>