<?php
// admin_manage_payments.php
session_start();
require_once '../includes/db.php';
include '../admin/admin_navbar.php';

// Debugging – remove later
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch all purchases
try {
    $stmt = $pdo->query("
        SELECT p.id, p.user_id, p.course_id, p.purchased_price AS amount, 
               p.purchased_at AS payment_date,
               u.username, c.title AS course_title
        FROM purchases p
        LEFT JOIN users u ON p.user_id = u.id
        LEFT JOIN courses c ON p.course_id = c.id
        ORDER BY p.purchased_at DESC
    ");
    $purchases = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Query failed: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Payments</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <style>
    :root{
      /* Requested Pinterest background (consider hosting locally for reliability) */
      --bg-image: url('https://i.pinimg.com/736x/fb/a8/ca/fba8cabecc76464c8a5e8fb46bcb533d.jpg');
    }
    /* Full-page background and overlay for contrast */
    .page-bg{
      position: fixed; inset: 0; z-index: -2;
      background-image: var(--bg-image);
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
    }
    .page-overlay{
      position: fixed; inset: 0; z-index: -1;
      background: rgba(17,24,39,0.45);
    }

    /* Translucent, elegant surfaces with blur */
    .card-surface{
      background-color: rgba(255,255,255,0.8);
      border: 1px solid rgba(226,232,240,0.75);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      box-shadow: 0 8px 24px rgba(16,24,40,.08);
    }
    .table thead th{
      background: rgba(248,250,252,0.9);
    }
    .table-hover tbody tr:hover{
      background-color: rgba(255,255,255,0.65);
    }
  </style>
</head>
<body class="bg-transparent">

  <!-- Background layers -->
  <div class="page-bg" aria-hidden="true"></div>
  <div class="page-overlay" aria-hidden="true"></div>

  <div class="container py-4 py-md-5">
    <div class="card card-surface mb-4">
      <div class="card-body">
        <h2 class="h4 mb-1">Manage Payment Details</h2>
        <p class="text-muted mb-0">Track user purchases and payment history.</p>
      </div>
    </div>

    <?php if (empty($purchases)): ?>
      <div class="alert alert-info card-surface border-0">No purchases found.</div>
    <?php else: ?>
      <div class="card card-surface">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th style="width:80px">ID</th>
                  <th style="width:200px">User</th>
                  <th>Course</th>
                  <th style="width:140px">Amount</th>
                  <th style="width:200px">Date</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($purchases as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['username'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['course_title'] ?? 'N/A') ?></td>
                    <td>₹<?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
