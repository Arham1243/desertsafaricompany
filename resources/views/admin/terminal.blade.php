<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Artisan Terminal</title>
    <style>
        :root {
            --bg: #0b0f14;
            --panel: #10151d;
            --muted: #9aa4b2;
            --border: #283546;
            /* slightly lighter for more prominent edges */
            --textarea: #0e141b;
            --accent: #3b82f6;
            --accent-strong: #2563eb;
            --text: #e6edf3;
        }

        html,
        body {
            height: 100%;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 980px;
            margin: 48px auto;
            padding: 0 18px;
        }

        .card {
            position: relative;
            background: var(--panel);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .header {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .title {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .terminal-pane {
            position: relative;
        }

        .terminal-body {
            background: var(--textarea);
            border: 1.25px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            min-height: 320px;
            max-height: 60vh;
            overflow: auto;
            color: var(--text);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            line-height: 1.55;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
        }

        .terminal-output {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .terminal-line {
            margin-bottom: 2px;
        }

        .terminal-prompt {
            display: flex;
            align-items: center;
            margin-bottom: 2px;
        }

        .prompt-path {
            color: #81c784;
            font-weight: 500;
        }

        .prompt-symbol {
            color: var(--text);
            margin: 0 8px 0 5px;
        }

        .terminal-input {
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: inherit;
            font-size: inherit;
            flex: 1;
            caret-color: #93c5fd;
        }

        .command-output {
            color: #e0e0e0;
            margin: 5px 0;
            padding-left: 0;
        }

        .command-echo {
            color: #9aa4b2;
            margin-bottom: 5px;
        }

        .error-output {
            color: #ff6b6b;
        }

        .success-output {
            color: #51cf66;
        }

        .info-output {
            color: #74c0fc;
        }

        /* Scrollbar styling */
        .terminal-body::-webkit-scrollbar {
            width: 8px;
        }

        .terminal-body::-webkit-scrollbar-track {
            background: #0f1720;
        }

        .terminal-body::-webkit-scrollbar-thumb {
            background: #283546;
            border-radius: 4px;
        }

        .terminal-body::-webkit-scrollbar-thumb:hover {
            background: #324256;
        }

        /* Hint */
        .artisan-hint {
            color: var(--muted);
            margin-bottom: 8px;
            font-size: 12px;
        }

        /* Selection */
        ::selection {
            background: #4a90e2;
            color: #fff;
        }

        ::-moz-selection {
            background: #4a90e2;
            color: #fff;
        }

        .terminal-output ::selection {
            background: #4a90e2;
            color: #fff;
        }

        .terminal-output ::-moz-selection {
            background: #4a90e2;
            color: #fff;
        }

        @media (max-width: 640px) {
            .container {
                margin: 28px auto;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title">Artisan Terminal</h1>
            </div>
            <div class="terminal-pane">
                <div class="terminal-body" id="terminal-body">
                    <div class="artisan-hint">This terminal will only execute PHP Artisan commands</div>
                    <div class="terminal-output" id="terminal-output"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        class MacTerminal {
            constructor() {
                this.terminalBody = document.getElementById('terminal-body');
                this.terminalOutput = document.getElementById('terminal-output');
                this.commandHistory = [];
                this.historyIndex = -1;
                this.currentInput = null;

                this.init();
            }

            init() {
                this.createPrompt();
                this.setupEventListeners();
            }

            setupEventListeners() {
                this.terminalBody.addEventListener('click', (e) => {
                    if (e.target === this.terminalBody || e.target.closest('.terminal-prompt')) {
                        if (this.currentInput) {
                            this.currentInput.focus();
                        }
                    }
                });
            }

            createPrompt() {
                const promptDiv = document.createElement('div');
                promptDiv.className = 'terminal-prompt';

                promptDiv.innerHTML = `
                    <span class="prompt-path">~</span>
                    <span class="prompt-symbol">$</span>
                `;

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'terminal-input';
                input.spellcheck = false;
                input.autocomplete = 'off';

                promptDiv.appendChild(input);
                this.terminalOutput.appendChild(promptDiv);

                this.currentInput = input;
                input.focus();

                this.setupInputHandlers(input);
                this.scrollToBottom();
            }

            setupInputHandlers(input) {
                input.addEventListener('keydown', (e) => {
                    switch (e.key) {
                        case 'Enter':
                            e.preventDefault();
                            this.executeCommand(input.value.trim());
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            this.navigateHistory('up', input);
                            break;
                        case 'ArrowDown':
                            e.preventDefault();
                            this.navigateHistory('down', input);
                            break;
                        case 'Tab':
                            e.preventDefault();
                            // Could implement tab completion here
                            break;
                    }
                });
            }

            navigateHistory(direction, input) {
                if (this.commandHistory.length === 0) return;

                if (direction === 'up') {
                    if (this.historyIndex === -1) {
                        this.historyIndex = this.commandHistory.length - 1;
                    } else if (this.historyIndex > 0) {
                        this.historyIndex--;
                    }
                } else {
                    if (this.historyIndex < this.commandHistory.length - 1) {
                        this.historyIndex++;
                    } else {
                        this.historyIndex = -1;
                        input.value = '';
                        return;
                    }
                }

                input.value = this.commandHistory[this.historyIndex] || '';
                // Move cursor to end
                setTimeout(() => {
                    input.setSelectionRange(input.value.length, input.value.length);
                }, 0);
            }

            async executeCommand(command) {
                if (!command) {
                    this.createPrompt();
                    return;
                }

                // Add to history
                if (command !== this.commandHistory[this.commandHistory.length - 1]) {
                    this.commandHistory.push(command);
                }
                this.historyIndex = -1;

                // Disable current input
                this.currentInput.disabled = true;
                this.currentInput.style.opacity = '0.7';

                // Check if it's an artisan command
                if (this.isArtisanCommand(command)) {
                    await this.executeArtisanCommand(command);
                } else {
                    this.handleNonArtisanCommand(command);
                }

                this.createPrompt();
            }

            isArtisanCommand(command) {
                return /^php\s+artisan\s+/.test(command.toLowerCase()) || /^artisan\s+/.test(command.toLowerCase());
            }

            async executeArtisanCommand(command) {
                try {
                    // Parse the command
                    let cleanCommand = command.replace(/^php\s+artisan\s+/i, '').replace(/^artisan\s+/i, '');
                    const parts = cleanCommand.split(/\s+/);
                    const artisanCommand = parts.shift();
                    const options = {};
                    const args = [];

                    // Parse arguments and options
                    parts.forEach(part => {
                        if (part.startsWith('--')) {
                            const [key, value] = part.split('=');
                            options[key] = value !== undefined ? value : true;
                        } else if (part.startsWith('-')) {
                            options[part] = true;
                        } else {
                            args.push(part);
                        }
                    });

                    // Add positional arguments to options
                    args.forEach((arg, index) => {
                        options[`arg${index}`] = arg;
                    });

                    const response = await fetch('/admin/terminal/run', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            command: artisanCommand,
                            options: options
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.addOutput(data.output || 'Command executed successfully', 'success-output');
                    } else {
                        this.addOutput(data.output || 'Command failed', 'error-output');
                    }
                } catch (error) {
                    this.addOutput(`Error: ${error.message}`, 'error-output');
                }
            }

            handleNonArtisanCommand(command) {
                const lowerCommand = command.toLowerCase();

                if (lowerCommand === 'clear' || lowerCommand === 'cls') {
                    this.clearTerminal();
                    return;
                }

                if (lowerCommand === 'help') {
                    this.showHelp();
                    return;
                }

                if (lowerCommand.startsWith('echo ')) {
                    this.addOutput(command.substring(5), 'command-output');
                    return;
                }

                // Default response for non-artisan commands
                this.addOutput(`Command not recognized: ${command}`, 'error-output');
                this.addOutput('This terminal only supports Laravel Artisan commands.', 'info-output');
                this.addOutput('Try: php artisan list', 'info-output');
            }

            addOutput(text, className = 'command-output') {
                const outputDiv = document.createElement('div');
                outputDiv.className = `terminal-line ${className}`;
                outputDiv.textContent = text;
                this.terminalOutput.appendChild(outputDiv);
                this.scrollToBottom();
            }

            clearTerminal() {
                this.terminalOutput.innerHTML = '';
            }

            showHelp() {
                const helpText = [
                    'Laravel Terminal Help:',
                    '',
                    'Available commands:',
                    '  php artisan <command>  - Execute Laravel Artisan commands',
                    '  artisan <command>      - Execute Laravel Artisan commands (shorthand)',
                    '  clear                  - Clear the terminal',
                    '  help                   - Show this help message',
                    '',
                    'Examples:',
                    '  php artisan list',
                    '  php artisan make:controller UserController',
                    '  php artisan migrate',
                    '  artisan route:list',
                    '',
                    'Use ↑/↓ arrow keys to navigate command history.'
                ];

                helpText.forEach(line => {
                    this.addOutput(line, 'info-output');
                });
            }

            scrollToBottom() {
                this.terminalBody.scrollTop = this.terminalBody.scrollHeight;
            }
        }

        // Initialize terminal when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new MacTerminal();
        });
        // Optional close handling if a close button exists in future
        const closeBtn = document.querySelector('.close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (confirm('Close terminal?')) {
                    window.location.href = '/admin/dashboard';
                }
            });
        }
    </script>
</body>

</html>
