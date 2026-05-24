<?php
session_start();

if (!isset($_SESSION['faculty_person_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../items/ItemModel.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemModel = new ItemModel();
    $faculty_id = $_SESSION['faculty_person_id'];
    
    $category = $_POST['category'] ?? '';
    $location = $_POST['location'] ?? '';
    $item_type = $_POST['item_type'] ?? '';
    
    try {
        if ($item_type === 'Identifiable') {
            $visible_name = $_POST['visible_name'] ?? '';
            $description = $_POST['id_description'] ?? '';
            
            if (empty($category) || empty($location) || empty($visible_name) || empty($description)) {
                $error = "Please fill all required fields for Identifiable Item.";
            } else {
                $itemModel->createIdentifiableItem($faculty_id, $category, $location, $visible_name, $description);
                $success = "Identifiable item successfully logged.";
            }
        } elseif ($item_type === 'Unidentifiable') {
            $description = $_POST['un_description'] ?? '';
            $hidden_description = $_POST['hidden_description'] ?? '';
            
            if (empty($category) || empty($location) || empty($description) || empty($hidden_description)) {
                $error = "Please fill all required fields for Unidentifiable Item.";
            } else {
                $itemModel->createUnidentifiableItem($faculty_id, $category, $location, $description, $hidden_description);
                $success = "Unidentifiable item successfully logged.";
            }
        } else {
            $error = "Invalid item type selected.";
        }
    } catch (Exception $e) {
        $error = "An error occurred while saving: " . $e->getMessage();
    }
}

require_once 'log_item_view.php';
