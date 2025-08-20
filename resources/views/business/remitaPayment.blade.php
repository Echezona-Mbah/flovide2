@include('business.head')

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
  <!-- Mobile menu button -->
  <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
    <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
      <i class="fas fa-bars text-2xl"></i>
    </button>
    <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain" height="40"
      src="../../asserts/dashboard/admin-logo.svg" width="120" />
    <div></div>
  </header>

  <!-- Mobile menu button -->
  @include('business.header')

  <!-- Sidebar -->
  @include('business.sidebar')
  
  <!-- Overlay -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
  <!-- Main content -->
  <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
    <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
      <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
        Subscriptions
      </h1>            
      @include('business.header_notifical')
    </header>
    <section class=" relative w-full ">
      <section
        class="bg-white text-gray-700 min-h-screen md:w-[80vw]   md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2vw] overflow-x-hidden ">
        <header class="flex items-center gap-4 mb-8">
          <button aria-label="Back"
            class="flex items-center justify-center w-9 h-9 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">
            <i class="fas fa-chevron-left text-lg"></i>
          </button>
          <h1 class="text-xl font-semibold text-gray-900 select-none">Page details</h1>
          <div class="ml-auto flex items-center gap-4">
            <div class="flex items-center gap-2">
              <span class="w-3 h-3 rounded-full bg-green-700 block"></span>
              <span class="text-gray-700 text-sm select-none">Active</span>
            </div>
            <button type="button"
              class="md:flex hidden items-center gap-2 rounded-lg border border-blue-300 bg-blue-100 px-4 py-2 text-blue-700 text-sm font-semibold hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
              <i class="far fa-copy"></i>
              Copy Link
            </button>
          </div>
        </header>

        <main class="flex flex-col lg:flex-row gap-10">
          <!-- Left form section -->
          <section class="flex-1 max-w-full lg:max-w-[600px] flex flex-col gap-6">
            <p class="text-xs text-gray-500 select-none">
              Create, edit and track payment pages all in one place.
            </p>

            <div class="flex flex-col gap-1">
              <label class="text-xs text-gray-600 font-semibold select-none" for="cover-upload">Add a cover
                image</label>
              <div class="flex flex-wrap gap-2 text-xs text-gray-500 select-none">
                <span class="border border-gray-300 rounded-md px-2 py-[2px]">File type: .png and .jpg</span>
                <span class="border border-gray-300 rounded-md px-2 py-[2px]">Max file size: 5MB</span>
                <span class="border border-gray-300 rounded-md px-2 py-[2px]">Dimensions: 1600 px by 300 px</span>
              </div>
              <label for="cover-upload"
                class="mt-2 cursor-pointer border border-dashed border-gray-300 rounded-md h-16 flex items-center justify-center text-xs text-gray-600 select-none hover:border-gray-400">
                Drag and drop here or
                <button type="button" class="ml-2 bg-blue-700 text-white text-xs font-semibold px-3 py-1 rounded-md">
                  <i class="fas fa-image mr-1"></i> Browse
                </button>
                <input type="file" id="cover-upload" class="hidden" />
              </label>
            </div>

            <form class="flex flex-col gap-6">
              <div class="flex flex-col gap-1">
                <label for="title" class="text-xs text-gray-600 select-none">Title</label>
                <input id="title" type="text" placeholder="12345678"
                  class="text-xs text-gray-400 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500" />
              </div>

              <div class="flex flex-col gap-1">
                <label class="text-xs text-gray-600 select-none">Fixed Amount</label>


                <div class="grid grid-cols-1 md:grid-cols-3 items-center gap-3">

                  <input type="text" placeholder="30"
                    class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 md:w-32 focus:outline-none focus:ring-1 focus:ring-blue-500" />


                  <div class="flex items-center gap-2">
                    <label for="rrr-toggle"
                      class="text-xs text-gray-700 select-none cursor-pointer flex items-center gap-1">
                      <span>RRR (optional)</span>

                      <div class="relative w-10 h-5 rounded-full bg-gray-300 cursor-pointer" tabindex="0" role="switch"
                        aria-checked="false" id="toggle-container">
                        <input type="checkbox" id="rrr-toggle" class="absolute opacity-0 w-0 h-0" aria-hidden="true" />
                        <span id="toggle-thumb"
                          class="absolute left-0.5 top-0.5 w-4 h-4 bg-gray-500 rounded-full transition-transform"></span>
                      </div>
                    </label>
                  </div>



                  <input type="text" placeholder="123456789"
                    class="text-xs text-gray-400 rounded-md border border-gray-300 px-3 py-2 md:w-36 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>

              </div>

              <div class="flex flex-col gap-1">
                <label for="service-type" class="text-xs text-gray-600 select-none">Service Type</label>
                <select id="service-type"
                  class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                  <option selected disabled>Select a service type</option>
                </select>
              </div>

              <div class="flex flex-col gap-1">
                <label for="subaccount" class="text-xs text-gray-600 select-none">Subaccount</label>
                <div class="flex gap-3">
                  <select id="subaccount"
                    class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex-1 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <option selected disabled>Select a subaccount</option>
                  </select>
                  <input type="text" value="10"
                    class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 w-16 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                  <span
                    class="text-xs text-gray-700 bg-gray-200 rounded-md px-2 py-2 flex items-center select-none">%</span>
                </div>
              </div>

              <div class="flex flex-col gap-1">
                <label for="currency" class="text-xs text-gray-600 select-none">Currency</label>
                <select id="currency"
                  class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 flex items-center gap-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                  <option selected>
                    🇺🇸 US Dollar
                  </option>
                </select>
              </div>

              <div class="flex flex-col gap-1">
                <label for="visibility" class="text-xs text-gray-600 select-none">Visibility</label>
                <select id="visibility"
                  class="text-xs text-gray-700 rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-1 focus:ring-blue-500">
                  <option selected>Public</option>
                </select>
              </div>

              <div class="flex gap-4 mt-6">
                <button type="submit"
                  class="text-blue-700 bg-blue-100 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                  Update Page
                </button>
                <button type="button"
                  class="text-red-600 bg-red-100 px-5 py-2 rounded-lg text-sm font-semibold hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-400">
                  Delete Page
                </button>
              </div>
            </form>
          </section>

          <!-- Right payments section -->
          <section class="flex-1 max-w-full lg:max-w-[520px] flex flex-col gap-4">
            <div class="flex justify-between items-center">
              <h2 class="font-semibold text-gray-900 select-none">Payments</h2>
              <button type="button"
                class="flex items-center gap-2 rounded-lg border border-blue-300 bg-white px-4 py-2 text-blue-700 text-sm font-semibold hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <i class="fas fa-file-export"></i>
                Export CSV
              </button>
            </div>

            <div
              class="md:flex-1 md:overflow-y-auto  w-full overflow-x-auto space-y-3 pr-2 bg-[#F3F3F3] p-4 rounded-2xl"
              style="max-height: 600px;" tabindex="0">
              <!-- Subscriber item template repeated 10 times -->
              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">

                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>

                <span class="font-semibold text-gray-900  text-[10px] ">Marvin
                  McKinney</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                    aria-label="Inactive status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Annette
                  Black</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Devon Lane</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Jacob Jones</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Kristin
                  Watson</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Jerome Bell</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                    aria-label="Inactive status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Wade Warren</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block" style="background-color: #2f855a"
                    aria-label="Active status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Ralph
                  Edwards</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">

                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                    aria-label="Inactive status"></span>
                </span>

                <span class="font-semibold text-gray-900  text-[10px] ">Leslie
                  Alexander</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>

              <div class="flex items-center gap-4 bg-white justify-between rounded-xl px-6 py-3 shadow-sm">
                <span class="bg-[#F3F3F3] p-2 rounded-md">
                  <span class="w-3 h-3 rounded-full block " style="background-color: #c53030"
                    aria-label="Inactive status"></span>
                </span>
                <span class="font-semibold text-gray-900  text-[10px] ">Brooklyn
                  Simmons</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-2 py-1">user-email@gmail.com</span>
                <span
                  class="bg-gray-100 text-gray-600 text-[10px] font-semibold rounded-full px-3 py-1">+1000001111</span>
              </div>
            </div>
          </section>
        </main>
        </div>
        </div>
      </section>
  </main>
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

    // Close sidebar on window resize if desktop
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
      } else {
        sidebar.classList.add('-translate-x-full');
      }
    });


    const toggle = document.getElementById("rrr-toggle");
    const toggleThumb = document.getElementById("toggle-thumb");
    const toggleContainer = document.getElementById("toggle-container");

    toggle.addEventListener("change", () => {
      if (toggle.checked) {
        toggleThumb.classList.add("translate-x-5", "bg-green-700");
        toggleThumb.classList.remove("bg-gray-500");
        toggleContainer.setAttribute("aria-checked", "true");
      } else {
        toggleThumb.classList.remove("translate-x-5", "bg-green-700");
        toggleThumb.classList.add("bg-gray-500");
        toggleContainer.setAttribute("aria-checked", "false");
      }
    });
  </script>
</body>

</html>