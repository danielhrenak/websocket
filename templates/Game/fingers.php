<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fastest Finger Game</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; flex-direction: column; align-items: center; }
        #gameContainer { margin-top: 20px; }
        #prompt { font-size: 24px; }
        #button { margin-top: 20px; padding: 10px 20px; font-size: 18px; }
        #scoreboard { margin-top: 20px; font-size: 18px; }
    </style>
</head>
<body>
<h1>Fastest Finger Game</h1>
<div id="gameContainer">
    <div id="prompt">Waiting for the next round...</div>
    <button id="button" disabled>PRESS!</button>
    <div id="scoreboard">Scoreboard will appear here.</div>
</div>

<script>
    const playerId = Math.floor(Math.random() * 1000); // Random ID for each player
    const ws = new WebSocket('ws://localhost:8080');

    let canClick = false;

    ws.onopen = () => {
        ws.send(JSON.stringify({ type: 'join', playerId }));
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);

        if (data.type === 'startRound') {
            document.getElementById('prompt').textContent = "Get Ready...";
            document.getElementById('button').disabled = true;
            canClick = false;
        }

        if (data.type === 'pressNow') {
            document.getElementById('prompt').textContent = "PRESS NOW!";
            document.getElementById('button').disabled = false;
            canClick = true;
        }

        if (data.type === 'roundResult') {
            document.getElementById('prompt').textContent =
                `Player ${data.winner} was the fastest!`;
            document.getElementById('button').disabled = true;
        }

        if (data.type === 'playerJoined') {
            document.getElementById('scoreboard').textContent =
                `Player ${data.playerId} joined the game!`;
        }
    };

    document.getElementById('button').addEventListener('click', () => {
        if (canClick) {
            ws.send(JSON.stringify({ type: 'click', playerId }));
            canClick = false;
        }
    });
</script>
</body>
</html>
