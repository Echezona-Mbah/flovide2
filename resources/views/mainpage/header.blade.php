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
              Personal
            </a>
            <a href="{{ route('business') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
              Business
            </a>
            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
              Developer
            </a>
            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
              Blog
            </a>
            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
              Contact Us
            </a>

            <button
              class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
              <a href="#" class=""> Login </a>
            </button>

            <button
              class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
              <a href="#" class=""> Get Started </a>
            </button>

            <button aria-label="Select country"
              class="flex items-center space-x-2 border border-[#3380C4] rounded-full px-3 py-1 text-white focus:outline-none"
              type="button">
              <img alt="Flag of Nigeria" class="w-6 h-6 rounded-full object-cover" decoding="async" height="14"
                src="{{asset('../asserts/homepage/ng.svg')}}" width="20" />
              <span> NG </span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
          </ul>
        </div>
      </section>

      <div
        class="md:max-w-7xl w-full flex flex-col lg:flex-row justify-between pt-16 pb-20 md:pb-0 px-4 md:px-10 gap-8">
        <div class="md:max-w-xl text-white">
          <h1
            class="font-semibold md:text-[3.5rem] text-4xl w-[87vw] md:full flex flex-wrap text-center md:text-left leading-[1.1] mb-6">
            Do business <br class="hidden md:inline-block" />
            like a local in <br class="hidden md:inline-block" />
            190+ countries
          </h1>
          <p class="md:text-[0.875rem] text-xl text-center md:text-left font-normal mb-8 max-w-md">
            Create business accounts in multiple currencies (GBP, USD, EUR,
            &amp; CHF) and move your money around for less. 190+ countries,
            40+ currencies.
          </p>

<div
  class="grid grid-cols-1 md:grid-cols-2 items-center justify-center w-full gap-y-4 md:w-[60%] md:space-x-14 md:items-center md:justify-normal px-10 md:px-0">

  <a href="{{ route('login') }}">
    <button
      class="bg-[#2D6BCF] text-white text-lg md:w-[15vw] font-medium rounded-full px-6 py-2.5 hover:bg-[#1f4e9e] transition">
      Sign In
    </button>
  </a>

  <a href="{{ route('register.saveStepData') }}">
    <button
      class="border md:w-[18vw] border-white border-opacity-40 text-white text-lg font-medium rounded-full px-6 md:px-4 py-2.5 hover:bg-white hover:bg-opacity-10 transition">
      Open a Free Account
    </button>
  </a>

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

    <section
      class="hidden md:flex items-center justify-center p-8 min-h-screen relative top-[-30vh] right-0 left-0 mx-auto">
      <img src="{{asset('../asserts/homepage/Dashboard.png')}}" alt="" class="w-[70vw] h-auto" />
    </section>
  </header>