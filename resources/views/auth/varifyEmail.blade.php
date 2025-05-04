

    
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sign Up</title>
    </head>
    
    <body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <section>
    
            <!-- logo -->
            <section class="relative">
                <div class="max-w-[300px] px-10 absolute top-10">
                    <img src="../asserts/auth/Logo.svg" alt="" class="w-[90px]" />
                </div>
            </section>
            <!-- logo end-->
    
    
        <ol class="flex flex-col w-[100%]">







         <!-- OTP verifications Screens -->
            <!-- stepper 5 -->
            <li class="flex  h-screen justify-between w-full items-center ">
                <section class="">

                    <!-- stepper link -->
                    <section class="md:flex relative top-[-11em] px-10 hidden ">
                        <section
                            class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                            <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                <span>
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                            </div>
                            <div
                                class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                <p>Country</p>
                            </div>
                        </section>

                        <section
                            class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                            <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                <span>
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                            </div>
                            <div
                                class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                <p>Business Info</p>
                            </div>
                        </section>

                        <section
                            class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                            <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                <span>
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                            </div>
                            <div
                                class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                <p>Personal info</p>
                            </div>
                        </section>

                        <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                            <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                <span>
                                    <i class="fa-solid fa-circle-dot"></i>
                                </span>
                            </div>
                            <div
                                class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                <p>Verification</p>
                            </div>
                        </section>
                    </section>
                    <!-- stepper link end-->

                    <!-- stepper content -->
                    <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                        <h1 class="w-full font-semibold">
                            Please Enter The OTP Sent To Your Email
                        </h1>

                        <!-- select country -->
                        <section class="w-full">
                            <form method="POST" action="{{ route('verifyemail.submit', ['email' => $userEmail]) }}" class="max-w-xl mt-10">
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

                            
                                <section class="flex flex-col gap-10">
                                    <div class="flex mb-2 space-x-2 rtl:space-x-reverse">
                                        <div>
                                            <label for="code-1" class="sr-only">First code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-next="code-2" id="code-1" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                        <div>
                                            <label for="code-2" class="sr-only">Second code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-prev="code-1" data-focus-input-next="code-3"
                                                id="code-2" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                        <div>
                                            <label for="code-3" class="sr-only">Third code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-prev="code-2" data-focus-input-next="code-4"
                                                id="code-3" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                        <div>
                                            <label for="code-4" class="sr-only">Fourth code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-prev="code-3" data-focus-input-next="code-5"
                                                id="code-4" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                        <div>
                                            <label for="code-5" class="sr-only">Fifth code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-prev="code-4" data-focus-input-next="code-6"
                                                id="code-5" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                        <div>
                                            <label for="code-6" class="sr-only">Sixth code</label>
                                            <input type="text" maxlength="1" data-focus-input-init
                                                data-focus-input-prev="code-5" id="code-6" name="code[]"
                                                class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                 />
                                        </div>
                                    </div>

                                    <section>
                                        <div class="flex  items-center w-full">
                                            <p>Edit your email - <span class="text-[#215F9C]">{{ $userEmail }}</span>
                                            </p>
                                            <img src="../asserts/icons/edit.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                        </div>
                                        <div class="flex items-center w-full">
                                            <button type="button" id="resendOtpBtn" class="flex items-center gap-1 text-[#215F9C] mt-2">
                                                Didn’t get code? Resend OTP
                                                <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </section>
                                </section>

                                <!-- stepper btn -->
                                <section class="mt-10 flex gap-5 mb-10">
                                <!-- Back Button -->
                                    <button type="button"
                                    class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C] btn-back">
                                    Back
                                    </button>

                                    <!-- Continue Button -->
                                    <button type="submit"
                                    class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] ">
                                    Continue
                                    </button>
                                
                                </section>

                
                                <!-- stepper btn end-->
                            </form>
                        </section>
                        <!-- select country end-->
                    </section>
                    <!-- stepper content end-->

                </section>

                <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                    <img src="../asserts/auth/user6.png" alt="" class="object-cover" />
                </section>
            </li>
            <!-- stepper 5 end-->



        </ol>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Debugging: Check if the button is being clicked
        console.log('Script Loaded: Adding Click Event to Resend OTP Button');
        
        // Bind the click event using jQuery
        $('#resendOtpBtn').on('click', function (e) {
            e.preventDefault();

            // Debugging: Check if button click event is triggered
            console.log('Resend OTP Button Clicked!');

            $.ajax({
                url: "{{ route('resend.otp', ['email' => $userEmail]) }}", // Resend OTP route
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // CSRF token
                },
                success: function (response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.message || 'OTP has been resent.',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Failed to resend OTP. Please try again.',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                }
            });
        });
    });
</script>
    <script>
        window.saveStepDataUrl = "{{ route('register.saveStepData') }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script src="{{ asset('../scripts/script.js') }}"></script>
    <script src="{{ asset('../scripts/stepper.js') }}"></script>
</body>

</html>