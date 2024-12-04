<?php
include('db_connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contract_id = $_POST['contract_id'];
    $action = $_POST['action'];

    // Determine the status based on the action
    $status = ($action == 'accept') ? 'accepted' : 'declined';

    // Update the contract status
    $stmt = $conn->prepare("UPDATE contracts SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $contract_id);

    if ($stmt->execute()) {
        header("Location: pending_contracts.php"); // Redirect back to pending contracts
        exit();
    } else {
        echo "Error updating contract status: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
