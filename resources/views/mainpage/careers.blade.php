<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Careers</title>
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
        <section class="bg-[#0F243D] md:h-[750px] md:mx-10 md:rounded-2xl text-white" id="mobileMenuButton">
            <!-- mobile menu -->
            <section
                class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
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

                        {{-- <button aria-label="Select country"
                            class="flex items-center space-x-2 border border-[#3380C4] rounded-full px-3 py-1 text-white focus:outline-none"
                            type="button">
                            <img alt="Flag of Nigeria" class="w-6 h-6 rounded-full object-cover" decoding="async"
                                height="14" src="../asserts/homepage/ng.svg" width="20" />
                            <span> NG </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button> --}}

                        <div id="google_translate_element"></div>
                    </ul>
                </div>
            </section>


            <div class="max-w-7xl mx-auto px-6 py-12 rounded-[40px]">
                <div class="text-center max-w-3xl mx-auto">
                    <p class="text-[14px] text-[#7bcf9e] font-medium mb-2">
                        Join Our Team
                    </p>
                    <h1 class="font-extrabold text-[40px] leading-[48px] mb-4">
                        Empowering Your
                        <br />
                        Financial Journey
                    </h1>
                    <p class="text-[14px] max-w-[520px] mx-auto mb-8">
                        Fast, secure, and reliable digital solutions for payments, savings, and everyday transactions.
                    </p>
                </div>
                <div class="mt-12 flex flex-col md:flex-row md:justify-center md:gap-8 gap-8 items-center">
                    <!-- First card -->
                    <div class="relative rounded-3xl overflow-hidden md:max-w-[320px] mx-auto md:mx-0">
                        <img src="../asserts/Personal/headerImage1.svg" alt="" />
                    </div>
                    <!-- Second card -->
                    <div
                        class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block">
                        <img src="../asserts/image 2 (1).png" alt="" />
                    </div>
                    <!-- Third card -->
                    <div
                        class="relative rounded-3xl overflow-hidden max-w-[320px] mx-auto md:mx-0 hidden md:inline-block">
                        <img src="../asserts/image 3.png" alt="" />
                    </div>
                </div>

                <div class="mt-16 flex flex-col md:flex-row md:justify-between items-center gap-6 md:gap-0 mx-auto">
                    <div class="flex items-center gap-10">
                        <img alt="OhentPay company logo white on transparent background" class="h-30 w-30" height="40"
                            src="../asserts/Personal/company1.png" />
                        <img alt="Sound wave style company logo white on transparent background" class="h-30 w-30"
                            height="40" src="../asserts/Personal/company2.png" />
                        <img alt="CentaDesk company logo white on transparent background" class="w-28" height="40"
                            src="../asserts/Personal/company3.png" />
                        <img alt="EW company logo white on transparent background" class="h-30 w-30" height="40"
                            src="../asserts/Personal/company4.png" />
                    </div>
                </div>
            </div>
        </section>
    </header>

    <main class="right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">










        <!-- footer -->
        @include('mainpage.footer')

    </main>

    @include('mainpage.script')

</body>

</html>