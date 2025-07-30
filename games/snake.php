<?php
session_start();
include('../includes/Navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Classic Snake - Elegant Edition</title>
    
    <!-- Classic Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;500;600&family=Cormorant+Garamond:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Elegant UI Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        :root {
            /* Classic Color Palette */
            --primary-navy: #1a1a2e;
            --secondary-navy: #16213e;
            --accent-gold: #d4af37;
            --accent-cream: #f5f5dc;
            --accent-sage: #9caf88;
            --text-primary: #2c2c2c;
            --text-secondary: #6c6c6c;
            --text-light: #ffffff;
            --border-elegant: rgba(212, 175, 55, 0.3);
            --shadow-classic: 0 8px 25px rgba(0, 0, 0, 0.15);
            --shadow-soft: 0 4px 12px rgba(0, 0, 0, 0.1);
            
            /* Typography */
            --font-heading: 'Crimson Text', serif;
            --font-body: 'Lato', sans-serif;
            --font-decorative: 'Cormorant Garamond', serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: linear-gradient(135deg, var(--accent-cream) 0%, #f0ebe5 100%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Classic background pattern */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 25% 25%, rgba(212, 175, 55, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(212, 175, 55, 0.05) 0%, transparent 50%);
            z-index: -1;
            pointer-events: none;
        }

        /* Main Game Container */
        .game-container {
            max-width: 1100px;
            width: 95%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: var(--shadow-classic);
            border: 1px solid var(--border-elegant);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
        }

        .game-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-gold), var(--accent-sage), var(--accent-gold));
        }

        /* Header Section */
        .game-header {
            text-align: center;
            padding: 2.5rem 2rem 1.5rem;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.8), rgba(245, 245, 220, 0.6));
        }

        .game-title {
            font-family: var(--font-heading);
            font-size: 3rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .game-subtitle {
            font-family: var(--font-decorative);
            font-size: 1.2rem;
            color: var(--text-secondary);
            font-style: italic;
            margin-bottom: 1.5rem;
        }

        /* Game Content */
        .game-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
            padding: 2rem;
        }

        /* Canvas Container */
        .canvas-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .canvas-frame {
            background: var(--primary-navy);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-classic);
            border: 2px solid var(--accent-gold);
            position: relative;
        }

        .canvas-frame::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, var(--accent-gold), var(--accent-sage));
            border-radius: 12px;
            z-index: -1;
        }

        #gameCanvas {
            background: #0a0a0a;
            border-radius: 8px;
            display: block;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.5);
        }

        /* Control Panel */
        .control-panel {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 12px;
            padding: 2rem;
            border: 1px solid var(--border-elegant);
            box-shadow: var(--shadow-soft);
            height: fit-content;
        }

        .panel-section {
            margin-bottom: 2rem;
        }

        .panel-section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 2px;
            background: var(--accent-gold);
        }

        /* Score Display */
        .score-display {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .score-card {
            background: var(--accent-cream);
            border: 1px solid var(--border-elegant);
            border-radius: 8px;
            padding: 1.5rem 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .score-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-soft);
        }

        .score-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .score-number {
            font-family: var(--font-heading);
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-navy);
        }

        /* Game Status */
        .status-display {
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid var(--accent-gold);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .status-text {
            font-size: 1rem;
            font-weight: 500;
            color: var(--primary-navy);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-sage);
            animation: pulse 2s infinite;
        }

        .status-indicator.playing {
            background: var(--accent-gold);
        }

        /* Elegant Buttons */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .btn-classic {
            padding: 1rem 2rem;
            border: 2px solid var(--accent-gold);
            background: transparent;
            color: var(--accent-gold);
            border-radius: 6px;
            font-family: var(--font-body);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
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
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-primary {
            background: var(--accent-gold);
            color: white;
        }

        .btn-primary::before {
            background: var(--primary-navy);
        }

        /* Instructions */
        .instructions {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 1.5rem;
            border: 1px solid var(--border-elegant);
        }

        .instruction-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .instruction-item:last-child {
            margin-bottom: 0;
        }

        .instruction-key {
            background: var(--primary-navy);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.8rem;
            min-width: 30px;
            text-align: center;
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.1); }
        }

        @keyframes scoreUpdate {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .score-update {
            animation: scoreUpdate 0.3s ease-in-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .game-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                padding: 1rem;
            }

            .game-header {
                padding: 2rem 1rem 1rem;
            }

            .game-title {
                font-size: 2.5rem;
            }

            .canvas-frame {
                padding: 1rem;
            }

            #gameCanvas {
                width: 100%;
                height: auto;
            }

            .control-panel {
                order: -1;
            }

            .score-display {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="game-container" data-aos="fade-up" data-aos-duration="800">
        <div class="game-header">
            <h1 class="game-title" data-aos="fade-down" data-aos-delay="200">Classic Snake</h1>
            <p class="game-subtitle" data-aos="fade-down" data-aos-delay="400">An Elegant Gaming Experience</p>
        </div>

        <div class="game-content">
            <div class="canvas-section" data-aos="fade-right" data-aos-delay="600">
                <div class="canvas-frame">
                    <canvas id="gameCanvas" width="480" height="480" tabindex="0"></canvas>
                </div>
            </div>

            <div class="control-panel" data-aos="fade-left" data-aos-delay="800">
                <div class="panel-section">
                    <h3 class="section-title">Score Board</h3>
                    <div class="score-display">
                        <div class="score-card">
                            <div class="score-label">Current</div>
                            <div class="score-number" id="currentScore">0</div>
                        </div>
                        <div class="score-card">
                            <div class="score-label">Best</div>
                            <div class="score-number" id="bestScore">0</div>
                        </div>
                    </div>
                </div>

                <div class="panel-section">
                    <div class="status-display">
                        <div class="status-text">
                            <div class="status-indicator" id="statusDot"></div>
                            <span id="gameStatus">Ready to Play</span>
                        </div>
                    </div>
                </div>

                <div class="panel-section">
                    <h3 class="section-title">Game Control</h3>
                    <div class="button-group">
                        <button class="btn-classic btn-primary" onclick="startGame()">
                            <i class="fas fa-play"></i>
                            <span>Start Game</span>
                        </button>
                        <button class="btn-classic" onclick="pauseGame()">
                            <i class="fas fa-pause"></i>
                            <span>Pause Game</span>
                        </button>
                        <button class="btn-classic" onclick="resetGame()">
                            <i class="fas fa-redo"></i>
                            <span>Reset Game</span>
                        </button>
                    </div>
                </div>

                <div class="panel-section">
                    <h3 class="section-title">Instructions</h3>
                    <div class="instructions">
                        <div class="instruction-item">
                            <span class="instruction-key">↑</span>
                            <span>Move Up</span>
                        </div>
                        <div class="instruction-item">
                            <span class="instruction-key">↓</span>
                            <span>Move Down</span>
                        </div>
                        <div class="instruction-item">
                            <span class="instruction-key">←</span>
                            <span>Move Left</span>
                        </div>
                        <div class="instruction-item">
                            <span class="instruction-key">→</span>
                            <span>Move Right</span>
                        </div>
                        <div class="instruction-item">
                            <span class="instruction-key">Space</span>
                            <span>Pause/Resume</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elegant Libraries -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });

        // Initialize Notyf for elegant notifications
        const notyf = new Notyf({
            duration: 3000,
            position: { x: 'right', y: 'top' },
            types: [
                {
                    type: 'success',
                    background: '#d4af37',
                    icon: {
                        className: 'fas fa-trophy',
                        tagName: 'i',
                        color: 'white'
                    }
                },
                {
                    type: 'warning',
                    background: '#9caf88',
                    icon: {
                        className: 'fas fa-skull',
                        tagName: 'i',
                        color: 'white'
                    }
                }
            ]
        });

        // Game Variables
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const box = 20;
        const canvasSize = 480;
        const gridSize = canvasSize / box;

        let snake, direction, food, score, gameLoop, paused, gameOver;
        let bestScore = localStorage.getItem('classicSnakeBestScore') || 0;
        document.getElementById('bestScore').textContent = bestScore;

        // Game Status Management
        function updateGameStatus(status, isPlaying = false) {
            document.getElementById('gameStatus').textContent = status;
            const statusDot = document.getElementById('statusDot');
            statusDot.className = isPlaying ? 'status-indicator playing' : 'status-indicator';
        }

        // Initialize Game
        function init() {
            snake = [{ x: 12, y: 12 }];
            direction = 'RIGHT';
            placeFood();
            score = 0;
            paused = true;
            gameOver = false;
            document.getElementById('currentScore').textContent = score;
            updateGameStatus('Ready to Play');
            if (gameLoop) clearInterval(gameLoop);
            draw();
        }

        // Place Food
        function placeFood() {
            food = {
                x: Math.floor(Math.random() * gridSize),
                y: Math.floor(Math.random() * gridSize)
            };
            // Ensure food doesn't spawn on snake
            if (snake.some(segment => segment.x === food.x && segment.y === food.y)) {
                placeFood();
            }
        }

        // Draw Game
        function draw() {
            // Clear canvas with elegant gradient
            const gradient = ctx.createLinearGradient(0, 0, canvasSize, canvasSize);
            gradient.addColorStop(0, '#0a0a0a');
            gradient.addColorStop(1, '#1a1a1a');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, canvasSize, canvasSize);

            // Draw food with elegant styling
            ctx.fillStyle = '#d4af37';
            ctx.shadowColor = '#d4af37';
            ctx.shadowBlur = 8;
            ctx.fillRect(food.x * box + 3, food.y * box + 3, box - 6, box - 6);

            // Draw snake with elegant gradient
            snake.forEach((segment, index) => {
                const alpha = 1 - (index * 0.05);
                ctx.fillStyle = `rgba(156, 175, 136, ${Math.max(alpha, 0.3)})`;
                ctx.shadowColor = '#9caf88';
                ctx.shadowBlur = 3;
                ctx.fillRect(segment.x * box + 2, segment.y * box + 2, box - 4, box - 4);
            });
            
            ctx.shadowBlur = 0;
        }

        // Game Movement
        function move() {
            if (paused || gameOver) return;
            
            let head = { ...snake[0] };
            
            // Update head position based on direction
            switch(direction) {
                case 'LEFT': head.x--; break;
                case 'UP': head.y--; break;
                case 'RIGHT': head.x++; break;
                case 'DOWN': head.y++; break;
            }

            // Check collisions
            if (head.x < 0 || head.x >= gridSize || head.y < 0 || head.y >= gridSize || 
                snake.some(segment => segment.x === head.x && segment.y === head.y)) {
                return endGame();
            }

            snake.unshift(head);

            // Check if food eaten
            if (head.x === food.x && head.y === food.y) {
                score++;
                updateScore();
                placeFood();
            } else {
                snake.pop();
            }
            
            draw();
        }

        // Update Score with Animation
        function updateScore() {
            document.getElementById('currentScore').textContent = score;
            document.getElementById('currentScore').classList.add('score-update');
            setTimeout(() => {
                document.getElementById('currentScore').classList.remove('score-update');
            }, 300);
        }

        // End Game
        function endGame() {
            clearInterval(gameLoop);
            gameOver = true;
            updateGameStatus('Game Over');
            
            if (score > bestScore) {
                bestScore = score;
                localStorage.setItem('classicSnakeBestScore', bestScore);
                document.getElementById('bestScore').textContent = bestScore;
                notyf.success(`🏆 New High Score: ${score} points!`);
            } else {
                notyf.open({
                    type: 'warning',
                    message: `Game Over! Final Score: ${score} points`
                });
            }
        }

        // Game Controls
        function startGame() {
            if (gameOver) init();
            if (paused) {
                paused = false;
                updateGameStatus('Playing...', true);
                gameLoop = setInterval(move, 150);
                canvas.focus();
            }
        }

        function pauseGame() {
            if (!gameOver && !paused) {
                paused = true;
                clearInterval(gameLoop);
                updateGameStatus('Paused');
            }
        }

        function resetGame() {
            if (gameLoop) clearInterval(gameLoop);
            updateGameStatus('Resetting...');
            setTimeout(() => init(), 500);
        }

        // Keyboard Controls
        document.addEventListener('keydown', (e) => {
            const keyMap = {
                'ArrowLeft': 'LEFT',
                'ArrowUp': 'UP', 
                'ArrowRight': 'RIGHT',
                'ArrowDown': 'DOWN',
                ' ': 'PAUSE'
            };
            
            const newDirection = keyMap[e.key];
            const oppositeMap = { 
                'LEFT': 'RIGHT', 
                'RIGHT': 'LEFT', 
                'UP': 'DOWN', 
                'DOWN': 'UP' 
            };
            
            if (newDirection === 'PAUSE') {
                e.preventDefault();
                if (!gameOver) {
                    if (paused) startGame();
                    else pauseGame();
                }
            } else if (newDirection && direction !== oppositeMap[newDirection]) {
                direction = newDirection;
            }
        });

        // Canvas Focus
        canvas.addEventListener('click', () => canvas.focus());

        // Responsive Canvas
        function resizeCanvas() {
            const container = canvas.parentElement;
            const containerWidth = container.clientWidth - 32; // Account for padding
            const size = Math.min(containerWidth, 480);
            canvas.style.width = size + 'px';
            canvas.style.height = size + 'px';
        }

        window.addEventListener('resize', resizeCanvas);
        
        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            resizeCanvas();
            init();
        });
    </script>
</body>
</html>
