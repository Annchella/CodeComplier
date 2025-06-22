<?php
session_start();
include('../includes/Navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Snake Game</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(120deg, #232526 0%, #414345 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .snake-glass {
      background: rgba(30,34,45,0.92);
      border-radius: 2rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.18);
      padding: 2.5rem 2rem 2rem 2rem;
      max-width: 480px;
      margin: 50px auto 40px auto;
      text-align: center;
      position: relative;
      overflow: hidden;
      border: 3px solid #00ffe7;
      animation: neon-glow 2s infinite alternate;
    }
    @keyframes neon-glow {
      0% { box-shadow: 0 0 20px #00ffe7, 0 0 40px #00ffe744; }
      100% { box-shadow: 0 0 40px #00ffe7, 0 0 80px #00ffe7aa; }
    }
    .snake-title {
      font-size: 2.2rem;
      font-weight: bold;
      color: #00ffe7;
      margin-bottom: 1.2rem;
      letter-spacing: 1px;
      text-shadow: 0 0 8px #00ffe7cc;
    }
    #snakeCanvas {
      background: #181c24;
      border-radius: 1rem;
      box-shadow: 0 4px 24px rgba(0,0,0,0.13);
      margin-bottom: 1.2rem;
      display: block;
      margin-left: auto;
      margin-right: auto;
      outline: none;
      border: 2px solid #00ffe7;
      transition: border 0.2s;
    }
    .score-box {
      font-size: 1.2rem;
      font-weight: 500;
      color: #00ffe7;
      margin-bottom: 0.5rem;
      letter-spacing: 1px;
    }
    .best-score {
      font-size: 1rem;
      color: #ffb347;
      margin-bottom: 1rem;
    }
    .snake-btn {
      font-size: 1.1rem;
      padding: 0.7rem 2.2rem;
      border-radius: 2rem;
      margin: 0.3rem;
      background: linear-gradient(90deg, #00ffe7 0%, #00c3ff 100%);
      color: #181c24;
      border: none;
      box-shadow: 0 2px 8px rgba(0,255,231,0.08);
      transition: background 0.2s, color 0.2s, transform 0.15s;
      font-weight: 500;
      text-shadow: 0 0 4px #fff;
    }
    .snake-btn:hover {
      background: linear-gradient(90deg, #00c3ff 0%, #00ffe7 100%);
      color: #fff;
      transform: scale(1.07);
    }
    .howto {
      font-size: 1rem;
      color: #aaa;
      margin-top: 1.5rem;
      margin-bottom: 0.5rem;
    }
    .sound-btn {
      background: none;
      border: none;
      color: #00ffe7;
      font-size: 1.5rem;
      margin-left: 0.5rem;
      vertical-align: middle;
      transition: color 0.2s;
    }
    .sound-btn:hover { color: #ffb347; }
    .pause-overlay {
      display: none;
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(24,28,36,0.88);
      border-radius: 2rem;
      z-index: 10;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      color: #00ffe7;
      font-size: 2rem;
      font-weight: bold;
      text-shadow: 0 0 8px #00ffe7cc;
      animation: animate__fadeIn 0.5s;
    }
    .pause-overlay.active { display: flex; }
    @media (max-width: 600px) {
      .snake-glass { padding: 1.2rem 0.5rem; }
      #snakeCanvas { width: 90vw !important; height: 90vw !important; }
    }
  </style>
</head>
<body>
  <div class="snake-glass animate__animated animate__fadeInDown">
    <div class="snake-title"><i class="fas fa-apple-alt text-warning"></i> Snake Game</div>
    <div class="score-box">Score: <span id="score">0</span></div>
    <div class="best-score">Best: <span id="bestScore">0</span></div>
    <canvas id="snakeCanvas" width="320" height="320" tabindex="0"></canvas>
    <div>
      <button class="snake-btn" onclick="startGame()"><i class="fas fa-play"></i> Start</button>
      <button class="snake-btn" onclick="pauseGame()"><i class="fas fa-pause"></i> Pause</button>
      <button class="snake-btn" onclick="resetGame()"><i class="fas fa-redo"></i> Reset</button>
      <button class="sound-btn" id="soundBtn" title="Toggle Sound"><i class="fas fa-volume-up"></i></button>
    </div>
    <div class="howto">
      <i class="fas fa-keyboard"></i> Use <b>Arrow keys</b> or <b>WASD</b> to move.<br>
      <span class="text-muted">Eat apples <i class="fas fa-apple-alt text-warning"></i> to grow!</span>
    </div>
    <div class="pause-overlay" id="pauseOverlay">
      <i class="fas fa-pause-circle"></i>
      <div>Paused</div>
    </div>
  </div>
  <!-- Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Sound effects
    const eatSound = new Audio("https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa4c2e.mp3");
    const gameOverSound = new Audio("https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa4c2e.mp3");
    let soundOn = true;
    document.getElementById('soundBtn').onclick = function() {
      soundOn = !soundOn;
      this.innerHTML = soundOn
        ? '<i class="fas fa-volume-up"></i>'
        : '<i class="fas fa-volume-mute"></i>';
    };

    // Snake Game Logic
    const canvas = document.getElementById('snakeCanvas');
    const ctx = canvas.getContext('2d');
    const box = 20;
    let snake, direction, food, score, game, paused, gameOver;
    let bestScore = localStorage.getItem('snakeBest') ? parseInt(localStorage.getItem('snakeBest')) : 0;
    document.getElementById('bestScore').textContent = bestScore;

    function resetGame() {
      snake = [{x: 8, y: 8}];
      direction = 'RIGHT';
      food = {x: Math.floor(Math.random()*16), y: Math.floor(Math.random()*16)};
      score = 0;
      paused = false;
      gameOver = false;
      document.getElementById('score').textContent = score;
      document.getElementById('pauseOverlay').classList.remove('active');
      draw();
    }

    function draw() {
      ctx.fillStyle = "#181c24";
      ctx.fillRect(0,0,320,320);

      // Draw snake
      for (let i=0; i<snake.length; i++) {
        ctx.shadowColor = "#00ffe7";
        ctx.shadowBlur = i==0 ? 16 : 4;
        ctx.fillStyle = i==0 ? "#00ffe7" : "#00c3ff";
        ctx.fillRect(snake[i].x*box, snake[i].y*box, box-2, box-2);
        ctx.strokeStyle = "#fff";
        ctx.strokeRect(snake[i].x*box, snake[i].y*box, box-2, box-2);
      }
      ctx.shadowBlur = 0;
      // Draw food
      ctx.fillStyle = "#ffb347";
      ctx.beginPath();
      ctx.arc(food.x*box+box/2, food.y*box+box/2, box/2.5, 0, 2*Math.PI);
      ctx.fill();
      ctx.font = "18px Arial";
      ctx.fillStyle = "#fff";
      ctx.fillText("🍎", food.x*box+2, food.y*box+box-4);
    }

    function move() {
      if (paused || gameOver) return;
      let head = {...snake[0]};
      if (direction === 'LEFT') head.x--;
      if (direction === 'UP') head.y--;
      if (direction === 'RIGHT') head.x++;
      if (direction === 'DOWN') head.y++;

      // Wall collision
      if (head.x<0 || head.x>15 || head.y<0 || head.y>15) return endGame();

      // Self collision
      for (let s of snake) if (s.x === head.x && s.y === head.y) return endGame();

      snake.unshift(head);

      // Eat food
      if (head.x === food.x && head.y === food.y) {
        score++;
        document.getElementById('score').textContent = score;
        if (soundOn) eatSound.play();
        food = {x: Math.floor(Math.random()*16), y: Math.floor(Math.random()*16)};
      } else {
        snake.pop();
      }
      draw();
    }

    function endGame() {
      clearInterval(game);
      gameOver = true;
      if (soundOn) gameOverSound.play();
      if (score > bestScore) {
        bestScore = score;
        localStorage.setItem('snakeBest', bestScore);
        document.getElementById('bestScore').textContent = bestScore;
        Swal.fire({
          icon: 'success',
          title: 'New Best Score!',
          text: `Your new best: ${score}`,
          confirmButtonColor: '#00ffe7'
        });
      } else {
        Swal.fire({
          icon: 'info',
          title: 'Game Over!',
          text: `Your score: ${score}`,
          confirmButtonColor: '#00ffe7'
        });
      }
    }

    function startGame() {
      if (gameOver) resetGame();
      if (game) clearInterval(game);
      paused = false;
      document.getElementById('pauseOverlay').classList.remove('active');
      game = setInterval(move, 110);
      canvas.focus();
    }

    function pauseGame() {
      paused = !paused;
      if (paused) {
        clearInterval(game);
        document.getElementById('pauseOverlay').classList.add('active');
      } else {
        document.getElementById('pauseOverlay').classList.remove('active');
        game = setInterval(move, 110);
      }
    }

    document.addEventListener('keydown', function(e) {
      if (gameOver) return;
      if (["ArrowLeft","a","A"].includes(e.key) && direction!=='RIGHT') direction='LEFT';
      else if (["ArrowUp","w","W"].includes(e.key) && direction!=='DOWN') direction='UP';
      else if (["ArrowRight","d","D"].includes(e.key) && direction!=='LEFT') direction='RIGHT';
      else if (["ArrowDown","s","S"].includes(e.key) && direction!=='UP') direction='DOWN';
      else if (e.key === " " || e.key === "Spacebar") pauseGame();
    });

    // Responsive canvas
    function resizeCanvas() {
      if (window.innerWidth < 400) {
        canvas.width = canvas.height = Math.min(window.innerWidth * 0.9, 320);
      } else {
        canvas.width = canvas.height = 320;
      }
      draw();
    }
    window.addEventListener('resize', resizeCanvas);

    // Start game on load
    resetGame();
    resizeCanvas();
  </script>
</body>
</html>
