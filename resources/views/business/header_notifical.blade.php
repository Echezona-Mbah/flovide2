<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
.page-loader {
  position: fixed;
  inset: 0;
  background: radial-gradient(circle at top, #eef6ff 0%, #f5f7fb 50%, #eef2ff 100%);
  display: grid;
  place-items: center;
  z-index: 9999;
  transition: opacity 0.7s ease, visibility 0.7s ease;
}
.page-loader.hide {
  opacity: 0;
  visibility: hidden;
}

.loader-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 14px;
  padding: 32px 40px;
  border-radius: 20px;
  background: rgba(255,255,255,0.9);
  border: 1px solid rgba(148,163,184,0.2);
  box-shadow: 0 30px 70px -35px rgba(15,23,42,0.4);
  backdrop-filter: blur(6px);
  color: #475569;
  font-size: 14px;
}

.logo-wrap {
  position: relative;
  display: grid;
  place-items: center;
  width: 90px;
  height: 90px;
}
.loader-logo {
  width: 70px;
  z-index: 2;
}
.logo-glow {
  position: absolute;
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background: conic-gradient(from 90deg, #3b82f6, #38bdf8, #a5b4fc, #3b82f6);
  filter: blur(8px);
  opacity: 0.55;
  animation: glowSpin 5s linear infinite; /* slower */
}

.orbit {
  position: relative;
  width: 70px;
  height: 70px;
  border: 2px solid #e2e8f0;
  border-radius: 50%;
  animation: orbitSpin 4s linear infinite; /* slower */
}
.orbit .dot {
  position: absolute;
  width: 8px;
  height: 8px;
  background: #2563eb;
  border-radius: 50%;
  top: -4px;
  left: 50%;
  transform: translateX(-50%);
}
.orbit .d2 { background: #38bdf8; transform: translateX(-50%) rotate(120deg); }
.orbit .d3 { background: #a5b4fc; transform: translateX(-50%) rotate(240deg); }

.bar {
  width: 140px;
  height: 6px;
  border-radius: 999px;
  background: #e5e7eb;
  overflow: hidden;
}
.bar span {
  display: block;
  width: 40%;
  height: 100%;
  background: linear-gradient(90deg, #3b82f6, #38bdf8, #6366f1);
  animation: shimmer 2.6s ease-in-out infinite; /* slower */
}

@keyframes glowSpin { to { transform: rotate(360deg); } }
@keyframes orbitSpin { to { transform: rotate(360deg); } }
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(220%); }
}

    </style>
<script>
  window.addEventListener('load', () => {
    const loader = document.getElementById('pageLoader');
    if (!loader) return;

    // Minimum time before hiding (ms)
    const minTime = 80;

    setTimeout(() => {
      loader.classList.add('hide');
    }, minTime);
  });
</script>

<div id="pageLoader" class="page-loader">
  <div class="loader-card">
    <div class="logo-wrap">
      <img src="../asserts/homepage/Logo.png" alt="Logo" class="loader-logo">
      <span class="logo-glow"></span>
    </div>

    <div class="orbit">
      <span class="dot d1"></span>
      <span class="dot d2"></span>
      <span class="dot d3"></span>
    </div>

    <div class="bar">
      <span></span>
    </div>
    <p>Preparing your workspace…</p>
  </div>
</div>
<div class="flex items-center gap-4 flex-wrap">
  <!-- Notifications -->
  <button id="notifyBtn" aria-label="Notifications"
    class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center border border-slate-200 shadow-sm hover:bg-slate-50 transition">
    <i class="fas fa-bell text-slate-600 text-lg"></i>
    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
  </button>

  <!-- Notification Box -->
  <div id="notificationBox"
    class="hidden flex flex-col px-0 pb-6 gap-4 absolute w-[420px] max-w-[95vw] right-[270px] top-[80px] bg-white border border-slate-100 shadow-[0_10px_50px_-20px_rgba(15,23,42,0.35)] rounded-2xl z-50 transition-all duration-300">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-3">
      <h2 class="text-base font-semibold text-slate-900">Notifications</h2>
      <button id="closeNotificationBtn" class="text-slate-500 hover:text-slate-800 text-xl">&times;</button>
    </div>

    <div id="notificationList" class="flex flex-col px-4 gap-4 w-full max-h-[340px] overflow-y-auto">
      <!-- Loop goes here -->
    </div>
  </div>

  <!-- Organization + Logout -->
  <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-full px-3 py-2 shadow-sm">
    <div class="flex items-center space-x-2">
      <div class="w-7 h-7">
        @if(Auth::user()->profile_picture)
          <img src="{{ asset(Auth::user()->profile_picture) }}"
            alt="Profile Picture" class="w-full h-full object-cover rounded-full border border-slate-200">
        @else
          <img src="{{ asset('asserts/dashboard/circle-dot.png') }}"
            alt="Default Icon" class="w-full h-full object-cover rounded-full border border-slate-200">
        @endif
      </div>

      <span class="text-slate-900 font-medium text-sm whitespace-nowrap">
        {{ auth()->user()->business_name }}
      </span>

      <i class="fas fa-chevron-right text-slate-500 text-xs"></i>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
      @csrf
      <button type="submit" aria-label="Logout"
        class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 rounded-full flex items-center justify-center transition">
        <i class="fas fa-sign-out-alt text-white text-sm"></i>
      </button>
    </form>
  </div>
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

<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

<script>
console.log("Script loaded!");

async function loadNotifications() {
  console.log("Function started...");

  try {
    const response = await fetch("/notifications");
    console.log("Fetch response:", response);

    const result = await response.json();
    console.log("JSON result:", result);

    const list = document.getElementById("notificationList");
    list.innerHTML = "";

    result.data.data.forEach(n => {
      const item = `
        <div class="border border-slate-100 bg-slate-50 rounded-xl p-4">
          <div class="flex justify-between items-center">
            <h3 class="font-semibold text-slate-800">${n.data?.title ?? 'Notification'}</h3>
            <p class="text-sm text-slate-500">${moment(n.created_at).fromNow()}</p>
          </div>
          <p class="text-slate-600 mt-2">${n.data?.message ?? ''}</p>
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
