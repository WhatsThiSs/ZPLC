<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/dotaznik1.css">
    <link rel="icon" type="image/png" href="/LCfavicon.png">
   
    <title>Dotazník 1</title>
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
        <h1>Dotazník 1</h1>
        <form id="question-form" action="/PHP/process_form.php" method="post">
            <input type="hidden" id="session" name="session" value="">

            <div class="question current-question" data-question-index="0">
                <label for="name">Otázka 1. Vaše meno:</label>
                <input type="text" id="name" name="name" placeholder="" required>
            </div>

            <div class="question" data-question-index="1">
                <label for="age">Otázka 2. Vek:</label>
                <input type="number" id="age" name="age" placeholder="" required>
            </div>

            <div class="question" data-question-index="2">
                <label for="gender">Otázka 3. Pohlavie:</label>
                <div>
                    <input type="radio" id="male" name="gender" value="male">
                    <label for="male">Muž</label>

                    <input type="radio" id="female" name="gender" value="female">
                    <label for="female">Žena</label>
                </div>
            </div>

            <div class="question" data-question-index="3">
                <label for="handedness">Otázka 4. Ste pravák alebo ľavák?</label>
                <div>
                    <input type="radio" id="right" name="handedness" value="right">
                    <label for="right">Pravák</label>

                    <input type="radio" id="left" name="handedness" value="left">
                    <label for="left">Ľavák</label>
                </div>
            </div>

            <div class="question" data-question-index="4">
                <label for="mouseexp">Otázka 5. Používate často počítačovú myš ?</label>
                <div>
                    <input type="radio" id="mouseexpyes" name="mouseexp" value="1">
                    <label for="mouseexpyes">Ano</label>

                    <input type="radio" id="mouseexpno" name="mouseexp" value="0">
                    <label for="mouseexpno">Nie</label>
                </div>
            </div>

            <div class="question" data-question-index="5">
                <label for="important">Otázka 6. Sú podľa vás emócie dôležité v spojitosti s prácou pri PC ?</label>
                <div>
                    <input type="radio" id="importantyes" name="important" value="2">
                    <label for="importantyes">Ano</label>

                    <input type="radio" id="importantno" name="important" value="1">
                    <label for="importantno">Nie</label>

                    <input type="radio" id="importantmaybe" name="important" value="0">
                    <label for="importantmaybe">Neviem posúdiť</label>
                </div>
            </div>

            <div class="question" data-question-index="6">
                <label for="factor">Otázka 7: Ktorý z nasledujúcich faktorov je pre vás najdôležitejší z hľadiska emocionálnej reakcie počas hrania počítačových hier ?</label>
                <div>
                    <input type="radio" id="difficulty" name="factor" value="5">
                    <label for="difficulty">Náročnosť hry</label>

                    <input type="radio" id="sounds" name="factor" value="4">
                    <label for="sounds">Zvukový dizajn</label>
                
                    <input type="radio" id="graphics" name="factor" value="3">
                    <label for="graphics">Grafický dizajn</label>
                
                    <input type="radio" id="concurence" name="factor" value="2">
                    <label for="concurence">Súťaživosť</label>

                    <input type="radio" id="nothing" name="factor" value="1">
                    <label for="nothing">Nič z uvedeného</label>
                </div>
            </div>

            <div class="question" data-question-index="7">
                <label for="expectation-words">Otázka 8. Vyberte slovo, ktoré najviac vystihuje váš aktuálny emocionálny stav:</label>
                <div>
                    <input type="radio" id="excitement" name="expectation-words" value="6">
                    <label for="excitement">Nadšenie</label>

                    <input type="radio" id="joy" name="expectation-words" value="5">
                    <label for="joy">Radosť</label>

                    <input type="radio" id="peace" name="expectation-words" value="4">
                    <label for="peace">Spokojnosť</label>
                
                    <input type="radio" id="neutral" name="expectation-words" value="3">
                    <label for="neutral">Neutralita</label>
                
                    <input type="radio" id="anxiety" name="expectation-words" value="2">
                    <label for="anxiety">Úzkosť</label>

                    <input type="radio" id="anger" name="expectation-words" value="1">
                    <label for="anger">Hnev</label>

                    <input type="radio" id="fear" name="expectation-words" value="0">
                    <label for="fear">Strach</label>
                </div>
            </div>

            <div class="question" data-question-index="8">
            <label for="current-emotion" id="current-emotion-label">Otázka 9. Ohodnoťte svoj aktuálny emocionálny stav  <-- --></label>
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

        // Spustenie prvého dotazu
        document.querySelector('.question').classList.add('current-question');

        // Funkcia na generovanie náhodného session ID
        function generateSessionID() {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        }

        function setSessionCookie(sessionID) {
            const now = new Date();
            const expirationTime = new Date(now.getTime() + 60 * 60 * 1000); // 60 minút
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
                    xhr.open('POST', '/PHP/process_form.php', true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            alert('Dáta úspešne odoslané');
                            window.location.href = 'game.html';
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