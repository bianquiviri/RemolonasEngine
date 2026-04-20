@extends('layout')

@section('title', 'Mi Panel - Remolonas')

@section('styles')
<style>
    .dashboard-header {
        padding: 40px 0;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-delivered { background: #E8F5E9; color: #2E7D32; }
    .status-processing { background: #FFF3E0; color: #E67E22; }

    .subscription-info h3 { margin-top: 0; }
    .next-delivery {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="dashboard-header">
        <h1>¡Hola, {{ $subscription->user->name ?? 'Suscriptor' }}!</h1>
        <p>Aquí tienes el estado actual de tu cosecha.</p>
    </div>

    @if($subscription)
    <div class="dashboard-grid">
        <div class="left-col">
            <div class="card">
                <h2>Historial de Pedidos</h2>
                <div class="orders-list">
                    @forelse($orders as $order)
                    <div class="order-item">
                        <div>
                            <strong>Pedido #{{ substr($order->id, 0, 8) }}</strong><br>
                            <small>{{ $order->scheduled_delivery_date->format('d M, Y') }}</small>
                        </div>
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
                    </div>
                    @empty
                    <p>No hay pedidos registrados.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="right-col">
            <div class="card subscription-info">
                <h3>Tu Plan</h3>
                <p><strong>{{ $subscription->plan->name }}</strong></p>
                <p>Próxima Entrega:</p>
                <div class="next-delivery">{{ $subscription->next_delivery_date->format('d M, Y') }}</div>
                <br>
                <a href="#" class="btn btn-primary" style="width: 100%; text-align: center; box-sizing: border-box;">Gestionar Plan</a>
            </div>

            <div class="card">
                <h3>Logística Engine</h3>
                <p><small>Estado: <strong>Operativo</strong></small></p>
                <div class="sre-badge">SRE High Availability</div>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <p>No tienes ninguna suscripción activa. <a href="/plans">¡Elige una ahora!</a></p>
    </div>
    @endif
</div>
@endsection
