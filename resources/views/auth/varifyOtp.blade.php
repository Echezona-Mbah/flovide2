


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Sign In</title>
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


            <li class="flex  h-screen justify-between w-full items-center  ">
                <section class="">


                    <section class="absolute md:top-[20vh] top-[16vh] w-full px-10">
                        <h1 class="w-full text-[#252525] font-semibold text-xl">
                            forgot-password
                        </h1>


                        <section>
                            <form method="POST" action="{{ route('forget-verify-otp') }}" id="otpForm">
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
                            
                                <input type="hidden" name="email" value="{{ old('email') }}"> {{-- Optional if you need email for verification --}}
                            
                                <section class="flex flex-col gap-6">
                                    <div>
                                        <label class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Enter OTP</label>
                                        <div class="flex gap-3">
                                            @for ($i = 1; $i <= 6; $i++)
                                                <input type="text" maxlength="1" class="otp-input w-10 h-10 text-center border rounded-lg text-xl dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="otp-{{ $i }}">
                                            @endfor
                                        </div>
                                        <input type="hidden" name="otp" id="otp-hidden">
                                    </div>
                            
                                    <section class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center w-full gap-5 ">
                                        <button type="submit"
                                            class="md:w-[10em] w-full rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]">
                                            Continue
                                        </button>
                                    </section>
                            
                                    <section class="flex gap-4 flex-col">
                                        <p>Don’t have an account? <a href="register" style="color: rgb(86, 86, 222)">Click here to register</a></p>
                                    </section>
                                </section>
                            </form>
                            


                        </section>

                        <section class="relative">
                            <section
                                class="absolute bottom-[-4em] w-full md:w-[45%] flex flex-col md:flex-row gap-2 md:justify-between  md:items-center ">
                                <p>© Flovide 2025</p>
                                <p><span><i class="fa-solid fa-envelope"></i></span>info@flovide.com</p>
                            </section>
                        </section>
                    </section>



                </section>

                <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                    <img src="../asserts/auth/user8.png" alt="" class="object-cover" />
                </section>
            </li>

        </ol>
    </section>




    <script>
        document.getElementById('otpForm').addEventListener('submit', function (e) {
            let otp = '';
            for (let i = 1; i <= 6; i++) {
                let val = document.getElementById('otp-' + i).value;
                if (!val || val.trim() === '') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Please fill in all OTP digits',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                    e.preventDefault();
                    return;
                }
                otp += val;
            }
    
            document.getElementById('otp-hidden').value = otp;
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