<?php
session_start();
include('includes/Header.php');

$isLoggedIn = isset($_SESSION['user']);
$username = $isLoggedIn ? htmlspecialchars($_SESSION['user']['username']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Code Compiler - Welcome</title>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-navy: #1a1a2e;
            --secondary-navy: #16213e;
            --accent-gold: #c9a96e;
            --accent-cream: #f5f5dc;
            --text-primary: #ffffff;
            --text-secondary: #b8b8b8;
            --text-muted: #8a8a8a;
            --border-elegant: rgba(201, 169, 110, 0.3);
            --shadow-soft: rgba(0, 0, 0, 0.2);
            --shadow-elegant: rgba(201, 169, 110, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Elegant background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(201, 169, 110, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(201, 169, 110, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .landing-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .hero-content {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
            opacity: 0;
            transform: translateY(30px);
            animation: elegantEntrance 2s ease-out 0.5s forwards;
        }

        .brand-emblem {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border: 2px solid var(--accent-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(201, 169, 110, 0.1);
            opacity: 0;
            animation: emblemAppear 1.5s ease-out 1s forwards;
            position: relative;
        }

        .brand-emblem::before {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border: 1px solid rgba(201, 169, 110, 0.3);
            border-radius: 50%;
            animation: gentleRotate 20s linear infinite;
        }

        .brand-emblem i {
            font-size: 2rem;
            color: var(--accent-gold);
        }

        .main-title {
            font-family: 'Crimson Text', serif;
            font-size: 3.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
            opacity: 0;
            animation: titleFadeIn 1.5s ease-out 1.5s forwards;
        }

        .subtitle {
            font-size: 1.2rem;
            font-weight: 300;
            color: var(--text-secondary);
            margin-bottom: 3rem;
            font-style: italic;
            opacity: 0;
            animation: subtitleFadeIn 1.5s ease-out 2s forwards;
        }

        .ornamental-line {
            width: 120px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            margin: 2rem auto;
            opacity: 0;
            animation: lineExpand 1s ease-out 2.5s forwards;
        }

        .loading-elegance {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 2rem;
            opacity: 0;
            animation: loadingAppear 1s ease-out 3s forwards;
        }

        .loading-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--accent-gold);
            animation: elegantPulse 2s ease-in-out infinite;
        }

        .loading-dot:nth-child(1) { animation-delay: 0s; }
        .loading-dot:nth-child(2) { animation-delay: 0.4s; }
        .loading-dot:nth-child(3) { animation-delay: 0.8s; }

        .status-message {
            position: absolute;
            bottom: 15%;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.95rem;
            color: var(--text-muted);
            opacity: 0;
            animation: statusAppear 1s ease-out 4s forwards;
            font-style: italic;
        }

        /* Hidden Content Styles */
        .elegant-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-elegant);
            border-radius: 8px;
            padding: 2.5rem;
            backdrop-filter: blur(10px);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .elegant-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(201, 169, 110, 0.05), transparent);
            transition: left 0.6s ease;
        }

        .elegant-card:hover::before {
            left: 100%;
        }

        .elegant-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-gold);
            box-shadow: 0 15px 35px var(--shadow-elegant);
        }

        .feature-icon {
            font-size: 2.2rem;
            color: var(--accent-gold);
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .feature-title {
            font-family: 'Crimson Text', serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
            font-weight: 300;
        }

        .btn-classic {
            padding: 0.75rem 2rem;
            font-size: 0.95rem;
            font-weight: 400;
            border-radius: 4px;
            border: 1px solid var(--accent-gold);
            background: transparent;
            color: var(--accent-gold);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn-classic::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--accent-gold);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-classic:hover::before {
            left: 0;
        }

        .btn-classic:hover {
            color: var(--primary-navy);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(201, 169, 110, 0.3);
        }

        .btn-primary-classic {
            background: var(--accent-gold);
            color: var(--primary-navy);
        }

        .btn-primary-classic::before {
            background: var(--primary-navy);
        }

        .btn-primary-classic:hover {
            color: var(--accent-gold);
        }

        .hero-section {
            text-align: center;
            padding: 4rem 0;
        }

        .hero-title {
            font-family: 'Crimson Text', serif;
            font-size: 3rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.7;
            font-weight: 300;
        }

        .cta-section {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-elegant);
            border-radius: 8px;
            padding: 3rem;
            text-align: center;
            margin: 3rem 0;
        }

        .cta-title {
            font-family: 'Crimson Text', serif;
            font-size: 1.8rem;
            color: var(--accent-gold);
            margin-bottom: 1rem;
        }

        .footer-elegant {
            border-top: 1px solid var(--border-elegant);
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
        }

        .social-links a {
            color: var(--text-muted);
            font-size: 1.2rem;
            margin: 0 1rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--accent-gold);
            transform: translateY(-3px);
        }

        .copyright {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-top: 1rem;
            font-weight: 300;
        }

        /* Animations */
        @keyframes elegantEntrance {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes emblemAppear {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes gentleRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes titleFadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes subtitleFadeIn {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes lineExpand {
            0% { opacity: 0; width: 0; }
            100% { opacity: 1; width: 120px; }
        }

        @keyframes loadingAppear {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes elegantPulse {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        @keyframes statusAppear {
            0% { opacity: 0; transform: translateX(-50%) translateY(10px); }
            100% { opacity: 1; transform: translateX(-50%) translateY(0); }
        }

        @keyframes gracefulExit {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.95); }
        }

        .fade-out {
            animation: gracefulExit 1.2s ease-out forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-title { font-size: 2.5rem; }
            .hero-title { font-size: 2.2rem; }
            .subtitle { font-size: 1.1rem; }
            .elegant-card { padding: 1.5rem; }
            .hero-content { padding: 1rem; }
        }
    </style>
</head>
<body>
    <div class="landing-container" id="landingContainer">
        <div class="hero-content">
            <div class="brand-emblem">
                <i class="fas fa-code"></i>
            </div>
            
            <h1 class="main-title">Code Compiler</h1>
            <p class="subtitle">Where elegance meets functionality</p>
            
            <div class="ornamental-line"></div>
            
            <div class="loading-elegance">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </div>
        
        <div class="status-message">
            Preparing your distinguished coding environment...
        </div>
    </div>

    <!-- Hidden Content -->
    <div id="originalContent" style="display: none;">
        <div class="container py-5">
            <div class="hero-section animate__animated animate__fadeIn">
                <h1 class="hero-title">
                    <i class="fas fa-terminal me-3" style="color: var(--accent-gold);"></i>
                    Online Code Compiler
                </h1>
                <p class="hero-subtitle">
                    Experience the refined art of programming with our distinguished online compiler. 
                    Crafted for developers who appreciate both <em>functionality</em> and <em>elegance</em>.
                </p>
                
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <?php if ($isLoggedIn): ?>
                        <a href="dashboard.php" class="btn-classic btn-primary-classic">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                        <a href="auth/logout.php" class="btn-classic">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    <?php else: ?>
                        <a href="auth/login.php" class="btn-classic btn-primary-classic">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="auth/register.php" class="btn-classic">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="elegant-card text-center h-100">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h5 class="feature-title">Swift Execution</h5>
                        <p class="feature-description">
                            Experience lightning-fast compilation and execution with our optimized infrastructure, 
                            designed for efficiency without compromise.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="elegant-card text-center h-100">
                        <div class="feature-icon">
                            <i class="bi bi-palette-fill"></i>
                        </div>
                        <h5 class="feature-title">Refined Interface</h5>
                        <p class="feature-description">
                            Code with sophistication using our thoughtfully designed interface, 
                            featuring elegant themes and intuitive navigation.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="elegant-card text-center h-100">
                        <div class="feature-icon">
                            <i class="bi bi-share-fill"></i>
                        </div>
                        <h5 class="feature-title">Seamless Collaboration</h5>
                        <p class="feature-description">
                            Share your work effortlessly with secure links and collaborate 
                            with fellow developers in real-time.
                        </p>
                    </div>
                </div>
            </div>

            <div class="cta-section">
                <h4 class="cta-title">
                    <i class="fas fa-quill-pen me-2"></i>
                    Begin Your Journey
                </h4>
                <p class="mb-4" style="color: var(--text-secondary);">
                    Step into our distinguished coding environment and transform your ideas into reality.
                </p>
                <a href="compiler/editor.php" class="btn-classic btn-primary-classic">
                    <i class="fas fa-code me-2"></i>Enter Editor
                </a>
            </div>
        </div>

        <footer class="footer-elegant">
            <div class="container">
                <div class="social-links mb-3">
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
                <div class="copyright">
                    &copy; <?= date('Y') ?> CodeCompiler. Crafted with distinction and care.
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto redirect with elegant timing
        setTimeout(function() {
            const landingContainer = document.getElementById('landingContainer');
            landingContainer.classList.add('fade-out');
            setTimeout(function() {
                <?php if ($isLoggedIn): ?>
                    window.location.href = 'dashboard.php';
                <?php else: ?>
                    window.location.href = 'auth/login.php';
                <?php endif; ?>
            }, 1200);
        }, 6000);

        // Click to proceed
        document.getElementById('landingContainer').addEventListener('click', function () {
            this.classList.add('fade-out');
            setTimeout(function () {
                <?php if ($isLoggedIn): ?>
                    window.location.href = 'dashboard.php';
                <?php else: ?>
                    window.location.href = 'auth/login.php';
                <?php endif; ?>
            }, 1200);
        });
    </script>
</body>
</html>
