<?php
session_start();
include('../includes/Navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tic-Tac-Toe</title>
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
      background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    }
    .ttt-glass {
      background: rgba(255,255,255,0.92);
      border-radius: 2rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.13);
      padding: 2.5rem 2rem 2rem 2rem;
      max-width: 480px;
      margin: 50px auto 40px auto;
      text-align: center;
      position: relative;
      overflow: hidden;
      border: 3px solid #ffb347;
      animation: ttt-glow 2s infinite alternate;
    }
    @keyframes ttt-glow {
      0% { box-shadow: 0 0 20px #ffb347, 0 0 40px #ffb34744; }
      100% { box-shadow: 0 0 40px #ffb347, 0 0 80px #ffb347aa; }
    }
    .ttt-title {
      font-size: 2.2rem;
      font-weight: bold;
      color: #ff7e5f;
      margin-bottom: 1.2rem;
      letter-spacing: 1px;
      text-shadow: 0 0 8px #ffb347cc;
    }
    .ttt-board {
      display: grid;
      grid-template-columns: repeat(3, 80px);
      grid-gap: 12px;
      justify-content: center;
      margin: 1.5rem auto 0 auto;
    }
    .ttt-cell {
      width: 80px; height: 80px;
      font-size: 2.3rem;
      font-weight: bold;
      border-radius: 1rem;
      border: 2px solid #ffb347;
      background: rgba(255,255,255,0.7);
      color: #ff7e5f;
      transition: background 0.2s, color 0.2s, box-shadow 0.2s;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      outline: none;
      display: flex;
      align-items: center;
      justify-content: center;
      user-select: none;
    }
    .ttt-cell:hover {
      background: #ffb34722;
      color: #ff3e55;
      box-shadow: 0 0 12px #ffb34755;
    }
    .ttt-status {
      margin: 1.2rem 0 0.5rem 0;
      font-weight: 600;
      color: #ff7e5f;
      font-size: 1.1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
    }
    .scoreboard {
      display: flex;
      justify-content: center;
      gap: 2.5rem;
      margin-bottom: 1.2rem;
      margin-top: 0.5rem;
    }
    .score-box {
      background: rgba(255,255,255,0.7);
      border-radius: 1rem;
      padding: 0.7rem 1.2rem;
      font-size: 1.1rem;
      color: #ff7e5f;
      font-weight: 500;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .ttt-btn {
      font-size: 1.1rem;
      padding: 0.7rem 2.2rem;
      border-radius: 2rem;
      margin: 0.3rem;
      background: linear-gradient(90deg, #ffecd2 0%, #fcb69f 100%);
      color: #ff7e5f;
      border: none;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      transition: background 0.2s, color 0.2s, transform 0.15s;
      font-weight: 500;
    }
    .ttt-btn:hover {
      background: linear-gradient(90deg, #fcb69f 0%, #ffecd2 100%);
      color: #fff;
      transform: scale(1.07);
    }
    @media (max-width: 600px) {
      .ttt-glass { padding: 1.2rem 0.5rem; }
      .ttt-board { grid-template-columns: repeat(3, 55px); grid-gap: 7px; }
      .ttt-cell { width: 55px; height: 55px; font-size: 1.4rem; }
      .scoreboard { gap: 1.2rem; }
      .score-box { padding: 0.5rem 0.7rem; font-size: 0.95rem; }
    }
  </style>
</head>
<body>
  <div class="ttt-glass animate__animated animate__fadeInDown">
    <div class="ttt-title"><i class="fas fa-gamepad text-warning"></i> Tic-Tac-Toe</div>
    <div class="scoreboard">
      <div class="score-box"><i class="fas fa-times text-danger"></i> X: <span id="scoreX">0</span></div>
      <div class="score-box"><i class="fas fa-circle text-primary"></i> O: <span id="scoreO">0</span></div>
      <div class="score-box"><i class="fas fa-equals text-secondary"></i> Draws: <span id="scoreDraw">0</span></div>
    </div>
    <div class="ttt-status" id="ttt-status">
      <span id="turnIcon"><i class="fas fa-times text-danger"></i></span>
      <span id="turnText">Player X's turn</span>
    </div>
    <div class="ttt-board mb-2" id="ttt-board">
      <button class="ttt-cell" data-cell="0"></button>
      <button class="ttt-cell" data-cell="1"></button>
      <button class="ttt-cell" data-cell="2"></button>
      <button class="ttt-cell" data-cell="3"></button>
      <button class="ttt-cell" data-cell="4"></button>
      <button class="ttt-cell" data-cell="5"></button>
      <button class="ttt-cell" data-cell="6"></button>
      <button class="ttt-cell" data-cell="7"></button>
      <button class="ttt-cell" data-cell="8"></button>
    </div>
    <button class="ttt-btn" onclick="tttRestart()"><i class="fas fa-redo"></i> Restart</button>
    <button class="ttt-btn" onclick="tttResetScore()"><i class="fas fa-eraser"></i> Reset Score</button>
  </div>
  <!-- Libraries -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Scoreboard
    let scoreX = localStorage.getItem('tttScoreX') ? parseInt(localStorage.getItem('tttScoreX')) : 0;
    let scoreO = localStorage.getItem('tttScoreO') ? parseInt(localStorage.getItem('tttScoreO')) : 0;
    let scoreDraw = localStorage.getItem('tttScoreDraw') ? parseInt(localStorage.getItem('tttScoreDraw')) : 0;
    document.getElementById('scoreX').textContent = scoreX;
    document.getElementById('scoreO').textContent = scoreO;
    document.getElementById('scoreDraw').textContent = scoreDraw;

    // Game logic
    let tttBoard = Array(9).fill('');
    let tttCurrent = 'X';
    let tttGameOver = false;
    const tttStatus = document.getElementById('ttt-status');
    const tttCells = document.querySelectorAll('.ttt-cell');
    const turnIcon = document.getElementById('turnIcon');
    const turnText = document.getElementById('turnText');

    function tttCheckWinner() {
      const wins = [
        [0,1,2],[3,4,5],[6,7,8],
        [0,3,6],[1,4,7],[2,5,8],
        [0,4,8],[2,4,6]
      ];
      for (let w of wins) {
        if (tttBoard[w[0]] && tttBoard[w[0]] === tttBoard[w[1]] && tttBoard[w[1]] === tttBoard[w[2]]) {
          return tttBoard[w[0]];
        }
      }
      if (tttBoard.every(cell => cell)) return 'Draw';
      return null;
    }

    function tttHandleClick(e) {
      const idx = +e.target.dataset.cell;
      if (tttBoard[idx] || tttGameOver) return;
      tttBoard[idx] = tttCurrent;
      e.target.innerHTML = tttCurrent === 'X'
        ? '<i class="fas fa-times text-danger"></i>'
        : '<i class="fas fa-circle text-primary"></i>';
      const winner = tttCheckWinner();
      if (winner) {
        tttGameOver = true;
        if (winner === 'Draw') {
          scoreDraw++;
          localStorage.setItem('tttScoreDraw', scoreDraw);
          document.getElementById('scoreDraw').textContent = scoreDraw;
          tttStatus.innerHTML = `<i class="fas fa-equals text-secondary"></i> <span>It's a draw!</span>`;
          Swal.fire({
            icon: 'info',
            title: "It's a draw!",
            showConfirmButton: false,
            timer: 1400
          });
        } else {
          if (winner === 'X') {
            scoreX++;
            localStorage.setItem('tttScoreX', scoreX);
            document.getElementById('scoreX').textContent = scoreX;
          } else {
            scoreO++;
            localStorage.setItem('tttScoreO', scoreO);
            document.getElementById('scoreO').textContent = scoreO;
          }
          tttStatus.innerHTML = winner === 'X'
            ? `<i class="fas fa-times text-danger"></i> <span>Player X wins!</span>`
            : `<i class="fas fa-circle text-primary"></i> <span>Player O wins!</span>`;
          Swal.fire({
            icon: 'success',
            title: `Player ${winner} wins!`,
            showConfirmButton: false,
            timer: 1400
          });
        }
      } else {
        tttCurrent = tttCurrent === 'X' ? 'O' : 'X';
        turnIcon.innerHTML = tttCurrent === 'X'
          ? '<i class="fas fa-times text-danger"></i>'
          : '<i class="fas fa-circle text-primary"></i>';
        turnText.textContent = `Player ${tttCurrent}'s turn`;
      }
    }
    tttCells.forEach(cell => cell.addEventListener('click', tttHandleClick));
    function tttRestart() {
      tttBoard = Array(9).fill('');
      tttCurrent = 'X';
      tttGameOver = false;
      tttCells.forEach(cell => cell.innerHTML = '');
      turnIcon.innerHTML = '<i class="fas fa-times text-danger"></i>';
      turnText.textContent = "Player X's turn";
      tttStatus.classList.remove('animate__shakeX');
    }
    function tttResetScore() {
      scoreX = scoreO = scoreDraw = 0;
      localStorage.setItem('tttScoreX', 0);
      localStorage.setItem('tttScoreO', 0);
      localStorage.setItem('tttScoreDraw', 0);
      document.getElementById('scoreX').textContent = 0;
      document.getElementById('scoreO').textContent = 0;
      document.getElementById('scoreDraw').textContent = 0;
      tttRestart();
      Swal.fire({
        icon: 'info',
        title: 'Scoreboard Reset!',
        showConfirmButton: false,
        timer: 1200
      });
    }
    tttRestart();
  </script>
</body>
</html>
