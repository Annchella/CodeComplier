<?php
session_start();

// 🔒 Stronger anti-cache headers
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 🚫 Block access if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['user']['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard - CodeSpace</title>

    <!-- Professional Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            /* Updated to match navbar colors */
            --primary-blue: #667eea;
            --primary-dark: #764ba2;
            --secondary-gray: #f8fafc;
            --accent-orange: #ff6b35;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-light: #e5e7eb;
            --white: #ffffff;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.15);
            --shadow-lg: 0 8px 25px rgba(0,0,0,0.15);
            
            /* Main gradients to match navbar */
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-accent: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            
            /* Additional matching colors */
            --navbar-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --hover-purple: #5a67d8;
            --light-purple: rgba(102, 126, 234, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc; /* Matches navbar background */
            color: var(--text-primary);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Professional Header */
        .professional-header {
            background: var(--navbar-gradient); /* Same as navbar */
            color: var(--white);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .professional-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><polygon fill="rgba(255,255,255,0.1)" points="1000,100 1000,0 0,0 0,80"/></svg>');
            background-size: 100% 100%;
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 8px 20px;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .user-avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .main-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .main-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            opacity: 0.9;
            max-width: 600px;
        }

        /* Stats Bar */
        .stats-bar {
            background: var(--white);
            border-radius: 20px;
            padding: 2rem;
            margin: -40px auto 0;
            max-width: 900px;
            box-shadow: var(--shadow-lg);
            position: relative;
            z-index: 10;
        }

        .stat-item-pro {
            text-align: center;
            padding: 1rem;
            border-right: 1px solid var(--border-light);
        }

        .stat-item-pro:last-child {
            border-right: none;
        }

        .stat-number-pro {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue); /* Purple instead of blue */
            margin-bottom: 0.5rem;
            font-family: 'JetBrains Mono', monospace;
        }

        .stat-label-pro {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Section Styling */
        .section-pro {
            padding: 80px 0;
        }

        .section-title-pro {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            text-align: center;
        }

        .section-subtitle-pro {
            font-size: 1.1rem;
            color: var(--text-secondary);
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }

        /* Professional Cards */
        .pro-card {
            background: var(--white);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .pro-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .pro-card:hover::before {
            transform: scaleX(1);
        }

        .pro-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-blue);
        }

        .card-icon-pro {
            width: 70px;
            height: 70px;
            background: var(--gradient-primary); /* Matches navbar */
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--white);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .pro-card:hover .card-icon-pro {
            background: var(--gradient-accent);
            transform: scale(1.1);
        }

        .card-title-pro {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .card-desc-pro {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.7;
        }

        .btn-pro {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .btn-pro:hover {
            background: var(--primary-dark);
            color: var(--white);
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* CTA Section */
        .cta-section {
            background: var(--secondary-gray);
            padding: 80px 0;
            text-align: center;
        }

        .cta-card {
            background: var(--white);
            border-radius: 20px;
            padding: 4rem 3rem;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: var(--shadow-md);
        }

        .cta-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .cta-desc {
            font-size: 1.1rem;
            color: var(--text-secondary);
            margin-bottom: 2.5rem;
        }

        .btn-cta {
            background: var(--gradient-primary); /* Purple gradient */
            color: var(--white);
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            color: var(--white);
        }

        /* Enhanced Footer Styles */
        .footer-enhanced {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: var(--white);
            padding: 4rem 0 0;
            margin-top: 4rem;
        }

        .footer-section {
            height: 100%;
        }

        .footer-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .footer-subtitle {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-desc {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .footer-links a:hover {
            color: var(--primary-blue);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--primary-blue);
            color: var(--white);
            transform: translateY(-2px);
        }

        .contact-info {
            margin-bottom: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .newsletter-text {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .newsletter-form {
            display: flex;
            gap: 0.5rem;
        }

        .newsletter-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
            font-size: 0.9rem;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-input:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: rgba(255, 255, 255, 0.15);
        }

        .newsletter-btn {
            padding: 0.75rem 1rem;
            background: var(--primary-blue);
            border: none;
            border-radius: 8px;
            color: var(--white);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .newsletter-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .copyright {
            color: rgba(255, 255, 255, 0.6);
            margin: 0;
            font-size: 0.9rem;
        }

        .footer-bottom-links {
            display: flex;
            gap: 2rem;
            justify-content: flex-end;
        }

        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: var(--primary-blue);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }
            
            .stats-bar {
                margin: -20px 15px 0;
                padding: 1.5rem;
            }
            
            .stat-item-pro {
                border-right: none;
                border-bottom: 1px solid var(--border-light);
                margin-bottom: 1rem;
                padding-bottom: 1rem;
            }
            
            .stat-item-pro:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
            
            .section-pro {
                padding: 60px 0;
            }
            
            .pro-card {
                margin-bottom: 2rem;
            }

            .footer-enhanced {
                padding: 3rem 0 0;
            }
            
            .footer-bottom-links {
                justify-content: center;
                margin-top: 1rem;
                flex-wrap: wrap;
                gap: 1rem;
            }
            
            .newsletter-form {
                flex-direction: column;
            }
            
            .social-links {
                justify-content: center;
            }
        }

        /* Animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeUp 0.8s ease forwards;
        }

        @keyframes fadeUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }
        .delay-3 { animation-delay: 0.3s; }
        .delay-4 { animation-delay: 0.4s; }
        .delay-5 { animation-delay: 0.5s; }
        .delay-6 { animation-delay: 0.6s; }
    </style>
</head>

<body>
    <?php include_once "includes/Navbar.php"; ?>
    
    <!-- Professional Header -->
    <div class="professional-header">
        <div class="container">
            <div class="header-content">
                <div class="user-badge fade-up">
                    <span>Welcome back, <?= $username ?></span>
                </div>
                <h1 class="main-title fade-up delay-1">Your Development Hub</h1>
                <p class="main-subtitle fade-up delay-2">
                    Streamline your coding workflow with professional tools designed for modern developers
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="container">
        <div class="stats-bar fade-up delay-3">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-item-pro">
                        <div class="stat-number-pro">12</div>
                        <div class="stat-label-pro">Active Projects</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-item-pro">
                        <div class="stat-number-pro">24</div>
                        <div class="stat-label-pro">Completed Tasks</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-item-pro">
                        <div class="stat-number-pro">8</div>
                        <div class="stat-label-pro">Courses Enrolled</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-item-pro">
                        <div class="stat-number-pro">156</div>
                        <div class="stat-label-pro">Hours This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Features Section -->
    <div class="section-pro">
        <div class="container">
            <h2 class="section-title-pro" data-aos="fade-up">Professional Development Tools</h2>
            <p class="section-subtitle-pro" data-aos="fade-up" data-aos-delay="100">
                Everything you need to build, learn, and grow as a developer in one comprehensive platform
            </p>

            <div class="row g-4">
                <?php
                $features = [
                    [
                        "icon" => "fas fa-code",
                        "title" => "Advanced Code Editor",
                        "desc" => "Professional IDE with intelligent autocomplete, syntax highlighting, and collaborative editing features.",
                        "link" => "compiler/editor.php"
                    ],
                    [
                        "icon" => "fas fa-book-open",
                        "title" => "Knowledge Base",
                        "desc" => "Comprehensive documentation, tutorials, and best practices for all programming languages.",
                        "link" => "pages/Notes.php"
                    ],
                    [
                        "icon" => "fas fa-graduation-cap",
                        "title" => "Professional Courses",
                        "desc" => "Industry-standard courses with hands-on projects and recognized certifications.",
                        "link" => "pages/paid-courses.php"
                    ],
                    [
                        "icon" => "fas fa-robot",
                        "title" => "AI Code Assistant",
                        "desc" => "Intelligent code suggestions, debugging help, and optimization recommendations.",
                        "link" => "#"
                    ],
                    [
                        "icon" => "fas fa-chart-line",
                        "title" => "Progress Analytics",
                        "desc" => "Track your learning progress with detailed analytics and performance insights.",
                        "link" => "#"
                    ],
                    [
                        "icon" => "fa-solid fa-gamepad",
                        "title" => "Fun Zone",
                        "desc" => "Engage with coding challenges, puzzles, and games to sharpen your skills while having fun.",
                        "link" => "pages/fun.php"
                    ]
                ];

                foreach ($features as $index => $f) {
                    $delay = ($index + 1) * 100;
                    echo "
                    <div class='col-lg-4 col-md-6' data-aos='fade-up' data-aos-delay='{$delay}'>
                        <div class='pro-card'>
                            <div class='card-icon-pro'>
                                <i class='{$f['icon']}'></i>
                            </div>
                            <h3 class='card-title-pro'>{$f['title']}</h3>
                            <p class='card-desc-pro'>{$f['desc']}</p>
                            <a href='{$f['link']}' class='btn-pro'>
                                Get Started <i class='fas fa-arrow-right'></i>
                            </a>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <div class="container">
            <div class="cta-card" data-aos="fade-up">
                <h2 class="cta-title">Ready to Level Up Your Skills?</h2>
                <p class="cta-desc">
                    Join thousands of developers who are already using our platform to accelerate their careers
                </p>
                <button id="logoutBtn" class="btn-cta">
                    <i class="fas fa-sign-out-alt me-2"></i>Secure Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="footer-enhanced">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h5 class="footer-title">
                            <i class="fas fa-code me-2"></i>CodeSpace
                        </h5>
                        <p class="footer-desc">
                            Professional development platform designed for modern developers. 
                            Build, learn, and grow your coding skills with industry-standard tools.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-github"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-discord"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6 class="footer-subtitle">Quick Links</h6>
                        <ul class="footer-links">
                            <li><a href="compiler/editor.php">Code Editor</a></li>
                            <li><a href="pages/Notes.php">Documentation</a></li>
                            <li><a href="pages/paid-courses.php">Courses</a></li>
                            <li><a href="pages/fun.php">Fun Zone</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Resources -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6 class="footer-subtitle">Resources</h6>
                        <ul class="footer-links">
                            <li><a href="#">API Documentation</a></li>
                            <li><a href="#">Tutorials</a></li>
                            <li><a href="#">Community</a></li>
                            <li><a href="#">Support</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-section">
                        <h6 class="footer-subtitle">Stay Connected</h6>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-envelope me-2"></i>
                                <span>support@codespace.dev</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe me-2"></i>
                                <span>www.codespace.dev</span>
                            </div>
                        </div>
                        <div class="newsletter">
                            <p class="newsletter-text">Get updates on new features</p>
                            <div class="newsletter-form">
                                <input type="email" placeholder="Your email" class="newsletter-input">
                                <button class="newsletter-btn">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="copyright">
                            &copy; 2025 CodeSpace. All rights reserved. Built for professional developers.
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="footer-bottom-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Service</a>
                            <a href="#">Cookie Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        // Logout functionality
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'End Session?',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Session ended successfully',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = 'auth/logout.php';
                    });
                }
            });
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll-triggered animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up').forEach(el => {
            observer.observe(el);
        });

        // Force reload if loaded from cache (back/forward navigation)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>
