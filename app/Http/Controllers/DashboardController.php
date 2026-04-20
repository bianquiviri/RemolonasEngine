<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // For demo purposes, we'll take the first subscription of the logged-in user
        // If not logged in, we'll take a random one for the demo
        $user = Auth::user();
        
        if (!$user) {
            $subscription = Subscription::with(['user', 'plan'])->first();
        } else {
            $subscription = Subscription::with(['user', 'plan'])
                ->where('user_id', $user->id)
                ->first();
        }

        $orders = [];
        if ($subscription) {
            $orders = Order::where('subscription_id', $subscription->id)
                ->orderBy('scheduled_delivery_date', 'desc')
                ->get();
        }

        return view('dashboard', compact('subscription', 'orders'));
    }
}
