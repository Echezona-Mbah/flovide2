@include('business.head')


<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    @include('business.header')
<style>
    <style>
.custom-scrollbar::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}
.custom-scrollbar {
    -ms-overflow-style: none; /* IE and Edge */
    scrollbar-width: none; /* Firefox */
}
</style>

</style>
    <!-- Sidebar -->
    @include('business.sidebar')

    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Bills payment </h1>
                @include('business.header_notifical')

        </header>
        <section class=" relative w-full">
            <section
                class="bg-white text-gray-700 min-h-screen  md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[100vw] mx-auto">
                    <!-- Top Stats -->

                    <!-- Main Content -->
                    <section class="bg-white text-gray-900 max-w-[100vw] relative">
                        <div class=" md:w-[75vw] w-[95vw] flex flex-col justify-center p-4  md:flex-row gap-8">
                            <div class="flex-1 md:max-w-md justify-center">
                                <h2 class="text-base font-semibold text-gray-900 mb-6">Select a Category</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-[#E3EDF9] rounded-xl p-6 flex flex-col gap-4 max-w-[280px]"
                                        id="openDstvBill">
                                        <div
                                            class="w-9 h-9 bg-[#2B6CB0] rounded-lg flex items-center justify-center text-white text-lg">
                                            <i class="fas fa-satellite-dish"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">DSTV</h3>
                                    </div>

                                    <div class="bg-[#D9F0E0] rounded-xl p-6 flex flex-col gap-4 max-w-[280px]"
                                        id="openDataBill">
                                        <div
                                            class="w-9 h-9 bg-[#2F855A] rounded-lg flex items-center justify-center text-white text-lg">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Data</h3>
                                    </div>

                                    <div class="bg-[#FAF8E6] rounded-xl p-6 flex flex-col gap-4 max-w-[280px] col-span-1"
                                        id="openElectricityBill">
                                        <div
                                            class="w-9 h-9 bg-[#B5A900] rounded-lg flex items-center justify-center text-white text-lg">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Electricity</h3>
                                    </div>
                                </div>
                            </div>
                           <div class="flex-1 md:max-w-lg w-full">
                                <h2 class="text-base font-semibold text-gray-900 mb-6">Payment History</h2>

                                @if($payments->isEmpty())
                                    <div class="bg-[#F3F3F3] w-full rounded-xl md:h-[100vh] h-[50vh] flex items-center justify-center text-gray-600 text-sm font-normal">
                                        <i class="fas fa-receipt mr-2"></i> No payment yet
                                    </div>
                                @else
                                    <!-- Scrollable Container -->
                                    <div class="bg-gray-100 rounded-3xl w-full p-6 text-[13px] max-h-[500px] overflow-y-auto space-y-3 custom-scrollbar">
                                        @foreach($payments as $bill)
                                            <div class="bg-white rounded-xl px-6 py-4 flex justify-between items-center font-semibold text-gray-900">
                                                <span>{{ ucfirst($bill->service_id) }}</span>
                                                <span>{{ ucfirst($bill->status) }}</span>
                                                <span>{{ \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y') }}</span>
                                                <span>₦{{ number_format($bill->amount, 0) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        </div>

                        <section>


                            <!-- DSTV bill -->
                            <section
                                class="absolute top-[10em] hidden md:top-[-4vh] md:right-[-30%] w-full h-[50vh] md:h-[100vh] flex items-center justify-center"
                                id="DSTVBillContainer">

                                <div class="w-[90vw]   md:w-[40vw] md:max-w-[480px]  bg-white rounded-xl shadow-lg p-4 md:p-8 relative"
                                    style="box-shadow: 0 0 20px rgb(0 0 0 / 0.1)">
                                    <button type="button" aria-label="Close" id="closeDSTVBill"
                                        class="absolute top-14 left-6 text-black text-xl  font-semibold focus:outline-none">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <h3 class="font-semibold text-lg mb-6 pt-20">Pay DSTV bill</h3>

                                    <form class="flex flex-col gap-6" method="POST" id="billPaymentForm" action="{{ route('billpayments.store') }}">
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
                                        <input type="hidden" name="form_type" value="dstv">
                                        <label class="flex flex-col text-gray-600 text-sm font-semibold gap-1">
                                            Smart Card Number
                                            <input name="billers_code" type="text" id="smartcardInput" placeholder="1234567890"
                                                class="border border-gray-300 rounded-md p-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                        </label>
                                    
                                        <div id="customerDetails" class="hidden mt-4 bg-white shadow-md rounded-lg p-4 border border-blue-100 text-sm text-gray-800 space-y-2 transition-all duration-300">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5.121 17.804A9 9 0 1119.879 6.196M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <span class="font-semibold">Customer Name:</span>
                                                <span id="customerName" class="ml-1 text-blue-700 font-medium"></span>
                                            </div>
                                        
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 11c0-1.105-.895-2-2-2s-2 .895-2 2 .895 2 2 2 2-.895 2-2zM20 12a8 8 0 11-16 0 8 8 0 0116 0z" />
                                                </svg>
                                                <span class="font-semibold">Smart Card Number:</span>
                                                <span id="customerNumber" class="ml-1 text-green-700 font-medium"></span>
                                            </div>
                                        </div>
                                        
                                    
                                        <label class="flex flex-col text-gray-600 text-sm font-semibold gap-1">
                                            Phone Number
                                            <input name="phone" type="text" placeholder="1234567890"
                                                class="border border-gray-300 rounded-md p-2 text-gray-400 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                        </label>
                                    
                                        <label class="flex flex-col text-gray-600 text-sm font-semibold gap-1">
                                            Product
                                            <select name="variation_code"
                                                class="border border-gray-300 rounded-md p-2 text-black focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                <option selected disabled>Select a Product</option>
                                                @foreach ($variations as $variation)
                                                    <option value="{{ $variation['code'] }}">
                                                        {{ $variation['name'] }} - {{ number_format($variation['amount']) }} {{ strtoupper($variation['currency'] ?? 'NGN') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </label>
                                        
                                        <!-- Add Alpine.js -->
                                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                                        <div x-data="{open: false, selected: null, selectCurrency(flag, label, code) {this.selected = { flag, label, code }; this.open = false; }}" class="relative w-full">
                                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Balance</label>

                                            <!-- Button -->
                                            <div @click="open = !open"  class="flex items-center justify-between border border-gray-300 rounded-md p-2 cursor-pointer bg-white w-full">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="selected">
                                                        <div class="flex items-center gap-2">
                                                            <img :src="selected.flag" class="w-5 h-4" />
                                                            <span x-text="selected.label"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!selected">
                                                        <span class="text-gray-400">Select currency</span>
                                                    </template>
                                                </div>
                                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                            <!-- Dropdown -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto w-full"
                                                x-transition>
                                                @foreach ($balances as $balance)
                                                    @php
                                                        $symbol = $balance->currency_meta['symbol'] ?? '';
                                                        $countryCode = strtoupper($balance->currency_meta['country'] ?? 'US');
                                                        $flag = "https://flagcdn.com/w20/" . strtolower($countryCode) . ".png";
                                                        $label = "$symbol {$balance->currency} (" . number_format($balance->amount, 2) . ")";
                                                        $code = $balance->id;
                                                    @endphp
                                                    <div @click="selectCurrency('{{ $flag }}', '{{ $label }}', '{{ $code }}')"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 cursor-pointer w-full">
                                                        <img src="{{ $flag }}" class="w-5 h-4" />
                                                        <span>{{ $label }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Hidden input for form -->
                                            <input type="hidden" name="balance" :value="selected?.code">
                                        </div>




                                        <!-- Amount input -->
                                        {{-- <label class="flex flex-col text-gray-600 text-sm font-semibold gap-1 mt-4">
                                            Amount
                                            <input name="amount" type="text" placeholder="12345678"
                                                class="border border-gray-300 rounded-md p-2 text-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                        </label> --}}

                                    
                                        <!-- Hidden field for service_id -->
                                        <input type="hidden" name="service_id" value="dstv"> <!-- or gotv, etc. -->
                                    
                                        <button type="submit"
                                            class="bg-[#C6D9EE] text-[#2F6DB3] font-semibold rounded-full w-[200px] py-2 px-8 w-max self-start">
                                            Pay Bill
                                        </button>
                                    </form>
                                    
                                </div>

                            </section>


                            <!-- Pay Electricity bill -->
                            <section
                                class="absolute hidden top-[10em] md:top-[-4vh] md:right-[-30%] w-full h-[50vh] md:h-[100vh] flex items-center justify-center"
                                id="ElectricityBillContainer">
                                <div class="w-[90vw]  md:w-[40vw] md:max-w-[480px]  bg-white rounded-xl shadow-lg p-4 md:p-8 relative"
                                    style="box-shadow: 0 0 20px rgb(0 0 0 / 0.1)">
                                    <button type="button" aria-label="Close" id="closeElectricityBill"
                                        class="absolute top-14 left-6 text-black text-xl  font-semibold focus:outline-none">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <h3 class="font-semibold text-lg mb-6 pt-20">Pay Electricity bill</h3>

                                    <form method="POST" action="{{ route('billpayments.store') }}" class="flex flex-col gap-5">
                                        @csrf
                                    
                                        {{-- Error Toast --}}
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
                                    
                                        {{-- Success Toast --}}
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
                                        <input type="hidden" name="form_type" value="electricity">

                                        {{-- Meter Number --}}
                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Meter Number
                                            <input type="text" id="electricityMeter" name="billers_code" placeholder="1234567890"
                                                class="border border-gray-300 rounded-md p-2 text-gray-700 placeholder-gray-400" required />
                                        </label>
                                    
                                        {{-- Electricity Company --}}
                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Select Electricity Company
                                            <select name="service_id" id="electricityCompany"
                                                class="border border-gray-300 rounded-md p-2 text-black focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                                                <option value="">-- Select Company --</option>
                                                @foreach ($electricitydata as $company)
                                                    <option value="{{ $company->service_id }}">{{ strtoupper($company->name) }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    
                                        {{-- Meter Type --}}
                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Type
                                            <select name="variation_code" id="electricityType"
                                                class="border border-gray-300 rounded-md p-2 text-black focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                                                <option value="prepaid">Prepaid</option>
                                                <option value="postpaid">Postpaid</option>
                                            </select>
                                        </label>
                                    
                                        {{-- Spinner --}}
                                        <div id="loadingSpinner" class="hidden text-blue-600 text-sm mt-2">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Verifying meter...
                                        </div>
                                    
                                        {{-- Verification Result --}}
                                        <div id="verifyDetails" class="mt-4"></div>
                                    
                                        {{-- Phone --}}
                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Phone Number
                                            <input type="text" name="phone" placeholder="08012345678"
                                                class="border border-gray-300 rounded-md p-2 text-gray-700 placeholder-gray-400" required />
                                        </label>
                                    
                                        <!-- Add Alpine.js -->
                                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                                        <div x-data="{open: false, selected: null, selectCurrency(flag, label, code) {this.selected = { flag, label, code }; this.open = false; }}" class="relative w-full">
                                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Balance</label>

                                            <!-- Button -->
                                            <div @click="open = !open"  class="flex items-center justify-between border border-gray-300 rounded-md p-2 cursor-pointer bg-white w-full">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="selected">
                                                        <div class="flex items-center gap-2">
                                                            <img :src="selected.flag" class="w-5 h-4" />
                                                            <span x-text="selected.label"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!selected">
                                                        <span class="text-gray-400">Select currency</span>
                                                    </template>
                                                </div>
                                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                            <!-- Dropdown -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto w-full"
                                                x-transition>
                                                @foreach ($balances as $balance)
                                                    @php
                                                        $symbol = $balance->currency_meta['symbol'] ?? '';
                                                        $countryCode = strtoupper($balance->currency_meta['country'] ?? 'US');
                                                        $flag = "https://flagcdn.com/w20/" . strtolower($countryCode) . ".png";
                                                        $label = "$symbol {$balance->currency} (" . number_format($balance->amount, 2) . ")";
                                                        $code = $balance->id;
                                                    @endphp
                                                    <div @click="selectCurrency('{{ $flag }}', '{{ $label }}', '{{ $code }}')"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 cursor-pointer w-full">
                                                        <img src="{{ $flag }}" class="w-5 h-4" />
                                                        <span>{{ $label }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Hidden input for form -->
                                            <input type="hidden" name="balance" :value="selected?.code">
                                        </div>
                                    
                                        {{-- Amount --}}
                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Amount
                                            <input type="text" name="amount" placeholder="1000"
                                                class="border border-gray-300 rounded-md p-2 text-gray-700 placeholder-gray-400" required />
                                        </label>
                                    
                                        {{-- <input type="hidden" name="service_id_hidden" value="electricity-service-id"> --}}
                                    
                                        {{-- Submit --}}
                                        <button type="submit"
                                            class="bg-[#D3E5F7] text-[#2F6DB3] font-semibold rounded-full py-2 px-8 w-max">
                                            Pay Bill
                                        </button>
                                    </form>
                                    
                                    
                                   
                                </div>
                            </section>


                            <!-- Buy Data -->
                            <section
                                class="absolute hidden top-[10em] md:top-[-4vh] md:right-[-30%] w-full h-[50vh] md:h-[100vh] flex items-center justify-center"
                                id="DataBillContainer">
                                <div class="w-[90vw]  md:w-[40vw] md:max-w-[480px]  bg-white rounded-xl shadow-lg p-4 md:p-8 relative"
                                    style="box-shadow: 0 0 20px rgb(0 0 0 / 0.1)">
                                    <button type="button" aria-label="Close" id="closeDataBill"
                                        class="absolute top-14 left-6 text-black text-xl  font-semibold focus:outline-none">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <h3 class="font-semibold text-lg mb-6 pt-20">Buy Data</h3>

                                    <form method="POST" action="{{ route('billpayments.store') }}" class="flex flex-col gap-5">
                                        @csrf
                                    
                                        {{-- Error Toast --}}
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
                                    
                                        {{-- Success Toast --}}
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
                                            <input type="hidden" name="form_type" value="data">

                                        <label class="flex flex-col gap-1 text-sm text-gray-600 font-semibold">
                                            Network
                                            <select name="service_id" id="network"
                                                class="border border-gray-300 rounded-md p-2 text-black focus:outline-none focus:ring-2 focus:ring-blue-300" required>
                                                <option value="">-- Select Company --</option>
                                                @foreach ($airTimedata as $company)
                                                    <option value="{{ $company->service_id }}">{{ strtoupper($company->name) }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    
                                        <div class="flex flex-col gap-1">
                                            <label for="phone"
                                                class="text-gray-500 font-semibold text-xs leading-4">Phone
                                                Number</label>
                                            <input id="phone" type="text" name="billers_code" placeholder="1234567890"
                                                class="border border-gray-300 rounded-md px-4 py-2 text-gray-400 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                                        </div>




                                        <div class="flex flex-col gap-1">
                                            <label for="bundle"
                                                class="text-gray-500 font-semibold text-xs leading-4">Choose
                                                Bundle</label>
                                            <select id="bundle" name="variation_code"
                                                class="border border-gray-300 rounded-md px-4 py-2 text-sm text-black focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                <option>10MB, 1 Day</option>
                                            </select>
                                        </div>

                                             <!-- Add Alpine.js -->
                                        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
                                        <div x-data="{open: false, selected: null, selectCurrency(flag, label, code) {this.selected = { flag, label, code }; this.open = false; }}" class="relative w-full">
                                            <label class="text-sm font-semibold text-gray-600 mb-1 block">Balance</label>

                                            <!-- Button -->
                                            <div @click="open = !open"  class="flex items-center justify-between border border-gray-300 rounded-md p-2 cursor-pointer bg-white w-full">
                                                <div class="flex items-center gap-2">
                                                    <template x-if="selected">
                                                        <div class="flex items-center gap-2">
                                                            <img :src="selected.flag" class="w-5 h-4" />
                                                            <span x-text="selected.label"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="!selected">
                                                        <span class="text-gray-400">Select currency</span>
                                                    </template>
                                                </div>
                                                <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>

                                            <!-- Dropdown -->
                                            <div x-show="open" @click.outside="open = false"
                                                class="absolute z-10 bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto w-full"
                                                x-transition>
                                                @foreach ($balances as $balance)
                                                    @php
                                                        $symbol = $balance->currency_meta['symbol'] ?? '';
                                                        $countryCode = strtoupper($balance->currency_meta['country'] ?? 'US');
                                                        $flag = "https://flagcdn.com/w20/" . strtolower($countryCode) . ".png";
                                                        $label = "$symbol {$balance->currency} (" . number_format($balance->amount, 2) . ")";
                                                        $code = $balance->id;
                                                    @endphp
                                                    <div @click="selectCurrency('{{ $flag }}', '{{ $label }}', '{{ $code }}')"
                                                        class="flex items-center gap-2 px-3 py-2 hover:bg-gray-100 cursor-pointer w-full">
                                                        <img src="{{ $flag }}" class="w-5 h-4" />
                                                        <span>{{ $label }}</span>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Hidden input for form -->
                                            <input type="hidden" name="balance" :value="selected?.code">
                                        </div>

                                        <div class="flex flex-col gap-1">
                                            <label for="amount" 
                                                class="text-gray-500 font-semibold text-xs leading-4">Amount
                                                (auto-generated)</label>
                                            <input id="amount" name="amount" type="text" readonly
                                             class="bg-gray-200 rounded-md px-4 py-2 text-sm text-black cursor-not-allowed" />
                                             <input type="hidden" id="amount_raw" name="amount">


                                        </div>

                                        <button type="submit"
                                            class="bg-[#C9DFF3] text-[#2F6DB3] font-semibold text-sm rounded-full py-2 px-8 w-max">
                                            Buy Now
                                        </button>
                                    </form>
                                </div>
                            </section>


                        </section>
                    </section>

                </div>
            </section>
    </main>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const DSTVBillContainer = document.getElementById('DSTVBillContainer');
            const closeDSTVBillBtn = document.getElementById('closeDSTVBill');
            const ElectricityBillContainer = document.getElementById('ElectricityBillContainer');
            const btnOpenElectricity = document.getElementById('openElectricityBill');
            const btnOpenDSTV = document.getElementById('openDstvBill');
            const btnOpenData = document.getElementById('openDataBill');
            const closeElectricityBillBtn = document.getElementById('closeElectricityBill');
            const DataBillContainer = document.getElementById('DataBillContainer');
            const closeDataBillBtn = document.getElementById('closeDataBill');

            const openBtn = document.getElementById('openSidebarBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const overlay = document.getElementById('overlay');

            function closeElectricityBill() {
                ElectricityBillContainer.classList.add('hidden');
            }
            function closeDSTVBill() {
                DSTVBillContainer.classList.add('hidden');
            }
            function closeDataBill() {
                DataBillContainer.classList.add('hidden');
            }

            function openElectricityBill() {
                ElectricityBillContainer.classList.remove('hidden');
                DataBillContainer.classList.add('hidden');
                DSTVBillContainer.classList.add('hidden');
            }

            function openDSTVBill() {
                DSTVBillContainer.classList.remove('hidden');
                DataBillContainer.classList.add('hidden');
                ElectricityBillContainer.classList.add('hidden');
            }

            function openDataBill() {
                DataBillContainer.classList.remove('hidden');
                ElectricityBillContainer.classList.add('hidden');
                DSTVBillContainer.classList.add('hidden');
            }

            if (btnOpenElectricity) btnOpenElectricity.addEventListener('click', openElectricityBill);
            if (btnOpenDSTV) btnOpenDSTV.addEventListener('click', openDSTVBill);
            if (btnOpenData) btnOpenData.addEventListener('click', openDataBill);

            if (closeElectricityBillBtn) closeElectricityBillBtn.addEventListener('click', closeElectricityBill);
            if (closeDSTVBillBtn) closeDSTVBillBtn.addEventListener('click', closeDSTVBill);
            if (closeDataBillBtn) closeDataBillBtn.addEventListener('click', closeDataBill);

            if (openBtn) openBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

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

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('smartcardInput');
            const customerDetailsDiv = document.getElementById('customerDetails');
            const customerNameSpan = document.getElementById('customerName');
            const customerNumberSpan = document.getElementById('customerNumber');
        
            let timeout = null;
        
            input.addEventListener('input', function () {
                clearTimeout(timeout);
        
                const cardNumber = input.value.trim();
        
                if (cardNumber.length >= 6) {
                    timeout = setTimeout(() => {
                        fetch("{{ route('Dstvverify') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                billers_code: cardNumber,
                                service_id: "dstv"
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.content && data.content.Customer_Name) {
                                customerNameSpan.innerText = data.content.Customer_Name;
                                customerNumberSpan.innerText = data.content.Smartcard_Number || cardNumber;
                                customerDetailsDiv.classList.remove('hidden');
                            } else {
                                customerDetailsDiv.classList.add('hidden');
                            }
                        })
                        .catch(() => {
                            customerDetailsDiv.classList.add('hidden');
                        });
                    }, 800);
                } else {
                    customerDetailsDiv.classList.add('hidden');
                }
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Electricity verification script loaded");

        const meterInput = document.getElementById('electricityMeter');
        const serviceSelect = document.getElementById('electricityCompany');
        const typeSelect = document.getElementById('electricityType');
        const verifyDisplay = document.getElementById('verifyDetails');
        const spinner = document.getElementById('loadingSpinner');

        const verifyUrl = "{{ route('verify.electricity') }}";
        const csrf = "{{ csrf_token() }}";

        function verifyMeter() {
            const meter = meterInput.value.trim();
            const service_id = serviceSelect.value;
            const type = typeSelect.value;

            if (!meter || !service_id || !type) {
                verifyDisplay.innerHTML = '';
                return;
            }

            spinner.classList.remove('hidden');
            verifyDisplay.innerHTML = '';

            fetch(verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    billers_code: meter,
                    service_id: service_id,
                    type: type
                })
            })
            .then(res => res.json())
            .then(data => {
                spinner.classList.add('hidden');
                console.log('Verification Response:', data);

                if (data?.data?.content) {
                    const d = data.data.content;
                    verifyDisplay.innerHTML = `
                        <div class="p-4 mt-4 bg-green-50 border rounded text-sm text-green-800">
                            <strong>Name:</strong> ${d.Customer_Name || 'N/A'}<br>
                            <strong>Address:</strong> ${d.Address || 'N/A'}<br>
                            <strong>Meter No:</strong> ${d.Meter_Number || 'N/A'}<br>
                            <strong>Meter Type:</strong> ${d.Meter_Type || 'N/A'}<br>
                            <strong>Account Type:</strong> ${d.Customer_Account_Type || 'N/A'}
                        </div>
                    `;
                } else {
                    verifyDisplay.innerHTML = `<div class="text-red-600 mt-2">Invalid meter or company. Try again.</div>`;
                }
            })
            .catch(err => {
                spinner.classList.add('hidden');
                console.error('Error verifying meter:', err);
                verifyDisplay.innerHTML = `<div class="text-red-600 mt-2">Error verifying details.</div>`;
            });
        }

        meterInput.addEventListener('blur', verifyMeter);
        serviceSelect.addEventListener('change', verifyMeter);
        typeSelect.addEventListener('change', verifyMeter);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const networkSelect = document.getElementById('network');
        const bundleSelect = document.getElementById('bundle');
        const amountInput = document.getElementById('amount');

        console.log('Script loaded: waiting for network selection');

        networkSelect.addEventListener('change', function () {
            const serviceId = this.value;
            console.log('Selected network service ID:', serviceId);

            bundleSelect.innerHTML = '<option>Loading bundles...</option>';
            amountInput.value = '';

            if (!serviceId) {
                console.warn('No service ID selected');
                return;
            }

            fetch(`/get-data-variations?service_id=${serviceId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! Status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                console.log('Full response JSON:', data);

                const variations = data?.data?.content?.variations ?? [];

                console.log('Extracted variations:', variations);

                if (variations.length === 0) {
                    console.warn('No bundles found for this network');
                    bundleSelect.innerHTML = '<option>No bundles found</option>';
                    return;
                }

                bundleSelect.innerHTML = '<option value="">-- Select a bundle --</option>';
                variations.forEach(variation => {
                    const option = document.createElement('option');
                    option.value = variation.variation_code;
                    option.textContent = variation.name;
                    option.dataset.amount = variation.variation_amount;
                    bundleSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('Error fetching bundles:', err);
                bundleSelect.innerHTML = '<option>Error loading bundles</option>';
            });
        });

            bundleSelect.addEventListener('change', function () {
            const selected = this.options[this.selectedIndex];
            const amount = selected.dataset.amount || '';
            amountInput.value = amount ? `₦ ${amount}` : '';
            
            // Store numeric value in hidden field
            document.getElementById('amount_raw').value = amount || '';
        });

    });
</script>



    
<script>
    @if ($errors->any() || session('success'))
        const formType = @json(old('form_type') ?? request('form_type'));
        if (formType === 'dstv') {
            document.getElementById('DSTVBillContainer')?.classList.remove('hidden');
        } else if (formType === 'electricity') {
            document.getElementById('ElectricityBillContainer')?.classList.remove('hidden');
        } else if (formType === 'data') {
            document.getElementById('DataBillContainer')?.classList.remove('hidden');
        }
    @endif
</script>







        

</body>

</html>