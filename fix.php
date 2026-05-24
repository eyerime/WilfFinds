<?php
require_once 'core/Database.php';
$db = Database::getConnection();

$new_hash = password_hash('password123', PASSWORD_DEFAULT);
$stmt = $db->prepare("UPDATE Faculty_User SET password_hash = ?");
$stmt->execute([$new_hash]);

echo "Password reset to 'password123' using your server's current PHP version!";
echo "<br>New Hash: " . $new_hash;
