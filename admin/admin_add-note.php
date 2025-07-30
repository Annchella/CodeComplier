<?php
session_start();

// Only allow admins
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require_once '../includes/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $language = $_POST['language'] ?? '';
    $created_by = $_SESSION['admin_id'];

    if ($title && $content && $language) {
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, language, created_by) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $content, $language, $created_by])) {
            $message = '<div class="alert alert-success">Note added successfully!</div>';
        } else {
            $message = '<div class="alert alert-danger">Error adding note.</div>';
        }
    } else {
        $message = '<div class="alert alert-warning">All fields are required.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Note (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Add a New Note</h2>
    <?= $message ?>
    <form method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" name="content" rows="6" required></textarea>
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <input type="text" class="form-control" name="language" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
</div>
</body>
</html>
