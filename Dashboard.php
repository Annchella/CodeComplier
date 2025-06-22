<?php
include 'includes/Header.php';
include 'includes/Navbar.php';

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['user']['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>

  <!-- Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #1e3c72, #2a5298);
      color: #fff;
    }

    .theme-toggle {
      position: fixed;
      top: 20px;
      right: 30px;
      z-index: 999;
    }

    .card {
      background: rgba(255, 255, 255, 0.1);
      border: none;
      border-radius: 20px;
      backdrop-filter: blur(10px);
      color: #fff;
      transition: transform 0.3s ease, box-shadow 0.3s;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
    }

    .btn {
      border-radius: 30px;
      padding: 8px 22px;
      font-weight: 500;
    }

    .dashboard-header {
      margin-top: 100px;
    }

    .avatar-img {
      width: 96px;
      height: 96px;
      border-radius: 50%;
      border: 3px solid #fff;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    .logout-section {
      margin-top: 60px;
    }

    .dark-mode {
      background: #121212 !important;
      color: #f1f1f1 !important;
    }

    .dark-mode .card {
      background: rgba(255, 255, 255, 0.05);
      color: #f1f1f1;
    }
  </style>
</head>

<body class="bg-dark">
  <?php include_once "includes/Navbar.php"; ?>
  <button id="themeToggle" class="btn btn-light theme-toggle"><i class="fas fa-moon"></i></button>

  <div class="container dashboard-header text-center">
  <?php
    $avatar = $_SESSION['user']['avatar'] ?? 'real.png';
  ?>
  <img src="uploads/<?= htmlspecialchars($avatar) ?>" class="avatar-img mb-3" alt="Avatar">
  <h2 class="fw-bold"> Welcome, <?= $username ?>!</h2>
  <p class="text-light opacity-75">Explore features and start coding.</p>
</div>

  <div class="container mt-4">
    <div class="row g-4">
      <!-- Feature Cards -->
      <?php
        $features = [
          ["icon" => "fas fa-code", "title" => "Code Editor", "desc" => "Start coding instantly.", "link" => "compiler/editor.php", "btn" => "primary"],
          ["icon" => "fas fa-book", "title" => "Programming Notes", "desc" => "Access notes easily.", "link" => "pages/Notes.php", "btn" => "secondary"],
          ["icon" => "fas fa-graduation-cap", "title" => "Courses", "desc" => "Browse premium & free courses.", "link" => "pages/paid-courses.php", "btn" => "info"],
          ["icon" => "fas fa-robot", "title" => "AI Assistant", "desc" => "Get suggestions from AI.", "link" => "#", "btn" => "dark"],
          ["icon" => "fas fa-trophy", "title" => "Challenges", "desc" => "Climb the leaderboard.", "link" => "#", "btn" => "success"],
          ["icon" => "fas fa-gamepad", "title" => "Fun Zone", "desc" => "Play Tic Tac Toe, Snake etc.", "link" => "pages/fun.php", "btn" => "outline-primary"]
        ];
        foreach ($features as $f) {
          echo "
          <div class='col-md-4' data-aos='zoom-in'>
            <div class='card text-center p-4 feature-card'>
              <i class='{$f['icon']} fa-2x mb-3 text-warning'></i>
              <h5>{$f['title']}</h5>
              <p>{$f['desc']}</p>
              <a href='{$f['link']}' class='btn btn-{$f['btn']}'>Explore</a>
            </div>
          </div>";
        }
      ?>
    </div>

    <div class="text-center logout-section">
      <button id="logoutBtn" class="btn btn-outline-danger px-4"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init();

    const themeToggle = document.getElementById('themeToggle');
    themeToggle.onclick = function () {
      document.body.classList.toggle('dark-mode');
      this.classList.toggle('btn-dark');
      this.classList.toggle('btn-light');
      this.innerHTML = document.body.classList.contains('dark-mode')
        ? '<i class="fas fa-sun"></i>'
        : '<i class="fas fa-moon"></i>';
    };

    document.getElementById('logoutBtn').onclick = function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Logout?',
        text: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, logout'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'auth/logout.php';
        }
      });
    };
  </script>
</body>
</html>
