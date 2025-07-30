
<!DOCTYPE html>
<html>
<head>
  <title>CodeRunner - Online Compiler</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --bg-primary: #1a1a1a;
      --bg-secondary: #262626;
      --bg-tertiary: #2d2d2d;
      --bg-hover: #3d3d3d;
      --border-color: #404040;
      --text-primary: #ffffff;
      --text-secondary: #a6a6a6;
      --text-muted: #737373;
      --accent-green: #00b894;
      --accent-blue: #0984e3;
      --accent-orange: #e17055;
      --accent-red: #d63031;
      --accent-yellow: #fdcb6e;
      --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background: var(--bg-primary);
      color: var(--text-primary);
      height: 100vh;
      overflow: hidden;
    }

    /* Header */
    .header {
      background: var(--bg-secondary);
      border-bottom: 1px solid var(--border-color);
      padding: 0.75rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      position: relative;
      z-index: 100;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--accent-green);
      text-decoration: none;
    }

    .user-info {
      color: var(--text-secondary);
      font-size: 0.9rem;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .theme-btn {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      border-radius: 6px;
      padding: 0.5rem 0.75rem;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .theme-btn:hover {
      background: var(--bg-hover);
      color: var(--text-primary);
    }

    /* Main Layout */
    .main-container {
      display: flex;
      height: calc(100vh - 60px);
    }

    .left-panel {
      width: 50%;
      background: var(--bg-primary);
      border-right: 1px solid var(--border-color);
      display: flex;
      flex-direction: column;
    }

    .right-panel {
      width: 50%;
      background: var(--bg-secondary);
      display: flex;
      flex-direction: column;
    }

    /* Control Bar */
    .control-bar {
      background: var(--bg-secondary);
      border-bottom: 1px solid var(--border-color);
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      min-height: 50px;
    }

    .language-select {
      background: var(--bg-primary);
      border: 1px solid var(--border-color);
      color: var(--text-primary);
      border-radius: 6px;
      padding: 0.5rem 0.75rem;
      font-size: 0.85rem;
      min-width: 120px;
      cursor: pointer;
    }

    .language-select:focus {
      outline: none;
      border-color: var(--accent-blue);
    }

    .run-button {
      background: var(--accent-green);
      border: none;
      color: white;
      border-radius: 6px;
      padding: 0.5rem 1.25rem;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .run-button:hover:not(:disabled) {
      background: #00a085;
      transform: translateY(-1px);
    }

    .run-button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .run-button.loading {
      background: var(--accent-orange);
    }

    /* Editor */
    .editor-container {
      flex: 1;
      position: relative;
      background: var(--bg-primary);
    }

    #editor {
      height: 100%;
      width: 100%;
    }

    /* Right Panel Tabs */
    .panel-tabs {
      background: var(--bg-tertiary);
      border-bottom: 1px solid var(--border-color);
      display: flex;
      padding: 0 1rem;
    }

    .tab-btn {
      background: transparent;
      border: none;
      color: var(--text-secondary);
      padding: 0.75rem 1rem;
      font-size: 0.85rem;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.2s;
    }

    .tab-btn.active {
      color: var(--text-primary);
      border-bottom-color: var(--accent-green);
    }

    .tab-btn:hover {
      color: var(--text-primary);
    }

    /* Tab Content */
    .tab-content {
      flex: 1;
      display: none;
      flex-direction: column;
    }

    .tab-content.active {
      display: flex;
    }

    /* Input Tab */
    .input-section {
      flex: 1;
      padding: 1rem;
    }

    .input-label {
      color: var(--text-secondary);
      font-size: 0.85rem;
      margin-bottom: 0.5rem;
      display: block;
    }

    .input-textarea {
      width: 100%;
      height: 200px;
      background: var(--bg-primary);
      border: 1px solid var(--border-color);
      border-radius: 6px;
      color: var(--text-primary);
      padding: 0.75rem;
      font-family: 'Consolas', 'Monaco', monospace;
      font-size: 0.85rem;
      resize: vertical;
      min-height: 100px;
    }

    .input-textarea:focus {
      outline: none;
      border-color: var(--accent-blue);
    }

    /* Output Tab */
    .output-section {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .output-header {
      background: var(--bg-tertiary);
      border-bottom: 1px solid var(--border-color);
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .status-info {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .status-badge {
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-accepted { background: rgba(0, 184, 148, 0.2); color: var(--accent-green); }
    .status-error { background: rgba(214, 48, 49, 0.2); color: var(--accent-red); }
    .status-running { background: rgba(253, 203, 110, 0.2); color: var(--accent-yellow); }

    .metrics {
      display: flex;
      gap: 1rem;
      font-size: 0.75rem;
      color: var(--text-muted);
    }

    .copy-btn {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-secondary);
      border-radius: 4px;
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
      cursor: pointer;
      transition: all 0.2s;
    }

    .copy-btn:hover {
      background: var(--bg-hover);
      color: var(--text-primary);
    }

    .output-content {
      flex: 1;
      background: var(--bg-primary);
      color: var(--text-primary);
      padding: 1rem;
      font-family: 'Consolas', 'Monaco', monospace;
      font-size: 0.85rem;
      line-height: 1.4;
      overflow-y: auto;
      white-space: pre-wrap;
      word-break: break-word;
    }

    .output-empty {
      color: var(--text-muted);
      text-align: center;
      padding: 3rem 1rem;
      font-style: italic;
    }

    /* Loading States */
    .loading-spinner {
      display: inline-block;
      width: 16px;
      height: 16px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Resizer */
    .resizer {
      width: 4px;
      background: var(--border-color);
      cursor: col-resize;
      position: relative;
      transition: background 0.2s;
    }

    .resizer:hover {
      background: var(--accent-blue);
    }

    /* Light Mode */
    body.light-mode {
      --bg-primary: #ffffff;
      --bg-secondary: #f8f9fa;
      --bg-tertiary: #f1f2f3;
      --bg-hover: #e9ecef;
      --border-color: #e1e4e8;
      --text-primary: #24292e;
      --text-secondary: #586069;
      --text-muted: #6a737d;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .main-container {
        flex-direction: column;
      }
      
      .left-panel, .right-panel {
        width: 100%;
        height: 50%;
      }
      
      .header {
        padding: 0.5rem 1rem;
      }
      
      .header-left, .header-right {
        gap: 0.5rem;
      }
      
      .user-info {
        display: none;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <div class="header">
    <div class="header-left">
      <a href="#" class="logo">CodeRunner</a>
      <div class="user-info">Welcome, Developer</div>
    </div>
    <div class="header-right">
      <button class="theme-btn" id="themeToggle">
        <i class="fas fa-moon"></i>
      </button>
    </div>
  </div>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Left Panel - Code Editor -->
    <div class="left-panel">
      <div class="control-bar">
        <select class="language-select" id="languageSelect">
          <option value="71">Python</option>
          <option value="54">C++</option>
          <option value="62">Java</option>
          <option value="63">JavaScript</option>
        </select>
        <button class="run-button" id="runButton">
          <i class="fas fa-play"></i>
          <span>Run</span>
        </button>
      </div>
      <div class="editor-container">
        <div id="editor"></div>
      </div>
    </div>

    <!-- Resizer -->
    <div class="resizer" id="resizer"></div>

    <!-- Right Panel - Input/Output -->
    <div class="right-panel">
      <div class="panel-tabs">
        <button class="tab-btn active" data-tab="input">
          <i class="fas fa-keyboard me-1"></i>Input
        </button>
        <button class="tab-btn" data-tab="output">
          <i class="fas fa-terminal me-1"></i>Output
        </button>
      </div>

      <!-- Input Tab -->
      <div class="tab-content active" id="input-tab">
        <div class="input-section">
          <label class="input-label">Custom Input</label>
          <textarea class="input-textarea" id="customInput" 
                    placeholder="Enter input for your program here..."></textarea>
        </div>
      </div>

      <!-- Output Tab -->
      <div class="tab-content" id="output-tab">
        <div class="output-section">
          <div class="output-header" id="outputHeader" style="display: none;">
            <div class="status-info">
              <span class="status-badge" id="statusBadge">Ready</span>
              <div class="metrics" id="metrics"></div>
            </div>
            <button class="copy-btn" id="copyBtn">
              <i class="fas fa-copy"></i>
            </button>
          </div>
          <div class="output-content" id="outputContent">
            <div class="output-empty">
              Click "Run" to see your program output here
            </div>
          </div>
        </div>
      </div>
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

    // Language configurations
    const languages = {
      '71': { 
        name: 'python', 
        template: '# Python Code\nprint("Hello, World!")\n\n# Your code here...' 
      },
      '54': { 
        name: 'cpp', 
        template: '#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << "Hello, World!" << endl;\n    return 0;\n}' 
      },
      '62': { 
        name: 'java', 
        template: 'public class Solution {\n    public static void main(String[] args) {\n        System.out.println("Hello, World!");\n    }\n}' 
      },
      '63': { 
        name: 'javascript', 
        template: '// JavaScript Code\nconsole.log("Hello, World!");\n\n// Your code here...' 
      }
    };

    // Initialize Monaco Editor
    require(["vs/editor/editor.main"], function () {
      editor = monaco.editor.create(document.getElementById('editor'), {
        value: languages['71'].template,
        language: 'python',
        theme: currentTheme,
        automaticLayout: true,
        fontSize: 14,
        lineHeight: 20,
        minimap: { enabled: false },
        scrollBeyondLastLine: false,
        wordWrap: 'on',
        lineNumbers: 'on',
        renderLineHighlight: 'gutter',
        selectOnLineNumbers: true,
        matchBrackets: 'always',
        folding: true,
        foldingHighlight: false,
        renderIndentGuides: true,
        occurrencesHighlight: false,
        overviewRulerBorder: false,
        hideCursorInOverviewRuler: true
      });
    });

    // Theme Toggle
    document.getElementById('themeToggle').addEventListener('click', function() {
      document.body.classList.toggle('light-mode');
      const isLight = document.body.classList.contains('light-mode');
      currentTheme = isLight ? 'vs' : 'vs-dark';
      monaco.editor.setTheme(currentTheme);
      this.innerHTML = isLight ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
    });

    // Language Selection
    document.getElementById('languageSelect').addEventListener('change', function() {
      const langId = this.value;
      const config = languages[langId];
      monaco.editor.setModelLanguage(editor.getModel(), config.name);
      editor.setValue(config.template);
    });

    // Tab Switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const tabName = this.dataset.tab;
        
        // Update active tab button
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Show corresponding tab content
        document.querySelectorAll('.tab-content').forEach(content => {
          content.classList.remove('active');
        });
        document.getElementById(tabName + '-tab').classList.add('active');
      });
    });

    // Run Code
    document.getElementById('runButton').addEventListener('click', function() {
      const code = editor.getValue().trim();
      if (!code) {
        showToast('Please write some code first!', 'warning');
        return;
      }

      const input = document.getElementById('customInput').value;
      const language = document.getElementById('languageSelect').value;
      
      // Switch to output tab
      document.querySelector('[data-tab="output"]').click();
      
      // Show loading state
      this.classList.add('loading');
      this.innerHTML = '<div class="loading-spinner"></div><span>Running</span>';
      this.disabled = true;
      
      showOutput({ status: 'running', output: 'Executing your code...' });

      // Simulate API call (replace with your actual endpoint)
      setTimeout(() => {
        const mockResult = {
          status: 'Accepted',
          output: 'Hello, World!\n\nExecution completed successfully.',
          time: '0.23',
          memory: '8420'
        };
        
        showOutput(mockResult);
        resetRunButton();
      }, 2000);

      /* Uncomment for actual API integration:
      fetch('Run.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code, input, language })
      })
      .then(response => response.json())
      .then(data => {
        showOutput(data);
        resetRunButton();
      })
      .catch(error => {
        showOutput({ 
          status: 'Error', 
          output: 'Failed to execute code: ' + error.message 
        });
        resetRunButton();
      });
      */
    });

    function resetRunButton() {
      const btn = document.getElementById('runButton');
      btn.classList.remove('loading');
      btn.innerHTML = '<i class="fas fa-play"></i><span>Run</span>';
      btn.disabled = false;
    }

    function showOutput(data) {
      const header = document.getElementById('outputHeader');
      const badge = document.getElementById('statusBadge');
      const metrics = document.getElementById('metrics');
      const content = document.getElementById('outputContent');
      
      header.style.display = 'flex';
      
      // Update status badge
      badge.className = 'status-badge';
      if (data.status.toLowerCase().includes('accept')) {
        badge.classList.add('status-accepted');
      } else if (data.status.toLowerCase().includes('error') || data.status.toLowerCase().includes('fail')) {
        badge.classList.add('status-error');
      } else if (data.status.toLowerCase().includes('run')) {
        badge.classList.add('status-running');
      }
      badge.textContent = data.status;
      
      // Update metrics
      if (data.time && data.memory) {
        metrics.innerHTML = `
          <span><i class="fas fa-clock"></i> ${data.time}s</span>
          <span><i class="fas fa-memory"></i> ${data.memory} KB</span>
        `;
      } else {
        metrics.innerHTML = '';
      }
      
      // Update content
      content.textContent = data.output || 'No output';
    }

    // Copy functionality
    document.getElementById('copyBtn').addEventListener('click', function() {
      const content = document.getElementById('outputContent').textContent;
      navigator.clipboard.writeText(content).then(() => {
        this.innerHTML = '<i class="fas fa-check"></i>';
        showToast('Output copied to clipboard!', 'success');
        setTimeout(() => {
          this.innerHTML = '<i class="fas fa-copy"></i>';
        }, 1500);
      });
    });

    // Panel Resizer
    let isResizing = false;
    
    document.getElementById('resizer').addEventListener('mousedown', function(e) {
      isResizing = true;
      document.addEventListener('mousemove', handleResize);
      document.addEventListener('mouseup', stopResize);
    });

    function handleResize(e) {
      if (!isResizing) return;
      
      const container = document.querySelector('.main-container');
      const containerRect = container.getBoundingClientRect();
      const leftPanel = document.querySelector('.left-panel');
      const rightPanel = document.querySelector('.right-panel');
      
      const percentage = ((e.clientX - containerRect.left) / containerRect.width) * 100;
      
      if (percentage > 30 && percentage < 70) {
        leftPanel.style.width = percentage + '%';
        rightPanel.style.width = (100 - percentage) + '%';
      }
    }

    function stopResize() {
      isResizing = false;
      document.removeEventListener('mousemove', handleResize);
      document.removeEventListener('mouseup', stopResize);
    }

    // Toast notifications
    function showToast(message, type = 'info') {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 2000,
        background: document.body.classList.contains('light-mode') ? '#fff' : '#2d2d2d',
        color: document.body.classList.contains('light-mode') ? '#24292e' : '#ffffff'
      });
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('runButton').click();
      }
    });
  </script>
</body>
</html>