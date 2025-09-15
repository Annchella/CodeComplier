<?php
// add_course.php
// Admin-only: Create new course with stable string id and status

declare(strict_types=1);
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once '../includes/connect.php'; // $conn = new mysqli(...)

// Helper to escape HTML
function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Ensure upload directory exists
$uploadDir = realpath(__DIR__ . "/../uploads/courses");
if ($uploadDir === false) {
    @mkdir(__DIR__ . "/../uploads/courses", 0775, true);
    $uploadDir = realpath(__DIR__ . "/../uploads/courses");
}

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read and validate fields
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $category    = trim($_POST['category'] ?? '');
    $difficulty  = trim($_POST['difficulty'] ?? '');
    $duration    = (int)($_POST['duration'] ?? 0);
    $features    = trim($_POST['features'] ?? '');
    $statusIn    = strtolower(trim($_POST['status'] ?? 'active'));
    $status      = in_array($statusIn, ['active','inactive'], true) ? $statusIn : 'active';

    if ($title === '' || $description === '' || $category === '' || $difficulty === '' || $price < 0 || $duration <= 0) {
        $error = "Please fill all required fields correctly.";
    }

    // Generate stable URL-friendly id (string) for this course
    $id = bin2hex(random_bytes(8)); // e.g., 16-char hex like memgm6a468wtrf-style

    // Handle image upload (optional)
    $image_url = '';
    if (!$error && isset($_FILES['image']) && is_array($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($uploadDir === false) {
                $error = "Upload directory not available.";
            } else {
                $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
                $allowedExt = ['jpg','jpeg','png','webp'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime  = $finfo ? finfo_file($finfo, $_FILES['image']['tmp_name']) : null;
                if ($finfo) finfo_close($finfo);
                $allowedMime = ['image/jpeg','image/png','image/webp'];
                if (!in_array($ext, $allowedExt, true) || !in_array((string)$mime, $allowedMime, true)) {
                    $error = "Invalid image type. Allowed: JPG, PNG, WEBP.";
                } else {
                    $filename = $id . '-' . time() . '.' . $ext;
                    $target   = $uploadDir . DIRECTORY_SEPARATOR . $filename;
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                        $error = "Failed to save uploaded image.";
                    } else {
                        // Public-relative path used by front-end pages
                        $image_url = "uploads/courses/" . $filename;
                    }
                }
            }
        } else {
            $error = "Image upload error code: " . (int)$_FILES['image']['error'];
        }
    }

    // Insert into DB
    if (!$error) {
        // Ensure courses table has columns:
        // id (VARCHAR UNIQUE), title (TEXT/VARCHAR), description (TEXT),
        // price (DECIMAL/DOUBLE), image_url (VARCHAR), category (VARCHAR),
        // difficulty (VARCHAR), duration_hours (INT), features (TEXT), status ENUM('active','inactive' DEFAULT 'active')
        $sql = "INSERT INTO courses 
                (id, title, description, price, image_url, category, difficulty, duration_hours, features, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            // Types: id(s), title(s), description(s), price(d), image_url(s), category(s), difficulty(s), duration(i), features(s), status(s)
            $ok = $stmt->bind_param("sssds ssiss", $id, $title, $description, $price, $image_url, $category, $difficulty, $duration, $features, $status);
            // Note: No spaces allowed in types string; correct contiguous string is "sssds ssiss" without spaces: "sssds ssiss" -> "sssds ssiss" (10 chars: s s s d s s s i s s)
            if (!$ok) {
                $error = "Bind failed: " . $stmt->error;
            } else {
                if ($stmt->execute()) {
                    $success = "Course added successfully! ID: " . h($id);
                } else {
                    $error = "Error adding course: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Course - Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <?php include('../includes/admin_navbar.php'); ?>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Add New Course</h1>

    <?php if ($success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <?= $success ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= h($error) ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label class="block text-gray-700 font-medium mb-2" for="title">Course Title</label>
          <input type="text" id="title" name="title" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="price">Price (₹)</label>
          <input type="number" id="price" name="price" step="0.01" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="category">Category</label>
          <select id="category" name="category" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Category</option>
            <option value="web">Web Development</option>
            <option value="mobile">Mobile Development</option>
            <option value="data">Data Science</option>
            <option value="ai">AI/ML</option>
            <option value="backend">Backend</option>
          </select>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="difficulty">Difficulty Level</label>
          <select id="difficulty" name="difficulty" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select Difficulty</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="duration">Duration (hours)</label>
          <input type="number" id="duration" name="duration" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="image">Course Image</label>
          <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="md:col-span-2">
          <label class="block text-gray-700 font-medium mb-2" for="description">Course Description</label>
          <textarea id="description" name="description" rows="3" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-gray-700 font-medium mb-2" for="features">Features (One per line)</label>
          <textarea id="features" name="features" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
        </div>

        <div>
          <label class="block text-gray-700 font-medium mb-2" for="status">Status</label>
          <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="active" selected>Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>

      <div class="mt-6">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
          Add Course
        </button>
      </div>
    </form>
  </div>
</body>
</html>
