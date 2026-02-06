@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">

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
                Payment
            </h1>
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full ">
            <section
                class="bg-white text-gray-700 min-h-screen md:w-[80vw]   md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden ">
                <header class="flex items-center gap-4 mb-8">
                    <a href="{{ route('payment.index') }}">
                        <button aria-label="Back"
                            class="flex items-center justify-center w-9 h-9 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-chevron-left text-lg"></i>
                        </button>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900 select-none">Page details</h1>
                    <div class="ml-auto flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full {{ $Payment->visibility === "private" ? "bg-yellow-500" : "bg-green-700" }} block"></span>
                            <span class="text-gray-700 text-sm select-none">{{ $Payment->visibility === "private" ? "Draft" : "Active" }}</span>
                        </div>
                        @if($Payment->visibility === "private")
                            <!-- Payment is private -->
                            <button type="button" class="md:flex hidden items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2 text-gray-500 cursor-not-allowed text-sm font-semibold">
                                <i class="far fa-copy"></i>
                                Copy Link
                            </button>
                        @else
                            <button type="button" value="{{ $Payment->page_link }}" onclick="copyLink(this.value)" class="md:flex hidden items-center gap-2 rounded-lg border border-blue-300 bg-blue-100 px-4 py-2 text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 hover:bg-blue-200 text-sm font-semibold">
                                <i class="far fa-copy"></i>
                                Copy Link
                            </button>
                        @endif
                    </div>
                </header>

                <main class="flex flex-col lg:flex-row gap-10">
                    <!-- Left form section -->
                    <section class="flex-1 max-w-full lg:max-w-[600px] flex flex-col gap-6">
                        <p class="text-xs text-gray-500 select-none">
                            Create, edit and track payment pages all in one place.
                        </p>
                        <script>
                            @if ($errors->any())
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: 'Please fix the following errors:',
                                    html: `
                                        <ul style="padding-left: 1.2em; margin: 0;">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    `,
                                    showConfirmButton: false,
                                    timer: 5000,
                                    timerProgressBar: true,
                                    customClass: {
                                        popup: 'text-sm'
                                    }
                                });
                            @endif

                            @if (session('success'))
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: "{{ session('success') }}",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                            @endif
                        </script>



                        <form class="flex flex-col gap-6" action="{{ route('payment.update', ['id' => $Payment->id]) }}"
                            method="POST" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="flex flex-col gap-1">
                                <label class="text-xs text-gray-600 font-semibold select-none" for="cover-upload">Add a
                                    cover image </label>
                                <div class="flex flex-wrap gap-2 text-xs text-gray-500 select-none">
                                    <span class="border border-gray-300 rounded-md px-2 py-[2px]">File type: .png and
                                        .jpg</span>
                                    <span class="border border-gray-300 rounded-md px-2 py-[2px]">Max file size:
                                        5MB</span>
                                    <span class="border border-gray-300 rounded-md px-2 py-[2px]">Dimensions: 1600 px by
                                        300 px</span>
                                </div>

                                {{-- Preview (existing image or empty container for JS preview) --}}
                                <div id="cover-preview"
                                    class="mt-2 relative w-full h-28 border border-gray-300 rounded-md overflow-hidden {{ $Payment->cover_image ? '' : 'hidden' }}">
                                    @if($Payment->cover_image)
                                        <img id="cover-img" src="{{ asset('storage/' . $Payment->cover_image) }}"
                                            alt="Cover Image" class="object-cover w-full h-full" />
                                    @else
                                        <img id="cover-img" src="" alt="Cover Image"
                                            class="object-cover w-full h-full hidden" />
                                    @endif
                                    <button type="button" id="remove-cover"
                                        class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded-md">
                                        Remove
                                    </button>
                                </div>

                                {{-- Upload box --}}
                                <label id="cover-upload-section" for="cover-upload"
                                    class="mt-2 cursor-pointer border border-dashed border-gray-300 rounded-md h-28 flex items-center justify-center text-xs text-gray-600 select-none hover:border-gray-400 {{ $Payment->cover_image ? 'hidden' : '' }}">
                                    Drag and drop here or
                                    <button type="button"
                                        class="ml-2 bg-blue-700 text-white text-xs font-semibold px-3 py-1 rounded-md">
                                        <i class="fas fa-image mr-1"></i> Browse
                                    </button>
                                    <input type="file" name="cover_image" id="cover-upload" class="hidden"
                                        accept="image/*" />
                                </label>

                                {{-- Hidden flag for removal --}}
                                <input type="hidden" name="remove_cover" id="remove-cover-input" value="0">
                            </div>


                            <div class="flex flex-col gap-1">
                                <label for="title" class="text-xs text-gray-600 select-none">Title</label>
                                <input id="title" name="title" type="text" value="{{ ucfirst($Payment->title) }}" class="text-xs text-dark-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                            </div>

                            <div class="flex flex-col gap-1">
                                <label class="text-xs text-gray-600 select-none">Amount</label>
                                <div class="grid grid-cols-1 items-center gap-3">
                                    <input type="number" name="amount" value="{{ $Payment->amount }}" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" />

                                    {{-- <div class="flex items-center gap-2">
                                        <label for="rrr-toggle"
                                            class="text-xs text-gray-700 select-none cursor-pointer flex items-center gap-1">
                                            <span>RRR (optional)</span>

                                            <div class="relative w-10 h-5 rounded-full bg-gray-300 cursor-pointer"
                                                tabindex="0" role="switch" aria-checked="false" id="toggle-container">
                                                <input type="checkbox" id="rrr-toggle"
                                                    class="absolute opacity-0 w-0 h-0" aria-hidden="true" />
                                                <span id="toggle-thumb"
                                                    class="absolute left-0.5 top-0.5 w-4 h-4 bg-gray-500 rounded-full transition-transform"></span>
                                            </div>
                                        </label>
                                    </div>

                                    <input type="text" placeholder="123456789"
                                        class="text-xs text-gray-400 rounded-md border border-gray-300 px-3 py-2 md:w-36 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                                    --}}
                                </div>

                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="currency" class="text-xs text-gray-600 select-none">Currency</label>
                                <select id="currency" name="currency"
                                    class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex items-center gap-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="" disabled {{ empty($Payment->currency) ? 'selected' : '' }}>🌍
                                        Select currency</option>
                                    <option value="USD" {{ $Payment->currency === 'USD' ? 'selected' : '' }}>🇺🇸 USD – US
                                        Dollar</option>
                                    <option value="EUR" {{ $Payment->currency === 'EUR' ? 'selected' : '' }}>🇪🇺 EUR –
                                        Euro</option>
                                    <option value="GBP" {{ $Payment->currency === 'GBP' ? 'selected' : '' }}>🇬🇧 GBP –
                                        British Pound</option>
                                    <option value="JPY" {{ $Payment->currency === 'JPY' ? 'selected' : '' }}>🇯🇵 JPY –
                                        Japanese Yen</option>
                                    <option value="CNY" {{ $Payment->currency === 'CNY' ? 'selected' : '' }}>🇨🇳 CNY –
                                        Chinese Yuan</option>
                                    <option value="INR" {{ $Payment->currency === 'INR' ? 'selected' : '' }}>🇮🇳 INR –
                                        Indian Rupee</option>
                                    <option value="AUD" {{ $Payment->currency === 'AUD' ? 'selected' : '' }}>🇦🇺 AUD –
                                        Australian Dollar</option>
                                    <option value="CAD" {{ $Payment->currency === 'CAD' ? 'selected' : '' }}>🇨🇦 CAD –
                                        Canadian Dollar</option>
                                    <option value="CHF" {{ $Payment->currency === 'CHF' ? 'selected' : '' }}>🇨🇭 CHF –
                                        Swiss Franc</option>
                                    <option value="NGN" {{ $Payment->currency === 'NGN' ? 'selected' : '' }}>🇳🇬 NGN –
                                        Nigerian Naira</option>
                                    <option value="ZAR" {{ $Payment->currency === 'ZAR' ? 'selected' : '' }}>🇿🇦 ZAR –
                                        South African Rand</option>
                                    <option value="KES" {{ $Payment->currency === 'KES' ? 'selected' : '' }}>🇰🇪 KES –
                                        Kenyan Shilling</option>
                                    <option value="GHS" {{ $Payment->currency === 'GHS' ? 'selected' : '' }}>🇬🇭 GHS –
                                        Ghanaian Cedi</option>
                                    <option value="EGP" {{ $Payment->currency === 'EGP' ? 'selected' : '' }}>🇪🇬 EGP –
                                        Egyptian Pound</option>
                                    <option value="BRL" {{ $Payment->currency === 'BRL' ? 'selected' : '' }}>🇧🇷 BRL –
                                        Brazilian Real</option>
                                    <option value="MXN" {{ $Payment->currency === 'MXN' ? 'selected' : '' }}>🇲🇽 MXN –
                                        Mexican Peso</option>
                                    <option value="ARS" {{ $Payment->currency === 'ARS' ? 'selected' : '' }}>🇦🇷 ARS –
                                        Argentine Peso</option>
                                    <option value="CLP" {{ $Payment->currency === 'CLP' ? 'selected' : '' }}>🇨🇱 CLP –
                                        Chilean Peso</option>
                                    <option value="COP" {{ $Payment->currency === 'COP' ? 'selected' : '' }}>🇨🇴 COP –
                                        Colombian Peso</option>
                                    <option value="PEN" {{ $Payment->currency === 'PEN' ? 'selected' : '' }}>🇵🇪 PEN –
                                        Peruvian Sol</option>
                                    <option value="RUB" {{ $Payment->currency === 'RUB' ? 'selected' : '' }}>🇷🇺 RUB –
                                        Russian Ruble</option>
                                    <option value="TRY" {{ $Payment->currency === 'TRY' ? 'selected' : '' }}>🇹🇷 TRY –
                                        Turkish Lira</option>
                                    <option value="SAR" {{ $Payment->currency === 'SAR' ? 'selected' : '' }}>🇸🇦 SAR –
                                        Saudi Riyal</option>
                                    <option value="AED" {{ $Payment->currency === 'AED' ? 'selected' : '' }}>🇦🇪 AED –
                                        UAE Dirham</option>
                                    <option value="QAR" {{ $Payment->currency === 'QAR' ? 'selected' : '' }}>🇶🇦 QAR –
                                        Qatari Riyal</option>
                                    <option value="KWD" {{ $Payment->currency === 'KWD' ? 'selected' : '' }}>🇰🇼 KWD –
                                        Kuwaiti Dinar</option>
                                    <option value="BHD" {{ $Payment->currency === 'BHD' ? 'selected' : '' }}>🇧🇭 BHD –
                                        Bahraini Dinar</option>
                                    <option value="OMR" {{ $Payment->currency === 'OMR' ? 'selected' : '' }}>🇴🇲 OMR –
                                        Omani Rial</option>
                                    <option value="PKR" {{ $Payment->currency === 'PKR' ? 'selected' : '' }}>🇵🇰 PKR –
                                        Pakistani Rupee</option>
                                    <option value="BDT" {{ $Payment->currency === 'BDT' ? 'selected' : '' }}>🇧🇩 BDT –
                                        Bangladeshi Taka</option>
                                    <option value="LKR" {{ $Payment->currency === 'LKR' ? 'selected' : '' }}>🇱🇰 LKR –
                                        Sri Lankan Rupee</option>
                                    <option value="THB" {{ $Payment->currency === 'THB' ? 'selected' : '' }}>🇹🇭 THB –
                                        Thai Baht</option>
                                    <option value="MYR" {{ $Payment->currency === 'MYR' ? 'selected' : '' }}>🇲🇾 MYR –
                                        Malaysian Ringgit</option>
                                    <option value="IDR" {{ $Payment->currency === 'IDR' ? 'selected' : '' }}>🇮🇩 IDR –
                                        Indonesian Rupiah</option>
                                    <option value="SGD" {{ $Payment->currency === 'SGD' ? 'selected' : '' }}>🇸🇬 SGD –
                                        Singapore Dollar</option>
                                    <option value="HKD" {{ $Payment->currency === 'HKD' ? 'selected' : '' }}>🇭🇰 HKD –
                                        Hong Kong Dollar</option>
                                    <option value="KRW" {{ $Payment->currency === 'KRW' ? 'selected' : '' }}>🇰🇷 KRW –
                                        South Korean Won</option>
                                    <option value="VND" {{ $Payment->currency === 'VND' ? 'selected' : '' }}>🇻🇳 VND –
                                        Vietnamese Dong</option>
                                    <option value="ILS" {{ $Payment->currency === 'ILS' ? 'selected' : '' }}>🇮🇱 ILS –
                                        Israeli Shekel</option>
                                    <option value="MAD" {{ $Payment->currency === 'MAD' ? 'selected' : '' }}>🇲🇦 MAD –
                                        Moroccan Dirham</option>
                                    <option value="TND" {{ $Payment->currency === 'TND' ? 'selected' : '' }}>🇹🇳 TND –
                                        Tunisian Dinar</option>
                                    <option value="DZD" {{ $Payment->currency === 'DZD' ? 'selected' : '' }}>🇩🇿 DZD –
                                        Algerian Dinar</option>
                                    <option value="ETB" {{ $Payment->currency === 'ETB' ? 'selected' : '' }}>🇪🇹 ETB –
                                        Ethiopian Birr</option>
                                    <option value="UGX" {{ $Payment->currency === 'UGX' ? 'selected' : '' }}>🇺🇬 UGX –
                                        Ugandan Shilling</option>
                                    <option value="TZS" {{ $Payment->currency === 'TZS' ? 'selected' : '' }}>🇹🇿 TZS –
                                        Tanzanian Shilling</option>
                                    <option value="RWF" {{ $Payment->currency === 'RWF' ? 'selected' : '' }}>🇷🇼 RWF –
                                        Rwandan Franc</option>
                                    <option value="XAF" {{ $Payment->currency === 'XAF' ? 'selected' : '' }}>🌍 XAF –
                                        Central African CFA Franc</option>
                                    <option value="XOF" {{ $Payment->currency === 'XOF' ? 'selected' : '' }}>🌍 XOF – West
                                        African CFA Franc</option>
                                    <option value="SCR" {{ $Payment->currency === 'SCR' ? 'selected' : '' }}>🇸🇨 SCR –
                                        Seychellois Rupee</option>
                                    <option value="MUR" {{ $Payment->currency === 'MUR' ? 'selected' : '' }}>🇲🇺 MUR –
                                        Mauritian Rupee</option>
                                    <option value="BWP" {{ $Payment->currency === 'BWP' ? 'selected' : '' }}>🇧🇼 BWP –
                                        Botswana Pula</option>
                                    <option value="NAD" {{ $Payment->currency === 'NAD' ? 'selected' : '' }}>🇳🇦 NAD –
                                        Namibian Dollar</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="subaccount" class="text-xs text-gray-600 select-none">Subaccount</label>
                                <div class="flex gap-3">
                                    <select id="subaccount" name="subaccount_id"
                                        class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="" disabled {{ empty($Payment->subaccount_id) ? 'selected' : '' }}> Select a subaccount </option>
                                        @foreach ($subaccounts as $subaccount)
                                            <option value="{{ $subaccount->id }}" {{ $Payment->subaccount_id == $subaccount->id ? 'selected' : '' }}>
                                                {{ $subaccount->account_number ? $subaccount->account_number . ' ' . $subaccount->bank_name . ' ' . $subaccount->account_name : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="percentage" value="{{ $Payment->percentage }}"
                                        class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 w-16 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                                    <span
                                        class="text-xs text-gray-700 bg-gray-200 rounded-md px-2 py-2 flex items-center select-none">%</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-1">
                                <label for="visibility" class="text-xs text-gray-600 select-none">Visibility</label>
                                <select id="visibility" name="visibility"  class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="" disabled {{ empty($Payment->visibility) ? "selected" : "" }}>Select
                                        visibility</option>
                                    <option value="public" {{ $Payment->visibility === "public" ? "selected" : "" }}>
                                        Public</option>
                                    <option value="private" {{ $Payment->visibility === "private" ? "selected" : "" }}>
                                        Private</option>
                                </select>
                            </div>

                            <div class="flex gap-4 mt-6">
                                <button type="submit"
                                    class="text-blue-700 bg-blue-100 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    Update Page
                                </button>
                                <button type="button" onclick="deletePayment(this.value)" value="{{ $Payment->id }}"
                                    class="text-red-600 bg-red-100 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-400">
                                    Delete Page
                                </button>
                            </div>
                        </form>
                    </section>

                    <!-- Right payments section -->
                    <section class="flex-1 max-w-full lg:max-w-[520px] flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <h2 class="font-semibold text-gray-900 select-none">Payments</h2>
                            @if ($Payment->visibility === 'private' || $Payment->records->isEmpty())
                                <button type="button" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-500 cursor-not-allowed text-sm font-semibold ">
                                    <i class="fas fa-file-export"></i>
                                    Export CSV
                                </button>
                            @else
                                <a href="{{ route('payment.export', ['id' => $Payment->id]) }}">
                                    <button type="button" class="flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-4 py-2 text-blue-700 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm font-semibold ">
                                        <i class="fas fa-file-export"></i>
                                        Export CSV
                                    </button>
                                </a>
                            @endif
                        </div>

                        <div class="md:flex-1 md:overflow-y-auto  w-full overflow-x-auto space-y-3 pr-2 bg-[#F3F3F3] p-4 rounded-2xl" style="max-height: 600px;" tabindex="0">
                            <!-- Subscriber item template repeated 10 times -->
                            @if ($Payment->records->isEmpty())
                                <div class="flex items-center gap-4 rounded-xl px-6 py-3">
                                    <img src="../../asserts/dashboard/person.png" alt="">
                                    <p>No Payment yet</p>
                                </div>
                            @else
                                @foreach ($Payment->records as $record)
                                    <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                                        <span class="bg-[#F3F3F3] p-2 rounded-md">
                                            @if ($record->status === 'failed')
                                                <span class="w-3 h-3 rounded-full block" style="background-color: #c53030" aria-label="Inactive status"></span>
                                            @elseif ($record->status === 'pending')   
                                                <span class="w-3 h-3 rounded-full block" style="background-color: #d69e2e" aria-label="Pending status"></span>
                                            @else                             
                                                <span class="w-3 h-3 rounded-full block" style="background-color: #38a169" aria-label="Active status"></span>            
                                            @endif
                                        </span>

                                        <span class="font-semibold text-gray-900  text-[10px] ">{{ $record->name }}</span>
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">{{ $record->email }}</span>
                                        <span class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">{{ $record->phone }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                </main>
            </section>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


        // const toggle = document.getElementById("rrr-toggle");
        // const toggleThumb = document.getElementById("toggle-thumb");
        // const toggleContainer = document.getElementById("toggle-container");

        // toggle.addEventListener("change", () => {
        //   if (toggle.checked) {
        //     toggleThumb.classList.add("translate-x-5", "bg-green-700");
        //     toggleThumb.classList.remove("bg-gray-500");
        //     toggleContainer.setAttribute("aria-checked", "true");
        //   } else {
        //     toggleThumb.classList.remove("translate-x-5", "bg-green-700");
        //     toggleThumb.classList.add("bg-gray-500");
        //     toggleContainer.setAttribute("aria-checked", "false");
        //   }
        // });



        function deletePayment(data) {
            // alert(data);
            Swal.fire({
                title: 'Are you sure to delete payment?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/payment/${data}/destory`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                        .then(response => response.json())
                        .then(res => {
                            // destructure
                            const { status, message } = res.data;
                            if (status === "success") {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: message || 'Your remita has been deleted.',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                                setTimeout(() => {
                                    window.location.href = '/payment';
                                }, 1500); // redirect after a short delay
                            } else {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'error',
                                    title: message,
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            }
                        })
                        .catch((err) => {
                            console.error(err);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Something went wrong.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        });
                }
            });
        }

        function copyLink(data){
            const link = data;

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


        document.addEventListener('DOMContentLoaded', () => {
            const removeBtn = document.getElementById('remove-cover');
            const preview = document.getElementById('cover-preview');
            const previewImg = document.getElementById('cover-img');
            const uploadSection = document.getElementById('cover-upload-section');
            const removeInput = document.getElementById('remove-cover-input');
            const fileInput = document.getElementById('cover-upload');

            // Remove image (either old or newly uploaded)
            if (removeBtn) {
                removeBtn.addEventListener('click', () => {
                    preview.classList.add('hidden');
                    previewImg.src = "";
                    previewImg.classList.add('hidden');
                    uploadSection.classList.remove('hidden');
                    removeInput.value = "1"; // mark for removal
                    fileInput.value = ""; // reset file input
                });
            }

            // Live preview when user selects a new file
            fileInput.addEventListener('change', (e) => {
                if (fileInput.files && fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        previewImg.src = event.target.result;
                        previewImg.classList.remove('hidden');
                        preview.classList.remove('hidden');
                        uploadSection.classList.add('hidden');
                        removeInput.value = "0"; // cancel removal
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            });
        });
    </script>
</body>

</html>