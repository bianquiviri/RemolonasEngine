<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="available_quantity", type="integer"),
 *     @OA\Property(property="image_url", type="string")
 * )
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
}
