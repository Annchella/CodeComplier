<?php
session_start();
require_once('../includes/db.php');

// Check if admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO courses (title, description, price) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $price]);
    header("Location: manage_courses.php");
    exit;
}

// Fetch all courses
$courses = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4"><i class="fas fa-book"></i> Manage Courses</h2>

  <form method="POST" class="mb-4 p-4 bg-white rounded shadow">
    <h5>Add New Course</h5>
    <div class="mb-3">
      <label>Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
      <label>Price (₹)</label>
      <input type="number" name="price" class="form-control" required>
    </div>
    <button class="btn btn-primary"><i class="fas fa-plus-circle"></i> Add Course</button>
  </form>

  <h5>All Courses</h5>
  <table class="table table-bordered table-hover bg-white">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Price</th>
        <th>Created</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($courses as $course): ?>
        <tr>
          <td><?= $course['id'] ?></td>
          <td><?= htmlspecialchars($course['title']) ?></td>
          <td>₹<?= $course['price'] ?></td>
          <td><?= $course['created_at'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

</body>
</html>
