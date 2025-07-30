<?php
require_once '../includes/db.php';

$username = 'admin';
$passwordPlain = 'admin123';
$hashedPassword = password_hash($passwordPlain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashedPassword]);

echo "✅ Admin user inserted successfully!";
?>
