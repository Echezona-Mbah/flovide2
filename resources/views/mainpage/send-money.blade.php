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
                        <span class="text-blue-600">{{ $countryCurrency['country']}} </span> at <br>
                        competitive rates.
                    </h1>

                    <p class="mt-6 text-gray-600 text-lg max-w-lg">
                        Fast, secure, and reliable money transfers to
                        <span class="text-blue-600 font-medium">{{ $countryCurrency['country']}}</span>.
                    </p>
                    <a href="{{ route('login') }}">
                        <button class="mt-8 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-medium transition">
                            Get Started Now
                        </button>
                    </a>
                </div>

                <!-- RIGHT CARD -->
                <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-auto">

                    <!-- YOU SEND -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 relative">

                        <p class="text-sm text-gray-500 mb-1">You send</p>

                        <div class="flex items-center justify-between">

                            <div class="flex items-center text-3xl font-semibold gap-1">
                            <span id="senderSymbol">£</span>
                            <input id="sendAmount" type="number" value="100" class="w-28 border-0 focus:ring-0">
                            </div>

                            <button onclick="toggleDropdown('sender')" class="flex items-center gap-2 bg-white px-3 py-1 rounded-full border">

                            <img id="senderFlag" src="https://flagcdn.com/w20/gb.png" class="w-5 h-5 rounded-full">
                            <span id="senderCode">GBP</span>

                            </button>

                        </div>

                        <!-- Sender Dropdown -->
                        <div id="senderDropdown" class="dropdown hidden absolute right-0 mt-2 w-80 bg-white border rounded-xl shadow-lg z-50">

                            <input type="text" placeholder="Search currency..."
                            class="w-full p-3 border-b outline-none dropdown-search">

                            <div class="max-h-60 overflow-y-auto">

                            @foreach($currencies as $code => $currency)
                                <div class="currency-item flex items-center gap-3 p-3 hover:bg-gray-100 cursor-pointer"
                                    data-target="sender"
                                    data-code="{{ $code }}"
                                    data-rate="{{ $currency['rate'] }}"
                                    data-symbol="{{ $currency['symbol'] }}"
                                    data-flag="{{ $currency['countrycode'] }}">

                                    <img src="https://flagcdn.com/w20/{{ $currency['countrycode'] }}.png"
                                    class="w-5 h-5 rounded-full">

                                    <span class="currency-text" data-country="{{ $currency['country_name'] }}">
                                        {{ $currency['country_name'] }} ({{ $code }})
                                    </span>
                                </div>
                                @endforeach

                            </div>
                        </div>

                    </div>

                    <!-- EXCHANGE -->
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 flex justify-between text-sm">
                    <span class="text-gray-500">Exchange rate</span>
                    <span id="exchangeText" class="font-medium text-gray-800"></span>
                    </div>

                    <!-- YOU RECEIVE -->
                    <div class="bg-gray-50 rounded-xl p-4 relative">

                        <p class="text-sm text-gray-500 mb-1">You receive</p>

                        <div class="flex items-center justify-between">

                            <div class="flex items-center text-3xl font-semibold gap-1">
                                <span id="receiverSymbol">€</span>
                                <span id="receiveAmount">0.00</span>
                            </div>

                            <button onclick="toggleDropdown('receiver')" class="flex items-center gap-2 bg-white px-3 py-1 rounded-full border">

                                <img id="receiverFlag"
                                src="https://flagcdn.com/w20/{{ strtolower($countryCurrency['countrycode']) }}.png"
                                class="w-5 h-5 rounded-full">

                                <span id="receiverCode">{{ array_key_first($currencies->toArray()) }}</span>

                            </button>

                        </div>

                        <!-- Receiver Dropdown -->
                        <div id="receiverDropdown" class="dropdown hidden absolute right-0 mt-2 w-80 bg-white border rounded-xl shadow-lg z-50">

                            <input type="text" placeholder="Search currency..."
                            class="w-full p-3 border-b outline-none dropdown-search">

                            <div class="max-h-60 overflow-y-auto">

                                @foreach($currencies as $code => $currency)
                                    <div class="currency-item flex items-center gap-3 p-3 hover:bg-gray-100 cursor-pointer"
                                        data-target="receiver"
                                        data-code="{{ $code }}"
                                        data-rate="{{ $currency['rate'] }}"
                                        data-symbol="{{ $currency['symbol'] }}"
                                        data-flag="{{ $currency['countrycode'] }}">

                                        <img src="https://flagcdn.com/w20/{{ $currency['countrycode'] }}.png"
                                        class="w-5 h-5 rounded-full">

                                        <span class="currency-text" data-country="{{ $currency['country_name'] }}">
                                            {{ $currency['country_name'] }} ({{ $code }})
                                        </span>

                                    </div>
                                @endforeach

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
                send money to  {{ $countryCurrency['country']}} ?
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


        <!-- HOW IT WORKS -->
        <section class="max-w-7xl mx-auto px-6 py-20">

            <!-- Section Header -->
            <div class="text-center mb-16">
                <span class="inline-block bg-blue-100 text-blue-600 text-sm font-semibold px-4 py-1 rounded-full mb-4 tracking-wide uppercase">
                    Simple &amp; Fast
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                    How to send money to <br>
                    <span class="text-blue-600">{{ $countryCurrency['country'] }}</span> in 3 easy steps
                </h2>
                <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                    Get your money where it needs to go — quickly, safely, and at the best rates.
                </p>
            </div>

            <!-- Steps Grid -->
            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-6">

                <!-- Connecting dashed line (desktop only) -->
                <div class="hidden md:block absolute top-16 left-[calc(16.66%+2rem)] right-[calc(16.66%+2rem)] h-0.5 border-t-2 border-dashed border-blue-200 z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 group-hover:scale-110 transition-transform duration-300">
                        1
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 mb-5">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Your Account</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        Sign up for free in minutes. Verify your identity once and you're ready to send money globally.
                    </p>
                    <a href="{{ route('register.saveStepData') }}"
                       class="mt-6 inline-flex items-center gap-1 text-blue-600 text-sm font-semibold hover:underline">
                        Get started
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 bg-blue-600 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-white text-blue-600 text-2xl font-bold mb-6 group-hover:scale-110 transition-transform duration-300">
                        2
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-500 mb-5">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Enter Transfer Details</h3>
                    <p class="text-blue-100 text-sm leading-relaxed">
                        Choose an amount, select {{ $countryCurrency['country'] }} as your destination, and pick the best exchange rate — all in real time.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-1 text-white text-sm font-semibold">
                        Live rates
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 flex items-center justify-center rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 group-hover:scale-110 transition-transform duration-300">
                        3
                    </div>
                    <div class="w-12 h-12 flex items-center justify-center rounded-full bg-green-50 mb-5">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Money Arrives Fast</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">
                        98% of transfers arrive within minutes. Your recipient in {{ $countryCurrency['country'] }} gets notified instantly.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-1 text-green-600 text-sm font-semibold">
                        ⚡ Usually within minutes
                    </span>
                </div>

            </div>

            <!-- CTA below steps -->
            <div class="mt-12 flex justify-center">
                <a href="{{ route('login') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-full font-semibold text-base transition-colors duration-200 shadow-md hover:shadow-lg">
                    Send Money to {{ $countryCurrency['country'] }} Now →
                </a>
            </div>

        </section>

        <!-- TRUST / STATS BANNER -->
        <section class="bg-gray-50 border-y border-gray-100 py-12">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">

                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">190+</p>
                        <p class="text-sm text-gray-500">Countries Supported</p>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">98%</p>
                        <p class="text-sm text-gray-500">Delivered in Minutes</p>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 flex items-center justify-center bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">5M+</p>
                        <p class="text-sm text-gray-500">Happy Customers</p>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <div class="w-12 h-12 flex items-center justify-center bg-orange-100 rounded-full">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">256-bit</p>
                        <p class="text-sm text-gray-500">SSL Encryption</p>
                    </div>

                </div>
            </div>
        </section>


        <!-- TESTIMONIALS -->
        <section class="max-w-7xl mx-auto px-6 py-20">

            <!-- Header -->
            <div class="text-center mb-14">
                <span class="inline-block bg-blue-100 text-blue-600 text-sm font-semibold px-4 py-1 rounded-full mb-4 tracking-wide uppercase">
                    Trusted by Millions
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 leading-tight">
                    What our customers say
                </h2>
                <p class="mt-4 text-gray-500 text-lg max-w-xl mx-auto">
                    Join millions of people who trust Flovide to send money to
                    <span class="text-blue-600 font-medium">{{ $countryCurrency['country'] }}</span> and beyond.
                </p>
            </div>

            <!-- Testimonial Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Card 1 -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col gap-4 border border-gray-100">
                    <!-- Stars -->
                    <div class="flex items-center gap-1 text-yellow-400 text-lg">
                        ★★★★★
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-1">
                        "Flovide made sending money abroad incredibly simple. The exchange rate was amazing and my family received the funds within minutes!"
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold text-sm">
                            AM
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Amara M.</p>
                            <p class="text-gray-400 text-xs">Sent to {{ $countryCurrency['country'] }}</p>
                        </div>
                        <img src="https://flagcdn.com/w20/{{ strtolower($countryCurrency['countrycode']) }}.png"
                             alt="{{ $countryCurrency['country'] }}" class="w-6 h-6 rounded-full object-cover ml-auto">
                    </div>
                </div>

                <!-- Card 2 (highlighted) -->
                <div class="bg-blue-600 rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col gap-4 border border-blue-500">
                    <!-- Stars -->
                    <div class="flex items-center gap-1 text-yellow-300 text-lg">
                        ★★★★★
                    </div>
                    <p class="text-blue-100 text-sm leading-relaxed flex-1">
                        "No hidden fees, transparent rates, and lightning-fast transfers. Flovide is now the only service I use for international payments."
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white text-blue-600 font-bold text-sm">
                            JK
                        </div>
                        <div>
                            <p class="font-semibold text-white text-sm">James K.</p>
                            <p class="text-blue-200 text-xs">Sent to {{ $countryCurrency['country'] }}</p>
                        </div>
                        <img src="https://flagcdn.com/w20/{{ strtolower($countryCurrency['countrycode']) }}.png"
                             alt="{{ $countryCurrency['country'] }}" class="w-6 h-6 rounded-full object-cover ml-auto">
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-300 p-8 flex flex-col gap-4 border border-gray-100">
                    <!-- Stars -->
                    <div class="flex items-center gap-1 text-yellow-400 text-lg">
                        ★★★★★
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed flex-1">
                        "I was skeptical at first, but Flovide delivered every time. The app is clean, fast, and I trust it completely for every transfer."
                    </p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-bold text-sm">
                            FO
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">Fatima O.</p>
                            <p class="text-gray-400 text-xs">Sent to {{ $countryCurrency['country'] }}</p>
                        </div>
                        <img src="https://flagcdn.com/w20/{{ strtolower($countryCurrency['countrycode']) }}.png"
                             alt="{{ $countryCurrency['country'] }}" class="w-6 h-6 rounded-full object-cover ml-auto">
                    </div>
                </div>

            </div>

            <!-- Overall rating bar -->
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6 bg-gray-50 rounded-2xl p-6 border border-gray-100">
                <div class="flex flex-col items-center">
                    <p class="text-5xl font-bold text-gray-900">4.9</p>
                    <div class="flex text-yellow-400 text-xl mt-1">★★★★★</div>
                    <p class="text-gray-500 text-sm mt-1">Average rating</p>
                </div>
                <div class="w-px h-16 bg-gray-200 hidden sm:block"></div>
                <div class="flex flex-col items-center">
                    <p class="text-5xl font-bold text-gray-900">50K+</p>
                    <p class="text-gray-500 text-sm mt-2">Verified reviews</p>
                </div>
                <div class="w-px h-16 bg-gray-200 hidden sm:block"></div>
                <div class="flex flex-col items-center">
                    <p class="text-5xl font-bold text-gray-900">98%</p>
                    <p class="text-gray-500 text-sm mt-2">Would recommend</p>
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
                        @foreach($currencies as $code => $data)
                            <a href="{{ route('send-money', 'send-money-to-' . $data['country']) }}"
                            data-currency="{{ $code }}"
                            data-flag="https://flagcdn.com/w20/{{ $data['countrycode'] }}.png"
                            class="country-link flex flex-col items-center gap-2 group">

                                <img src="https://flagcdn.com/w80/{{ $data['countrycode'] }}.png"
                                    alt="{{ ucfirst($data['country']) }}"
                                    class="w-14 h-14 rounded-full object-cover">

                                <span class="flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1 text-sm text-gray-700 group-hover:bg-gray-50">
                                    {{ ucfirst($data['country']) }}
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M7 17L17 7M7 7h10v10"/>
                                    </svg>
                                </span>
                            </a>
                        @endforeach
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
        <!-- Dynamic Calculation -->

