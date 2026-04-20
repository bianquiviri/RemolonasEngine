<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/picking/order/{orderId}",
     *     summary="Obtener items de un pedido para picking",
     *     tags={"Picking"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="orderId", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Lista de items")
     * )
     */
    public function getOrderItems(Order $order)
    {
        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'items' => $order->items()->with('product')->get()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/picking/scan",
     *     summary="Escanear un producto por código de barras",
     *     tags={"Picking"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="order_id", type="string", format="uuid"),
     *             @OA\Property(property="barcode", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Item marcado como recogido")
     * )
     */
    public function scanItem(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'barcode' => 'required|string',
        ]);

        $product = Product::where('barcode', $request->barcode)->first();

        if (!$product) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        $orderItem = OrderItem::where('order_id', $request->order_id)
            ->where('product_id', $product->id)
            ->where('is_picked', false)
            ->first();

        if (!$orderItem) {
            return response()->json(['message' => 'Este producto no pertenece al pedido o ya ha sido recogido'], 400);
        }

        $orderItem->update([
            'is_picked' => true,
            'picked_at' => now(),
            'picked_by' => $request->user()->id,
        ]);

        // Si es el primer item recogido, cambiar estado del pedido a 'picking'
        $order = Order::find($request->order_id);
        if ($order->status === 'pending') {
            $order->update(['status' => 'picking']);
        }

        return response()->json([
            'message' => 'Producto recogido con éxito',
            'item' => $orderItem->load('product')
        ]);
    }

    /**
     * @OA\Post(
     *     path="/picking/complete/{orderId}",
     *     summary="Finalizar el picking de un pedido",
     *     tags={"Picking"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="orderId", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=200, description="Pedido listo para despacho")
     * )
     */
    public function completePicking(Order $order)
    {
        $unpickedCount = $order->items()->where('is_picked', false)->count();

        if ($unpickedCount > 0) {
            return response()->json([
                'message' => "Faltan $unpickedCount items por recoger",
                'remaining_items' => $order->items()->where('is_picked', false)->with('product')->get()
            ], 400);
        }

        $order->update(['status' => 'ready_for_dispatch']);

        return response()->json([
            'message' => 'Picking completado. Pedido listo para despacho.',
            'order' => $order
        ]);
    }
}
