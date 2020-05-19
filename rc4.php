<?php
require_once 'Decryptoid.php';

$cipherRC4 = <<<HTML
        <html><head><title>RC4</title></head>
        <body>
        <b>RC4</b>
        <br></br>
        <form method="post" action="rc4.php" enctype="multipart/form-data">
        
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
echo $cipherRC4;


if(isset($_POST["btSignOut"])){
    signOut();
}
if(isset($_POST["btHome"])){
    header("Location:Decryptoid.php");
}
if(isset($_POST["btText"]) || isset($_POST["btTextDec"])){
    if(isset($_POST["key"]) && isset($_POST["textIn"])){
        $k1 = sanitizeMySQL($conn, $_POST["key"]);
        $t = sanitizeMySQL($conn, $_POST["textIn"]);
        echo "<b>Key</b>: " . $k1 . " ";
        echo "<br>";
        echo "<b>Original Text</b>: [" . $t . "]";
        echo "<br>";
        if(isset($_POST["btTextDec"])) {
            echo "<b>Decryption</b>: [" . rc4Decrypt($t, $k1) . "]";
            upload($conn, $t, "RC4", $k1, "", "Decryption");
        }else{
            echo "<b>Encryption</b>: [" . rc4($t, $k1) . "]";
            upload($conn, $t, "RC4", $k1, "", "Encryption");
        }
        
    }else{
        echo "No Key or Text Present";
    }
}
if(isset($_POST["btFile"]) || isset($_POST["btFileDec"]) ){
    if(isset($_POST["key"]) && $_FILES["fileIn"]["size"] > 0){               
	if($_FILES["fileIn"]["type"] == "text/plain") {
            $k1 = sanitizeMySQL($conn, $_POST["key"]);
            $text = sanitizeMySQL($conn, file_get_contents($_FILES["fileIn"]["tmp_name"]));
            echo "<b>Key</b>: " . $k1 . " ";
            echo "<br>";
            echo "<b>Original Text</b>: [" . $text . "]";
            echo "<br>";
            if(isset($_POST["btFileDec"])) {
                echo "<b>Decryption</b>: [" . rc4Decrypt($text, $k1) . "]";
                upload($conn, $text, "RC4", $k1, "", "Decryption");
            }else{
                echo "<b>Encryption</b>: [" . rc4($text, $k1) . "]";
                upload($conn, $text, "RC4", $k1, "", "Encryption");
            }
        }else{
            echo "This file is not allowed";
        }
    }else{
        echo "No Key or File Present";
    }
}
$conn->close();
function rc4($text, $key){
    $rc4 = array(); //initialize 256 byte array

    for($i = 0; $i < 256; $i++){
        $rc4[$i] = $i;
    }

    //KSA
    $j = 0;
    for($i = 0; $i < 256; $i++){
        $j = ($j + $rc4[$i] + ord($key[$i % strlen($key)])) % 256;
        $rc4 = swap($rc4, $i, $j);
    }
    
    //PRGA
    $i = $j = 0;

    $output = "";
    for($k = 0; $k < strlen($text); $k++){
        $i = ($i + 1) % 256;
        $j =  ($j + $rc4[$i]) % 256;
        $rc4 = swap($rc4, $i, $j);
        $output .= (ord($text[$k]) ^ ($rc4[($rc4[$i] + $rc4[$j]) % 256])) . " ";
    }
    return $output;
}

function rc4Decrypt($ascii, $key){

    $textArr = explode(' ', $ascii, strlen($ascii));
    $text = "";
    foreach($textArr as $char){
        $text .= chr($char);
    }

    $rc4 = array(); //initialize 256 byte array

    for($i = 0; $i < 256; $i++){
        $rc4[$i] = $i;
    }

    //KSA
    $j = 0;
    for($i = 0; $i < 256; $i++){
        $j = ($j + $rc4[$i] + ord($key[$i % strlen($key)])) % 256;
        $rc4 = swap($rc4, $i, $j);
    }
    
    //PRGA
    $i = $j = 0;

    $output = "";
    for($k = 0; $k < count($textArr); $k++){
        $i = ($i + 1) % 256;
        $j =  ($j + $rc4[$i]) % 256;
        $rc4 = swap($rc4, $i, $j);
        $output .= chr(ord($text[$k]) ^ ($rc4[($rc4[$i] + $rc4[$j]) % 256])) . " ";
    }
    //substr
    return $output;
}
function swap($rc4, $i, $j){
    $temp = $rc4[$i];
    $rc4[$i] = $rc4[$j];
    $rc4[$j] = $temp;
    return $rc4;    
}
?>
