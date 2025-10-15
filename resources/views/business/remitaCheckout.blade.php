<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Remita | Checkout</title>
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
    @include('../mainpage.navbar')

    <!-- hero section -->
    <header class="relative">
        <section class="bg-[#0F243D] md:hidden h-[120px]" id="mobileMenuButton">
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
                <div id="mobileMenuContent" class="mt-2 absolute top-[13vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
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

                        <button class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                            <a href="#" class=""> Login </a>
                        </button>

                        <button class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                            <a href="#" class=""> Get Started </a>
                        </button>

                        <button aria-label="Select country" class="flex items-center space-x-2 border border-[#3380C4] rounded-full px-3 py-1 text-white focus:outline-none" type="button">
                            <img alt="Flag of Nigeria" class="w-6 h-6 rounded-full object-cover" decoding="async" height="14" src="{{asset('../asserts/homepage/ng.svg')}}" width="20" />
                            <span> NG </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                    </ul>
                </div>
            </section>
        </section>
    </header>


    <main class="md:relative md:top-[3vh] right-0 left-0 mx-auto space-y-10 md:space-y-20 overflow-x-hidden">

        <!-- Hero Image -->
        <div class="p-8">
            <div class="h-80 w-full rounded-xl overflow-hidden">
                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d" alt="Tax and Accounting" class="w-full h-full object-cover rounded-xl">
            </div>
        </div>

        <!-- remita Card -->
        <main class="relative top-[-30vh] flex justify-center">
            <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-center mb-6"> {{ ucfirst($remita->title) }}</h2>

                <!-- Price -->
                <div class="flex items-center space-x-2 mb-6 border-t pt-4">
                    <!-- Icon background -->
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M3 11l8.586 8.586a2 2 0 002.828 0l6.586-6.586a2 2 0 000-2.828L12.828 3H7a4 4 0 00-4 4v4z" />
                        </svg>
                    </div>
                    <span class="text-xl font-semibold">
                        {{ number_format($remita->amount, 2) . " " . $remita->currency }}
                    </span>
                </div>

                <!-- Form -->
                <form class="space-y-4" method="post" action="remita.pay">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium mb-1">Full Name</label>
                        <input type="text" name="fullname" placeholder="John Doe" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" placeholder="eg. johndoe@gmail.com" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Card Number</label>
                        <div class="flex items-center border rounded-md px-3 py-2">
                            <input type="text" name="cardnumber" placeholder="0000 0000 0000 0000" class="w-full outline-none">
                            <img src="https://img.icons8.com/color/36/000000/visa.png" class="ml-2 h-6">
                            <img src="https://img.icons8.com/color/36/000000/mastercard.png" class="ml-1 h-6">
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium mb-1">Expiration Date</label>
                            <input type="text" name="expirationDate" placeholder="MM / YYYY" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-sm font-medium mb-1">Security Code</label>
                            <input type="text" name="SecurityCode" placeholder="000" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Cardholder Name</label>
                        <input type="text" name="Cardholder" placeholder="Enter cardholder name" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="w-36 bg-blue-700 text-white py-2 rounded-3xl hover:bg-blue-700">Pay</button>
                </form>
            </div>
        </main>

        <!-- footer -->
        @include('../mainpage.footer')

    </main>

    @include('../mainpage.script')

</body>

</html>