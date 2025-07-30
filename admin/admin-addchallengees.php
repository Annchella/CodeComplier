<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$message = '';

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $difficulty = $_POST['difficulty'];
    $added_by = 1; // Set it dynamically if needed

    if ($title && $description && $difficulty) {
        $stmt = $pdo->prepare("INSERT INTO challenges (title, description, difficulty, added_by) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $description, $difficulty, $added_by])) {
            $message = "<div class='alert alert-success'>✅ Challenge added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>❌ Failed to add challenge.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠ Please fill all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Challenge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h2 class="mb-4">📘 Add New Challenge</h2>
    <?= $message ?>
    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label">Challenge Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Challenge Description</label>
        <textarea name="description" class="form-control" rows="5" required></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Difficulty</label>
        <select name="difficulty" class="form-select" required>
          <option value="easy">Easy</option>
          <option value="medium">Medium</option>
          <option value="hard">Hard</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Challenge</button>
      <a href="../admin/admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
  </div>
</body>
</html>
