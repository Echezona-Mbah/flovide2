<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flovide API Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
        }
        code, pre { 
            font-family: 'JetBrains Mono', monospace; 
            font-size: 0.875rem; 
        }
        .sidebar-active { 
            color: #2563eb; 
            border-left: 2px solid #2563eb; 
            background-color: #eff6ff; 
        }
        .hide-scrollbar::-webkit-scrollbar { 
            display: none; 
        }
        .hide-scrollbar { 
            -ms-overflow-style: none; 
            scrollbar-width: none; 
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    <!-- Navigation -->
    <nav class="fixed top-0 z-50 w-full border-b border-slate-200 bg-white/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-8">
                <a href="#" class="flex items-center gap-2">
                    <!-- <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">N</div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">Flovide<span class="text-indigo-600">.</span></span> -->
                    <img src="{{asset('../asserts/homepage/Logo.png')}}" alt="Logo" />
                </a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                    <a href="#overview" class="hover:text-indigo-600 transition">Overview</a>
                    <a href="#api-reference" class="hover:text-indigo-600 transition">API Reference</a>
                    <a href="#webhooks" class="hover:text-indigo-600 transition">Webhooks</a>
                    <a href="#sdks" class="hover:text-indigo-600 transition">SDKs</a>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <button class="hidden sm:block text-sm font-medium text-slate-600 hover:text-indigo-600">Support</button>
                <a href="#" class="rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                    Get API Keys
                </a>
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm">
        <div class="absolute right-0 top-0 h-full w-64 bg-white p-6 shadow-xl">
            <div class="flex flex-col gap-6">
                <a href="#overview" class="text-lg font-semibold text-slate-900">Overview</a>
                <a href="#api-reference" class="text-lg font-semibold text-slate-900">API Reference</a>
                <a href="#webhooks" class="text-lg font-semibold text-slate-900">Webhooks</a>
                <a href="#sdks" class="text-lg font-semibold text-slate-900">SDKs</a>
                <hr>
                <button class="w-full rounded-lg bg-indigo-600 py-3 text-white">Get API Keys</button>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <header class="relative pt-32 pb-16 bg-white overflow-hidden" id="overview">
        <div class="absolute inset-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px] [mask-image:radial-gradient(ellipse_50%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-4">v2.4.0 Release</span>
            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl mb-6">Build with Our API</h1>
            <p class="mx-auto max-w-2xl text-lg text-slate-600 mb-10 leading-relaxed">
                Seamlessly integrate global payments, card issuing, and identity verification into your platform with our developer-first REST APIs.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#quick-start" class="w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white rounded-lg font-semibold hover:shadow-lg transition">Explore Docs</a>
                <a href="#" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition">Platform Status: <span class="text-emerald-500">Operational</span></a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        
        <!-- Quick Start -->
        <section id="quick-start" class="mb-24">
            <h2 class="text-2xl font-bold mb-8 flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 text-sm italic">1</span>
                Quick Start Guide
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="p-6 bg-white rounded-xl shadow-sm border border-slate-100">
                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="font-bold mb-2">Create Account</h3>
                    <p class="text-slate-500 text-sm">Sign up for a sandbox account to test integrations safely.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-slate-100">
                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    </div>
                    <h3 class="font-bold mb-2">Generate Keys</h3>
                    <p class="text-slate-500 text-sm">Retrieve your Secret and Public keys from the developer settings.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-slate-100">
                    <div class="h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-bold mb-2">Make Request</h3>
                    <p class="text-slate-500 text-sm">Initiate your first payment via simple POST request to our servers.</p>
                </div>
            </div>

            <div class="bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
                <div class="flex items-center justify-between px-4 py-2 bg-slate-800/50 border-b border-slate-700">
                    <div class="flex gap-1.5">
                        <div class="h-3 w-3 rounded-full bg-red-500"></div>
                        <div class="h-3 w-3 rounded-full bg-amber-500"></div>
                        <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <span class="text-xs text-slate-400 font-medium uppercase tracking-widest">cURL Request</span>
                    <button class="text-slate-400 hover:text-white transition" onclick="copyCode(this, 'curl-code')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>
                <div class="p-6 overflow-x-auto hide-scrollbar">
                    <pre id="curl-code" class="text-indigo-300 text-sm leading-6">
<span class="text-emerald-400">curl</span> https://api.flovide.com/v1/payments \
-H <span class="text-amber-300">"Authorization: Bearer sk_test_51Mz..."</span> \
-d <span class="text-emerald-400">amount</span>=2000 \
-d <span class="text-emerald-400">currency</span>="usd" \
-d <span class="text-emerald-400">customer_email</span>="dev@example.com"
                    </pre>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <div class="lg:flex lg:gap-16">
            
            <!-- Left Sidebar Sticky -->
            <aside class="hidden lg:block w-64 flex-shrink-0 sticky top-24 h-[calc(100vh-6rem)] overflow-y-auto pr-4 border-r border-slate-200">
                <nav class="flex flex-col gap-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 px-3">Basics</p>
                    <a href="#auth" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">Authentication</a>
                    <a href="#security" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">Security</a>
                    
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-8 mb-2 px-3">Core API</p>
                    <a href="#payments" class="sidebar-active px-3 py-2 text-sm font-medium text-slate-600 rounded-md transition-all">Payments</a>
                    <a href="#transfers" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">Transfers</a>
                    <a href="#webhooks" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">Webhooks</a>
                    
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-8 mb-2 px-3">Reference</p>
                    <a href="#errors" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">Error Codes</a>
                    <a href="#sdks" class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 hover:bg-white rounded-md transition-all">SDKs & Libraries</a>
                </nav>
            </aside>

            <!-- Right Content Area -->
            <div class="flex-1 min-w-0">
                
                <!-- Authentication Section -->
                <section id="auth" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Authentication</h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        The Flovide API uses API keys to authenticate requests. You can view and manage your API keys in the Flovide Dashboard. Your API keys carry many privileges, so be sure to keep them secure! Do not share your secret API keys in publicly accessible areas such as GitHub, client-side code, and so forth.
                    </p>
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-amber-700">
                                    All API requests must be made over <span class="font-bold">HTTPS</span>. Calls made over plain HTTP will fail. API requests without authentication will also fail.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- API Reference: Payments -->
                <section id="payments" class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Payments API</h2>
                        <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                    </div>

                    <!-- Endpoint Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-12 shadow-sm">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-xs">POST</span>
                                <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/v1/charges</code>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Create a Payment Charge</h3>
                            <p class="text-slate-600 mb-6">To initiate a payment from a customer, you create a Charge object. You can charge a saved card, a mobile money wallet, or generate a bank transfer reference.</p>
                            
                            <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Parameters</h4>
                            <div class="space-y-4 border-t border-slate-100 pt-4 mb-8">
                                <div class="flex justify-between">
                                    <div>
                                        <span class="font-mono text-sm font-semibold text-slate-900">amount</span>
                                        <span class="text-xs text-slate-400 ml-2 italic">Integer</span>
                                    </div>
                                    <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="font-mono text-sm font-semibold text-slate-900">currency</span>
                                        <span class="text-xs text-slate-400 ml-2 italic">String</span>
                                    </div>
                                    <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                                </div>
                            </div>

                            <!-- Code Toggle/Tabs -->
                            <div class="bg-slate-900 rounded-xl overflow-hidden">
                                <div class="flex border-b border-slate-800">
                                    <button class="px-4 py-3 text-xs font-bold text-white border-b-2 border-indigo-500 bg-slate-800/50">Response Object</button>
                                </div>
                                <div class="p-6 overflow-x-auto hide-scrollbar">
                                    <pre class="text-slate-300 text-sm">
{
    <span class="text-indigo-400">"id"</span>: <span class="text-emerald-400">"ch_3N1x2Y9zL5k0"</span>,
    <span class="text-indigo-400">"object"</span>: <span class="text-emerald-400">"charge"</span>,
    <span class="text-indigo-400">"amount"</span>: <span class="text-amber-300">2000</span>,
    <span class="text-indigo-400">"status"</span>: <span class="text-emerald-400">"succeeded"</span>,
    <span class="text-indigo-400">"paid"</span>: <span class="text-amber-300">true</span>,
    <span class="text-indigo-400">"customer"</span>: {
        <span class="text-indigo-400">"email"</span>: <span class="text-emerald-400">"customer@example.com"</span>
    }
}
                                    </pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Webhooks -->
                <section id="webhooks" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Webhooks</h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Flovide uses webhooks to notify your application when an event happens in your account. Webhooks are particularly useful for asynchronous events like when a customer's bank confirms a transfer or a recurring payment fails.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-white border border-slate-200 rounded-lg">
                            <h4 class="font-bold text-slate-900 mb-2">payment.success</h4>
                            <p class="text-sm text-slate-500">Sent when a charge is successfully captured.</p>
                        </div>
                        <div class="p-4 bg-white border border-slate-200 rounded-lg">
                            <h4 class="font-bold text-slate-900 mb-2">payout.failed</h4>
                            <p class="text-sm text-slate-500">Sent when a transfer to a bank account fails.</p>
                        </div>
                    </div>
                </section>

                <!-- Error Handling -->
                <section id="errors" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Error Handling</h2>
                    <div class="overflow-hidden bg-white border border-slate-200 rounded-xl shadow-sm">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Meaning</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <tr>
                                    <td class="px-6 py-4 font-mono text-sm text-indigo-600">400 - Bad Request</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">The request was unacceptable, often due to missing parameters.</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-mono text-sm text-indigo-600">401 - Unauthorized</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">No valid API key provided.</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-mono text-sm text-indigo-600">404 - Not Found</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">The requested resource doesn't exist.</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-mono text-sm text-indigo-600">500 - Server Error</td>
                                    <td class="px-6 py-4 text-sm text-slate-600">Something went wrong on our end.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- SDKs -->
                <section id="sdks" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-8">Official SDKs</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center">
                            <div class="text-2xl mb-2">🟨</div>
                            <span class="font-bold text-slate-900 group-hover:text-indigo-600">NodeJS</span>
                        </a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center">
                            <div class="text-2xl mb-2">🐘</div>
                            <span class="font-bold text-slate-900 group-hover:text-indigo-600">PHP</span>
                        </a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center">
                            <div class="text-2xl mb-2">🐍</div>
                            <span class="font-bold text-slate-900 group-hover:text-indigo-600">Python</span>
                        </a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center">
                            <div class="text-2xl mb-2">☕</div>
                            <span class="font-bold text-slate-900 group-hover:text-indigo-600">Java</span>
                        </a>
                    </div>
                </section>

            </div>
        </div>
    </main>



    <!-- footer -->
    @include('mainpage.footer')
    
    @include('mainpage.script')

    <!-- Scripts for UI interactivity -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) mobileMenu.classList.add('hidden');
        });

        // Simple Copy to Clipboard
        function copyCode(btn, elementId) {
            const text = document.getElementById(elementId).innerText;
            const tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            
            // Visual feedback
            const originalIcon = btn.innerHTML;
            btn.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
            setTimeout(() => {
                btn.innerHTML = originalIcon;
            }, 2000);
        }

        // Active Sidebar on Scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('aside nav a');
            
            let current = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 120) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('sidebar-active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('sidebar-active');
                }
            });
        });
    </script>
</body>
</html>