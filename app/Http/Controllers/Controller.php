<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @OA\Info(
 *     title="Remolonas Ops API",
 *     version="1.0.0",
 *     description="API para la gestión de pedidos, tiendas y suscripciones de Remolonas.",
 *     @OA\Contact(
 *         email="soporte@remolonas.com"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Ingresa el token de Sanctum obtenido en el login"
 * )
 */
abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
