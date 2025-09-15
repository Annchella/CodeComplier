<?php
declare(strict_types=1);

// 1) Load DB connector (adjust the path/filename to your project)
$path = __DIR__ . '/../includes/Db.php'; // e.g., ../includes/connect.php or ../includes/Db.php
if (!is_file($path)) {
  error_log('Missing DB include at ' . $path);
  http_response_code(500);
  echo '<p>Server misconfiguration: DB connector missing.</p>';
  exit;
}
require_once $path;

// 2) Ensure we have a mysqli connection in $conn (support both styles)
if (!isset($conn) || !($conn instanceof mysqli)) {
  if (class_exists('Db') && method_exists('Db', 'getConnection')) {
    $conn = Db::getConnection(); // Db.php should expose this if it doesn't define $conn
  }
}
if (!isset($conn) || !($conn instanceof mysqli)) {
  error_log('DB connector did not provide $conn');
  http_response_code(500);
  echo '<p>Server misconfiguration: no DB connection.</p>';
  exit;
}

// 3) Payment config (Stripe optional)
$USE_STRIPE   = true;               // set false if not using Stripe
$STRIPE_SECRET= getenv('STRIPE_SECRET') ?: ''; // put your Stripe secret in environment, not in code
$CURRENCY     = 'INR';              // change if needed

// Helpers
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function json_response(array $data, int $status = 200): never {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

// 4) Parse id
$id = isset($_GET['id']) ? trim((string)$_GET['id']) : '';
if ($id == '') {
  http_response_code(400);
  echo '<!doctype html><p>Bad request: missing course id.</p>';
  exit;
}

// 5) Handle AJAX payment session creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $payload = file_get_contents('php://input');
  $req = json_decode($payload, true);
  if (json_last_error() !== JSON_ERROR_NONE || !is_array($req)) {
    json_response(['error' => 'Invalid JSON'], 400);
  }
  $action = $req['action'] ?? '';
  if ($action === 'create_stripe_session') {
    if (!$USE_STRIPE || $STRIPE_SECRET === '') {
      json_response(['error' => 'Stripe not configured'], 400);
    }
    // Fetch course again authoritatively (server-side)
    $stmt = $conn->prepare("SELECT id, title, price, status FROM courses WHERE id = ? LIMIT 1");
    if (!$stmt) json_response(['error' => 'DB prepare failed'], 500);
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $course = $res ? $res->fetch_assoc() : null;
    $stmt->close();

    if (!$course || strtolower((string)$course['status']) !== 'active') {
      json_response(['error' => 'Course unavailable'], 404);
    }

    $title = (string)$course['title'];
    $price = (float)$course['price']; // Stripe expects minor units

    // Create Stripe Checkout Session
    require_once __DIR__ . '/../vendor/autoload.php';
    \Stripe\Stripe::setApiKey($STRIPE_SECRET);
    try {
      $session = \Stripe\Checkout\Session::create([
        'mode' => 'payment',
        'line_items' => [[
          'price_data' => [
            'currency' => strtolower($CURRENCY),
            'product_data' => [
              'name' => $title,
              'metadata' => ['course_id' => $course['id']],
            ],
            'unit_amount' => (int)round($price * 100),
          ],
          'quantity' => 1,
        ]],
        'metadata' => ['course_id' => $course['id']],
        'success_url' => sprintf('%s://%s%s?status=success&id=%s&session_id={CHECKOUT_SESSION_ID}',
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
          $_SERVER['HTTP_HOST'],
          dirname($_SERVER['REQUEST_URI']) . '/purchase_success.php',
          rawurlencode($course['id'])
        ),
        'cancel_url' => sprintf('%s://%s%s?id=%s',
          isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http',
          $_SERVER['HTTP_HOST'],
          $_SERVER['REQUEST_URI'],
          rawurlencode($course['id'])
        ),
      ]);
      json_response(['checkout_url' => $session->url], 200);
    } catch (\Throwable $e) {
      json_response(['error' => 'Stripe error: '.$e->getMessage()], 500);
    }
  } else {
    json_response(['error' => 'Unknown action'], 400);
  }
}

