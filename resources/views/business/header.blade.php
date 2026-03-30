<header class="bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70 p-4 flex items-center justify-between md:hidden">
  <!-- Sidebar toggle -->
  <button aria-label="Open sidebar" id="openSidebarBtn" class="text-slate-900 focus:outline-none">
    <i class="fas fa-bars text-2xl"></i>
  </button>

  <!-- User profile + greeting -->
  <a href="{{ url('/organization_setting') }}" class="flex items-center gap-3">
    <div class="relative">
      <img src="{{ auth()->user()->profile_picture ?? '../../asserts/dashboard/default-user.png' }}"
        alt="{{ auth()->user()->business_name }}"
        class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm" />
      <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white"></span>
    </div>
    <div class="leading-tight">
      <p class="text-[11px] text-slate-500">Welcome</p>
      <p class="text-sm font-semibold text-slate-900 truncate max-w-[110px]">
        {{ auth()->user()->business_name }}
      </p>
    </div>
  </a>

  <!-- Notifications + Logout -->
  <div class="flex items-center gap-2">
    <button id="notifyBtn_Mobile" aria-label="Notifications"
      class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm border border-slate-200">
      <i class="fas fa-bell text-slate-600 text-lg"></i>
      <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
    </button>

    <form method="POST" action="{{ route('logout') }}" class="flex items-center m-0 p-0">
      @csrf
      <button type="submit" aria-label="Logout"
        class="w-10 h-10 flex items-center justify-center bg-red-500 rounded-full hover:bg-red-600 transition shadow-sm">
        <i class="fas fa-sign-out-alt text-white text-lg"></i>
      </button>
    </form>
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
          <div class="border border-gray-200 rounded-xl p-4 bg-white shadow-sm">
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
