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

        // Handle avatar upload
        if (!empty($_FILES['avatar']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $avatarName = uniqid('avatar_') . '.' . $ext;
                move_uploaded_file($_FILES['avatar']['tmp_name'], '../uploads/' . $avatarName);
            } else {
                $message = "<div class='alert alert-warning'>Unsupported avatar format. Only JPG, PNG, GIF, and WEBP allowed.</div>";
                $avatarName = 'default.png';
            }
        }

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, avatar) VALUES (?, ?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $password, $avatarName]);
            $message = "<div class='alert alert-success'>✅ Registration successful! <a href='login.php'>Login now</a></div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
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

    <form action="registration.php" method="POST" enctype="multipart/form-data">
      <div class="input-box">
        <input type="text" name="username" placeholder="Enter your name" required>
      </div>
      <div class="input-box">
        <input type="email" name="email" placeholder="Enter your email" required>
      </div>
      <div class="input-box">
        <input type="password" name="password" placeholder="Create password" required>
      </div>
      <div class="input-box">
        <label style="color:#555; font-size:14px;">Choose Avatar (optional):</label>
        <input type="file" name="avatar" accept="image/*">
      </div>
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
      <div class="policy">
        <input type="checkbox" required>
        <h3>I accept all terms & conditions</h3>
      </div>
      <div class="input-box button">
        <input type="submit" value="Register Now">
      </div>
      <div class="text">
        <h3>Already have an account? <a href="login.php">Login now</a></h3>
      </div>
    </form>
  </div>
</body>
</html>
