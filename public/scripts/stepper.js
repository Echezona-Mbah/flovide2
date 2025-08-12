document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('ol li');
    let currentStep = 0;
    let formData = {};

    const saveStepDataUrl = window.saveStepDataUrl;

    const countryCurrencyMap = {
        'AE': 'د.إ', // United Arab Emirates
        'AU': '$',   // Australia
        'BR': 'R$',  // Brazil
        'CA': '$',   // Canada
        'CN': '¥',   // China
        'DE': '€',   // Germany
        'EG': '£',   // Egypt
        'FR': '€',   // France
        'GB': '£',   // United Kingdom
        'GH': '₵',   // Ghana
        'IN': '₹',   // India
        'JP': '¥',   // Japan
        'KE': 'KSh', // Kenya
        'KR': '₩',   // South Korea
        'MX': '$',   // Mexico
        'NG': '₦',   // Nigeria
        'RU': '₽',   // Russia
        'SA': 'ر.س', // Saudi Arabia
        'US': '$',   // United States
        'ZA': 'R',   // South Africa
    };
    

    const currencySymbolSpan = document.getElementById('currency-symbol');
    if (currencySymbolSpan) {
        currencySymbolSpan.innerText = '';
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.innerText = message;
        toast.classList.remove('hidden');
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 500);
        }, 3000);
    }

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('hidden', index !== stepIndex);
        });
    }

    showStep(currentStep);

    async function submitFormData(formData) {
        try {
            // Show the loader
            const loader = document.getElementById('loader');
            loader.classList.remove('hidden'); // Show the loader
    
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch(saveStepDataUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(formData),
            });
    
            // Hide the loader once the response is received
            loader.classList.add('hidden'); // Hide the loader
    
            if (response.status === 422) {
                const errorData = await response.json();
    
                // Show error in console and optionally to user
                console.warn('Email already exists');
                const emailError = document.querySelector('#email-error');
                if (emailError) {
                    emailError.textContent = errorData.message || 'Validation error';
                    emailError.style.display = 'block';
                }
    
                return;
            }
    
            if (response.redirected) {
                window.location.href = response.url;
                return;
            }
    
            const responseText = await response.text();
            if (response.headers.get('Content-Type')?.includes('application/json')) {
                const result = JSON.parse(responseText);
                showToast('Form submitted successfully!');
            } else {
                console.error('Unexpected response:', responseText);
            }
        } catch (error) {
            console.error('Submission error:', error);
    
            // Hide the loader in case of error
            const loader = document.getElementById('loader');
            loader.classList.add('hidden');
        }
    }
    
    

    async function validateStep(step) {
        let hasError = false;

        if (step === 0) {
            const selectedCountry = document.getElementById('selectedCountryId').value;
            const countryError = document.getElementById('countryErrorMessage');
            if (!selectedCountry) {
                countryError.innerText = "Please select a country!";
                countryError.classList.add("error-message");
                hasError = true;
            } else {
                countryError.innerText = "";
                countryError.classList.remove("error-message");
                formData.country = selectedCountry;
                formData.currencySymbol = countryCurrencyMap[selectedCountry] || '$';
            }
        }

        if (step === 1) {
            const fields = [
                { id: 'business-name', errorId: 'businessNameError', message: 'Please enter a business name!' },
                { id: 'registration-number', errorId: 'error-registration_number', message: 'Please enter a registration number!' },
                { id: 'business-type', errorId: 'error-business_type', message: 'Please enter the business type!' },
                { id: 'company-url', errorId: 'error-company_url', message: 'Please enter the company URL!' },
                { id: 'industry', errorId: 'error-industry', message: 'Please select an industry!' },
                { id: 'annual-turnover', errorId: 'error-annual_turnover', message: 'Please enter the annual turnover!' }
            ];

            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorContainer = document.getElementById(field.errorId);
                input.setAttribute('data-error-id', field.errorId);

                const value = input.value.trim();

                if (!value) {
                    errorContainer.innerText = field.message;
                    errorContainer.classList.add("error-message");
                    hasError = true;
                } else {
                    if (field.id === 'company-url') {
                        const urlPattern = /^(https?:\/\/)?([\w\d-]+\.)+[\w\d]{2,}(\/.*)?$/i;
                        if (!urlPattern.test(value)) {
                            errorContainer.innerText = "Please enter a valid URL!";
                            errorContainer.classList.add("error-message");
                            hasError = true;
                            return;
                        }
                    }
                    if (field.id === 'annual-turnover') {
                        const numericPattern = /^[0-9]+$/;
                        if (!numericPattern.test(value)) {
                            errorContainer.innerText = "Annual turnover must be a number!";
                            errorContainer.classList.add("error-message");
                            hasError = true;
                            return;
                        }
                    }
                    errorContainer.innerText = '';
                    errorContainer.classList.remove("error-message");
                    formData[field.id] = value;
                }
            });

            const day = parseInt(document.getElementById('day').value.trim());
            const month = parseInt(document.getElementById('month').value.trim());
            const year = parseInt(document.getElementById('year').value.trim());
            const errorDateContainer = document.getElementById('error-incorporation_date');

            console.log("Day:", day, "Month:", month, "Year:", year); // Debugging statement

            function isValidDate(d, m, y) {
                const date = new Date(y, m - 1, d);
                return date.getDate() === d && date.getMonth() === m - 1 && date.getFullYear() === y;
            }

            if (
                isNaN(day) || day < 1 || day > 31 ||
                isNaN(month) || month < 1 || month > 12 ||
                isNaN(year) || year < 1900 || year > new Date().getFullYear() ||
                !isValidDate(day, month, year)
            ) {
                errorDateContainer.innerText = "Please enter a valid date.";
                errorDateContainer.classList.add("error-message");
                hasError = true;
            } else {
                errorDateContainer.innerText = "";
                errorDateContainer.classList.remove("error-message");
                formData['day'] = day;
                formData['month'] = month;
                formData['year'] = year;
            }

            

            if (currencySymbolSpan) {
                currencySymbolSpan.innerText = formData.currencySymbol || '$';
            }
        }

        if (step === 2) {
            const sameAsStreet = document.getElementById('toggle-checkbox').checked;
            const streetAddressVal = document.getElementById('street_address').value.trim();
        
            const fields = [
                { id: 'street_address', errorId: 'error-street-address', message: 'Please enter your street address!' },
                { id: 'city', errorId: 'error-city', message: 'Please enter your city!' },
                { id: 'trading_address', errorId: 'error-trading-address', message: 'Please enter your trading address!' },
                { id: 'message', errorId: 'error-nature-of-business', message: 'Please enter the nature of your business!' }
            ];
        
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorContainer = document.getElementById(field.errorId);
                input.setAttribute('data-error-id', field.errorId);
        
                let value = input.value.trim();
        
                // Handle "Same as street address"
                if (field.id === 'trading_address' && sameAsStreet) {
                    value = streetAddressVal;
                    input.value = value; // update input so it's saved
                }
        
                if (!value) {
                    errorContainer.innerText = field.message;
                    errorContainer.classList.add("error-message");
                    hasError = true;
                } else {
                    errorContainer.innerText = "";
                    errorContainer.classList.remove("error-message");
                    formData[field.id] = value;
                }
            });
        }
        

        if (step === 3) {
            const fields = [
                { id: 'email', errorId: 'error-email', message: 'Please enter your email!' },
                { id: 'state', errorId: 'error-state', message: 'Please enter your state!' },
                { id: 'password', errorId: 'error-password', message: 'Please enter your password!' },
                { id: 'confirm_password', errorId: 'error-confirm-password', message: 'Please confirm your password!' }
            ];
    
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const errorContainer = document.getElementById(field.errorId);
                input.setAttribute('data-error-id', field.errorId);
    
                const value = input.value.trim();
    
                // Reset previous error
                errorContainer.innerText = "";
                errorContainer.classList.remove("error-message");
    
                if (!value) {
                    errorContainer.innerText = field.message;
                    errorContainer.classList.add("error-message");
                    hasError = true;
                } else if (field.id === 'password' && value.length < 8) {
                    errorContainer.innerText = "Password must be more than 8 characters!";
                    errorContainer.classList.add("error-message");
                    hasError = true;
                } else {
                    formData[field.id] = value;
                }
            });
    
            const password = document.getElementById('password').value.trim();
            const confirmPassword = document.getElementById('confirm_password').value.trim();
    
            if (password && confirmPassword && password !== confirmPassword) {
                const confirmPasswordError = document.getElementById('error-confirm-password');
                confirmPasswordError.innerText = "Passwords do not match!";
                confirmPasswordError.classList.add("error-message");
                hasError = true;
            }
        }
    
        

        return { hasError };
    }

    steps.forEach((step, index) => {
        const continueButton = step.querySelector('.btn-continue');
        const backButton = step.querySelector('.btn-back');

        if (continueButton) {
            continueButton.addEventListener('click', async (e) => {
                e.preventDefault();
                const { hasError } = await validateStep(currentStep);
                if (hasError) return;

                if (currentStep === steps.length - 1) {
                    await submitFormData(formData);
                } else {
                    currentStep++;
                    showStep(currentStep);
                }
            });
        }

        if (backButton) {
            backButton.addEventListener('click', (e) => {
                e.preventDefault();
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const selectedCountryInput = document.getElementById('selectedCountryId');
        if (selectedCountryInput) {
            selectedCountryInput.addEventListener('change', (event) => {
                const selectedCountry = event.target.value;
                const currencySymbol = countryCurrencyMap[selectedCountry] || '$';
                formData.country = selectedCountry;
                formData.currencySymbol = currencySymbol;
    
                if (currencySymbolSpan) {
                    currencySymbolSpan.innerText = currencySymbol;
                }
    
                // The state input is displayed for all countries, no changes needed here
                // If you have any special logic for a particular country, you can add it here
            });
        }
    });

    // Automatically copy street_address to trading_address if checkbox is checked
    document.getElementById('toggle-checkbox').addEventListener('change', function () {
        const streetAddress = document.getElementById('street_address').value.trim();
        const tradingInput = document.getElementById('trading_address');

        if (this.checked) {
            tradingInput.value = streetAddress;
            tradingInput.setAttribute('readonly', true); // optional: prevent manual change
        } else {
            tradingInput.value = '';
            tradingInput.removeAttribute('readonly');
        }
    });

    

    const allFields = document.querySelectorAll('input, select, textarea');
    allFields.forEach(input => {
        const validate = () => {
            const errorId = input.dataset.errorId;
            const errorContainer = errorId ? document.getElementById(errorId) : null;
            const value = input.value.trim();

            if (!errorContainer) return;

            if (input.id === 'company-url') {
                const urlPattern = /^(https?:\/\/)?([\w\d-]+\.)+[\w\d]{2,}(\/.*)?$/i;
                if (!value) {
                    errorContainer.innerText = "Please enter the company URL!";
                } else if (!urlPattern.test(value)) {
                    errorContainer.innerText = "Please enter a valid URL!";
                } else {
                    errorContainer.innerText = "";
                }
            } else if (input.id === 'annual-turnover') {
                const numericPattern = /^[0-9]+$/;
                if (!value) {
                    errorContainer.innerText = "Please enter the annual turnover!";
                } else if (!numericPattern.test(value)) {
                    errorContainer.innerText = "Annual turnover must be a number!";
                } else {
                    errorContainer.innerText = "";
                }
            } else if (input.id === 'email') {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!value) {
                    errorContainer.innerText = "Please enter your email!";
                } else if (!emailPattern.test(value)) {
                    errorContainer.innerText = "Please enter a valid email address!";
                } else {
                    errorContainer.innerText = "";
                }
            } else {
                errorContainer.innerText = value ? "" : (input.placeholder || "This field is required");
            }

            if (errorContainer.innerText.trim() !== "") {
                errorContainer.classList.add("error-message");
            } else {
                errorContainer.classList.remove("error-message");
            }
        };

        input.addEventListener('input', validate);
        input.addEventListener('blur', validate); // ✅ blur works here
    });
});

