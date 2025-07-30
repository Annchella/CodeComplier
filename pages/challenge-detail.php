<?php
session_start();
require_once '../includes/db.php';

// Authentication check
if (!isset($_SESSION['user'])) {
    header('Location: ../auth/login.php');
    exit;
}
$user_id = $_SESSION['user']['id'];

// Fetch and validate challenge
$challenge_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM challenges WHERE id = ?');
$stmt->execute([$challenge_id]);
$challenge = $stmt->fetch();

if (!$challenge) {
    header('Location: challenges.php');
    exit;
}

$test_cases = json_decode($challenge['test_cases'], true) ?? [];

// Now safe to include HTML output files
include '../includes/Header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($challenge['title']) ?> - Challenge</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- CodeMirror CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css" rel="stylesheet">
    
    <style>
        .challenge-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
        }
        
        /* FIXED: Proper CodeMirror sizing with !important */
        .CodeMirror {
            width: 100% !important;
            height: 650px !important;
            min-height: 650px !important;
            max-height: 800px !important;
            font-size: 18px !important;
            line-height: 1.5 !important;
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace !important;
            border: 2px solid #ddd !important;
            border-radius: 12px !important;
            background: #282a36 !important;
        }
        
        .CodeMirror-scroll {
            height: 650px !important;
            min-height: 650px !important;
        }
        
        .CodeMirror-lines {
            padding: 15px 0 !important;
        }
        
        .CodeMirror-line {
            padding: 0 12px !important;
            line-height: 1.5 !important;
        }
        
        .CodeMirror-gutter {
            min-height: 650px !important;
        }
        
        .CodeMirror-sizer {
            min-height: 650px !important;
        }
        
        .CodeMirror-cursor {
            border-left: 2px solid #f8f8f2 !important;
        }
        
        .test-case {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #007bff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .test-case:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        
        .output-section {
            background: #2d3748;
            color: #fff;
            border-radius: 10px;
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            max-height: 400px;
            overflow-y: auto;
            font-size: 16px;
            line-height: 1.4;
            white-space: pre-wrap;
        }
        
        .btn-run {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-run:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }
        
        .btn-submit {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.4);
        }
        
        .constraint-item {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #17a2b8;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .editor-controls {
            background: #343a40;
            padding: 1rem;
            border-radius: 10px 10px 0 0;
            border-bottom: 1px solid #495057;
        }
        
        .language-selector {
            background: #495057;
            color: white;
            border: 1px solid #6c757d;
            border-radius: 6px;
            padding: 0.5rem;
            font-size: 14px;
        }
        
        .language-selector:focus {
            background: #6c757d;
            color: white;
            border-color: #007bff;
        }
        
        @media (max-width: 768px) {
            .CodeMirror {
                height: 500px !important;
                min-height: 500px !important;
                font-size: 16px !important;
            }
            
            .CodeMirror-scroll {
                height: 500px !important;
                min-height: 500px !important;
            }
        }
    </style>
