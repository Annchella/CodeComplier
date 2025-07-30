<?php
session_start();
include('../includes/Navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Classic Tic-Tac-Toe</title>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;500&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- AOS Animation Library -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Notyf for elegant notifications -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
  
  <style>
    :root {
      --primary-gold: #d4af37;
      --secondary-gold: #b8941f;
      --dark-navy: #1a1a2e;
      --light-navy: #16213e;
      --cream: #f8f6f0;
      --text-dark: #2c2c2c;
      --text-light: #6c6c6c;
      --shadow-elegant: 0 10px 30px rgba(0, 0, 0, 0.15);
      --shadow-subtle: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Lato', sans-serif;
      background: linear-gradient(135deg, var(--cream) 0%, #f0ebe5 100%);
      min-height: 100vh;
      color: var(--text-dark);
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
      background: 
        radial-gradient(circle at 25% 25%, rgba(212, 175, 55, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(212, 175, 55, 0.03) 0%, transparent 50%);
      z-index: -1;
      pointer-events: none;
    }

    .game-container {
      max-width: 600px;
      margin: 3rem auto;
      padding: 0 1rem;
    }

    .game-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      box-shadow: var(--shadow-elegant);
      padding: 3rem 2.5rem;
      text-align: center;
      border: 1px solid rgba(212, 175, 55, 0.2);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }

    .game-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-gold), var(--secondary-gold));
    }

    .game-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--dark-navy);
      margin-bottom: 0.5rem;
      letter-spacing: -0.02em;
    }

    .game-subtitle {
      font-size: 1rem;
      color: var(--text-light);
      margin-bottom: 2rem;
      font-style: italic;
    }

    .scoreboard {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-bottom: 2rem;
      flex-wrap: wrap;
    }

    .score-item {
      background: var(--cream);
      border: 1px solid rgba(212, 175, 55, 0.3);
      border-radius: 8px;
      padding: 1rem 1.5rem;
      min-width: 120px;
      transition: all 0.3s ease;
    }

    .score-item:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-subtle);
    }

    .score-label {
      font-size: 0.85rem;
      color: var(--text-light);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.25rem;
    }

    .score-value {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-navy);
    }

    .game-status {
      margin: 1.5rem 0;
      padding: 1rem;
      background: rgba(212, 175, 55, 0.1);
      border-radius: 8px;
      border-left: 4px solid var(--primary-gold);
    }

    .status-text {
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--dark-navy);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .game-board {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      max-width: 300px;
      margin: 2rem auto;
      padding: 1.5rem;
      background: var(--dark-navy);
      border-radius: 12px;
      box-shadow: var(--shadow-elegant);
    }

    .game-cell {
      aspect-ratio: 1;
      background: var(--cream);
      border: none;
      border-radius: 6px;
      font-size: 2rem;
      font-weight: 600;
      color: var(--dark-navy);
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    .game-cell::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .game-cell:hover::before {
      left: 100%;
    }

    .game-cell:hover {
      background: rgba(212, 175, 55, 0.1);
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .game-cell:disabled {
      cursor: not-allowed;
      opacity: 0.8;
    }

    .game-cell.x-mark {
      color: #c53030;
    }

    .game-cell.o-mark {
      color: #3182ce;
    }

    .game-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }

    .elegant-btn {
      padding: 0.75rem 2rem;
      border: 2px solid var(--primary-gold);
      background: transparent;
      color: var(--primary-gold);
      border-radius: 6px;
      font-weight: 500;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .elegant-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: var(--primary-gold);
      transition: left 0.3s ease;
      z-index: -1;
    }

    .elegant-btn:hover::before {
      left: 0;
    }

    .elegant-btn:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
    }

    .elegant-btn.primary {
      background: var(--primary-gold);
      color: white;
    }

    .elegant-btn.primary::before {
      background: var(--secondary-gold);
    }

    /* Winning animation */
    @keyframes celebrate {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }

    .winner-cell {
      animation: celebrate 0.6s ease-in-out;
      background: rgba(212, 175, 55, 0.3) !important;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .game-container {
        margin: 1rem auto;
        padding: 0 0.5rem;
      }

      .game-card {
        padding: 2rem 1.5rem;
      }

      .game-title {
        font-size: 2rem;
      }

      .scoreboard {
        gap: 1rem;
      }

      .score-item {
        padding: 0.75rem 1rem;
        min-width: 100px;
      }

      .game-board {
        max-width: 250px;
        padding: 1rem;
      }

      .game-cell {
        font-size: 1.5rem;
      }

      .game-controls {
        flex-direction: column;
        align-items: center;
      }

      .elegant-btn {
        width: 100%;
        max-width: 200px;
        justify-content: center;
      }
    }

    /* Custom notification styles */
    .notyf__toast--success {
      background: var(--primary-gold);
    }

    .notyf__toast--error {
      background: var(--secondary-gold);
    }
  </style>
