<?php
require_once '../includes/db.php';
session_start();

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF token.";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Show logout success message
        if (isset($_GET['logout_success'])) {
            echo "<div class='alert alert-success'>You have been logged out successfully.</div>";
        }

        // Verify user credentials
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Redirect based on role
            if ($user['role'] === 'admin') {
                $_SESSION['admin_logged_in'] = true;
                header("Location: ../admin/admin_dashboard.php");
            } else {
                header("Location: ../dashboard.php");
            }
            exit;
        } else {
            $error = "❌ Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Welcome Back</title>

  <!-- Font Awesome & Google Fonts -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #00c6ff, #0072ff);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      background: #fff;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
    }
    header {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 1rem;
      color: #333;
    }
    form input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
      transition: border-color 0.3s;
    }
    form input:focus {
      border-color: #0072ff;
    }
    .button {
      background: #0072ff;
      color: #fff;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: 600;
    }
    .button:hover {
      background: #0056cc;
    }
    .error-message {
      background: #ffe5e5;
      color: #d60000;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
      font-size: 14px;
    }
    .signup {
      margin-top: 1rem;
      font-size: 14px;
    }
    .signup a {
      color: #0072ff;
      text-decoration: none;
      font-weight: 500;
    }
    .signup a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <header>Welcome Back</header>

    <?php if ($error): ?>
      <div class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="" autocomplete="off">
      <input type="email" name="email" placeholder="Enter your email" required autocomplete="off">
      <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="new-password">

      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <input type="submit" class="button" value="Sign In">
    </form>

    <div class="signup">
      Don't have an account? <a href="Register.php">Create Account</a>
    </div>
  </div>
</body>
</html>
