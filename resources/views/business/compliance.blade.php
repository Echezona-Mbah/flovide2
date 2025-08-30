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
                Dashboard
            </h1>
                       @include('business.header_notifical')

        </header>


        {{-- @php
  $cac = $documents['cac'] ?? null;
@endphp

<!-- In status badge -->
<div class="text-sm font-semibold px-3 py-0.5 rounded-full 
    {{ $cac?->status === 'confirmed' ? 'bg-green-200 text-green-700' :
       ($cac?->status === 'under_review' ? 'bg-yellow-200 text-yellow-700' :
       ($cac?->status === 'rejected' ? 'bg-red-200 text-red-700' : 'bg-gray-300 text-gray-700')) }}">
    {{ ucfirst(str_replace('_', ' ', $cac->status ?? 'not submitted')) }}
</div> --}}



        <section class=" relative w-full ">
            <section
                class="bg-white text-gray-700 min-h-screen md:w-[80vw]   md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden ">


                <div class=" mx-auto space-y-4">
                  <!-- Item 1 -->
                  @php
                      $statusMap = [
                          'no' => ['label' => 'Not submitted', 'color' => 'gray', 'icon' => 'fas fa-file-alt'],
                          'under review' => ['label' => 'Under review', 'color' => 'yellow', 'icon' => 'fas fa-bookmark'],
                          'confirmed' => ['label' => 'Confirmed', 'color' => 'green', 'icon' => 'fas fa-check'],
                          'rejected' => ['label' => 'Rejected', 'color' => 'red', 'icon' => 'fas fa-exclamation-triangle'],
                        'yes' => ['label' => 'Verified', 'color' => 'green', 'icon' => 'fas fa-check'],
                      ];

                      $cacStatus = $statusMap[$user->cac_status ?? 'no'];
                      $bvnStatus = $statusMap[$user->bvn_status ?? 'no'];
                      $cacStatus = $statusMap[$user->cac_status ?? 'no'];
                      $valididStatus = $statusMap[$user->valid_id_status ?? 'no'];
                      $tinStatus = $statusMap[$user->tin_status ?? 'no'];
                      $utilitybillStatus = $statusMap[$user->utility_bill_status ?? 'no'];

                  @endphp

                    <!-- CAC Certificate -->
                  <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                      <div
                        class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $cacStatus['color'] }}-400 text-{{ $cacStatus['color'] }}-500">
                        <i class="{{ $cacStatus['icon'] }}"></i>
                      </div>
                      <p class="font-semibold text-gray-900 text-sm leading-5">
                        CAC certificate
                      </p>
                    </div>
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                      <div
                        class="text-{{ $cacStatus['color'] }}-700 bg-{{ $cacStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                        {{ $cacStatus['label'] }}
                      </div>

                      @if ($user->cac_status == "confirmed" && $user->cac_certificate)
                          <!-- Show Download Button -->
                          <a href="{{ asset('storage/' . $user->cac_certificate) }}" 
                              class="bg-green-600 text-white px-4 py-2 rounded"
                              download>
                              Download
                          </a>
                      @else
                          <!-- Show Upload Button -->
                          <button id="uploadBtn"
                               class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                              Upload
                          </button>
                      @endif















                    </div>
                  </div>


                  <!-- Item 2 -->
                @if ($user->countries_id == "Nigeria")
                <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                    <div
                        class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $bvnStatus['color'] }}-400 text-{{ $bvnStatus['color'] }}-500">
                        <i class="{{ $bvnStatus['icon'] }}"></i>
                    </div>
                    <p class="font-semibold text-gray-900 text-sm leading-5">
                        Bank Verification Number (BVN)
                    </p>
                    </div>

                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                    <div
                        class="text-{{ $bvnStatus['color'] }}-700 bg-{{ $bvnStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                        {{ $bvnStatus['label'] }}
                    </div>

                    @if ($user->bvn_status == "yes")
                        <!-- Show Disabled Button for Verified -->
                        <button id="openBVN"
                        class="text-gray-500 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 bg-gray-100 cursor-not-allowed"
                        disabled>
                        Verified
                        </button>
                    @else
                        <!-- Show Active Button for Adding BVN -->
                        <button id="openBVN"
                        class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                        Add
                        </button>
                    @endif

                    </div>
                </div>
                @endif


                  <!-- Item 3 -->
                  <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                      <div
                      class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $valididStatus['color'] }}-400 text-{{ $valididStatus['color'] }}-500">
                      <i class="{{ $valididStatus['icon'] }}"></i>
                    </div>
                      <p class="font-semibold text-gray-900 text-sm leading-5">
                        Valid ID of Directors/Owners
                      </p>
                    </div>
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                      <div
                      class="text-{{ $valididStatus['color'] }}-700 bg-{{ $valididStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                      {{ $valididStatus['label'] }}
                    </div>
                    
                      @if ($user->valid_id_status == "confirmed" && $user->valid_id)
                          <!-- Show Download Button -->
                          <a href="{{ asset('storage/' . $user->valid_id) }}" 
                              class="bg-green-600 text-white px-4 py-2 rounded"
                              download>
                              Download
                          </a>
                      @else
                          <!-- Show Upload Button -->
                            <button id="openValidID"
                          class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                          Upload
                      </button>
                      @endif
                    </div>
                  </div>

                  <!-- Item 4 -->
                  <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                       <div
                      class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $tinStatus['color'] }}-400 text-{{ $tinStatus['color'] }}-500">
                      <i class="{{ $tinStatus['icon'] }}"></i>
                    </div>
                      <p class="font-semibold text-gray-900 text-sm leading-5">
                        Tax Identification Number (TIN)
                      </p>
                    </div>
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                     <div
                      class="text-{{ $tinStatus['color'] }}-700 bg-{{ $tinStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                      {{ $tinStatus['label'] }}
                    </div>

                       @if ($user->tin_status == "confirmed" && $user->tin)
                          <!-- Show Download Button -->
                          <a href="{{ asset('storage/' . $user->tin) }}" 
                              class="bg-green-600 text-white px-4 py-2 rounded"
                              download>
                              Download
                          </a>
                      @else
                          <!-- Show Upload Button -->
                            <button id="openTIN"
                          class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                          Upload
                      </button>
                      <!-- TIN -->
                    @endif

                    </div>
                  </div>

                  <!-- Item 5 -->
                  <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                           <div
                      class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $utilitybillStatus['color'] }}-400 text-{{ $utilitybillStatus['color'] }}-500">
                      <i class="{{ $utilitybillStatus['icon'] }}"></i>
                    </div>
                      <p class="font-semibold text-gray-900 text-sm leading-5">
                        Utility Bill / Proof of Address
                      </p>
                    </div>
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                       <div
                      class="text-{{ $utilitybillStatus['color'] }}-700 bg-{{ $utilitybillStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                      {{ $utilitybillStatus['label'] }}
                    </div>
                      <!-- Utility Bill -->

                          @if ($user->utility_bill_status == "confirmed" && $user->utility_bill)
                          <!-- Show Download Button -->
                          <a href="{{ asset('storage/' . $user->utility_bill) }}" 
                              class="bg-green-600 text-white px-4 py-2 rounded"
                              download>
                              Download
                          </a>
                      @else
                          <!-- Show Upload Button -->
                            <button id="openUtility"
                          class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                          Upload
                      </button>
                     @endif
                    </div>
                  </div>
                </div>


                <!-- modal upload -->
                <section
                    class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
                    id="modal" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold mb-4">CAC certificate</p>

                            <button class="close-modal border h-6 w-6 rounded-md flex items-center justify-center ">
                                <i class="fas fa-times  text-md cursor-pointer text-[#828282]"></i>

                            </button>
                        </div>
                      <form class="space-y-5" method="POST" action="{{ route('compliance.store') }}"enctype="multipart/form-data">

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
                            <input type="hidden" name="document_type" value="cac">

                            <div class="mb-4">
                                <label class="flex flex-col text-[#828282] text-md gap-1">
                                    Upload 
                                    <input type="file" name="document"
                                        class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300" />
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">
                                Submit
                            </button>
                      </form>


                    </div>

                </section>
                <!-- modal end-->

                  <!--  modalBvn -->
                <section
                    class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
                    id="modalBvn" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold mb-4">Bank Verification Number (BVN)</p>

                            <button class=" close-modal border h-6 w-6 rounded-md flex items-center justify-center ">
                                <i class="fas fa-times  text-md cursor-pointer text-[#828282]"></i>

                            </button>
                        </div>
                         <form class="space-y-5" method="POST" action="{{ route('compliance.store') }}"enctype="multipart/form-data">

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

                           
                            <input type="hidden" name="document_type" value="bvn">

                            <div class="mb-4">

                                <label class="flex flex-col text-[#828282] text-md gap-1">
                                BVN
                                    <input type="text" name="bvn"
                                        class="border border-gray-300  p-2 rounded-2xl text-black flex items-center focus:outline-none focus:ring-1 focus:ring-blue-300 placeholder:text-[#E7E7E7]" placeholder="1234567812345678" />
                                        
                                    
                                </label>
                            </div>

                          

                            <button type="submit"
                                class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">
                               Submit
                            </button>
                        </form>

                    </div>

                </section>
                <!-- modal end-->
                <!-- modal for Valid ID -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50"
                        id="modalValidID" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Valid ID of Directors/Owners</p>
                            <button class="close-modal border h-6 w-6 rounded-md flex items-center justify-center">
                                <i class="fas fa-times text-md cursor-pointer text-[#828282]"></i>
                            </button>
                        </div>
                        <form action="{{ route('compliance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                            <input type="hidden" name="document_type" value="valid_id">
                            <input type="file" name="document" required
                                class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit"
                                class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>


                <!-- modal for TIN -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50"
                      id="modalTIN" style="display: none;">
                  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                      <div class="flex justify-between items-center border-b pb-4 mb-4">
                          <p class="text-xl font-semibold">Tax Identification Number (TIN)</p>
                          <button class="close-modal border h-6 w-6 rounded-md flex items-center justify-center">
                              <i class="fas fa-times text-md cursor-pointer text-[#828282]"></i>
                          </button>
                      </div>
                      <form action="{{ route('compliance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                          @csrf
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
                          <input type="hidden" name="document_type" value="tin">
                          <input type="file" name="document" required
                              class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                          <button type="submit"
                              class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                      </form>
                  </div>
                </section>

              <!-- modal for Utility Bill -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50"
                      id="modalUtility" style="display: none;">
                  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                      <div class="flex justify-between items-center border-b pb-4 mb-4">
                          <p class="text-xl font-semibold">Utility Bill / Proof of Address</p>
                          <button class="close-modal border h-6 w-6 rounded-md flex items-center justify-center">
                              <i class="fas fa-times text-md cursor-pointer text-[#828282]"></i>
                          </button>
                      </div>
                      <form action="{{ route('compliance.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                          <input type="hidden" name="document_type" value="utility_bill">
                          <input type="file" name="document" required
                              class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                          <button type="submit"
                              class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                      </form>
                  </div>
                </section>


            </section>

        </section>
    </main>

<script>
    // Open specific modals
    document.getElementById('openValidID')?.addEventListener('click', () => {
        document.getElementById('modalValidID').style.display = 'flex';
    });

    document.getElementById('openTIN')?.addEventListener('click', () => {
        document.getElementById('modalTIN').style.display = 'flex';
    });

    document.getElementById('openUtility')?.addEventListener('click', () => {
        document.getElementById('modalUtility').style.display = 'flex';
    });

    document.getElementById('uploadBtn')?.addEventListener('click', () => {
        document.getElementById('modal').style.display = 'flex';
    });

    document.getElementById('openBVN')?.addEventListener('click', () => {
        document.getElementById('modalBvn').style.display = 'flex';
    });

    // Close modals
    document.querySelectorAll('.close-modal').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('section').style.display = 'none';
        });
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