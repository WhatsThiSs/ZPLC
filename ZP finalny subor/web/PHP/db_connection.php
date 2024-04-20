<?php 
$servername = "localhost";
$username = "lubo2025";
$password = "Yy0tK]b7u-";
$dbname = "databazaZP";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
