<?php
// admin-add_course.php
session_start();
require_once __DIR__ . '/includes/db.php';

// Simple admin check — adapt to your auth system
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: /auth/login.php');
    exit;
}

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image       = trim($_POST['image'] ?? ''); // URL
    $price       = (float)($_POST['price'] ?? 0);
    $category    = trim($_POST['category'] ?? '');
    $difficulty  = trim($_POST['difficulty'] ?? '');
    $duration    = (int)($_POST['duration_hours'] ?? 0);
    $features    = trim($_POST['features'] ?? '');
    $objectives  = trim($_POST['objectives'] ?? '');
    $requirements= trim($_POST['requirements'] ?? '');
    $status      = trim($_POST['status'] ?? 'active');
    $instructor  = trim($_POST['instructor'] ?? 'Admin');

    // Basic validation
    if ($title === '' || $description === '' || $price < 0 || $duration <= 0) {
        $error = "Please fill required fields correctly.";
    } else {
        try {
            $sql = "INSERT INTO courses
                (title, description, image, price, category, difficulty, duration_hours, rating, students_count, instructor, status, created_at, updated_at, features, objectives, requirements)
                VALUES
                (:title, :description, :image, :price, :category, :difficulty, :duration, 0, 0, :instructor, :status, NOW(), NOW(), :features, :objectives, :requirements)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':image' => $image,
                ':price' => $price,
                ':category' => $category,
                ':difficulty' => $difficulty,
                ':duration' => $duration,
                ':instructor' => $instructor,
                ':status' => $status,
                ':features' => $features,
                ':objectives' => $objectives,
                ':requirements' => $requirements,
            ]);

            $success = "Course added successfully.";
        } catch (PDOException $e) {
            $error = "Insert failed: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Course - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h1 class="mb-4">Add New Course</h1>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" class="card p-4">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Title*</label>
        <input name="title" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Price (₹)*</label>
        <input name="price" type="number" step="0.01" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Category*</label>
        <input name="category" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Difficulty*</label>
        <select name="difficulty" class="form-select" required>
          <option value="beginner">Beginner</option>
          <option value="intermediate">Intermediate</option>
          <option value="advanced">Advanced</option>
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">Duration (hours)*</label>
        <input name="duration_hours" type="number" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Instructor</label>
        <input name="instructor" class="form-control" value="Admin">
      </div>
      <div class="col-12">
        <label class="form-label">Image URL*</label>
        <input name="image" type="url" class="form-control" placeholder="https://example.com/image.jpg" required>
      </div>
      <div class="col-12">
        <label class="form-label">Description*</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Features (one per line)</label>
        <textarea name="features" class="form-control" rows="2"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Objectives</label>
        <textarea name="objectives" class="form-control" rows="2"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Requirements</label>
        <textarea name="requirements" class="form-control" rows="2"></textarea>
      </div>
      <div class="col-12">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="active" selected>active</option>
          <option value="inactive">inactive</option>
        </select>
      </div>
    </div>

    <div class="mt-4">
      <button class="btn btn-primary">Add Course</button>
      <a href="admin_dashboard.php" class="btn btn-secondary ms-2">Back</a>
    </div>
  </form>
</div>
</body>
</html>
