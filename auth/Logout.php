<?php
session_start();

// 1. Unset all session variables
$_SESSION = [];

// 2. Destroy the session
session_destroy();

// 3. Clear the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// 4. Clear custom auth cookies (if set)
if (isset($_COOKIE['email'])) {
    setcookie('email', '', time() - 3600, '/');
}
if (isset($_COOKIE['password'])) {
    setcookie('password', '', time() - 3600, '/');
}

// 5. Optional: Regenerate session ID (cleanup)
session_regenerate_id(true);

// 6. Redirect to login page
header("Location: login.php");
exit;
?>
