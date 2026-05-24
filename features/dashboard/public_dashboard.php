<?php
session_start();
require_once __DIR__ . '/../items/ItemModel.php';

$itemModel = new ItemModel();
$items = $itemModel->getAllPublicItems();
$stats = $itemModel->getStats();

// Include the view
require_once 'public_dashboard_view.php';
