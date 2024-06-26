document.addEventListener('DOMContentLoaded', function () {
  const canvas = document.getElementById('game-canvas');
  const ctx = canvas.getContext('2d');
  const startButton = document.getElementById('start-button');
  const timerContainer = document.getElementById('timer-container');
  const scoreContainer = document.getElementById('score-container');
  const levelContainer = document.getElementById('level-container');

  let squares = [];
  let timeRemaining = 60;
  let score = 0;
  let totalClicks = 0;
  let uniqueClicks = 0;
  let misses = 0;
  let hits = 0;
  let level = 2;
  let gameEnded = false;
  let timerInterval;
  let prevMouseX, prevMouseY;
  let mouseSpeedSum = 0;
  let mouseMoveCount = 0;
  let clickCount = 0;
  let usedColors = [];
  const midColors = [
    '#FFD700', '#FFA07A', '#87CEEB', '#98FB98', '#FF6347',
    '#7B68EE', '#038a03', '#2525c2', '#FA8072', '#00CED1',
    '#FF1493', '#32CD32', '#FF8C00', '#8B008B', '#40E0D0'
  ]
  let distances = [];
  let prevDirection;
  let overallDirectionChanges = 0;
  const DIRECTION_CHANGE_THRESHOLD = 4;
  let overallMouseSpeedSum = 0;
  let overallMouseMoveCount = 0;
  let overallClickCount = 0;
  let levelStartTime;
  let levelCompletionTime = null;
  let thresholdedDirectionChanges=0;
  
  let overallAverageMouseSpeed = 0;
  let clickFrequency = 0;
  
  const correctSound = new Audio('correct.wav');
  const incorrectSound = new Audio('incorrect.wav');
  const tickSound = new Audio('tick.wav');

  function drawSquare(square) { // v tomto leveli je použitý gradient pre čísla aj štvorce 
    const gradient = ctx.createLinearGradient(square.x, square.y, square.x + square.size, square.y + square.size);
    gradient.addColorStop(0, square.color);
    gradient.addColorStop(1, 'white'); 
    ctx.fillStyle = gradient;
    ctx.fillRect(square.x, square.y, square.size, square.size);

    const textGradient = ctx.createLinearGradient(square.x, square.y, square.x + square.size, square.y + square.size);
    textGradient.addColorStop(0, 'black');
    textGradient.addColorStop(1, 'gray'); // odtieň čiernej farby pre gradient
    ctx.fillStyle = textGradient;
    ctx.font = '20px Arial';
    ctx.fillText(square.number, square.x + square.size / 2 - 8, square.y + square.size / 2 + 8);
}

  function drawSquares() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    squares.forEach(square => {
      drawSquare(square);
    });
  }

  function generateRandomSquare(number) {  
    const size = Math.floor(Math.random() * 50) + 30;
    let x, y;
    do {
      x = Math.random() * (canvas.width - size);
      y = Math.random() * (canvas.height - size);
    } while (squares.some(square => isOverlap(square, { x, y, size })));

    let color;
    if (usedColors.length < midColors.length) {
      do {
        color = midColors[Math.floor(Math.random() * midColors.length)];
      } while (usedColors.includes(color));
      usedColors.push(color);
    } else {
      usedColors = [];
      color = midColors[Math.floor(Math.random() * midColors.length)];
      usedColors.push(color);
    }

    return { x, y, size, number, color, speed: 0 }; 
  }

  function shuffleSquares() {
    squares.sort(() => Math.random() - 0.5);
    squares.forEach((square, index) => {
      square.number = index + 1;
    });
  }

  function isOverlap(square1, square2) { // nemaju pretrcat... 
    return (
      square1.x < square2.x + square2.size &&
      square1.x + square1.size > square2.x &&
      square1.y < square2.y + square2.size &&
      square1.y + square1.size > square2.y
    );
  }

    function calculateAverageMouseSpeed() {
    const averageSpeed = mouseSpeedSum / mouseMoveCount;
    overallAverageMouseSpeed = averageSpeed; 
    console.log(`Priemerná rýchlosť pohybu myši: ${averageSpeed.toFixed(3)} px`);
    overallMouseSpeedSum += mouseSpeedSum;
    overallMouseMoveCount += mouseMoveCount;
    mouseSpeedSum = 0;
    mouseMoveCount = 0;
  }

  function calculateClickFrequency() {
    clickFrequency = clickCount / (60 - timeRemaining); 
    console.log(`Frekvencia kliknutí: ${clickFrequency.toFixed(3)} kliknutí za sekundu`);
    overallClickCount += clickCount;
    clickCount = 0;
  }

  function calculateOverallAverages() {  
    const overallAverageMouseSpeed = overallMouseSpeedSum / overallMouseMoveCount;
    const overallTotalDirectionChanges = overallDirectionChanges;
    console.log(`Celková priemerná rýchlosť pohybu myši: ${overallAverageMouseSpeed.toFixed(3)}`);
    console.log(`Celkový súčet zmeny smeru: ${overallTotalDirectionChanges}`);
  }

  function calculateDirectionChanges() {
    const filteredDistances = distances.filter(distance => distance > DIRECTION_CHANGE_THRESHOLD); // pocet zmien smeru pohybu mysi ale aby ratalo az od urcitej vzdialenosti... 
    const thresholdedDirectionChanges = filteredDistances.length;
    console.log(`Počet zmien smeru (s vyfiltrovaním): ${thresholdedDirectionChanges}`);
    overallDirectionChanges += thresholdedDirectionChanges;
    distances = [];
  }

  function endLevel() {
    timeRemaining = 0;
    clearInterval(timerInterval);
  }

  function formatTime(milliseconds) {
    const seconds = (milliseconds / 1000).toFixed(3);
    return seconds;
  }

  function startNextLevel() {
    console.log('Spúšťa sa ďalší level...');
    window.location.href = 'level3.html';
  }

  function startLevel() {
    if (!gameEnded) {
      squares = [];
      let squareCount = 20; 
      let levelTime = 40; 
  
      timeRemaining = levelTime;
      levelContainer.innerText = `Level 2`; 
  
      levelStartTime = new Date();
  
      for (let i = 0; i < squareCount; i++) {
        squares.push(generateRandomSquare());
      }

      shuffleSquares();
      drawSquares();
      clearInterval(timerInterval);
  
         timerInterval = setInterval(function () {  // spustim timer začne hra
        timerContainer.innerText = `Čas: ${timeRemaining}`;
        if (timeRemaining <= 0) {   // čas vyprašl
          levelCompletionTime = new Date();
          gameEnded = false;
          tickSound.pause();
          calculateDirectionChanges();
          calculateAverageMouseSpeed();
          calculateClickFrequency();
          sendDataToServer();
          endLevel();
           alert("čas vypršal pre level 2..");
          clearInterval(timerInterval);
          startNextLevel(); 
        } else {  // ešte nevypršal
          drawSquares();
          timeRemaining--;
          tickSound.play();
        }
      }, 1000);
  
      canvas.addEventListener('mousemove', function (event) {
        if (!gameEnded) {
          const mouseX = event.clientX - canvas.getBoundingClientRect().left;
          const mouseY = event.clientY - canvas.getBoundingClientRect().top;
  
          if (prevMouseX !== undefined && prevMouseY !== undefined) {
            const distance = Math.sqrt((mouseX - prevMouseX) ** 2 + (mouseY - prevMouseY) ** 2);
            mouseSpeedSum += distance;
            mouseMoveCount++;
  
            const deltaX = mouseX - prevMouseX;
            const deltaY = mouseY - prevMouseY;
            const angle = Math.atan2(deltaY, deltaX);
            const angleDegrees = (angle * 180) / Math.PI;
            const direction = Math.round((angleDegrees + 180) / 45) % 8;
  
            if (prevDirection !== undefined && direction !== prevDirection) {
              distances.push(distance);
              prevDirection = direction;
            } else {
              prevDirection = direction;
            }
          }
          prevMouseX = mouseX;
          prevMouseY = mouseY;
        }
      });

      canvas.addEventListener('click', function (event) {
        if (!gameEnded) {
          clickCount++;
          const clickX = event.clientX - canvas.getBoundingClientRect().left;
          const clickY = event.clientY - canvas.getBoundingClientRect().top;
          totalClicks++;
          let correctClick = false;
  
          squares.forEach(square => {
            if (
              clickX >= square.x &&
              clickX <= square.x + square.size &&
              clickY >= square.y &&
              clickY <= square.y + square.size &&
              square.number === score + 1
            ) {
              correctClick = true;       // trafi sa do stvorca hrac
              hits++;
              score++;
              uniqueClicks++;
              scoreContainer.innerText = `Skóre: ${score}`;
              squares.splice(squares.indexOf(square), 1);
              drawSquares();
              correctSound.play();
  
              if (squares.length === 0) {   // vyklika vsetky stvorce
                levelCompletionTime = new Date();
                gameEnded = false;
                tickSound.pause();
                calculateDirectionChanges();
                calculateAverageMouseSpeed();
                calculateClickFrequency();
                sendDataToServer();
                endLevel();
                alert("Gratulujem, level 2 dokončený");
                startNextLevel();
              }
            }
          });
  
          if (!correctClick) {
            misses++;
  
            squares.forEach(square => {
              if (
                clickX >= square.x &&
                clickX <= square.x + square.size &&
                clickY >= square.y &&
                clickY <= square.y + square.size
              ) {
                setTimeout(() => {
                  //
                }, 200);
                incorrectSound.play();
              }
            });
          }
        }
      });
  
      startButton.style.display = 'none';
    }
  }

  function getSessionIDFromCookie() {
    return document.cookie.replace(/(?:(?:^|.*;\s*)session\s*\=\s*([^;]*).*$)|^.*$/, "$1");
  }

function sendDataToServer() {
    const sessionID = getSessionIDFromCookie();
    const data = {
        Session: sessionID,
        TimeRemaining: timeRemaining,
        Misses: misses,
        Hits: hits,
        overallAverageMouseSpeed: overallAverageMouseSpeed, 
        thresholdedDirectionChanges: overallDirectionChanges,
        ClickFrequency: clickFrequency, 
        Level: 2 
    };

    fetch('/PHP/process_level_data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        console.log(data.message); 
        console.log('Gratulujem! Dáta úspešne odoslané.');
    })
    .catch(error => {
        console.error(error);
        alert('Nastala chyba.');
    });
}


  function showLevelCompletionAlert(message) {
    alert(message);
  }
  
  startButton.addEventListener('click', startLevel);
});
