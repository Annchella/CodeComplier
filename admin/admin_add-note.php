<?php
session_start();
require_once('../includes/db.php'); 
include '../admin/admin_navbar.php';

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

// Fetch all notes
$notes = $pdo->query("SELECT * FROM notes ORDER BY created_at DESC");

// For delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM notes WHERE id=?")->execute([$id]);
    header("Location: admin_notes.php"); // prevent resubmission
    exit;
}

// For edit
$editNote = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id=?");
    $stmt->execute([$id]);
    $editNote = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Notes</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <style>
    :root{
      /* Requested background image (Pinterest). Consider hosting locally for reliability. */
      --bg-image: url('https://i.pinimg.com/1200x/06/9f/bd/069fbd9f7c811ae1457b96809cfbc7fb.jpg');
    }
  </style>
</head>
<body class="bg-transparent text-slate-800">
  <!-- Background + overlay -->
  <div class="fixed inset-0 -z-20 bg-center bg-cover bg-no-repeat" style="background-image: var(--bg-image)"></div>
  <div class="fixed inset-0 -z-10 bg-slate-900/45"></div>

  <main class="max-w-6xl mx-auto px-4 py-6 space-y-6">
    <!-- Header -->
    <section class="bg-white/80 backdrop-blur-md border border-slate-200/70 rounded-2xl p-5 md:p-6 shadow-sm animate__animated animate__fadeIn">
      <h1 class="text-2xl font-semibold">Notes Administration</h1>
      <p class="text-slate-600 text-sm">Create and manage programming notes with title, content, and language.</p>
    </section>

    <!-- Form Card -->
    <section class="bg-white/80 backdrop-blur-md border border-slate-200/70 rounded-2xl p-5 md:p-6 shadow-sm animate__animated animate__fadeIn">
      <form id="noteForm" method="POST" class="grid gap-5" novalidate>
        <input type="hidden" name="id" value="<?= $editNote['id'] ?? '' ?>">

        <div class="grid gap-1.5">
          <label for="title" class="font-medium">Title <span class="text-red-600">*</span></label>
          <input id="title" name="title" type="text" required
                 class="w-full rounded-lg border border-slate-300 bg-white/90 px-3 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                 placeholder="e.g., Arrays in PHP"
                 value="<?= htmlspecialchars($editNote['title'] ?? '') ?>">
          <p class="text-xs text-slate-500">Enter a short, descriptive title.</p>
        </div>

        <div class="grid gap-1.5">
          <label for="content" class="font-medium">Content <span class="text-red-600">*</span></label>
          <textarea id="content" name="content" rows="8" required
                    class="w-full rounded-lg border border-slate-300 bg-white/90 px-3 py-2.5 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Write the note content here..."><?= htmlspecialchars($editNote['content'] ?? '') ?></textarea>
          <p class="text-xs text-slate-500">Use clear structure; code fences if needed.</p>
        </div>

        <div class="grid gap-1.5">
          <label for="language" class="font-medium">Language <span class="text-red-600">*</span></label>
          <select id="language" name="language" required
                  class="w-full rounded-lg border border-slate-300 bg-white/90 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <?php 
              $languages = ['Java','Python','C++','JavaScript'];
              foreach($languages as $lang){
                $selected = ($editNote && $editNote['language'] == $lang) ? 'selected' : '';
                echo "<option value='$lang' $selected>$lang</option>";
              }
            ?>
          </select>
          <p class="text-xs text-slate-500">Choose the primary language for this note.</p>
        </div>

        <div class="flex items-center gap-3 pt-1">
          <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Save
          </button>
          <!-- Explicit native reset -->
          <button type="button" id="resetBtn" 
  class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white/90 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
  Reset
</button>

        </div>
      </form>
    </section>

    <!-- Table Card -->
    <section class="bg-white/80 backdrop-blur-md border border-slate-200/70 rounded-2xl p-5 md:p-6 shadow-sm animate__animated animate__fadeInUp">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-semibold">All Notes</h2>
        <div class="text-xs text-slate-600">Newest first</div>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-slate-100/90 text-slate-700">
              <th class="text-left px-3 py-2.5 font-medium border-b border-slate-200">Title</th>
              <th class="text-left px-3 py-2.5 font-medium border-b border-slate-200">Language</th>
              <th class="text-left px-3 py-2.5 font-medium border-b border-slate-200">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200">
            <?php while($row = $notes->fetch(PDO::FETCH_ASSOC)){ ?>
            <tr class="hover:bg-white/70">
              <td class="px-3 py-2.5 font-medium text-slate-800"><?= htmlspecialchars($row['title']); ?></td>
              <td class="px-3 py-2.5 text-slate-700"><?= htmlspecialchars($row['language']); ?></td>
              <td class="px-3 py-2.5">
                <div class="flex items-center gap-2">
                  <a href="?edit=<?= $row['id']; ?>" class="inline-flex items-center rounded-md border border-amber-300 bg-amber-50/90 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                    Edit
                  </a>
                  <a href="?delete=<?= $row['id']; ?>" class="inline-flex items-center rounded-md border border-rose-300 bg-rose-50/90 px-2.5 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-100"
                     onclick="return confirm('Delete?')">
                    Delete
                  </a>
                </div>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <script>
    // Reset button clears all fields
    document.getElementById("resetBtn").addEventListener("click", function(){
      document.querySelector("input[name='id']").value = "";
      document.querySelector("input[name='title']").value = "";
      document.querySelector("textarea[name='content']").value = "";
      document.querySelector("select[name='language']").selectedIndex = 0;
    });
  </script>
</body>
</html>
