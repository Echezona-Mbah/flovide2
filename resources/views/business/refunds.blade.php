@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
    </header>

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
                Refunds 
            </h1>            
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
            <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[100vw] mx-auto">
                    <!-- Top Stats -->

                    <!-- Main Content -->
                    <section class="bg-white text-gray-900">
                        <div class="flex flex-col lg:flex-row min-h-screen md:max-w-[76vw] md:mx-auto">
                            <!-- Left side -->
                            <section class="flex-1 p-6 lg:p-10 lg:w-[750px]">
                                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mb-6">
                                    <label for="search" class="sr-only">Search requests</label>
                                    <input id="search" type="search" placeholder="Search requests" class="flex-1 border border-gray-300 rounded-lg py-2 px-4 text-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    <button class="border-2 rounded p-2 bg-gray-300" id="openModalBtn">Create Refund</button>
                                    <select aria-label="Filter requests" id="filter" class="border border-gray-300 rounded-lg py-2 px-4 text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="all">All requests</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>
                                <section class="overflow-x-auto w-full">
                                    <table class="w-full border-collapse text-sm ">
                                        <thead>
                                            <tr class="text-gray-600 font-semibold text-left border-b border-gray-200">
                                                <th class="pb-3 pl-2 pr-6">Full Name</th>
                                                <th class="pb-3 pr-6">Amount</th>
                                                <th class="pb-3 pr-6">Approve/Reject</th>
                                                <th class="pb-3 pr-2">Status</th>
                                                <th class="sr-only">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody class="cursor-pointer">
                                            @forelse($refunds as $refund)
                                                <tr class="refundsDataRow" data-id="{{ $refund->id }}" data-type="{{ ucfirst($refund->type) }}" data-amount="{{ number_format($refund->amount, 2) }}" data-currency="{{ $refund->currency }}" data-reference="{{ $refund->transaction_ref_number }}"
                                                    data-status="{{ ucfirst($refund->status) }}" data-sender="{{ ucfirst($refund->name) }}" data-reason="{{ $refund->reason }}" data-recipient="{{ ucfirst($refund->recipient) }}">
                                                    <td class="font-normal pl-2 pr-6 py-3">{{ $refund->name }}</td>
                                                    <td class="pr-6 py-3 font-normal">{{ number_format($refund->amount, 2) . ' '. $refund->currency }}</td>
                                                    @if ($refund->action !== null)
                                                        @php
                                                            if(strtolower($refund->action) == "approved"){
                                                                $colorBtn = "border-green-500 text-green-600";
                                                            }else{
                                                                $colorBtn = "border-red-500 text-red-600";
                                                            }
                                                        @endphp
                                                        <td class="pr-6 py-3">
                                                            <button type="button" class="inline-flex items-center gap-1 border {{ $colorBtn }} rounded-lg py-1.5 px-3 font-medium cursor-pointer select-none">
                                                                {{ ucfirst($refund->action) }} <i class="fas fa-chevron-down text-xs"></i>
                                                            </button>                                                            
                                                        </td>
                                                    @else
                                                        <td class="pr-6 py-3">
                                                            <select class="border border-gray-300 rounded-lg py-1.5 px-3 text-gray-700 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Select approval status">
                                                                <option value="">Select</option>
                                                                <option value="approved">Approved</option>
                                                                <option value="rejected">Rejected</option>
                                                            </select>
                                                        </td>
                                                    @endif

                                                    @php
                                                        $status = $refund->status;
                                                        if($status == "success"){
                                                            $color = "text-green-700 font-semibold cursor-pointer";
                                                        }else if($status == "processing"){
                                                            $color = "text-blue-700 font-semibold cursor-pointer";
                                                        }else if($status == "pending"){
                                                            $color = "text-gray-600";
                                                        }else{
                                                            $color = "text-red-600 font-semibold cursor-pointer";
                                                        }
                                                    @endphp
                                                    <td class="pr-2 py-3 {{ $color }}">
                                                        {{ strtolower($refund->status) === "success" ? "Successful" : ucfirst($refund->status) }}
                                                    </td>
                                                    <td data-id="1" class="pr-2 py-3 text-[#828282] flex items-center gap-1 cursor-pointer select-none">
                                                        View details
                                                        <i class="fas fa-external-link-alt text-xs"></i>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-gray-500">No refund requests found.</td>
                                                </tr>
                                            @endforelse
                                            
                                        </tbody>
                                    </table>
                                </section>
                            </section>

                            <!-- Right side -->
                            <section class="w-full lg:w-[24vw] border-l bg-white p-6 lg:p-8 bg-gray-50">
                                <div class="flex  justify-between items-center h-10 mb-6 bg-gray-200 w-full text-[12px] rounded-md p-1 w-[20vw] font-semibold">
                                    <div class="bg-white cursor-pointer px-2 py-2 rounded-md navBarSection1">Transaction Details</div>
                                    <div class="px-2 py-2 cursor-pointer rounded-md navBarSection2">Refund Tracker</div>
                                </div>
                                <section class="refund-details">
                                    {{-- refund details  --}}
                                </section>
                                <section class="trackerCont">
                                    <div class="w-full max-w-lg mx-auto bg-white p-6">
                                        
                                        <!-- Step 1 -->
                                        <div class="relative flex items-start ">
                                            <!-- Line -->
                                            <div class="absolute left-3 top-0 h-full border-l-2 border-green-500"></div>
                                            <!-- Dot -->
                                            <div class="relative z-10 w-6 h-6 flex items-center justify-center bg-white border-2 border-green-500 rounded-full">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            </div>
                                            <!-- Text -->
                                            <div class="ml-4 mb-8">
                                                <h3 class="font-medium text-gray-900">Refund successfully initiated</h3>
                                                <p class="text-sm text-gray-500">23rd Feb, 2025</p>
                                            </div>
                                        </div>

                                        <!-- Step 2 -->
                                        <div class="relative flex items-start">
                                            <!-- Line -->
                                            <div class="absolute left-3 top-0 h-full border-l-2 border-gray-300"></div>
                                            <!-- Dot -->
                                            <div class="relative z-10 w-6 h-6 flex items-center justify-center bg-white border-2 border-green-500 rounded-full">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            </div>
                                            <!-- Text -->
                                            <div class="ml-4 mb-8">
                                                <h3 class="font-medium text-gray-900">Processing</h3>
                                                <p class="text-sm text-gray-500">23rd Feb, 2025</p>
                                            </div>
                                        </div>

                                        <!-- Step 3 -->
                                        <div class="relative flex items-start">
                                            <!-- Line (shorter so it stops at the dot) -->
                                            <div class="absolute left-3 top-0 h-3 border-l-2 border-gray-300"></div>
                                            <!-- Dot -->
                                            <div class="relative z-10 w-6 h-6 flex items-center justify-center bg-white border-2 border-gray-300 rounded-full">
                                                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                                            </div>
                                            <!-- Text -->
                                            <div class="ml-4">
                                                <h3 class="font-medium text-gray-900">Refund successful</h3>
                                                <p class="text-sm text-gray-500">24th Feb, 2025</p>
                                            </div>
                                        </div>

                                    </div>
                                </section>

                            </section>
                        </div>
                    </section>

                </div>
            </section>
    </main>


    <!-- Modal Background -->
    <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <!-- Modal Content -->
        <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
            
            <!-- Close Button -->
            <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
                ✖
            </button>

            <h2 class="text-xl font-bold mb-4">Refund Form</h2>
            
            <!-- Form -->
            <form id="contactForm" class="space-y-4">
                <div>
                    <label class="block mb-1 font-medium">Fullname</label>
                    <input type="text" name="name" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Amount</label>
                    <input type="tel" name="amount" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Method</label>
                    <select name="method" id="method" class="w-full border rounded px-3 py-2 focus:ring">
                        <option value="">Select Method</option>
                        <option value="deposit">Deposit</option>
                        <option value="online">Online</option>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 font-medium">Reason</label>
                    <textarea name="message" required class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-300"></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Submit
                </button>
            </form>
        </div>
    </div>


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
        document.addEventListener('DOMContentLoaded', function () {
            const refund_details = document.querySelector('.refund-details');
            const rows = document.querySelectorAll('.refundsDataRow');

            refund_details.innerHTML = '<p class="text-gray-300">Choose Approve or Reject to Start Tracking..</p>';

            rows.forEach(row => {
                row.addEventListener('click', function () {
                    rows.forEach(r => r.classList.remove('bg-blue-50', 'font-semibold'));
                    this.classList.add('bg-blue-50', 'font-semibold');

                    const amount = this.getAttribute('data-amount');
                    const currency = this.getAttribute('data-currency');
                    const reference = this.getAttribute('data-reference');
                    const type = this.getAttribute('data-type');
                    const status = this.getAttribute('data-status');
                    const sender = this.getAttribute('data-sender');

                    const detailHTML = `
                        <h2 class="text-2xl font-normal mb-6">${amount + ' ' + currency}</h2>

                        <div class="mb-6">
                            <p class="text-gray-600 mb-1">Transaction reference no.</p>
                            <div class="flex items-center gap-2">
                                <a href="#" lass="text-blue-800 font-semibold text-sm break-all">${reference}</a>
                                <button aria-label="Copy transaction reference number" class="text-green-600 hover:text-green-700">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-600 mb-1">Type</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <i class="fas fa-arrow-down-left text-green-600"></i>
                                <span>${type}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-600 mb-1">Status</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <i class="fas fa-check-double text-green-700"></i>
                                <span>${status}</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-600 mb-1">Time &amp; date</p>
                            <div class="flex items-center gap-2 text-gray-900 font-semibold">
                                <span>12:15:14</span>
                                <span class="border-l border-gray-400 pl-2">Aug 6, 2025</span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-600 mb-1">Sender</p>
                            <p class="font-semibold text-gray-900">${sender}</p>
                        </div>

                        <div>
                            <p class="text-gray-600 mb-1">Recipient</p>
                            <a href="#" class="text-blue-800 font-semibold">Self</a>
                        </div>
                    `;

                    // Replace content
                    refund_details.innerHTML = detailHTML;
                });
            });

        });


        document.addEventListener('DOMContentLoaded', function () {
            
            // Filter functionality
            const searchInput = document.getElementById('search');
            const filterSelect = document.getElementById('filter');
            const rows = document.querySelectorAll('refundsDataRow');

            function filterTransactions() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedFilter = filterSelect.value;

                rows.forEach(row => {
                    const method = row.dataset.type.toLowerCase();
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
        const navBarSection1 = document.querySelector(".navBarSection1");
        const navBarSection2 = document.querySelector(".navBarSection2");
        navBarSection1.addEventListener("click", ()=>{
            navBarSection2.classList.remove("bg-white");
            navBarSection1.classList.add("bg-white");
        });
        navBarSection2.addEventListener("click", ()=>{
            navBarSection1.classList.remove("bg-white");
            navBarSection2.classList.add("bg-white");
        });



        //toast function
        function showToast(message, type = "success") {
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: "#fff",
                color: "#333",
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                didClose: () => {
                    if (type === "success") {
                        location.reload();
                    }
                }
            });
        }


        const openModalBtn = document.getElementById("openModalBtn");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const formModal = document.getElementById("formModal");
        const contactForm = document.getElementById("contactForm");

        // Open modal
        openModalBtn.addEventListener("click", () => {
            formModal.classList.remove("hidden");
        });

        // Close modal
        closeModalBtn.addEventListener("click", () => {
            formModal.classList.add("hidden");
        });

        // Close when clicking outside modal
        formModal.addEventListener("click", (e) => {
            if (e.target === formModal) {
                formModal.classList.add("hidden");
            }
        });

        // Handle form submit
        contactForm.addEventListener("submit", (e) => {
            e.preventDefault();
            
            const form = e.target;
            const name = form.name.value;
            const amount = form.amount.value;
            const method = form.method.value;
            const reason = form.message.value;
            //validate input
            if (!name || !amount || !method || !reason) {
                showToast("All fields are required.", "error");
            }else{
                const formData = new FormData();
                formData.append('fullname', name);
                formData.append('amount', amount);
                formData.append('method', method);
                formData.append('reason', reason);
                fetch('{{ route("refund.store"); }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === "success"){
                        showToast("Refund Created Successfully", "sucess");
                        formModal.classList.add("hidden");
                    }else{
                        showToast(data.message || "An error occurred.", "error");
                        console.log(data);
                    }
                })
                .catch(error => {
                    console.log(error);
                    showToast("Request failed. Please try again.", "error");
                })
            }

        });


        document.addEventListener('click', function (e) {
            if (e.target.closest('[aria-label="Copy transaction reference number"]')) {
                const button = e.target.closest('button');
                const reference = button.previousElementSibling.textContent.trim();

                navigator.clipboard.writeText(reference)
                    .then(() => {
                        showToast("Reference number copied!", "success");
                    })
                    .catch(() => {
                        showToast("Failed to copy reference number.", "error");
                    });
            }
        });

    </script>
</body>

</html>