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
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
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

            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-users text-base">
                </i>
                Beneficiaries
            </a>
        
            <a aria-current="page"
                class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="#">
                <i class="fas fa-wallet text-base">
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
                Customers
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
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <section class="flex flex-col md:flex-row gap-10 mx-auto max-w-full min-h-screen">
                    <!-- Left side: Table and search -->
                    <div class="flex-1 p-6 md:p-10 max-w-full md:max-w-4xl">
                        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 mb-6">
                            <div class="flex-1 max-w-md w-full">
                                <label for="search" class="sr-only">Search customers</label>
                                <div class="relative text-gray-400 focus-within:text-gray-600">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input id="search" type="search" placeholder="Search customers"
                                        class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300" />
                                </div>
                            </div>
                            <button type="button"
                                class="mt-4 md:mt-0 inline-flex items-center space-x-2 rounded-lg border border-blue-300 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                <i class="fas fa-file-export text-sm"></i>
                                <span>Export CSV</span>
                            </button>
                            <a href="{{ route('add_customer.create') }}">
                                <button type="button"
                                    class="mt-4 md:mt-0 ml-3 inline-flex items-center space-x-2 rounded-lg bg-blue-100 px-4 py-2 text-sm text-blue-700 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    <i class="fas fa-plus text-sm"></i>
                                    <span>Add Customer</span>
                                </button>
                            </a>
                            
                        </div>
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
                        <table class="w-full text-left text-sm text-gray-600 border-separate border-spacing-y-1">
                            <thead class="text-gray-500 font-semibold">
                                <tr>
                                    <th class="pl-4 pb-2">Customer name</th>
                                    <th class="pb-2">Email</th>
                                    <th class="pb-2">Phone</th>
                                    <th class="pr-4 pb-2 text-right">Date Added</th>
                                </tr>
                            </thead>


                            <tbody id="customerTableBody">
                                @foreach($customers as $customer)
                                <tr onclick="loadCustomerDetails({{ $customer->id }})"
                                    class="bg-blue-50 font-semibold rounded-lg cursor-pointer hover:bg-blue-100"
                                    data-id="{{ $customer->id }}">
                                    <td class="pl-4 py-3">{{ $customer->customer_name }}</td>
                                    <td class="py-3 max-w-[120px] truncate">{{ $customer->email }}</td>
                                    <td class="py-3">{{ $customer->phone }}</td>
                                    <td class="pr-4 py-3 text-right">{{ $customer->created_at->format('M j, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>      </div>

                    <!-- Right side: Form -->
                    <div class="w-full md:w-96 border-l border-gray-200 p-6 md:p-10 bg-white">
                        <h3 class="font-semibold text-gray-800 mb-3">Customer Details</h3>
                        <h2 id="customerName" class="text-2xl font-normal mb-6">—</h2>
                
                        <div class="space-y-6 text-sm text-gray-700">
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Date Added</p>
                                <p id="customerDate" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Email</p>
                                <p id="customerEmail" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Phone</p>
                                <p id="customerPhone" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Bank</p>
                                <p id="customerBank" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Account Name</p>
                                <p id="accountName" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Bank Country</p>
                                <p id="bankCountry" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-1">Account Number</p>
                                <p id="accountNumber" class="font-semibold text-base text-gray-900">—</p>
                            </div>
                        </div>
                
                        <div class="mt-10 flex space-x-4">
                            <button id="editButton"
                            class="inline-flex items-center space-x-2 rounded-lg bg-blue-100 px-6 py-2 text-blue-700 hover:bg-blue-200">
                            <i class="fas fa-pen"></i>
                            <span>Edit</span>
                        </button>
                        
                        
                        <button type="button"
                        id="deleteButton"
                        class="inline-flex items-center space-x-2 rounded-lg bg-red-100 px-6 py-2 text-red-600 hover:bg-red-200">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete</span>
                    </button>

                        
                        </div>
                    </div>
                </section>
            </section>
    </main>
    <script>
     function loadCustomerDetails(id) {
    fetch(`/customers/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error("Customer not found");
            }
            return response.json();
        })
        .then(data => {
            const customer = data.data;

            document.querySelector('#customerName').innerText = customer.name || '—';
            document.querySelector('#customerEmail').innerText = customer.email || '—';
            document.querySelector('#customerPhone').innerText = customer.phone || '—';
            document.querySelector('#customerDate').innerText = new Date(customer.created_at).toLocaleDateString('en-US', {
                month: 'short', day: 'numeric', year: 'numeric'
            });
            document.querySelector('#customerBank').innerText = customer.bank || '—';
            document.querySelector('#accountName').innerText = customer.account_name || '—';
            document.querySelector('#bankCountry').innerText = customer.bank_country || '—';
            document.querySelector('#accountNumber').innerText = customer.account_number || '—';
            const editBtn = document.querySelector('#editButton');
            editBtn.onclick = function () {
                window.location.href = `/customer/${id}/edit`;
            };

            document.querySelector('#deleteButton').onclick = function () {
                deleteCustomer(id);
            };


        })
        .catch(error => {
            console.error("Error loading customer details:", error);
            alert("Could not load customer details.");
        });
}


   // ✅ Dynamically set the Edit button URL
//    const editBtn = document.querySelector('#editButton');
//                 editBtn.onclick = function () {
//                     window.location.href = `/customer/${id}/edit`;
//                 };

            // ✅ Dynamically set the Delete button too, if applicable
            // const deleteBtn = document.querySelector('#deleteButton');
            // deleteBtn.onclick = function () {
            //     deleteCustomer(id);
            // };

function deleteCustomer(customerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this customer delete!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/customer/${customerId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Deleted!', data.message || 'Customer has been deleted.', 'success');
                location.reload(); // or remove the row from DOM if you prefer
            })
            .catch(() => {
                Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
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