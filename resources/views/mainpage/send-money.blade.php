<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Home | Send Money</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        <script>
            window.fcWidgetMessengerConfig = {
                open: false,
            }
        </script>
        <script src='//fw-cdn.com/16096204/7073720.js' chat='true'></script>
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
// ✅ allow navigation to the same page with slug
document.querySelectorAll('.country-link').forEach(link => {
    link.addEventListener('click', function() {
        // no preventDefault → link navigates normally
        console.log("🌍 Country clicked, navigating to:", this.href);
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