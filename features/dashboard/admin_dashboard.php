<?php
session_start();

if (!isset($_SESSION['faculty_person_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../items/ItemModel.php';

$itemModel = new ItemModel();
$faculty_id = $_SESSION['faculty_person_id'];

// Get all items logged by this faculty
$myItems = $itemModel->getItemsByFaculty($faculty_id);
$stats = $itemModel->getStats();

// We could filter by status if requested. For now, we will pass them all to view
// and filter in JS or PHP.

require_once 'admin_dashboard_view.php';
