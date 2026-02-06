@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    
    <!-- Mobile menu button -->
    @include('business.header')

    <!-- Sidebar -->
    @include('business.sidebar')
    
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Webhooks
            </h1>
            @include('business.header_notifical')
        </header>
        <section class=" relative w-full">
            <section class="bg-white text-gray-700 min-h-screen w-full md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden ">
                <div class="max-w-[1200px] mx-auto">
                    <div class="w-full max-w-xl bg-white rounded-xl p-6">

                        <!-- Warning -->
                        <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-2 rounded-md mb-6">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.72-1.36 3.485 0l6.516 11.588c.75 1.334-.214 2.985-1.742 2.985H3.483c-1.528 0-2.492-1.651-1.742-2.985L8.257 3.1zM11 14a1 1 0 10-2 0 1 1 0 002 0zm-1-7a1 1 0 00-.993.883L9 8v3a1 1 0 001.993.117L11 11V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Always keep your secret key confidential.</span>
                        </div>

                        <!-- Secret Key -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Secret key</label>
                            <div class="relative">
                                <input type="password" id="secretKey" placeholder="****************" class="w-full border rounded-md px-3 py-2 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                <span id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <a href="#" class="text-sm text-blue-600 mt-1 inline-flex items-center gap-1">
                                <i class="fas fa-sync-alt"></i> Generate a new secret key
                            </a>
                        </div>

                        <!-- Public Key -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Public key</label>
                            <div class="relative">
                                <input type="text" id="publicKey" readonly value="pk_fl_asdfghjklwertyuiytdsdfgh" class="w-full border rounded-md px-3 py-2 pr-10 text-sm bg-gray-50 focus:outline-none" />
                                <span id="copyPublicKey" class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer">
                                    <i class="fas fa-copy"></i>
                                </span>
                            </div>
                        </div>

                        <!-- IP Whitelist -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">IP address whitelist</label>
                            <input type="text" placeholder="Type or paste IP address and press enter to add" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Callback URL -->
                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Callback URL</label>
                            <input type="text" placeholder="https://your-callback-url.com" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Webhook URL -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Webhook URL</label>
                            <input type="text" placeholder="https://your-webhook-url.com" class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <!-- Button -->
                        <button class="bg-blue-100 text-blue-700 text-sm font-medium px-6 py-2 rounded-full hover:bg-blue-200 transition">
                            Save Changes
                        </button>

                    </div>
                </div>
            </section>
        </section>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        openBtn.addEventListener('click', openSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on window resize if desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });


        const togglePassword = document.getElementById("togglePassword");
        const passwordInput = document.getElementById("secretKey");
        const icon = togglePassword.querySelector("i");

        togglePassword.addEventListener("click", () => {
            const isPassword = passwordInput.type === "password";

            // Toggle input type
            passwordInput.type = isPassword ? "text" : "password";

            // Toggle icon
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });



        const copyBtn = document.getElementById("copyPublicKey");
        const publicKeyInput = document.getElementById("publicKey");

        copyBtn.addEventListener("click", () => {
            // Copy text
            publicKeyInput.select();
            publicKeyInput.setSelectionRange(0, 99999); // Mobile support
            document.execCommand("copy");

            // SweetAlert2 Toast
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "Public key copied",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        });
    </script>


</body>

</html>