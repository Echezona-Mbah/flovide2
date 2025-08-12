

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('../style/style.css')}}">
    <link rel="stylesheet" href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css')}}">
    <link rel="preconnect" href="{{asset('https://fonts.googleapis.com')}}">
    <link rel="preconnect" href="{{asset('https://fonts.gstatic.com')}}" crossorigin>

    <title>Sign In</title>
</head>

<body>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <section>

        <!-- logo -->
               <section class="relative">
                <div class="max-w-[300px] px-10 absolute top-10">
                    <a href="{{ route('personal') }}">
                        <img src="{{asset('../asserts/auth/Logo.svg')}}" alt="" class="w-[90px]" />
                    </a>
                </div>
            </section>
        <!-- logo end-->


        <ol class="flex flex-col w-[100%]">


            <li class="flex  h-screen justify-between w-full items-center  ">
                <section class="">


                    <section class="absolute md:top-[20vh] top-[16vh] w-full px-10">
                           <!-- Header -->
                            <div class="text-center mb-6">
                                <h2 class="text-2xl font-bold text-gray-800">Accept Team Invitation</h2>
                                <p class="text-gray-600 text-sm mt-1">
                                    You've been invited to join <span class="font-semibold">   {{ $member->userOwner->business_name ?? 'our team' }}</span>
                                </p>
                            </div>


                        <section>
                            <form class="max-w-xl md:mt-8 mt-10" method="POST" action="{{ route('team.accept-invite', $member->invite_token) }}">
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

                                        <div>
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Full Name</label>
                                        <input type="name" name="name" id="helper-text" value="{{ old('name', $member->name) }}" aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="">
                                    </div>
                                    <!-- bussiness email -->
                                    <div>
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Email</label>
                                        <input type="email" name="email" id="helper-text" value="{{ old('email', $member->email) }}" aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="johndoe@gmail.com">
                                    </div>

                                    <!-- bussiness email end-->

                                    <!-- password -->
                                    <div>
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Password</label>
                                        <input type="password" name="password" id="helper-text"
                                            aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="password">
                                    </div>

                                       <div>
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="helper-text"
                                            aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Re-enter your password">
                                    </div>
                                    <!-- password end-->


                                    <section
                                        class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center w-full gap-5 ">
                                        <button type="submit"
                                            class="md:w-[10em] w-full rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]"
                                           >
                                           Accept Invitation
                                        </button>

                                      
                                    </section>


                                    <!-- sign up -->

                                  

                                    <!-- sign up end-->
                                </section>
                            </form>


                        </section>

                        {{-- <section class="relative">
                            <section
                                class="absolute bottom-[-4em] w-full md:w-[45%] flex flex-col md:flex-row gap-2 md:justify-between  md:items-center ">
                                <p>© Flovide 2025</p>
                                <p><span><i class="fa-solid fa-envelope"></i></span>info@flovide.com</p>
                            </section>
                        </section> --}}
                    </section>



                </section>

                <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                    <img src="{{asset('../asserts/auth/user8.png')}}" alt="" class="object-cover" />
                </section>
            </li>

        </ol>
    </section>


</body>

</html>