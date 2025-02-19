<?php
session_start();
require_once 'config.php';

// Function to display error message
function display_error($field) {
    global $errors;
    if (isset($errors[$field])) {
        echo '<div class="error-message">' . htmlspecialchars($errors[$field]) . '</div>';
    }
}

// Get record ID from URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    $_SESSION['error'] = "Invalid record ID";
    header("Location: index.php");
    exit();
}

// Fetch existing record
try {
    $stmt = $conn->prepare("SELECT * FROM personal_data WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$record) {
        $_SESSION['error'] = "Record not found";
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching record: " . $e->getMessage();
    header("Location: index.php");
    exit();
}

// Get form data from session if exists, otherwise use database record
$form_data = $_SESSION['form_data'] ?? $record;

// Clear session data
unset($_SESSION['form_data']);
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

// Function to get form value
function get_form_value($field) {
    global $form_data;
    return htmlspecialchars($form_data[$field] ?? '');
}

// Function to calculate and display age
function calculate_age($date_of_birth) {
    if (empty($date_of_birth)) return '';
    $dob = new DateTime($date_of_birth);
    $today = new DateTime('2025-02-13'); // Using provided current time
    $age = $today->diff($dob)->y;
    return "Age: " . $age . " years old";
}

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Personal Information</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="form-header">
        <h1>Edit Personal Data</h1>
        <p>Please modify the required fields marked with an asterisk (*)</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            Please double-check your input and try again.
        </div>
    <?php endif; ?>

    <form id="personalDataForm" action="update_form.php" method="POST" novalidate>
        <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                            <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'input-error' : ''; ?>" 
                                   id="last_name" name="last_name" pattern="^[A-Za-z\s-]+$" required 
                                   placeholder="Enter your last name" value="<?php echo get_form_value('last_name'); ?>">
                        </div>
                        <?php display_error('last_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'input-error' : ''; ?>" 
                            id="first_name" name="first_name" pattern="^[A-Za-z\s-]+$" required 
                            placeholder="Enter your first name" value="<?php echo get_form_value('first_name'); ?>">
                        </div>
                        <?php display_error('first_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="middle_initial">Middle Initial</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" class="form-control <?php echo isset($errors['middle_initial']) ? 'input-error' : ''; ?>" 
                            id="middle_initial" name="middle_initial" pattern="^[A-Za-z\s-]+$" required 
                            placeholder="Enter middle initial" value="<?php echo get_form_value('middle_initial'); ?>">
                        </div>
                        <?php display_error('middle_initial'); ?>
                    </div>

                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <div class="input-group">
                            <i class="fas fa-calendar"></i>
                            <input type="date" class="form-control <?php echo isset($errors['date_of_birth']) ? 'input-error' : ''; ?>" 
                            id="date_of_birth" name="date_of_birth" required value="<?php echo get_form_value('date_of_birth'); ?>">
                        </div>
                        <?php display_error('date_of_birth'); ?>
                        <div id="age_display"><?php echo calculate_age(get_form_value('date_of_birth')); ?></div>
                    </div>

                    <div class="form-group">
                        <label>Sex</label>
                        <div class="radio-group <?php echo isset($errors['sex']) ? 'input-error' : ''; ?>">
                            <div class="radio-item">
                                <input type="radio" class="radio-input" id="male" name="sex" value="male" required 
                                <?php echo (isset($form_data['sex']) && $form_data['sex'] == 'male') ? 'checked' : ''; ?>>
                                <label for="male">Male</label>
                            </div>
                            <div class="radio-item"> 
                                <input type="radio" class="radio-input" id="female" name="sex" value="female" required 
                                <?php echo (isset($form_data['sex']) && $form_data['sex'] == 'female') ? 'checked' : ''; ?>>
                                <label for="female">Female</label>
                            </div>
                        </div>
                        <?php display_error('sex'); ?>
                    </div>

                    <div class="form-group">
                        <label for="civil_status">Civil Status</label>
                        <div class="input-group">
                            <i class="fas fa-ring"></i>
                            <select class="form-control <?php echo isset($errors['civil_status']) ? 'input-error' : ''; ?>" id="civil_status" name="civil_status" required>
                                <option value="" <?php echo !isset($form_data['civil_status']) ? 'selected' : ''; ?> disabled>Select Civil Status</option>
                                <option value="single" <?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'single') ? 'selected' : ''; ?>>Single</option>
                                <option value="married" <?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'married') ? 'selected' : ''; ?>>Married</option>
                                <option value="widowed" <?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'widowed') ? 'selected' : ''; ?>>Widowed</option>
                                <option value="divorced" <?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'divorced') ? 'selected' : ''; ?>>Legally Separated</option>
                                <option value="others" <?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'others') ? 'selected' : ''; ?>>Others</option>
                            </select>
                        </div>
                        <?php display_error('civil_status'); ?>
                        <div id="others_container" style="display:<?php echo (isset($form_data['civil_status']) && $form_data['civil_status'] == 'others') ? 'block' : 'none'; ?>; margin-top:10px;">
                            <div class="input-group">
                                <i class="fas fa-pen"></i>
                                <input type="text" class="form-control" id="others_specify" name="others_specify" placeholder="Please specify" value="<?php echo get_form_value('others_specify'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <div class="input-group">
                            <i class="fas fa-mobile-alt"></i>
                            <input type="tel" class="form-control <?php echo isset($errors['mobile_number']) ? 'input-error' : ''; ?>" 
                            id="mobile_number" name="mobile_number" required pattern="\+?[\d\s-]{10,}" 
                            placeholder="09XX XXX XXXX" value="<?php echo get_form_value('mobile_number'); ?>">
                        </div>
                        <?php display_error('mobile_number'); ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'input-error' : ''; ?>" 
                            id="email" name="email" placeholder="example@domain.com" value="<?php echo get_form_value('email'); ?>">
                        </div>
                        <?php display_error('email'); ?>
                    </div>

                    <div class="form-group">
                        <label for="telephone_number">Telephone Number</label>
                        <div class="input-group">
                            <i class="fas fa-phone"></i>
                            <input type="tel" class="form-control <?php echo isset($errors['telephone_number']) ? 'input-error' : ''; ?>" 
                            id="telephone_number" name="telephone_number" pattern="\+?[\d\s-]{7,}" 
                            placeholder="XXX XXXX" value="<?php echo get_form_value('telephone_number'); ?>">
                        </div>
                        <?php display_error('telephone_number'); ?>
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
                            <input type="text" class="form-control <?php echo isset($errors['pob_unit_no']) ? 'input-error' : ''; ?>" 
                            id="pob_unit_no" name="pob_unit_no" placeholder="Enter room/floor/unit number" value="<?php echo get_form_value('pob_unit_no'); ?>">
                        </div>
                        <?php display_error('pob_unit_no'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_house_no">House/Lot & Blk. No</label>
                        <div class="input-group">
                            <i class="fas fa-home"></i>
                            <input type="text" class="form-control" id="pob_house_no" name="pob_house_no" 
                            placeholder="Enter house/lot & block no." value="<?php echo get_form_value('pob_house_no'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_street">Street Name</label>
                        <div class="input-group">
                            <i class="fas fa-road"></i>
                            <input type="text" class="form-control <?php echo isset($errors['pob_street']) ? 'input-error' : ''; ?>" 
                            id="pob_street" name="pob_street" required placeholder="Enter street name" value="<?php echo get_form_value('pob_street'); ?>">
                        </div>
                        <?php display_error('pob_street'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_subdivision">Subdivision</label>
                        <div class="input-group">
                            <i class="fas fa-map-marked-alt"></i>
                            <input type="text" class="form-control" id="pob_subdivision" name="pob_subdivision" 
                            placeholder="Enter subdivision" value="<?php echo get_form_value('pob_subdivision'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pob_barangay">Barangay/District/Locality</label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="form-control <?php echo isset($errors['pob_barangay']) ? 'input-error' : ''; ?>" 
                            id="pob_barangay" name="pob_barangay" required 
                            placeholder="Enter barangay" value="<?php echo get_form_value('pob_barangay'); ?>">
                        </div>
                        <?php display_error('pob_barangay'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_city">City/Municipality</label>
                        <div class="input-group">
                            <i class="fas fa-city"></i>
                            <input type="text" class="form-control <?php echo isset($errors['pob_city']) ? 'input-error' : ''; ?>" 
                            id="pob_city" name="pob_city" required 
                            placeholder="Enter city/municipality" value="<?php echo get_form_value('pob_city'); ?>">
                        </div>
                        <?php display_error('pob_city'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_province">Province</label>
                        <div class="input-group">
                            <i class="fas fa-map"></i>
                            <input type="text" class="form-control <?php echo isset($errors['pob_province']) ? 'input-error' : ''; ?>" 
                            id="pob_province" name="pob_province" required 
                            placeholder="Enter province" value="<?php echo get_form_value('pob_province'); ?>">
                        </div>
                        <?php display_error('pob_province'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_country">Country</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <select class="form-control <?php echo isset($errors['pob_country']) ? 'input-error' : ''; ?>" 
                            id="pob_country" name="pob_country" required>
                                <option value="">Select Country</option>
                                <?php
                                foreach($countries as $country) {
                                    echo "<option value=\"" . htmlspecialchars($country) . "\" " . ((isset($form_data['pob_country']) && $form_data['pob_country'] == $country) ? 'selected' : '') . ">" . htmlspecialchars($country) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <?php display_error('pob_country'); ?>
                    </div>

                    <div class="form-group">
                        <label for="pob_zip_code">Zip Code</label>
                        <div class="input-group">
                            <i class="fas fa-mail-bulk"></i>
                            <input type="text" class="form-control <?php echo isset($errors['pob_zip_code']) ? 'input-error' : ''; ?>" 
                            id="pob_zip_code" name="pob_zip_code" required pattern="[0-9]+" 
                            placeholder="Enter zip code" value="<?php echo get_form_value('pob_zip_code'); ?>">
                        </div>
                        <?php display_error('pob_zip_code'); ?>
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
                            <input type="text" class="form-control <?php echo isset($errors['unit_no']) ? 'input-error' : ''; ?>" 
                            id="unit_no" name="unit_no" required 
                            placeholder="Enter room/floor/unit number" value="<?php echo get_form_value('unit_no'); ?>">
                        </div>
                        <?php display_error('unit_no'); ?>
                    </div>

                    <div class="form-group">
                        <label for="house_no">House/Lot & Blk. No</label>
                        <div class="input-group">
                            <i class="fas fa-home"></i>
                            <input type="text" class="form-control <?php echo isset($errors['house_no']) ? 'input-error' : ''; ?>" 
                            id="house_no" name="house_no" required 
                            placeholder="Enter house/lot & block no." value="<?php echo get_form_value('house_no'); ?>">
                        </div>
                        <?php display_error('house_no'); ?>
                    </div>

                    <div class="form-group">
                        <label for="street">Street Name</label>
                        <div class="input-group">
                            <i class="fas fa-road"></i>
                            <input type="text" class="form-control <?php echo isset($errors['street']) ? 'input-error' : ''; ?>" 
                            id="street" name="street" required 
                            placeholder="Enter street name" value="<?php echo get_form_value('street'); ?>">
                        </div>
                        <?php display_error('street'); ?>
                    </div>

                    <div class="form-group">
                        <label for="subdivision">Subdivision</label>
                        <div class="input-group">
                            <i class="fas fa-map-marked-alt"></i>
                            <input type="text" class="form-control" id="subdivision" name="subdivision" 
                            placeholder="Enter subdivision" value="<?php echo get_form_value('subdivision'); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="barangay">Barangay/District/Locality</label>
                        <div class="input-group">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" class="form-control <?php echo isset($errors['barangay']) ? 'input-error' : ''; ?>" 
                            id="barangay" name="barangay" required 
                            placeholder="Enter barangay" value="<?php echo get_form_value('barangay'); ?>">
                        </div>
                        <?php display_error('barangay'); ?>
                    </div>

                    <div class="form-group">
                        <label for="city">City/Municipality</label>
                        <div class="input-group">
                            <i class="fas fa-city"></i>
                            <input type="text" class="form-control <?php echo isset($errors['city']) ? 'input-error' : ''; ?>" 
                            id="city" name="city" required 
                            placeholder="Enter city/municipality" value="<?php echo get_form_value('city'); ?>">
                        </div>
                        <?php display_error('city'); ?>
                    </div>

                    <div class="form-group">
                        <label for="province">Province</label>
                        <div class="input-group">
                            <i class="fas fa-map"></i>
                            <input type="text" class="form-control <?php echo isset($errors['province']) ? 'input-error' : ''; ?>" 
                            id="province" name="province" required 
                            placeholder="Enter province" value="<?php echo get_form_value('province'); ?>">
                        </div>
                        <?php display_error('province'); ?>
                    </div>

                    <div class="form-group">
                        <label for="country">Country</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <select class="form-control <?php echo isset($errors['country']) ? 'input-error' : ''; ?>" 
                            id="country" name="country" required>
                                <option value="">Select Country</option>
                                <?php
                                foreach($countries as $country) {
                                    echo "<option value=\"" . htmlspecialchars($country) . "\" " . ((isset($form_data['country']) && $form_data['country'] == $country) ? 'selected' : '') . ">" . htmlspecialchars($country) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <?php display_error('country'); ?>
                    </div>

                    <div class="form-group">
                        <label for="zip_code">Zip Code</label>
                        <div class="input-group">
                            <i class="fas fa-mail-bulk"></i>
                            <input type="text" class="form-control <?php echo isset($errors['zip_code']) ? 'input-error' : ''; ?>" 
                            id="zip_code" name="zip_code" required pattern="[0-9]+" 
                            placeholder="Enter zip code" value="<?php echo get_form_value('zip_code'); ?>">
                        </div>
                        <?php display_error('zip_code'); ?>
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
                            <input type="text" class="form-control <?php echo isset($errors['tin']) ? 'input-error' : ''; ?>" 
                            id="tin" name="tin" pattern="\d{9,12}" 
                            placeholder="XXX-XXX-XXX" value="<?php echo get_form_value('tin'); ?>">
                        </div>
                        <?php if (isset($errors['tin'])): ?>
                            <div class="invalid-feedback"><?php echo $errors['tin']; ?></div>
                        <?php endif; ?>
                        <div class="requirements">Format: 9-12 digits</div>
                    </div>

                    <div class="form-group">
                        <label for="nationality">Nationality</label>
                        <div class="input-group">
                            <i class="fas fa-globe"></i>
                            <input type="text" class="form-control <?php echo isset($errors['nationality']) ? 'input-error' : ''; ?>" 
                            id="nationality" name="nationality" pattern="^[A-Za-z\s-]+$" required 
                            placeholder="Enter your nationality" value="<?php echo get_form_value('nationality'); ?>">
                        </div>
                        <?php display_error('nationality'); ?>
                    </div>

                    <div class="form-group">
                        <label for="religion">Religion</label>
                        <div class="input-group">
                            <i class="fas fa-pray"></i>
                            <input type="text" class="form-control" id="religion" name="religion" 
                            placeholder="Enter your religion" value="<?php echo get_form_value('religion'); ?>">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <h3 class="subsection-title">Father's Name</h3>
                    </div>

                    <div class="form-group">
                        <label for="father_last_name">Father's Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control <?php echo isset($errors['father_last_name']) ? 'input-error' : ''; ?>" 
                            id="father_last_name" name="father_last_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter father's last name" value="<?php echo get_form_value('father_last_name'); ?>">
                        </div>
                        <?php display_error('father_last_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="father_first_name">Father's First Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control <?php echo isset($errors['father_first_name']) ? 'input-error' : ''; ?>" 
                            id="father_first_name" name="father_first_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter father's first name" value="<?php echo get_form_value('father_first_name'); ?>">
                        </div>
                        <?php display_error('father_first_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="father_middle_name">Father's Middle Name</label>
                        <div class="input-group">
                            <i class="fas fa-male"></i>
                            <input type="text" class="form-control <?php echo isset($errors['father_middle_name']) ? 'input-error' : ''; ?>" 
                            id="father_middle_name" name="father_middle_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter father's middle name" value="<?php echo get_form_value('father_middle_name'); ?>">
                        </div>
                        <?php display_error('father_middle_name'); ?>
                    </div>

                    <div class="form-group full-width">
                        <h3 class="subsection-title">Mother's Maiden Name</h3>
                    </div>

                    <div class="form-group">
                        <label for="mother_last_name">Mother's Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control <?php echo isset($errors['mother_last_name']) ? 'input-error' : ''; ?>" 
                            id="mother_last_name" name="mother_last_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter mother's last name" value="<?php echo get_form_value('mother_last_name'); ?>">
                        </div>
                        <?php display_error('mother_last_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="mother_first_name">Mother's First Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control <?php echo isset($errors['mother_first_name']) ? 'input-error' : ''; ?>" 
                            id="mother_first_name" name="mother_first_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter mother's first name" value="<?php echo get_form_value('mother_first_name'); ?>">
                        </div>
                        <?php display_error('mother_first_name'); ?>
                    </div>

                    <div class="form-group">
                        <label for="mother_middle_name">Mother's Middle Name</label>
                        <div class="input-group">
                            <i class="fas fa-female"></i>
                            <input type="text" class="form-control <?php echo isset($errors['mother_middle_name']) ? 'input-error' : ''; ?>" 
                            id="mother_middle_name" name="mother_middle_name" pattern="^[A-Za-z\s-]+$" 
                            placeholder="Enter mother's middle name" value="<?php echo get_form_value('mother_middle_name'); ?>">
                        </div>
                        <?php display_error('mother_middle_name'); ?>
                    </div>
                </div>
            </div>

            <!-- Submit Section -->
            <div class="submit-section">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Information
                </button>
                <a href="index.php" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </div>
    </form>
</div>
<script src="script.js"></script>
</body>
</html>