<?php
session_start();
require_once '../includes/db.php';

// ✅ Ensure course ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("⚠️ Invalid course ID.");
}
$course_id = (int) $_GET['id'];

// ✅ Fetch course
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();
if (!$course) {
    die("⚠️ Course not found.");
}

// ✅ Check login
$is_logged_in = isset($_SESSION['user']['id']);
$user_id = $is_logged_in ? $_SESSION['user']['id'] : null;

// ✅ Check if purchased
$has_purchased = false;
if ($is_logged_in) {
    $check = $pdo->prepare("SELECT 1 FROM purchases WHERE user_id = ? AND course_id = ?");
    $check->execute([$user_id, $course_id]);
    $has_purchased = $check->fetchColumn() ? true : false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($course['title']) ?> – Course Details</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #fafbfc;
      color: #2d3748;
      line-height: 1.6;
      min-height: 100vh;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 40px 20px;
    }

    .course-layout {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 60px;
      align-items: start;
    }

    /* Course Main Content */
    .course-main {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
    }

    .course-image {
      width: 100%;
      height: 350px;
      object-fit: cover;
      display: block;
    }

    .course-content {
      padding: 40px;
    }

    .course-title {
      font-family: 'Crimson Text', serif;
      font-size: 2.5rem;
      font-weight: 600;
      color: #1a202c;
      margin-bottom: 20px;
      line-height: 1.3;
    }

    .course-description {
      font-size: 1.1rem;
      color: #4a5568;
      line-height: 1.7;
      margin-bottom: 30px;
    }

    .course-features {
      list-style: none;
      margin-bottom: 30px;
    }

    .course-features li {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 8px 0;
      color: #2d3748;
    }

    .feature-icon {
      width: 20px;
      height: 20px;
      background: #48bb78;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      flex-shrink: 0;
    }

    /* Sidebar */
    .course-sidebar {
      position: sticky;
      top: 40px;
    }

    .price-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      margin-bottom: 20px;
    }

    .price-section {
      text-align: center;
      margin-bottom: 25px;
      padding-bottom: 25px;
      border-bottom: 1px solid #e2e8f0;
    }

    .price-label {
      font-size: 14px;
      color: #718096;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 8px;
    }

    .price-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: #2d3748;
    }

    .status-alert {
      padding: 16px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      font-size: 14px;
      line-height: 1.5;
    }

    .status-alert.info {
      background: #ebf8ff;
      border: 1px solid #bee3f8;
      color: #2b6cb0;
    }

    .status-alert.success {
      background: #f0fff4;
      border: 1px solid #9ae6b4;
      color: #276749;
    }

    .status-alert a {
      color: inherit;
      text-decoration: underline;
      font-weight: 500;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 14px 24px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      width: 100%;
      margin-bottom: 12px;
    }

    .btn-primary {
      background: #4299e1;
      color: white;
      border: 1px solid #4299e1;
    }

    .btn-primary:hover {
      background: #3182ce;
      border-color: #3182ce;
      transform: translateY(-1px);
    }

    .btn-success {
      background: #48bb78;
      color: white;
      border: 1px solid #48bb78;
    }

    .btn-success:hover {
      background: #38a169;
      border-color: #38a169;
      transform: translateY(-1px);
    }

    .btn-outline {
      background: white;
      color: #4299e1;
      border: 1px solid #4299e1;
    }

    .btn-outline:hover {
      background: #4299e1;
      color: white;
    }

    /* Payment Forms */
    .payment-form {
      background: white;
      border-radius: 8px;
      padding: 25px;
      margin-top: 20px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
    }

    .form-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
      font-size: 18px;
      font-weight: 600;
      color: #2d3748;
      padding-bottom: 15px;
      border-bottom: 1px solid #e2e8f0;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-weight: 500;
      color: #2d3748;
      margin-bottom: 6px;
      font-size: 14px;
    }

    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      font-size: 15px;
      transition: all 0.2s ease;
      background: #fafbfc;
    }

    .form-input:focus {
      outline: none;
      border-color: #4299e1;
      background: white;
      box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
    }

    .form-input::placeholder {
      color: #a0aec0;
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    .security-badge {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      color: #4a5568;
      margin-top: 15px;
      padding: 10px 12px;
      background: #f7fafc;
      border-radius: 6px;
      border: 1px solid #e2e8f0;
    }

    .hidden {
      display: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 20px 16px;
      }

      .course-layout {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .course-content {
        padding: 30px 25px;
      }

      .course-title {
        font-size: 2rem;
      }

      .price-card {
        padding: 25px 20px;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .course-sidebar {
        position: static;
      }
    }

    /* Subtle hover effects */
    .course-main:hover {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .price-card:hover {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    /* Clean animations */
    .course-main,
    .price-card,
    .payment-form {
      transition: box-shadow 0.3s ease;
    }

    .btn:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="course-layout">
      <!-- Main Course Content -->
      <div class="course-main">
        <?php if (!empty($course['image'])): ?>
          <img src="<?= htmlspecialchars($course['image']) ?>" alt="Course Image" class="course-image">
        <?php endif; ?>

        <div class="course-content">
          <h1 class="course-title"><?= htmlspecialchars($course['title']) ?></h1>
          <p class="course-description"><?= nl2br(htmlspecialchars($course['description'])) ?></p>

          <ul class="course-features">
            <li>
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              Lifetime access to all course materials
            </li>
            <li>
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              Expert instructor support and guidance
            </li>
            <li>
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              Certificate of completion
            </li>
            <li>
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              Access on mobile and desktop
            </li>
            <li>
              <span class="feature-icon"><i class="fas fa-check"></i></span>
              30-day money-back guarantee
            </li>
          </ul>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="course-sidebar">
        <div class="price-card">
          <div class="price-section">
            <div class="price-label">Course Price</div>
            <div class="price-value">₹<?= number_format($course['price'], 0) ?></div>
          </div>

          <?php if (!$is_logged_in): ?>
            <div class="status-alert info">
              <i class="fas fa-info-circle"></i>
              <div>
                You're viewing as a guest. <a href="../auth/login.php">Sign in</a> to purchase this course.
              </div>
            </div>
          <?php elseif ($has_purchased): ?>
            <div class="status-alert success">
              <i class="fas fa-check-circle"></i>
              <div>
                <strong>Enrolled successfully!</strong><br>
                You have full access to this course.
              </div>
            </div>
            <a href="my_courses.php" class="btn btn-success">
              <i class="fas fa-play"></i> Continue Learning
            </a>
          <?php else: ?>
            <button class="btn btn-primary" onclick="showForm('card')">
              <i class="fas fa-credit-card"></i> Pay with Card
            </button>
            <button class="btn btn-outline" onclick="showForm('upi')">
              <i class="fas fa-mobile-alt"></i> Pay with UPI
            </button>

            <!-- Card Payment Form -->
            <form method="POST" action="purchases.php" class="payment-form hidden" id="cardForm">
              <div class="form-header">
                <i class="fas fa-credit-card"></i>
                <span>Card Payment</span>
              </div>
              
              <input type="hidden" name="course_id" value="<?= $course_id ?>">
              <input type="hidden" name="payment_method" value="card">

              <div class="form-group">
                <label class="form-label">Card Number</label>
                <input type="text" name="card_number" class="form-input" placeholder="1234 5678 9012 3456" maxlength="19" required>
              </div>

              <div class="form-group">
                <label class="form-label">Cardholder Name</label>
                <input type="text" name="card_name" class="form-input" placeholder="Full name as on card" required>
              </div>

              <div class="form-row">
                <div class="form-group">
                  <label class="form-label">Expiry Date</label>
                  <input type="text" name="expiry_date" class="form-input" placeholder="MM/YY" maxlength="5" required>
                </div>
                <div class="form-group">
                  <label class="form-label">CVV</label>
                  <input type="password" name="cvv" class="form-input" placeholder="123" maxlength="4" required>
                </div>
              </div>

              <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Your payment information is secure and encrypted</span>
              </div>

              <button type="submit" class="btn btn-primary" style="margin-top: 15px;">
                <i class="fas fa-lock"></i> Complete Payment
              </button>
            </form>

            <!-- UPI Payment Form -->
            <form method="POST" action="purchases.php" class="payment-form hidden" id="upiForm">
              <div class="form-header">
                <i class="fas fa-mobile-alt"></i>
                <span>UPI Payment</span>
              </div>
              
              <input type="hidden" name="course_id" value="<?= $course_id ?>">
              <input type="hidden" name="payment_method" value="upi">

              <div class="form-group">
                <label class="form-label">UPI ID</label>
                <input type="text" name="upi_id" class="form-input" placeholder="yourname@paytm" required>
              </div>

              <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Secure and instant UPI payment</span>
              </div>

              <button type="submit" class="btn btn-success" style="margin-top: 15px;">
                <i class="fas fa-mobile-alt"></i> Pay with UPI
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script>
    function showForm(method) {
      document.getElementById('cardForm').classList.add('hidden');
      document.getElementById('upiForm').classList.add('hidden');
      
      if(method === 'card') {
        document.getElementById('cardForm').classList.remove('hidden');
      }
      if(method === 'upi') {
        document.getElementById('upiForm').classList.remove('hidden');
      }
    }

    document.addEventListener('DOMContentLoaded', function() {
      // Card number formatting
      const cardNumberInput = document.querySelector('input[name="card_number"]');
      if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
          let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
          let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
          if (formattedValue.length <= 19) {
            e.target.value = formattedValue;
          }
        });
      }

      // Expiry date formatting
      const expiryInput = document.querySelector('input[name="expiry_date"]');
      if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
          let value = e.target.value.replace(/\D/g, '');
          if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
          }
          e.target.value = value;
        });
      }

      // CVV formatting
      const cvvInput = document.querySelector('input[name="cvv"]');
      if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
          e.target.value = e.target.value.replace(/\D/g, '');
        });
      }
    });
  </script>
</body>
</html>