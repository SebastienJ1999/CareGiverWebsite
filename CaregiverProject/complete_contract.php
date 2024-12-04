<?php
include('db_connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['contract_id']) && isset($_POST['rating'])) {
    $contract_id = $_POST['contract_id'];
    $rating = $_POST['rating'];

    // Step 1: Update the contract to mark as 'completed' and add the rating
    $sql_update_contract = "UPDATE contracts SET status = 'completed', rating = ? WHERE id = ?";
    $stmt_update_contract = $conn->prepare($sql_update_contract);
    $stmt_update_contract->bind_param("ii", $rating, $contract_id);

    if ($stmt_update_contract->execute()) {
        // Step 2: Get the caregiver username from the contract
        $sql_get_caregiver = "SELECT caregiver_username FROM contracts WHERE id = ?";
        $stmt_get_caregiver = $conn->prepare($sql_get_caregiver);
        $stmt_get_caregiver->bind_param("i", $contract_id);
        $stmt_get_caregiver->execute();
        $result = $stmt_get_caregiver->get_result();

        if ($result->num_rows > 0) {
            $contract = $result->fetch_assoc();
            $caregiver_username = $contract['caregiver_username'];

            // Step 3: Calculate the new average rating for the caregiver
            $sql_avg_rating = "SELECT AVG(rating) as avg_rating FROM contracts WHERE caregiver_username = ? AND rating IS NOT NULL";
            $stmt_avg_rating = $conn->prepare($sql_avg_rating);
            $stmt_avg_rating->bind_param("s", $caregiver_username);
            $stmt_avg_rating->execute();
            $avg_result = $stmt_avg_rating->get_result();

            if ($avg_result->num_rows > 0) {
                $avg_data = $avg_result->fetch_assoc();
                $new_avg_rating = round($avg_data['avg_rating'], 2);

                // Step 4: Update the caregiver's average rating in the members table
                $sql_update_member = "UPDATE members SET avg_rating = ? WHERE username = ?";
                $stmt_update_member = $conn->prepare($sql_update_member);
                $stmt_update_member->bind_param("ds", $new_avg_rating, $caregiver_username);

                if ($stmt_update_member->execute()) {
                    // Successfully updated average rating
                } else {
                    echo "Error updating caregiver average rating: " . $stmt_update_member->error;
                }

                $stmt_update_member->close();
            } else {
                echo "Error calculating average rating: No valid ratings found.";
            }

            $stmt_avg_rating->close();
        } else {
            echo "Error: Caregiver not found for the contract.";
        }

        $stmt_get_caregiver->close();
    } else {
        echo "Error updating contract status or adding rating: " . $stmt_update_contract->error;
    }

    $stmt_update_contract->close();
} else {
    echo "Invalid request. Please ensure all required fields are filled.";
}

$conn->close();

// Redirect after processing all steps to avoid premature redirection
header("Location: view_active_contracts.php");
exit();
?>
