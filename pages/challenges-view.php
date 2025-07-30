<?php
require_once 'includes/db.php';
session_start();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM challenges WHERE id = ?");
$stmt->execute([$id]);
$challenge = $stmt->fetch();

if (!$challenge) {
  die("Challenge not found.");
}
?>
<div class="container mt-5">
  <h2><?= htmlspecialchars($challenge['title']) ?></h2>
  <p class="text-muted">Difficulty: <?= $challenge['difficulty'] ?></p>
  <p><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>

  <form action="submit_challenge.php" method="POST">
    <input type="hidden" name="challenge_id" value="<?= $challenge['id'] ?>">
    <textarea name="submitted_code" class="form-control" rows="10" placeholder="Write your code here..." required></textarea>
    <button class="btn btn-success mt-3" type="submit">Submit Code</button>
  </form>
</div>
