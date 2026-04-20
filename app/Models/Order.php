<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @OA\Schema(
 *     schema="Order",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="subscription_id", type="string", format="uuid"),
 *     @OA\Property(property="store_id", type="string", format="uuid", nullable=true),
 *     @OA\Property(property="status", type="string", enum={"pending", "picking", "ready_for_dispatch", "delivered", "failed"}),
 *     @OA\Property(property="delivery_date", type="string", format="date"),
 *     @OA\Property(property="total_amount", type="number", format="float")
 * )
 */
class Order extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'error_log' => 'array',
        'scheduled_delivery_date' => 'date',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
