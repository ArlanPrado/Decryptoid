<?php

function userInput() {
    require_once "login.php";
    session_start();
  
	echo <<<_END
		<html><head><title>Online Virus Checker</title></head><body>
		<h4>Admin Login</h4>
		<form method="post" enctype="multipart/form-data">
		Username:
		<input type="text" name="name">
		<br>	
		Password:
		<input type="text" name="pass">
		<br>
		<br>
		<input type="submit" name = "Submit" value="Submit"></form>
		<form method="post" action = "signup.php" enctype="multipart/form-data">
		<input type="submit" name = "SignUp" value="Sign Up">
		</form>
_END;

    if(isset($_POST["name"]) && isset($_POST["pass"]) && !empty($_POST["name"]) && !empty($_POST["pass"])) {
		$conn = new mysqli($hn, $un, $pw, $db);
		if($conn->connect_error) die("OOPS");
		
		$username = $conn->real_escape_string($_POST["name"]);
		$password = $conn->real_escape_string($_POST["pass"]);
		
		$query = "SELECT * FROM user_info WHERE name='$username'";
		$result = $conn->query($query);
		if(!$result) die("OOPS");
		$token = $result->fetch_row();
		if($token == null) die("Invalid username/password combination.");
		$salt1 = $token[3];
		$salt2 = $token[4];
		$temp = hash('ripemd128', "$salt1$password$salt2");
		if($temp == $token[2]) {
			$_SESSION["username"] = $username;
			$_SESSION["email"] = $token[1];
			$_SESSION["password"] = $password;
			$_SESSION["hashed"] = $temp;
			echo $_SESSION["username"] . "<br>" . $_SESSION["email"] . "<br>" . $_SESSION["password"] . "<br>" . $_SESSION["hashed"]; 
			//redirect here
		}
		$result->close();
		mysqli_close($conn);
	}
}

function main() {
	userInput();
	echo "</body></html>";
}

main();
?>
