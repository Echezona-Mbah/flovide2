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
  <section class="w-full flex flex-col md:flex-row min-h-screen">
    <!-- Left side: Table and filters -->
    <div class="w-full px-4 sm:px-6 lg:px-8 pt-8">
      <!-- Navigation -->
      <nav class="border-b border-gray-200 mb-6">
        <ul class="flex space-x-8 text-sm font-medium text-gray-600">
             <li>
            <a href="{{ route('organization') }}" aria-current="page" class="text-black border-b-2 border-black pb-3 block">
                Team Members
            </a>
            </li>
            <li>
            <a href="{{ route('organization_setting') }}" class="hover:text-gray-900 block">
                Settings
            </a>
            </li>
            <li>
            <a href="{{ route('organization_plan') }}" class="hover:text-gray-900 block">
                Subscription plan
            </a>
            </li>

        </ul>
      </nav>
      <!-- Search and New Member -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 sm:gap-0">
        <div class="flex-1 max-w-xs">
          <label class="sr-only" for="search"> Search team </label>
          <div class="relative text-gray-400 focus-within:text-gray-600">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
              <i class="fas fa-search"> </i>
            </span>
            <input
              class="block w-full rounded-lg border border-gray-300 py-2 pl-10 pr-3 text-sm placeholder-gray-400 focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
              id="search" name="search" placeholder="Search team" type="search" />
          </div>
        </div>
        <button
          class="inline-flex items-center justify-center rounded-full border border-blue-300 bg-blue-100 px-4 py-2 text-sm font-medium text-blue-800 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
          type="button">
          <i class="fas fa-plus mr-2"> </i>
          New Member
        </button>
      </div>
      <!-- Table -->
      <section class="w-full overflow-x-auto">
        <table
          class="w-full mt-6 text-sm text-left text-gray-700 border-separate border-spacing-y-3 max-w-[900px] md:max-w-[90vw]">
         <thead class="bg-white">
    <tr>
        <th class="whitespace-nowrap px-4 py-3 text-left font-semibold text-gray-500">Member</th>
        <th class="whitespace-nowrap px-4 py-3 text-left font-semibold text-gray-500">Email</th>
        <th class="whitespace-nowrap px-4 py-3 text-left font-semibold text-gray-500">Date Added</th>
        <th class="whitespace-nowrap px-4 py-3 text-left font-semibold text-gray-500">Role</th>
    </tr>
</thead>
<tbody class="divide-y divide-gray-100 w-full">
    @forelse ($members as $member)
        <tr>
            <td class="whitespace-nowrap px-4 py-5 flex items-center gap-3">
                @if(!empty($member->avatar))
                    <img src="{{ $member->avatar }}" alt="{{ $member->name }}" 
                         class="w-6 h-6 rounded-full object-cover">
                @else
                    <span class="w-6 h-6 rounded-full bg-gray-300 flex items-center justify-center">
                        {{ strtoupper(substr($member->user->business_name, 0, 1)) }}
                    </span>
                @endif
                <span class="font-semibold text-gray-900">
                    {{ $member->user->business_name ?? 'Unknown' }}
                </span>
            </td>

            <td class="whitespace-nowrap px-4 py-5 text-gray-900">
                {{ $member->email ?? 'No email' }}
            </td>

            <td class="whitespace-nowrap px-4 py-5 font-semibold text-gray-900">
                {{ $member->created_at?->format('M j, Y') ?? 'N/A' }}
            </td>

            <td class="whitespace-nowrap px-4 py-5">
              @if($currentMemberRole === 'Owner')
                  <form action="{{ route('members.updateRole', $member->id) }}" method="POST">
                      @csrf
                      @method('PATCH')
                      <select name="role" class="rounded-lg border border-gray-300 py-2 px-3 text-gray-900" onchange="this.form.submit()">
                          <option {{ $member->role === 'Owner' ? 'selected' : '' }}>Owner</option>
                          <option {{ $member->role === 'Admin' ? 'selected' : '' }}>Admin</option>
                          <option {{ $member->role === 'Accountant' ? 'selected' : '' }}>Accountant</option>
                          <option {{ $member->role === 'Author' ? 'selected' : '' }}>Author</option>
                      </select>
                  </form>
              @else
                  <input type="text" value="{{ $member->role }}" 
                        class="rounded-lg border border-gray-300 py-2 px-3 text-gray-900 bg-gray-100"
                        readonly>
              @endif
          </td>

        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center py-6 text-gray-500">
                No team members found.
            </td>
        </tr>
    @endforelse
</tbody>


        </table>
      </section>
    </div>
  </section>
</section>

        <!-- modal -->

      <section
          class="fixed inset-0 z-50 flex items-center justify-center bg-[#FFFFFF66] drop-shadow-sm backdrop-blur-sm bg-opacity-50"
          id="modal" style="display: none">
          <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <div class="flex justify-between w-full items-center border-b pb-4 mb-4">
              <p class="text-xl font-semibold">Add a New Member</p>
              <button type="button" id="closeModal" class="border h-6 w-6 rounded-md flex items-center justify-center">
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
                <label class="flex flex-col text-[#828282] text-md gap-1">
                  Email
                  <input type="email" name="email" class="border border-gray-300 p-2 rounded-lg text-black focus:outline-none focus:ring-1 focus:ring-blue-300 placeholder:text-[#D7D4D5]"
                    placeholder="john@gmail.com" required />
                </label>
              </div>

              <div>
                <label class="flex flex-col text-[#828282] text-md gap-1">
                  Role
                  <select name="role" class="rounded-lg border border-gray-300 py-2 px-3 text-gray-900" required>
                    <option>Owner</option>
                    <option>Admin</option>
                    <option>Accountant</option>
                    <option selected>Author</option>
                  </select>
                </label>
              </div>

              <button type="submit" class="w-full bg-[#215F9C] text-white font-semibold py-2 px-4 rounded-2xl">
                Add Member
              </button>
            </form>
          </div>
        </section>

        <!-- modal end-->
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

    document.addEventListener("DOMContentLoaded", function () {
      // Get modal element
      const modal = document.getElementById("modal");

      // Get "Create Virtual Card" button
      const createBtn = document.querySelectorAll("button");
      createBtn.forEach((btn) => {
        if (btn.textContent.trim() === "New Member") {
          btn.addEventListener("click", function () {
            modal.style.display = "flex"; // Show modal
          });
        }
      });

      // Get Close button (the one with the 'fa-times' icon)
      const closeBtn = modal.querySelector(".fa-times");
      closeBtn.addEventListener("click", function () {
        modal.style.display = "none"; // Hide modal
      });

      // Optional: Hide modal on page load
      modal.style.display = "none";
    });
  </script>
</body>

</html>