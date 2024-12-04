<?php
// Database connection
$servername = "localhost";
$username = ""; #Mysql username
$password = ""; #Mysql Password
$dbname = ""; #Database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select all users with plain-text passwords
$sql = "SELECT id, password FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Hash the plain-text password
        $hashed_password = password_hash($row['password'], PASSWORD_DEFAULT);

        // Update the database with the hashed password
        $update_sql = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $hashed_password, $row['id']);
        $update_stmt->execute();
    }
    echo "Passwords updated to hashed versions!";
} else {
    echo "No users found in the database.";
}

$conn->close();
?>
