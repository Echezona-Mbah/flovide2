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
                                <h1 class="text-xl font-extrabold leading-tight">New Invoice</h1>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-2">
                                    <span class="w-3 h-3 rounded-full bg-gray-400"></span>
                                    <span class="text-sm text-gray-600 select-text">Unsaved</span>
                                </div>
                                <button disabled class="flex items-center space-x-2 rounded-full border border-gray-300 px-4 py-2 text-gray-400 cursor-not-allowed select-none"
                                    aria-label="Copy Link">
                                    <i class="far fa-copy"></i>
                                    <span class="hidden md:flex">Copy Link</span>
                                </button>
                            </div>
                        </section>
                        <section class="flex flex-col lg:flex-row gap-8">
                            <!-- Left form -->
                            <section class="flex-1 max-w-full lg:max-w-[600px] space-y-6">
                                <!-- Invoice Details -->
                                <div>
                                    <h2 class="font-semibold text-sm mb-3">Invoice Details</h2>
                                    <label for="billedTo" class="block mb-1 text-xs font-semibold text-gray-700">Billed
                                        to</label>
                                    <select id="billedTo"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                        aria-label="Billed to email">
                                        <option selected>florence@gmail.com</option>
                                        <option>example1@gmail.com</option>
                                        <option>example2@gmail.com</option>
                                    </select>
                                </div>

                                <div class="flex flex-wrap gap-4 mb-4">
                                    <div class="flex-1 min-w-[150px]">
                                        <label for="invoiceNumber"
                                            class="block mb-1 text-xs font-semibold text-gray-700">Invoice
                                            number</label>
                                        <input id="invoiceNumber" type="text" value="NEXG-IN001"
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />
                                    </div>
                                    <div class="flex-1 min-w-[150px]">
                                        <label for="dueDate" class="block mb-1 text-xs font-semibold text-gray-700">Due
                                            date</label>
                                        <div class="relative">
                                            <input id="dueDate" type="date" value="2025-08-27"
                                                class="w-full rounded-md border border-gray-300 px-3 py-2 pr-10 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Due date" />
                                            <i
                                                class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="address"
                                        class="block mb-1 text-xs font-semibold text-gray-700">Address</label>
                                    <input id="address" type="text"
                                        value="2972 Westheimer Rd. Santa Ana, Illinois 85486"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]" />
                                </div>

                                <div class="mb-6">
                                    <label for="currency"
                                        class="block mb-1 text-xs font-semibold text-gray-700">Currency</label>
                                    <select id="currency"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                        aria-label="Currency">
                                        <option selected>
                                            🇬🇧 British Pound
                                        </option>
                                        <option>🇺🇸 US Dollar</option>
                                        <option>🇪🇺 Euro</option>
                                    </select>
                                </div>

                                <!-- Invoice Items -->
                                <div>
                                    <h3 class="font-semibold text-sm mb-3 flex items-center gap-2">
                                        Invoice Items
                                        <button type="button"
                                            class="ml-auto flex items-center gap-1 rounded-full border border-[#3B82F6] bg-[#E0F2FE] px-3 py-1 text-[#3B82F6] text-sm font-semibold hover:bg-[#bae6fd] transition-colors">
                                            <i class="fas fa-plus"></i> Add Item
                                        </button>
                                    </h3>

                                    <section class="overflow-auto">
                                        <!-- Item 1 -->
                                        <div class="grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3"
                                            aria-label="Invoice item 1">
                                            <label for="item1"
                                                class="text-xs font-semibold text-gray-700 block">Item</label>
                                            <label for="qty1"
                                                class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                                            <label for="rate1"
                                                class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                                            <label for="total1"
                                                class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                                            <input id="item1" type="text" value="UX/UI Design"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Item description" />
                                            <input id="qty1" type="number" value="100"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Quantity" />
                                            <input id="rate1" type="number" value="85"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Rate" />
                                            <input id="total1" type="text" value="8,500" disabled
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-400 text-center bg-gray-50 cursor-not-allowed"
                                                aria-label="Total" />
                                        </div>

                                        <!-- Item 2 -->
                                        <div class="grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3"
                                            aria-label="Invoice item 2">
                                            <label for="item2"
                                                class="text-xs font-semibold text-gray-700 block">Item</label>
                                            <label for="qty2"
                                                class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                                            <label for="rate2"
                                                class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                                            <label for="total2"
                                                class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                                            <input id="item2" type="text" value="Site deployment"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-0 focus:border-gray-300"
                                                aria-label="Item description" />
                                            <input id="qty2" type="number" value="1"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-0 focus:border-gray-300"
                                                aria-label="Quantity" />
                                            <div class="flex justify-center items-center">
                                                <div class="relative inline-block w-10 h-5 rounded-full bg-gray-300 cursor-default"
                                                    aria-label="Rate toggle off" role="switch" aria-checked="false"
                                                    tabindex="0">
                                                    <div
                                                        class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-gray-400 transition-transform">
                                                    </div>
                                                </div>
                                            </div>
                                            <input id="total2" type="text" value="500"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center"
                                                aria-label="Total" />
                                        </div>

                                        <!-- Item 3 -->
                                        <div class="grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3"
                                            aria-label="Invoice item 3">
                                            <label for="item3"
                                                class="text-xs font-semibold text-gray-700 block">Item</label>
                                            <label for="qty3"
                                                class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                                            <label for="rate3"
                                                class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                                            <label for="total3"
                                                class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                                            <input id="item3" type="text" value="Macbook Pro 2024"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-0 focus:border-gray-300"
                                                aria-label="Item description" />
                                            <input id="qty3" type="number" value="1"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-0 focus:border-gray-300"
                                                aria-label="Quantity" />
                                            <div class="flex justify-center items-center">
                                                <div class="relative inline-block w-10 h-5 rounded-full bg-gray-300 cursor-default"
                                                    aria-label="Rate toggle off" role="switch" aria-checked="false"
                                                    tabindex="0">
                                                    <div
                                                        class="absolute left-0.5 top-0.5 w-4 h-4 rounded-full bg-gray-400 transition-transform">
                                                    </div>
                                                </div>
                                            </div>
                                            <input id="total3" type="text" value="2800"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center"
                                                aria-label="Total" />
                                        </div>

                                        <!-- Item 4 -->
                                        <div class="grid grid-cols-[3fr_1fr_1fr_1fr] items-center gap-3 mb-3"
                                            aria-label="Invoice item 4">
                                            <label for="item4"
                                                class="text-xs font-semibold text-gray-700 block">Item</label>
                                            <label for="qty4"
                                                class="text-xs font-semibold text-gray-700 block text-center">QTY</label>
                                            <label for="rate4"
                                                class="text-xs font-semibold text-gray-700 block text-center">Rate</label>
                                            <label for="total4"
                                                class="text-xs font-semibold text-gray-700 block text-center">Total</label>

                                            <input id="item4" type="text" value="Mobile app development"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Item description" />
                                            <input id="qty4" type="number" value="250"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Quantity" />
                                            <input id="rate4" type="number" value="90"
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 text-center focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]"
                                                aria-label="Rate" />
                                            <input id="total4" type="text" value="22,500" disabled
                                                class="rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-400 text-center bg-gray-50 cursor-not-allowed"
                                                aria-label="Total" />
                                        </div>
                                    </section>
                                </div>

                                <div>
                                    <label for="note" class="block mb-1 text-xs font-semibold text-gray-700">Add a
                                        note
                                        (optional)</label>
                                    <textarea id="note" rows="4"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 resize-none focus:outline-none focus:ring-1 focus:ring-[#3B82F6] focus:border-[#3B82F6]">Note that the “QTY” field for all items except site deployment and Macbook Pro 2024, refers to the number of work hours spent.</textarea>
                                </div>

                                <div class="flex gap-4 flex-wrap">
                                    <button type="submit"
                                        class="rounded-full bg-[#BFDBFE] text-[#1E40AF] px-6 py-2 text-sm font-semibold hover:bg-[#93c5fd] transition-colors">
                                        Create Invoice
                                    </button>
                                    <button type="button"
                                        class="rounded-full border border-gray-300 px-6 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors flex items-center gap-2">
                                        <i class="far fa-file-alt"></i> Save as Draft
                                    </button>
                                </div>
                            </section>

                            <!-- Right preview -->
                            <section
                                class="flex-1 max-w-full lg:max-w-[600px] bg-[#F3F4F6] rounded-xl md:p-6 p-2 select-text"
                                aria-label="Invoice preview">
                                <h3 class="font-semibold text-sm mb-3">Preview</h3>
                                <div class="bg-white rounded-xl md:p-6 p-2 space-y-6 max-w-full overflow-hidden"
                                    aria-label="Invoice preview content">
                                    <div class="flex justify-between items-center">
                                        <h2 class="font-extrabold text-3xl leading-tight">Invoice</h2>
                                        <div class="flex flex-col items-center text-center">
                                            <div
                                                class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center mb-1">
                                                <div class="w-4 h-4 rounded-full bg-black"></div>
                                            </div>
                                            <span class="text-xs font-semibold">Nexus Global</span>
                                            <span
                                                class="text-[10px] text-gray-400 leading-tight">hi@nexusglobal.com</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-between text-xs text-gray-700">
                                        <div class="flex items-center gap-1">
                                            <span>Invoice number</span>
                                            <span class="font-semibold">NEXG-IN001</span>
                                        </div>
                                        <div></div>
                                    </div>

                                    <div
                                        class="border border-gray-300 rounded-md overflow-hidden text-xs text-gray-700">
                                        <div class="grid grid-cols-2 border-b border-gray-300">
                                            <div class="p-3 border-r border-gray-300">
                                                <p class="font-semibold mb-1">Billed To</p>
                                                <p>florence@gmail.com</p>
                                            </div>
                                            <div class="p-3">
                                                <p class="font-semibold mb-1">Due Date</p>
                                                <p class="font-semibold">27 August, 2025</p>
                                            </div>
                                        </div>
                                        <div class="p-3 text-xs font-semibold">
                                            <p>Address</p>
                                            <p class="font-semibold">
                                                2972 Westheimer Rd. Santa Ana, Illinois 85486
                                            </p>
                                        </div>
                                    </div>

                                    <table
                                        class="w-full border border-gray-300 text-xs text-gray-700 border-collapse overflow-auto">
                                        <thead>
                                            <tr class="bg-gray-100 border-b border-gray-300">
                                                <th class="border-r border-gray-300 p-2 text-left">Items</th>
                                                <th class="border-r border-gray-300 p-2 text-center">QTY</th>
                                                <th class="border-r border-gray-300 p-2 text-center">Rate</th>
                                                <th class="p-2 text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b border-gray-300">
                                                <td class="border-r border-gray-300 p-2">UX/UI Design</td>
                                                <td class="border-r border-gray-300 p-2 text-center">100</td>
                                                <td class="border-r border-gray-300 p-2 text-center">85</td>
                                                <td class="p-2 text-center">£ 8,500</td>
                                            </tr>
                                            <tr class="border-b border-gray-300">
                                                <td class="border-r border-gray-300 p-2">Site deployment</td>
                                                <td class="border-r border-gray-300 p-2 text-center">1</td>
                                                <td class="border-r border-gray-300 p-2 text-center">-</td>
                                                <td class="p-2 text-center">£ 500</td>
                                            </tr>
                                            <tr class="border-b border-gray-300">
                                                <td class="border-r border-gray-300 p-2">Macbook Pro 2024</td>
                                                <td class="border-r border-gray-300 p-2 text-center">1</td>
                                                <td class="border-r border-gray-300 p-2 text-center">-</td>
                                                <td class="p-2 text-center">£ 2,800</td>
                                            </tr>
                                            <tr>
                                                <td class="border-r border-gray-300 p-2">
                                                    Mobile app development
                                                </td>
                                                <td class="border-r border-gray-300 p-2 text-center">250</td>
                                                <td class="border-r border-gray-300 p-2 text-center">90</td>
                                                <td class="p-2 text-center">£ 22,500</td>
                                            </tr>
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
                                            <span>Total</span>
                                            <span>£ 34,300</span>
                                        </div>
                                    </div>

                                    <div
                                        class="border border-gray-300 rounded-md p-3 text-[10px] text-gray-700 max-w-[400px]">
                                        <p class="font-semibold mb-1">Note:</p>
                                        <p>
                                            Note that the “QTY” field for all items except site deployment and
                                            Macbook Pro 2024, refers to the number of work hours spent.
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
