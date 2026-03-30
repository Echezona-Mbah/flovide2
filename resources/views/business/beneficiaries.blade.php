@include('business.head')
<body class="bg-[#EEF2F7] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Payout Accounts</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Beneficiaries</h1>
        <p class="mt-1 text-sm text-slate-500">Create and manage payout recipients.</p>
      </div>
      @include('business.header_notifical')
    </header>

    <section class="w-full">
      <div class="rounded-[28px] bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 md:px-10 py-7 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Directory</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Beneficiaries</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                Search, review, and manage payout accounts.
              </p>
            </div>
            <a href="{{ route('add_beneficias.create') }}"
              class="flex items-center justify-center gap-2 bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 rounded-full hover:bg-slate-800 transition w-full md:w-auto">
              <i class="fas fa-plus"></i>
              Add Beneficiary
            </a>
          </div>
        </div>

        <!-- Filters -->
        <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/40">
          <div class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between">
            <div class="flex items-center border border-slate-200 rounded-xl px-4 py-2.5 w-full md:max-w-md bg-white">
              <i class="fas fa-search text-slate-400 mr-3"></i>
              <input type="search" placeholder="Search beneficiaries"
                     class="w-full text-sm focus:outline-none bg-transparent" />
            </div>
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-white text-slate-500 border-b border-slate-100">
              <tr>
                <th class="text-left px-4 py-4">Full Name</th>
                <th class="text-left px-4 py-4 hidden sm:table-cell">Bank</th>
                <th class="text-left px-4 py-4">Account</th>
                <th class="text-left px-4 py-4 hidden md:table-cell">Country</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">

              @forelse ($beneficias as $beneficia)
              <tr onclick="showDetails(this)"
                  class="cursor-pointer hover:bg-sky-50 transition"
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
                  data-phone="{{ $beneficia->phone }}">
                <td class="px-4 py-4 font-semibold">
                  {{ $beneficia->account_name }}
                  <div class="text-xs text-slate-500 sm:hidden">{{ $beneficia->bank }}</div>
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
                <td colspan="4" class="text-center py-8 text-slate-500">
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

      </div>
    </section>

    <!-- Modal -->
    <div id="beneficiaryModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50">
      <div id="modalPanel"
        class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition duration-300 flex flex-col">

        <div class="flex items-center justify-between p-5 border-b bg-white">
          <h2 class="font-bold text-lg">Beneficiary details</h2>
          <button onclick="closeModal()" class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-8">
          <section>
            <h3 class="section-title">Beneficiary Information</h3>
            <div class="space-y-3">
              <div class="info-row"><span>Full Name</span><p id="detail-name"></p></div>
              <div class="info-row"><span>Country</span><p id="detail-country"></p></div>
            </div>
          </section>

          <section>
            <h3 class="section-title">Beneficiary Address</h3>
            <div class="space-y-3">
              <div class="info-row"><span>Address Line 1</span><p id="detail-address1"></p></div>
              <div class="info-row"><span>Address Line 2</span><p id="detail-address2"></p></div>
              <div class="info-row"><span>City / State</span><p id="detail-city"></p></div>
            </div>
          </section>

          <section>
            <h3 class="section-title">Bank Details</h3>
            <div class="space-y-3">
              <div class="info-row"><span>Bank</span><p id="detail-bank"></p></div>
              <div class="info-row"><span>Account Number</span><p id="detail-account"></p></div>
              <div class="info-row"><span>Phone</span><p id="detail-phone"></p></div>
              <div class="info-row"><span>Currency</span><p id="detail-currency"></p></div>
              <div class="info-row"><span>Swift / BIC</span><p id="detail-swift"></p></div>
            </div>
          </section>
        </div>

        <div class="border-t bg-white p-4 flex gap-3 sticky bottom-0">
          <button id="deleteBtn" class="flex-1 bg-red-600 text-white font-semibold py-3 rounded-xl hover:bg-red-700 transition">
            Delete
          </button>
        </div>
      </div>
    </div>

  </main>

  <style>
    .section-title { font-size:12px; font-weight:bold; color:#6b7280; text-transform:uppercase; }
    .info-row { background:#f9fafb; padding:14px; border-radius:12px; }
    .info-row span { font-size:12px; color:#6b7280; }
    .info-row p { font-weight:600; margin-top:4px; }
  </style>

  <script>
    let selectedBeneficiaryId = null;

    function setField(id, value){
      const el = document.getElementById(id);
      if(!el) return;

      const row = el.closest(".info-row");
      if(value === undefined || value === null || value === "" || value === "null"){
        row.style.display = "none";
      }else{
        row.style.display = "block";
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
            location.reload();
          } else {
            alert(data.message || "Delete failed");
          }

        } catch(e) {
          console.error(e);
          alert("Something went wrong");
        }
      });
    });

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
