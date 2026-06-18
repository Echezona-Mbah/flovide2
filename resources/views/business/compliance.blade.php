@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.sumsub.com/sumsub-websdk/1.0.0/sumsub-websdk.min.js"></script>

  @include('business.header')
   @include('business.sidebar')

    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>

    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class="items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Dashboard
            </h1>
            @include('business.header_notifical')
        </header>

        <section class="relative w-full">
            @if (!auth()->user()->isFullyVerified())
            <div class="relative overflow-hidden rounded-xl border border-yellow-300 bg-yellow-50 p-5 mb-6">
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-200 rounded-full opacity-30"></div>
                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-700">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-yellow-900">Account verification required</h4>
                            <p class="text-sm text-yellow-800 mt-1 leading-relaxed">
                                For your safety and compliance, some features are temporarily unavailable.
                                Please complete your verification to unlock full access.
                            </p>
                        </div>
                    </div>
                    <a href="{{ url('/compliance') }}"
                        class="inline-flex items-center justify-center gap-2 bg-yellow-600 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-700 transition shadow-sm">
                        <i class="fas fa-arrow-right"></i>
                        Complete Verification
                    </a>
                </div>
            </div>
            @endif

            <section class="bg-white text-gray-700 min-h-screen w-full md:rounded-3xl md:p-6 p-2 shadow-md md:absolute overflow-x-hidden">
                <div class="mx-auto space-y-4">

                    @php
                        $statusMap = [
                            'no'           => ['label' => 'Not submitted', 'color' => 'gray',   'icon' => 'fas fa-file-alt'],
                            'under review' => ['label' => 'Under review',  'color' => 'yellow', 'icon' => 'fas fa-bookmark'],
                            'confirmed'    => ['label' => 'Confirmed',      'color' => 'green',  'icon' => 'fas fa-check'],
                            'rejected'     => ['label' => 'Rejected',       'color' => 'red',    'icon' => 'fas fa-exclamation-triangle'],
                            'yes'          => ['label' => 'Verified',       'color' => 'green',  'icon' => 'fas fa-check'],
                        ];

                        $cacStatus                  = $statusMap[$user->cac_status                  ?? 'no'];
                        $bvnStatus                  = $statusMap[$user->bvn_status                  ?? 'no'];
                        $valididStatus              = $statusMap[$user->valid_id_status              ?? 'no'];
                        $tinStatus                  = $statusMap[$user->tin_status                  ?? 'no'];
                        $utilitybillStatus          = $statusMap[$user->utility_bill_status          ?? 'no'];
                        $proofofidentitystatus      = $statusMap[$user->proof_of_identity_status     ?? 'no'];
                        $ownershipstatus            = $statusMap[$user->ownership_status             ?? 'no'];
                        $organisationalchartstatus  = $statusMap[$user->organisational_chart_status  ?? 'no'];
                        $registerofdirectorsstatus  = $statusMap[$user->register_of_directors_status ?? 'no'];
                        $formationdocumentstatus    = $statusMap[$user->formation_document_status    ?? 'no'];
                    @endphp

                    {{-- Identity Document --}}
                    <div class="flex items-center justify-between border border-gray-200 rounded-lg p-4 mb-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900">Identity Document</h3>
                            <p class="text-xs text-gray-500 mt-1">ID card • Passport • Residence permit • Driver's license</p>
                        </div>
                        <div>
                            @if($user->identity_verification_status == 'confirmed')
                                <span class="text-green-600 text-xs font-medium">Verified</span>
                            @elseif($user->identity_verification_status == 'rejected')
                                <span class="text-red-600 text-xs font-medium">Rejected</span>
                            @else
                                <a href="{{ route('sumsub.kyc') }}"
                                    class="text-gray-900 text-sm border border-gray-300 rounded-lg px-5 py-2">
                                    Start Verification
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- CAC Certificate --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $cacStatus['color'] }}-400 text-{{ $cacStatus['color'] }}-500">
                                <i class="{{ $cacStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">CAC certificate</p>
                                <p class="text-gray-500 text-sm leading-5">Upload either Certificate of Registration, Certificate of Incorporate or a Certificate of Formation.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $cacStatus['color'] }}-700 bg-{{ $cacStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $cacStatus['label'] }}
                            </div>
                            @if ($user->cac_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->cac_certificate ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openCAC" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- BVN (Nigeria only) --}}
                    @if ($user->countries_id == "Nigeria")
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $bvnStatus['color'] }}-400 text-{{ $bvnStatus['color'] }}-500">
                                <i class="{{ $bvnStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Bank Verification Number (BVN)</p>
                                <p class="text-gray-500 text-sm leading-5">Provide your BVN for identity verification. Ensure it matches your account details.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $bvnStatus['color'] }}-700 bg-{{ $bvnStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $bvnStatus['label'] }}
                            </div>
                            @if ($user->bvn_status == "yes")
                                <button class="text-gray-500 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 bg-gray-100 cursor-not-allowed" disabled>Verified</button>
                            @else
                                <button id="openBVN" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Add</button>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Valid ID of Directors/Owners --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $valididStatus['color'] }}-400 text-{{ $valididStatus['color'] }}-500">
                                <i class="{{ $valididStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Valid ID of Directors/Owners</p>
                                <p class="text-gray-500 text-sm leading-5">Provide a valid government-issued ID for all directors or owners.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $valididStatus['color'] }}-700 bg-{{ $valididStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $valididStatus['label'] }}
                            </div>
                            @if ($user->valid_id_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->valid_id ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openValidID" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- TIN --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $tinStatus['color'] }}-400 text-{{ $tinStatus['color'] }}-500">
                                <i class="{{ $tinStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Tax Identification Number (TIN)</p>
                                <p class="text-gray-500 text-sm leading-5">Provide your official Tax Identification Number for verification purposes.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $tinStatus['color'] }}-700 bg-{{ $tinStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $tinStatus['label'] }}
                            </div>
                            @if ($user->tin_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->tin ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openTIN" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Utility Bill --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $utilitybillStatus['color'] }}-400 text-{{ $utilitybillStatus['color'] }}-500">
                                <i class="{{ $utilitybillStatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Utility Bill / Proof of Address</p>
                                <p class="text-gray-500 text-sm leading-5">Upload a copy of your last bank statement or a utility bill dated within the last 3 months.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $utilitybillStatus['color'] }}-700 bg-{{ $utilitybillStatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $utilitybillStatus['label'] }}
                            </div>
                            @if ($user->utility_bill_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->utility_bill ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openUtility" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Proof of Identity --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $proofofidentitystatus['color'] }}-400 text-{{ $proofofidentitystatus['color'] }}-500">
                                <i class="{{ $proofofidentitystatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Proof of Identity</p>
                                <p class="text-gray-500 text-sm leading-5">You will need to have your physical passport, drivers license, or national ID.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $proofofidentitystatus['color'] }}-700 bg-{{ $proofofidentitystatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $proofofidentitystatus['label'] }}
                            </div>
                            @if ($user->proof_of_identity_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->proof_of_identity ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openProofOfIdentity" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Ownership --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $ownershipstatus['color'] }}-400 text-{{ $ownershipstatus['color'] }}-500">
                                <i class="{{ $ownershipstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Ownership</p>
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
                            @if ($user->ownership_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->ownership_document ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openOwnership" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Organisational Chart --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $organisationalchartstatus['color'] }}-400 text-{{ $organisationalchartstatus['color'] }}-500">
                                <i class="{{ $organisationalchartstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Organisational Chart</p>
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
                            @if ($user->organisational_chart_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->organisational_chart ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openOrganisationalChart" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Register of Directors --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $registerofdirectorsstatus['color'] }}-400 text-{{ $registerofdirectorsstatus['color'] }}-500">
                                <i class="{{ $registerofdirectorsstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Register of Directors</p>
                                <p class="text-gray-500 text-sm leading-5">If you have them provide official list of the Directors.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $registerofdirectorsstatus['color'] }}-700 bg-{{ $registerofdirectorsstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $registerofdirectorsstatus['label'] }}
                            </div>
                            @if ($user->register_of_directors_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->register_of_directors ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openRegisterOfDirectors" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                    {{-- Formation Document --}}
                    <div class="flex items-center justify-between border border-gray-300 rounded-xl p-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border border-{{ $formationdocumentstatus['color'] }}-400 text-{{ $formationdocumentstatus['color'] }}-500">
                                <i class="{{ $formationdocumentstatus['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm leading-5">Formation Document</p>
                                <p class="text-gray-500 text-sm leading-5">Upload either Memorandum & Articles of Association or Articles of Incorporation</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-x-20">
                            <div class="text-{{ $formationdocumentstatus['color'] }}-700 bg-{{ $formationdocumentstatus['color'] }}-200 rounded-full px-3 py-0.5 text-xs font-semibold select-none">
                                {{ $formationdocumentstatus['label'] }}
                            </div>
                            @if ($user->formation_document_status == "confirmed")
                                <a href="{{ asset('storage/' . ($user->formation_document ?? '')) }}" class="bg-green-600 text-white px-4 py-2 rounded" download>Download</a>
                            @else
                                <button id="openFormationDocument" class="text-gray-900 text-sm font-normal border border-gray-300 rounded-lg px-5 py-2 hover:bg-gray-50">Upload</button>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- ── MODALS ─────────────────────────────────────────────────────────── --}}

                @php
                    $modals = [
                        ['id' => 'modal',                    'title' => 'CAC Certificate',                'type' => 'cac',                    'field' => 'file'],
                        ['id' => 'modalBvn',                 'title' => 'Bank Verification Number (BVN)', 'type' => 'bvn',                    'field' => 'bvn'],
                        ['id' => 'modalValidID',             'title' => 'Valid ID of Directors/Owners',   'type' => 'valid_id',               'field' => 'file'],
                        ['id' => 'modalTIN',                 'title' => 'Tax Identification Number (TIN)','type' => 'tin',                    'field' => 'file'],
                        ['id' => 'modalUtility',             'title' => 'Utility Bill / Proof of Address','type' => 'utility_bill',           'field' => 'file'],
                        ['id' => 'modalProofOfIdentity',     'title' => 'Proof of Identity',              'type' => 'proof_of_identity',      'field' => 'file'],
                        ['id' => 'modalOwnership',           'title' => 'Ownership',                      'type' => 'ownership',              'field' => 'file'],
                        ['id' => 'modalOrganisationalChart', 'title' => 'Organisational Chart',           'type' => 'organisational_chart',   'field' => 'file'],
                        ['id' => 'modalRegisterOfDirectors', 'title' => 'Register of Directors',          'type' => 'register_of_directors',  'field' => 'file'],
                        ['id' => 'modalFormationDocument',   'title' => 'Formation Document',             'type' => 'formation_document',     'field' => 'file'],
                    ];
                @endphp

                @foreach($modals as $modal)
                <section class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
                    id="{{ $modal['id'] }}" style="display: none;">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
                            <p class="text-xl font-semibold">{{ $modal['title'] }}</p>
                            <button class="close-modal border h-6 w-6 rounded-md flex items-center justify-center">
                                <i class="fas fa-times text-md cursor-pointer text-[#828282]"></i>
                            </button>
                        </div>

                        @if (session('success'))
                        <script>
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: @json(session('success')), showConfirmButton: false, timer: 4000, timerProgressBar: true });
                        </script>
                        @endif
                        @if (session('error'))
                        <script>
                            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: @json(session('error')), showConfirmButton: false, timer: 4000, timerProgressBar: true });
                        </script>
                        @endif
                        @if ($errors->any())
                        <script>
                            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: @json($errors->first()), showConfirmButton: false, timer: 4000, timerProgressBar: true });
                        </script>
                        @endif

                        <form class="space-y-5" method="POST" action="{{ route('compliance.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="document_type" value="{{ $modal['type'] }}">

                            @if($modal['field'] === 'bvn')
                                <label class="flex flex-col text-[#828282] text-md gap-1">
                                    BVN
                                    <input type="text" name="bvn" placeholder="1234567812345678"
                                        class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300 placeholder:text-[#E7E7E7]" />
                                </label>
                            @else
                                <label class="flex flex-col text-[#828282] text-md gap-1">
                                    Upload
                                    <input type="file" name="document" required
                                        class="border border-gray-300 p-2 rounded-2xl text-black focus:outline-none focus:ring-1 focus:ring-blue-300" />
                                </label>
                            @endif

                            <button type="submit" class="w-full bg-[#215F9C] text-white font-medium py-2 px-4 rounded-2xl">
                                Submit
                            </button>
                        </form>
                    </div>
                </section>
                @endforeach

            </section>
        </section>
    </main>

    <script>
        // ── Modal triggers ──────────────────────────────────────────────────
        const triggers = {
            'openCAC':                  'modal',
            'openBVN':                  'modalBvn',
            'openValidID':              'modalValidID',
            'openTIN':                  'modalTIN',
            'openUtility':              'modalUtility',
            'openProofOfIdentity':      'modalProofOfIdentity',
            'openOwnership':            'modalOwnership',
            'openOrganisationalChart':  'modalOrganisationalChart',
            'openRegisterOfDirectors':  'modalRegisterOfDirectors',
            'openFormationDocument':    'modalFormationDocument',
        };

        Object.entries(triggers).forEach(([btnId, modalId]) => {
            document.getElementById(btnId)?.addEventListener('click', () => {
                document.getElementById(modalId).style.display = 'flex';
            });
        });

        // ── Close modals ────────────────────────────────────────────────────
        document.querySelectorAll('.close-modal').forEach(btn => {
            btn.addEventListener('click', function () {
                this.closest('section').style.display = 'none';
            });
        });

        // ── File size check (10MB max) ───────────────────────────────────────
        const MAX_SIZE = 10 * 1024 * 1024; // 10MB in bytes

        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                if (file.size > MAX_SIZE) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large',
                        text: `"${file.name}" is ${(file.size / 1024 / 1024).toFixed(1)}MB. Maximum allowed size is 10MB.`,
                        confirmButtonColor: '#215F9C',
                    });
                    this.value = ''; // clear the input
                    return;
                }

                // Warn for files over 5MB but under 10MB
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Large file',
                        text: `"${file.name}" is ${(file.size / 1024 / 1024).toFixed(1)}MB. This may take a moment to upload.`,
                        confirmButtonColor: '#215F9C',
                    });
                }
            });
        });

        // Block form submit if file is too large
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function (e) {
                const fileInputs = this.querySelectorAll('input[type="file"]');
                for (const input of fileInputs) {
                    if (input.files[0] && input.files[0].size > MAX_SIZE) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'File too large',
                            text: 'Please upload a file smaller than 10MB.',
                            confirmButtonColor: '#215F9C',
                        });
                        return;
                    }
                }
            });
        });

        // ── Sidebar ─────────────────────────────────────────────────────────
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('overlay');

        function openSidebar() { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); document.body.style.overflow = ''; }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) { sidebar.classList.remove('-translate-x-full'); overlay.classList.add('hidden'); document.body.style.overflow = ''; }
            else { sidebar.classList.add('-translate-x-full'); }
        });
    </script>

    <script>
        fetch('/sumsub-token')
        .then(res => res.json())
        .then(data => {
            if (!data.token) { console.error("Sumsub token error", data); return; }
            SumsubWebSdk.init({
                accessToken: data.token,
                containerId: "sumsub-kyc-widget",
                onMessage:  message => console.log("Message:", message),
                onError:    error   => console.error("Error:", error),
                onComplete: result  => console.log("Verification completed:", result)
            });
        });
    </script>
</body>
</html>