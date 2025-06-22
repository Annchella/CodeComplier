<?php
session_start();
include('includes/Header.php');
include('includes/Navbar.php'); 

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['user']['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Welcome to Online Code Compiler</title>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
    <!-- AOS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"/>

    <style>
        body {
            background: linear-gradient(to bottom right, #dfe9f3, #ffffff);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background: linear-gradient(to top left, #ffffff, #f3f9ff);
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            box-shadow: 0 0 16px rgba(0, 0, 0, 0.05);
        }
        .feature-card {
            background: #fff;
            border-radius: 1.2rem;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }
        .feature-icon {
            font-size: 2.5rem;
            color: #007bff;
            background: #e7f1ff;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .btn-cta {
            border-radius: 2rem;
            padding: 0.6rem 1.8rem;
        }
        .footer {
            background-color: #2c3e50;
            color: #fff;
            padding: 2rem 0;
            margin-top: 3rem;
            border-top-left-radius: 2rem;
            border-top-right-radius: 2rem;
        }
        .footer a {
            color: #1abc9c;
            margin: 0 0.5rem;
            font-size: 1.2rem;
        }
        .footer a:hover {
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <!-- Hero -->
    <div class="hero text-center mb-5 animate__animated animate__fadeIn">
        <h1 class="fw-bold mb-3"><i class="fas fa-terminal"></i> Online Code Compiler</h1>
        <p class="lead">Write, test, and share code from your browser — <span class="fw-bold text-primary">no setup required</span>.</p>
        <?php if ($isLoggedIn): ?>
            <a href="dashboard.php" class="btn btn-primary btn-cta mt-3"><i class="fas fa-home"></i> Dashboard</a>
            <a href="auth/logout.php" class="btn btn-outline-secondary btn-cta mt-3 ms-2"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-primary btn-cta mt-3"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="auth/register.php" class="btn btn-success btn-cta mt-3 ms-2"><i class="fas fa-user-plus"></i> Register</a>
        <?php endif; ?>
    </div>

    <!-- Features -->
    <div class="row g-4 text-center">
        <div class="col-md-4" data-aos="fade-up">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                <h5>Fast & Secure</h5>
                <p>Compile and run code with blazing speed inside secure containers.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-moon-stars-fill"></i></div>
                <h5>Dark Mode</h5>
                <p>Switch between light and dark themes for comfortable coding.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-globe"></i></div>
                <h5>Shareable Links</h5>
                <p>Share your code snippets instantly with unique links.</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center mt-5" data-aos="zoom-in">
        <div class="bg-primary text-white p-4 rounded-4 shadow-sm">
            <h4><i class="fas fa-bolt"></i> Jump into Code!</h4>
            <p>Use the online compiler without logging in.</p>
            <a href="compiler/editor.php" class="btn btn-light btn-cta mt-2">
                <i class="fas fa-code"></i> Try the Editor
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer text-center">
    <div class="container">
        <div class="mb-2">
            <a href="#"><i class="fab fa-github"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
        <div>&copy; <?= date('Y') ?> CodeCompiler. All rights reserved.</div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ once: true });
</script>
</body>
</html>
