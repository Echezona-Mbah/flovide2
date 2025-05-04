<style>
    #email-error{
        background-color: #F56565;
        color: white;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 10px;
        widows: 20px;
    }


    /* Add this to your stylesheet */
    .error-message {
        background-color: #F56565;
        color: white;
        padding: 10px;
        border-radius: 5px;
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 10px;
        widows: 20px;
    }
    #error-incorporation_date {
    font-size: 12px;
    margin-top: 5px;
}
.error-message {
    display: block;
}

#loader.hidden {
    display: none;
}

#loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
}

.spinner {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

    
    </style>
    <!DOCTYPE html>
    <html lang="en">
    
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <title>Sign Up</title>
    </head>
    
    <body>
        <style>
    .toast {
        position: fixed;
        top: 30px; /* change from bottom to top */
        right: 30px;
        background-color: #333;
        color: #fff;
        padding: 16px 24px;
        border-radius: 8px;
        font-size: 16px;
        opacity: 0;
        transition: opacity 0.5s ease, transform 0.5s ease;
        transform: translateY(-20px); /* move upward initially */
        z-index: 9999;
    }
    .toast.show {
        opacity: 1;
        transform: translateY(0);
    }
    
        </style>
        <div id="toast" class="toast hidden"></div>

          
          <!-- Loader (Initially Hidden) -->
        <div id="loader" class="hidden">
            <div class="spinner"></div>
        </div>
    
        <section>
    
            <!-- logo -->
            <section class="relative">
                <div class="max-w-[300px] px-10 absolute top-10">
                    <img src="../asserts/auth/Logo.svg" alt="" class="w-[90px]" />
                </div>
            </section>
            <!-- logo end-->
    
    
            <ol class="flex flex-col w-[100%]">
    
                <!--  Business location screen  -->
                <!-- stepper 1 -->
                <li class="flex  h-screen justify-between w-full items-center  ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Verification</p>
                                </div>
                            </section>
    
    
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Where is your business located?
                            </h1>
    
                            <!-- select country -->
                            <section>
                                <form class="max-w-sm mt-10" action="" method="POST" id="countryForm">
                                    @csrf
                                    <label for="countries" class="block mb-2 text-sm font-bold text-gray-900 dark:text-white">Country</label>
                            
                                    <div class="custom-dropdown">
                                        <div class="dropdown-button" id="dropdownButton">
                                            <div class="flex gap-4 items-center">
                                                <img src="" alt="" class="hidden" />
                                                <span>Select a country</span>
                                            </div>
                                            <i class="fa-solid fa-angle-down"></i>
                                        </div>
                                        <div class="dropdown-content" id="dropdownContent">
                                            @foreach ($countries as $country)
                                                <div class="country-option flex items-center gap-2 px-4 py-2 cursor-pointer hover:bg-gray-100" 
                                                    data-id="{{ $country->code }}" 
                                                    data-name="{{ $country->name }}" 
                                                    data-flag="{{ $country->flag ?? 'https://flagcdn.com/' . strtolower($country->code) . '.svg' }}"
                                                    data-code="{{ $country->code ?? 'US' }}">
                                                    <img src="{{ $country->flag ?? 'https://flagcdn.com/' . strtolower($country->code) . '.svg' }}" 
                                                        alt="{{ $country->name }} flag" 
                                                        class="w-6 h-6 rounded-full border-2 border-gray-200 object-cover">
                                                    <span>{{ $country->name }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                    </div>
                            
                                    <input type="hidden" name="country_id" id="selectedCountryId">
                            
                                    <!-- Error Message Container -->
                                    <div id="countryErrorMessage" ></div>
                            
                                    <!-- Stepper Button -->
                                    <section class="mt-10">
                                        <button type="submit" class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] btn-continue">
                                            Continue
                                        </button>
                                    </section>
                                </form>
                            </section>
                            
                            
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user1.png" alt="" class="object-cover" />
                    </section>
                </li>
                <!-- stepper 1 end -->
    
              
    
    
                <!--  Business Information pt1 screen  -->
                <!-- stepper 2 -->
                <li class="flex  h-screen justify-between w-full items-center ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden ">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Verification</p>
                                </div>
                            </section>
    
    
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Your Business Information
                            </h1>
    
                            <!-- select country -->
                            <section class="w-full">
                                <form class="max-w-xl mt-10" id="businessForm">
                                    <section class="flex flex-col gap-10">
                                        <!-- Business Name -->
                                        <div>
                                            <label for="business-name" data-error-id="businessNameError" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Business Name</label>
                                            <input type="text" id="business-name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter business name">
                                            <div id="businessNameError" ></div>
    
                                        </div>
                                        <!-- Registration Number -->
                                        <div>
                                            <label for="helper-text" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Registration Number</label>
                                            <input type="text" data-error-id="error-registration_number" id="registration-number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter your company reg no">
                                            <div id="error-registration_number"></div>
                                        </div>
                                        <!-- Incorporation Date -->
                                        <div>
                                            <label for="incorporation-date" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Incorporation Date</label>
                                            <section class="flex flex-col md:flex-row md:justify-between md:items-center md:gap-2 gap-10">
                                                <input type="text" id="day" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Day">
                                                <select id="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                    <option value="" disabled selected>Select a month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <input type="text" id="year" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Year">
                                            </section>
                                            <div id="error-incorporation_date" class="text-red-500 mt-2"></div>
                                        </div>
                                        
                                        <!-- Business Type -->
                                        <div>
                                            <label for="business-type" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Business Type</label>
                                            <input type="text" id="business-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Enter your business type">
                                            <div id="error-business_type" ></div>
    
                                        </div>
    
                                        <!-- Company URL -->
                                        <div>
                                            <label for="company-url" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Company URL</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
                                                    <p>https://</p>
                                                </div>
                                                <input type="text" id="company-url" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-20 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter your URL">
                                            </div>
                                            <div id="error-company_url"></div>
    
                                        </div>
                                        <!-- Industry -->
                                        <div>
                                            <label for="industry" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Industry</label>
                                            <select id="industry" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option selected>Select an option</option>
                                                @foreach ($industries as $industry)
                                                <option value="{{ $industry->id }}">{{ $industry->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="error-industry"></div>
                                        </div>
    
                                      <!-- Annual Turnover -->
                                      
                                      <div class="relative">
                                        <label for="annual-turnover" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Annual Turnover</label>
                                        
                                        <!-- Wrapper div for the input and currency symbol -->
                                        <div class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full pl-8 p-2.5 flex items-center">
                                            <span id="currency-symbol" class="text-gray-900 mr-2"></span>
                                            <input type="text" id="annual-turnover" 
                                                class="bg-transparent border-none text-gray-900 text-lg w-full focus:outline-none" 
                                                placeholder="Enter your turnover">
                                        </div>
                                        
                                        <div id="error-annual_turnover" class="text-red-500 text-sm mt-1"></div>
                                    </div>
                                    
    
                                        
                                        
                                        
    
                                    </section>
                                
    
    
                                    <!-- stepper btn -->
                                    <section class="mt-10 flex gap-5 mb-10">
                                        <button type="button"
                                            class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C] btn-back
                                            ">
                                            Back
                                        </button>
                                        <button type="button"
                                            class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] btn-continue"                                         id="submitBusiness"
                                            >
                                            Continue
                                        </button>
                                    </section>
                                    <!-- stepper btn end-->
                                </form>
                            </section>
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user2.png" alt="" class="object-cover" />
                    </section>
                </li>
                <!-- stepper 2 end-->
    
    
    
                <!--  Business Information pt2 screen  -->
                <!-- stepper 3 cont -->
                <li class="flex  h-screen justify-between w-full items-center ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden ">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Verification</p>
                                </div>
                            </section>
    
    
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Your Business Information
                            </h1>
    
                            <!-- select country -->
                            <section class="w-full">
                                <form class="max-w-xl mt-10">
                                    <section class="flex flex-col gap-10">
                                        <!-- Street Address -->
                                        <div>
                                            <label for="street_address"
                                                class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Street Address</label>
                                            <input type="text" id="street_address"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Enter street address">
                                            <div id="error-street-address" class="text-red-500 text-sm"></div>
                                        </div>
                            
                                        <!-- City -->
                                        <div>
                                            <label for="city"
                                                class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">City</label>
                                            <input type="text" id="city"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Enter city">
                                            <div id="error-city" class="text-red-500 text-sm"></div>
                                        </div>
                            
                                        <!-- Trading Address -->
                                        <div>
                                            <section class="flex justify-between items-center w-full">
                                                <label for="trading_address"
                                                    class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Trading Address</label>
                            
                                                <!-- Toggle Button -->
                                                <section class="mb-2 capitalize">
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <span
                                                            class="ms-3 text-sm font-medium text-[#828282] dark:text-gray-300 mx-2 hidden md:flex">Same
                                                            as street address</span>
                                                        <input type="checkbox" id="toggle-checkbox" class="sr-only peer">
                                                        <div
                                                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                                        </div>
                                                    </label>
                                                </section>
                                            </section>
                            
                                            <div id="trading-address-container" class="mt-4">
                                                <input type="text" id="trading_address"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Enter trading address">
                                            </div>
                                            <div id="error-trading-address" class="text-red-500 text-sm"></div>
                                        </div>
                            
                                        <!-- Nature of Business -->
                                        <div>
                                            <label for="message"
                                                class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Nature of Business</label>
                                            <textarea id="message" rows="6"
                                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="Enter the nature of your business, such as the services you offer, your target audience, etc."></textarea>
                                            <div id="error-nature-of-business" class="text-red-500 text-sm"></div>
                                        </div>
                                    </section>
                            
                                                              <!-- stepper btn -->
                                    <section class="mt-10 flex gap-5 mb-10">
                                        <button type="button"
                                            class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C] btn-back
                                            ">
                                            Back
                                        </button>
                                        <button type="button"
                                            class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] btn-continue"                                         id="submitBusiness"
                                            >
                                            Continue
                                        </button>
                                    </section>
                                    <!-- stepper btn end-->
                                </form>
                            </section>
                            
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user3.png" alt="" class="object-cover" />
                    </section>
                </li>
                <!-- stepper 3 cont end-->
    
    
    
                <!-- Create  Account screen -->
                <!-- stepper 4  -->
                <li class="flex  h-screen justify-between w-full items-center ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden ">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D7D4D5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#D7D4D5] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#828282]">
                                    <p>Verification</p>
                                </div>
                            </section>
    
    
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Create Your Account
                            </h1>
    
                            <!-- select country -->
                            <section class="w-full">
                                <form class="max-w-xl mt-10">
                                    @if ($errors->any())
                                    <script>
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'error',
                                            title: '{{ $errors->first() }}',
                                            showConfirmButton: false,
                                            timer: 4000,
                                            timerProgressBar: true,
                                        });
                                    </script>
                                @endif
                                
                                @if (session('status'))
                                    <script>
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'success',
                                            title: '{{ session('status') }}',
                                            showConfirmButton: false,
                                            timer: 4000,
                                            timerProgressBar: true,
                                        });
                                    </script>
                                @endif
                                
                                @if (session('error'))
                                    <script>
                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            icon: 'error',
                                            title: '{{ session('error') }}',
                                            showConfirmButton: false,
                                            timer: 4000,
                                            timerProgressBar: true,
                                        });
                                    </script>
                                @endif
                                    <section class="flex flex-col gap-10">
                                        <!-- Street Address -->
                                        {{-- <div>
                                            <label for="street_address" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Street Address</label>
                                            <input type="text" id="street_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter street address">
                                            <div id="error-street-address" class="text-red-500 text-sm"></div>
                                        </div> --}}
                            
                                        <!-- City -->
                                        {{-- <div>
                                            <label for="city" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">City</label>
                                            <input type="text" id="city" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Enter city">
                                            <div id="error-city" class="text-red-500 text-sm"></div>
                                        </div> --}}
    
                                               <!-- select state -->
                                    <!-- Select State -->
                                    <!-- Nigerian State Select (visible only if country is NG) -->
                                    
                                    {{-- <div id="ng-state-select" class="hidden">
                                        <div class="custom-select">
                                            <select id="state" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="" disabled selected>Select a state</option>
                                                @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="error-state" class="text-red-500 text-sm"></div>
                                    </div> --}}

                                    <!-- Generic State Input (visible for all non-NG countries) -->
                                    <div id="generic-state-input">
                                        <label for="state" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">State</label>
                                        <input type="text" id="state" placeholder="Enter Your State"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <div id="error-state" class="text-red-500 text-sm"></div>
                                    </div>
    
                            
                                        <!-- Email -->
                                        <div>
                                            <label for="email" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Email</label>
                                            <input type="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="johndoe@gmail.com">
                                            <div id="error-email" class="text-red-500 text-sm"></div>
                                            <small id="email-error" class="text-danger" style="display: none;"></small>
                                        </div>
                                            
    
                            
                                        <!-- Password -->
                                        <div>
                                            <label for="password" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Password</label>
                                            <input type="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" autocomplete="off">
                                            <div id="error-password" class="text-red-500 text-sm"></div>
                                        </div>
                            
                                        <!-- Confirm Password -->
                                        <div>
                                            <label for="confirm_password" class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Confirm Password</label>
                                            <input type="password" id="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" autocomplete="off">
                                            <div id="error-confirm-password" class="text-red-500 text-sm"></div>                                    </div>
                            
                                        <!-- Terms and Conditions -->
                                        <div>
                                            <p class="text-[#828282]">By signing up, you agree to our <a href="#" class="text-[#252525] underline font-medium">Terms and Conditions</a>, and <a href="#" class="text-[#252525] underline font-medium">Privacy Policy</a></p>
                                        </div>
                                    </section>
                            
                                    <!-- Stepper Buttons -->
                                    <section class="mt-10 flex gap-5 mb-10">
                                        <button type="button" class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C] btn-back">Back</button>
                                        <button type="button" class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] btn-continue">Continue</button>
                                    </section>
                                    <!-- Stepper Buttons End -->
                                </form>
                            </section>
                            
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user5.png" alt="" class="object-cover" />
                    </section>
                </li>
                <!-- stepper 4 end-->
    
    
    
    
    
                <!-- OTP verifications Screens -->
                <!-- stepper 5 -->
                {{-- <li class="flex  h-screen justify-between w-full items-center ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden ">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Verification</p>
                                </div>
                            </section>
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Please Enter The OTP Sent To Your Email
                            </h1>
    
                            <!-- select country -->
                            <section class="w-full">
                                <form class="max-w-xl mt-10">
                                    <section class="flex flex-col gap-10">
                                        <div class="flex mb-2 space-x-2 rtl:space-x-reverse">
                                            <div>
                                                <label for="code-1" class="sr-only">First code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-next="code-2" id="code-1"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="code-2" class="sr-only">Second code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-prev="code-1" data-focus-input-next="code-3"
                                                    id="code-2"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="code-3" class="sr-only">Third code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-prev="code-2" data-focus-input-next="code-4"
                                                    id="code-3"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="code-4" class="sr-only">Fourth code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-prev="code-3" data-focus-input-next="code-5"
                                                    id="code-4"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="code-5" class="sr-only">Fifth code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-prev="code-4" data-focus-input-next="code-6"
                                                    id="code-5"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                            <div>
                                                <label for="code-6" class="sr-only">Sixth code</label>
                                                <input type="text" maxlength="1" data-focus-input-init
                                                    data-focus-input-prev="code-5" id="code-6"
                                                    class="block w-10 h-10 md:w-18 md:h-16 py-3 text-sm font-extrabold text-center text-gray-900 bg-[#FAFAFA] border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required />
                                            </div>
                                        </div>
    
                                        <section>
                                            <div class="flex  items-center w-full">
                                                <p>Edit your email - <span class="text-[#215F9C]">efeativie@gmail.com</span>
                                                </p>
                                                <img src="../asserts/icons/edit.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                            </div>
                                            <div class="flex  items-center w-full">
                                                <p>Didn’t get code? <span class="text-[#215F9C]">Resend OTP</span></p>
                                                <img src="../asserts/icons/share.svg" alt="" class="w-5 h-5 text-[#215F9C]">
                                            </div>
                                        </section>
                                    </section>
    
                                    <!-- stepper btn -->
                                    <section class="mt-10 flex gap-5 mb-10">
                                        <button type="button" type="button"
                                            class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C]btn-back">
                                            Back
                                        </button>
                                        <button type="submit"
                                        class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C] btn-continue">
                                        Continue
                                    </button>
                                    
                                    </section>
                                    <!-- stepper btn end-->
                                </form>
                            </section>
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user6.png" alt="" class="object-cover" />
                    </section>
                </li> --}}
                <!-- stepper 5 end-->
    
    
    
    
                <!-- Bank  varification screen -->
                <!-- stepper 6 -->
                {{-- <li class="flex  h-screen justify-between w-full items-center ">
                    <section class="">
    
                        <!-- stepper link -->
                        <section class="md:flex relative top-[-11em] px-10 hidden ">
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-1em] leading-relaxed text-[#215F9C]">
                                    <p>Country</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Business Info</p>
                                </div>
                            </section>
    
                            <section
                                class="flex w-full relative items-center text-blue-600 dark:text-blue-500 after:content-[''] after:w-[12vw] after:h-0.5 after:border-b after:border-[#D6E7F5] after:border-4 after:inline-block after:rounded-full">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Personal info</p>
                                </div>
                            </section>
    
                            <section class="flex w-full relative items-center text-blue-600 dark:text-blue-500">
                                <div class="flex items-center justify-center h-10 text-[#589DD8] shrink-0">
                                    <span>
                                        <i class="fa-solid fa-circle-dot"></i>
                                    </span>
                                </div>
                                <div
                                    class="block font-[500] text-base antialiased absolute top-10 left-[-2em] leading-relaxed text-[#215F9C]">
                                    <p>Verification</p>
                                </div>
                            </section>
    
    
                        </section>
                        <!-- stepper link end-->
    
                        <!-- stepper content -->
                        <section class="absolute md:top-[40vh] top-[30vh] w-full px-10">
                            <h1 class="w-full font-semibold">
                                Bank Verification
                            </h1>
    
    
                            <section class="w-full">
                                <form class="max-w-xl mt-10">
                                    <section class="flex flex-col gap-10">
                                        <!-- Bank verification -->
                                        <div>
                                            <label for="helper-text"
                                                class="block mb-2 text-sm font-bold text-[#828282] dark:text-white">Please
                                                Enter Your
                                                BVN</label>
                                            <input type="text" id="helper-text" aria-describedby="helper-text-explanation"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                placeholder="12345678901">
    
                                        </div>
                                        <!-- Bank verification end-->
    
                                        <div>
                                            <p class="text-[#828282]">The Bank Verification Number (BVN) is a unique
                                                11-digit identifier
                                                assigned to
                                                each individual and remains the same across all banks.</p>
                                        </div>
                                    </section>
    
                                    <!-- stepper btn -->
                                    <section class="mt-10 flex gap-5 mb-10">
                                        <button type="button" type="button"
                                            class="w-[10em] rounded-full p-2 h-12 border border-[#215F9C] text-[#215F9C]"
                                            id="btn-back">
                                            Back
                                        </button>
                                        <button type="button"
                                            class="w-[10em] rounded-full p-2 h-12 bg-[#D6E7F5] text-[#215F9C]"
                                            id="btn-continue">
                                            Submit
                                        </button>
                                    </section>
                                    <!-- stepper btn end-->
                                </form>
                            </section>
                            <!-- select country end-->
                        </section>
                        <!-- stepper content end-->
    
                    </section>
    
                    <section class="w-[50%] h-full bg-[#F0F0F0]  items-center justify-center p-14 hidden md:flex">
                        <img src="../asserts/auth/user7.png" alt="" class="object-cover" />
                    </section>
                </li> --}}
                <!-- stepper 6 end-->
            </ol>
        </section>
        <script>
            window.saveStepDataUrl = "{{ route('register.saveStepData') }}";
        </script>
        
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <script src="{{ asset('../scripts/script.js') }}"></script>
        <script src="{{ asset('../scripts/stepper.js') }}"></script>
    </body>
    
    </html>