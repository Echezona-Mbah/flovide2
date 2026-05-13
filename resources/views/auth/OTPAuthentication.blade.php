<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flovide - Enter OTP</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100">

    <div class="flex bg-white min-h-screen">

        <!-- RIGHT OTP SECTION -->
        <div class="flex w-full md:w-1/2 items-center justify-center p-6">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-sm p-8">
                
                <!-- Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-4 py-2 rounded-full text-sm">
                        Get it quick!
                        <span>⏳</span>
                    </span>
                </div>

                <!-- Error Message -->
                @if(session('error'))
                    <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Title -->
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                    Enter OTP
                </h2>

                <!-- Description -->
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    An OTP was sent to the registered email or phone number. 
                    You can also get an OTP directly from your mobile app.
                </p>

                <!-- OTP Form -->
                <form action="{{ route('otp.verify') }}" method="POST" id="otpForm">
                    @csrf

                    <!-- Hidden combined OTP -->
                    <input type="hidden" name="otp" id="otp">                    

                    <!-- OTP Inputs -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        <span class="text-gray-500 text-lg">—</span>

                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-box flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Links -->
                    <div class="flex justify-between text-sm mb-8">
                        <button type="button" id="resendOtp" class="text-blue-600 hover:underline">
                            Resend OTP (<span id="countdown">05:00</span>s)
                        </button>
                        <!-- <a href="#" class="text-blue-600 hover:underline">
                            Use backup code
                        </a> -->
                    </div>

                    <!-- Continue Button -->
                    <button type="submit" id="submitBtn" class="w-full bg-blue-500 text-white py-3 rounded-lg font-medium hover:bg-blue-600 transition flex items-center justify-center gap-2">
                        <span id="btnText">Continue</span>
                        <svg id="btnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>

                </form>

            </div>
        </div>

        <!-- LEFT IMAGE SECTION -->
        <section class="w-1/2 h-screen bg-[#F0F0F0] items-center justify-center p-14 hidden md:flex">
            <img src="../asserts/auth/user8.png" alt="" class="object-cover max-h-full" />
        </section>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const inputs = document.querySelectorAll(".otp-box");
        const hiddenInput = document.getElementById("otp");
        const otpForm = document.getElementById("otpForm");
        const submitBtn = document.getElementById("submitBtn");
        const btnText = document.getElementById("btnText");
        const btnSpinner = document.getElementById("btnSpinner");

        function setLoading(isLoading) {
            if (isLoading) {
                submitBtn.disabled = true;
                btnText.textContent = "Processing...";
                btnSpinner.classList.remove("hidden");
                inputs.forEach(input => input.disabled = true);
            } else {
                submitBtn.disabled = false;
                btnText.textContent = "Continue";
                btnSpinner.classList.add("hidden");
                inputs.forEach(input => input.disabled = false);
            }
        }

        otpForm.addEventListener("submit", (e) => {
            setLoading(true);
        });

        inputs.forEach((input, index) => {

            // Typing functionality
            input.addEventListener("input", (e) => {

                // Allow only numbers
                input.value = input.value.replace(/\D/g, '');

                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateOTP();

                // Auto submit when complete
                autoSubmitIfComplete();
            });

            // Backspace functionality
            input.addEventListener("keydown", (e) => {
                if (e.key === "Backspace" && !input.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Paste functionality
            input.addEventListener("paste", (e) => {
                e.preventDefault();

                const pastedData = (e.clipboardData || window.clipboardData)
                    .getData("text")
                    .replace(/\D/g, '')
                    .slice(0, inputs.length);

                if (!pastedData) return;

                pastedData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });

                updateOTP();

                // Focus last filled input
                const lastIndex = pastedData.length - 1;

                if (inputs[lastIndex]) {
                    inputs[lastIndex].focus();
                }

                // Auto submit when complete
                autoSubmitIfComplete();
            });
        });

        function updateOTP() {
            let otp = "";

            inputs.forEach((input) => {
                otp += input.value;
            });

            hiddenInput.value = otp;
        }

        function autoSubmitIfComplete() {
            const otp = hiddenInput.value;

            if (otp.length === inputs.length) {
                setLoading(true);
                otpForm.submit();
            }
        }

        // RESEND OTP FUNCTIONALITY
        const resendBtn = document.getElementById('resendOtp');
        const countdownSpan = document.getElementById('countdown');

        let countdown = 300;
        let timer;

        function formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;

            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        }

        function startCountdown() {
            resendBtn.disabled = true;

            countdownSpan.textContent = formatTime(countdown);

            timer = setInterval(() => {
                countdown--;

                countdownSpan.textContent = formatTime(countdown);

                if (countdown <= 0) {
                    clearInterval(timer);

                    resendBtn.disabled = false;

                    countdown = 300;
                    countdownSpan.textContent = formatTime(countdown);
                }
            }, 1000);
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        resendBtn.addEventListener('click', function() {

            resendBtn.disabled = true;

            fetch("{{ route('otp.resend') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(res => res.json())
            .then(data => {

                Toast.fire({
                    icon: data.status === 'success' ? 'success' : 'error',
                    title: data.message || "OTP resent successfully!"
                });

                if (data.status === 'success') {
                    startCountdown();
                } else {
                    resendBtn.disabled = false;
                }
            })
            .catch(err => {

                Toast.fire({
                    icon: 'error',
                    title: "Failed to resend OTP. Try again."
                });

                resendBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
