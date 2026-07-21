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
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Settings</h1>
        <p class="mt-1 text-sm text-slate-500">Manage security and profile settings.</p>
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
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Organization Settings</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">
                Update security settings and manage organization profile.
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
              <li><a class="text-slate-900 border-b-2 border-slate-900 pb-3 block" href="{{ route('organization_setting') }}">Settings</a></li>
              <li><a class="hover:text-slate-900 block pb-3" href="{{ route('organization_plan') }}">Subscription plan</a></li>
            </ul>
          </nav>

          <div class="grid lg:grid-cols-[2fr_1fr] gap-10">
            <!-- Left -->
            <section class="space-y-8">

              <div class="rounded-2xl border border-slate-100 bg-slate-50/60 p-6">
                <h3 class="text-lg font-semibold mb-4">General</h3>

                <!-- Change Password -->
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-slate-600">Change password</p>
                    <p class="text-xs text-slate-500">A confirmation link will be sent to your email</p>
                  </div>
                  <button type="button" id="openChangePasswordModal"
                    class="rounded-lg border border-slate-300 px-6 py-2 text-sm font-semibold hover:bg-slate-100">
                    Change
                  </button>
                </div>
              </div>

              <div class="rounded-2xl border border-red-100 bg-red-50/70 p-6">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm text-red-700 font-semibold">Deactivate account</p>
                    <p class="text-xs text-red-600">This will permanently delete your account</p>
                  </div>
                  <button type="button" id="openDeactivateModal"
                    class="rounded-lg border border-red-300 px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-100">
                    Deactivate
                  </button>
                </div>
              </div>

            </section>

            <!-- Right -->
            <aside class="space-y-6">
              <form action="{{ route('organization_setting.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="updateProfile">

                <div class="flex items-center gap-4">
                  <div class="rounded-full w-20 h-20 overflow-hidden flex items-center justify-center bg-slate-200">
                    @if(Auth::user()->profile_picture)
                      <img src="{{ asset(Auth::user()->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                    @else
                      <img src="../../asserts/dashboard/circle-dot.png" alt="Default Icon" class="w-16">
                    @endif
                  </div>
                  <div>
                    <p class="font-semibold mb-1">Profile photo</p>
                    <p class="text-gray-600 mb-3 text-sm max-w-xs">PNG/JPG under 10MB</p>

                    <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">
                    <button type="button"
                      onclick="document.getElementById('profile_picture').click()"
                      class="rounded-full border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100">
                      Upload new image
                    </button>
                  </div>
                </div>

                <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-xl">
                  Save Changes
                </button>
              </form>
            </aside>
          </div>
        </div>
      </div>
    </section>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-semibold mb-4">Change Password</h2>
        <form action="{{ route('organization_setting.store') }}" method="POST">
          @csrf
          <input type="hidden" name="form_type" value="password">

          <div class="relative mb-3">
            <input type="password" id="old_password" name="old_password" placeholder="Current Password" class="w-full border rounded-lg px-4 py-2" required>
            <span onclick="togglePassword('old_password', this)" class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>
          </div>

          <div class="relative mb-3">
            <input type="password" id="password" name="password" placeholder="New Password" class="w-full border rounded-lg px-4 py-2" required>
            <span onclick="togglePassword('password', this)" class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>
            <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">
              Password must be at least 8 characters, include uppercase, lowercase, number, and symbol.
            </p>
          </div>

          <div class="relative mb-4">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="w-full border rounded-lg px-4 py-2" required>
            <span onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-2 cursor-pointer text-gray-600">👁️</span>
          </div>

          <div class="flex justify-end space-x-2">
            <button type="button" id="closeChangePasswordModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-lg">Update</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Deactivate Modal -->
    <div id="deactivateModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
      <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
        <h2 class="text-lg font-semibold mb-4 text-red-600">Deactivate Account</h2>
        <p class="mb-4 text-sm">Are you sure you want to deactivate your account?</p>

        <form method="POST" action="{{ route('organization_setting.store') }}">
          @csrf
          <input type="hidden" name="form_type" value="deactivate">

          <div class="flex justify-end space-x-2">
            <button type="button" id="closeDeactivateModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Deactivate</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- Scripts (unchanged logic) -->
  <script>
    document.getElementById('openChangePasswordModal').addEventListener('click', () => {
      document.getElementById('changePasswordModal').classList.remove('hidden');
      document.getElementById('changePasswordModal').classList.add('flex');
    });
    document.getElementById('closeChangePasswordModal').addEventListener('click', () => {
      document.getElementById('changePasswordModal').classList.add('hidden');
    });

    document.getElementById('openDeactivateModal').addEventListener('click', () => {
      document.getElementById('deactivateModal').classList.remove('hidden');
      document.getElementById('deactivateModal').classList.add('flex');
    });
    document.getElementById('closeDeactivateModal').addEventListener('click', () => {
      document.getElementById('deactivateModal').classList.add('hidden');
    });

    function togglePassword(fieldId, icon) {
      const field = document.getElementById(fieldId);
      if (field.type === "password") {
        field.type = "text";
        icon.textContent = "🙈";
      } else {
        field.type = "password";
        icon.textContent = "👁️";
      }
    }

    const passwordField = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;

    passwordField?.addEventListener('input', function () {
      if (strongPasswordRegex.test(passwordField.value)) {
        passwordField.classList.remove('border-red-500');
        passwordField.classList.add('border-green-500');
        passwordError.classList.add('hidden');
      } else {
        passwordField.classList.remove('border-green-500');
        passwordField.classList.add('border-red-500');
        passwordError.classList.remove('hidden');
      }
    });

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

    const fileInput = document.getElementById('profile_picture');
    fileInput?.addEventListener('change', function(event) {
      const reader = new FileReader();
      reader.onload = function(e) {
        const img = document.querySelector('section img');
        if (img) img.src = e.target.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    });
  </script>
</body>
</html>
