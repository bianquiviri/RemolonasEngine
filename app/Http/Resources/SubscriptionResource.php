<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'frequency' => $this->frequency,
            'next_delivery_date' => $this->next_delivery_date->toIso8601String(),
            'plan' => new PlanResource($this->whenLoaded('plan')),
            'user_id' => $this->user_id,
        ];
    }
}
