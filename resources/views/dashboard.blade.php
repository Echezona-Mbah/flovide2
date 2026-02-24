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
    <main class=" flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                {{ __('Dashboard') }}
            </h1>
            @include('business.header_notifical')   
        </header>
        <section class=" relative w-full">
        @if (!auth()->user()->isFullyVerified())
        <div class="relative overflow-hidden rounded-xl border border-yellow-300 bg-yellow-50 p-5 mb-6">
            
            <!-- soft background accent -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full opacity-30"></div>

            <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Left content -->
            <div class="flex items-start gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-700">
                <i class="fas fa-shield-alt text-lg"></i>
                </div>

                <div>
                <h4 class="text-sm font-semibold text-yellow-900">
                    Account verification required
                </h4>
                <p class="text-sm text-yellow-800 mt-1 leading-relaxed">
                    For your safety and compliance, some features are temporarily unavailable.
                    Please complete your verification to unlock full access.
                </p>
                </div>
            </div>

            <!-- Action button -->
            <a href="{{ url('/compliance') }}"
                class="inline-flex items-center justify-center gap-2 bg-yellow-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-700 transition shadow-sm">
                <i class="fas fa-arrow-right"></i>
                Complete Verification
            </a>

            </div>
        </div>
        @endif



            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[100vw] mx-auto">
                    <section class="bg-white text-gray-900 p-6 md:p-4 w-full">
                        <div class="max-w-[100vw] mx-auto">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full mb-10 gap-4">

                                <!-- Balance Info -->
                                <div class="mb-4 md:mb-0">
                                    <p class="text-gray-500 text-sm mb-1">{{ __('Total Balance') }}</p>
                                    <h1 class="font-extrabold text-2xl md:text-xl">
                                        {{-- {{ $balance }}{{ number_format($balance) }} --}}
                                    </h1>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap justify-start gap-3 md:gap-2 w-full md:w-auto">
                                    <a href="{{ route('send') }}" class="flex-1 md:flex-none">
                                        <button class="w-full md:w-auto flex items-center justify-center gap-1 rounded-full border border-blue-300 bg-blue-100 px-4 py-2 text-blue-700 text-sm font-medium hover:bg-blue-200 transition">
                                            <i class="fas fa-file-invoice text-xs"></i>
                                            {{ __('Send Money') }}
                                        </button>
                                    </a>

                                    <button class="flex-1 md:flex-none w-full md:w-auto flex items-center justify-center gap-1 rounded-full border border-gray-300 bg-white px-4 py-2 text-gray-900 text-sm font-medium hover:bg-gray-50 transition">
                                        <i class="fas fa-cube text-xs"></i>
                                        {{ __('Exchange') }}
                                    </button>

                                    <a href="{{ route('add_money') }}" class="flex-1 md:flex-none w-full md:w-auto">
                                        <button class="w-full md:w-auto flex items-center justify-center gap-1 rounded-full border border-gray-300 px-4 py-2 text-sm font-medium transition
                                            {{ request()->routeIs('add_money')
                                                ? 'bg-gray-100 text-gray-900'
                                                : 'bg-white text-gray-900 hover:bg-gray-50' }}">
                                            <i class="far fa-file-alt text-xs"></i>
                                            {{ __('Add Money') }}
                                        </button>
                                    </a>
                                </div>
                            </div>



                            {{-- @php
                            $showMore = count($balance) > 0;
                        @endphp --}}
                        
                        <section class="mb-10">
                            <div class="flex justify-between items-center mb-5">
                                <h2 class="font-semibold text-lg">{{ __('Your Balances') }}</h2>
                                    <a href="{{ route('add_account.create') }}"
                                        class="text-sm px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition"
                                    >
                                        {{ __('Add Balance') }}
                                    </a>
                            </div>

                            <div
                                class="flex gap-4 overflow-x-auto pb-2
                                    sm:grid sm:grid-cols-2
                                    lg:grid-cols-5
                                    sm:overflow-visible">

                                @foreach($balances as $balance)
                                    <div
                                        class="min-w-[260px] sm:min-w-0
                                            border border-gray-200 rounded-xl p-5
                                            bg-white shadow-sm
                                            hover:shadow-md transition">

                                        <!-- HEADER -->
                                        <div class="flex items-center gap-2 mb-3">
                                            <img
                                                src="https://flagcdn.com/w20/{{ strtolower($balance->currency_meta['country']) }}.png"
                                                alt="Flag of {{ strtoupper($balance->currency_meta['country']) }}"
                                                width="20" height="15"
                                                class="rounded-sm"
                                            />
                                            <span class="text-sm font-semibold uppercase tracking-wide">
                                                {{ $balance->currency }}
                                            </span>
                                        </div>

                                        <!-- BODY -->
                                        <p class="text-xs text-gray-400 mb-1">
                                            {{ $balance->name }}
                                        </p>

                                        <p class="text-xl font-bold text-gray-900">
                                            {{ $balance->currency_meta['symbol'] }}{{ number_format($balance->amount, 2) }}
                                        </p>

                                    </div>
                                @endforeach

                            </div>








                    
                        </section>
                        
                        

                            <section class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                
                                <div>
                                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                                        <!-- Header -->
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="font-semibold text-lg">Exchange Rate</h3>
                                            <span id="rateText" class="text-sm text-gray-500">Loading...</span>
                                        </div>

                                        <!-- From -->
                                        <div class="bg-gray-50 rounded-xl p-4 mb-3">
                                            <div class="flex justify-between items-center">
                                                <input id="fromAmount" type="number" value="100" class="bg-transparent text-2xl font-bold outline-none w-1/2" />
                                                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector" data-type="from">
                                                    <img src="https://flagcdn.com/w20/gb.png" class="w-5 h-4 rounded-sm flag" />
                                                    <span class="font-medium code">GBP</span>
                                                    <i class="fas fa-chevron-down text-xs"></i>

                                                    <!-- Dropdown -->
                                                    <!-- Inside each .currency-dropdown -->