// document.addEventListener('DOMContentLoaded', () => {
//     const countryOptions = document.querySelectorAll('.country-option');
//     const ngStateSelect = document.getElementById('ng-state-select');
//     const genericStateInput = document.getElementById('generic-state-input');
//     const form = document.getElementById('countryForm'); // Replace with your form ID

//     // Nigerian States List
//     const nigerianStates = [
//         'Abia', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River',
//         'Delta', 'Ebonyi', 'Edo', 'Ekiti', 'Enugu', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
//         'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau',
//         'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
//     ];

//     // Function to populate Nigerian states in the dropdown
//     function populateStateOptions() {
//         const selectOptions = document.getElementById('select-options');
//         selectOptions.innerHTML = ''; // Clear previous options
        
//         nigerianStates.forEach(state => {
//             const option = document.createElement('div');
//             option.classList.add('select-option');
//             option.textContent = state;
//             option.addEventListener('click', () => {
//                 document.getElementById('selected-state').textContent = state;
//                 ngStateSelect.classList.remove('hidden');
//                 document.getElementById('error-selected-state').textContent = ''; // Clear error
//             });
//             selectOptions.appendChild(option);
//         });
//     }

//     // Listen for country selection
//     countryOptions.forEach(option => {
//         option.addEventListener('click', (e) => {
//             const selectedCountryCode = e.currentTarget.getAttribute('data-code');
//             const selectedCountryName = e.currentTarget.getAttribute('data-name');

