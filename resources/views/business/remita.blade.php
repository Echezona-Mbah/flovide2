@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
    </header>

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
                Subscriptions
            </h1>
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[1200px] mx-auto">
                  
                    <div class=" rounded-2xl p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div class="flex-1 max-w-full sm:max-w-xs">
                                <div class="relative">
                                    <input class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Search pages" type="text" />
                                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    </i>
                                </div>
                            </div>
                            <a href="{{ route('remita.create') }}">
                                <button class="flex items-center gap-2 bg-[#0f5499] text-white text-sm font-semibold rounded-full px-5 py-2 whitespace-nowrap hover:bg-[#0c3f6a] transition">
                                    <i class="fas fa-paste"></i>
                                Create Remita Page
                                </button>
                            </a>
                        </div>
                        <!-- Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-20">
                            <!-- Card 1 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div>
                                        <img alt="Satellite dish with blue sky background"
                                            class="w-10 h-10 rounded-lg object-cover" height="56"
                                            src="/src/asserts/dashboard/img1.png" width="56" />
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">30 USD</div>
                                        </div>
                                        <div
                                            class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-md leading-tight">Upcoming Food Delivery Subscriptions
                                    </h3>
                                </div>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div>
                                        <img alt="Stack of pancakes with strawberries and syrup"
                                            class="w-10 h-10 rounded-lg object-cover" height="56"
                                            src="/src/asserts/dashboard/img2.png" width="56" />
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div
                                            class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-md leading-tight">Delicious Meals Delivered to You
                                    </h3>
                                </div>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div>
                                        <img alt="Chocolate layered cake slice on white plate"
                                            class="w-10 h-10 rounded-lg object-cover" height="56"
                                            src="/src/asserts/dashboard/img3.png" width="56" />
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">100 GBP</div>
                                        </div>
                                        <div
                                            class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Yearly</div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-md leading-tight">Personalized Meal Deliveries for
                                        Events</h3>
                                </div>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/src/asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">1,000 NGN</div>
                                        </div>
                                        <div
                                            class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>

                                <h3 class="font-semibold text-md leading-tight">Tips for Efficient Remote Food Delivery</h3>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#F9F7E5] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                          <img src="/src/asserts/dashboard/note.png" alt="">
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                    
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 5 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/src/asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                                
                                <h3 class="font-semibold text-md leading-tight">Sustainable Food Delivery for a Greener...</h3>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 6 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div>
                                        <img alt="Chocolate layered cake slice on white plate"
                                            class="w-10 h-10 rounded-lg object-cover" height="56"
                                            src="/src/asserts/dashboard/img1.png"
                                            width="56" />
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div
                                            class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-md leading-tight">Quick Guide to Modern Food
                                        Delivery...</h3>
                                </div>
                                <div class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div class="bg-[#F9F7E5] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <img src="/src/asserts/dashboard/note.png" alt="">
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 7 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/src/asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>

                                <h3 class="font-semibold text-md leading-tight">Unique Food Delivery Ideas</h3>
                                <div
                                    class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div
                                            class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 8 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/src/asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                            
                                <h3 class="font-semibold text-md leading-tight">Food Delivery Trends in Grocery...</h3>
                                <div class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Card 9 -->
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/src/asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                            Monthly</div>
                                    </div>
                                </div>
                            
                                <h3 class="font-semibold text-md leading-tight">Using Storytelling to Engage Food Deli...</h3>
                                <div class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src="../../asserts/dashboard/person.png" alt="">
                                            50
                                        </div>
                                        <div class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button"
                                            class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
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