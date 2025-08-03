<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Artisan Terminal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            background: #000;
            height: 100vh;
            overflow: hidden;
        }

        .terminal-window {
            width: 100%;
            height: 100vh;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
            display: flex;
            flex-direction: column;
        }

        .terminal-header {
            background: linear-gradient(180deg, #4a4a4a 0%, #2a2a2a 100%);
            height: 28px;
            display: flex;
            align-items: center;
            padding: 0 10px;
            border-bottom: 1px solid #1a1a1a;
            position: relative;
        }

        .traffic-lights {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .traffic-light {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            border: 0.5px solid rgba(0, 0, 0, 0.2);
        }

        .close {
            background: linear-gradient(135deg, #ff5f57 0%, #ff3b30 100%);
        }

        .minimize {
            background: linear-gradient(135deg, #ffbd2e 0%, #ff9500 100%);
        }

        .maximize {
            background: linear-gradient(135deg, #28ca42 0%, #30d158 100%);
        }

        .terminal-title {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .terminal-body {
            flex: 1;
            background: #000;
            padding: 15px;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.4;
            color: #fff;
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

        .prompt-user {
            color: #4fc3f7;
            font-weight: 600;
        }

        .prompt-separator {
            color: #fff;
            margin: 0 5px;
        }

        .prompt-path {
            color: #81c784;
            font-weight: 500;
        }

        .prompt-symbol {
            color: #fff;
            margin: 0 8px 0 5px;
        }

        .terminal-input {
            background: transparent;
            border: none;
            outline: none;
            color: #fff;
            font-family: inherit;
            font-size: inherit;
            flex: 1;
            caret-color: #fff;
        }

        .terminal-cursor {
            display: inline-block;
            width: 8px;
            height: 16px;
            background: #fff;
            animation: blink 1s infinite;
            margin-left: 2px;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0;
            }
        }

        .command-output {
            color: #e0e0e0;
            margin: 5px 0;
            padding-left: 0;
        }

        .command-echo {
            color: #888;
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
            background: #1a1a1a;
        }

        .terminal-body::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 4px;
        }

        .terminal-body::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Welcome message styling */
        .welcome-message {
            color: #888;
            margin-bottom: 15px;
            font-style: italic;
        }

        .artisan-hint {
            color: #ffd93d;
            margin-bottom: 10px;
            font-size: 12px;
        }

        /* Command history */
        .history-item {
            color: #666;
            font-size: 11px;
            margin-bottom: 1px;
        }

        /* Terminal-style text selection */
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

        /* Make output text selectable */
        .terminal-line {
            user-select: text;
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
        }
    </style>
</head>

<body>
    <div class="terminal-window">
        <div class="terminal-header">
            <div class="traffic-lights">
                <div class="traffic-light close"></div>
                <div class="traffic-light minimize"></div>
                <div class="traffic-light maximize"></div>
            </div>
            <div class="terminal-title">{{ env('APP_NAME', 'Laravel') }} — Terminal</div>
        </div>
        <div class="terminal-body" id="terminal-body">
            <div class="artisan-hint">This terminal will only execute PHP Artisan commands</div>
            <div class="terminal-output" id="terminal-output"></div>
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
                // Only focus when clicking on empty terminal areas
                this.terminalBody.addEventListener('click', (e) => {
                    // Only focus if clicking on the terminal body itself or prompt area
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

        // Traffic light functionality (optional)
        document.querySelector('.close').addEventListener('click', () => {
            if (confirm('Close terminal?')) {
                window.location.href = '/admin/dashboard';
            }
        });
    </script>
</body>

</html>
