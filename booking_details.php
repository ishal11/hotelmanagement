.<?php
// Include database connection file
require_once 'db.php';

// Start session
session_start();

// Get booking details from URL parameters securely
$roomType = isset($_GET['room_type']) ? htmlspecialchars($_GET['room_type']) : 'Unknown';
$checkIn = isset($_GET['check_in']) ? htmlspecialchars($_GET['check_in']) : 'Not Provided';
$checkOut = isset($_GET['check_out']) ? htmlspecialchars($_GET['check_out']) : 'Not Provided';
$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

$userName = "Unknown User"; // Default value if user not found

if ($userId > 0) {
    // Query to fetch user details
    $stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $userName = htmlspecialchars($row['name']);
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2>Booking Details</h2>
            </div>
            <div class="card-body">
                <p><strong>Room Type:</strong> <?= $roomType ?></p>
                <p><strong>Check-in Date:</strong> <?= $checkIn ?></p>
                <p><strong>Check-out Date:</strong> <?= $checkOut ?></p>
                <p><strong>Booked By:</strong> <?= $userName ?></p>
            </div>
        </div>
    </div>

</body>
</html>
