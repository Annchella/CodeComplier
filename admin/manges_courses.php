<!-- ===========================
     FILE 1: admin.html (Admin Panel)
     Features: Add, Edit, Delete courses (saved in localStorage)
     Includes a clear "Edit mode" vs "Add mode" to avoid the
     common issue where a second course keeps updating the first.
     =========================== -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Panel - Manage Courses</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <style>
    .btn { @apply px-3 py-2 rounded font-medium; }
    .btn-primary { @apply bg-blue-600 text-white; }
    .btn-secondary { @apply bg-gray-200 text-gray-800; }
    .btn-danger { @apply bg-red-600 text-white; }
    .badge { @apply text-xs px-2 py-1 rounded; }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl md:text-3xl font-bold">Admin Panel – Manage Courses</h1>
      <a href="courses.html" class="btn btn-secondary">Go to User Page</a>
    </div>

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
      <div class="flex items-center justify-between mb-4">
        <h2 id="formModeTitle" class="text-xl font-bold">Add New Course</h2>
        <div class="space-x-2">
          <button id="newCourseBtn" type="button" class="btn btn-secondary hidden">+ New Course</button>
        </div>
      </div>

      <form id="courseForm" class="grid md:grid-cols-2 gap-4" autocomplete="off">
        <input type="hidden" name="id" />

        <div>
          <label class="block text-sm font-medium mb-1">Title *</label>
          <input type="text" name="title" class="w-full border rounded px-3 py-2" required />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Price (₹) *</label>
          <input type="number" name="price" min="0" step="0.01" class="w-full border rounded px-3 py-2" required />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Category *</label>
          <input type="text" name="category" placeholder="web | mobile | data | ai | backend" class="w-full border rounded px-3 py-2" required />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Difficulty *</label>
          <select name="difficulty" class="w-full border rounded px-3 py-2" required>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Duration (hours) *</label>
          <input type="number" name="duration" min="1" class="w-full border rounded px-3 py-2" required />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Rating (0-5)</label>
          <input type="number" name="rating" min="0" max="5" step="0.1" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Students</label>
          <input type="number" name="students" min="0" class="w-full border rounded px-3 py-2" />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Instructor *</label>
          <input type="text" name="instructor" class="w-full border rounded px-3 py-2" required />
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Status *</label>
          <select name="status" class="w-full border rounded px-3 py-2" required>
            <option value="active">Active</option>
            <option value="draft">Draft</option>
            <option value="archived">Archived</option>
          </select>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-1">Image URL *</label>
          <input type="url" name="image" class="w-full border rounded px-3 py-2" placeholder="https://.../image.jpg" required />
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-1">Description *</label>
          <textarea name="description" rows="3" class="w-full border rounded px-3 py-2" required></textarea>
        </div>

        <div class="md:col-span-2 flex gap-3 pt-2">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
        </div>
      </form>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-xl shadow p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">All Courses</h2>
        <div class="text-sm text-gray-600">(<span id="countLabel">0</span>)</div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="py-3 px-3">Title</th>
              <th class="py-3 px-3">Category</th>
              <th class="py-3 px-3">Price</th>
              <th class="py-3 px-3">Students</th>
              <th class="py-3 px-3">Rating</th>
              <th class="py-3 px-3">Status</th>
              <th class="py-3 px-3">Actions</th>
            </tr>
          </thead>
          <tbody id="courseTableBody"></tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // ---------- Storage Helpers ----------
    function loadCourses() {
      try {
        const raw = localStorage.getItem('courses');
        return raw ? JSON.parse(raw) : [];
      } catch (e) {
        console.error('Failed to parse courses from localStorage', e);
        return [];
      }
    }
    function saveCourses(list) {
      localStorage.setItem('courses', JSON.stringify(list));
    }
    function uid() {
      // Robust unique id for new entries
      return Date.now().toString(36) + Math.random().toString(36).slice(2, 8);
    }

    // ---------- State ----------
    let editingId = null; // null => add mode, otherwise edit mode

    // ---------- DOM ----------
    const form = document.getElementById('courseForm');
    const courseTableBody = document.getElementById('courseTableBody');
    const formModeTitle = document.getElementById('formModeTitle');
    const newCourseBtn = document.getElementById('newCourseBtn');
    const resetBtn = document.getElementById('resetBtn');
    const countLabel = document.getElementById('countLabel');

    // ---------- Render ----------
    function renderTable() {
      const courses = loadCourses();
      countLabel.textContent = courses.length;
      courseTableBody.innerHTML = '';

      if (!courses.length) {
        courseTableBody.innerHTML = `<tr><td colspan="7" class="py-6 px-3 text-center text-gray-500">No courses yet. Add your first course above.</td></tr>`;
        return;
      }

      courses.forEach((c) => {
        const tr = document.createElement('tr');
        tr.className = 'border-b last:border-0';
        tr.innerHTML = `
          <td class="py-3 px-3 font-medium">${c.title}</td>
          <td class="py-3 px-3">${c.category}</td>
          <td class="py-3 px-3">₹${Number(c.price).toLocaleString()}</td>
          <td class="py-3 px-3">${Number(c.students || 0).toLocaleString()}</td>
          <td class="py-3 px-3">${Number(c.rating || 0).toFixed(1)}</td>
          <td class="py-3 px-3">
            <span class="badge ${badgeClass(c.status)}">${capitalize(c.status)}</span>
          </td>
          <td class="py-3 px-3 space-x-2">
            <button class="btn btn-secondary" onclick="onEdit('${c.id}')">Edit</button>
            <button class="btn btn-danger" onclick="onDelete('${c.id}')">Delete</button>
          </td>`;
        courseTableBody.appendChild(tr);
      });
    }

    function badgeClass(status) {
      const s = (status || '').toLowerCase();
      if (s === 'active') return 'bg-green-100 text-green-700';
      if (s === 'draft') return 'bg-yellow-100 text-yellow-700';
      if (s === 'archived') return 'bg-gray-100 text-gray-700';
      return 'bg-gray-100 text-gray-700';
    }

    function capitalize(str) { return (str || '').charAt(0).toUpperCase() + (str || '').slice(1); }

    // ---------- Form Helpers ----------
    function clearForm() {
      form.reset();
      form.elements['id'].value = '';
      editingId = null;
      formModeTitle.textContent = 'Add New Course';
      newCourseBtn.classList.add('hidden');
    }

    function fillForm(data) {
      for (const key in data) {
        if (form.elements[key] !== undefined && form.elements[key] !== null) {
          form.elements[key].value = data[key];
        }
      }
    }

    // ---------- Events ----------
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      // If editingId exists, keep id; else create new id
      const id = editingId || formData.get('id') || uid();

      const course = {
        id,
        title: formData.get('title')?.trim(),
        price: parseFloat(formData.get('price')),
        category: formData.get('category')?.trim(),
        difficulty: formData.get('difficulty'),
        duration: parseInt(formData.get('duration')),
        rating: parseFloat(formData.get('rating')) || 4.5,
        students: parseInt(formData.get('students')) || 0,
        instructor: formData.get('instructor')?.trim(),
        status: formData.get('status')?.trim(),
        image: formData.get('image')?.trim(),
        description: formData.get('description')?.trim(),
        updated_at: new Date().toISOString(),
      };

      // Basic validation guard
      if (!course.title || !isFinite(course.price) || !course.category || !course.instructor || !course.image || !course.description) {
        alert('Please complete all required fields.');
        return;
      }

      const list = loadCourses();
      const idx = list.findIndex((x) => x.id === id);
      if (idx > -1) list[idx] = course; else list.push(course);
      saveCourses(list);

      // After save, always return to Add mode for easy adding of another
      clearForm();
      renderTable();
    });

    resetBtn.addEventListener('click', clearForm);

    newCourseBtn.addEventListener('click', clearForm);

    // Expose functions for table buttons
    window.onEdit = (id) => {
      const list = loadCourses();
      const course = list.find((c) => c.id === id);
      if (!course) return;
      fillForm(course);
      editingId = id;
      formModeTitle.textContent = 'Edit Course';
      newCourseBtn.classList.remove('hidden');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.onDelete = (id) => {
      if (!confirm('Delete this course?')) return;
      const list = loadCourses().filter((c) => c.id !== id);
      saveCourses(list);
      // If we were editing this one, exit edit mode
      if (editingId === id) clearForm();
      renderTable();
    };

    // Initial render
    renderTable();
  </script>
</body>
</html>


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
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .card { @apply bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden; }
    .pill { @apply text-xs px-2 py-1 rounded bg-gray-100; }
  </style>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-6xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl md:text-3xl font-bold">Available Courses</h1>
      <div class="flex items-center gap-2">
        <button id="refreshBtn" class="px-3 py-2 rounded bg-gray-200">Refresh</button>
        <a href="admin.html" class="px-3 py-2 rounded bg-indigo-600 text-white">Admin</a>
      </div>
    </div>

    <div id="coursesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    <div id="emptyState" class="hidden text-center text-gray-500 py-16">No active courses available yet.</div>
  </div>

  <script>
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
        empty.classList.remove('hidden');
        return;
      }
      empty.classList.add('hidden');

      active.forEach(course => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
          <img src="${course.image}" alt="${course.title}" class="w-full h-48 object-cover" />
          <div class="p-5">
            <div class="flex items-center justify-between mb-2">
              <h3 class="text-lg font-bold">${course.title}</h3>
              
            </div>
            
            
          </div>`;
        grid.appendChild(card);
      });
    }

    document.getElementById('refreshBtn').addEventListener('click', renderCourses);
    renderCourses();
  </script>
</body>
</html>
