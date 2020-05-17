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
        <input type="submit" value="Submit" name="btText">
        <br></br>
        
        <label for="fileIn">File Input:</label>
        <input type="file" id="fileIn" name="fileIn">
        <input type="submit" value="Submit" name="btFile">
        <br></br>
        </form>
        </body>
        </html>
        HTML;
echo $cipherDubTranspo;


if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
if(isset($_POST["btText"])){
    if(isset($_POST["key1"]) && isset($_POST["key2"]) && isset($_POST["textIn"])){
        
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
?>