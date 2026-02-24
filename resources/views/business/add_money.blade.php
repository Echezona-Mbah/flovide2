@include('business.head')


<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                {{ __('Add Money') }}
            </h1>
                 @include('business.header_notifical')
        </header>
        <section class="relative w-full">
            <section class="bg-white text-gray-700 min-h-screen md:rounded-3xl p-2 shadow-md md:absolute w-full overflow-x-hidden ">

                <section class="bg-white min-h-screen flex flex-col items-center justify-start p-6">
                    <!-- Progress bar -->
                    <div class="w-full max-w-3xl flex justify-between items-center mb-16 select-none">

                    </div>

                    <!-- Choose wallet -->
                    <div class="text-center mb-12 max-w-xs w-full">
                        <p class="font-semibold text-sm mb-3">{{ __('Choose which wallet balance to top-up') }}</p>
                        <button
                            class="inline-flex items-center gap-1 bg-gray-200 rounded-md px-3 py-1 text-sm font-medium text-gray-800">
                            <img src="https://flagcdn.com/w20/gb.png" alt="UK flag" class="w-5 h-5 rounded-sm" />
                            GBP
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Amount input styled as display -->
                    <div class="text-center mb-16">
                        <input id="amountInput" type="text" min="0" inputmode="decimal" placeholder="0.00"
                            class="text-6xl font-extrabold text-gray-600 text-center md:w-[30vw] w-full bg-transparent border-none outline-none focus:ring-0 appearance-none" />
                    </div>

                    <!-- Info box -->
                    <div class="w-full max-w-md border border-gray-300 rounded-lg overflow-hidden mb-16 select-none"
                        role="region" aria-label="Arrival time and fees information">
                        <div class="flex items-center gap-4 border-b border-gray-300 px-5 py-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-700"
                                aria-hidden="true">
                                <i class="fas fa-stopwatch text-lg"></i>
                            </div>
                            <div class="flex justify-between w-full font-semibold text-sm">
                                <span>{{ __('Arrival time') }}</span>
                                <span class="font-normal">{{ __('Today - in seconds') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 px-5 py-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-700"
                                aria-hidden="true">
                                <i class="fas fa-file-invoice-dollar text-lg"></i>
                            </div>
                            <div class="flex justify-between w-full font-semibold text-sm">
                                <span>{{ __('Fees') }}</span>
                                <span class="font-normal">£ 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- Info box -->
                    <div id="infoBox"
                        class="max-w-md w-full border border-green-300 rounded-md bg-green-50 text-green-600 px-4 py-2 mb-6 flex items-center gap-2 text-sm select-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ __('Enter the amount you wish to add to continue') }}</span>
                    </div>

                    <!-- Continue button -->
                    <button id="continueBtn" disabled
                        class="max-w-md w-full bg-gray-300 text-gray-600 font-semibold rounded-full py-3 cursor-not-allowed">
                        {{ __('Continue') }}
                    </button>
                </section>
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


        const amountInput = document.getElementById('amountInput');
        const continueBtn = document.getElementById('continueBtn');
        const infoBox = document.getElementById('infoBox');

        function formatNumberWithCommas(numStr) {
            const [intPart, decimalPart] = numStr.split('.');
            const withCommas = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return decimalPart !== undefined ? `${withCommas}.${decimalPart}` : withCommas;
        }

        amountInput.addEventListener('input', (e) => {
            const selectionStart = amountInput.selectionStart;
            const rawValue = amountInput.value.replace(/,/g, '');

            // Only allow numbers and one dot
            let cleaned = rawValue.replace(/[^0-9.]/g, '');
            const parts = cleaned.split('.');
            if (parts.length > 2) {
                cleaned = parts[0] + '.' + parts.slice(1).join('');
            }

            // Format with commas
            const beforeLength = cleaned.length;
            const formatted = formatNumberWithCommas(cleaned);
            amountInput.value = formatted;

            // Restore cursor position
            const afterLength = formatted.length;
            amountInput.selectionStart = amountInput.selectionEnd =
                selectionStart + (afterLength - beforeLength);

            // Button toggle logic
            const value = parseFloat(cleaned) || 0;
            if (value > 0) {
                continueBtn.disabled = false;
                continueBtn.classList.remove('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
                continueBtn.classList.add('bg-blue-600', 'text-white', 'cursor-pointer');
                infoBox.style.display = 'none';
            } else {
                continueBtn.disabled = true;
                continueBtn.classList.add('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
                continueBtn.classList.remove('bg-blue-600', 'text-white', 'cursor-pointer');
                infoBox.style.display = 'flex';
            }
        });

        amountInput.addEventListener('blur', () => {
            let value = parseFloat(amountInput.value.replace(/,/g, ''));
            if (!isNaN(value) && value > 0) {
                amountInput.value = formatNumberWithCommas(value.toFixed(2));
            } else {
                amountInput.value = '';
            }
        });


    </script>
</body>

</html>