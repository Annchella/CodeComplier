<?php
session_start();
include('../includes/Navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment</title>
  <!-- Libraries (see above) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <style>
    body {
      background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
      min-height: 100vh;
    }
    .payment-card {
      background: rgba(255,255,255,0.85);
      border-radius: 2rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.10);
      padding: 2.5rem 2rem;
      max-width: 450px;
      margin: 40px auto;
      backdrop-filter: blur(6px);
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem #007bff33;
    }
    .summary-card {
      background: rgba(255,255,255,0.7);
      border-radius: 1.5rem;
      box-shadow: 0 4px 24px rgba(0,0,0,0.08);
      padding: 1.5rem 1rem;
      margin-bottom: 2rem;
    }
    .credit-card-icons i {
      font-size: 2rem;
      margin-right: 0.5rem;
      color: #007bff;
    }
    .btn-pay {
      font-size: 1.1rem;
      padding: 0.75rem 2.5rem;
      border-radius: 2rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: background 0.2s, color 0.2s, transform 0.15s;
    }
    .btn-pay:hover {
      background: #007bff;
      color: #fff;
      transform: translateY(-2px) scale(1.04);
    }
  </style>
</head>
<body>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="payment-card animate__animated animate__fadeInDown">
        <h3 class="mb-4 text-center"><i class="fas fa-credit-card"></i> Payment</h3>
        <div class="summary-card mb-4">
          <h5 class="mb-2"><i class="bi bi-bag-check"></i> Order Summary</h5>
          <ul class="list-unstyled mb-2">
            <li><strong>Course:</strong> Full Stack Web Dev</li>
            <li><strong>Access:</strong> Lifetime</li>
          </ul>
          <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold">Total:</span>
            <span class="fs-5 text-primary fw-bold"><i class="fas fa-indian-rupee-sign"></i> 999</span>
          </div>
        </div>
        <form action="payment-success.php" method="POST" id="paymentForm" autocomplete="off">
          <input type="hidden" name="course" value="<?= $_GET['course'] ?>">
          <input type="hidden" name="price" value="<?= $_GET['price'] ?>">

          <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Card Number</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
              <input type="text" class="form-control" id="cardNumber" name="card_number" required placeholder="1234 5678 9012 3456" maxlength="19">
            </div>
            <div class="credit-card-icons mt-2">
              <i class="fab fa-cc-visa"></i>
              <i class="fab fa-cc-mastercard"></i>
              <i class="fab fa-cc-amex"></i>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
              <label class="form-label">Expiry</label>
              <input type="text" class="form-control" id="expiry" name="expiry" required placeholder="MM/YY" maxlength="5">
            </div>
            <div class="col">
              <label class="form-label">CVV</label>
              <input type="password" class="form-control" id="cvv" name="cvv" required placeholder="123" maxlength="4">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Cardholder Name</label>
            <input type="text" class="form-control" placeholder="Your Name" required>
          </div>
          <button type="submit" class="btn btn-success btn-pay w-100 mt-2"><i class="fas fa-lock"></i> Pay Securely</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  // Animate.css on load
  document.querySelector('.payment-card').classList.add('animate__fadeInDown');

  // Payment form fake handler
  document.getElementById('paymentForm').onsubmit = function(e) {
    e.preventDefault();
    Swal.fire({
      icon: 'success',
      title: 'Payment Successful!',
      text: 'Thank you for your purchase. Access has been granted.',
      confirmButtonColor: '#007bff'
    });
    toastr.success('Payment completed! Enjoy your course.');
    // Optionally, redirect or unlock content here
  };
</script>
</body>
</html>
