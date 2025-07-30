<?php
session_start();
// Ensure your Navbar has a dark theme to complement this design
include('../includes/Navbar.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fun Zone - Interactive UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Library #1: AOS (Animate on Scroll) CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Roboto+Mono:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0d1117; /* GitHub-style dark blue */
            --card-color: #161b22;
            --border-color: #30363d;
            --primary-text: #c9d1d9;
            --heading-text: #ffffff;
            --accent-color: #58a6ff; /* A vibrant, accessible blue */
            --font-heading: 'Roboto Mono', monospace;
            --font-body: 'Montserrat', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--primary-text);
            font-family: var(--font-body);
            min-height: 100vh;
            overflow-x: hidden; /* Prevent AOS from causing scrollbars */
        }
        
        /* Particle background container */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1; /* Place it behind all content */
        }

        .fun-container {
            max-width: 900px;
            margin: 100px auto;
            padding: 3rem;
            position: relative; /* Needed to sit above the particles */
            z-index: 1;
        }

        .fun-title {
            font-family: var(--font-heading);
            font-size: 3rem;
            text-align: center;
            margin-bottom: 3rem;
            color: var(--heading-text);
            font-weight: 700;
        }

        .game-card {
            background-color: rgba(22, 27, 34, 0.8); /* Semi-transparent card */
            backdrop-filter: blur(5px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: transform 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden; /* Hide the glowing aurora effect initially */
        }
        
        /* Aurora hover effect */
        .game-card::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% -50%, var(--accent-color), transparent 60%);
            opacity: 0;
            transform: scaleY(0);
            transform-origin: bottom;
            transition: all 0.5s cubic-bezier(0.19, 1, 0.22, 1);
            pointer-events: none;
        }
        
        .game-card:hover::before {
            opacity: 0.15;
            transform: scaleY(1);
        }

        .game-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent-color);
        }

        .game-card h4 {
            font-family: var(--font-heading);
            font-size: 1.5rem;
            color: var(--heading-text);
            margin-bottom: 0.75rem;
        }
        
        .game-card p {
            line-height: 1.7;
        }

        .fun-btn {
            margin-top: 1.5rem;
            background-color: transparent;
            border: 2px solid var(--accent-color);
            padding: 0.7rem 1.8rem;
            font-weight: bold;
            border-radius: 8px;
            color: var(--accent-color);
            transition: all 0.3s ease;
        }

        .fun-btn:hover {
            background-color: var(--accent-color);
            color: var(--bg-color);
        }
    </style>
</head>
<body>

<!-- Library #2: tsParticles container -->
<div id="particles-js"></div>

<div class="container fun-container">
    <div class="fun-title" data-aos="fade-down">
        Welcome to the Fun Zone
    </div>
    
    <div class="row">
        <!-- Add data-aos attributes for animations -->
        <div class="col-md-6" data-aos="fade-right" data-aos-delay="200">
            <div class="game-card text-center">
                <h4><i class="fas fa-gamepad"></i> Tic Tac Toe</h4>
                <p>A timeless test of logic and strategy.</p>
                <a href="../games/tictactoe.php" class="btn fun-btn">Play Now</a>
            </div>
        </div>
        <div class="col-md-6" data-aos="fade-left" data-aos-delay="400">
            <div class="game-card text-center">
                <h4><i class="fas fa-snake"></i> Snake Game</h4>
                <p>The fast-paced arcade classic, reimagined.</p>
                <a href="../games/snake.php" class="btn fun-btn">Play Now</a>
            </div>
        </div>
    </div>
</div>

<!-- Library Scripts -->
<!-- tsParticles -->
<script src="https://cdn.jsdelivr.net/npm/tsparticles@3.4.0/tsparticles.bundle.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Initialization Script -->
<script>
    // 1. Initialize AOS (Animate on Scroll)
    AOS.init({
        duration: 800, // Animation duration
        once: true,    // Animate elements only once
    });

    // 2. Initialize tsParticles
    tsParticles.load("particles-js", {
        fpsLimit: 60,
        interactivity: {
            events: {
                onHover: {
                    enable: true,
                    mode: "repulse", // Pushes particles away from cursor
                },
                resize: true,
            },
            modes: {
                repulse: {
                    distance: 100,
                    duration: 0.4,
                },
            },
        },
        particles: {
            color: { value: "#ffffff" },
            links: {
                color: "#ffffff",
                distance: 150,
                enable: true,
                opacity: 0.1,
                width: 1,
            },
            move: {
                direction: "none",
                enable: true,
                outModes: "out",
                random: false,
                speed: 1,
                straight: false,
            },
            number: {
                density: { enable: true, area: 800 },
                value: 80,
            },
            opacity: { value: 0.2 },
            shape: { type: "circle" },
            size: { value: { min: 1, max: 3 } },
        },
        detectRetina: true,
    });
</script>

</body>
</html>
