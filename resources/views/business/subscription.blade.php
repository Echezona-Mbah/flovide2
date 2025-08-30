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
                Subscriptions
            </h1>
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[1200px] mx-auto">
                    <!-- Top Stats -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <div
                            class="flex-1 rounded-2xl bg-gradient-to-r from-[#1f4d3a] to-[#2f6a44] p-6 text-white flex flex-col justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-[#3a6a56] p-2 rounded-lg flex items-center justify-center"
                                    style="width: 36px; height: 36px">
                                    <img src="/asserts/dashboard/sub-no.png" alt="">
                                  
                                </div>
                            </div>
                            <p class="mt-4 text-sm font-normal">
                                Number of subscribers
                            </p>
                            <div class="flex items-center justify-between mt-1">
                                <h2 class="text-2xl font-extrabold leading-none">
                                    1,500
                                </h2>
                                <div
                                    class="bg-white text-[#2f6a44] text-xs font-semibold rounded-full flex items-center gap-1 px-3 py-1">
                                    <i class="fas fa-arrow-up">
                                    </i>
                                    36%
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex-1 rounded-2xl bg-white p-6 flex flex-col justify-between border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-[#d0e1fd] p-2 rounded-lg flex items-center justify-center"
                                    style="width: 36px; height: 36px">
                                    <img src="/asserts/dashboard/sub-plans.png" alt="">
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 font-normal">
                                Number of plans
                            </p>
                            <h2 class="text-2xl font-extrabold leading-none text-gray-900 mt-1">
                                10
                            </h2>
                        </div>
                        <div
                            class="flex-1 rounded-2xl bg-white p-6 flex flex-col justify-between border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-[#fdd7dd] p-2 rounded-lg flex items-center justify-center"
                                    style="width: 36px; height: 36px">
                                    <img src="/asserts/dashboard/sub-un.png" alt="">
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 font-normal">
                                Number of unsubscribers
                            </p>
                            <h2 class="text-2xl font-extrabold leading-none text-gray-900 mt-1">
                                10
                            </h2>
                        </div>
                        <div
                            class="flex-1 rounded-2xl bg-white p-6 flex flex-col justify-between border border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-[#f9f3c9] p-2 rounded-lg flex items-center justify-center"
                                    style="width: 36px; height: 36px">
                                    <img src="/asserts/dashboard/sub-total.png" alt="">
                                </div>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 font-normal">
                                All-time subscriber total
                            </p>
                            <h2 class="text-2xl font-extrabold leading-none text-gray-900 mt-1">
                                3,000
                            </h2>
                        </div>
                    </div>
                    <!-- Main Content -->
                    <div class="bg-[#e9e9e9] rounded-2xl p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div class="flex-1 max-w-full sm:max-w-xs">
                                <div class="relative">
                                    <input
                                        class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-gray-600 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Search subscriptions" type="text" />
                                    <i
                                        class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">
                                    </i>
                                </div>
                            </div>
                            <a href="{{ route('add_subscription.create') }}">
                                <button
                                    class="flex items-center gap-2 bg-[#0f5499] text-white text-sm font-semibold rounded-full px-5 py-2 whitespace-nowrap hover:bg-[#0c3f6a] transition">
                                    <i class="fas fa-box"></i>
                                    Create Subscription
                                </button>
                            </a>
                            
                        </div>
                        <!-- Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Card 1 -->

                           @foreach ($subscriptions as $subscription)
                                <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4"data-title="{{ $subscription->title }}">
                                    <div class="flex justify-between items-center gap-4">
                                        <div>
                                            <img alt="Cover Image"
                                            class="w-14 h-14 rounded-lg object-cover" height="56" width="56"
                                            src="{{ $subscription->cover_image ? asset('storage/' . $subscription->cover_image) : asset('/asserts/dashboard/solar_box.png') }}" />
                                            {{-- src="{{ $subscription->cover_image ? asset('storage/' . $subscription->cover_image) : asset('/assets/dashboard/solar_box.png') }}" /> --}}

                                        </div>
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <div class="text-lg font-extrabold">
                                                    {{ $subscription->amount }} {{ strtoupper($subscription->currency) }}
                                                </div>
                                            </div>
                                            <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">
                                                {{ ucfirst($subscription->subscription_interval) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h3 class="font-semibold text-md leading-tight">
                                            {{ $subscription->title }}
                                        </h3>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                        <div class="flex gap-4 items-center">
                                            <div class="flex items-center gap-1">
                                                <img src="/assets/dashboard/person.png" alt="">
                                                50 {{-- Replace this with actual number of users if available --}}
                                            </div>
                                            <div class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                                <i class="fas fa-check text-[10px]"></i>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <!-- Edit button -->
                                            <a href="{{ route('subscriptions.edit', $subscription->id) }}" 
                                                aria-label="Edit button"
                                                class="bg-blue-200 rounded-full w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-300 transition">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        
                                            <!-- Original Next button -->
                                            <button aria-label="Next button"
                                                class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach


       
                            <!-- Card 9 -->
                            {{-- <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-14 h-14 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="/asserts/dashboard/solar_box.png" alt="">
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
                                            <img src="/asserts/dashboard/person.png" alt="">
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
                            </div> --}}
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
    <script>
        document.getElementById('subscriptionSearch').addEventListener('keyup', function () {
            let query = this.value.toLowerCase();
            document.querySelectorAll('.subscription-card').forEach(function (card) {
                let title = card.dataset.title.toLowerCase();
                if (title.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    </script>
    
</body>

</html>