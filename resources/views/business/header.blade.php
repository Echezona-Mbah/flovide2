<header class="bg-blue-100 p-4 flex items-center justify-between md:hidden">
    <!-- Sidebar toggle -->
    <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
        <i class="fas fa-bars text-2xl"></i>
    </button>

    <!-- User profile + greeting as clickable link -->
    <a href="{{ url('/organization_setting') }}" class="flex items-center gap-3">
        <img src="{{ auth()->user()->profile_picture ?? '../../asserts/dashboard/default-user.png' }}"
            alt="{{ auth()->user()->business_name }}"
            class="w-10 h-10 rounded-full object-cover border-2 border-gray-300" />
        <span class="text-sm font-medium text-[#1E1E1E]">
            Hi, {{ auth()->user()->business_name }}
        </span>
    </a>


    <!-- Notifications + Logout aligned horizontally -->
    <div class="flex items-center gap-2">
        <!-- Notifications -->
        <button id="notifyBtn_Mobile" aria-label="Notifications"
                class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full">
            <i class="fas fa-bell text-[#4B4B4B] text-lg"></i>
            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-[#00B37E]"></span>
        </button>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" aria-label="Logout"
                    class="w-10 h-10 flex items-center justify-center bg-red-500 rounded-full hover:bg-red-600 transition">
                <i class="fas fa-sign-out-alt text-white text-lg"></i>
            </button>
        </form>

        <!-- Notification dropdown -->
        <div id="notificationBox_mobile"
             class="hidden absolute top-16 right-0 flex flex-col items-center px-4 py-6 gap-4 w-[90%] max-w-sm bg-white shadow-[0_4px_60px_5px_rgba(0,0,0,0.1)] rounded-[24px] z-50 transition-all duration-300">
            <div class="flex justify-between w-full border-b-2 border-gray-100 pb-2">
                <h2 class="text-lg font-semibold">Notifications</h2>
                <button id="closeNotificationBtn_mobile" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
            </div>
            <div id="notificationList_mobile" class="flex flex-col items-start px-4 gap-4 w-full max-h-80 overflow-y-auto">
                <!-- Loop goes here -->
            </div>
        </div>
    </div>
</header>








    <!-- Add Moment JS -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notifyBtn = document.getElementById('notifyBtn_Mobile');
            const closeNotificationBtn = document.getElementById('closeNotificationBtn_mobile');
            const notificationBox = document.getElementById('notificationBox_mobile');

            notifyBtn.addEventListener('click', () => {
                notificationBox.classList.remove('hidden');
                notificationBox.classList.add('opacity-100', 'translate-y-0');
            });

            closeNotificationBtn.addEventListener('click', () => {
                notificationBox.classList.add('hidden');
            });
        });
                

        async function loadNotifications() {
            console.log("Function started...");

            try {
                const response = await fetch("/notifications");
                console.log("Fetch response:", response);

                const result = await response.json();
                console.log("JSON result:", result);

                const list = document.getElementById("notificationList_mobile");
                list.innerHTML = "";

                result.data.data.forEach(n => {
                    const item = `
                        <div class="border border-gray-200 rounded-xl p-4">
                            <div class="flex justify-between items-center">
                                <h3 class="font-semibold text-gray-800">${n.data?.title ?? 'Notification'}</h3>
                                <p class="text-sm text-gray-500">${moment(n.created_at).fromNow()}</p>
                            </div>
                            <p class="text-gray-600 mt-2">${n.data?.message ?? ''}</p>
                        </div>
                    `;
                    list.innerHTML += item;
                });

            } catch (error) {
                console.error("Error:", error);
            }
        }

        loadNotifications();
    </script>

