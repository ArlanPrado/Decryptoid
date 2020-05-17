<?php
require_once "login.php";
$conn = new mysqli($hn, $un, $pw, $db);
if($conn->connect_error) die("OOPS");

//will create tables for you you just need to make the database
$query = "CREATE TABLE IF NOT EXISTS user_info(
          name VARCHAR(225),
          email VARCHAR(225),
          password CHAR(128),
          salt1 char(5),
          salt2 char(5)
)";

$result = $conn->query($query);
if (!$result) die("OOPS");
echo "tables created.";
mysqli_close($conn);
?>
