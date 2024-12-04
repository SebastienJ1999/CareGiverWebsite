<?php
include('Header.php');
include('db_connect.php');

// Start session
session_start();

// Ensure caregiver is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_screen.html");
    exit();
}

$caregiver_username = $_SESSION['username'];

// Query to get all pending contracts for this caregiver
$sql = "SELECT * FROM contracts WHERE caregiver_username = ? AND status = 'pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $caregiver_username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Contracts</title>
    <link rel="stylesheet" type="text/css" href="pending_contracts.css">
</head>
<body>
    <h1>Pending Contracts</h1>
    <div class="contracts-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Calculate the total hours for the contract
                $start_date = new DateTime($row['start_date']);
                $end_date = new DateTime($row['end_date']);
                $interval = $start_date->diff($end_date);
                $number_of_days = $interval->days + 1; // Including start and end date
                $total_hours = $number_of_days * $row['daily_hours'];

                // Display contract information
                echo "<div class='contract-card'>";
                echo "<p>Start Date: " . htmlspecialchars($row['start_date']) . "</p>";
                echo "<p>End Date: " . htmlspecialchars($row['end_date']) . "</p>";
                echo "<p>Daily Hours: " . htmlspecialchars($row['daily_hours']) . "</p>";
                echo "<p>Total Hours for Contract: " . htmlspecialchars($total_hours) . "</p>";
                echo "<form action='contract_response.php' method='post'>";
                echo "<input type='hidden' name='contract_id' value='" . $row['id'] . "'>";
                echo "<button type='submit' name='action' value='accept'>Accept</button>";
                echo "<button type='submit' name='action' value='decline'>Decline</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No pending contracts available.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
