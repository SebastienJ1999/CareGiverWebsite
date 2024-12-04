<?php

// Include header
include('Header.php');

// Start session
session_start();

// Database connection
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
$current_username = $_SESSION['username'] ?? 'fail'; // Replace 'fail' with a real username for testing

// Query to get member details
$sql_user = "SELECT id, name, phone, care_dollars, address, available_time, avg_rating FROM members WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $current_username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Check if user exists
if ($result_user->num_rows > 0) {
    $user = $result_user->fetch_assoc();
    $member_id = $user['id'];
} else {
    echo "User not found!";
    exit;
}
$stmt_user->close();

// Query for Member's Parent
$sql_parents = "SELECT name, age, health_needs FROM parents WHERE member_username = ?";
$stmt_parents = $conn->prepare($sql_parents);
$stmt_parents->bind_param("i", $member_id);
$stmt_parents->execute();
$result_parents = $stmt_parents->get_result();

$parents = [];
while ($row = $result_parents->fetch_assoc()) {
    $parents[] = $row;
}

$stmt_parents->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User Information</title>
    <link rel="stylesheet" href="check_account.css">
</head>
<body>
    <div class="container">
        <h1>Edit User Information</h1>
        <form action="update_user_info.php" method="post">
            <div class="user-info">
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>

                <label for="phone"><strong>Phone:</strong></label><br>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"><br><br>

                <p><strong>Care Dollars:</strong> <?php echo htmlspecialchars($user['care_dollars']); ?></p>

                <!-- <label for="available_time"><strong>Remaining Weekly Hours:</strong></label><br>
                <input type="number" id="available_time" name="available_time" value="<?php echo htmlspecialchars($user['available_time']); ?>"><br><br> -->
                <p><strong>Remaining Weekly Hours:</strong> <?php echo htmlspecialchars($user['available_time']); ?></p>

                <p><strong>Review Rating:</strong> <?php echo htmlspecialchars($user['avg_rating']); ?> / 5</p>

                <label for="address"><strong>Address:</strong></label><br>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br><br>
            </div>

            <h2>Parents' Information</h2>
            <div class="parents-info">
                <?php if (!empty($parents)): ?>
                    <?php foreach ($parents as $parent): ?>
                        <fieldset>
                            <legend>Parent's Information</legend>
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($parent['name']); ?></p>

                            <label for="parent_age"><strong>Age:</strong></label><br>
                            <input type="number" id="parent_age" name="parent_age" value="<?php echo htmlspecialchars($parent['age']); ?>"><br><br>

                            <label for="parent_needs"><strong>Health Needs:</strong></label><br>
                            <textarea id="parent_needs" name="parent_needs"><?php echo htmlspecialchars($parent['health_needs']); ?></textarea><br><br>
                        </fieldset>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No parents' information available.</p>
                <?php endif; ?>
            </div>

            <input type="submit" value="Save Changes">
        </form>
    </div>
</body>
</html>
