@extends('layout')

@section('title', 'Picking: Pedido #' . substr($order->id, 0, 8))

@section('styles')
<style>
    :root {
        --ops-charcoal: #121212;
        --ops-green: #2E7D32;
        --ops-glass: rgba(18, 18, 18, 0.8);
    }

    body {
        background-color: var(--ops-charcoal);
        color: #E0E0E0;
    }

    .glass-panel {
        background: var(--ops-glass);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 24px;
    }

    .progress-track {
        height: 6px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--ops-green), #4CAF50);
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .product-card.picked {
        background: rgba(46, 125, 50, 0.1);
        border-left-color: var(--ops-green);
    }

    .scan-btn {
        background: linear-gradient(135deg, var(--ops-green), #1B5E20);
        box-shadow: 0 8px 32px rgba(46, 125, 50, 0.3);
    }

    .scan-btn:active {
        transform: scale(0.95);
    }

    .location-badge {
        font-family: 'Inter', sans-serif;
        background: rgba(255, 255, 255, 0.05);
        color: #88d982;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container py-8 max-w-lg">
    <!-- Header -->
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl text-white mb-1">Picking Queue</h1>
            <p class="text-sm text-gray-400">Order #{{ substr($order->id, 0, 8) }}</p>
        </div>
        <div class="text-right">
            <span class="text-2xl font-bold text-green-500" id="picked-count">{{ $order->items->where('is_picked', true)->count() }}</span>
            <span class="text-gray-500">/{{ $order->items->count() }}</span>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-track mb-8">
        <div id="progress-bar" class="progress-fill" style="width: {{ ($order->items->where('is_picked', true)->count() / max($order->items->count(), 1)) * 100 }}%"></div>
    </div>

    <!-- Items List -->
    <div class="space-y-4 mb-32" id="items-list">
        @foreach($order->items as $item)
        <div class="glass-panel p-4 flex items-center gap-4 product-card {{ $item->is_picked ? 'picked' : '' }}" data-barcode="{{ $item->product->barcode }}">
            <div class="w-16 h-16 rounded-xl bg-gray-800 flex-shrink-0 overflow-hidden">
                @if($item->product->image_url)
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>
            <div class="flex-grow">
                <div class="flex justify-between items-start">
                    <h3 class="text-white text-lg leading-tight">{{ $item->product->name }}</h3>
                    <span class="location-badge">GH-{{ strtoupper(substr($item->product->id, 0, 4)) }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Qty: {{ $item->quantity }} • {{ $item->product->sku ?? 'No SKU' }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] text-gray-600 uppercase tracking-widest font-bold">Barcode: {{ $item->product->barcode }}</span>
                </div>
            </div>
            <div class="flex-shrink-0">
                @if($item->is_picked)
                    <div class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @else
                    <div class="w-6 h-6 rounded-full border-2 border-gray-700"></div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Action Button -->
    <div class="fixed bottom-8 left-1/2 -translate-x-1/2 w-full max-w-xs px-4">
        <button onclick="simulateScan()" class="scan-btn w-full py-5 rounded-full text-white font-bold flex items-center justify-center gap-3 transition-transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            Scan Barcode
        </button>
    </div>
</div>

<script>
    function simulateScan() {
        const barcode = prompt("Ingrese código de barras (Prueba):");
        if (!barcode) return;

        fetch('/api/v1/picking/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                // En un sistema real, el token vendría de la sesión/auth
                'Authorization': 'Bearer ' + '{{ auth()->user() ? auth()->user()->createToken("test")->plainTextToken : "" }}'
            },
            body: JSON.stringify({
                order_id: '{{ $order->id }}',
                barcode: barcode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Producto recogido con éxito') {
                location.reload(); // Recarga simple para demo
            } else {
                alert(data.message || 'Error al escanear');
            }
        });
    }
</script>
@endsection