//             // Update the UI to show the correct state input based on selected country
//             if (selectedCountryCode === 'NG') {
//                 ngStateSelect.classList.remove('hidden');
//                 genericStateInput.classList.add('hidden');
//                 populateStateOptions();
//             } else {
//                 ngStateSelect.classList.add('hidden');
//                 genericStateInput.classList.remove('hidden');
//             }
//         });
//     });

//     // Validate form before submission
//     form.addEventListener('submit', (e) => {
//         const selectedState = document.getElementById('selected-state').textContent;

//         if (selectedState === 'Select a state') {
//             e.preventDefault(); // Prevent form submission
//             document.getElementById('error-state').textContent = 'Please select a state.'; // Show error message
//         } else {
//             document.getElementById('error-state').textContent = ''; // Clear any previous error
//         }
//     });
// });


// ✅ FIXED submitOtp
// function submitOtp(formData) {
//     return fetch('/verify-email', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
//         },
//         body: JSON.stringify({ email: formData.email, otp: formData.otp })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success) {
//             showToast("Email verified successfully!");
//             return true;
//         } else {
//             showToast(data.message || "Invalid OTP. Please try again.");
//             return false;
//         }
//     })
//     .catch(error => {
//         console.error('OTP Verification Error:', error);
//         showToast("Server error while verifying OTP.");
//         return false;
//     });
// }





