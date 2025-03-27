<?php
// Include database connection file
require_once 'db.php';

// Start session to access user ID
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Get user ID from session
$userId = $_SESSION['user_id'];

// Get room type from URL parameter securely
$roomType = isset($_GET['room_type']) ? htmlspecialchars($_GET['room_type']) : 'Unknown';

// Check if form is submitted
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];

    // Validate input
    if (empty($checkIn) || empty($checkOut)) {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        // Insert booking into database
        $stmt = $conn->prepare("INSERT INTO booking_rooms (user_id, check_in, check_out, room_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $userId, $checkIn, $checkOut, $roomType);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Booking successful!</div>";
            // Redirect to booking details page
            header("Location: booking_details.php?room_type=$roomType&check_in=$checkIn&check_out=$checkOut&user_id=$userId");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}

// Close the connection after all operations
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Room</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Book a <?= $roomType ?> Room</h2>
            </div>
            <div class="card-body">
                <?= $message; ?>

                <form action="book_room.php?room_type=<?= urlencode($roomType) ?>" method="post">
                    <div class="mb-3">
                        <label for="checkIn" class="form-label">Check-in Date:</label>
                        <input type="date" id="checkIn" name="checkIn" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="checkOut" class="form-label">Check-out Date:</label>
                        <input type="date" id="checkOut" name="checkOut" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Book Now</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
