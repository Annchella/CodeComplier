<?php
session_start();
require_once __DIR__ . '/../includes/Db.php';

// ✅ Ensure user logged in
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user']['id'];

// ✅ Fetch purchased courses for this user
$stmt = $pdo->prepare("
    SELECT c.*, p.purchased_at
    FROM purchases p
    JOIN courses c ON p.course_id = c.id
    WHERE p.user_id = ?
    ORDER BY p.purchased_at DESC
");
$stmt->execute([$user_id]);
$courses = $stmt->fetchAll();

// Get user info for personalization (adjust column name as needed)
try {
    $userStmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $userStmt->execute([$user_id]);
    $user = $userStmt->fetch();
    $userName = $user ? $user['username'] : 'Student';
} catch (PDOException $e) {
    // If username column doesn't exist, try other common column names or use fallback
    $userName = $_SESSION['user']['username'] ?? $_SESSION['user']['email'] ?? 'Student';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Courses – LearnHub</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Crimson+Text:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: #fafbfc;
      color: #2d3748;
      line-height: 1.6;
      min-height: 100vh;
    }

    .header {
      background: white;
      border-bottom: 1px solid #e2e8f0;
      padding: 20px 0;
      margin-bottom: 40px;
    }

    .header-content {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      font-family: 'Crimson Text', serif;
      font-size: 1.8rem;
      font-weight: 600;
      color: #2d3748;
      text-decoration: none;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 15px;
      color: #4a5568;
      font-size: 14px;
    }

    .user-avatar {
      width: 36px;
      height: 36px;
      background: linear-gradient(135deg, #4299e1, #3182ce);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 600;
      font-size: 14px;
    }

    .container {
      max-width: 1100px;
      margin: 0 auto;
      padding: 0 20px 40px;
      margin-top :80px; 
    }

    .page-header {
      margin-bottom: 40px;
    }

    .page-title {
      font-family: 'Crimson Text', serif;
      font-size: 2.5rem;
      font-weight: 600;
      color: #1a202c;
      margin-bottom: 8px;
    }

    .page-subtitle {
      color: #4a5568;
      font-size: 1.1rem;
    }

    .stats-bar {
      display: flex;
      gap: 30px;
      margin: 30px 0;
      padding: 20px 0;
      border-top: 1px solid #e2e8f0;
      border-bottom: 1px solid #e2e8f0;
    }

    .stat-item {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #4a5568;
      font-size: 14px;
    }

    .stat-number {
      font-weight: 600;
      color: #2d3748;
    }

    .empty-state {
      text-align: center;
      padding: 80px 20px;
      background: white;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .empty-icon {
      width: 80px;
      height: 80px;
      background: #f7fafc;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: #a0aec0;
      font-size: 2rem;
    }

    .empty-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 10px;
    }

    .empty-text {
      color: #4a5568;
      margin-bottom: 25px;
      font-size: 1rem;
    }

    .courses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 30px;
    }

    .course-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      border: 1px solid #e2e8f0;
      transition: all 0.3s ease;
      position: relative;
    }

    .course-card:hover {
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      transform: translateY(-2px);
    }

    .course-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .course-content {
      padding: 25px;
    }

    .course-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #1a202c;
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .course-description {
      color: #4a5568;
      font-size: 14px;
      line-height: 1.6;
      margin-bottom: 20px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .course-meta {
      display: flex;
      justify-content: between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 13px;
      color: #718096;
    }

    .purchased-date {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .progress-section {
      margin-bottom: 20px;
    }

    .progress-label {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: #4a5568;
      margin-bottom: 6px;
    }

    .progress-bar {
      width: 100%;
      height: 6px;
      background: #e2e8f0;
      border-radius: 3px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #48bb78, #38a169);
      border-radius: 3px;
      transition: width 0.3s ease;
    }

    .course-actions {
      display: flex;
      gap: 10px;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 20px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      flex: 1;
    }

    .btn-primary {
      background: #4299e1;
      color: white;
      border: 1px solid #4299e1;
    }

    .btn-primary:hover {
      background: #3182ce;
      border-color: #3182ce;
      transform: translateY(-1px);
      text-decoration: none;
      color: white;
    }

    .btn-outline {
      background: white;
      color: #4a5568;
      border: 1px solid #e2e8f0;
    }

    .btn-outline:hover {
      background: #f7fafc;
      border-color: #cbd5e0;
      text-decoration: none;
      color: #4a5568;
    }

    .btn-browse {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: #4299e1;
      color: white;
      border: 1px solid #4299e1;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.2s ease;
    }

    .btn-browse:hover {
      background: #3182ce;
      border-color: #3182ce;
      transform: translateY(-1px);
      text-decoration: none;
      color: white;
    }

    .enrollment-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(72, 187, 120, 0.9);
      color: white;
      font-size: 12px;
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 12px;
    }

    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
      }

      .container {
        padding: 0 16px 40px;
      }

      .page-title {
        font-size: 2rem;
      }

      .stats-bar {
        flex-direction: column;
        gap: 15px;
      }

      .courses-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .course-content {
        padding: 20px;
      }

      .course-actions {
        flex-direction: column;
      }
    }

    .filter-tabs {
      display: flex;
      gap: 10px;
      margin-bottom: 30px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 0;
    }

    .filter-tab {
      padding: 12px 20px;
      background: none;
      border: none;
      color: #4a5568;
      font-weight: 500;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s ease;
    }

    .filter-tab.active {
      color: #4299e1;
      border-bottom-color: #4299e1;
    }

    .filter-tab:hover {
      color: #2d3748;
    }
  </style>
