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
                    <nav class="border-b border-gray-200">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <ul class="flex space-x-8 text-sm font-normal text-gray-700 h-14 items-center">
                                <li>
                                    <a href="{{ route('organization') }}" class="hover:text-gray-900">Team Members</a>
                                </li>
                                <li>
                                    <a href="{{ route('organization_setting') }}" class="hover:text-gray-900">Settings</a>
                                </li>
                                <li>
                                    <a href="{{ route('organization_plan') }}" aria-current="page"
                                        class="font-semibold text-gray-900 border-b-2 border-black pb-3">Subscription
                                        plan</a>
                                </li>
                            </ul>
                        </div>
                    </nav>

                      <section class="w-full px-4 sm:px-6 lg:px-8 mt-8">
                    <section class="border border-gray-300 rounded-lg max-w-2xl mx-auto">
                        <header class="flex justify-between items-center border-b border-gray-300 px-6 py-4">
                            <h2 class="font-semibold text-gray-900 text-base">Current Plan</h2>
                            <button
                                class="text-sm font-normal text-gray-900 border border-gray-300 rounded-lg px-4 py-2 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-400">Change
                                Plan</button>
                        </header>
                        <div class="flex justify-between items-center px-6 py-4 text-gray-900 text-sm">
                            <span>Basic</span>
                            <span class="font-semibold">FREE</span>
                        </div>
                    </section>
                    </section>
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