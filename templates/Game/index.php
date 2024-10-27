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
<div id="game-box">
    <button id="click-button">Click Me!</button>
    <div id="scores"></div>
</div>
<script>
    const socket = new WebSocket('ws://localhost:8080');
    socket.onopen = () => {
        console.log('WebSocket connected');
    };
    socket.onmessage = (event) => {
        const message = JSON.parse(event.data);
        console.log('Message received:', message);
        updateScore(message.player_id, message.score);
    };
    document.getElementById('click-button').addEventListener('click', () => {
        const message = {
            action: 'click'
        };
        console.log('click');
        socket.send(JSON.stringify(message));
    });
    function updateScore(playerId, score) {
        const scoresDiv = document.getElementById('scores');
        let playerScoreDiv = document.getElementById('player-' + playerId);
        if (!playerScoreDiv) {
            playerScoreDiv = document.createElement('div');
            playerScoreDiv.id = 'player-' + playerId;
            scoresDiv.appendChild(playerScoreDiv);
        }
        playerScoreDiv.innerText = `Player ${playerId}: ${score}`;
    }
</script>
</body>
</html>
