@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">


    <!-- Mobile menu button -->
    @include('business.header')

    <!-- Sidebar -->
    @include('business.sidebar')

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Top-Up Your Wallet
            </h1>
            @include('business.header_notifical')
        </header>
        {{-- <section class="relative w-full"> --}}
            <section
                class="bg-white w-full text-gray-700 min-h-screen md:rounded-tl-3xl md:p-6 p-2 shadow-md overflow-x-hidden ">

                <!-- Container -->
                <div class="max-w-2xl mx-auto py-12 px-4">

                    <!-- Steps -->
                    <div class="flex items-center justify-between mb-10">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full border-2 border-blue-500"></div>
                            <p class="text-xs mt-1">Amount</p>
                        </div>

                        <div class="flex-1 h-0.5 bg-blue-300 mx-2"></div>

                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full border-2 border-blue-500 bg-white"></div>
                            <p class="text-xs mt-1">Card</p>
                        </div>

                        <div class="flex-1 h-0.5 bg-gray-300 mx-2"></div>

                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300"></div>
                            <p class="text-xs mt-1">Verification</p>
                        </div>
                    </div>

                    <!-- Page Title -->
                    <h2 class="text-center text-2xl font-semibold mb-10">Pay with card</h2>

                    <!-- Card Input Section -->
                    <div class="space-y-6">

                        <!-- Card Number -->
                        <div>
                            <label class="block mb-2 font-medium">Card Number</label>
                            <div class="relative">
                                <input type="text" placeholder="0000 0000 0000 0000"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">

                                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center space-x-1">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                        class="w-7">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png"
                                        class="w-7">
                                </div>
                            </div>
                        </div>

                        <!-- Expiration & CVC -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 font-medium">Expiration Date</label>
                                <input type="text" placeholder="MM / YYYY"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block mb-2 font-medium">Security Code</label>
                                <input type="text" placeholder="000"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="border border-gray-200 rounded-lg p-5">
                            <div class="flex justify-between py-2">
                                <span>You get</span>
                                <span class="font-semibold">£ 200.00</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-200">
                                <span>Fees</span>
                                <span class="font-semibold">£ 0.20</span>
                            </div>
                            <div class="flex justify-between py-2 border-t border-gray-200">
                                <span>Should arrive</span>
                                <span class="font-semibold">in seconds</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-10">
                        <button
                            class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-full font-medium text-sm">
                            You get £ 200
                        </button>
                    </div>

                </div>
            </section>
            {{--
        </section> --}}
    </main>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on window resize if desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>

</html>