<script>
console.log("🚀 Exchange script loaded");

let selected = { sender: null, receiver: null };


// =====================
// Toggle dropdown
// =====================
function toggleDropdown(type) {
    console.log("📂 Toggle dropdown:", type);

    document.querySelectorAll('.dropdown')
        .forEach(d => d.classList.add('hidden'));

    document.getElementById(type + 'Dropdown')
        .classList.toggle('hidden');
}


// =====================
// Close dropdown outside click
// =====================
document.addEventListener('click', e => {
    if (!e.target.closest('.relative')) {
        console.log("🖱 Click outside — closing dropdowns");

        document.querySelectorAll('.dropdown')
            .forEach(d => d.classList.add('hidden'));
    }
});


// =====================
// Select currency
// =====================
document.querySelectorAll('.currency-item').forEach(item => {

    item.addEventListener('click', () => {

        const t = item.dataset.target;

        console.log("✅ Currency clicked:", item.dataset);

        selected[t] = {
            code: item.dataset.code,
            rate: parseFloat(item.dataset.rate),
            symbol: item.dataset.symbol,
            flag: item.dataset.flag
        };

        console.log(`📌 Selected ${t}:`, selected[t]);

        document.getElementById(t + 'Flag').src =
            `https://flagcdn.com/w20/${selected[t].flag}.png`;

        document.getElementById(t + 'Symbol').textContent =
            selected[t].symbol;

        document.getElementById(t + 'Code').textContent =
            selected[t].code;

        updateAll();
        toggleDropdown(t);
    });

});


