@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile menu button -->
  @include('business.header')

    <!-- Sidebar -->
   @include('business.sidebar')
    <!-- Overlay -->
    <div
      id="overlay"
      class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"
    ></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
      <header
        class="items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex"
      >
        <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
          Dashboard
        </h1>
                   @include('business.header_notifical')

      </header>
      <section class="relative w-full">
        <section
          class="bg-white text-gray-700 min-h-screen md:w-[80vw] md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden"
        >
          <section class="mx-auto flex flex-col md:flex-row min-h-screen">
            <!-- Left side: Table and filters -->
            <section class="flex-1 border-r border-gray-200 p-6">
              <div
                class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0 max-w-3xl"
              >
                <div class="flex-1">
                  <label for="search" class="sr-only">Search chargebacks</label>
                  <div
                    class="relative text-gray-400 focus-within:text-gray-600"
                  >
                    <span
                      class="absolute inset-y-0 left-0 flex items-center pl-3"
                    >
                      <i class="fas fa-search"></i>
                    </span>
                    <input
                      id="searchInput"
                      type="search"
                      placeholder="Search chargebacks"
                      class="block w-full rounded-full border border-gray-300 py-2 pl-10 pr-4 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    />
                  </div>
                </div>
                <div class="relative inline-block text-left">
                  <select
                   id="statusFilter"
                    aria-label="Filter requests"
                    class="rounded-full border border-gray-300 py-2 pl-4 pr-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer appearance-none"
                    style="
                      background-image: url('data:image/svg+xml,%3csvg fill=\'none\' stroke=\'%236B7280\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' viewBox=\'0 0 24 24\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3cpath d=\'M6 9l6 6 6-6\'/%3e%3c/svg%3e');
                      background-repeat: no-repeat;
                      background-position: right 0.75rem center;
                      background-size: 1.25em 1.25em;">
                    <option value="all" selected>All requests</option>
                    <option value="new">New</option>
                    <option value="resolved">Resolved</option>
                    <option value="evidence submitted">Evidence submitted</option>
                    <option value="evidence rejected">Evidence rejected</option>
                  </select>
                </div>
              </div>
              <section class="overflow-x-auto w-full">
                <table
                  class="w-full mt-6 text-sm text-left text-gray-700 border-separate border-spacing-y-3 min-w-[600px]" >
                  <thead class="text-gray-500 font-semibold">
                    <tr>
                      <th class="pl-2">Customer</th>
                      <th>Amount</th>
                      <th>Deadline</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                 <tbody>
               @if($chargebacks->isEmpty())
    <div class="text-center py-6 text-gray-500">
        No chargebacks found.
    </div>
@else
    @foreach ($chargebacks as $chargeback)
        @php
            $status = ucfirst($chargeback->status ?? '');
            $colorClass = match (strtolower($status)) {
                'new' => 'text-gray-600',
                'resolved' => 'text-green-600',
                'evidence submitted' => 'text-blue-600',
                'evidence rejected' => 'text-red-600',
                default => 'text-black',
            };
        @endphp
        <tr 
            onclick='showDetails(@json($chargeback))'
            class="bg-gray-50 font-semibold cursor-pointer hover:bg-gray-100 chargeback-row"
            data-status="{{ $status }}"
            data-name="{{ strtolower($chargeback->name ?? '') }}"
            data-amount="{{ $chargeback->amount ?? '' }}">
            <td class="pl-2 py-3">{{ $chargeback->name ?? 'Unknown' }}</td>
            <td class="py-3">{{ number_format($chargeback->amount ?? 0, 2) }} {{ $chargeback->currency ?? '' }}</td>
            <td class="py-3">
                {{ $chargeback->deadline ? \Carbon\Carbon::parse($chargeback->deadline)->format('d/m/Y') : '' }}
            </td>
            <td class="py-3 hover:underline {{ $colorClass }}">
                {{ $status }}
            </td>
            <td class="py-3 text-gray-500 font-semibold flex items-center space-x-1">
                <span>Details</span>
                <i class="fas fa-arrow-up-right-from-square"></i>
            </td>
        </tr>
    @endforeach
