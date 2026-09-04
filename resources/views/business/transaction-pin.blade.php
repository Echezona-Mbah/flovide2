@include('business.head')
<body class="bg-[#E9E9E9] text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('business.header')
    @include('business.sidebar')
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>

    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class="items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Transaction PIN
            </h1>
            @include('business.header_notifical')
        </header>

        <section class="mx-auto max-w-2xl">
            <div class="rounded-3xl bg-white shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)] border border-slate-100 overflow-hidden">

                <div class="px-6 md:px-10 py-8 bg-[#215F9C] text-white border-b border-sky-200/70">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em]">Security</p>
                        <h2 class="mt-2 text-2xl md:text-3xl font-black tracking-tight text-white">Transaction PIN</h2>
                        <p class="mt-2 text-sm text-white max-w-2xl">
                            Your 4-digit PIN authorizes every transfer you send. Keep it private — never share it with anyone, including support.
                        </p>
                    </div>
                </div>

                <div class="p-6 md:p-10 space-y-8">

                    <!-- Loading state while we check pin status -->
                    <div id="pinStatusLoading" class="text-sm text-slate-500">
                        Checking your PIN status…
                    </div>

                    <!-- ── SET PIN (first time) ─────────────────────────────── -->
                    <form id="setPinForm" class="space-y-6 hidden" autocomplete="off">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Set your PIN</h3>
                            <p class="text-sm text-slate-500">You haven't set a transaction PIN yet. Create one to start sending money.</p>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium mb-1 block">New PIN</label>
                                <input type="password" id="set_pin" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Confirm PIN</label>
                                <input type="password" id="set_pin_confirmation" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-1 block">Account Password</label>
                            <input type="password" id="set_account_password" placeholder="Confirm with your account password"
                                   class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400" />
                            <p class="text-xs text-slate-400 mt-1">We ask for your password once to confirm it's really you setting this up.</p>
                        </div>

                        <button type="submit" class="w-full bg-[#215F9C] text-white font-semibold py-3 rounded-xl hover:bg-slate-800 transition">
                            Set Transaction PIN
                        </button>
                    </form>

                    <!-- ── UPDATE PIN (already set) ─────────────────────────── -->
                    <form id="updatePinForm" class="space-y-6 hidden" autocomplete="off">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Update your PIN</h3>
                            <p class="text-sm text-slate-500">Enter your current PIN, then choose a new one.</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-1 block">Current PIN</label>
                            <input type="password" id="current_pin" maxlength="4" inputmode="numeric" pattern="\d*"
                                   placeholder="••••" class="w-full sm:w-1/2 border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium mb-1 block">New PIN</label>
                                <input type="password" id="new_pin" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Confirm New PIN</label>
                                <input type="password" id="new_pin_confirmation" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#215F9C] text-white font-semibold py-3 rounded-xl hover:bg-slate-800 transition">
                            Update Transaction PIN
                        </button>

                        <button type="button" id="forgotPinBtn" class="w-full text-sm text-[#215F9C] font-medium hover:underline">
                            Forgot your PIN?
                        </button>
                    </form>

                    <!-- ── RESET PIN (forgot flow) ───────────────────────────── -->
                    <form id="resetPinForm" class="space-y-6 hidden" autocomplete="off">
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Reset your PIN</h3>
                            <p class="text-sm text-slate-500">Confirm your account password to set a new PIN.</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium mb-1 block">Account Password</label>
                            <input type="password" id="reset_account_password" placeholder="Your account password"
                                   class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-blue-400" />
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium mb-1 block">New PIN</label>
                                <input type="password" id="reset_new_pin" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <div>
                                <label class="text-sm font-medium mb-1 block">Confirm New PIN</label>
                                <input type="password" id="reset_new_pin_confirmation" maxlength="4" inputmode="numeric" pattern="\d*"
                                       placeholder="••••" class="w-full border rounded-xl px-3 py-2 tracking-[0.5em] text-center text-lg focus:ring-2 focus:ring-blue-400" />
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#215F9C] text-white font-semibold py-3 rounded-xl hover:bg-slate-800 transition">
                            Reset Transaction PIN
                        </button>

                        <button type="button" id="backToUpdateBtn" class="w-full text-sm text-slate-500 font-medium hover:underline">
                            ← Back
                        </button>
                    </form>

                </div>
            </div>
        </section>
    </main>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusUrl = "{{ route('business.pin.status') }}";
        const setUrl    = "{{ route('business.pin.set') }}";
        const updateUrl = "{{ route('business.pin.update') }}";
        const resetUrl  = "{{ route('business.pin.reset') }}";
        const csrf      = "{{ csrf_token() }}";

        const loadingEl     = document.getElementById("pinStatusLoading");
        const setForm       = document.getElementById("setPinForm");
        const updateForm    = document.getElementById("updatePinForm");
        const resetForm     = document.getElementById("resetPinForm");
        const forgotBtn     = document.getElementById("forgotPinBtn");
        const backBtn       = document.getElementById("backToUpdateBtn");

        function toast(icon, title) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon,
                title,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
            });
        }

        // Only digits allowed in pin inputs
        document.querySelectorAll('input[maxlength="4"]').forEach((el) => {
            el.addEventListener('input', () => {
                el.value = el.value.replace(/\D/g, '').slice(0, 4);
            });
        });

        function showForm(which) {
            [setForm, updateForm, resetForm].forEach(f => f.classList.add('hidden'));
            which.classList.remove('hidden');
        }

        // ── Load current status ────────────────────────────────────────────
        fetch(statusUrl, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(res => {
                loadingEl.classList.add('hidden');
                if (res?.data?.has_pin) {
                    showForm(updateForm);
                } else {
                    showForm(setForm);
                }
            })
            .catch(() => {
                loadingEl.textContent = 'Could not check PIN status. Please refresh the page.';
            });

        // ── Set PIN ──────────────────────────────────────────────────────────
        setForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const pin = document.getElementById('set_pin').value;
            const pinConfirmation = document.getElementById('set_pin_confirmation').value;
            const accountPassword = document.getElementById('set_account_password').value;

            if (pin.length !== 4) return toast('error', 'PIN must be exactly 4 digits.');
            if (pin !== pinConfirmation) return toast('error', 'PINs do not match.');
            if (!accountPassword) return toast('error', 'Please enter your account password.');

            fetch(setUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    pin,
                    pin_confirmation: pinConfirmation,
                    account_password: accountPassword,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toast('success', res.message);
                    setForm.reset();
                    showForm(updateForm);
                } else {
                    toast('error', res.message);
                }
            })
            .catch(() => toast('error', 'Something went wrong. Please try again.'));
        });

        // ── Update PIN ───────────────────────────────────────────────────────
        updateForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const currentPin = document.getElementById('current_pin').value;
            const newPin = document.getElementById('new_pin').value;
            const newPinConfirmation = document.getElementById('new_pin_confirmation').value;

            if (currentPin.length !== 4) return toast('error', 'Enter your current 4-digit PIN.');
            if (newPin.length !== 4) return toast('error', 'New PIN must be exactly 4 digits.');
            if (newPin !== newPinConfirmation) return toast('error', 'New PINs do not match.');

            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    current_pin: currentPin,
                    new_pin: newPin,
                    new_pin_confirmation: newPinConfirmation,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toast('success', res.message);
                    updateForm.reset();
                } else {
                    toast('error', res.message);
                }
            })
            .catch(() => toast('error', 'Something went wrong. Please try again.'));
        });

        // ── Forgot / Reset PIN ───────────────────────────────────────────────
        forgotBtn.addEventListener('click', () => showForm(resetForm));
        backBtn.addEventListener('click', () => showForm(updateForm));

        resetForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const accountPassword = document.getElementById('reset_account_password').value;
            const newPin = document.getElementById('reset_new_pin').value;
            const newPinConfirmation = document.getElementById('reset_new_pin_confirmation').value;

            if (!accountPassword) return toast('error', 'Please enter your account password.');
            if (newPin.length !== 4) return toast('error', 'New PIN must be exactly 4 digits.');
            if (newPin !== newPinConfirmation) return toast('error', 'New PINs do not match.');

            fetch(resetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    account_password: accountPassword,
                    new_pin: newPin,
                    new_pin_confirmation: newPinConfirmation,
                }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    toast('success', res.message);
                    resetForm.reset();
                    showForm(updateForm);
                } else {
                    toast('error', res.message);
                }
            })
            .catch(() => toast('error', 'Something went wrong. Please try again.'));
        });
    });
    </script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('overlay');

        openBtn?.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        closeBtn?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        });

        overlay?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('-translate-x-full');
            }
        });
    </script>
</body>
</html>