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
                Transaction History
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative w-full">
            <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute w-full overflow-x-hidden right-[-2.3vw]">
                <section class="flex flex-col md:flex-row gap-10  w-full mx-auto max-w-full min-h-screen">
                <!-- Left side: Transactions list -->
                <div class="flex-1 p-6 md:p-10 ">
                    <form class="flex flex-col sm:flex-row gap-10 mb-6">
                        <label for="search" class="sr-only">Search transactions history</label>
                        <input id="search" type="search" placeholder="Search transactions history" class="flex-1 rounded-full border border-gray-300 px-4 py-2 text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" />
                        <select id="filter" aria-label="Filter transactions" class="rounded-full border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent">
                            <option value="all">All transactions</option>
                            <option value="deposit">Deposits</option>
                            <option value="withdrawal">Withdrawals</option>
                            <option value="successful">Successful</option>
                            <option value="failed">Failed</option>
                        </select>
                    </form>
                
                    <table class="w-full border-separate border-spacing-y-1 text-sm text-gray-700">
                        <thead class="text-gray-600 font-semibold text-left">
                            <tr>
                                <th class="pl-2 pb-2">Type</th>
                                <th class="pb-2">Date</th>
                                <th class="pb-2">Sender</th>
                                <th class="pb-2">Recipient</th>
                                <th class="pb-2">Amount</th>
                                <th class="pr-2 pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($transactions->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500">No Transaction History Found.</td>
                                </tr>
                            @else
                                @foreach($transactions as $index => $transaction)

                                    <tr class="alpha cursor-pointer {{ $index === 0 ? 'bg-blue-50' : '' }}"
                                        data-amount="{{ $transaction->amount }}" data-currency="{{ $transaction->currency }}" data-reference="{{ $transaction->reference }}"
                                        data-method="{{ $transaction->method }}" data-status="{{ $transaction->status }}" data-sender="{{ $transaction->sender_id == Auth::id() ? 'Self' : $transaction->sender }}"
                                        data-recipient="{{ $transaction->recipient_id == Auth::id() ? 'Self' : $transaction->recipient }}" data-created-at="{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s M j, Y') }}">

                                        @if($transaction->method == "deposit")
                                            <td class="pl-2 py-3 text-green-600 font-semibold flex items-center justify-center" title="Deposit">
                                                <i class="fas fa-arrow-down"></i>
                                            </td>
                                        @elseif ($transaction->method == "withdrawal")
                                            <td class="pl-2 py-3 text-red-600 font-semibold flex items-center justify-center" title="Withdrawal">
                                                <i class="fas fa-arrow-up"></i>
                                            </td>
                                        @endif
                                           <td class="font-semibold py-3">{{ $transaction->recipient_type }}</td>

                                        <td class="font-semibold py-3">{{ \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y') }}</td>
                                        <td class="font-semibold py-3">{{ $transaction->sender_id == Auth::id() ? 'Self' : $transaction->sender  }}</td>
                                        <td class="font-semibold py-3">{{ $transaction->recipient_id == Auth::id() ? 'Self' : $transaction->recipient  }}</td>
                                        <td class="font-semibold py-3">{{ $transaction->amount ." ".  $transaction->currency }}</td>
                                        @if($transaction->status == "successful" || $transaction->status == "processing")
                                            <td class="pr-2 py-3 text-green-700 flex justify-center" title="Success">
                                                <i class="fas fa-check-double"></i>
                                            </td>
                                        @elseif ($transaction->status == "failed")
                                            <td class="pr-2 py-3 text-red-600 flex justify-center" title="Failed">
                                                <i class="fas fa-times-circle"></i>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Right side: Transaction details -->
                <aside class="w-full md:w-96 p-6 md:p-10 border-l border-gray-100 TDetails">
                    <h2 class="font-semibold text-gray-900 mb-6">Transaction Details</h2>
                    @if ($latestTransaction)
                        <p class="text-3xl font-normal mb-6">{{ $latestTransaction->amount ." ".  $latestTransaction->currency }}</p>
                    
                        <div class="mb-6">
                            <p class="text-gray-500 text-sm mb-1">Transaction reference no.</p>
                            <div class="flex items-center gap-3">
                                <a href="#" class="text-blue-800 font-semibold text-sm hover:underline" id="referenceText">{{ $latestTransaction->reference }}</a>
                                <button onclick="copyReference()" aria-label="Copy transaction reference number" class="text-green-600 hover:text-green-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6a2 2 0 012 2v2m4 4h2a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    
                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Type</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <i class="fas fa-arrow-down-left text-green-600"></i>
                                <span>{{ ucfirst($latestTransaction->method) }}</span>
                            </div>
                        </div>
                    
                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Status</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                @if ($latestTransaction->status == "successful")
                                    <i class="fas fa-check-double text-green-700"></i>
                                    <span>Success</span>
                                @elseif ($latestTransaction->status == "failed")
                                    <i class="fas fa-times-circle text-red-600"></i>
                                    <span>Failed</span>
                                @endif
                            </div>
                        </div>
                    
                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Time &amp; date</p>
                            <div class="flex items-center gap-4 text-gray-900 font-semibold text-base">
                                <span>{{ \Carbon\Carbon::parse($latestTransaction->created_at)->format('H:i:s') }}</span>
                                <span class="border-l border-gray-300 pl-4">{{ \Carbon\Carbon::parse($latestTransaction->created_at)->format('M j, Y') }}</span>
                            </div>
                        </div>
                    
                        <div class="mb-6">
                            <p class="text-gray-500 text-sm mb-1">Sender</p>
                            <p class="font-semibold text-gray-900">{{ $latestTransaction->sender_id == Auth::id() ? 'Self' : $latestTransaction->sender  }}</p>
                        </div>
                    
                        <div>
                            <p class="text-gray-500 text-sm mb-1">Recipient</p>
                            <a href="#" class="font-semibold text-blue-800 hover:underline">{{ $latestTransaction->recipient_id == Auth::id() ? 'Self' : $latestTransaction->recipient  }}</a>
                        </div>
                    @endif
                </aside>
            </section>
        </section>
    </main>



    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('tr.alpha');
            const detailsPanel = document.querySelector('.TDetails');

            rows.forEach(row => {
                row.addEventListener('click', function () {
                    const amount = this.getAttribute('data-amount');
                    const currency = this.getAttribute('data-currency');
                    const reference = this.getAttribute('data-reference');
                    const method = this.getAttribute('data-method');
                    const status = this.getAttribute('data-status');
                    const sender = this.getAttribute('data-sender');
                    const recipient = this.getAttribute('data-recipient');
                    const createdAt = this.getAttribute('data-created-at');

                    // Format the createdAt date to "Month day, year"
                    const date = new Date(createdAt);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });

                    //change the background color to blue and remove from others
                    rows.forEach(r => r.classList.remove('bg-blue-50'));
                    this.classList.add('bg-blue-50');

                    //send data to the aside panel
                    detailsPanel.innerHTML = `
                        <h2 class="font-semibold text-gray-900 mb-6">Transaction Details</h2>
                        <p class="text-3xl font-normal mb-6">${amount} ${currency}</p>


                        <div class="mb-6">
                            <p class="text-gray-500 text-sm mb-1">Transaction reference no.</p>
                            <div class="flex items-center gap-3">
                                <a href="#" class="text-blue-800 font-semibold text-sm hover:underline" id="referenceText">${reference}</a>
                                <button onclick="copyReference()" aria-label="Copy transaction reference number" class="text-green-600 hover:text-green-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h6a2 2 0 012 2v2m4 4h2a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-2" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Type</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <i class="fas fa-arrow-${method === 'deposit' ? 'down' : 'up'}-left text-${method === 'deposit' ? 'green' : 'red'}-600"></i>
                                <span>${method.charAt(0).toUpperCase() + method.slice(1)}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Status</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                ${status === 'successful' ? `<i class="fas fa-check-double text-green-700"></i><span>Success</span>` : 
                                `<i class="fas fa-times-circle text-red-600"></i><span>Failed</span>`}
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-400 text-sm mb-1">Time &amp; Date</p>
                            <div class="flex items-center gap-4 text-gray-900 font-semibold text-base">
                                <span>${createdAt.split(' ')[0]}</span>
                                <span class="border-l border-gray-300 pl-4">${formattedDate}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-500 text-sm mb-1">Sender</p>
                            <p class="font-semibold text-gray-900">${sender}</p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-sm mb-1">Recipient</p>
                            <a href="#" class="font-semibold text-blue-800 hover:underline">${recipient}</a>
                        </div>
                    `;

                });
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const filterSelect = document.getElementById('filter'); // handles both method and status
            const rows = document.querySelectorAll('tbody tr.alpha');

            function filterTransactions() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedFilter = filterSelect.value;

                rows.forEach(row => {
                    const method = row.dataset.method.toLowerCase();
                    const status = row.dataset.status.toLowerCase();
                    const rowText = row.textContent.toLowerCase();

                    const matchesSearch = rowText.includes(searchTerm);

                    const matchesFilter = selectedFilter === 'all' || method === selectedFilter || status === selectedFilter;

                    if (matchesSearch && matchesFilter) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterTransactions);
            filterSelect.addEventListener('change', filterTransactions);
        });


        

        function copyReference() {
            const text = document.getElementById("referenceText").textContent;
            navigator.clipboard.writeText(text).then(function () {
                alert("Transaction reference copied!");
            }).catch(function (err) {
                console.log('Could not copy text: ', err);
            });
        }

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