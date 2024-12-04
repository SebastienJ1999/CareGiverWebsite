<?php
include('db_connect.php');
include('Header.php');

// Start session
session_start();

// Ensure member is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_screen.html");
    exit();
}

$current_username = $_SESSION['username'];

// Fetch active contracts where the current user is the requester and the contract is still active
$sql = "
    SELECT * FROM contracts 
    WHERE member_username = ? 
    AND status = 'accepted'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Active Contracts</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Active Contracts</h1>
    <div class="contracts-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='contract-card'>";
                echo "<p>Start Date: " . htmlspecialchars($row['start_date']) . "</p>";
                echo "<p>End Date: " . htmlspecialchars($row['end_date']) . "</p>";
                echo "<p>Daily Hours: " . htmlspecialchars($row['daily_hours']) . "</p>";
                
                // Complete Contract and Review Form
                echo "<form action='complete_contract.php' method='post'>";
                echo "<input type='hidden' name='contract_id' value='" . $row['id'] . "'>";
                echo "<label for='rating'>Rating (1-5):</label>";
                echo "<select name='rating' required>
                        <option value=''>Select</option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                      </select><br><br>";
                echo "<button type='submit' name='action' value='complete'>Mark as Completed</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No active contracts available.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
