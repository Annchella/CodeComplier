<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Navbar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    .navbar {
      background: #212529;
    }
    .navbar-brand {
      font-weight: bold;
      color: #fff !important;
    }
    .navbar-nav .nav-link {
      color: #adb5bd !important;
      transition: 0.3s;
    }
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link.active {
      color: #fff !important;
      background: #0d6efd;
      border-radius: 5px;
      padding-left: 12px;
      padding-right: 12px;
    }
    .navbar .logout-btn {
      color: #fff;
      font-weight: 500;
      transition: 0.3s;
    }
    .navbar .logout-btn:hover {
      color: #f8d7da;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php">
      <i class="fas fa-user-shield me-2"></i> Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manges_courses.php"><i class="fas fa-book me-1"></i> Courses</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_add-note.php"><i class="fas fa-sticky-note me-1"></i> Notes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_payments.php"><i class="fas fa-credit-card me-1"></i> Payments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_manage-user.php"><i class="fas fa-users me-1"></i> Users</a>
        </li>
      </ul>

      <div class="d-flex">
        <a href="../auth/logout.php" class="logout-btn text-decoration-none">
          <i class="fas fa-sign-out-alt me-1"></i> Logout
        </a>
      </div>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
