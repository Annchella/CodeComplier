<?php
// pages/courses.php
declare(strict_types=1);
require_once '../includes/connect.php'; // provides $conn (mysqli)

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$sql = "SELECT id, title, description, image, price, category, difficulty, duration_hours, rating, students_count 
        FROM courses 
        WHERE status = 'active'
        ORDER BY created_at DESC";
$res = $conn->query($sql);
$courses = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Courses</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7fafc}
    .glass-card{background:#fff;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.08);overflow:hidden;transition:.2s}
    .glass-card:hover{transform:translateY(-4px)}
    .course-img{width:100%;height:180px;object-fit:cover;background:#f3f4f6}
    .pill{display:inline-block;padding:.2em .6em;border-radius:1em;background:#e0e7ff;color:#4338ca;font-size:.85em}
    .price{color:#6b46c1;font-weight:700}
  </style>
</head>
<body class="py-4">
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 m-0">Available Courses</h1>
  </div>

  <?php if (empty($courses)): ?>
    <div class="text-center text-muted py-5">No active courses available.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($courses as $c): 
        $img = $c['image'] ?: 'https://placehold.co/400x200?text=No+Image';
        $href = 'courses_details.php?id='.urlencode((string)$c['id']);
      ?>
      <div class="col-md-6 col-lg-4">
        <div class="glass-card h-100 d-flex flex-column">
          <img src="<?= h($img) ?>" alt="<?= h($c['title']) ?>" class="course-img" onerror="this.src='https://placehold.co/400x200?text=No+Image'">
          <div class="p-3 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-semibold"><?= h($c['title']) ?></span>
              <span class="pill"><?= h(ucfirst((string)$c['difficulty'])) ?></span>
            </div>
            <div class="text-muted mb-2" style="min-height:48px"><?= h(mb_strimwidth((string)$c['description'],0,120,'…')) ?></div>
            <div class="d-flex justify-content-between text-secondary mb-2" style="font-size:.95em">
              <span><i class="bi bi-clock"></i> <?= (int)$c['duration_hours'] ?> hrs</span>
              <span><i class="bi bi-people"></i> <?= number_format((int)$c['students_count']) ?></span>
              <span><i class="bi bi-star-fill text-warning"></i> <?= number_format((float)$c['rating'],1) ?></span>
            </div>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <span class="price">₹<?= number_format((float)$c['price'],2) ?></span>
              <a class="btn btn-primary" href="<?= h($href) ?>">Enroll</a>
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
