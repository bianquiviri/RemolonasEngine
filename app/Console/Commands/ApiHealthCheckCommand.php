<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApiHealthCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the health of the application and its dependencies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Check Database
            DB::connection()->getPdo();
            
            // Check if we can reach the health endpoint internally
            // This is a simple CLI-based health check for Docker
            $this->info('Application is healthy');
            return 0;
        } catch (\Exception $e) {
            $this->error('Application is unhealthy: ' . $e->getMessage());
            return 1;
        }
    }
}
