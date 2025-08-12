@include('business.head')


<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    @include('business.header')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Sidebar -->
    @include('business.sidebar')

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Dashboard
            </h1>
            @include('business.header_notifical')   
        </header>
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[100vw] mx-auto">
                    <section class="bg-white text-gray-900 p-6 md:p-4 w-full">
                        <div class="max-w-[100vw] mx-auto">
                            <div
                                class="flex flex-col md:flex-row md:items-center md:justify-between w-full md:gap-0 mb-10">
                                <div>
                                    <p class="text-gray-500 text-sm mb-1">Total Balance</p>
                                    <h1 class="font-extrabold text-2xl md:text-xl">
                                        {{-- {{ $balance }}{{ number_format($balance) }} --}}
                                    </h1>
                                </div>
                                
                                
                                
                                <div class="flex flex-col md:flex-row  mt-10 md:mt-0 gap-6 md:gap-2">
                                    <a href="{{ route('send') }}">
                                        <button
                                            class="flex items-center gap-1 rounded-full border border-blue-300 bg-blue-100 px-5 py-2 text-blue-700 text-sm md:text-sm font-medium hover:bg-blue-200 transition">
                                            <i class="fas fa-file-invoice text-xs"></i>
                                            Send Money
                                        </button>
                                    </a>
                                    
                                    <button
                                        class="flex items-center gap-1 rounded-full border border-gray-300 bg-white px-5 py-2 text-gray-900 text-sm md:text-sm font-medium hover:bg-gray-50 transition">
                                        <i class="fas fa-cube text-xs"></i>
                                        Exchange
                                    </button>
                                    <button
                                        class="flex items-center gap-1 rounded-full border border-gray-300 bg-white px-5 py-2 text-gray-900 text-sm md:text-sm font-medium hover:bg-gray-50 transition">
                                        <i class="far fa-file-alt text-xs"></i>
                                        Add Money
                                    </button>
                                    {{-- <button
                                        class="flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2 text-gray-900 text-sm md:text-sm font-medium hover:bg-gray-50 transition">
                                        <i class="fas fa-arrow-up-right-from-square"></i>
                                        Send Money
                                    </button> --}}
                                </div>
                            </div>
                            {{-- @php
                            $showMore = count($balance) > 0;
                        @endphp --}}
                        
                        <section class="mb-10">
                            <div class="flex justify-between items-center mb-5">
                                <h2 class="font-semibold text-lg">Your Balances</h2>
                                    <a href="{{ route('add_account.create') }}"
                                        class="text-sm px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition"
                                    >
                                        Add Balance
                                    </a>
                            </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5">

                        {{-- Default balance from users table --}}
                        {{-- <div class="border border-gray-200 rounded-xl p-5 flex flex-col justify-between">
                            <div class="flex items-center gap-2 mb-3">
                                <img 
                                    src="https://flagcdn.com/w20/{{ strtolower($flagCountry) }}.png" 
                                    alt="Flag of {{ strtoupper($flagCountry) }}"
                                    width="20" height="15" class="rounded-sm"
                                />
                                <span class="text-sm font-medium uppercase">{{ $defaultCurrency }}</span>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-1">Main Wallet</p>
                                <p class="font-semibold text-lg">
                                    {{ $currencySymbol }}{{ number_format($defaultBalance, 2) }}
                                </p>
                            </div>
                        </div> --}}

                        {{-- Loop through balances table --}}
                        @foreach($balances as $balance)
                            <div class="border border-gray-200 rounded-xl p-5 flex flex-col justify-between">
                                <div class="flex items-center gap-2 mb-3">
                                   <img 
                                        src="https://flagcdn.com/w20/{{ strtolower($balance->currency_meta['country']) }}.png" 
                                        alt="Flag of {{ strtoupper($balance->currency_meta['country']) }}"
                                        width="20" height="15" class="rounded-sm"
                                    />

                                    <span class="text-sm font-medium uppercase">{{ $balance->currency }}</span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">{{ $balance->name }}</p>
                                    <p class="font-semibold text-lg">
                                        {{ $balance->currency_meta['symbol'] }}{{ number_format($balance->amount, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach

                    </div>





                        
                            {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-5">
                                @foreach(collect($balances)->take(5) as $balance)
                                    <div class="border border-gray-200 rounded-xl p-5 flex flex-col justify-between">
                                        <div class="flex items-center gap-2 mb-3">
                                            <img 
                                                src="https://flagcdn.com/w20/{{ strtolower($balance['country']) }}.png" 
                                                alt="Flag of {{ strtoupper($balance['country']) }}"
                                                width="20" height="15" class="rounded-sm"
                                            />
                                            <span class="text-sm">{{ $balance['currency'] }}</span>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">Balance</p>
                                            <p class="font-semibold text-lg">
                                                {{ $balance['symbol'] }}{{ number_format($balance['balance'], 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div> --}}
                        </section>
                        
                        

                            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="font-semibold text-lg">Transactions</h3>
                                        <button
                                            class="flex items-center gap-1 text-gray-700 text-sm font-semibold hover:text-gray-900">
                                            See All
                                            <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="space-y-3 bg-gray-50 rounded-xl p-4 md:p-6 max-w-full overflow-x-auto">
                                        @forelse ($transactions as $tx)
                                        <div class="flex items-center justify-between bg-white rounded-lg p-3 md:p-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="flex items-center justify-center w-10 h-10 rounded-lg 
                                                    {{ $tx->transaction_type === 'payment' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                                    <i class="fas {{ $tx->transaction_type === 'payment' ? 'fa-arrow-up-right' : 'fa-arrow-down-left' }}"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">{{ $tx->sender ?? 'No name' }}</p>
                                                    <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($tx->created_at)->format('M j, Y') }}</p>
                                                </div>
                                            </div>
                                            <p class="font-semibold text-sm md:text-base">{{ number_format($tx->amount, 2) }} {{ $tx->currency }}</p>
                                        </div>
                                        @empty
                                        <p class="text-gray-400">No transactions yet.</p>
                                        @endforelse
                                    </div>
                                    
                                </div>

                                <div>
                                    <h3 class="font-semibold text-lg mb-5">Monthly Income</h3>
                                    <div class="border border-gray-200 rounded-xl p-5 max-w-full overflow-x-auto">
                                        <div class="flex justify-between items-center mb-4">
                                            <select aria-label="Select quarter"
                                                class="text-xs border border-gray-300 rounded-full py-1 px-3 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                <option>1st quarter</option>
                                                <option>2nd quarter</option>
                                                <option>3rd quarter</option>
                                                <option>4th quarter</option>
                                            </select>
                                            <span class="font-semibold text-sm">GBP</span>
                                        </div>
                                        <svg viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="w-full h-[200px]" aria-label="Monthly income line chart">
                                            <defs>
                                                <linearGradient id="gradient" x1="0" y1="0" x2="0" y2="1">
                                                    <stop offset="0%" stop-color="#34A853" stop-opacity="0.2" />
                                                    <stop offset="100%" stop-color="#34A853" stop-opacity="0" />
                                                </linearGradient>
                                            </defs>
                                            <path
                                                d="M0 200 L50 200 L70 150 L90 170 L110 130 L130 140 L150 120 L170 130 L190 110 L210 120 L230 100 L250 110 L270 90 L290 100 L310 80 L330 50 L350 50 L400 50 L400 200 Z"
                                                fill="url(#gradient)" stroke="#34A853" stroke-width="2" />
                                            <circle cx="310" cy="80" r="7" fill="#fff" stroke="#34A853"
                                                stroke-width="2" />
                                            <line x1="310" y1="80" x2="250" y2="80" stroke="#000" stroke-width="1"
                                                stroke-linecap="round" />
                                            <rect x="190" y="70" width="60" height="25" rx="5" ry="5" fill="#000"
                                                opacity="0.85" />
                                            <text x="220" y="88" fill="#fff" font-size="12"
                                                font-family="Inter, sans-serif" font-weight="600" text-anchor="middle">
                                                24,400
                                            </text>
                                            <text x="0" y="195" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                January
                                            </text>
                                            <text x="150" y="195" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                February
                                            </text>
                                            <text x="350" y="195" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                March
                                            </text>
                                            <text x="380" y="15" fill="#111827" font-size="12"
                                                font-family="Inter, sans-serif" font-weight="700" text-anchor="end">
                                                GBP
                                            </text>
                                            <text x="10" y="180" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                0
                                            </text>
                                            <text x="10" y="140" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                1k
                                            </text>
                                            <text x="10" y="100" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                10k
                                            </text>
                                            <text x="10" y="60" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                20k
                                            </text>
                                            <text x="10" y="20" fill="#6B7280" font-size="10"
                                                font-family="Inter, sans-serif">
                                                50k
                                            </text>
                                        </svg>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>

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
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('toggleBalances');
            let expanded = false;
    
            toggleBtn?.addEventListener('click', () => {
                document.querySelectorAll('.more-balance').forEach(el => {
                    el.classList.toggle('hidden');
                });
                expanded = !expanded;
                toggleBtn.textContent = expanded ? 'See Less' : 'See More';
            });
        });
    </script>
    
</body>

</html>