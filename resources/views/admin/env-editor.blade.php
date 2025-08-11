<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>.env Editor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <style>
        :root {
            --bg: #0b0f14;
            --panel: #10151d;
            --muted: #9aa4b2;
            --border: #283546;
            /* slightly lighter for more prominent edges */
            --surface: #0e141b;
            --accent: #3b82f6;
            --accent-strong: #2563eb;
            --text: #e6edf3;
            --danger: #ef4444;
            --danger-strong: #dc2626;
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
            margin-bottom: 12px;
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

        .table-wrap {
            background: var(--surface);
            border: 1.25px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #0f1720;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        th,
        td {
            border-bottom: 1px solid var(--border);
            padding: 10px 12px;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        input.env-input {
            width: 100%;
            background: #0b1118;
            color: var(--text);
            border: 1.25px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            outline: none;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            line-height: 1.4;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.02);
        }

        input.env-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .2);
        }

        .row-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .icon-button {
            border: 0;
            border-radius: 8px;
            padding: 8px 10px;
            background: #17202b;
            color: #e5e7eb;
            cursor: pointer;
            transition: filter .18s ease, transform .06s ease;
        }

        .icon-button:hover {
            filter: brightness(1.08);
        }

        .icon-button:active {
            transform: translateY(0);
        }

        .icon-button.danger {
            background: linear-gradient(180deg, var(--danger), var(--danger-strong));
            color: #fff;
            box-shadow: 0 8px 16px rgba(220, 38, 38, 0.35);
        }

        .icon-button.danger:hover {
            filter: brightness(1.05);
        }

        .icon-button.danger:active {
            transform: translateY(0);
            box-shadow: 0 6px 12px rgba(220, 38, 38, 0.3);
        }

        .actions-bar {
            margin-top: 14px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 0;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: .01em;
            cursor: pointer;
            transition: transform .06s ease, box-shadow .2s ease, filter .18s ease;
        }

        .button.primary {
            color: #fff;
            background: linear-gradient(180deg, var(--accent), var(--accent-strong));
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.45);
        }

        .button.primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.55);
            filter: brightness(1.05);
        }

        .button.primary:active {
            transform: translateY(0);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.45);
        }

        .button.secondary {
            background: #17202b;
            color: #e5e7eb;
        }

        .button.secondary:hover {
            filter: brightness(1.08);
        }

        button,
        input {
            -webkit-appearance: none;
            appearance: none;
        }

        @media (max-width: 640px) {
            .container {
                margin: 28px auto;
            }

            th:nth-child(3),
            td:nth-child(3) {
                width: 1%;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1 class="title">.env Editor</h1>
            </div>

            <form method="POST" action="{{ route('admin.env.save') }}">
                @csrf
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Key</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="env-rows">
                            @foreach ($env as $key => $value)
                                <tr>
                                    <td><input class="env-input" name="keys[]" value="{{ $key }}"></td>
                                    <td><input class="env-input" name="values[]" value="{{ $value }}"></td>
                                    <td class="row-actions"><button type="button" class="icon-button danger"
                                            onclick="removeRow(this)">Remove</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="actions-bar">
                    <button type="button" class="button secondary" onclick="addRow()">Add Variable</button>
                    <button type="submit" class="button primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function removeRow(btn) {
            btn.closest('tr').remove();
        }

        function addRow() {
            const tbody = document.getElementById('env-rows');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input class="env-input" name="keys[]" value=""></td>
                <td><input class="env-input" name="values[]" value=""></td>
                <td class="row-actions"><button type="button" class="icon-button danger" onclick="removeRow(this)">Remove</button></td>
            `;
            tbody.appendChild(row);
        }
    </script>
</body>

</html>
