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
    <title>Programming Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .tab-content { margin-top: 20px; }
        .accordion-button:not(.collapsed) { background-color: #0d6efd; color: white; }
        body.dark-mode { background-color: #121212; color: #eee; }
        .dark-mode .accordion-button { background-color: #1f1f1f; color: #eee; }
        .dark-mode .accordion-body { background-color: #252525; color: #ccc; }
    </style>
</head>
<body class="bg-light" style="margin-top:70px;">
    <div class="container mt-5">
        <h2 class="text-center mb-4"><i class="fas fa-book-open"></i> Programming Language Notes</h2>
        <!-- Tabs -->
        <ul class="nav nav-tabs" id="notesTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="java-tab" data-bs-toggle="tab" data-bs-target="#java" type="button">Java</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="python-tab" data-bs-toggle="tab" data-bs-target="#python" type="button">Python</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="cpp-tab" data-bs-toggle="tab" data-bs-target="#cpp" type="button">C++</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="js-tab" data-bs-toggle="tab" data-bs-target="#js" type="button">JavaScript</button>
            </li>
        </ul>
        <!-- Notes Content -->
        <div class="tab-content" id="notesTabContent">
            <!-- Java Notes -->
            <div class="tab-pane fade show active" id="java" role="tabpanel">
                <div class="accordion" id="javaNotes">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="java1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#javaCollapse1">
                                Introduction to Java
                            </button>
                        </h2>
                        <div id="javaCollapse1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                Java is a high-level, object-oriented programming language. It runs on the Java Virtual Machine (JVM) and is known for portability across platforms using "Write Once, Run Anywhere".
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="java2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#javaCollapse2">
                                Basic Syntax
                            </button>
                        </h2>
                        <div id="javaCollapse2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <pre>
public class Main {
    public static void main(String[] args) {
        System.out.println("Hello, World!");
    }
}
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="java3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#javaCollapse3">
                                OOPs Concepts
                            </button>
                        </h2>
                        <div id="javaCollapse3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Java supports Object-Oriented Programming concepts like Classes, Objects, Inheritance, Polymorphism, Abstraction, and Encapsulation.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="java4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#javaCollapse4">
                                Collections Framework
                            </button>
                        </h2>
                        <div id="javaCollapse4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                The Collections Framework provides data structures like List, Set, Map, and Queue to store and manipulate groups of objects efficiently.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Python Notes -->
            <div class="tab-pane fade" id="python" role="tabpanel">
                <div class="accordion" id="pythonNotes">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="python1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pythonCollapse1">
                                Introduction to Python
                            </button>
                        </h2>
                        <div id="pythonCollapse1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                Python is a high-level, interpreted language with dynamic typing and easy syntax. It is widely used in web development, data science, automation, and more.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="python2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pythonCollapse2">
                                Basic Syntax
                            </button>
                        </h2>
                        <div id="pythonCollapse2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <pre>
def greet():
    print("Hello, World!")

greet()
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="python3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pythonCollapse3">
                                Data Structures
                            </button>
                        </h2>
                        <div id="pythonCollapse3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Python has built-in data structures like List, Tuple, Set, and Dictionary for storing collections of data.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="python4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pythonCollapse4">
                                Functions and Modules
                            </button>
                        </h2>
                        <div id="pythonCollapse4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                Functions are defined using <code>def</code>. Modules are files containing Python code that can be imported using <code>import</code>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- C++ Notes -->
            <div class="tab-pane fade" id="cpp" role="tabpanel">
                <div class="accordion" id="cppNotes">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="cpp1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cppCollapse1">
                                Introduction to C++
                            </button>
                        </h2>
                        <div id="cppCollapse1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                C++ is an extension of the C programming language. It supports both procedural and object-oriented programming and is commonly used in game development, systems programming, and competitive coding.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="cpp2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cppCollapse2">
                                Basic Syntax
                            </button>
                        </h2>
                        <div id="cppCollapse2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <pre>
#include &lt;iostream&gt;
using namespace std;

int main() {
    cout &lt;&lt; "Hello, World!" &lt;&lt; endl;
    return 0;
}
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="cpp3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cppCollapse3">
                                STL (Standard Template Library)
                            </button>
                        </h2>
                        <div id="cppCollapse3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                STL provides useful classes like vector, map, set, and algorithms for efficient data manipulation.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="cpp4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cppCollapse4">
                                OOP in C++
                            </button>
                        </h2>
                        <div id="cppCollapse4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                C++ supports classes, objects, inheritance, polymorphism, encapsulation, and abstraction.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- JavaScript Notes -->
            <div class="tab-pane fade" id="js" role="tabpanel">
                <div class="accordion" id="jsNotes">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="js1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#jsCollapse1">
                                Introduction to JavaScript
                            </button>
                        </h2>
                        <div id="jsCollapse1" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                JavaScript is a scripting language used to make web pages interactive. It can run in the browser and on the server (Node.js). It supports functional and object-oriented styles.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="js2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#jsCollapse2">
                                Basic Syntax
                            </button>
                        </h2>
                        <div id="jsCollapse2" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <pre>
function greet() {
    console.log("Hello, World!");
}
greet();
                                </pre>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="js3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#jsCollapse3">
                                DOM Manipulation
                            </button>
                        </h2>
                        <div id="jsCollapse3" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                JavaScript can select and modify HTML elements using methods like <code>getElementById</code>, <code>querySelector</code>, and can change content, style, and attributes dynamically.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="js4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#jsCollapse4">
                                ES6 Features
                            </button>
                        </h2>
                        <div id="jsCollapse4" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                ES6 introduced features like <code>let</code> and <code>const</code>, arrow functions, template literals, destructuring, and classes.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
