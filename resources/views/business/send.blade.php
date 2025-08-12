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
                Send to a beneficias
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative w-full">
            <section class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute w-full overflow-x-hidden right-[-2.3vw]">
                <section class="flex flex-col w-full min-h-screen bg-gray-50 p-6 md:p-10">
                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">My Beneficiaries</h2>
                        <p class="text-sm text-gray-500">Send money to your saved beneficiaries.</p>
                    </div>
                
                    <!-- Search bar -->
                    <form class="flex flex-col sm:flex-row gap-4 mb-8">
                        <input 
                            id="search" 
                            type="search" 
                            placeholder="Search by name, account number or email"
                            class="flex-1 rounded-full border border-gray-300 px-4 py-2 text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" 
                        />
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm hover:bg-blue-700 transition">
                            Search
                        </button>
                    </form>
                
                    <!-- Beneficiaries Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @forelse($beneficiaries as $beneficiary)
                            <div 
                            class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 cursor-pointer"
                            data-id="{{ $beneficiary->recipient_id }}"
                            data-account-name="{{ $beneficiary->account_name }}"
                            data-account-number="{{ $beneficiary->account_number }}"
                            data-bank="{{ $beneficiary->bank }}"
                            data-currency="{{ $beneficiary->currency }}"
                            data-country="{{ $beneficiary->country }}"
                            data-amount="100"
                            data-gets="0.06"
                            onclick="openModalFromElement(this)">
                    
                           
                                                        
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold text-lg">
                                        {{ Str::substr($beneficiary->account_name ?? 'N/A', 0, 1) }}
                                    </div>
                
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-800 truncate w-40" id="beneficiaryName">{{ $beneficiary->account_name }}</h3>
                                        <p class="text-xs text-gray-400">Saved Beneficiary</p>
                                    </div>
                                </div>
                
                                <div class="mb-3 space-y-1 text-sm text-gray-700">
                                    <p><strong id="beneficiaryAccount">Account:</strong> {{ $beneficiary->account_number }}</p>
                                    <p><strong id="beneficiaryBank">Bank:</strong> {{ $beneficiary->bank }}</p>
                                </div>
                            </div>
                       
                        @empty
                            <div class="col-span-full text-center text-gray-500">
                                You haven't added any beneficiaries yet.
                            </div>
                        @endforelse
                    </div>
                
                        <!-- Transaction Modal -->
                        <div id="transactionModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
                            <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
                                <!-- Close Button -->
                                <button id="closeModalBtn" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-lg">&times;</button>

                                <h2 class="text-lg font-bold mb-4">Send Money</h2>

                                <!-- First Section: You Send -->
                                <div class="flex flex-col gap-6 w-full max-w-md">

                                    <!-- Amount + Currency -->
                                    <div class="flex items-center border rounded-lg overflow-hidden">
                                      <span id="sendCurrencySymbol" class="px-3 text-sm text-gray-600 bg-gray-50 border-r">₦</span>
                                        <input 
                                                type="text" 
                                                id="sendAmount"
                                                inputmode="decimal"
                                                class="w-full px-3 py-2 text-sm focus:outline-none"
                                                value="100.00" 
                                                placeholder="You Send"
                                            />



                                        @if (!empty($balanceList))
                                        <div class="flex items-center gap-2 px-3 py-2 bg-gray-50">
                                          <img 
                                                id="currencyFlag"
                                                src="https://flagcdn.com/24x18/{{ strtolower($balanceList[0]->currency_meta['country'] ?? 'us') }}.png"
                                                alt="Flag"
                                                class="w-5 h-auto rounded shadow"
                                            />
                                           <span id="currencySymbol" class="text-sm font-medium">
                                                {{ $balanceList[0]->currency_meta['symbol'] ?? '₦' }}
                                            </span>

                                            <select 
                                                id="currency" 
                                                class="bg-transparent text-sm focus:outline-none"
                                            >
                                               @foreach ($balanceList as $balance)
                                                    <option 
                                                        value="{{ $balance->currency }}"
                                                        data-id="{{ $balance->id }}"
                                                        data-symbol="{{ $balance->currency_meta['symbol'] }}"
                                                        data-country="{{ $balance->currency_meta['country'] }}"
                                                        data-balance="{{ $balance->balance }}">
                                                        {{ $balance->currency }} - {{ $balance->name }} ({{ $balance->currency_meta['symbol'] }}{{ number_format($balance->amount) }})
                                                    </option>

                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Exchange Info -->
                                    <div class="space-y-2 mb-6 text-sm text-gray-700">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Exchange rate:</span>
                                            <span id="exchangeRateText">NGN 1.00 = $0.0006</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Transfer fee:</span>
                                            <span id="transferFeeText">₦55.00</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium">Delivery:</span>
                                            <span>Usually within 15 minutes(Can take up to 2 hours)</span>
                                        </div>
                                    </div>
                                    <!-- Loader -->
                                    <div id="rateLoader" class="flex items-center justify-center gap-2 text-sm text-gray-500 mb-2 hidden">
                                        <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                        </svg>
                                        <span>Refreshing exchange rate...</span>
                                    </div>

                                    <!-- Recipient Gets -->
                                    <h5 class="text-sm font-semibold text-gray-800 mb-2">Beneficia gets</h5>
                                    <div class="space-y-2 mb-6 text-sm text-gray-700">
                                        <div class="flex justify-between">
                                           <div class="font-medium flex items-center gap-1">
