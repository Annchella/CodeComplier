<?php 
session_start();
include('../includes/Navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Premium Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 0.5rem;
      border-top-right-radius: 0.5rem;
    }
    .card {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    }
    .price-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background: #ffc107;
      color: #222;
      padding: 0.4em 0.8em;
      border-radius: 0.5rem;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="container mt-5 pt-4">
  <h2 class="text-center mb-4">📚 Premium Courses</h2>

  <div class="row">
    <!-- Course 1 -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm position-relative">
        <img src="../assets/images/fullstack.jpg" class="card-img-top" alt="Full Stack" />
        <span class="price-badge">₹999</span>
        <div class="card-body">
          <h5 class="card-title">Full Stack Web Development</h5>
          <p class="card-text">Learn MERN stack development from scratch to advanced level.</p>
          <a href="payment.php?course=Full+Stack+Web+Development&price=999" class="btn btn-primary w-100">View Details</a>
        </div>
      </div>
    </div>

    <!-- Course 2 -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm position-relative">
        <img src="../assets/images/python.jpg" class="card-img-top" alt="Python" />
        <span class="price-badge">₹799</span>
        <div class="card-body">
          <h5 class="card-title">Python for Beginners</h5>
          <p class="card-text">Master Python with real-world projects and exercises.</p>
          <a href="payment.php?course=Python+for+Beginners&price=799" class="btn btn-primary w-100">View Details</a>
        </div>
      </div>
    </div>

    <!-- Course 3 -->
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm position-relative">
        <img src="../assets/images/java.jpg" class="card-img-top" alt="Java" />
        <span class="price-badge">₹699</span>
        <div class="card-body">
          <h5 class="card-title">Java Programming</h5>
          <p class="card-text">Master Java with real-world projects and case studies.</p>
          <a href="payment.php?course=Java+Programming&price=699" class="btn btn-primary w-100">View Details</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
