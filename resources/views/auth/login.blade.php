@extends('layout')

@section('title', 'Bienvenido a la Cosecha - Remolonas Engine')

@section('styles')
<style>
    :root {
        --primary: #2E7D32;
        --primary-light: #88d982;
        --accent: #E67E22;
        --white-glass: rgba(255, 255, 255, 0.1);
        --dark-glass: rgba(0, 0, 0, 0.2);
    }

    body {
        margin: 0;
        overflow-x: hidden;
        background-color: #0a0a0a;
    }

    .auth-wrapper {
        position: relative;
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .bg-video-container {
        position: fixed;
        inset: 0;
        z-index: -1;
        overflow: hidden;
    }

    .bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1.1);
        animation: slowZoom 20s infinite alternate;
        filter: brightness(0.7) contrast(1.1);
    }

    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.15); }
    }

    .auth-card {
        width: 100%;
        max-width: 480px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px;
        padding: 50px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
    }

    .auth-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at center, rgba(46, 125, 50, 0.15) 0%, transparent 70%);
        z-index: -1;
    }

    .editorial-title {
        font-family: 'Newsreader', serif;
        color: #FFFFFF;
        font-size: 3.5rem;
        line-height: 0.9;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
        font-style: italic;
    }

    .form-label {
        font-family: 'Manrope', sans-serif;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 8px;
        display: block;
    }

    .input-field {
        width: 100%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 16px 20px;
        color: white;
        font-family: 'Manrope', sans-serif;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 20px;
    }

    .input-field:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--primary-light);
        box-shadow: 0 0 0 4px rgba(136, 217, 130, 0.1);
    }

    .primary-btn {
        width: 100%;
        padding: 18px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--primary), #1B5E20);
        color: white;
        font-weight: 700;
        font-family: 'Manrope', sans-serif;
        font-size: 16px;
        letter-spacing: 0.02em;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        box-shadow: 0 10px 20px -5px rgba(46, 125, 50, 0.4);
    }

    .primary-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(46, 125, 50, 0.6);
    }

    .primary-btn:active {
        transform: translateY(0);
    }

    .tab-nav {
        display: flex;
        gap: 30px;
        margin-bottom: 40px;
    }

    .tab-link {
        color: rgba(255, 255, 255, 0.4);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        padding-bottom: 8px;
        position: relative;
        transition: color 0.3s;
    }

    .tab-link.active {
        color: white;
    }

    .tab-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 20px;
        height: 3px;
        background: var(--accent);
        border-radius: 2px;
    }

    .forgot-link {
        display: block;
        text-align: center;
        margin-top: 24px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.4);
        text-decoration: none;
        transition: color 0.2s;
    }

    .forgot-link:hover {
        color: white;
    }

    .sre-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 8px;
        color: rgba(255, 255, 255, 0.2);
        font-weight: 900;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="bg-video-container">
        <img src="/Users/bianquiviri/.gemini/antigravity/brain/c20aa384-74ff-43bd-8f55-77f83ebd9db6/remolonas_auth_background_v2_1776723925459.png" class="bg-image" alt="Organic Greenhouse">
    </div>

    <div class="auth-card">
        <div class="sre-tag">Security Lvl: SRE-Grade</div>
        
        <div class="tab-nav">
            <span class="tab-link active" onclick="switchAuth('login')">Entrar</span>
            <span class="tab-link" onclick="switchAuth('register')">Unirse</span>
        </div>

        <!-- Formulario de Login -->
        <div id="login-section">
            <h1 class="editorial-title">The Harvest <br>Await.</h1>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label">Identidad (Email)</label>
                    <input type="email" name="email" class="input-field" placeholder="tu@email.com" required>
                </div>
                <div>
                    <label class="form-label">Llave de Acceso</label>
                    <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                </div>
                <button type="submit" class="primary-btn">Comenzar Experiencia</button>
                <a href="{{ route('password.request') }}" class="forgot-link">¿Perdiste tu llave? Recuperar acceso</a>
            </form>
        </div>

        <!-- Formulario de Registro -->
        <div id="register-section" style="display: none;">
            <h1 class="editorial-title">Join the <br>Greenhouse.</h1>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div>
                    <label class="form-label">Nombre Completo</label>
                    <input type="text" name="name" class="input-field" placeholder="Juan Pérez" required>
                </div>
                <div>
                    <label class="form-label">Email Personal</label>
                    <input type="email" name="email" class="input-field" placeholder="tu@email.com" required>
                </div>
                <div>
                    <label class="form-label">Nueva Contraseña</label>
                    <input type="password" name="password" class="input-field" placeholder="••••••••" required>
                </div>
                <div>
                    <label class="form-label">Confirmar Llave</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="••••••••" required>
                </div>
                <button type="submit" class="primary-btn">Crear Cuenta Cliente</button>
            </form>
        </div>
    </div>
</div>

<script>
    function switchAuth(type) {
        const loginSec = document.getElementById('login-section');
        const registerSec = document.getElementById('register-section');
        const tabs = document.querySelectorAll('.tab-link');

        if (type === 'login') {
            loginSec.style.display = 'block';
            registerSec.style.display = 'none';
            tabs[0].classList.add('active');
            tabs[1].classList.remove('active');
        } else {
            loginSec.style.display = 'none';
            registerSec.style.display = 'block';
            tabs[0].classList.remove('active');
            tabs[1].classList.add('active');
        }
    }
</script>
@endsection
