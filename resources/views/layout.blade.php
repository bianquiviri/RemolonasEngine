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
            --primary-light: #88d982;
            --secondary: #E67E22;
            --background: #0a0a0a;
            --surface: rgba(255, 255, 255, 0.03);
            --text-main: #FFFFFF;
            --text-muted: rgba(255, 255, 255, 0.5);
            --glass-border: rgba(255, 255, 255, 0.1);
            --radius: 24px;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            margin: 0;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3 {
            font-family: 'Newsreader', serif;
            font-weight: 500;
            letter-spacing: -0.02em;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        nav {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 20px 0;
            border-bottom: 1px solid var(--glass-border);
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Newsreader', serif;
            font-size: 28px;
            font-weight: 700;
            color: white;
            text-decoration: none;
            font-style: italic;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav-links a, .nav-links button {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            transition: color 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .nav-links a:hover, .nav-links button:hover {
            color: white;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-family: 'Manrope', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #1B5E20);
            color: white;
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(46, 125, 50, 0.5);
        }

        .card {
            background: var(--surface);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius);
            padding: 32px;
            transition: transform 0.3s, border-color 0.3s;
        }

        .card:hover {
            border-color: rgba(255, 255, 255, 0.2);
        }

        footer {
            padding: 80px 0 40px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
            border-top: 1px solid var(--glass-border);
            margin-top: 60px;
        }

        .sre-badge {
            display: inline-block;
            padding: 6px 16px;
            background: rgba(136, 217, 130, 0.05);
            color: var(--primary-light);
            border: 1px solid rgba(136, 217, 130, 0.1);
            border-radius: 30px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-top: 15px;
        }

        /* Utility for Glass Layouts */
        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 32px;
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
                @auth
                    <a href="/dashboard">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; font-weight: 500; font-size: 14px; color: var(--text-main); cursor: pointer; margin-left: 20px;">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Entrar</a>
                @endauth
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
