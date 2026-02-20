<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>{{ __('Sign In') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<<<<<<< HEAD
    <style>
        .form-group {
            width: 100%;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .form-input {
            width: 100%;
            height: 48px;
            padding: 0 45px 0 15px; /* right padding for icon */
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f9fafb;
            font-size: 14px;
            outline: none;
        }

        .form-input:focus {
            border-color: #000000ff;
        }

        .eye-btn {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 16px;
        }

    </style>
=======
>>>>>>> recovery
</head>
<body>
    <section>

        <!-- logo -->
            <section class="relative">
                <div class="max-w-[300px] px-10 absolute top-10">
                    <a href="{{ route('personal') }}">
                        <img src="../asserts/auth/Logo.svg" alt="" class="w-[90px]" />
                    </a>
                </div>
            </section>
        <!-- logo end-->


        <ol class="flex flex-col w-[100%]">


            <li class="flex  h-screen justify-between w-full items-center  ">
                <section class="">


                    <section class="absolute md:top-[20vh] top-[16vh] w-full px-10">
                        <h1 class="w-full text-[#252525] font-semibold text-xl">
                            {{ __('Sign In') }}
                        </h1>


                        <section>
                            <form class="max-w-xl md:mt-8 mt-10" method="POST" action="{{ route('login') }}">
                                @csrf
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
                                <section class="flex flex-col gap-6">
                                    <!-- bussiness email -->
                                    <div>
<<<<<<< HEAD
                                        <label for="helper-text" class="block mb-2 text-sm font-bold text-[#828282]">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="helper-text" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="johndoe@gmail.com">
=======
                                        <label for="helper-text" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">{{ __('Email') }}</label>
                                        <input type="email" name="email" id="helper-text" aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="johndoe@gmail.com">
>>>>>>> recovery
                                    </div>
                                    <!-- bussiness email end-->

                                    <!-- password -->
<<<<<<< HEAD
                                    <div class="form-group">
                                        <label for="helper-text" class="block mb-2 text-sm font-bold text-[#828282]">{{ __('Password') }}</label>

                                        <div class="password-wrapper">
                                            <input type="password" name="password" id="password" placeholder="••••••••" class="form-input">
                                            <button type="button" id="togglePassword" class="eye-btn">
                                                <i id="eyeIcon" class="fa-solid fa-eye-slash"></i>
                                            </button>
                                        </div>
=======
                                    <div>
                                        <label for="helper-text" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">{{ __('Password') }}</label>
                                        <input type="password" name="password" id="helper-text" aria-describedby="helper-text-explanation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="user1234">
>>>>>>> recovery
                                    </div>

                                     
                                    <section class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center w-full gap-5 ">
                                        <button type="submit" class="md:w-[10em] w-full rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]"
                                           >
                                            {{ __('Sign In') }}
                                        </button>

                                        <div class="flex items-center">
                                            <p><span class="text-[#215F9C]"><a href="forgotpassword">{{ __('Forgot password') }}</a></span></p>
                                            <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                        </div>
                                    </section>


                                    <!-- sign up -->

                                    <section class="flex gap-4 flex-col">
                                        <p>{{ __('Don’t have an account?') }}<a href="register" style="color: rgb(86, 86, 222)">{{ __('Click here to register') }}</a></p>
                                        <a href="{{ env('APP_PLAYSTORE_LINK') }}" target="_blank" class="flex items-center gap-2">
                                            <p><span class="text-[#215F9C]">{{ __('Create personal account') }}</span></p>
                                            <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                            <span class="text-[#215F9C] bg-[#D6E7F5] px-2 py-1 text-sm rounded-full">
                                                <i class="fa fa-mobile px-1" aria-hidden="true"></i>{{ __('mobile') }}
                                            </span>
                                        </a>

                                        {{-- <div class="flex items-center">
                                            <p><span class="text-[#215F9C]">{{ __('Create business account') }}</span></p>
                                            <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                        </div> --}}
                                    </section>

                                    <!-- sign up end-->
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
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = password.type === 'password';

            password.type = isPassword ? 'text' : 'password';

            // Toggle icon
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>


</body>

</html>