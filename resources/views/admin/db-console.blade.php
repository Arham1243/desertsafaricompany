<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>SQL Runner</title>
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

        form.sql-runner {
            position: relative;
        }

        .editor {
            position: relative;
            padding-bottom: 76px;
            /* space for the floating Run button */
        }

        textarea.sql-input {
            width: 100%;
            max-width: 100%;
            min-height: 260px;
            resize: vertical;
            /* vertical only */
            background: var(--textarea);
            color: var(--text);
            border: 1.25px solid var(--border);
            border-radius: 10px;
            padding: 14px 14px 14px 14px;
            outline: none;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 14px;
            line-height: 1.55;
            caret-color: #93c5fd;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
        }

        textarea.sql-input::placeholder {
            color: #6b7280;
        }

        textarea.sql-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .run-button {
            position: absolute;
            right: 20px;
            bottom: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: 0;
            border-radius: 10px;
            background: linear-gradient(180deg, var(--accent), var(--accent-strong));
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .01em;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.45);
            transition: transform .06s ease, box-shadow .2s ease, filter .18s ease;
        }

        .run-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.55);
            filter: brightness(1.05);
        }

        .run-button:active {
            transform: translateY(0);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.45);
        }

        .run-button:focus-visible {
            outline: 3px solid rgba(59, 130, 246, 0.35);
            outline-offset: 2px;
        }

        .result {
            margin-top: 20px;
            background: var(--panel);
            border: 1.25px solid var(--border);
            border-radius: 12px;
            padding: 16px 16px 6px 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.03);
        }

        .result h3 {
            margin: 0 0 12px 0;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        pre {
            margin: 0 0 10px 0;
            max-height: 100vh;
            overflow-y: auto;
            background: #0b0f14;
            border: 1px dashed #283546;
            border-radius: 10px;
            padding: 14px;
            color: var(--text);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            line-height: 1.55;
        }

        button,
        textarea {
            -webkit-appearance: none;
            appearance: none;
        }

        @media (max-width: 640px) {
            .container {
                margin: 28px auto;
            }

            .run-button {
                right: 16px;
                bottom: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title">SQL Query</h1>
            </div>

            <form class="sql-runner" action="{{ route('admin.db.console.run') }}" method="POST">
                @csrf
                <div class="editor">
                    <textarea class="sql-input" name="query" placeholder="-- Write your SQL here" spellcheck="false">{{ $query }}</textarea>
                    <button type="submit" class="run-button">Run</button>
                </div>
            </form>
        </div>

        @if ($output !== null)
            <div class="result">
                <h3>Result</h3>
                <pre>{{ print_r($output, true) }}</pre>
            </div>
        @endif
    </div>
</body>

</html>
