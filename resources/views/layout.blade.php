<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Remolonas Engine')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        :root {
            --primary: #2E7D32;
            --secondary: #E67E22;
            --background: #FDFBF7;
            --surface: #FFFFFF;
            --text-main: #1B1C1A;
            --text-muted: #40493D;
            --radius: 16px;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            margin: 0;
            line-height: 1.5;
        }

        h1, h2, h3 {
            font-family: 'Newsreader', serif;
            font-weight: 600;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 15px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Newsreader', serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }

        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: var(--text-main);
            font-weight: 500;
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: var(--radius);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: 24px;
        }

        footer {
            padding: 60px 0;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        .sre-badge {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(46, 125, 50, 0.1);
            color: var(--primary);
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav>
        <div class="container nav-content">
            <a href="/" class="logo">Remolonas</a>
            <div class="nav-links">
                <a href="/">Inicio</a>
                <a href="/plans">Planes</a>
                <a href="/dashboard">Dashboard</a>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2026 Remolonas Engine. Todos los derechos reservados.</p>
            <div class="sre-badge">SRE-Grade Reliability Active</div>
        </div>
    </footer>
</body>
</html>
