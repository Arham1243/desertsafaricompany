<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>.env Editor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: monospace;
            background: #1e1e1e;
            color: #e6e6e6;
            padding: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        td,
        th {
            padding: 0.5rem;
            border: 1px solid #444;
        }

        input {
            background: #2a2a2a;
            border: none;
            color: #0f0;
            width: 100%;
            padding: 5px;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        button {
            background: #333;
            color: #fff;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #555;
        }
    </style>
</head>

<body>

    <h2>.env Editor</h2>

    <form method="POST" action="{{ route('admin.env.save') }}">
        @csrf
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
                        <td><input name="keys[]" value="{{ $key }}"></td>
                        <td><input name="values[]" value="{{ $value }}"></td>
                        <td class="actions"><button type="button" onclick="removeRow(this)">🗑</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" onclick="addRow()">➕ Add Variable</button>
        <button type="submit">💾 Save Changes</button>
    </form>

    <script>
        function removeRow(btn) {
            btn.closest('tr').remove()
        }

        function addRow() {
            const tbody = document.getElementById('env-rows')
            const row = document.createElement('tr')
            row.innerHTML = `
                <td><input name="keys[]" value=""></td>
                <td><input name="values[]" value=""></td>
                <td class="actions"><button type="button" onclick="removeRow(this)">🗑</button></td>
            `
            tbody.appendChild(row)
        }
    </script>

</body>

</html>