// 6) Fetch the course to render the page (match your column names exactly)
$stmt = $conn->prepare("
  SELECT 
    id, title, description, image, price, category, difficulty, duration_hours, 
    rating, students_count, instructor, status, features, objectives, requirements
  FROM courses WHERE id = ? LIMIT 1
");
if (!$stmt) {
  http_response_code(500);
  echo '<!doctype html><p>Server error.</p>';
  exit;
}
$stmt->bind_param("s", $id);
$stmt->execute();
$res = $stmt->get_result();
$course = $res ? $res->fetch_assoc() : null;
$stmt->close();

if (!$course || strtolower((string)$course['status']) !== 'active') {
  http_response_code(404);
  echo '<!doctype html><p>Course not found or inactive.</p>';
  exit;
}

// 7) Prepare fields for display
$title       = h((string)$course['title']);
$desc        = h((string)($course['description'] ?? ''));
$image       = (string)($course['image'] ?? ''); // if your column is image_url, use that name above and here
$img         = $image !== '' ? $image : 'https://placehold.co/800x400?text=Course';
$priceVal    = (float)($course['price'] ?? 0.0);
$category    = h((string)($course['category'] ?? ''));
$difficulty  = h(ucfirst((string)($course['difficulty'] ?? '')));
$duration    = (int)($course['duration_hours'] ?? 0);
$rating      = (float)($course['rating'] ?? 0);
$students    = (int)($course['students_count'] ?? 0);
$instructor  = h((string)($course['instructor'] ?? ''));
$features    = nl2br(h((string)($course['features'] ?? '')));
$objectives  = nl2br(h((string)($course['objectives'] ?? '')));
$requirements= nl2br(h((string)($course['requirements'] ?? '')));
$courseId    = h((string)$course['id']);
$currencyTxt = h($CURRENCY);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= $title ?> – Course Details</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{background:#f7fafc}
    .hero{background:#fff;border-radius:16px;box-shadow:0 6px 24px rgba(0,0,0,.08);overflow:hidden}
    .price{color:#6b46c1;font-weight:700;font-size:1.25rem}
    .muted{color:#6b7280}
  </style>
</head>
<body class="py-4">
<div class="container">
  <a href="courses.php" class="btn btn-link mb-3">&larr; Back to courses</a>
  <div class="hero row g-0">
    <div class="col-md-6">
      <img src="<?= h($img) ?>" alt="<?= $title ?>" class="w-100" style="height:100%;object-fit:cover;min-height:260px" onerror="this.src='https://placehold.co/800x400?text=Course'">
    </div>
    <div class="col-md-6 p-4">
      <h1 class="h3 mb-2"><?= $title ?></h1>
      <div class="muted mb-3"><?= $desc ?></div>
      <div class="d-flex flex-wrap gap-3 small muted mb-3">
        <span>Category: <?= $category ?></span>
        <span>Difficulty: <?= $difficulty ?></span>
        <span>Duration: <?= (int)$duration ?> hrs</span>
        <span>Rating: <?= number_format((float)$rating,1) ?></span>
        <span>Students: <?= number_format((int)$students) ?></span>
        <?php if ($instructor !== ''): ?><span>Instructor: <?= $instructor ?></span><?php endif; ?>
      </div>
      <div class="mb-3">
        <div class="fw-semibold">What’s included</div>
        <div class="small muted"><?= $features ?: '—' ?></div>
      </div>
      <div class="mb-3">
        <div class="fw-semibold">Objectives</div>
        <div class="small muted"><?= $objectives ?: '—' ?></div>
      </div>
      <div class="mb-3">
        <div class="fw-semibold">Requirements</div>
        <div class="small muted"><?= $requirements ?: '—' ?></div>
      </div>
      <div class="d-flex justify-content-between align-items-center my-3">
        <div class="price">Price: <?= $currencyTxt ?> <?= number_format((float)$priceVal, 2) ?></div>
        <button id="enrollBtn" class="btn btn-primary">Enroll Now</button>
      </div>
      <small class="text-secondary">Course ID: <?= $courseId ?></small>
      <div id="errorBox" class="alert alert-danger d-none mt-3"></div>
    </div>
  </div>
</div>

<script>
const enrollBtn = document.getElementById('enrollBtn');
const errorBox = document.getElementById('errorBox');
function showError(msg){ errorBox.textContent = msg; errorBox.classList.remove('d-none'); }

enrollBtn.addEventListener('click', async (e)=>{
  e.preventDefault();
  errorBox.classList.add('d-none');
  try{
    const res = await fetch(location.href, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({action:'create_stripe_session'})
    });
    const data = await res.json();
    if(!res.ok){ throw new Error(data.error || 'Failed to start checkout'); }
    if(!data.checkout_url){ throw new Error('No checkout URL'); }
    window.location.assign(data.checkout_url);
  }catch(err){
    showError(err.message);
  }
});
</script>
</body>
</html>
