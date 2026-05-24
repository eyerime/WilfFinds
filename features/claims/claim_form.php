<?php
session_start();
require_once __DIR__ . '/../items/ItemModel.php';
require_once 'ClaimModel.php';

$itemModel = new ItemModel();
$claimModel = new ClaimModel();

$item_id = $_GET['item_id'] ?? ($_POST['item_id'] ?? null);

if (!$item_id) {
    header("Location: ../dashboard/public_dashboard.php");
    exit;
}

$item = $itemModel->getItemById($item_id);

if (!$item || $item['status'] !== 'Listed') {
    $error_msg = "This item is no longer available for claiming.";
    // Include a simple error view or redirect
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $id_type = trim($_POST['id_type'] ?? '');
    $id_number = trim($_POST['id_number'] ?? '');
    $proof = trim($_POST['ownership_proof'] ?? '');

    // Validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($id_type) || empty($id_number)) {
        $error = "Please fill in all personal details.";
    } elseif ($item['item_type'] === 'Unidentifiable' && empty($proof)) {
        $error = "Ownership proof is mandatory for unidentifiable items.";
    } elseif ($claimModel->hasActiveClaim($id_number)) {
        $error = "You already have an active claim pending review. You cannot submit another claim until it is resolved.";
    } else {
        try {
            $claimModel->createClaim($item_id, $first_name, $last_name, $email, $id_type, $id_number, $proof);
            $success = "Your claim has been submitted successfully and is pending review.";
        } catch (Exception $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
    }
}

require_once 'claim_form_view.php';
