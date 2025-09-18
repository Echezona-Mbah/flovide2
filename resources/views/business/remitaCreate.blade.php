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
        Subscriptions
      </h1>            
      @include('business.header_notifical')
    </header>
    <section class=" relative w-full ">
      <section
        class="bg-white text-gray-700 min-h-screen md:w-[80vw]   md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden ">
        <header class="flex items-center gap-4 mb-8">
            <a href="{{ route('remita.index') }}">
                <button aria-label="Back" class="flex items-center justify-center w-9 h-9 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-chevron-left text-lg"></i>
                </button>
            </a>
            <h1 class="text-xl font-semibold text-gray-900 select-none">Page details</h1>
            <div class="ml-auto flex items-center gap-4">
                <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-gray-300 block"></span>
                <span class="text-gray-700 text-sm select-none">Unsaved</span>
                </div>
                <button type="button" class="md:flex hidden items-center gap-2 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2 text-gray-500 text-sm font-semibold">
                    <i class="far fa-copy"></i>
                    Copy Link
                </button>
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


                <form class="flex flex-col gap-6" action="{{ route('remita.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-gray-600 font-semibold select-none" for="cover-upload">
                            Add a cover image
                        </label>

                        <div class="flex flex-wrap gap-2 text-xs text-gray-500 select-none">
                            <span class="border border-gray-300 rounded-md px-2 py-[2px]">File type: .png and .jpg</span>
                            <span class="border border-gray-300 rounded-md px-2 py-[2px]">Max file size: 5MB</span>
                            <span class="border border-gray-300 rounded-md px-2 py-[2px]">Dimensions: 1600 px by 300 px</span>
                        </div>

                        <!-- Upload Box -->
                        <div id="upload-container" class="relative cursor-pointer mt-2 border border-dashed border-gray-300 rounded-md h-28 flex flex-col items-center justify-center text-xs text-gray-600 select-none hover:border-gray-400">
                            
                            <!-- Hidden Input -->
                            <input type="file" name="cover_image" id="cover-upload" class="hidden" />

                            <!-- Label & Button -->
                            <div id="upload-label" class="flex flex-col items-center">
                                <p>Drag and drop here or</p>
                                <button type="button" id="browse-btn" class="mt-1 bg-blue-700 text-white text-xs font-semibold px-3 py-1 rounded-md">
                                    <i class="fas fa-image mr-1"></i> Browse
                                </button>
                            </div>

                            <!-- Image Preview -->
                            <div id="preview" class="hidden relative w-full h-full flex items-center justify-center">
                                <img id="preview-img" class="max-h-24 object-contain rounded-md" />
                                <!-- Remove Button -->
                                <button type="button" id="remove-btn" class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-md shadow">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-col gap-1">
                        <label for="title" class="text-xs text-gray-600 select-none">Title</label>
                        <input id="title" type="text" name="title" placeholder="Enter Remita Title" class="text-xs text-gray-400 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-xs text-gray-600 select-none">Fixed Amount</label>
                        <input type="number" name="amount" placeholder="Enter Remita Amount" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" />

                        {{-- <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-3">

                            <input type="text" placeholder="30" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 md:w-32 focus:outline-none focus:ring-1 focus:ring-blue-500" />

                            <div class="flex items-center gap-2">
                                <label for="rrr-toggle" class="text-xs text-gray-700 select-none cursor-pointer flex items-center gap-1">
                                <span>RRR (optional)</span>

                                <div class="relative w-10 h-5 rounded-full bg-gray-300 cursor-pointer" tabindex="0" role="switch" aria-checked="false" id="toggle-container">
                                    <input type="checkbox" id="rrr-toggle" class="absolute opacity-0 w-0 h-0" aria-hidden="true" />
                                    <span id="toggle-thumb" class="absolute left-0.5 top-0.5 w-4 h-4 bg-gray-500 rounded-full transition-transform"></span>
                                </div>
                                </label>
                            </div>

                            <input type="text" placeholder="123456789" class="text-xs text-gray-400 rounded-md border border-gray-300 px-3 py-2 md:w-36 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                        </div> --}}

                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="service-type" class="text-xs text-gray-600 select-none">Service Type</label>
                        <select id="service-type" name="service_type" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="" selected >Select a service type</option>
                            <option value="school_fees">School Fees</option>
                            <option value="utilities">Utilities</option>
                            <option value="donation">Donation</option>
                            <option value="subscription">Subscription</option>
                            <option value="product_payment">Product Payment</option>
                            <option value="event_registration">Event Registration</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="subaccount" class="text-xs text-gray-600 select-none">Subaccount</label>
                        <div class="flex gap-3">
                            <select id="subaccount" name="subaccount_id" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option selected value="">Select a subaccount</option>
                                @foreach ($subaccounts as $subaccount)
                                    <option value="{{ $subaccount->id }}">
                                    {{ $subaccount->decrypted_account ? $subaccount->decrypted_account . ' ' . $subaccount->bank_name . ' ' . $subaccount->account_name : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="percentage" value="10" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 w-16 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                            <span class="text-xs text-gray-700 bg-gray-200 rounded-md px-2 py-2 flex items-center select-none">%</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="currency" class="text-xs text-gray-600 select-none">Currency</label>
                        <select id="currency" name="currency" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex items-center gap-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option selected>🌍 Select currency</option>
                            <option value="USD">🇺🇸 USD – US Dollar</option>
                            <option value="EUR">🇪🇺 EUR – Euro</option>
                            <option value="GBP">🇬🇧 GBP – British Pound</option>
                            <option value="JPY">🇯🇵 JPY – Japanese Yen</option>
                            <option value="CNY">🇨🇳 CNY – Chinese Yuan</option>
                            <option value="INR">🇮🇳 INR – Indian Rupee</option>
                            <option value="AUD">🇦🇺 AUD – Australian Dollar</option>
                            <option value="CAD">🇨🇦 CAD – Canadian Dollar</option>
                            <option value="CHF">🇨🇭 CHF – Swiss Franc</option>
                            <option value="NGN">🇳🇬 NGN – Nigerian Naira</option>
                            <option value="ZAR">🇿🇦 ZAR – South African Rand</option>
                            <option value="KES">🇰🇪 KES – Kenyan Shilling</option>
                            <option value="GHS">🇬🇭 GHS – Ghanaian Cedi</option>
                            <option value="EGP">🇪🇬 EGP – Egyptian Pound</option>
                            <option value="BRL">🇧🇷 BRL – Brazilian Real</option>
                            <option value="MXN">🇲🇽 MXN – Mexican Peso</option>
                            <option value="ARS">🇦🇷 ARS – Argentine Peso</option>
                            <option value="CLP">🇨🇱 CLP – Chilean Peso</option>
                            <option value="COP">🇨🇴 COP – Colombian Peso</option>
                            <option value="PEN">🇵🇪 PEN – Peruvian Sol</option>
                            <option value="RUB">🇷🇺 RUB – Russian Ruble</option>
                            <option value="TRY">🇹🇷 TRY – Turkish Lira</option>
                            <option value="SAR">🇸🇦 SAR – Saudi Riyal</option>
                            <option value="AED">🇦🇪 AED – UAE Dirham</option>
                            <option value="QAR">🇶🇦 QAR – Qatari Riyal</option>
                            <option value="KWD">🇰🇼 KWD – Kuwaiti Dinar</option>
                            <option value="BHD">🇧🇭 BHD – Bahraini Dinar</option>
                            <option value="OMR">🇴🇲 OMR – Omani Rial</option>
                            <option value="PKR">🇵🇰 PKR – Pakistani Rupee</option>
                            <option value="BDT">🇧🇩 BDT – Bangladeshi Taka</option>
                            <option value="LKR">🇱🇰 LKR – Sri Lankan Rupee</option>
                            <option value="THB">🇹🇭 THB – Thai Baht</option>
                            <option value="MYR">🇲🇾 MYR – Malaysian Ringgit</option>
                            <option value="IDR">🇮🇩 IDR – Indonesian Rupiah</option>
                            <option value="SGD">🇸🇬 SGD – Singapore Dollar</option>
                            <option value="HKD">🇭🇰 HKD – Hong Kong Dollar</option>
                            <option value="KRW">🇰🇷 KRW – South Korean Won</option>
                            <option value="VND">🇻🇳 VND – Vietnamese Dong</option>
                            <option value="ILS">🇮🇱 ILS – Israeli Shekel</option>
                            <option value="MAD">🇲🇦 MAD – Moroccan Dirham</option>
                            <option value="TND">🇹🇳 TND – Tunisian Dinar</option>
                            <option value="DZD">🇩🇿 DZD – Algerian Dinar</option>
                            <option value="ETB">🇪🇹 ETB – Ethiopian Birr</option>
                            <option value="UGX">🇺🇬 UGX – Ugandan Shilling</option>
                            <option value="TZS">🇹🇿 TZS – Tanzanian Shilling</option>
                            <option value="RWF">🇷🇼 RWF – Rwandan Franc</option>
                            <option value="XAF">🌍 XAF – Central African CFA Franc</option>
                            <option value="XOF">🌍 XOF – West African CFA Franc</option>
                            <option value="SCR">🇸🇨 SCR – Seychellois Rupee</option>
                            <option value="MUR">🇲🇺 MUR – Mauritian Rupee</option>
                            <option value="BWP">🇧🇼 BWP – Botswana Pula</option>
                            <option value="NAD">🇳🇦 NAD – Namibian Dollar</option>

                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="visibility" class="text-xs text-gray-600 select-none">Visibility</label>
                        <select id="visibility" name="visibility" class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="" selected>Select visibility</option>
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>

                    <div class="flex gap-4 mt-6">
                        <button type="submit" class="text-blue-700 bg-blue-100 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        Create Page
                        </button>
                    </div>
                </form> 
            </section>

          <!-- Right payments section -->
          <section class="flex-1 max-w-full lg:max-w-[520px] flex flex-col gap-4">
            <div class="flex justify-between items-center">
              <h2 class="font-semibold text-gray-900 select-none">Payments</h2>
              <button type="button" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-gray-500 text-sm font-semibold">
                <i class="fas fa-file-export"></i>
                Export CSV
              </button>
            </div>

            <div class="md:flex-1 md:overflow-y-auto w-full overflow-x-auto pr-2 bg-[#F3F3F3] p-4 rounded-2xl flex items-center justify-center" style="max-height: 600px;" tabindex="0">
                <div class="flex items-center gap-4 rounded-xl px-6 py-3">
                    <img src="../../asserts/dashboard/person.png" alt="">
                    <p>No payments yet</p>
                </div>
            </div>

          </section>
        </main>
        </div>
        </div>
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
        //     if (toggle.checked) {
        //         toggleThumb.classList.add("translate-x-5", "bg-green-700");
        //         toggleThumb.classList.remove("bg-gray-500");
        //         toggleContainer.setAttribute("aria-checked", "true");
        //     } else {
        //         toggleThumb.classList.remove("translate-x-5", "bg-green-700");
        //         toggleThumb.classList.add("bg-gray-500");
        //         toggleContainer.setAttribute("aria-checked", "false");
        //     }
        // });


        const fileInput = document.getElementById("cover-upload");
        const browseBtn = document.getElementById("browse-btn");
        const previewContainer = document.getElementById("preview");
        const previewImg = document.getElementById("preview-img");
        const uploadLabel = document.getElementById("upload-label");
        const removeBtn = document.getElementById("remove-btn");

        // Open file manager when clicking "Browse"
        browseBtn.addEventListener("click", () => {
        fileInput.click();
        });

        // Handle file selection
        fileInput.addEventListener("change", (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewContainer.classList.remove("hidden");
            uploadLabel.classList.add("hidden");
            };
            reader.readAsDataURL(file);
        }
        });

        // Handle remove
        removeBtn.addEventListener("click", () => {
        fileInput.value = ""; 
        previewImg.src = "";
        previewContainer.classList.add("hidden");
        uploadLabel.classList.remove("hidden");
        });

    </script>
</body>

</html>