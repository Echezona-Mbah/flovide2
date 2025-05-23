<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Beneficiaries
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Outfit", sans-serif;
        }
    </style>
</head>

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </header>
    <!-- Sidebar -->
    <aside aria-label="Sidebar" id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-[#E9E9E9] flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0">
        <div class="flex items-center justify-between py-8 px-6 flex-shrink-0">
            <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
                height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
            <button aria-label="Close sidebar" id="closeSidebarBtn" class="text-[#1E1E1E] focus:outline-none md:hidden">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto px-6 pb-8 space-y-3 text-sm font-normal text-[#4B4B4B]">
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-tachometer-alt text-base">
                </i>
                Dashboard
            </a>

            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-user-friends text-base">
                </i>
                Payout accounts
            </a>
          
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-layer-group text-base">
                </i>
                Subaccounts
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-history text-base">
                </i>
                Transaction History
            </a>
            <a aria-current="page" class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]"
                href="#">
                <i class="fas fa-wallet text-base">
                </i>
            Beneficiaries
            </a>
           
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-users text-base">
                </i>
                Customers
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice text-base">
                </i>
                Invoices
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-sync-alt text-base">
                </i>
                Subscriptions
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice-dollar text-base">
                </i>
                Bills payment
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-exchange-alt text-base">
                </i>
                Remita
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-undo text-base">
                </i>
                Refunds
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-ban text-base">
                </i>
                Chargebacks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-credit-card text-base">
                </i>
                Virtual Cards
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-balance-scale text-base">
                </i>
                Compliance
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-code-branch text-base">
                </i>
                Webhooks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-building text-base">
                </i>
                Your organization
            </a>
        </nav>
    </aside>
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Payout accounts
            </h1>
            <div class="flex items-center gap-4 flex-wrap">
                <button aria-label="Notifications"
                    class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-[#4B4B4B] text-lg">
                    </i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]">
                    </span>
                </button>
                <button aria-label="Select organization Nexus Global"
                    class="bg-white rounded-full flex items-center gap-2 py-2 px-4 text-sm font-normal text-[#1E1E1E] whitespace-nowrap">
                    <i class="fas fa-bullseye text-[#1E1E1E]">
                    </i>
                    Nexus Global
                    <i class="fas fa-chevron-right text-[#1E1E1E]">
                    </i>
                </button>
                <button aria-label="Logout" class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-[#1E1E1E] text-lg">
                    </i>
                </button>
            </div>
        </header>
        <section class=" relative w-full">
            <section
                class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  w-full ">
                
                    <section class="flex flex-col md:flex-row  mx-auto max-w-full min-h-screen">
                        <!-- Left side: Table and search -->
                        <section class="flex-1 p-2 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-8 md:gap-0">
                                <div class="flex-1 max-w-md">
                                    <label for="search" class="sr-only">Search beneficiaries</label>
                                    <div class="relative text-gray-400 focus-within:text-gray-600">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input id="search" type="search" placeholder="Search beneficiaries"
                                            class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400" />
                                    </div>
                                </div>
                                <a href="{{ route('add_beneficias.create') }}"
                            class="flex items-center justify-center gap-1 rounded-full bg-blue-200 text-blue-800 text-[12px] font-semibold px-4 py-2 min-w-[140px] hover:bg-blue-300 transition">
                                <i class="fas fa-plus"></i> Add Beneficiary
                            </a>
                            </div>
                            <!-- Beneficiaries Table -->
                            <table class="w-full border-collapse text-sm text-gray-700 overflow-auto">
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
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left font-normal pb-3 px-3">Full Name</th>
                                        <th class="text-left font-normal pb-3 px-3">Bank</th>
                                        <th class="text-left font-normal pb-3 px-3">Bank Account no.</th>
                                        <th class="text-left font-normal pb-3 px-3">Bank country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($beneficias as $beneficia)
                                    <tr 
                                        id="beneficia-row-{{ $beneficia->id }}"
                                        class="bg-blue-50 font-semibold border-b border-gray-200 cursor-pointer"
                                        data-id="{{ $beneficia->id }}"
                                        data-name="{{ $beneficia->account_name }}"
                                        data-bank="{{ $beneficia->bank }}"
                                        data-account="{{ $beneficia->account_number }}"
                                        data-country="{{ optional($beneficia->country)->name ?? 'N/A' }}"
                                        onclick="showDetails(this)">
                                        <td class="px-3 py-3">{{ $beneficia->account_name }}</td>
                                        <td class="px-3 py-3">{{ $beneficia->bank }}</td>
                                        <td class="px-3 py-3">{{ $beneficia->account_number }}</td>
                                        <td class="px-3 py-3">{{ optional($beneficia->country)->name ?? 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-3 py-3 text-center text-gray-500">No beneficiaries found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </section>
                    
                        <!-- Right side: Beneficiary details -->
                        <section id="beneficiary-details" class="w-full md:w-96 border-l border-gray-200 p-6 md:p-10">
                            <h3 class="font-semibold text-gray-900 mb-6">Beneficiary Details</h3>
                            <h2 id="detail-name" class="text-3xl font-normal mb-6">Select a beneficiary</h2>
                        
                            <input type="hidden" id="beneficia-id" />
                        
                            <div class="mb-6">
                                <p class="text-sm text-gray-500 mb-1">Bank</p>
                                <p id="detail-bank" class="text-base font-normal text-gray-900">-</p>
                            </div>
                        
                            <div class="mb-6">
                                <p class="text-sm text-gray-400 mb-1">Bank country</p>
                                <p id="detail-country" class="text-base font-semibold text-gray-900">-</p>
                            </div>
                        
                            <div class="mb-10 flex items-center gap-2">
                                <div>
                                    <p class="text-sm text-gray-400 mb-1">Bank account number</p>
                                    <p id="detail-account" class="text-base font-semibold text-gray-900 inline-block">-</p>
                                </div>
                                <button aria-label="Copy bank account number"
                                    onclick="copyAccountNumber()"
                                    class="text-green-600 hover:text-green-700 focus:outline-none">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        
                            <div class="flex gap-4">
                                <a id="edit-link"
                                href="#"
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-6 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <i class="fas fa-pen"></i> Edit
                                </a>

                                <button type="button"
                                    onclick="deleteBeneficia()"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-100 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-400">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </div>
                        </section>
                        
                    </section>
           
            </section>
    </main>
    <script>
    function showDetails(row) {
        const id = row.dataset.id;

        // Update text details
        document.getElementById('beneficia-id').value = id;
        document.getElementById('detail-name').textContent = row.dataset.name;
        document.getElementById('detail-bank').textContent = row.dataset.bank;
        document.getElementById('detail-account').textContent = row.dataset.account;
        document.getElementById('detail-country').textContent = row.dataset.country;

        // Update the edit link
        const editUrl = `{{ url('beneficias') }}/${id}/edit`;
        document.getElementById('edit-link').href = editUrl;
    }

    function copyAccountNumber() {
        const number = document.getElementById('detail-account').textContent;
        navigator.clipboard.writeText(number).then(() => {
            alert("Account number copied!");
        });
    }

    
        function deleteBeneficia() {
    const id = document.getElementById('beneficia-id').value;
    if (!id) {
        Swal.fire({
            icon: 'warning',
            title: 'No beneficiary selected',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: "This beneficiary will be deleted permanently.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/beneficia/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to delete');
                return response.json();
            })
            .then(data => {
                document.getElementById(`beneficia-row-${id}`).remove();

                // Reset detail panel
                document.getElementById('beneficia-id').value = '';
                document.getElementById('detail-name').textContent = 'Select a beneficiary';
                document.getElementById('detail-bank').textContent = '-';
                document.getElementById('detail-account').textContent = '-';
                document.getElementById('detail-country').textContent = '-';

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Beneficiary deleted successfully',
                    showConfirmButton: false,
                    timer: 3000
                });
            })
            .catch(error => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error deleting beneficiary',
                    showConfirmButton: false,
                    timer: 3000
                });
                console.error(error);
            });
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
    </script>
</body>

</html>