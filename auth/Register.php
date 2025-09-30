<?php
require_once '../includes/db.php';
session_start();

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $message = "<div class='alert error'>Invalid CSRF token.</div>";
    } else {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $avatarName = 'default.png'; // fallback if no avatar uploaded

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $password, $avatarName]);
            $message = "<div class='alert success'>✅ Registration successful! <a href='Login.php'>Login now</a></div>";
        } catch (PDOException $e) {
            $message = "<div class='alert error'> Email already exists. </div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title> 
  <style>
    /* Fonts */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto&display=swap');

    body {
      font-family: 'Roboto', sans-serif;
      background: linear-gradient(135deg, #e6e9f0, #eef1f5);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .card {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      text-align: center;
    }

    .title {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      margin-bottom: 20px;
      color: #2c3e50;
    }

    .input-group {
      margin: 15px 0;
    }

    .input-group input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      outline: none;
      transition: border-color 0.3s;
    }

    .input-group input:focus {
      border-color: #6c5ce7;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background: #2c3e50;
      color: #fff;
      font-size: 16px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s;
      margin-top: 10px;
    }

    .btn:hover {
      background: #34495e;
    }

    .login-link {
      margin-top: 15px;
      font-size: 14px;
      color: #555;
    }

    .login-link a {
      color: #6c5ce7;
      text-decoration: none;
      font-weight: 500;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .alert {
      margin: 15px 0;
      padding: 12px;
      border-radius: 6px;
      font-size: 14px;
      text-align: left;
    }

    .alert.success {
      background: #e6ffed;
      color: #1b7a2f;
      border: 1px solid #a5e2b4;
    }

    .alert.error {
      background: #ffe6e6;
      color: #a33a3a;
      border: 1px solid #e2a5a5;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <h2 class="title">Create an Account</h2>

      <?= $message ?>

      <!-- Disable browser autofill -->
      <form action="Register.php" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="input-group">
          <input type="text" name="username" placeholder="Full Name" required autocomplete="new-username">
        </div>
        <div class="input-group">
          <input type="email" name="email" placeholder="Email Address" required autocomplete="new-email">
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" required autocomplete="new-password">
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <button type="submit" class="btn">Register</button>

        <p class="login-link">
          Already have an account? <a href="Login.php">Login here</a>
        </p>
      </form>
    </div>
  </div>
</body>
</html>