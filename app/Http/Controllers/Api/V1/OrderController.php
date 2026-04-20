<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::whereHas('subscription', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->get();

        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order->subscription);
        return new OrderResource($order);
    }
}
