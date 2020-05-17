<?php
require_once 'Decryptoid.php';
//startUp();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("Unable To Login"); //maybe change this to have a function instead
 
$cipherDES = <<<HTML
        <html><head><title>DES</title></head>
        <body>
        <b>DES</b>
        <br></br>
        <form method="post" action="des.php" enctype="multipart/form-data">
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
echo $cipherDES;


if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
if(isset($_POST["btText"])){
    if(isset($_POST["textIn"])){
        
    }else{
        echo "No Text Present";
    }
}
if(isset($_POST["btFile"])){
    if($_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $text = sanitizeMySQL(file_get_contents($conn, $_FILES["fileIn"]["tmp_name"]));
            echo $text;
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No File Present";
    }
}
?>
