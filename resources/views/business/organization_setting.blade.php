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
                                <div class="mb-10">
                                    <h2 class="font-semibold text-lg mb-4">
                                        Identification
                                    </h2>
                                    <form class="flex flex-col space-y-3 max-w-xl">
                                        <label class="text-gray-500 text-sm font-semibold" for="business-name">
                                            Business name
                                        </label>
                                        <div class="flex space-x-4">
                                            <input
                                                class="flex-grow rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-black"
                                                id="business-name" type="text" value="Nexus Global" />
                                            <button
                                                class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold hover:bg-gray-100"
                                                type="button">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-4 max-w-xl rounded-md border border-blue-300 bg-blue-50 px-4 py-2 text-blue-700 text-sm flex items-center space-x-2"
                                        role="alert">
                                        <svg aria-hidden="true" class="w-4 h-4 flex-shrink-0" fill="none"
                                            stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" viewbox="0 0 24 24">
                                            <path
                                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z">
                                            </path>
                                            <line x1="12" x2="12" y1="9" y2="13">
                                            </line>
                                            <line x1="12" x2="12" y1="17" y2="17">
                                            </line>
                                        </svg>
                                        <p>
                                            Changes will first be reviewed before confirmation is granted.
                                        </p>
                                    </div>
                                </div>
                                <!-- General -->
                                <div>
                                    <h3 class="font-semibold text-lg mb-4">
                                        General
                                    </h3>
                                    <form class="max-w-xl space-y-6">
                                        <div>
                                            <label class="text-gray-500 text-sm font-semibold mb-1 block" for="email">
                                                Email
                                            </label>
                                            <div class="flex space-x-4">
                                                <input
                                                    class="flex-grow rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-black"
                                                    id="email" type="email" value="nexg@gmail.com" />
                                                <button
                                                    class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold hover:bg-gray-100"
                                                    type="button">
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between max-w-xl">
                                            <div>
                                                <label class="text-gray-500 text-sm font-semibold block"
                                                    for="change-password">
                                                    Change password
                                                </label>
                                                <p class="text-xs">
                                                    A confirmation link will be sent to your email
                                                </p>
                                            </div>
                                            <button
                                                class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-semibold hover:bg-gray-100"
                                                type="button">
                                                Change
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between max-w-xl">
                                            <div>
                                                <label class="text-gray-400 text-sm font-semibold block"
                                                    for="deactivate-account">
                                                    Deactivate account
                                                </label>
                                                <p class="text-sm">
                                                    This will permanently delete your account
                                                </p>
                                            </div>
                                            <button
                                                class="rounded-lg border border-red-300 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                                                type="button">
                                                Deactivate account
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </section>
                            <!-- Right side -->
                            <aside
                                class="md:w-1/3 mt-12 md:mt-0 border-t md:border-t-0 md:border-l border-gray-200 pt-8 md:pt-0 md:pl-12 flex flex-col items-start">
                           <section class="flex items-center space-x-4">
                                 <div aria-label="Profile photo placeholder with icon"
                                    class="rounded-full  w-20 h-20 flex items-center justify-center mb-4">
                                    <img alt="Icon representing profile photo upload" aria-hidden="true" class="w-16"
                                        height="30"
                                        src="../../asserts/dashboard/circle-dot.png"
                                        width="30" />
                                </div>
                                <div>
                                    <p class="font-semibold mb-1">
                                        Profile photo
                                    </p>
                                    <p class="text-gray-600 mb-3 text-sm max-w-xs">
                                        We support PNGs and JPGs under 10MB
                                    </p>
                                    <button
                                        class="rounded-full border border-gray-300 px-4 py-2 text-sm hover:bg-gray-100"
                                        type="button">
                                        Upload new image
                                    </button>
                                </div>
                           </section>
                            </aside>
                        </section>
                    </div>
                </section>
            </section>

        </section>
        </section>
    </main>
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