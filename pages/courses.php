<?php
// courses.php (user side)
session_start();
require_once __DIR__ . '/includes/db.php';

// Fetch all active courses
$stmt = $pdo->query("SELECT id, title, description, image, price, category, difficulty, duration_hours, rating, students_count 
                     FROM courses 
                     WHERE status='active' 
                     ORDER BY created_at DESC");
$courses = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card-hover { transition: transform .15s, box-shadow .15s; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.08); }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <h1 class="mb-4">Available Courses</h1>
  <?php if (empty($courses)): ?>
    <div class="text-muted">No active courses available.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($courses as $c): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card card-hover h-100">
            <?php if (!empty($c['image'])): ?>
              <img src="<?= htmlspecialchars($c['image']) ?>" class="card-img-top" style="height:180px;object-fit:cover" alt="Course image">
            <?php else: ?>
              <img src="https://via.placeholder.com/400x180?text=No+Image" class="card-img-top" alt="No image">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?= htmlspecialchars($c['title']) ?></h5>
              <p class="text-muted mb-2" style="min-height:48px">
                <?= htmlspecialchars(mb_strimwidth($c['description'], 0, 100, '…')) ?>
              </p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-bold">₹<?= number_format((float)$c['price'], 2) ?></div>
                  <small class="text-muted">
                    <?= htmlspecialchars($c['difficulty']) ?> • <?= (int)$c['duration_hours'] ?> hrs
                  </small>
                </div>
                <a href="course_details.php?id=<?= urlencode($c['id']) ?>" class="btn btn-success">Enroll</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
