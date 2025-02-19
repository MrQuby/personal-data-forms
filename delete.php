<?php
session_start();
    require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
        try {
            // First check if the record exists and is not already deleted
            $check_sql = "SELECT id FROM personal_data WHERE id = :id AND deleted_at IS NULL";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bindParam(':id', $id);
            $check_stmt->execute();
        
            if ($check_stmt->rowCount() > 0) {
                // Record exists and is not deleted, proceed with soft delete
                $sql = "UPDATE personal_data SET deleted_at = :deleted_at WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $current_time = date('Y-m-d H:i:s');
                $stmt->bindParam(':deleted_at', $current_time);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                
                $_SESSION['success_message'] = "Record deleted successfully!";
            } else {
                $_SESSION['error'] = "Record not found or already deleted.";
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Error deleting record: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid request. No record ID provided.";
}

// Redirect back to index page
header("Location: index.php");
exit();
?>
