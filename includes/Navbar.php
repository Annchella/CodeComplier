<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="/codecomplier/dashboard.php">CodeCompiler</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link" href="/codecomplier/dashboard.php">Home</a>
        <a class="nav-link" href="/codecomplier/compiler/editor.php">Compiler</a>
        <a class="nav-link" href="/codecomplier/pages/Notes.php">Notes</a>  
        <a class="nav-link" href="/codecomplier/pages/paid-courses.php">Courses</a> 
        <a class="nav-link" href="/codecomplier/challenges.php">Challenges</a>
        <a class="nav-link" href="/codecomplier/leaderboard.php">Leaderboard</a>
        <a class="nav-link" href="/codecomplier/ai.php">AI Tools</a>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
          <a class="nav-link" href="/codecomplier/admin/admin_login.php">Admin</a>
        <?php endif; ?>
      </div>

      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['user'])): ?>
          <span class="text-white me-3">👋 Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
          <a href="/codecomplier/auth/logout.php" class="btn btn-outline-light">Logout</a>
        <?php else: ?>
          <a href="/codecomplier/auth/login.php" class="btn btn-outline-light">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
