<?php
session_start();
include '../includes/db.php'; // PDO connection
include '../includes/Header.php';
include 'admin_navbar.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// ------------------------
// Handle Update User
// ------------------------
if (isset($_POST['update_user'])) {
    $id = intval($_POST['user_id']);
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET username=?, email=?, role=? WHERE id=?");
    $stmt->execute([$username, $email, $role, $id]);
    $msg = "User updated successfully!";
}

// ------------------------
// Handle Delete User
// ------------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user && $user['role'] !== 'admin') {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$id]);
        $msg = "User deleted successfully!";
    } else {
        $msg = "Cannot delete an admin!";
    }
}

// ------------------------
// Handle Block/Unblock User
// ------------------------
if (isset($_GET['toggle'])) {
    $id = intval($_GET['toggle']);
    $stmt = $pdo->prepare("SELECT role, status FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user && $user['role'] !== 'admin') {
        $new_status = ($user['status'] ?? 'active') == 'active' ? 'blocked' : 'active';
        $stmt = $pdo->prepare("UPDATE users SET status=? WHERE id=?");
        $stmt->execute([$new_status, $id]);
        $msg = "User status updated!";
    } else {
        $msg = "Cannot block/unblock an admin!";
    }
}

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Edit user if requested
$edit_user = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $edit_user = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Manage Users</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    :root{
      /* Provided Unsplash image URL */
      --bg-image: url('https://images.unsplash.com/photo-1432839318976-b5c5785ce43f?q=80&w=1920&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGV');
    }
    /* Background + overlay */
    .page-bg{
      position: fixed; inset: 0; z-index: -2;
      background-image: var(--bg-image);
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover; /* full-bleed background */
    }
    .page-overlay{
      position: fixed; inset: 0; z-index: -1;
      background: rgba(17,24,39,0.45); /* contrast layer for readability */
    }

    /* Translucent surfaces */
    .card-surface{
      background-color: rgba(255,255,255,0.8);
      border: 1px solid rgba(226,232,240,0.75);
      backdrop-filter: blur(6px);
      -webkit-backdrop-filter: blur(6px);
      box-shadow: 0 8px 24px rgba(16,24,40,.08);
    }
    .table thead th{
      background: rgba(1, 5, 9, 0.9);
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
        <h2 class="h4 mb-1">Admin - Manage Users</h2>
        <p class="text-muted mb-0">Edit roles, block or delete users, and review account details.</p>
      </div>
    </div>

    <?php if(isset($msg)): ?>
      <div class="alert alert-info card-surface border-0"><?= $msg ?></div>
    <?php endif; ?>

    <?php if ($edit_user): ?>
      <div class="card card-surface mb-4">
        <div class="card-header">Edit User: <?= htmlspecialchars($edit_user['username']) ?></div>
        <div class="card-body">
          <form method="post" class="row g-3">
            <input type="hidden" name="user_id" value="<?= $edit_user['id'] ?>">
            <div class="col-md-4">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($edit_user['username']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($edit_user['email']) ?>" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Role</label>
              <select name="role" class="form-select">
                <option value="free" <?= $edit_user['role']=='free'?'selected':'' ?>>Free</option>
                <option value="premium" <?= $edit_user['role']=='premium'?'selected':'' ?>>Premium</option>
                <option value="admin" <?= $edit_user['role']=='admin'?'selected':'' ?>>Admin</option>
              </select>
            </div>
            <div class="col-12 d-flex gap-2">
              <button type="submit" name="update_user" class="btn btn-success">
                <i class="fa fa-save me-1"></i> Update
              </button>
              <a href="admin_manage-user.php" class="btn btn-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    <?php endif; ?>

    <div class="card card-surface">
      <div class="card-body">
        <div class="table-responsive">
          <table id="usersTable" class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
              <tr>
                <th style="width:70px">ID</th>
                <th style="min-width:160px">Username</th>
                <th style="min-width:220px">Email</th>
                <th style="width:120px">Role</th>
                <th style="width:120px">Status</th>
                <th style="min-width:180px">Created At</th>
                <th style="min-width:160px">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td><?= $user['id'] ?></td>
                  <td><?= htmlspecialchars($user['username']) ?></td>
                  <td><?= htmlspecialchars($user['email']) ?></td>
                  <td><?= ucfirst($user['role']) ?></td>
                  <td>
                    <?php if(($user['status'] ?? 'active') == 'active'): ?>
                      <span class="badge bg-success">Active</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Blocked</span>
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($user['created_at'] ?? '-') ?></td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="admin_manage-user.php?edit=<?= $user['id'] ?>" class="btn btn-sm btn-primary" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      <?php if($user['role'] !== 'admin'): ?>
                        <button class="btn btn-sm btn-warning" title="Block/Unblock" onclick="toggleStatus(<?= $user['id'] ?>, '<?= htmlspecialchars($user['status'] ?? 'active') ?>')">
                          <i class="fa fa-user-slash"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteUser(<?= $user['id'] ?>)">
                          <i class="fa fa-trash"></i>
                        </button>
                      <?php else: ?>
                        <span class="text-muted">No actions</span>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#usersTable').DataTable({
        order: [[ 0, 'desc' ]],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']]
      });
    });

    function deleteUser(id){
      Swal.fire({
        title: 'Delete this user?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = 'admin_manage-user.php?delete=' + id;
        }
      });
    }

    function toggleStatus(id, currentStatus){
      const action = currentStatus === 'active' ? 'block' : 'unblock';
      Swal.fire({
        title: `Confirm ${action}`,
        text: `Do you want to ${action} this user?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Yes, ${action}`
      }).then((result) => {
        if(result.isConfirmed){
          window.location.href = 'admin_manage-user.php?toggle=' + id;
        }
      });
    }
  </script>

  <!-- Background layers at end of body for progressive paint -->
  <div class="page-bg" aria-hidden="true"></div>
  <div class="page-overlay" aria-hidden="true"></div>
</body>
</html>