<span id="recipientSymbol"></span>
                                                <span id="recipientAmount">0.00</span>
                                            </div>

                                            <span class="font-medium flex items-center gap-1">
                                                <img id="recipientFlag" src="https://flagcdn.com/24x18/us.png" alt="Recipient Flag" class="w-5 h-auto rounded shadow" />
                                                <span id="recipientGets">USD</span>
                                            </span>

                                        </div>
                                    </div>
                                </div>

                                <!-- Send Button -->
                                <button id="sendBtn" class="w-full bg-green-600 text-white py-2 rounded-lg text-center hover:bg-green-700 transition">
                                    Send
                                </button>
                                
                            </div>
                        </div>

                        <!-- Transaction Summary Modal -->
                        <form method="POST" action="{{ route('send') }}">
                            @csrf

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

                                @if (session('error'))
                                    <script>
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'error',
                                            title: @json(session('error')),
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


                            <div id="summaryModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
                                <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative space-y-6">
                                    <button type="button" id="closeSummaryModalBtn" class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-lg">&times;</button>
                        
                                    <h2 class="text-xl font-bold text-center text-gray-800">Confirm Transaction</h2>
                        
                                    <!-- Section 1: Beneficiary Info -->
                                    <div class="bg-gray-100 rounded-lg p-4">
                                        <h3 class="text-sm font-semibold text-gray-600 mb-2">Beneficiary Info</h3>
                                        <div class="flex justify-between text-sm text-gray-800">
                                            <span>Name:</span>
                                            <span id="summaryName">...</span>
                                        </div>
                                        <div class="flex justify-between text-sm text-gray-800 mt-1">
                                            <span>Account No:</span>
                                            <span id="summaryAccount">...</span>
                                        </div>
                                    </div>
                        
                                    <!-- Section 2: Transaction Details -->
                                    <div class="bg-white border rounded-lg p-4 shadow-sm space-y-2 text-sm text-gray-700">
                                        {{-- <input type="hidden" name="recipient_id" id="recipientIdInput">
                                        <input type="hidden" name="balance_id" value="{{ $balanceList[0]['id'] ?? '' }}">
                                        <input type="hidden" name="amount" id="amountInput">
                                        <input type="hidden" name="reference" value="For invoice">
                         --}}
                                        <input type="hidden" name="recipient_id" id="recipientIdInput">
                                        <input type="hidden" name="balance_id" id="balanceIdInput" value="{{ $balanceList[0]['id'] ?? '' }}">
                                        <input type="hidden" name="amount" id="amountInput">
                                        <input type="hidden" name="reference" value="For invoice">
                                        <input type="hidden" name="transfer_fee" id="transferFeeInput">
                                        <input type="hidden" name="total_amount" id="totalAmountInput">
                                        <input type="hidden" name="exchange_rate" id="exchangeRateInput">
                                        <input type="hidden" name="recipient_amount" id="recipientAmountInput">

                                        <div class="flex justify-between">
                                            <span>Bank:</span>
                                            <span id="summaryBank">...</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Amount Sent:</span>
                                            <span id="summaryAmountSent">₦0.00</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Transfer Fee:</span>
                                            <span id="summaryFee">₦0.00</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Total:</span>
                                            <span id="summaryTotal">₦0.00</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Exchange Rate:</span>
                                            <span id="summaryRate">...</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>They'll Receive:</span>
                                            <span id="summaryReceive">...</span>
                                        </div>
                                    </div>
                        
                                    <!-- Final Process Button -->
                                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-center hover:bg-blue-700 transition">
                                        Process Transaction
                                    </button>
                                </div>
                            </div>
                        </form>
                        

                </section>
                
               
                
                
        </section>
    </main>


