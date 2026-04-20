<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    /**
     * @OA\Get(
     *     path="/stores",
     *     summary="Listar todas las tiendas (Supervisor)",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tiendas",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Store"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Store::all());
    }

    /**
     * @OA\Post(
     *     path="/stores",
     *     summary="Crear una nueva tienda (Supervisor)",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="location", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tienda creada",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
        ]);

        $store = Store::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'location' => $validated['location'],
            'active' => true,
        ]);

        return response()->json($store, 201);
    }

    /**
     * @OA\Get(
     *     path="/stores/{store}",
     *     summary="Ver detalles de una tienda",
     *     tags={"Stores"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="store", in="path", required=true, @OA\Schema(type="string", format="uuid")),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la tienda",
     *         @OA\JsonContent(ref="#/components/schemas/Store")
     *     )
     * )
     */
    public function show(Store $store)
    {
        return response()->json($store);
    }
}
