<?php
$servername = "localhost";
$username = "root";
$password = ""; // By default, WAMP doesn't set a password for root
$dbname = "caregiver_db"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
