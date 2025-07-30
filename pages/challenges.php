<?php
session_start();
include '../includes/Header.php';
include '../includes/Navbar.php';
require_once '../includes/db.php';

// Get filter parameters
$difficulty = $_GET['difficulty'] ?? 'all';
$category = $_GET['category'] ?? 'all';
$sort = $_GET['sort'] ?? 'newest';
$search = $_GET['search'] ?? '';

// Build query
$query = "SELECT c.*, u.username as added_by_name FROM challenges c 
          LEFT JOIN users u ON c.added_by = u.id";
$conditions = [];
$params = [];

if ($difficulty !== 'all') {
    $conditions[] = "c.difficulty = ?";
    $params[] = $difficulty;
}

if ($category !== 'all') {
    $conditions[] = "c.category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $conditions[] = "(c.title LIKE ? OR c.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Add sorting
switch ($sort) {
    case 'difficulty':
        $query .= " ORDER BY FIELD(c.difficulty, 'easy', 'medium', 'hard')";
        break;
    case 'points':
        $query .= " ORDER BY c.points DESC";
        break;
    case 'title':
        $query .= " ORDER BY c.title ASC";
        break;
    default:
        $query .= " ORDER BY c.created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$challenges = $stmt->fetchAll();

// Get unique categories
$categories = $pdo->query("SELECT DISTINCT category FROM challenges WHERE category IS NOT NULL ORDER BY category")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Challenges - Code Compiler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ffa500 100%);
            --card-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 2px,
                rgba(255,255,255,0.03) 2px,
                rgba(255,255,255,0.03) 4px
            );
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .filter-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .challenge-card {
            background: white;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            height: 100%;
            border: none;
            overflow: hidden;
            position: relative;
        }

        .challenge-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .challenge-card:hover::before {
            transform: scaleX(1);
        }

        .challenge-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .difficulty-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .difficulty-easy { background: var(--success-gradient); }
        .difficulty-medium { background: var(--warning-gradient); }
        .difficulty-hard { background: var(--danger-gradient); }

        .category-badge {
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .points-badge {
            background: linear-gradient(45deg, #ffd700 0%, #ffb347 100%);
            color: #333;
            border: none;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            border: none;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.8rem;
            color: white;
        }

        .stats-icon.bg-primary { background: var(--primary-gradient); }
        .stats-icon.bg-success { background: var(--success-gradient); }
        .stats-icon.bg-warning { background: var(--warning-gradient); }
        .stats-icon.bg-danger { background: var(--danger-gradient); }

        .btn-challenge {
            background: var(--primary-gradient);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-challenge::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-challenge:hover::before {
            left: 100%;
        }

        .btn-challenge:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .search-input {
            border-radius: 25px;
            padding: 0.8rem 1.5rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-select {
            border-radius: 15px;
            padding: 0.8rem 1rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .no-challenges {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .challenge-footer {
            background: rgba(102, 126, 234, 0.05);
            border-top: 1px solid rgba(102, 126, 234, 0.1);
        }

        .challenge-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
            color: #666;
        }

        .filter-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">
                        <i class="fas fa-code me-3"></i>Programming Challenges
                    </h1>
                    <p class="lead mb-4">
                        Master your coding skills with our comprehensive collection of programming challenges. 
                        From algorithmic puzzles to data structure problems - level up your programming game!
                    </p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-trophy me-3 fa-2x"></i>
                                <div>
                                    <h5 class="mb-0"><?= count($challenges) ?></h5>
                                    <span>Active Challenges</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users me-3 fa-2x"></i>
                                <div>
                                    <h5 class="mb-0">1K+</h5>
                                    <span>Active Coders</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-medal me-3 fa-2x"></i>
                                <div>
                                    <h5 class="mb-0">24/7</h5>
                                    <span>Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-laptop-code pulse-animation" style="font-size: 10rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <h5 class="mb-4">
                <i class="fas fa-filter me-2"></i>Find Your Perfect Challenge
            </h5>
            <form method="GET" id="filterForm">
                <div class="row g-4">
                    <div class="col-md-3">
                        <label class="filter-label">
                            <i class="fas fa-search me-2"></i>Search Challenges
                        </label>
                        <input type="text" name="search" class="form-control search-input" 
                               placeholder="Search by title..." 
                               value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">
                            <i class="fas fa-layer-group me-2"></i>Difficulty
                        </label>
                        <select name="difficulty" class="form-select">
                            <option value="all">All Levels</option>
                            <option value="easy" <?= $difficulty === 'easy' ? 'selected' : '' ?>>Easy</option>
                            <option value="medium" <?= $difficulty === 'medium' ? 'selected' : '' ?>>Medium</option>
                            <option value="hard" <?= $difficulty === 'hard' ? 'selected' : '' ?>>Hard</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">
                            <i class="fas fa-tags me-2"></i>Category
                        </label>
                        <select name="category" class="form-select">
                            <option value="all">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat['category']) ?>" 
                                        <?= $category === $cat['category'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['category']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="filter-label">
                            <i class="fas fa-sort me-2"></i>Sort By
                        </label>
                        <select name="sort" class="form-select">
                            <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                            <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Title</option>
                            <option value="difficulty" <?= $sort === 'difficulty' ? 'selected' : '' ?>>Difficulty</option>
                            <option value="points" <?= $sort === 'points' ? 'selected' : '' ?>>Points</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-challenge w-100">
                            <i class="fas fa-search me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon bg-primary">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3 class="fw-bold text-primary"><?= count($challenges) ?></h3>
                    <p class="text-muted mb-0">Total Challenges</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon bg-success">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="fw-bold text-success">
                        <?= count(array_filter($challenges, function($c) { return $c['difficulty'] === 'easy'; })) ?>
                    </h3>
                    <p class="text-muted mb-0">Easy Challenges</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon bg-warning">
                        <i class="fas fa-fire"></i>
                    </div>
                    <h3 class="fw-bold text-warning">
                        <?= count(array_filter($challenges, function($c) { return $c['difficulty'] === 'medium'; })) ?>
                    </h3>
                    <p class="text-muted mb-0">Medium Challenges</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stats-card">
                    <div class="stats-icon bg-danger">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="fw-bold text-danger">
                        <?= count(array_filter($challenges, function($c) { return $c['difficulty'] === 'hard'; })) ?>
                    </h3>
                    <p class="text-muted mb-0">Hard Challenges</p>
                </div>
            </div>
        </div>

        <!-- Challenges Grid -->
        <div class="row">
            <?php if (empty($challenges)): ?>
                <div class="col-12">
                    <div class="no-challenges">
                        <i class="fas fa-search fa-4x mb-4 text-muted"></i>
                        <h3>No challenges found</h3>
                        <p class="lead">Try adjusting your filters or search terms to find more challenges.</p>
                        <a href="challenges.php" class="btn btn-challenge mt-3">
                            <i class="fas fa-refresh me-2"></i>View All Challenges
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($challenges as $challenge): ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card challenge-card">
                            <div class="card-body p-4">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0 flex-grow-1 fw-bold">
                                        <?= htmlspecialchars($challenge['title']) ?>
                                    </h5>
                                    <span class="badge difficulty-badge difficulty-<?= $challenge['difficulty'] ?> text-white">
                                        <?= ucfirst($challenge['difficulty']) ?>
                                    </span>
                                </div>

                                <!-- Description -->
                                <p class="challenge-description mb-4">
                                    <?= htmlspecialchars($challenge['description']) ?>
                                </p>

                                <!-- Badge Row -->
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <span class="badge points-badge">
                                            <i class="fas fa-coins me-1"></i><?= $challenge['points'] ?> pts
                                        </span>
                                    </div>
                                    <div class="col-6 text-end">
                                        <?php if ($challenge['category']): ?>
                                            <span class="badge category-badge">
                                                <?= htmlspecialchars($challenge['category']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Language & Time Info -->
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="fab fa-js-square me-1"></i>
                                            <?= ucfirst($challenge['language']) ?>
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= $challenge['time_limit'] ?>ms
                                        </small>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="d-grid">
                                    <a href="challenge-detail.php?id=<?= $challenge['id'] ?>" 
                                       class="btn btn-challenge text-white">
                                        <i class="fas fa-play me-2"></i>Start Challenge
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="card-footer challenge-footer">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        <?= htmlspecialchars($challenge['added_by_name'] ?? 'Admin') ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= date('M j, Y', strtotime($challenge['created_at'])) ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-submit form when filters change
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Search with debounce
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 800);
        });

        // Add smooth scrolling to challenge cards
        document.querySelectorAll('.challenge-card').forEach(card => {
            card.addEventListener('click', function(e) {
                if (e.target.tagName !== 'A') {
                    const link = this.querySelector('a[href*="challenge-detail"]');
                    if (link) {
                        window.location.href = link.href;
                    }
                }
            });
        });
    </script>
</body>
</html>
<?php


// Get filter parameters
$difficulty = $_GET['difficulty'] ?? 'all';
$category = $_GET['category'] ?? 'all';
$sort = $_GET['sort'] ?? 'newest';
$search = $_GET['search'] ?? '';

// Build query
$query = "SELECT c.*, u.username as added_by_name FROM challenges c 
          LEFT JOIN users u ON c.added_by = u.id";
$conditions = [];
$params = [];

if ($difficulty !== 'all') {
    $conditions[] = "c.difficulty = ?";
    $params[] = $difficulty;
}

if ($category !== 'all') {
    $conditions[] = "c.category = ?";
    $params[] = $category;
}

if (!empty($search)) {
    $conditions[] = "(c.title LIKE ? OR c.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Add sorting
switch ($sort) {
    case 'difficulty':
        $query .= " ORDER BY FIELD(c.difficulty, 'easy', 'medium', 'hard')";
        break;
    case 'points':
        $query .= " ORDER BY c.points DESC";
        break;
    case 'title':
        $query .= " ORDER BY c.title ASC";
        break;
    default:
        $query .= " ORDER BY c.created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$challenges = $stmt->fetchAll();

// Get unique categories
$categories = $pdo->query("SELECT DISTINCT category FROM challenges WHERE category IS NOT NULL ORDER BY category")->fetchAll();
?>

