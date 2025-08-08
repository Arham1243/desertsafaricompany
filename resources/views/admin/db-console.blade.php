<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>DB Console</title>
</head>

<body>
    <form action="{{ route('admin.db.console.run') }}" method="POST">
        @csrf
        <textarea name="query" rows="5" cols="80">{{ $query }}</textarea>
        <br>
        <button type="submit">Run</button>
    </form>

    @if ($output !== null)
        <h3>Result:</h3>
        <pre>{{ print_r($output, true) }}</pre>
    @endif
</body>

</html>
