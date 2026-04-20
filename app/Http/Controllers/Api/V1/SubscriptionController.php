<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription::with('plan')
            ->where('user_id', $request->user()->id)
            ->get();
            
        return SubscriptionResource::collection($subscriptions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'frequency' => 'required|in:weekly,monthly',
        ]);

        $subscription = Subscription::create([
            'id' => (string) Str::uuid(),
            'user_id' => $request->user()->id,
            'plan_id' => $validated['plan_id'],
            'status' => 'active',
            'frequency' => $validated['frequency'],
            'next_delivery_date' => now()->addWeek(),
        ]);

        return new SubscriptionResource($subscription->load('plan'));
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);
        return new SubscriptionResource($subscription->load('plan'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);
        
        $validated = $request->validate([
            'status' => 'sometimes|in:active,paused,cancelled',
            'frequency' => 'sometimes|in:weekly,monthly',
        ]);

        $subscription->update($validated);

        return new SubscriptionResource($subscription->load('plan'));
    }

    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);
        $subscription->delete();
        
        return response()->json(null, 204);
    }
}
