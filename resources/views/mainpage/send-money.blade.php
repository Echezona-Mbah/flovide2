<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Home | Send Money</title>
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

        <header>
            <section class="bg-white md:hidden text-white" id="mobileMenuButton">
                <!-- mobile menu -->
                <section class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
                    <section class="flex justify-between items-center w-full">
                        <div>
                            <img src="../asserts/mobileLogo2.png" alt="" style="width: 60px;" />
                        </div>
                        <div id="openSidebarBtn">
                            <img src="../asserts/menu-icon2.png" alt="" style="width: 40px;" />
                        </div>
                    </section>
                </section>
                <!-- Mobile Dropdown Menu -->
                <section class="md:hidden px-4 py-3 text-white w-full flex justify-center items-center">
                    <!-- Dropdown Content -->
                    <div id="mobileMenuContent" class="mt-2 absolute top-[15vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
                        <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[20px] font-medium space-y-6 ">
                            <a href="{{ route('personal') }}" class="block px-4 py-2 text-white hover:bg-[#3B82F6] border border-[#3380C4] p-4 bg-[#1E5186] rounded-xl">
                                {{ __('Personal') }}
                            </a>
                            <a href="{{ route('business') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                {{ __('Business') }}
                            </a>
                            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                {{ __('Developer') }}
                            </a>
                            <a href="{{ route('blog') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                {{ __('Blog') }}
                            </a>
                            <a href="{{route('contactUs')}}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                {{ __('Contact Us') }}
                            </a>

                            <button class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                                <a href="{{ route('login') }}" class=""> {{ __('Login') }} </a>
                            </button>

                            <button class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                                <a href="{{ route('register.saveStepData') }}" class=""> {{ __('Get Started') }} </a>
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
            </section>
        </header>


        <section class="max-w-7xl mx-auto px-6 py-20">
            <div class="grid lg:grid-cols-2 gap-14 items-center">

                <!-- LEFT CONTENT -->
                <div>
                    <h1 class="text-5xl md:text-6xl font-bold leading-tight text-gray-900">
                        Send money to <br>
                        <span class="text-blue-600">{{ $country }}</span> at <br>
                        competitive rates.
                    </h1>

                    <p class="mt-6 text-gray-600 text-lg max-w-lg">
                        Fast, secure, and reliable money transfers to
                        <span class="text-blue-600 font-medium">{{ $country }}</span>.
                    </p>
                    <a href="{{ route('login') }}">
                        <button class="mt-8 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-medium transition">
                            Get Started Now
                        </button>
                    </a>
                </div>

                <!-- RIGHT CARD -->
                <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-auto">
                    
                    <!-- You Send -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <p class="text-sm text-gray-500 mb-1">You send</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-semibold">£100</span>
                            <div class="flex items-center gap-2 bg-white px-3 py-1 rounded-full border">
                                <span>🇬🇧</span>
                                <span class="font-medium">GBP</span>
                            </div>
                        </div>
                    </div>

                    <!-- Exchange Rate -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 flex justify-between text-sm">
                        <span class="text-gray-500">Exchange rate</span>
                        <span class="font-medium text-gray-800">1 GBP = 1.1313 EUR</span>
                    </div>

                    <!-- You Receive -->
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-sm text-gray-500 mb-1">You receive</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-semibold">€113.13</span>
                            <div class="flex items-center gap-2 bg-white px-3 py-1 rounded-full border">
                                <span>🇪🇺</span>
                                <span class="font-medium">EUR</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br><br> 
            <br><br> 

            <!-- Heading -->
            <h2 class="text-4xl md:text-5xl font-bold text-center text-gray-900 max-w-3xl mx-auto leading-tight">
                Why use Flovide to <br>
                send money to {{ $country }}?
            </h2>

            <!-- Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mt-16">

                <!-- Card 1 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 mb-6">
                        <span class="text-blue-600 text-lg">✔</span>
                    </div>
                    <h3 class="font-semibold text-lg mb-3">Good Rates</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        We offer good rates and no hidden fees on all international transactions.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 mb-6">
                        <span class="text-green-600 text-lg">$</span>
                    </div>
                    <h3 class="font-semibold text-lg mb-3">Low Fees</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Low fees on transactions ensuring you get value for your money.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-purple-100 mb-6">
                        <span class="text-purple-600 text-lg">⚡</span>
                    </div>
                    <h3 class="font-semibold text-lg mb-3">Speed</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        With Flovide, your money reaches its destination swiftly when you need it to.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 mb-6">
                        <span class="text-gray-700 text-lg">🔒</span>
                    </div>
                    <h3 class="font-semibold text-lg mb-3">Security</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Flovide employs state-of-the-art encryption technology to safeguard your transactions.
                    </p>
                </div>

            </div>
        </section>

        <br><br> 
        <br><br>

        <main class="md:relative md:top-[-20vh] right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">
           
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
                
                <div class="max-w-7xl mx-auto px-4 py-10">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">

                        <!-- Country Item -->
                        <a href="{{ route('send-money', 'send-money-to-Austria') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/at.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/at.png" alt="Austria" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Austria
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-belgium') }}" data-currency="BEF" data-flag="https://flagcdn.com/w20/be.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/be.png" alt="Belgium" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Belgium
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-bulgaria') }}" data-currency="BGN" data-flag="https://flagcdn.com/w20/bg.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/bg.png" alt="Bulgaria" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Bulgaria
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-canada') }}" data-currency="CAD" data-flag="https://flagcdn.com/w20/ca.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/ca.png" alt="Canada" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Canada
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>
                        
                        <a href="{{ route('send-money', 'send-money-to-croatia') }}" data-currency="HRK" data-flag="https://flagcdn.com/w20/hr.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/hr.png" alt="Croatia" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Croatia
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-cyprus') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/cy.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/cy.png" alt="Cyprus" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Cyprus
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-czech-republic') }}" data-currency="CZK" data-flag="https://flagcdn.com/w20/cz.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/cz.png" alt="Czech Republic" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Czech Republic
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-denmark') }}" data-currency="DKK" data-flag="https://flagcdn.com/w20/dk.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/dk.png" alt="Denmark" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Denmark
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-estonia') }}" data-currency="EEK" data-flag="https://flagcdn.com/w20/ee.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/ee.png" alt="Estonia" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Estonia
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>


                        <a href="{{ route('send-money', 'send-money-to-finland') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/ee.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/fi.png" alt="Finland" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Finland
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-france') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/fr.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/fr.png" alt="France" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            France
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-germany') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/de.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/de.png" alt="Germany" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Germany
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-gibraltar') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/gi.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/gi.png" alt="Gibraltar" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Gibraltar
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-greece') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/gr.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/gr.png" alt="Greece" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Greece
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-hungary') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/hu.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/hu.png" alt="Hungary" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Hungary
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-iceland') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/is.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/is.png" alt="Iceland" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Iceland
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-ireland') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/ie.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/ie.png" alt="Ireland" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Ireland
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-italy') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/it.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/it.png" alt="Italy" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Italy
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-latvia') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/lv.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/lv.png" alt="Latvia" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Latvia
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-liechtenstein') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/li.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/li.png" alt="Liechtenstein" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Liechtenstein
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-lithuania') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/lt.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/lt.png" alt="Lithuania" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Lithuania
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-luxembourg') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/lu.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/lu.png" alt="Luxembourg" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Luxembourg
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-malta') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/mt.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/mt.png" alt="Malta" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Malta
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-netherlands') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/nl.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/nl.png" alt="Netherlands" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Netherlands
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-nigeria') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/ng.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/ng.png" alt="Nigeria" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Nigeria
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-norway') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/no.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/no.png" alt="Norway" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Norway
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-poland') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/pl.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/pl.png" alt="Poland" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Poland
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-portugal') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/pt.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/pt.png" alt="Portugal" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Portugal
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-romania') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/ro.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/ro.png" alt="Romania" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Romania
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-slovakia') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/sk.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/sk.png" alt="Slovakia" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Slovakia
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-slovenia') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/si.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/si.png" alt="Slovenia" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Slovenia
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-spain') }}" data-currency="EUR" data-flag="https://flagcdn.com/w20/es.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/es.png" alt="Spain" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Spain
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-sweden') }}" data-currency="SEK" data-flag="https://flagcdn.com/w20/se.png" class=" country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/se.png" alt="Sweden" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            Sweden
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-united-kingdom') }}" data-currency="GBP" data-flag="https://flagcdn.com/w20/gb.png" class="country-link flex flex-col items-center gap-2 group">
                            <img src="https://flagcdn.com/w80/gb.png" alt="United Kingdom" class="w-14 h-14 rounded-full object-cover">
                            <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                            United Kingdom
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M7 17L17 7M7 7h10v10"/>
                            </svg>
                            </span>
                        </a>

                        <a href="{{ route('send-money', 'send-money-to-united-states') }}" data-currency="USD" data-flag="https://flagcdn.com/w20/us.png" class="country-link flex flex-col items-center gap-2 group">
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

            </section>



            <section>
                <section class="bg-white text-gray-900">
                    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                        <h1 class="text-3xl font-semibold text-gray-900 text-center mb-10">
                            {{ __('Frequently Asked Questions') }}
                        </h1>
                        <div class="flex flex-col lg:flex-row gap-10 lg:gap-20">
                            <section class="lg:flex-1 max-w-md">
                                <img alt="Customer support agent" class="rounded-3xl w-full object-cover" height="320" src="{{asset('../asserts/customerCare.png')}}" width="400" />
                                <h2 class="mt-6 text-xl font-semibold text-gray-900">
                                    {{ __('Need to speak with someone?') }}
                                </h2>
                                <button class="mt-3 inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2 text-white text-sm font-medium hover:bg-gray-800 transition" type="button">
                                    {{ __('Contact Support') }}
                                    <span>
                                    <img src="{{asset('../asserts/arrow-up-black.svg')}}" alt="" width="20px" />
                                    </span>
                                </button>
                            </section>
                            <section class="lg:flex-1 max-w-3xl space-y-4">
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

            <!-- get app on store -->
            @include('mainpage.getapp')


            <!-- footer -->
            @include('mainpage.footer')

        </main>

        @include('mainpage.script')

    </body>
</html>