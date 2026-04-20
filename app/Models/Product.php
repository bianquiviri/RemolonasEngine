<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @OA\Schema(
 *     schema="Product",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="barcode", type="string"),
 *     @OA\Property(property="available_quantity", type="integer"),
 *     @OA\Property(property="image_url", type="string")
 * )
 */
class Product extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];
}
