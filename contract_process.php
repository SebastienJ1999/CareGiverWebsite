<?php
include('Header.php');
include('db_connect.php'); // Include the database connection

// Start session
session_start();

// Get the form data for the member
$dailyHours = $_POST['dailyHours'];
$caregiverName = $_POST['caregiverName'];
$parentName = $_POST['parentName'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Insert the member data into the members table
$sql = "INSERT INTO contracts (start_date, end_date, daily_hours, caregiver_id, member_id) 
        VALUES ('$startDate', '$endDate', '$dailyHours', '$caregiverName', '$parentName')";


// Redirect to the login page after successful registration
header("Location: main_menu.php");
$conn->close();
exit();