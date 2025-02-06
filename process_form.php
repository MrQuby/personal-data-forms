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
    $formatted_name = $last_name . ', ' . $first_name;
    if (!empty($middle_initial)) {
        $formatted_name .= ' ' . strtoupper($middle_initial) . '.';
    }
    return $formatted_name;
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

    // Format parent names
    $father_last_name = sanitize_input($_POST['father_last_name'] ?? '');
    $father_first_name = sanitize_input($_POST['father_first_name'] ?? '');
    $father_middle_name = sanitize_input($_POST['father_middle_name'] ?? '');
    
    $mother_last_name = sanitize_input($_POST['mother_last_name'] ?? '');
    $mother_first_name = sanitize_input($_POST['mother_first_name'] ?? '');
    $mother_middle_name = sanitize_input($_POST['mother_middle_name'] ?? '');
    
    // Format full names
    $fathers_name = format_name($father_last_name, $father_first_name, $father_middle_name);
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
        <style>
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                margin: 0;
                padding: 2rem;
                font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            }

            .result-container {
                max-width: 800px;
                margin: 2rem auto;
                padding: 2rem;
                background: #fff;
                border-radius: 15px;
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }

            .success-message {
                background-color: #d4edda;
                color: #155724;
                padding: 1.5rem;
                border-radius: 10px;
                margin-bottom: 2rem;
                text-align: center;
                border-left: 5px solid #28a745;
            }

            .success-message h2 {
                margin: 0 0 0.5rem 0;
                color: #0f4c1c;
            }

            .result-item {
                margin: 1.2rem 0;
                padding: 1rem;
                border-radius: 8px;
                background: #f8f9fa;
                border-left: 4px solid #4a90e2;
                transition: all 0.3s ease;
            }

            .result-item:hover {
                transform: translateX(5px);
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }

            .result-label {
                font-weight: 600;
                color: #2c3e50;
                min-width: 150px;
                display: inline-block;
            }

            .section-title {
                color: #2c3e50;
                margin: 2rem 0 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid #4a90e2;
            }

            .btn-primary {
                display: inline-block;
                padding: 12px 24px;
                background: #4a90e2;
                color: white;
                text-decoration: none;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }

            .btn-primary:hover {
                background: #357abd;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
            }

            .icon-container {
                display: inline-block;
                width: 30px;
                color: #4a90e2;
            }

            @media (max-width: 768px) {
                .result-container {
                    margin: 1rem;
                    padding: 1rem;
                }

                .result-label {
                    display: block;
                    margin-bottom: 0.5rem;
                }
            }

            .button-group {
                display: flex;
                justify-content: center;
                margin-top: 2rem;
            }

            @media print {
                body {
                    background: white;
                    padding: 0;
                }

                .result-container {
                    box-shadow: none;
                    margin: 0;
                    padding: 1rem;
                }

                .button-group {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
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

                <h3 class="section-title"><i class="fas fa-address-book"></i> Contact Information</h3>
                <div class="result-item">
                    <div class="icon-container"><i class="fas fa-phone"></i></div>
                    <span class="result-label">Mobile:</span>
                    <span><?php echo sanitize_input($_POST['mobile_number'] ?? ''); ?></span>
                    <?php if (!empty($email)): ?>
                    <br>
                    <div class="icon-container"><i class="fas fa-envelope"></i></div>
                    <span class="result-label">Email:</span>
                    <span><?php echo $email; ?></span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($fathers_name) || !empty($mothers_name)): ?>
                <h3 class="section-title"><i class="fas fa-users"></i> Family Information</h3>
                <?php endif; ?>

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
                    <span class="result-label">Mother's Name:</span>
                    <span><?php echo $mothers_name; ?></span>
                </div>
                <?php endif; ?>

                <div class="button-group">
                    <a href="index.php" class="btn-primary">
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