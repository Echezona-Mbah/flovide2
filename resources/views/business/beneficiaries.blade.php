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
<section class="relative w-full bg-gray-50 min-h-screen">

    <section class="max-w-7xl mx-auto px-3 md:px-6 py-6">

        <section class="bg-white rounded-2xl shadow-md">

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:p-6 border-b">

                <!-- Search -->
                <div class="flex items-center border border-gray-300 rounded-full px-4 py-2 w-full md:max-w-md">
                    <i class="fas fa-search text-gray-400 mr-3"></i>
                    <input type="search"
                        placeholder="Search beneficiaries"
                        class="w-full text-sm focus:outline-none" />
                </div>

                <!-- Button -->
                <a href="{{ route('add_beneficias.create') }}"
                    class="flex items-center justify-center gap-2 bg-blue-600 text-white text-sm font-semibold px-5 py-2 rounded-full hover:bg-blue-700 transition w-full md:w-auto">
                    <i class="fas fa-plus"></i>
                    Add Beneficiary
                </a>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3">Full Name</th>
                            <th class="text-left px-4 py-3 hidden sm:table-cell">Bank</th>
                            <th class="text-left px-4 py-3">Account</th>
                            <th class="text-left px-4 py-3 hidden md:table-cell">Country</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($beneficias as $beneficia)

                        <tr
                        onclick="showDetails(this)"
                        class="cursor-pointer border-b hover:bg-blue-50 transition"

                        data-id="{{ $beneficia->id }}"
                        data-name="{{ $beneficia->account_name }}"
                        data-bank="{{ $beneficia->bank }}"
                        data-account="{{ $beneficia->account_number }}"
                        data-country="{{ $beneficia->country }}"
                        data-city="{{ $beneficia->city }}"
                        data-state="{{ $beneficia->state }}"
                        data-address1="{{ $beneficia->address_line1 }}"
                        data-address2="{{ $beneficia->address_line2 }}"
                        data-swift="{{ $beneficia->swift_bic }}"
                        data-currency="{{ $beneficia->currency }}"
                        data-phone="{{ $beneficia->phone }}" >
                            <td class="px-4 py-4 font-semibold">
                                {{ $beneficia->account_name }}
                                <div class="text-xs text-gray-500 sm:hidden">
                                    {{ $beneficia->bank }}
                                </div>
                            </td>

                            <td class="px-4 py-4 hidden sm:table-cell">
                                {{ $beneficia->bank }}
                            </td>

                            <td class="px-4 py-4 font-mono">
                                {{ $beneficia->account_number }}
                            </td>

                            <td class="px-4 py-4 hidden md:table-cell">
                                {{ $beneficia->country }}
                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-gray-500">
                                No beneficiaries found
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

            <!-- Pagination -->
            <div class="p-4">
                {{ $beneficias->links() }}
            </div>

        </section>

    </section>

</section>



    <!-- ===================== MODAL ===================== -->

