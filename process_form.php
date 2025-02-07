<?php
// process_form.php

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to format name
function format_name($last_name, $first_name, $middle_initial) {
    // If all fields are empty, return empty string
    if (empty($last_name) && empty($first_name) && empty($middle_initial)) {
        return '';
    }
    
    // Capitalize first letter of each word in last name and first name
    $last_name = ucwords(strtolower($last_name));
    $first_name = ucwords(strtolower($first_name));
    
    // Build the formatted name only if both last name and first name are present
    if (!empty($last_name) && !empty($first_name)) {
        $formatted_name = $last_name . ', ' . $first_name;
        if (!empty($middle_initial)) {
            // Take only the first character and make it uppercase
            $middle_initial = strtoupper(substr($middle_initial, 0, 1));
            $formatted_name .= ' ' . $middle_initial . '.';
        }
        return $formatted_name;
    }
    
    return '';
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    
    // Combine place of birth components
    $place_of_birth = implode(', ', array_filter([
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

    // Process father's name
    $father_last_name = sanitize_input($_POST['father_last_name'] ?? '');
    $father_first_name = sanitize_input($_POST['father_first_name'] ?? '');
    $father_middle_name = sanitize_input($_POST['father_middle_name'] ?? '');
    $fathers_name = format_name($father_last_name, $father_first_name, $father_middle_name);

    // Process mother's maiden name
    $mother_last_name = sanitize_input($_POST['mother_last_name'] ?? '');
    $mother_first_name = sanitize_input($_POST['mother_first_name'] ?? '');
    $mother_middle_name = sanitize_input($_POST['mother_middle_name'] ?? '');
    $mothers_name = format_name($mother_last_name, $mother_first_name, $mother_middle_name);
    
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
                    <span><?php echo $calculated_age; ?> years old</span>
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

                <?php if (!empty($fathers_name) || !empty($mothers_name)): ?>
                <h3 class="section-title"><i class="fas fa-users"></i> Family Information</h3>
                <?php if (!empty($fathers_name)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-male"></i></div>
                    <span class="result-label">Father's Name:</span>
                    <span><?php echo $fathers_name; ?></span>
                </div>
                <?php endif; ?>

                <?php if (!empty($mothers_name)): ?>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-female"></i></div>
                    <span class="result-label">Mother's Maiden Name:</span>
                    <span><?php echo $mothers_name; ?></span>
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

        <script>
            // Add smooth scroll animation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    </body>
    </html>
    <?php
} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php");
    exit();
}
?>