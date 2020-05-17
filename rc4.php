<?php
/*
session_start();
$username = sanitizeMySQL($_SESSION['username']);  //this will be entered in the database once the user inputs text to decrypt or encrypt
$ip_address = sanitizeMySQL($_SESSION['ip']);
$rem_address = sanitizeMySQL($_SERVER['REMOTE_ADDR']);

if($ip_address != $rem_address){
 //   session_unset();    //clear session information
 //   header("Location: login.php");   //whatever login file name
}
*/
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Unable To Login"); //maybe change this to have a function instead
 
$cipherRC4 = <<<HTML
        <html><head><title>RC4</title></head>
        <body>
        <b>RC4</b>
        <br></br>
        <form method="post" action="rc4.php">
        <input type="submit" value="Log Out" name="btSignOut"></form>
        <form method="post" action="simplesubstitution.php">
        <input type="submit" value="Home" name="btHome"></form>
        <br>
        <form method="post" action="rc4.php" enctype="multipart/form=data">
        <label for="textIn">Text Input:</label>
        <input type="text" id="textIn" name="textIn">
        <input type="submit" value="Submit" name="btText">
        <br></br>
        
        <label for="fileIn">File Input:</label>
        <input type="file" id="fileIn" name="fileIn">
        <input type="submit" value="Submit" name="btFile">
        <br></br>
        
        <input type="text" id="key" name"key">
        </form>
        </body>
        </html>
        HTML;
echo $cipherRC4;


if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
function signOut(){
 //   session_unset();    //clear session information
 //   header("Location: login.php");   //whatever login file name

    echo "Logged Out";
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

?>
