<?php
include('db_connect.php'); // Include the database connection

// Start session
session_start();

// Get the form data for the member
$username = $_POST['username'];
$name = $_POST['name'];
$password = $_POST['password'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$available_time = $_POST['available_time'];
$is_caregiver = isset($_POST['is_caregiver']) ? 1 : 0;

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the member data into the members table
$sql = "INSERT INTO members (username, name, password, address, phone, available_time, care_dollars, is_caregiver) 
        VALUES ('$username', '$name', '$hashed_password', '$address', '$phone', '$available_time', 2000, $is_caregiver)";

if ($conn->query($sql) === TRUE) {
    // Get the username of the newly inserted member
    $member_username = $username;

    // Get the form data for the parent (if provided)
    $parent_name = $_POST['parent_name'];
    $parent_age = $_POST['parent_age'];
    $parent_needs = $_POST['parent_needs'];

    // If parent name is provided, add parent information to the parents table
    if (!empty($parent_name)) {
        $sql_parent = "INSERT INTO parents (member_username, name, age, health_needs) 
                       VALUES ('$member_username', '$parent_name', '$parent_age', '$parent_needs')";
        
        if ($conn->query($sql_parent) !== TRUE) {
            echo "Error adding parent information: " . $conn->error;
        }
    }

    // Redirect to the login page after successful registration
    header("Location: login_screen.html");
    exit();

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the database connection
$conn->close();
?>
