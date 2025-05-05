<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>
        Payout Accounts
    </title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: "Outfit", sans-serif;
        }
    </style>
</head>

<body class="bg-[#E9E9E9]  text-[#1E1E1E] min-h-screen flex flex-col md:flex-row">
    <!-- Mobile menu button -->
    <header class="bg-[#E9E9E9] p-4 flex items-center justify-between md:hidden">
        <button aria-label="Open sidebar" id="openSidebarBtn" class="text-[#1E1E1E] focus:outline-none">
            <i class="fas fa-bars text-2xl"></i>
        </button>
        <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
            height="40" src="../asserts/dashboard/admin-logo.svg" width="120" />
        <div></div>
    </header>
    <!-- Sidebar -->
    <aside aria-label="Sidebar" id="sidebar"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-[#E9E9E9] flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:flex-shrink-0">
        <div class="flex items-center justify-between py-8 px-6 flex-shrink-0">
            <img alt="Flovide logo black text with circular orbit design" class="w-[120px] h-[40px] object-contain"
                height="40" src="../asserts/dashboard/admin-logo.svg" width="120" />
            <button aria-label="Close sidebar" id="closeSidebarBtn" class="text-[#1E1E1E] focus:outline-none md:hidden">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto px-6 pb-8 space-y-3 text-sm font-normal text-[#4B4B4B]">
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-tachometer-alt text-base">
                </i>
                Dashboard
            </a>
            <a aria-current="page"
                class="flex items-center gap-3 py-2 px-3 rounded-full bg-white font-semibold text-[#1E1E1E]" href="{{ route("business.payouts") }}">
                <i class="fas fa-wallet text-base">
                </i>
                Payout accounts
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="{{ route("business.subaccount") }}">
                <i class="fas fa-layer-group text-base">
                </i>
                Subaccounts
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-history text-base">
                </i>
                Transaction History
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-user-friends text-base">
                </i>
                Beneficiaries
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-users text-base">
                </i>
                Customers
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice text-base">
                </i>
                Invoices
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-sync-alt text-base">
                </i>
                Subscriptions
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-file-invoice-dollar text-base">
                </i>
                Bills payment
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-exchange-alt text-base">
                </i>
                Remita
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-undo text-base">
                </i>
                Refunds
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-ban text-base">
                </i>
                Chargebacks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-credit-card text-base">
                </i>
                Virtual Cards
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-balance-scale text-base">
                </i>
                Compliance
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-code-branch text-base">
                </i>
                Webhooks
            </a>
            <a class="flex items-center gap-3 py-2 px-3 rounded-md hover:bg-white" href="#">
                <i class="fas fa-building text-base">
                </i>
                Your organization
            </a>
        </nav>
    </aside>
    <!-- Overlay -->
    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-30 z-20 hidden md:hidden"></div>
    <!-- Main content -->
    <main class="flex-1 p-2 md:p-8 overflow-auto ml-0 md:ml-0">
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Payout accounts
            </h1>
            <div class="flex items-center gap-4 flex-wrap">
                <button aria-label="Notifications"
                    class="relative bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-[#4B4B4B] text-lg">
                    </i>
                    <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-[#00B37E]">
                    </span>
                </button>
                <button aria-label="Select organization Nexus Global"
                    class="bg-white rounded-full flex items-center gap-2 py-2 px-4 text-sm font-normal text-[#1E1E1E] whitespace-nowrap">
                    <i class="fas fa-bullseye text-[#1E1E1E]">
                    </i>
                    Nexus Global
                    <i class="fas fa-chevron-right text-[#1E1E1E]">
                    </i>
                </button>
                <button aria-label="Logout" class="bg-white w-10 h-10 rounded-full flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-[#1E1E1E] text-lg">
                    </i>
                </button>
            </div>
        </header>
        <section class=" relative ">
            <section
                class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
                <!-- Left form -->
                <section class="flex flex-col lg:flex-row gap-8 bg-white rounded-tl-3xl md:p-6 p-2 ">
                    <section class="flex-1 bg-white rounded-xl md:p-6 p-2 max-w-full lg:max-w-lg ">
                        <h2 class="text-xl font-extrabold mb-1">
                            Add a Payout Account
                        </h2>
                        <p class="text-[#6B6B6B] mb-6 text-sm font-normal">
                            You can make withdrawals directly into any payout account of your
                            choice.
                        </p>
                        <div class="flex flex-col gap-5 text-sm font-normal text-[#6B6B6B]">
                            @csrf
                            <div class="flex flex-col gap-1">
                                <label class="font-normal text-[#6B6B6B]" for="bank"> Bank </label>
                                <span class="text-red-500 errorbank"></span>
                                <select class="border border-[#C4C4C4] rounded-md py-2 px-3 text-[#6B6B6B] placeholder-[#6B6B6B] focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]"
                                    id="bank" name="bank">
                                    <option value="" disabled="" selected=""> Select your bank </option>
                                    <option value="GTBank"> GTBank </option>
                                    <option value="Zenith"> Zenith </option>
                                    <option value="UBA"> UBA </option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="font-normal text-[#6B6B6B]" for="country">
                                    In what country is your bank located?
                                </label>
                                <span class="text-red-500 errorcountry"></span>
                                <select
                                    class="border border-[#C4C4C4] rounded-md py-2 px-3 text-[#6B6B6B] placeholder-[#6B6B6B] focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]"
                                    id="country" name="country">
                                    <option value="" disabled="" selected=""> Select country </option>
                                    <option value="Nigeria"> Nigeria </option>
                                    <option value="Ghana"> Ghana </option>
                                    <option value="Kenya"> Kenya </option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="font-normal text-[#6B6B6B]" for="account-number">
                                    Bank account number
                                </label>
                                <span class="text-red-500 errornumber"></span>
                                <input
                                    class="border border-[#C4C4C4] rounded-md py-2 px-3 text-[#C4C4C4] placeholder-[#C4C4C4] focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]"
                                    id="account-number" name="account-number" placeholder="12345678" type="text" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="font-normal text-[#6B6B6B]" for="account-name">
                                    Bank account name
                                </label>
                                <span class="text-red-500 errorname"></span>
                                <input
                                    class="border border-[#C4C4C4] rounded-md py-2 px-3 text-[#C4C4C4] placeholder-[#C4C4C4] focus:outline-none focus:ring-2 focus:ring-[#A9D3F7]"
                                    id="account-name" name="account-name" placeholder="John Doe" type="text" />
                            </div>
                            <div class="flex flex-col gap-1">
                                <div id="responseMessage"></div>
                            </div>
                            <button id="bankAccountForm" class="self-start bg-[#A9D3F7] text-[#1E4F8B] font-semibold text-sm rounded-full py-2.5 px-6 mt-2"
                                type="submit"> Add Account </button>
                        </div>
                    </section>


                    <!-- Right payout accounts list -->
                    <section class="flex-1 max-w-full lg:max-w-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-base text-[#1E1E1E]">
                                Payout Accounts
                            </h3>
                            <button aria-label="Delete All Payout Accounts"
                                class="flex items-center gap-1 text-[#D92D20] text-sm font-semibold rounded-md px-3 py-1 border border-[#D92D20] whitespace-nowrap">
                                <i class="fas fa-trash-alt">
                                </i>
                                Delete All
                            </button>
                        </div>
                        <div aria-label="List of payout accounts"
                            class="bg-[#F7F7F7] rounded-xl p-2 md:p-5 flex flex-col gap-3 max-w-full lg:max-w-lg overflow-x-scroll md:overflow-hidden">
                            <div aria-label="Payout account 2100048486 GTBank payout account selected"
                                class="flex items-center gap-4 bg-white rounded-lg py-3 px-4 max-w-full">
                                <div aria-hidden="true"
                                    class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#E6F4F1] flex-shrink-0">
                                    <i class="fas fa-check text-[#00875F] text-lg">
                                    </i>
                                </div>
                                <span class="font-normal text-base text-[#1E1E1E] select-none">
                                    2100048486
                                </span>
                                <span
                                    class="text-xs font-normal text-[#4B4B4B] bg-[#E9E9E9] rounded-full py-1 px-2 whitespace-nowrap">
                                    GTBank
                                </span>
                                <span
                                    class="text-xs font-semibold text-[#00875F] bg-[#E6F4F1] rounded-full py-1 px-2 flex items-center gap-1 whitespace-nowrap">
                                    <i class="fas fa-university text-xs">
                                    </i>
                                    PAYOUT ACCOUNT
                                </span>
                                <button aria-label="Edit payout account 2100048486 GTBank"
                                    class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </button>
                                <button aria-label="Delete payout account 2100048486 GTBank"
                                    class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-trash-alt">
                                    </i>
                                </button>
                            </div>
                            <div aria-label="Payout account 2100048486 Zenith"
                                class="flex items-center gap-4 bg-[#E9E9E9] rounded-lg py-3 px-4 max-w-full">
                                <div aria-hidden="true"
                                    class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#C4C4C4] flex-shrink-0">
                                    <i class="fas fa-check text-[#6B6B6B] text-lg">
                                    </i>
                                </div>
                                <span class="font-normal text-base text-[#1E1E1E] select-none">
                                    2100048486
                                </span>
                                <span
                                    class="text-xs font-normal text-[#4B4B4B] bg-[#E9E9E9] rounded-full py-1 px-2 whitespace-nowrap">
                                    Zenith
                                </span>
                                <button aria-label="Edit payout account 2100048486 Zenith"
                                    class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </button>
                                <button aria-label="Delete payout account 2100048486 Zenith"
                                    class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-trash-alt">
                                    </i>
                                </button>
                            </div>
                            <div aria-label="Payout account 12345678901 UBA"
                                class="flex items-center gap-4 bg-[#E9E9E9] rounded-lg py-3 px-4 max-w-full">
                                <div aria-hidden="true"
                                    class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#C4C4C4] flex-shrink-0">
                                    <i class="fas fa-check text-[#6B6B6B] text-lg">
                                    </i>
                                </div>
                                <span class="font-normal text-base text-[#1E1E1E] select-none">
                                    12345678901
                                </span>
                                <span
                                    class="text-xs font-normal text-[#4B4B4B] bg-[#E9E9E9] rounded-full py-1 px-2 whitespace-nowrap">
                                    UBA
                                </span>
                                <button aria-label="Edit payout account 12345678901 UBA"
                                    class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </button>
                                <button aria-label="Delete payout account 12345678901 UBA"
                                    class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                    <i class="fas fa-trash-alt">
                                    </i>
                                </button>
                            </div>
                        </div>
                    </section>
                </section>
            </section>
    </main>


    <script>
        document.querySelector('#bankAccountForm').addEventListener('click', function() {
            // alert("hello")
            let bank = document.querySelector("#bank").value;
            let country = document.querySelector("#country").value;
            let account_number = document.querySelector("#account-number").value;
            let account_name = document.querySelector("#account-name").value;
            //error section
            let errorbank = document.querySelector(".errorbank");
            let errorcountry = document.querySelector(".errorcountry");
            let errornumber = document.querySelector(".errornumber");
            let errorname = document.querySelector(".errorname");

            if(bank == ""){
                errorbank.innerHTML = "Please Select Your Bank";
                errornumber.innerHTML = "";
                errorcountry.innerHTML = "";
                errorname.innerHTML = "";
            }else if (country == ""){
                errorcountry.innerHTML = "Please Select Your Bank Country";
                errornumber.innerHTML = "";
                errorbank.innerHTML = "";
                errorname.innerHTML = "";
            }else if (account_number == ""){
                errornumber.innerHTML = "Please Enter Your Bank Number";
                errorbank.innerHTML = "";
                errorcountry.innerHTML = "";
                errorname.innerHTML = "";
            }else if (account_name == ""){
                errorname.innerHTML = "Please Enter Your Bank Name";
                errornumber.innerHTML = "";
                errorcountry.innerHTML = "";
                errorbank.innerHTML = "";
            }else{

                const formData = new FormData();
                formData.append('account_name', account_name);
                formData.append('account_number', account_number);
                formData.append('bank_country', country);
                formData.append('bank_name', bank);

                const responseDiv = document.getElementById('responseMessage');

                fetch('/api/business/bank-account', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`,  //Bearer token
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        responseDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        // Optionally refresh account list
                        // fetchBankAccounts();
                    } else {
                        responseDiv.innerHTML = `<div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg border border-red-200 flex justify-between items-center">Error: ${data.message}</div>`;
                    }
                })
                .catch(error => {
                    responseDiv.innerHTML = `<div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg border border-red-200 flex justify-between items-center">Request failed: ${error.message}</div>`;
                });
            }
        });



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
    </script>

</body>

</html>