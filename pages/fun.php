<?php
session_start();
include('../includes/Navbar.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Fun Zone</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72, #2a5298);
      min-height: 100vh;
      color: #f8f9fa;
      font-family: 'Segoe UI', 'Roboto', sans-serif;
    }
    .fun-container {
      max-width: 960px;
      margin: 80px auto;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 2rem;
      padding: 3rem 2rem;
      backdrop-filter: blur(10px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
    }
    .fun-title {
      font-size: 2.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 2rem;
      color: #ffd369;
    }
    .game-card {
      background: rgba(255,255,255,0.08);
      border-radius: 1.5rem;
      padding: 1.8rem;
      margin-bottom: 1.5rem;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .game-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }
    .game-card h4 {
      color: #fff;
    }
    .game-card p {
      color: #dee2e6;
    }
    .fun-btn {
      margin-top: 1rem;
      background: #ffd369;
      border: none;
      padding: 0.6rem 1.5rem;
      font-weight: bold;
      border-radius: 0.5rem;
      color: #333;
      transition: background 0.3s ease;
    }
    .fun-btn:hover {
      background: #ffc107;
    }
    @media (max-width: 768px) {
      .fun-container {
        padding: 2rem 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="container fun-container">
    <div class="fun-title"><i class="fas fa-laugh-beam"></i> Welcome to the Fun Zone!</div>
    <div class="row">
      <div class="col-md-6">
        <div class="game-card text-center">
          <h4><i class="fas fa-gamepad"></i> Tic Tac Toe</h4>
          <p>Challenge your brain in this timeless strategy game.</p>
          <a href="../games/tictactoe.php" class="btn fun-btn">Play Now</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="game-card text-center">
          <h4><i class="fas fa-snake"></i> Snake Game</h4>
          <p>Feed the snake and avoid the walls in this arcade classic!</p>
          <a href="../games/snake.php" class="btn fun-btn">Play Now</a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
