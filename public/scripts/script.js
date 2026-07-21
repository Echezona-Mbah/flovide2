const dropdownButton = document.getElementById('dropdownButton');
const dropdownContent = document.getElementById('dropdownContent');
const hiddenInput = document.getElementById('selectedCountryId');
const form = document.getElementById('countryForm');

// Toggle dropdown visibility when button is clicked
dropdownButton.addEventListener('click', () => {
    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
});

// When a country is selected from the dropdown
document.querySelectorAll('.country-option').forEach(option => {
    option.addEventListener('click', () => {
        const name = option.dataset.name;
        const flag = option.dataset.flag;
        const id = option.dataset.id;

        // Update the button text and image
        dropdownButton.querySelector('span').textContent = name;
        dropdownButton.querySelector('span').classList = 'font-medium';  // Adjusting class for font weight
        const img = dropdownButton.querySelector('img');
        img.src = flag;
        img.classList = 'flex w-6 h-6 rounded-full border-2 border-gray-200 object-cover';
        img.classList.remove('hidden');

        // Hide the dropdown and update the hidden input
        dropdownContent.style.display = 'none';
        hiddenInput.value = id;
    });
});

// Close the dropdown if user clicks outside
window.addEventListener('click', (e) => {
    if (!dropdownButton.contains(e.target) && !dropdownContent.contains(e.target)) {
        dropdownContent.style.display = 'none';
    }
});







// $('#submitBusiness').on('click', function () {
//     // Clear previous errors
//     $('span[id^="error-"]').text('');

//     let formData = {
//         _token: '{{ csrf_token() }}',
//         business_name: $('#business-name').val(),
//         registration_number: $('#registration-number').val(),
//         day: $('#day').val(),
//         month: $('#month').val(),
//         year: $('#year').val(),
//         business_type: $('#business-type').val(),
//         company_url: $('#company-url').val(),
//         industry: $('#industry').val(),
//         annual_turnover: $('#annual-turnover').val()
//     };

//     $.ajax({
//         url: "{{ route('business.processStep2') }}", // Process Step 2
//         method: 'POST',
//         data: formData,
//         success: function (response) {
//             alert(response.message);
//         },
//         error: function (xhr) {
//             let errors = xhr.responseJSON.errors;
//             if (errors) {
//                 $.each(errors, function (key, value) {
//                     $('#error-' + key).text(value[0]);
//                 });
//             }
//         }
//     });
// });












// Handle country selection in the dropdown
const countryOptions = document.querySelectorAll('.country-option');
countryOptions.forEach(option => {
    option.addEventListener('click', function() {
        const countryId = this.getAttribute('data-id');
        const countryName = this.getAttribute('data-name');
        const countryFlag = this.getAttribute('data-flag');

        // Update the selected country ID in the hidden input
        document.getElementById('selectedCountryId').value = countryId;

        // Optionally, update the display of the selected country in the dropdown button
        document.querySelector('#dropdownButton span').textContent = countryName;
        document.querySelector('#dropdownButton img').src = countryFlag;
    });
});


   
   // const dropdownButton = document.getElementById('dropdownButton');
    // const dropdownContent = document.getElementById('dropdownContent');
    // const hiddenInput = document.getElementById('selectedCountryId');

    // dropdownButton.addEventListener('click', () => {
    //     dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    // });

    // document.querySelectorAll('.country-option').forEach(option => {
    //     option.addEventListener('click', () => {
    //         const name = option.dataset.name;
    //         const flag = option.dataset.flag;
    //         const id = option.dataset.id;

    //         dropdownButton.querySelector('span').textContent = name;
    //         dropdownButton.querySelector('span').classList = 'font-[500px]';
    //         const img = dropdownButton.querySelector('img');
    //         img.src = flag;
    //         img.classList = 'flex w-6 h-6 rounded-full border-2 border-gray-200 object-cover';
    //         img.classList.remove('hidden');

    //         dropdownContent.style.display = 'none';
    //         hiddenInput.value = id;
    //     });
    // });

    // window.addEventListener('click', (e) => {
    //     if (!dropdownButton.contains(e.target) && !dropdownContent.contains(e.target)) {
    //         dropdownContent.style.display = 'none';
    //     }
    // });





// Toggle the trading address container based on the checkbox state
const toggleCheckbox = document.getElementById('toggle-checkbox');
const tradingAddressContainer = document.getElementById('trading-address-container');

toggleCheckbox.addEventListener('change', function () {
    if (this.checked) {
        tradingAddressContainer.classList.add('hidden');
    } else {
        tradingAddressContainer.classList.remove('hidden');
    }
});
















// Custom select dropdown for all the states
// const states = [
//     "Abia", "Adamawa", "Akwa Ibom", "Anambra", "Bauchi", "Bayelsa", "Benue",
//     "Borno", "Cross River", "Delta", "Ebonyi", "Edo", "Ekiti", "Enugu",
//     "Gombe", "Imo", "Jigawa", "Kaduna", "Kano", "Kogi", "Kwara", "Lagos",
//     "Nasarawa", "Niger", "Ogun", "Ondo", "Osun", "Oyo", "Plateau", "Rivers",
//     "Sokoto", "Taraba", "Yobe", "Zamfara"
// ];

// const selectButton = document.getElementById('select-button');
// const selectOptions = document.getElementById('select-options');
// const selectedState = document.getElementById('selected-state');

// // Populate dropdown with states
// states.forEach(state => {
//     const stateDiv = document.createElement('div');
//     stateDiv.textContent = state;
//     stateDiv.addEventListener('click', () => {
//         selectedState.textContent = state; // Update selected state
//         selectOptions.style.display = 'none'; // Hide dropdown
//     });
//     selectOptions.appendChild(stateDiv);
// });

// // Toggle dropdown visibility
// selectButton.addEventListener('click', () => {
//     selectOptions.style.display = selectOptions.style.display === 'block' ? 'none' : 'block';
// });

// // Close dropdown if clicked outside
// window.addEventListener('click', (event) => {
//     if (!event.target.matches('.select-button')) {
//         selectOptions.style.display = 'none';
//     }
// });






//handle OTP
function focusNextInput(el, prevId, nextId) {
    if (el.value.length === 0) {
        if (prevId) {
            document.getElementById(prevId).focus();
        }
    } else {
        if (nextId) {
            document.getElementById(nextId).focus();
        }
    }
}

document.querySelectorAll('[data-focus-input-init]').forEach(function (element) {
    element.addEventListener('keyup', function () {
        const prevId = this.getAttribute('data-focus-input-prev');
        const nextId = this.getAttribute('data-focus-input-next');
        focusNextInput(this, prevId, nextId);
    });

    // Handle paste event to split the pasted code into each input
    element.addEventListener('paste', function (event) {
        event.preventDefault();
        const pasteData = (event.clipboardData || window.clipboardData).getData('text');
        const digits = pasteData.replace(/\D/g, ''); // Only take numbers from the pasted data

        // Get all input fields
        const inputs = document.querySelectorAll('[data-focus-input-init]');

        // Iterate over the inputs and assign values from the pasted string
        inputs.forEach((input, index) => {
            if (digits[index]) {
                input.value = digits[index];
                // Focus the next input after filling the current one
                const nextId = input.getAttribute('data-focus-input-next');
                if (nextId) {
                    document.getElementById(nextId).focus();
                }
            }
        });
    });
});
