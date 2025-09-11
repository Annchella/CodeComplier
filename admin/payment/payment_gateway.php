<?php
session_start();
include('../includes/db.php');

$payment_id = $_GET['payment_id'];

// Simulate success
$conn->query("UPDATE payments SET status='success' WHERE id=$payment_id");

// Create purchase
$payment = $conn->query("SELECT * FROM payments WHERE id=$payment_id")->fetch_assoc();
$user_id = $payment['user_id'];

// Assuming course_id was sent via session or GET
$course_id = $_SESSION['course_id'];
$conn->query("INSERT INTO purchases (user_id, course_id, payment_id) VALUES ($user_id, $course_id, $payment_id)");

echo "Payment successful! Course added to your account.";