// document.addEventListener('DOMContentLoaded', () => {
//     const steps = document.querySelectorAll('ol li');
//     let currentStep = 0;
//     let formData = {};

//     const saveStepDataUrl = window.saveStepDataUrl;

//     const countryCurrencyMap = {
//         'NG': '₦', 'US': '$', 'GB': '£', 'CA': '$', 'AU': '$', 'DE': '€', 'FR': '€',
//         'IN': '₹', 'CN': '¥', 'JP': '¥', 'ZA': 'R', 'BR': 'R$', 'RU': '₽',
//         'KE': 'KSh', 'GH': '₵', 'AE': 'د.إ', 'SA': 'ر.س', 'KR': '₩', 'MX': '$', 'EG': '£',
//     };

//     const currencySymbolSpan = document.getElementById('currency-symbol');
//     if (currencySymbolSpan) {
//         currencySymbolSpan.innerText = '';
//     }

//     function showToast(message) {
//         const toast = document.getElementById('toast');
//         toast.innerText = message;
//         toast.classList.remove('hidden');
//         toast.classList.add('show');
//         setTimeout(() => {
//             toast.classList.remove('show');
//             setTimeout(() => {
//                 toast.classList.add('hidden');
//             }, 500);
//         }, 3000);
//     }

//     function showStep(stepIndex) {
//         steps.forEach((step, index) => {
//             step.classList.toggle('hidden', index !== stepIndex);
//         });
//     }

//     showStep(currentStep);

//     async function submitFormData(formData) {
//         try {
//             const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
//             const response = await fetch(saveStepDataUrl, {
//                 method: 'POST',
//                 headers: {
//                     'Content-Type': 'application/json',
//                     'X-CSRF-TOKEN': csrfToken
//                 },
//                 body: JSON.stringify(formData),
//             });
    
