<?php
$servername = "mysql80.r6.websupport.sk";
$username = "lubo2025";
$password = "Xc6<N%U&d?";
$dbname = "databazaZP";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba pripojenia k databáze: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

//  top 10 hráčov podľa počtu Hits spolu s menami 
$sql = "SELECT MAX(l.Hits) AS Hits, MIN(l.Misses) AS Misses, MAX(l.ClickFrequency) AS ClickFrequency, MAX(d.name) AS name
FROM (
    SELECT l.session, MAX(l.Hits) AS MaxHits
    FROM level3 AS l
    GROUP BY l.session
     ) AS max_hits
    JOIN level3 AS l ON max_hits.session = l.session AND max_hits.MaxHits = l.Hits
    JOIN dotaznik1 AS d ON l.session = d.session
    GROUP BY l.session
    ORDER BY Hits DESC, Misses ASC, ClickFrequency DESC
LIMIT 10";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>#</th><th>Meno</th><th>Zásahy</th><th>Missnutia</th><th>Frekvencia Klikania</th></tr>";
    $rank = 1;
    while($row = $result->fetch_assoc()) {
        $highlight = ''; // Predvolená hodnota pre zvýraznenie
        // Porovnávanie aktuálnej session s sessionami v top 10
        if (isset($_SESSION['session']) && $_SESSION['session'] == $row['session']) {
            $highlight = 'style="font-weight: bold; background-color: yellow;"'; // Zvýraznenie riadku
        }
        echo "<tr $highlight><td>".$rank."</td><td>".$row["name"]."</td><td>".$row["Hits"]."</td><td>".$row["Misses"]."</td><td>".$row["ClickFrequency"]."</td></tr>";
        $rank++;
    }
    echo "</table>";
} else {
    echo "Žiadni hráči nie sú k dispozícii";
}

$conn->close();
?>
