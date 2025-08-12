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
            <h1 class="md:text-2xl text-[8px] font-extrabold leading-tight flex-1 md:min-w-[200px]">
                Subscriptions
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="w-full">
                    <!-- Top Stats -->
                    <section class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-2 md:gap-4">
                            <button aria-label="Go back"
                                class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200">
                                <i class="fas fa-chevron-left text-gray-700 text-sm md:text-lg"></i>
                            </button>
                            <h1 class="font-semibold md:text-xl text-md text-gray-900">Edit Subscription details</h1>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-2">
                                <span class="w-3.5 h-3.5 rounded-full bg-green-800 block"></span>
                                <span class="text-sm text-gray-800">Active</span>
                            </div>
                            <button type="button"
                                class="flex items-center gap-2 rounded-lg border border-blue-300 bg-blue-100 px-4 py-2 text-blue-700 text-sm font-semibold hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <i class="fas fa-copy"></i>
                                <span class='hidden md:flex'> Copy Link</span>
                            </button>
                        </div>
                    </section>
                    <!-- Main Content -->
                    <main class="flex flex-col lg:flex-row gap-10">
                        <!-- Left form section -->
                        <section class="flex-1 max-w-lg">
                            <p class="mb-6 text-sm text-gray-500">
                                Create, edit and track subscriptions all in one place.
                            </p>

                            <form method="POST" action="{{ route('subscriptions.update', $subscription->id) }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                @method('PUT')
                            

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
                                    <label for="cover-image" class="block mb-2 text-sm font-medium text-gray-700">Add a cover image</label>
                                    <div class="flex flex-wrap gap-2 mb-2 text-xs text-gray-500">
                                        <span class="inline-block border border-gray-300 rounded px-2 py-0.5">File type: .png and .jpg</span>
                                        <span class="inline-block border border-gray-300 rounded px-2 py-0.5">Max file size: 5MB</span>
                                        <span class="inline-block border border-gray-300 rounded px-2 py-0.5">Dimensions: 1600 px by 300 px</span>
                                    </div>
                                
                                    @if ($subscription->cover_image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $subscription->cover_image) }}" alt="Cover Image"
                                                class="w-full h-32 object-cover rounded-lg border border-gray-300" />
                                        </div>
                                    @endif
                                
                                    <label for="cover-image"
                                        class="relative flex items-center justify-center h-20 rounded-lg border-2 border-dashed border-gray-300 text-gray-600 cursor-pointer hover:border-gray-400">
                                        <span
                                            class="absolute top-2 left-2 text-xs text-gray-400 select-none pointer-events-none">Drag and drop here or</span>
                                        <button type="button"
                                            class="ml-24 flex items-center gap-2 rounded-full bg-blue-700 px-4 py-1 text-white text-sm font-semibold hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <i class="fas fa-folder-open"></i> Browse
                                        </button>
                                        <input type="file" id="cover-image" name="cover_image" accept=".png,.jpg"
                                            class="absolute inset-0 opacity-0 cursor-pointer" />
                                    </label>
                                </div>
                                

                                <div>
                                    <label for="title"
                                        class="block mb-2 text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" id="title" name="title"
                                    value="{{ old('title', $subscription->title) }}"
                                        class="w-full rounded border border-gray-300 px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>

                                <div>
                                    <label for="interval"
                                        class="block mb-2 text-xs font-semibold text-gray-500 uppercase">Subscription
                                        interval</label>
                                    <select id="interval" name="subscription_interval"
                                        class="w-full rounded border border-gray-300 px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="monthly" {{ $subscription->subscription_interval == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="weekly" {{ $subscription->subscription_interval == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="yearly" {{ $subscription->subscription_interval == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="amount"
                                        class="block mb-2 text-sm font-medium text-gray-700">Amount</label>
                                    <input type="number" id="amount" name="amount" value="{{ old('amount', $subscription->amount) }}"
                                        class="w-full rounded border border-gray-300 px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                </div>

                                {{-- <div>
                                    <label for="currency"
                                        class="block mb-2 text-xs font-semibold text-gray-500 uppercase">Currency</label>
                                    <select id="currency" name="currency"
                                        class="w-full rounded border border-gray-300 px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                                        <option value="GBP" selected>
                                            🇬🇧 British Pound
                                        </option>
                                        <option value="USD">🇺🇸 US Dollar</option>
                                        <option value="EUR">🇪🇺 Euro</option>
                                    </select>
                                </div> --}}
                                <div>
                                    <label for="currency" class="block mb-2 text-xs font-semibold text-gray-500 uppercase">Currency</label>
                                
                                    <div class="relative">
                                        <!-- Button that shows selected currency -->
                                        <button id="currencyDropdownButton" type="button"
                                            class="w-full flex items-center justify-between border border-gray-300 px-4 py-2 text-sm text-gray-900 bg-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <div id="selectedCurrency" class="flex items-center gap-2">
                                                <img src="https://flagcdn.com/w40/{{ strtolower($subscription->country_code ?? 'gb') }}.png"
                                                     alt="{{ $subscription->currency }} flag" class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                                                <span>{{ $subscription->currency }} ({{ $subscription->currency }})</span>
                                            </div>
                                            
                                            <svg class="w-4 h-4 ml-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                
                                        <!-- Dropdown content -->
                                        <div id="currencyDropdown" class="hidden absolute z-10 w-full bg-white border border-gray-200 rounded shadow mt-1 max-h-60 overflow-y-auto">
                                            @foreach ($countries as $country)
                                                <div class="currency-option flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-gray-100"
                                                    data-value="{{ $country['currency_code'] }}"
                                                    data-label="{{ $country['currency'] }} ({{ $country['currency_code'] }})"
                                                    data-flag="https://flagcdn.com/w40/{{ strtolower($country['code']) }}.png"
                                                    data-alt="{{ $country['name'] }} flag">
                                                    <img src="https://flagcdn.com/w40/{{ strtolower($country['code']) }}.png"
                                                        alt="{{ $country['name'] }} flag"
                                                        class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                                                    <span>{{ $country['currency'] }} ({{ $country['currency_code'] }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                
                                        <!-- Hidden input -->
                                        <input type="hidden" id="currency" name="currency" value="{{ old('currency', $subscription->currency) }}">
                                    </div>
                                </div>
                                
                                
                                
                                

                            

                                <div>
                                    <label for="visibility"
                                        class="block mb-2 text-xs font-semibold text-gray-500 uppercase">Visibility</label>
                                    <select id="visibility" name="visibility"
                                        class="w-full rounded border border-gray-300 px-4 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="public" {{ $subscription->visibility == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ $subscription->visibility == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>

                                <div class="flex gap-6">
                                    <button type="submit"
                                        class="rounded-full bg-blue-200 text-blue-800 px-5 py-2 text-sm font-semibold hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        Update Subscription
                                    </button>
                                    <button type="button"
                                        class="rounded-full bg-red-100 text-red-600 px-5 py-2 text-sm font-semibold hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-400">
                                        Delete Subscription
                                    </button>
                                </div>
                            </form>
                        </section>

                        <!-- Right subscribers section -->
                        <section
                            class="flex-1 md:max-w-xl bg-gray-50 w-full rounded-2xl p-4 md:p-6 flex flex-col overflow-x-auto">
                            <div class="flex flex-col md:flex-row w-full items-center justify-between mb-6 gap-y-4">
                                <h2 class="font-semibold text-gray-900">Subscribers</h2>
                                <div class="flex items-center w-full justify-center items-center">
                                    <button type="button"
                                        class="rounded-l-full border w-[30vw] md:w-[5vw] border-gray-300 bg-white px-4 py-1 text-sm font-semibold text-gray-900 shadow-sm"
                                        aria-pressed="true">
                                        All
                                    </button>
                                    <button type="button"
                                        class="rounded-none w-[30vw] md:w-[5vw] border-t border-b border-gray-300 bg-gray-100 px-4 py-1 text-sm text-gray-500"
                                        aria-pressed="false">
                                        Active
                                    </button>
                                    <button type="button"
                                        class="rounded-r-full w-[30vw] md:w-[5vw] border text-center border-gray-300 bg-gray-100 px-4 py-1 text-sm text-gray-500"
                                        aria-pressed="false">
                                        Expired
                                    </button>
                                </div>
                                <button type="button"
                                    class="flex items-center gap-2 rounded-lg border  text-center border-blue-300 bg-white px-4 py-1 text-blue-700 text-[10px] font-semibold hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <i class="fas fa-file-export"></i> Export CSV
                                </button>
                            </div>

                            <div class="md:flex-1 md:overflow-y-auto  w-full overflow-x-auto space-y-3 pr-2"
                                style="max-height: 600px;" tabindex="0">
                                <!-- Subscriber item template repeated 10 times -->
                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">

                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>

                                    <span class="font-semibold text-gray-900  text-[10px] ">Marvin
                                        McKinney</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                                            aria-label="Inactive status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Annette
                                        Black</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Devon Lane</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Jacob Jones</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Kristin
                                        Watson</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Jerome Bell</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                                            aria-label="Inactive status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Wade Warren</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                                            aria-label="Active status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Ralph
                                        Edwards</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">

                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                                            aria-label="Inactive status"></span>
                                    </span>

                                    <span class="font-semibold text-gray-900  text-[10px] ">Leslie
                                        Alexander</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>

                                <div
                                    class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                    <span class="bg-[#F3F3F3] p-2 rounded-md">
                                        <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                                            aria-label="Inactive status"></span>
                                    </span>
                                    <span class="font-semibold text-gray-900  text-[10px] ">Brooklyn
                                        Simmons</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                                    <span
                                        class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
                                </div>
                            </div>
                        </section>
                    </main>
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
        const dropdownButton = document.getElementById('currencyDropdownButton');
        const dropdown = document.getElementById('currencyDropdown');
        const options = dropdown.querySelectorAll('.currency-option');
        const selectedDisplay = document.getElementById('selectedCurrency');
        const hiddenInput = document.getElementById('currency');

        dropdownButton.addEventListener('click', () => {
            dropdown.classList.toggle('hidden');
        });

        options.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value');
                const label = option.getAttribute('data-label');
                const flag = option.getAttribute('data-flag');
                const alt = option.getAttribute('data-alt');

                selectedDisplay.innerHTML = `
                    <img src="${flag}" alt="${alt}" class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                    <span>${label}</span>
                `;

                hiddenInput.value = value;
                dropdown.classList.add('hidden');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!dropdownButton.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
<script>
    function previewCoverImage(event) {
        const input = event.target;
        const preview = document.getElementById('coverImagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

    
</body>

</html>