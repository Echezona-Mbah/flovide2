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
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute w-full overflow-x-hidden right-[-2.3vw]">
                <section class="flex flex-col md:flex-row gap-10 w-full mx-auto max-w-full min-h-screen px-4 md:px-8">
                    <div class="w-full py-6">
                        <section class="flex items-center justify-between mb-8 select-none" aria-label="Page header">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('invoices.index') }}">
                                    <button aria-label="Back"
                                        class="flex items-center justify-center w-10 h-10 rounded-md bg-gray-200 transition-colors">
                                        <i class="fas fa-chevron-left text-lg text-[#1E1E1E]"></i>
                                    </button>
                                </a>
                                <h1 class="text-xl font-extrabold leading-tight">Edit Invoice</h1>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    @php
                                        if($invoice->status === 'draft') {
                                            $status = 'Draft';
                                            $statusColor = 'bg-yellow-400';
                                            $statusTextColor = 'text-gray-300';
                                            $statusBorderColor = 'border-gray-400';
                                            $statusEnable = 'disabled';
                                            $statusCusor = 'cursor-not-allowed';
                                        } else {
                                            $status = 'Active';
                                            $statusColor = 'bg-green-600';
                                            $statusTextColor = 'text-blue-800';
                                            $statusBorderColor = 'border-blue-800';
                                            $statusEnable = '';
                                            $statusCusor = '';
                                        }
                                    @endphp
                                    <span class="w-3 h-3 rounded-full {{ $statusColor }}"></span>
                                    <span class="text-sm text-gray-600 select-text">{{ $status }}</span>
                                </div>
                                <button {{ $statusEnable }}  onclick="copyLink()" class="flex items-center space-x-2 rounded-full border {{ $statusBorderColor . ' ' . $statusTextColor . ' ' . $statusCusor }} px-4 py-2  select-none" aria-label="Copy Link">
                                    <i class="far fa-copy"></i>
                                    <span class="hidden md:flex">Copy Link</span>
                                </button>
                            </div>
                        </section>
                        <section class="flex flex-col lg:flex-row gap-8">
                            <!-- Left form -->
                            <section class="flex-1 max-w-full lg:max-w-[600px] space-y-6">
                                <form id="invoiceForm" method="POST">
                                    @method('PUT')
                                    @csrf <!-- CSRF token for form submission -->
                                    <!-- Invoice Details -->
                                    <div>
                                        <h2 class="font-semibold text-sm mb-3">Invoice Details</h2>
                                        <label for="billedTo" class="block mb-1 text-xs font-semibold text-gray-700">Billed to</label>
                                        <select id="billedTo" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" aria-label="Billed to email">
                                            <option selected>{{ $invoice->billed_to }}</option>
                                        </select>
                                    </div>

                                    <div class="flex flex-wrap gap-4 mt-3 mb-4">
                                        <div class="flex-1 min-w-[150px]">
                                            <label for="invoiceNumber" class="block mb-1 text-xs font-semibold text-gray-700">Invoice number</label>
                                            <input id="invoiceNumber" type="text" value="{{ $invoice->invoice_number }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />
                                        </div>
                                        <div class="flex-1 min-w-[150px]">
                                            <label for="dueDate" class="block mb-1 text-xs font-semibold text-gray-700">Due date</label>
                                            <div class="relative">
                                                <input id="dueDate" type="date" value="{{ $invoice->created_at->format('Y-m-d') }}" class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" aria-label="Due date" />
                                                <i class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="address"
                                            class="block mb-1 text-xs font-semibold text-gray-700">Address</label>
                                        <input id="address" type="text" value="{{ $invoice->address }}" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />
                                    </div>

                                    <div class="mb-6">
                                        <label for="currency" class="block mb-1 text-xs font-semibold text-gray-700">Currency</label>
                                        @php
                                            $currencies = ['USD', 'EUR', 'GBP', 'NGN', 'CAD'];
                                        @endphp
                                        <select id="currency" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" aria-label="Currency">                                            
                                            @foreach($currencies as $currency)
                                                <option value="{{ $currency }}" {{ $invoice->currency === $currency ? 'selected' : '' }}>
                                                    {{ $currency }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Invoice Items -->
                                    <div>
                                        <h3 class="font-semibold text-sm mb-3 flex items-center gap-2">
                                            Invoice Items
                                            <button type="button" class="ml-auto flex items-center gap-1 rounded-full border border-[#3B82F6] bg-[#E0F2FE] px-3 py-1 text-[#3B82F6] text-sm font-semibold hover:bg-[#bae6fd] transition-colors">
                                                <i class="fas fa-plus"></i> Add Item
                                            </button>
                                        </h3>
                                        <section class="overflow-auto itemslistSection">
                                            @foreach($invoice->items as $index => $item)
                                                @php
                                                    $itemIndex = $index + 1;
                                                @endphp
                                                <div class="grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3" aria-label="Invoice item {{ $itemIndex }}">
                                                    <label for="item{{ $itemIndex }}" class="text-xs font-semibold text-gray-700 block">Item</label>
                                                    <label for="qty{{ $itemIndex }}" class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                                                    <label for="rate{{ $itemIndex }}" class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                                                    <label for="total{{ $itemIndex }}" class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                                                    <input id="item{{ $itemIndex }}" type="text" value="{{ $item->item_name }}" placeholder="Item description" class="item-name rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />

                                                    <input id="qty{{ $itemIndex }}" type="number" value="{{ $item->qty }}" class="item-qty rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />

                                                    <div class="flex justify-center items-center">
                                                        <div class="rate-toggle relative inline-block w-10 h-5 {{ $item->rate_enabled ? 'bg-blue-500' : 'bg-gray-300' }} rounded-full cursor-pointer" role="switch" aria-checked="{{ $item->rate_enabled ? 'true' : 'false' }}">
                                                            <div class="dot absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform"></div>
                                                        </div>
                                                        <input id="rate{{ $itemIndex }}" type="number" value="{{ $item->rate ?? 0 }}" class="item-rate rate rounded-md w-40 border ml-2 px-3 py-2 text-sm text-center" />
                                                    </div>

                                                    <div class="flex justify-center items-center gap-3">
                                                        {{-- Total input --}}
                                                        <input id="total{{ $itemIndex }}" type="text" value="0" disabled class="item-total rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-gray-400 text-center bg-gray-50 cursor-not-allowed" aria-label="Total" />
                                                        {{-- create trash button --}}
                                                        <button type="button" id="delete{{ $itemIndex }}" class="text-black-500 border-gray-300 bg-gray-300 px-3 py-2 rounded-md hover:text-red-700 transition-colors">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                        <div></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </section>

                                    </div>

                                    <div class="mt-3">
                                        <label for="note" class="block mb-1 text-xs font-semibold text-gray-700">Add a note (optional)</label>
                                        <textarea id="note" rows="4" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 resize-none focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]">
                                            {{ $invoice->note }}
                                        </textarea>
                                    </div>
                                    {{-- error and success section  --}}
                                    <div class="flex gap-4 mt-3 flex-wrap">
                                        <div id="successCon" class="hidden text-green-600 text-sm font-semibold"></div>
                                        <div id="errorCon" class="hidden text-red-600 text-sm font-semibold"></div>
                                    </div>
                                    @php
                                        if($invoice->status === 'draft') {
                                            $buttontext = 'Create Invoice';
                                            $buttontext2 = 'Save Invoice';
                                            $invoiceStatus = 'draft';
                                        } else {
                                            $buttontext = 'Update Invoice';
                                            $buttontext2 = 'Saved as Draft';
                                            $invoiceStatus = 'pending';
                                        }
                                    @endphp
                                    <input type="hidden" name="status" id="invoiceStatus" value="{{ $invoiceStatus }}">
                                    <div class="flex gap-4 mt-3 flex-wrap">
                                        <button type="submit" class="rounded-full bg-[#BFDBFE] text-[#1E40AF] px-6 py-2 text-sm font-semibold hover:bg-[#93c5fd] transition-colors">
                                            {{ $buttontext }}
                                        </button>
                                        <button type="button" class="rounded-full border border-gray-300 px-6 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                                            <i class="far fa-file-alt"></i> {{ $buttontext2 }}
                                        </button>
                                    </div>
                                </form>
                            </section>

                            <!-- Right preview -->
                            <section class="flex-1 max-w-full lg:max-w-[600px] bg-[#F3F4F6] rounded-xl md:p-6 p-2 select-text" aria-label="Invoice preview">
                                <h3 class="font-semibold text-sm mb-3">Preview</h3>
                                <div class="bg-white rounded-xl md:p-6 p-2 space-y-6 max-w-full overflow-hidden"
                                    aria-label="Invoice preview content">
                                    <div class="flex justify-between items-center">
                                        <h2 class="font-extrabold text-3xl leading-tight">Invoice</h2>
                                        <div class="flex flex-col items-center text-center">
                                            <div class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center mb-1">
                                                <div class="w-4 h-4 rounded-full bg-black"></div>
                                            </div>
                                            <span class="text-xs font-semibold">Nexus Global</span>
                                            <span class="text-[10px] text-gray-400 leading-tight">hi@nexusglobal.com</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between text-xs text-gray-700">
                                        <div class="flex items-center gap-1">
                                            <span>Invoice number</span>
                                            <span class="font-semibold">{{ $invoice->invoice_number }}</span>
                                        </div>
                                        <div></div>
                                    </div>

                                    <div class="border border-gray-300 rounded-md overflow-hidden text-xs text-gray-700">
                                        <div class="grid grid-cols-2 border-b border-gray-300">
                                            <div class="p-3 border-r border-gray-300">
                                                <p class="font-semibold mb-1">Billed To</p>
                                                <p>{{ $invoice->billed_to }}</p>
                                            </div>
                                            <div class="p-3">
                                                <p class="font-semibold mb-1">Due Date</p>
                                                <p class="font-semibold">{{ $invoice->created_at->format('Y-m-d') }}</p>
                                            </div>
                                        </div>
                                        <div class="p-3 text-xs font-semibold">
                                            <p>Address</p>
                                            <p class="font-semibold">
                                                {{ $invoice->address }}
                                            </p>
                                        </div>
                                    </div>

                                    <table class="w-full border border-gray-300 text-xs text-gray-700 border-collapse overflow-auto">
                                        <thead>
                                            <tr class="bg-gray-100 border-b border-gray-300">
                                                <th class="border-r border-gray-300 p-2 text-left">Items</th>
                                                <th class="border-r border-gray-300 p-2 text-center">QTY</th>
                                                <th class="border-r border-gray-300 p-2 text-center">Rate</th>
                                                <th class="p-2 text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($invoice->items as $item)
                                                <tr class="border-b border-gray-300">
                                                    <td class="border-r border-gray-300 p-2">{{ $item->item_name }}</td>
                                                    <td class="border-r border-gray-300 p-2 text-center">{{ $item->qty }}</td>
                                                    <td class="border-r border-gray-300 p-2 text-center">
                                                        @if ($item->rate_enabled && $item->rate !== null)
                                                            {{ $invoice->currency }} {{ number_format($item->rate, 2) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="p-2 text-center">{{ $invoice->currency }} {{ number_format($item->total, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="p-2 text-center text-gray-400">No items available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                    <div
                                        class="border border-gray-300 rounded-md p-4 text-xs text-gray-700 max-w-[280px] ml-auto">
                                        <div class="flex justify-between mb-2">
                                            <span>Subtotal</span>
                                            <span>Total</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span>Tax</span>
                                            <span>£ 0.00</span>
                                        </div>
                                        <div class="flex justify-between font-semibold text-gray-900">
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
                                            <span>Total</span>
                                            <span>{{ $currencySymbol }} {{ number_format($invoice->amount, 2) }}</span>
                                        </div>
                                    </div>
                                    @php
                                        if(empty($invoice->note)) {
                                            $noteStatus = 'hidden';
                                        } else {
                                            $noteStatus = '';
                                        }
                                    @endphp
                                    <div {{ $noteStatus }} class="border border-gray-300 rounded-md p-3 text-[10px] text-gray-700 max-w-[400px]">
                                        <p class="font-semibold mb-1">Note:</p>
                                        <p>
                                            {{ $invoice->note }}
                                        </p>
                                    </div>
                                </div>
                            </section>
                        </section>
                    </div>
                </section>

            </section>
        </section>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //store the original invoice data
        window.originalInvoice = {
            invoice_number: @json($invoice->invoice_number),
            billed_to: @json($invoice->billed_to),
            due_date: @json($invoice->due_date ? $invoice->due_date->format('Y-m-d') : ''),
            address: @json($invoice->address),
            currency: @json($invoice->currency),
            note: @json($invoice->note),
            status: @json($invoice->status),
            items: @json($invoice->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'qty' => (int) $item->qty,
                    'rate_enabled' => (bool) $item->rate_enabled,
                    'rate' => $item->rate_enabled ? (float) $item->rate : null,
                    'total' => (float) $item->total,
                ];
            })),
        };



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

        document.addEventListener('DOMContentLoaded', function() {
            // Start with the first item already present
            let itemIndex = 1;

            const itemContainer = document.querySelector('section.overflow-auto.itemslistSection');
            const addItemBtn = document.querySelector('button:has(i.fa-plus)');

            addItemBtn.addEventListener('click', () => {
                itemIndex++;
                const newRow = document.createElement('div');
                newRow.className = 'grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3';
                newRow.setAttribute('aria-label', `Invoice item ${itemIndex}`);
                newRow.innerHTML = `
                        <label for="item1" class="text-xs font-semibold text-gray-700 block">Item</label>
                        <label for="qty1" class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                        <label for="rate1" class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                        <label for="total1" class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                        <input id="item${itemIndex}" type="text" placeholder="Item description" class="item-name rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:ring-1 focus:ring-[#3B82F6]" />
                        <input id="qty${itemIndex}" type="number" value="1" class="item-qty rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-center text-gray-900 qty" />
                        <div class="flex justify-center items-center">
                            <div class="rate-toggle relative inline-block w-10 h-5 bg-gray-300 rounded-full cursor-pointer" role="switch" aria-checked="false">
                                <div class="dot absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform"></div>
                            </div>
                            <input id="rate${itemIndex}" type="number" value="0" class="item-rate rate rounded-md w-40 border ml-2 px-3 py-2 text-sm text-center" />
                        </div>
                        <div class="flex justify-center items-center gap-3">
                            <input id="total${itemIndex}" type="text" value="0" disabled class="item-total rounded-md w-40 border border-gray-300 px-3 py-2 text-sm text-gray-400 text-center bg-gray-50 cursor-not-allowed" aria-label="Total" />
                            <button type="button" id="delete${itemIndex}" class="text-black-500 border-gray-300 bg-gray-300 px-3 py-2 rounded-md hover:text-red-700 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <div></div>
                        </div>
                    `;
                itemContainer.appendChild(newRow);
                attachEvents(newRow, itemIndex);
            });

            function attachEvents(row, index) {
                const qtyInput = row.querySelector(`#qty${index}`);
                const rateInput = row.querySelector(`#rate${index}`);
                const totalInput = row.querySelector(`#total${index}`);
                const toggle = row.querySelector('.rate-toggle');
                const deleteBtn = row.querySelector(`#delete${index}`);

                qtyInput.addEventListener('input', updateTotal);
                rateInput.addEventListener('input', updateTotal);

                function updateTotal() {
                    const qty = parseFloat(qtyInput.value) || 0;
                    const rate = toggle.getAttribute('aria-checked') === 'true' ? parseFloat(rateInput.value) || 0 : 0;
                    totalInput.value = (qty * rate).toFixed(2);
                }

                toggle.addEventListener('click', () => {
                    const isEnabled = toggle.getAttribute('aria-checked') === 'true';
                    toggle.setAttribute('aria-checked', String(!isEnabled));
                    toggle.classList.toggle('bg-blue-500');
                    toggle.classList.toggle('bg-gray-300');
                    updateTotal();
                });

                // Delete button functionality
                deleteBtn.addEventListener('click', () => {
                    row.remove();
                });
                updateTotal();
            }

            // Attach initial event listeners
            document.querySelectorAll('[aria-label^="Invoice item"]').forEach((row, i) => {
                attachEvents(row, i + 1);
            });

            // Submit form and send JSON payload
            document.querySelector('#invoiceForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const invoiceNumber = document.querySelector('#invoiceNumber').value.trim();
                const billedTo = document.querySelector('#billedTo').value.trim();
                const dueDate = document.querySelector('#dueDate').value;
                const address = document.querySelector('#address').value.trim();
                const currency = document.querySelector('#currency').value;
                const note = document.querySelector('#note').value.trim();
                const invoiceStatus = document.querySelector('#invoiceStatus').value.trim();

                if (!invoiceNumber || !billedTo || !dueDate || !address || !currency) {
                    showMessage("Please fill in all invoice details (Billed To, Invoice Number, Due Date, Address, Currency).", 'error');
                    return;
                }

                const rows = document.querySelectorAll('[aria-label^="Invoice item"]');
                const payload = {
                    invoice_number: invoiceNumber,
                    billed_to: billedTo,
                    due_date: dueDate,
                    address,
                    currency,
                    note,
                    status: invoiceStatus,
                    items: [],
                    deleted_item_ids: []
                };

                let hasError = false;

                rows.forEach((row, index) => {
                    const itemId = row.getAttribute('data-item-id') || null;
                    const itemName = row.querySelector('.item-name')?.value.trim();
                    const qtyVal = row.querySelector('.item-qty')?.value;
                    const qty = parseInt(qtyVal);
                    const rateInput = row.querySelector('.item-rate');
                    const totalInput = row.querySelector('.item-total');
                    const toggle = row.querySelector('[role="switch"]');

                    const rateEnabled = toggle?.getAttribute('aria-checked') === 'true';
                    const rate = rateEnabled ? parseFloat(rateInput?.value || 0) : null;
                    const total = parseFloat(totalInput?.value?.replace(/,/g, '') || 0);

                    if (!itemName || isNaN(qty) || qty <= 0 || (rateEnabled && (rate === null || rate < 0))) {
                        hasError = true;
                        showMessage(`Please check item ${index + 1}: Ensure item name, valid quantity, and rate (if enabled) are provided.`, 'error');
                        return;
                    }

                    payload.items.push({
                        id: itemId,
                        item_name: itemName,
                        qty,
                        rate_enabled: rateEnabled,
                        rate,
                        total
                    });
                });

                const deletedIds = document.querySelectorAll('input.deleted-item-id');
                deletedIds.forEach(input => {
                    payload.deleted_item_ids.push(input.value);
                });

                payload.amount = payload.items.reduce((sum, item) => sum + item.total, 0);
                
                // Validate if there are items to submit
                if (hasError || payload.items.length === 0) return;

                // Compare current payload with original data
                if (!hasInvoiceChanged(payload, window.originalInvoice)) {
                    showMessage("No changes detected in the invoice.", 'error');
                    return;
                }

                try {
                    const response = await fetch("{{ route('invoices.update', $invoice->id) }}", {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify(payload)
                    });

                    const text = await response.text();
                    try {
                        const result = JSON.parse(text);

                        if (response.ok) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Invoice updated successfully!',
                                icon: 'success',
                                timer: 3000,
                                showConfirmButton: false,
                                willClose: () => location.reload()
                            });
                        } else {
                            const firstError = result.errors ? Object.values(result.errors).flat()[0] : "An unknown error occurred.";
                            showMessage(firstError, 'error');
                        }
                    } catch {
                        console.log("Non-JSON response:", text);
                        showMessage("Unexpected server response. Check console.", 'error');
                    }

                } catch (err) {
                    console.log(err);
                    showMessage("Error connecting to server. Try again.", 'error');
                }

                function showMessage(message, type = 'success') {
                    const container = document.getElementById(type === 'success' ? 'successCon' : 'errorCon');
                    container.textContent = message;
                    container.classList.remove('hidden');
                    setTimeout(() => {
                        container.textContent = '';
                        container.classList.add('hidden');
                    }, 5000);
                }

                function hasInvoiceChanged(current, original) {
                    const fieldsToCompare = ['invoice_number', 'billed_to', 'due_date', 'address', 'currency', 'note', 'status'];

                    for (let key of fieldsToCompare) {
                        if (current[key] !== original[key]) return true;
                    }

                    const origItems = original.items || [];
                    const currItems = current.items || [];

                    if (currItems.length !== origItems.length) return true;

                    for (let currItem of currItems) {
                        const origItem = origItems.find(i => String(i.id) === String(currItem.id));
                        if (!origItem) return true;

                        if (
                            currItem.item_name !== origItem.item_name ||
                            currItem.qty !== origItem.qty ||
                            currItem.rate_enabled !== origItem.rate_enabled ||
                            parseFloat(currItem.rate || 0) !== parseFloat(origItem.rate || 0) ||
                            parseFloat(currItem.total) !== parseFloat(origItem.total)
                        ) {
                            return true;
                        }
                    }

                    return false;
                }
            });


        });

        
        function copyLink(){
            const link = 'your-link-to-copy';

            navigator.clipboard.writeText(link).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Link copied to clipboard!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            }).catch(err => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Failed to copy link!',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        }
    </script>
</body>

</html>
