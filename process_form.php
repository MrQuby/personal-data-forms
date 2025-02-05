<?php
// process_form.php

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate date
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Function to calculate age
function calculateAge($birthdate) {
    $birth = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $today->diff($birth)->y;
    return $age;
}

// Initialize error array
$errors = [];

// Validate and process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate each field
    $last_name = sanitize_input($_POST["last_name"]);
    $first_name = sanitize_input($_POST["first_name"]);
    $middle_initial = sanitize_input($_POST["middle_initial"]);
    $date_of_birth = sanitize_input($_POST["date_of_birth"]);
    
    // Validate names (only letters, spaces, and hyphens)
    if (!preg_match("/^[A-Za-z\s-]+$/", $last_name)) {
        $errors[] = "Invalid last name format";
    }
    if (!preg_match("/^[A-Za-z\s-]+$/", $first_name)) {
        $errors[] = "Invalid first name format";
    }
    if ($middle_initial && !preg_match("/^[A-Za-z]$/", $middle_initial)) {
        $errors[] = "Invalid middle initial";
    }
    
    // Validate date of birth
    if (!validateDate($date_of_birth)) {
        $errors[] = "Invalid date of birth";
    }
    
    // If no errors, display the information
    if (empty($errors)) {
        // Format the full name
        $middle_initial = $middle_initial ? " " . strtoupper($middle_initial) . "." : "";
        $full_name = ucwords($last_name) . ", " . ucwords($first_name) . $middle_initial;
        
        // Calculate age
        $age = calculateAge($date_of_birth);
        
        // Start HTML output
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Submitted Information</title>
            <style>
                body {
                    font-family: 'Segoe UI', system-ui, sans-serif;
                    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
                    margin: 0;
                    padding: 2rem;
                    min-height: 100vh;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
                .info-group {
                    margin-bottom: 1rem;
                    padding: 1rem;
                    border-bottom: 1px solid #e5e7eb;
                }
                .label {
                    font-weight: 600;
                    color: #374151;
                }
                .value {
                    color: #111827;
                    margin-left: 0.5rem;
                }
                .back-btn {
                    display: inline-block;
                    margin-top: 1rem;
                    padding: 0.75rem 1.5rem;
                    background-color: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: 600;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1 style="text-align: center; color: #2563eb;">Submitted Information</h1>
                
                <div class="info-group">
                    <span class="label">Full Name:</span>
                    <span class="value"><?php echo $full_name; ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Date of Birth:</span>
                    <span class="value"><?php echo date('F d, Y', strtotime($date_of_birth)); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Age:</span>
                    <span class="value"><?php echo $age; ?> years old</span>
                </div>
                
                <div class="info-group">
                    <span class="label">Sex:</span>
                    <span class="value"><?php echo ucfirst(sanitize_input($_POST["sex"])); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Civil Status:</span>
                    <span class="value"><?php echo ucfirst(sanitize_input($_POST["civil_status"])); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">TIN:</span>
                    <span class="value"><?php echo sanitize_input($_POST["tin"]); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Nationality:</span>
                    <span class="value"><?php echo ucfirst(sanitize_input($_POST["nationality"])); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Religion:</span>
                    <span class="value"><?php echo ucfirst(sanitize_input($_POST["religion"] ?? "Not specified")); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Home Address:</span>
                    <span class="value"><?php echo sanitize_input($_POST["home_address"]); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Contact Information:</span><br>
                    <span class="label">Mobile:</span>
                    <span class="value"><?php echo sanitize_input($_POST["mobile_number"]); ?></span><br>
                    <span class="label">Email:</span>
                    <span class="value"><?php echo sanitize_input($_POST["email_address"]); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Father's Name:</span>
                    <span class="value"><?php echo ucwords(sanitize_input($_POST["fathers_name"])); ?></span>
                </div>
                
                <div class="info-group">
                    <span class="label">Mother's Maiden Name:</span>
                    <span class="value"><?php echo ucwords(sanitize_input($_POST["mothers_maiden_name"])); ?></span>
                </div>
                
                <a href="javascript:history.back()" class="back-btn">Back to Form</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        // If there are errors, display them and provide a back button
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Form Submission Error</title>
            <style>
                body {
                    font-family: 'Segoe UI', system-ui, sans-serif;
                    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
                    margin: 0;
                    padding: 2rem;
                    min-height: 100vh;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    background-color: #ffffff;
                    padding: 2rem;
                    border-radius: 12px;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
                .error-message {
                    color: #dc2626;
                    background-color: #fee2e2;
                    padding: 1rem;
                    border-radius: 6px;
                    margin-bottom: 1rem;
                }
                .back-btn {
                    display: inline-block;
                    padding: 0.75rem 1.5rem;
                    background-color: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 6px;
                    font-weight: 600;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1 style="text-align: center; color: #dc2626;">Form Submission Error</h1>
                <?php foreach ($errors as $error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endforeach; ?>
                <a href="javascript:history.back()" class="back-btn">Back to Form</a>
            </div>
        </body>
        </html>
        <?php
    }
}
?>