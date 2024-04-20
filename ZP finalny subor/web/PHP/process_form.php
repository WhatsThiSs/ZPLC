<?php
// Pripojenie k databáze
$servername = "mysql80.r6.websupport.sk";
$username = "lubo2025";
$password = "Xc6<N%U&d?";
$dbname = "databazaZP";

error_reporting(E_ALL);
ini_set('display_errors', '1');


$conn = new mysqli($servername, $username, $password, $dbname);

$conn->set_charset("utf8mb4");

// Kontrola pripojenia
if ($conn->connect_error) {
    die("Chyba pripojenia k databáze: " . $conn->connect_error);
}

// Získanie dát z formulára a zabezpečenie vstupov
$name = mysqli_real_escape_string($conn, $_POST['name']);
$age = intval($_POST['age']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$handedness = mysqli_real_escape_string($conn, $_POST['handedness']);
$mouseexp = intval($_POST['mouseexp']);
$important = intval($_POST['important']);
$factor = intval($_POST['factor']);
$session = mysqli_real_escape_string($conn, $_POST['session']);
$current_emotion = intval($_POST['current-emotion']);
$expectation_words = "";
if(isset($_POST['expectation-words'])) {
    $expectation_words = intval($_POST['expectation-words']);
}
// Vloženie dát do tabuľky
$sql = "INSERT INTO dotaznik1 (session, name, age, gender, handedness, mouseexp, important, factor, expectation_words, current_emotion)
        VALUES ('$session', '$name', '$age', '$gender', '$handedness', '$mouseexp', '$important', '$factor', '$expectation_words', '$current_emotion')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Zatvorenie prepared statement a pripojenia k databáze
$conn->close();
?>

<!-- testovanie..
<pre> <?php 
print_r($_POST);  