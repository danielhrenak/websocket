<!DOCTYPE html>
<html>
<head>
    <title>Real-Time Chat Application</title>
    <!-- Add your CSS styles here -->
</head>
<body>
<div id="chat-box">
    <div id="chat-messages"></div>
    <div id="user-input">
        <input type="text" id="message-input" placeholder="Type your message here...">
        <button id="send-button">Send</button>
    </div>
</div>
<script>
    const socket = new WebSocket('ws://localhost:8080');
    // Handle WebSocket connection
    socket.onopen = () => {
        console.log('WebSocket connected');
    };
    // Handle incoming messages
    socket.onmessage = (event) => {
        const message = JSON.parse(event.data);
        appendMessage(message.user_id, message.content, 'received');
    };
    // Send messages to the server
    document.getElementById('send-button').addEventListener('click', () => {
        const messageInput = document.getElementById('message-input');
        const messageContent = messageInput.value.trim();
        if (messageContent !== '') {
            const message = {
                user_id: 1, // Replace with the actual user ID
                content: messageContent,
            };
            socket.send(JSON.stringify(message));
            appendMessage(message.user_id, messageContent, 'sent');
            messageInput.value = '';
        }
    });
    // Function to display messages on the chat interface
    function appendMessage(userId, content, messageType) {
        const chatMessages = document.getElementById('chat-messages');
        const messageDiv = document.createElement('div');
        messageDiv.className = messageType;
        messageDiv.innerText = `User ${userId}: ${content}`;
        chatMessages.appendChild(messageDiv);
    }
</script>
</body>
</html>
