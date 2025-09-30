<?php
// Use consistent include path to project's shared Db.php
require_once __DIR__ . '/../includes/Db.php';
include '../admin/admin_navbar.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

$editing = false;
$editCourse = null;

// ✅ Add new course
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    // use duration_hours column (DB schema uses duration_hours)
    $duration = trim($_POST['duration_hours'] ?? $_POST['duration'] ?? '');
    $difficulty = trim($_POST['difficulty'] ?? 'Beginner');
    $status = trim($_POST['status'] ?? 'inactive');
    $image = trim($_POST['image'] ?? ''); // ✅ URL-based image

    if ($id) {
        // ✅ Update
        $stmt = $pdo->prepare("UPDATE courses SET title=?, description=?, price=?, duration_hours=?, difficulty=?, status=?, image=? WHERE id=?");
        $stmt->execute([$title, $description, $price, $duration, $difficulty, $status, $image, $id]);
    } else {
        // ✅ Insert
        $stmt = $pdo->prepare("INSERT INTO courses (title, description, price, duration_hours, difficulty, status, image, created_at) VALUES (?,?,?,?,?,?,?,NOW())");
        $stmt->execute([$title, $description, $price, $duration, $difficulty, $status, $image]);
    }
    header("Location: manges_courses.php");
    exit;
}

// ✅ Delete course
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
    $stmt->execute([$id]);
    header("Location: manges_courses.php?msg=deleted");
    exit;
}

// ✅ Edit course
if (isset($_GET['edit'])) {
    $editing = true;
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
    $stmt->execute([$id]);
    $editCourse = $stmt->fetch();
}

// ✅ Fetch all courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin – Manage Courses</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      /* Provided Unsplash image (kept params for quality and fit) */
      --bg-image: url('https://plus.unsplash.com/premium_photo-1661402349251-1e852f2d9465?w=1600&auto=format&fit=crop&q=70&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDd8fHxlbnww');
    }
    /* Full-page background + overlay */
    .page-bg{
      position: fixed; inset: 0; z-index: -2;
      background-image: var(--bg-image);
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover; /* professional full-bleed approach */
    }
    .page-overlay{
      position: fixed; inset: 0; z-index: -1;
      background: rgba(17, 24, 39, 0.45); /* dark overlay for contrast */
    }

    /* Translucent, elegant surfaces */
    .card-surface{
      background-color: rgba(255, 255, 255, 0.8);
      border: 1px solid rgba(226, 232, 240, 0.75);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      box-shadow: 0 8px 24px rgba(16,24,40,.08);
    }

    .form-control, .form-select{
      background-color: rgba(255,255,255,0.92);
    }

    .table thead th{
      background: rgba(248, 250, 252, 0.9);
    }
    .table-hover tbody tr:hover{
      background-color: rgba(255,255,255,0.65);
    }

    .section-title{
      color: #0f172a; opacity: .9;
    }
  </style>
</head>
<body class="bg-transparent">

  <!-- Background layers -->
  <div class="page-bg" aria-hidden="true"></div>
  <div class="page-overlay" aria-hidden="true"></div>

  <div class="container py-4 py-md-5">

    <div class="card card-surface mb-4">
      <div class="card-body">
        <h1 class="h4 mb-1 section-title">Admin – Manage Courses</h1>
        <p class="text-muted mb-0">Create, update, and manage courses with pricing, difficulty, and status.</p>
      </div>
    </div>

    <!-- ✅ Course Form -->
    <form method="post" class="card card-surface p-4 mb-5">
      <input type="hidden" name="id" value="<?= htmlspecialchars($editCourse['id'] ?? '') ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Course Title <span class="text-danger">*</span></label>
          <input type="text" name="title" value="<?= htmlspecialchars($editCourse['title'] ?? '') ?>" class="form-control" required placeholder="e.g., PHP for Beginners">
        </div>
        <div class="col-md-3">
          <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
          <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($editCourse['price'] ?? '') ?>" class="form-control" required placeholder="0.00">
        </div>
        <div class="col-md-3">
          <label class="form-label">Duration (hrs)</label>
          <input type="text" name="duration_hours" value="<?= htmlspecialchars($editCourse['duration_hours'] ?? $editCourse['duration'] ?? '') ?>" class="form-control" placeholder="e.g., 12">
        </div>

        <div class="col-12">
          <label class="form-label">Description <span class="text-danger">*</span></label>
          <textarea name="description" class="form-control" rows="4" required placeholder="Brief description of the course"><?= htmlspecialchars($editCourse['description'] ?? '') ?></textarea>
        </div>

        <div class="col-md-4">
          <label class="form-label">Difficulty</label>
          <select name="difficulty" class="form-select">
            <?php
              $levels = ["Beginner","Intermediate","Advanced"];
              foreach ($levels as $lvl) {
                $sel = ($editCourse['difficulty'] ?? '') === $lvl ? 'selected' : '';
                echo "<option $sel>$lvl</option>";
              }
            ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active" <?= (($editCourse['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= (($editCourse['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Image URL</label>
          <input type="url" name="image" value="<?= htmlspecialchars($editCourse['image'] ?? '') ?>" class="form-control" placeholder="https://example.com/image.jpg">
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success"><?= $editing ? "Update" : "Add" ?> Course</button>
        <?php if ($editing): ?>
          <a href="manges_courses.php" class="btn btn-secondary">Cancel</a>
        <?php endif; ?>
      </div>
    </form>

    <!-- ✅ Course Table -->
    <div class="card card-surface">
      <div class="card-header bg-primary text-white">All Courses</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead>
              <tr>
                <th style="width:60px">#</th>
                <th style="width:100px">Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Status</th>
                <th>Difficulty</th>
                <th style="width:160px">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($courses as $c): ?>
              <tr>
                <td><?= $c['id'] ?></td>
                <td>
                  <?php if (!empty($c['image'])): ?>
                    <img src="<?= htmlspecialchars($c['image']) ?>" alt="course" class="img-fluid rounded" style="max-width: 90px;">
                  <?php else: ?>
                    <span class="text-muted">No image</span>
                  <?php endif; ?>
                </td>
                <td class="fw-semibold"><?= htmlspecialchars($c['title']) ?></td>
                <td>₹<?= number_format($c['price'], 2) ?></td>
                <td>
                  <span class="badge <?= $c['status']==='active' ? 'text-bg-success' : 'text-bg-secondary' ?>">
                    <?= htmlspecialchars(ucfirst($c['status'])) ?>
                  </span>
                </td>
                <td><?= htmlspecialchars($c['difficulty']) ?></td>
                <td>
                  <div class="d-flex gap-2">
                    <a href="manges_courses.php?edit=<?= $c['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="manges_courses.php?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger js-delete">Delete</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // SweetAlert2 confirm delete (replaces native confirm)
    document.querySelectorAll('.js-delete').forEach(link => {
      link.addEventListener('click', function(e){
        e.preventDefault();
        const href = this.getAttribute('href');
        Swal.fire({
          title: 'Delete this course?',
          text: 'This action cannot be undone.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Yes, delete'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      });
    });
  </script>
</body>
</html>
