<?php
require_once 'Decryptoid.php';
 
$cipherDES = <<<HTML
        <html><head><title>DES</title></head>
        <body>
        <b>DES</b>
        <br></br>
        <form method="post" action="des.php" enctype="multipart/form-data">
        
        <label for="key">Key:</label>
        <input type="text" id="key" name="key"><br></br>
        
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
    if(isset($_POST["key"]) && isset($_POST["textIn"])){
        $k1 = sanitizeMySQL($conn, $_POST["key"]);
        $t = sanitizeMySQL($conn, $_POST["textIn"]);
        echo "<b>Key</b>: " . $k1 . " ";
        echo "<br>";
        echo "<b>Original Text</b>: [" . $t . "]";
        echo "<br>";
        echo "<b>Encryption/Decryption</b>: [" . des($t, $k1) . "]";
    }else{
        echo "No Key or Text Present";
    }
}
if(isset($_POST["btFile"])){
    if(isset($_POST["key"]) && $_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $k1 = sanitizeMySQL($conn, $_POST["key"]);
            $text = sanitizeMySQL($conn, file_get_contents($_FILES["fileIn"]["tmp_name"]));
            echo "<b>Key</b>: " . $k1 . " ";
            echo "<br>";
            echo "<b>Original Text</b>: [" . $text . "]";
            echo "<br>";
            echo "<b>Encryption/Decryption</b>: [" . des($text, $k1) . "]";
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No Key or File Present";
    }
}

$conn->close();

function des($text, $key){
    $bits = strlen($text) * 8;
    $blocks = ceil($bits / 64);
    
    //get 8 characters and separate into array
    $blocked = array();
    for($i = 0; $i < $blocks; $i++){
        $blocked[$i] = substr($text, $i * 8, 8);
        if((($i * 8) + 8) > strlen($text)) {
			$blocked[$i] = substr($text, $i * 8);
			$buffer = 8 - strlen($blocked[$i]);
			for($j=0; $j<$buffer; $j++) {
				$blocked[$i] .= "0";
			}
		}
    }
    
    echo var_dump($blocked);
    
    //foreach
    foreach($blocked as $block){
        //initial permutation ~ scramble the block
        //
    }
}
?>
