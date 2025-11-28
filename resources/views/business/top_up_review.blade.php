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

                    <!-- Progress Stepper -->
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

                        <div class="flex-1 h-0.5 bg-blue-300 mx-2"></div>

                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full border-2 border-blue-500"></div>
                            <p class="text-xs mt-1">Verification</p>
                        </div>
                    </div>

                    <!-- Title -->
                    <h2 class="text-center text-2xl font-semibold mb-10">
                        Review details
                    </h2>

                    <!-- Details Box -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-8">

                        <div class="flex justify-between px-6 py-4 border-b border-gray-200">
                            <span>We’ll withdraw</span>
                            <span class="font-semibold">£ 200.20</span>
                        </div>

                        <div class="flex justify-between px-6 py-4 border-b border-gray-200">
                            <span>Fees</span>
                            <span class="font-semibold">£ 0.20</span>
                        </div>

                        <div class="flex justify-between px-6 py-4 border-b border-gray-200">
                            <span>You get exactly</span>
                            <span class="font-semibold">£ 200.00</span>
                        </div>

                        <div class="flex justify-between px-6 py-4">
                            <span>Should arrive</span>
                            <span class="font-semibold">in seconds</span>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="space-y-4">

                        <button id="openModal"
                            class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-full font-medium">
                            Top-up wallet
                        </button>

                        <button
                            class="w-full bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 py-3 rounded-full font-medium">
                            Cancel
                        </button>

                    </div>

                </div>
            </section>


            <div id="successModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">

                <div class="bg-white w-full max-w-md rounded-xl shadow-xl relative p-8 text-center">

                    <!-- Close button -->
                    <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                        ✖
                    </button>

                    <!-- Emoji Image -->
                    <img src="https://em-content.zobj.net/source/microsoft-teams/337/smiling-face-with-sunglasses_1f60e.png" alt="Success Emoji" class="w-32 h-32 mx-auto mb-6" />

                    <h2 class="text-xl font-semibold mb-6">Transaction Successful!</h2>

                    <!-- Continue Button -->
                    <button id="continueBtn" class="w-full bg-blue-700 hover:bg-blue-800 text-white py-3 rounded-full font-medium">
                        Continue
                    </button>
                </div>
            </div>

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

        const modal = document.getElementById('successModal');
        document.getElementById('openModal').onclick = () => modal.classList.remove('hidden');
        document.getElementById('closeModal').onclick = () => modal.classList.add('hidden');
        document.getElementById('continueBtn').onclick = () => modal.classList.add('hidden');
    </script>
</body>

</html>