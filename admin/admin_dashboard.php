<?php
// admin_dashboard.php

session_start();
include '../includes/Header.php';
include '../admin/admin_navbar.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Fallback if username is not set
$username = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : "Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>

  <!-- Vendor CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      /* Use your selected Unsplash image; desktop default */
      --bg-image: url('https://images.unsplash.com/photo-1605379399642-870262d3d051?auto=format&fit=crop&w=1920&q=80');
      --border: #e5e7eb;
      --text-1: #111827;
      --text-2: #6b7280;
      --primary: #0d6efd;
      --danger: #dc3545;
      --shadow-1: 0 2px 6px rgba(16,24,40,.06);
      --shadow-2: 0 8px 24px rgba(16,24,40,.08);
      --radius: 12px;
      --radius-lg: 16px;
      --sp-3: 1rem;
      --sp-4: 1.25rem;
      --sp-5: 1.5rem;
    }

    /* Background image and contrast overlay */
    .page-bg{
      position: fixed;
      inset: 0;
      z-index: -2;
      background-image: var(--bg-image);
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover; /* responsive fill, crops edges as needed */
    }
    .page-overlay{
      position: fixed;
      inset: 0;
      z-index: -1;
      background: rgba(17,24,39,0.45); /* darken for text contrast */
    }

    body{
      color: var(--text-1);
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      background: transparent;
    }

    /* More transparent, readable panels (with blur) */
    .page-header,
    .stat,
    .admin-card{
      background-color: rgba(255, 255, 255, 0.78); /* transparency level */
      border: 1px solid rgba(229, 231, 235, 0.75);
      border-radius: var(--radius);
      box-shadow: var(--shadow-1);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
    }
    .page-header{
      border-radius: var(--radius-lg);
      padding: var(--sp-5);
    }
    .admin-card{
      transition: box-shadow .2s ease, transform .2s ease, border-color .2s ease;
      display:block; color:inherit; text-decoration:none; height:100%; outline:none;
      background-color: rgba(255, 255, 255, 0.72); /* a bit more transparent on cards */
      border: 1px solid rgba(229, 231, 235, 0.70);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }
    .admin-card:focus-visible{
      box-shadow: 0 0 0 4px rgba(13,110,253,.15), var(--shadow-1);
    }
    .admin-card:hover{
      border-color: rgba(209, 213, 219, 0.9);
      box-shadow: var(--shadow-2);
      transform: translateY(-2px);
    }

    .page-title{ margin: 0; font-weight: 700; letter-spacing: .2px }
    .page-subtitle{ margin: .25rem 0 0 0; color: var(--text-2) }

    /* Stats */
    .stats{ display:flex; gap:.75rem; flex-wrap:wrap }
    .stat{
      flex: 1 1 180px; min-width: 180px;
      padding: var(--sp-3) var(--sp-4);
    }
    .stat .label{ color: var(--text-2); font-size:.9rem; margin-bottom:.25rem }
    .stat .value{ font-size:1.35rem; font-weight:800 }

    /* Section title */
    .section-title{
      font-size: 1rem; font-weight: 600; color: var(--text-2);
      margin: var(--sp-4) 0 var(--sp-3); text-transform: uppercase; letter-spacing: .06em;
    }

    /* Card internals */
    .admin-card .card-header{
      display:flex; align-items:center; gap:.75rem;
      padding: var(--sp-4) var(--sp-4) .9rem;
      background: transparent;
      border-bottom: 1px solid rgba(229,231,235,0.75);
      border-top-left-radius: var(--radius);
      border-top-right-radius: var(--radius);
    }
    .soft-icon{
      width: 44px; height: 44px; border-radius: 10px;
      background: #eff6ff; color: #2563eb;
      display:flex; align-items:center; justify-content:center; font-size:1.1rem;
    }
    .admin-card .card-body{ padding: var(--sp-3) var(--sp-4) var(--sp-4) }
    .admin-card h5{ margin: 0; font-weight: 700 }
    .admin-card p{ margin: .35rem 0 var(--sp-3); color: var(--text-2) }

    .btn-cta{
      display:inline-flex; align-items:center; gap:.5rem;
      border-radius: 8px; padding: .55rem .9rem;
      border: 1px solid #cfe2ff; background: #eff6ff; color: #0d6efd;
      font-weight: 600; text-decoration: none;
      transition: background .2s ease, border-color .2s ease, color .2s ease;
    }
    .btn-cta:hover{ background:#e0efff; border-color:#b6d6ff; color:#0b5ed7 }

    .btn-logout{
      display:inline-flex; align-items:center; gap:.5rem;
      border: 1px solid #f1c6c6; background:#fff5f5; color:#b42318;
      padding: .6rem 1rem; border-radius: 8px; font-weight: 700;
      transition: background .2s ease, border-color .2s ease, color .2s ease;
    }
    .btn-logout:hover{ background:#ffecec; border-color:#f0a8a8; color:#912018 }

    :focus-visible{ outline: 3px solid rgba(13,110,253,.3); outline-offset: 2px }

    /* Responsive: swap background size for performance */
    @media (max-width: 991.98px){
      :root{
        --bg-image: url('https://images.unsplash.com/photo-1605379399642-870262d3d051?auto=format&fit=crop&w=1366&q=80');
      }
      .page-header{ padding: var(--sp-4) }
      .admin-card .card-header{ padding: var(--sp-3) var(--sp-4) .9rem }
      .admin-card .card-body{ padding: var(--sp-3) var(--sp-4) var(--sp-4) }
    }
    @media (max-width: 575.98px){
      :root{
        --bg-image: url('https://images.unsplash.com/photo-1605379399642-870262d3d051?auto=format&fit=crop&w=768&q=75');
      }
    }
  </style>
</head>
<body>
  <!-- Background layers -->
  <div class="page-bg" aria-hidden="true"></div>
  <div class="page-overlay" aria-hidden="true"></div>

  <div class="container py-4 py-md-5">

    <!-- Page header -->
    <div class="page-header mb-4" data-aos="fade-down">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
        <div>
          <h1 class="page-title"><i class="fas fa-user-shield me-2"></i>Welcome, <?= $username ?> (Admin)</h1>
          <p class="page-subtitle">Manage the platform with a clear, consistent dashboard.</p>
        </div>
      </div>

      <!-- Quick stats (static UI numbers) -->
      <div class="mt-4 stats" role="region" aria-label="Quick statistics">
        <div class="stat">
          <div class="label">Total Users</div>
          <div class="value">150</div>
        </div>
        <div class="stat">
          <div class="label">Active Courses</div>
          <div class="value">45</div>
        </div>
        <div class="stat">
          <div class="label">Notes Published</div>
          <div class="value">89</div>
        </div>
      </div>
    </div>

    <h2 class="section-title">Administration</h2>

    <!-- Cards -->
    <div class="row g-3 g-md-4">
      <div class="col-md-6 col-xl-4" data-aos="fade-up">
        <a class="admin-card" href="admin_add-note.php" aria-label="Manage Notes">
          <div class="card-header">
            <div class="soft-icon"><i class="fas fa-book"></i></div>
            <div><h5 class="mb-0">Manage Notes</h5></div>
          </div>
          <div class="card-body">
            <p>Add, edit, or delete programming notes.</p>
            <span class="btn-cta"><i class="fa-solid fa-arrow-right"></i> Go</span>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="50">
        <a class="admin-card" href="manges_courses.php" aria-label="Manage Courses">
          <div class="card-header">
            <div class="soft-icon"><i class="fas fa-graduation-cap"></i></div>
            <div><h5 class="mb-0">Manage Courses</h5></div>
          </div>
          <div class="card-body">
            <p>Add, edit, or delete premium/free courses.</p>
            <span class="btn-cta"><i class="fa-solid fa-arrow-right"></i> Go</span>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="100">
        <a class="admin-card" href="admin_manage-user.php" aria-label="Manage User">
          <div class="card-header">
            <div class="soft-icon"><i class="fas fa-users"></i></div>
            <div><h5 class="mb-0">Manage User</h5></div>
          </div>
          <div class="card-body">
            <p>Edit or block users.</p>
            <span class="btn-cta"><i class="fa-solid fa-arrow-right"></i> Go</span>
          </div>
        </a>
      </div>

      <div class="col-md-6 col-xl-4" data-aos="fade-up" data-aos-delay="150">
        <a class="admin-card" href="manage_payments.php" aria-label="Manage Payments">
          <div class="card-header">
            <div class="soft-icon"><i class="fas fa-credit-card"></i></div>
            <div><h5 class="mb-0">Manage Payments</h5></div>
          </div>
          <div class="card-body">
            <p>View and track user purchases and transactions.</p>
            <span class="btn-cta"><i class="fa-solid fa-arrow-right"></i> Go</span>
          </div>
        </a>
      </div>
    </div>

    <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="150">
      <button type="button" class="btn-logout" id="logoutBtn">
        <i class="fas fa-sign-out-alt"></i> Logout
      </button>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 500, easing: 'ease-out', once: true });

    // Logout confirmation
    document.getElementById('logoutBtn').addEventListener('click', function(){
      Swal.fire({
        title: 'Log out?',
        text: 'The admin session will be closed.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b42318',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, logout'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '../auth/logout.php';
        }
      });
    });
  </script>
</body>
</html>
