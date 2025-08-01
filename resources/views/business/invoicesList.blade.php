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
                Invoices
            </h1>

            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
            <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute w-full overflow-x-hidden right-[-2.3vw]">
                <section class="flex flex-col md:flex-row gap-10 w-full mx-auto max-w-full min-h-screen px-4 md:px-8">
                    <div class="w-full py-6">
                        <!-- Top Filters -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 gap-4">
                                <!-- Search -->
                                <div class="relative max-w-md w-full">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="invoiceSearch" placeholder="Search Invoices" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent" />
                                </div>

                                <!-- Filter Buttons -->
                                <div class="flex rounded-lg  border border-gray-300 text-sm font-semibold w-full sm:w-auto" id="filterButtons">
                                    <button class="filter-btn bg-white text-black px-4 py-2 font-semibold border-r border-gray-300" data-filter="all">
                                        All
                                    </button>
                                    <button class="filter-btn bg-gray-100 text-gray-400 px-4 py-2 border-r border-gray-300" data-filter="draft">
                                        Draft
                                    </button>
                                    <button class="filter-btn bg-gray-100 text-gray-400 px-4 py-2 border-r border-gray-300" data-filter="overdue">
                                        Overdue
                                    </button>
                                    <button class="filter-btn bg-gray-100 text-gray-400 px-4 py-2 border-r border-gray-300" data-filter="paid">
                                        Paid
                                    </button>
                                </div>
                            </div>

                            <!-- Create Invoice Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('invoices.create') }}">
                                    <button class="flex items-center space-x-2 bg-blue-100 text-blue-700 text-sm font-semibold px-4 py-2 rounded-full hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <i class="fas fa-file-invoice"></i>
                                        <span>Create Invoice</span>
                                    </button>
                                </a>
                            </div>
                        </div>

                        <!-- Table Wrapper for Scroll on Small Screens -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm border-separate border-spacing-y-3 min-w-[768px]">
                                <thead>
                                    <tr class="text-gray-500 font-normal border-b border-gray-200">
                                        <th class="pb-3 font-normal pl-2">Invoice Number</th>
                                        <th class="pb-3 font-normal">Client</th>
                                        <th class="pb-3 font-normal">Date Created</th>
                                        <th class="pb-3 font-normal">Last Updated</th>
                                        <th class="pb-3 font-normal">Amount</th>
                                        <th class="pb-3 font-normal">Status</th>
                                        <th class="pb-3 font-normal pr-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">

                                    @forelse($invoices as $invoice)

                                    <tr class="invoice-row" data-status="{{ strtolower($invoice->status) }}" data-invoice="{{ strtolower($invoice->invoice_number . ' ' . $invoice->billed_to) }}">
                                        <td class="font-semibold pl-2 py-3">{{ $invoice->invoice_number }}</td>
                                        <td class="py-3">{{ $invoice->billed_to ?? 'N/A' }}</td>
                                        <td class="py-3 font-semibold">{{ $invoice->created_at->format('d M Y') }}</td>
                                        <td class="py-3">{{ $invoice->updated_at->format('d M Y') }}</td>
                                        @php
                                            $symbols = [
                                                'USD' => '$',
                                                'GBP' => '£',
                                                'EUR' => '€',
                                                'NGN' => '₦',
                                                'JPY' => '¥',
                                                'CAD' => 'C$',
                                                'AUD' => 'A$',
                                            ];

                                            $currencySymbol = $symbols[$invoice->currency] ?? $invoice->currency;
                                        @endphp
                                        <td class="py-3 font-semibold">{{ $currencySymbol }} {{ number_format($invoice->amount, 2) }}</td>
                                        
                                        @php
                                            $status = $invoice->status ?? 'Overdue';

                                            if ($status === 'paid') {
                                                $statusClass = 'text-green-500';
                                            } elseif ($status === 'draft') {
                                                $statusClass = 'text-gray-500';
                                            } else {
                                                $statusClass = 'text-orange-500';
                                            }
                                        @endphp

                                        <td class="py-3 font-semibold {{ $statusClass }}">
                                            {{ ucfirst($status) }}
                                        </td>

                                        <td class="py-3 pr-2">
                                            <button onclick="toggleMenu(this)" class="text-gray-400 bg-gray-100 px-2 py-1 rounded-full hover:bg-gray-200 focus:outline-none" aria-label="Actions">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <!-- Hidden Action Menu -->
                                            <div class="action-menu absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg z-50 hidden">
                                                <button onclick="editInvoice('{{ $invoice->id }}')" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Edit invoice</button>
                                                <button onclick="deleteInvoice('{{ $invoice->id }}')" class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-500">Delete invoice</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr class="text-center text-gray-400">
                                        <td colspan="7">No invoices found.</td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <section class="absolute bottom-0 w-full md:px-8 py-2 right-0 left-0">
                            <div class="flex flex-row  justify-between items-center mt-6 text-gray-500 text-sm font-semibold gap-3 ">
                                <div class="hidden md:flex">Page  {{ $invoices->currentPage() }} of {{ $invoices->lastPage() }}</div>
                                <nav class="inline-flex space-x-1" aria-label="Pagination">
                                    {{ $invoices->links('pagination::tailwind') }}
                                    {{-- <button class="border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Previous page">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-4 py-2 bg-white font-semibold" aria-current="page">
                                        1
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed" disabled>
                                        2
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed" disabled>
                                        3
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed" disabled>
                                        ...
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-4 py-2 text-gray-400 cursor-not-allowed" disabled>
                                        15
                                    </button>
                                    <button class="border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" aria-label="Next page">
                                        <i class="fas fa-chevron-right"></i>
                                    </button> --}}
                                </nav>
                            </div>
                        </section>
                    </div>
                </section>

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


        
        document.addEventListener('DOMContentLoaded', () => {
            const filterButtons = document.querySelectorAll('.filter-btn');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    filterButtons.forEach(btn => {
                        btn.classList.remove('bg-white', 'text-black');
                        btn.classList.add('bg-gray-100', 'text-gray-400');
                    });

                    button.classList.remove('bg-gray-100', 'text-gray-400');
                    button.classList.add('bg-white', 'text-black');

                    // Filter invoices based on the selected filter
                    const filter = button.getAttribute('data-filter');
                    document.querySelectorAll('.invoice-row').forEach(row => {
                        const status = row.getAttribute('data-status');
                        if (filter === 'all' || status === filter) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });
                });
            });



            const searchInput = document.getElementById('invoiceSearch');

            searchInput.addEventListener('input', () => {
                const term = searchInput.value.trim().toLowerCase();

                document.querySelectorAll('.invoice-row').forEach(row => {
                    const keywords = row.getAttribute('data-invoice');
                    if (keywords.includes(term)) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        });


        // Toggle action menu
        function toggleMenu(button) {
            // Close all open menus first
            document.querySelectorAll('.action-menu').forEach(menu => menu.classList.add('hidden'));

            // Toggle this menu
            const menu = button.nextElementSibling;
            menu.classList.toggle('hidden');

            //Close on outside click
            document.addEventListener('click', function handleOutsideClick(e) {
                if (!button.parentElement.contains(e.target)) {
                    menu.classList.add('hidden');
                    document.removeEventListener('click', handleOutsideClick);
                }
            });
        }

        function editInvoice(id) {
            if (!id) {
                console.warn('Invalid invoice ID');
                return;
            }
            // Redirect to edit invoice page
            window.location.href = `/invoices/${id}/edit`;
        }

        function deleteInvoice(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This invoice will be permanently deleted.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/invoices/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    }).then(res => {
                        if (res.ok) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'Invoice has been deleted.',
                                icon: 'success',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Failed to delete invoice.',
                                icon: 'error'
                            });
                        }
                    });
                }
                
            });
        }
    </script>

</body>

</html>