@extends('layout')

@section('title', 'Nuestras Cajas - Remolonas')

@section('styles')
<style>
    .plans-header {
        padding: 60px 0;
        text-align: center;
    }

    .plan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        padding-bottom: 80px;
    }

    .plan-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s;
        overflow: hidden;
        padding: 0;
    }

    .plan-card:hover {
        transform: translateY(-5px);
    }

    .plan-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .plan-content {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .plan-price {
        font-size: 32px;
        font-weight: 700;
        color: var(--secondary);
        margin: 10px 0;
    }

    .plan-price span {
        font-size: 16px;
        color: var(--text-muted);
        font-weight: 400;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="plans-header">
        <h1>Elige tu Cosecha</h1>
        <p>Selecciona el plan que mejor se adapte al ritmo de tu cocina.</p>
    </div>

    <div class="plan-grid">
        @foreach($plans as $plan)
        <div class="card plan-card">
            <img src="{{ $plan->image_url ?? 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $plan->name }}" class="plan-image">
            <div class="plan-content">
                <div>
                    <h3>{{ $plan->name }}</h3>
                    <div class="plan-price">{{ number_format($plan->price, 2) }}€ <span>/ entrega</span></div>
                    <p>Frescura garantizada directamente del agricultor.</p>
                </div>
                <a href="#" class="btn btn-primary" style="text-align: center; margin-top: 20px;">Suscribirse</a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
