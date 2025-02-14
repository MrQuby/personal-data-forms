<?php
session_start();

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to check if string is empty or only spaces
function is_empty_or_spaces($str) {
    return !isset($str) || trim($str) === '';
}

// Function to format name
function format_name($last_name, $first_name, $middle_initial = '') {
    // Capitalize first letter of each word in names
    $last_name = ucwords(strtolower($last_name));
    $first_name = ucwords(strtolower($first_name));
    $middle_initial = strtoupper($middle_initial);

    $formatted_name = $last_name . ', ' . $first_name;
    
    if (!empty($middle_initial)) {
        // Get first character of middle initial if it's longer
        $middle_initial = substr($middle_initial, 0, 1);
        $formatted_name .= ' ' . $middle_initial . '.';
    }
    
    return $formatted_name;
}

// Function to validate name format
function validate_name($name) {
    return preg_match("/^[A-Za-z\s-]+$/", trim($name));
}

// Function to validate mobile number
function validate_mobile($number) {
    return preg_match("/^\+?[\d\s-]{11,}$/", trim($number));
}

// Function to validate telephone number
function validate_telephone($number) {
    return preg_match("/^\+?[\d\s-]{7,}+$/", trim($number));
}

// Function to validate zip code
function validate_zip($zip) {
    return preg_match("/^\d{4,}$/", trim($zip));
}