// =====================
// Search filter
// =====================
document.querySelectorAll('.dropdown-search').forEach(input => {

    input.addEventListener('input', e => {

        const search = e.target.value.toLowerCase();
        console.log("🔍 Search:", search);

        const list = e.target.nextElementSibling;

        list.querySelectorAll('.currency-item').forEach(item => {
            item.style.display =
                item.textContent.toLowerCase().includes(search)
                    ? 'flex' : 'none';
        });

    });

});


// =====================
// Calculate conversion
// =====================
function calculate() {

    console.log("🧮 Running calculation...");

    if (!selected.sender || !selected.receiver) {
        console.warn("⚠ Sender or receiver not selected", selected);
        return;
    }

    const send =
        parseFloat(document.getElementById('sendAmount').value) || 0;

    const senderRate = parseFloat(selected.sender.rate);
    const receiverRate = parseFloat(selected.receiver.rate);

    console.log("💰 Send amount:", send);
    console.log("📊 Sender rate:", senderRate);
    console.log("📊 Receiver rate:", receiverRate);

    if (!senderRate || !receiverRate) {
        console.error("❌ Invalid rate detected!");
        return;
    }

    const result = (send / senderRate) * receiverRate;

    console.log("✅ Final result:", result);

    document.getElementById('receiveAmount')
        .textContent = result.toFixed(2);
}


