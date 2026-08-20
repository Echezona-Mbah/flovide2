@include('business.head')

<body class="bg-[#F0F2F7] text-[#1A1D2E] min-h-screen flex flex-col md:flex-row">
  @include('business.header')
  @include('business.sidebar')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <div id="overlay" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

  <main class="flex-1 p-3 md:p-8 overflow-auto">

    <header class="hidden md:flex items-center justify-between mb-7 gap-4">
      <div class="flex items-center gap-3">
        <a href="{{ route('balance.show', $balance->id) }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200 hover:bg-gray-50 transition shadow-sm">
          <i class="fas fa-arrow-left text-sm text-gray-600"></i>
        </a>
        <span class="text-sm text-gray-400 font-medium">{{ $balance->name }} /</span>
        <span class="text-sm font-semibold text-gray-700">Interac Auto Deposit</span>
      </div>
      @include('business.header_notifical')
    </header>

    <div class="mb-5 flex items-center gap-3 md:hidden">
      <a href="{{ route('balance.show', $balance->id) }}" class="w-9 h-9 flex items-center justify-center rounded-full bg-white border border-gray-200">
        <i class="fas fa-arrow-left text-gray-600 text-sm"></i>
      </a>
      <h1 class="text-lg font-bold">Interac Auto Deposit</h1>
    </div>

    {{-- Wide container instead of max-w-lg --}}
    <div class="max-w-6xl mx-auto space-y-5">

      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex items-center justify-between mb-6 gap-3 flex-wrap">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center">
              <i class="fas fa-bolt text-red-500"></i>
            </div>
            <div>
              <p class="font-bold text-gray-800">Auto Deposit Emails</p>
              <p class="text-xs text-gray-400">{{ $balance->currency }} · {{ $balance->name }}</p>
            </div>
          </div>
          <button type="button" onclick="openEmailModal()"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs font-semibold px-4 py-2.5 rounded-full hover:opacity-90 transition shadow-md shadow-red-100">
            <i class="fas fa-plus text-[11px]"></i> Add Email
          </button>
        </div>

        <p class="text-xs text-gray-400 mb-5 flex items-start gap-1.5">
          <i class="fas fa-info-circle mt-0.5 shrink-0"></i>
            <span>These are the emails registered with Flovide for Interac Auto Deposit on this wallet. Auto Deposit emails must end with <strong>@flovide.com</strong>, for example <code>payment@flovide.com</code>.</span>
        </p>

        {{-- Table --}}
        <div class="overflow-x-auto rounded-2xl border border-gray-100">
          <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date Added</th>
                {{-- <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th> --}}
              </tr>
            </thead>
            <tbody id="emailList" class="divide-y divide-gray-100 bg-white">
              @forelse($autoDeposits as $ad)
                <tr data-id="{{ $ad->id }}" class="hover:bg-gray-50 transition">
                  <td class="px-5 py-4">
                    <div class="flex items-center gap-2 min-w-0">
                      <i class="fas fa-envelope text-red-400 text-xs shrink-0"></i>
                      <span class="font-semibold text-gray-900 truncate email-cell">{{ $ad->email }}</span>
                    </div>
                  </td>
                  <td class="px-5 py-4">
                    <span class="status-cell text-[11px] bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">{{ ucfirst($ad->status) }}</span>
                  </td>
                  <td class="px-5 py-4 text-gray-500 date-cell whitespace-nowrap">
                    {{ $ad->added_at?->format('M j, Y · g:i A') ?? $ad->created_at->format('M j, Y · g:i A') }}
                  </td>
                  {{-- <td class="px-5 py-4 text-right">
                    <button type="button" onclick="removeEmail({{ $ad->id }}, this)"
                      class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 hover:text-red-500 text-gray-400 inline-flex items-center justify-center transition"
                      title="Remove">
                      <i class="fas fa-trash text-xs"></i>
                    </button>
                  </td> --}}
                </tr>
              @empty
                <tr id="emptyRow">
                  <td colspan="4" class="px-5 py-10 text-center">
                    <div class="flex flex-col items-center gap-2 text-gray-400">
                      <i class="fas fa-exclamation-circle text-xl text-gray-300"></i>
                      <span class="text-sm">No Auto Deposit emails set up yet</span>
                    </div>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </main>

  {{-- Modal --}}
  <div id="emailModal" class="fixed inset-0 z-[70] bg-black/50 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden">
      <div class="px-6 pt-6 pb-5 border-b border-gray-100 flex items-center justify-between">
        <div>
          <h2 class="font-bold text-gray-800">New Auto Deposit Email</h2>
          <p class="text-xs text-gray-400">Enter an Auto Deposit email ending with @flovide.com</p>
      </div>
        <button onclick="closeEmailModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
          <i class="fas fa-times text-sm"></i>
        </button>
      </div>

      <div class="p-6">
        <div id="modalEmailWrap" class="flex items-center gap-3 border-2 border-gray-200 rounded-xl px-4 py-3.5 focus-within:border-red-400 focus-within:ring-4 focus-within:ring-red-50 transition bg-gray-50">
          <i class="fas fa-at text-gray-400 text-sm shrink-0"></i>
          <input type="email" id="modalEmailInput" placeholder="payment@flovide.com"
            class="flex-1 bg-transparent text-sm text-gray-900 outline-none placeholder-gray-400" />
        </div>
        <p id="modalEmailError" class="hidden text-xs text-red-500 mt-2"></p>

        <button type="button" onclick="submitEmail()" id="modalSaveBtn"
          class="w-full mt-5 bg-gradient-to-r from-red-500 to-rose-500 text-white font-bold py-3.5 rounded-2xl hover:opacity-90 transition flex items-center justify-center gap-2">
          <i class="fas fa-save text-sm" id="modalSaveIcon"></i>
          <span id="modalSaveLabel">Save Email</span>
        </button>
      </div>
    </div>
  </div>

  <script>
    function openEmailModal() {
      document.getElementById('modalEmailInput').value = '';
      document.getElementById('modalEmailError').classList.add('hidden');
      const m = document.getElementById('emailModal');
      m.classList.remove('hidden');
      m.classList.add('flex');
    }
    function closeEmailModal() {
      const m = document.getElementById('emailModal');
      m.classList.add('hidden');
      m.classList.remove('flex');
    }
    document.getElementById('emailModal').addEventListener('click', function (e) {
      if (e.target === this) closeEmailModal();
    });

    async function submitEmail() {
      const email = document.getElementById('modalEmailInput').value.trim();
      const errEl = document.getElementById('modalEmailError');
      const btn   = document.getElementById('modalSaveBtn');
      const icon  = document.getElementById('modalSaveIcon');
      const lbl   = document.getElementById('modalSaveLabel');

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        errEl.textContent = 'Please enter a valid email address.';
        errEl.classList.remove('hidden');
        return;
      }
      errEl.classList.add('hidden');

      btn.disabled = true;
      icon.className = 'fas fa-spinner fa-spin text-sm';
      lbl.textContent = 'Saving…';

      try {
        const res = await fetch('{{ route("balance.interac_autodeposit.save", $balance->id) }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
          body: JSON.stringify({ email }),
        });

        const data = await res.json();

        if (res.ok && data.success) {
          document.getElementById('emptyRow')?.remove();

          const row = document.createElement('tr');
          row.dataset.id = data.data.id;
          row.className = 'hover:bg-gray-50 transition';
          row.innerHTML = `
            <td class="px-5 py-4">
              <div class="flex items-center gap-2 min-w-0">
                <i class="fas fa-envelope text-red-400 text-xs shrink-0"></i>
                <span class="font-semibold text-gray-900 truncate email-cell">${data.data.email}</span>
              </div>
            </td>
            <td class="px-5 py-4">
              <span class="status-cell text-[11px] bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">${data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1)}</span>
            </td>
            <td class="px-5 py-4 text-gray-500 date-cell whitespace-nowrap">Just now</td>
            <td class="px-5 py-4 text-right">
              <button type="button" onclick="removeEmail(${data.data.id}, this)"
                class="w-8 h-8 rounded-full bg-gray-50 hover:bg-red-50 hover:text-red-500 text-gray-400 inline-flex items-center justify-center transition"
                title="Remove">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </td>`;
          document.getElementById('emailList').prepend(row);

          closeEmailModal();
          Swal.fire({ icon: 'success', title: 'Added!', text: 'Interac Auto Deposit email added.', confirmButtonColor: '#e11d48', timer: 1600, showConfirmButton: false });
        } else {
          errEl.textContent = data.message ?? 'Something went wrong. Please try again.';
          errEl.classList.remove('hidden');
        }
      } catch (e) {
        errEl.textContent = 'Network error. Please try again.';
        errEl.classList.remove('hidden');
      } finally {
        btn.disabled = false;
        icon.className = 'fas fa-save text-sm';
        lbl.textContent = 'Save Email';
      }
    }

    async function removeEmail(emailId, btn) {
      const result = await Swal.fire({
        icon: 'warning',
        title: 'Remove this email?',
        text: 'This Auto Deposit email will no longer be linked to this wallet.',
        showCancelButton: true,
        confirmButtonText: 'Remove',
        confirmButtonColor: '#e11d48',
      });

      if (!result.isConfirmed) return;

      try {
        const res = await fetch(`{{ url('/business/balance/' . $balance->id . '/interac-autodeposit') }}/${emailId}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
          },
        });

        const data = await res.json();

        if (res.ok && data.success) {
          const row = btn.closest('tr');
          row.remove();

          const list = document.getElementById('emailList');
          if (!list.children.length) {
            list.innerHTML = `
              <tr id="emptyRow">
                <td colspan="4" class="px-5 py-10 text-center">
                  <div class="flex flex-col items-center gap-2 text-gray-400">
                    <i class="fas fa-exclamation-circle text-xl text-gray-300"></i>
                    <span class="text-sm">No Auto Deposit emails set up yet</span>
                  </div>
                </td>
              </tr>`;
          }
        } else {
          Swal.fire({ icon: 'error', title: 'Failed', text: data.message ?? 'Could not remove email.', confirmButtonColor: '#e11d48' });
        }
      } catch (e) {
        Swal.fire({ icon: 'error', title: 'Network Error', text: 'Could not reach the server.', confirmButtonColor: '#e11d48' });
      }
    }
  </script>

</body>
</html>