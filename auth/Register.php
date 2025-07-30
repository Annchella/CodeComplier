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
        $message = "<div class='alert alert-danger'>Invalid CSRF token.</div>";
    } else {
        $username = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $avatarName = 'default.png'; // fallback if no avatar uploaded

        

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $password, $avatarName]);
            $message = "<div class='alert alert-success'>✅ Registration successful! <a href='code complier/auth/Login.php'>Login now</a></div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'> Email is already existed "  . "</div>";
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
  <link rel="stylesheet" href="../assets/css/Register-style.css">
</head>
<body>
  <div class="wrapper">
    <h2>Registration</h2>
    
    <?= $message ?>

    <form action="Register.php" method="POST" enctype="multipart/form-data">
      <div class="input-box">
        <input type="text" name="username" placeholder="Enter your name" required>
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Create password" required>
      </div>
      
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      
      <div class="input-box button">
        <input type="submit" value="Register Now">
      </div>
      <div class="text">
        <h3>Already have an account? <a href="/codecomplier/auth/Login.php">Login now</a></h3>
      </div>
    </form>
  </div>
</body>
</html>
