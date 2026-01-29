<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home | Business</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

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

  @include('mainpage.header')


  <main class="md:relative md:top-[-20vh] right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">
    <!-- business part -->
    <section class="bg-white">
      <div class="md:max-w-5xl md:mx-auto px-4 md:px-6 py-12">
        <h1 class="text-center text-3xl sm:text-4xl font-medium text-gray-900 md:max-w-[35vw] mx-auto leading-tight">
          {{ __('Robust business accounts designed for growth') }}
        </h1>
        <div class="mt-12 rounded-3xl md:border border-gray-400 p-4 md:p-10 flex flex-col gap-8 sm:gap-0 bg-white">
          <section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 w-full justify-between">
            <button class="bg-gray-900 text-white rounded-xl md:px-10 py-2 font-semibold text-sm" type="button">
              {{ __('Global Payments') }}
            </button>
            <button class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm" type="button">
              {{ __('Dedicated Support') }}
            </button>
            <button class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm" type="button">
              {{ __('Prestine Security') }}
            </button>
            <button class="bg-gray-100 text-gray-700 rounded-xl md:px-10 py-2 text-sm" type="button">
              {{ __('Integrations') }}
            </button>
          </section>

          <section class="flex flex-col md:flex-row gap-y-8 w-full">
            <div class="flex flex-col sm:w-1/2">
              <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-900 mb-6">
                <img src="{{asset('../asserts/mingcute_world-line.svg')}}" alt="" class="h-6 w-6" />
              </div>
              <h2 class="text-gray-900 font-bold text-xl mb-2">
                {{ __('Your business to the world') }}
              </h2>
              <p class="text-gray-700 text-sm mb-8 leading-relaxed max-w-[320px]">
                {{ __('Create business account to manage your money, Make local and international payments to 190+ countries.') }}
              </p>
              <button
                class="bg-gray-900 text-white rounded-full px-6 py-2 text-sm font-semibold w-max flex items-center gap-2 hover:bg-gray-800 transition"
                type="button">
                {{ __('Create A Business Account Now') }}
                <span>
                  <img src="{{asset('../asserts/homepage/arrow-up.svg')}}" alt="" width="20px" />
                </span>
              </button>
            </div>
            <div class="sm:w-1/2 flex justify-end">
              <img
                alt="Young woman sitting on bed holding a credit card in one hand and using a laptop on her lap, wearing a light blue knitted sweater"
                class="rounded-2xl max-w-full h-auto object-cover" height="320"
                src="{{asset('../asserts/homepage/globalPayments.png')}}" width="480" />
            </div>
          </section>
        </div>
      </div>
    </section>
    <!-- business part end-->

    <!-- blog section -->
    <section class="w-full">
      <section class="md:mx-auto px-4 md:px-6 md:pt-12 pt-4 pb-16 text-center">
        <p class="text-[#777777] text-sm mb-2">{{ __('Our Services') }}</p>
        <h1 class="md:text-4xl text-3xl font-medium mb-3 text-gray-900">
          {{ __('Sync Your Finances') }}
        </h1>
        <p class="text-[#777777] max-w-xl mx-auto text-sm md:text-base">
          {{ __('Effortlessly control your money across multiple scenarios in different currencies, ensuring smooth and secure financial transactions anytime, anywhere.') }}
        </p>
      </section>

      <section class="md:max-w-7xl md:mx-auto grid grid-cols-1 md:grid-cols-2 md:gap-6 gap-y-10 pb-16 px-4">
        <!-- Card 1 -->
        <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
          <section class="flex flex-col justify-start items-start w-full px-10 pt-10">
            <h2 class="font-semibold text-lg mb-2">{{ __('Secure Transactions') }}</h2>
            <p class="text-gray-600 text-sm mb-4">
              {{ __('Enjoy fast, safe, and reliable financial services.') }}
            </p>
            <button
              class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
              type="button">
              {{ __('Get Started Now') }}
              <span>
                <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
              </span>
            </button>
          </section>
          <section class="flex justify-start items-start w-full">
            <img alt="Blue digital lock surrounded by futuristic circular interface representing secure transactions"
              class="rounded-2xl flex-grow px-10 w-[40vw]" src="{{asset('../asserts/homepage/blog_img1.png')}}" />
          </section>
        </article>
        <!-- Card 2 -->
        <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
          <section class="flex flex-col justify-start items-start w-full px-10 pt-10">
            <h2 class="font-semibold text-lg mb-2">{{ __('Currency Exchange') }}</h2>
            <p class="text-gray-600 text-sm mb-4">
              {{ __('Convert money effortlessly and send in your preferred currency.') }}
            </p>
            <button
              class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
              type="button">
              {{ __('Get Started Now') }}
              <span>
                <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
              </span>
            </button>
          </section>

          <section>
            <img
              alt="Businessman hand interacting with digital world map and network connections representing currency exchange"
              class="rounded-t-xl w-full flex-grow px-10" height="200px"
              src="{{asset('../asserts/homepage/blog_img2.png')}}" width="200px" />
          </section>
        </article>
        <!-- Card 3 -->
        <article class="bg-[#F2F2F2] rounded-3xl flex flex-col relative">
          <section class="flex flex-col justify-start items-start w-full px-10 pt-10">
            <h2 class="font-semibold text-lg mb-2">{{ __('Local Business Finance') }}</h2>
            <p class="text-gray-200px text-sm mb-4">
              {{ __('Convert money effortlessly and send in your preferred currency.') }}
            </p>
            <button
              class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
              type="button">
              {{ __('Get Started Now') }}
              <span>
                <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
              </span>
            </button>
          </section>

          <section class="flex justify-start items-start w-full absolute bottom-0">
            <img
              alt="Smiling chef in restaurant kitchen with customers in background representing local business finance"
              class="rounded-t-xl w-full flex-grow px-10" height="200px"
              src="{{asset('../asserts/homepage/blog_img3.png')}}" width="200px" />
          </section>
        </article>
        <!-- Card 4 -->
        <article class="bg-[#F2F2F2] rounded-3xl flex flex-col">
          <section class="flex flex-col justify-start items-start w-full px-10 pt-10">
            <h2 class="font-semibold text-lg mb-2">{{ __('Funds Remittance') }}</h2>
            <p class="text-gray-200px text-sm mb-4">
              {{ __('Quickly transfer funds to family, friends, or business worldwide.') }}
            </p>
            <button
              class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
              type="button">
              {{ __('Get Started Now') }}
              <span>
                <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
              </span>
            </button>
          </section>
          <section>
            <img alt="Father and two children using tablet outdoors in park representing funds remittance"
              class="rounded-t-xl w-full flex-grow px-10" height="200px"
              src="{{asset('../asserts/homepage/blog_img4.png')}}" width="200px" />
          </section>
        </article>
      </section>
    </section>
    <!-- blog section end-->

    <!-- plans  -->
    <section   id="plans" class="w-full flex justify-center items-center">
      <section class="bg-[#11402f] text-white max-w-7xl md:rounded-3xl">
        <div class="max-w-7xl mx-auto px-6 py-16 rounded-3xl">
          <div class="text-center max-w-3xl mx-auto">
            <p class="text-sm font-normal mb-2 text-[#82D3AB]">
              {{ __('Pricing Plans') }}
            </p>
            <h1 class="text-3xl md:text-5xl font-medium leading-tight mb-4">
              {{ __('Affordable Plans for Every Budget, Choose Yours') }}
            </h1>
            <p class="text-sm font-normal max-w-xl mx-auto">
              {{ __('Flovide offers a range of pricing plans to fit every budget and level of need. Whether you’re a solo professional or a business.') }}
            </p>
          </div>

          <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 max-w-7xl mx-auto">
            <!-- Basic Plan -->
            <div class="rounded-3xl border shadow-2xl border-transparent bg-[#1f4a3a] flex flex-col justify-between">
              <div class="p-8">
                <div class="mb-6">
                  <img src="{{asset('../asserts/basic_good.svg')}}" alt="Basic Plan Icon" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-semibold mb-3">{{ __('Basic Plan') }}</h2>
                <p class="text-sm mb-6 leading-relaxed">
                  {{ __('Enjoy unparalleled benefits, including exclusive lifestyle perks and premium travel experiences.') }}
                </p>
                <p class="text-3xl font-semibold mb-6">{{ __('Free') }}</p>
                <button
                  class="inline-flex items-center gap-2 bg-white text-black text-sm font-semibold rounded-full px-5 py-2"
                  type="button">
                  {{ __('Get Started Now') }}
                  <span>
                    <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
                  </span>
                </button>
              </div>
              <div class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]">
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
            </div>

            <!-- Standard Plan -->
            <div
              class="rounded-3xl shadow-2xl bg-gradient-to-br from-[#4a7f2a] via-[#1f4a3a] to-[#2f5a4a] flex flex-col justify-between">
              <div class="p-8">
                <div class="mb-6">
                  <img src="{{asset('../asserts/solar_star-broken.svg')}}" alt="star icon" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-semibold mb-3">{{ __('Standard Plan') }}</h2>
                <p class="text-sm mb-6 leading-relaxed">
                  {{ __('Whether you\'re sending money abroad or managing your budget, our Standard account helps you maximize your money with ease.') }}
                </p>
                <p class="text-3xl font-semibold mb-6">
                  £3.99<span class="text-base font-normal">/ {{ __('per month') }}</span>
                </p>
                <button
                  class="inline-flex items-center gap-2 bg-[#7fc02a] text-white text-sm font-semibold rounded-full px-5 py-2"
                  type="button">
                  {{ __('Get Started Now') }}
                  <span>
                    <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
                  </span>
                </button>
              </div>
              <div class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]">
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
            </div>

            <!-- Enterprise Plan -->
            <div class="rounded-3xl border shadow-2xl border-transparent bg-[#1f4a3a] flex flex-col justify-between">
              <div class="p-8">
                <div class="mb-6">
                  <img src="{{asset('../asserts/enterprice_icon.svg')}}" alt="enterprice_icon Icon" class="w-6 h-6" />
                </div>
                <h2 class="text-xl font-semibold mb-3">{{ __('Enterprise Plan') }}</h2>
                <p class="text-sm mb-6 leading-relaxed">
                  {{ __('Get exclusive benefits like priority in-app support and everyday spending, all for less than the cost of a coffee.') }}
                </p>
                <p class="text-3xl font-semibold mb-6">
                  £9.99<span class="text-base font-normal">/ {{ __('per month') }}</span>
                </p>
                <button
                  class="inline-flex items-center gap-2 bg-white text-black text-sm font-semibold rounded-full px-5 py-2"
                  type="button">
                  {{ __('Get Started Now') }}
                  <span>
                    <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
                  </span>
                </button>
              </div>
              <div class="border-t border-dashed border-[#2f5a4a] p-8 space-y-3 text-sm text-[#7ea6b7]">
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
            </div>
          </div>
        </div>
      </section>
    </section>

    <!-- plans end-->




    <!-- testimonies -->
    <section class="flex w-full justify-center items-center">
      <section class="bg-white text-gray-900 md:max-w-7xl relative overflow-hidden">
        <section class="px-4 md:max-w-[90rem] md:mx-auto md:px-6 py-12">
          <div class="flex flex-col md:flex-row gap-y-4 justify-center w-full md:justify-between items-center mb-10">
            <h2 class="text-2xl md:text-4xl font-medium leading-tight">
              {{ __('What Our Clients Say About Us') }}
            </h2>
            <button
              class="flex items-center justify-center gap-2 rounded-full border border-gray-800 px-2 py-2 text-sm font-medium hover:bg-gray-100 transition w-[45vw] md:w-[15vw]"
              type="button">
              {{ __('Get Started Now') }}
              <span>
                <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
              </span>
            </button>
          </div>

          <!-- Slider Container -->
          <div id="testimonial-slider" class="relative w-full overflow-hidden">
            <div id="slider-track" class="flex transition-transform duration-500 ease-in-out gap-6">
              <template id="testimonial-card">
                <article
                  class="min-w-full md:min-w-[33.3333%] bg-gray-100 rounded-tl-[1.5rem] rounded-tr-[1.5rem] rounded-br-[1.5rem] p-8 gap-y-20 flex flex-col justify-between">
                  <p class="mb-8 text-base leading-relaxed">
                    Flovide makes international transfers so easy and affordable. I can send money to my family abroad
                    without worrying about high fees
                  </p>
                  <div class="flex items-center gap-4">
                    <img alt="User" class="w-16 h-16 rounded-full object-cover" src="../asserts/download (4).jpg" />
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
                    I love having the options to send money to multiple countries in multi-currency choices in one
                    place. Managing my business payments in USD and EUR has never been smoother. </p>
                  <div class="flex items-center gap-4">
                    <img alt="User" class="w-16 h-16 rounded-full object-cover" src="../asserts/download (1).jpg" />
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
                    The security and speed are what sold me on Flovide. My transactions always feel safe, and transfers
                    arrive quickly.
                  </p>
                  <div class="flex items-center gap-4">
                    <img alt="User" class="w-16 h-16 rounded-full object-cover" src="../asserts/download (7).jpg" />
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
                    With Flovide, I can move money around 40+ currencies effortlessly. It’s perfect for someone like me
                    who travels often
                  </p>
                  <div class="flex items-center gap-4">
                    <img alt="User" class="w-16 h-16 rounded-full object-cover" src="../asserts/download (6).jpg" />
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
                    The customer support team is always responsive and helpful. Flovide truly makes me feel like am on
                    top of the world.
                  </p>
                  <div class="flex items-center gap-4">
                    <img alt="User" class="w-16 h-16 rounded-full object-cover" src="../asserts/download (5).jpg" />
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
            <button aria-label="Previous" id="prev-btn"
              class="w-12 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:bg-gray-100 transition">
              <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button aria-label="Next" id="next-btn"
              class="w-12 h-10 rounded-full border border-gray-800 flex items-center justify-center text-gray-800 hover:bg-gray-100 transition">
              <i class="fas fa-chevron-right text-sm"></i>
            </button>
          </div>
        </section>
      </section>
    </section>
    <!-- testimonies end -->






    <!-- Our globe countries -->
    <section class="w-full flex flex-col items-center justify-center">
      <section class="mx-auto px-6 pt-12 pb-16 text-center space-y-4">
        <h1 class="text-4xl font-medium mb-3 text-gray-900">
          {{ __('Global access in 190+ countries') }}
        </h1>
        <p class="text-[#777777] max-w-xl mx-auto text-sm md:text-base">
          {{ __('Moving, traveling, or sending money abroad? You\'re covered in 190+ countries—effortless payments, wherever you call home or do business.') }}
        </p>

        <button
          class="inline-flex items-center justify-center border border-gray-400 rounded-full px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-200 w-max mb-4"
          type="button">
          {{ __('Get Started Now') }}
          <span>
            <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
          </span>
        </button>
      </section>

      <!-- <section class="max-w-7xl px-10"> -->
        <!-- <img src="{{asset('../asserts/country.svg')}}" alt="" class="hidden md:inline-block" /> -->
        <!-- <img src="{{asset('../asserts/world_countries_mobile.svg')}}" alt=" " class="md:hidden " /> -->
        
        <div class="max-w-7xl mx-auto px-4 py-10">
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">

            <!-- Country Item -->
            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/at.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/at.png" alt="Austria" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Austria
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="BEF" data-flag="https://flagcdn.com/w20/be.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/be.png" alt="Belgium" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Belgium
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="BGN" data-flag="https://flagcdn.com/w20/bg.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/bg.png" alt="Bulgaria" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Bulgaria
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="CAD" data-flag="https://flagcdn.com/w20/ca.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/ca.png" alt="Canada" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Canada
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>
            
            <a href="#exchange-calculator" data-currency="HRK" data-flag="https://flagcdn.com/w20/hr.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/hr.png" alt="Croatia" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Croatia
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/cy.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/cy.png" alt="Cyprus" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Cyprus
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="CZK" data-flag="https://flagcdn.com/w20/cz.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/cz.png" alt="Czech Republic" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Czech Republic
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="DKK" data-flag="https://flagcdn.com/w20/dk.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/dk.png" alt="Denmark" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Denmark
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EEK" data-flag="https://flagcdn.com/w20/ee.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/ee.png" alt="Estonia" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Estonia
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>


            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/ee.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/fi.png" alt="Finland" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Finland
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/fr.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/fr.png" alt="France" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                France
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/de.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/de.png" alt="Germany" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Germany
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/gi.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/gi.png" alt="Gibraltar" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Gibraltar
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/gr.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/gr.png" alt="Greece" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Greece
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/hu.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/hu.png" alt="Hungary" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Hungary
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/is.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/is.png" alt="Iceland" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Iceland
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/ie.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/ie.png" alt="Ireland" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Ireland
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/it.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/it.png" alt="Italy" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Italy
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/lv.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/lv.png" alt="Latvia" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Latvia
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/li.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/li.png" alt="Liechtenstein" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Liechtenstein
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/lt.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/lt.png" alt="Lithuania" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Lithuania
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/lu.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/lu.png" alt="Luxembourg" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Luxembourg
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/mt.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/mt.png" alt="Malta" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Malta
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/nl.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/nl.png" alt="Netherlands" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Netherlands
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/ng.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/ng.png" alt="Nigeria" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Nigeria
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/no.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/no.png" alt="Norway" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Norway
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/pl.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/pl.png" alt="Poland" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Poland
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/pt.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/pt.png" alt="Portugal" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Portugal
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/ro.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/ro.png" alt="Romania" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Romania
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/sk.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/sk.png" alt="Slovakia" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Slovakia
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/si.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/si.png" alt="Slovenia" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Slovenia
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="EUR" data-flag="https://flagcdn.com/w20/es.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/es.png" alt="Spain" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Spain
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="SEK" data-flag="https://flagcdn.com/w20/se.png" class=" country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/se.png" alt="Sweden" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                Sweden
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="GBP" data-flag="https://flagcdn.com/w20/gb.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/gb.png" alt="United Kingdom" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                United Kingdom
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>

            <a href="#exchange-calculator" data-currency="USD" data-flag="https://flagcdn.com/w20/us.png" class="country-link flex flex-col items-center gap-2 group">
              <img src="https://flagcdn.com/w80/us.png" alt="United States" class="w-14 h-14 rounded-full object-cover">
              <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                United States
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path d="M7 17L17 7M7 7h10v10"/>
                </svg>
              </span>
            </a>


          </div>
        </div>

      <!-- </section> -->
    </section>

    <!-- Our globe countries end-->






    <!-- Accordion -->
    <section>
      <section class="bg-white text-gray-900">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
          <h1 class="text-3xl font-semibold text-gray-900 text-center mb-10">
            {{ __('Frequently Asked Questions') }}
          </h1>
          <div class="flex flex-col lg:flex-row gap-10 lg:gap-20">
            <section class="lg:flex-1 max-w-md">
              <img alt="Customer support agent" class="rounded-3xl w-full object-cover" height="320"
                src="{{asset('../asserts/customerCare.png')}}" width="400" />
              <h2 class="mt-6 text-xl font-semibold text-gray-900">
                {{ __('Need to speak with someone?') }}
              </h2>
              <button
                class="mt-3 inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2 text-white text-sm font-medium hover:bg-gray-800 transition"
                type="button">
                {{ __('Contact Support') }}
                <span>
                  <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
                </span>
              </button>
            </section>
            <section class="lg:flex-1 max-w-3xl space-y-4">
              <!-- Accordion Item -->

              <!-- Accordion Item 1 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('How do I create a Flovide account?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed">
                  <p class="list-disc mt-2">
                    {{ __('Creating a Flovide account is very easy and simple. Just download Flovide mobile app, fill in your details and submit. If you are fully verified, you can immediately start making local and international payments to 190+ countries. However, businesses can create account online or in the app.') }}
                  </p>
                </div>
              </article>

              <!-- Accordion Item 2 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('What security measures does Flovide use to protect my account?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden">
                  {{ __('Flovide uses multi-factor authentication (MFA), encrypted communications, and continuous fraud monitoring to ensure your transactions and account access remain secure.') }}
                </div>
              </article>

              <!-- Accordion Item 3 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('Does Flovide hold my money?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden">
                  {{ __('No. Flovide does not hold customer funds. All funds are immediately remitted to your desired locations.') }}

                </div>
              </article>

              <!-- Accordion Item 4 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('How long does it take to send money with Flovide?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden">
                  {{ __('The time it takes Flovide to deliver your funds depends on the type of transfer that you make. About 98% of our transfers are completed within minutes.') }}
                </div>
              </article>

              <!-- Accordion Item 5 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('Are there any transaction fees?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden">
                  {{ __('Sending money through Flovide is completely free if you are an individual. However, Business pay for transactions depending on their chosen plan.') }}
                </div>
              </article>

              <!-- Accordion Item 6 -->
              <article class="accordion bg-gray-100 rounded-2xl p-6 shadow-sm">
                <header class="flex justify-between items-center cursor-pointer">
                  <h3 class="font-semibold text-gray-900 text-base leading-6">
                    {{ __('How can I contact customer support?') }}
                  </h3>
                  <button aria-label="Toggle" class="toggle-btn text-gray-900">
                    <svg class="h-6 w-6 plus-icon" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <svg class="h-6 w-6 close-icon hidden" fill="none" stroke="currentColor" stroke-width="2"
                      viewBox="0 0 24 24">
                      <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </button>
                </header>
                <div class="accordion-content mt-3 text-gray-900 text-sm leading-relaxed hidden">
                  {{ __('You can contact support via:') }}
                  <ul class="list-disc pl-5 mt-2">
                    <li>{{ __('Live chat in the Flovide app or website (24/7).') }}</li>
                    <li>
                      {{ __('Email: support@flovide.com (response within 24 hours).') }}
                    </li>
                    <li>
                      {{ __('Phone support (check app for your region’s number).') }}
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


  <script>
    document.querySelectorAll('.country-link').forEach(link => {
      link.addEventListener('click', () => {
        const currency = link.dataset.currency;
        const flag = link.dataset.flag;

        // Target ONLY the "TO" currency selector
        const toSelector = document.querySelector('.currency-selector[data-type="to"]');

        if (!toSelector || !currency || !flag) return;

        // Update flag and code
        toSelector.querySelector('.flag').src = flag;
        toSelector.querySelector('.code').textContent = currency;

        // toSelector.dispatchEvent(new Event('change'));
      });
    });
  </script>


</body>

</html>