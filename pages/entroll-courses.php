<?php
session_start();
include('../includes/db.php');
$user_id = $_SESSION['user']['id'];
$course_id = $_GET['course_id'];

// Check if already purchased
$check = $conn->query("SELECT * FROM purchases WHERE user_id=$user_id AND course_id=$course_id");
if($check->num_rows > 0){
    echo "You already enrolled this course!";
    exit;
}

// Create pending payment
$course = $conn->query("SELECT * FROM courses WHERE id=$course_id")->fetch_assoc();
$amount = $course['price'];

$conn->query("INSERT INTO payments (user_id, amount, status) VALUES ($user_id, $amount, 'pending')");
$payment_id = $conn->insert_id;

// Redirect to payment gateway with payment_id, amount
header("Location: payment_gateway.php?payment_id=$payment_id");
