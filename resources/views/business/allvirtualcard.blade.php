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
                    <a href="{{ route('virtualCard') }}">
                        <button
                            class="flex items-center gap-2 bg-[#215F9C] text-white text-sm font-medium rounded-full px-5 py-2"
                            type="button">
                            <i class="fas fa-credit-card"></i> New Virtual Card
                        </button>
                    </a>

                </div>
                <!-- 
                <div class="flex flex-col items-center justify-center mt-24 space-y-4 text-center text-gray-700">
                    <i class="fas fa-credit-card text-gray-400 text-2xl"></i>
                    <p class="text-sm">Create your first virtual card</p>
                    <button class="bg-gray-100 text-gray-700 text-sm rounded-full px-6 py-2 hover:bg-gray-200"
                        type="button">
                        Create Virtual Card
                    </button>
                </div> -->


                <section class="grid grid-cols-1 md:grid-cols-3 space-y-4 md:space-x-4 justify-between items-center w-full">
                
@foreach ($cards as $card)
    <section class="flex flex-col rounded-2xl border border-gray-200 max-w-[492px] w-full">
        <div class="p-2">
            @php
                $bgImage = match($card->currency) {
                    'NGN' => asset('../../asserts/dashboard/virtualCard1.png'),
                    'USD' => asset('../../asserts/dashboard/virtualCard2.png'),
                    'GBP' => asset('../../asserts/dashboard/virtualCard3.png'),
                    default => asset('../../asserts/dashboard/virtualCard1.png'),
                };

                $currencySymbol = match($card->currency) {
                    'NGN' => '₦',
                    'USD' => '$',
                    'GBP' => '£',
                    default => $card->currency
                };
            @endphp
            <img src="{{ $bgImage }}" alt="Virtual Card">
        </div>

        <div class="p-4 space-y-3">
            {{-- Hidden Details --}}
            <div class="hidden-details hidden">
                <div class="flex justify-between items-center text-gray-600 font-sans font-medium text-sm">
                    <p>Balance</p>
                    <div class="flex items-center gap-2">
                        <span class="text-gray-400 font-normal text-lg">{{ $currencySymbol }}{{ $card->balance }}</span>
                        <button aria-label="More options" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center text-gray-600 font-sans font-medium text-sm">
                    <p>Card Number</p>
                    <p class="tracking-widest" id="card-number-{{ $card->id }}">{{ $card->card_number }}</p>
                </div>


                <div class="flex justify-between items-center text-gray-600 font-sans font-medium text-sm">
                    <p>CVV</p>
                    <p>{{ $card->cvv }}</p>
                </div>
            </div>

            {{-- Always Visible --}}
            <div class="flex justify-between items-center text-gray-600 font-sans font-medium text-sm">
                <p>Expiry</p>
                <p>{{ $card->expiry_month }}/{{ $card->expiry_year }}</p>
            </div>

            <div class="flex justify-between mt-4 gap-3">
                <button type="button"
                        onclick="toggleDetails(this)"
                        class="flex items-center justify-center gap-2 w-full rounded-md border border-gray-300 py-2 text-gray-600 text-sm font-sans hover:bg-gray-100">
                    <i class="far fa-eye"></i> Show
                </button>
                <button type="button"
                        onclick="copyCardNumber('card-number-{{ $card->id }}')"
                        class="flex items-center justify-center gap-2 w-full rounded-md border border-gray-300 py-2 text-gray-600 text-sm font-sans hover:bg-gray-100">
                    <i class="far fa-copy"></i> Copy
                </button>

                <form id="delete-form-{{ $card->id }}" method="POST"
                    action="{{ route('virtualCard.destroy', $card->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            onclick="confirmDelete('{{ $card->id }}')"
                            class="flex items-center justify-center gap-2 w-full rounded-md border border-gray-300 py-2 text-gray-600 text-sm font-sans hover:bg-gray-100">
                        <i class="far fa-trash-alt"></i> Delete
                    </button>
                </form>

            </div>
        </div>
    </section>
@endforeach


                </section>



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
                        <form class="space-y-4">

                            <div>
                                <img src="../../asserts/dashboard/VirtualCard.png" alt="">
                            </div>

                            <div class="mb-4">

                                <label class="flex flex-col text-[#828282] text-md gap-1">
                                    Currency
                                    <select
                                        class="border border-gray-300  p-2 rounded-2xl text-black flex items-center focus:outline-none focus:ring-1 focus:ring-blue-300">
                                        <option class="flex items-center" selected>
                                            Us Dollar
                                        </option>
                                    </select>
                                </label>
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
    function toggleDetails(button) {
        const section = button.closest('section');
        const hiddenDetails = section.querySelector('.hidden-details');
        const isHidden = hiddenDetails.classList.contains('hidden');

        hiddenDetails.classList.toggle('hidden', !isHidden);
        button.innerHTML = isHidden
            ? '<i class="far fa-eye-slash"></i> Hide'
            : '<i class="far fa-eye"></i> Show';
    }
</script>
    <script>
    function copyCardNumber(id) {
        const element = document.getElementById(id);
        if (element) {
            const text = element.textContent.trim();
            navigator.clipboard.writeText(text).then(() => {
                alert('Card number copied!');
            }).catch(() => {
                alert('Failed to copy card number.');
            });
        }
    }
    </script>
<script>
    function confirmDelete(cardId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This card will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + cardId).submit();
            }
        });
    }
</script>



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