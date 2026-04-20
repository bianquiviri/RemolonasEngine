<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PickingController extends Controller
{
    public function show(Order $order)
    {
        // Forzar carga de items y productos para la vista
        $order->load('items.product');
        
        return view('picking.show', compact('order'));
    }
}
