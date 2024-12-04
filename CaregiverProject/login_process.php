<?php
include('db_connect.php'); // Include the database connection

// Start session
session_start();

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare SQL statement to prevent SQL injection
$sql = $conn->prepare("SELECT id, password FROM members WHERE username = ?");
$sql->bind_param("s", $username);
$sql->execute();
$result = $sql->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Verify the hashed password
    if (password_verify($password, $row['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $username;

        // Redirect to main menu
        header("Location: main_menu.php");
        exit();
    } else {
        echo "Invalid password. Please try again.";
    }
} else {
    echo "No user found with that username. Please register.";
}

// Close the database connection
$conn->close();
?>