// =====================
// Exchange rate text
// =====================
function updateExchange() {

    console.log("🔄 Updating exchange text");

    if (!selected.sender || !selected.receiver) return;

    const rate =
        selected.receiver.rate / selected.sender.rate;

    console.log("📈 Exchange rate:", rate);

    document.getElementById('exchangeText').textContent =
        `1 ${selected.sender.code} = ${rate.toFixed(4)} ${selected.receiver.code}`;
}


// =====================
// Update all
// =====================
function updateAll() {
    console.log("🔁 updateAll triggered");
    calculate();
    updateExchange();
}


// =====================
// Input listener
// =====================
document.getElementById('sendAmount')
.addEventListener('input', () => {
    console.log("⌨ Input changed");
    calculate();
});


// =====================
// Country grid click
// =====================
document.querySelectorAll('.country-link').forEach(link => {

    link.addEventListener('click', function(e) {

        e.preventDefault();

        const code = this.dataset.currency;
        console.log("🌍 Country clicked:", code);

        document.querySelectorAll('#receiverDropdown .currency-item')
        .forEach(item => {

            if (item.dataset.code === code) {
                console.log("🎯 Matching currency found:", code);
                item.click();
            }

        });

    });

});


// =====================
// Default setup
// =====================
console.log("⚙ Setting default currencies");

// Default sender GBP
const defaultSenderItem =
    document.querySelector('#senderDropdown .currency-item[data-code="GBP"]');

if (defaultSenderItem) {
    console.log("🇬🇧 Default sender GBP found");
    defaultSenderItem.click();
} else {
    console.warn("⚠ Default sender GBP NOT found");
}


// Default receiver
const slugCurrencyCode =
    "{{ $countryCurrency['currency_code'] ?? array_key_first($currencies->toArray()) }}";

console.log("🎯 Slug receiver currency:", slugCurrencyCode);

let receiverItem =
    document.querySelector(
        `#receiverDropdown .currency-item[data-code="${slugCurrencyCode}"]`
    );

if (!receiverItem) {
    console.warn("⚠ Slug receiver not found — using first currency");

    receiverItem =
        document.querySelector('#receiverDropdown .currency-item');
}

if (receiverItem) {
    console.log("✅ Default receiver selected:", receiverItem.dataset.code);
    receiverItem.click();
} else {
    console.error("❌ No receiver currency found!");
}

</script>


    </body>
</html>