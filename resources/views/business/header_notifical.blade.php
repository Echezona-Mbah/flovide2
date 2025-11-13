<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex items-center gap-4 flex-wrap">
    <button id="notifyBtn" aria-label="Notifications"
        class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
        <i class="fas fa-bell text-[#4B4B4B] text-lg"></i>
        <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]"></span>
    </button>
    <div id="notificationBox"
        class="hidden flex flex-col items-center px-0 pb-9 gap-8 isolate absolute w-[422px] h-[434px] right-[270px] top-[80px] bg-white shadow-[0_4px_60px_5px_rgba(0,0,0,0.1)] rounded-[24px] z-50 transition-all duration-300">
        <!-- Header -->
        <div class="flex text-center justify-between border-b-2 w-full border-gray-100 px-6 pt-3 pb-3">
            <h2 class="text-lg font-semibold">Notifications</h2>
            <button id="closeNotificationBtn" class="text-gray-500 hover:text-gray-800 text-xl">&times;</button>
        </div>
        <!-- Notification Content -->
        <div class="flex flex-col items-start px-4 gap-4 w-full h-[340px] overflow-y-auto">
            <!-- Single Notification -->
            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Debit Alert</h3>
                    <p class="text-sm text-gray-500">3min ago</p>
                </div>
                <p class="text-gray-600 mt-2">Your account has been debited with ₦15,000 - James Okoro</p>
            </div>

            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Card POS Transaction</h3>
                    <p class="text-sm text-gray-500">3hr ago</p>
                </div>
                <p class="text-gray-600 mt-2">You spent ₦3,200 at Shoprite Ikeja using your virtual card ending in 3456.
                </p>
            </div>

            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Transaction Failed</h3>
                    <p class="text-sm text-gray-500">3min ago</p>
                </div>
                <p class="text-gray-600 mt-2">Your attempt to transfer ₦25,000 to Chioma Adebayo was unsuccessful due to
                    insufficient balance.</p>
            </div>

            <div class="border border-gray-200 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-semibold text-gray-800">Introducing Dollar Cards!</h3>
                    <p class="text-sm text-gray-500">3min ago</p>
                </div>
                <p class="text-gray-600 mt-2">You can now create virtual dollar cards to shop internationally. Fast,
                    safe, and secure.</p>
            </div>
        </div>
    </div>
    <button aria-label="Select organization Nexus Global"
        class="bg-white rounded-full flex items-center gap-2 py-2 px-4 text-sm font-normal text-[#1E1E1E] whitespace-nowrap">
        <i class="fas fa-bullseye text-[#1E1E1E]"></i>
        Nexus Global
        <i class="fas fa-chevron-right text-[#1E1E1E]"></i>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" aria-label="Logout"
                class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fas fa-sign-out-alt text-[#1E1E1E] text-lg"></i>
            </button>
        </form>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifyBtn = document.getElementById('notifyBtn');
        const closeNotificationBtn = document.getElementById('closeNotificationBtn');
        const notificationBox = document.getElementById('notificationBox');

        notifyBtn.addEventListener('click', () => {
            notificationBox.classList.remove('hidden');
            notificationBox.classList.add('opacity-100', 'translate-y-0');
        });

        closeNotificationBtn.addEventListener('click', () => {
            notificationBox.classList.add('hidden');
        });
    });
</script>