<?php
session_start();
require_once 'config.php';

// Import all the validation functions from process_form.php

// function to sanitize input
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        $_SESSION['error'] = "Invalid record ID";
        header("Location: index.php");
        exit();
    }

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
        header("Location: edit.php?id=" . $id);
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
    $pob_unit_no = sanitize_input($_POST['pob_unit_no'] ?? '');
    
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

    // Sanitize contact information
    $mobile_number = sanitize_input($_POST['mobile_number'] ?? '');
    $telephone_number = sanitize_input($_POST['telephone_number'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    
    // Sanitize parent information
    $father_last_name = sanitize_input($_POST['father_last_name'] ?? '');
    $father_first_name = sanitize_input($_POST['father_first_name'] ?? '');
    $father_middle_name = sanitize_input($_POST['father_middle_name'] ?? '');
    $mother_last_name = sanitize_input($_POST['mother_last_name'] ?? '');
    $mother_first_name = sanitize_input($_POST['mother_first_name'] ?? '');
    $mother_middle_name = sanitize_input($_POST['mother_middle_name'] ?? '');

    // Get parent information - only if all fields are filled
    $father_name = '';
    if ($father_first_filled && $father_middle_filled && $father_last_filled) {
        $father_name = format_name($father_last_name, $father_first_name, $father_middle_name);
    }

    $mother_name = '';
    if ($mother_first_filled && $mother_middle_filled && $mother_last_filled) {
        $mother_name = format_name($mother_last_name, $mother_first_name, $mother_middle_name);
    }

    // Format the full name of the person
    $full_name = format_name($last_name, $first_name, $middle_initial);

    if (empty($errors)) {
        try {
            // Prepare the SQL UPDATE statement
            $sql = "UPDATE personal_data SET 
                last_name = :last_name,
                first_name = :first_name,
                middle_initial = :middle_initial,
                date_of_birth = :date_of_birth,
                sex = :sex,
                civil_status = :civil_status,
                nationality = :nationality,
                religion = :religion,
                pob_unit_no = :pob_unit_no,
                pob_street = :pob_street,
                pob_barangay = :pob_barangay,
                pob_city = :pob_city,
                pob_province = :pob_province,
                pob_country = :pob_country,
                pob_zip_code = :pob_zip_code,
                unit_no = :unit_no,
                house_no = :house_no,
                street = :street,
                barangay = :barangay,
                city = :city,
                province = :province,
                country = :country,
                zip_code = :zip_code,
                mobile_number = :mobile_number,
                telephone_number = :telephone_number,
                email = :email,
                tin = :tin,
                father_last_name = :father_last_name,
                father_first_name = :father_first_name,
                father_middle_name = :father_middle_name,
                mother_last_name = :mother_last_name,
                mother_first_name = :mother_first_name,
                mother_middle_name = :mother_middle_name
                WHERE id = :id";

            $stmt = $conn->prepare($sql);
            
            // Bind all parameters
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':middle_initial', $middle_initial);
            $stmt->bindParam(':date_of_birth', $date_of_birth);
            $stmt->bindParam(':sex', $sex);
            $stmt->bindParam(':civil_status', $civil_status);
            $stmt->bindParam(':nationality', $nationality);
            $stmt->bindParam(':religion', $religion);
            
            // Place of Birth
            $stmt->bindParam(':pob_unit_no', $pob_unit_no);
            $stmt->bindParam(':pob_street', $pob_street);
            $stmt->bindParam(':pob_barangay', $pob_barangay);
            $stmt->bindParam(':pob_city', $pob_city);
            $stmt->bindParam(':pob_province', $pob_province);
            $stmt->bindParam(':pob_country', $pob_country);
            $stmt->bindParam(':pob_zip_code', $pob_zip_code);
            
            // Home Address
            $stmt->bindParam(':unit_no', $unit_no);
            $stmt->bindParam(':house_no', $house_no);
            $stmt->bindParam(':street', $street);
            $stmt->bindParam(':barangay', $barangay);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':province', $province);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':zip_code', $zip_code);

            // Contact Information
            $stmt->bindParam(':mobile_number', $mobile_number);
            $stmt->bindParam(':telephone_number', $telephone_number);
            $stmt->bindParam(':email', $email);
            
            // Government IDs
            $stmt->bindParam(':tin', $tin);
            
            // Parent Information
            $stmt->bindParam(':father_last_name', $father_last_name);
            $stmt->bindParam(':father_first_name', $father_first_name);
            $stmt->bindParam(':father_middle_name', $father_middle_name);
            $stmt->bindParam(':mother_last_name', $mother_last_name);
            $stmt->bindParam(':mother_first_name', $mother_first_name);
            $stmt->bindParam(':mother_middle_name', $mother_middle_name);
            
            $stmt->execute();
            
            // Set success message
            $_SESSION['success_message'] = "Record updated successfully!";
            
            // Clear form data from session
            unset($_SESSION['form_data']);
            
            // Redirect to index page
            header("Location: index.php");
            exit();
            
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating record: " . $e->getMessage();
            header("Location: edit.php?id=" . $id);
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: edit.php?id=" . $id);
        exit();
    }
} else {
    // Redirect if accessed directly without form submission
    header("Location: index.php");
    exit();
}
?>