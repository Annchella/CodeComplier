<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Compiler AI Assistant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #chat-container {
            width: 450px;
            height: 650px;
            background: #fff;
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
        }

        #chat-header {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: white;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
        }

        .bot-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 18px;
        }

        .header-info h2 {
            font-size: 18px;
            font-weight: 600;
        }

        .header-info p {
            font-size: 12px;
            opacity: 0.8;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .header-actions button {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .header-actions button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        #chat-box {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .msg {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            display: inline-block;
            animation: fadeIn 0.3s ease;
            position: relative;
        }

        .user {
            align-self: flex-end;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .bot {
            align-self: flex-start;
            background: #ffffff;
            color: #333;
            border: 1px solid #e0e0e0;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .msg-time {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 5px;
            text-align: right;
        }

        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .suggestion-chip {
            background: #f0f2f5;
            border: none;
            border-radius: 16px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
        }

        .suggestion-chip:hover {
            background: #e4e6e9;
        }

        #input-area {
            display: flex;
            padding: 15px;
            border-top: 1px solid #e0e0e0;
            background: #fff;
            align-items: center;
        }

        #input-area i {
            color: #1e3c72;
            margin: 0 10px;
            cursor: pointer;
        }

        #user-input {
            flex: 1;
            border: none;
            padding: 12px 15px;
            border-radius: 24px;
            background: #f0f2f5;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }

        #user-input:focus {
            background: #e8eaed;
        }

        #send-btn {
            background: #1e3c72;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        #send-btn:hover {
            background: #152a52;
            transform: scale(1.05);
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #e0e0e0;
            align-self: flex-start;
            width: fit-content;
            margin-bottom: 5px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #999;
            border-radius: 50%;
            margin: 0 2px;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }

        .welcome-message {
            text-align: center;
            padding: 15px;
            background: #fff;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .welcome-message h3 {
            color: #1e3c72;
            margin-bottom: 8px;
        }

        .welcome-message p {
            color: #666;
            font-size: 13px;
            margin-bottom: 12px;
        }

        .code-block {
            background: #2d2d2d;
            color: #f8f8f2;
            border-radius: 8px;
            padding: 12px;
            margin: 8px 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            overflow-x: auto;
            white-space: pre-wrap;
            border-left: 4px solid #1e3c72;
        }

        .language-tag {
            display: inline-block;
            background: #1e3c72;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            margin-bottom: 5px;
        }

        .language-selector {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .lang-btn {
            background: #e9ecef;
            border: none;
            border-radius: 16px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.2s;
        }

        .lang-btn.active {
            background: #1e3c72;
            color: white;
        }

        .lang-btn:hover {
            background: #d8dbe0;
        }

        .lang-btn.active:hover {
            background: #152a52;
        }

        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(5px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>

<div id="chat-container">
    <div id="chat-header">
        <div class="header-left">
            <div class="bot-avatar">
                <i class="fas fa-code"></i>
            </div>
            <div class="header-info">
                <h2>Code Compiler AI</h2>
                <p>Online • Ready to compile</p>
            </div>
        </div>
        <div class="header-actions">
            <button id="theme-toggle"><i class="fas fa-moon"></i></button>
            <button id="clear-chat"><i class="fas fa-trash"></i></button>
        </div>
    </div>
    
    <div id="chat-box">
        <div class="welcome-message">
            <h3>Welcome to Code Compiler AI! 👨‍💻</h3>
            <p>I can help you with code compilation, debugging, and programming concepts in multiple languages.</p>
            <div class="language-selector">
                <button class="lang-btn active" data-lang="all">All Languages</button>
                <button class="lang-btn" data-lang="python">Python</button>
                <button class="lang-btn" data-lang="java">Java</button>
                <button class="lang-btn" data-lang="cpp">C++</button>
                <button class="lang-btn" data-lang="js">JavaScript</button>
                <button class="lang-btn" data-lang="php">PHP</button>
            </div>
            <div class="suggestions">
                <button class="suggestion-chip">Compile a program</button>
                <button class="suggestion-chip">Show me syntax examples</button>
                <button class="suggestion-chip">Debug my code</button>
                <button class="suggestion-chip">Language comparison</button>
            </div>
        </div>
    </div>
    
    <div id="input-area">
        <i class="fas fa-code" id="insert-code"></i>
        <input type="text" id="user-input" placeholder="Ask about code compilation, syntax, or debugging..." />
        <i class="fas fa-microphone"></i>
        <button id="send-btn">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
const chatBox = document.getElementById('chat-box');
const userInput = document.getElementById('user-input');
const sendBtn = document.getElementById('send-btn');
const themeToggle = document.getElementById('theme-toggle');
const clearChatBtn = document.getElementById('clear-chat');
const suggestionChips = document.querySelectorAll('.suggestion-chip');
const langButtons = document.querySelectorAll('.lang-btn');
const insertCodeBtn = document.getElementById('insert-code');

// Programming language examples and responses
const languageExamples = {
    python: {
        hello: `print("Hello, World!")`,
        factorial: `def factorial(n):
    if n == 0:
        return 1
    else:
        return n * factorial(n-1)

print(factorial(5))`,
        syntax: `# Variables
x = 5
name = "Python"

# Lists
my_list = [1, 2, 3, 4, 5]

# Loops
for i in range(5):
    print(i)

# Functions
def greet(name):
    return f"Hello, {name}!"`
    },
    java: {
        hello: `public class HelloWorld {
    public static void main(String[] args) {
        System.out.println("Hello, World!");
    }
}`,
        factorial: `public class Factorial {
    public static int factorial(int n) {
        if (n == 0) {
            return 1;
        } else {
            return n * factorial(n - 1);
        }
    }
    
    public static void main(String[] args) {
        System.out.println(factorial(5));
    }
}`,
        syntax: `// Variables
int x = 5;
String name = "Java";

// Arrays
int[] myArray = {1, 2, 3, 4, 5};

// Loops
for (int i = 0; i < 5; i++) {
    System.out.println(i);
}

// Methods
public static String greet(String name) {
    return "Hello, " + name + "!";
}`
    },
    cpp: {
        hello: `#include <iostream>
using namespace std;

int main() {
    cout << "Hello, World!" << endl;
    return 0;
}`,
        factorial: `#include <iostream>
using namespace std;

int factorial(int n) {
    if (n == 0) {
        return 1;
    } else {
        return n * factorial(n - 1);
    }
}

int main() {
    cout << factorial(5) << endl;
    return 0;
}`,
        syntax: `// Variables
int x = 5;
string name = "C++";

// Arrays
int myArray[] = {1, 2, 3, 4, 5};

// Loops
for (int i = 0; i < 5; i++) {
    cout << i << endl;
}

// Functions
string greet(string name) {
    return "Hello, " + name + "!";
}`
    },
    js: {
        hello: `console.log("Hello, World!");`,
        factorial: `function factorial(n) {
    if (n === 0) {
        return 1;
    } else {
        return n * factorial(n - 1);
    }
}

console.log(factorial(5));`,
        syntax: `// Variables
let x = 5;
const name = "JavaScript";

// Arrays
const myArray = [1, 2, 3, 4, 5];

// Loops
for (let i = 0; i < 5; i++) {
    console.log(i);
}

// Functions
function greet(name) {
    return \`Hello, \${name}!\`;
}`
    },
    php: {
        hello: `<?php
echo "Hello, World!";
?>`,
        factorial: `<?php
function factorial($n) {
    if ($n == 0) {
        return 1;
    } else {
        return $n * factorial($n - 1);
    }
}

echo factorial(5);
?>`,
        syntax: `<?php
// Variables
$x = 5;
$name = "PHP";

// Arrays
$myArray = array(1, 2, 3, 4, 5);

// Loops
for ($i = 0; $i < 5; $i++) {
    echo $i;
}

// Functions
function greet($name) {
    return "Hello, " . $name . "!";
}
?>`
    }
};

// Compiler responses and information
const responses = {
    "hi": "Hello! 👋 I'm your Code Compiler AI. How can I help with your programming today?",
    "hello": "Hey there! 😊 Ready to write some code?",
    "how are you": "I'm running smoothly today, compiling code in my virtual circuits! How about you?",
    "bye": "Happy coding! 👨‍💻 Come back if you need help with your programs!",
    "thanks": "You're welcome! 🙌 Keep coding and let me know if you need more help.",
    "compile": "I can help you understand compilation processes and show you how to compile code in different languages. Which language are you working with?",
    "python": `Python is an interpreted language, so it doesn't need compilation in the traditional sense. You can run Python code directly using the Python interpreter.\n\nExample: <span class="language-tag">Python</span>\n<div class="code-block">python my_script.py</div>`,
    "java": `Java code needs to be compiled to bytecode before execution.\n\nCompilation: <span class="language-tag">Java</span>\n<div class="code-block">javac MyProgram.java</div>\n\nExecution: <span class="language-tag">Java</span>\n<div class="code-block">java MyProgram</div>`,
    "c++": `C++ is a compiled language that produces machine code.\n\nCompilation: <span class="language-tag">C++</span>\n<div class="code-block">g++ -o myprogram mycode.cpp</div>\n\nExecution: <span class="language-tag">C++</span>\n<div class="code-block">./myprogram</div>`,
    "javascript": `JavaScript is an interpreted language that runs in browsers or Node.js environments.\n\nFor browsers: Include in HTML\n<div class="code-block">&lt;script src="myscript.js"&gt;&lt;/script&gt;</div>\n\nFor Node.js: <span class="language-tag">JavaScript</span>\n<div class="code-block">node myscript.js</div>`,
    "php": `PHP is a server-side scripting language that's interpreted by the PHP engine.\n\nExecution: <span class="language-tag">PHP</span>\n<div class="code-block">php my_script.php</div>\n\nOr through a web server when accessed via HTTP.`,
    "debug": "I can help you debug common issues! Tell me about the error you're getting or share your code snippet.",
    "syntax": "I can show you syntax examples for different programming languages. Which language are you interested in?",
    "hello world": "Here's 'Hello, World!' in different languages:",
    "factorial": "Here's how to implement a factorial function in different languages:",
    "compiler error": "Common compiler errors include syntax errors, type mismatches, undefined variables, and missing imports. Share your error message for specific help!",
    "what can you do": "I can help with:\n• Code compilation processes\n• Syntax examples in Python, Java, C++, JavaScript, PHP\n• Debugging tips\n• Language comparisons\n• Code optimization suggestions\n• Explaining programming concepts",
    "help": "I'm here to help with all things code compilation and programming! You can ask me about:\n• How to compile code in different languages\n• Syntax examples\n• Debugging assistance\n• Programming concepts\n• Language comparisons\n\nWhat would you like to know?"
};

// Add message to chat area
function appendMessage(text, sender) {
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('msg', sender);
    
    // Check if text contains code blocks and format them
    if (text.includes('<div class="code-block">')) {
        msgDiv.innerHTML = text;
    } else {
        const textDiv = document.createElement('div');
        textDiv.innerHTML = text.replace(/\n/g, '<br>');
        msgDiv.appendChild(textDiv);
    }
    
    const timeDiv = document.createElement('div');
    timeDiv.classList.add('msg-time');
    timeDiv.textContent = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    msgDiv.appendChild(timeDiv);
    
    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Show typing indicator
function showTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.classList.add('typing-indicator');
    typingDiv.id = 'typing-indicator';
    
    for (let i = 0; i < 3; i++) {
        const dot = document.createElement('div');
        dot.classList.add('typing-dot');
        typingDiv.appendChild(dot);
    }
    
    chatBox.appendChild(typingDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Hide typing indicator
function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Get bot response with enhanced logic for programming questions
function getBotResponse(input) {
    const key = input.toLowerCase().trim();
    
    // Check for exact matches first
    if (responses[key]) {
        return responses[key];
    }
    
    // Check for language-specific requests
    if (key.includes("python")) {
        if (key.includes("hello") || key.includes("hello world")) {
            return `Here's a Python "Hello, World!" program:\n<span class="language-tag">Python</span>\n<div class="code-block">${languageExamples.python.hello}</div>`;
        } else if (key.includes("factorial")) {
            return `Here's a factorial function in Python:\n<span class="language-tag">Python</span>\n<div class="code-block">${languageExamples.python.factorial}</div>`;
        } else if (key.includes("syntax")) {
            return `Here are some Python syntax examples:\n<span class="language-tag">Python</span>\n<div class="code-block">${languageExamples.python.syntax}</div>`;
        }
        return responses.python;
    }
    
    if (key.includes("java")) {
        if (key.includes("hello") || key.includes("hello world")) {
            return `Here's a Java "Hello, World!" program:\n<span class="language-tag">Java</span>\n<div class="code-block">${languageExamples.java.hello}</div>`;
        } else if (key.includes("factorial")) {
            return `Here's a factorial function in Java:\n<span class="language-tag">Java</span>\n<div class="code-block">${languageExamples.java.factorial}</div>`;
        } else if (key.includes("syntax")) {
            return `Here are some Java syntax examples:\n<span class="language-tag">Java</span>\n<div class="code-block">${languageExamples.java.syntax}</div>`;
        }
        return responses.java;
    }
    
    if (key.includes("c++") || key.includes("cpp")) {
        if (key.includes("hello") || key.includes("hello world")) {
            return `Here's a C++ "Hello, World!" program:\n<span class="language-tag">C++</span>\n<div class="code-block">${languageExamples.cpp.hello}</div>`;
        } else if (key.includes("factorial")) {
            return `Here's a factorial function in C++:\n<span class="language-tag">C++</span>\n<div class="code-block">${languageExamples.cpp.factorial}</div>`;
        } else if (key.includes("syntax")) {
            return `Here are some C++ syntax examples:\n<span class="language-tag">C++</span>\n<div class="code-block">${languageExamples.cpp.syntax}</div>`;
        }
        return responses["c++"];
    }
    
    if (key.includes("javascript") || key.includes("js")) {
        if (key.includes("hello") || key.includes("hello world")) {
            return `Here's a JavaScript "Hello, World!" program:\n<span class="language-tag">JavaScript</span>\n<div class="code-block">${languageExamples.js.hello}</div>`;
        } else if (key.includes("factorial")) {
            return `Here's a factorial function in JavaScript:\n<span class="language-tag">JavaScript</span>\n<div class="code-block">${languageExamples.js.factorial}</div>`;
        } else if (key.includes("syntax")) {
            return `Here are some JavaScript syntax examples:\n<span class="language-tag">JavaScript</span>\n<div class="code-block">${languageExamples.js.syntax}</div>`;
        }
        return responses.javascript;
    }
    
    if (key.includes("php")) {
        if (key.includes("hello") || key.includes("hello world")) {
            return `Here's a PHP "Hello, World!" program:\n<span class="language-tag">PHP</span>\n<div class="code-block">${languageExamples.php.hello}</div>`;
        } else if (key.includes("factorial")) {
            return `Here's a factorial function in PHP:\n<span class="language-tag">PHP</span>\n<div class="code-block">${languageExamples.php.factorial}</div>`;
        } else if (key.includes("syntax")) {
            return `Here are some PHP syntax examples:\n<span class="language-tag">PHP</span>\n<div class="code-block">${languageExamples.php.syntax}</div>`;
        }
        return responses.php;
    }
    
    // Check for partial matches
    for (const [question, answer] of Object.entries(responses)) {
        if (key.includes(question)) {
            return answer;
        }
    }
    
    // Special responses for programming concepts
    if (key.includes("hello world")) {
        let response = "Here's 'Hello, World!' in different languages:\n\n";
        response += `<span class="language-tag">Python</span>\n<div class="code-block">${languageExamples.python.hello}</div>\n\n`;
        response += `<span class="language-tag">Java</span>\n<div class="code-block">${languageExamples.java.hello}</div>\n\n`;
        response += `<span class="language-tag">C++</span>\n<div class="code-block">${languageExamples.cpp.hello}</div>`;
        return response;
    }
    
    if (key.includes("factorial")) {
        let response = "Here's how to implement a factorial function:\n\n";
        response += `<span class="language-tag">Python</span>\n<div class="code-block">${languageExamples.python.factorial}</div>\n\n`;
        response += `<span class="language-tag">Java</span>\n<div class="code-block">${languageExamples.java.factorial}</div>`;
        return response;
    }
    
    // Default response for unrecognized input
    return "I'm not sure I understand. Could you rephrase your question about code compilation or programming? Try asking about a specific language (Python, Java, C++, JavaScript, PHP) or a programming concept.";
}

// Handle sending messages
function handleSend() {
    const text = userInput.value.trim();
    if (!text) return;
    
    appendMessage(text, 'user');
    userInput.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate thinking time
    setTimeout(() => {
        hideTypingIndicator();
        appendMessage(getBotResponse(text), 'bot');
    }, 1000 + Math.random() * 1000);
}

// Event listeners
sendBtn.addEventListener('click', handleSend);
userInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') handleSend();
});

// Theme toggle functionality
themeToggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme');
    const icon = themeToggle.querySelector('i');
    if (document.body.classList.contains('dark-theme')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
        document.body.style.background = 'linear-gradient(135deg, #0f1b3d, #1a2b5f)';
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
        document.body.style.background = 'linear-gradient(135deg, #1e3c72, #2a5298)';
    }
});

// Clear chat functionality
clearChatBtn.addEventListener('click', () => {
    if (confirm('Are you sure you want to clear the chat?')) {
        // Keep only the welcome message
        const welcomeMessage = document.querySelector('.welcome-message');
        chatBox.innerHTML = '';
        chatBox.appendChild(welcomeMessage);
    }
});

// Suggestion chips functionality
suggestionChips.forEach(chip => {
    chip.addEventListener('click', () => {
        userInput.value = chip.textContent;
        handleSend();
    });
});

// Language selector functionality
langButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        langButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        
        const lang = btn.getAttribute('data-lang');
        if (lang !== 'all') {
            userInput.value = `Tell me about ${lang}`;
            handleSend();
        }
    });
});

// Insert code button functionality
insertCodeBtn.addEventListener('click', () => {
    userInput.value = "How do I compile a program?";
    handleSend();
});

// Add initial bot message on load
window.addEventListener('load', () => {
    setTimeout(() => {
        appendMessage("Hi there! I'm your Code Compiler AI assistant. I can help you with code compilation, debugging, and programming concepts in Python, Java, C++, JavaScript, and PHP. What would you like to know?", 'bot');
    }, 500);
});
</script>

</body>
</html>