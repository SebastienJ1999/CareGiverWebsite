<?php
include('Header.php');
include('db_connect.php'); // Include the database connection

// Start session
session_start();

// Process the form submission if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data for the contract
    $dailyHours = $_POST['dailyHours'];
    $caregiverUsername = $_POST['caregiverName']; // Caregiver username
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Calculate the number of total hours (assuming it's a daily rate across all days in range)
    $date1 = new DateTime($startDate);
    $date2 = new DateTime($endDate);
    $interval = $date1->diff($date2);
    $totalDays = $interval->days + 1; // Adding 1 to include both start and end dates

    // Calculate total care dollars (daily hours * number of days * rate of 30)
    $totalHours = $dailyHours * $totalDays;
    $totalCareDollars = $totalHours * 30;

    // Assume current user is the one logged in and sending the contract
    $memberUsername = $_SESSION['username'];

    // Insert the contract data into the contracts table with status "pending"
    $stmt = $conn->prepare("INSERT INTO contracts (start_date, end_date, daily_hours, caregiver_username, member_username, total_hours, total_cost, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssissii", $startDate, $endDate, $dailyHours, $caregiverUsername, $memberUsername, $totalHours, $totalCareDollars);

    if ($stmt->execute()) {
        // Redirect to the main menu after successful contract submission
        header("Location: main_menu.php");
        exit();
    } else {
        echo "Error inserting contract: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html>
<head>
    <title>Contract</title>
    <link rel="stylesheet" type="text/css" href="contract_screen.css">
</head>
<body>
    <form action="contract_process.php" method="post">
        <h1>Contract</h1>
        <h3>Start Date</h3>
        <input type="date" name="startDate" required>

        <h3>End Date</h3>
        <input type="date" name="endDate" required> <br><br>

        <input type="number" name="dailyHours" min="0" max="24" placeholder="Daily Working Hours" required><br><br>

        <input type="text" id="caregiverName" name="caregiverName" placeholder="Caregiver Username" required><br><br>

        <input type="submit" class="submit-button" value="Finish Contract">
    </form>
</body>
</html>