<!-- JavaScript -->
 <!-- JavaScript -->




    

    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const currencySelect = document.getElementById("currency");
            const amountInput = document.getElementById("sendAmount");
            const flagImg = document.getElementById("currencyFlag");
            const symbolSpan = document.getElementById("currencySymbol");
            const exchangeRateText = document.getElementById("exchangeRateText");
            const transferFeeText = document.getElementById("transferFeeText");
            const recipientAmountText = document.getElementById("recipientAmount");
            const recipientGetsText = document.getElementById("recipientGets");
            const sendCurrencySymbol = document.getElementById("sendCurrencySymbol");

            function formatNumberInput(input) {
                const cleaned = input.replace(/,/g, '');
                const number = parseFloat(cleaned);
                if (isNaN(number)) return '';
                return number.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }


        
            function getRecipientCurrency() {
                const selectedBeneficiary = document.querySelector('.selected-beneficiary');
                if (selectedBeneficiary) {
                    return selectedBeneficiary.dataset.currency || "USD";
                }
                return "USD"; // fallback
            }
        
            async function fetchExchangeRate(fromCurrency, toCurrency, amount) {
                const url = `${window.location.origin}/exchange-rate?from_currency=${fromCurrency}&to_currency=${toCurrency}&amount=${amount}`;
                console.log("🌍 Fetching exchange rate from:", url);
        
                try {
                    const response = await fetch(url);
                    const contentType = response.headers.get("content-type") || "";
        
                    if (!response.ok || !contentType.includes("application/json")) {
                        const text = await response.text();
                        console.error("❌ Response not JSON:", text.substring(0, 100));
                        throw new Error("Invalid response format");
                    }
        
                    const data = await response.json();
                    console.log("✅ Exchange rate data received:", data);
                    return data;
                } catch (err) {
                    console.error("🚨 Exchange rate fetch error:", err.message);
                    return null;
                }
            }
        
            async function updateRateDisplay() {
                const selectedOption = currencySelect.options[currencySelect.selectedIndex];
                const fromCurrency = selectedOption.value;
                const symbol = selectedOption.dataset.symbol;
                const country = selectedOption.dataset.country;
                const amount = parseFloat(amountInput.value || 0);
                const recipientCurrency = getRecipientCurrency();

                // Show loader
                document.getElementById('rateLoader').classList.remove('hidden');

                // Update flag and currency display
                flagImg.src = `https://flagcdn.com/24x18/${country.toLowerCase()}.png`;
                symbolSpan.textContent = symbol;

                // ✅ Update the symbol next to the amount input
                sendCurrencySymbol.textContent = symbol;

                if (fromCurrency === recipientCurrency) {
                    exchangeRateText.textContent = `${fromCurrency} 1.00 = ${recipientCurrency} 1.00`;
                    recipientAmountText.textContent = amount.toFixed(2);
                    transferFeeText.textContent = `${symbol}55.00`;
                    document.getElementById('rateLoader').classList.add('hidden');
                    return;
                }

                const rateData = await fetchExchangeRate(fromCurrency, recipientCurrency, amount);

                if (rateData && rateData.rate) {
                    const rate = parseFloat(rateData.rate);
                    const recipientAmount = parseFloat(rateData.converted_amount);
                    const fee = parseFloat(rateData.transfer_fee || 0);

                    exchangeRateText.textContent = `${fromCurrency} 1.00 = ${recipientCurrency} ${rate}`;
                    transferFeeText.textContent = `${symbol}${fee.toFixed(2)}`;
                    recipientAmountText.textContent = recipientAmount.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    

                    const recipientSymbol = document.getElementById("recipientSymbol");
                    const currencySymbols = {
                        USD: "$", NGN: "₦", EUR: "€", GBP: "£", GHS: "₵", KES: "KSh", ZAR: "R",
                        XOF: "CFA", XAF: "FCFA", BWP: "P", TZS: "TSh", UGX: "USh", MWK: "MK",
                        CAD: "C$", AUD: "A$", INR: "₹", CNY: "¥", JPY: "¥", RUB: "₽", BRL: "R$",
                        MXN: "Mex$", AED: "د.إ", SAR: "﷼", QAR: "ر.ق", EGP: "£", LKR: "Rs", PKR: "₨",
                        THB: "฿", MYR: "RM", IDR: "Rp", PHP: "₱", KRW: "₩", CHF: "Fr", SEK: "kr",
                        NOK: "kr", DKK: "kr", CZK: "Kč", PLN: "zł", HUF: "Ft", TRY: "₺", ARS: "$",
                        CLP: "$", COP: "$", PEN: "S/"
                    };

                    if (recipientSymbol) {
                        recipientSymbol.textContent = currencySymbols[recipientCurrency] || recipientCurrency;
                    }

                } else {
                    exchangeRateText.textContent = "Rate unavailable";
                    transferFeeText.textContent = `${symbol}0.00`;
                    recipientAmountText.textContent = "0.00";
                }

                // Hide loader
                document.getElementById('rateLoader').classList.add('hidden');
            }

          




        
            window.openModalFromElement = function (el) {
                document.querySelectorAll('.selected-beneficiary').forEach(el => el.classList.remove('selected-beneficiary'));
                el.classList.add('selected-beneficiary');
        
                const sendAmount = parseFloat(el.dataset.amount || 100);
                const gets = parseFloat(el.dataset.gets || 0);
                const currency = el.dataset.currency || "USD";
                const accountName = el.dataset.accountName || "N/A";
                const accountNumber = el.dataset.accountNumber || "N/A";
                const bank = el.dataset.bank || "N/A";
                const recipientId = el.dataset.id || "";
        
                console.log("👤 Selected Beneficiary:");
                console.log("Name:", accountName);
                console.log("Account:", accountNumber);
                console.log("Bank:", bank);
                console.log("Currency:", currency);
                console.log("Gets rate:", gets);
                console.log("Recipient ID:", recipientId);
        
                document.getElementById("beneficiaryName").textContent = accountName;
                document.getElementById("beneficiaryAccount").textContent = accountNumber;
                document.getElementById("beneficiaryBank").textContent = bank;
        
                recipientCurrency = currency;
                amountInput.value = sendAmount;
               recipientGetsText.textContent = currency;
                document.getElementById("recipientFlag").src = `https://flagcdn.com/24x18/${el.dataset.country?.toLowerCase() || "us"}.png`;
                recipientAmountText.textContent = (sendAmount * gets).toFixed(2);
        
                updateRateDisplay();
                document.getElementById("transactionModal")?.classList?.remove("hidden");
            };
        
            currencySelect?.addEventListener("change", () => {
                console.log("🔁 Currency changed");
                updateRateDisplay();
            });
        
            amountInput?.addEventListener("input", () => {
                console.log("✍️ Amount input changed:", amountInput.value);
                updateRateDisplay();
            });
        
            document.getElementById("closeModalBtn")?.addEventListener("click", () => {
                console.log("❌ Transaction modal closed");
                document.getElementById("transactionModal").classList.add("hidden");
            });
        
            window.addEventListener("click", function (e) {
                if (e.target.id === "transactionModal") {
                    console.log("🔒 Clicked outside modal to close");
                    document.getElementById("transactionModal").classList.add("hidden");
                }
            });
        
            document.getElementById('sendBtn').addEventListener('click', () => {
                const name = document.getElementById('beneficiaryName')?.textContent || "N/A";
                const account = document.getElementById('beneficiaryAccount')?.textContent || "N/A";
                const bank = document.getElementById('beneficiaryBank')?.textContent || "N/A";
                const amount = parseFloat(document.getElementById('sendAmount')?.value || 0);
                const feeText = document.getElementById('transferFeeText')?.textContent || '₦0';
                const fee = parseFloat(feeText.replace(/[^\d.]/g, '')) || 0;
                const total = amount + fee;
                const rate = document.getElementById('exchangeRateText')?.textContent || '';
                const receive = document.getElementById('recipientAmount')?.textContent || '0.00';
                const currency = document.getElementById('recipientGets')?.textContent || 'USD';
                const recipientId = document.querySelector('.selected-beneficiary')?.dataset?.id || '';
                const symbol = document.getElementById('sendCurrencySymbol')?.textContent || '₦';

                // UI summary display
                document.getElementById('summaryName').textContent = name;
                document.getElementById('summaryAccount').textContent = account;
                document.getElementById('summaryBank').textContent = bank;
                document.getElementById('summaryAmountSent').textContent = `${symbol}${amount.toFixed(2)}`;
                document.getElementById('summaryFee').textContent = `${symbol}${fee.toFixed(2)}`;
                document.getElementById('summaryTotal').textContent = `${symbol}${total.toFixed(2)}`;
                document.getElementById('summaryRate').textContent = rate;
                document.getElementById('summaryReceive').textContent = `${currency} ${receive}`;

                // 🛠 Hidden input field updates
                document.getElementById('recipientIdInput').value = recipientId;
                document.getElementById('amountInput').value = amount.toFixed(2);
                document.getElementById('transferFeeInput').value = fee.toFixed(2);
                document.getElementById('totalAmountInput').value = total.toFixed(2);
                document.getElementById('exchangeRateInput').value = rate;
                document.getElementById('recipientAmountInput').value = receive.replace(/,/g, '');

                // Get balance ID from selected option
                const selectedOption = document.getElementById('currency')?.selectedOptions[0];
                const balanceId = selectedOption?.getAttribute('data-id') || '';
                document.getElementById('balanceIdInput').value = balanceId;

                document.getElementById('summaryModal').classList.remove('hidden');
            });

        
            document.getElementById("closeSummaryModalBtn")?.addEventListener("click", () => {
                console.log("❌ Summary modal closed");
                document.getElementById("summaryModal").classList.add("hidden");
            });
        
            console.log("🚀 DOM Ready – initializing with first rate check");
            updateRateDisplay();
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