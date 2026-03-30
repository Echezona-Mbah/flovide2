@include('business.head')
<body class="bg-[#EEF2F7] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Organization</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Subscription Plan</h1>
        <p class="mt-1 text-sm text-slate-500">Manage your plan and billing preferences.</p>
      </div>
      @include('business.header_notifical')
    </header>

    <section class="w-full">
      <div class="rounded-[28px] bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 md:px-10 py-7 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Workspace</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Subscription Plan</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                Review your current plan and upgrade when you need more capacity.
              </p>
            </div>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-10 space-y-8">

          <!-- Tabs -->
          <nav class="border-b border-slate-100">
            <ul class="flex flex-wrap gap-6 text-sm font-semibold text-slate-500">
              <li><a class="hover:text-slate-900 block pb-3" href="{{ route('organization') }}">Team Members</a></li>
              <li><a class="hover:text-slate-900 block pb-3" href="{{ route('organization_setting') }}">Settings</a></li>
              <li><a class="text-slate-900 border-b-2 border-slate-900 pb-3 block" href="{{ route('organization_plan') }}">Subscription plan</a></li>
            </ul>
          </nav>

          <!-- Plan Card -->
          <section class="max-w-2xl">
            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
              <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                  <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Current Plan</p>
                  <h3 class="mt-2 text-xl font-semibold text-slate-900">Basic</h3>
                </div>
                <div class="text-right">
                  <p class="text-xs text-slate-500">Price</p>
                  <p class="text-xl font-black text-slate-900">FREE</p>
                </div>
              </div>

              <div class="px-6 py-6 space-y-4 text-sm text-slate-600">
                <div class="flex items-center justify-between">
                  <span>Team members</span>
                  <span class="font-semibold text-slate-900">Up to 3</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Monthly transactions</span>
                  <span class="font-semibold text-slate-900">Limited</span>
                </div>
                <div class="flex items-center justify-between">
                  <span>Support</span>
                  <span class="font-semibold text-slate-900">Standard</span>
                </div>
              </div>

              <div class="px-6 py-5 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs text-slate-500">Need more?</p>
                <button class="text-sm font-semibold text-slate-900 border border-slate-300 rounded-lg px-4 py-2 hover:bg-slate-50">
                  Change Plan
                </button>
              </div>
            </div>
          </section>

        </div>
      </div>
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
