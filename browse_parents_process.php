<?php
include('db_connect.php'); // Include the database connection
include('Header.php');
// Database credentials

// Query to select all rows from the 'members' table
$sql = "SELECT * FROM parents";
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
                <th>Name</th>
                <th>Parent ID</th>
                <th>Age</th>
                <th>Health Needs</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data for each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["health_needs"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No members found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Close the connection
$conn->close();