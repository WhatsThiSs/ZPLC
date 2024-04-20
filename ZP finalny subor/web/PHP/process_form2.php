<?php

$servername = "mysql80.r6.websupport.sk";
$username = "lubo2025";
$password = "Xc6<N%U&d?";
$dbname = "databazaZP";

// Pripojenie k databáze
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrola pripojenia
if ($conn->connect_error) {
    die("Chyba pripojenia k databáze: " . $conn->connect_error);
}

// Získanie dát z formulára a ošetrenie vstupov
$session = mysqli_real_escape_string($conn, $_POST['session']);
$expectationsafter = mysqli_real_escape_string($conn, $_POST['expectationsafter']);
$hard = mysqli_real_escape_string($conn, $_POST['hard']);
$changes = mysqli_real_escape_string($conn, $_POST['changes']);
$expectationwordafter = mysqli_real_escape_string($conn, $_POST['expectationwordafter']);
$current_emotion = intval($_POST['current-emotion']);

$expectation_words = "";
if(isset($_POST['expectation-words'])) {
    $expectation_words = intval($_POST['expectation-words']);
}

// Príprava SQL dotazu pre vloženie údajov do databázy
$sql = "INSERT INTO dotaznik2 (session, expectationsafter, hard, changes, expectation_words, expectationwordafter, current_emotion)
VALUES ('$session', '$expectationsafter', '$hard', '$changes', '$expectation_words', '$expectationwordafter', '$current_emotion')";

// Spustenie dotazu
if ($conn->query($sql) === TRUE) {
    echo "Dáta úspešne uložené do databázy";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Uzatvorenie pripojenia k databáze
$conn->close();

?>
