<?php

//include header
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

// Assume current user is logged in and their username is stored in the session
$current_username = $_SESSION['username'] ?? 'fail'; // name here is just for testing

// query to get member details
$sql_user = "SELECT id, name, phone, care_dollars, address, available_time, avg_rating FROM members WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $current_username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Check if user exists
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $member_id = $user['id']; // MEM ID
} else {
    echo "User not found!";
    exit;
}
$stmt_user->close();

// Query for Member's Parent
$sql_parents = "SELECT name, age, health_needs FROM parents WHERE member_id = ?";
$stmt_parents = $conn->prepare($sql_parents);
$stmt_parents->bind_param("i", $member_id);
$stmt_parents->execute();
$result_parents = $stmt_parents->get_result();

$parents = [];
while ($row = $result_parents->fetch_assoc()) {
    $parents[] = $row;
}

$stmt_parents->close();
$conn->close();         	// WILL NEED TO ADD INFO ON IF PARENT IS CARED FOR!!!!!!!!!!
?>
	

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Information</h1>
        <div class="user-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Care Dollars:</strong> <?php echo htmlspecialchars($user['care_dollars']); ?></p>
            <p><strong>Remaining Weekly Hours:</strong> <?php echo htmlspecialchars($user['available_time']); ?></p>
            <p><strong>Review Rating:</strong> <?php echo htmlspecialchars($user['avg_rating']); ?> / 5</p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
        </div>

        <h2>Parents' Information</h2>
        <div class="parents-info">
            <?php if (!empty($parents)): ?>
                <ul>
                    <?php foreach ($parents as $parent): ?>
                        <li>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($parent['name']); ?></p>
                            <p><strong>Age:</strong> <?php echo htmlspecialchars($parent['age']); ?></p>
                            <p><strong>Health Needs:</strong> <?php echo htmlspecialchars($parent['health_needs']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No parents' information available.</p>  <!-- msg if user has no parent -->
            <?php endif; ?>
        </div>
    </div>
</body>
</html>