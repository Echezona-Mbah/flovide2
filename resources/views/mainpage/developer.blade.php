<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flovide API Documentation</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet" />
  <script>
    window.fcWidgetMessengerConfig = {
      open: false,
    }
  </script>
  <script src='//fw-cdn.com/16096204/7073720.js' chat='true'></script>
  <style>
    :root {
      --ink: #0b0d12;
      --paper: #f8f6f2;
      --accent: #ff7a00;
      --accent-2: #21c7a8;
      --slate: #5b6070;
      --glow: rgba(255, 122, 0, 0.18);
    }
    body {
      font-family: "Space Grotesk", sans-serif;
      background: var(--paper);
      color: var(--ink);
    }
    code, pre {
      font-family: "IBM Plex Mono", monospace;
      font-size: 0.875rem;
    }
    .sidebar-active {
      color: var(--ink);
      border-left: 3px solid var(--accent);
      background: rgba(255, 122, 0, 0.08);
    }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
  </style>
</head>
<body class="antialiased">


     <nav class="fixed top-0 z-50 w-full border-b border-black/10 bg-[#fff8]/backdrop-blur-md backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-10">
            <a href="{{ route('personal') }}" class="flex items-center gap-2">
            <img src="{{ asset('../asserts/homepage/Logo.png') }}" alt="Logo" />
            </a>
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-[#5b6070]">
            <a href="#overview" class="hover:text-black transition">Overview</a>
            <a href="#api-reference" class="hover:text-black transition">API Reference</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="hidden sm:block text-sm font-medium text-[#5b6070] hover:text-black">Support</button>
            <a href="#" class="rounded-full bg-black px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#1a1a1a] transition-all">
            Get API Keys
            </a>
            <button id="mobile-menu-btn" class="md:hidden p-2 text-[#5b6070]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
            </button>
        </div>
        </div>
    </nav>

    <div id="mobile-menu" class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm">
        <div class="absolute right-0 top-0 h-full w-72 bg-white p-6 shadow-xl">
        <div class="flex flex-col gap-6">
            <a href="#overview" class="text-lg font-semibold text-black">Overview</a>
            <a href="#api-reference" class="text-lg font-semibold text-black">API Reference</a>
            <a href="#balances" class="text-lg font-semibold text-black">Balances</a>
            <a href="#beneficiaries" class="text-lg font-semibold text-black">Beneficiaries</a>
            <a href="#transactions" class="text-lg font-semibold text-black">Transactions</a>
            <a href="#rates" class="text-lg font-semibold text-black">Rates</a>
            <a href="#reference-data" class="text-lg font-semibold text-black">Reference Data</a>
            <a href="#webhooks" class="text-lg font-semibold text-black">Webhooks</a>
            <a href="#sdks" class="text-lg font-semibold text-black">SDKs</a>
            <hr>
            <button class="w-full rounded-lg bg-black py-3 text-white">Get API Keys</button>
        </div>
        </div>
    </div>

    <header class="relative pt-32 pb-16" id="overview">
        <div class="absolute inset-0">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full blur-3xl" style="background: var(--glow)"></div>
        <div class="absolute top-40 right-0 h-64 w-64 rounded-full blur-3xl" style="background: rgba(33,199,168,0.16)"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,transparent_0_48%,rgba(0,0,0,0.02)_48%_52%,transparent_52%_100%)] [background-size:16px_16px]"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-3 py-1 text-xs font-bold uppercase tracking-widest text-black">
            v2.4.0 Release
            <span class="h-1.5 w-1.5 rounded-full bg-[var(--accent)]"></span>
        </span>
        <h1 class="mt-6 text-4xl font-bold tracking-tight sm:text-6xl">
            Build clean, reliable money flows
        </h1>
        <p class="mx-auto mt-5 max-w-2xl text-lg text-[#5b6070] leading-relaxed">
            Integrate balances, beneficiaries, account inquiry, keys, and developer-first payment infrastructure with our REST APIs built for speed and clarity.
        </p>
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#quick-start" class="w-full sm:w-auto px-8 py-3 bg-black text-white rounded-lg font-semibold hover:shadow-lg transition">Explore Docs</a>
            <a href="#" class="w-full sm:w-auto px-8 py-3 bg-white border border-black/10 text-black rounded-lg font-semibold hover:bg-black/5 transition">
            Platform Status: <span class="text-[var(--accent-2)]">Operational</span>
            </a>
        </div>
        </div>
    </header>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
    <section id="quick-start" class="mb-24">
      <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-black text-white text-sm">1</span>
        Quick Start Guide
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="p-6 bg-white rounded-2xl shadow-sm border border-black/10">
          <div class="h-10 w-10 bg-black text-white rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          </div>
          <h3 class="font-bold mb-2">Create Account</h3>
          <p class="text-[#5b6070] text-sm">Sign up for an account and access your developer settings.</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow-sm border border-black/10">
          <div class="h-10 w-10 bg-black text-white rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
          </div>
          <h3 class="font-bold mb-2">Generate Keys</h3>
          <p class="text-[#5b6070] text-sm">Retrieve your Public Key and Secret Key from the developer dashboard.</p>
        </div>

        <div class="p-6 bg-white rounded-2xl shadow-sm border border-black/10">
          <div class="h-10 w-10 bg-black text-white rounded-lg flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <h3 class="font-bold mb-2">Make Request</h3>
          <p class="text-[#5b6070] text-sm">Pass your keys in request headers and test balances and beneficiaries.</p>
        </div>
      </div>

      <div class="bg-[#0b0d12] rounded-2xl overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-4 py-2 bg-[#111520] border-b border-white/10">
          <div class="flex gap-1.5">
            <div class="h-3 w-3 rounded-full bg-red-500"></div>
            <div class="h-3 w-3 rounded-full bg-amber-400"></div>
            <div class="h-3 w-3 rounded-full bg-emerald-500"></div>
          </div>
          <span class="text-xs text-white/60 font-medium uppercase tracking-widest">cURL Request</span>
          <button class="copy-single text-white/50 hover:text-white transition" data-target="quick-start-code">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
          </button>
        </div>
        <div class="p-6 overflow-x-auto hide-scrollbar">
          <pre id="quick-start-code" class="text-[#f4a261] text-sm leading-6">curl -X GET "https://flovide.com/api/v1/balances" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"</pre>
        </div>
      </div>
    </section>

    <div class="lg:flex lg:gap-16" id="api-reference">
      <aside class="hidden lg:block w-64 flex-shrink-0 sticky top-24 h-[calc(100vh-6rem)] overflow-y-auto pr-4 border-r border-black/10">
        <nav class="flex flex-col gap-1">
          <p class="text-xs font-bold text-[#5b6070] uppercase tracking-widest mb-2 px-3">Basics</p>
          <a href="#auth" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Authentication</a>
          <a href="#security" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Security</a>

          <p class="text-xs font-bold text-[#5b6070] uppercase tracking-widest mt-8 mb-2 px-3">Core API</p>
          <a href="#balances" class="sidebar-active px-3 py-2 text-sm font-medium rounded-md transition-all">Balances</a>
          <a href="#beneficiaries" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Beneficiaries</a>
          <a href="#transactions" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Transactions</a>
          <a href="#rates" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Rates</a>
          <a href="#reference-data" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Reference Data</a>


          <a href="#webhooks" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Webhooks</a>

          <p class="text-xs font-bold text-[#5b6070] uppercase tracking-widest mt-8 mb-2 px-3">Reference</p>
          <a href="#errors" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">Error Codes</a>
          <a href="#sdks" class="px-3 py-2 text-sm font-medium text-[#5b6070] hover:text-black hover:bg-white rounded-md transition-all">SDKs & Libraries</a>
        </nav>
      </aside>

            <div class="flex-1 min-w-0">
                <section id="auth" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Authentication</h2>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        The Flovide API uses your Public Key and Secret Key to authenticate requests. Include both keys in the request headers.
                        Your public key identifies your account, while your secret key authorizes access.
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
                                    All API requests must be made over HTTPS. Never expose your secret key in frontend code or public repositories.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="security" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Security</h2>
                    <p class="text-slate-600 leading-relaxed">
                        Store your secret key only on the server side. Use your public key for identification and your secret key for authorization.
                    </p>
                </section>

                <section id="balances" class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Balances API</h2>
                        <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/balances</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">List all balances</h3>
                                <p class="text-slate-600 mb-6">Returns all balances that belong to the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                    <summary class="cursor-pointer font-semibold text-slate-800">Parameters</summary>
                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <span class="font-mono text-sm font-semibold text-slate-900">currency</span>
                                            <span class="text-xs text-slate-400 ml-2 italic">String</span>
                                        </div>
                                        <span class="text-xs text-slate-400 font-semibold uppercase">Optional</span>
                                    </div>
                                </details>

                                <div class="api-block" data-group="balances-get"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/balances/{id}</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Fetch single balance</h3>
                                <p class="text-slate-600 mb-6">Returns one balance record for the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                    <summary class="cursor-pointer font-semibold text-slate-800">Path Parameter</summary>
                                    <div class="mt-4 flex justify-between">
                                        <div>
                                            <span class="font-mono text-sm font-semibold text-slate-900">id</span>
                                            <span class="text-xs text-slate-400 ml-2 italic">Integer</span>
                                        </div>
                                        <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                                    </div>
                                </details>

                                <div class="api-block" data-group="balances-single"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-xs">POST</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/balances</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Create a balance</h3>
                                <p class="text-slate-600 mb-6">Creates a new balance for the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                    <summary class="cursor-pointer font-semibold text-slate-800">Body Parameters</summary>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">name</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">currency</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        {{-- <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">amount</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Optional</span></div> --}}
                                    </div>
                                </details>

                                <div class="api-block" data-group="balances-post"></div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="beneficiaries" class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Beneficiaries API</h2>
                        <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-md font-bold text-xs">POST</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/beneficiaries/account-inquiry</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Account Inquiry</h3>
                                <p class="text-slate-600 mb-6">Validate recipient details before creating a beneficiary.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                    <summary class="cursor-pointer font-semibold text-slate-800">Body Parameters</summary>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">currency</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank_code</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">account_number</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                    </div>
                                </details>

                                <div class="api-block" data-group="beneficiary-inquiry"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-xs">POST</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/beneficiaries</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Create beneficiary</h3>
                                <p class="text-slate-600 mb-6">Creates a beneficiary for the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                    <summary class="cursor-pointer font-semibold text-slate-800">Body Parameters</summary>
                                    <div class="mt-4 space-y-3">
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">type</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">firstNames</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Individual</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">lastName</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Individual</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">name</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Corporate</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">transfer_method</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.country</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.currency</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.accountHolder</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.accountNumber</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Required (Bank)</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.bankCode</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Required (Bank)</span></div>
                                        <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">bank.mobileNumber</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Required (Mobile)</span></div>
                                    </div>
                                </details>

                                <div class="api-block" data-group="beneficiary-create"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                                    <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/beneficiaries</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">List beneficiaries</h3>
                                <p class="text-slate-600 mb-6">Returns all beneficiaries for the matched account.</p>

                                <div class="api-block" data-group="beneficiary-list"></div>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                                <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/beneficiaries/{id}</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Fetch single beneficiary</h3>
                                <p class="text-slate-600 mb-6">Returns one beneficiary record for the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                <summary class="cursor-pointer font-semibold text-slate-800">Path Parameter</summary>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                    <span class="font-mono text-sm font-semibold text-slate-900">id</span>
                                    <span class="text-xs text-slate-400 ml-2 italic">Integer</span>
                                    </div>
                                    <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                                </div>
                                </details>

                                <div class="api-block" data-group="beneficiary-single"></div>
                            </div>
                        </div>


                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-md font-bold text-xs">DELETE</span>
                                <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/beneficiaries/{id}</code>
                                </div>
                                <h3 class="text-xl font-bold mb-3">Delete beneficiary</h3>
                                <p class="text-slate-600 mb-6">Deletes a beneficiary by ID for the matched account.</p>

                                <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                                <summary class="cursor-pointer font-semibold text-slate-800">Path Parameter</summary>
                                <div class="mt-4 flex justify-between">
                                    <div>
                                    <span class="font-mono text-sm font-semibold text-slate-900">id</span>
                                    <span class="text-xs text-slate-400 ml-2 italic">Integer</span>
                                    </div>
                                    <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                                </div>
                                </details>

                                <div class="api-block" data-group="beneficiary-delete"></div>
                            </div>
                        </div>

                    </div>
                </section>

                <section id="transactions" class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Transactions API</h2>
                        <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                            <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/transactions</code>
                            </div>
                            <h3 class="text-xl font-bold mb-3">List transactions</h3>
                            <p class="text-slate-600 mb-6">Returns all transactions for the matched account.</p>

                            <div class="api-block" data-group="transactions-list"></div>
                        </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                            <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/transactions/{id}</code>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Fetch single transaction</h3>
                            <p class="text-slate-600 mb-6">Returns one transaction record for the matched account.</p>

                            <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                            <summary class="cursor-pointer font-semibold text-slate-800">Path Parameter</summary>
                            <div class="mt-4 flex justify-between">
                                <div>
                                <span class="font-mono text-sm font-semibold text-slate-900">id</span>
                                <span class="text-xs text-slate-400 ml-2 italic">Integer</span>
                                </div>
                                <span class="text-xs text-red-500 font-semibold uppercase">Required</span>
                            </div>
                            </details>

                            <div class="api-block" data-group="transactions-single"></div>
                        </div>
                        </div>

                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-md font-bold text-xs">POST</span>
                            <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/transactions</code>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Initiate transaction</h3>
                            <p class="text-slate-600 mb-6">Creates a new transaction for the matched account.</p>

                            <div class="api-block" data-group="transactions-post"></div>
                        </div>
                        </div>
                    </div>
                </section>

                <section id="rates" class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Rates API</h2>
                        <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-6 md:p-8">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                            <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/rates</code>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Get exchange rate</h3>
                            <p class="text-slate-600 mb-6">Returns exchange rate and transfer fee for a currency pair.</p>

                            <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                            <summary class="cursor-pointer font-semibold text-slate-800">Query Params</summary>
                            <div class="mt-4 space-y-3">
                                <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">from_currency</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">to_currency</span></div><span class="text-xs text-red-500 font-semibold uppercase">Required</span></div>
                                <div class="flex justify-between"><div><span class="font-mono text-sm font-semibold">amount</span></div><span class="text-xs text-slate-400 font-semibold uppercase">Optional</span></div>
                            </div>
                            </details>

                            <div class="api-block" data-group="rates-get"></div>
                        </div>
                        </div>
                    </div>
                </section>


                <!-- Reference Data Section -->
                <section id="reference-data" class="mb-20">
                  <div class="flex items-center gap-4 mb-8">
                    <h2 class="text-3xl font-bold text-slate-900">Reference Data API</h2>
                    <span class="px-2 py-1 bg-slate-200 text-slate-600 rounded text-[10px] font-bold uppercase tracking-wider">Reference</span>
                  </div>

                  <div class="space-y-8">
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                      <div class="p-6 md:p-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                          <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                          <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/reference-data/currencies</code>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Currencies only</h3>
                        <p class="text-slate-600 mb-6">Returns all supported currencies.</p>
                        <div class="api-block" data-group="reference-data-currencies"></div>
                      </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                      <div class="p-6 md:p-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                          <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-md font-bold text-xs">GET</span>
                          <code class="text-sm font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded">/api/v1/reference-data/banks</code>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Banks only</h3>
                        <p class="text-slate-600 mb-6">Returns banks, optionally filtered by <code>country_iso</code> and <code>currency</code>.</p>

                        <details class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4" open>
                          <summary class="cursor-pointer font-semibold text-slate-800">Query Params</summary>
                          <div class="mt-4 space-y-3">
                            <div class="flex justify-between">
                              <div><span class="font-mono text-sm font-semibold">country_iso</span></div>
                              <span class="text-xs text-slate-400 font-semibold uppercase">Optional</span>
                            </div>
                            <div class="flex justify-between">
                              <div><span class="font-mono text-sm font-semibold">currency</span></div>
                              <span class="text-xs text-slate-400 font-semibold uppercase">Optional</span>
                            </div>
                          </div>
                        </details>

                        <div class="api-block" data-group="reference-data-banks"></div>
                      </div>
                    </div>
                  </div>
                </section>






                <section id="webhooks" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Webhooks</h2>
                    <p class="text-slate-600 mb-8 leading-relaxed">
                        Flovide uses webhooks to notify your application when an event happens in your account.
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

                <section id="errors" class="mb-20">
                  <h2 class="text-3xl font-bold text-slate-900 mb-6">Error Handling</h2>
                  <p class="text-slate-600 mb-6">
                    All errors return a consistent structure: <code>success</code>, <code>message</code>, <code>code</code>, and optional <code>errors</code>/<code>data</code>.
                  </p>

                  <div class="mb-6 bg-slate-900 rounded-xl overflow-hidden">
                    <div class="px-4 py-3 text-xs font-bold text-white border-b border-slate-800">Error Response Example</div>
                    <div class="p-6 overflow-x-auto hide-scrollbar">
                      <pre class="text-slate-300 text-sm leading-6">{
                  "success": false,
                  "message": "Validation failed",
                  "code": "VALIDATION_ERROR",
                  "errors": {
                    "amount": ["The amount field is required."]
                  },
                  "data": null
                }</pre>
                    </div>
                  </div>

                  <div class="overflow-x-auto bg-white border border-slate-200 rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                      <thead class="bg-slate-50">
                        <tr>
                          <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">HTTP Status</th>
                          <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Error Code</th>
                          <th class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Meaning</th>
                        </tr>
                      </thead>
                      <tbody class="divide-y divide-slate-200">
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">400</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">BAD_REQUEST</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Malformed or invalid request payload.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">401</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">UNAUTHORIZED</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Missing or invalid authentication credentials/API keys.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">403</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">FORBIDDEN</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Authenticated but not allowed to perform this action.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">404</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">NOT_FOUND</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Requested resource was not found.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">422</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">VALIDATION_ERROR</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Validation failed for one or more request fields.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">422</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">INSUFFICIENT_FUNDS</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Wallet balance is not enough to complete the transaction.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">500</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">SERVER_ERROR</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Unexpected internal server error occurred.</td>
                        </tr>
                        <tr>
                          <td class="px-6 py-4 font-mono text-sm text-indigo-600">500</td>
                          <td class="px-6 py-4 font-mono text-sm text-slate-800">TXN_FAILED</td>
                          <td class="px-6 py-4 text-sm text-slate-600">Transaction processing failed on the server/provider side.</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </section>


                <section id="sdks" class="mb-20">
                    <h2 class="text-3xl font-bold text-slate-900 mb-8">Official SDKs</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center"><div class="text-2xl mb-2">🟨</div><span class="font-bold text-slate-900 group-hover:text-indigo-600">NodeJS</span></a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center"><div class="text-2xl mb-2">🐘</div><span class="font-bold text-slate-900 group-hover:text-indigo-600">PHP</span></a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center"><div class="text-2xl mb-2">🐍</div><span class="font-bold text-slate-900 group-hover:text-indigo-600">Python</span></a>
                        <a href="#" class="group p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-300 hover:shadow-md transition-all text-center"><div class="text-2xl mb-2">☕</div><span class="font-bold text-slate-900 group-hover:text-indigo-600">Java</span></a>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @include('mainpage.footer')
    @include('mainpage.script')

    <script>
        const codeExamples = {
            'balances-get': {
                curl: `curl -X GET "https://flovide.com/api/v1/balances" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/balances', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/balances", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/balances",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
                java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/balances"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/balances");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `[
  {
    "id": "019d0a58-ac0f-70de-9b54-b280179a2598",
    "name": "Main Wallet",
    "currency": "GBP",
    "balance": 0,
    "created": "2026-03-19T12:11:21+00:00"
  }
]`
            },
            'balances-single': {
                curl: `curl -X GET "https://flovide.com/api/v1/balances/70" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/balances/70', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/balances/70", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/balances/70",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
                java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/balances/70"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/balances/70");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `{
  "id": "019d0a58-ac0f-70de-9b54-b280179a2598",
  "name": "Main Wallet",
  "currency": "GBP",
  "balance": 0,
  "created": "2026-03-19T12:11:21+00:00"
}`
            },
            'balances-post': {
                curl: `curl -X POST "https://flovide.com/api/v1/balances" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/balances', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  },
  body: JSON.stringify({
    name: 'Main Wallet',
    currency: 'GBP'
  })
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "bytes"
  "fmt"
  "io"
  "net/http"
)

func main() {
  jsonData := []byte(\`{
    "name":"Main Wallet",
    "currency":"GBP"
  }\`)

  req, _ := http.NewRequest("POST", "https://flovide.com/api/v1/balances", bytes.NewBuffer(jsonData))
  req.Header.Set("Accept", "application/json")
  req.Header.Set("Content-Type", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.post(
    "https://flovide.com/api/v1/balances",
    headers={
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
    json={
        "name": "Main Wallet",
        "currency": "GBP"
    },
)

print(response.json())`,
                java: `String json = """
{
  "name": "Main Wallet",
  "currency": "GBP",
  "amount": 0
}
""";

HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/balances"))
    .header("Accept", "application/json")
    .header("Content-Type", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .POST(HttpRequest.BodyPublishers.ofString(json))
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using System.Text;

using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var json = """
{
  "name": "Main Wallet",
  "currency": "GBP"
}
""";

var content = new StringContent(json, Encoding.UTF8, "application/json");
var response = await client.PostAsync("https://flovide.com/api/v1/balances", content);
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `{
  "id": "019d0a58-ac0f-70de-9b54-b280179a2598",
  "name": "Main Wallet",
  "currency": "GBP",
  "balance": 0,
  "created": "2026-03-19T12:11:21+00:00"
}`
            },

            'beneficiary-inquiry': {
                curl: `curl -X POST "https://flovide.com/api/v1/beneficiaries/account-inquiry" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/beneficiaries/account-inquiry', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  },
  body: JSON.stringify({
    currency: 'NGN',
    bank_code: '000007',
    account_number: '6322069407'
  })
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "bytes"
  "fmt"
  "io"
  "net/http"
)

func main() {
  jsonData := []byte(\`{
    "currency":"NGN",
    "bank_code":"000007",
    "account_number":"6322069407"
  }\`)

  req, _ := http.NewRequest("POST", "https://flovide.com/api/v1/beneficiaries/account-inquiry", bytes.NewBuffer(jsonData))
  req.Header.Set("Accept", "application/json")
  req.Header.Set("Content-Type", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.post(
    "https://flovide.com/api/v1/beneficiaries/account-inquiry",
    headers={
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
    json={
        "currency": "NGN",
        "bank_code": "000007",
        "account_number": "6322069407",
    },
)

print(response.json())`,
                java: `String json = """
{
  "currency": "NGN",
  "bank_code": "000007",
  "account_number": "6322069407"
}
""";

HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/beneficiaries/account-inquiry"))
    .header("Accept", "application/json")
    .header("Content-Type", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .POST(HttpRequest.BodyPublishers.ofString(json))
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using System.Text;

using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var json = """
{
  "currency": "NGN",
  "bank_code": "000007",
  "account_number": "6322069407"
}
""";

var content = new StringContent(json, Encoding.UTF8, "application/json");
var response = await client.PostAsync("https://flovide.com/api/v1/beneficiaries/account-inquiry", content);
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `{
  "success": true,
  "provider": "payaza",
  "data": {
    "success": true,
    "data": {
      "response_code": 200,
      "response_message": "Approved or completely successful",
      "response_content": {
        "account_number": "6322069407",
        "bank_code": "000007",
        "account_name": "ECHEZONA ERNEST MBAH",
        "account_status": "ACTIVE",
        "transaction_reference": 9
      }
    }
  }
}`
            },
            'beneficiary-create': {
                curl: `curl -X POST "https://flovide.com/api/v1/beneficiaries" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/beneficiaries', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  },
  body: JSON.stringify({
    type: 'individual',
    firstNames: 'ECHEZONA',
    lastName: 'MBAH',
    transfer_method: 'bank',
    bank: {
      country: 'NG',
      currency: 'NGN',
      accountHolder: 'ECHEZONA ERNEST MBAH',
      accountNumber: '6322069407',
      bankCode: '000007'
    }
  })
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "bytes"
  "fmt"
  "io"
  "net/http"
)

func main() {
  jsonData := []byte(\`{
    "type":"individual",
    "firstNames":"ECHEZONA",
    "lastName":"MBAH",
    "transfer_method":"bank",
    "bank":{
      "country":"NG",
      "currency":"NGN",
      "accountHolder":"ECHEZONA ERNEST MBAH",
      "accountNumber":"6322069407",
      "bankCode":"000007"
    }
  }\`)

  req, _ := http.NewRequest("POST", "https://flovide.com/api/v1/beneficiaries", bytes.NewBuffer(jsonData))
  req.Header.Set("Accept", "application/json")
  req.Header.Set("Content-Type", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.post(
    "https://flovide.com/api/v1/beneficiaries",
    headers={
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
    json={
        "type": "individual",
        "firstNames": "ECHEZONA",
        "lastName": "MBAH",
        "transfer_method": "bank",
        "bank": {
            "country": "NG",
            "currency": "NGN",
            "accountHolder": "ECHEZONA ERNEST MBAH",
            "accountNumber": "6322069407",
            "bankCode": "000007"
        }
    },
)

print(response.json())`,
                java: `String json = """
{
  "type": "individual",
  "firstNames": "ECHEZONA",
  "lastName": "MBAH",
  "transfer_method": "bank",
  "bank": {
    "country": "NG",
    "currency": "NGN",
    "accountHolder": "ECHEZONA ERNEST MBAH",
    "accountNumber": "6322069407",
    "bankCode": "000007"
  }
}
""";

HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/beneficiaries"))
    .header("Accept", "application/json")
    .header("Content-Type", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .POST(HttpRequest.BodyPublishers.ofString(json))
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using System.Text;

using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var json = """
{
  "type": "individual",
  "firstNames": "ECHEZONA",
  "lastName": "MBAH",
  "transfer_method": "bank",
  "bank": {
    "country": "NG",
    "currency": "NGN",
    "accountHolder": "ECHEZONA ERNEST MBAH",
    "accountNumber": "6322069407",
    "bankCode": "000007"
  }
}
""";

var content = new StringContent(json, Encoding.UTF8, "application/json");
var response = await client.PostAsync("https://flovide.com/api/v1/beneficiaries", content);
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `{
  "success": true,
  "message": "Beneficiary created successfully",
  "data": {
    "country": "NG",
    "currency": "NGN",
    "type": "individual",
    "first_names": "ECHEZONA",
    "last_name": "MBAH",
    "beneficiary_name": null,
    "account_number": "6322069407",
    "account_name": "ECHEZONA ERNEST MBAH",
    "phone": null,
    "bank": "FIDELITY BANK",
    "transfer_method": "bank",
    "bank_code": "000007",
    "id": 019d0a58-ac0f-70de-9b54-b280179a2598
  }
}`
            },

            'beneficiary-list': {
                curl: `curl -X GET "https://flovide.com/api/v1/beneficiaries" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
                javascript: `fetch('https://flovide.com/api/v1/beneficiaries', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
                go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/beneficiaries", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
                python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/beneficiaries",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
                java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/beneficiaries"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
                csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/beneficiaries");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
                response: `{
  "message": "Beneficiaries retrieved successfully",
  "success": true,
  "data": [
    {
      "id": 019d0a58-ac0f-70de-9b54-b280179a2598,
      "account_name": "ECHEZONA ERNEST MBAH",
      "bank": "FIDELITY BANK",
      "currency": "NGN"
    }
  ]
}`
            },

            'beneficiary-single': {
  curl: `curl -X GET "https://flovide.com/api/v1/beneficiaries/114" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
  javascript: `fetch('https://flovide.com/api/v1/beneficiaries/114', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/beneficiaries/114", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/beneficiaries/114",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/beneficiaries/114"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/beneficiaries/114");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
  response: `{
  "success": true,
  "data": {
    "id": 019d0a58-ac0f-70de-9b54-b280179a2598,
    "account_name": "ECHEZONA ERNEST MBAH",
    "bank": "FIDELITY BANK",
    "currency": "NGN"
  }
}`
},

            'beneficiary-delete': {
  curl: `curl -X DELETE "https://flovide.com/api/v1/beneficiaries/114" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
  javascript: `fetch('https://flovide.com/api/v1/beneficiaries/114', {
  method: 'DELETE',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("DELETE", "https://flovide.com/api/v1/beneficiaries/114", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
  python: `import requests

response = requests.delete(
    "https://flovide.com/api/v1/beneficiaries/114",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/beneficiaries/114"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .DELETE()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.DeleteAsync("https://flovide.com/api/v1/beneficiaries/114");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
  response: `{
  "success": true,
  "message": "Beneficiary deleted successfully"
}`
},

'transactions-list': {
  curl: `curl -X GET "https://flovide.com/api/v1/transactions" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
  javascript: `fetch('https://flovide.com/api/v1/transactions', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/transactions", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/transactions",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/transactions"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/transactions");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
  response: `{
  "success": true,
  "message": "Transactions retrieved successfully",
  "data": []
}`
},

'transactions-single': {
  curl: `curl -X GET "https://flovide.com/api/v1/transactions/1" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,
  javascript: `fetch('https://flovide.com/api/v1/transactions/1', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,
  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/transactions/1", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/transactions/1",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,
  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/transactions/1"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/transactions/1");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
  response: `{
  "success": true,
  "message": "Transaction retrieved successfully",
  "data": {
    "id": 1,
    "amount": "5000.00",
    "currency": "GBP",
    "status": "successful"
  }
}`
},

'transactions-post': {
  curl: `curl -X POST "https://flovide.com/api/v1/transactions" ^
  -H "Accept: application/json" ^
  -H "Content-Type: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY" ^
  -d "{\"amount\":1000,\"recipient_id\":\"97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234\",\"balance_id\":1,\"reference\":\"Test payment\",\"transfer_fee\":0,\"total_amount\":1000,\"exchange_rate\":\"NGN to NGN\",\"recipient_amount\":1000,\"account_number\":\"1234567890\",\"account_name\":\"John Doe\",\"bank\":\"bank\",\"bank_code\":\"058\"}"`,
  javascript: `fetch('https://flovide.com/api/v1/transactions', {
  method: 'POST',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  },
  body: JSON.stringify({
    amount: 1000,
    recipient_id: '97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234',
    balance_id: 1,
    reference: 'Test payment',
    transfer_fee: 0,
    total_amount: 1000,
    exchange_rate: 'NGN to NGN',
    recipient_amount: 1000,
    account_number: '1234567890',
    account_name: 'John Doe',
    bank: 'bank',
    bank_code: '058'
  })
})
  .then(res => res.json())
  .then(data => console.log(data));`,
  go: `package main

import (
  "bytes"
  "fmt"
  "io"
  "net/http"
)

func main() {
  jsonData := []byte(\`{
    "amount":1000,
    "recipient_id":"97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234",
    "balance_id":1,
    "reference":"Test payment",
    "transfer_fee":0,
    "total_amount":1000,
    "exchange_rate":"NGN to NGN",
    "recipient_amount":1000,
    "account_number":"1234567890",
    "account_name":"John Doe",
    "bank":"bank",
    "bank_code":"058"
  }\`)

  req, _ := http.NewRequest("POST", "https://flovide.com/api/v1/transactions", bytes.NewBuffer(jsonData))
  req.Header.Set("Accept", "application/json")
  req.Header.Set("Content-Type", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,
  python: `import requests

response = requests.post(
    "https://flovide.com/api/v1/transactions",
    headers={
        "Accept": "application/json",
        "Content-Type": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
    json={
        "amount": 1000,
        "recipient_id": "97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234",
        "balance_id": 1,
        "reference": "Test payment",
        "transfer_fee": 0,
        "total_amount": 1000,
        "exchange_rate": "NGN to NGN",
        "recipient_amount": 1000,
        "account_number": "1234567890",
        "account_name": "John Doe",
        "bank": "bank",
        "bank_code": "058"
    },
)

print(response.json())`,
  java: `String json = """
{
  "amount": 1000,
  "recipient_id": "97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234",
  "balance_id": 1,
  "reference": "Test payment",
  "transfer_fee": 0,
  "total_amount": 1000,
  "exchange_rate": "NGN to NGN",
  "recipient_amount": 1000,
  "account_number": "1234567890",
  "account_name": "John Doe",
  "bank": "bank",
  "bank_code": "058"
}
""";

HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/transactions"))
    .header("Accept", "application/json")
    .header("Content-Type", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .POST(HttpRequest.BodyPublishers.ofString(json))
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,
  csharp: `using System.Text;

using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var json = """
{
  "amount": 1000,
  "recipient_id": "97dfe8c1-3f6d-4c2f-9f13-0d7f1e9c1234",
  "balance_id": 1,
  "reference": "Test payment",
  "transfer_fee": 0,
  "total_amount": 1000,
  "exchange_rate": "NGN to NGN",
  "recipient_amount": 1000,
  "account_number": "1234567890",
  "account_name": "John Doe",
  "bank": "bank",
  "bank_code": "058"
}
""";

var content = new StringContent(json, Encoding.UTF8, "application/json");
var response = await client.PostAsync("https://flovide.com/api/v1/transactions", content);
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,
  response: `{
  "message": "Transaction initiated successfully"
}`
},


'rates-get': {
  curl: `curl -X GET "https://flovide.com/api/v1/rates?from_currency=GBP&to_currency=EUR&amount=100&to_amount=112" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,

  javascript: `fetch('https://flovide.com/api/v1/rates?from_currency=GBP&to_currency=EUR&amount=100&to_amount=112', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,

  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/rates?from_currency=GBP&to_currency=EUR&amount=100&to_amount=112", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,

  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/rates",
    params={
        "from_currency": "GBP",
        "to_currency": "EUR",
        "amount": 100,
        "to_amount": 112
    },
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,

  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/rates?from_currency=GBP&to_currency=EUR&amount=100&to_amount=112"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,

  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/rates?from_currency=GBP&to_currency=EUR&amount=100&to_amount=112");
var body = await response.Content.ReadAsStringAsync();
Console.WriteLine(body);`,

  response: `{
  "rate": {
    "from_currency": {
      "currency_code": "GBP",
      "amount": 1
    },
    "to_currency": {
      "currency_code": "EUR",
      "amount": "1.127"
    },
    "last_updated": "2026-05-05T10:30:00+00:00",
    "outside_market_hours": false
  },
  "sender": {
    "currency_code": "GBP",
    "amount": 100
  },
  "recipient": {
    "currency_code": "EUR",
    "amount": 112
  },
  "reversed": false
}`
},

// Keep these two only in codeExamples.
// Remove: 'reference-data-all': { ... },

'reference-data-currencies': {
  curl: `curl -X GET "https://flovide.com/api/v1/reference-data/currencies" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,

  javascript: `fetch('https://flovide.com/api/v1/reference-data/currencies', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,

  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/reference-data/currencies", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,

  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/reference-data/currencies",
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,

  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/reference-data/currencies"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,

  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/reference-data/currencies");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,

  response: `{
  "success": true,
  "message": "Currencies fetched successfully",
  "code": "CURRENCIES_FETCHED",
  "data": [
    {
      "code": "NGN",
      "country_code": "NG",
      "name": "Nigerian Naira"
    },
    {
      "code": "GBP",
      "country_code": "GB",
      "name": "British Pound"
    },
    {
      "code": "EGP",
      "country_code": "EG",
      "name": "Egyptian Pound"
    },
  ]
}`
},

'reference-data-banks': {
  curl: `curl -X GET "https://flovide.com/api/v1/reference-data/banks?country_iso=NG&currency=NGN" ^
  -H "Accept: application/json" ^
  -H "X-Public-Key: pk_live_xxxxxxxxxxxxxxxxx" ^
  -H "X-Secret-Key: REDACTED_STRIPE_KEY"`,

  javascript: `fetch('https://flovide.com/api/v1/reference-data/banks?country_iso=NG&currency=NGN', {
  method: 'GET',
  headers: {
    'Accept': 'application/json',
    'X-Public-Key': 'pk_live_xxxxxxxxxxxxxxxxx',
    'X-Secret-Key': 'REDACTED_STRIPE_KEY'
  }
})
  .then(res => res.json())
  .then(data => console.log(data));`,

  go: `package main

import (
  "fmt"
  "io"
  "net/http"
)

func main() {
  req, _ := http.NewRequest("GET", "https://flovide.com/api/v1/reference-data/banks?country_iso=NG&currency=NGN", nil)
  req.Header.Set("Accept", "application/json")
  req.Header.Set("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
  req.Header.Set("X-Secret-Key", "REDACTED_STRIPE_KEY")

  client := &http.Client{}
  resp, _ := client.Do(req)
  defer resp.Body.Close()

  body, _ := io.ReadAll(resp.Body)
  fmt.Println(string(body))
}`,

  python: `import requests

response = requests.get(
    "https://flovide.com/api/v1/reference-data/banks",
    params={
        "country_iso": "NG",
        "currency": "NGN",
    },
    headers={
        "Accept": "application/json",
        "X-Public-Key": "pk_live_xxxxxxxxxxxxxxxxx",
        "X-Secret-Key": "REDACTED_STRIPE_KEY",
    },
)

print(response.json())`,

  java: `HttpRequest request = HttpRequest.newBuilder()
    .uri(URI.create("https://flovide.com/api/v1/reference-data/banks?country_iso=NG&currency=NGN"))
    .header("Accept", "application/json")
    .header("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx")
    .header("X-Secret-Key", "REDACTED_STRIPE_KEY")
    .GET()
    .build();

HttpResponse<String> response = HttpClient.newHttpClient()
    .send(request, HttpResponse.BodyHandlers.ofString());

System.out.println(response.body());`,

  csharp: `using var client = new HttpClient();

client.DefaultRequestHeaders.Add("Accept", "application/json");
client.DefaultRequestHeaders.Add("X-Public-Key", "pk_live_xxxxxxxxxxxxxxxxx");
client.DefaultRequestHeaders.Add("X-Secret-Key", "REDACTED_STRIPE_KEY");

var response = await client.GetAsync("https://flovide.com/api/v1/reference-data/banks?country_iso=NG&currency=NGN");
var body = await response.Content.ReadAsStringAsync();

Console.WriteLine(body);`,

  response: `{
  "success": true,
  "message": "Banks fetched successfully",
  "code": "BANKS_FETCHED",
  "data": [
    {
      "name": "FIDELITY BANK",
      "country_iso": "NG",
      "currency": "NGN",
      "bank_code": "000007",
      "sort_code": null,
      "type": "bank"
    }
  ]
}`
},







        };

        function escapeHtml(str) {
            return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function buildApiBlock(group) {
            const labels = {
                curl: 'cURL',
                javascript: 'JavaScript',
                go: 'Go',
                python: 'Python',
                java: 'Java',
                csharp: 'C#'
            };

            const tabs = ['curl', 'javascript', 'go', 'python', 'java', 'csharp'];

            return `
                <div class="bg-slate-900 rounded-xl overflow-hidden">
                    <div class="flex flex-wrap border-b border-slate-800">
                        ${tabs.map((tab, index) => `
                            <button class="code-tab px-4 py-3 text-xs font-bold ${index === 0 ? 'text-white border-b-2 border-indigo-500 bg-slate-800/50' : 'text-slate-400 hover:text-white'}" data-group="${group}" data-target="${tab}">
                                ${labels[tab]}
                            </button>
                        `).join('')}
                    </div>
                    <div class="relative">
                        <button class="copy-visible-btn absolute right-4 top-4 z-10 text-slate-400 hover:text-white transition" data-group="${group}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        <div class="code-panels" data-group="${group}">
                            ${tabs.map((tab, index) => `
                                <div class="code-panel ${index === 0 ? '' : 'hidden'} p-6 overflow-x-auto hide-scrollbar" data-panel="${tab}">
                                    <pre class="text-slate-300 text-sm leading-6">${escapeHtml(codeExamples[group][tab])}</pre>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
                <div class="mt-6 bg-slate-900 rounded-xl overflow-hidden">
                    <div class="flex border-b border-slate-800">
                        <div class="px-4 py-3 text-xs font-bold text-white border-b-2 border-indigo-500 bg-slate-800/50">Response Object</div>
                    </div>
                    <div class="p-6 overflow-x-auto hide-scrollbar">
                        <pre class="text-slate-300 text-sm leading-6">${escapeHtml(codeExamples[group].response)}</pre>
                    </div>
                </div>
            `;
        }


        document.querySelectorAll('.api-block').forEach((el) => {
            el.innerHTML = buildApiBlock(el.dataset.group);
        });

        document.addEventListener('click', (e) => {
            const tab = e.target.closest('.code-tab');
            if (tab) {
                const group = tab.dataset.group;
                const target = tab.dataset.target;
                const container = tab.closest('.bg-slate-900');

                container.querySelectorAll('.code-tab').forEach(item => {
                    item.classList.remove('text-white', 'border-b-2', 'border-indigo-500', 'bg-slate-800/50');
                    item.classList.add('text-slate-400');
                });

                tab.classList.add('text-white', 'border-b-2', 'border-indigo-500', 'bg-slate-800/50');
                tab.classList.remove('text-slate-400');

                document.querySelectorAll(`.code-panels[data-group="${group}"] .code-panel`).forEach(panel => {
                    panel.classList.toggle('hidden', panel.dataset.panel !== target);
                });
            }

            const copyBtn = e.target.closest('.copy-visible-btn');
            if (copyBtn) {
                const group = copyBtn.dataset.group;
                const visible = document.querySelector(`.code-panels[data-group="${group}"] .code-panel:not(.hidden) pre`);
                navigator.clipboard.writeText(visible.innerText);
                const originalIcon = copyBtn.innerHTML;
                copyBtn.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                setTimeout(() => { copyBtn.innerHTML = originalIcon; }, 2000);
            }

            const copySingle = e.target.closest('.copy-single');
            if (copySingle) {
                const target = document.getElementById(copySingle.dataset.target);
                navigator.clipboard.writeText(target.innerText);
                const originalIcon = copySingle.innerHTML;
                copySingle.innerHTML = `<svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                setTimeout(() => { copySingle.innerHTML = originalIcon; }, 2000);
            }
        });

        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
        mobileMenu.addEventListener('click', (e) => {
            if (e.target === mobileMenu) mobileMenu.classList.add('hidden');
        });

        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('aside nav a');
            let current = "";

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 120) current = section.getAttribute('id');
            });

            navLinks.forEach(link => {
                link.classList.remove('sidebar-active');
                if (link.getAttribute('href').includes(current)) link.classList.add('sidebar-active');
            });
        });
    </script>
</body>
</html>
