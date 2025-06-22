<?php
include 'includes/Navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>AI Chatbot</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f4f4; }
    .chat-container {
        max-width: 700px;
        margin: 100px auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        height: 80vh;
        display: flex;
        flex-direction: column;
    }
    .chat-box {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 20px;
        padding-right: 10px;
    }
    .message {
        margin: 10px 0;
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 80%;
    }
    .user-msg {
        background: #d1e7dd;
        align-self: flex-end;
    }
    .bot-msg {
        background: #e2e3e5;
        align-self: flex-start;
    }
  </style>
</head>
<body>

<div class="chat-container">
  <div id="chatBox" class="chat-box"></div>
  <form id="chatForm">
    <div class="input-group">
      <input type="text" id="userInput" class="form-control" placeholder="Ask something..." required>
      <button type="submit" class="btn btn-primary">Send</button>
    </div>
  </form>
</div>

<script>
const chatBox = document.getElementById('chatBox');
const chatForm = document.getElementById('chatForm');
const userInput = document.getElementById('userInput');

chatForm.onsubmit = async (e) => {
    e.preventDefault();
    const userMsg = userInput.value.trim();
    if (!userMsg) return;

    addMessage(userMsg, 'user-msg');
    userInput.value = '';

    // Simulate bot response
    const botReply = await getBotResponse(userMsg);
    addMessage(botReply, 'bot-msg');
};

function addMessage(text, className) {
    const msg = document.createElement('div');
    msg.className = `message ${className}`;
    msg.textContent = text;
    chatBox.appendChild(msg);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function getBotResponse(msg) {
    // Simulated answers - can be expanded or replaced with real API
    if (msg.toLowerCase().includes("php"))
        return "PHP is a server-side scripting language used to develop dynamic websites.";
    else if (msg.toLowerCase().includes("hello"))
        return "Hello! How can I help you today?";
    else if (msg.toLowerCase().includes("compiler"))
        return "You can try our compiler from the menu!";
    else
        return "I'm still learning. Try asking something else!";
}
</script>

</body>
</html>
