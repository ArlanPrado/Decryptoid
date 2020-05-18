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
        echo "<b>Encyption</b>: [" . dtEncrypt($t, $k1, $k2, $alphabet) . "]";
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
    $key1Arr = array();
    $keyInd = 1;
    for($j = 0; $j < strlen($alphabet); $j++){      //get the number for each letter in the key1
        for($i = 0; $i < strlen($key1); $i++){
            if($alphabet[$j] == $key1[$i]){
                $key1Arr[$keyInd] = $alphabet[$j];
                $keyInd++;
            }
        }
    }
    //echo var_dump($key1Arr);
   // echo "<br>";
    $key1wTextArr = array();
    //strlen($text)
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
    return $text1;
}
function tDecrypt($text, $key1, $alphabet){
    //$key1TwoDim = 
    
    /*
    $key1Arr = array();
    $keyInd = 1;
    for($j = 0; $j < strlen($alphabet); $j++){      //get the number for each letter in the key1
        for($i = 0; $i < strlen($key1); $i++){
            if($alphabet[$j] == $key1[$i]){
                $key1Arr[$keyInd] = $alphabet[$j];
                $keyInd++;
            }
        }
    }

    echo "<br>";
    echo var_dump($key1Arr);
    $textNum = strlen($text);
    $key1Num = strlen($key1);
    $rows = (int)($textNum / $key1Num);
    if($textNum % $key1Num > 0){
        $rows = $rows + 1;
    }
    $placeholderNum = ($rows * $key1Num) - $textNum;
    //echo $rows;
    $textArr = array();
    $j = 0;
    for($i = 0; $i < strlen($key1); $i++){          //organize the substrings into columns
        $textArr[$i+1] = substr($text, $j, $rows);
        $j += $rows;
        echo $textArr[$i+1];
    }
    echo "<br>";
    echo var_dump($textArr);
    //arrange the columns into the key's order
    $text1 = array();
    $keyChar2 = NULL;
    //key1 is TESTING
    for($i = 1; $i < strlen($key1)+1; $i++){        //order the columns based on alphabetical priority
        //$priori = array_search($key1Arr[$i], $key1); //get T's priority
       // $substring = $textArr[$priori];  //get the index at the same priority as T
        //$text1[$i] = $substring;//place the column into the array[$i]
        
    }
    */
}

function uploadDT($t, $key1, $key2){
    $result = $conn->query("SELECT * FROM user_ciphers");
        
    if(!$result) die("Cannot connect to database");
    $date = new DateTime();
    $username = $_SESSION["username"];
    $query = "INSERT INTO user_ciphers VALUES($username, $t, 'Double Transposition',$date->getTimestamp(), key1, key2)";    //username, text, cipher, timestamp->do not put?, key
    $result = $conn->query($query);
}
?>