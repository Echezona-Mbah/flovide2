@include('business.head')

<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    
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
                Referral Rewards 
            </h1>
            @include('business.header_notifical')
        </header>
        <section class="relative w-full">
            <section class="bg-white text-gray-700 min-h-screen w-full md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[1200px] mx-auto">
                    <div class="bg-white max-w-4xl p-8 md:p-12">
                    
                        <!-- Heading -->
                        <h1 class="text-3xl md:text-5xl font-bold text-gray-900 leading-tight mb-6">
                            Make referrals,<br class="hidden md:block" />
                            Get rewards!
                        </h1>

                        <!-- Bullet points -->
                        <ul class="space-y-4 text-gray-600 text-base md:text-lg mb-6">
                            <li class="flex items-start gap-3">
                                <span class="mt-2 w-2 h-2 bg-gray-500 rounded-full"></span>
                                <p>
                                    Invite a personal account and get 
                                    <span class="font-semibold text-gray-900">£10</span> 
                                    when they make a total deposit of 
                                    <span class="font-semibold text-gray-900">£1,000</span> 
                                    within 15 days.
                                </p>
                            </li>

                            <li class="flex items-start gap-3">
                                <span class="mt-2 w-2 h-2 bg-gray-500 rounded-full"></span>
                                <p>
                                    Invite a business account and get 
                                    <span class="font-semibold text-gray-900">£50</span> 
                                    when they make a total deposit of 
                                    <span class="font-semibold text-gray-900">£2,000</span> 
                                    within 15 days.
                                </p>
                            </li>
                        </ul>

                        <!-- Share link -->
                        <p class="text-blue-600 font-medium mb-2">Share your link</p>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <!-- Input -->
                            <div class="flex items-center bg-gray-100 rounded-full px-4 py-3 w-full">
                                <input type="text" value="https://www.flovide.com/i/DE412C54E2" readonly  class="bg-transparent w-full outline-none text-gray-700 text-sm"/>
                            </div>

                            <!-- Button -->
                            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-full flex items-center justify-center gap-2 transition">
                                Share
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 12v.01M4 6v.01M4 18v.01M12 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>
            </section>
        </section>
    </main>

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