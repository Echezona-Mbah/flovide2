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
                Create A Balance
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative ">
            <section class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
                <!-- Left form -->
                <section class="flex flex-col lg:flex-row gap-8 bg-white rounded-tl-3xl md:p-6 p-2 ">
                    <section class="flex-1 bg-white rounded-xl md:p-6 p-2 max-w-full lg:max-w-lg ">
                        <h2 class="text-xl font-extrabold mb-1">
                            Create A New Balance
                        </h2>
                        <p class="text-[#6B6B6B] mb-6 text-sm font-normal">
                            You can make withdrawals directly into any bank account of your
                            choice.
                        </p>
                       
                        <form method="POST" action="{{ route('ohentpay.createBalance') }}" class="space-y-6" enctype="multipart/form-data">
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
                            <div class="flex flex-col gap-5 text-sm font-normal text-[#6B6B6B]">
                                {{-- @csrf --}}
    
                                <div>
                                    <label for="currency" class="block mb-2 text-xs font-semibold text-gray-500 uppercase">Currency</label>
                                
                                    <div class="relative">
                                        <!-- Button that shows selected currency -->
                                        <button id="currencyDropdownButton" type="button"
                                            class="w-full flex items-center justify-between border border-gray-300 px-4 py-2 text-sm text-gray-900 bg-white rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <div id="selectedCurrency" class="flex items-center gap-2">
                                                <img src="https://flagcdn.com/w40/gb.png" alt="United Kingdom flag" class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                                                <span>Pound Sterling (GBP)</span>
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
                                        <input type="hidden" id="currency" name="currency" value="GBP">
                                    </div>
                                </div>
    
      
                               
                                <div class="flex flex-col gap-1">
                                    <label class="font-normal text-[#6B6B6B]" for="account-number">
                                        Name Your Account
                                    </label>
                                    <span class="text-red-500 errornumber"></span>
                                    <input
                                        class="border border-[#C4C4C4] rounded-md py-2 px-3 text-[#C4C4C4] placeholder-[#C4C4C4] focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]"
                                        id="account-number" name="name" placeholder="Name" type="text" />
                                </div>
                                
                                <div class="flex flex-col gap-1">
                                    <div id="responseMessage"></div>
                                </div>
                                <button id="bankAccountForm" class="self-start bg-[#A9D3F7] text-[#1E4F8B] font-semibold text-sm rounded-full py-2.5 px-6 mt-2"
                                    type="submit"> Add Account </button>
                            </div>

                        </form>
                   
                    </section>


                    <!-- Right payout accounts list -->
                    <section class="flex-1 max-w-full lg:max-w-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-base text-[#1E1E1E]">
                                Bank Accounts
                            </h3>
                            {{-- <button aria-label="Delete All Payout Accounts" class="delete-all-btn flex items-center gap-1 text-[#D92D20] text-sm font-semibold rounded-md px-3 py-1 border border-[#D92D20] whitespace-nowrap">
                                <i class="fas fa-trash-alt"></i>
                                Delete All
                            </button> --}}
                        </div>
                     

                        <!-- Account List Container -->
                        <div aria-label="List of payout accounts" class="bg-[#F7F7F7] rounded-xl p-2 md:p-5 flex flex-col gap-3 max-w-full lg:max-w-lg overflow-x-scroll md:overflow-hidden">
                            <p id="default-message" class="mt-4 text-green-600 font-medium hidden"></p>

                           

                                    
                            
                                @foreach($balances as $account)
                                    <div aria-label="Payout account {{ $account['account_number'] ?? 'N/A' }} {{ $account['bank_name'] ?? '' }} payout account" class="flex items-center gap-4 bg-white rounded-lg py-3 px-4 max-w-full">
                                        
                                        <!-- View Balance Icon -->
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#E6F4F1] flex-shrink-0">
                                            <i class="fas fa-check text-[#00875F] text-lg" onclick='openBalanceModal(@json($account))'></i>
                                        </div>

                                        <!-- Account Info Box -->
                                        <div class="flex items-center gap-4 bg-[#E9E9E9] rounded-lg py-3 px-4 max-w-full">
                                            <div class="flex flex-col text-sm text-[#1E1E1E] select-none gap-1">
                                                <div class="flex items-center gap-2">
                                                    <img 
                                                        src="https://flagcdn.com/w20/{{ strtolower($account->currency_meta['country']) }}.png" 
                                                        alt="Flag of {{ strtoupper($account->currency_meta['country']) }}"
                                                        width="20" height="15" class="rounded-sm"
                                                    />
                                                    <span style="font-weight: 400px">{{ $account['name'] ?? 'Unnamed Account' }}</span>
                                                </div>
                                                <span class="text-xs text-[#4B4B4B]">{{ $account['currency'] ?? '' }}</span>
                                            </div>
                                            
                                            <span class="text-lg font-bold text-[#1E1E1E] bg-[#E9E9E9] rounded-full py-1 px-3 whitespace-nowrap">
                                                {{  $account->currency_meta['symbol'] }}{{ number_format($account['amount'] ?? 0, 2) }}
                                            </span>

                                            {{-- <span class="text-xs font-semibold text-[#00875F] bg-[#E6F4F1] rounded-full py-1 px-2 flex items-center gap-1 whitespace-nowrap">
                                                <i class="fas fa-university text-xs"></i>
                                                {{ $account['bank_name'] ?? '' }}
                                            </span> --}}

                                     <!-- Edit Button -->
                                            <button type="button"
                                            class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0"
                                            onclick="openEditModal('{{ $account['id'] }}', '{{ $account['name'] }}')">
                                            <i class="fas fa-pencil-alt"></i>
                                            </button>



                                            {{-- <button class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0 delete-icon" data-id="{{ $account['id'] ?? '' }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button> --}}
                                        </div>
                                    </div>
                                @endforeach
                        </div>
                        <!-- Balance Details Modal -->
                        <div id="balanceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative overflow-y-auto max-h-[90vh]">
                                <button onclick="closeBalanceModal()" class="absolute top-3 right-3 text-gray-600 hover:text-black text-2xl">
                                    &times;
                                </button>

                                <h2 class="text-xl font-semibold mb-4">Balance Details</h2>
                                <div id="balanceDetails" class="space-y-4">
                                    <!-- Dynamically loaded balance details will be injected here -->
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div id="editModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                                <h2 class="text-lg font-bold mb-4">Edit Balance</h2>
                                
                                <form id="editBalanceForm" method="POST" action="{{ route('update.balance') }}">
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
                                    <input type="hidden" name="balance_id" id="editBalanceId">
                                    
                                    <div class="mb-4">
                                        <label for="editBalanceName" class="block text-sm font-medium">New Name</label>
                                        <input type="text" name="name" id="editBalanceName" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" required>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="button" onclick="closeEditModal()" class="mr-2 px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        

                        
                 
                            
                        
                    </section>
                </section>
            </section>
    </main>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}}
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
    function openBalanceModal(account) {
        const modal = document.getElementById('balanceModal');
        const detailsDiv = document.getElementById('balanceDetails');
        detailsDiv.innerHTML = ''; // Clear previous content

        const html = `
            <div class="p-4 border rounded-lg shadow-sm bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center gap-2">
                        <img 
                            src="https://flagcdn.com/24x18/${(account.country || 'us').toLowerCase()}.png" 
                            alt="${account.country || 'Flag'}" 
                            class="w-5 h-auto rounded"
                        />
                        <span class="font-medium">${account.name || 'Unnamed Account'}</span>
                    </div>
                    <span class="text-xs text-gray-500">${account.currency || ''}</span>
                </div>

                <p class="text-sm">Balance: 
                    <span class="font-bold">${account.symbol || ''}${parseFloat(account.balance || 0).toFixed(2)}</span>
                </p>

                ${account.addresses && account.addresses[0] && account.addresses[0].details ? `
                    <div class="text-sm mt-2 text-gray-700">
                        <p><strong>Bank:</strong> ${account.addresses[0].details.bank_name}</p>
                        <p><strong>Account:</strong> ${account.addresses[0].details.account_number}</p>
                        <p><strong>Name:</strong> ${account.addresses[0].details.account_name}</p>
                    </div>
                ` : ''}
            </div>
        `;

        detailsDiv.innerHTML = html;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBalanceModal() {
        const modal = document.getElementById('balanceModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
<script>
    function openEditModal(balanceId, balanceName) {
        document.getElementById('editBalanceId').value = balanceId;
        document.getElementById('editBalanceName').value = balanceName;

        // Show the modal
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>



</body>

</html>