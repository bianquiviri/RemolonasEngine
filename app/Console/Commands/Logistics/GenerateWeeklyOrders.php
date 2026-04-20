<?php

namespace App\Console\Commands\Logistics;

use Illuminate\Console\Command;

class GenerateWeeklyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logistics:generate-weekly-orders';

    protected $description = 'Generate orders for active subscriptions due in the next 7 days.';

    public function handle()
    {
        $this->info('Starting Logistics:GenerateWeeklyOrders');

        $subscriptions = \App\Models\Subscription::where('status', 'active')
            ->whereBetween('next_delivery_date', [now()->startOfDay(), now()->addDays(7)->endOfDay()])
            ->get();

        $this->info("Found {$subscriptions->count()} subscriptions due.");

        foreach ($subscriptions as $subscription) {
            // For resilience, we dispatch this to a Redis queue.
            \App\Jobs\ProcessOrderJob::dispatch($subscription);
        }

        $this->info('All jobs dispatched successfully.');
    }
}
