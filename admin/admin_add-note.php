<?php
session_start();
require_once('../includes/db.php'); // This should define $pdo

// Add / Edit
if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $language = $_POST['language'];
    $created_by = 1; // Replace with admin session id

    if(isset($_POST['id']) && $_POST['id'] != ''){
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE notes SET title=?, content=?, language=? WHERE id=?");
        $stmt->execute([$title, $content, $language, $id]);
    }else{
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, language, created_by) VALUES (?,?,?,?)");
        $stmt->execute([$title, $content, $language, $created_by]);
    }
}

// For fetching all notes
$notes = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");

// For delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM notes WHERE id=?")->execute([$id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Notes</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-100">

<header class="bg-indigo-600 text-white p-4">
    <h1 class="text-2xl font-bold">Admin Notes</h1>
</header>

<main class="p-6 space-y-6">

<div class="bg-white p-6 rounded-xl shadow-md animate__animated animate__fadeIn">
<form method="POST" class="grid gap-4">
<input type="hidden" name="id" id="noteId">
<input type="text" name="title" placeholder="Title" class="p-2 border rounded-lg" required>
<textarea name="content" placeholder="Content" class="p-2 border rounded-lg" required></textarea>
<select name="language" class="p-2 border rounded-lg" required>
<option>Java</option>
<option>Python</option>
<option>C++</option>
<option>JavaScript</option>
</select>
<button class="bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">Save</button>
</form>
</div>

<div class="bg-white p-6 rounded-xl shadow-md animate__animated animate__fadeInUp">
<table class="w-full border-collapse border">
<thead class="bg-gray-200">
<tr>
<th class="p-2 border">Title</th>
<th class="p-2 border">Language</th>
<th class="p-2 border">Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = $notes->fetch(PDO::FETCH_ASSOC)){ ?>
<tr>
<td class="p-2 border"><?= htmlspecialchars($row['title']); ?></td>
<td class="p-2 border"><?= htmlspecialchars($row['language']); ?></td>
<td class="p-2 border space-x-2">
<a href="?edit=<?= $row['id']; ?>" class="bg-yellow-400 px-2 py-1 rounded">Edit</a>
<a href="?delete=<?= $row['id']; ?>" class="bg-red-500 px-2 py-1 rounded" onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</main>
</body>
</html>
