<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="Remolonas Engine API",
 *     version="1.0.0",
 *     description="API para la gestión de suscripciones de cajas orgánicas."
 * )
 * @OA\Server(url="/api/v1")
 */
class PlanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/plans",
     *     summary="Listar todos los planes activos",
     *     tags={"Plans"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de planes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Plan"))
     *     )
     * )
     */
    public function index()
    {
        return PlanResource::collection(Plan::where('active', true)->get());
    }

    /**
     * @OA\Get(
     *     path="/plans/{plan}",
     *     summary="Ver detalles de un plan",
     *     tags={"Plans"},
     *     @OA\Parameter(name="plan", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del plan",
     *         @OA\JsonContent(ref="#/components/schemas/Plan")
     *     )
     * )
     */
    public function show(Plan $plan)
    {
        return new PlanResource($plan);
    }
}
