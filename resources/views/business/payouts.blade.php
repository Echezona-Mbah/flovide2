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
        <header class=" items-center justify-between mb-8 flex-wrap gap-4 hidden md:flex">
            <h1 class="text-2xl font-extrabold leading-tight flex-1 min-w-[200px]">
                Payout accounts
            </h1>
            @include('business.header_notifical')

        </header>
        <section class=" relative ">
            <section class="flex flex-col lg:flex-row gap-8 bg-white md:rounded-tl-3xl md:p-6 p-2 shadow-md md:absolute right-[-2.3vw] overflow-x-hidden  ">
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
                            {{-- @csrf --}}
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
                            <button aria-label="Delete All Payout Accounts" class="delete-all-btn flex items-center gap-1 text-[#D92D20] text-sm font-semibold rounded-md px-3 py-1 border border-[#D92D20] whitespace-nowrap">
                                <i class="fas fa-trash-alt"></i>
                                Delete All
                            </button>
                        </div>
                        <div aria-label="List of payout accounts" class="bg-[#F7F7F7] rounded-xl p-2 md:p-5 flex flex-col gap-3 max-w-full lg:max-w-lg overflow-x-scroll md:overflow-hidden">
                            <p id="default-message" class="mt-4 text-green-600 font-medium hidden"></p>
                            @if($bankAccounts->isEmpty())
                                <p>No bank accounts found.</p>
                            @else
                                @foreach($bankAccounts as $account)
                                @if($account->default)
                                    <div aria-label="Payout account 2100048486 GTBank payout account selected" class="flex items-center gap-4 bg-white rounded-lg py-3 px-4 max-w-full">
                                        <div aria-hidden="true" class="flex items-center justify-center w-9 h-9 rounded-lg bg-[#E6F4F1] flex-shrink-0">
                                            <i class="fas fa-check text-[#00875F] text-lg">
                                            </i>
                                        </div>
                                @else
                                    <div aria-label="" class="flex items-center gap-4 bg-[#E9E9E9] rounded-lg py-3 px-4 max-w-full">
                                        <div aria-hidden="true" data-id="{{ $account->id }}" class="set-default-btn flex items-center justify-center w-9 h-9 rounded-lg bg-[#C4C4C4] flex-shrink-0">
                                            <i class="fas fa-check text-[#6B6B6B] text-lg"></i>
                                        </div>
                                @endif
                                        <span class="font-normal text-base text-[#1E1E1E] select-none">
                                            {{ Crypt::decryptString($account->account_number) }}
                                        </span>
                                        <span
                                            class="text-xs font-normal text-[#4B4B4B] bg-[#E9E9E9] rounded-full py-1 px-2 whitespace-nowrap">
                                            {{ $account->bank_name }}
                                        </span>
                                        @if($account->default)
                                            <span class="text-xs font-semibold text-[#00875F] bg-[#E6F4F1] rounded-full py-1 px-2 flex items-center gap-1 whitespace-nowrap">
                                                <i class="fas fa-university text-xs"></i>
                                                PAYOUT ACCOUNT
                                            </span>
                                        @endif
                                        <button class="ml-auto text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0">
                                            <a href="{{ route('business.edit', $account->id) }}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        </button>
                                        <button class="text-[#6B6B6B] hover:text-[#1E1E1E] flex-shrink-0 delete-icon" data-id="{{ $account->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                            
                        </div>
                    </section>
                </section>
            </section>
    </main>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

                fetch('{{ route('business.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json()).then(data => {
                    if (data.status == "success") {
                        Swal.fire('Added!', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        responseDiv.innerHTML = `<div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg border border-red-200 flex justify-between items-center">Error: ${data.message}</div>`;
                    }
                })
                .catch(error => {
                    console.log(error);
                });
            }
        });


        document.querySelectorAll('.set-default-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id; 
        
                fetch(`/business/bank-accounts/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // const msg = document.getElementById('default-message');
                    // msg.textContent = data.message;
                    // msg.classList.remove('hidden');
                    Swal.fire('Set!', data.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .catch(err => console.log(err));
            });
        });


        document.querySelectorAll('.delete-icon').forEach((button) => {
            button.addEventListener('click', function() {
                const accountId = this.getAttribute('data-id');  
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/business/delete-account/${accountId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Your bank account has been deleted.',
                                    'success'
                                ).then(() => {
                                    location.reload(); 
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting your bank account.',
                                    'error'
                                );
                                console.log(data);
                            }
                        })
                        .catch(error => console.log('Error:', error));
                    }
                });
            });
        });


        document.querySelector('.delete-all-btn').addEventListener('click', function () {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete all your saved bank accounts!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete all!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/business/bank-accounts/delete-all', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        Swal.fire('Deleted!', 'All your bank accounts have been deleted.', 'success').then(() => {
                            location.reload();
                        });
                    })
                    .catch(err => {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                        console.log(err);
                    });
                }
            });
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