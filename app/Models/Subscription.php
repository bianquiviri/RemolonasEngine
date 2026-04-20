<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Subscription",
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="frequency", type="string"),
 *     @OA\Property(property="next_delivery_date", type="string", format="date-time"),
 *     @OA\Property(property="user_id", type="string", format="uuid"),
 *     @OA\Property(property="plan", ref="#/components/schemas/Plan")
 * )
 */
class Subscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'next_delivery_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