</head>
<body>
  <div class="game-container">
    <div class="game-card" data-aos="fade-up" data-aos-duration="800">
      <h1 class="game-title">Tic-Tac-Toe</h1>
      <p class="game-subtitle">A timeless classic reimagined</p>
      
      <div class="scoreboard" data-aos="fade-up" data-aos-delay="200">
        <div class="score-item">
          <div class="score-label">Player X</div>
          <div class="score-value" id="scoreX">0</div>
        </div>
        <div class="score-item">
          <div class="score-label">Player O</div>
          <div class="score-value" id="scoreO">0</div>
        </div>
        <div class="score-item">
          <div class="score-label">Draws</div>
          <div class="score-value" id="scoreDraw">0</div>
        </div>
      </div>

      <div class="game-status" data-aos="fade-up" data-aos-delay="400">
        <div class="status-text" id="gameStatus">
          <i class="fas fa-times" style="color: #c53030;"></i>
          <span>Player X's turn</span>
        </div>
      </div>

      <div class="game-board" data-aos="zoom-in" data-aos-delay="600">
        <button class="game-cell" data-cell="0"></button>
        <button class="game-cell" data-cell="1"></button>
        <button class="game-cell" data-cell="2"></button>
        <button class="game-cell" data-cell="3"></button>
        <button class="game-cell" data-cell="4"></button>
        <button class="game-cell" data-cell="5"></button>
        <button class="game-cell" data-cell="6"></button>
        <button class="game-cell" data-cell="7"></button>
        <button class="game-cell" data-cell="8"></button>
      </div>

      <div class="game-controls" data-aos="fade-up" data-aos-delay="800">
        <button class="elegant-btn primary" onclick="restartGame()">
          <i class="fas fa-redo"></i>
          <span>New Game</span>
        </button>
        <button class="elegant-btn" onclick="resetScore()">
          <i class="fas fa-eraser"></i>
          <span>Reset Score</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
  
  <script>
    // Initialize AOS
    AOS.init({
      duration: 600,
      easing: 'ease-out-cubic',
      once: true
    });

    // Initialize Notyf
    const notyf = new Notyf({
      duration: 2000,
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
          type: 'info',
          background: '#b8941f',
          icon: {
            className: 'fas fa-handshake',
            tagName: 'i',
            color: 'white'
          }
        }
      ]
    });

    // Game state
    let gameBoard = Array(9).fill('');
    let currentPlayer = 'X';
    let gameActive = true;
    let scores = {
      X: parseInt(localStorage.getItem('tttScoreX')) || 0,
      O: parseInt(localStorage.getItem('tttScoreO')) || 0,
      draw: parseInt(localStorage.getItem('tttScoreDraw')) || 0
    };

    // DOM elements
    const cells = document.querySelectorAll('.game-cell');
    const gameStatus = document.getElementById('gameStatus');
    const scoreElements = {
      X: document.getElementById('scoreX'),
      O: document.getElementById('scoreO'),
      draw: document.getElementById('scoreDraw')
    };

    // Winning combinations
    const winningCombinations = [
      [0, 1, 2], [3, 4, 5], [6, 7, 8], // Rows
      [0, 3, 6], [1, 4, 7], [2, 5, 8], // Columns
      [0, 4, 8], [2, 4, 6] // Diagonals
    ];

    // Initialize game
    function initGame() {
      updateScoreDisplay();
      updateGameStatus();
      cells.forEach(cell => {
        cell.addEventListener('click', handleCellClick);
      });
    }

    // Handle cell click
    function handleCellClick(e) {
      const cellIndex = parseInt(e.target.dataset.cell);
      
      if (gameBoard[cellIndex] !== '' || !gameActive) {
        return;
      }

      // Make move
      gameBoard[cellIndex] = currentPlayer;
      e.target.textContent = currentPlayer;
      e.target.classList.add(currentPlayer === 'X' ? 'x-mark' : 'o-mark');
      e.target.disabled = true;

      // Check for winner
      if (checkWinner()) {
        return;
      }

      // Check for draw
      if (gameBoard.every(cell => cell !== '')) {
        gameActive = false;
        gameStatus.innerHTML = `
          <i class="fas fa-handshake" style="color: #b8941f;"></i>
          <span>It's a draw!</span>
        `;
        scores.draw++;
        localStorage.setItem('tttScoreDraw', scores.draw);
        updateScoreDisplay();
        notyf.open({ type: 'info', message: 'Game ended in a draw!' });
        return;
      }

      // Switch player
      currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
      updateGameStatus();
    }

    // Check for winner
    function checkWinner() {
      for (let combination of winningCombinations) {
        const [a, b, c] = combination;
        if (gameBoard[a] && gameBoard[a] === gameBoard[b] && gameBoard[a] === gameBoard[c]) {
          gameActive = false;
          
          // Highlight winning cells
          combination.forEach(index => {
            cells[index].classList.add('winner-cell');
          });

          // Update status
          const winnerIcon = gameBoard[a] === 'X' ? 
            '<i class="fas fa-times" style="color: #c53030;"></i>' : 
            '<i class="fas fa-circle" style="color: #3182ce;"></i>';
          
          gameStatus.innerHTML = `
            ${winnerIcon}
            <span>Player ${gameBoard[a]} wins!</span>
          `;

          // Update score
          scores[gameBoard[a]]++;
          localStorage.setItem(`tttScore${gameBoard[a]}`, scores[gameBoard[a]]);
          updateScoreDisplay();

          // Show notification
          notyf.success(`Player ${gameBoard[a]} wins the game!`);

          // Disable all cells
          cells.forEach(cell => cell.disabled = true);
          
          return true;
        }
      }
      return false;
    }

    // Update game status
    function updateGameStatus() {
      if (gameActive) {
        const playerIcon = currentPlayer === 'X' ? 
          '<i class="fas fa-times" style="color: #c53030;"></i>' : 
          '<i class="fas fa-circle" style="color: #3182ce;"></i>';
        
        gameStatus.innerHTML = `
          ${playerIcon}
          <span>Player ${currentPlayer}'s turn</span>
        `;
      }
    }

    // Update score display
    function updateScoreDisplay() {
      scoreElements.X.textContent = scores.X;
      scoreElements.O.textContent = scores.O;
      scoreElements.draw.textContent = scores.draw;
    }

    // Restart game
    function restartGame() {
      gameBoard = Array(9).fill('');
      currentPlayer = 'X';
      gameActive = true;
      
      cells.forEach(cell => {
        cell.textContent = '';
        cell.disabled = false;
        cell.classList.remove('x-mark', 'o-mark', 'winner-cell');
      });
      
      updateGameStatus();
    }

    // Reset score
    function resetScore() {
      scores = { X: 0, O: 0, draw: 0 };
      localStorage.removeItem('tttScoreX');
      localStorage.removeItem('tttScoreO');
      localStorage.removeItem('tttScoreDraw');
      updateScoreDisplay();
      restartGame();
      notyf.open({ type: 'info', message: 'Scoreboard has been reset!' });
    }

    // Initialize the game
    initGame();
  </script>
</body>
</html>
