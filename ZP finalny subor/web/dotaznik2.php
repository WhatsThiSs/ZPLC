<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/dotaznik2.css">
    <link rel="icon" type="image/png" href="/LCfavicon.png">
    <title>Dotazník 2</title>
    <style>
        .question {
            display: none;
        }

        .current-question {
            display: block;
        }
    </style>
</head>
<body>
    <div id="questionnaire-container" class="container">
        <h1>Dotazník 2</h1>
        <form id="question-form" action="/PHP/process_form2.php" method="post">
        <input type="hidden" id="session" name="session">

           <div class="question current-question" data-question-index="0">
                <label for="expectationsafter">Otázka 1. Splnila táto hra vaše očakávania z hľadiska náročnosti ?</label>
                <div>
                    <input type="radio" id="expectationsafter-yes" name="expectationsafter" value="1">
                    <label for="expectationsafter-yes">Ano</label>

                    <input type="radio" id="expectationsafter-no" name="expectationsafter" value="0">
                    <label for="expectationsafter-no">Nie</label>
                </div>
            </div>

            <div class="question" data-question-index="1">
                <label for="hard">Otázka 2. Ktorý level bol pre vás najnáročnejší ?</label>
                <div>
                    <input type="radio" id="hard-1" name="hard" value="1">
                    <label for="hard-1">Level 1</label>

                    <input type="radio" id="hard-2" name="hard" value="2">
                    <label for="hard-2">level 2</label>

                    <input type="radio" id="hard-3" name="hard" value="3">
                    <label for="hard-3">level 3</label>
                </div>
            </div>

            <div class="question" data-question-index="2">
                <label for="changes">Otázka 3. Pocítili ste nejaké náznaky zmeny nálady počas hrania ?</label>
                <div>
                    <input type="radio" id="changes-yes" name="changes" value="1">
                    <label for="changes-yes">Ano</label>

                    <input type="radio" id="changes-no" name="changes" value="0">
                    <label for="changes-no">Nie</label>
                </div>
            </div>
            
            <div class="question" data-question-index="3">
            <label for="expectation-words">Otázka 4. Ktorý z nasledujúcich faktorov BOL pre vás najdôležitejší z hľadiska emocionálnej reakcie počas hrania počítačových hier:</label>
           <div>
                    <input type="radio" id="difficulty" name="expectation-words" value="5">
                    <label for="difficulty">Náročnosť hry</label>

                    <input type="radio" id="sounds" name="expectation-words" value="4">
                    <label for="sounds">Zvukový dizajn</label>
                
                    <input type="radio" id="graphics" name="expectation-words" value="3">
                    <label for="graphics">Grafický dizajn</label>
                
                    <input type="radio" id="concurence" name="expectation-words" value="2">
                    <label for="concurence">Súťaživosť</label>

                    <input type="radio" id="nothing" name="expectation-words" value="1">
                    <label for="nothing">Nič z uvedeného</label>
                </div>
        </div>

         <div class="question" data-question-index="4">
            <label for="expectationwordafter">Otázka 5. Vyberte slovo, ktoré PO odohraní hry podľa vás najviac vystihuje váš aktuálny emocionálny stav:</label>
            <div>
                <input type="radio" id="excitement" name="expectationwordafter" value="2"> <!-- Nadšenie -->
                <label for="excitement">Nadšenie</label>

                <input type="radio" id="joy" name="expectationwordafter" value="2"> <!-- Radosť -->
                <label for="joy">Radosť</label>

                <input type="radio" id="peace" name="expectationwordafter" value="2"> <!-- Spokojnosť -->
                <label for="peace">Spokojnosť</label>

                <input type="radio" id="neutral" name="expectationwordafter" value="1"> <!-- Neutralita -->
                <label for="neutral">Neutralita</label>

                <input type="radio" id="anxiety" name="expectationwordafter" value="0"> <!-- Úzkosť -->
                <label for="anxiety">Úzkosť</label>

                <input type="radio" id="anger" name="expectationwordafter" value="0"> <!-- Hnev -->
                <label for="anger">Hnev</label>

                <input type="radio" id="fear" name="expectationwordafter" value="0"> <!-- Strach -->
                <label for="fear">Strach</label>
            </div>
        </div>



            <div class="question" data-question-index="5">
                <label for="current-emotion" id="current-emotion-label">Otázka 6. Vizuálne ohodnotte smajlíkom ako sa cítite po dohraní hry.</label>
                <input type="range" id="current-emotion" name="current-emotion" min="0" max="10" step="1" value="5" required>
                <div id="emoji-container"></div>
            </div>

            <button type="button" id="submit-button">Odoslať</button>
        </form>

          <script>
            document.addEventListener('DOMContentLoaded', function () {
                function updateEmoji() {
                    const currentEmotion = document.getElementById('current-emotion').value;
                    const emojiContainer = document.getElementById('emoji-container');
                    emojiContainer.innerHTML = getEmoji(currentEmotion);
                }

                function getEmoji(emotionValue) {
                    if (emotionValue <= 1) {
                        return '😢'; 
                    } else if (emotionValue <= 3) {
                        return '😞';
                    } else if (emotionValue <= 6) {
                        return '😐'; 
                    } else if (emotionValue <= 8) {
                        return '🙂';
                    } else {
                        return '😊'; 
                    }
                }

                // Zobraziť smajlíka pri načítaní stránky
                updateEmoji();

                document.getElementById('current-emotion').addEventListener('input', function () {
                    updateEmoji();
                });

                // Funkcia na generovanie náhodného session ID
                function generateSessionID() {
                    return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
                }

                function setSessionCookie(sessionID) {
                    const now = new Date();
                    const expirationTime = new Date(now.getTime() + 20 * 60 * 1000); // 20 minút
                    document.cookie = `session=${sessionID}; expires=${expirationTime.toUTCString()}; path=/`;
                }

                // Získanie alebo generovanie session ID a uloženie do cookies
                let sessionID = document.cookie.replace(/(?:(?:^|.*;\s*)session\s*\=\s*([^;]*).*$)|^.*$/, "$1");
                if (!sessionID) { 
                    sessionID = generateSessionID();
                    setSessionCookie(sessionID);
                }
                document.getElementById('session').value = sessionID;
                console.log("Session ID:", sessionID);

                // Funkcia na validáciu vstupov
                function validateInputs(inputs) {
                    let isValid = true;

                    inputs.forEach(input => {
                        if (input.hasAttribute('required') && input.value.trim() === '') {
                            isValid = false;
                        }

                        if (input.type === 'radio') {
                            const radioGroup = document.querySelectorAll(`input[name="${input.name}"]:checked`);
                            if (radioGroup.length === 0) {
                                isValid = false;
                            }
                        }

                        if (input.type === 'checkbox' && input.name === 'expectation-words') {
                            const checkedCheckboxes = document.querySelectorAll('input[name="expectation-words"]:checked');
                            if (checkedCheckboxes.length === 0) {
                                isValid = false;
                            }
                        }
                    });

                    return isValid;
                }

                // Odoslanie formulára
                document.getElementById('submit-button').addEventListener('click', function () {
                    const questions = document.querySelectorAll('.question');
                    const currentQuestionIndex = [...questions].findIndex(question => question.classList.contains('current-question'));
                    const currentQuestion = questions[currentQuestionIndex];
                    const inputs = currentQuestion.querySelectorAll('input, select');
                    const isLastQuestion = currentQuestionIndex === questions.length - 1;

                    if (validateInputs(inputs)) {
                        if (isLastQuestion) {
                            // Odoslanie formulára pomocou AJAX
                            const formData = new FormData(document.getElementById('question-form'));
                            const xhr = new XMLHttpRequest();
                            xhr.open('POST', '/PHP/process_form2.php', true);
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    alert('Dáta úspešne odoslané');
                                    window.location.href = 'thanks.php';
                                }
                            };
                            xhr.send(formData);
                        } else {
                            // Prechod na ďalšiu otázku
                            currentQuestion.classList.remove('current-question');
                            questions[currentQuestionIndex + 1].classList.add('current-question');
                        }
                    } else {
                        alert('Vyplňte všetky povinné otázky.');
                    }
                });

                // Spustenie prvého dotazu
                document.querySelector('.question').classList.add('current-question');
            });
        </script>
    </div>
</body>
</html>
