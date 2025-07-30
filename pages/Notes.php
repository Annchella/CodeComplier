<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
$username = htmlspecialchars($_SESSION['user']['username']);
include('../includes/Navbar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Notes for Students - CodeSpace</title>
    
    <!-- Student-Friendly Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --java-color: #f89820;
            --python-color: #3776ab;
            --cpp-color: #00599c;
            --js-color: #f7df1e;
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            margin-top: 70px;
        }

        /* Student-friendly header */
        .student-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .student-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="books" width="20" height="20" patternUnits="userSpaceOnUse"><text x="10" y="15" font-size="12" fill="rgba(255,255,255,0.1)" text-anchor="middle">📚</text></pattern></defs><rect width="100" height="100" fill="url(%23books)"/></svg>');
        }

        /* Language tabs */
        .language-tabs {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 10px;
            margin: -30px auto 40px;
            max-width: 600px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 15px 20px;
            border-radius: 15px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 0 5px;
            cursor: pointer;
        }

        .tab-btn.active {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .tab-btn.java.active { background: var(--java-color); }
        .tab-btn.python.active { background: var(--python-color); }
        .tab-btn.cpp.active { background: var(--cpp-color); }
        .tab-btn.javascript.active { background: var(--js-color); color: #333; }

        .tab-btn:hover:not(.active) {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary);
        }

        /* Tab content - FIXED: Hide inactive tabs */
        .tab-content {
            position: relative;
        }

        .tab-pane {
            display: none; /* Hide all tabs by default */
            animation: fadeIn 0.5s ease-in-out;
        }

        .tab-pane.active {
            display: block; /* Show only active tab */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Note cards */
        .note-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .note-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topic-card {
            background: #f8fafc;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary);
            transition: all 0.3s ease;
        }

        .topic-card:hover {
            background: #f1f5f9;
            transform: translateX(5px);
        }

        .topic-title {
            font-size: 18px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .topic-description {
            color: #64748b;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Code examples */
        .code-example {
            background: #1e293b;
            border-radius: 12px;
            overflow: hidden;
            margin: 15px 0;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .code-header {
            background: #334155;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #475569;
        }

        .code-dots {
            display: flex;
            gap: 6px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot.red { background: #ef4444; }
        .dot.yellow { background: #f59e0b; }
        .dot.green { background: #10b981; }

        .code-lang {
            color: #94a3b8;
            font-size: 12px;
            font-weight: 500;
        }

        .code-content {
            padding: 20px;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
            line-height: 1.6;
            color: #e2e8f0;
            overflow-x: auto;
        }

        /* Difficulty levels */
        .difficulty {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .difficulty.beginner {
            background: #dcfce7;
            color: #166534;
        }

        .difficulty.intermediate {
            background: #fef3c7;
            color: #92400e;
        }

        .difficulty.advanced {
            background: #fecaca;
            color: #991b1b;
        }

        /* Key points */
        .key-points {
            background: #eff6ff;
            border-radius: 12px;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3b82f6;
        }

        .key-points h4 {
            color: #1e40af;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .key-points ul {
            color: #1e40af;
            padding-left: 20px;
        }

        .key-points li {
            margin-bottom: 5px;
        }

        /* Search functionality */
        .search-container {
            position: relative;
            max-width: 500px;
            margin: 0 auto 30px;
        }

        .search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: none;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .student-header {
                padding: 60px 0 40px;
            }
            
            .language-tabs {
                margin: -20px 15px 30px;
            }
            
            .tab-btn {
                padding: 12px 15px;
                font-size: 14px;
            }
            
            .note-section {
                padding: 20px;
                margin: 0 15px 20px;
            }
        }
    </style>
</head>

<body>
    <?php include_once "../includes/Navbar.php"; ?>
    
    <!-- Student Header -->
    <div class="student-header">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6" data-aos="fade-up">
                    Student <span class="text-yellow-300">Programming Notes</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-90" data-aos="fade-up" data-aos-delay="200">
                    Learn Java, Python, C++, and JavaScript with easy examples
                </p>
                <div class="flex justify-center space-x-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">📖 Easy to Understand</span>
                    </div>
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">💡 Practical Examples</span>
                    </div>
                    <div class="bg-white bg-opacity-20 px-6 py-3 rounded-full">
                        <span class="font-semibold">🎯 Student Focused</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-6 pb-20">
        <!-- Search Section -->
        <div class="search-container" data-aos="fade-up">
            <input type="text" class="search-input" placeholder="Search programming concepts..." id="searchInput">
            <i class="fas fa-search search-icon"></i>
        </div>

        <!-- Language Tabs -->
        <div class="language-tabs" data-aos="fade-up" data-aos-delay="200">
            <div class="flex flex-wrap justify-center">
                <button class="tab-btn java active" data-tab="java">
                    <i class="fab fa-java mr-2"></i>Java
                </button>
                <button class="tab-btn python" data-tab="python">
                    <i class="fab fa-python mr-2"></i>Python
                </button>
                <button class="tab-btn cpp" data-tab="cpp">
                    <i class="fas fa-code mr-2"></i>C++
                </button>
                <button class="tab-btn javascript" data-tab="javascript">
                    <i class="fab fa-js-square mr-2"></i>JavaScript
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Java Tab - ACTIVE BY DEFAULT -->
            <div id="java" class="tab-pane active">
                <div class="note-section" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fab fa-java" style="color: var(--java-color);"></i>
                        Java Fundamentals
                    </h2>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-play-circle"></i>
                            Getting Started with Java
                        </h3>
                        <p class="topic-description">
                            Java is a popular programming language used for building applications. It's known for being "write once, run anywhere".
                        </p>
                        
                        <div class="key-points">
                            <h4><i class="fas fa-lightbulb"></i>Key Points:</h4>
                            <ul>
                                <li>Java is object-oriented and platform-independent</li>
                                <li>Every Java program starts with a main method</li>
                                <li>Java code is compiled into bytecode</li>
                            </ul>
                        </div>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">Java</div>
                            </div>
                            <div class="code-content">
<pre><code>public class HelloWorld {
    public static void main(String[] args) {
        System.out.println("Hello, World!");
        System.out.println("Welcome to Java!");
    }
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-database"></i>
                            Variables and Data Types
                        </h3>
                        <p class="topic-description">
                            Variables are containers that store data. Java has different types for different kinds of data.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">Java</div>
                            </div>
                            <div class="code-content">
<pre><code>public class Variables {
    public static void main(String[] args) {
        int age = 20;                    // Whole numbers
        double height = 5.8;             // Decimal numbers
        String name = "Alice";           // Text
        boolean isStudent = true;        // True or False
        
        System.out.println("Name: " + name);
        System.out.println("Age: " + age);
        System.out.println("Height: " + height);
    }
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Python Tab - HIDDEN BY DEFAULT -->
            <div id="python" class="tab-pane">
                <div class="note-section" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fab fa-python" style="color: var(--python-color);"></i>
                        Python Fundamentals
                    </h2>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-play-circle"></i>
                            Welcome to Python
                        </h3>
                        <p class="topic-description">
                            Python is known for being easy to read and write. It's like writing in plain English!
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">Python</div>
                            </div>
                            <div class="code-content">
<pre><code># Your first Python program
print("Hello, World!")
print("Welcome to Python!")

# Variables - no type declaration needed!
name = "Emma"
age = 18
height = 5.6

print(f"Hi, I'm {name} and I'm {age} years old!")</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-list-ul"></i>
                            Lists in Python
                        </h3>
                        <p class="topic-description">
                            Lists are like magical containers that can hold different types of data.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">Python</div>
                            </div>
                            <div class="code-content">
<pre><code># Creating lists
fruits = ["apple", "banana", "orange"]
numbers = [1, 2, 3, 4, 5]

# Adding items
fruits.append("strawberry")
print("Fruits:", fruits)

# Looping through lists
for fruit in fruits:
    print(f"- {fruit}")</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- C++ Tab - HIDDEN BY DEFAULT -->
            <div id="cpp" class="tab-pane">
                <div class="note-section" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fas fa-code" style="color: var(--cpp-color);"></i>
                        C++ Fundamentals
                    </h2>

                    <div class="topic-card">
                        <div class="difficulty intermediate">🟡 Intermediate</div>
                        <h3 class="topic-title">
                            <i class="fas fa-rocket"></i>
                            Getting Started with C++
                        </h3>
                        <p class="topic-description">
                            C++ is a powerful language that gives you control over performance and memory.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">C++</div>
                            </div>
                            <div class="code-content">
<pre><code>#include &lt;iostream&gt;
#include &lt;string&gt;

using namespace std;

int main() {
    cout &lt;&lt; "Hello, World!" &lt;&lt; endl;
    
    int age = 20;
    string name = "Alex";
    
    cout &lt;&lt; "Name: " &lt;&lt; name &lt;&lt; endl;
    cout &lt;&lt; "Age: " &lt;&lt; age &lt;&lt; endl;
    
    return 0;
}</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="topic-card">
                        <div class="difficulty intermediate">🟡 Intermediate</div>
                        <h3 class="topic-title">
                            <i class="fas fa-layer-group"></i>
                            Vectors in C++
                        </h3>
                        <p class="topic-description">
                            Vectors are dynamic arrays that can grow and shrink as needed.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">C++</div>
                            </div>
                            <div class="code-content">
<pre><code>#include &lt;iostream&gt;
#include &lt;vector&gt;

using namespace std;

int main() {
    vector&lt;int&gt; numbers = {1, 2, 3, 4, 5};
    numbers.push_back(6);
    
    cout &lt;&lt; "Numbers: ";
    for(int num : numbers) {
        cout &lt;&lt; num &lt;&lt; " ";
    }
    
    return 0;
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript Tab - HIDDEN BY DEFAULT -->
            <div id="javascript" class="tab-pane">
                <div class="note-section" data-aos="fade-up">
                    <h2 class="section-title">
                        <i class="fab fa-js-square" style="color: var(--js-color);"></i>
                        JavaScript Fundamentals
                    </h2>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-globe"></i>
                            JavaScript - The Web Language
                        </h3>
                        <p class="topic-description">
                            JavaScript makes websites interactive! It runs in your web browser.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">JavaScript</div>
                            </div>
                            <div class="code-content">
<pre><code>// Your first JavaScript program
console.log("Hello, World!");

// Variables
let name = "Emma";
const age = 19;
let isStudent = true;

// Arrays
let courses = ["Math", "Science", "English"];

console.log("Student:", name);
console.log("Courses:", courses);</code></pre>
                            </div>
                        </div>
                    </div>

                    <div class="topic-card">
                        <div class="difficulty beginner">🟢 Beginner</div>
                        <h3 class="topic-title">
                            <i class="fas fa-function"></i>
                            Functions in JavaScript
                        </h3>
                        <p class="topic-description">
                            Functions are reusable blocks of code that perform specific tasks.
                        </p>

                        <div class="code-example">
                            <div class="code-header">
                                <div class="code-dots">
                                    <div class="dot red"></div>
                                    <div class="dot yellow"></div>
                                    <div class="dot green"></div>
                                </div>
                                <div class="code-lang">JavaScript</div>
                            </div>
                            <div class="code-content">
<pre><code>// Function declaration
function greetStudent(name) {
    return "Hello, " + name + "!";
}

// Arrow function (modern way)
const calculateGrade = (score) => {
    if (score >= 90) return "A";
    if (score >= 80) return "B";
    return "C";
};

// Using functions
console.log(greetStudent("Alice"));
console.log("Grade:", calculateGrade(85));</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });

        // FIXED: Proper tab functionality
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Hide all tab panes
                document.querySelectorAll('.tab-pane').forEach(pane => {
                    pane.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding tab pane
                const tabId = this.getAttribute('data-tab');
                const targetTab = document.getElementById(tabId);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
                
                // Scroll to top smoothly
                window.scrollTo({ 
                    top: 0, 
                    behavior: 'smooth' 
                });
                
                console.log('Switched to:', tabId); // Debug log
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const topicCards = document.querySelectorAll('.topic-card');
            
            topicCards.forEach(card => {
                const title = card.querySelector('.topic-title').textContent.toLowerCase();
                const description = card.querySelector('.topic-description').textContent.toLowerCase();
                const codeContent = card.querySelector('.code-content');
                const code = codeContent ? codeContent.textContent.toLowerCase() : '';
                
                if (title.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    code.includes(searchTerm) || 
                    searchTerm === '') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Debug: Log current active tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = document.querySelector('.tab-pane.active');
            console.log('Initial active tab:', activeTab ? activeTab.id : 'none');
        });
    </script>
</body>
</html>
