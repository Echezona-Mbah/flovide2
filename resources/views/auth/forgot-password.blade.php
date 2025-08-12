


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <title>Forgot Password</title>
</head>

<body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                            forgot-password
                        </h1>


                        <section>
                            <form class="max-w-xl md:mt-8 mt-10" method="POST" action="{{ route('forgotpassword') }}">
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
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Email</label>
                                        <input type="email" name="email" id="helper-text" aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="johndoe@gmail.com">
                                    </div>

                                    <!-- bussiness email end-->




                                    <section
                                        class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center w-full gap-5 ">
                                        <button type="submit"
                                            class="md:w-[10em] w-full rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]"
                                           >
                                            Send Code
                                        </button>

                                 
                                    </section>


                                    <!-- sign up -->

                                    <section class="flex gap-4 flex-col">
                                        <p>Don’t have an account?<a href="register" style="color: rgb(86, 86, 222)">Click here to register</a></p>
                                        {{-- <div class="flex items-center">
                                            <p><span class="text-[#215F9C]">Create personal account</span></p>
                                            <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                            <span
                                                class="text-[#215F9C]  bg-[#D6E7F5] px-2 py-1 text-sm  rounded-full"><i
                                                    class="fa fa-mobile px-1" aria-hidden="true"></i>mobile</span>
                                        </div>
                                        <div class="flex items-center">
                                            <p><span class="text-[#215F9C]">Create business account</span></p>
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


</body>

</html>