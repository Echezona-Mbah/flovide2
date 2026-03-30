@include('business.head')
<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @include('business.header')
  @include('business.sidebar')

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-2 md:p-8 overflow-auto">
    <header class="hidden md:flex items-center justify-between mb-8 gap-4">
      <div>
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Organization</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Team Members</h1>
        <p class="mt-1 text-sm text-slate-500">Manage roles, access, and team access policies.</p>
      </div>
      @include('business.header_notifical')
    </header>

    <section class="w-full">
      <div class="rounded-[28px] bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">
        <!-- Top bar -->
        <div class="px-6 md:px-10 py-6 bg-gradient-to-r from-sky-200 via-sky-100 to-blue-50 border-b border-sky-200/70">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
              <p class="text-xs uppercase tracking-[0.3em] text-slate-600">Workspace</p>
              <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-slate-900">Team Members</h2>
              <p class="mt-2 text-sm text-slate-600 max-w-2xl">Invite, update roles, and keep your org secure.</p>
            </div>
            <button
              class="inline-flex items-center justify-center rounded-full border border-sky-300 bg-white/70 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-white"
              type="button">
              <i class="fas fa-plus mr-2"></i>
              New Member
            </button>
          </div>
        </div>

        <!-- Content -->
        <div class="p-6 md:p-10 space-y-6">
          <!-- Navigation -->
          <nav class="border-b border-slate-100">
            <ul class="flex flex-wrap gap-6 text-sm font-semibold text-slate-500">
              <li>
                <a href="{{ route('organization') }}" class="text-slate-900 border-b-2 border-slate-900 pb-3 block">
                  Team Members
                </a>
              </li>
              <li>
                <a href="{{ route('organization_setting') }}" class="hover:text-slate-900 block pb-3">
                  Settings
                </a>
              </li>
              <li>
                <a href="{{ route('organization_plan') }}" class="hover:text-slate-900 block pb-3">
                  Subscription Plan
                </a>
              </li>
            </ul>
          </nav>

          <!-- Search -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex-1 max-w-xs">
              <label class="sr-only" for="search">Search team</label>
              <div class="relative text-slate-400 focus-within:text-slate-700">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <i class="fas fa-search"></i>
                </span>
                <input
                  class="block w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-3 text-sm placeholder-slate-400 focus:border-sky-400 focus:ring-2 focus:ring-sky-200 focus:outline-none"
                  id="search" name="search" placeholder="Search team" type="search" />
              </div>
            </div>
            <div class="text-sm text-slate-500">
              {{ $members->count() ?? 0 }} members
            </div>
          </div>

          <!-- Table -->
          <div class="w-full overflow-x-auto">
            <table class="w-full text-sm text-left border-separate border-spacing-y-3">
              <thead class="text-slate-500">
                <tr>
                  <th class="px-4 py-3 font-semibold">Member</th>
                  <th class="px-4 py-3 font-semibold">Email</th>
                  <th class="px-4 py-3 font-semibold">Date Added</th>
                  <th class="px-4 py-3 font-semibold">Role</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-transparent">
                @forelse ($members as $member)
                  <tr class="bg-white rounded-xl shadow-[0_10px_30px_-22px_rgba(15,23,42,0.35)]">
                    <td class="px-4 py-4 flex items-center gap-3 rounded-l-xl">
                      @if(!empty($member->avatar))
                        <img src="{{ $member->avatar }}" alt="{{ $member->name }}" class="w-7 h-7 rounded-full object-cover">
                      @else
                        @php
                          $initial = strtoupper(substr($member->user->business_name ?? $member->user->name ?? '', 0, 1));
                        @endphp
                        <span class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-semibold">
                          {{ $initial }}
                        </span>
                      @endif
                      <span class="font-semibold text-slate-900">
                        {{ $member->user->business_name ?? 'Unknown' }}
                      </span>
                    </td>

                    <td class="px-4 py-4 text-slate-700">
                      {{ $member->email ?? 'No email' }}
                    </td>

                    <td class="px-4 py-4 text-slate-800 font-semibold">
                      {{ $member->created_at?->format('M j, Y') ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-4 rounded-r-xl">
                      @if($currentMemberRole === 'Owner')
                        <form action="{{ route('members.updateRole', $member->id) }}" method="POST">
                          @csrf
                          @method('PATCH')
                          <select name="role" class="rounded-xl border border-slate-200 py-2 px-3 text-slate-900 bg-white" onchange="this.form.submit()">
                            <option {{ $member->role === 'Owner' ? 'selected' : '' }}>Owner</option>
                            <option {{ $member->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option {{ $member->role === 'Accountant' ? 'selected' : '' }}>Accountant</option>
                            <option {{ $member->role === 'Author' ? 'selected' : '' }}>Author</option>
                          </select>
                        </form>
                      @else
                        <input type="text" value="{{ $member->role }}" class="rounded-xl border border-slate-200 py-2 px-3 text-slate-900 bg-slate-50" readonly>
                      @endif
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center py-8 text-slate-500">
                      No team members found.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <section
        class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] backdrop-blur-sm"
        id="modal" style="display: none">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md border border-slate-100">
          <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
            <p class="text-xl font-semibold">Add a New Member</p>
            <button type="button" id="closeModal" class="border h-7 w-7 rounded-lg flex items-center justify-center">
              <i class="fas fa-times text-md cursor-pointer text-[#828282]"></i>
            </button>
          </div>

          <form id="addMemberForm" method="POST" action="{{ route('team.store') }}" class="space-y-6">
            @csrf
            @if ($errors->any())
              <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: @json($errors->first()),
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                });
              </script>
            @endif

            @if (session('success'))
              <script>
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: @json(session('success')),
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                });
              </script>
            @endif

            <div>
              <label class="flex flex-col text-slate-600 text-sm gap-1">
                Email
                <input type="email" name="email" class="border border-slate-200 p-2.5 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-200"
                  placeholder="john@gmail.com" required />
              </label>
            </div>

            <div>
              <label class="flex flex-col text-slate-600 text-sm gap-1">
                Role
                <select name="role" class="rounded-xl border border-slate-200 py-2.5 px-3 text-slate-900" required>
                  <option>Owner</option>
                  <option>Admin</option>
                  <option>Accountant</option>
                  <option selected>Author</option>
                </select>
              </label>
            </div>

            <button type="submit" class="w-full bg-slate-900 text-white font-semibold py-2.5 px-4 rounded-xl">
              Add Member
            </button>
          </form>
        </div>
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

    window.addEventListener("resize", () => {
      if (window.innerWidth >= 768) {
        sidebar.classList.remove("-translate-x-full");
        overlay.classList.add("hidden");
        document.body.style.overflow = "";
      } else {
        sidebar.classList.add("-translate-x-full");
      }
    });

    document.addEventListener("DOMContentLoaded", function () {
      const modal = document.getElementById("modal");
      const createBtn = document.querySelectorAll("button");

      createBtn.forEach((btn) => {
        if (btn.textContent.trim() === "New Member") {
          btn.addEventListener("click", function () {
            modal.style.display = "flex";
          });
        }
      });

      const closeBtn = modal.querySelector(".fa-times");
      closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
      });

      modal.style.display = "none";
    });
  </script>
</body>
</html>
