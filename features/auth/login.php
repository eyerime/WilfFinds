<?php
session_start();

// Redirect to admin dashboard if already logged in
if (isset($_SESSION['faculty_person_id'])) {
    header("Location: ../dashboard/admin_dashboard.php");
    exit;
}

require_once 'UserModel.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($employee_id) && !empty($password)) {
        $userModel = new UserModel();
        
        // --- DEBUG START ---
        $db = Database::getConnection();
        $check = $db->prepare("SELECT * FROM Faculty_User WHERE employee_id = ?");
        $check->execute([$employee_id]);
        $rawUser = $check->fetch();
        
        if (!$rawUser) {
            $error = "DEBUG: Employee ID '$employee_id' not found in database.";
        } else if (!password_verify($password, $rawUser['password_hash'])) {
            $error = "DEBUG: ID found, but password verification failed. Hash in DB: " . substr($rawUser['password_hash'], 0, 10) . "...";
        } else {
            // If it gets here, it should work!
            $user = $userModel->authenticate($employee_id, $password);
            $_SESSION['faculty_person_id'] = $user['person_id'];
            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            header("Location: ../dashboard/admin_dashboard.php");
            exit;
        }
        // --- DEBUG END ---
        
    } else {
        $error = "Please fill in all fields.";
    }
}


// Include the view
require_once 'login_view.php';
