<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Us</title>
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

    <header class="">
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
                        <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Blog') }}
                        </a>
                        <a href="{{route('contactUs')}}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                            {{ __('Contact Us') }}
                        </a>

                        <button
                            class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                            <a href="{{ route('login') }}" class=""> {{ __('Login') }} </a>
                        </button>

                        <button
                            class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
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
    

    <div class="max-w-6xl mx-auto px-4 py-12">

        <!-- CONTACT SECTION -->
        <div class="grid md:grid-cols-2 gap-10 items-start">
            
            <!-- Contact Form -->
            <div class="order-2 md:order-1">
                <h2 class="text-3xl font-bold mb-6">Contact Us</h2>

                <form class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium">First Name</label>
                            <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-sm font-medium">Last Name</label>
                            <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Company Name (optional)</label>
                        <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Email</label>
                        <input type="email" class="w-full mt-1 border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Phone number (optional)</label>
                        <input type="text" class="w-full mt-1 border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-medium">How can we help you?</label>
                        <textarea rows="4" class="w-full mt-1 border rounded-lg px-3 py-2 resize-none"></textarea>
                        <p class="text-xs text-gray-400 text-right mt-1">Max. 500 characters</p>
                    </div>

                    <button class="bg-blue-600 text-white px-6 py-2 rounded-full text-sm hover:bg-blue-700 transition">
                        Submit
                    </button>
                </form>
            </div>

            <!-- Image -->
            <div class="order-1 md:order-2 rounded-3xl overflow-hidden">
                <img src="../asserts/cont.png" alt="Support" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- FAQ SECTION -->
        <div class="mt-20">
            <h2 class="text-2xl font-bold text-center mb-10">Frequently Asked Questions</h2>

            <div class="grid md:grid-cols-3 gap-8">

                <!-- Support Card -->
                <div class="bg-white rounded-2xl p-6 shadow-sm order-2 md:order-1">
                    <img src="../asserts/customerCare.png" class="rounded-xl mb-4" alt="">
                    <h4 class="font-semibold mb-2">Need to speak with someone?</h4>
                    <button class="bg-gray-900 text-white px-4 py-2 rounded-full text-sm">
                        Contact Support →
                    </button>
                </div>

                <!-- FAQ List -->
                <div class="md:col-span-2 space-y-3 order-1 md:order-2">
                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">How do I create a Flovide account?</p>
                        <span class="text-xl">×</span>
                    </div>

                    <div class="bg-gray-100 rounded-xl p-4 text-sm text-gray-600">
                        Create business accounts to manage your money in different currencies.
                        Make local and international payments to 190+ countries.
                    </div>

                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">Which currencies can I hold in my account?</p>
                        <span class="text-xl">+</span>
                    </div>

                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">How long does it take to send and receive money?</p>
                        <span class="text-xl">+</span>
                    </div>

                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">Is my money safe with Flovide?</p>
                        <span class="text-xl">+</span>
                    </div>

                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">Are there any transaction fees?</p>
                        <span class="text-xl">+</span>
                    </div>

                    <div class="bg-white rounded-xl p-4 flex justify-between items-center">
                        <p class="font-medium">How can I contact customer support?</p>
                        <span class="text-xl">+</span>
                    </div>
                </div>
            </div>
        </div>

    </div>




    <!-- footer -->
    @include('mainpage.footer')

    </main>

    @include('mainpage.script')

</body>

</html>