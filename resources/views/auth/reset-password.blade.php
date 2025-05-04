


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
                            Reset Password
                        </h1>


                        <section>
                            <form class="max-w-xl md:mt-8 mt-10" method="POST" action="{{ route('resetPassword') }}">
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
                                    {{-- <div>
                                        <label for="helper-text"
                                            class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Email</label>
                                        <input type="email" name="email" id="helper-text" aria-describedby="helper-text-explanation"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="johndoe@gmail.com">
                                    </div> --}}

                                    <!-- bussiness email end-->

                                  <!-- Password -->
                                  <div class="relative">
                                    <label for="password" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Password</label>
                                    <input type="password" name="password" id="password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="user1234" onkeyup="checkPasswordStrength()">
                                
                                    <!-- Eye toggle button -->
                                    <button type="button" onclick="togglePassword('password')"
                                        class="absolute right-3 top-[42px] text-gray-600 dark:text-gray-300">
                                        👁️
                                    </button>
                                </div>

                                    <!-- Confirm Password -->
                                    <div class="relative mt-4">
                                        <label for="password_confirmation" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="password-toggle bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="user1234">
                                        
                                        <!-- Eye icon -->
                                        <button type="button" onclick="togglePassword('password_confirmation')"
                                            class="absolute right-3 top-[42px] text-gray-600 dark:text-gray-300">
                                            👁️
                                        </button>
                                    </div>

                                    <!-- password end-->

                                    <!-- Password strength checklist -->
                                        <ul class="text-sm mt-2 space-y-1 text-gray-600 dark:text-gray-300" id="password-criteria">
                                            <li id="lowercase" class="text-red-500">✖ At least one lowercase letter</li>
                                            <li id="uppercase" class="text-red-500">✖ At least one uppercase letter</li>
                                            <li id="number" class="text-red-500">✖ At least one number</li>
                                            <li id="special" class="text-red-500">✖ At least one special character (@$!%*?&)</li>
                                            <li id="length" class="text-red-500">✖ Minimum 8 characters</li>
                                        </ul>


                                    <section
                                        class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center w-full gap-5 ">
                                        <button type="submit"
                                            class="md:w-[10em] w-full rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]"
                                           >
                                           Reset Password
                                        </button>

                                        {{-- <div class="flex items-center">
                                            <p><span class="text-[#215F9C]"><a href="forgotpassword">Forgot password</a></span></p>
                                            <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">

                                        </div> --}}
                                    </section>


                                    <!-- sign up -->

                                    <section class="flex gap-4 flex-col">
                                        <p>Don’t have an account?<a href="register" style="color: rgb(86, 86, 222)">Click here to register</a></p>
                                 
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
<script>
    function togglePassword(fieldId) {
        const input = document.getElementById(fieldId);
        input.type = input.type === "password" ? "text" : "password";
    }

    function checkPasswordStrength() {
        const password = document.getElementById("password").value;

        // Define criteria
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[@$!%*?&]/.test(password);
        const hasLength = password.length >= 8;

        // Update visual feedback
        document.getElementById("lowercase").className = hasLower ? "text-green-600" : "text-red-500";
        document.getElementById("lowercase").innerHTML = hasLower ? "✔ Contains lowercase letter" : "✖ At least one lowercase letter";

        document.getElementById("uppercase").className = hasUpper ? "text-green-600" : "text-red-500";
        document.getElementById("uppercase").innerHTML = hasUpper ? "✔ Contains uppercase letter" : "✖ At least one uppercase letter";

        document.getElementById("number").className = hasNumber ? "text-green-600" : "text-red-500";
        document.getElementById("number").innerHTML = hasNumber ? "✔ Contains number" : "✖ At least one number";

        document.getElementById("special").className = hasSpecial ? "text-green-600" : "text-red-500";
        document.getElementById("special").innerHTML = hasSpecial ? "✔ Contains special character" : "✖ At least one special character (@$!%*?&)";

        document.getElementById("length").className = hasLength ? "text-green-600" : "text-red-500";
        document.getElementById("length").innerHTML = hasLength ? "✔ Minimum 8 characters" : "✖ Minimum 8 characters";
    }
</script>



</html>