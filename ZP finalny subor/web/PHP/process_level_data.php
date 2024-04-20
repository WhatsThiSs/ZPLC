<?php

$servername = "mysql80.r6.websupport.sk";
$username = "lubo2025";
$password = "Xc6<N%U&d?";
$dbname = "databazaZP";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba pripojenia k databáze: " . $conn->connect_error);
}

// Získanie údajov z JSON payloadu
$data = json_decode(file_get_contents('php://input'), true);

// Ošetrenie údajov a príprava na vloženie do databázy
$tableName = "level" . mysqli_real_escape_string($conn, $data['Level']);
$session = mysqli_real_escape_string($conn, $data['Session']);
$timeRemaining = intval($data['TimeRemaining']);
$misses = intval($data['Misses']);
$hits = intval($data['Hits']);
$overallAverageMouseSpeed = round(floatval($data['overallAverageMouseSpeed']), 3); // Zaokrúhlenie na 3 desatinné miesta
$thresholdedDirectionChanges = intval($data['thresholdedDirectionChanges']);
$clickFrequency = round(floatval($data['ClickFrequency']), 3); // Zaokrúhlenie na 3 desatinné miesta
$level = intval($data['Level']);

// Príprava prepared statement
$sql = "INSERT INTO $tableName (Session, TimeRemaining, Misses, Hits, overallAverageMouseSpeed, thresholdedDirectionChanges, ClickFrequency, Level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Viazanie parametrov
$stmt->bind_param("siiisidi", $session, $timeRemaining, $misses, $hits, $overallAverageMouseSpeed, $thresholdedDirectionChanges, $clickFrequency, $level);

// Spustenie dotazu
if ($stmt->execute()) {
    echo json_encode(array("message" => "Dáta úspešne uložené do databázy"));
} else {
    echo json_encode(array("message" => "Chyba: " . $conn->error));
}

// Uzatvorenie prepared statement a pripojenia k databáze
$stmt->close();
$conn->close();
