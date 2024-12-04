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
        if ($action == 'accept') {
            // Fetch the contract information to get daily hours and caregiver username
            $contract_query = $conn->prepare("SELECT daily_hours, caregiver_username, start_date, end_date FROM contracts WHERE id = ?");
            $contract_query->bind_param("i", $contract_id);
            $contract_query->execute();
            $contract_result = $contract_query->get_result();

            if ($contract_result->num_rows > 0) {
                $contract = $contract_result->fetch_assoc();
                $daily_hours = $contract['daily_hours'];
                $caregiver_username = $contract['caregiver_username'];
                $start_date = new DateTime($contract['start_date']);
                $end_date = new DateTime($contract['end_date']);

                // Calculate the number of days between start and end dates
                $interval = $start_date->diff($end_date);
                $number_of_days = $interval->days + 1; // Including start and end date

                // Calculate total contract hours
                $total_contract_hours = $daily_hours * $number_of_days;

                // Update caregiver's available time
                $update_hours_query = $conn->prepare("UPDATE members SET available_time = available_time - ? WHERE username = ?");
                $update_hours_query->bind_param("is", $total_contract_hours, $caregiver_username);

                if ($update_hours_query->execute()) {
                    // Successfully updated available hours
                    header("Location: pending_contracts.php"); // Redirect back to pending contracts
                    exit();
                } else {
                    echo "Error updating caregiver's available time: " . $update_hours_query->error;
                }

                $update_hours_query->close();
            }

            $contract_query->close();
        }

        header("Location: pending_contracts.php"); // Redirect back to pending contracts
        exit();
    } else {
        echo "Error updating contract status: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
