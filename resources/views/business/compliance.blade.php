@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.sumsub.com/sumsub-websdk/1.0.0/sumsub-websdk.min.js"></script>

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
            @if (!auth()->user()->isFullyVerified())
            <div class="relative overflow-hidden rounded-xl border border-yellow-300 bg-yellow-50 p-5 mb-6">
                
                <!-- soft background accent -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full opacity-30"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                
                <!-- Left content -->
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-700">
                    <i class="fas fa-shield-alt text-lg"></i>
                    </div>

                    <div>
                    <h4 class="text-sm font-semibold text-yellow-900">
                        Account verification required
                    </h4>
                    <p class="text-sm text-yellow-800 mt-1 leading-relaxed">
                        For your safety and compliance, some features are temporarily unavailable.
                        Please complete your verification to unlock full access.
                    </p>
                    </div>
                </div>


                <!-- Action button -->
                <a href="{{ url('/compliance') }}"
                    class="inline-flex items-center justify-center gap-2 bg-yellow-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-700 transition shadow-sm">
                    <i class="fas fa-arrow-right"></i>
                    Complete Verification
                </a>

                </div>
            </div>
            @endif

            {{-- <a href="{{ route('sumsub.kyc') }}"
            class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
            Start Verification
            </a> --}}

            <section class="bg-white text-gray-700 min-h-screen  w-full md:rounded-3xl md:p-6 p-2 shadow-md md:absolute  overflow-x-hidden ">


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
                      $proofofidentitystatus = $statusMap[$user->proof_of_identity_status ?? 'no'];
                      $ownershipstatus = $statusMap[$user->ownership_status ?? 'no'];
                      $organisationalchartstatus = $statusMap[$user->organisational_chart_status ?? 'no'];
                      $registerofdirectorsstatus = $statusMap[$user->register_of_directors_status ?? 'no'];
                      $formationdocumentstatus = $statusMap[$user->formation_document_status ?? 'no'];

                  @endphp

                  <div class="flex items-center justify-between border border-gray-200 rounded-lg p-4 mb-4">
    
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            Identity Document
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            ID card • Passport • Residence permit • Driver's license
                        </p>
                    </div>

                    <div>

                        @if($user->identity_verification_status == 'confirmed')

                        <span class="text-green-600 text-xs font-medium">
                        Verified
                        </span>

                        @elseif($user->identity_verification_status == 'rejected')

                        <span class="text-red-600 text-xs font-medium">
                        Rejected
                        </span>

                        @else

                        <a href="{{ route('sumsub.kyc') }}"
                        class="text-gray-900 text-sm border border-gray-300 rounded-lg px-5 py-2">
                        Start Verification
                        </a>

                        @endif
                        {{-- <a href="{{ route('sumsub.kyc') }}"
                        class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                            Start Verification
                        </a> --}}
                    </div>

                </div>

                    <!-- CAC Certificate -->
                  <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                    <div class="flex items-center space-x-4">
                      <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $cacStatus['color'] }}-400 text-{{ $cacStatus['color'] }}-500">
                        <i class="{{ $cacStatus['icon'] }}"></i>
                      </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm leading-5">
                            CAC certificate
                            </p>
                            <p class="text-gray-500 text-sm leading-5">
                                Upload either Certificate of Registration, Certificate of Incorporate or a Certificate of Formation.
                            </p>
                        </div> 
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
                    <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $bvnStatus['color'] }}-400 text-{{ $bvnStatus['color'] }}-500">
                        <i class="{{ $bvnStatus['icon'] }}"></i>
                    </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm leading-5">
                                Bank Verification Number (BVN)
                            </p>
                            <p class="text-gray-500 text-sm leading-5">
                                Provide your BVN for identity verification. Ensure it matches your account details.
                            </p>
                        </div>  
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
                        <div>
                            <p class="font-semibold text-gray-900 text-sm leading-5">
                                Valid ID of Directors/Owners
                            </p>
                            <p class="text-gray-500 text-sm leading-5">
                                Provide a valid government-issued ID for all directors or owners.
                            </p>
                        </div>
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
                        <div>
                            <p class="font-semibold text-gray-900 text-sm leading-5">
                                Tax Identification Number (TIN)
                            </p>
                            <p class="text-gray-500 text-sm leading-5">
                                Provide your official Tax Identification Number for verification purposes.
                            </p>
                        </div>
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
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $utilitybillStatus['color'] }}-400 text-{{ $utilitybillStatus['color'] }}-500">
                                <i class="{{ $utilitybillStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Utility Bill / Proof of Address
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    Upload a copy of your last bank statement or a utility bill dated within the last 3 months.
                                </p>
                            </div> 
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $utilitybillStatus['color'] }}-700 bg-{{ $utilitybillStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $utilitybillStatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->utility_bill_status == "confirmed" && $user->utility_bill)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->utility_bill) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Item 7 -->
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $proofofidentitystatus['color'] }}-400 text-{{ $proofofidentitystatus['color'] }}-500">
                                <i class="{{ $proofofidentitystatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Proof of Identity
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    You will need to have your physical passport, drivers license, or national ID.
                                </p>
                            </div>   
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $proofofidentitystatus['color'] }}-700 bg-{{ $proofofidentitystatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $proofofidentitystatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->proof_of_identity_status == "confirmed" && $user->proof_of_identity)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->proof_of_identity) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Item 9 -->
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $ownershipstatus['color'] }}-400 text-{{ $ownershipstatus['color'] }}-500">
                                <i class="{{ $ownershipstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Ownership
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    Provide details of the ultimate beneficial owners with percentages (25% or above) 
                                    <br> stated on shares registry or other legal document (any Commercial Registry
                                    <br> document containing the beneficial ownership information or Annual Report)
                                </p>
                            </div>   
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $ownershipstatus['color'] }}-700 bg-{{ $ownershipstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $ownershipstatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->ownership_status == "confirmed" && $user->ownership_document)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->ownership_document) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Item 10 -->
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $organisationalchartstatus['color'] }}-400 text-{{ $organisationalchartstatus['color'] }}-500">
                                <i class="{{ $organisationalchartstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Organisational Chart
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    If there is more than one layer of ownership, then we'll also need an 
                                    <br> organisational chart signed by one of your Corporate Officers showing
                                    <br> the last ultimate beneficial owners.
                                </p>
                            </div>   
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $organisationalchartstatus['color'] }}-700 bg-{{ $organisationalchartstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $organisationalchartstatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->organisational_chart_status == "confirmed" && $user->organisational_chart)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->organisational_chart) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Item 8 -->
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $registerofdirectorsstatus['color'] }}-400 text-{{ $registerofdirectorsstatus['color'] }}-500">
                                <i class="{{ $registerofdirectorsstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Register of Directors
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    If you have them provide official list of the Directors.
                                </p>
                            </div>   
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $registerofdirectorsstatus['color'] }}-700 bg-{{ $registerofdirectorsstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $registerofdirectorsstatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->register_of_directors_status == "confirmed" && $user->register_of_directors)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->register_of_directors) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Item 6 -->
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $formationdocumentstatus['color'] }}-400 text-{{ $formationdocumentstatus['color'] }}-500">
                                <i class="{{ $formationdocumentstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">
                                    Formation Document
                                </p>
                                <p class="text-gray-500 text-sm leading-5">
                                    Upload either Memorandum & Articles of Association or Articles of Incorporation
                                </p>
                            </div>                         
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $formationdocumentstatus['color'] }}-700 bg-{{ $formationdocumentstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $formationdocumentstatus['label'] }}
                            </div>
                            <!-- Utility Bill -->

                            @if ($user->formation_document_status == "confirmed" && $user->formation_document)
                                <!-- Show Download Button -->
                                <a href="{{ asset('storage/' . $user->formation_document) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>
                                    Download
                                </a>
                            @else
                                <!-- Show Upload Button -->
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">
                                    Upload
                                </button>
                            @endif
                        </div>
                    </div>





                </div>


                <!-- modal upload -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
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
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
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






                <!-- modal for Proof Of Identity -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50" id="modalProofOfIdentity" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Proof of Identity</p>
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
                            <input type="hidden" name="document_type" value="proof_of_identity">
                            <input type="file" name="document" required class="border border-gray-300 w-full p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>




                <!-- modal for Ownership -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50" id="modalOwnership" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Ownership</p>
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
                            <input type="hidden" name="document_type" value="ownership">
                            <input type="file" name="document" required class="border border-gray-300 w-full p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>





                <!-- modal for Organisational Chart -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50" id="modalOrganisationalChart" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Organisational Chart</p>
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
                            <input type="hidden" name="document_type" value="organisational_chart">
                            <input type="file" name="document" required class="border border-gray-300 w-full p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>





                <!-- modal for Register of Directors -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50" id="modalRegisterOfDirectors" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Register of Directors</p>
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
                            <input type="hidden" name="document_type" value="register_of_directors">
                            <input type="file" name="document" required class="border border-gray-300 w-full p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>





                <!-- modal for Formation document -->
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm bg-opacity-50" id="modalFormationDocument" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">Formation Document</p>
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
                            <input type="hidden" name="document_type" value="formation_document">
                            <input type="file" name="document" required class="border border-gray-300 w-full p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300">
                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">Submit</button>
                        </form>
                    </div>
                </section>




            </section>

        </section>
    </main>


<script>
fetch('/sumsub-token')
.then(res => res.json())
.then(data => {

    if(!data.token){
        console.error("Sumsub token error", data);
        return;
    }

    SumsubWebSdk.init({
        accessToken: data.token,
        containerId: "sumsub-kyc-widget",

        onMessage: message => {
            console.log("Message:", message);
        },

        onError: error => {
            console.error("Error:", error);
        },

        onComplete: result => {
            console.log("Verification completed:", result);
        }
    });

});
</script>

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




        document.getElementById('openProofOfIdentity')?.addEventListener('click', () => {
            document.getElementById('modalProofOfIdentity').style.display = 'flex';
        });

        document.getElementById('openOwnership')?.addEventListener('click', () => {
            document.getElementById('modalOwnership').style.display = 'flex';
        });

        document.getElementById('openOrganisationalChart')?.addEventListener('click', () => {
            document.getElementById('modalOrganisationalChart').style.display = 'flex';
        });

        document.getElementById('openRegisterOfDirectors')?.addEventListener('click', () => {
            document.getElementById('modalRegisterOfDirectors').style.display = 'flex';
        });

        document.getElementById('openFormationDocument')?.addEventListener('click', () => {
            document.getElementById('modalFormationDocument').style.display = 'flex';
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