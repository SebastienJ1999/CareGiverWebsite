<?php
include('db_connect.php');

// Start session
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_screen.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the updated values from the form
$phone = $_POST['phone'];
// $available_time = $_POST['available_time'];
$address = $_POST['address'];
$parent_age = $_POST['parent_age'];
$parent_needs = $_POST['parent_needs'];

// Update the members table
$stmt = $conn->prepare("UPDATE members SET phone = ?, available_time = ?, address = ? WHERE id = ?");
$stmt->bind_param("sisi", $phone, $available_time, $address, $user_id);

if (!$stmt->execute()) {
    echo "Error updating user information: " . $stmt->error;
}

$stmt->close();

// Update the parents table if there is a parent record
$stmt_parent = $conn->prepare("UPDATE parents SET age = ?, health_needs = ? WHERE member_username = ?");
$stmt_parent->bind_param("isi", $parent_age, $parent_needs, $user_id);

if (!$stmt_parent->execute()) {
    echo "Error updating parent information: " . $stmt_parent->error;
}

$stmt_parent->close();

$conn->close();

// Redirect back to the user info page
header("Location: check_account.php");
exit();
?>
