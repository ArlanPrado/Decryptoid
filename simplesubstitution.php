<?php
require_once 'Decryptoid.php';
//startUp();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Unable To Login"); //maybe change this to have a function instead

$cipherSimSub = <<<HTML
        <html><head><title>Simple Substitution</title></head>
        <body>
        <b>Simple Substitution</b>
        <br></br>
        
        <form method="post" action="simplesubstitution.php" enctype="multipart/form-data">
        
        <label for="key">Key:</label>
        <input type="text" id="key" name="key"><br></br>
        
        <label for="textIn">Text Input:</label>
        <input type="text" id="textIn" name="textIn">
        <input type="submit" value="Submit" name="btText">
        <br></br>
        
        <label for="fileIn">File Input:</label>
        <input type="file" id="fileIn" name="fileIn">
        <input type="submit" value="Upload" name="btFile">
        <br></br>
             
        </form>
        </body>
        </html>
HTML;
echo $cipherSimSub;
if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
if(isset($_POST["btText"])){
    if(isset($_POST["key"]) && isset($_POST["textIn"])){
		$text = $conn->real_escape_string($_POST["textIn"]);
		$key = $conn->real_escape_string($_POST["key"]);
        echo simpleCipherCrypt($text, $key) . "<br>";
        echo simpleCipherDecrypt($text, $key);
    }else{
        echo "No Key or Text Present";
    }
}
if(isset($_POST["btFile"])){
    if(isset($_POST["key"]) && $_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $text = sanitizeMySQL($conn, file_get_contents($_FILES["fileIn"]["tmp_name"]));
            echo $text;
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No Key or File Present";
    }
}

function simpleCipherCrypt($text, $key) {
	$alphabet_ref = "abcdefghijklmnopqrstuvwxyz";
	$encrypted = "";
	$text = strtolower($text);
	for($i=0; $i<strlen($text); $i++) {
		if(strpos($alphabet_ref, $text[$i]) === FALSE) {
			$encrypted .= $text[$i];
			continue;
		}
		$placeholder = strpos($alphabet_ref, $text[$i]);
		$encrypted .= $key[$placeholder];
	}
	return $encrypted;
}

function simpleCipherDecrypt($text, $key) {
	$alphabet_ref = "abcdefghijklmnopqrstuvwxyz";
	$decrypted = "";
	$text = strtolower($text);
	for($i=0; $i<strlen($text); $i++) {
		if(strpos($alphabet_ref, $text[$i]) === FALSE) {
			$decrypted .= $text[$i];
			continue;
		}
		$placeholder = strpos($key, $text[$i]);
		$decrypted .= $alphabet_ref[$placeholder];
	}
	return $decrypted;
}

?>
