<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/thanks.css">
    <link rel="icon" type="image/png" href="/resource/thank.jpg">
    <link rel="icon" type="image/png" href="/LCfavicon.png">
    <title>Ďakujeme!</title>
    <style>
    .thanks-container {
        text-align: center;
        padding: 20px;
    }

    .results-container {
        margin-top: 20px;
    }
    
    .smiley-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

   .emoji {
        font-size: 36px;
        padding: 5px;
    }

     .feedback-container {
        margin-top: 20px;
        text-align: center;
    }

    .feedback-label {
        margin-right: 10px;
    }

    #submit-feedback-button {
        margin-top: 10px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #submit-feedback-button:hover {
        background-color: #45a049;
    }
    .top-number {
        width: 30px;
    }
    .smiley {
        font-size: 44px;
    }
    
    </style>
</head>
<body>
    <div class="thanks-container">
        <h1>Ďakujeme za účasť!</h1>
        <p>Model kategorizuje na 3 cieľové emócie: pozitivna - neutrálna - negatívna.</p> 
        <p>Zobrazený smajlík zodpovedá výslednej emocii.</p> 
        <p>Pod tým je dotaz na spätnú väzbu o tom či to sedí, prosím odošlite ju.</p> 
        <div id="prediction-section">
            <h2>Výsledky predikcie:</h2>
            <button id="play-again-button">Spustiť predikciu</button>
        </div>
        <div id="thanks-message" style="display: none;">Dáta úspešne odoslané.</div>

        <?php
        // Kontrola prítomnosti súboru modelu                       // Pôvodne cela tato stranka slúžila iba ako podakovanie za účasť...
        $modelFilePath = 'model.json'; // Cesta k modelu

        if (file_exists($modelFilePath)) {
            $modelContent = file_get_contents($modelFilePath); // Načítanie obsahu modelu
            //echo "<pre>$modelContent</pre>"; // Zobrazenie obsahu modelu
        } else {
            echo "Model nie je prítomný na serveri.";
        }
        ?>
        
        <?php
        // Pripojenie k databáze
        $conn = new mysqli('mysql80.r6.websupport.sk', 'lubo2025', 'Xc6<N%U&d?', 'databazaZP');
        
        // Kontrola spojenia
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Dotaz na databázu pre získanie údajov len pre aktívny sessionID a dáta ktoré potrebuje model
        $sessionID = $_COOKIE['session'];

        $sql = "SELECT 
                    l2.TimeRemaining AS TimeRemaining_y,
                    l1.overallAverageMouseSpeed AS overallAverageMouseSpeed_x,
                    l2.overallAverageMouseSpeed AS overallAverageMouseSpeed_y,
                    l2.thresholdedDirectionChanges AS thresholdedDirectionChanges_y,
                    l2.ClickFrequency AS ClickFrequency_y,
                    l3.Misses AS Misses,
                    l3.Hits AS Hits,
                    l3.overallAverageMouseSpeed AS overallAverageMouseSpeed,
                    l3.thresholdedDirectionChanges AS thresholdedDirectionChanges,
                    l3.ClickFrequency AS ClickFrequency,
                    d2.current_emotion AS current_emotion_y,
                    d2.expectationwordafter AS new_expectation     
                FROM 
                    dotaznik1 d1
                INNER JOIN 
                    level1 l1 ON d1.session = l1.Session
                INNER JOIN 
                    level2 l2 ON d1.session = l2.Session
                INNER JOIN 
                    level3 l3 ON d1.session = l3.Session
                INNER JOIN 
                    dotaznik2 d2 ON d1.session = d2.session
                WHERE 
                    d1.session = '$sessionID'";

        $result = $conn->query($sql);

        // Vytvorenie asociatívneho poľa pre výsledky
        $data = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Formátovanie dát pre model
                $formatted_data = array(
                    floatval($row['TimeRemaining_y']),
                    floatval($row['overallAverageMouseSpeed_x']),
                    floatval($row['overallAverageMouseSpeed_y']),
                    floatval($row['thresholdedDirectionChanges_y']),
                    floatval($row['ClickFrequency_y']),
                    floatval($row['Misses']),
                    floatval($row['Hits']),
                    floatval($row['overallAverageMouseSpeed']),
                    floatval($row['thresholdedDirectionChanges']),
                    floatval($row['ClickFrequency']),
                    floatval($row['current_emotion_y']),
                    floatval($row['expectationwordafter'])
                );
                echo '<script>const new_expectation = ' . json_encode($row['new_expectation']) . ';</script>';
                // Normalizácia dát
                $means = array_sum($formatted_data) / count($formatted_data);
                $stds = sqrt(array_sum(array_map(function($x) use ($means) { return pow($x - $means, 2); }, $formatted_data)) / count($formatted_data));
                $normalized_data = array_map(function($x) use ($means, $stds) { return ($x - $means) / $stds; }, $formatted_data);

                $data[] = $normalized_data;
            }
            // Konvertovanie do formátu JSON
            $json_data = json_encode($data);
        } else {
            echo "<script>console.error('Chyba: Nepodarilo sa načítať dáta z databázy.')</script>";
        }
        ?>

        <div id="results-container"></div>
        <div id="model-input" style="display: none;"><?php echo json_encode($data); ?></div>

        <!-- spätná vazba -->
        <div class="feedback-container" style="display: none;">
            <h3>Ohodnoťte, nakoľko súhlasíte, alebo nesúhlasíte s touto predikciou.</h3>
            <label class="feedback-label"><input type="radio" name="feedback" value="5"> Úplne súhlasím</label>
            <label class="feedback-label"><input type="radio" name="feedback" value="4"> Súhlasím</label>
            <label class="feedback-label"><input type="radio" name="feedback" value="3"> Čiastočne súhlasím</label>
            <label class="feedback-label"><input type="radio" name="feedback" value="2"> Nesúhlasím</label>
            <label class="feedback-label"><input type="radio" name="feedback" value="1"> Úplne nesúhlasím</label>
            <button id="submit-feedback-button">Odoslať hodnotenie</button>
            <div id="feedback-error" style="color: red; display: none;">Prosím, zvoľte si niektoré hodnotenie.</div> 
        </div>
    </div>

    <!-- Import TensorFlow.js knižnice -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.8.0/dist/tf.min.js"></script>

    <script>
    // Nastavenie TensorFlow.js backendu na CPU
    tf.setBackend('cpu');

    // Definícia triedy L1 pre regularizáciu
    class L1 {
        constructor(config) {
            return tf.regularizers.l1(config);
        }
        static get className() {
            return 'L1';
        }
    }
    tf.serialization.registerClass(L1);

    // Funkcia na získanie sessionID z cookies pomocou JavaScriptu
    function getSessionIDFromCookie() {
        return document.cookie.replace(/(?:(?:^|.*;\s*)session\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    }

    // Funkcia na predikciu pomocou TensorFlow.js
    async function predictUsingTF(data) {
        const model = await tf.loadLayersModel('model.json'); // Načítanie modelu
        const tensorData = tf.tensor(data); // Konverzia dát na tensor
        const predictions = model.predict(tensorData); // Predikcia
        return predictions.array(); // Vrátenie výsledkov
    }

    // Funkcia na odoslanie údajov modelu na server a zobrazenie odpovede
    async function fetchDataAndProcess() {
        const dataFromDatabase = JSON.parse(document.getElementById('model-input').innerText); // Získanie dát z databázy
        console.log('Odoslané dáta modelu:', dataFromDatabase);

        try {
            const predictions = await predictUsingTF(dataFromDatabase); // Predikcia pomocou TensorFlow.js
            console.log('Odpoveď modelu:', predictions); // Zobrazenie výsledkov

            // Skrytie textu a tlačidla pre predikciu
            document.getElementById('prediction-section').style.display = 'none';

            // Zobrazenie výsledkov na stránke
            const resultsContainer = document.getElementById('results-container');
            resultsContainer.innerHTML = '<div class="smiley-container">';

            // Uloženie výsledkov do databázy
            const   sessionID = getSessionIDFromCookie();
            const predikovaneHodnoty = predictions[0]; // Prístup k prvemu prvku v poli, ktorý obsahuje pole s pravdepodobnosťami
            const maxPravdepodobnost = Math.max(...predikovaneHodnoty); // Nájde maximálnu hodnotu v poli pravdepodobností
            const indexMaxPravdepodobnosti = predikovaneHodnoty.indexOf(maxPravdepodobnost); // Index maximálnej pravdepodobnosti
            const predikovana = indexMaxPravdepodobnosti;
            const odhadovana = new_expectation; 


            // Rozhodovanie o smajlikovi na základe predikovanej hodnoty
            let smiley = '';
            if (predikovana === 0) {
                smiley = '😞'; // smutný smajlík
            } else if (predikovana === 1) {
                smiley = '😐'; // neutrálny smajlík
            } else if (predikovana === 2) {
                smiley = '😊'; // šťastný smajlík
            }

                   // Zaokrúhlenie pravdepodobností na 3 desatinné miesta
                const roundedProbabilities = predikovaneHodnoty.map(probability => probability.toFixed(2));

                // Vytvorenie zoznamu smajlíkov pre jednotlivé emócie
                const emoji = ['😞', '😐', '😊'];

                // Vloženie smajlíka a pravdepodobností do kontajnera
                resultsContainer.innerHTML = `
                    <div class="smiley-container">
                        <div class="smiley" style="font-size: 60px;">${smiley}</div>
                    </div>
                    <div class="probabilities-container">
                        <h3>Predikcia modelu:</h3>
                        <ul style="list-style-type: none;"> 
                            <li><span class="emoji">${emoji[0]}</span> <span class="top-number">${roundedProbabilities[0]}</span> - Negatívna emócia</li>
                            <li><span class="emoji">${emoji[1]}</span> <span class="top-number">${roundedProbabilities[1]}</span> - Neutrálna emócia</li>
                            <li><span class="emoji">${emoji[2]}</span> <span class="top-number">${roundedProbabilities[2]}</span> - Pozitívna emócia</li>
                        </ul>
                    </div>`;

            // Zobrazenie formulára na hodnotenie a odoslanie údajov na server po kliknutí na tlačidlo "Spustiť predikciu"
            document.querySelector('.feedback-container').style.display = 'block';

            // Nastavenie obsluhy udalosti pre tlačidlo "Odoslať hodnotenie"
            document.getElementById('submit-feedback-button').addEventListener('click', function() {
            const selectedValue = document.querySelector('input[name="feedback"]:checked');
            if (!selectedValue) {
                // Ak nie je vybrané žiadne hodnotenie, zobrazíme upozornenie
                document.getElementById('feedback-error').style.display = 'block';
            } else {
                // Ak je vybrané hodnotenie, odosielame údaje na server
                document.getElementById('feedback-error').style.display = 'none';
                const selectedFeedbackValue = selectedValue.value;
                sendDataToServer(sessionID, odhadovana, predikovana, selectedFeedbackValue);
            }
        });


        } catch (error) {
            console.error('Chyba pri predikcii:', error); // Zobrazenie chyby pri predikcii
        }
    }

    // Funkcia na odoslanie údajov na server
    async function sendDataToServer(sessionID, odhadovana, predikovana, feedbackValue) {
        const data = {
            sessionID: sessionID,
            odhadovana: odhadovana,
            predikovana: predikovana,
            overenie: feedbackValue 
        };

        try {
            const response = await fetch('save.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Nastala chyba spojenia');
            }

            console.log('Údaje úspešne odoslané na server.');

            // Výpis údajov odoslaných do databázy
            console.log('Odoslané údaje do databázy:', data);

            // Schovanie feedback containeru a zobrazenie poďakovania
            document.querySelector('.feedback-container').style.display = 'none';
            document.getElementById('thanks-message').style.display = 'block';

        } catch (error) {
            console.error('Chyba pri odosielaní údajov na server:', error);
        }
    }

    // Spustenie funkcie pri stlačení tlačidla
    document.getElementById('play-again-button').addEventListener('click', async function () {
        await fetchDataAndProcess();
    });

    // Zobrazenie aktívneho session v konzole
    console.log('Aktívny session:', getSessionIDFromCookie());

    
    // Spustenie funkcie pri načítaní dokumentu
    document.addEventListener('DOMContentLoaded', async function () {
        // Skrytie tlačidla pre spustenie predikcie
        document.getElementById('play-again-button').style.display = 'none';
        
        // Spustenie funkcie pre načítanie údajov a ich spracovanie
        await fetchDataAndProcess();
    });
    
    </script>
</body>
</html>