//             if (response.redirected) {
//                 // If Laravel returned a redirect, manually follow it
//                 window.location.href = response.url;
//                 return;
//             }
    
//             const responseText = await response.text();
//             console.log('Response:', responseText);
    
//             if (response.headers.get('Content-Type')?.includes('application/json')) {
//                 const result = JSON.parse(responseText);
//                 console.log('Form data saved:', result);
//                 showToast('Form submitted successfully!');
//             } else {
//                 console.error('Unexpected response type, expected JSON.');
//                 console.error('Received:', responseText);
//             }
//         } catch (error) {
//             console.error('Error submitting form:', error);
//         }
//     }
    

//     async function validateStep(step) {
//         let hasError = false;

//         if (step === 0) {
//             const selectedCountry = document.getElementById('selectedCountryId').value;
//             const countryError = document.getElementById('countryErrorMessage');
//             if (!selectedCountry) {
//                 countryError.innerText = "Please select a country!";
//                 countryError.classList.add("error-message");
//                 hasError = true;
//             } else {
//                 countryError.innerText = "";
//                 countryError.classList.remove("error-message");
//                 formData.country = selectedCountry;
//                 formData.currencySymbol = countryCurrencyMap[selectedCountry] || '$';
//             }
//         }

//         if (step === 1) {
//             const fields = [
//                 { id: 'business-name', errorId: 'businessNameError', message: 'Please enter a business name!' },
//                 { id: 'registration-number', errorId: 'error-registration_number', message: 'Please enter a registration number!' },
//                 { id: 'business-type', errorId: 'error-business_type', message: 'Please enter the business type!' },
//                 { id: 'company-url', errorId: 'error-company_url', message: 'Please enter the company URL!' },
//                 { id: 'industry', errorId: 'error-industry', message: 'Please select an industry!' },
//                 { id: 'annual-turnover', errorId: 'error-annual_turnover', message: 'Please enter the annual turnover!' }
//             ];

//             fields.forEach(field => {
//                 const input = document.getElementById(field.id);
//                 const errorContainer = document.getElementById(field.errorId);

//                 if (!input.value.trim()) {
//                     errorContainer.innerText = field.message;
//                     errorContainer.classList.add("error-message");
//                     hasError = true;
//                 } else {
//                     if (field.id === 'company-url') {
//                         const urlPattern = /^(https?:\/\/)?([\w\d-]+\.)+[\w\d]{2,}(\/.*)?$/i;
//                         if (!urlPattern.test(input.value.trim())) {
//                             errorContainer.innerText = "Please enter a valid URL!";
//                             errorContainer.classList.add("error-message");
//                             hasError = true;
//                             return;
//                         }
//                     }
//                     if (field.id === 'annual-turnover') {
//                         const numericPattern = /^[0-9]+$/;
//                         if (!numericPattern.test(input.value.trim())) {
//                             errorContainer.innerText = "Annual turnover must be a number!";
//                             errorContainer.classList.add("error-message");
//                             hasError = true;
//                             return;
//                         }
//                     }
//                     errorContainer.innerText = "";
//                     errorContainer.classList.remove("error-message");
//                     formData[field.id] = input.value.trim();
//                 }
//             });

//             const dayInput = document.getElementById('day');
//             const monthInput = document.getElementById('month');
//             const yearInput = document.getElementById('year');
//             const errorDateContainer = document.getElementById('error-incorporation_date');

//             const day = parseInt(dayInput.value.trim());
//             const month = parseInt(monthInput.value.trim());
//             const year = parseInt(yearInput.value.trim());

//             let dateErrorMessage = '';

//             if (isNaN(day) || day < 1 || day > 31) {
//                 dateErrorMessage = 'Please enter a valid day (1-31).';
//             } else if (isNaN(month) || month < 1 || month > 12) {
//                 dateErrorMessage = 'Please select a valid month.';
//             } else if (isNaN(year) || year < 1900 || year > new Date().getFullYear()) {
//                 dateErrorMessage = 'Please enter a valid year.';
//             }

