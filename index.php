<?php
$countries = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda",
    "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain",
    "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan",
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria",
    "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada",
    "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros",
    "Congo", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark",
    "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador",
    "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji",
    "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece",
    "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras",
    "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel",
    "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati",
    "Korea, North", "Korea, South", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos",
    "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania",
    "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta",
    "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova",
    "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia",
    "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria",
    "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama",
    "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
    "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia",
    "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe",
    "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore",
    "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Sudan",
    "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
    "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga",
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda",
    "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay",
    "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen",
    "Zambia", "Zimbabwe"
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Personal Information Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script>
        function validateName(input) {
            const value = input.value.trim();
            const errorElement = document.getElementById(input.id + '_error');
            
            // Check for empty or spaces-only input
            if (!value) {
                input.style.borderColor = 'red';
                if (errorElement) {
                    errorElement.textContent = 'This field is required';
                    errorElement.style.display = 'block';
                    errorElement.style.color = 'red';
                }
                return false;
            }
            
            // Check for valid characters (letters, spaces, and hyphens only)
            if (!/^[A-Za-z\s-]+$/.test(value)) {
                input.style.borderColor = 'red';
                if (errorElement) {
                    errorElement.textContent = 'Please enter only letters, spaces, or hyphens';
                    errorElement.style.display = 'block';
                    errorElement.style.color = 'red';
                }
                return false;
            }
            
            // If validation passes, clear the error state
            input.style.borderColor = '';
            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }
            return true;
        }

        function calculateAge() {
            const dobInput = document.getElementById('date_of_birth');
            const dob = new Date(dobInput.value);
            const today = new Date();
            
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            const ageDisplay = document.getElementById('age_display');
            if (age < 0) {
                ageDisplay.textContent = 'Invalid date of birth';
                ageDisplay.style.color = 'red';
                dobInput.setCustomValidity('Please enter a valid date of birth');
            } else {
                ageDisplay.textContent = `Age: ${age} years old`;
                ageDisplay.style.color = '#666';
                dobInput.setCustomValidity('');
            }
            
            // Store age in hidden field for form submission
            document.getElementById('calculated_age').value = age;
            updateProgress();
        }

        function handleCivilStatus() {
            const civilStatus = document.getElementById('civil_status');
            const othersContainer = document.getElementById('others_container');
            
            if (civilStatus.value === 'others') {
                othersContainer.style.display = 'block';
                document.getElementById('others_specify').required = true;
            } else {
                othersContainer.style.display = 'none';
                document.getElementById('others_specify').required = false;
            }
            updateProgress();
        }

        function updateProgress() {
            const form = document.getElementById('personalDataForm');
            const requiredFields = form.querySelectorAll('[required]');
            const totalFields = requiredFields.length;
            let filledFields = 0;
            
            requiredFields.forEach(field => {
                if (field.type === 'radio') {
                    if (document.querySelector(`input[name="${field.name}"]:checked`)) {
                        filledFields++;
                    }
                } else if (field.value.trim() !== '') {
                    filledFields++;
                }
            });
            
            const progress = Math.round((filledFields / totalFields) * 100);
            document.getElementById('progressText').textContent = `${progress}%`;
            document.getElementById('formProgress').style.width = `${progress}%`;
        }

        // Add event listeners when document loads
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate initial age if date is already set
            const dobInput = document.getElementById('date_of_birth');
            if (dobInput.value) {
                calculateAge();
            }
            
            // Add input event listeners for name fields
            const nameFields = ['last_name', 'first_name', 'middle_initial'];
            nameFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('input', function() {
                        validateName(this);
                        updateProgress();
                    });
                    
                    input.addEventListener('blur', function() {
                        validateName(this);
                    });
                }
            });
            
            // Add input event listeners for all text fields to prevent spaces-only input
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.addEventListener('input', function() {
                    if (!this.value.trim() && this.required) {
                        this.setCustomValidity('This field cannot be empty or contain only spaces');
                    } else {
                        this.setCustomValidity('');
                    }
                    updateProgress();
                });
            });
        });

        function validateForm() {
            const form = document.getElementById('personalDataForm');
            const inputs = form.querySelectorAll('input[required], select[required]');
            let isValid = true;
            
            // Clear all previous errors first
            document.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
                error.style.display = 'none';
            });
            document.querySelectorAll('input, select').forEach(input => {
                input.style.borderColor = '';
            });
            document.querySelector('.radio-group').classList.remove('error');

            // Validate date of birth specifically
            const dobInput = document.getElementById('date_of_birth');
            const dobError = document.getElementById('date_of_birth_error');
            if (!dobInput.value) {
                isValid = false;
                dobInput.style.borderColor = 'red';
                if (dobError) {
                    dobError.textContent = 'Please select your date of birth';
                    dobError.style.display = 'block';
                    dobError.style.color = 'red';
                }
            }

            // Validate civil status specifically
            const civilStatusInput = document.getElementById('civil_status');
            const civilStatusError = document.getElementById('civil_status_error');
            if (!civilStatusInput.value) {
                isValid = false;
                civilStatusInput.style.borderColor = 'red';
                if (civilStatusError) {
                    civilStatusError.textContent = 'Please select your civil status';
                    civilStatusError.style.display = 'block';
                    civilStatusError.style.color = 'red';
                }
            }

            // Validate mobile number specifically
            const mobileInput = document.getElementById('mobile_number');
            const mobileError = document.getElementById('mobile_number_error');
            if (!mobileInput.value.trim()) {
                isValid = false;
                mobileInput.style.borderColor = 'red';
                if (mobileError) {
                    mobileError.textContent = 'Please enter your mobile number';
                    mobileError.style.display = 'block';
                    mobileError.style.color = 'red';
                }
            } else if (!mobileInput.value.match(/^\+?[\d\s-]{10,}$/)) {
                isValid = false;
                mobileInput.style.borderColor = 'red';
                if (mobileError) {
                    mobileError.textContent = 'Please enter a valid mobile number';
                    mobileError.style.display = 'block';
                    mobileError.style.color = 'red';
                }
            }

            // Validate sex radio buttons specifically
            const sexRadioGroup = document.querySelector('.radio-group');
            const sexError = document.getElementById('sex_error');
            const sexSelected = form.querySelector('input[name="sex"]:checked');
            
            if (!sexSelected) {
                isValid = false;
                sexRadioGroup.classList.add('error');
                if (sexError) {
                    sexError.textContent = 'Please select your sex';
                    sexError.style.display = 'block';
                    sexError.style.color = 'red';
                }
            }

            // Validate place of birth fields
            const pobFields = ['pob_barangay', 'pob_city', 'pob_province', 'pob_country', 'pob_zip_code'];
            pobFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const errorElement = document.getElementById(fieldId + '_error');
                if (input && input.required && !input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                    if (errorElement) {
                        errorElement.textContent = 'This field is required';
                        errorElement.style.display = 'block';
                        errorElement.style.color = 'red';
                    }
                }
            });

            // Validate home address fields
            const addressFields = ['unit_no', 'house_no', 'street', 'barangay', 'city', 'province', 'country', 'zip_code'];
            addressFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                const errorElement = document.getElementById(fieldId + '_error');
                if (input && input.required && !input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = 'red';
                    if (errorElement) {
                        errorElement.textContent = 'This field is required';
                        errorElement.style.display = 'block';
                        errorElement.style.color = 'red';
                    }
                }
            });

            // Validate nationality
            const nationalityInput = document.getElementById('nationality');
            const nationalityError = document.getElementById('nationality_error');
            if (!nationalityInput.value.trim()) {
                isValid = false;
                nationalityInput.style.borderColor = 'red';
                if (nationalityError) {
                    nationalityError.textContent = 'Please enter your nationality';
                    nationalityError.style.display = 'block';
                    nationalityError.style.color = 'red';
                }
            } else if (!/^[A-Za-z\s-]+$/.test(nationalityInput.value.trim())) {
                isValid = false;
                nationalityInput.style.borderColor = 'red';
                if (nationalityError) {
                    nationalityError.textContent = 'Please enter only letters, spaces, or hyphens';
                    nationalityError.style.display = 'block';
                    nationalityError.style.color = 'red';
                }
            }

            // Validate email address
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email_error');
            if (emailInput.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
                isValid = false;
                emailInput.style.borderColor = 'red';
                if (emailError) {
                    emailError.textContent = 'Please enter a valid email address (e.g., example@domain.com)';
                    emailError.style.display = 'block';
                    emailError.style.color = 'red';
                }
            }

            // Validate telephone number only if it has a value
            const telephoneInput = document.getElementById('telephone_number');
            const telephoneError = document.getElementById('telephone_number_error');
            if (telephoneInput.value.trim()) {
                if (!/^\d{7}$/.test(telephoneInput.value.trim())) {
                    isValid = false;
                    telephoneInput.style.borderColor = 'red';
                    if (telephoneError) {
                        telephoneError.textContent = 'Please enter exactly 7 digits';
                        telephoneError.style.display = 'block';
                        telephoneError.style.color = 'red';
                    }
                }
            }

            // Validate all other inputs
            inputs.forEach(input => {
                // Skip the fields we already validated specifically
                if (['date_of_birth', 'civil_status', 'mobile_number', 'sex', 'pob_barangay', 'pob_city', 'pob_province', 'pob_country', 'pob_zip_code', 'unit_no', 'house_no', 'street', 'barangay', 'city', 'province', 'country', 'zip_code', 'nationality', 'email', 'telephone_number'].includes(input.id)) {
                    return;
                }

                const errorElement = document.getElementById(input.id + '_error');
                let errorMessage = '';

                if (input.type === 'radio') {
                    const radioGroup = form.querySelector(`input[name="${input.name}"]:checked`);
                    if (!radioGroup) {
                        isValid = false;
                        const groupError = document.getElementById(`${input.name}_error`);
                        if (groupError) {
                            groupError.textContent = 'Please select an option';
                            groupError.style.display = 'block';
                            groupError.style.color = 'red';
                        }
                    }
                } else if (input.type === 'select-one' && !input.value) {
                    isValid = false;
                    errorMessage = 'Please select an option';
                    input.style.borderColor = 'red';
                } else if (input.type === 'email' && input.value.trim()) {
                    if (!input.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                        isValid = false;
                        errorMessage = 'Please enter a valid email address';
                        input.style.borderColor = 'red';
                    }
                } else if (!input.value.trim()) {
                    isValid = false;
                    errorMessage = 'This field is required';
                    input.style.borderColor = 'red';
                }

                if (errorMessage && errorElement) {
                    errorElement.textContent = errorMessage;
                    errorElement.style.display = 'block';
                    errorElement.style.color = 'red';
                }
            });

            return isValid;
        }

        // Add event listeners when document loads
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('personalDataForm');
            
            // Form submit handler
            form.addEventListener('submit', function(event) {
                if (!validateForm()) {
                    event.preventDefault();
                    // Scroll to the first error
                    const firstError = document.querySelector('.error-message[style*="block"]');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });

            // Add specific validation for date of birth, civil status, and mobile number
            const dobInput = document.getElementById('date_of_birth');
            const civilStatusInput = document.getElementById('civil_status');
            const mobileInput = document.getElementById('mobile_number');

            [dobInput, civilStatusInput, mobileInput].forEach(input => {
                if (!input) return;
                
                input.addEventListener('blur', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (!this.value.trim()) {
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = this.type === 'date' ? 
                                'Please select your date of birth' : 
                                (this.id === 'civil_status' ? 
                                    'Please select your civil status' : 
                                    'Please enter your mobile number');
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    } else {
                        // Additional validation for mobile number
                        if (this.id === 'mobile_number' && !this.value.match(/^\+?[\d\s-]{10,}$/)) {
                            this.style.borderColor = 'red';
                            if (errorElement) {
                                errorElement.textContent = 'Please enter a valid mobile number';
                                errorElement.style.display = 'block';
                                errorElement.style.color = 'red';
                            }
                        } else {
                            this.style.borderColor = '';
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.style.display = 'none';
                            }
                        }
                    }
                });

                // Clear error on input
                input.addEventListener('input', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (this.value.trim()) {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                });
            });

            // Add event listeners for radio buttons
            document.querySelectorAll('input[name="sex"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const radioGroup = this.closest('.radio-group');
                    const errorElement = document.getElementById('sex_error');
                    if (radioGroup) {
                        radioGroup.classList.remove('error');
                    }
                    if (errorElement) {
                        errorElement.textContent = '';
                        errorElement.style.display = 'none';
                    }
                    updateProgress();
                });
            });

            // Add validation for place of birth fields
            const pobFields = ['pob_barangay', 'pob_city', 'pob_province', 'pob_country', 'pob_zip_code'];
            pobFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('blur', function() {
                        const errorElement = document.getElementById(this.id + '_error');
                        if (this.required && !this.value.trim()) {
                            this.style.borderColor = 'red';
                            if (errorElement) {
                                errorElement.textContent = 'This field is required';
                                errorElement.style.display = 'block';
                                errorElement.style.color = 'red';
                            }
                        } else {
                            this.style.borderColor = '';
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.style.display = 'none';
                            }
                        }
                    });

                    input.addEventListener('input', function() {
                        const errorElement = document.getElementById(this.id + '_error');
                        if (this.value.trim()) {
                            this.style.borderColor = '';
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.style.display = 'none';
                            }
                        }
                        updateProgress();
                    });
                }
            });

            // Add validation for home address fields
            const addressFields = ['unit_no', 'house_no', 'street', 'barangay', 'city', 'province', 'country', 'zip_code'];
            addressFields.forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('blur', function() {
                        const errorElement = document.getElementById(this.id + '_error');
                        if (this.required && !this.value.trim()) {
                            this.style.borderColor = 'red';
                            if (errorElement) {
                                errorElement.textContent = 'This field is required';
                                errorElement.style.display = 'block';
                                errorElement.style.color = 'red';
                            }
                        } else {
                            this.style.borderColor = '';
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.style.display = 'none';
                            }
                        }
                    });

                    input.addEventListener('input', function() {
                        const errorElement = document.getElementById(this.id + '_error');
                        if (this.value.trim()) {
                            this.style.borderColor = '';
                            if (errorElement) {
                                errorElement.textContent = '';
                                errorElement.style.display = 'none';
                            }
                        }
                        updateProgress();
                    });
                }
            });

            // Add validation for nationality
            const nationalityInput = document.getElementById('nationality');
            if (nationalityInput) {
                nationalityInput.addEventListener('blur', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (!this.value.trim()) {
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = 'Please enter your nationality';
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    } else if (!/^[A-Za-z\s-]+$/.test(this.value.trim())) {
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = 'Please enter only letters, spaces, or hyphens';
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    } else {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                });

                nationalityInput.addEventListener('input', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (this.value.trim() && /^[A-Za-z\s-]+$/.test(this.value.trim())) {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                    updateProgress();
                });
            }

            // Validate email address
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email_error');
            if (emailInput) {
                emailInput.addEventListener('blur', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (this.value.trim() && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim())) {
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = 'Please enter a valid email address (e.g., example@domain.com)';
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    } else {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                });

                emailInput.addEventListener('input', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (!this.value.trim() || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.value.trim())) {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                    updateProgress();
                });
            }

            // Validate telephone number only if it has a value
            const telephoneInput = document.getElementById('telephone_number');
            if (telephoneInput) {
                telephoneInput.addEventListener('input', function(e) {
                    // Remove any non-digit characters from input
                    this.value = this.value.replace(/\D/g, '');
                    
                    // Limit to 7 digits
                    if (this.value.length > 7) {
                        this.value = this.value.slice(0, 7);
                    }

                    const errorElement = document.getElementById(this.id + '_error');
                    
                    if (!this.value.trim()) {
                        // Clear error immediately when empty
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    } else if (this.value.trim().length === 7) {
                        // Valid when exactly 7 digits
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    } else {
                        // Show error if some digits entered but not 7
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = 'Please enter exactly 7 digits';
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    }
                    updateProgress();
                });

                // Clear validation on backspace/delete to empty
                telephoneInput.addEventListener('keyup', function(e) {
                    if (!this.value.trim()) {
                        const errorElement = document.getElementById(this.id + '_error');
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                });

                telephoneInput.addEventListener('blur', function() {
                    const errorElement = document.getElementById(this.id + '_error');
                    if (!this.value.trim()) {
                        // Clear error when empty
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    } else if (this.value.trim().length !== 7) {
                        // Show error only if not empty and not 7 digits
                        this.style.borderColor = 'red';
                        if (errorElement) {
                            errorElement.textContent = 'Please enter exactly 7 digits';
                            errorElement.style.display = 'block';
                            errorElement.style.color = 'red';
                        }
                    } else {
                        this.style.borderColor = '';
                        if (errorElement) {
                            errorElement.textContent = '';
                            errorElement.style.display = 'none';
                        }
                    }
                });
            }
        });
    </script>