<div class="currency-dropdown hidden absolute bg-white border rounded shadow mt-1 z-50 max-h-48 overflow-y-auto p-2">
    <input type="text" class="currency-search w-full border rounded px-2 py-1 mb-2 text-sm" placeholder="Search..." />
    @foreach($allCurrencies as $c)
        <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" 
             data-code="{{ $c['code'] }}" 
             data-flag="{{ $c['flag'] }}">
            <img src="{{ $c['flag'] }}" class="w-5 h-4 rounded-sm" /> {{ $c['country_name'] }} {{ $c['code'] }}
        </div>
    @endforeach
</div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Swap button -->
                                        <div class="flex justify-center my-2">
                                            <button class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white shadow hover:bg-blue-700 transition swap-btn">
                                                <i class="fas fa-exchange-alt text-sm"></i>
                                            </button>
                                        </div>

                                        <!-- To -->
                                        <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                            <div class="flex justify-between items-center">
                                                <input id="toAmount" type="number" value="0" class="bg-transparent text-2xl font-bold outline-none w-1/2" readonly/>
                                                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-full border cursor-pointer currency-selector " data-type="to">
                                                    <img src="https://flagcdn.com/w20/ng.png" class="w-5 h-4 rounded-sm flag" />
                                                    <span class="font-medium code">NGN</span>
                                                    <i class="fas fa-chevron-down text-xs"></i>

                                                    <!-- Dropdown -->
                                                    <!-- Inside each .currency-dropdown -->
<div class="currency-dropdown hidden absolute bg-white border rounded shadow mt-1 z-50 max-h-48 overflow-y-auto p-2">
    <input type="text" class="currency-search w-full border rounded px-2 py-1 mb-2 text-sm" placeholder="Search..." />
    @foreach($allCurrencies as $c)
        <div class="currency-item flex items-center gap-2 px-3 py-1 cursor-pointer hover:bg-gray-100" 
             data-code="{{ $c['code'] }}" 
             data-flag="{{ $c['flag'] }}">
            <img src="{{ $c['flag'] }}" class="w-5 h-4 rounded-sm" /> {{ $c['country_name'] }} {{ $c['code'] }}
        </div>
    @endforeach
