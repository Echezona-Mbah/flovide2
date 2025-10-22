<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice | Reciept</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            color: #252525;
        }
    </style>
</head>

<body>
    <!-- navbar  -->
    @include('../mainpage.navbar')
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <!-- hero section -->
        <header class="relative">
            <section class="bg-[#0F243D] md:hidden h-[120px]" id="mobileMenuButton">
                <!-- mobile menu -->
                <section
                    class="text-white relative top-10 md:hidden border border-[#1E5186] shadow-2xl mx-2 rounded-2xl p-2">
                    <section class="flex justify-between items-center w-full">
                        <div>
                            <img src="{{asset('../asserts/mobileLogo.svg')}}" alt="" />
                        </div>
                        <div id="openSidebarBtn">
                            <img src="{{asset('../asserts/menu-icon.svg')}}" alt="" />
                        </div>
                    </section>
                </section>

                <!-- Mobile Dropdown Menu -->
                <section class="md:hidden px-4 py-3 text-white w-full flex justify-center items-center">
                    <!-- Dropdown Content -->
                    <div id="mobileMenuContent"
                        class="mt-2 absolute top-[13vh] left-0 right-0 w-full flex flex-col items-center justify-center z-50 hidden">
                        <ul class="bg-[#1C3C5E] w-full rounded-2xl shadow-md p-4 text-[20px] font-medium space-y-6 ">
                            <a href="{{ route('personal') }}"
                                class="block px-4 py-2 text-white hover:bg-[#3B82F6] border border-[#3380C4] p-4 bg-[#1E5186] rounded-xl">
                                Personal
                            </a>
                            <a href="{{ route('business') }}" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                Business
                            </a>
                            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                Developer
                            </a>
                            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                Blog
                            </a>
                            <a href="#" class="block px-4 py-2 text-white hover:bg-[#1E5186]">
                                Contact Us
                            </a>

                            <button
                                class="text-center w-full px-4 py-2 text-white font-semibold hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                                <a href="#" class=""> Login </a>
                            </button>

                            <button
                                class="text-center w-full px-4 py-2 text-white font-semibold bg-[#1E5186] hover:bg-[#3B82F6] border border-[#3380C4] p-4 rounded-full">
                                <a href="#" class=""> Get Started </a>
                            </button>

                            <button aria-label="Select country"
                                class="flex items-center space-x-2 border border-[#3380C4] rounded-full px-3 py-1 text-white focus:outline-none"
                                type="button">
                                <img alt="Flag of Nigeria" class="w-6 h-6 rounded-full object-cover" decoding="async"
                                    height="14" src="{{asset('../asserts/homepage/ng.svg')}}" width="20" />
                                <span> NG </span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </ul>
                    </div>
                </section>
            </section>
        </header>

        <section class="w-full">
            <div class="w-full py-6">
                <section class="flex justify-center items-center min-h-screen p-4">
                    <!-- Center preview -->
                    <section class="w-full max-w-[600px] bg-[#F3F4F6] rounded-xl md:p-6 p-2 select-text" aria-label="Invoice">
                        {{-- <h3 class="font-semibold text-sm mb-3">Preview</h3> --}}
                        <div class="bg-white rounded-xl md:p-6 p-2 space-y-6 max-w-full overflow-hidden" aria-label="Invoice preview content">
                            <div class="flex justify-between items-center">
                                <h2 class="font-extrabold text-3xl leading-tight">Invoice</h2>
                                <div class="flex flex-col items-center text-center">
                                    <div
                                        class="w-8 h-8 rounded-full border-2 border-black flex items-center justify-center mb-1">
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
                                    @forelse ($invoice->items as $item)
                                        <tr class="border-b border-gray-300">
                                            <td class="border-r border-gray-300 p-2">{{ $item->item_name }}</td>
                                            <td class="border-r border-gray-300 p-2 text-center">{{ $item->qty }}
                                            </td>
                                            <td class="border-r border-gray-300 p-2 text-center">
                                                @if ($item->rate_enabled && $item->rate !== null)
                                                    {{ $invoice->currency }} {{ number_format($item->rate, 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="p-2 text-center">{{ $invoice->currency }}
                                                {{ number_format($item->total, 2) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="p-2 text-center text-gray-400">No items available
                                            </td>
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
                                @php
                                        $symbols = [
                                            'USD' => '$',
                                            'GBP' => '£',
                                            'EUR' => '€',
                                            'NGN' => '₦',
                                            'JPY' => '¥',
                                            'CAD' => 'C$',
                                            'AUD' => 'A$',
                                            'INR' => '₹',
                                            'CNY' => '¥',
                                            'CHF' => 'CHF',
                                            'SEK' => 'kr',
                                            'NOK' => 'kr',
                                            'DKK' => 'kr',
                                            'ZAR' => 'R',
                                            'BRL' => 'R$',
                                            'MXN' => 'Mex$',
                                            'SGD' => 'S$',
                                            'HKD' => 'HK$',
                                            'NZD' => 'NZ$',
                                            'KRW' => '₩',
                                            'PLN' => 'zł',
                                            'RUB' => '₽',
                                            'AED' => 'د.إ',
                                            'SAR' => '﷼',
                                            'TRY' => '₺',
                                            'IDR' => 'Rp',
                                            'THB' => '฿',
                                            'ILS' => '₪',
                                            'PHP' => '₱',
                                        ];
                                        $currencySymbol = $symbols[$invoice->currency] ?? $invoice->currency;
                                    @endphp
                                <div class="flex justify-between mb-2">
                                    <span>Tax</span>
                                    <span>{{ $currencySymbol }} 0.00</span>
                                </div>
                                <div class="flex justify-between font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>{{ $currencySymbol }} {{ number_format($invoice->amount, 2) }}</span>
                                </div>
                            </div>

                            @php
                                $noteStatus = empty($invoice->note) ? 'hidden' : '';
                            @endphp
                            <div {{ $noteStatus }}
                                class="border border-gray-300 rounded-md p-3 text-[10px] text-gray-700 max-w-[400px]">
                                <p class="font-semibold mb-1">Note:</p>
                                <p>{{ $invoice->note }}</p>
                            </div>
                        </div>
                    </section>
                </section>

            </div>
        </section>




        <!-- footer -->
        @include('../mainpage.footer')
    </main>


    @include('../mainpage.script')

</body>

</html>