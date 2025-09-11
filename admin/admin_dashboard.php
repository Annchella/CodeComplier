<?php
// admin_dashboard.php

session_start();
include '../includes/Header.php';
include '../includes/Navbar.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$username = htmlspecialchars($_SESSION['admin_username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background: linear-gradient(90deg, #004e92, #000428);
            color: #fff;
            padding: 2rem;
            border-radius: 1rem;
        }
        .dashboard-header h2 {
            margin: 0;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card i {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="dashboard-header text-center mb-4">
            <h2><i class="fas fa-user-shield"></i> Welcome, <?= $username ?> (Admin)</h2>
            <p class="lead">Manage your platform efficiently</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up">
                <div class="card text-center p-4">
                    <i class="fas fa-book"></i>
                    <h5 class="card-title">Manage Notes</h5>
                    <p class="card-text">Add, edit or delete programming notes.</p>
                    <a href="admin_add-note.php" class="btn btn-primary">Go</a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card text-center p-4">
                    <i class="fas fa-graduation-cap"></i>
                    <h5 class="card-title">Manage Courses</h5>
                    <p class="card-text">Add, edit or delete premium/free courses.</p>
                    <a href="manges_courses.php" class="btn btn-primary">Go</a>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card text-center p-4">
                    <i class="fas fa-credit-card"></i>
                    <h5 class="card-title">Manage Payments</h5>
                    <p class="card-text">View and track user purchases and transactions.</p>
                    <a href="manage_payments.php" class="btn btn-primary">Go</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="../auth/logout.php" class="btn btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
