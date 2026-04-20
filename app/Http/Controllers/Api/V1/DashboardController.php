<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/dashboard/customer",
     *     summary="Datos para el Dashboard del Cliente",
     *     tags={"Dashboards"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Estadísticas del cliente")
     * )
     */
    public function customer(Request $request)
    {
        $userId = $request->user()->id;
        $nextDelivery = Order::whereHas('subscription', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'pending')
            ->orderBy('scheduled_delivery_date', 'asc')
            ->first();

        return response()->json([
            'active_subscriptions' => Subscription::where('user_id', $userId)->where('status', 'active')->count(),
            'next_delivery' => $nextDelivery ? new \App\Http\Resources\OrderResource($nextDelivery) : null,
            'order_history_count' => Order::whereHas('subscription', fn($q) => $q->where('user_id', $userId))->count(),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/operator",
     *     summary="Datos para el Dashboard del Operador (Tienda)",
     *     tags={"Dashboards"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Estadísticas de la tienda")
     * )
     */
    public function operator(Request $request)
    {
        $user = $request->user();
        
        if (!$user->store_id) {
            return response()->json(['error' => 'Usuario no tiene una tienda asignada'], 403);
        }

        $storeId = $user->store_id;

        return response()->json([
            'store_info' => Store::find($storeId),
            'pending_orders_count' => Order::where('store_id', $storeId)->where('status', 'pending')->count(),
            'picking_now_count' => Order::where('store_id', $storeId)->where('status', 'picking')->count(),
            'ready_today_count' => Order::where('store_id', $storeId)->where('status', 'ready_for_dispatch')->count(),
            'recent_orders' => \App\Http\Resources\OrderResource::collection(
                Order::where('store_id', $storeId)->orderBy('created_at', 'desc')->take(5)->get()
            ),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/dashboard/supervisor",
     *     summary="Datos para el Panel de Control Global (Supervisor)",
     *     tags={"Dashboards"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Visión global de la operación")
     * )
     */
    public function supervisor()
    {
        return response()->json([
            'global_stats' => [
                'total_orders' => Order::count(),
                'orders_by_status' => Order::select('status', DB::raw('count(*) as count'))->groupBy('status')->get(),
            ],
            'store_monitoring' => Store::withCount(['orders' => fn($q) => $q->where('status', '!=', 'delivered')])->get(),
            'alerts' => Order::where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24))
                ->count(),
        ]);
    }
}
