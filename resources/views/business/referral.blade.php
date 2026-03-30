@include('business.head')

<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
        Referral Rewards
      </h1>
      @include('business.header_notifical')
    </header>

    <section class="mx-auto max-w-5xl">
      <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

        <!-- Header -->
        <div class="px-6 md:px-10 py-8 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 text-slate-900 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Referrals</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Make Referrals, Get Rewards</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                Invite users and earn cash rewards when they deposit successfully.
              </p>
            </div>
            <div class="rounded-2xl bg-white/70 border border-sky-200/60 px-4 py-3 text-sm text-slate-700">
              Status: <span class="font-semibold">Active</span>
            </div>
          </div>
        </div>

        <!-- Body -->
        <div class="p-6 md:p-10 space-y-8">

          <!-- Reward Cards -->
          <div class="grid md:grid-cols-2 gap-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Personal</p>
              <h3 class="mt-2 text-xl font-semibold text-slate-900">Earn £10</h3>
              <p class="mt-2 text-sm text-slate-600">
                When your invitee deposits a total of <span class="font-semibold text-slate-900">£1,000</span> within 15 days.
              </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
              <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Business</p>
              <h3 class="mt-2 text-xl font-semibold text-slate-900">Earn £50</h3>
              <p class="mt-2 text-sm text-slate-600">
                When your invitee deposits a total of <span class="font-semibold text-slate-900">£2,000</span> within 15 days.
              </p>
            </div>
          </div>

          <!-- Share Link -->
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
            <p class="text-sm font-semibold text-slate-900 mb-2">Share your referral link</p>
            <div class="flex flex-col sm:flex-row gap-3">
              <div class="flex items-center bg-white rounded-full px-4 py-3 w-full border border-slate-200">
                <input type="text" id="referralInput" value="{{ $referralLink }}" readonly
                  class="bg-transparent w-full outline-none text-slate-700 text-sm" />
              </div>
              <button id="copyReferralBtn"
                class="bg-slate-900 hover:bg-slate-800 text-white font-medium px-6 py-3 rounded-full flex items-center justify-center gap-2 transition">
                Share
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12v.01M4 6v.01M4 18v.01M12 6l6 6-6 6" />
                </svg>
              </button>
            </div>
          </div>

        </div>
      </div>
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

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    });

    const copyBtn = document.getElementById('copyReferralBtn');
    const referralInput = document.getElementById('referralInput');

    copyBtn.addEventListener('click', () => {
      referralInput.select();
      referralInput.setSelectionRange(0, 99999);

      navigator.clipboard.writeText(referralInput.value).then(() => {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Referral link copied!',
          showConfirmButton: false,
          timer: 2000,
          timerProgressBar: true
        });
      }).catch((err) => {
        console.error('Failed to copy: ', err);
      });
    });
  </script>
</body>
</html>
