<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\Store;

class AssignOrderToStore
{
    /**
     * Lógica para asignar un pedido a una tienda.
     * En una fase avanzada, esto usaría geo-localización o balanceo de carga.
     */
    public function execute(Order $order): void
    {
        // Por ahora, asignamos a la primera tienda activa disponible
        $store = Store::where('active', true)->first();

        if ($store) {
            $order->update([
                'store_id' => $store->id,
                'status' => 'pending'
            ]);
        }
    }
}
