
<?php
// includes/db.php
// Single PDO connection used by all pages

$DB_HOST = 'localhost';
$DB_NAME = 'code_compiler';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHAR = 'utf8mb4';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHAR}",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    // In production, don't echo DB errors — log them instead.
    die("DB Connection failed: " . $e->getMessage());
}
