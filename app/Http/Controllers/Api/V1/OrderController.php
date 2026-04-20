<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Listar historial de pedidos (Cliente) o pedidos de tienda (Operador)",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('operator')) {
            // Operadores ven pedidos de su tienda (simulado: primera tienda por ahora)
            return OrderResource::collection(Order::whereNotNull('store_id')->get());
        }

        $orders = Order::whereHas('subscription', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return OrderResource::collection($orders);
    }

    /**
     * @OA\Patch(
     *     path="/orders/{order}/status",
     *     summary="Actualizar estado del pedido (Operador/Supervisor)",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"picking", "ready_for_dispatch", "delivered", "failed"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Estado actualizado",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:picking,ready_for_dispatch,delivered,failed',
        ]);

        $order->update(['status' => $validated['status']]);

        return new OrderResource($order);
    }

    /**
     * @OA\Get(
     *     path="/orders/{order}",
     *     summary="Ver detalles de un pedido",
     *     tags={"Orders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del pedido",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     )
     * )
     */
    public function show(Order $order)
    {
        // Simple auth check for now, can be expanded with policies
        return new OrderResource($order);
    }
}
