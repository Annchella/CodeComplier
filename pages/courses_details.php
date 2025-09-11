<?php
session_start();
include('../includes/db.php'); // Include your DB connection

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Debug: check what ID we got
if ($course_id <= 0) {
    die("<div style='padding:20px; background:#f8d7da; color:#842029;'>
        ❌ Invalid or missing course ID in URL.<br>
        Try opening: <b>courses.php</b> and click a course again.
    </div>");
}

// Fetch the course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch(PDO::FETCH_ASSOC);

// Debugging if not found
if (!$course) {
    echo "<div style='padding:20px; background:#f8d7da; color:#842029;'>";
    echo "<h3>❌ Course not found!</h3>";
    echo "<p><b>Course ID requested:</b> $course_id</p>";

    // Show all courses for debugging
    $all = $pdo->query("SELECT id, title, status FROM courses")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>Available courses:\n";
    print_r($all);
    echo "</pre></div>";
    exit;
}

// ✅ Check if already purchased
$stmt2 = $pdo->prepare("SELECT * FROM purchases WHERE user_id=? AND course_id=? AND status='completed'");
$stmt2->execute([$user_id, $course_id]);
$alreadyPurchased = $stmt2->rowCount() > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['title']) ?> | Course Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<div class="container py-5">
    <a href="courses.php" class="btn btn-outline-primary mb-4"><i class="fas fa-arrow-left"></i> Back to Courses</a>
    
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($course['image'] ?: 'https://placehold.co/600x400?text=No+Image') ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($course['title']) ?>">
        </div>
        <div class="col-md-6">
            <h2 class="fw-bold"><?= htmlspecialchars($course['title']) ?></h2>
            <p class="text-muted"><strong>Difficulty:</strong> <?= htmlspecialchars($course['difficulty']) ?></p>
            <p class="text-muted"><strong>Price:</strong> ₹<?= number_format($course['price'], 2) ?></p>
            <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>

            <?php if ($alreadyPurchased): ?>
                <div class="alert alert-success">
                    ✅ You have already purchased this course. <a href="my_courses.php">Go to My Courses</a>
                </div>
            <?php else: ?>
                <a href="payment.php?course_id=<?= $course_id ?>" class="btn btn-success btn-lg">
                    <i class="fas fa-shopping-cart"></i> Purchase Now
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
