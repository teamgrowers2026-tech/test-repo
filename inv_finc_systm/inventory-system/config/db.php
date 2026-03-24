<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "inventorydb";
$conn = "";

try{
    $conn = mysqli_connect($host, $user, $pass, $dbname);
}
catch(mysqli_sql_exception){
    echo"Could not connect!";
}
?>