</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Chart placeholder -->
                                        <div class="h-32 bg-gray-50 rounded-xl flex items-center justify-center text-sm text-gray-400">
                                            Exchange rate trend chart
                                        </div>
                                    </div>

                                </div>


                                <div>
                                    <div class="flex items-center justify-between mb-5">
                                        <h3 class="font-semibold text-lg">{{ __('Transactions') }}</h3>
                                        <button
                                            class="flex items-center gap-1 text-gray-700 text-sm font-semibold hover:text-gray-900">
                                            {{ __('See All') }}
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
                                        <p class="text-gray-400">{{ __('No transactions yet.') }}</p>
                                        @endforelse
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



        // document.addEventListener('DOMContentLoaded', () => {
        //     const swapBtn = document.querySelector('.swap-btn');
        //     const selectors = document.querySelectorAll('.currency-selector');

        //     // Toggle dropdown
        //     selectors.forEach(sel => {
        //         sel.addEventListener('click', e => {
        //             e.stopPropagation();
        //             const dropdown = sel.querySelector('.currency-dropdown');
        //             dropdown.classList.toggle('hidden');
        //         });

        //         // Select currency from dropdown
        //         sel.querySelectorAll('.currency-item').forEach(item => {
        //             item.addEventListener('click', e => {
        //                 const code = item.dataset.code;
        //                 const flag = item.dataset.flag;

        //                 sel.querySelector('.code').textContent = code;
        //                 sel.querySelector('.flag').src = flag;

        //                 sel.querySelector('.currency-dropdown').classList.add('hidden');
        //             });
        //         });
        //     });

        //     // Swap currencies and flags
        //     swapBtn.addEventListener('click', () => {
        //         const from = document.querySelector('.currency-selector[data-type="from"]');
        //         const to = document.querySelector('.currency-selector[data-type="to"]');

        //         // Swap code
        //         const tempCode = from.querySelector('.code').textContent;
        //         from.querySelector('.code').textContent = to.querySelector('.code').textContent;
        //         to.querySelector('.code').textContent = tempCode;

        //         // Swap flag
        //         const tempFlag = from.querySelector('.flag').src;
        //         from.querySelector('.flag').src = to.querySelector('.flag').src;
        //         to.querySelector('.flag').src = tempFlag;
        //     });

        //     // Close dropdown if clicked outside
        //     document.addEventListener('click', () => {
        //         document.querySelectorAll('.currency-dropdown').forEach(drop => drop.classList.add('hidden'));
        //     });
        // });


        
    </script>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const swapBtn = document.querySelector('.swap-btn');
    const selectors = document.querySelectorAll('.currency-selector');
    const fromAmount = document.getElementById('fromAmount');
    const toAmount = document.getElementById('toAmount');
    const rateText = document.getElementById('rateText');

    async function calculate() {
        const from = document.querySelector('.currency-selector[data-type="from"] .code').innerText;
        const to = document.querySelector('.currency-selector[data-type="to"] .code').innerText;
        const amt = parseFloat(fromAmount.value);

        if (!amt || amt <= 0) {
            toAmount.value = 0;
            rateText.innerText = '';
            return;
        }

        try {
            const res = await fetch(`/dashboard/exchange-rate?from=${from}&to=${to}`);
            const data = await res.json();

            if (data && data.rate) {
                toAmount.value = (amt * data.rate).toFixed(2);
                rateText.innerText = `1 ${from} = ${data.rate} ${to}`;
            }
        } catch (err) {
            console.error(err);
            rateText.innerText = 'Error fetching rate';
        }
    }

    selectors.forEach(sel => {
        const dropdown = sel.querySelector('.currency-dropdown');
        const searchInput = sel.querySelector('.currency-search');

        // Open dropdown
        sel.addEventListener('click', e => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            searchInput?.focus();
        });

        // Handle search
        searchInput?.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            dropdown.querySelectorAll('.currency-item').forEach(item => {
                const text = item.innerText.toLowerCase();
                item.style.display = text.includes(filter) ? 'flex' : 'none';
            });
        });

        // Handle selection
        sel.querySelectorAll('.currency-item').forEach(item => {
            item.addEventListener('click', e => {
                e.stopPropagation();
                sel.querySelector('.code').textContent = item.dataset.code;
                sel.querySelector('.flag').src = item.dataset.flag;
                dropdown.classList.add('hidden');
                calculate();
            });
        });
    });

    // Swap button
    swapBtn.addEventListener('click', () => {
        const from = document.querySelector('.currency-selector[data-type="from"]');
        const to = document.querySelector('.currency-selector[data-type="to"]');

        [from.querySelector('.code').innerText, to.querySelector('.code').innerText] =
        [to.querySelector('.code').innerText, from.querySelector('.code').innerText];

        [from.querySelector('.flag').src, to.querySelector('.flag').src] =
        [to.querySelector('.flag').src, from.querySelector('.flag').src];

        calculate();
    });

    fromAmount.addEventListener('input', calculate);

    // Close all dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.currency-dropdown').forEach(drop => drop.classList.add('hidden'));
    });

    // Initial calculation
    calculate();
});
</script>


</body>

</html>