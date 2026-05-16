<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #1f2937;
            display: grid;
            min-height: 100vh;
            place-items: center;
        }
        .card {
            max-width: 640px;
            margin: 1rem;
            padding: 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        h1 { margin-top: 0; }
        p { line-height: 1.6; }
        code {
            display: inline-block;
            background: #f1f5f9;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Database temporarily unavailable</h1>
        <p>{{ $message ?? 'The app cannot reach MongoDB right now.' }}</p>
        <p>Please verify your <code>DB_URI</code>, Atlas network access, and internet connection, then refresh.</p>
    </main>
</body>
</html>
