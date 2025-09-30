<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            transition: margin-left 0.3s ease;
        }

        /* Top Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
            transition: all 0.3s ease;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .logo {
            color: white;
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo i {
            font-size: 28px;
        }

        .header-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
            display: none;
        }

        .search-box input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 8px 40px 8px 15px;
            color: white;
            width: 300px;
            transition: all 0.3s ease;
        }

        .search-box input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .search-box input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            width: 350px;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
        }

        .user-menu {
            position: relative;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .user-info:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b6b, #feca57);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            padding: 10px;
            display: none;
            z-index: 1001;
            margin-top: 10px;
        }

        .user-dropdown.show {
            display: block;
            animation: fadeInUp 0.3s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            color: #374151;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
            color: #667eea;
        }

        .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 5px 0;
        }

        /* Sidebar - FIXED: Always hidden by default */
        .sidebar {
            position: fixed;
            top: 70px;
            left: 0;
            width: 280px;
            height: calc(100vh - 70px);
            background: white;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            transform: translateX(-100%);
            transition: all 0.3s ease;
            z-index: 999;
            overflow-y: auto;
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-header h3 {
            color: #1f2937;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-header p {
            color: #6b7280;
            font-size: 14px;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section-title {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 20px;
            margin-bottom: 15px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 10px;
            border-radius: 8px;
        }

        .menu-item:hover {
            background: #f3f4f6;
            color: #667eea;
            transform: translateX(5px);
        }

        .menu-item.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: #667eea;
            border-radius: 2px;
        }

        .menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .menu-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
        }

        .menu-badge.success {
            background: #10b981;
        }

        .menu-badge.warning {
            background: #f59e0b;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Main Content - FIXED: No automatic margin */
        .main-content {
            margin-top: 70px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            margin-left: 0; /* Always start with no margin */
        }

        .main-content.sidebar-open {
            margin-left: 280px;
        }

        .content-header {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .content-header h1 {
            color: #1f2937;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .content-header p {
            color: #6b7280;
            font-size: 16px;
        }

        /* Responsive Design */
        @media (min-width: 1024px) {
            .search-box {
                display: block;
            }
        }

        @media (max-width: 1023px) {
            .top-header {
                padding: 0 15px;
            }

            .logo {
                font-size: 20px;
            }

            .user-info span {
                display: none;
            }

            .main-content {
                padding: 20px 15px;
            }
        }

        /* Login Button Styles */
        .login-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="header-left">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <a href="/codecomplier/dashboard.php" class="logo">
                <i class="fas fa-code"></i>
                CodeCompiler
            </a>
        </div>

        

        <div class="header-right">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="user-menu">
                    <div class="user-info" onclick="toggleUserDropdown()">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['user']['username'], 0, 1)) ?>
                        </div>
                        <span><?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                        <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                    </div>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            Profile
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-cog"></i>
                            Settings
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-bell"></i>
                            Notifications
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/codecomplier/auth/logout.php" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/codecomplier/auth/login.php" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>
    

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>Navigation</h3>
            <p>Explore all features</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section">
                <div class="menu-section-title">Main</div>
                <a href="/codecomplier/dashboard.php" class="menu-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/codecomplier/compiler/editor.php" class="menu-item">
                    <i class="fas fa-code"></i>
                    <span>Code Editor</span>
                    <span class="menu-badge success">New</span>
                </a>
                
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Learning</div>
                <a href="/codecomplier/pages/Notes.php" class="menu-item">
                    <i class="fas fa-sticky-note"></i>
                    <span>Notes</span>
                </a>
                <a href="/codecomplier/pages/paid-courses.php" class="menu-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Courses</span>
                </a>
                <a href="/codecomplier/pages/my_courses.php" class="menu-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span> My Courses</span>
                </a>
                <a href="/codecomplier/pages/challenges.php" class="menu-item">
                    <i class="fas fa-trophy"></i>
                    <span>Challenges</span>
                    <span class="menu-badge">5</span>
                </a>
                <a href="/codecomplier/leaderboard.php" class="menu-item">
                    <i class="fas fa-medal"></i>
                    <span>Leaderboard</span>
                </a>
                <a href="/codecomplier/pages/fun.php" class="menu-item">
                    <i class="fa-solid fa-gamepad"></i>
                    <span>Fun Zone</span>
                </a>
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Tools</div>
                <a href="/codecomplier/ai.php" class="menu-item">
                    <i class="fas fa-robot"></i>
                    <span>AI Assistant</span>
                    <span class="menu-badge warning">Beta</span>
                </a>
                
            </div>

            <div class="menu-section">
                <div class="menu-section-title">Support</div>
                <a href="/codecomplier/pages/help_center.php" class="menu-item">
                    <i class="fas fa-question-circle"></i>
                    <span>Help Center</span>
                </a>
                <a href="/codecomplier/pages/contact_us.php" class="menu-item">
                    <i class="fas fa-envelope"></i>
                    <span>Contact</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    
        
        <!-- Your page content goes here -->
    </div>

    <script>
        let sidebarOpen = false;
        let userDropdownOpen = false;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            sidebarOpen = !sidebarOpen;
            
            if (sidebarOpen) {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                // Only add margin on desktop
                if (window.innerWidth >= 1024) {
                    mainContent.classList.add('sidebar-open');
                }
            } else {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
            }
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            userDropdownOpen = !userDropdownOpen;
            
            if (userDropdownOpen) {
                dropdown.classList.add('show');
            } else {
                dropdown.classList.remove('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            const dropdown = document.getElementById('userDropdown');
            
            if (!userMenu.contains(event.target) && userDropdownOpen) {
                dropdown.classList.remove('show');
                userDropdownOpen = false;
            }
        });

        // Handle responsive behavior on window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            // Close sidebar on resize to prevent layout issues
            if (sidebarOpen) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                mainContent.classList.remove('sidebar-open');
                sidebarOpen = false;
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure sidebar starts closed
            sidebarOpen = false;
            
            // Update active menu item based on current page
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.menu-item');
            
            menuItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') && currentPath.includes(item.getAttribute('href'))) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
