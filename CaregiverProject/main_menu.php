<?php
	include('Header.php');
	include('db_connect.php'); // Include the database connection

	session_start();
	
	// check if validly logged in
	if (!isset($_SESSION['username'])) {
    header("Location: login_screen.html"); // Redirect to login page if not logged in
    exit();
}

	$sql = $conn->prepare("SELECT id, password FROM members WHERE username = ?");
	$sql->bind_param("s", $username);
	$sql->execute();
	$result = $sql->get_result();
	
	
   /*
	$curr_id = $_SESSION['id'];
	//SQL query to get needed data
	$sql = "SELECT * FROM members WHERE id = '$curr_id' ";      //needs where to select row
	$res = mysqli_query($conn, $sql); //save to variable
	if (mysqli_num_rows($res) > 0) { // returns num of rows
		while($row = mysqli_fetch_assoc($res)) {
			$name = $row['name'];
			$currency = $row[care_dollars'];
		}
	} else {
		echo "No user found with the provided ID.";
}
        */
	
	//mysqli_close($conn); // close connection
?>

<!-- Start of html-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">   <!-- -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Menu</title> <!-- change Document to name at top of website -->
    <link rel="stylesheet" href="main_menu.css"> 
</head>
<body>
    <!-- HEADER START!!!!!! May need to copy header to other webpages-->
    <header>
        <nav>
		<!--
            //ul class="menu"> <Adds header items w links to respective locations(change if needed)
                <li><a href="account.html" id="check-account">Check Your Account</a></li>
                <li><a href="caregiver.html">Browse for Caregivers</a></li>
                <li><a href="parents.html">Browse for Parents</a></li>
                <li><a href="contract.html">Fill Out a Contract</a></li>
                <li><a href="sign_out.php" id="sign-out">Sign Out</a></li>
            </ul>
			-->
        </nav>
    </header>
    <!-- HEADER END!!!!!!!!-->
    <main>
        <div class="welcome-message">   <!-- Body, -->
            <h1>Welcome to Caregiver.co!</h1>
           <!--  <p>Current Care Dollars: <strong>   </strong> </p> -->
		   <p> <img src="Pictures/Pic1.jpg" alt="Description of the image"> </p>
        </div>
    </main>
    
</body>
</html>
