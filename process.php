<?php
session_start();
require 'db.php'; // Include database connection

// Ensure it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // SIGNUP PROCESS
    if ($action === 'signup') {
        $name = $conn->real_escape_string(trim($_POST['name']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $password = trim($_POST['password']);

        // Input validation
        if (empty($name) || empty($email) || empty($password)) {
            die("Error: All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Error: Invalid email format.");
        }

        if (strlen($password) < 6) {
            die("Error: Password must be at least 6 characters.");
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            die("Error: Email is already registered.");
        }
        $stmt->close();

        // Store user in database without password hashing
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            header("Location: login.html?signup=success");
            exit();
        } else {
            die("Error: " . $conn->error);
        }
        $stmt->close();
    }

    // LOGIN PROCESS
    if ($action === 'login') {
        $email = $conn->real_escape_string(trim($_POST['email']));
        $password = trim($_POST['password']);

        // Input validation
        if (empty($email) || empty($password)) {
            die("Error: Both email and password are required.");
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Directly compare passwords (not secure)
            if ($password === $user['password']) {
                // Store session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                // Redirect to home page after login
                header("Location: http://localhost/AD%20LAB%20PROJECT/index.html");
                exit();
            } else {
                die("Error: Incorrect password.");
            }
        } else {
            die("Error: No account found with that email.");
        }

        $stmt->close();
    }
}

$conn->close();
?>