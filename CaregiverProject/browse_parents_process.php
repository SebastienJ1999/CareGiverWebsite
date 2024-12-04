<?php
include('db_connect.php'); // Include the database connection
include('Header.php'); // Include the header

// Query to select parents along with their child information using a JOIN
$sql = "
    SELECT 
        parents.id AS parent_id, 
        parents.name AS parent_name, 
        parents.age AS parent_age, 
        parents.health_needs, 
        members.username AS child_username,
        members.name AS child_name
    FROM 
        parents
    LEFT JOIN 
        members 
    ON 
        parents.member_username = members.username";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members List</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Members List</h1>
    <table>
        <thead>
            <tr>
                <th>Parent Name</th>
                <th>Parent ID</th>
                <th>Parent Age</th>
                <th>Health Needs</th>
                <th>Child Username</th>
                <th>Child Name</th>
            </tr>
        </thead>
        <tbody>
    <?php
    if ($result->num_rows > 0) {
        // Output data for each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars(isset($row["parent_name"]) ? $row["parent_name"] : '') . "</td>";
            echo "<td>" . htmlspecialchars(isset($row["parent_id"]) ? $row["parent_id"] : '') . "</td>";
            echo "<td>" . htmlspecialchars(isset($row["parent_age"]) ? $row["parent_age"] : '') . "</td>";
            echo "<td>" . htmlspecialchars(isset($row["health_needs"]) ? $row["health_needs"] : '') . "</td>";
            echo "<td>" . htmlspecialchars(isset($row["child_username"]) ? $row["child_username"] : '') . "</td>";
            echo "<td>" . htmlspecialchars(isset($row["child_name"]) ? $row["child_name"] : '') . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No members found.</td></tr>";
    }
    ?>
</tbody>

    </table>
</body>
</html>
<?php
// Close the connection
$conn->close();
?>
