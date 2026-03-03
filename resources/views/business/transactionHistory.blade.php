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
      <section class="relative w-full rounded-2xl bg-gray-50 min-h-screen">

    <section class="max-w-7xl mx-auto px-3 md:px-6 py-6">

        <section class="bg-white rounded-2xl shadow-md">

            <!-- Header -->
            <div class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between p-4 md:p-6 border-b">

                <input type="search"
                    placeholder="Search transactions"
                    class="w-full md:max-w-md border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                <select
                    class="w-full md:w-auto border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                    <option>All transactions</option>
                    <option>Deposits</option>
                    <option>Withdrawals</option>
                    <option>Successful</option>
                    <option>Failed</option>

                </select>

            </div>

            <!-- Table -->
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3 hidden md:table-cell">Sender</th>
                            <th class="px-4 py-3 hidden md:table-cell">Recipient</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($transactions as $transaction)

                        <tr onclick="openTransaction(this)"
                            class="cursor-pointer hover:bg-blue-50 border-b transition"

                            data-amount="{{ $transaction->amount }}"
                            data-currency="{{ $transaction->currency }}"
                            data-reference="{{ $transaction->reference }}"
                            data-method="{{ $transaction->method }}"
                            data-status="{{ $transaction->status }}"
                            data-sender="{{ $transaction->sender }}"
                            data-recipient="{{ $transaction->recipient }}"
                            data-date="{{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s M j, Y') }}">

                            <td class="px-4 py-4 text-center">
                                @if($transaction->method == "deposit")
                                    <i class="fas fa-arrow-down text-green-600"></i>
                                @else
                                    <i class="fas fa-arrow-up text-red-600"></i>
                                @endif
                            </td>

                            <td class="px-4 py-4">
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M j, Y') }}
                            </td>

                            <td class="px-4 py-4 hidden md:table-cell">
                                {{ $transaction->sender }}
                            </td>

                            <td class="px-4 py-4 hidden md:table-cell">
                                {{ $transaction->recipient_account_name }}
                            </td>

                            <td class="px-4 py-4 font-semibold">
                                {{ $transaction->amount }} {{ $transaction->currency }}
                            </td>

                            <td class="px-4 py-4 text-center">

                                @if($transaction->status == "success")
                                    <span class="status success">Success</span>
                                @elseif($transaction->status == "failed")
                                    <span class="status failed">Failed</span>
                                @else
                                    <span class="status processing">Processing</span>
                                @endif

                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                No transactions found
                            </td>
                        </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </section>

</section>


<div id="transactionModal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-50">

    <div id="transactionPanel"
        class="absolute right-0 top-0 h-full w-full sm:w-[480px] bg-white shadow-2xl transform translate-x-full transition duration-300 flex flex-col">

        <!-- Header -->
        <div class="flex items-center justify-between p-5 border-b">

            <h2 class="font-bold text-lg">Transaction details</h2>

            <button onclick="closeTransaction()"
                class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
                <i class="fas fa-times"></i>
            </button>

        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4">

            <div class="info-row">
                <span>Amount</span>
                <p id="t-amount"></p>
            </div>

            <div class="info-row">
                <span>Reference</span>
                <p id="t-reference"></p>
            </div>

            <div class="info-row">
                <span>Type</span>
                <p id="t-method"></p>
            </div>

            <div class="info-row">
                <span>Status</span>
                <p id="t-status"></p>
            </div>

            <div class="info-row">
                <span>Sender</span>
                <p id="t-sender"></p>
            </div>

            <div class="info-row">
                <span>Recipient</span>
                <p id="t-recipient"></p>
            </div>

            <div class="info-row">
                <span>Date</span>
                <p id="t-date"></p>
            </div>

        </div>

    </div>

</div>
<style>

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

.status {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status.success {
    background: #dcfce7;
    color: #15803d;
}

.status.failed {
    background: #fee2e2;
    color: #b91c1c;
}

.status.processing {
    background: #fef9c3;
    color: #a16207;
}

</style>
<script>

function openTransaction(row) {

    document.getElementById("t-amount").textContent =
        row.dataset.amount + " " + row.dataset.currency;

    document.getElementById("t-reference").textContent =
        row.dataset.reference;

    document.getElementById("t-method").textContent =
        row.dataset.method;

    document.getElementById("t-status").textContent =
        row.dataset.status;

    document.getElementById("t-sender").textContent =
        row.dataset.sender;

    document.getElementById("t-recipient").textContent =
        row.dataset.recipient;

    document.getElementById("t-date").textContent =
        row.dataset.date;

    const modal = document.getElementById("transactionModal");
    const panel = document.getElementById("transactionPanel");

    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";

    setTimeout(() => {
        panel.classList.remove("translate-x-full");
    }, 10);
}

function closeTransaction() {

    const modal = document.getElementById("transactionModal");
    const panel = document.getElementById("transactionPanel");

    panel.classList.add("translate-x-full");

    setTimeout(() => {
        modal.classList.add("hidden");
        document.body.style.overflow = "auto";
    }, 300);
}

</script>



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
    </script>
</body>

</html>