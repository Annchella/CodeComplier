<?php
session_start();
include('../includes/db.php');
$user_id = $_SESSION['user']['id'];

$courses = $conn->query("
    SELECT c.* 
    FROM courses c 
    JOIN purchases p ON c.id = p.course_id
    WHERE p.user_id = $user_id
");

while($course = $courses->fetch_assoc()){
    echo "<div class='course-card'>
            <h3>{$course['title']}</h3>
            <p>{$course['description']}</p>
          </div>";
}
