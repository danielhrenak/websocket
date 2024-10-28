<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Závodná hra s prekážkami</title>
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
            height: 600px;
            background-color: #333;
            position: relative;
            overflow: hidden;
        }

        #player {
            width: 50px;
            height: 50px;
            background-color: #4CAF50;
            position: absolute;
            bottom: 20px;
            left: 175px;
            border-radius: 5px;
        }

        .obstacle {
            width: 50px;
            height: 50px;
            background-color: #ff5733;
            position: absolute;
            top: -60px;
            border-radius: 5px;
        }

        #score {
            position: absolute;
            top: 10px;
            left: 10px;
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body>
<div id="gameContainer">
    <div id="player"></div>
    <div id="score">Score: 0</div>
</div>

<script>
    const gameContainer = document.getElementById('gameContainer');
    const player = document.getElementById('player');
    const scoreDisplay = document.getElementById('score');

    let playerX = 175;
    let score = 0;
    let obstacles = [];
    let gameOver = false;

    function createObstacle() {
        const obstacle = document.createElement('div');
        obstacle.classList.add('obstacle');
        obstacle.style.left = Math.floor(Math.random() * (gameContainer.offsetWidth - 50)) + 'px';
        gameContainer.appendChild(obstacle);
        obstacles.push(obstacle);
    }

    function moveObstacles() {
        obstacles.forEach((obstacle, index) => {
            const obstacleY = parseInt(obstacle.style.top) || 0;
            obstacle.style.top = obstacleY + 1 + 'px';

            // Skontroluje, či došlo ku kolízii
            if (obstacleY + 50 >= gameContainer.offsetHeight - 70 &&
                parseInt(obstacle.style.left) < playerX + 50 &&
                parseInt(obstacle.style.left) + 50 > playerX) {
                gameOver = true;
                alert('Koniec hry! Skóre: ' + score);
                resetGame();
            }

            // Odstráni prekážky mimo obrazovky
            if (obstacleY > gameContainer.offsetHeight) {
                obstacle.remove();
                obstacles.splice(index, 1);
                score++;
                scoreDisplay.textContent = 'Score: ' + score;
            }
        });
    }

    function resetGame() {
        score = 0;
        obstacles.forEach(obstacle => obstacle.remove());
        obstacles = [];
        playerX = 175;
        gameOver = false;
        scoreDisplay.textContent = 'Score: ' + score;
    }

    function gameLoop() {
        if (!gameOver) {
            moveObstacles();
            requestAnimationFrame(gameLoop);
        }
    }

    // Vytvára nové prekážky každých 1,5 sekundy
    setInterval(() => {
        if (!gameOver) createObstacle();
    }, 1500);

    // Pohyb hráča pomocou klávesov
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft' && playerX > 0) playerX -= 20;
        if (e.key === 'ArrowRight' && playerX < gameContainer.offsetWidth - 50) playerX += 20;
        player.style.left = playerX + 'px';
    });

    // Spustenie hry
    gameLoop();
</script>
</body>
</html>
