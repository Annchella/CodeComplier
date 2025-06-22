<?php
$host = 'localhost';
$dbname = 'code_compiler'; // Change if your DB has a different name
$user = 'root';             // Default XAMPP user
$pass = '';                 // Default XAMPP password is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>
