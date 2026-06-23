@include('business.head')

<body class="min-h-screen bg-[linear-gradient(180deg,#f7f4ee_0%,#eef2f7_100%)] text-[#1E1E1E] flex flex-col md:flex-row">
        @include('business.header')

    @include('business.sidebar')

    <div id="overlay" class="fixed inset-0 bg-slate-950/40 z-20 hidden md:hidden"></div>

    @php
        $activeMode = old('mode', session('mode', $mode ?? 'live'));
        $live = session('webhook_settings.live', $settings['live'] ?? []);
        $test = session('webhook_settings.test', $settings['test'] ?? []);
    @endphp

    <main class="flex-1 overflow-auto p-2 md:p-8">

        <header class="bg-blue-100 p-4 flex items-center justify-between md:hidden rounded-2xl mb-4">
            <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>

            <a href="{{ url('/organization_setting') }}" class="flex items-center gap-3">
                <img src="{{ auth()->user()->profile_picture ?? '../../asserts/dashboard/default-user.png' }}"
                    alt="{{ auth()->user()->business_name }}"
                    class="w-10 h-10 rounded-full object-cover border-2 border-gray-300" />
                <span class="text-sm font-medium text-[#1E1E1E]">
                    Hi, {{ auth()->user()->business_name }}
                </span>
            </a>

            <div class="flex items-center gap-2">
                <button id="notifyBtn_Mobile" aria-label="Notifications"
                        class="relative w-10 h-10 flex items-center justify-center bg-white rounded-full">
                    <i class="fas fa-bell text-[#4B4B4B] text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-[#00B37E]"></span>
                </button>

                <form method="POST" action="{{ route('logout') }}" class="flex items-center m-0 p-0">
                    @csrf
                    <button type="submit" aria-label="Logout"
                            class="w-10 h-10 flex items-center justify-center bg-red-500 rounded-full hover:bg-red-600 transition">
                        <i class="fas fa-sign-out-alt text-white text-lg"></i>
                    </button>
                </form>
            </div>
        </header>

        <header class="hidden md:flex items-start justify-between gap-4 mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-[#9a7b4f]">Developer Settings</p>
                <h1 class="mt-2 text-3xl font-black tracking-tight text-[#162033]">Webhooks</h1>
                <p class="mt-2 max-w-2xl text-sm text-slate-500">
                    Manage separate webhook credentials and endpoints for live and test traffic.
                </p>
            </div>
            @include('business.header_notifical')
        </header>

        <section class="w-full">
            <div class="grid gap-6 lg:grid-cols-[1fr_1.1fr]">
                <section class="overflow-hidden rounded-[28px] bg-[#162033] text-white shadow-[0_30px_80px_-35px_rgba(15,23,42,0.5)]">
                    <div class="p-6 md:p-8">
                        <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-[#e2c494]">
                            Security Overview
                        </span>

                        <h2 class="mt-4 text-2xl md:text-3xl font-black tracking-tight">Live and test environments</h2>
                        <p class="mt-3 max-w-md text-sm leading-6 text-slate-300">
                            Use test mode while integrating, then switch to live mode when you are ready for production traffic.
                        </p>

                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Live</p>
                                <p class="mt-3 text-lg font-semibold">Production</p>
                                <p class="mt-1 text-sm text-slate-300">Real events and real credentials.</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Test</p>
                                <p class="mt-3 text-lg font-semibold">Sandbox</p>
                                <p class="mt-1 text-sm text-slate-300">Safe environment for development and QA.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-[28px] border border-white/70 bg-white/90 p-5 md:p-7 shadow-[0_30px_70px_-40px_rgba(15,23,42,0.35)]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#9a7b4f]">Configuration</p>
                            <h2 class="mt-2 text-2xl font-black tracking-tight text-[#162033]">Keys and endpoints</h2>
                        </div>
                        <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            Secure
                        </span>
                    </div>

                    @if (session('success'))
                        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('business.webhooks.update') }}" method="POST" class="mt-6 space-y-5">
                        @csrf

                        <div class="inline-flex rounded-full bg-slate-100 p-1">
                            <button type="button" class="mode-tab rounded-full px-4 py-2 text-sm font-semibold {{ $activeMode === 'live' ? 'bg-[#162033] text-white' : 'text-slate-600' }}" data-mode="live">
                                Live
                            </button>
                            <button type="button" class="mode-tab rounded-full px-4 py-2 text-sm font-semibold {{ $activeMode === 'test' ? 'bg-[#162033] text-white' : 'text-slate-600' }}" data-mode="test">
                                Test
                            </button>
                        </div>

                        <input type="hidden" name="mode" id="modeInput" value="{{ $activeMode }}">

                        <div class="mode-panel {{ $activeMode === 'live' ? '' : 'hidden' }}" data-panel="live">
                            <div class="space-y-5">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Live secret key</label>
                                    <div class="relative">
                                        <input type="password" id="liveSecretKey" value="{{ old('live_secret_key', $live['secret_key'] ?? '') }}" readonly class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 font-mono text-sm">
                                        <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" data-target="liveSecretKey">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="generate-secret mt-3 inline-flex items-center gap-2 rounded-full bg-[#162033] px-4 py-2 text-sm font-semibold text-white" data-mode="live">
                                        <i class="fas fa-rotate"></i>
                                        Generate live secret
                                    </button>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Live public key</label>
                                    <div class="relative">
                                        <input type="text" id="livePublicKey" readonly value="{{ old('live_public_key', $live['public_key'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 font-mono text-sm">
                                        <button type="button" class="copy-key absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" data-target="livePublicKey">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Live IP whitelist</label>
                                    <textarea name="live_ip_whitelist" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('live_ip_whitelist', $live['ip_whitelist'] ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Live callback URL</label>
                                    <input type="text" name="live_callback_url" value="{{ old('live_callback_url', $live['callback_url'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Live webhook URL</label>
                                    <input type="text" name="live_webhook_url" value="{{ old('live_webhook_url', $live['webhook_url'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="mode-panel {{ $activeMode === 'test' ? '' : 'hidden' }}" data-panel="test">
                            <div class="space-y-5">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4">
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Test secret key</label>
                                    <div class="relative">
                                        <input type="password" id="testSecretKey" value="{{ old('test_secret_key', $test['secret_key'] ?? '') }}" readonly class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 font-mono text-sm">
                                        <button type="button" class="toggle-password absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" data-target="testSecretKey">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <button type="button" class="generate-secret mt-3 inline-flex items-center gap-2 rounded-full bg-[#162033] px-4 py-2 text-sm font-semibold text-white" data-mode="test">
                                        <i class="fas fa-rotate"></i>
                                        Generate test secret
                                    </button>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Test public key</label>
                                    <div class="relative">
                                        <input type="text" id="testPublicKey" readonly value="{{ old('test_public_key', $test['public_key'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-12 font-mono text-sm">
                                        <button type="button" class="copy-key absolute right-3 top-1/2 -translate-y-1/2 text-slate-400" data-target="testPublicKey">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Test IP whitelist</label>
                                    <textarea name="test_ip_whitelist" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">{{ old('test_ip_whitelist', $test['ip_whitelist'] ?? '') }}</textarea>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Test callback URL</label>
                                    <input type="text" name="test_callback_url" value="{{ old('test_callback_url', $test['callback_url'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">Test webhook URL</label>
                                    <input type="text" name="test_webhook_url" value="{{ old('test_webhook_url', $test['webhook_url'] ?? '') }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-4">
                            <button class="inline-flex items-center gap-2 rounded-full bg-[#c89d63] px-6 py-3 text-sm font-semibold text-[#162033] transition hover:bg-[#b98946]">
                                <i class="fas fa-floppy-disk"></i>
                                Save changes
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const notifyBtn = document.getElementById('notifyBtn_Mobile');
            const closeNotificationBtn = document.getElementById('closeNotificationBtn_mobile');
            const notificationBox = document.getElementById('notificationBox_mobile');

            notifyBtn?.addEventListener('click', () => {
                notificationBox?.classList.remove('hidden');
                notificationBox?.classList.add('opacity-100', 'translate-y-0');
            });

            closeNotificationBtn?.addEventListener('click', () => {
                notificationBox?.classList.add('hidden');
            });
        });

        async function loadNotifications() {
            try {
                const response = await fetch("/notifications");
                const result = await response.json();
                const list = document.getElementById("notificationList_mobile");
                if (!list || !result?.data?.data) return;

                list.innerHTML = "";

                result.data.data.forEach(n => {
                    const item = `
                        <div class="border border-gray-200 rounded-xl p-4">
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

        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar?.classList.remove('-translate-x-full');
            overlay?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar?.classList.add('-translate-x-full');
            overlay?.classList.add('hidden');
            document.body.style.overflow = '';
        }

        openBtn?.addEventListener('click', openSidebar);
        closeBtn?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                sidebar?.classList.remove('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar?.classList.add('-translate-x-full');
            }
        });

        const modeInput = document.getElementById('modeInput');
        const tabs = document.querySelectorAll('.mode-tab');
        const panels = document.querySelectorAll('.mode-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const mode = tab.dataset.mode;
                modeInput.value = mode;

                tabs.forEach(t => {
                    t.classList.remove('bg-[#162033]', 'text-white');
                    t.classList.add('text-slate-600');
                });

                tab.classList.add('bg-[#162033]', 'text-white');
                tab.classList.remove('text-slate-600');

                panels.forEach(panel => {
                    panel.classList.toggle('hidden', panel.dataset.panel !== mode);
                });
            });
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const icon = button.querySelector('i');
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });

        document.querySelectorAll('.copy-key').forEach(button => {
            button.addEventListener('click', async () => {
                const input = document.getElementById(button.dataset.target);
                await navigator.clipboard.writeText(input.value);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Key copied',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            });
        });

        document.querySelectorAll('.generate-secret').forEach(button => {
            button.addEventListener('click', async () => {
                const mode = button.dataset.mode;

                const response = await fetch("{{ route('business.webhooks.regenerate-secret') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ mode })
                });

                const data = await response.json();

                if (mode === 'live') {
                    document.getElementById('liveSecretKey').value = data.secret_key;
                    document.getElementById('livePublicKey').value = data.public_key;
                } else {
                    document.getElementById('testSecretKey').value = data.secret_key;
                    document.getElementById('testPublicKey').value = data.public_key;
                }

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: `${mode.charAt(0).toUpperCase() + mode.slice(1)} secret generated`,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            });
        });
    </script>
</body>
</html>
