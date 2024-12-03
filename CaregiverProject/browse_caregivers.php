<?php
// Include header
include('Header.php');
// Start session
session_start();

// Database connect
$servername = "127.0.0.1";
$username = "root"; 
$password = ""; 
$dbname = "caregiver_db";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query for all caregivers with available hours > than 0
$sql_caregivers = "
    SELECT name, phone, address, available_time, avg_rating, care_dollars
    FROM members
    WHERE is_caregiver = 1 AND available_time > 0
";
$result_caregivers = $conn->query($sql_caregivers);

$conn->close(); // close connection
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Caregivers</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Available Caregivers</h1>
        <?php if ($result_caregivers->num_rows > 0): ?>
            <div class="caregivers-list">
                <?php while ($caregiver = $result_caregivers->fetch_assoc()): ?>
                    <div class="caregiver-card">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($caregiver['name']); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($caregiver['phone']); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($caregiver['address']); ?></p>
                        <p><strong>Available Hours:</strong> <?php echo htmlspecialchars($caregiver['available_time']); ?></p>
                        <p><strong>Rating:</strong> <?php echo htmlspecialchars($caregiver['avg_rating']); ?> / 5</p>
                        <p><strong>Care Dollars:</strong> <?php echo htmlspecialchars($caregiver['care_dollars']); ?></p>
                        <br>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No available caregivers found.</p>
        <?php endif; ?>
    </div>
</body>
</html>