@endif

                </table>
              </section>
            </section>
 
            <!-- Right side: Dynamic Details -->
            <section class="w-full md:w-96 p-6" id="chargebackDetails">
              <h2 class="font-semibold text-lg mb-6">Details</h2>
              <p id="cbAmount" class="text-3xl font-light mb-6">--</p>

              <div class="mb-6">
                <p class="text-gray-500 mb-1">Transaction reference no.</p>
                <div class="flex items-center space-x-2">
                  <a href="#" id="cbReference" class="text-[#215F9C] font-semibold hover:underline">--</a>
                  <button onclick="copyRef()" class="text-teal-600 hover:text-teal-700 focus:outline-none">
                    <i class="far fa-copy"></i>
                  </button>
                </div>
              </div>

              {{-- <form method="POST" action="{{ route('chargeback.update.reason') }}">
                @csrf --}}
                <input type="hidden" name="id" id="cbId">

                <div class="mb-6">
                  <p class="text-gray-500 mb-1">Reason</p>
                  <input
                    placeholder="Type your reason here" value="" id="cbReason" readonly
                    class="w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-gray-700 text-sm focus:outline-none"
                  />
                </div>


                <div class="mb-6">
                  <p class="text-gray-500 mb-1">Bank email</p>
                  <p id="cbBankEmail" class="font-semibold">--</p>
                </div>

                {{-- <div class="mb-6">
                  <p class="text-gray-500 mb-1">Customer</p>
                  <p id="cbUser" class="font-semibold">--</p>
                </div> --}}

                <div class="mb-6">
                  <p class="text-gray-500 mb-1">Payment method</p>
                  <p id="cbPaymentMethod" class="font-semibold">--</p>
                </div>

                <div class="mb-8">
                  <p class="text-gray-500 mb-1">Deadline</p>
                  <p id="cbDeadline" class="font-semibold">--</p>
                </div>

                <button
                  type="submit"
                  class="text-[#215F9C] bg-blue-100 hover:bg-blue-200 font-semibold text-sm rounded-full py-2 px-5"
                >
                  Submit evidence
                </button>
              {{-- </form> --}}
            </section>

          </section>

          <!-- modal -->

          <section
            class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
            id="modal"
            style="display: none">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
              <div
                class="flex justify-between w-full items-center border-b pb-4 mb-4"
              >
                <p class="text-xl font-semibold mb-4">Submit Evidence</p>

                <button
                  class="border h-6 w-6 rounded-md flex items-center justify-center"
                >
                  <i
                    class="fas fa-times text-md cursor-pointer text-[#828282]"
                  ></i>
                </button>
              </div>
              @if(isset($chargeback))

            <form class="space-y-6" method="POST" action="{{ route('chargeback.submitEvidence') }}" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $chargeback->id }}">

                

                <div class="mb-4">
                  <label class="flex flex-col text-[#828282] text-md gap-1">
                    Transaction reference no.    @if ($errors->any())
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
                    <p class="text-[#252525] font-semibold">{{ $chargeback->transaction_reference }}</p>
                  </label>
                </div>

                <div class="text-[#666666] font-normal text-sm mb-4">
                  <p>Upload document <span class="text-red-500">(required)</span></p>
                  <p>PNGs, PDF and JPGs under 10MB</p>
                </div>

                <div class="mb-4">
                  <label class="flex flex-col text-[#828282] text-md gap-1">
                    Upload
                    <input
                      type="file"
                      name="evidence"
                      required
                      class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300"
                    />
                  </label>
                </div>

                <div class="mb-4">
                  <label class="flex flex-col text-[#828282] text-md gap-1">
                    Notes (optional)
                    <textarea
                      name="note"
                      rows="4"
                      class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300"
                      placeholder="Please explain the reason for your request"
                    ></textarea>
                  </label>
                </div>

                <button
                  type="submit"
                  class="w-full bg-[#215F9C] text-white font-semibold py-2 px-4 rounded-2xl"
                >
                  Submit
                </button>
            </form>

            @endif


            </div>
          </section>
          <!-- modal end-->
        </section>
      </section>
    </main>

    <script>
     document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('searchInput');
      const statusFilter = document.getElementById('statusFilter');
      const rows = document.querySelectorAll('.chargeback-row');

      function filterRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;

        rows.forEach(row => {
          const name = row.getAttribute('data-name') || '';
          const amount = row.getAttribute('data-amount') || '';
          const status = row.getAttribute('data-status') || '';

          const matchesStatus = selectedStatus === 'all' || status.toLowerCase() === selectedStatus;
          const matchesSearch =
            name.includes(searchTerm) ||
            amount.includes(searchTerm) ||
            status.toLowerCase().includes(searchTerm);

          row.style.display = matchesStatus && matchesSearch ? '' : 'none';
        });
      }

      searchInput.addEventListener('input', filterRows);
      statusFilter.addEventListener('change', filterRows);
    });
  </script>

