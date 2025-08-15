<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .error-container {
            max-width: 400px;
            padding: 2rem;
        }

        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #495057;
        }

        .error-message {
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            color: #6c757d;
        }

        .btn-home {
            color: #0d6efd;
            padding: 0.75rem 1.5rem;
            border-radius: 0.375rem;
            font-size: 1rem;
            text-decoration: none;
        }

        .btn-home:hover {
            color: #0b5ed7;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">The page you’re looking for doesn’t exist or has been moved.</div>
        <a href="{{ request()->is('admin/*') ? route('admin.dashboard') : url('/') }}" class="btn-home">Go to Home</a>
    </div>
</body>

</html>
