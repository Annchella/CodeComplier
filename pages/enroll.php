<?php
session_start();
include('../includes/db.php');

if(!isset($_SESSION['user'])){
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$course_id = $_GET['course_id'] ?? 0;

if(!$course_id){
    die("Invalid course");
}

// Check if already purchased
$check = $pdo->prepare("SELECT * FROM purchases WHERE user_id=? AND course_id=?");
$check->execute([$user_id, $course_id]);
if($check->rowCount() > 0){
    die("You already enrolled this course!");
}

// Get course price
$course_stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$course_stmt->execute([$course_id]);
$course = $course_stmt->fetch(PDO::FETCH_ASSOC);
if(!$course){
    die("Course not found.");
}
$amount = $course['price'];

// Create pending payment
$payment_stmt = $pdo->prepare("INSERT INTO payments (user_id, amount, status) VALUES (?, ?, 'pending')");
$payment_stmt->execute([$user_id, $amount]);
$payment_id = $pdo->lastInsertId();

// Save course_id to session for later payment processing
$_SESSION['course_id'] = $course_id;

// Redirect to your payment page
header("Location: payment_gateway.php?payment_id=$payment_id");
exit;
