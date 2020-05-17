<?php

function userInput() {
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
		require_once "login.php";
		$conn = new mysqli($hn, $un, $pw, $db);
		if($conn->connect_error) die("OOPS");
		
		$username = $conn->real_escape_string($_POST["name"]);
		$pass = $conn->real_escape_string($_POST["pass"]);
	}
}

function main() {
	userInput();
	echo "</body></html>";
}

main();
?>
