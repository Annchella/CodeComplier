<?php
// Use __DIR__ to build a reliable path to the shared includes folder
require_once __DIR__ . '/../includes/Db.php';
 include_once "../includes/Navbar.php";

// ✅ Get active courses only
$stmt = $pdo->query("SELECT * FROM courses WHERE status='active' ORDER BY created_at DESC");
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LearnHub - Available Courses</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #6c757d;
      --success: #06d6a0;
      --light-bg: #f8f9fa;
      --dark-text: #212529;
      --card-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
      --card-hover-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: var(--dark-text);
      padding-bottom: 2rem;
    }
    
    .navbar {
      background: linear-gradient(120deg, var(--primary), #3a56d4);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .hero-section {
      background: linear-gradient(135deg, #667eea, #764ba2), url('https://i.pinimg.com/736x/a9/a8/53/a9a8533cd3f519ab8928ef5696f16f9a.jpg');
      background-position: center;
      color: white;
      border-radius: 0 0 20px 20px;
      margin-bottom: 2rem;
      padding: 3rem 1rem;
      margin-top:70px;
    }
    
    .course-card {
      transition: all 0.3s ease;
      border: none;
      border-radius: 12px;
      overflow: hidden;
      height: 100%;
    }
    
    .course-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--card-hover-shadow);
    }
    
    .course-img {
      height: 200px;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .course-card:hover .course-img {
      transform: scale(1.05);
    }
    
    .card-body {
      display: flex;
      flex-direction: column;
    }
    
    .difficulty-badge {
      font-size: 0.75rem;
      padding: 0.35em 0.65em;
      border-radius: 50px;
    }
    
    .beginner-badge {
      background-color: #06d6a0;
      color: white;
    }
    
    .intermediate-badge {
      background-color: #ffd166;
      color: #000;
    }
    
    .advanced-badge {
      background-color: #ef476f;
      color: white;
    }
    
    .price-tag {
      font-weight: 700;
      font-size: 1.25rem;
      color: var(--primary);
    }
    
    .search-container {
      max-width: 500px;
      margin: 0 auto 2rem;
    }
    
    .search-box {
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      border: 2px solid #e9ecef;
      transition: all 0.3s;
    }
    
    .search-box:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
    
    .category-filter {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-bottom: 2rem;
    }
    
    .category-btn {
      border-radius: 50px;
      padding: 0.5rem 1.25rem;
      font-weight: 500;
      transition: all 0.3s;
      border: 2px solid transparent;
    }
    
    .category-btn:hover, .category-btn.active {
      background-color: var(--primary);
      color: white;
      border-color: var(--primary);
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
    }
    
    .empty-state i {
      font-size: 4rem;
      color: #dee2e6;
      margin-bottom: 1rem;
    }
    
    .footer {
      background-color: white;
      padding: 1.5rem 0;
      margin-top: 3rem;
      border-top: 1px solid #e9ecef;
    }
    
    .rating {
      color: #ffc107;
      margin-bottom: 0.5rem;
    }
    
    .enroll-btn {
      background: linear-gradient(to right, var(--primary), #5e72e4);
      border: none;
      border-radius: 50px;
      padding: 0.5rem 1.5rem;
      font-weight: 500;
      transition: all 0.3s;
    }
    
    .enroll-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }
    
    @media (max-width: 768px) {
      .hero-section {
        padding: 2rem 1rem;
      }
      
      .hero-title {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <!-- <?php include_once "../includes/Navbar.php"; ?> -->

  
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container text-center">
      <h1 class="hero-title fw-bold mb-3">Expand Your Knowledge</h1>
      <p class="lead mb-4">Discover top-quality courses taught by industry experts and advance your career</p>
      <div class="search-container">
        <div class="input-group">
          <input type="text" class="form-control search-box" placeholder="Search for courses...">
          <button class="btn btn-light" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </div>
  </section>

  <div class="container">
    <!-- Category Filters -->
    <div class="category-filter">
      <button class="btn category-btn active">All Courses</button>
      <button class="btn category-btn">Web Development</button>
      <button class="btn category-btn">Data Science</button>
      <button class="btn category-btn">Design</button>
      <button class="btn category-btn">Business</button>
    </div>

    <!-- Courses Grid -->
    <div class="row g-4">
      <?php if (!$courses): ?>
        <div class="col-12">
          <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>No courses available at the moment</h3>
            <p class="text-muted">Check back later for new courses</p>
          </div>
        </div>
      <?php endif; ?>

      <?php foreach ($courses as $course): 
        // Determine badge class based on difficulty
        $difficultyClass = 'beginner-badge';
        if (stripos($course['difficulty'] ?? '', 'intermediate') !== false) {
          $difficultyClass = 'intermediate-badge';
        } elseif (stripos($course['difficulty'] ?? '', 'advanced') !== false) {
          $difficultyClass = 'advanced-badge';
        }
        
        // Generate random rating for demo purposes
        $rating = number_format(rand(35, 50) / 10, 1);
        $reviewCount = rand(10, 200);
      ?>
        <div class="col-md-6 col-lg-4">
          <div class="card course-card shadow-sm">
            <div class="position-relative">
              <img src="<?= htmlspecialchars($course['image'] ?: 'https://placehold.co/400x200?text=No+Image') ?>" 
                   class="course-img card-img-top" 
                   alt="<?= htmlspecialchars($course['title']) ?>" 
                   onerror="this.src='https://placehold.co/400x200?text=No+Image'">
              <span class="position-absolute top-0 end-0 m-3 difficulty-badge <?= $difficultyClass ?>">
                <?= htmlspecialchars($course['difficulty'] ?? 'Unknown') ?>
              </span>
            </div>
            <div class="card-body">
              <div class="rating">
                <?php
                $fullStars = floor($rating);
                $hasHalfStar = ($rating - $fullStars) >= 0.5;
                
                for ($i = 0; $i < $fullStars; $i++) {
                  echo '<i class="fas fa-star"></i> ';
                }
                
                if ($hasHalfStar) {
                  echo '<i class="fas fa-star-half-alt"></i> ';
                  $fullStars++; // Count half star as one for empty stars calculation
                }
                
                for ($i = 0; $i < (5 - $fullStars); $i++) {
                  echo '<i class="far fa-star"></i> ';
                }
                ?>
                <span class="ms-1 small">(<?= $rating ?>)</span>
              </div>
              
              <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
              <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars($course['description']) ?></p>
              
              <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                  <i class="far fa-clock text-muted me-1"></i>
                  <span class="text-muted small"><?= htmlspecialchars($course['duration_hours'] ?? $course['duration'] ?? 'N/A') ?> hrs</span>
                  <span class="mx-2 text-muted">•</span>
                  <i class="fas fa-user-graduate text-muted me-1"></i>
                  <span class="text-muted small"><?= $reviewCount ?> reviews</span>
                </div>
              </div>
              
              <div class="mt-3 d-flex justify-content-between align-items-center">
                <span class="price-tag">₹<?= number_format($course['price'], 2) ?></span>
                <a href="course_details.php?id=<?= $course['id'] ?>" class="btn enroll-btn">
                  Enroll <i class="fas fa-arrow-right ms-1"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">&copy; 2023 LearnHub. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <div class="social-links">
            <a href="#" class="text-decoration-none text-secondary me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-decoration-none text-secondary me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-decoration-none text-secondary me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-decoration-none text-secondary"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Simple filter functionality for demonstration
    document.addEventListener('DOMContentLoaded', function() {
      const categoryButtons = document.querySelectorAll('.category-btn');
      const courseCards = document.querySelectorAll('.course-card');
      
      categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Remove active class from all buttons
          categoryButtons.forEach(btn => btn.classList.remove('active'));
          
          // Add active class to clicked button
          this.classList.add('active');
          
          // In a real implementation, you would filter courses here
          // For this demo, we're just showing a toast notification
          const category = this.textContent;
          alert(`Filtering by: ${category}. This would filter courses in a real implementation.`);
        });
      });
      
      // Search functionality
      const searchBox = document.querySelector('.search-box');
      searchBox.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        
        courseCards.forEach(card => {
          const title = card.querySelector('.card-title').textContent.toLowerCase();
          const description = card.querySelector('.card-text').textContent.toLowerCase();
          
          if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>