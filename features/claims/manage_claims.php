<?php
session_start();

if (!isset($_SESSION['faculty_person_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../items/ItemModel.php';
require_once 'ClaimModel.php';

$faculty_id = $_SESSION['faculty_person_id'];
$item_id = $_GET['item_id'] ?? null;

if (!$item_id) {
    header("Location: ../dashboard/admin_dashboard.php");
    exit;
}

$itemModel = new ItemModel();
$claimModel = new ClaimModel();

// Ensure the item belongs to the logged-in faculty
$item = $itemModel->getItemById($item_id);
if (!$item || $item['logged_by_faculty_id'] != $faculty_id) {
    echo "Unauthorized access or item not found.";
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $claim_id = $_POST['claim_id'] ?? null;

    if ($claim_id && in_array($action, ['Approve', 'Reject'])) {
        $status = ($action === 'Approve') ? 'Approved' : 'Rejected';
        
        try {
            $claimModel->updateClaimStatus($claim_id, $status);
            $success = "Claim #{$claim_id} has been {$status}.";
            
            // If approved, you might want to automatically reject others, but for simplicity we let them do it manually.
            // Wait, if it's approved, maybe we should redirect to handoff?
            if ($status === 'Approved') {
                header("Location: handoff.php?claim_id=" . $claim_id);
                exit;
            }
        } catch (Exception $e) {
            $error = "Error updating claim: " . $e->getMessage();
        }
    }
}

// Fetch pending claims for this item
$claims = $claimModel->getPendingClaimsForFacultyItem($faculty_id, $item_id);

require_once 'manage_claims_view.php';
