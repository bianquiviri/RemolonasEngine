<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @OA\Schema(
 *     schema="Store",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="location", type="string"),
 *     @OA\Property(property="active", type="boolean")
 * )
 */
class Store extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'location', 'active'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
