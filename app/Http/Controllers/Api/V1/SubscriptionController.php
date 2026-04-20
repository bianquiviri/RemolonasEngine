<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="sanctum"
 * )
 */
class SubscriptionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/subscriptions",
     *     summary="Listar suscripciones del usuario",
     *     tags={"Subscriptions"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de suscripciones",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Subscription"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('plan')
            ->where('user_id', $request->user()->id)
            ->get();
            
        return SubscriptionResource::collection($subscriptions);
    }

    /**
     * @OA\Post(
     *     path="/subscriptions",
     *     summary="Crear una nueva suscripción",
     *     tags={"Subscriptions"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="plan_id", type="integer"),
     *             @OA\Property(property="frequency", type="string", enum={"weekly", "monthly"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Suscripción creada",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'frequency' => 'required|in:weekly,monthly',
        ]);

        $subscription = Subscription::create([
            'id' => (string) Str::uuid(),
            'user_id' => $request->user()->id,
            'plan_id' => $validated['plan_id'],
            'status' => 'active',
            'frequency' => $validated['frequency'],
            'next_delivery_date' => now()->addWeek(),
        ]);

        return new SubscriptionResource($subscription->load('plan'));
    }

    /**
     * @OA\Get(
     *     path="/subscriptions/{subscription}",
     *     summary="Ver detalles de una suscripción",
     *     tags={"Subscriptions"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="subscription", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la suscripción",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     )
     * )
     */
    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        return new SubscriptionResource($subscription->load('plan'));
    }

    /**
     * @OA\Patch(
     *     path="/subscriptions/{subscription}",
     *     summary="Actualizar estado o frecuencia de una suscripción",
     *     tags={"Subscriptions"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="subscription", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", enum={"active", "paused", "cancelled"}),
     *             @OA\Property(property="frequency", type="string", enum={"weekly", "monthly"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Suscripción actualizada",
     *         @OA\JsonContent(ref="#/components/schemas/Subscription")
     *     )
     * )
     */
    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        
        $validated = $request->validate([
            'status' => 'sometimes|in:active,paused,cancelled',
            'frequency' => 'sometimes|in:weekly,monthly',
        ]);

        $subscription->update($validated);

        return new SubscriptionResource($subscription->load('plan'));
    }

    /**
     * @OA\Delete(
     *     path="/subscriptions/{subscription}",
     *     summary="Cancelar/Eliminar una suscripción",
     *     tags={"Subscriptions"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="subscription", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(response=204, description="Suscripción eliminada")
     * )
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();
        
        return response()->json(null, 204);
    }
}
