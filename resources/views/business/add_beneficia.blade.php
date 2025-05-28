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
                Payout accounts
            </h1>
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
           <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
            <section class="flex flex-col md:flex-row gap-10 mx-auto max-w-full min-h-screen">
                <!-- Left side: Table and search -->
                <section class="flex-1 overflow-auto ">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 md:gap-10">
                        <div class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full sm:w-[400px] mb-4">
                            <i class="fas fa-search text-gray-400 mr-3"></i>
                            <input type="search" id="search-input" placeholder="Search beneficiaries"
                                class="w-full text-sm text-gray-600 placeholder-gray-400 focus:outline-none" />
                        </div>
                        <a href="{{ route('add_beneficias.create') }}"
                            class="flex items-center justify-center gap-1 rounded-full bg-blue-200 text-blue-800 text-[12px] font-semibold px-4 py-2 min-w-[140px] hover:bg-blue-300 transition">
                                <i class="fas fa-plus"></i> Add Beneficiary
                            </a>

                    </div>
                    <table class="w-full border-collapse text-sm ">
                        <thead>
                            <tr class="text-gray-600 font-semibold text-left border-b border-gray-300">
                                <th class="py-3 pl-4 ">Full Name</th>
                                <th class="py-3 px-4">Bank</th>
                                <th class="py-3 px-4">Bank Account no.</th>
                                <th class="py-3 pr-4 pl-6">Bank country</th>
                            </tr>
                        </thead>
                        <tbody id="beneficiaries-table" class="divide-y divide-gray-200">
                            @foreach ($beneficiaries as $beneficia)
                            <tr onclick="window.location='{{ route('beneficias.edit', $beneficia->id) }}'"
                                class="bg-blue-50 font-semibold text-gray-900 cursor-pointer hover:bg-blue-100 transition">
                                <td class="py-3 pl-4">{{ $beneficia->account_name }}</td>
                                <td class="py-3 px-4">{{ $beneficia->bank }}</td>
                                <td class="py-3 px-4">{{ $beneficia->account_number }}</td>
                                <td class="py-3 pr-4 pl-6">{{ $beneficia->country->name }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $beneficiaries->links() }}
                    </div>
                </section>
            
                <!-- Right side: Form -->
                <section class="w-full max-w-md border-l border-gray-200 pl-8">
                    <h2 class="font-semibold text-gray-900 text-base mb-2">Manually Add a Beneficiary</h2>
                    <p class="text-gray-500 text-sm mb-6 max-w-[320px]">
                        You can also quickly add someone as a beneficiary right after sending money to them.
                    </p>
                    <form method="POST" action="{{ route('add_beneficias.store') }}" class="flex flex-col gap-4 text-sm text-gray-600">
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
                        
                        <!-- Country -->
                        <label for="country" class="font-normal">In what country is your bank located?</label>
                        <select id="country" name="country_id"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option selected disabled>Select country</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->id }}" data-code="{{ $c->code }}" {{ old('country_id', $beneficia->country_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                
                       <!-- Bank -->
                        <label for="bank" class="font-normal">Bank</label>
                        <select id="bank" name="bank"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <option selected disabled>Select your bank</option>
                            @foreach($banks as $b)
                                <option value="{{ $b->name }}"
                                    data-country="{{ $b->country_code }}"
                                    {{ old('bank', $beneficia->bank) == $b->name ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>

                
                        <label for="account-number" class="font-normal">Bank account number</label>
                        <input type="text" id="account-number" name="account_number" placeholder="12345678"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
            
                        <label for="account-name" class="font-normal">Bank account name</label>
                        <input type="text" id="account-name" name="account_name" placeholder="John Doe"
                            class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
            
                        <div class="flex gap-4 mt-6">
                            <button type="submit"
                                class="bg-blue-200 text-blue-800 font-semibold rounded-full px-6 py-2 hover:bg-blue-300 transition">
                                Add Beneficiary
                            </button>
                            <button type="button"
                                class="border border-gray-300 rounded-full px-6 py-2 hover:bg-gray-100 transition">
                                Cancel
                            </button>
                        </div>
                    </form>
                </section>
            </section>
           </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('search-input');
            const tableBody = document.getElementById('beneficiaries-table');
    
            input.addEventListener('keyup', function () {
                const query = this.value;
    
                fetch(`/beneficiaries/search?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        let rows = '';
    
                        if (data.length === 0) {
                            rows = '<tr><td colspan="4" class="text-center py-4 text-gray-500">No results found</td></tr>';
                        } else {
                            data.forEach(b => {
                                rows += `
                                    <tr onclick="window.location='/beneficias/${b.id}/edit'" 
                                        class="bg-blue-50 font-semibold text-gray-900 cursor-pointer hover:bg-blue-100 transition">
                                        <td class="py-3 pl-4">${b.account_name}</td>
                                        <td class="py-3 px-4">${b.bank}</td>
                                        <td class="py-3 px-4">${b.account_number}</td>
                                        <td class="py-3 pr-4 pl-6">${b.country.name}</td>
                                    </tr>
                                `;
                            });
                        }
    
                        tableBody.innerHTML = rows;
                    });
            });
        });
    </script>
    
    
    
    <script>
        document.getElementById('country').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const selectedCountryCode = selectedOption.getAttribute('data-code');
            const bankOptions = document.querySelectorAll('#bank option');
        
            bankOptions.forEach(option => {
                if (option.value === "" || option.disabled) return; // Skip the placeholder
                option.style.display = option.dataset.country === selectedCountryCode ? 'block' : 'none';
            });
        
            document.getElementById('bank').value = '';
        });
        
        // Trigger filtering on page load if editing
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('country').dispatchEvent(new Event('change'));
        });
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
    </script>
</body>

</html>