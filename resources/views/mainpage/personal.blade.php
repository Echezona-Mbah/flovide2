<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home | Personal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
      rel="stylesheet"
    />

    <style>
      body {
        color: #252525;
      }
    </style>
  </head>

  <body>
    <!-- navbar  -->

    @include('mainpage.navbar')

    <!-- hero section -->

    <header class="">
     <section class="bg-[#0F243D] md:h-[750px] md:mx-10 md:rounded-2xl text-white" id="mobileMenuButton">
      <!-- mobile menu -->
      <section class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
        <section class="flex justify-between items-center w-full">
          <div>
            <img src="../asserts/mobileLogo.svg" alt="" />
          </div>
          <div id="openSidebarBtn">
            <img src="../asserts/menu-icon.svg" alt="" />
          </div>
        </section>
      </section>
           <!-- Mobile Dropdown Menu -->
      <section class="md:hidden px-4 py-3 text-white w-full flex justify-center items-center">
        <!-- Dropdown Content -->
        <div id="mobileMenuContent"
          class="mt-2 absolute top-[15vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
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
              <a href="{{ route('login') }}" class=""> Login </a>
            </button>

            <button
              class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
              <a href="{{ route('register.saveStepData') }}" class=""> Get Started </a>
            </button>

            <button aria-label="Select country"
              class="flex items-center space-x-2 border border-[#3380C4] rounded-full px-3 py-1 text-white focus:outline-none"
              type="button">
              <img alt="Flag of Nigeria" class="w-6 h-6 rounded-full object-cover" decoding="async" height="14"
                src="../asserts/homepage/ng.svg" width="20" />
              <span> NG </span>
              <i class="fas fa-chevron-down text-xs"></i>
            </button>
          </ul>
        </div>
      </section>


        <div class="max-w-7xl mx-auto px-6 py-12 rounded-[40px]">
          <div class="text-center max-w-3xl mx-auto">
            <p class="text-[14px] text-[#7bcf9e] font-medium mb-2">
              Welcome To Flovide
            </p>
            <h1 class="font-extrabold text-[40px] leading-[48px] mb-4">
              Take Total Control Of
              <br />
              Your Money
            </h1>
            <p class="text-[14px] max-w-[520px] mx-auto mb-8">
             Send money to over 190+ countries around the world in different currencies like GBP, USD, EUR, CHF, CAD, NGN etc. 
            </p>
            <div
              class="flex flex-col md:flex-row justify-center gap-4 flex-wrap"
            >
              <button
                class="bg-[#215F9C] hover:bg-[#1f4a7a] transition-colors rounded-full px-6 py-2 text-[14px] font-semibold"
              >
                Get Started With Personal
              </button>
              <a href="{{ route('register.saveStepData') }}">
                <button
                  class="border border-[#2a5ea8] hover:border-[#1f4a7a] transition-colors rounded-full px-6 py-2 text-[14px] font-semibold"
                >
                  Get Started With Business
                </button>
              </a>

            </div>
          </div>
          <div
            class="mt-12 flex flex-col md:flex-row md:justify-center md:gap-8 gap-8 items-center"
          >
            <!-- First card -->
            <div
              class="relative rounded-3xl overflow-hidden md:max-w-[320px] mx-auto md:mx-0"
            >
              <img src="../asserts/Personal/headerImage1.svg" alt="" />
            </div>
            <!-- Second card -->
            <div
              class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block"
            >
              <img src="../asserts/Personal/headerImage2.png" alt="" />
            </div>
            <!-- Third card -->
            <div
              class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block"
            >
              <img src="../asserts/Personal/headerImage3.png" alt="" />
            </div>
          </div>

          <div
            class="mt-16 flex flex-col md:flex-row md:justify-between items-center gap-6 md:gap-0 mx-auto"
          >
            <div class="flex items-center gap-10">
              <img
                alt="OhentPay company logo white on transparent background"
                class="h-30 w-30"
                height="40"
                src="../asserts/Personal/company1.png"
              />
              <img
                alt="Sound wave style company logo white on transparent background"
                class="h-30 w-30"
                height="40"
                src="../asserts/Personal/company2.png"
              />
              <img
                alt="CentaDesk company logo white on transparent background"
                class="w-28"
                height="40"
                src="../asserts/Personal/company3.png"
              />
              <img
                alt="EW company logo white on transparent background"
                class="h-30 w-30"
                height="40"
                src="../asserts/Personal/company4.png"
              />
            </div>
            <div class="flex items-center gap-2">
              <div class="flex -space-x-3">
                <div
                  class="w-10 h-10 rounded-full bg-[#7ea9d9] border-2 border-[#11263b]"
                ></div>
                <div
                  class="w-10 h-10 rounded-full bg-[#7ea9d9] border-2 border-[#11263b]"
                ></div>
                <div
                  class="w-10 h-10 rounded-full bg-[#7ea9d9] border-2 border-[#11263b]"
                ></div>
              </div>
              <div class="flex items-center gap-1">
                <svg
                  class="w-5 h-5 text-yellow-400"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.388-2.462a1 1 0 00-1.175 0l-3.388 2.462c-.784.57-1.838-.196-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"
                  ></path>
                </svg>
                <svg
                  class="w-5 h-5 text-yellow-400"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.388-2.462a1 1 0 00-1.175 0l-3.388 2.462c-.784.57-1.838-.196-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"
                  ></path>
                </svg>
                <svg
                  class="w-5 h-5 text-yellow-400"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.388-2.462a1 1 0 00-1.175 0l-3.388 2.462c-.784.57-1.838-.196-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"
                  ></path>
                </svg>
                <svg
                  class="w-5 h-5 text-yellow-400"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.388-2.462a1 1 0 00-1.175 0l-3.388 2.462c-.784.57-1.838-.196-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"
                  ></path>
                </svg>
                <svg
                  class="w-5 h-5 text-yellow-400/90"
                  fill="currentColor"
                  viewbox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.974a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.388 2.462a1 1 0 00-.364 1.118l1.287 3.974c.3.922-.755 1.688-1.54 1.118l-3.388-2.462a1 1 0 00-1.175 0l-3.388 2.462c-.784.57-1.838-.196-1.539-1.118l1.287-3.974a1 1 0 00-.364-1.118L2.045 9.4c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69l1.286-3.974z"
                  ></path>
                </svg>
                <span class="text-white text-xs font-normal ml-2"> 4.9 </span>
              </div>
              <p class="text-white text-xs font-normal ml-4">
                from 500+ reviews
              </p>
            </div>
          </div>
        </div>
      </section>
    </header>

    <main
      class="right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden"
    >
      <!-- about us  -->
      <section class="max-w-7xl mx-auto px-6 py-12 md:py-20">
        <div
          class="flex flex-col md:flex-row items-center md:items-start gap-10 md:gap-20"
        >
          <img
            alt="Two people fist bumping over a wooden table with charts and laptop in background"
            class="w-full max-w-md md:max-w-lg rounded-3xl object-cover"
            height="400"
            src="../asserts/Personal/about_us.png"
            width="600"
          />
          <div class="max-w-xl pt-4">
            <h1 class="text-3xl md:text-4xl font-semibold leading-tight mb-6">
              Flovide – Do More with Your Money
            </h1>
            <p class="text-gray-600 text-base md:text-lg leading-relaxed mb-12">
              Experience effortless money transfers and multi-currency accounts
              tailored for your financial needs. With Flovide, you can send and
              receive money at lower costs across 190+ countries in over 40
              currencies.
            </p>
            {{-- <div
              class="flex space-x-10 justify-between items-center md:space-x-20 text-gray-800"
            >
              <div>
                <p class="text-2xl font-bold">$20B</p>
                <p class="text-sm mt-1">Assets managed</p>
              </div>
              <div>
                <p class="text-2xl font-bold">$150k</p>
                <p class="text-sm mt-1">Loans approved</p>
              </div>
              <div>
                <p class="text-2xl font-bold">15+</p>
                <p class="text-sm mt-1">Years in business</p>
              </div>
            </div> --}}
          </div>
        </div>
      </section>
      <!-- about us end-->

      <!-- blog section -->
      <section class="w-full">
        <section class="md:mx-auto px-4 md:px-6 pt-4 pb-16 text-center">
          <p class="text-[#777777] text-sm mb-2">Our Services</p>
          <h1 class="md:text-4xl text-3xl font-medium mb-3 text-gray-900">
            Sync Your Finances
          </h1>
          <p class="text-[#777777] max-w-xl mx-auto text-sm md:text-base">
           Effortlessly control your money across multiple scenarios in different currencies,
            ensuring smooth and secure financial transactions anytime, anywhere.
          </p>
        </section>

        <section
          class="md:max-w-7xl md:mx-auto grid grid-cols-1 md:grid-cols-2 md:gap-6 gap-y-10 pb-16 px-4"
        >
          <!-- Card 1 -->
          <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
            <section
              class="flex flex-col justify-start items-start w-full px-10 pt-10"
            >
              <h2 class="font-semibold text-lg mb-2">Secure Transactions</h2>
              <p class="text-gray-600 text-sm mb-4">
                Enjoy fast, safe, and reliable financial services.
              </p>
              <button
                class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
                type="button"
              >
                Get Started Now
                <span>
                  <img
                    src="../asserts/arrow-up-black.svg"
                    alt=""
                    width="20px"
                  />
                </span>
              </button>
            </section>
            <section class="flex justify-start items-start w-full">
              <img
                alt="Blue digital lock surrounded by futuristic circular interface representing secure transactions"
                class="rounded-2xl flex-grow px-10 w-[40vw]"
                src="../asserts/homepage/blog_img1.png"
              />
            </section>
          </article>
          <!-- Card 2 -->
          <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
            <section
              class="flex flex-col justify-start items-start w-full px-10 pt-10"
            >
              <h2 class="font-semibold text-lg mb-2">Currency Exchange</h2>
              <p class="text-gray-600 text-sm mb-4">
                Convert money effortlessly and send in your preferred currency.
              </p>
              <button
                class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
                type="button"
              >
                Get Started Now
                <span>
                  <img
                    src="../asserts/arrow-up-black.svg"
                    alt=""
                    width="20px"
                  />
                </span>
              </button>
            </section>

            <section>
              <img
                alt="Businessman hand interacting with digital world map and network connections representing currency exchange"
                class="rounded-t-xl w-full flex-grow px-10"
                height="200px"
                src="../asserts/homepage/blog_img2.png"
                width="200px"
              />
            </section>
          </article>
          <!-- Card 3 -->
          <article class="bg-[#F2F2F2] rounded-3xl flex flex-col relative">
            <section
              class="flex flex-col justify-start items-start w-full px-10 pt-10"
            >
              <h2 class="font-semibold text-lg mb-2">Local Business Finance</h2>
              <p class="text-gray-200px text-sm mb-4">
                Convert money effortlessly and send in your preferred currency.
              </p>
              <button
                class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
                type="button"
              >
                Get Started Now
                <span>
                  <img
                    src="../asserts/arrow-up-black.svg"
                    alt=""
                    width="20px"
                  />
                </span>
              </button>
            </section>

            <section
              class="flex justify-start items-start w-full absolute bottom-0"
            >
              <img
                alt="Smiling chef in restaurant kitchen with customers in background representing local business finance"
                class="rounded-t-xl w-full flex-grow px-10"
                height="200px"
                src="../asserts/homepage/blog_img3.png"
                width="200px"
              />
            </section>
          </article>
          <!-- Card 4 -->
          <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
            <section
              class="flex flex-col justify-start items-start w-full px-10 pt-10"
            >
              <h2 class="font-semibold text-lg mb-2">Funds Remittance</h2>
              <p class="text-gray-200px text-sm mb-4">
                Quickly transfer funds to family, friends, or business
                worldwide.
              </p>
              <button
                class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
                type="button"
              >
                Get Started Now
                <span>
                  <img
                    src="../asserts/arrow-up-black.svg"
                    alt=""
                    width="20px"
                  />
                </span>
              </button>
            </section>
            <section>
              <img
                alt="Father and two children using tablet outdoors in park representing funds remittance"
                class="rounded-t-xl w-full flex-grow px-10"
                height="200px"
                src="../asserts/homepage/blog_img4.png"
                width="200px"
              />
            </section>
          </article>
        </section>
      </section>
      <!-- blog section end-->

      <!-- plans  -->
      <section class="w-full flex justify-center items-center">
        <section class="bg-[#13263E] text-white max-w-7xl md:rounded-3xl">
          <div class="max-w-7xl mx-auto px-6 py-16 rounded-3xl">
            <div class="text-center max-w-3xl mx-auto">
              <p class="text-sm font-normal mb-2 text-[#82D3AB]">
                {{-- Pricing Plans --}}
              </p>
              <h1 class="text-3xl md:text-5xl font-medium leading-tight mb-4">
                Sending money is free, making it affordable for you to send money<br />
                 both locally and internationally
              </h1>
              {{-- <h2>Sending money is free, making it affordable for you to send money both locally and internationally. </h2> --}}
              <p class="text-sm font-normal max-w-xl mx-auto">
                Flovide offers a free sending to our personal clients while businesses can choose from a range of pricing plans to fit every budget and level of need.
              </p>
            </div>

            <div
              class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 max-w-7xl mx-auto"
            >
              <!-- Basic Plan -->
              {{-- <div
                class="rounded-3xl border shadow-2xl border-transparent bg-[#1C3C5E] flex flex-col justify-between">
                <div class="p-8">
                  <div class="mb-6">
                    <img
                      src="../asserts/basic_good.svg"
                      alt="Basic Plan Icon"
                      class="w-6 h-6"
                    />
                  </div>
                  <h2 class="text-xl font-semibold mb-3">Basic Plan</h2>
                  <p class="text-sm mb-6 leading-relaxed">
                    Enjoy unparalleled benefits, including exclusive lifestyle
                    perks, premium travel experiences, and a meticulously
                    crafted platinum-plated card.
                  </p>
                  <p class="text-3xl font-semibold mb-6">Free</p>
                  <button
                    class="inline-flex items-center gap-2 bg-white text-black text-sm font-semibold rounded-full px-5 py-2"
                    type="button"
                  >
                    Get Started Now
                    <span>
                      <img
                        src="../asserts/arrow-up-black.svg"
                        alt=""
                        width="20px"
                      />
                    </span>
                  </button>
                </div>
                <div
                  class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]">
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    1
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    2
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    3
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    4
                  </p>
                </div>
              </div> --}}

              <!-- Standard Plan -->
              {{-- <div
                class="rounded-3xl shadow-2xl bg-gradient-to-tl from-[#1C3C5E] via-[#1C3C5E] to-[#1f4a3a] flex flex-col justify-between">
                <div class="p-8">
                  <div class="mb-6">
                    <img
                      src="../asserts/solar_star-broken.svg"
                      alt="star icon"
                      class="w-6 h-6"
                    />
                  </div>
                  <h2 class="text-xl font-semibold mb-3">Standard Plan</h2>
                  <p class="text-sm mb-6 leading-relaxed">
                    Whether you're sending money abroad or managing your budget,
                    our Standard account helps you maximize your money with
                    ease.
                  </p>
                  <p class="text-3xl font-semibold mb-6">
                    £3.99<span class="text-base font-normal">/ per month</span>
                  </p>
                  <button
                    class="inline-flex items-center gap-2 bg-[#7fc02a] text-white text-sm font-semibold rounded-full px-5 py-2"
                    type="button"
                  >
                    Get Started Now
                    <span>
                      <img
                        src="../asserts/homepage/arrow-up.svg"
                        alt=""
                        width="20px"
                      />
                    </span>
                  </button>
                </div>
                <div
                  class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]"
                >
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    1
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    2
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    3
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    4
                  </p>
                </div>
              </div> --}}

              <!-- Enterprise Plan -->
              {{-- <div
                class="rounded-3xl border shadow-2xl border-transparent bg-[#1C3C5E] flex flex-col justify-between">
                <div class="p-8">
                  <div class="mb-6">
                    <img
                      src="../asserts/enterprice_icon.svg"
                      alt="enterprice_icon Icon"
                      class="w-6 h-6"
                    />
                  </div>
                  <h2 class="text-xl font-semibold mb-3">Enterprise Plan</h2>
                  <p class="text-sm mb-6 leading-relaxed">
                    Get exclusive benefits like priority in-app support and
                    everyday spending protection—all for less than the cost of a
                    coffee.
                  </p>
                  <p class="text-3xl font-semibold mb-6">
                    £9.99<span class="text-base font-normal">/ per month</span>
                  </p>
                  <button
                    class="inline-flex items-center gap-2 bg-white text-black text-sm font-semibold rounded-full px-5 py-2"
                    type="button"
                  >
                    Get Started Now
                    <span>
                      <img
                        src="../asserts/arrow-up-black.svg"
                        alt=""
                        width="20px"
                      />
                    </span>
                  </button>
                </div>
                <div
                  class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]"
                >
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    1
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    2
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    3
                  </p>
                  <p class="flex items-center gap-3">
                    <i class="fas fa-check-circle opacity-40"></i> Selling point
                    4
                  </p>
                </div>
              </div> --}}
            </div>
          </div>
        </section>
      </section>

      <!-- plans end-->

      <!-- testimonies -->
      <section class="flex w-full justify-center items-center">
        <section
          class="bg-white text-gray-900 md:max-w-7xl relative overflow-hidden"
        >
          <section class="px-4 md:max-w-[90rem] md:mx-auto md:px-6 py-12">
            <div
              class="flex flex-col md:flex-row gap-y-4 justify-center w-full md:justify-between items-center mb-10"
            >
              <h2 class="text-2xl md:text-4xl font-medium leading-tight">
                What Our Clients Say About Us
              </h2>
              <button
                class="flex items-center justify-center gap-2 rounded-full border border-gray-800 px-2 py-2 text-sm font-medium hover:bg-gray-100 transition w-[45vw] md:w-[15vw]"
                type="button"
              >
                Get Started Now
                <span>
                  <img
                    src="../asserts/arrow-up-black.svg"
                    alt=""
                    width="20px"
                  />
                </span>
              </button>
            </div>

            <!-- Slider Container -->
            <div
              id="testimonial-slider"
              class="relative w-full overflow-hidden"
            >
              <div
                id="slider-track"
                class="flex transition-transform duration-500 ease-in-out gap-6"
              >
                <template id="testimonial-card">
                  <article
                    class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                    <p class="mb-8 text-base leading-relaxed">
                      Flovide makes international transfers so easy and affordable. I can send money to my family abroad without worrying about high fees
                    </p>
                    <div class="flex items-center gap-4">
                      <img
                        alt="User"
                        class="w-16 h-16 rounded-full object-cover"
                        src="../asserts/download (4).jpg"
                      />
                      <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                          Chijioke
                        </h3>
                        <p class="text-gray-700 text-sm leading-tight">
                          Nigeria
                        </p>
                      </div>
                    </div>
                  </article>

                  <article
                    class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                    <p class="mb-8 text-base leading-relaxed">
                     I love having the options to send money to multiple countries in multi-currency choices in one place. Managing my business payments in USD and EUR has never been smoother. 
                    </p>
                    <div class="flex items-center gap-4">
                      <img
                        alt="User"
                        class="w-16 h-16 rounded-full object-cover"
                        src="../asserts/download (1).jpg"
                      />
                      <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                          Sarah
                        </h3>
                        <p class="text-gray-700 text-sm leading-tight">
                          UK
                        </p>
                      </div>
                    </div>
                  </article>

                  <article
                    class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                    <p class="mb-8 text-base leading-relaxed">
                        The security and speed are what sold me on Flovide. My transactions always feel safe, and transfers arrive quickly.
                    </p>
                    <div class="flex items-center gap-4">
                      <img
                        alt="User"
                        class="w-16 h-16 rounded-full object-cover"
                        src="../asserts/download (7).jpg"
                      />
                      <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                          Leon
                        </h3>
                        <p class="text-gray-700 text-sm leading-tight">
                          Germany
                        </p>
                      </div>
                    </div>
                  </article>

                  <article
                    class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                    <p class="mb-8 text-base leading-relaxed">
                    With Flovide, I can move money around 40+ currencies effortlessly. It’s perfect for someone like me who travels often
                    </p>
                    <div class="flex items-center gap-4">
                      <img
                        alt="User"
                        class="w-16 h-16 rounded-full object-cover"
                        src="../asserts/download (6).jpg"
                      />
                      <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                          Amina
                        </h3>
                        <p class="text-gray-700 text-sm leading-tight">
                          UAE
                        </p>
                      </div>
                    </div>
                  </article>


                  <article
                    class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                    <p class="mb-8 text-base leading-relaxed">
                      The customer support team is always responsive and helpful. Flovide truly makes me feel like am on top of the world. 
                    </p>
                    <div class="flex items-center gap-4">
                      <img
                        alt="User"
                        class="w-16 h-16 rounded-full object-cover"
                        src="../asserts/download (5).jpg"
                      />
                      <div>
                        <h3 class="font-semibold text-gray-900 leading-tight">
                          Michael
                        </h3>
                        <p class="text-gray-700 text-sm leading-tight">
                          Uganda
                        </p>
                      </div>
                    </div>
                  </article>

                </template>
              </div>
            </div>

            <!-- Slider Controls -->
            <div class="flex justify-center mt-10 gap-4">
              <button
                aria-label="Previous"
                id="prev-btn"
                class="w-12 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:bg-gray-100 transition"
              >
                <i class="fas fa-chevron-left text-sm"></i>
              </button>
              <button
                aria-label="Next"
                id="next-btn"
                class="w-12 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:bg-gray-100 transition"
              >
                <i class="fas fa-chevron-right text-sm"></i>
              </button>
            </div>
          </section>
        </section>
      </section>
      <!-- testimonies end -->

      <!--  Virtual Card-->
      {{-- <section class="w-full flex flex-col items-center justify-center">
        <section class="mx-auto px-6 pt-12 pb-16 text-center space-y-4">
          <p class="text-gray-400 text-sm font-semibold mb-2">Virtual Card</p>
          <h1 class="text-4xl font-medium mb-3 text-gray-900">
            More than a card—it’s your key
          </h1>
          <p class="text-[#777777] max-w-xl mx-auto text-sm md:text-base">
            Access. Identity. Simplicity. With our virtual card, you are able to
            make seamless transactions, smart connections, and everyday ease.
            Wherever you go, your key goes with you.
          </p>

          <button
            class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
            type="button"
          >
            Get Started Now
            <span>
              <img src="../asserts/arrow-up-black.svg" alt="" width="20px" />
            </span>
          </button>
        </section>

        <section class="max-w-7xl px-4 md:px-10">
          <img src="../asserts/Personal/virutalCard.svg" alt="" class="" />
        </section>
      </section> --}}
      <!--  Virtual Card end -->

      <!-- Our globe countries -->
      <section class="w-full flex flex-col items-center justify-center">
        <section class="mx-auto px-6 pt-12 pb-16 text-center space-y-4">
          <h1 class="text-4xl font-medium mb-3 text-gray-900">
            Global access in 190+ countries
          </h1>
          <p class="text-[#777777] max-w-xl mx-auto text-sm md:text-base">
          Moving, traveling, or sending money abroad? You're covered in 190+ countries—effortless payments, 
          wherever you call home or do business.
          </p>

          <button
            class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
            type="button"
          >
            Get Started Now
            <span>
              <img src="../asserts/arrow-up-black.svg" alt="" width="20px" />
            </span>
          </button>
        </section>

        <section class="max-w-7xl px-10">
          <img
            src="../asserts/country.svg"
            alt=""
            class="hidden md:inline-block"
          />
          <img
            src="../asserts/world_countries_mobile.svg"
            alt=" "
            class="md:hidden"
          />
        </section>
      </section>

      <!-- Our globe countries end-->

      <!-- business part -->
      <section class="bg-white">
        <div class="md:max-w-5xl md:mx-auto px-4 md:px-6 py-12">
          <h1
            class="text-center text-3xl sm:text-4xl font-medium text-gray-900 md:max-w-[35vw] mx-auto leading-tight"
          >
            Robust business account designed for growth
          </h1>
          <div
            class="mt-12 rounded-3xl md:border border-gray-400 p-4 md:p-10 flex flex-col gap-8 sm:gap-0 bg-white"
          >
            <section
              class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 w-full justify-between"
            >
              <button
                class="bg-gray-900 text-white rounded-xl md:px-10 py-2 font-semibold text-sm"
                type="button"
              >
                Global Payments
              </button>
              <button
                class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm"
                type="button"
              >
                Dedicated Support
              </button>
              <button
                class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm"
                type="button"
              >
                Prestine Security
              </button>
              <button
                class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm"
                type="button"
              >
                Integrations
              </button>
            </section>

            <section class="flex flex-col md:flex-row gap-y-8 w-full">
              <div class="flex flex-col sm:w-1/2">
                <div
                  class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 mb-6"
                >
                  <img
                    src="../asserts/mingcute_world-line.svg"
                    alt=""
                    class="h-6 w-6"
                  />
                </div>
                <h2 class="text-gray-900 font-bold text-xl mb-2">
                  Your business to the world
                </h2>
                <p
                  class="text-gray-700 text-sm mb-8 leading-relaxed max-w-[320px]"
                >
                  Create business account to manage your money,
                   Make local and international payments to 190+ countries.
                </p>
                <button
                  class="bg-gray-900 text-white rounded-full px-6 py-2 text-sm font-semibold w-max flex items-center gap-2 hover:bg-gray-800 transition"
                  type="button"
                >
                  Create A Business Account Now
                  <span>
                    <img
                      src="../asserts/homepage/arrow-up.svg"
                      alt=""
                      width="20px"
                    />
                  </span>
                </button>
              </div>
              <div class="sm:w-1/2 flex justify-end">
                <img
                  alt="Young woman sitting on bed holding a credit card in one hand and using a laptop on her lap, wearing a light blue knitted sweater"
                  class="rounded-2xl max-w-full h-auto object-cover"
                  height="320"
                  src="../asserts/homepage/globalPayments.png"
                  width="480"
                />
              </div>
            </section>
          </div>
        </div>
      </section>
      <!-- business part end-->

      <!-- Accordion -->
      <section>
        <section class="bg-white text-gray-900">
          <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <h1 class="text-3xl font-semibold text-gray-900 text-center mb-10">
              Frequently Asked Questions
            </h1>
            <div class="flex flex-col lg:flex-row gap-10 lg:gap-20">
              <section class="lg:flex-1 max-w-md">
                <img
                  alt="Customer support agent"
                  class="rounded-3xl w-full object-cover"
                  height="320"
                  src="../asserts/customerCare.png"
                  width="400"
                />
                <h2 class="mt-6 text-xl font-semibold text-gray-900">
                  Need to speak with someone?
                </h2>
                <button
                  class="mt-3 inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2 text-white text-sm font-medium hover:bg-gray-800 transition"
                  type="button"
                >
                  Contact Support
                  <span>
                    <img
                      src="../asserts/arrow-up-black.svg"
                      alt=""
                      width="20px"
                    />
                  </span>
                </button>
              </section>
              <section class="lg:flex-1 max-w-3xl space-y-4">
                <!-- Accordion Item -->

                <!-- Accordion Item 1 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                      How do I create a Flovide account?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed"
                  >
                    <p class="list-disc mt-2">
                      Creating a Flovide account is very easy and simple. 
                      Just download Flovide mobile app, fill in your details and submit. 
                      If you are fully verified, you can immediately start making local and international payments to 190+ countries.
                       However, businesses can create account online or in the app. 
                    </p>
                  </div>
                </article>

                <!-- Accordion Item 2 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                      What security measures does Flovide use to protect my account?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden"
                  >
                    "Flovide uses multi-factor authentication (MFA), encrypted communications,
                     and continuous fraud monitoring to ensure your transactions
                      and account access remain secure."
                  </div>
                </article>

                <!-- Accordion Item 3 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                      Does Flovide hold my money?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden"
                  >
                    No. Flovide does not hold customer funds.
                    All funds are immediately remitted to your desired locations. 
                  </div>
                </article>

                <!-- Accordion Item 4 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                       How long does it take to send money with Flovide?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden"
                  >
                    The time it takes Flovide to deliver your funds depends on the type of transfer that you make.
                     About 98% of our transfers are completed within minutes. 
                  </div>
                </article>

                <!-- Accordion Item 5 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                      Are there any transaction fees?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden"
                  >
                    Sending money through Flovide is completely free if you are an individual.
                     However, Business pay for transactions depending on their chosen plan. 
                  </div>
                </article>

                <!-- Accordion Item 6 -->
                <article
                  class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm"
                >
                  <header
                    class="flex justify-between items-center cursor-pointer"
                  >
                    <h3 class="font-semibold text-gray-900 text-base leading-6">
                      How can I contact customer support?
                    </h3>
                    <button
                      aria-label="Toggle"
                      class="toggle-btn text-gray-900"
                    >
                      <svg
                        class="h-6 w-6 plus-icon"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M12 4v16m8-8H4"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                      <svg
                        class="h-6 w-6 close-icon hidden"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                      >
                        <path
                          d="M6 18L18 6M6 6l12 12"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </button>
                  </header>
                  <div
                    class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden"
                  >
                    You can contact support via:
                    <ul class="list-disc pl-5 mt-2">
                      <li>Live chat in the Flovide app or website (24/7).</li>
                      <li>
                        Email: support@flovide.com (response within 24 hours).
                      </li>
                      <li>
                        Phone support (check app for your region’s number).
                      </li>
                    </ul>
                  </div>
                </article>
              </section>
            </div>
          </section>
        </section>
      </section>
      <!-- Accordion end-->

      <!-- get app on store -->
                 @include('mainpage.getapp')


      <!-- footer -->
           @include('mainpage.footer')

    </main>

           @include('mainpage.script')

  </body>
</html>