</head>
<body>

<div class="container">
    <div class="form-header">
        <h1>Personal Data</h1>
        <p>Please fill in all required fields marked with an asterisk (*)</p>
    </div>

    <form id="personalDataForm" action="process_form.php" method="POST" novalidate>
        <!-- Add hidden field for calculated age -->
        <input type="hidden" id="calculated_age" name="calculated_age" value="">

        <div class="form-content">
            <!-- Progress bar -->
            <div class="progress-bar">
                <div class="progress" id="formProgress"></div>
                <span id="progressText">0%</span>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h2 class="form-section-title">Personal Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="last_name" name="last_name" required 
                                pattern="[A-Za-z\s-]+" placeholder="Last Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="last_name_error">Please enter a valid last name</div>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="first_name" name="first_name" required 
                                pattern="[A-Za-z\s-]+" placeholder="First Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="first_name_error">Please enter a valid first name</div>
                    </div>

                    <div class="form-group">
                        <label for="middle_initial">Middle Initial</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="middle_initial" name="middle_initial" 
                                required maxlength="1" pattern="[A-Za-z]" placeholder="M.I."
                                oninput="this.value = this.value.toUpperCase(); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="middle_initial_error">Please enter a single letter</div>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <div class="input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                            required onchange="calculateAge()" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                        </div>
                        <div id="age_display" class="requirements"></div>
                        <div class="error-message" id="date_of_birth_error"></div>
                    </div>

                    <div class="form-group">
                        <label>Sex</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" class="radio-input" id="male" name="sex" value="male" required onchange="updateProgress()">
                                <label for="male">Male</label>
                            </div>
                            <div class="radio-item"> 
                                <input type="radio" class="radio-input" id="female" name="sex" value="female" required onchange="updateProgress()">
                                <label for="female">Female</label>
                            </div>
                        </div>
                        <div class="error-message" id="sex_error">Please select your sex</div>
                    </div>

                    <div class="form-group">
                        <label for="civil_status">Civil Status</label>
                        <div class="input-group">
                            <i class="fas fa-ring"></i>
                            <select class="form-control" id="civil_status" name="civil_status" required 
                                onchange="handleCivilStatus(); updateProgress()">
                                <option value="">Select status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="widowed">Widowed</option>
                                <option value="divorced">Legally Separated</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div id="others_container" style="display:none; margin-top:10px;">
                            <div class="input-group">
                                <i class="fas fa-pen"></i>
                                <input type="text" class="form-control" id="others_specify" 
                                    name="others_specify" placeholder="Please specify">
                            </div>
                        </div>
                        <div class="error-message" id="civil_status_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <div class="input-group">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" class="form-control" id="mobile_number" name="mobile_number" 
                                required pattern="\+?[\d\s-]{10,}" placeholder="+63 XXX XXX XXXX"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="mobile_number_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control" id="email" name="email" 
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" 
                                placeholder="example@email.com"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="email_error">Please enter a valid email address</div>
                    </div>

                    <div class="form-group">
                        <label for="telephone_number">Telephone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="tel" class="form-control" id="telephone_number" name="telephone_number" 
                                placeholder="(02) XXXX-XXXX"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="telephone_number_error"></div>
                    </div>
                </div>
            </div>

            <!-- Place of Birth Section -->
            <div class="form-section">
                <h2 class="form-section-title">Place of Birth</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="pob_unit_no">RM/FLR/Unit No. & Bldg. Name</label>
                        <div class="input-group">
                            <i class="fas fa-building"></i>
                            <input type="text" class="form-control" id="pob_unit_no" name="pob_unit_no"
                                placeholder="Room/Floor/Unit Number and Building Name"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_house_no">House/Lot & Blk. No</label>
                        <div class="input-group">
                            <i class="fas fa-home"></i>
                            <input type="text" class="form-control" id="pob_house_no" name="pob_house_no"
                                placeholder="House/Lot and Block Number"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_street">Street Name</label>
                        <div class="input-group">
                            <i class="fas fa-road"></i>
                            <input type="text" class="form-control" id="pob_street" name="pob_street"
                                placeholder="Street Name"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_subdivision">Subdivision</label>
                        <div class="input-group">
                            <i class="fas fa-map-marked-alt"></i>
                            <input type="text" class="form-control" id="pob_subdivision" name="pob_subdivision" 
                                placeholder="Subdivision"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_barangay">Barangay/District/Locality</label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="form-control" id="pob_barangay" name="pob_barangay" required 
                                placeholder="Barangay/District/Locality"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="pob_barangay_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="pob_city">City/Municipality</label>
                        <div class="input-group">
                            <i class="fas fa-city"></i>
                            <input type="text" class="form-control" id="pob_city" name="pob_city" required 
                                placeholder="City/Municipality"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="pob_city_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="pob_province">Province</label>
                        <div class="input-group">
                            <i class="fas fa-map"></i>
                            <input type="text" class="form-control" id="pob_province" name="pob_province" required 
                                placeholder="Province"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="pob_province_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="pob_country">Country</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <select class="form-control" id="pob_country" name="pob_country" required onchange="updateProgress()">
                                <option value="">Select Country</option>
                                <?php
                                foreach($countries as $country) {
                                    echo "<option value=\"" . htmlspecialchars($country) . "\">" . htmlspecialchars($country) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="error-message" id="pob_country_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="pob_zip_code">Zip Code</label>
                        <div class="input-group">
                            <i class="fas fa-mail-bulk"></i>
                            <input type="text" class="form-control" id="pob_zip_code" name="pob_zip_code" required 
                                placeholder="Zip Code" pattern="[0-9]+" 
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="pob_zip_code_error"></div>
                    </div>
                </div>
            </div>

            <!-- Home Address Section -->
            <div class="form-section">
                <h2 class="form-section-title">Home Address</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="unit_no">RM/FLR/Unit No. & Bldg. Name</label>
                        <div class="input-group">
                            <i class="fas fa-building"></i>
                            <input type="text" class="form-control" id="unit_no" name="unit_no" required 
                                placeholder="Room/Floor/Unit Number and Building Name"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="unit_no_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="house_no">House/Lot & Blk. No</label>
                        <div class="input-group">
                            <i class="fas fa-home"></i>
                            <input type="text" class="form-control" id="house_no" name="house_no" required 
                                placeholder="House/Lot and Block Number"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="house_no_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="street">Street Name</label>
                        <div class="input-group">
                            <i class="fas fa-road"></i>
                            <input type="text" class="form-control" id="street" name="street" required 
                                placeholder="Street Name"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="street_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="subdivision">Subdivision</label>
                        <div class="input-group">
                            <i class="fas fa-map-marked-alt"></i>
                            <input type="text" class="form-control" id="subdivision" name="subdivision" 
                                placeholder="Subdivision"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="barangay">Barangay/District/Locality</label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="form-control" id="barangay" name="barangay" required 
                                placeholder="Barangay/District/Locality"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="barangay_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="city">City/Municipality</label>
                        <div class="input-group">
                            <i class="fas fa-city"></i>
                            <input type="text" class="form-control" id="city" name="city" required 
                                placeholder="City/Municipality"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="city_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="province">Province</label>
                        <div class="input-group">
                            <i class="fas fa-map"></i>
                            <input type="text" class="form-control" id="province" name="province" required 
                                placeholder="Province"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="province_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <select class="form-control" id="country" name="country" required onchange="updateProgress()">
                                <option value="">Select Country</option>
                                <?php
                                foreach($countries as $country) {
                                    echo "<option value=\"" . htmlspecialchars($country) . "\">" . htmlspecialchars($country) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="error-message" id="country_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <div class="input-group">
                            <i class="fas fa-mail-bulk"></i>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required 
                                placeholder="Zip Code" pattern="[0-9]+" 
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="zip_code_error"></div>
                    </div>
                </div>
            </div>

            <!-- Government IDs Section -->
            <div class="form-section">
                <h2 class="form-section-title">Government IDs & Other Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="tin">TIN Number</label>
                        <div class="input-group">
                            <i class="fas fa-id-card"></i>
                            <input type="text" class="form-control" id="tin" name="tin" 
                                pattern="\d{9,12}" placeholder="XXX-XXX-XXX"
                                oninput="updateProgress()">
                        </div>
                        <div class="requirements">Format: 9-12 digits</div>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <input type="text" class="form-control" id="nationality" name="nationality" 
                                required pattern="[A-Za-z\s-]+" placeholder="Enter your nationality"
                                oninput="updateProgress()">
                        </div>
                        <div class="error-message" id="nationality_error"></div>
                    </div>

                    <div class="form-group">
                        <label for="religion">Religion</label>
                        <div class="input-group">
                            <i class="fas fa-pray"></i>
                            <input type="text" class="form-control" id="religion" name="religion" 
                                placeholder="Enter your religion"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <h3 class="subsection-title">Father's Name</h3>
                    </div>

                    <div class="form-group">
                        <label for="father_last_name">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control" id="father_last_name" name="father_last_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Father's Last Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="father_last_name_error">Please enter a valid last name</div>
                    </div>

                    <div class="form-group">
                        <label for="father_first_name">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control" id="father_first_name" name="father_first_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Father's First Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="father_first_name_error">Please enter a valid first name</div>
                    </div>

                    <div class="form-group">
                        <label for="father_middle_name">Middle Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control" id="father_middle_name" name="father_middle_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Father's Middle Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="father_middle_name_error">Please enter a valid middle name</div>
                    </div>

                    <div class="form-group full-width">
                        <h3 class="subsection-title">Mother's Maiden Name</h3>
                    </div>

                    <div class="form-group">
                        <label for="mother_last_name">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control" id="mother_last_name" name="mother_last_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Mother's Last Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="mother_last_name_error">Please enter a valid last name</div>
                    </div>

                    <div class="form-group">
                        <label for="mother_first_name">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control" id="mother_first_name" name="mother_first_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Mother's First Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="mother_first_name_error">Please enter a valid first name</div>
                    </div>

                    <div class="form-group">
                        <label for="mother_middle_name">Middle Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control" id="mother_middle_name" name="mother_middle_name" 
                                pattern="[A-Za-z\s-]+" placeholder="Mother's Middle Name"
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="mother_middle_name_error">Please enter a valid middle name</div>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Information
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function updateProgress() {
    const form = document.getElementById('personalDataForm');
    const requiredFields = form.querySelectorAll('[required]');
    const totalFields = requiredFields.length;
    let filledFields = 0;
    
    requiredFields.forEach(field => {
        if (field.type === 'radio') {
            if (document.querySelector(`input[name="${field.name}"]:checked`)) {
                filledFields++;
            }
        } else if (field.value.trim() !== '') {
            filledFields++;
        }
    });
    
    const progress = Math.round((filledFields / totalFields) * 100);
    document.getElementById('progressText').textContent = `${progress}%`;
    document.getElementById('formProgress').style.width = `${progress}%`;
}

// Add input event listeners to all text inputs
document.querySelectorAll('input[type="text"]').forEach(input => {
    input.addEventListener('input', function() {
        // Remove multiple spaces
        this.value = this.value.replace(/\s+/g, ' ');
        // Remove spaces at the beginning
        this.value = this.value.replace(/^\s+/, '');
        // Update form progress
        updateProgress();
    });
});

// Initialize progress bar on page load
document.addEventListener('DOMContentLoaded', updateProgress);

// Add validation on blur for all inputs
document.querySelectorAll('input, select').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.required) {
            const errorDiv = document.getElementById(`${this.id}_error`);
            if (!this.value.trim() || (this.validity && !this.validity.valid)) {
                this.classList.add('error');
                if (errorDiv) errorDiv.style.display = 'block';
            } else {
                this.classList.remove('error');
                if (errorDiv) errorDiv.style.display = 'none';
            }
        }
    });
}
</script>

</body>
</html>