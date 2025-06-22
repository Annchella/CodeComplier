<?php
include '../includes/Navbar.php';


session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Online Code Compiler</title>
  <?php include '../includes/header.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
  <!-- SweetAlert2 for notifications -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: #181a1b;
      color: #f1f1f1;
      transition: background 0.3s, color 0.3s;
    }
    .card, .alert, .form-control, .form-select {
      border-radius: 10px !important;
    }
    #editor { height: 400px; border-radius: 8px; }
    .monaco-editor, .monaco-editor-background { border-radius: 8px; }
    #output-area { margin-top: 20px; }
    #output-card pre {
      background: #23272b;
      color: #f1f1f1;
      padding: 15px;
      border-radius: 8px;
      white-space: pre-wrap;
      font-size: 1rem;
      margin-bottom: 0;
    }
    .theme-toggle {
      position: absolute;
      top: 20px;
      right: 30px;
      z-index: 10;
    }
    .btn-copy {
      float: right;
      margin-top: -8px;
      margin-bottom: 8px;
    }
    @media (max-width: 600px) {
      #editor { height: 220px !important; }
      .theme-toggle { right: 10px; top: 10px; }
    }
    /* Light mode */
    body.light-mode {
      background: #f8f9fa !important;
      color: #222 !important;
    }
    body.light-mode #output-card pre {
      background: #f4f4f4;
      color: #222;
    }
  </style>
</head>
<body class="p-4">
  <button id="themeToggle" class="btn btn-dark theme-toggle"><i class="fas fa-moon"></i></button>
  <div class="container">
    <h3 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?> 👋</h3>

    <div class="mb-3 row">
      <div class="col-md-4">
        <label for="language" class="form-label">Choose Language:</label>
        <select id="language" class="form-select">
          <option value="54">C++</option>
          <option value="62">Java</option>
          <option value="71" selected>Python</option>
          <option value="63">JavaScript</option>
        </select>
      </div>
      <div class="col-md-8 text-end align-self-end">
        <button id="run" class="btn btn-success"><i class="fas fa-play"></i> Run</button>
      </div>
    </div>

    <textarea id="code" hidden>print("Hello, World!")</textarea>
    <div id="editor" class="border mb-3"></div>

    <div class="mb-3">
      <label class="form-label">Custom Input:</label>
      <textarea id="input" class="form-control" placeholder="Enter input for your program..."></textarea>
    </div>

    <div id="output-area">
      <div id="output-card"></div>
    </div>
  </div>

  <script>
    // Monaco Editor Setup
    require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' }});
    window.MonacoEnvironment = { getWorkerUrl: () => proxy };
    let proxy = URL.createObjectURL(new Blob([`
      self.MonacoEnvironment = {baseUrl: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/'};
      importScripts('https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/base/worker/workerMain.js');
    `], { type: 'text/javascript' }));

    let editor;
    let currentTheme = 'vs-dark';
    require(["vs/editor/editor.main"], function () {
      editor = monaco.editor.create(document.getElementById('editor'), {
        value: document.getElementById('code').value,
        language: 'python',
        theme: currentTheme,
        automaticLayout: true,
        fontSize: 16,
        minimap: { enabled: false }
      });
    });

    // Theme Toggle
    document.getElementById('themeToggle').onclick = function() {
      document.body.classList.toggle('light-mode');
      currentTheme = document.body.classList.contains('light-mode') ? 'vs-light' : 'vs-dark';
      monaco.editor.setTheme(currentTheme);
      this.classList.toggle('btn-dark');
      this.classList.toggle('btn-light');
      this.innerHTML = document.body.classList.contains('light-mode')
        ? '<i class="fas fa-sun"></i>'
        : '<i class="fas fa-moon"></i>';
    };

    // Language Selector Logic
    document.getElementById('language').addEventListener('change', function () {
      const langMap = {
        '54': 'cpp',
        '62': 'java',
        '71': 'python',
        '63': 'javascript'
      };
      const templates = {
        '54': '#include <iostream>\nint main() {\n    std::cout << "Hello, World!";\n    return 0;\n}',
        '62': 'public class Main {\n    public static void main(String[] args) {\n        System.out.println("Hello, World!");\n    }\n}',
        '71': 'print("Hello, World!")',
        '63': 'console.log("Hello, World!");'
      };
      monaco.editor.setModelLanguage(editor.getModel(), langMap[this.value]);
      editor.setValue(templates[this.value]);
    });

    // Run Button Logic
    document.getElementById('run').addEventListener('click', function () {
      const code = editor.getValue();
      const input = document.getElementById('input').value;
      const language = document.getElementById('language').value;
      const outputCard = document.getElementById('output-card');

      outputCard.innerHTML = `
        <div class="alert alert-secondary text-center">
          <i class="fas fa-spinner fa-spin"></i> Running...
        </div>
      `;

      fetch('Run.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code, input, language })
      })
      .then(response => response.json())
      .then(data => {
        let statusClass = 'primary';
        let statusIcon = 'fa-info-circle';
        if (data.status && data.status.toLowerCase().includes('accepted')) {
          statusClass = 'success';
          statusIcon = 'fa-check-circle';
        } else if (data.status && (data.status.toLowerCase().includes('error') || data.status.toLowerCase().includes('failed'))) {
          statusClass = 'danger';
          statusIcon = 'fa-times-circle';
        } else if (data.status && data.status.toLowerCase().includes('compil')) {
          statusClass = 'warning';
          statusIcon = 'fa-exclamation-triangle';
        }

        outputCard.innerHTML = `
          <div class="card shadow-sm" id="output-card-inner">
            <div class="card-header bg-${statusClass} text-white">
              <i class="fas ${statusIcon}"></i>
              <strong>Status:</strong> ${data.status}
            </div>
            <div class="card-body">
              <div class="mb-2">
                <span class="badge bg-primary"><i class="fas fa-clock"></i> Time: ${data.time} sec</span>
                <span class="badge bg-info text-dark ms-2"><i class="fas fa-memory"></i> Memory: ${data.memory} KB</span>
                <button class="btn btn-outline-secondary btn-sm btn-copy" id="copyOutput"><i class="fas fa-copy"></i> Copy Output</button>
              </div>
              <pre id="outputText">${data.output}</pre>
            </div>
          </div>
        `;

        // Copy output feature with SweetAlert2 feedback
        document.getElementById('copyOutput').onclick = function() {
          const text = document.getElementById('outputText').innerText;
          navigator.clipboard.writeText(text);
          Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Output copied to clipboard.',
            timer: 1200,
            showConfirmButton: false,
            background: document.body.classList.contains('light-mode') ? '#fff' : '#23272b',
            color: document.body.classList.contains('light-mode') ? '#222' : '#f1f1f1'
          });
        };
      })
      .catch(err => {
        outputCard.innerHTML = `
          <div class="alert alert-danger">
            <strong>Error:</strong> ${err.message}
          </div>
        `;
      });
    });
  </script>
</body>
</html>