<script>
function showDetails(data) {
  console.log("Selected chargeback:", data);


  document.getElementById('cbAmount').textContent = `${parseFloat(data.amount).toFixed(2)} ${data.currency}`;
  document.getElementById('cbReference').textContent = data.transaction_reference || '--';
  document.getElementById('cbId').value = data.id;
  document.getElementById('cbReason').value = data.reason || 'sddas';
  document.getElementById('cbBankEmail').textContent = data.bank_email || 'Not provided';
  // document.getElementById('cbUser').textContent = data.customer_name || 'Unknown';
  document.getElementById('cbPaymentMethod').textContent = data.payment_method || 'Unknown';
  document.getElementById('cbDeadline').textContent = data.deadline || '--';
}

function copyRef() {
  const ref = document.getElementById('cbReference').textContent;
  navigator.clipboard.writeText(ref);
  alert('Reference copied!');
}
</script>

    
    <script>
      const sidebar = document.getElementById("sidebar");
      const openBtn = document.getElementById("openSidebarBtn");
      const closeBtn = document.getElementById("closeSidebarBtn");
      const overlay = document.getElementById("overlay");

      function openSidebar() {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.remove("hidden");
        document.body.style.overflow = "hidden";
      }

      function closeSidebar() {
        sidebar.classList.add("-translate-x-full");
        overlay.classList.add("hidden");
        document.body.style.overflow = "";
      }

      openBtn.addEventListener("click", openSidebar);
      closeBtn.addEventListener("click", closeSidebar);
      overlay.addEventListener("click", closeSidebar);

      // Close sidebar on window resize if desktop
      window.addEventListener("resize", () => {
        if (window.innerWidth >= 768) {
          sidebar.classList.remove("-translate-x-full");
          overlay.classList.add("hidden");
          document.body.style.overflow = "";
        } else {
          sidebar.classList.add("-translate-x-full");
        }
      });

      document.addEventListener("DOMContentLoaded", function () {
        // Get modal element
        const modal = document.getElementById("modal");

        // Get "Create Virtual Card" button
        const createBtn = document.querySelectorAll("button");
        createBtn.forEach((btn) => {
          if (btn.textContent.trim() === "Submit evidence") {
            btn.addEventListener("click", function () {
              modal.style.display = "flex"; // Show modal
            });
          }
        });

        // Get Close button (the one with the 'fa-times' icon)
        const closeBtn = modal.querySelector(".fa-times");
        closeBtn.addEventListener("click", function () {
          modal.style.display = "none"; // Hide modal
        });

        // Optional: Hide modal on page load
        modal.style.display = "none";
      });
    </script>
  </body>
</html>
