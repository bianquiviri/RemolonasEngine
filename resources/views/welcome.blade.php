@extends('layout')

@section('title', 'Remolonas - Fruta y Verdura Ecológica')

@section('styles')
<style>
    .hero {
        padding: 100px 0;
        text-align: center;
        background: linear-gradient(180deg, rgba(46, 125, 50, 0.05) 0%, rgba(253, 251, 247, 0) 100%);
    }

    .hero h1 {
        font-size: 56px;
        margin-bottom: 20px;
        color: var(--primary);
    }

    .hero p {
        font-size: 18px;
        color: var(--text-muted);
        max-width: 600px;
        margin: 0 auto 40px;
    }

    .features {
        padding: 80px 0;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .feature-card {
        text-align: center;
        padding: 40px;
    }

    .feature-card h3 {
        margin-top: 20px;
        font-size: 24px;
    }

    .feature-card p {
        color: var(--text-muted);
    }

    .cta-section {
        padding: 100px 0;
        background: var(--primary);
        color: white;
        text-align: center;
        border-radius: 40px;
        margin: 40px 20px;
    }

    .cta-section h2 {
        font-size: 40px;
        margin-bottom: 20px;
    }

    .btn-white {
        background: white;
        color: var(--primary);
    }
</style>
@endsection

@section('content')
<section class="hero">
    <div class="container">
        <h1>Directo de la tierra,<br>automáticamente.</h1>
        <p>Remolonas Engine gestiona tus suscripciones de fruta y verdura orgánica con precisión SRE, garantizando frescura en cada entrega.</p>
        <a href="/plans" class="btn btn-primary">Elegir mi Caja</a>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="feature-grid">
            <div class="card feature-card">
                <h3>Logística Inteligente</h3>
                <p>Cálculo automático de entregas para evitar domingos y optimizar rutas de reparto.</p>
            </div>
            <div class="card feature-card">
                <h3>Suscripción Flexible</h3>
                <p>Pausa, salta o cambia de plan con un solo clic desde tu dashboard personalizado.</p>
            </div>
            <div class="card feature-card">
                <h3>Máxima Fiabilidad</h3>
                <p>Infraestructura robusta que garantiza que tu pedido nunca se pierda.</p>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <h2>¿Listo para empezar?</h2>
        <p>Únete a la revolución de la comida real gestionada por ingenieros.</p>
        <br>
        <a href="/plans" class="btn btn-white">Ver Planes Disponibles</a>
    </div>
</section>
@endsection
