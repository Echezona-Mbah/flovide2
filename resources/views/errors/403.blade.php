<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#f7f4ef] text-[#111827] flex items-center justify-center px-4">

    <main class="w-full max-w-2xl">
        <div class="relative overflow-hidden rounded-3xl bg-white shadow-xl border border-black/10 p-8 md:p-12">
            <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-orange-100"></div>
            <div class="absolute -left-16 -bottom-16 h-40 w-40 rounded-full bg-emerald-100"></div>

            <div class="relative">
                <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-black text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c.828 0 1.5.672 1.5 1.5S12.828 14 12 14s-1.5-.672-1.5-1.5S11.172 11 12 11z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 10V8a5 5 0 00-10 0v2m-1 0h12a1 1 0 011 1v8a1 1 0 01-1 1H6a1 1 0 01-1-1v-8a1 1 0 011-1z" />
                    </svg>
                </div>

                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-600">
                    403 Access Restricted
                </p>

                <h1 class="mt-3 text-3xl md:text-5xl font-extrabold tracking-tight">
                    You do not have access to this area.
                </h1>

                <p class="mt-4 text-base md:text-lg text-gray-600 leading-relaxed">
                    Your admin role does not include permission for this page. If you believe this is a mistake, contact a Super Admin or Security Admin to update your access.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold text-white hover:bg-gray-800 transition">
                        Back to Dashboard
                    </a>

                    <button onclick="history.back()"
                            class="inline-flex items-center justify-center rounded-xl border border-black/10 bg-white px-5 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition">
                        Go Back
                    </button>
                </div>

                <div class="mt-8 rounded-2xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-900">
                    Signed in as:
                    <span class="font-semibold">
                        {{ auth('admin')->user()->name ?? auth('admin')->user()->email ?? 'Admin user' }}
                    </span>
                </div>
            </div>
        </div>
    </main>

</body>
</html>