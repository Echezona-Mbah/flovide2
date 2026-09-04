<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="min-h-screen bg-gray-50 px-4 py-6">

        {{-- Header --}}
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}"
            class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-gray-600 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>

            <div>
                <h1 class="text-xl font-bold text-gray-900">Navigation</h1>
                <p class="text-sm text-gray-500">Quick access</p>
            </div>
        </div>


        {{-- Overview --}}
        <div class="mb-6">
            <h2 class="mb-3 px-1 text-xs font-semibold uppercase tracking-wider text-gray-500">
                Overview
            </h2>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                <a href="{{ route('admin.add_admin') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-circle-plus"></i>
                    </div>

                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900">Add Admin</span>
                        <p class="text-xs text-gray-500">Create a new administrator</p>
                    </div>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.all_admin') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900">Admins</span>
                        <p class="text-xs text-gray-500">Manage administrators</p>
                    </div>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.exchangerate') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </div>

                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900">Exchange Rate</span>
                        <p class="text-xs text-gray-500">Manage currency exchange rates</p>
                    </div>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.career.index') }}"
                class="flex items-center gap-4 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>

                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900">Careers</span>
                        <p class="text-xs text-gray-500">Manage career opportunities</p>
                    </div>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                 <a href="{{ route('admin.promocodes.index') }}"
                class="flex items-center gap-4 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>

                    <div class="flex-1">
                        <span class="text-sm font-semibold text-gray-900">Promo codes</span>
                        <p class="text-xs text-gray-500">Manage promocodes opportunities</p>
                    </div>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

            </div>
        </div>


        {{-- Favourites --}}
        <div class="mb-6">
            <h2 class="mb-3 px-1 text-xs font-semibold uppercase tracking-wider text-gray-500">
                Favourites
            </h2>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                <a href="{{ route('admin.personal-account') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Personal Account
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.business-account') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">
                        <i class="fa-solid fa-building"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Business Account
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 text-gray-700">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Dashboard
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.blog') }}"
                class="flex items-center gap-4 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Blog Post
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

            </div>
        </div>


        {{-- Workspace --}}
        <div class="mb-6">
            <h2 class="mb-3 px-1 text-xs font-semibold uppercase tracking-wider text-gray-500">
                Workspace
            </h2>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm">

                <a href="{{ route('admin.currency.limits') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                        <i class="fa-solid fa-coins"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Currency Limits
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="{{ route('admin.contact-requests') }}"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4 active:bg-gray-50">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Contact Requests
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>


                <a href="javascript:void(0);"
                class="flex items-center gap-4 border-b border-gray-100 px-4 py-4">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-50 text-yellow-600">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Goal Metrics
                    </span>

                    <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-bold text-yellow-700">
                        3
                    </span>
                </a>


                <a href="javascript:void(0);"
                class="flex items-center gap-4 px-4 py-4">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                        <i class="fa-solid fa-bullhorn"></i>
                    </div>

                    <span class="flex-1 text-sm font-semibold text-gray-900">
                        Campaigns
                    </span>

                    <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                </a>

            </div>
        </div>

    </div>
</body>
</html>