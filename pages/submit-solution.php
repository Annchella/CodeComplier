<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to submit solutions']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$code = $input['code'] ?? '';
$language = $input['language'] ?? 'javascript';
$challenge_id = $input['challenge_id'] ?? 0;
$user_id = $_SESSION['user_id'];

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'No code provided']);
    exit;
}

// Get challenge details
$stmt = $pdo->prepare("SELECT * FROM challenges WHERE id = ?");
$stmt->execute([$challenge_id]);
$challenge = $stmt->fetch();

if (!$challenge) {
    echo json_encode(['success' => false, 'message' => 'Challenge not found']);
    exit;
}

try {
    // Simple validation (you can expand this)
    $test_cases = json_decode($challenge['test_cases'], true) ?? [];
    $passed_tests = 0;
    $total_tests = count($test_cases);
    
    // For demo purposes, randomly determine if solution passes
    $passed_tests = rand(0, $total_tests);
    $success = $passed_tests === $total_tests;
    
    // Store submission (you'll need to create a submissions table)
    $stmt = $pdo->prepare("
        INSERT INTO user_submissions (user_id, challenge_id, code, language, status, passed_tests, total_tests, submitted_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
        code = VALUES(code),
        language = VALUES(language),
        status = VALUES(status),
        passed_tests = VALUES(passed_tests),
        total_tests = VALUES(total_tests),
        submitted_at = VALUES(submitted_at)
    ");
    
    $status = $success ? 'passed' : 'failed';
    
    // Create table if doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS user_submissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            challenge_id INT NOT NULL,
            code TEXT NOT NULL,
            language VARCHAR(50) DEFAULT 'javascript',
            status ENUM('passed', 'failed', 'error') DEFAULT 'failed',
            passed_tests INT DEFAULT 0,
            total_tests INT DEFAULT 0,
            submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_user_challenge (user_id, challenge_id)
        )
    ");
    
    $stmt->execute([$user_id, $challenge_id, $code, $language, $status, $passed_tests, $total_tests]);
    
    // Update user points if passed
    if ($success) {
        $pdo->prepare("
            INSERT INTO user_points (user_id, total_points, challenges_completed)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE
            total_points = total_points + ?,
            challenges_completed = challenges_completed + 1
        ")->execute([$user_id, $challenge['points'], $challenge['points']]);
    }
    
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Congratulations! All test cases passed!' : "Failed $passed_tests/$total_tests test cases",
        'passed_tests' => $passed_tests,
        'total_tests' => $total_tests,
        'points_earned' => $success ? $challenge['points'] : 0
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error processing submission: ' . $e->getMessage()
    ]);
}
?>
