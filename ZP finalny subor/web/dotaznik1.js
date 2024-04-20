document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.getElementById('submit-button');
    const expectationSlider = document.getElementById('current-emotion'); // Doplnená inicializácia premennej
    const expectationEmojiContainer = document.getElementById('expectation-emoji-container');

    // Aktualizácia smajlíka pri posunutí slideru pre úroveň očakávania
    expectationSlider.addEventListener('input', function () {
        updateExpectationEmoji();
    });

    function updateExpectationEmoji() {
        const expectationValue = expectationSlider.value;
        expectationEmojiContainer.innerHTML = getEmoji(expectationValue);
    }
/*
    submitButton.addEventListener('click', function () {
        const expectationWordsCheckboxes = document.querySelectorAll('input[name="expectation-words"]:checked');

        const selectedExpectationWords = Array.from(expectationWordsCheckboxes)
            .map(checkbox => checkbox.value);

        const name = document.getElementById('name').value;
        const age = document.getElementById('age').value;
        const gender = document.querySelector('input[name="gender"]:checked').value;
        const handedness = document.querySelector('input[name="handedness"]:checked').value;
        const mouseexp = document.querySelector('input[name="mouseexp"]:checked').value;
        const important = document.querySelector('input[name="important"]:checked').value;
        const factor = document.querySelector('input[name="factor"]:checked').value;
        const currentEmotion = document.getElementById('current-emotion').value;
        const session = document.getElementById('session').value;

        if (name && age && gender && handedness && mouseexp && important && factor && currentEmotion && session) {

            const formData = {
                session: session,
                name: name,
                age: age,
                gender: gender,
                handedness: handedness,
                mouseexp: mouseexp,
                important: important,
                factor: factor,
                selectedExpectationWords: selectedExpectationWords,
                current_emotion: currentEmotion
            };

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'process_form.php', true); // Opravený odkaz na súbor
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                }
            };

            const jsonData = JSON.stringify(formData);

            xhr.send(jsonData);
        } else {
            alert('Vyplňte všechny otázky.');
        }
    }); */
});
