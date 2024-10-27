<?php
/**
 * @var \App\View\AppView $this
 * @var array $scores
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Clicker Game</title>
    <style>
        #game-box {
            text-align: center;
            margin-top: 50px;
        }
        #click-button {
            padding: 20px;
            font-size: 20px;
        }
        #scores {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div id="login-box">
    <input type="text" id="username-input" placeholder="Enter your username">
    <button id="login-button">Login</button>
</div>
<div id="game-box" style="display: none">
    <button id="click-button" data-username="">Click Me!</button>
    <div id="scores">
        <?php foreach ($scores as $score): ?>
            <div id="player-<?= $score['username'] ?>"><?= $score['username'] ?>: <?= $score['score'] ?></div>
        <?php endforeach; ?>
    </div>
</div>
<script>
    const socket = new WebSocket('ws://localhost:8080');
    socket.onopen = () => {
        console.log('WebSocket connected');
    };
    socket.onmessage = (event) => {
        const message = JSON.parse(event.data);
        console.log('Message received:', message);
        if (message.action === 'login' && message.status === 'success') {
            document.getElementById('login-box').style.display = 'none';
            document.getElementById('game-box').style.display = 'block';
        } else if (message.action === 'click') {
            updateScore(message.username, message.score);
        }
    };
    document.getElementById('login-button').addEventListener('click', () => {
        const usernameInput = document.getElementById('username-input');
        const username = usernameInput.value.trim();
        if (username !== '') {
            const message = {
                action: 'login',
                username: username
            };
            document.getElementById('click-button').dataset.username = username;
            console.log('Sending login message:', message);
            socket.send(JSON.stringify(message));
        }
    });
    document.getElementById('click-button').addEventListener('click', () => {
        const username = document.getElementById('click-button').dataset.username;
        const message = {
            action: 'click',
            username: username
        };
        console.log('Sending click message:', message);
        socket.send(JSON.stringify(message));
    });
    function updateScore(username, score) {
        const scoresDiv = document.getElementById('scores');
        let playerScoreDiv = document.getElementById('player-' + username);
        console.log('Updating score:', username, score);
        console.log(playerScoreDiv);
        if (!playerScoreDiv) {
            playerScoreDiv = document.createElement('div');
            playerScoreDiv.id = 'player-' + username;
            scoresDiv.appendChild(playerScoreDiv);
        }
        playerScoreDiv.innerText = `${username}: ${score}`;
    }
</script>
</body>
</html>
