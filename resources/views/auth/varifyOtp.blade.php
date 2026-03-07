<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>{{ __('Varify OTP') }}</title>
</head>
<body class="min-h-screen bg-gray-100">
    <section class="relative">
        <div class="max-w-[300px] px-10 absolute top-10">
            <a href="{{ route('personal') }}">
                <img src="../asserts/auth/Logo.svg" alt="" class="w-[90px]" />
            </a>
        </div>
    </section>

    <div class="flex bg-white min-h-screen">


        <!-- RIGHT OTP SECTION -->
        <div class="flex w-full md:w-1/2 items-center justify-center p-6">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-sm p-8">

                <!-- Error Message -->
                @if(session('error'))
                    <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Title -->
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">
                    {{ __('Enter OTP') }}
                </h2>

                <!-- Description -->
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    An OTP was sent to the registered email. 
                    <!-- You can also get an OTP directly from your mobile app. -->
                </p>

                <!-- OTP Form -->
                <form action="{{ route('forget-verify-otp') }}" method="POST" id="otpForm">
                    @csrf
                            
                    @if ($errors->any())
                        <script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: '{{ $errors->first() }}',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                            });
                        </script>
                    @endif
                    
                    @if (session('status'))
                        <script>
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: '{{ session('status') }}',
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true,
                            });
                        </script>
                    @endif
                    
                    <input type="hidden" name="email" value="{{ old('email') }}">               

                    <!-- OTP Inputs -->
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />

                        <span class="text-gray-500 text-lg">—</span>

                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <input type="text" maxlength="1" class="otp-input flex-1 max-w-[45px] aspect-square text-center text-lg rounded-lg border border-gray-300 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <!-- Hidden combined OTP -->
                    <input type="hidden" name="otp" id="otp-hidden">

                    <!-- Links -->
                    <div class="flex justify-between text-sm mb-8">
                        <!-- <button type="button" id="resendOtp" class="text-blue-600 hover:underline">
                            Resend OTP (<span id="countdown">60</span>s)
                        </button> -->
                    </div>

                    <!-- Continue Button -->
                    <button type="submit" class="w-full bg-blue-500 text-white py-3 rounded-lg font-medium hover:bg-blue-600 transition">
                        {{ __('Continue') }}
                    </button>
                            
                    <section class="flex gap-4 flex-col mt-5 text-center">
                        <p>{{ __('Don’t have an account?') }} <a href="register" style="color: rgb(86, 86, 222)">{{ __('Click here to register') }}</a></p>
                    </section>

                </form>

            </div>
        </div>

        <!-- LEFT IMAGE SECTION -->
        <section class="w-1/2 h-screen bg-[#F0F0F0] items-center justify-center p-14 hidden md:flex">
            <img src="../asserts/auth/user8.png" alt="" class="object-cover max-h-full" />
        </section>
    </div>


    <script>
        document.getElementById('otpForm').addEventListener('submit', function (e) {
            let otp = '';
            // for (let i = 1; i <= 6; i++) {
            //     let val = document.getElementById('otp-' + i).value;
            //     if (!val || val.trim() === '') {
            //         Swal.fire({
            //             toast: true,
            //             position: 'top-end',
            //             icon: 'error',
            //             title: '{{ __('Please fill in all OTP digits') }}',
            //             showConfirmButton: false,
            //             timer: 4000,
            //             timerProgressBar: true,
            //         });
            //         e.preventDefault();
            //         return;
            //     }
            //     otp += val;
            // }
    
            // document.getElementById('otp-hidden').value = otp;
        

            let hiddenInput = document.getElementById('otp-hidden');
            inputs.forEach((input) => {
                otp += input.value;
            });
            hiddenInput.value = otp;
        });



        const inputs = document.querySelectorAll(".otp-input");

        // Handle paste OTP
        inputs.forEach((input, index) => {
            input.addEventListener("paste", function(e) {
                e.preventDefault();

                const pasteData = (e.clipboardData || window.clipboardData).getData("text");
                const digits = pasteData.replace(/\D/g, "").split("");

                digits.forEach((digit, i) => {
                    if (inputs[i]) {
                        inputs[i].value = digit;
                    }
                });

                updateOTP();

                // focus last filled input
                const lastIndex = Math.min(digits.length - 1, inputs.length - 1);
                if (lastIndex >= 0) {
                    inputs[lastIndex].focus();
                }
            });
        });
    
        // Optional: Auto-focus next input
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
        });
    </script>
    
</body>

</html>