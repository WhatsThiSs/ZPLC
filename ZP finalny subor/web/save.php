<?php
// Pripojenie k databáze
$conn = new mysqli('mysql80.r6.websupport.sk', 'lubo2025', 'Xc6<N%U&d?', 'databazaZP');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Získanie údajov z požiadavky AJAX
$postData = json_decode(file_get_contents("php://input"), true); // Dekódovanie JSON reťazca

// Overenie, či sa údaje úspešne dekódovali
if ($postData === null) {
    echo "Chyba pri dekódovaní JSON dát.";
    exit;
}

// Extrahovanie údajov z dekódovaného poľa
$sessionID = $postData['sessionID'];
$odhadovana = $postData['odhadovana'];
$predikovana = $postData['predikovana'];
$overenie = $postData['overenie'];

// Pripravenie SQL dotazu s parametrami
$insertSql = "INSERT INTO pred (sessionID, odhadovana, predikovana, overenie, datum) VALUES (?, ?, ?, ?, CURRENT_DATE)";

// Príprava príkazu a väzba parametrov
$stmt = $conn->prepare($insertSql);
$stmt->bind_param("ssss", $sessionID, $odhadovana, $predikovana, $overenie);

// Vloženie údajov do databázy
if ($stmt->execute()) {
    echo "Údaje úspešne uložené do databázy.";
} else {
    echo "Chyba pri vkladaní údajov do databázy: " . $stmt->error;
}

// Uvoľnenie príkazu
$stmt->close();

// Zatvorenie spojenia s databázou
$conn->close();
?>
