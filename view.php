<?php
require_once 'config.php';
session_start();

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM personal_data WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$record) {
        $_SESSION['error'] = "Record not found.";
        header("Location: index.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Error fetching record: " . $e->getMessage();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Personal Data</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .record-details {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 20px auto;
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2em;
            margin-bottom: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1em;
            margin: 0;
        }

        .section {
            background: #ffffff;
            margin-bottom: 15px;
            padding: 5px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .section:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.4em;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: #3498db;
            font-size: 1.2em;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ebf5fb;
            border-radius: 50%;
        }

        .detail-item {
            display: flex;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .detail-item:nth-child(even) {
            background: #ffffff;
        }

        .detail-item:hover {
            background: #edf2f7;
            transform: translateX(5px);
        }

        .label {
            font-weight: 600;
            width: 200px;
            color: #34495e;
            font-size: 0.95em;
        }

        .value {
            flex: 1;
            color: #2c3e50;
            font-size: 0.95em;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.2);
        }

        .btn-back:hover {
            background: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn-back i {
            font-size: 1.1em;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .record-details {
                box-shadow: none;
                padding: 20px;
            }
            .actions {
                display: none;
            }
            .section {
                box-shadow: none;
                page-break-inside: avoid;
            }
            .section:hover {
                transform: none;
                box-shadow: none;
            }
            .detail-item:hover {
                transform: none;
            }
        }

        @media (max-width: 768px) {
            .detail-item {
                flex-direction: column;
                gap: 5px;
            }
            .label {
                width: 100%;
            }
            .value {
                padding-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="record-details">
            <div class="header">
                <h1>Personal Information Details</h1>
                <p>Record ID: <?php echo htmlspecialchars($record['id']); ?></p>
            </div>

            <!-- Personal Information Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-user"></i> Personal Information</h2>
                <div class="detail-item">
                    <div class="label">Full Name:</div>
                    <div class="value"><?php echo htmlspecialchars($record['last_name'] . ', ' . $record['first_name'] . ' ' . $record['middle_initial']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Date of Birth:</div>
                    <div class="value"><?php echo htmlspecialchars($record['date_of_birth']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Sex:</div>
                    <div class="value"><?php echo htmlspecialchars($record['sex']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Civil Status:</div>
                    <div class="value"><?php echo htmlspecialchars($record['civil_status']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Nationality:</div>
                    <div class="value"><?php echo htmlspecialchars($record['nationality']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Religion:</div>
                    <div class="value"><?php echo htmlspecialchars($record['religion']); ?></div>
                </div>
            </div>

            <!-- Place of Birth Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Place of Birth</h2>
                <div class="detail-item">
                    <div class="label">Complete Address:</div>
                    <div class="value">
                        <?php
                            $pob_address = [];
                            if (!empty($record['pob_unit_no'])) $pob_address[] = $record['pob_unit_no'];
                            if (!empty($record['pob_street'])) $pob_address[] = $record['pob_street'];
                            if (!empty($record['pob_barangay'])) $pob_address[] = $record['pob_barangay'];
                            if (!empty($record['pob_city'])) $pob_address[] = $record['pob_city'];
                            if (!empty($record['pob_province'])) $pob_address[] = $record['pob_province'];
                            if (!empty($record['pob_country'])) $pob_address[] = $record['pob_country'];
                            if (!empty($record['pob_zip_code'])) $pob_address[] = $record['pob_zip_code'];
                            echo htmlspecialchars(implode(', ', $pob_address));
                        ?>
                    </div>
                </div>
            </div>

            <!-- Home Address Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-home"></i> Home Address</h2>
                <div class="detail-item">
                    <div class="label">Complete Address:</div>
                    <div class="value">
                        <?php
                            $home_address = [];
                            if (!empty($record['unit_no'])) $home_address[] = $record['unit_no'];
                            if (!empty($record['house_no'])) $home_address[] = $record['house_no'];
                            if (!empty($record['street'])) $home_address[] = $record['street'];
                            if (!empty($record['barangay'])) $home_address[] = $record['barangay'];
                            if (!empty($record['city'])) $home_address[] = $record['city'];
                            if (!empty($record['province'])) $home_address[] = $record['province'];
                            if (!empty($record['country'])) $home_address[] = $record['country'];
                            if (!empty($record['zip_code'])) $home_address[] = $record['zip_code'];
                            echo htmlspecialchars(implode(', ', $home_address));
                        ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-phone"></i> Contact Information</h2>
                <div class="detail-item">
                    <div class="label">Mobile Number:</div>
                    <div class="value"><?php echo htmlspecialchars($record['mobile_number']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Telephone Number:</div>
                    <div class="value"><?php echo htmlspecialchars($record['telephone_number']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="label">Email:</div>
                    <div class="value"><?php echo htmlspecialchars($record['email']); ?></div>
                </div>
            </div>

            <!-- Government IDs Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-id-card"></i> Government IDs</h2>
                <div class="detail-item">
                    <div class="label">TIN:</div>
                    <div class="value"><?php echo htmlspecialchars($record['tin']); ?></div>
                </div>
            </div>

            <!-- Parent Information Section -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-users"></i> Parent Information</h2>
                <div class="detail-item">
                    <div class="label">Father's Name:</div>
                    <div class="value">
                        <?php 
                            echo htmlspecialchars(trim($record['father_last_name'] . ', ' . 
                                 $record['father_first_name'] . ' ' . 
                                 $record['father_middle_name']));
                        ?>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="label">Mother's Name:</div>
                    <div class="value">
                        <?php 
                            echo htmlspecialchars(trim($record['mother_last_name'] . ', ' . 
                                 $record['mother_first_name'] . ' ' . 
                                 $record['mother_middle_name']));
                        ?>
                    </div>
                </div>
            </div>

            <!-- Record Information -->
            <div class="section">
                <h2 class="section-title"><i class="fas fa-clock"></i> Record Information</h2>
                <div class="detail-item">
                    <div class="label">Created At:</div>
                    <div class="value"><?php echo date('F d, Y h:i A', strtotime($record['created_at'])); ?></div>
                </div>
                <?php if ($record['updated_at']): ?>
                <div class="detail-item">
                    <div class="label">Last Updated:</div>
                    <div class="value"><?php echo date('F d, Y h:i A', strtotime($record['updated_at'])); ?></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="actions">
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</body>
</html>
