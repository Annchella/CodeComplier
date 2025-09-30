<?php
include('../includes/db.php'); // This sets up $pdo
include_once "../includes/Navbar.php";
$languages = ['Java','Python','C++','JavaScript'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Programming Notes Hub</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
  
  * {
    font-family: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  }
  
  body {
    background: 
      linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.85)),
      url('https://plus.unsplash.com/premium_photo-1666274789529-0fd5aa038fe5?q=80&w=735&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D') center/cover fixed;
    color: #1a1a1a;
    line-height: 1.6;
    min-height: 100vh;
  }
  
  .container-custom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
  }
  
  .card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: all 0.2s ease;
  }
  
  .card:hover {
    background: rgba(255, 255, 255, 0.98);
    border-color: #d1d5db;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
  }
  
  .header-section {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    padding: 3rem 0;
    margin-bottom: 3rem;
  }
  
  .search-box {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(229, 231, 235, 0.6);
    border-radius: 8px;
    transition: all 0.2s ease;
    position: relative;
  }
  
  .search-box:focus-within {
    background: rgba(255, 255, 255, 0.95);
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }
  
  .search-input {
    background: transparent;
    border: none;
    padding: 0.875rem 1rem 0.875rem 3rem;
    font-size: 1rem;
    width: 100%;
    outline: none;
    color: #1f2937;
  }
  
  .search-input::placeholder {
    color: #9ca3af;
  }
  
  .search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    transition: color 0.2s ease;
  }
  
  .search-box:focus-within .search-icon {
    color: #3b82f6;
  }
  
  .tab-container {
    border-bottom: 2px solid #f3f4f6;
    margin-bottom: 2.5rem;
    display: flex;
    gap: 0;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }
  
  .tab-container::-webkit-scrollbar {
    display: none;
  }
  
  .tab-button {
    background: transparent;
    border: none;
    padding: 1rem 1.5rem;
    font-size: 0.95rem;
    font-weight: 500;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s ease;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
  }
  
  .tab-button:hover {
    color: #374151;
    background: #f9fafb;
  }
  
  .tab-button.active {
    color: #1f2937;
    border-bottom-color: #3b82f6;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
  }
  
  .language-icon {
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
  }
  
  .note-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(229, 231, 235, 0.7);
    border-radius: 8px;
    padding: 1.5rem;
    transition: all 0.2s ease;
    position: relative;
    cursor: pointer;
  }
  
  .note-card:hover {
    background: rgba(255, 255, 255, 0.96);
    border-color: rgba(209, 213, 219, 0.8);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    transform: translateY(-1px);
  }
  
  .note-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #e5e7eb;
    border-radius: 8px 0 0 8px;
    transition: background 0.2s ease;
  }
  
  .note-card:hover::before {
    background: #3b82f6;
  }
  
  .note-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.75rem;
    line-height: 1.4;
  }
  
  .note-content {
    color: #4b5563;
    font-size: 0.925rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  
  .note-meta {
    display: flex;
    justify-content: between;
    align-items: center;
    font-size: 0.825rem;
    color: #6b7280;
    border-top: 1px solid #f3f4f6;
    padding-top: 1rem;
  }
  
  .note-date {
    display: flex;
    align-items: center;
    gap: 0.375rem;
  }
  
  .language-tag {
    background: #f3f4f6;
    color: #374151;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    font-family: 'JetBrains Mono', monospace;
  }
  
  .section-header {
    display: flex;
    align-items: center;
    justify-content: between;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #f3f4f6;
  }
  
  .section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
  }
  
  .section-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.125rem;
  }
  
  .count-badge {
    background: #f3f4f6;
    color: #374151;
    padding: 0.375rem 0.75rem;
    border-radius: 6px;
    font-size: 0.825rem;
    font-weight: 500;
  }
  
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    border: 2px dashed rgba(229, 231, 235, 0.8);
    border-radius: 12px;
    margin: 2rem 0;
  }
  
  .empty-icon {
    width: 64px;
    height: 64px;
    background: #f9fafb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: #9ca3af;
    font-size: 1.5rem;
  }
  
  .empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
  }
  
  .empty-description {
    color: #6b7280;
    font-size: 0.95rem;
  }
  
  .page-title {
    font-size: 2.25rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
  }
  
  .page-subtitle {
    color: #6b7280;
    font-size: 1.125rem;
    font-weight: 400;
  }
  
  .java-theme { background: #f7f3f0; color: #d97706; }
  .python-theme { background: #f0f9ff; color: #0284c7; }
  .cpp-theme { background: #f8fafc; color: #475569; }
  .javascript-theme { background: #fffbeb; color: #d97706; }
  
  .fade-in {
    animation: fadeIn 0.4s ease forwards;
    opacity: 0;
  }
  
  .fade-in.visible {
    opacity: 1;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  
  .more-options {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 32px;
    height: 32px;
    background: transparent;
    border: none;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    cursor: pointer;
    transition: all 0.2s ease;
    opacity: 0;
  }
  
  .note-card:hover .more-options {
    opacity: 1;
  }
  
  .more-options:hover {
    background: #f3f4f6;
    color: #6b7280;
  }
  
  @media (max-width: 768px) {
    .container-custom { padding: 1rem; }
    .page-title { font-size: 1.875rem; }
    .notes-grid { grid-template-columns: 1fr; gap: 1rem; }
    .tab-container { gap: 0; }
    .tab-button { padding: 0.75rem 1rem; font-size: 0.875rem; }
  }
</style>
</head>
<body>

<!-- <?php include_once "../includes/Navbar.php"; ?> -->

<div class="header-section">
  <div class="container-custom">
    <h1 class="page-title">Programming Notes</h1>
    <p class="page-subtitle">Organize and manage your coding knowledge efficiently</p>
  </div>
</div>

<main class="container-custom">

  <!-- Search -->
  <div class="card p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
      <i class="fas fa-search mr-2 text-blue-600"></i>
      Search Notes
    </h2>
    <div class="search-box">
      <i class="fas fa-search search-icon"></i>
      <input id="searchInput" type="text" placeholder="Search through your programming notes..." class="search-input">
    </div>
  </div>

  <!-- Tabs -->
  <div class="tab-container">
    <?php foreach($languages as $i=>$lang){ 
      $iconClass = '';
      $themeClass = '';
      switch($lang) {
        case 'Java': 
          $iconClass = 'fab fa-java'; 
          $themeClass = 'java-theme';
          break;
        case 'Python': 
          $iconClass = 'fab fa-python'; 
          $themeClass = 'python-theme';
          break;
        case 'C++': 
          $iconClass = 'fas fa-code'; 
          $themeClass = 'cpp-theme';
          break;
        case 'JavaScript': 
          $iconClass = 'fab fa-js-square'; 
          $themeClass = 'javascript-theme';
          break;
      }
    ?>
      <button class="tab-button <?= $i==0 ? 'active' : '' ?>" data-tab="<?= $lang ?>">
        <div class="language-icon <?= $themeClass ?> rounded">
          <i class="<?= $iconClass ?>"></i>
        </div>
        <?= $lang ?>
      </button>
    <?php } ?>
  </div>

  <!-- Notes Content -->
  <?php foreach($languages as $i=>$lang){
      $stmt = $pdo->prepare("SELECT * FROM notes WHERE language = ? ORDER BY created_at DESC");
      $stmt->execute([$lang]);
      $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $iconClass = '';
      $themeClass = '';
      switch($lang) {
        case 'Java': 
          $iconClass = 'fab fa-java'; 
          $themeClass = 'java-theme';
          break;
        case 'Python': 
          $iconClass = 'fab fa-python'; 
          $themeClass = 'python-theme';
          break;
        case 'C++': 
          $iconClass = 'fas fa-code'; 
          $themeClass = 'cpp-theme';
          break;
        case 'JavaScript': 
          $iconClass = 'fab fa-js-square'; 
          $themeClass = 'javascript-theme';
          break;
      }
  ?>
  <div class="tabContent <?= $i==0?'':'hidden' ?>" id="tab-<?= $lang ?>">
    <div class="section-header">
      <div class="section-title">
        <div class="section-icon <?= $themeClass ?>">
          <i class="<?= $iconClass ?>"></i>
        </div>
        <?= $lang ?> Notes
      </div>
      <div class="count-badge">
        <?= count($notes) ?> note<?= count($notes) !== 1 ? 's' : '' ?>
      </div>
    </div>
    
    <?php if ($notes): ?>
      <div class="notes-grid">
        <?php foreach ($notes as $note): 
          $date = date("M j, Y", strtotime($note['created_at']));
        ?>
          <div class="note-card noteCard fade-in">
            <button class="more-options">
              <i class="fas fa-ellipsis-h"></i>
            </button>
            
            <h3 class="note-title"><?= htmlspecialchars($note['title']); ?></h3>
            <p class="note-content"><?= htmlspecialchars($note['content']); ?></p>
            
            <div class="note-meta">
              <div class="note-date">
                <i class="far fa-calendar-alt"></i>
                <span><?= $date ?></span>
              </div>
              <div class="language-tag"><?= $lang ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <div class="empty-icon">
          <i class="<?= $iconClass ?>"></i>
        </div>
        <h3 class="empty-title">No <?= $lang ?> notes yet</h3>
        <p class="empty-description">Start documenting your <?= htmlspecialchars($lang) ?> knowledge and create your first note.</p>
      </div>
    <?php endif; ?>
  </div>
  <?php } ?>

</main>

<footer style="margin-top: 4rem; padding: 2rem 0; border-top: 1px solid rgba(229, 231, 235, 0.6); background: rgba(250, 250, 250, 0.8); backdrop-filter: blur(10px);">
  <div class="container-custom">
    <p style="text-align: center; color: #6b7280; font-size: 0.875rem;">
      Programming Notes Hub &copy; <?= date('Y') ?> - Streamline your coding journey
    </p>
  </div>
</footer>

<script>
const tabBtns = document.querySelectorAll('.tab-button');
const tabContents = document.querySelectorAll('.tabContent');

tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    // Update active tab
    tabBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    
    // Show corresponding content
    tabContents.forEach(tc => tc.classList.add('hidden'));
    document.getElementById('tab-' + btn.dataset.tab).classList.remove('hidden');
  });
});

// Search functionality with smooth animations
document.getElementById('searchInput').addEventListener('input', e => {
  const query = e.target.value.toLowerCase();
  const cards = document.querySelectorAll('.noteCard');
  
  cards.forEach(card => {
    const text = card.innerText.toLowerCase();
    const matches = text.includes(query);
    
    if (matches) {
      card.style.display = 'block';
      setTimeout(() => card.classList.add('visible'), 50);
    } else {
      card.style.display = 'none';
      card.classList.remove('visible');
    }
  });
});

// Initialize fade-in animations
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.fade-in');
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.classList.add('visible');
    }, index * 50);
  });
});

// Add smooth scrolling behavior
document.documentElement.style.scrollBehavior = 'smooth';
</script>

</body>
</html>