<div id="beneficiaryModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50">

    <!-- Slide Panel -->
    <div id="modalPanel"
        class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition duration-300 flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b bg-white">
            <h2 class="font-bold text-lg">Beneficiary details</h2>

            <button onclick="closeModal()"
                class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-5 space-y-8">

            <!-- ================= Beneficiary Information ================= -->
            <section>
                <h3 class="section-title">Beneficiary Information</h3>

                <div class="space-y-3">

                    <div class="info-row">
                        <span>Full Name</span>
                        <p id="detail-name"></p>
                    </div>

                    <div class="info-row">
                        <span>Country</span>
                        <p id="detail-country"></p>
                    </div>

                </div>
            </section>

            <!-- ================= Address ================= -->
            <section>
                <h3 class="section-title">Beneficiary Address</h3>

                <div class="space-y-3">

                    <div class="info-row">
                        <span>Address Line 1</span>
                        <p id="detail-address1"></p>
                    </div>

                    <div class="info-row">
                        <span>Address Line 2</span>
                        <p id="detail-address2"></p>
                    </div>

                    <div class="info-row">
                        <span>City / State</span>
                        <p id="detail-city"></p>
                    </div>

                </div>
            </section>

            <!-- ================= Bank ================= -->
            <section>
                <h3 class="section-title">Bank Details</h3>

                <div class="space-y-3">

                    <div class="info-row">
                        <span>Bank</span>
                        <p id="detail-bank"></p>
                    </div>

                    <div class="info-row">
                        <span>Account Number</span>
                        <p id="detail-account"></p>
                    </div>

                    <div class="info-row">
                        <span>Phone</span>
                        <p id="detail-phone"></p>
                    </div>

                    <div class="info-row">
                        <span>Currency</span>
                        <p id="detail-currency"></p>
                    </div>


                    <div class="info-row">
                        <span>Swift / BIC</span>
                        <p id="detail-swift"></p>
                    </div>

                </div>
            </section>

        </div>


        <!-- ✅ FIXED FOOTER ACTIONS -->
        <div
            class="border-t bg-white p-4 flex gap-3 sticky bottom-0">


            <button id="deleteBtn"
                    class="flex-1 bg-red-600 text-white font-semibold py-3 rounded-xl hover:bg-red-700 transition">
                Delete
            </button>

        </div>

    </div>
</div>



<style>

.section-title {
    font-size: 12px;
    font-weight: bold;
    color: #6b7280;
    text-transform: uppercase;
}

.info-row {
    background: #f9fafb;
    padding: 14px;
    border-radius: 12px;
}

.info-row span {
    font-size: 12px;
    color: #6b7280;
}

.info-row p {
    font-weight: 600;
    margin-top: 4px;
}

</style>



<script>
let selectedBeneficiaryId = null;

function setField(id, value){
    const el = document.getElementById(id);
    if(!el) return;

    const row = el.closest(".info-row");

    if(value === undefined || value === null || value === "" || value === "null"){
        row.style.display = "none";   // 🔥 hide row
    }else{
        row.style.display = "block";  // 🔥 show row
        el.innerText = value;
    }
}

function showDetails(row){
    selectedBeneficiaryId = row.dataset.id;

    setField("detail-name", row.dataset.name);
    setField("detail-country", row.dataset.country);
    setField("detail-address1", row.dataset.address1);
    setField("detail-address2", row.dataset.address2);

    const cityState = `${row.dataset.city || ""} ${row.dataset.state || ""}`.trim();
    setField("detail-city", cityState);

    setField("detail-bank", row.dataset.bank);
    setField("detail-account", row.dataset.account);
    setField("detail-phone", row.dataset.phone);
    setField("detail-currency", row.dataset.currency);
    setField("detail-swift", row.dataset.swift);

    const modal = document.getElementById("beneficiaryModal");
    const panel = document.getElementById("modalPanel");

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
    setTimeout(() => panel.classList.remove("translate-x-full"), 10);
}

// Close modal
function closeModal(){
    const modal = document.getElementById("beneficiaryModal");
    const panel = document.getElementById("modalPanel");

    panel.classList.add("translate-x-full");
    setTimeout(() => {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
    }, 300);

    selectedBeneficiaryId = null;
}

// Delete beneficiary
document.addEventListener("DOMContentLoaded", function() {
    const deleteBtn = document.getElementById("deleteBtn");

    deleteBtn.addEventListener("click", async function(){

        if(!selectedBeneficiaryId) return;

        if(!confirm("Are you sure you want to delete this beneficiary?")) return;

        try {
            const res = await fetch(`/beneficia/${selectedBeneficiaryId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            });

            const data = await res.json();

            if(res.ok){
                alert(data.message || "Beneficiary deleted successfully");
                closeModal();
                location.reload(); // reload list for web
            } else {
                alert(data.message || "Delete failed");
            }

        } catch(e) {
            console.error(e);
            alert("Something went wrong");
        }

    });
});
</script>



    </main>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->

   

{{-- <script>
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
</script> --}}
    
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