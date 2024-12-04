<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root";
$password = "harsh";
$dbname = "user_auth";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch and sanitize input data
$user = htmlspecialchars(trim($_POST['username'])); // Clean username
$pass = $_POST['password']; // Password from the form

// Check if the user exists
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify hashed password
    if (password_verify($pass, $row['password'])) {
        header("Location: page2.php"); // Redirect to the next page
        exit();
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href='index.html';</script>";
    }
} else {
    echo "<script>alert('Invalid username or password'); window.location.href='index.html';</script>";
}

$stmt->close();
$conn->close();
?>
