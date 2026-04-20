<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'is_picked',
        'picked_at',
        'picked_by',
    ];

    protected $casts = [
        'is_picked' => 'boolean',
        'picked_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function picker()
    {
        return $this->belongsTo(User::class, 'picked_by');
    }
}
