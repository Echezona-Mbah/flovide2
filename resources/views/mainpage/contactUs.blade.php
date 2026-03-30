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
        <section class="bg-[#0F243D] text-white" id="mobileMenuButton">
            <!-- Mobile Navbar -->
            <section class="md:hidden px-4 py-3 border border-[#1E5186] rounded-2xl shadow-md">
                <div class="flex justify-between items-center w-full">
                    <div>
                        <img src="../asserts/mobileLogo.svg" alt="Flovide Logo" class="h-8">
                    </div>
                    <div id="openSidebarBtn">
                        <img src="../asserts/menu-icon.svg" alt="Menu Icon" class="h-6">
                    </div>
                </div>
            </section>

            <!-- Mobile Dropdown Menu -->
            <section class="md:hidden px-4 py-3 w-full flex justify-center items-center">
                <div id="mobileMenuContent" class="mt-2 absolute top-[15vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
                    <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[18px] font-medium space-y-4 text-white">
                        <a href="{{ route('personal') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Personal</a>
                        <a href="{{ route('business') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Business</a>
                        <a href="{{ url('/Coming') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Developer</a>
                        <a href="{{ route('blog') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Blog</a>
                        <a href="{{ route('careers') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Career</a>
                        <a href="{{ route('contactUs') }}" class="block px-4 py-2 rounded-lg hover:bg-[#3B82F6]">Contact Us</a>
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
                                <a href="mailto:support@flovide.com" 
              class="mt-3 inline-flex items-center gap-2 rounded-full bg-gray-900 px-5 py-2 text-white text-sm font-medium hover:bg-gray-800 transition">
                
                {{ __('Contact Support') }}

                <span>
                    <img src="../asserts/arrow-up-black.svg" alt="" width="20px" />
                </span>
            </a>
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