<?php
include '../includes/Navbar.php';
include '../includes/db.php'; // Update path if needed

if (session_status() === PHP_SESSION_NONE) session_start();

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $cardNumber = $_POST['card_number'] ?? '';
    $expiry = $_POST['expiry'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $course = $_POST['course'] ?? '';
    $price = $_POST['price'] ?? '';
    $userId = $_SESSION['user']['id'] ?? null;

    // Simple dummy validation
    if (!$name || strlen($cardNumber) < 12 || strlen($cvv) !== 3 || !$userId) {
        echo "<div class='alert alert-danger text-center m-4'>❌ Invalid payment details. Please try again.</div>";
        exit;
    }

    // Save payment in DB
    $stmt = $conn->prepare("INSERT INTO purchases (user_id, course_name, price, payment_date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("isd", $userId, $course, $price);

    if ($stmt->execute()) {
        echo "<div class='container text-center mt-5'>";
        echo "<h2 class='text-success'>✅ Payment Successful!</h2>";
        echo "<p>Thank you <strong>$name</strong> for purchasing <strong>$course</strong>.</p>";
        echo "<p>Amount Paid: ₹$price</p>";
        echo "<a href='my-courses.php' class='btn btn-primary mt-3'>Go to My Courses</a>";
        echo "</div>";
    } else {
        echo "<div class='alert alert-danger text-center m-4'>❌ Failed to record payment. Please try again.</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert alert-warning text-center m-4'>⚠️ No payment data received.</div>";
}
?>
