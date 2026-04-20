<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'scheduled_delivery_date' => $this->scheduled_delivery_date->toIso8601String(),
            'subscription_id' => $this->subscription_id,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
