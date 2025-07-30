<?php 
session_start();
include('../includes/Navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Courses - CodeSpace</title>
    
    <!-- Modern Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #8b5cf6;
            --accent: #06b6d4;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --dark: #1e293b;
            --light: #f8fafc;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-4: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --gradient-5: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --gradient-6: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin-top: 70px;
        }

        /* Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }

        /* Hero Section */
        .hero-section {
            background: var(--gradient-1);
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translateY(100vh) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        /* Course Card Styles */
        .course-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            height: 100%;
        }

        .course-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-1);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .course-card:hover::before {
            transform: scaleX(1);
        }

        .course-card:hover {
            transform: translateY(-15px) scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
        }

        .course-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .course-card:hover .course-image {
            transform: scale(1.1);
        }

        .course-content {
            padding: 30px;
        }

        .price-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--gradient-5);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .difficulty-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            z-index: 10;
        }

        .difficulty-beginner { background: var(--gradient-4); }
        .difficulty-intermediate { background: var(--gradient-2); }
        .difficulty-advanced { background: var(--gradient-6); }

        .course-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .course-description {
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .course-features {
            margin-bottom: 25px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #475569;
            font-size: 14px;
        }

        .feature-item i {
            color: var(--primary);
            margin-right: 10px;
            width: 16px;
        }

        .course-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-weight: 700;
            color: var(--primary);
            font-size: 18px;
        }

        .stat-label {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        .btn-course {
            background: var(--gradient-1);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-course::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-course:hover::before {
            left: 100%;
        }

        .btn-course:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Filter Section */
        .filter-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .filter-btn {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 10px 20px;
            border-radius: 25px;
            margin: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* Search Box */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px;
            }
            
            .course-content {
                padding: 20px;
            }
            
            .course-stats {
                flex-direction: column;
                gap: 10px;
            }
            
            .stat-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <?php include_once "../includes/Navbar.php"; ?>
    
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="floating-elements">
            <div class="floating-shape" style="width: 80px; height: 80px; left: 10%; animation-delay: 0s;"></div>
            <div class="floating-shape" style="width: 60px; height: 60px; left: 20%; animation-delay: 2s;"></div>
            <div class="floating-shape" style="width: 100px; height: 100px; left: 70%; animation-delay: 4s;"></div>
            <div class="floating-shape" style="width: 40px; height: 40px; left: 80%; animation-delay: 6s;"></div>
        </div>
        
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6" data-aos="fade-up">
                    Premium <span class="text-yellow-300">Courses</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90" data-aos="fade-up" data-aos-delay="200">
                    Master programming skills with industry-expert instructors
                </p>
                <div class="flex justify-center space-x-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">🎓 Expert Instructors</span>
                    </div>
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">🏆 Industry Projects</span>
                    </div>
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">📜 Certificates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 -mt-10 relative z-20 pb-20">
        <!-- Search Section -->
        <div class="search-container" data-aos="fade-up">
            <input type="text" class="search-input" placeholder="Search courses..." id="searchInput">
            <i class="fas fa-search search-icon"></i>
        </div>

        <!-- Filter Section -->
        <div class="filter-section" data-aos="fade-up" data-aos-delay="200">
            <div class="text-center">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Filter by Category</h3>
                <div class="flex flex-wrap justify-center">
                    <button class="filter-btn active" data-filter="all">All Courses</button>
                    <button class="filter-btn" data-filter="web">Web Development</button>
                    <button class="filter-btn" data-filter="mobile">Mobile Development</button>
                    <button class="filter-btn" data-filter="data">Data Science</button>
                    <button class="filter-btn" data-filter="ai">AI/ML</button>
                    <button class="filter-btn" data-filter="backend">Backend</button>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Course 1: Full Stack Web Development -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="300" data-category="web">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=500&h=250&fit=crop" class="course-image" alt="Full Stack Development">
                    <div class="price-badge">₹2,999</div>
                    <div class="difficulty-badge difficulty-intermediate">Intermediate</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Full Stack Web Development</h3>
                    <p class="course-description">Master the complete web development stack with React, Node.js, Express, and MongoDB. Build real-world applications from scratch.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fas fa-video"></i>
                            <span>50+ Hours of Video Content</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-project-diagram"></i>
                            <span>10 Real-world Projects</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-certificate"></i>
                            <span>Industry Certificate</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-users"></i>
                            <span>Community Access</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.8</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1,234</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('Full Stack Web Development', 2999)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>

            <!-- Course 2: Python for Data Science -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="400" data-category="data">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1526379095098-d400fd0bf935?w=500&h=250&fit=crop" class="course-image" alt="Python Data Science">
                    <div class="price-badge">₹1,999</div>
                    <div class="difficulty-badge difficulty-beginner">Beginner</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Python for Data Science</h3>
                    <p class="course-description">Learn Python programming with focus on data analysis, visualization, and machine learning using pandas, numpy, and scikit-learn.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fas fa-video"></i>
                            <span>40+ Hours of Content</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-chart-bar"></i>
                            <span>Data Analysis Projects</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-brain"></i>
                            <span>ML Algorithms</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-file-csv"></i>
                            <span>Real Datasets</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.9</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">2,156</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">40h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('Python for Data Science', 1999)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>

            <!-- Course 3: React Native Mobile Development -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="500" data-category="mobile">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&h=250&fit=crop" class="course-image" alt="React Native">
                    <div class="price-badge">₹2,499</div>
                    <div class="difficulty-badge difficulty-intermediate">Intermediate</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">React Native Mobile Development</h3>
                    <p class="course-description">Build cross-platform mobile applications using React Native. Learn navigation, state management, and native integrations.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Cross-platform Apps</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-store"></i>
                            <span>App Store Deployment</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-plug"></i>
                            <span>Native Integrations</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-rocket"></i>
                            <span>Performance Optimization</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.7</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">856</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">35h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('React Native Mobile Development', 2499)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>

            <!-- Course 4: Java Enterprise Development -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="600" data-category="backend">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1517077304055-6e89abbf09b0?w=500&h=250&fit=crop" class="course-image" alt="Java Enterprise">
                    <div class="price-badge">₹1,799</div>
                    <div class="difficulty-badge difficulty-advanced">Advanced</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Java Enterprise Development</h3>
                    <p class="course-description">Master enterprise Java development with Spring Boot, Hibernate, microservices architecture, and cloud deployment.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fas fa-leaf"></i>
                            <span>Spring Boot Framework</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-database"></i>
                            <span>Database Integration</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-cubes"></i>
                            <span>Microservices</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-cloud"></i>
                            <span>Cloud Deployment</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.6</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1,089</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">45h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('Java Enterprise Development', 1799)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>

            <!-- Course 5: Machine Learning with TensorFlow -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="700" data-category="ai">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?w=500&h=250&fit=crop" class="course-image" alt="Machine Learning">
                    <div class="price-badge">₹3,499</div>
                    <div class="difficulty-badge difficulty-advanced">Advanced</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">Machine Learning with TensorFlow</h3>
                    <p class="course-description">Deep dive into machine learning and neural networks using TensorFlow. Build AI models for real-world applications.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fas fa-brain"></i>
                            <span>Neural Networks</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-eye"></i>
                            <span>Computer Vision</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-comments"></i>
                            <span>NLP Projects</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-robot"></i>
                            <span>AI Applications</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.9</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">567</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">60h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('Machine Learning with TensorFlow', 3499)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>

            <!-- Course 6: DevOps & Cloud Computing -->
            <div class="course-card" data-aos="fade-up" data-aos-delay="800" data-category="backend">
                <div class="relative overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=500&h=250&fit=crop" class="course-image" alt="DevOps">
                    <div class="price-badge">₹2,799</div>
                    <div class="difficulty-badge difficulty-intermediate">Intermediate</div>
                </div>
                <div class="course-content">
                    <h3 class="course-title">DevOps & Cloud Computing</h3>
                    <p class="course-description">Learn modern DevOps practices with Docker, Kubernetes, AWS, and CI/CD pipelines for scalable applications.</p>
                    
                    <div class="course-features">
                        <div class="feature-item">
                            <i class="fab fa-docker"></i>
                            <span>Docker & Containers</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-dharmachakra"></i>
                            <span>Kubernetes</span>
                        </div>
                        <div class="feature-item">
                            <i class="fab fa-aws"></i>
                            <span>AWS Cloud</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-code-branch"></i>
                            <span>CI/CD Pipelines</span>
                        </div>
                    </div>

                    <div class="course-stats">
                        <div class="stat-item">
                            <div class="stat-number">4.8</div>
                            <div class="stat-label">Rating</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">743</div>
                            <div class="stat-label">Students</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">42h</div>
                            <div class="stat-label">Duration</div>
                        </div>
                    </div>

                    <button class="btn-course" onclick="enrollCourse('DevOps & Cloud Computing', 2799)">
                        <i class="fas fa-shopping-cart mr-2"></i>Enroll Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        // Filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filter = this.getAttribute('data-filter');
                const cards = document.querySelectorAll('.course-card');
                
                cards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeInUp 0.6s ease';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.course-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.course-title').textContent.toLowerCase();
                const description = card.querySelector('.course-description').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || searchTerm === '') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Enroll course function
        function enrollCourse(courseName, price) {
            Swal.fire({
                title: 'Enroll in Course',
                html: `
                    <div class="text-left">
                        <h4 class="font-bold text-lg mb-3">${courseName}</h4>
                        <p class="text-gray-600 mb-4">Price: ₹${price}</p>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <h5 class="font-semibold mb-2">What's included:</h5>
                            <ul class="text-sm text-gray-700">
                                <li>✓ Lifetime access to course content</li>
                                <li>✓ Certificate of completion</li>
                                <li>✓ Community access</li>
                                <li>✓ 24/7 support</li>
                            </ul>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-credit-card mr-2"></i>Proceed to Payment',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#6b7280',
                customClass: {
                    popup: 'rounded-3xl',
                    confirmButton: 'rounded-full px-6 py-3',
                    cancelButton: 'rounded-full px-6 py-3'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to payment page
                    window.location.href = `payment.php?course=${encodeURIComponent(courseName)}&price=${price}`;
                }
            });
        }

        // Add smooth scrolling
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

        // Add loading animation to buttons
        document.querySelectorAll('.btn-course').forEach(btn => {
            btn.addEventListener('click', function() {
                const originalText = this.innerHTML;
                this.innerHTML = '<div class="loading"></div> Processing...';
                this.disabled = true;
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            });
        });
    </script>
</body>
</html>
