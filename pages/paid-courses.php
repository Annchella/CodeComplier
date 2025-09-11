<!-- ===========================
     FILE 2: courses.html (User Side)
     Shows only courses where status === 'active' (case-insensitive)
     =========================== -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Courses – LearnHub</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <!-- AOS (Animate On Scroll) -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .glass-card {
      background: rgba(255,255,255,0.85);
      border-radius: 1.2rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.12);
      backdrop-filter: blur(6px);
      transition: transform 0.18s, box-shadow 0.18s;
      overflow: hidden;
      border: 1px solid rgba(255,255,255,0.25);
    }
    .glass-card:hover {
      transform: translateY(-6px) scale(1.03);
      box-shadow: 0 16px 40px rgba(0,0,0,0.18);
    }
    .course-img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: #f3f4f6;
      border-top-left-radius: 1.2rem;
      border-top-right-radius: 1.2rem;
    }
    .pill {
      display: inline-block;
      padding: 0.3em 0.9em;
      border-radius: 1em;
      font-size: 0.85em;
      font-weight: 500;
      background: #e0e7ff;
      color: #4338ca;
      margin-left: 0.5rem;
    }
    .enroll-btn {
      border-radius: 2em;
      font-weight: 500;
      padding: 0.5em 1.5em;
      font-size: 1em;
      background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      border: none;
      transition: background 0.2s, box-shadow 0.2s;
      box-shadow: 0 2px 8px rgba(102,126,234,0.08);
    }
    .enroll-btn:hover {
      background: linear-gradient(90deg, #ff6b35 0%, #f7931e 100%);
      color: #fff;
      box-shadow: 0 4px 16px rgba(255,107,53,0.13);
    }
    .card-title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #2d3748;
    }
    .card-desc {
      color: #6b7280;
      min-height: 48px;
      margin-bottom: 1rem;
    }
    .card-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 1.2rem;
    }
    .price {
      color: #764ba2;
      font-weight: bold;
      font-size: 1.15rem;
    }
    .admin-btn {
      border-radius: 2em;
      font-weight: 500;
      background: #667eea;
      color: #fff;
      padding: 0.5em 1.2em;
      border: none;
      transition: background 0.2s;
    }
    .admin-btn:hover {
      background: #764ba2;
      color: #fff;
    }
    .refresh-btn {
      border-radius: 2em;
      font-weight: 500;
      background: #f3f4f6;
      color: #764ba2;
      padding: 0.5em 1.2em;
      border: none;
      transition: background 0.2s;
    }
    .refresh-btn:hover {
      background: #ffe29f;
      color: #ff6b35;
    }
    .empty-state {
      color: #888;
      padding: 4rem 0;
    }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h1 class="fw-bold mb-0 animate__animated animate__fadeInDown">
        <i class="fas fa-graduation-cap text-primary"></i> Available Courses
      </h1>
      <div class="d-flex align-items-center gap-2">
        <button id="refreshBtn" class="refresh-btn me-2" title="Refresh">
          <i class="fas fa-sync-alt"></i>
        </button>
        
      </div>
    </div>
    <div id="coursesGrid" class="row g-4"></div>
    <div id="emptyState" class="empty-state text-center d-none animate__animated animate__fadeIn">
      <i class="fas fa-box-open fa-2x mb-2"></i>
      <div>No active courses available yet.</div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
    function loadCourses() {
      try {
        const raw = localStorage.getItem('courses');
        return raw ? JSON.parse(raw) : [];
      } catch (e) {
        console.error('Failed to parse courses from localStorage', e);
        return [];
      }
    }

    function renderCourses() {
      const grid = document.getElementById('coursesGrid');
      const empty = document.getElementById('emptyState');
      grid.innerHTML = '';

      const active = loadCourses().filter(c => (c.status || '').toLowerCase() === 'active');
      if (!active.length) {
        empty.classList.remove('d-none');
        return;
      }
      empty.classList.add('d-none');

      active.forEach(course => {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';
        col.setAttribute('data-aos', 'zoom-in');
        col.innerHTML = `
          <div class="glass-card h-100 d-flex flex-column animate__animated animate__fadeInUp">
            <img src="${course.image || 'https://placehold.co/400x200?text=No+Image'}" alt="${course.title}" class="course-img" onerror="this.src='https://placehold.co/400x200?text=No+Image'"/>
            <div class="p-4 d-flex flex-column flex-grow-1">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="card-title">${course.title}</span>
                <span class="pill">${course.difficulty}</span>
              </div>
              <div class="card-desc mb-2">${course.description}</div>
              <div class="d-flex align-items-center justify-content-between text-secondary mb-2" style="font-size:0.97em;">
                <span title="Duration"><i class="far fa-clock"></i> ${Number(course.duration)} hrs</span>
                <span title="Students"><i class="fas fa-users"></i> ${Number(course.students || 0).toLocaleString()} students</span>
                <span title="Rating"><i class="fas fa-star text-warning"></i> ${(Number(course.rating || 0)).toFixed(1)}</span>
              </div>
              <div class="card-footer mt-auto">
                <span class="price">₹${Number(course.price).toLocaleString()}</span>
                <a href="courses_details.php?id=${course.id}" class="enroll-btn">
                  <i class="fas fa-arrow-right"></i> Enroll Now
                </a>
              </div>
            </div>
          </div>
        `;
        grid.appendChild(col);
      });
      AOS.refresh();
    }

    document.getElementById('refreshBtn').addEventListener('click', renderCourses);
    renderCourses();
  </script>
</body>
</html>


