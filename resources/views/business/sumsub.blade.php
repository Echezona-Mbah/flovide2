@include('business.head')

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-4xl bg-white shadow-xl rounded-2xl p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-blue-100">
                <i class="fas fa-id-card text-blue-600 text-2xl"></i>
            </div>

            <h2 class="text-2xl font-bold mt-4 text-gray-800">
                Identity Verification
            </h2>

            <p class="text-gray-500 mt-2 text-sm max-w-md mx-auto">
                To keep your account secure and comply with regulations, we need to verify your identity.
                This process only takes a few minutes.
            </p>
        </div>

        <!-- Verification Steps -->
        <div class="grid md:grid-cols-3 gap-4 mb-8 text-sm text-gray-600">

            <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-lg">
                <i class="fas fa-passport text-blue-500"></i>
                <span>Upload your ID document</span>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-lg">
                <i class="fas fa-camera text-green-500"></i>
                <span>Take a quick selfie</span>
            </div>

            <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-lg">
                <i class="fas fa-check-circle text-purple-500"></i>
                <span>Verification review</span>
            </div>

        </div>

        <!-- Sumsub Widget -->
        <div class="border rounded-xl overflow-hidden">
            <div id="sumsub-kyc-widget" style="height:700px;"></div>
        </div>

    </div>

</div>

<script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>

<script>
async function initSumsub(){

    const response = await fetch('/sumsub-token');
    const data = await response.json();

    console.log("Sumsub token:", data);

    if(!data.token){
        alert("Failed to load token");
        return;
    }

    const snsWebSdkInstance = snsWebSdk
        .init(
            data.token,
            () => fetch('/sumsub-token')
                .then(res => res.json())
                .then(data => data.token)
        )
        .withConf({
            lang: "en"
        })
        .withOptions({
            adaptIframeHeight: true
        })
        .build();

    snsWebSdkInstance.launch('#sumsub-kyc-widget');

}

initSumsub();
</script>










{{-- @include('business.head')

<div class="max-w-3xl mx-auto mt-10">

<h2 class="text-xl font-bold mb-6">
Identity Verification
</h2>

<div id="sumsub-kyc-widget" style="height:700px;"></div>

</div>

<script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>

<script>
async function initSumsub(){

    const response = await fetch('/sumsub-token');
    const data = await response.json();

    console.log("Sumsub token:", data);

    if(!data.token){
        alert("Failed to load token");
        return;
    }

    const snsWebSdkInstance = snsWebSdk
        .init(
            data.token,
            () => fetch('/sumsub-token')
                .then(res => res.json())
                .then(data => data.token)
        )
        .withConf({
            lang: "en"
        })
        .withOptions({
            adaptIframeHeight: true
        })
        .build();

    snsWebSdkInstance.launch('#sumsub-kyc-widget');

}

initSumsub();
</script> --}}