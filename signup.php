<?php

function inputField() {
echo <<<_END
    <html><head><title>Online Virus Checker</title></head><body>
    <h4>Admin Login</h4>
    <form method="post" enctype="multipart/form-data">
    Email:
    <input type="text" name="email">
    <br>
    Username:
    <input type="text" name="name">
    <br>
    Password:
    <input type="text" name="pass">
    <br>
    Confirm Password:
    <input type="text" name="confirm">
    <br>
    <br>
    <input type="submit" name = "submit" value="Submit"></form>
    <form method="post" action="users.php">
    <input type="submit" name = "back" value="Back"></form>
_END;

	require_once "login.php";
	$conn = new mysqli($hn, $un, $pw, $db);
	if($conn->connect_error) die("OOPS");
	
    if(!empty($_POST["email"]) && !empty($_POST["name"]) && !empty($_POST["pass"]) && !empty($_POST["confirm"])) {    
		//fetch the user input
		$username = $conn->real_escape_string($_POST["name"]);
		$email = $conn->real_escape_string($_POST["email"]);
		$password = $conn->real_escape_string($_POST["pass"]);
		$confirm = $conn->real_escape_string($_POST["confirm"]);
		
		//all the validations
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) die("Invalid Email"); //built in function that validates emails
		$query = "SELECT * FROM user_info WHERE email = '$email'"; //use the database to find if the email is already taken
		$result = $conn->query($query);
		if(!$result) die("OOPS");
		if($result->num_rows > 0) die("Email is already taken."); 
		if($password != $confirm) die("Passwords do not match."); //check if passwords match
		$query = "SELECT * FROM user_info WHERE name = '$username'"; //use the database to find if the username is already taken
		$result = $conn->query($query);
		if(!$result) die("OOPS");
		if($result->num_rows > 0) die("Username is already taken."); //if there is at least one result that means the username is taken
		
		//salt and hash the password to be stored in the database
		$salt1 = randomString(5);
		$salt2 = randomString(5);
		$new_pass = hash('ripemd128', "$salt1$password$salt2");
		
		//prepare to insert values into the database
		$stmt = $conn->prepare('INSERT INTO user_info VALUES (?, ?, ?, ?, ?)');
		$stmt->bind_param('sssss', $username, $email, $new_pass, $salt1, $salt2);
		$stmt->execute();
		printf("%d Row inserted.\n", $stmt->affected_rows);
		$stmt->close();
		$result->close();
		echo "Successfully signed up.";
    }
    mysqli_close($conn); //close the connection if nothing is accomplished
}

function randomString($length) { //function that creates random string for salts
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!?"; //all the characters that can be used for the string
	$random = "";
	for($i=0; $i<$length; $i++) { //pick random indexes in characters to append to the random string
		$random .= $characters[rand(0, strlen($characters)-1)];
	}
	return $random;
}

inputField();
echo "</body></html>";
?>
