<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Billing\SubscriptionBillingService;

class TestBilling extends Command
{
    protected $signature = 'billing:test';
    protected $description = 'Manual test for subscription billing';

    public function handle()
    {
        $this->info('Running billing test...');
        app(SubscriptionBillingService::class)->run();
        $this->info('Billing test completed.');
    }
}