//             if (dateErrorMessage) {
//                 errorDateContainer.innerText = dateErrorMessage;
//                 errorDateContainer.classList.add("error-message");
//                 hasError = true;
//             } else {
//                 errorDateContainer.innerText = "";
//                 errorDateContainer.classList.remove("error-message");
//                 formData['day'] = day;
//                 formData['month'] = month;
//                 formData['year'] = year;
//             }

//             const annualTurnoverInput = document.getElementById('annual-turnover');
//             if (annualTurnoverInput && currencySymbolSpan) {
//                 currencySymbolSpan.innerText = formData.currencySymbol || '$';
//             }
//         }

//         if (step === 2) {
//             const fields = [
//                 { id: 'street_address', errorId: 'error-street-address', message: 'Please enter your street address!' },
//                 { id: 'city', errorId: 'error-city', message: 'Please enter your city!' },
//                 { id: 'trading_address', errorId: 'error-trading-address', message: 'Please enter your Trading address!' },
//                 { id: 'message', errorId: 'error-nature-of-business', message: 'Please enter the nature of your business!' }
//             ];

//             fields.forEach(field => {
//                 const input = document.getElementById(field.id);
//                 const errorContainer = document.getElementById(field.errorId);

//                 if (!input.value.trim()) {
//                     errorContainer.innerText = field.message;
//                     errorContainer.classList.add("error-message");
//                     hasError = true;
//                 } else {
//                     errorContainer.innerText = "";
//                     errorContainer.classList.remove("error-message");
//                     formData[field.id] = input.value.trim();
//                 }
//             });
//         }

//         if (step === 3) {
//             const fields = [
//                 { id: 'email', errorId: 'error-email', message: 'Please enter your email!' },
//                 { id: 'state', errorId: 'error-state', message: 'Please enter your state!' },
//                 { id: 'password', errorId: 'error-password', message: 'Please enter your password!' },
//                 { id: 'confirm_password', errorId: 'error-confirm-password', message: 'Please confirm your password!' }
//             ];

//             fields.forEach(field => {
//                 const input = document.getElementById(field.id);
//                 const errorContainer = document.getElementById(field.errorId);

//                 if (!input.value.trim()) {
//                     errorContainer.innerText = field.message;
//                     errorContainer.classList.add("error-message");
//                     hasError = true;
//                 } else {
//                     errorContainer.innerText = "";
//                     errorContainer.classList.remove("error-message");
//                     formData[field.id] = input.value.trim();
//                 }
//             });

//             const password = document.getElementById('password').value.trim();
//             const confirmPassword = document.getElementById('confirm_password').value.trim();

//             if (password && confirmPassword && password !== confirmPassword) {
//                 const confirmPasswordError = document.getElementById('error-confirm-password');
//                 confirmPasswordError.innerText = "Passwords do not match!";
//                 confirmPasswordError.classList.add("error-message");
//                 hasError = true;
//             }
//         }

//         return { hasError };
//     }

//     steps.forEach((step, index) => {
//         const continueButton = step.querySelector('.btn-continue');
//         const backButton = step.querySelector('.btn-back');

//         if (continueButton) {
//             continueButton.addEventListener('click', async (event) => {
//                 event.preventDefault();
//                 const { hasError } = await validateStep(currentStep);
//                 if (hasError) return;

//                 if (currentStep === 3) {
//                     await submitFormData(formData);
//                 }

//                 currentStep++;
//                 showStep(currentStep);
//             });
//         }

//         if (backButton) {
//             backButton.addEventListener('click', (event) => {
//                 event.preventDefault();
//                 if (currentStep > 0) {
//                     currentStep--;
//                     showStep(currentStep);
//                 }
//             });
//         }
//     });

//     const selectedCountryInput = document.getElementById('selectedCountryId');
//     if (selectedCountryInput) {
//         selectedCountryInput.addEventListener('change', (event) => {
//             const selectedCountry = event.target.value;
//             const currencySymbol = countryCurrencyMap[selectedCountry] || '$';
//             formData.country = selectedCountry;
//             formData.currencySymbol = currencySymbol;

//             if (currencySymbolSpan) {
//                 currencySymbolSpan.innerText = currencySymbol;
//             }
//         });
//     }
// });