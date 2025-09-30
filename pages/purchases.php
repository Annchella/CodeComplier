<?php
session_start();
require_once '../includes/db.php';

// ✅ Ensure logged in
if (!isset($_SESSION['user']['id'])) {
    die("⚠️ You must be logged in to purchase.");
}
$user_id = $_SESSION['user']['id'];

// ✅ Get POST data
$course_id = $_POST['course_id'] ?? null;
$payment_method = $_POST['payment_method'] ?? null;

// Validate course
if (!$course_id) {
    die("⚠️ No course selected.");
}
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();
if (!$course) {
    die("⚠️ Course not found.");
}
$course_price = $course['price'];

// ✅ Validate payment method
$errors = [];
if ($payment_method === 'card') {
    $card_number = $_POST['card_number'] ?? '';
    $card_name   = $_POST['card_name'] ?? '';
    $expiry_date = $_POST['expiry_date'] ?? '';
    $cvv         = $_POST['cvv'] ?? '';

    if (strlen(trim($card_number)) < 16) {
        $errors[] = "Invalid card number.";
    }
    if (strlen(trim($card_name)) < 2) {
        $errors[] = "Cardholder name is required.";
    }
    if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiry_date)) {
        $errors[] = "Expiry must be MM/YY.";
    }
    if (!preg_match('/^[0-9]{3}$/', $cvv)) {
        $errors[] = "Invalid CVV.";
    }
}
elseif ($payment_method === 'upi') {
    $upi_id = $_POST['upi_id'] ?? '';
    if (!preg_match('/^[a-zA-Z0-9.\-_]{2,}@[a-zA-Z]{2,}$/', $upi_id)) {
        $errors[] = "Invalid UPI ID.";
    }
} else {
    $errors[] = "Please choose a valid payment method.";
}

// ❌ Show errors if any
if (!empty($errors)) {
    echo "<h2>Payment Failed</h2>";
    echo "<ul style='color:red;'>";
    foreach ($errors as $err) {
        echo "<li>" . htmlspecialchars($err) . "</li>";
    }
    echo "</ul>";
    echo "<a href='course_details.php?id=" . $course_id . "'>⬅ Back to course</a>";
    exit;
}

// ✅ Insert purchase if not already purchased
$check = $pdo->prepare("SELECT id FROM purchases WHERE user_id = ? AND course_id = ?");
$check->execute([$user_id, $course_id]);
if (!$check->fetch()) {
    $stmt = $pdo->prepare("
        INSERT INTO purchases (user_id, course_id, purchased_price, purchased_at)
        VALUES (:user_id, :course_id, :price, NOW())
    ");
    $stmt->execute([
        ':user_id' => $user_id,
        ':course_id' => $course_id,
        ':price' => $course_price
    ]);
}

// ✅ Show success page (instead of redirect)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Successful</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5 text-center">
    <div class="card shadow p-5">
      <h2 class="text-success">🎉 Course Purchased Successfully!</h2>
      <p class="mt-3">You now have access to <strong><?= htmlspecialchars($course['title']) ?></strong>.</p>
      <div class="mt-4">
        <a href="my_courses.php" class="btn btn-primary btn-lg">📚 View My Courses</a>
        <a href="courses.php" class="btn btn-outline-secondary btn-lg ms-2">🔎 Browse More Courses</a>
      </div>
    </div>
  </div>
</body>
</html>
