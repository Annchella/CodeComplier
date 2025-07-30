<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please log in to run code']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$code = $input['code'] ?? '';
$language = $input['language'] ?? 'javascript';
$challenge_id = $input['challenge_id'] ?? 0;

if (empty($code)) {
    echo json_encode(['success' => false, 'output' => 'No code provided']);
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

// Basic JavaScript execution (for demo purposes)
if ($language === 'javascript') {
    try {
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'challenge_');
        file_put_contents($tempFile, $code);
        
        // Execute with timeout
        $output = shell_exec("timeout 5 node $tempFile 2>&1");
        
        // Clean up
        unlink($tempFile);
        
        // Test with sample cases
        $test_cases = json_decode($challenge['test_cases'], true) ?? [];
        $results = [];
        
        foreach ($test_cases as $test_case) {
            $results[] = [
                'input' => $test_case['input'],
                'expected' => $test_case['output'],
                'actual' => 'Test result here', // You'll need to implement actual testing
                'passed' => true // Placeholder
            ];
        }
        
        echo json_encode([
            'success' => true,
            'output' => $output ?: 'Code executed successfully',
            'test_results' => $results
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'output' => 'Error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'output' => 'Language not supported yet. Currently only JavaScript is supported.'
    ]);
}
?>
