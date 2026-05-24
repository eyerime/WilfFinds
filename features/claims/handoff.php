<?php
session_start();

if (!isset($_SESSION['faculty_person_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../items/ItemModel.php';
require_once 'ClaimModel.php';

$faculty_id = $_SESSION['faculty_person_id'];
$claim_id = $_GET['claim_id'] ?? ($_POST['claim_id'] ?? null);

if (!$claim_id) {
    header("Location: ../dashboard/admin_dashboard.php");
    exit;
}

$claimModel = new ClaimModel();
$claim = $claimModel->getClaimById($claim_id, $faculty_id);

// Check if claim exists and is approved, and belongs to this faculty's item
if (!$claim || $claim['claim_status'] !== 'Approved') {
    echo "Invalid claim or claim not approved yet.";
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $physical_id_verified = isset($_POST['physical_id_verified']) ? true : false;
    
    if ($physical_id_verified) {
        try {
            $claimModel->processHandoff($claim_id, $faculty_id);
            $success = "Handoff complete! The item has been marked as Returned.";
            $claim['claim_status'] = 'Returned'; // Update local state so form hides
        } catch (Exception $e) {
            $error = "An error occurred during handoff: " . $e->getMessage();
        }
    } else {
        $error = "You must verify the physical ID to complete the handoff.";
    }
}

require_once 'handoff_view.php';
