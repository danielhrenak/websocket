<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hra "Klikacie kruhy"</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        #gameContainer {
            width: 400px;
            height: 400px;
            background-color: #333;
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .circle {
            width: 40px;
            height: 40px;
            background-color: #ff5733;
            border-radius: 50%;
            position: absolute;
            cursor: pointer;
        }

        #score, #timer {
            position: absolute;
            top: 10px;
            font-size: 18px;
        }

        #score {
            left: 10px;
        }

        #timer {
            right: 10px;
        }
    </style>
</head>
<body>
<div id="button-box">
    <button id="start-game">Start game</button>
</div>
<div id="result-box" style="display: none">
    <h2>Game Over!</h2>
    <p>Your score: <span id="final-score"></span></p>
</div>
<div id="gameContainer">
    <div id="score">Skóre: 0</div>
    <div id="timer">Čas: 5</div>
</div>

<script>
    const gameContainer = document.getElementById('gameContainer');
    const scoreDisplay = document.getElementById('score');
    const timerDisplay = document.getElementById('timer');

    let score = 0;
    let timeLeft = 5;
    let gameInterval, timerInterval;

    function createCircle() {
        const circle = document.createElement('div');
        circle.classList.add('circle');

        // Nastavenie náhodnej pozície pre kruh
        const x = Math.floor(Math.random() * (gameContainer.offsetWidth - 40));
        const y = Math.floor(Math.random() * (gameContainer.offsetHeight - 40));
        circle.style.left = x + 'px';
        circle.style.top = y + 'px';

        // Pridanie udalosti kliknutia na kruh
        circle.addEventListener('click', () => {
            score++;
            scoreDisplay.textContent = 'Skóre: ' + score;
            circle.remove();
        });

        // Pridanie kruhu do hracej plochy
        gameContainer.appendChild(circle);

        // Odstránenie kruhu po 1 sekunde, ak hráč neklikol
        setTimeout(() => {
            circle.remove();
        }, 1000);
    }

    function startGame() {
        // Každých 1,5 sekundy sa vytvorí nový kruh
        gameInterval = setInterval(createCircle, 1500);

        // Odpočet času
        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = 'Čas: ' + timeLeft;
            if (timeLeft === 0) endGame();
        }, 1000);
    }

    function endGame() {
        clearInterval(gameInterval);
        clearInterval(timerInterval);
        document.getElementById('button-box').style.display = 'block';
        document.getElementById('result-box').style.display = 'block';
        document.getElementById('final-score').textContent = score;
    }

    function resetGame() {
        score = 0;
        timeLeft = 5;
        scoreDisplay.textContent = 'Skóre: ' + score;
        timerDisplay.textContent = 'Čas: ' + timeLeft;
        gameContainer.innerHTML = '<div id="score">Skóre: 0</div><div id="timer">Čas: 5</div>';
        startGame();
    }

    document.getElementById('start-game').addEventListener('click', () => {
        resetGame();
        document.getElementById('button-box').style.display = 'none';
    });

</script>
</body>
</html>
