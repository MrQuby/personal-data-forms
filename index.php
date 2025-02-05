<!DOCTYPE html>
<html>
<head>
    <title>Personal Information Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="form-header">
        <h1>Personal Data</h1>
        <p>Please fill in all required fields marked with an asterisk (*)</p>
    </div>

    <form action="process_form.php" method="post" id="personalInfoForm" onsubmit="return validateForm()">
        <div class="form-content">
            <!-- Progress bar -->
            <div class="progress-bar">
                <div class="progress" id="formProgress"></div>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h2 class="form-section-title">Personal Information</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="last_name">Last Name *</label>
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
                        <label for="first_name">First Name *</label>
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
                        <label for="middle_initial">Middle Initial *</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control" id="middle_initial" name="middle_initial" 
                                maxlength="1" pattern="[A-Za-z]" placeholder="M.I."
                                oninput="validateName(this); updateProgress()"
                                onkeydown="return !/[0-9]/.test(event.key)">
                        </div>
                        <div class="error-message" id="middle_initial_error">Please enter a single letter</div>
                    </div>
                </div>
            </div>

            <!-- Additional Details Section -->
            <div class="form-section">
                <h2 class="form-section-title">Additional Details</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth *</label>
                        <div class="input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                            required onchange="calculateAge()" max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                        </div>
                        <div id="age_display" class="requirements"></div>
                    </div>

                    <div class="form-group">
                        <label>Sex *</label>
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
                        <label for="civil_status">Civil Status *</label>
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
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="form-section">
                <h2 class="form-section-title">Contact Information</h2>
                <div class="form-grid">
                    <div class="form-group span-3">
                        <label for="home_address">Home Address *</label>
                        <div class="input-group">
                            <i class="fas fa-home"></i>
                            <input type="text" class="form-control" id="home_address" name="home_address" 
                                required placeholder="Enter your complete address"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mobile_number">Mobile Number *</label>
                        <div class="input-group">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" class="form-control" id="mobile_number" name="mobile_number" 
                                required pattern="\+?[\d\s-]{10,}" placeholder="+63 XXX XXX XXXX"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email_address">Email Address *</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control" id="email_address" name="email_address" 
                                required placeholder="your.email@example.com"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telephone_number">Telephone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="tel" class="form-control" id="telephone_number" name="telephone_number" 
                                placeholder="(02) XXXX-XXXX"
                                oninput="updateProgress()">
                        </div>
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
                                required pattern="\d{9,12}" placeholder="XXX-XXX-XXX"
                                oninput="updateProgress()">
                                </div>
                        <div class="requirements">Format: 9-12 digits</div>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality *</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <input type="text" class="form-control" id="nationality" name="nationality" 
                                required pattern="[A-Za-z\s-]+" placeholder="Enter your nationality"
                                oninput="updateProgress()">
                        </div>
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
                </div>
            </div>

            <!-- Family Information Section -->
            <div class="form-section">
                <h2 class="form-section-title">Family Information</h2>
                <div class="form-grid">
                    <div class="form-group span-2">
                        <label for="fathers_name">Father's Name *</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control" id="fathers_name" name="fathers_name" 
                                required pattern="[A-Za-z\s\.,]+" placeholder="Enter your father's complete name"
                                oninput="updateProgress()">
                        </div>
                    </div>

                    <div class="form-group span-2">
                        <label for="mothers_maiden_name">Mother's Maiden Name *</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control" id="mothers_maiden_name" name="mothers_maiden_name" 
                                required pattern="[A-Za-z\s\.,]+" placeholder="Enter your mother's maiden name"
                                oninput="updateProgress()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="submit-section">
            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane"></i> Submit Information
            </button>
        </div>
    </form>
</div>

<script>
function validateName(input) {
    const nameRegex = /^[A-Za-z\s-]+$/;
    const value = input.value;
    const errorElement = document.getElementById(input.id + '_error');
    
    if (!nameRegex.test(value) || /\d/.test(value)) {
        input.setCustomValidity("Names cannot contain numbers");
        errorElement.style.display = 'block';
        return false;
    } else {
        input.setCustomValidity("");
        errorElement.style.display = 'none';
        return true;
    }
}
function calculateAge() {
    const dob = new Date(document.getElementById('date_of_birth').value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    const ageDisplay = document.getElementById('age_display');
    ageDisplay.textContent = `Age: ${age} years`;
    ageDisplay.style.color = age >= 0 ? 'var(--success-color)' : 'var(--error-color)';
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
    const form = document.getElementById('personalInfoForm');
    const requiredFields = form.querySelectorAll('[required]');
    const totalFields = requiredFields.length;
    let filledFields = 0;

    requiredFields.forEach(field => {
        if (field.value.trim() !== '') {
            filledFields++;
        }
    });

    const progress = (filledFields / totalFields) * 100;
    document.getElementById('formProgress').style.width = `${progress}%`;
}

function validateForm() {
    const form = document.getElementById('personalInfoForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        const errorDiv = document.getElementById(`${input.id}_error`);
        const inputGroup = input.closest('.input-group');

        // Remove previous error states
        input.classList.remove('error');
        if (inputGroup) {
            inputGroup.classList.remove('error');
        }

        // Check if empty or invalid
        if (!input.value.trim() || (input.validity && !input.validity.valid)) {
            input.classList.add('error');
            if (inputGroup) {
                inputGroup.classList.add('error');
            }
            if (errorDiv) {
                errorDiv.style.display = 'block';
            }
            isValid = false;
        } else {
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
        }
    });

    // Prevent spaces-only input
    const textInputs = form.querySelectorAll('input[type="text"]');
    textInputs.forEach(input => {
        if (input.value.trim() === '' && input.required) {
            input.classList.add('error');
            const errorDiv = document.getElementById(`${input.id}_error`);
            if (errorDiv) {
                errorDiv.style.display = 'block';
            }
            isValid = false;
        }
    });

    return isValid;
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
});
</script>

</body>
</html>