// Function to validate TIN
function validate_tin($tin) {
    return preg_match("/^\d{9,12}$/", trim($tin));
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    
    // Store all POST data in session
    $_SESSION['form_data'] = $_POST;
    
    // Validate required fields
    $required_fields = [
        'last_name' => 'Last Name',
        'first_name' => 'First Name',
        'middle_initial' => 'Middle Initial',
        'date_of_birth' => 'Date of Birth',
        'sex' => 'Sex',
        'civil_status' => 'Civil Status',
        'nationality' => 'Nationality',
        // 'email' => 'Email',
        'mobile_number' => 'Mobile Number',
        // Add required place of birth fields
        'pob_street' => 'Place of Birth Street',
        'pob_barangay' => 'Place of Birth Barangay/District/Locality',
        'pob_city' => 'Place of Birth City/Municipality',
        'pob_province' => 'Place of Birth Province',
        'pob_country' => 'Place of Birth Country',
        'pob_zip_code' => 'Place of Birth Zip Code',
        // Add required home address fields
        'unit_no' => 'Home Address Unit No.',
        'house_no' => 'Home Address House/Lot & Blk. No.',
        'street' => 'Home Address Street',
        'barangay' => 'Home Address Barangay/District/Locality',
        'city' => 'Home Address City/Municipality',
        'province' => 'Home Address Province',
        'country' => 'Home Address Country',
        'zip_code' => 'Home Address Zip Code'
    ];

    // Check required fields
    foreach ($required_fields as $field => $label) {
        if (is_empty_or_spaces($_POST[$field] ?? '')) {
            $errors[$field] = "$label is required";
        }
    }

    // Validate names (last name, first name)
    foreach (['last_name', 'first_name'] as $field) {
        if (!empty($_POST[$field]) && !validate_name($_POST[$field])) {
            $errors[$field] = "Please use only letters, spaces, and hyphens for " . strtolower($required_fields[$field]);
        }
    }

    // Validate middle initial
    if (!empty($_POST['middle_initial'])) {
        if (!preg_match("/^[A-Za-z]$/", trim($_POST['middle_initial']))) {
            $errors['middle_initial'] = "Middle initial must be a single letter";
        }
    } else {
        $errors['middle_initial'] = "Middle initial is required";
    }

    // Validate date of birth
    if (!empty($_POST['date_of_birth'])) {
        $dob = new DateTime($_POST['date_of_birth']);
        $today = new DateTime('2025-02-13'); // Using provided current time
        
        if ($dob > $today) {
            $errors['date_of_birth'] = "Date of birth cannot be in the future.";
        } else {
            $age = $today->diff($dob)->y;
            $_SESSION['form_data']['age'] = $age;
            
            if ($age < 18) {
                $errors['date_of_birth'] = "You must be at least 18 years old to submit this form.";
            }
        }
    }

    // Validate email
    if (!empty($_POST['email'])) {
        if (!filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Please enter a valid email address (e.g., example@domain.com).";
        }
    }

    // Validate mobile number
    if (!empty($_POST['mobile_number']) && !validate_mobile($_POST['mobile_number'])) {
        $errors['mobile_number'] = "Please enter a valid mobile number (e.g., 09XX XXX XXXX).";
    }

    // Validate telephone number (if provided)
    if (!empty($_POST['telephone_number']) && !validate_telephone($_POST['telephone_number'])) {
        $errors['telephone_number'] = "Please enter a valid telephone number (e.g., XXXX-XXXX).";
    }

    // Validate nationality
    if (!empty($_POST['nationality']) && !validate_name($_POST['nationality'])) {
        $errors['nationality'] = "Please use only letters, spaces, and hyphens for nationality.";
    }

    // Validate zip codes
    if (!empty($_POST['zip_code']) && !validate_zip($_POST['zip_code'])) {
        $errors['zip_code'] = "Please enter a valid zip code (at least 4 digits).";
    }
    if (!empty($_POST['pob_zip_code']) && !validate_zip($_POST['pob_zip_code'])) {
        $errors['pob_zip_code'] = "Please enter a valid zip code (at least 4 digits).";
    }

    // Validate TIN (if provided)
    if (!empty($_POST['tin']) && !validate_tin($_POST['tin'])) {
        $errors['tin'] = "Please enter a valid TIN number (9-12 digits).";
    }

    // Validate civil status others
    if (isset($_POST['civil_status']) && $_POST['civil_status'] === 'others') {
        if (is_empty_or_spaces($_POST['others_specify'] ?? '')) {
            $errors['others_specify'] = "Please specify your civil status.";
        }
    }

    // Validate first name
    if (empty($_POST['first_name']) || !validate_name($_POST['first_name'])) {
        $errors['first_name'] = "Please enter a valid first name using only letters.";
    }

    // Validate middle initial
    if (empty($_POST['middle_initial']) || !validate_name($_POST['middle_initial'])) {
        $errors['middle_initial'] = "Please enter a valid middle initial using only letters.";
    }

    // Validate last name
    if (empty($_POST['last_name']) || !validate_name($_POST['last_name'])) {
        $errors['last_name'] = "Please enter a valid last name using only letters.";
    }

    // Validate father's name - if any field is filled, all must be filled
    $father_first_filled = !empty($_POST['father_first_name']);
    $father_middle_filled = !empty($_POST['father_middle_name']);
    $father_last_filled = !empty($_POST['father_last_name']);
    
    if ($father_first_filled || $father_middle_filled || $father_last_filled) {
        if (!$father_first_filled) {
            $errors['father_first_name'] = "Please complete all father's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['father_first_name'])) {
            $errors['father_first_name'] = "Please enter a valid first name using only letters.";
        }
        
        if (!$father_middle_filled) {
            $errors['father_middle_name'] = "Please complete all father's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['father_middle_name'])) {
            $errors['father_middle_name'] = "Please enter a valid middle name using only letters.";
        }
        
        if (!$father_last_filled) {
            $errors['father_last_name'] = "Please complete all father's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['father_last_name'])) {
            $errors['father_last_name'] = "Please enter a valid last name using only letters.";
        }
    }

    // Validate mother's maiden name - if any field is filled, all must be filled
    $mother_first_filled = !empty($_POST['mother_first_name']);
    $mother_middle_filled = !empty($_POST['mother_middle_name']);
    $mother_last_filled = !empty($_POST['mother_last_name']);
    
    if ($mother_first_filled || $mother_middle_filled || $mother_last_filled) {
        if (!$mother_first_filled) {
            $errors['mother_first_name'] = "Please complete all mother's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['mother_first_name'])) {
            $errors['mother_first_name'] = "Please enter a valid first name using only letters.";
        }
        
        if (!$mother_middle_filled) {
            $errors['mother_middle_name'] = "Please complete all mother's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['mother_middle_name'])) {
            $errors['mother_middle_name'] = "Please enter a valid middle name using only letters.";
        }
        
        if (!$mother_last_filled) {
            $errors['mother_last_name'] = "Please complete all mother's name fields or leave them all empty.";
        } elseif (!validate_name($_POST['mother_last_name'])) {
            $errors['mother_last_name'] = "Please enter a valid last name using only letters.";
        }
    }

    // If there are errors, store them in session and redirect back
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    // Sanitize and store all inputs
    $last_name = sanitize_input($_POST['last_name'] ?? '');
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $middle_initial = sanitize_input($_POST['middle_initial'] ?? '');
    $date_of_birth = sanitize_input($_POST['date_of_birth'] ?? '');
    $calculated_age = sanitize_input($_POST['calculated_age'] ?? '');
    $sex = sanitize_input($_POST['sex'] ?? '');
    $civil_status = sanitize_input($_POST['civil_status'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $telephone_number = sanitize_input($_POST['telephone_number'] ?? '');
    $religion = sanitize_input($_POST['religion'] ?? '');
    $nationality = sanitize_input($_POST['nationality'] ?? '');
    $tin = sanitize_input($_POST['tin'] ?? '');
    
    // Capitalize name components
    $last_name = ucwords(strtolower($last_name));
    $first_name = ucwords(strtolower($first_name));
    $middle_initial = strtoupper($middle_initial);
    $nationality = ucwords(strtolower($nationality));
    
    // Format home address
    $unit_no = sanitize_input($_POST['unit_no'] ?? '');
    $house_no = sanitize_input($_POST['house_no'] ?? '');
    $street = sanitize_input($_POST['street'] ?? '');
    $subdivision = sanitize_input($_POST['subdivision'] ?? '');
    $barangay = sanitize_input($_POST['barangay'] ?? '');
    $city = sanitize_input($_POST['city'] ?? '');
    $province = sanitize_input($_POST['province'] ?? '');
    $country = sanitize_input($_POST['country'] ?? '');
    $zip_code = sanitize_input($_POST['zip_code'] ?? '');
    
    // Process place of birth fields
    $pob_barangay = sanitize_input($_POST['pob_barangay'] ?? '');
    $pob_city = sanitize_input($_POST['pob_city'] ?? '');
    $pob_province = sanitize_input($_POST['pob_province'] ?? '');
    $pob_country = sanitize_input($_POST['pob_country'] ?? '');
    $pob_zip_code = sanitize_input($_POST['pob_zip_code'] ?? '');
    $pob_street = sanitize_input($_POST['pob_street'] ?? '');
    
    // Combine place of birth components
    $place_of_birth = implode(', ', array_filter([
        $pob_street,
        $pob_barangay,
        $pob_city,
        $pob_province,
        $pob_country,
        $pob_zip_code
    ]));
    
    // Combine address components
    $home_address = implode(', ', array_filter([
        $unit_no,
        $house_no,
        $street,
        $subdivision,
        $barangay,
        $city,
        $province,
        $country,
        $zip_code
    ]));

    // Get parent information - only if all fields are filled
    $father_name = '';
    if ($father_first_filled && $father_middle_filled && $father_last_filled) {
        $father_first_name = sanitize_input($_POST['father_first_name']);
        $father_middle_name = sanitize_input($_POST['father_middle_name']);
        $father_last_name = sanitize_input($_POST['father_last_name']);
        $father_name = format_name($father_last_name, $father_first_name, $father_middle_name);
    }

    $mother_name = '';
    if ($mother_first_filled && $mother_middle_filled && $mother_last_filled) {
        $mother_first_name = sanitize_input($_POST['mother_first_name']);
        $mother_middle_name = sanitize_input($_POST['mother_middle_name']);
        $mother_last_name = sanitize_input($_POST['mother_last_name']);
        $mother_name = format_name($mother_last_name, $mother_first_name, $mother_middle_name);
    }

    // Format the full name of the person
    $full_name = format_name($last_name, $first_name, $middle_initial);
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form Submission Result</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="styles.css" rel="stylesheet">
    </head>
    <body class="result-page">
        <div class="container">
            <div class="result-container">
                <div class="success-message">
                    <h2><i class="fas fa-check-circle"></i> Form Submitted Successfully!</h2>
                    <p>Thank you for submitting your information.</p>
                </div>

                <h3 class="section-title"><i class="fas fa-user"></i> Personal Information</h3>
                
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-id-card"></i></div>
                    <span class="result-label">Name:</span>
                    <span><?php echo $full_name; ?></span>
                </div>

                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-calendar"></i></div>
                    <span class="result-label">Date of Birth:</span>
                    <span><?php echo date('F d, Y', strtotime($date_of_birth)); ?></span>
                </div>

                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-birthday-cake"></i></div>
                    <span class="result-label">Age:</span>
                    <span><?php echo $_SESSION['form_data']['age']; ?> years old</span>
                </div>

                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-venus-mars"></i></div>
                    <span class="result-label">Sex:</span>
                    <span><?php echo ucfirst($sex); ?></span>
                </div>

                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-ring"></i></div>
                    <span class="result-label">Civil Status:</span>
                    <span><?php echo ucfirst($civil_status); ?></span>
                </div>

                <?php if (!empty($home_address)): ?>
                <h3 class="section-title"><i class="fas fa-home"></i> Address Information</h3>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-map-marker-alt"></i></div>
                    <span class="result-label">Home Address:</span>
                    <span><?php echo $home_address; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($place_of_birth)): ?>
                <h3 class="section-title"><i class="fas fa-map-marker-alt"></i> Place of Birth</h3>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-map-marker-alt"></i></div>
                    <span class="result-label">Place of Birth:</span>
                    <span><?php echo $place_of_birth; ?></span>
                </div>
                <?php endif; ?>

                <h3 class="section-title"><i class="fas fa-address-book"></i> Contact Information</h3>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-phone"></i></div>
                    <span class="result-label">Mobile Number:</span>
                    <span><?php echo sanitize_input($_POST['mobile_number'] ?? ''); ?></span>
                </div>
                
                <?php if (!empty($telephone_number)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-phone-alt"></i></div>
                    <span class="result-label">Telephone Number:</span>
                    <span><?php echo $telephone_number; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($email)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-envelope"></i></div>
                    <span class="result-label">Email:</span>
                    <span><?php echo $email; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($religion) || !empty($nationality)): ?>
                <h3 class="section-title"><i class="fas fa-user-tag"></i> Additional Information</h3>
                <?php if (!empty($religion)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-pray"></i></div>
                    <span class="result-label">Religion:</span>
                    <span><?php echo $religion; ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($nationality)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-globe"></i></div>
                    <span class="result-label">Nationality:</span>
                    <span><?php echo $nationality; ?></span>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if (!empty($tin)): ?>
                <h3 class="section-title"><i class="fas fa-id-card"></i> Government IDs</h3>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-id-card"></i></div>
                    <span class="result-label">TIN Number:</span>
                    <span><?php echo $tin; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($father_name) || !empty($mother_name)): ?>
                <h3 class="section-title"><i class="fas fa-users"></i> Parent Information</h3>
                <?php if (!empty($father_name)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-male"></i></div>
                    <span class="result-label">Father's Name:</span>
                    <span><?php echo $father_name; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($mother_name)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-female"></i></div>
                    <span class="result-label">Mother's Maiden Name:</span>
                    <span><?php echo $mother_name; ?></span>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <div class="button-group">
                    <a href="index.php" class="btn-back">
                        <i class="fas fa-arrow-left"></i> Back to Form
                    </a>
                </div>
            </div>
        </div>
        <script src="script.js"></script>
    </body>
    </html>
    <?php
} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php");
    exit();
}
?>