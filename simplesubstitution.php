<?php
require_once 'Decryptoid.php';

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
        <input type="submit" value="Encryption" name="btText">
        <input type="submit" value="Decryption" name="btTextDec">
        <br></br>
        
        <label for="fileIn">File Input:</label>
        <input type="file" id="fileIn" name="fileIn">
        <input type="submit" value="Encryption" name="btFile">
        <input type="submit" value="Decryption" name="btFileDec">
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
if(isset($_POST["btText"]) || isset($_POST["btTextDec"])){
    if(!empty($_POST["key"]) && isset($_POST["textIn"])){
        $k1 = sanitizeMySQL($conn, $_POST["key"]);
        $t = sanitizeMySQL($conn, $_POST["textIn"]);
        echo "<b>Key 1</b>: " . $k1;
        echo "<br>";
        echo "<b>Original Text</b>: [" . $t . "]";
        echo "<br>";
        if(isset($_POST["btTextDec"])) {
				echo "<b>Decryption</b>: [" . simpleCipherDecrypt($t, $k1) . "]";
                                upload($conn, $t, "Simple Substitution", $k1, "", "Decryption");
			}
			else {
				echo "<b>Encryption</b>: [" . simpleCipherCrypt($t, $k1) . "]";
                                upload($conn, $t, "Simple Substitution", $k1, "", "Encryption");
			}
    }else{
        echo "No Keys or Text Present";
    }
}

if(isset($_POST["btFile"]) || isset($_POST["btFileDec"])){
    if(!empty($_POST["key"]) && $_FILES["fileIn"]["size"] > 0){               
		$t = fileIO($conn);
		$k1 = sanitizeMySQL($conn, $_POST["key"]);
		if($t != "") {
			echo "<b>Key 1</b>: " . $k1;
			echo "<br>";
			echo "<b>Original Text</b>: [" . $t . "]";
			echo "<br>";
			if(isset($_POST["btFileDec"])) {
				echo "<b>Decryption</b>: [" . simpleCipherDecrypt($t, $k1) . "]";
                                upload($conn, $t, "Simple Substitution", $k1, "", "Decryption");
			}
			else {
				echo "<b>Encryption</b>: [" . simpleCipherCrypt($t, $k1) . "]";
                                upload($conn, $t, "Simple Substitution", $k1, "", "Encryption");
			}
        }
    }
    else{
		echo "No Keys or Text Present";
	}
}
$conn->close();
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
