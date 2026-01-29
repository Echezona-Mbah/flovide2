    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain" height="40" src="../../asserts/dashboard/admin-logo.svg" width="120" />
        <div>
            <button id="notifyBtn_Mobile" aria-label="Notifications" class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-bell text-[#4B4B4B] text-lg"></i>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]"></span>
            </button>

            <div id="notificationBox_mobile" class="hidden flex flex-col items-center px-4 py-6 gap-4 isolate absolute top-16 right-1/2 transform translate-x-1/2 w-[90%] max-w-sm bg-white shadow-[0_4px_60px_5px_rgba(0,0,0,0.1)] rounded-[24px] z-50 transition-all duration-300">
                <!-- Header -->
                <div class="flex text-center justify-between border-b-2 w-full border-gray-100 pb-2">
                    <h2 class="text-lg font-semibold">Notifications</h2>
                    <button id="closeNotificationBtn_mobile" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                </div>

                <!-- Notification Content -->
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

