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
        // Step 2: Get the contract details (caregiver username, member username, total cost)
        $sql_get_contract = "SELECT caregiver_username, member_username, total_cost FROM contracts WHERE id = ?";
        $stmt_get_contract = $conn->prepare($sql_get_contract);
        $stmt_get_contract->bind_param("i", $contract_id);
        $stmt_get_contract->execute();
        $result = $stmt_get_contract->get_result();

        if ($result->num_rows > 0) {
            $contract = $result->fetch_assoc();
            $caregiver_username = $contract['caregiver_username'];
            $member_username = $contract['member_username'];
            $totalCost = $contract['total_cost'];

            // Step 3: Update the member's care dollars balance (subtract cost)
            $sql_update_member = "UPDATE members SET care_dollars = care_dollars - ? WHERE username = ?";
            $stmt_update_member = $conn->prepare($sql_update_member);
            $stmt_update_member->bind_param("is", $totalCost, $member_username);
            $stmt_update_member->execute();
            $stmt_update_member->close();

            // Step 4: Update the caregiver's care dollars balance (add cost)
            $sql_update_caregiver = "UPDATE members SET care_dollars = care_dollars + ? WHERE username = ?";
            $stmt_update_caregiver = $conn->prepare($sql_update_caregiver);
            $stmt_update_caregiver->bind_param("is", $totalCost, $caregiver_username);
            $stmt_update_caregiver->execute();
            $stmt_update_caregiver->close();

            // Step 5: Calculate the new average rating for the caregiver
            $sql_avg_rating = "SELECT AVG(rating) as avg_rating FROM contracts WHERE caregiver_username = ? AND rating IS NOT NULL";
            $stmt_avg_rating = $conn->prepare($sql_avg_rating);
            $stmt_avg_rating->bind_param("s", $caregiver_username);
            $stmt_avg_rating->execute();
            $avg_result = $stmt_avg_rating->get_result();

            if ($avg_result->num_rows > 0) {
                $avg_data = $avg_result->fetch_assoc();
                $new_avg_rating = round($avg_data['avg_rating'], 2);

                // Step 6: Update the caregiver's average rating in the members table
                $sql_update_caregiver_rating = "UPDATE members SET avg_rating = ? WHERE username = ?";
                $stmt_update_caregiver_rating = $conn->prepare($sql_update_caregiver_rating);
                $stmt_update_caregiver_rating->bind_param("ds", $new_avg_rating, $caregiver_username);
                $stmt_update_caregiver_rating->execute();
                $stmt_update_caregiver_rating->close();
            }

            $stmt_avg_rating->close();

            // Redirect to the active contracts page after successful update
            header("Location: view_active_contracts.php");
            exit();
        }

        $stmt_get_contract->close();
    } else {
        echo "Error updating contract status or adding rating: " . $stmt_update_contract->error;
    }

    $stmt_update_contract->close();
}

$conn->close();
?>
