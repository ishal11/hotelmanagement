<?php
// db.php - Database connection
$host = 'localhost';      // Change if different
$username = 'root';       // Your MySQL username
$password = '';           // Your MySQL password
$database = 'hotel_coco_palms';

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
