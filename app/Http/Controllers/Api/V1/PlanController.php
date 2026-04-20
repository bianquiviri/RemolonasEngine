<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return PlanResource::collection(Plan::where('active', true)->get());
    }

    public function show(Plan $plan)
    {
        return new PlanResource($plan);
    }
}
