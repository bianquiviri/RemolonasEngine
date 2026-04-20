@extends('layout')

@section('title', 'Recuperar Contraseña - Remolonas Engine')

@section('styles')
<style>
    .auth-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 140px);
        background-color: #FDFBF7;
    }

    .glass-card {
        width: 100%;
        max-width: 440px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        color: var(--text-muted);
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.1);
        background: white;
        font-family: 'Manrope', sans-serif;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(46, 125, 50, 0.1);
    }

    .btn-auth {
        width: 100%;
        margin-top: 10px;
        background: linear-gradient(135deg, var(--primary), #1B5E20);
        color: white;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="glass-card">
        <h2 class="text-3xl mb-4">¿Olvidaste tu contraseña?</h2>
        <p class="text-sm text-gray-500 mb-6">No te preocupes. Introduce tu email y te enviaremos un enlace para restablecerla.</p>
        
        <form action="#" method="POST">
            @csrf
            <div class="form-group">
                <label>Email de registro</label>
                <input type="email" name="email" class="form-control" placeholder="tu@email.com" required>
            </div>
            <button type="submit" class="btn-auth">Enviar enlace de recuperación</button>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-xs text-green-700 font-bold uppercase tracking-widest">Volver al inicio de sesión</a>
            </div>
        </form>
    </div>
</div>
@endsection
