/*

document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submit-button');
    const expectationEmojiContainer = document.getElementById('expectation-emoji-container');
    const expectationWordsCheckboxes = document.querySelectorAll('input[name="expectation-words"]');
    const expectationSlider = document.getElementById('current-emotion'); // pridane

    // Aktualizácia smajlíka pri posunutí slideru pre úroveň očakávania
    expectationSlider.addEventListener('input', function () {
        updateExpectationEmoji();
    });

    function updateExpectationEmoji() {
        const expectationValue = expectationSlider.value;
        expectationEmojiContainer.innerHTML = getEmoji(expectationValue);
    }

    // Spracovanie vybraných slov pre očakávania
    submitButton.addEventListener('click', function () {
        const selectedExpectationWords = Array.from(expectationWordsCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        const expectationsafter = document.querySelector('input[name="expectationsafter"]:checked').value;
        const hard = document.querySelector('input[name="hard"]:checked').value;
        const changes = document.querySelector('input[name="changes"]:checked').value;
        const currentEmotion = document.getElementById('current-emotion').value;

        // Vytvorenie objektu s dátami na odoslanie na server
        const formData = {
            expectationsafter: expectationsafter,
            hard: hard,
            changes: changes,
            currentEmotion: currentEmotion,
            selectedExpectationWords: selectedExpectationWords
        };

        // Odoslanie dát na server pomocou AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'process_form2.php', true); // Zmenené URL na spracovanie formulára
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                // Môžete pridať ďalšie spracovanie odpovede zo servera, ak je to potrebné
            }
        };

        // Prevedenie objektu na JSON reťazec
        const jsonData = JSON.stringify(formData);

        // Odoslanie dát na server
        xhr.send(jsonData);
    });
});
