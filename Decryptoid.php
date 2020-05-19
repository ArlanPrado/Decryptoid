<?php
require_once 'login.php';
startUp();
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Unable To Login"); //maybe change this to have a function instead
//$stmt = $conn->prepare('INSERT INTO user_ciphers VALUES(?,?,?,?,?,?)');
//if(!$stmt) die("Database access failed");
//$stmt->bind_param('sssiss',$username, $text, $cipher, $encOrdec, $date, $key);

$cipherPage = <<<HTML
        <html><head><title>Cipher4U</title></head>
        <body>
        <form method="post" action="Decryptoid.php">
        <input type="submit" value="Log Out" name="btSignOut"></form>
        <br></br>
        <form method="post" action="Decryptoid.php">
        <label for="cipher">Cipher:</label>
        <select id="cipher" name="cipher">
            <option value="cipSimpSub">Simple Substitution</option>
            <option value="cipDubTrans">Double Transposition</option>
            <option value="cipRC4">RC4</option>
            <option value="cipDES">DES</option>
        </select>
        <input type="submit" value="Submit" name="btCipher">
        </form>
        </body>
        </html>
HTML;

echo $cipherPage;
if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btCipher"])){
    $_POST["btCipher"] = null;
    $cipher = $_POST["cipher"];
    cipherView($cipher);
}
function cipherView($cipher){
    if($cipher == "cipSimpSub"){
        header("Location: simplesubstitution.php");
    }else if($cipher == "cipDubTrans"){
        header("Location: doubletransposition.php");
    }else if($cipher == "cipRC4"){
        header("Location: rc4.php");
    }else{ //cipDES 
        header("Location: des.php");
    }
}

function startUp(){
    session_start();
    /*if((sanitizeMySQL($_SESSION['ip']) != sanitizeMySQL($_SERVER['REMOTE_ADDR'])) || !isset($_SESSION['username'])){
        signOut();
    }*/
    $username = $_SESSION["username"];  //this will be entered in the database once the user inputs text to decrypt or encrypt
	echo "Welcome $username.";
}    
function signOut(){
    $_SESSION = array();
    setcookie(session_name(), '', time() - 2592000, '/');
    session_destroy();
    header("Location: users.php");
}
function clean_strings($dirtyString){
    $cleanString = stripslashes($dirtyString);
    $cleanString = strip_tags($cleanString);
    $cleanString = htmlentities($cleanString);
    
    return $cleanString;
}
//returns the sanitized mysql string
function sanitizeMySQL($conn, $var){
    $var = $conn->real_escape_string($var);
    $var = clean_strings($var);
    return $var;
}

//function that takes a file, sanitizes it, and then returns the text 
function fileIO($conn) {
	$text = "";
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $text = sanitizeMySQL($conn, file_get_contents($_FILES["fileIn"]["tmp_name"]));
        }else{
            echo "This file is not allowed";
        }
    return $text;
}
//input text, cipher used, key used, $encryption or decryption
function upload($conn, $text, $cipher, $key, $encOrdec){
    //all parameters 
    $date = new DateTime();
    $username = $_SESSION["username"];
    //$date = $date->getTimestamp();
    
    $query = "INSERT INTO Decryptoid.user_ciphers VALUES('$username', '$text', '$cipher', '$encOrdec', '$key')";
    $result = $conn->query($query);
    if(!$result) die("<br></br>Database access failed");
    $result->close();
    
    //$stmt->execute();
}
?>
