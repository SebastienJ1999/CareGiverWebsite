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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Contracts</title>
    <link rel="stylesheet" type="text/css" href="pending_contracts.css">
    <style>
        /* Basic styling for the container */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .contracts-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .contract-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .contract-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .contract-card p {
            margin: 10px 0;
            color: #555;
        }

        .button-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .button-container button[name="action"][value="accept"] {
            background-color: #28a745; /* Green */
        }

        .button-container button[name="action"][value="accept"]:hover {
            background-color: #218838;
        }

        .button-container button[name="action"][value="decline"] {
            background-color: #dc3545; /* Red */
        }

        .button-container button[name="action"][value="decline"]:hover {
            background-color: #c82333;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Pending Contracts</h1>
        <div class="contracts-container">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Calculate total cost
                    $date1 = new DateTime($row['start_date']);
                    $date2 = new DateTime($row['end_date']);
                    $interval = $date1->diff($date2);
                    $totalDays = $interval->days + 1; // Including both start and end dates
                    $totalHours = $row['daily_hours'] * $totalDays;
                    $totalCost = $totalHours * 30;

                    echo "<div class='contract-card'>";
                    echo "<p><strong>Start Date:</strong> " . htmlspecialchars($row['start_date']) . "</p>";
                    echo "<p><strong>End Date:</strong> " . htmlspecialchars($row['end_date']) . "</p>";
                    echo "<p><strong>Daily Hours:</strong> " . htmlspecialchars($row['daily_hours']) . "</p>";
                    echo "<p><strong>Total Hours:</strong> " . htmlspecialchars($totalHours) . "</p>";
                    echo "<p><strong>Total Cost:</strong> " . htmlspecialchars($totalCost) . " Care Dollars</p>";
                    echo "<div class='button-container'>";
                    echo "<form action='contract_response.php' method='post'>";
                    echo "<input type='hidden' name='contract_id' value='" . $row['id'] . "'>";
                    echo "<button type='submit' name='action' value='accept'>Accept</button>";
                    echo "<button type='submit' name='action' value='decline'>Decline</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No pending contracts available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
