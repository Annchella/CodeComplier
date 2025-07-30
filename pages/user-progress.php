<?php
session_start();
include '../includes/Header.php';
include '../includes/Navbar.php';
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user's submissions
$stmt = $pdo->prepare("
    SELECT s.*, c.title, c.difficulty, c.points, c.category
    FROM user_submissions s
    JOIN challenges c ON s.challenge_id = c.id
    WHERE s.user_id = ?
    ORDER BY s.submitted_at DESC
");
$stmt->execute([$user_id]);
$submissions = $stmt->fetchAll();

// Get user stats
$stats = $pdo->prepare("
    SELECT 
        COUNT(*) as total_attempts,
        SUM(CASE WHEN status = 'passed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'passed' THEN c.points ELSE 0 END) as total_points
    FROM user_submissions s
    JOIN challenges c ON s.challenge_id = c.id
    WHERE s.user_id = ?
")->execute([$user_id]);
$stats = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Progress - Code Compiler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">
            <i class="fas fa-chart-line me-3"></i>My Challenge Progress
        </h1>
        
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-code fa-3x text-primary mb-3"></i>
                        <h3><?= $stats['total_attempts'] ?></h3>
                        <p class="text-muted">Total Attempts</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h3><?= $stats['completed'] ?></h3>
                        <p class="text-muted">Completed Challenges</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h3><?= $stats['total_points'] ?></h3>
                        <p class="text-muted">Total Points</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Submissions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Submissions</h5>
            </div>
            <div class="card-body">
                <?php if (empty($submissions)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5>No submissions yet</h5>
                        <p class="text-muted">Start solving challenges to see your progress here!</p>
                        <a href="challenges.php" class="btn btn-primary">
                            <i class="fas fa-play me-2"></i>Start First Challenge
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Challenge</th>
                                    <th>Difficulty</th>
                                    <th>Status</th>
                                    <th>Points</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($submissions as $submission): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($submission['title']) ?></strong>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($submission['category']) ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $submission['difficulty'] === 'easy' ? 'success' : 
                                            ($submission['difficulty'] === 'medium' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($submission['difficulty']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $submission['status'] === 'passed' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($submission['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= $submission['status'] === 'passed' ? $submission['points'] : 0 ?>
                                    </td>
                                    <td><?= date('M j, Y', strtotime($submission['submitted_at'])) ?></td>
                                    <td>
                                        <a href="challenge-detail.php?id=<?= $submission['challenge_id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