</head>
<body>
  <?php include_once "../includes/Navbar.php"; ?>

  <div class="container">
    <div class="page-header">
      <h1 class="page-title">My Learning Journey</h1>
      <p class="page-subtitle">Continue where you left off and explore your enrolled courses</p>
      
      <div class="stats-bar">
        <div class="stat-item">
          <i class="fas fa-book-open"></i>
          <span>Enrolled Courses: <span class="stat-number"><?= count($courses) ?></span></span>
        </div>
        <div class="stat-item">
          <i class="fas fa-clock"></i>
          <span>Total Learning Hours: <span class="stat-number"><?= count($courses) * 8 ?>+</span></span>
        </div>
        <div class="stat-item">
          <i class="fas fa-certificate"></i>
          <span>Certificates Earned: <span class="stat-number"><?= min(count($courses), 2) ?></span></span>
        </div>
      </div>
    </div>

    <?php if (!$courses): ?>
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-book-open"></i>
        </div>
        <h2 class="empty-title">Start Your Learning Journey</h2>
        <p class="empty-text">You haven't enrolled in any courses yet. Discover amazing courses and start learning something new today!</p>
        <a href="courses.php" class="btn-browse">
          <i class="fas fa-search"></i> Browse Courses
        </a>
      </div>
    <?php else: ?>
      <div class="filter-tabs">
        <button class="filter-tab active">
          <i class="fas fa-play-circle"></i> All Courses
        </button>
        <button class="filter-tab">
          <i class="fas fa-clock"></i> In Progress
        </button>
        <button class="filter-tab">
          <i class="fas fa-check-circle"></i> Completed
        </button>
      </div>

      <div class="courses-grid">
        <?php foreach ($courses as $index => $course): ?>
          <div class="course-card">
            <div class="enrollment-badge">
              <i class="fas fa-check"></i> Enrolled
            </div>
            
            <?php if (!empty($course['image'])): ?>
              <img src="<?= htmlspecialchars($course['image']) ?>" class="course-image" alt="Course Image">
            <?php endif; ?>
            
            <div class="course-content">
              <h3 class="course-title"><?= htmlspecialchars($course['title']) ?></h3>
              <p class="course-description"><?= htmlspecialchars($course['description']) ?></p>
              
              <div class="course-meta">
                <div class="purchased-date">
                  <i class="fas fa-calendar"></i>
                  <span>Enrolled <?= date('M j, Y', strtotime($course['purchased_at'])) ?></span>
                </div>
              </div>

              <div class="progress-section">
                <div class="progress-label">
                  <span>Progress</span>
                  <span><?= rand(15, 85) ?>% Complete</span>
                </div>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: <?= rand(15, 85) ?>%"></div>
                </div>
              </div>

              <div class="course-actions">
                <a href="course_content.php?id=<?= $course['id'] ?>" class="btn btn-primary">
                  <i class="fas fa-play"></i> Continue Learning
                </a>
                <a href="course_details.php?id=<?= $course['id'] ?>" class="btn btn-outline">
                  <i class="fas fa-info-circle"></i> Details
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script>
    // Simple filter functionality (for demo purposes)
    document.querySelectorAll('.filter-tab').forEach(tab => {
      tab.addEventListener('click', function() {
        // Remove active class from all tabs
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        // Add active class to clicked tab
        this.classList.add('active');
      });
    });

    // Add some interactivity to course cards
    document.querySelectorAll('.course-card').forEach(card => {
      card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
      });
      
      card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(-2px)';
      });
    });
  </script>
</body>
</html>