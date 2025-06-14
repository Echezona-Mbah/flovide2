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
            <section
                class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  w-full ">
                
                    <section class="flex flex-col md:flex-row  mx-auto max-w-full min-h-screen">
                        <!-- Left side: Table and search -->
                        <section class="flex-1 p-2 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-8 md:gap-0">
                                <div class="flex-1 max-w-md">
                                    <label for="search" class="sr-only">Search beneficiaries</label>
                                    <div
                                        class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full sm:w-[400px]">
                                        <i class="fas fa-search text-gray-400 mr-3"></i>
                                        <input type="search" id="search-input" placeholder="Search beneficiaries"
                                        class="w-full text-sm text-gray-600 placeholder-gray-400 focus:outline-none" />
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
                                <tbody id="beneficiaries-table" >
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
                            <div class="mt-4">
                                {{ $beneficias->links() }}
                            </div>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->

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