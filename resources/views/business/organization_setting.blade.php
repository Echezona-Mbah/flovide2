@include('business.head')
<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Mobile menu button -->
  @include('business.header')

    <!-- Sidebar -->
   @include('business.sidebar')
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class="items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Dashboard
            </h1>
                        @include('business.header_notifical')

        </header>
        <section class="relative w-full">

            <section
                class="bg-white text-gray-700 min-h-screen md:w-[80vw] md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden">
                <section class="w-full flex flex-col min-h-screen">
                    <nav class="flex space-x-8 border-b border-gray-200 text-sm font-medium text-gray-700 pt-6 px-4 sm:px-6 lg:px-8">
                              <a class="hover:text-gray-900" href="{{ route('organization') }}">
                                  Team Members
                              </a>
                              <a class="text-gray-900 border-b-2 border-black pb-3 font-semibold" href="{{ route('organization_setting') }}">
                                  Settings
                              </a>
                              <a class="hover:text-gray-900" href="{{ route('organization_plan') }}">
                                  Subscription plan
                              </a>
                          </nav>
                    <div class=" w-full px-4 sm:px-6 lg:px-8">
                      
                        <section class="flex flex-col md:flex-row mt-8  border-gray-200 pt-8">
                            <!-- Left side -->
                            <section class="md:w-2/3 pr-0 md:pr-12">

                                <!-- Identification -->
                                {{-- <div class="mb-10">
                                    <h2 class="font-semibold text-lg mb-4">Identification</h2>

                                    <form class="flex flex-col space-y-3 max-w-xl">
                                        <label class="text-gray-500 text-sm font-semibold" for="business-name">
                                            Business name
                                        </label>
                                        <div class="flex space-x-4">
                                            <input
                                                id="business-name"
                                                type="text"
                                                value="Nexus Global"
                                                class="flex-grow rounded-lg border border-gray-300 px-4 py-2 text-sm
                                                    focus:outline-none focus:ring-1 focus:ring-black"
                                            />
                                            <button
                                                type="button"
                                                class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold
                                                    hover:bg-gray-100">
                                                Update
                                            </button>
                                        </div>
                                    </form>

                                    <div
                                        role="alert"
                                        class="mt-4 max-w-xl rounded-md border border-blue-300 bg-blue-50
                                            px-4 py-2 text-blue-700 text-sm flex items-center space-x-2"
                                    >
                                        <svg
                                            aria-hidden="true"
                                            class="w-4 h-4 flex-shrink-0"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z">
                                            </path>
                                            <line x1="12" x2="12" y1="9" y2="13"></line>
                                            <line x1="12" x2="12" y1="17" y2="17"></line>
                                        </svg>
                                        <p>Changes will first be reviewed before confirmation is granted.</p>
                                    </div>
                                </div> --}}

                                <!-- General -->
                                <div>
                                    <h3 class="font-semibold text-lg mb-4">General</h3>

                                    <span class="max-w-xl space-y-6">

                                        <!-- Change Password -->
                                        <div class="flex items-center justify-between max-w-xl">
                                            <div>
                                                {{-- <label id="openChangePasswordModal" class="text-gray-500 text-sm font-semibold block" for="change-password">
                                                    Change password
                                                </label> --}}
                                                <p class="text-xs">
                                                    A confirmation link will be sent to your email
                                                </p>
                                            </div>
                                       <!-- Change Password -->
                                        <button type="button" id="openChangePasswordModal"
                                            class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold hover:bg-gray-100">
                                            Change
                                        </button>
                                            <div id="changePasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                                                    <h2 class="text-lg font-semibold mb-4">Change Password</h2>
                                            <form action="{{ route('organization_setting.store') }}" method="POST">
                                                 @csrf
                                                    @if ($errors->any())
                                                    <script>
                                                        Swal.fire({
                                                            toast: true,
                                                            position: 'top-end',
                                                            icon: 'error',
                                                            title: @json($errors->first()),
                                                            showConfirmButton: false,
                                                            timer: 4000,
                                                            timerProgressBar: true,
                                                        });
                                                    </script>
                                                    @endif

                                                    @if (session('error'))
                                                    <script>
                                                        Swal.fire({
                                                            toast: true,
                                                            position: 'top-end',
                                                            icon: 'error',
                                                            title: @json(session('error')),
                                                            showConfirmButton: false,
                                                            timer: 4000,
                                                            timerProgressBar: true,
                                                        });
                                                    </script>
                                                    @endif

                                                    
                                                    @if (session('success'))
                                                    <script>
                                                        Swal.fire({
                                                            toast: true,
                                                            position: 'top-end',
                                                            icon: 'success',
                                                            title: @json(session('success')),
                                                            showConfirmButton: false,
                                                            timer: 4000,
                                                            timerProgressBar: true,
                                                        });
                                                    </script>
                                                    @endif
                                              <input type="hidden" name="form_type" value="password">

                                                <!-- Old Password -->
                                                <div class="relative mb-3">
                                                    <input type="password" id="old_password" name="old_password" placeholder="Current Password"
                                                        class="w-full border rounded-lg px-4 py-2" required>
                                                    <span onclick="togglePassword('old_password', this)"
                                                        class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>
                                                </div>

                                                <!-- New Password -->
                                               <div class="relative mb-3">
                                                    <input type="password" id="password" name="password" placeholder="New Password"
                                                        class="w-full border rounded-lg px-4 py-2" required>
                                                    <span onclick="togglePassword('password', this)"
                                                        class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>

                                                    <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">
                                                        Password must be at least 8 characters, include uppercase, lowercase, number, and symbol.
                                                    </p>
                                                </div>


                                                <!-- Confirm Password -->
                                                <div class="relative mb-4">
                                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password"
                                                        class="w-full border rounded-lg px-4 py-2" required>
                                                    <span onclick="togglePassword('password_confirmation', this)"
                                                        class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>
                                                </div>

                                                <div class="flex justify-end space-x-2">
                                                    <button type="button" id="closeChangePasswordModal"
                                                            class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-black text-white rounded-lg">Update</button>
                                                </div>
                                            </form>

                                                </div>
                                            </div>

                                        </div>

                                        <!-- Deactivate Account -->
                                        <div class="flex items-center justify-between max-w-xl">
                                            <div>
                                                {{-- <label id="openDeactivateModal" class="text-gray-400 text-sm font-semibold block" for="deactivate-account">
                                                    Deactivate account
                                                </label> --}}
                                                <p class="text-sm">
                                                    This will permanently delete your account
                                                </p>
                                            </div>

                                            <button type="button" id="openDeactivateModal"
                                                class="rounded-lg border border-red-300 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-50">
                                                Deactivate account
                                            </button>
                                        </div>
                                       <div id="deactivateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                                            <div class="bg-white rounded-lg p-6 w-full max-w-md">

                                                <h2 class="text-lg font-semibold mb-4 text-red-600">Deactivate Account</h2>
                                                <p class="mb-4 text-sm">Are you sure you want to deactivate your account?</p>

                                                <form method="POST" action="{{ route('organization_setting.store') }}">
                                                    @csrf
                                                      @if ($errors->any())
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json($errors->first()),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json(session('error')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        
                                        @if (session('success'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'success',
                                                title: @json(session('success')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif
                                                    <input type="hidden" name="form_type" value="deactivate">
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button" id="closeDeactivateModal"
                                                            class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-600 text-white rounded-lg">Deactivate</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>



                        </span>
                                </div>

                            </section>

                            <!-- Right side -->
                            <aside
                                class="md:w-1/3 mt-12 md:mt-0 border-t md:border-t-0 md:border-l border-gray-200 pt-8 md:pt-0 md:pl-12 flex flex-col items-start">
                           <form action="{{ route('organization_setting.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
    @csrf
                 @if ($errors->any())
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json($errors->first()),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        @if (session('error'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'error',
                                                title: @json(session('error')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif

                                        
                                        @if (session('success'))
                                        <script>
                                            Swal.fire({
                                                toast: true,
                                                position: 'top-end',
                                                icon: 'success',
                                                title: @json(session('success')),
                                                showConfirmButton: false,
                                                timer: 4000,
                                                timerProgressBar: true,
                                            });
                                        </script>
                                        @endif
    <input type="hidden" name="form_type" value="updateProfile">

    <section class="flex items-center space-x-4">
        <!-- Profile Image Preview -->
        <div class="rounded-full w-20 h-20 overflow-hidden flex items-center justify-center bg-gray-200">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset(Auth::user()->profile_picture) }}" 
                     alt="Profile Picture" class="w-full h-full object-cover">
            @else
                <img src="../../asserts/dashboard/circle-dot.png" 
                     alt="Default Icon" class="w-16">
            @endif
        </div>

        <div>
            <p class="font-semibold mb-1">Profile photo</p>
            <p class="text-gray-600 mb-3 text-sm max-w-xs">
                We support PNGs and JPGs under 10MB
            </p>

            <!-- Hidden Input -->
            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">

            <!-- Button to trigger file select -->
            <button type="button"
                onclick="document.getElementById('profile_picture').click()"
                class="rounded-full border border-gray-300 px-4 py-2 text-sm hover:bg-gray-100">
                Upload new image
            </button>
        </div>
    </section>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Save Changes
    </button>
</form>
<script>
    const fileInput = document.getElementById('profile_picture');
    fileInput.addEventListener('change', function(event) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('section img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

                            </aside>
                        </section>
                    </div>
                </section>
            </section>

        </section>
        </section>
    </main>


    <script>
    // Change Password Modal
    document.getElementById('openChangePasswordModal').addEventListener('click', () => {
        document.getElementById('changePasswordModal').classList.remove('hidden');
        document.getElementById('changePasswordModal').classList.add('flex');
    });
    document.getElementById('closeChangePasswordModal').addEventListener('click', () => {
        document.getElementById('changePasswordModal').classList.add('hidden');
    });

    // Deactivate Modal
    document.getElementById('openDeactivateModal').addEventListener('click', () => {
        document.getElementById('deactivateModal').classList.remove('hidden');
        document.getElementById('deactivateModal').classList.add('flex');
    });
    document.getElementById('closeDeactivateModal').addEventListener('click', () => {
        document.getElementById('deactivateModal').classList.add('hidden');
    });

    function togglePassword(fieldId, icon) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
        icon.textContent = "🙈"; // change icon when visible
    } else {
        field.type = "password";
        icon.textContent = "👁️"; // change back to eye
    }
}
</script>

<script>
const passwordField = document.getElementById('password');
const passwordError = document.getElementById('passwordError');

// Regex pattern for strong password
const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

passwordField.addEventListener('input', function () {
    if (strongPasswordRegex.test(passwordField.value)) {
        // ✅ Valid password
        passwordField.classList.remove('border-red-500');
        passwordField.classList.add('border-green-500');
        passwordError.classList.add('hidden');
    } else {
        // ❌ Invalid password
        passwordField.classList.remove('border-green-500');
        passwordField.classList.add('border-red-500');
        passwordError.classList.remove('hidden');
    }
});
</script>




    <script>
        const sidebar = document.getElementById("sidebar");
        const openBtn = document.getElementById("openSidebarBtn");
        const closeBtn = document.getElementById("closeSidebarBtn");
        const overlay = document.getElementById("overlay");

        function openSidebar() {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
            document.body.style.overflow = "";
        }

        openBtn.addEventListener("click", openSidebar);
        closeBtn.addEventListener("click", closeSidebar);
        overlay.addEventListener("click", closeSidebar);

        // Close sidebar on window resize if desktop
        window.addEventListener("resize", () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove("-translate-x-full");
                overlay.classList.add("hidden");
                document.body.style.overflow = "";
            } else {
                sidebar.classList.add("-translate-x-full");
            }
        });

    </script>
</body>

</html>