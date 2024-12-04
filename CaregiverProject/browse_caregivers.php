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

// Get the current user's username from the session
$current_username = $_SESSION['username'] ?? null;

// Make sure the user is logged in
if (!$current_username) {
    header("Location: login_screen.html");
    exit();
}

// Query to get the current user's city (assuming the address field contains the city as part of the string)
$sql_user = "SELECT address FROM members WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $current_username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $current_user_city = $user['address']; // Assuming the address includes city information
} else {
    echo "User not found.";
    exit();
}

$stmt_user->close();

// Query for caregivers in the same city as the current user and with available hours > 0
$sql_caregivers = "
    SELECT name, phone, address, available_time, avg_rating, care_dollars
    FROM members
    WHERE is_caregiver = 1 AND available_time > 0 AND address = ?
";
$stmt_caregivers = $conn->prepare($sql_caregivers);
$stmt_caregivers->bind_param("s", $current_user_city);
$stmt_caregivers->execute();
$result_caregivers = $stmt_caregivers->get_result();

$conn->close(); // close connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Caregivers</title>
    <link rel="stylesheet" href="browse_caregivers.css">
</head>
<body>
    <div class="container">
        <h1>Available Caregivers in Your City</h1>
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
            <p>No available caregivers found in your city.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$stmt_caregivers->close();
?>
