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
            <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
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

                        <script>
                            @if ($errors->any())
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: '{{ $errors->first() }}',
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    customClass: {
                                        popup: 'text-sm'
                                    }
                                });
                            @endif
                        </script>
                        <!-- Cards Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-20">
                            
                            @foreach ($remitas as $remita )
                                <!-- Card 1 -->
                                <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                    <div class="flex justify-between items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                            @php
                                                if($remita->cover_image != null){
                                                    $coverImage = asset('storage/' . $remita->cover_image);
                                                }else{
                                                    $coverImage = asset('asserts/dashboard/img1.png');
                                                }
                                            @endphp
                                            <img alt="Satellite dish with blue sky background" class="w-10 h-10 rounded-lg object-cover" height="56" src="{{ $coverImage }}" width="56" />
                                        </div>
                                        <div>
                                            <div class="flex justify-between items-start">
                                                <div class="text-lg font-extrabold">{{ number_format($remita->amount, 2) . ' ' . $remita->currency }}</div>
                                            </div>
                                            {{-- <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">Monthly</div> --}}
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-md leading-tight">{{ ucfirst($remita->title) }}</h3>
                                    </div>
                                    <div
                                        class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                        <div class="flex gap-4 items-center">
                                            <div class="flex items-center gap-1">
                                                <img src=" {{ asset("asserts/dashboard/person.png") }}" alt="">
                                                {{ $payment_count[$remita->id] ?? 0  }}
                                            </div>
                                            <div class="bg-[#F9F7E5] {{ $remita->visibility == "Private" ? 'bg-[#F9F7E5]' : 'bg-[#d1f0d9]' }}  text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                                @if ($remita->visibility == 'Private')
                                                    <img src="/src/asserts/dashboard/note.png" alt="">
                                                @else
                                                    <i class="fas fa-check text-[10px]"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <button class="{{ $remita->visibility == 'Private' ? 'hidden' : '' }}" aria-label="Link button" class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                                <i class="fas fa-link"></i>
                                            </button>
                                            <a href="{{ route('remita.edit', $remita->id) }}">
                                                <button aria-label="Next button" class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            
                            @endforeach
                            

                            
                            <div class="bg-white rounded-2xl p-4 flex flex-col justify-between shadow-sm gap-y-4 border border-[#D6D6D6]">
                                <div class="flex justify-between items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                        <img src="asserts/dashboard/copy.png" alt="">
                                    </div>
                                    <div>
                                        <div class="flex justify-between items-start">
                                            <div class="text-lg font-extrabold">5 GBP</div>
                                        </div>
                                        <div class="text-xs bg-gray-300 text-gray-700 rounded-full px-2 py-1 inline-block mt-1 select-none">Monthly</div>
                                    </div>
                                </div>
                            
                                <h3 class="font-semibold text-md leading-tight">Using Storytelling to Engage Food Deli...</h3>
                                <div class="flex items-center justify-between gap-3 mt-4 text-sm font-semibold text-gray-700">
                                    <div class="flex gap-4 items-center">
                                        <div class="flex items-center gap-1">
                                            <img src=" {{ asset("asserts/dashboard/person.png") }}" alt="">
                                            50
                                        </div>
                                        <div class="bg-[#d1f0d9] text-[#2f6a44] rounded-full w-7 h-7 flex items-center justify-center">
                                            <i class="fas fa-check text-[10px]"></i>
                                        </div>
                                    </div>
                                    <div class="flex gap-4">
                                        <button aria-label="Link button" class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-link"></i>
                                        </button>
                                        <button aria-label="Next button" class="bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-300 transition">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </section>
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