</head>
<body>
    <!-- Challenge Header -->
    <div class="challenge-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-3"><?= htmlspecialchars($challenge['title']) ?></h1>
                    <div class="d-flex flex-wrap gap-3">
                        <span class="badge bg-<?= 
                            $challenge['difficulty'] === 'easy' ? 'success' : 
                            ($challenge['difficulty'] === 'medium' ? 'warning' : 'danger') ?> fs-6 px-3 py-2">
                            <i class="fas fa-layer-group me-2"></i><?= ucfirst($challenge['difficulty']) ?>
                        </span>
                        <span class="badge bg-light text-dark fs-6 px-3 py-2">
                            <i class="fas fa-coins me-2"></i><?= $challenge['points'] ?> points
                        </span>
                        <?php if ($challenge['category']): ?>
                            <span class="badge bg-info fs-6 px-3 py-2">
                                <i class="fas fa-tags me-2"></i><?= htmlspecialchars($challenge['category']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="challenges.php" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Back to Challenges
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Problem Description -->
            <div class="col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Problem Description
                        </h5>
                    </div>
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-file-alt me-2"></i>Description
                        </h6>
                        <p class="lh-lg"><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>

                        <?php if (!empty($test_cases)): ?>
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-vial me-2"></i>Test Cases
                            </h6>
                            <?php foreach ($test_cases as $index => $test_case): ?>
                                <div class="test-case">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-play me-2"></i>Example <?= $index + 1 ?>
                                    </h6>
                                    <div class="mb-2">
                                        <strong class="text-success">Input:</strong> 
                                        <code class="bg-light p-2 rounded d-block mt-1"><?= htmlspecialchars($test_case['input']) ?></code>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-info">Expected Output:</strong> 
                                        <code class="bg-light p-2 rounded d-block mt-1"><?= htmlspecialchars($test_case['output']) ?></code>
                                    </div>
                                    <?php if (isset($test_case['explanation'])): ?>
                                        <div class="text-muted">
                                            <strong>Explanation:</strong> 
                                            <span class="d-block mt-1"><?= htmlspecialchars($test_case['explanation']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div class="mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cogs me-2"></i>Constraints
                            </h6>
                            <div class="constraint-item">
                                <i class="fas fa-clock text-info me-2"></i>
                                Time limit: <strong><?= $challenge['time_limit'] ?>ms</strong>
                            </div>
                            <div class="constraint-item">
                                <i class="fas fa-memory text-warning me-2"></i>
                                Memory limit: <strong><?= $challenge['memory_limit'] ?>MB</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code Editor -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="editor-controls">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-white">
                                <i class="fas fa-code me-2"></i>Code Editor
                            </h5>
                            <div class="d-flex gap-2">
                                <select id="languageSelect" class="form-select form-select-sm language-selector">
                                    <option value="javascript">JavaScript</option>
                                    <option value="python">Python</option>
                                    <option value="java">Java</option>
                                    <option value="cpp">C++</option>
                                </select>
                                
                                <select id="fontSizeSelect" class="form-select form-select-sm language-selector">
                                    <option value="16">16px</option>
                                    <option value="18" selected>18px</option>
                                    <option value="20">20px</option>
                                    <option value="22">22px</option>
                                </select>
                                
                                <select id="themeSelect" class="form-select form-select-sm language-selector">
                                    <option value="dracula" selected>Dracula</option>
                                    <option value="monokai">Monokai</option>
                                    <option value="default">Default</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <textarea id="codeEditor" name="code"></textarea>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <button type="button" id="runCode" class="btn btn-run">
                                <i class="fas fa-play me-2"></i>Run Code
                            </button>
                            <button type="button" id="submitCode" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>Submit Solution
                            </button>
                            <button type="button" id="resetCode" class="btn btn-outline-secondary">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                            <button type="button" id="formatCode" class="btn btn-outline-info">
                                <i class="fas fa-magic me-2"></i>Format
                            </button>
                        </div>
                        
                        <div id="output" style="display: none;">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-terminal me-2"></i>Output:
                            </h6>
                            <div id="outputContent" class="output-section"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/python/python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closebrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/matchbrackets.min.js"></script>

    <script>
        // Language-specific starter code templates
        const starterCodeTemplates = {
            javascript: `function solution(input) {
    // Write your JavaScript solution here
    // Example: return input.split(' ').map(Number);
    
}

// Test your function
console.log(solution("test input"));`,
            
            python: `def solution(input_data):
    # Write your Python solution here
    # Example: return list(map(int, input_data.split()))
    pass

# Test your function
print(solution("test input"))`,
            
            java: `public class Solution {
    public static void main(String[] args) {
        // Write your Java solution here
        System.out.println("Hello World!");
    }
    
    public static String solution(String input) {
        // Your solution logic here
        return input;
    }
}`,
            
            cpp: `#include <iostream>
#include <vector>
#include <string>
using namespace std;

string solution(string input) {
    // Write your C++ solution here
    return input;
}

int main() {
    // Test your function
    cout << solution("test input") << endl;
    return 0;
}`
        };

        // Language modes for CodeMirror
        const languageModes = {
            javascript: 'javascript',
            python: 'python',
            java: 'text/x-java',
            cpp: 'text/x-c++src'
        };

        // Initialize CodeMirror
        const editor = CodeMirror.fromTextArea(document.getElementById('codeEditor'), {
            lineNumbers: true,
            theme: 'dracula',
            mode: 'javascript',
            indentUnit: 4,
            tabSize: 4,
            autoCloseBrackets: true,
            matchBrackets: true,
            lineWrapping: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete",
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });

        // Set initial code and size
        editor.setValue(starterCodeTemplates.javascript);
        editor.setSize('100%', 650);

        // Force refresh after initialization
        setTimeout(() => {
            editor.refresh();
            editor.setSize('100%', 650);
        }, 100);

        // Language selector
        document.getElementById('languageSelect').addEventListener('change', function() {
            const language = this.value;
            editor.setOption('mode', languageModes[language]);
            
            // Load saved code or use template
            const savedCode = localStorage.getItem(`challenge_${<?= $challenge_id ?>}_${language}`);
            if (savedCode) {
                editor.setValue(savedCode);
            } else {
                editor.setValue(starterCodeTemplates[language]);
            }
            
            editor.refresh();
        });

        // Theme selector
        document.getElementById('themeSelect').addEventListener('change', function() {
            editor.setOption('theme', this.value);
            editor.refresh();
        });

        // Font size selector
        document.getElementById('fontSizeSelect').addEventListener('change', function() {
            const fontSize = this.value + 'px';
            document.querySelector('.CodeMirror').style.fontSize = fontSize;
            editor.refresh();
        });

        // Run code
        document.getElementById('runCode').addEventListener('click', function() {
            const code = editor.getValue().trim();
            const language = document.getElementById('languageSelect').value;
            
            if (!code) {
                alert('Please write some code first!');
                return;
            }
            
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Running...';
            this.disabled = true;
            
            setTimeout(() => {
                document.getElementById('output').style.display = 'block';
                document.getElementById('outputContent').textContent = 
                    `Language: ${language.toUpperCase()}\n` +
                    `Status: Code executed successfully!\n\n` +
                    `Your Code:\n${code}\n\n` +
                    `Note: Implement actual ${language} execution on server`;
                
                this.innerHTML = originalHtml;
                this.disabled = false;
            }, 1000);
        });

        // Submit code
        document.getElementById('submitCode').addEventListener('click', function() {
            const code = editor.getValue().trim();
            const language = document.getElementById('languageSelect').value;
            
            if (!code) {
                alert('Please write some code first!');
                return;
            }
            
            if (confirm(`Submit your ${language} solution?`)) {
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';
                this.disabled = true;
                
                setTimeout(() => {
                    alert('🎉 Solution submitted successfully!');
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }, 1500);
            }
        });

        // Reset code
        document.getElementById('resetCode').addEventListener('click', function() {
            const language = document.getElementById('languageSelect').value;
            if (confirm('Reset your code? This cannot be undone.')) {
                editor.setValue(starterCodeTemplates[language]);
                localStorage.removeItem(`challenge_${<?= $challenge_id ?>}_${language}`);
            }
        });

        // Format code
        document.getElementById('formatCode').addEventListener('click', function() {
            const code = editor.getValue();
            const language = document.getElementById('languageSelect').value;
            
            // Basic formatting based on language
            let formatted = code;
            if (language === 'javascript' || language === 'java' || language === 'cpp') {
                formatted = code.replace(/;/g, ';\n').replace(/{/g, '{\n').replace(/}/g, '\n}');
            }
            
            editor.setValue(formatted);
        });

        // Auto-save functionality
        editor.on('change', function() {
            const language = document.getElementById('languageSelect').value;
            const code = editor.getValue();
            localStorage.setItem(`challenge_${<?= $challenge_id ?>}_${language}`, code);
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            setTimeout(() => {
                editor.refresh();
                editor.setSize('100%', 650);
            }, 100);
        });

        // Load saved code on page load
        window.addEventListener('load', function() {
            const language = document.getElementById('languageSelect').value;
            const savedCode = localStorage.getItem(`challenge_${<?= $challenge_id ?>}_${language}`);
            if (savedCode) {
                editor.setValue(savedCode);
            }
        });
    </script>
</body>
</html>
