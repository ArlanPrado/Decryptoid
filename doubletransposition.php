<?php
require_once 'Decryptoid.php';
//startUp();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Unable To Login"); //maybe change this to have a function instead

$cipherDubTranspo = <<<HTML
        
        <html><head><title>Double Transposition</title></head>
        <body>
        <b>Double Transposition</b>
         <br></br>
        <form method="post" action="doubletransposition.php" enctype="multipart/form-data">
        
        <label for="key1">Key 1:</label>
        <input type="text" id="key1" name="key1"><br></br>
        <label for="key2">Key 2:</label>
        <input type="text" id="key2" name="key2"><br></br>
        
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
echo $cipherDubTranspo;

$alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";


if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
if(isset($_POST["btText"])){
    if(isset($_POST["key1"]) && isset($_POST["key2"]) && isset($_POST["textIn"])){
        $k1 = strtoupper(sanitizeMySQL($conn, $_POST["key1"]));
        $k2 = strtoupper(sanitizeMySQL($conn, $_POST["key2"]));
        $t = sanitizeMySQL($conn, $_POST["textIn"]);
        echo "<b>Key 1</b>: " . $k1 . "<b> Key 2</b>: " . $k2;
        echo "<br>";
        echo "<b>Original Text</b>: [" . $t . "]";
        echo "<br>";
        echo "<b>Encryption</b>: [" . dtEncrypt($t, $k1, $k2, $alphabet) . "]";
    }else{
        echo "No Keys or Text Present";
    }
}

if(isset($_POST["btTextDec"])){
    if(isset($_POST["key1"]) && isset($_POST["key2"]) && isset($_POST["textIn"])){
        $k1 = strtoupper(sanitizeMySQL($conn, $_POST["key1"]));
        $k2 = strtoupper(sanitizeMySQL($conn, $_POST["key2"]));
        $t = sanitizeMySQL($conn, $_POST["textIn"]);
        echo "<b>Key 1</b>: " . $k1 . "<b> Key 2</b>: " . $k2;
        echo "<br>";
        echo "<b>Original Text</b>: [" . $t . "]";
        echo "<br>";
        echo "<b>Decryption</b>: [" . dtDecrypt($t, $k1, $k2, $alphabet) . "]";
    }else{
        echo "No Keys or Text Present";
    }
}

if(isset($_POST["btFile"])){
    if(isset($_POST["key1"]) && isset($_POST["key2"])  && $_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $text = sanitizeMySQL($conn, file_get_contents($conn, $_FILES["fileIn"]["tmp_name"]));
            echo $text;
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No Keys or File Present";
    }
}

if(isset($_POST["btFileDec"])){
    if(isset($_POST["key1"]) && isset($_POST["key2"])  && $_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $text = sanitizeMySQL($conn, file_get_contents($conn, $_FILES["fileIn"]["tmp_name"]));
            echo $text;
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No Keys or File Present";
    }
}

function dTEncrypt($text, $key1, $key2, $alphabet){
    $text1 = tEncrypt($text, $key1, $alphabet);
    $text2 = tEncrypt($text1, $key2, $alphabet);
    return $text2;
}
function dTDecrypt($text, $key1, $key2, $alphabet){
    $text1 = tDecrypt($text, $key2, $alphabet);
    $text2 = tDecrypt($text1, $key1, $alphabet);
    return $text2;
}
function tEncrypt($text, $key1, $alphabet){
    $key1Arr = orderKey($alphabet, $key1);
    $key1wTextArr = array();
    $keyInd = 1;
    $text = str_replace(" ", "", $text);
    for($i = 0; $i < strlen($text); $i++){      //arrange the text letters in columns
        if($keyInd > strlen($key1)){
            $keyInd = 1;
        }
        if(!isset($key1wTextArr[$keyInd])){
            $key1wTextArr[$keyInd] = $text[$i];
        }
        else{
            $key1wTextArr[$keyInd] = $key1wTextArr[$keyInd] . $text[$i];
        }
        $keyInd++;
    }
    $text1 = "";
    $keyChar2 = NULL;
    for($i = 1; $i < strlen($key1)+1; $i++){        //order the columns based on alphabetical priority
        $keyChar = $key1Arr[$i];
        $strPos = stripos($key1, $keyChar) + 1;
        if($keyChar2 == $keyChar){              //if the last 
            $strPos = stripos($key1, $keyChar, $strPos) + 1;
        }
        $text1 = $text1 . $key1wTextArr[$strPos];
        $keyChar2 = $keyChar;        
    }
    $split = floor(strlen($text) / strlen($key1)); //determines how many characters should be grouped
    $text1 = wordwrap($text1, $split, " ", true); //put a space every group of indexes that is determined by split
    return $text1;
}
function tDecrypt($text, $key1, $alphabet){
    $key1Arr = orderKey($alphabet, $key1); 
    $finalDecrypted = ""; 
    $finalTable = array();
    $text = str_replace(" ", "", $text);
    $mincolumns = floor(strlen($text) / strlen($key1)); //is the minimum amount of columns in the table
    $remainder = strlen($text) % strlen($key1); //how many rows are in the table
    $temp_key = $key1; //will be used to handle duplicates
    
    for($i=1; $i<=sizeof($key1Arr); $i++) {
		$temp = strpos($temp_key, $key1Arr[$i]) + 1;
		$temp_key[$temp-1] = "!"; //replace current letter so duplicates can be handled
		if($temp <= $remainder) {
			$finalTable[$temp] = substr($text, 0, $mincolumns+1); //arrange the cipher
			$text = substr($text, $mincolumns+1); 
		}
		else {
			$finalTable[$temp] = substr($text, 0, $mincolumns); //arrange the cipher 
			$text = substr($text, $mincolumns); 
		}
	}
	
	for($i=0; $i<strlen($finalTable[1]); $i++) { //goes down the letters of the column
		for($j=1; $j<=strlen($key1); $j++) { //goes across the letters in the rows
			if($i >= strlen($finalTable[$j])) { //handles the different sizes of columns
				continue; //skip over to avoid errors
			}
			$finalDecrypted .= $finalTable[$j][$i];
		}
	}
	return $finalDecrypted;
}

function orderKey($alphabet, $key1) {
	$key1Arr = array();
	$keyInd = 1;
	for($j = 0; $j < strlen($alphabet); $j++){      //get the number for each letter in the key
        for($i = 0; $i < strlen($key1); $i++){
            if($alphabet[$j] == $key1[$i]){
                $key1Arr[$keyInd] = $alphabet[$j];
                $keyInd++;
            }
        }
    }
    return $key1Arr;
}

?>
