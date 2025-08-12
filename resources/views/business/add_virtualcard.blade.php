@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
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
                Dashboard
            </h1>
                        @include('business.header_notifical')

        </header>
        <section class=" relative w-full ">
            <section
                class="bg-white text-gray-700 min-h-screen md:w-[80vw]   md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden ">


                <div
                    class=" mx-auto px-6 py-8 flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                    <div class="flex flex-col md:flex-row items-center md:items-stretch gap-4 w-full md:w-auto">
                        <div
                            class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full md:w-72 text-gray-500 text-sm">
                            <i class="fas fa-search mr-3"></i>
                            <input type="text" placeholder="Search cards"
                                class="outline-none w-full bg-transparent placeholder-gray-400" />
                        </div>
                        <div class="flex border border-gray-300 rounded-full overflow-hidden text-sm select-none">
                            <button class="bg-gray-100 text-gray-900 font-semibold px-5 py-2 rounded-l-full"
                                aria-pressed="true">
                                All
                            </button>
                            <button class="px-5 py-2 text-gray-400 cursor-default">Active</button>
                            <button class="px-5 py-2 text-gray-400 cursor-default rounded-r-full">
                                Expired
                            </button>
                        </div>
                    </div>
                    <button
                        class="flex items-center gap-2 bg-[#215F9C] text-white text-sm font-medium rounded-full px-5 py-2"
                        type="button">
                        <i class="fas fa-credit-card"></i> New Virtual Card
                    </button>
                </div>

                <div class="flex flex-col items-center justify-center mt-24 space-y-4 text-center text-gray-700">
                    <i class="fas fa-credit-card text-gray-400 text-2xl"></i>
                    <p class="text-sm">Create your first virtual card</p>
                    <button class="bg-gray-100 text-gray-700 text-sm rounded-full px-6 py-2 hover:bg-gray-200"
                        type="button">
                        Create Virtual Card
                    </button>
                </div>



                <!-- modal -->

                <section
                    class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
                    id="modal" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold mb-4">New Virtual Card</p>

                            <button class=" border h-6 w-6 rounded-md flex items-center justify-center ">
                                <i class="fas fa-times  text-md cursor-pointer text-[#828282]"></i>

                            </button>
                        </div>
                        <form class="space-y-4" method="POST"  action="{{ route('virtualCard.store') }}">
                              @csrf
                                 @if ($errors->any())
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json($errors->first()),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json(session('error')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        
                                        @if (session('success'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'success',
                                                title: @json(session('success')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif
                            <div>
                                <img src="../../asserts/dashboard/VirtualCard.png" alt="">
                            </div>

                            <div class="mb-4">

                        

                                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                                        <div x-data="{open: false, selected: null, selectCurrency(flag, label, code) {this.selected = { flag, label, code }; this.open = false; }}" class="relative w-full">
                                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Currency</label>

                                            <!-- Button -->
                                            <div @click="open = !open"  class="flex items-center justify-between border border-gray-300 rounded-md p-2 cursor-pointer bg-white w-full">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="selected">
                                                        <div class="flex items-center gap-2">
                                                            <img :src="selected.flag" class="w-5 h-4" />
                                                            <span x-text="selected.label"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!selected">
                                                        <span class="text-gray-400">Select currency</span>
                                                    </template>
                                                </div>
                                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                            <!-- Dropdown -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto w-full"
                                                x-transition>
                                                @foreach ($countries as $country)
                                                    @php
                                                        $symbol = $country->currency_meta['symbol'] ?? '';
                                                        $countryCode = strtoupper($country->code ?? 'US');
                                                        $flag = "https://flagcdn.com/w20/" . strtolower($countryCode) . ".png";
                                                        $label = $symbol . ' ' . $country->name . ' (' . $country->currency . ')';
                                                        $code = $country->currency_code;
                                                    @endphp
                                                    <div @click="selectCurrency('{{ $flag }}', '{{ $label }}', '{{ $code }}')"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 cursor-pointer w-full">
                                                        <img src="{{ $flag }}" class="w-5 h-4" />
                                                        <span>{{ $label }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Hidden input for form -->
                                            <input type="hidden" name="currency" x-model="selected.code">
                                        </div>


                            </div>
                            <button type="submit"
                                class="w-full bg-[#215F9C] text-white font-semibold py-2 px-4 rounded-2xl">
                                Create Card
                            </button>
                        </form>

                    </div>

                </section>
                <!-- modal end-->

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






        document.addEventListener("DOMContentLoaded", function () {
            // Get modal element
            const modal = document.getElementById("modal");

            // Get "Create Virtual Card" button
            const createBtn = document.querySelectorAll("button");
            createBtn.forEach((btn) => {
                if (btn.textContent.trim() === "Create Virtual Card") {
                    btn.addEventListener("click", function () {
                        modal.style.display = "flex"; // Show modal
                    });
                }
            });

            // Get Close button (the one with the 'fa-times' icon)
            const closeBtn = modal.querySelector(".fa-times");
            closeBtn.addEventListener("click", function () {
                modal.style.display = "none"; // Hide modal
            });

            // Optional: Hide modal on page load
            modal.style.display = "none";
        });
    </script>
</body>

</html>